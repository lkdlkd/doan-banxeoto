<?php
// app/models/CartModel.php
// Model quản lý giỏ hàng sử dụng Session

class CartModel
{
    private $db;
    
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Kết nối DB để lấy thông tin xe
        require_once __DIR__ . '/../../config/database.php';
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Thêm xe vào giỏ hàng
    public function addToCart($carId)
    {
        // Kiểm tra xe đã có trong giỏ hàng chưa
        if (in_array($carId, $_SESSION['cart'])) {
            return ['success' => false, 'message' => 'Xe đã có trong giỏ hàng'];
        }
        
        // Kiểm tra xe có tồn tại và còn hàng không
        $sql = "SELECT id, status FROM cars WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$carId]);
        $car = $stmt->fetch();
        
        if (!$car) {
            return ['success' => false, 'message' => 'Xe không tồn tại'];
        }
        
        if ($car['status'] === 'sold') {
            return ['success' => false, 'message' => 'Xe đã được bán'];
        }
        
        // Thêm vào giỏ hàng
        $_SESSION['cart'][] = $carId;
        
        return ['success' => true, 'message' => 'Đã thêm xe vào giỏ hàng'];
    }
    
    // Xóa xe khỏi giỏ hàng
    public function removeFromCart($carId)
    {
        $key = array_search($carId, $_SESSION['cart']);
        if ($key !== false) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
            return ['success' => true, 'message' => 'Đã xóa xe khỏi giỏ hàng'];
        }
        return ['success' => false, 'message' => 'Xe không có trong giỏ hàng'];
    }
    
    // Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        $_SESSION['cart'] = [];
        return ['success' => true, 'message' => 'Đã xóa toàn bộ giỏ hàng'];
    }
    
    // Lấy danh sách xe trong giỏ hàng với thông tin chi tiết
    public function getCartItems()
    {
        if (empty($_SESSION['cart'])) {
            return [];
        }
        
        $placeholders = str_repeat('?,', count($_SESSION['cart']) - 1) . '?';
        
        $sql = "SELECT c.*, b.name as brand_name, b.logo as brand_logo,
                cat.name as category_name, ci.image_url
                FROM cars c
                LEFT JOIN brands b ON c.brand_id = b.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN car_images ci ON c.id = ci.car_id
                WHERE c.id IN ({$placeholders})
                GROUP BY c.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($_SESSION['cart']);
        
        return $stmt->fetchAll();
    }
    
    // Đếm số lượng xe trong giỏ hàng
    public function getCartCount()
    {
        return count($_SESSION['cart']);
    }
    
    // Tính tổng giá trị giỏ hàng
    public function getCartTotal()
    {
        if (empty($_SESSION['cart'])) {
            return 0;
        }
        
        $placeholders = str_repeat('?,', count($_SESSION['cart']) - 1) . '?';
        
        $sql = "SELECT SUM(price) as total FROM cars WHERE id IN ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($_SESSION['cart']);
        $result = $stmt->fetch();
        
        return $result['total'] ?? 0;
    }
    
    // Kiểm tra xe có trong giỏ hàng không
    public function isInCart($carId)
    {
        return in_array($carId, $_SESSION['cart']);
    }
    
    // Lấy danh sách ID xe trong giỏ hàng
    public function getCartIds()
    {
        return $_SESSION['cart'];
    }
}
