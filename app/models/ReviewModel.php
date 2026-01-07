<?php
// app/models/ReviewModel.php

require_once __DIR__ . '/BaseModel.php';

class ReviewModel extends BaseModel
{
    protected $table = 'reviews';

    // Lấy reviews của một xe
    public function getReviewsByCar($carId)
    {
        $sql = "SELECT r.*, u.full_name as user_name, u.email as user_email 
                FROM {$this->table} r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.car_id = ? 
                ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$carId]);
        return $stmt->fetchAll();
    }

    // Lấy reviews của một user
    public function getReviewsByUser($userId)
    {
        $sql = "SELECT r.*, c.name as car_name, ci.image_url as car_image, 
                b.name as brand_name 
                FROM {$this->table} r 
                LEFT JOIN cars c ON r.car_id = c.id 
                LEFT JOIN car_images ci ON c.id = ci.car_id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                WHERE r.user_id = ? 
                GROUP BY r.id 
                ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Lấy tất cả reviews với thông tin chi tiết
    public function getAllWithDetails()
    {
        $sql = "SELECT r.*, c.name as car_name, ci.image_url as car_image,
                b.name as brand_name, 
                u.full_name as user_name, u.email as user_email 
                FROM {$this->table} r 
                LEFT JOIN cars c ON r.car_id = c.id 
                LEFT JOIN car_images ci ON c.id = ci.car_id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN users u ON r.user_id = u.id 
                GROUP BY r.id 
                ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tính điểm trung bình của xe
    public function getAverageRating($carId)
    {
        $stmt = $this->db->prepare("SELECT AVG(rating) as avg_rating, COUNT(*) as review_count FROM {$this->table} WHERE car_id = ?");
        $stmt->execute([$carId]);
        return $stmt->fetch();
    }

    // Kiểm tra user đã review xe chưa
    public function hasUserReviewed($userId, $carId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = ? AND car_id = ?");
        $stmt->execute([$userId, $carId]);
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    // Tạo review mới
    public function createReview($data)
    {
        // Kiểm tra user đã review chưa
        if ($this->hasUserReviewed($data['user_id'], $data['car_id'])) {
            return false;
        }
        return $this->create($data);
    }

    // Lấy reviews theo rating
    public function getReviewsByRating($carId, $rating)
    {
        $sql = "SELECT r.*, u.full_name as user_name, u.avatar as user_avatar 
                FROM {$this->table} r 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.car_id = ? AND r.rating = ? 
                ORDER BY r.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$carId, $rating]);
        return $stmt->fetchAll();
    }

    // Alias method cho getReviewsByCar
    public function getByCarId($carId)
    {
        return $this->getReviewsByCar($carId);
    }

    // Thêm phản hồi của admin
    public function addReply($reviewId, $replyContent)
    {
        $sql = "UPDATE {$this->table} SET admin_reply = ?, replied_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$replyContent, $reviewId]);
    }

    // Lấy review theo ID
    public function getById($id)
    {
        $sql = "SELECT r.*, c.name as car_name, u.full_name as user_name, u.email as user_email 
                FROM {$this->table} r 
                LEFT JOIN cars c ON r.car_id = c.id 
                LEFT JOIN users u ON r.user_id = u.id 
                WHERE r.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
