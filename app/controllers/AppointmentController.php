<?php
// app/controllers/AppointmentController.php

require_once __DIR__ . '/../models/AppointmentModel.php';
require_once __DIR__ . '/../models/CarModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class AppointmentController
{
    private $appointmentModel;
    private $carModel;

    public function __construct()
    {
        $this->appointmentModel = new AppointmentModel();
        $this->carModel = new CarModel();
    }

    // Xử lý tạo lịch hẹn
    public function createAppointment()
    {
        SessionHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cars');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $carId = $_POST['car_id'] ?? null;
        $appointmentDate = $_POST['appointment_date'] ?? null;
        $appointmentTime = $_POST['appointment_time'] ?? null;
        $fullName = $_POST['full_name'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $notes = $_POST['notes'] ?? '';
        
        // Validate
        if (!$carId || !$appointmentDate || !$appointmentTime || !$fullName || !$phone) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: /appointment/book/' . $carId);
            exit;
        }
        
        // Validate date (must be future)
        $today = date('Y-m-d');
        if ($appointmentDate < $today) {
            $_SESSION['error'] = 'Ngày hẹn phải là ngày trong tương lai';
            header('Location: /appointment/book/' . $carId);
            exit;
        }
        
        // Check availability
        if (!$this->appointmentModel->checkAvailability($appointmentDate, $appointmentTime)) {
            $_SESSION['error'] = 'Khung giờ này đã đầy. Vui lòng chọn giờ khác';
            header('Location: /appointment/book/' . $carId);
            exit;
        }
        
        // Lấy thông tin xe
        $car = $this->carModel->getById($carId);
        if (!$car) {
            $_SESSION['error'] = 'Xe không tồn tại';
            header('Location: /cars');
            exit;
        }
        
        // Tạo lịch hẹn
        $appointmentData = [
            'user_id' => $userId,
            'car_id' => $carId,
            'appointment_date' => $appointmentDate,
            'appointment_time' => $appointmentTime,
            'full_name' => $fullName,
            'phone' => $phone,
            'email' => $email,
            'notes' => $notes,
            'status' => 'pending'
        ];
        
        $appointmentId = $this->appointmentModel->create($appointmentData);
        
        if ($appointmentId) {
            $_SESSION['success'] = 'Đặt lịch xem xe thành công! Chúng tôi sẽ liên hệ xác nhận với bạn sớm.';
            header('Location: /car/' . $carId);
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại.';
            header('Location: /appointment/book/' . $carId);
        }
        exit;
    }

    // Hủy lịch hẹn
    public function cancelAppointment($appointmentId)
    {
        SessionHelper::requireLogin();
        
        $appointment = $this->appointmentModel->findById($appointmentId);
        
        if (!$appointment) {
            $_SESSION['error'] = 'Lịch hẹn không tồn tại';
            header('Location: /appointments');
            exit;
        }
        
        // Kiểm tra quyền hủy
        if ($appointment['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Bạn không có quyền hủy lịch hẹn này';
            header('Location: /appointments');
            exit;
        }
        
        // Chỉ cho phép hủy lịch hẹn pending
        if ($appointment['status'] !== 'pending') {
            $_SESSION['error'] = 'Không thể hủy lịch hẹn đã được xác nhận hoặc hoàn thành';
            header('Location: /appointments');
            exit;
        }
        
        $this->appointmentModel->updateStatus($appointmentId, 'cancelled');
        
        $_SESSION['success'] = 'Đã hủy lịch hẹn thành công';
        header('Location: /appointments');
        exit;
    }

    // Hiển thị danh sách lịch hẹn của user
    public function showMyAppointments()
    {
        SessionHelper::requireLogin();
        
        $userId = $_SESSION['user_id'];
        $appointments = $this->appointmentModel->getAppointmentsByUser($userId);
        
        include __DIR__ . '/../views/user/appointments.php';
    }

    // ===== ADMIN FUNCTIONS =====

    // Hiển thị danh sách lịch hẹn (Admin)
    public function adminList()
    {
        SessionHelper::requireAdmin();
        include __DIR__ . '/../views/admin/appointments/list.php';
    }

    // Cập nhật trạng thái lịch hẹn (Admin)
    public function adminUpdateStatus($appointmentId, $status)
    {
        SessionHelper::requireAdmin();
        
        $validStatuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái không hợp lệ';
            header('Location: ' . BASE_URL . '/admin/appointments');
            exit;
        }
        
        $appointment = $this->appointmentModel->findById($appointmentId);
        if (!$appointment) {
            $_SESSION['error'] = 'Lịch hẹn không tồn tại';
            header('Location: ' . BASE_URL . '/admin/appointments');
            exit;
        }
        
        $this->appointmentModel->updateStatus($appointmentId, $status);
        
        $_SESSION['success'] = 'Đã cập nhật trạng thái lịch hẹn thành công';
        header('Location: ' . BASE_URL . '/admin/appointments');
        exit;
    }

    // Xóa lịch hẹn (Admin)
    public function adminDelete($appointmentId)
    {
        SessionHelper::requireAdmin();
        
        $appointment = $this->appointmentModel->findById($appointmentId);
        if (!$appointment) {
            $_SESSION['error'] = 'Lịch hẹn không tồn tại';
            header('Location: ' . BASE_URL . '/admin/appointments');
            exit;
        }
        
        $this->appointmentModel->delete($appointmentId);
        
        $_SESSION['success'] = 'Đã xóa lịch hẹn thành công';
        header('Location: ' . BASE_URL . '/admin/appointments');
        exit;
    }

    // Xem chi tiết lịch hẹn (Admin)
    public function adminDetail($appointmentId)
    {
        SessionHelper::requireAdmin();
        
        $appointment = $this->appointmentModel->getAppointmentWithDetails($appointmentId);
        
        if (!$appointment) {
            $_SESSION['error'] = 'Lịch hẹn không tồn tại';
            header('Location: ' . BASE_URL . '/admin/appointments');
            exit;
        }
        
        include __DIR__ . '/../views/admin/appointments/detail.php';
    }
}
