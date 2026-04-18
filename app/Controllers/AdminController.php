<?php
namespace App\Controllers;
use App\Models\Admin;
use App\Models\Check;

class AdminController {
    protected $adminModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->adminModel = new Admin();
    }

    public function index() {
        $nUsers = $this->adminModel->countUsers();
        $this->render('dashboard',['nUsers' => $nUsers]);
    }

    public function getUsers() {
        $users = $this->adminModel->getAllUsers();
        $this->render('users', ['users' => $users]);
    }

    public function addProduct() {
        $categories = $this->adminModel->getAllCategory();
        $this->render('addProduct', ['category' => $categories]); 
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