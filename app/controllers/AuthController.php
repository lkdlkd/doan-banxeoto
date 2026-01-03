<?php
// app/controllers/AuthController.php

require_once __DIR__ . '/../models/UserModel.php';

class AuthController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        
        // Khởi động session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Hiển thị trang đăng nhập
    public function showLogin()
    {
        // Nếu đã đăng nhập, chuyển về trang chủ
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Xử lý đăng nhập
    public function login()
    {
        $errors = [];
        $email = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $remember = isset($_POST['remember']);
            
            // Validate
            if (empty($email)) {
                $errors[] = 'Vui lòng nhập email';
            }
            
            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            }
            
            // Nếu không có lỗi, thực hiện xác thực
            if (empty($errors)) {
                // Cho phép đăng nhập bằng email hoặc username
                $user = $this->userModel->authenticateByEmail($email, $password);
                
                if ($user) {
                    // Lưu thông tin vào session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'] ?? $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['avatar'] = $user['avatar'] ?? '';
                    
                    // Xử lý Remember Me
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/');
                        $this->userModel->updateUser($user['id'], ['remember_token' => $token]);
                    }
                    
                    $_SESSION['success'] = 'Chào mừng ' . ($user['full_name'] ?? $user['username']) . '!';
                    
                    // Chuyển hướng theo role
                    if ($user['role'] === 'admin') {
                        $this->redirect('/admin/dashboard');
                    } else {
                        $this->redirect('/');
                    }
                } else {
                    $errors[] = 'Email hoặc mật khẩu không đúng';
                }
            }
        }
        
        // Hiển thị lại form với lỗi
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Hiển thị trang đăng ký
    public function showRegister()
    {
        // Nếu đã đăng nhập, chuyển về trang chủ
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/');
        }
        
        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Xử lý đăng ký
    public function register()
    {
        $errors = [];
        $formData = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $formData['fullname'] = trim($_POST['fullname'] ?? '');
            $formData['email'] = trim($_POST['email'] ?? '');
            $formData['phone'] = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $terms = isset($_POST['terms']);
            
            // Validate họ tên
            if (empty($formData['fullname'])) {
                $errors[] = 'Vui lòng nhập họ và tên';
            } elseif (strlen($formData['fullname']) < 2) {
                $errors[] = 'Họ và tên phải có ít nhất 2 ký tự';
            }
            
            // Validate email
            if (empty($formData['email'])) {
                $errors[] = 'Vui lòng nhập email';
            } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            } elseif ($this->userModel->emailExists($formData['email'])) {
                $errors[] = 'Email này đã được sử dụng';
            }
            
            // Validate số điện thoại
            if (empty($formData['phone'])) {
                $errors[] = 'Vui lòng nhập số điện thoại';
            } elseif (!preg_match('/^[0-9]{10,11}$/', preg_replace('/\s+/', '', $formData['phone']))) {
                $errors[] = 'Số điện thoại không hợp lệ';
            }
            
            // Validate mật khẩu
            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            } elseif (strlen($password) < 8) {
                $errors[] = 'Mật khẩu phải có ít nhất 8 ký tự';
            }
            
            // Validate xác nhận mật khẩu
            if (empty($confirm_password)) {
                $errors[] = 'Vui lòng xác nhận mật khẩu';
            } elseif ($password !== $confirm_password) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            }
            
            // Validate điều khoản
            if (!$terms) {
                $errors[] = 'Vui lòng đồng ý với điều khoản dịch vụ';
            }
            
            // Nếu không có lỗi, tạo tài khoản
            if (empty($errors)) {
                // Tạo username từ email
                $username = explode('@', $formData['email'])[0];
                // Đảm bảo username duy nhất
                $baseUsername = $username;
                $counter = 1;
                while ($this->userModel->usernameExists($username)) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }
                
                $data = [
                    'username' => $username,
                    'email' => $formData['email'],
                    'password' => $password,
                    'full_name' => $formData['fullname'],
                    'phone' => preg_replace('/\s+/', '', $formData['phone']),
                    'role' => 'user',
                    'created_at' => date('Y-m-d H:i:s')
                ];
                
                $userId = $this->userModel->createUser($data);
                
                if ($userId) {
                    // Tự động đăng nhập sau khi đăng ký
                    $user = $this->userModel->find($userId);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'] ?? $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['avatar'] = $user['avatar'] ?? '';
                    
                    $_SESSION['success'] = 'Đăng ký thành công! Chào mừng bạn đến với AutoCar!';
                    $this->redirect('/');
                } else {
                    $errors[] = 'Có lỗi xảy ra khi tạo tài khoản. Vui lòng thử lại.';
                }
            }
        }
        
        // Hiển thị lại form với lỗi
        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Đăng xuất
    public function logout()
    {
        // Xóa tất cả session
        session_unset();
        session_destroy();
        
        $this->redirect('/login');
    }

    // Kiểm tra đăng nhập
    public function checkAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
    }

    // Kiểm tra quyền admin
    public function checkAdmin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            $this->redirect('/');
        }
    }

    // Helper redirect
    private function redirect($path)
    {
        header("Location: $path");
        exit();
    }
}
