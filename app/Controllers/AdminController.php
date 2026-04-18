<?php
namespace App\Controllers;
use App\Models\Admin;
use App\Models\Check;
use App\Models\Order;

class AdminController {
    protected $adminModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->adminModel = new Admin();
    }

    public function index() {
        $data = [
                'nUsers'         => $this->adminModel->countUsers(),
                'nProducts'      => $this->adminModel->countProducts(),
                'totalRevenue'   => $this->adminModel->getTotalRevenue(),
                'pendingOrders'  => $this->adminModel->countPendingOrders(),
                'completedOrders'=> $this->adminModel->countCompletedOrders()
            ];
        $this->render('dashboard', $data);
    }

    public function getUsers() {
        $limit = 7;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        
        $offset = ($page - 1) * $limit;
        
        $totalUsersCount = $this->adminModel->countUsers();
        $totalPages = ceil($totalUsersCount / $limit);
        
        $users = $this->adminModel->getAllUsers($limit, $offset);
        
        $this->render('users', [
            'users'       => $users,
            'currentPage' => $page,
            'totalPages'  => $totalPages
        ]);
    }
    //delete user 
    public function deleteUser() {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $this->adminModel->deleteUser($id);
        }

        header("Location: index.php?url=admin/users");
        exit();
    }
    // add user
    public function addUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $room_num = $_POST['room_number'] ?? null;
            $role = $_POST['role'] ?? 'user';

            try {
                $targetDir = "assets/users/"; 
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                $imgName = $_FILES['profile_picture']['name'];
                $imagePath = $targetDir . time() . "_" . $imgName;

                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $imagePath)) {
                    $this->adminModel->create($name, $email, $password, $imagePath, $room_num, $role);
                    
                    header("Location: index.php?url=admin/users");
                    exit();
                } else {
                    $errorMsg = "Failed to upload image.";
                    }
                    } catch (\PDOException $e) {
                        $rooms = $this->adminModel->getAllRooms();
                        $errorMsg = ($e->getCode() == 23000) ? "Email '$email' is already registered!" : "Error: " . $e->getMessage();
                        
                        return $this->render('addUser', [
                            'rooms' => $rooms,
                            'error' => $errorMsg
                            ]);
                            }
                            }
                            
                            // GET Request: Show the form
                            $rooms = $this->adminModel->getAllRooms();
                            $this->render('addUser', ['rooms' => $rooms]);
                            }
                            
                            public function addProduct() {
                                $categories = $this->adminModel->getAllCategory();
        $this->render('addProduct', ['category' => $categories]); 
    }
    //edit user
    public function editUser() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?url=admin/users");
            exit();
        }

        // 1. Fetch existing user data to populate the form
        $user = $this->adminModel->getUserById($id);
        $rooms = $this->adminModel->getAllRooms();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $room_num = $_POST['room_number'];
            $role = $_POST['role'];
            
            // Logic for Password: Only hash and update if the user typed a new one
            if (!empty($_POST['password'])) {
                $password = $_POST['password'];
            } else {
                // If empty, we need to keep the old password (requires a specific Model method or logic)
                $password = $user['password'];
            }

            // Logic for Image: Keep old image if a new one isn't uploaded
            $imagePath = $user['profile_path'];
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === 0) {
                $targetDir = "assets/users/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                
                $newPath = $targetDir . time() . "_" . $_FILES['profile_picture']['name'];
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $newPath)) {
                    $imagePath = $newPath;
                }
            }

            try {
                $this->adminModel->updateUser($id, $name, $email, $password, $imagePath, $room_num, $role);
                header("Location: index.php?url=admin/users");
                exit();
            } catch (\PDOException $e) {
                $errorMsg = "Update failed: " . $e->getMessage();
            }
        }

        $this->render('editUser', [
            'user'  => $user,
            'rooms' => $rooms,
            'error' => $errorMsg ?? null
        ]);
    }
    public function getTrashedUsers() {
        $users = $this->adminModel->getTrashedUsers();
        $this->render('trashedUsers', ['users' => $users]);
    }

    public function restoreUser() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->adminModel->restoreUser($id);
        }
        header("Location: index.php?url=admin/trashedUsers");
        exit();
    }
    public function addCategory() {
    $this->render('addCategory');
    }
    
  public function storeCategory() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['cat_name']);

        if (empty($name)) {
            return $this->render('addCategory', ['error' => "Category name cannot be empty!"]);
        }

        if (strlen($name) < 3) {
            return $this->render('addCategory', ['error' => "Category name must be at least 3 characters."]);
        }

        try {
            $existingCategories = $this->adminModel->getAllCategory();
            foreach ($existingCategories as $cat) {
                if (strtolower($cat['cat_name']) === strtolower($name)) {
                    return $this->render('addCategory', ['error' => "The category '$name' already exists!"]);
                }
            }

            $this->adminModel->createCategory($name);
            header("Location: index.php?url=admin/categories"); 
            exit();
        } catch (\PDOException $e) {
            $errorMsg = "Error: " . $e->getMessage();
            return $this->render('addCategory', ['error' => $errorMsg]);
        }
    }
}

