<?php
// app/models/CarModel.php

require_once __DIR__ . '/BaseModel.php';

class CarModel extends BaseModel
{
    protected $table = 'cars';

    // Lấy tất cả xe với thông tin brand và category
    public function getAllWithDetails()
    {
        $sql = "SELECT c.*, b.name as brand_name, cat.name as category_name 
                FROM {$this->table} c 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy xe theo ID với thông tin chi tiết
    public function getCarWithDetails($id)
    {
        $sql = "SELECT c.*, b.name as brand_name, b.logo_url as brand_logo, 
                cat.name as category_name 
                FROM {$this->table} c 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                WHERE c.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tìm kiếm xe
    public function search($keyword, $brandId = null, $categoryId = null, $minPrice = null, $maxPrice = null)
    {
        $sql = "SELECT c.*, b.name as brand_name, cat.name as category_name 
                FROM {$this->table} c 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                WHERE 1=1";
        
        $params = [];
        
        if ($keyword) {
            $sql .= " AND (c.name LIKE ? OR c.description LIKE ?)";
            $searchTerm = "%{$keyword}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($brandId) {
            $sql .= " AND c.brand_id = ?";
            $params[] = $brandId;
        }
        
        if ($categoryId) {
            $sql .= " AND c.category_id = ?";
            $params[] = $categoryId;
        }
        
        if ($minPrice) {
            $sql .= " AND c.price >= ?";
            $params[] = $minPrice;
        }
        
        if ($maxPrice) {
            $sql .= " AND c.price <= ?";
            $params[] = $maxPrice;
        }
        
        $sql .= " ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Lấy xe theo brand
    public function getCarsByBrand($brandId)
    {
        $sql = "SELECT c.*, cat.name as category_name 
                FROM {$this->table} c 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                WHERE c.brand_id = ? 
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$brandId]);
        return $stmt->fetchAll();
    }

    // Lấy xe theo category
    public function getCarsByCategory($categoryId)
    {
        $sql = "SELECT c.*, b.name as brand_name 
                FROM {$this->table} c 
                LEFT JOIN brands b ON c.brand_id = b.id 
                WHERE c.category_id = ? 
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    // Lấy xe theo trạng thái
    public function getCarsByStatus($status)
    {
        $sql = "SELECT c.*, b.name as brand_name, cat.name as category_name 
                FROM {$this->table} c 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                WHERE c.status = ? 
                ORDER BY c.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    // Lấy xe mới nhất
    public function getLatestCars($limit = 10)
    {
        $sql = "SELECT c.*, b.name as brand_name, cat.name as category_name 
                FROM {$this->table} c 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                ORDER BY c.created_at DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy xe nổi bật (có nhiều review hoặc đánh giá cao)
    public function getFeaturedCars($limit = 6)
    {
        $sql = "SELECT c.*, b.name as brand_name, cat.name as category_name, 
                COUNT(r.id) as review_count, AVG(r.rating) as avg_rating 
                FROM {$this->table} c 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                LEFT JOIN reviews r ON c.id = r.car_id 
                WHERE c.status = 'available' 
                GROUP BY c.id 
                ORDER BY review_count DESC, avg_rating DESC 
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Cập nhật trạng thái xe
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    // Lấy hình ảnh của xe
    public function getCarImages($carId)
    {
        $stmt = $this->db->prepare("SELECT * FROM car_images WHERE car_id = ?");
        $stmt->execute([$carId]);
        return $stmt->fetchAll();
    }

    // Thêm hình ảnh cho xe
    public function addCarImage($carId, $imageUrl)
    {
        $stmt = $this->db->prepare("INSERT INTO car_images (car_id, image_url) VALUES (?, ?)");
        return $stmt->execute([$carId, $imageUrl]);
    }

    // Xóa hình ảnh của xe
    public function deleteCarImage($imageId)
    {
        $stmt = $this->db->prepare("DELETE FROM car_images WHERE id = ?");
        return $stmt->execute([$imageId]);
    }
}
