<?php
require_once __DIR__ . '/../models/UserModel.php';

class UserController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function updateProfile()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $fullName = trim($_POST['full_name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $birthDate = $_POST['birth_date'] ?? null;
        $address = trim($_POST['address'] ?? '');

        // Validate
        if (empty($fullName)) {
            $_SESSION['error'] = 'Họ và tên không được để trống';
            header('Location: /profile');
            exit;
        }

        // Validate phone nếu có
        if (!empty($phone) && !preg_match('/^[0-9]{10}$/', $phone)) {
            $_SESSION['error'] = 'Số điện thoại phải có 10 chữ số';
            header('Location: /profile');
            exit;
        }

        // Update profile
        $result = $this->userModel->updateUser($userId, [
            'full_name' => $fullName,
            'phone' => $phone,
            'birth_date' => $birthDate,
            'address' => $address
        ]);

        if ($result) {
            $_SESSION['success'] = 'Cập nhật thông tin thành công';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }

        header('Location: /profile');
        exit;
    }

    public function changePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validate
        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['password_error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: /profile');
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['password_error'] = 'Mật khẩu xác nhận không khớp';
            header('Location: /profile');
            exit;
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['password_error'] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
            header('Location: /profile');
            exit;
        }

        // Get user
        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            $_SESSION['password_error'] = 'Người dùng không tồn tại';
            header('Location: /profile');
            exit;
        }

        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            $_SESSION['password_error'] = 'Mật khẩu hiện tại không đúng';
            header('Location: /profile');
            exit;
        }

        // Update password
        $result = $this->userModel->updateUser($userId, [
            'password' => $newPassword  // UserModel sẽ tự hash
        ]);

        if ($result) {
            $_SESSION['password_success'] = 'Đổi mật khẩu thành công';
        } else {
            $_SESSION['password_error'] = 'Có lỗi xảy ra, vui lòng thử lại';
        }

        header('Location: /profile#security');
        exit;
    }
}