public function editCategory() {
    $id = $_GET['id'] ?? null;
    if (!$id) {
        header("Location: index.php?url=admin/categories");
        exit();
    }
    
    $category = $this->adminModel->getCategoryById($id);
    if (!$category) {
        header("Location: index.php?url=admin/categories");
        exit();
    }
    
    $this->render('editCategory', ['category' => $category]);
}

public function updateCategory() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'] ?? null;
        $name = trim($_POST['cat_name']);
        
        if (!$id) {
            header("Location: index.php?url=admin/add-product");
            exit();
        }
        
        if (empty($name)) {
            $category = $this->adminModel->getCategoryById($id);
            return $this->render('editCategory', [
                'category' => $category,
                'error' => "Category name cannot be empty!"
            ]);
        }
        
        if (strlen($name) < 3) {
            $category = $this->adminModel->getCategoryById($id);
            return $this->render('editCategory', [
                'category' => $category,
                'error' => "Category name must be at least 3 characters."
            ]);
        }
        
        try {
            $existingCategories = $this->adminModel->getAllCategory();
            foreach ($existingCategories as $cat) {
                // Check if name already exists (excluding current category)
                if ($cat['id'] != $id && strtolower($cat['cat_name']) === strtolower($name)) {
                    $category = $this->adminModel->getCategoryById($id);
                    return $this->render('editCategory', [
                        'category' => $category,
                        'error' => "The category '$name' already exists!"
                    ]);
                }
            }
            
            $this->adminModel->updateCategory($id, $name);
            header("Location: index.php?url=admin/categories");
            exit();
        } catch (\PDOException $e) {
            $category = $this->adminModel->getCategoryById($id);
            return $this->render('editCategory', [
                'category' => $category,
                'error' => "Error: " . $e->getMessage()
            ]);
        }
    }
}

public function deleteCategory() {
    $id = $_GET['id'] ?? null;
    if ($id) {
        $this->adminModel->deleteCategory($id);
    }
    header("Location: index.php?url=admin/categories");
    exit();
}

