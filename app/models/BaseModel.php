<?php
// app/models/BaseModel.php

require_once __DIR__ . '/../../config/database.php';

class BaseModel
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Lấy tất cả bản ghi
    public function all()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Tìm bản ghi theo ID
    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Tạo bản ghi mới
    public function create($data)
    {
        $fields = array_keys($data);
        $values = array_values($data);
        
        $fieldString = implode(', ', $fields);
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        
        $sql = "INSERT INTO {$this->table} ({$fieldString}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute($values)) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Cập nhật bản ghi
    public function update($id, $data)
    {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "{$key} = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        $fieldString = implode(', ', $fields);
        
        $sql = "UPDATE {$this->table} SET {$fieldString} WHERE {$this->primaryKey} = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($values);
    }

    // Xóa bản ghi
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }

    // Tìm kiếm với điều kiện
    public function where($conditions = [])
    {
        $whereClauses = [];
        $values = [];
        
        foreach ($conditions as $key => $value) {
            $whereClauses[] = "{$key} = ?";
            $values[] = $value;
        }
        
        $whereString = implode(' AND ', $whereClauses);
        $sql = "SELECT * FROM {$this->table} WHERE {$whereString}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($values);
        
        return $stmt->fetchAll();
    }

    // Đếm số bản ghi
    public function count($conditions = [])
    {
        if (empty($conditions)) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table}");
            $stmt->execute();
        } else {
            $whereClauses = [];
            $values = [];
            
            foreach ($conditions as $key => $value) {
                $whereClauses[] = "{$key} = ?";
                $values[] = $value;
            }
            
            $whereString = implode(' AND ', $whereClauses);
            $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$whereString}";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($values);
        }
        
        $result = $stmt->fetch();
        return $result['total'];
    }

    // Phân trang
    public function paginate($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $perPage, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}
