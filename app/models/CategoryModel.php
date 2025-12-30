<?php
// app/models/CategoryModel.php

require_once __DIR__ . '/BaseModel.php';

class CategoryModel extends BaseModel
{
    protected $table = 'categories';

    // Tìm category theo tên
    public function findByName($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    // Lấy danh sách categories với số lượng xe
    public function getCategoriesWithCarCount()
    {
        $sql = "SELECT c.*, COUNT(car.id) as car_count 
                FROM {$this->table} c 
                LEFT JOIN cars car ON c.id = car.category_id 
                GROUP BY c.id 
                ORDER BY c.name ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tìm kiếm categories
    public function search($keyword)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name LIKE ? OR description LIKE ?");
        $searchTerm = "%{$keyword}%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    // Kiểm tra tên category đã tồn tại
    public function nameExists($name, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE name = ? AND id != ?");
            $stmt->execute([$name, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE name = ?");
            $stmt->execute([$name]);
        }
        
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}
