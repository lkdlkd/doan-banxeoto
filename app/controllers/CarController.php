<?php
// app/controllers/CarController.php

require_once __DIR__ . '/../models/CarModel.php';
require_once __DIR__ . '/../models/BrandModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class CarController
{
    private $carModel;
    private $brandModel;
    private $categoryModel;

    public function __construct()
    {
        $this->carModel = new CarModel();
        $this->brandModel = new BrandModel();
        $this->categoryModel = new CategoryModel();

        // Khởi động session nếu chưa có
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Hiển thị danh sách xe
    public function index()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        $cars = $this->carModel->getAllWithDetails();
        $brands = $this->brandModel->getAll();
        $categories = $this->categoryModel->getAll();

        $totalCars = count($cars);
        $availableCars = count(array_filter($cars, fn($c) => $c['status'] === 'available'));
        $soldCars = count(array_filter($cars, fn($c) => $c['status'] === 'sold'));

        // Load view
        require_once __DIR__ . '/../views/admin/cars/list.php';
    }

    // Hiển thị form thêm xe
    public function showAdd()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        $brands = $this->brandModel->getAll();
        $categories = $this->categoryModel->getAll();

        // Load view
        require_once __DIR__ . '/../views/admin/cars/add.php';
    }

    // Thêm xe mới
    public function add()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/cars/add');
        }

        $name = trim($_POST['name'] ?? '');
        $brandId = intval($_POST['brand_id'] ?? 0);
        $categoryId = intval($_POST['category_id'] ?? 0);
        $price = floatval($_POST['price'] ?? 0);
        $year = intval($_POST['year'] ?? date('Y'));
        $mileage = intval($_POST['mileage'] ?? 0);
        $fuel = trim($_POST['fuel'] ?? 'gasoline');
        $transmission = trim($_POST['transmission'] ?? 'automatic');
        $color = trim($_POST['color'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = trim($_POST['status'] ?? 'available');

        // Thông số kỹ thuật
        $stock = intval($_POST['stock'] ?? 1);
        $engine = trim($_POST['engine'] ?? '');
        $horsepower = !empty($_POST['horsepower']) ? intval($_POST['horsepower']) : null;
        $torque = !empty($_POST['torque']) ? intval($_POST['torque']) : null;
        $acceleration = !empty($_POST['acceleration']) ? floatval($_POST['acceleration']) : null;
        $drivetrain = trim($_POST['drivetrain'] ?? '');
        $seats = !empty($_POST['seats']) ? intval($_POST['seats']) : null;
        $doors = !empty($_POST['doors']) ? intval($_POST['doors']) : null;

        // Validate
        if (empty($name) || $brandId === 0 || $categoryId === 0 || $price <= 0) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            $this->redirect('/admin/cars/add');
        }

        // Xử lý ảnh chính
        $mainImage = '';
        if (!empty($_FILES['main_image']['name'])) {
            $uploadResult = $this->handleImageUpload($_FILES['main_image']);
            if ($uploadResult['success']) {
                $mainImage = $uploadResult['url'];
            }
        } elseif (!empty($_POST['main_image_url'])) {
            $mainImage = trim($_POST['main_image_url']);
        }

        // Tạo xe mới
        $carData = [
            'name' => $name,
            'brand_id' => $brandId,
            'category_id' => $categoryId,
            'price' => $price,
            'year' => $year,
            'mileage' => $mileage,
            'fuel' => $fuel,
            'transmission' => $transmission,
            'color' => $color,
            'description' => $description,
            'status' => $status,
            'stock' => $stock,
            'engine' => $engine,
            'horsepower' => $horsepower,
            'torque' => $torque,
            'acceleration' => $acceleration,
            'drivetrain' => $drivetrain,
            'seats' => $seats,
            'doors' => $doors
        ];

        $carId = $this->carModel->create($carData);

        if ($carId) {
            // Xử lý ảnh gallery
            $galleryImages = [];

            // Upload từ file
            if (!empty($_FILES['gallery_images']['name'][0])) {
                foreach ($_FILES['gallery_images']['tmp_name'] as $key => $tmpName) {
                    if (!empty($_FILES['gallery_images']['name'][$key])) {
                        $file = [
                            'name' => $_FILES['gallery_images']['name'][$key],
                            'type' => $_FILES['gallery_images']['type'][$key],
                            'tmp_name' => $tmpName,
                            'error' => $_FILES['gallery_images']['error'][$key],
                            'size' => $_FILES['gallery_images']['size'][$key]
                        ];
                        $uploadResult = $this->handleImageUpload($file);
                        if ($uploadResult['success']) {
                            $galleryImages[] = $uploadResult['url'];
                        }
                    }
                }
            }

            // URLs từ input
            if (!empty($_POST['gallery_urls'])) {
                $urls = array_filter(array_map('trim', explode("\n", $_POST['gallery_urls'])));
                $galleryImages = array_merge($galleryImages, $urls);
            }

            // Lưu gallery images vào car_images
            foreach ($galleryImages as $imageUrl) {
                $this->carModel->addImage($carId, $imageUrl);
            }

            // Nếu có main image, thêm vào gallery
            if (!empty($mainImage)) {
                $this->carModel->addImage($carId, $mainImage);
            }

            $_SESSION['success'] = 'Thêm xe mới thành công!';
            $this->redirect('/admin/cars');
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi thêm xe';
            $this->redirect('/admin/cars/add');
        }
    }

    // Hiển thị form sửa xe
    public function showEdit($id)
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        // Kiểm tra ID hợp lệ
        if ($id <= 0) {
            $_SESSION['error'] = 'ID xe không hợp lệ';
            $this->redirect('/admin/cars');
        }

        $car = $this->carModel->findWithDetails($id);
        if (!$car) {
            $_SESSION['error'] = 'Không tìm thấy xe';
            $this->redirect('/admin/cars');
        }

        $brands = $this->brandModel->getAll();
        $categories = $this->categoryModel->getAll();
        $carImages = $this->carModel->getImages($id);

        // Load view
        require_once __DIR__ . '/../views/admin/cars/edit.php';
    }

    // Cập nhật xe
    public function edit()
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/cars');
        }

        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $brandId = intval($_POST['brand_id'] ?? 0);
        $categoryId = intval($_POST['category_id'] ?? 0);
        $price = floatval($_POST['price'] ?? 0);
        $year = intval($_POST['year'] ?? date('Y'));
        $mileage = intval($_POST['mileage'] ?? 0);
        $fuel = trim($_POST['fuel'] ?? 'gasoline');
        $transmission = trim($_POST['transmission'] ?? 'automatic');
        $color = trim($_POST['color'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = trim($_POST['status'] ?? 'available');

        // Thông số kỹ thuật
        $stock = intval($_POST['stock'] ?? 1);
        $engine = trim($_POST['engine'] ?? '');
        // Thông số kỹ thuật
        $stock = intval($_POST['stock'] ?? 1);
        $engine = trim($_POST['engine'] ?? '');
        $horsepower = !empty($_POST['horsepower']) ? intval($_POST['horsepower']) : null;
        $torque = !empty($_POST['torque']) ? intval($_POST['torque']) : null;
        $acceleration = !empty($_POST['acceleration']) ? floatval($_POST['acceleration']) : null;
        $drivetrain = trim($_POST['drivetrain'] ?? '');
        $seats = !empty($_POST['seats']) ? intval($_POST['seats']) : null;
        $doors = !empty($_POST['doors']) ? intval($_POST['doors']) : null;

        // Validate
        if ($id <= 0) {
            $_SESSION['error'] = 'ID xe không hợp lệ';
            $this->redirect('/admin/cars');
        }

        if (empty($name) || $brandId === 0 || $categoryId === 0 || $price <= 0) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin bắt buộc';
            $this->redirect('/admin/cars/edit?id=' . $id);
        }

        // Cập nhật thông tin xe
        $carData = [
            'name' => $name,
            'brand_id' => $brandId,
            'category_id' => $categoryId,
            'price' => $price,
            'year' => $year,
            'mileage' => $mileage,
            'fuel' => $fuel,
            'transmission' => $transmission,
            'color' => $color,
            'description' => $description,
            'status' => $status,
            'stock' => $stock,
            'engine' => $engine,
            'horsepower' => $horsepower,
            'torque' => $torque,
            'acceleration' => $acceleration,
            'drivetrain' => $drivetrain,
            'seats' => $seats,
            'doors' => $doors
        ];

        $result = $this->carModel->update($id, $carData);

        if ($result) {
            // Xóa ảnh cũ nếu có
            if (!empty($_POST['deleted_image_ids'])) {
                $deletedIds = explode(',', $_POST['deleted_image_ids']);
                foreach ($deletedIds as $imageId) {
                    $imageId = intval(trim($imageId));
                    if ($imageId > 0) {
                        $this->carModel->deleteImage($imageId);
                    }
                }
            }

            // Xử lý ảnh mới nếu có
            $newImages = [];

            // Upload từ file
            if (!empty($_FILES['new_images']['name'][0])) {
                foreach ($_FILES['new_images']['tmp_name'] as $key => $tmpName) {
                    if (!empty($_FILES['new_images']['name'][$key])) {
                        $file = [
                            'name' => $_FILES['new_images']['name'][$key],
                            'type' => $_FILES['new_images']['type'][$key],
                            'tmp_name' => $tmpName,
                            'error' => $_FILES['new_images']['error'][$key],
                            'size' => $_FILES['new_images']['size'][$key]
                        ];
                        $uploadResult = $this->handleImageUpload($file);
                        if ($uploadResult['success']) {
                            $this->carModel->addImage($id, $uploadResult['url']);
                        }
                    }
                }
            }

            // URLs từ input
            if (!empty($_POST['new_image_urls'])) {
                foreach ($_POST['new_image_urls'] as $url) {
                    $url = trim($url);
                    if (!empty($url)) {
                        $this->carModel->addImage($id, $url);
                    }
                }
            }

            $_SESSION['success'] = 'Cập nhật xe thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật xe';
        }

        $this->redirect('/admin/cars/edit?id=' . $id);
    }

    // Xóa xe
    public function delete($id)
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        $id = intval($id);
        if ($id <= 0) {
            $_SESSION['error'] = 'ID xe không hợp lệ';
            $this->redirect('/admin/cars');
        }

        // Xóa xe
        $result = $this->carModel->delete($id);

        if ($result) {
            $_SESSION['success'] = 'Xóa xe thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi xóa xe';
        }

        $this->redirect('/admin/cars');
    }

    // Xóa ảnh của xe
    public function deleteImage($imageId)
    {
        // Kiểm tra quyền admin
        $this->checkAdmin();

        $imageId = intval($imageId);
        if ($imageId <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID ảnh không hợp lệ']);
            exit;
        }

        $result = $this->carModel->deleteImage($imageId);
        echo json_encode(['success' => $result]);
        exit;
    }

    // Xử lý upload ảnh
    private function handleImageUpload($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        $maxSize = 10 * 1024 * 1024; // 10MB

        // Kiểm tra lỗi upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Lỗi khi tải ảnh lên'];
        }

        // Kiểm tra loại file
        if (!in_array($file['type'], $allowedTypes)) {
            return ['success' => false, 'message' => 'Chỉ chấp nhận file ảnh (JPG, PNG, GIF, WEBP)'];
        }

        // Kiểm tra kích thước
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'Kích thước ảnh không được vượt quá 10MB'];
        }

        // Tạo thư mục nếu chưa tồn tại
        $uploadDir = BASE_PATH . '/assets/images/cars/';
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
                'url' => BASE_URL . '/assets/images/cars/' . $fileName
            ];
        }

        return ['success' => false, 'message' => 'Không thể lưu ảnh'];
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
