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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            
            // Validate
            if (empty($username)) {
                $errors[] = 'Vui lòng nhập tên đăng nhập';
            }
            
            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            }
            
            // Nếu không có lỗi, thực hiện xác thực
            if (empty($errors)) {
                $user = $this->userModel->authenticate($username, $password);
                
                if ($user) {
                    // Lưu thông tin vào session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'] ?? $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['email'] = $user['email'];
                    
                    // Chuyển hướng theo role
                    if ($user['role'] === 'admin') {
                        $this->redirect('/admin/dashboard');
                    } else {
                        $this->redirect('/');
                    }
                } else {
                    $errors[] = 'Tên đăng nhập hoặc mật khẩu không đúng';
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
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $full_name = trim($_POST['full_name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            
            // Validate
            if (empty($username)) {
                $errors[] = 'Vui lòng nhập tên đăng nhập';
            } elseif (strlen($username) < 3) {
                $errors[] = 'Tên đăng nhập phải có ít nhất 3 ký tự';
            } elseif ($this->userModel->usernameExists($username)) {
                $errors[] = 'Tên đăng nhập đã tồn tại';
            }
            
            if (empty($email)) {
                $errors[] = 'Vui lòng nhập email';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            } elseif ($this->userModel->emailExists($email)) {
                $errors[] = 'Email đã được sử dụng';
            }
            
            if (empty($password)) {
                $errors[] = 'Vui lòng nhập mật khẩu';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự';
            }
            
            if (empty($confirm_password)) {
                $errors[] = 'Vui lòng xác nhận mật khẩu';
            } elseif ($password !== $confirm_password) {
                $errors[] = 'Mật khẩu xác nhận không khớp';
            }
            
            // full_name là optional nếu chưa có cột trong database
            
            // Nếu không có lỗi, tạo tài khoản
            if (empty($errors)) {
                $data = [
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'phone' => $phone,
                    'role' => 'user' // Mặc định là user
                ];
                
                // Chỉ thêm full_name nếu có giá trị
                if (!empty($full_name)) {
                    $data['full_name'] = $full_name;
                }
                
                $userId = $this->userModel->createUser($data);
                
                if ($userId) {
                    // Tự động đăng nhập sau khi đăng ký
                    $user = $this->userModel->find($userId);
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'] ?? $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['email'] = $user['email'];
                    
                    $_SESSION['success'] = 'Đăng ký thành công!';
                    $this->redirect('/');
                } else {
                    $errors[] = 'Có lỗi xảy ra khi tạo tài khoản';
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