public function getCategories() {
    $categories = $this->adminModel->getAllCategory();
    $this->render('categories', ['categories' => $categories]);
}

    public function storeProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['product_name']);
            $price = (float)$_POST['price'];
            $cat_id = $_POST['category'] ?? null;

            if ($price <= 0) {
                $categories = $this->adminModel->getAllCategory();
                return $this->render('addProduct', [
                    'category' => $categories,
                    'error'    => "Price must be a positive number!"
                ]);
            }

            try {
                $imgName = $_FILES['product_img']['name'];
                $targetDir = "assets/products/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                
                $imagePath = $targetDir . time() . "_" . $imgName;

                if (move_uploaded_file($_FILES['product_img']['tmp_name'], $imagePath)) {
                    $this->adminModel->createProduct($name, $price, $imagePath, $cat_id);
                    header("Location: index.php?url=admin/products");
                    exit();
                }
            } catch (\PDOException $e) {
                $categories = $this->adminModel->getAllCategory();
                $errorMsg = ($e->getCode() == 23000) ? "Product name '$name' already exists!" : "Error: " . $e->getMessage();
                
                return $this->render('addProduct', [
                    'category' => $categories,
                    'error'    => $errorMsg
                ]);
            }
        }
    }

    public function getProducts() {
        $limit = 7;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;
        $totalProductsCount = $this->adminModel->countProducts();
        $totalPages = ceil($totalProductsCount / $limit);
        $products = $this->adminModel->getAllProduct($limit, $offset);
        $this->render('products', [
            'products'    => $products,
            'currentPage' => $page,
            'totalPages'  => $totalPages
        ]);
    }
   
    public function editProduct() {
        $id = $_GET['id'] ?? null;
        $product = $this->adminModel->getProductById($id);
        
        if (!$product) {
            header("Location: index.php?url=admin/products");
            exit();
        }
        
        $categories = $this->adminModel->getAllCategory();
        $this->render('editProduct', [
            'product' => $product,
            'category' => $categories
        ]);
    }
    public function updateProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = trim($_POST['product_name']);
            $price = (float)$_POST['price'];
            $cat_id = $_POST['category'];
            
            $imagePath = null;
            if (!empty($_FILES['product_img']['name'])) {
                $targetDir = "assets/products/";
                $imagePath = $targetDir . time() . "_" . $_FILES['product_img']['name'];
                move_uploaded_file($_FILES['product_img']['tmp_name'], $imagePath);
            }

            try {
                $this->adminModel->updateProduct($id, $name, $price, $cat_id, $imagePath);
                header("Location: index.php?url=admin/products");
                exit();
            } catch (\Exception $e) {
                die("Error: " . $e->getMessage());
            }
        }
    }

    public function deleteProduct() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->adminModel->deleteProduct($id);
        }
        header("Location: index.php?url=admin/products");
        exit();
    }

    public function getDeletedProducts() {
    $products = $this->adminModel->deletedProduct();
    $this->render('trash', ['products' => $products]);
    }

    public function getOrders() {
        $orderModel = new Order();
        $orders = $orderModel->getAllOrders();
        
        // Fetch items for each order
        foreach ($orders as &$order) {
            $order['items'] = $orderModel->getOrderItems($order['id']);
        }
        
        $this->render('orders', ['orders' => $orders]);
    }

    function getChecks($one_user = null, $start_date = null, $end_date = null) {
        if(isset($start_date) && isset($end_date) && $start_date > $end_date){
            return $this->render('checks', [
                'users' => [],
                'start_date' => $start_date,
                'end_date' => $end_date,
                'error' => "Start date cannot be greater than end date."
            ]);
        }
        $start_page = $_GET['page'] ?? 1;
        $items_per_page = 10;
        $checks = new Check();
        if($one_user === '') $one_user = null;
        $users = $this->getAllUsers($start_date, $end_date);
        $users_checks = [];
        if(isset($one_user)){
            $users_checks = $checks->getOneUserPayments($one_user, $start_date, $end_date);
        } else {
            $users_checks = $checks->getUsersPayments($start_date, $end_date, $items_per_page, ($start_page - 1) * $items_per_page);
        }
        $this->render('checks', [
            'users' => $users,
            'users_checks' => $users_checks,
            'start_date' => $start_date, 
            'end_date' => $end_date, 
            'one_user' => $one_user,
            'page' => $start_page,
            'items_per_page' => $items_per_page,
            'total_pages' => ceil($this->getChecksCount($start_date, $end_date) / $items_per_page)
        ]);
    }
    
    public function getAllUsers($start_date = null, $end_date = null) {
        $sql = "SELECT DISTINCT u.id, u.name FROM users u
                JOIN orders o ON u.id = o.user_id
                WHERE o.status = 'completed'";
        
        if ($start_date) {
            $sql .= " AND o.created_at >= ?";
        }

        if ($end_date) {
            $sql .= " AND o.created_at <= ?";
        }

        $params = [];

        if ($start_date) {
            $params[] = $start_date;
        }

        if ($end_date) {
            $params[] = $end_date;
        }

        return $this->adminModel->query($sql, $params)->fetchAll();
    }

    public function getChecksCount($start_date = null, $end_date = null) {
        $sql = "SELECT COUNT(DISTINCT u.id) AS count FROM users u
                JOIN orders o ON u.id = o.user_id
                WHERE o.status = 'completed'";
        $params = [];

        if ($start_date) {
            $sql .= " AND o.created_at >= ?";
            $params[] = $start_date;
        }

        if ($end_date) {
            $sql .= " AND o.created_at <= ?";
            $params[] = $end_date;
        }

        $result = $this->adminModel->query($sql, $params)->fetch();
        return $result['count'] ?? 0;
    }
    public function restore() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->adminModel->restoreProduct($id);
        }
        header("Location: index.php?url=admin/trash");
        exit();
    }
    private function render($viewName, $data = []) {
        extract($data);
        require_once ROOT_PATH . '/views/partials/navbar.php';
        echo '<div class="container mt-4">';
        require_once ROOT_PATH . "/views/admin/{$viewName}.php";
        echo '</div>';
    }

}