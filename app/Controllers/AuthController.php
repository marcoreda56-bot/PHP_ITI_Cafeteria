<?php
namespace App\Controllers;

use App\Models\User;
class AuthController {
    
    public function login() {
        if (session_status() === PHP_SESSION_NONE) 
            {
                session_start();
          }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && $password === $user['password']) {
                
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role']      = $user['role']; 

                if (strtolower($user['role']) === 'admin') {
                    header("Location: index.php?url=admin/home");
                } else {
                    header("Location: index.php?url=user/home");
                }
            } else {
                $error = "invalid inputs";
            }
        }
        require_once ROOT_PATH . '/views/auth/login.php';
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: index.php?url=login");
        exit();
    }
}