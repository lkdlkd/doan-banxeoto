<?php
// app/models/FavoriteModel.php

require_once __DIR__ . '/BaseModel.php';

class FavoriteModel extends BaseModel
{
    protected $table = 'favorites';

    // Lấy danh sách xe yêu thích của user
    public function getFavoritesByUser($userId)
    {
        $sql = "SELECT f.*, c.name as car_name, c.price, c.main_image, 
                b.name as brand_name, cat.name as category_name 
                FROM {$this->table} f 
                INNER JOIN cars c ON f.car_id = c.id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                WHERE f.user_id = ? 
                ORDER BY f.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Kiểm tra xe đã được yêu thích chưa
    public function isFavorite($userId, $carId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = ? AND car_id = ?");
        $stmt->execute([$userId, $carId]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    // Thêm vào yêu thích
    public function addFavorite($userId, $carId)
    {
        // Kiểm tra đã tồn tại chưa
        if ($this->isFavorite($userId, $carId)) {
            return false;
        }
        
        return $this->create([
            'user_id' => $userId,
            'car_id' => $carId
        ]);
    }

    // Xóa khỏi yêu thích
    public function removeFavorite($userId, $carId)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE user_id = ? AND car_id = ?");
        return $stmt->execute([$userId, $carId]);
    }

    // Đếm số lượng yêu thích của một xe
    public function countFavoritesByCar($carId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE car_id = ?");
        $stmt->execute([$carId]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    // Lấy danh sách user đã yêu thích xe
    public function getUsersByFavoriteCar($carId)
    {
        $sql = "SELECT u.* 
                FROM users u 
                INNER JOIN {$this->table} f ON u.id = f.user_id 
                WHERE f.car_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$carId]);
        return $stmt->fetchAll();
    }

    // Toggle favorite (thêm nếu chưa có, xóa nếu đã có)
    public function toggleFavorite($userId, $carId)
    {
        if ($this->isFavorite($userId, $carId)) {
            return $this->removeFavorite($userId, $carId);
        } else {
            return $this->addFavorite($userId, $carId);
        }
    }
}
