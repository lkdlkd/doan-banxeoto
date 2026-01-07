<?php
// app/controllers/CompareController.php
// Controller xử lý các action liên quan đến so sánh xe

require_once __DIR__ . '/../models/CompareModel.php';

class CompareController
{
    private $compareModel;

    public function __construct()
    {
        $this->compareModel = new CompareModel();
    }

    // Xử lý thêm xe vào danh sách so sánh
    public function add()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $carId = $_POST['car_id'] ?? null;

        if (!$carId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin xe']);
            return;
        }

        $result = $this->compareModel->addToCompare($carId);
        $result['count'] = $this->compareModel->getCompareCount();
        $result['max'] = $this->compareModel->getMaxCompare();

        echo json_encode($result);
    }

    // Xử lý xóa xe khỏi danh sách so sánh
    public function remove()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $carId = $_POST['car_id'] ?? null;

        if (!$carId) {
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin xe']);
            return;
        }

        $result = $this->compareModel->removeFromCompare($carId);
        $result['count'] = $this->compareModel->getCompareCount();

        echo json_encode($result);
    }

    // Xử lý xóa toàn bộ danh sách so sánh
    public function clear()
    {
        header('Content-Type: application/json');

        $result = $this->compareModel->clearCompare();
        $result['count'] = 0;

        echo json_encode($result);
    }

    // Lấy thông tin danh sách so sánh (JSON)
    public function getInfo()
    {
        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'count' => $this->compareModel->getCompareCount(),
            'max' => $this->compareModel->getMaxCompare(),
            'items' => $this->compareModel->getCompareIds()
        ]);
    }
}
