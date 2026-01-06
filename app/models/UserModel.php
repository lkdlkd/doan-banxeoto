<?php
// app/models/UserModel.php

require_once __DIR__ . '/BaseModel.php';

class UserModel extends BaseModel
{
    protected $table = 'users';

    // Tìm user theo username
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    // Tìm user theo email
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    // Tạo user mới với mật khẩu đã mã hóa
    public function createUser($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->create($data);
    }

    // Cập nhật user
    public function updateUser($id, $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->update($id, $data);
    }

    // Xác thực đăng nhập bằng username
    public function authenticate($username, $password)
    {
        $user = $this->findByUsername($username);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    // Xác thực đăng nhập bằng email
    public function authenticateByEmail($email, $password)
    {
        $user = $this->findByEmail($email);
        
        // Nếu không tìm thấy bằng email, thử tìm bằng username
        if (!$user) {
            $user = $this->findByUsername($email);
        }
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        
        return false;
    }

    // Lấy user theo ID
    public function getUserById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy danh sách user theo role
    public function getUsersByRole($role)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }

    // Kiểm tra username đã tồn tại
    public function usernameExists($username, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE username = ? AND id != ?");
            $stmt->execute([$username, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE username = ?");
            $stmt->execute([$username]);
        }
        
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    // Kiểm tra email đã tồn tại
    public function emailExists($email, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE email = ? AND id != ?");
            $stmt->execute([$email, $excludeId]);
        } else {
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE email = ?");
            $stmt->execute([$email]);
        }
        
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }
}
