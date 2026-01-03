<?php
/**
 * AutoCar - Main Router
 * Luxury Supercar Website
 */

session_start();

// Define base path
define('BASE_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/views');

// Include config
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/config/database.php';

// Get the request URI
$request = $_SERVER['REQUEST_URI'];
$basePath = '';

// Remove base path from request
$request = str_replace($basePath, '', $request);
$request = strtok($request, '?'); // Remove query string

// Simple Router
switch ($request) {
    case '':
    case '/':
    case '/home':
        require VIEW_PATH . '/user/home.php';
        break;
        
    case '/cars':
        require VIEW_PATH . '/user/car_list.php';
        break;
        
    case '/login':
        require_once APP_PATH . '/controllers/AuthController.php';
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->showLogin();
        }
        break;
        
    case '/register':
        require_once APP_PATH . '/controllers/AuthController.php';
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->showRegister();
        }
        break;
    
    case '/forgot-password':
        require VIEW_PATH . '/auth/forgot_password.php';
        break;
    
    case '/about':
        require VIEW_PATH . '/user/about.php';
        break;
        
    case '/contact':
        require VIEW_PATH . '/user/contact.php';
        break;
    
    case '/cart':
        require VIEW_PATH . '/user/cart.php';
        break;
    
    case '/cart/add':
        require_once APP_PATH . '/controllers/CartController.php';
        $controller = new CartController();
        $controller->add();
        break;
    
    case '/cart/remove':
        require_once APP_PATH . '/controllers/CartController.php';
        $controller = new CartController();
        $controller->remove();
        break;
    
    case '/cart/info':
        require_once APP_PATH . '/controllers/CartController.php';
        $controller = new CartController();
        $controller->getInfo();
        break;
    
    case '/compare':
        require VIEW_PATH . '/user/compare.php';
        break;
    
    case '/compare/add':
        require_once APP_PATH . '/controllers/CompareController.php';
        $controller = new CompareController();
        $controller->add();
        break;
    
    case '/compare/remove':
        require_once APP_PATH . '/controllers/CompareController.php';
        $controller = new CompareController();
        $controller->remove();
        break;
    
    case '/compare/info':
        require_once APP_PATH . '/controllers/CompareController.php';
        $controller = new CompareController();
        $controller->getInfo();
        break;
    
    case '/orders':
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        require VIEW_PATH . '/user/orders.php';
        break;
    
    case '/favorites':
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        require VIEW_PATH . '/user/favorites.php';
        break;
    
    case '/favorites/remove':
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }
        require_once APP_PATH . '/models/FavoriteModel.php';
        $favoriteModel = new FavoriteModel();
        $data = json_decode(file_get_contents('php://input'), true);
        $carId = $data['car_id'] ?? 0;
        $result = $favoriteModel->removeFavorite($_SESSION['user_id'], $carId);
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
        break;
    
    case '/favorites/add':
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            exit;
        }
        require_once APP_PATH . '/models/FavoriteModel.php';
        $favoriteModel = new FavoriteModel();
        $data = json_decode(file_get_contents('php://input'), true);
        $carId = $data['car_id'] ?? 0;
        $result = $favoriteModel->addFavorite($_SESSION['user_id'], $carId);
        header('Content-Type: application/json');
        echo json_encode(['success' => $result !== false, 'message' => $result ? 'Đã thêm vào yêu thích' : 'Xe đã có trong danh sách yêu thích']);
        break;
        
    case '/profile':
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        require VIEW_PATH . '/user/profile.php';
        break;
    
    case '/logout':
        require_once APP_PATH . '/controllers/AuthController.php';
        $authController = new AuthController();
        $authController->logout();
        break;
    
    // Admin Routes
    case '/admin':
    case '/admin/dashboard':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require VIEW_PATH . '/admin/dashboard.php';
        break;
    
    case '/admin/cars':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/CarController.php';
        $controller = new CarController();
        $controller->index();
        break;
    
    case '/admin/cars/add':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/CarController.php';
        $controller = new CarController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->add();
        } else {
            $controller->showAdd();
        }
        break;
    
    case '/admin/cars/edit':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/CarController.php';
        $controller = new CarController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->edit();
        } else {
            $id = intval($_GET['id'] ?? 0);
            $controller->showEdit($id);
        }
        break;
    
    case '/admin/cars/delete':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/CarController.php';
        $controller = new CarController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id'] ?? 0);
            $controller->delete($id);
        } else {
            $id = intval($_GET['id'] ?? 0);
            $controller->delete($id);
        }
        break;
    
    case '/admin/brands':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/BrandController.php';
        $controller = new BrandController();
        $controller->index();
        break;
    
    case '/admin/brands/add':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/BrandController.php';
        $controller = new BrandController();
        $controller->add();
        break;
    
    case '/admin/brands/edit':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/BrandController.php';
        $controller = new BrandController();
        $controller->edit();
        break;
    
    case '/admin/brands/delete':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/BrandController.php';
        $controller = new BrandController();
        $controller->delete();
        break;
    
    case '/admin/categories':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/CategoryController.php';
        $controller = new CategoryController();
        $controller->index();
        break;
    
    case '/admin/categories/add':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/CategoryController.php';
        $controller = new CategoryController();
        $controller->add();
        break;
    
    case '/admin/categories/edit':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/CategoryController.php';
        $controller = new CategoryController();
        $controller->edit();
        break;
    
    case '/admin/categories/delete':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require_once APP_PATH . '/controllers/CategoryController.php';
        $controller = new CategoryController();
        $controller->delete();
        break;
    
    case '/admin/orders':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require VIEW_PATH . '/admin/orders/list.php';
        break;
    
    case '/admin/users':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require VIEW_PATH . '/admin/users/list.php';
        break;
    
    case '/admin/reviews':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require VIEW_PATH . '/admin/reviews/list.php';
        break;
    
    case '/admin/contacts':
        // Kiểm tra đăng nhập và quyền admin
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
        require VIEW_PATH . '/admin/contacts/list.php';
        break;
        
    default:
        // Check if it's a car detail page
        if (preg_match('/^\/car\/(\d+)$/', $request, $matches)) {
            $_GET['id'] = $matches[1];
            require VIEW_PATH . '/user/car_detail.php';
        } elseif (preg_match('/^\/cars\/(\d+)$/', $request, $matches)) {
            $_GET['id'] = $matches[1];
            require VIEW_PATH . '/user/car_detail.php';
        } else {
            // 404 Page
            http_response_code(404);
            echo '<!DOCTYPE html>
            <html lang="vi">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>404 - Không Tìm Thấy | AutoCar</title>
                <link rel="stylesheet" href="' . $basePath . '/assets/css/style.css">
            </head>
            <body style="background: #0a0a0a; min-height: 100vh; display: flex; align-items: center; justify-content: center;">
                <div style="text-align: center; color: #fff;">
                    <h1 style="font-size: 120px; color: #D4AF37; margin-bottom: 20px;">404</h1>
                    <p style="font-size: 24px; margin-bottom: 30px;">Trang bạn tìm kiếm không tồn tại</p>
                    <a href="' . $basePath . '/" style="background: #D4AF37; color: #000; padding: 15px 40px; text-decoration: none; border-radius: 30px; font-weight: 600;">Về Trang Chủ</a>
                </div>
            </body>
            </html>';
        }
        break;
}
