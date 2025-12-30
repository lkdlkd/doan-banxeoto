<?php
// app/models/ContactModel.php

require_once __DIR__ . '/BaseModel.php';

class ContactModel extends BaseModel
{
    protected $table = 'contacts';

    // Lấy tất cả liên hệ sắp xếp theo ngày tạo
    public function getAllContacts()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy liên hệ theo trạng thái
    public function getContactsByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE status = ? ORDER BY created_at DESC");
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    // Tạo liên hệ mới
    public function createContact($data)
    {
        return $this->create($data);
    }

    // Cập nhật trạng thái liên hệ
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    // Đếm số liên hệ theo trạng thái
    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE status = ?");
        $stmt->execute([$status]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    // Lấy liên hệ chưa xử lý
    public function getUnreadContacts()
    {
        return $this->getContactsByStatus('unread');
    }

    // Lấy liên hệ đã xử lý
    public function getReadContacts()
    {
        return $this->getContactsByStatus('read');
    }

    // Đánh dấu đã đọc
    public function markAsRead($id)
    {
        return $this->updateStatus($id, 'read');
    }

    // Đánh dấu chưa đọc
    public function markAsUnread($id)
    {
        return $this->updateStatus($id, 'unread');
    }

    // Tìm kiếm liên hệ
    public function search($keyword)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE name LIKE ? OR email LIKE ? OR message LIKE ? ORDER BY created_at DESC");
        $searchTerm = "%{$keyword}%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll();
    }
}
