<?php
namespace App\Controllers;
use App\Models\Admin;

class AdminController{
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

    private function render($viewName, $data = []) {
        extract($data);
        
        require_once ROOT_PATH . '/views/partials/navbar.php';
        
        require_once ROOT_PATH . "/views/admin/{$viewName}.php";
    }
}
?>