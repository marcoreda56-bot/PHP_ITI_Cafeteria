<?php
namespace App\Controllers;

use App\Models\Auth;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
                $_SESSION['profile_path'] = $user['profile_path'] ?? '';
                $_SESSION['room_id'] = $user['room_id'] ?? null;
                $_SESSION['email'] = $user['email'] ?? '';

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
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $userModel = new \App\Models\Auth();
            $user = $userModel->findByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32)); 
                $hash = hash("sha256", $token);
                $expiry = date("Y-m-d H:i:s", time() + 60 * 30);

                $userModel->updateResetToken($hash, $expiry, $email);

                if ($this->sendResetEmail($email, $token)) {
                    $success = "Check your email for the reset link!";
                } else {
                    $error = "Failed to send email. Try again later.";
                }
            } else {
                $error = "If this email exists, you will receive a link.";
            }
        }
        require_once ROOT_PATH . '/views/auth/forgetPass.php';
    }

    private function sendResetEmail($to, $token) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'sandbox.smtp.mailtrap.io'; 
            $mail->SMTPAuth   = true;
            $mail->Username   = '3753d17950f6da'; 
            $mail->Password   = '94d24dd079f819'; 
            $mail->Port       = 2525;

            $mail->setFrom('support@yourstore.com', 'Coffee Shop Support');
            $mail->addAddress($to);

            $resetLink = "http://localhost/PHP_ITI_Project/Public/index.php?url=reset-password&token=" . $token;
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Click the link below to reset your password:<br><br>
                            <a href='$resetLink'>$resetLink</a><br><br>
                            This link will expire in 30 minutes.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    public function resetPassword() {
        $token = $_GET['token'] ?? null;
        if (!$token) {
            die("Token is missing.");
        }

        $hash = hash("sha256", $token);
        
        $userModel = new \App\Models\Auth();
        $user = $userModel->findUserByToken($hash);

        if (!$user) {
            die("Token invalid or expired.");
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = $_POST['password'] ?? '';
            $conf_pass = $_POST['confirm_password'] ?? '';

            if ($password === $conf_pass && strlen($password) >= 6) {
                $userModel->forget($password, $user['email']);
                
                $userModel->updateResetToken(null, null, $user['email']);
                
                header("Location: index.php?url=login&reset=success");
                exit();
            } else {
                $error = "Passwords do not match or too short (min 6).";
            }
        }

        require_once ROOT_PATH . '/views/auth/reset_confirm.php';
    }
    public function logout() {
            if (session_status() === PHP_SESSION_NONE) session_start();
            session_destroy();
            header("Location: index.php?url=login");
            exit();
        }
    }