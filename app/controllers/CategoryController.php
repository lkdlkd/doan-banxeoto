<?php
// app/controllers/CategoryController.php

require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/CarModel.php';

class CategoryController
{
    private $categoryModel;
    private $carModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->carModel = new CarModel();

        // Khởi động session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Hiển thị danh sách danh mục
    public function index()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        $categories = $this->categoryModel->getCategoriesWithCarCount();
        $totalCategories = count($categories);

        // Load view
        require_once __DIR__ . '/../views/admin/categories/list.php';
    }

    // Thêm danh mục mới
    public function add()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories');
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Validate
        if (empty($name)) {
            $_SESSION['error'] = 'Vui lòng nhập tên danh mục';
            $this->redirect('/admin/categories');
        }

        // Tạo danh mục
        $result = $this->categoryModel->create([
            'name' => $name,
            'description' => $description
        ]);

        if ($result) {
            $_SESSION['success'] = 'Thêm danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi thêm danh mục';
        }

        $this->redirect('/admin/categories');
    }

    // Cập nhật danh mục
    public function edit()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories');
        }

        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');

        // Validate
        if (empty($name)) {
            $_SESSION['error'] = 'Vui lòng nhập tên danh mục';
            $this->redirect('/admin/categories');
        }

        if ($id <= 0) {
            $_SESSION['error'] = 'ID danh mục không hợp lệ';
            $this->redirect('/admin/categories');
        }

        // Chuẩn bị dữ liệu cập nhật
        $data = [
            'name' => $name,
            'description' => $description
        ];

        // Cập nhật danh mục
        $result = $this->categoryModel->update($id, $data);

        if ($result) {
            $_SESSION['success'] = 'Cập nhật danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật danh mục';
        }

        $this->redirect('/admin/categories');
    }

    // Xóa danh mục
    public function delete()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories');
        }

        $id = intval($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = 'ID danh mục không hợp lệ';
            $this->redirect('/admin/categories');
        }

        // Kiểm tra xem danh mục có xe nào không
        $carCount = $this->carModel->countByCategory($id);
        if ($carCount > 0) {
            $_SESSION['error'] = "Không thể xóa danh mục này vì còn {$carCount} xe thuộc danh mục";
            $this->redirect('/admin/categories');
        }

        // Xóa danh mục
        $result = $this->categoryModel->delete($id);

        if ($result) {
            $_SESSION['success'] = 'Xóa danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa danh mục';
        }

        $this->redirect('/admin/categories');
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
