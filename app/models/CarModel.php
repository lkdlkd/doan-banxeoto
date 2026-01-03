<?php
// app/models/CarModel.php

require_once __DIR__ . '/BaseModel.php';

class CarModel extends BaseModel
{
    protected $table = 'cars';

    // Lấy tất cả xe với thông tin brand và category
    public function getAllWithDetails()
    {
        $sql = "SELECT c.*, b.name as brand_name, cat.name as category_name,
                ci.image_url 
                FROM {$this->table} c 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                LEFT JOIN car_images ci ON c.id = ci.car_id
                GROUP BY c.id
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
    public function search($keyword, $brandIds = null, $categoryIds = null, $minPrice = null, $maxPrice = null)
    {
        $sql = "SELECT c.*, b.name as brand_name, cat.name as category_name 
                FROM {$this->table} c 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                WHERE c.status = 'available'";
        
        $params = [];
        
        if ($keyword) {
            $sql .= " AND (c.name LIKE ? OR c.description LIKE ? OR b.name LIKE ?)";
            $searchTerm = "%{$keyword}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($brandIds) {
            // Support comma-separated brand IDs
            $brandArray = is_array($brandIds) ? $brandIds : explode(',', $brandIds);
            $brandArray = array_filter($brandArray); // Remove empty values
            if (!empty($brandArray)) {
                $placeholders = str_repeat('?,', count($brandArray) - 1) . '?';
                $sql .= " AND c.brand_id IN ($placeholders)";
                $params = array_merge($params, $brandArray);
            }
        }
        
        if ($categoryIds) {
            // Support comma-separated category IDs
            $categoryArray = is_array($categoryIds) ? $categoryIds : explode(',', $categoryIds);
            $categoryArray = array_filter($categoryArray); // Remove empty values
            if (!empty($categoryArray)) {
                $placeholders = str_repeat('?,', count($categoryArray) - 1) . '?';
                $sql .= " AND c.category_id IN ($placeholders)";
                $params = array_merge($params, $categoryArray);
            }
        }
        
        if ($minPrice !== null && $minPrice !== '') {
            $sql .= " AND c.price >= ?";
            $params[] = $minPrice;
        }
        
        if ($maxPrice !== null && $maxPrice !== '') {
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

    // Đếm số xe theo brand
    public function countByBrand($brandId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE brand_id = ?");
        $stmt->execute([$brandId]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    // Đếm số xe theo category
    public function countByCategory($categoryId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE category_id = ?");
        $stmt->execute([$categoryId]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    // Tìm xe theo ID với đầy đủ thông tin
    public function findWithDetails($id)
    {
        $sql = "SELECT c.*, b.name as brand_name, cat.name as category_name 
                FROM {$this->table} c 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN categories cat ON c.category_id = cat.id 
                WHERE c.id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy danh sách ảnh của xe
    public function getImages($carId)
    {
        $stmt = $this->db->prepare("SELECT * FROM car_images WHERE car_id = ? ORDER BY id");
        $stmt->execute([$carId]);
        return $stmt->fetchAll();
    }

    // Thêm ảnh mới cho xe
    public function addImage($carId, $imageUrl)
    {
        $stmt = $this->db->prepare("INSERT INTO car_images (car_id, image_url) VALUES (?, ?)");
        return $stmt->execute([$carId, $imageUrl]);
    }

    // Xóa ảnh
    public function deleteImage($imageId)
    {
        $stmt = $this->db->prepare("DELETE FROM car_images WHERE id = ?");
        return $stmt->execute([$imageId]);
    }

    // Lấy xe theo ID (alias cho findWithDetails)
    public function getById($id)
    {
        return $this->findWithDetails($id);
    }

    // Lấy xe tương tự (cùng brand hoặc category)
    public function getSimilarCars($currentCarId, $brandId, $categoryId, $limit = 4)
    {
        $sql = "SELECT c.*, b.name as brand_name, cat.name as category_name,
                (SELECT image_url FROM car_images WHERE car_id = c.id LIMIT 1) as image_url
                FROM {$this->table} c
                LEFT JOIN brands b ON c.brand_id = b.id
                LEFT JOIN categories cat ON c.category_id = cat.id
                WHERE c.id != ? 
                AND (c.brand_id = ? OR c.category_id = ?)
                AND c.status = 'available'
                ORDER BY 
                    CASE WHEN c.brand_id = ? THEN 2 ELSE 1 END DESC,
                    c.created_at DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, $currentCarId, PDO::PARAM_INT);
        $stmt->bindValue(2, $brandId, PDO::PARAM_INT);
        $stmt->bindValue(3, $categoryId, PDO::PARAM_INT);
        $stmt->bindValue(4, $brandId, PDO::PARAM_INT);
        $stmt->bindValue(5, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
