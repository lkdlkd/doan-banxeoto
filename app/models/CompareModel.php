<?php
// app/models/CompareModel.php
// Model quản lý so sánh xe sử dụng Session

class CompareModel
{
    private $db;
    private $maxCompare = 4; // Tối đa 4 xe so sánh
    
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['compare'])) {
            $_SESSION['compare'] = [];
        }
        
        // Kết nối DB để lấy thông tin xe
        require_once __DIR__ . '/../../config/database.php';
        $this->db = Database::getInstance()->getConnection();
    }
    
    // Thêm xe vào danh sách so sánh
    public function addToCompare($carId)
    {
        // Kiểm tra xe đã có trong danh sách so sánh chưa
        if (in_array($carId, $_SESSION['compare'])) {
            return ['success' => false, 'message' => 'Xe đã có trong danh sách so sánh'];
        }
        
        // Kiểm tra giới hạn số lượng
        if (count($_SESSION['compare']) >= $this->maxCompare) {
            return ['success' => false, 'message' => 'Chỉ có thể so sánh tối đa ' . $this->maxCompare . ' xe'];
        }
        
        // Kiểm tra xe có tồn tại không
        $sql = "SELECT id FROM cars WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$carId]);
        
        if (!$stmt->fetch()) {
            return ['success' => false, 'message' => 'Xe không tồn tại'];
        }
        
        // Thêm vào danh sách so sánh
        $_SESSION['compare'][] = $carId;
        
        return ['success' => true, 'message' => 'Đã thêm xe vào danh sách so sánh'];
    }
    
    // Xóa xe khỏi danh sách so sánh
    public function removeFromCompare($carId)
    {
        $key = array_search($carId, $_SESSION['compare']);
        if ($key !== false) {
            unset($_SESSION['compare'][$key]);
            $_SESSION['compare'] = array_values($_SESSION['compare']); // Reindex array
            return ['success' => true, 'message' => 'Đã xóa xe khỏi danh sách so sánh'];
        }
        return ['success' => false, 'message' => 'Xe không có trong danh sách so sánh'];
    }
    
    // Xóa toàn bộ danh sách so sánh
    public function clearCompare()
    {
        $_SESSION['compare'] = [];
        return ['success' => true, 'message' => 'Đã xóa toàn bộ danh sách so sánh'];
    }
    
    // Lấy danh sách xe trong danh sách so sánh với thông tin chi tiết
    public function getCompareItems()
    {
        if (empty($_SESSION['compare'])) {
            return [];
        }
        
        $placeholders = str_repeat('?,', count($_SESSION['compare']) - 1) . '?';
        
        $sql = "SELECT c.*, b.name as brand_name, b.logo as brand_logo,
                cat.name as category_name, ci.image_url
                FROM cars c
                LEFT JOIN brands b ON c.brand_id = b.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                LEFT JOIN car_images ci ON c.id = ci.car_id
                WHERE c.id IN ({$placeholders})
                GROUP BY c.id
                ORDER BY FIELD(c.id, {$placeholders})";
        
        $stmt = $this->db->prepare($sql);
        $params = array_merge($_SESSION['compare'], $_SESSION['compare']);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    // Đếm số lượng xe trong danh sách so sánh
    public function getCompareCount()
    {
        return count($_SESSION['compare']);
    }
    
    // Kiểm tra xe có trong danh sách so sánh không
    public function isInCompare($carId)
    {
        return in_array($carId, $_SESSION['compare']);
    }
    
    // Lấy danh sách ID xe trong danh sách so sánh
    public function getCompareIds()
    {
        return $_SESSION['compare'];
    }
    
    // Lấy giới hạn số lượng
    public function getMaxCompare()
    {
        return $this->maxCompare;
    }
}
