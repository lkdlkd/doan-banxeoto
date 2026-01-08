<?php
// app/controllers/BrandController.php

require_once __DIR__ . '/../models/BrandModel.php';
require_once __DIR__ . '/../models/CarModel.php';

class BrandController
{
    private $brandModel;
    private $carModel;

    public function __construct()
    {
        $this->brandModel = new BrandModel();
        $this->carModel = new CarModel();

        // Khởi động session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Hiển thị danh sách thương hiệu
    public function index()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        $brands = $this->brandModel->getBrandsWithCarCount();
        $totalBrands = count($brands);

        // Load view
        require_once __DIR__ . '/../views/admin/brands/list.php';
    }

    // Thêm thương hiệu mới
    public function add()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/brands');
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $logo = '';

        // Validate
        if (empty($name)) {
            $_SESSION['error'] = 'Vui lòng nhập tên thương hiệu';
            $this->redirect('/admin/brands');
        }

        // Xử lý upload logo - ưu tiên file upload
        if (!empty($_FILES['logo_file']['name'])) {
            $uploadResult = $this->handleImageUpload($_FILES['logo_file']);
            if ($uploadResult['success']) {
                $logo = $uploadResult['url'];
            } else {
                $_SESSION['error'] = $uploadResult['message'];
                $this->redirect('/admin/brands');
            }
        } elseif (!empty($_POST['logo_url'])) {
            $logo = trim($_POST['logo_url']);
        }

        // Tạo thương hiệu
        $result = $this->brandModel->create([
            'name' => $name,
            'logo' => $logo,
            'description' => $description
        ]);

        if ($result) {
            $_SESSION['success'] = 'Thêm thương hiệu thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi thêm thương hiệu';
        }

        $this->redirect('/admin/brands');
    }

    // Cập nhật thương hiệu
    public function edit()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/brands');
        }

        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Validate
        if (empty($name)) {
            $_SESSION['error'] = 'Vui lòng nhập tên thương hiệu';
            $this->redirect('/admin/brands');
        }

        if ($id <= 0) {
            $_SESSION['error'] = 'ID thương hiệu không hợp lệ';
            $this->redirect('/admin/brands');
        }

        // Chuẩn bị dữ liệu cập nhật
        $data = [
            'name' => $name,
            'description' => $description
        ];

        // Xử lý upload logo mới (nếu có)
        if (!empty($_FILES['logo_file']['name'])) {
            $uploadResult = $this->handleImageUpload($_FILES['logo_file']);
            if ($uploadResult['success']) {
                $data['logo'] = $uploadResult['url'];
            }
        } elseif (!empty($_POST['logo_url'])) {
            $data['logo'] = trim($_POST['logo_url']);
        }

        // Cập nhật thương hiệu
        $result = $this->brandModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Cập nhật thương hiệu thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật thương hiệu';
        }

        $this->redirect('/admin/brands');
    }

    // Xóa thương hiệu
    public function delete()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/brands');
        }

        $id = intval($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID thương hiệu không hợp lệ';
            $this->redirect('/admin/brands');
        }

        // Kiểm tra xem thương hiệu có xe nào không
        $carCount = $this->carModel->countByBrand($id);
        if ($carCount > 0) {
            $_SESSION['error'] = "Không thể xóa thương hiệu này vì còn {$carCount} xe thuộc thương hiệu";
            $this->redirect('/admin/brands');
        }

        // Xóa thương hiệu
        $result = $this->brandModel->delete($id);

        if ($result) {
            $_SESSION['success'] = 'Xóa thương hiệu thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa thương hiệu';
        }

        $this->redirect('/admin/brands');
    }

    // Xử lý upload ảnh
    private function handleImageUpload($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp', 'image/svg+xml'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Kiểm tra lỗi upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Lỗi khi tải logo lên'];
        }

        // Kiểm tra loại file
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP, SVG)'];
        }

        // Kiểm tra kích thước
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'Kích thước logo không được vượt quá 5MB'];
        }

        // Tạo thư mục nếu chưa tồn tại
        $uploadDir = BASE_PATH . '/assets/images/brands/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Tạo tên file unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $targetFile = $uploadDir . $fileName;

        // Di chuyển file
        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return [
                'success' => true,
                'url' => BASE_URL . '/assets/images/brands/' . $fileName
            ];
        }

        return ['success' => false, 'message' => 'Không thể lưu logo'];
    }

    // Kiểm tra quyền admin
    private function checkAdmin()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập';
            $this->redirect('/login');
        }

        if ($_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            $this->redirect('/');
        }
    }

    // Helper redirect
    private function redirect($path)
    {
        header("Location: $path");
        exit();
    }
}
