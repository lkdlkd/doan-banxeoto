<?php
// public/index.php

// Khởi động session
session_start();

// Load controllers
require_once __DIR__ . '/app/controllers/AuthController.php';
require_once __DIR__ . '/app/controllers/UserController.php';
require_once __DIR__ . '/app/controllers/CarController.php';
require_once __DIR__ . '/app/controllers/OrderController.php';

// Lấy URI và loại bỏ query string
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Nếu URI rỗng, set mặc định là trang chủ
if (empty($uri)) {
    $uri = '/';
}

// Routing
switch ($uri) {
    // Auth routes
    case '/login':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->showLogin();
        } else {
            $controller->login();
        }
        break;
        
    case '/register':
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $controller->showRegister();
        } else {
            $controller->register();
        }
        break;
        
    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    
    // Home route
    case '/':
        require_once __DIR__ . '/app/views/user/home.php';
        break;
    
    // Car routes
    // case '/cars':
    //     $controller = new CarController();
    //     $controller->index();
    //     break;
        
    // case (preg_match('/^\/cars\/(\d+)$/', $uri, $matches) ? true : false):
    //     $controller = new CarController();
    //     $controller->detail($matches[1]);
    //     break;
    
    // Admin routes
    // case '/admin':
    // case '/admin/dashboard':
    //     $controller = new AuthController();
    //     $controller->checkAdmin();
    //     require_once __DIR__ . '/../app/views/admin/dashboard.php';
    //     break;
        
    // case '/admin/cars':
    //     $controller = new AuthController();
    //     $controller->checkAdmin();
    //     $carController = new CarController();
    //     $carController->adminList();
    //     break;
        
    // case '/admin/cars/add':
    //     $controller = new AuthController();
    //     $controller->checkAdmin();
    //     $carController = new CarController();
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         require_once __DIR__ . '/../app/views/admin/cars/add.php';
    //     } else {
    //         $carController->create();
    //     }
    //     break;
    
    // User profile
    case '/profile':
        $controller = new AuthController();
        $controller->checkAuth();
        require_once __DIR__ . '/app/views/user/profile.php';
        break;
    
    // 404 Not Found
    default:
        http_response_code(404);
        echo '<h1>404 - Không tìm thấy trang</h1>';
        echo '<p>Trang bạn đang tìm kiếm không tồn tại.</p>';
        echo '<a href="/">Về trang chủ</a>';
        break;
}
