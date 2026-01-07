<?php
// app/models/AppointmentModel.php

require_once __DIR__ . '/BaseModel.php';

class AppointmentModel extends BaseModel
{
    protected $table = 'appointments';

    // Lấy tất cả lịch hẹn với thông tin chi tiết
    public function getAllWithDetails()
    {
        $sql = "SELECT a.*, c.name as car_name, c.price as car_price, 
                b.name as brand_name, 
                u.full_name as user_name, u.email as user_email,
                (SELECT image_url FROM car_images WHERE car_id = c.id ORDER BY id ASC LIMIT 1) as car_image
                FROM {$this->table} a 
                LEFT JOIN cars c ON a.car_id = c.id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN users u ON a.user_id = u.id 
                ORDER BY a.appointment_date DESC, a.appointment_time DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Lấy lịch hẹn theo ID với thông tin chi tiết
    public function getAppointmentWithDetails($id)
    {
        $sql = "SELECT a.*, c.name as car_name, c.price as car_price, 
                b.name as brand_name, 
                u.full_name as user_name, u.email as user_email,
                (SELECT image_url FROM car_images WHERE car_id = c.id ORDER BY id ASC LIMIT 1) as car_image
                FROM {$this->table} a 
                LEFT JOIN cars c ON a.car_id = c.id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN users u ON a.user_id = u.id 
                WHERE a.id = ?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy lịch hẹn theo ID (đơn giản)
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Lấy lịch hẹn theo user
    public function getAppointmentsByUser($userId, $limit = null)
    {
        $sql = "SELECT a.*, 
                c.name as car_name, 
                c.price as car_price, 
                b.name as brand_name,
                (SELECT image_url FROM car_images WHERE car_id = c.id ORDER BY id ASC LIMIT 1) as car_image
                FROM {$this->table} a 
                LEFT JOIN cars c ON a.car_id = c.id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                WHERE a.user_id = ? 
                ORDER BY a.appointment_date DESC, a.appointment_time DESC";

        if ($limit !== null) {
            $sql .= " LIMIT " . (int)$limit;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Lấy lịch hẹn theo trạng thái
    public function getAppointmentsByStatus($status)
    {
        $sql = "SELECT a.*, c.name as car_name, b.name as brand_name, 
                u.full_name as user_name 
                FROM {$this->table} a 
                LEFT JOIN cars c ON a.car_id = c.id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN users u ON a.user_id = u.id 
                WHERE a.status = ? 
                ORDER BY a.appointment_date DESC, a.appointment_time DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status]);
        return $stmt->fetchAll();
    }

    // Cập nhật trạng thái lịch hẹn
    public function updateStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    // Tạo lịch hẹn mới
    public function createAppointment($data)
    {
        return $this->create($data);
    }

    // Đếm lịch hẹn theo trạng thái
    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE status = ?");
        $stmt->execute([$status]);
        $result = $stmt->fetch();
        return $result['total'];
    }

    // Kiểm tra xem có lịch hẹn nào trong khoảng thời gian không
    public function checkAvailability($date, $time)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE appointment_date = ? 
                AND appointment_time = ? 
                AND status != 'cancelled'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date, $time]);
        $result = $stmt->fetch();

        // Giới hạn tối đa 3 lịch hẹn cùng thời điểm
        return $result['total'] < 3;
    }

    // Lấy lịch hẹn theo ngày
    public function getAppointmentsByDate($date)
    {
        $sql = "SELECT a.*, c.name as car_name, b.name as brand_name, 
                u.full_name as user_name 
                FROM {$this->table} a 
                LEFT JOIN cars c ON a.car_id = c.id 
                LEFT JOIN brands b ON c.brand_id = b.id 
                LEFT JOIN users u ON a.user_id = u.id 
                WHERE a.appointment_date = ? 
                AND a.status != 'cancelled'
                ORDER BY a.appointment_time ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }
}
