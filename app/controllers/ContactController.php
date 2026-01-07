<?php
// app/controllers/ContactController.php

require_once __DIR__ . '/../models/ContactModel.php';

class ContactController
{
    private $contactModel;

    public function __construct()
    {
        $this->contactModel = new ContactModel();
    }

    // Hiển thị trang liên hệ
    public function index()
    {
        require_once VIEW_PATH . '/user/contact.php';
    }

    // Xử lý gửi form liên hệ
    public function submit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /contact');
            exit;
        }

        try {
            // Validate dữ liệu
            $errors = [];

            if (empty($_POST['name'])) {
                $errors[] = 'Vui lòng nhập họ tên';
            }

            if (empty($_POST['phone'])) {
                $errors[] = 'Vui lòng nhập số điện thoại';
            } elseif (!preg_match('/^[0-9]{10,11}$/', str_replace(' ', '', $_POST['phone']))) {
                $errors[] = 'Số điện thoại không hợp lệ';
            }

            if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Email không hợp lệ';
            }

            if (empty($_POST['message'])) {
                $errors[] = 'Vui lòng nhập nội dung tin nhắn';
            }

            if (!empty($errors)) {
                $_SESSION['contact_error'] = implode('<br>', $errors);
                $_SESSION['contact_old'] = $_POST;
                header('Location: /contact');
                exit;
            }

            // Lưu vào database
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim(str_replace(' ', '', $_POST['phone'])),
                'subject' => trim($_POST['subject'] ?? ''),
                'message' => trim($_POST['message']),
                'status' => 'new'
            ];

            $result = $this->contactModel->createContact($data);

            if ($result) {
                $_SESSION['contact_success'] = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi trong thời gian sớm nhất.';
                unset($_SESSION['contact_old']);
            } else {
                $_SESSION['contact_error'] = 'Có lỗi xảy ra, vui lòng thử lại sau.';
            }
        } catch (Exception $e) {
            $_SESSION['contact_error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        header('Location: /contact');
        exit;
    }

    // Admin - Danh sách liên hệ
    public function adminList()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }

        // Contacts đã được load trong view
        require VIEW_PATH . '/admin/contacts/list.php';
    }

    // Admin - Cập nhật trạng thái
    public function adminUpdateStatus($id, $status)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Không có quyền']);
            exit;
        }

        try {
            $result = $this->contactModel->updateStatus($id, $status);

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    // Admin - Xóa liên hệ
    public function adminDelete($id)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            echo json_encode(['success' => false, 'message' => 'Không có quyền']);
            exit;
        }

        try {
            $result = $this->contactModel->delete($id);

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Xóa thất bại']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
