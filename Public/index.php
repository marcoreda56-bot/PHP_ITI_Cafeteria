<?php

session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ROOT_PATH', dirname(__DIR__));

require_once ROOT_PATH . '/vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->load();

$url = $_GET['url'] ?? 'login'; 

switch ($url) {
    case 'login':
        $controller = new \App\Controllers\AuthController();
        $controller->login();
        break;
    case 'register':
        $controller = new \App\Controllers\AuthController();
        $controller->register();
        break;
    case 'forget':
        $controller = new \App\Controllers\AuthController();
        $controller->forgetPass();
        break;
    
    case 'admin/home':
    case 'admin/users':
    case 'admin/products':
    case 'admin/add-product':
    case 'admin/store-product':
    case 'admin/delete-product':
    case 'admin/edit-product':
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') { 
        header("Location: index.php?url=login"); 
        exit(); 
    }
    $controller = new \App\Controllers\AdminController();
    
    if ($url === 'admin/users') {
        $controller->getUsers();
    } 
    elseif($url === 'admin/products'){
        $controller->getProducts();
    }
    elseif($url === 'admin/store-product'){
        $controller->storeProduct();
    }
    elseif($url === 'admin/add-product'){
        $controller->addProduct(); 
    }
    elseif($url === 'admin/edit-product'){
        $controller->editProduct();
    }
    elseif($url === 'admin/delete-product'){
        $controller->deleteProduct();
    }
    else {
        $controller->index(); 
    }
    break;
    case 'admin/orders':
        if ($_SESSION['role'] !== 'admin') { header("Location: index.php?url=login"); exit(); }
        require_once ROOT_PATH . '../views/admin/dashboard.php';
        break;

    case 'logout':
        $controller = new \App\Controllers\AuthController();
        $controller->logout();
        break;
    case 'reset-password':
        $controller = new \App\Controllers\AuthController();
        $controller->resetPassword();
        break;

    case 'user/home':
    case 'user/menu':
    case 'user/cart':
    case 'user/cart/add':
    case 'user/cart/update':
    case 'user/cart/remove':
    case 'user/checkout':
    case 'user/orders':
    case 'user/order':
    case 'user/order/cancel':
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?url=login");
            exit();
        }

        $controller = new \App\Controllers\UserController();

        if ($url === 'user/menu') {
            $controller->menu();
        } elseif ($url === 'user/cart') {
            $controller->cart();
        } elseif ($url === 'user/cart/add') {
            $controller->addToCart();
        } elseif ($url === 'user/cart/update') {
            $controller->updateCart();
        } elseif ($url === 'user/cart/remove') {
            $controller->removeFromCart();
        } elseif ($url === 'user/checkout') {
            $controller->checkout();
        } elseif ($url === 'user/orders') {
            $controller->myOrders();
        } elseif ($url === 'user/order') {
            $controller->orderDetails();
        } elseif ($url === 'user/order/cancel') {
            $controller->cancelOrder();
        } else {
            $controller->home();
        }
        break;

    default:
        header("Location: index.php?url=login");
        exit();
}