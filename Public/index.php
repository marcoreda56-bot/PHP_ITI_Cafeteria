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
    case 'admin/home':
        if ($_SESSION['role'] !== 'admin') { header("Location: index.php?url=login"); exit(); }
        require_once ROOT_PATH . '/views/admin/dashboard.php';
        break;
    case 'admin/orders':
        if ($_SESSION['role'] !== 'admin') { header("Location: index.php?url=login"); exit(); }
        require_once ROOT_PATH . '../views/admin/dashboard.php';
        break;

    case 'user/home':
        if (!isset($_SESSION['user_id'])) { header("Location: index.php?url=login"); exit(); }
        require_once ROOT_PATH . '/views/user/home.php';
        break;

    case 'logout':
        $controller = new \App\Controllers\AuthController();
        $controller->logout();
        break;
    
}