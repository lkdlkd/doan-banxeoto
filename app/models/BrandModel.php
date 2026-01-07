<?php
// app/models/BrandModel.php

require_once __DIR__ . '/BaseModel.php';

class BrandModel extends BaseModel
{
    protected $table = 'brands';

    // Tìm brand theo tên
    public function findByName($name)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name = ?");
        $stmt->execute([$name]);
        return $stmt->fetch();
    }

    // Lấy danh sách brands với số lượng xe
    public function getBrandsWithCarCount()
    {
        $sql = "SELECT b.*, COUNT(c.id) as car_count 
                FROM {$this->table} b 
                LEFT JOIN cars c ON b.id = c.brand_id 
                GROUP BY b.id 
                ORDER BY b.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tìm kiếm brands
    public function search($keyword)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name LIKE ? OR country LIKE ?");
        $searchTerm = "%{$keyword}%";
        $stmt->execute([$searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }

    // Kiểm tra tên brand đã tồn tại
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
