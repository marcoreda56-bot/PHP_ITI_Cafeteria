<?php
namespace App\Controllers;

use App\Models\Auth;
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

            $userModel = new Auth();
            $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {  
                session_regenerate_id(true);              
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role']      = $user['role']; 

                if (strtolower($user['role']) === 'admin') {
                    header("Location: index.php?url=admin/home");
                } else {
                    header("Location: index.php?url=user/home");
                }
            } else {
                $error = "invalid email or password";
            }
        }
        require_once ROOT_PATH . '/views/auth/login.php';
    }

 public function register() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $userModel = new Auth();
        $error = null;
        $rooms = $userModel->getAllRooms();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $conf_pass = $_POST['confirm_password'] ?? '';
        $room_num = $_POST['room_number'] ?? '';
        $email_pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';
        
        if (!preg_match($email_pattern, $email)) {
            $error = "Invalid email format";
        } elseif ($password !== $conf_pass) {
            $error = "Passwords do not match";
        } elseif (strlen($name) < 3) {
            $error = "Name must be at least 3 characters";
        }

        if (!$error) {
            $userModel = new Auth();
            if ($userModel->findByEmail($email)) {
                $error = "Email already exists";
            }
        }

        $profile_path = 'Public/assets/default.png';
        if (!$error && isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $upload_dir = ROOT_PATH . '/Public/assets/';
            if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

            $image    = $_FILES['profile_picture'];
            $ext      = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
            $allowed  = ['jpg', 'jpeg', 'png'];

            if (!in_array($ext, $allowed)) {
                $error = "Invalid file type";
            } elseif ($image['size'] > 2 * 1024 * 1024) {
                $error = "File too large";
            } else {
                $new_name = bin2hex(random_bytes(8)) . "." . $ext; 
                if (move_uploaded_file($image['tmp_name'], $upload_dir . $new_name)) {
                    $profile_path = 'Public/assets/' . $new_name;
                } else {
                    $error = "Upload failed";
                }
            }
        }
        
        if (!$error) {
            $userModel = new Auth();
            $userModel->create($name, $email, $password, $profile_path,$room_num,'user');
            header("Location: index.php?url=login");
            exit();
        }
    }

    require ROOT_PATH . '/views/auth/register.php';
}
public function forgetPass() {
    $error = null;
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $conf_pass = $_POST['confirm_password'] ?? '';

        if (empty($password) || $password !== $conf_pass) {
            $error = "Passwords do not match or are empty";
        } else {
            $userModel = new Auth();
            $user = $userModel->findByEmail($email);

            if ($user) {
                $userModel->forget($password, $email);
                header("Location: index.php?url=login");
                exit();
            } else {
                $error = "Email not found";
            }
        }
    }
    require_once ROOT_PATH . '/views/auth/forgetPass.php';
}
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: index.php?url=login");
        exit();
    }
}