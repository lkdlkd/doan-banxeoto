<?php
// app/models/OrderModel.php

require_once __DIR__ . '/BaseModel.php';

class OrderModel extends BaseModel
{
    protected $table = 'orders';

    // Lấy tất cả đơn hàng với thông tin chi tiết
    public function getAllWithDetails()
    {
        $sql = "SELECT o.*, c.name as car_name, c.price as car_price, 
                ci.image_url as car_image, b.name as brand_name, 
                u.full_name as user_name, u.email as user_email, u.phone as user_phone 
                FROM {$this->table} o 
                LEFT JOIN cars c ON o.car_id = c.id 
                LEFT JOIN car_images ci ON c.id = ci.car_id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN users u ON o.user_id = u.id 
                GROUP BY o.id 
                ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy đơn hàng theo ID với thông tin chi tiết
    public function getOrderWithDetails($id)
    {
        $sql = "SELECT o.*, c.name as car_name, c.price as car_price, 
                ci.image_url as car_image, b.name as brand_name, 
                u.full_name as user_name, u.email as user_email 
                FROM {$this->table} o 
                LEFT JOIN cars c ON o.car_id = c.id 
                LEFT JOIN car_images ci ON c.id = ci.car_id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy đơn hàng theo ID (đơn giản)
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy đơn hàng theo user
    public function getOrdersByUser($userId)
    {
        $sql = "SELECT o.*, c.name as car_name, c.price as car_price, 
                ci.image_url as car_image, b.name as brand_name 
                FROM {$this->table} o 
                LEFT JOIN cars c ON o.car_id = c.id 
                LEFT JOIN car_images ci ON c.id = ci.car_id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                WHERE o.user_id = ? 
                GROUP BY o.id 
                ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Alias cho getOrdersByUser
    public function getByUserId($userId, $limit = null)
    {
        $sql = "SELECT o.*, c.name as car_name, c.price as car_price, 
                ci.image_url as car_image, b.name as brand_name 
                FROM {$this->table} o 
                LEFT JOIN cars c ON o.car_id = c.id 
                LEFT JOIN car_images ci ON c.id = ci.car_id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                WHERE o.user_id = ? 
                GROUP BY o.id 
                ORDER BY o.created_at DESC";

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Lấy đơn hàng theo trạng thái
    public function getOrdersByStatus($status)
    {
        $sql = "SELECT o.*, c.name as car_name, c.price as car_price, 
                b.name as brand_name, u.full_name as user_name 
                FROM {$this->table} o 
                LEFT JOIN cars c ON o.car_id = c.id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN users u ON o.user_id = u.id 
                WHERE o.status = ? 
                ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    // Tạo đơn hàng mới
    public function createOrder($data)
    {
        return $this->create($data);
    }

    // Đếm đơn hàng theo trạng thái
    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE status = ?");
        $stmt->execute([$status]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    // Lấy doanh thu theo tháng
    public function getRevenueByMonth($year)
    {
        $sql = "SELECT MONTH(o.created_at) as month, SUM(c.price) as revenue 
                FROM {$this->table} o 
                INNER JOIN cars c ON o.car_id = c.id 
                WHERE YEAR(o.created_at) = ? AND o.status = 'completed' 
                GROUP BY MONTH(o.created_at) 
                ORDER BY month ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$year]);
        return $stmt->fetchAll();
    }

    // Lấy tổng doanh thu
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(c.price) as total_revenue 
                FROM {$this->table} o 
                INNER JOIN cars c ON o.car_id = c.id 
                WHERE o.status = 'completed'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total_revenue'] ?? 0;
    }

    // Kiểm tra user đã mua xe chưa (đã confirmed hoặc completed)
    public function checkUserPurchased($userId, $carId)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE user_id = ? AND car_id = ? 
                AND status IN ('confirmed', 'completed')";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId, $carId]);
        $result = $stmt->fetch();

        return $result['total'] > 0;
    }
}
