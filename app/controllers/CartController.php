<?php
// app/controllers/CartController.php
// Controller xử lý các action liên quan đến giỏ hàng

require_once __DIR__ . '/../models/CartModel.php';

class CartController
{
    private $cartModel;
    
    public function __construct()
    {
        $this->cartModel = new CartModel();
    }
    
    // Xử lý thêm xe vào giỏ
    public function add()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $carId = $_POST['car_id'] ?? null;
        
        if (!$carId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin xe']);
            return;
        }
        
        $result = $this->cartModel->addToCart($carId);
        $result['count'] = $this->cartModel->getCartCount();
        
        echo json_encode($result);
    }
    
    // Xử lý xóa xe khỏi giỏ
    public function remove()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        $carId = $_POST['car_id'] ?? null;
        
        if (!$carId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin xe']);
            return;
        }
        
        $result = $this->cartModel->removeFromCart($carId);
        $result['count'] = $this->cartModel->getCartCount();
        $result['total'] = $this->cartModel->getCartTotal();
        
        echo json_encode($result);
    }
    
    // Xử lý xóa toàn bộ giỏ hàng
    public function clear()
    {
        header('Content-Type: application/json');
        
        $result = $this->cartModel->clearCart();
        $result['count'] = 0;
        $result['total'] = 0;
        
        echo json_encode($result);
    }
    
    // Lấy thông tin giỏ hàng (JSON)
    public function getInfo()
    {
        header('Content-Type: application/json');
        
        echo json_encode([
            'success' => true,
            'count' => $this->cartModel->getCartCount(),
            'total' => $this->cartModel->getCartTotal(),
            'items' => $this->cartModel->getCartIds()
        ]);
    }
}
