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
    public function deleteProduct() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->adminModel->deleteProduct($id);
        }
        header("Location: index.php?url=admin/products");
        exit();
    }

    public function editProduct() {
        $id = $_GET['id'] ?? null;
        $product = $this->adminModel->getProductById($id);
        $categories = $this->adminModel->getAllCategory();
        
        $this->render('editProduct', [
            'product' => $product,
            'category' => $categories
        ]);
}
    private function render($viewName, $data = []) {
        extract($data);
        require_once ROOT_PATH . '/views/partials/navbar.php';
        require_once ROOT_PATH . "/views/admin/{$viewName}.php";
    }
}