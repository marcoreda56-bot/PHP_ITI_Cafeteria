<?php
namespace App\Controllers;
use App\Models\Admin;

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