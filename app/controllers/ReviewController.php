<?php
// app/controllers/ReviewController.php

require_once __DIR__ . '/../models/ReviewModel.php';
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/CarModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class ReviewController
{
    private $reviewModel;
    private $orderModel;
    private $carModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->orderModel = new OrderModel();
        $this->carModel = new CarModel();
    }

    // Hiển thị form đánh giá
    public function showReviewForm($carId)
    {
        SessionHelper::requireLogin();
        
        $userId = $_SESSION['user_id'];
        
        // Kiểm tra user đã mua xe này chưa (đơn hàng confirmed hoặc completed)
        $hasPurchased = $this->checkUserPurchased($userId, $carId);
        
        if (!$hasPurchased) {
            $_SESSION['error'] = 'Bạn chỉ có thể đánh giá xe đã mua';
            header('Location: ' . BASE_URL . '/car/' . $carId);
            exit;
        }
        
        // Kiểm tra đã đánh giá chưa
        if ($this->reviewModel->hasUserReviewed($userId, $carId)) {
            $_SESSION['error'] = 'Bạn đã đánh giá xe này rồi';
            header('Location: ' . BASE_URL . '/car/' . $carId);
            exit;
        }
        
        $car = $this->carModel->getById($carId);
        if (!$car) {
            $_SESSION['error'] = 'Xe không tồn tại';
            header('Location: ' . BASE_URL . '/cars');
            exit;
        }
        
        include __DIR__ . '/../views/user/review_form.php';
    }

    // Xử lý submit đánh giá
    public function submitReview()
    {
        SessionHelper::requireLogin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/cars');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $carId = $_POST['car_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = $_POST['comment'] ?? '';
        
        // Validate
        if (!$carId || !$rating) {
            $_SESSION['error'] = 'Vui lòng chọn số sao đánh giá';
            header('Location: ' . BASE_URL . '/review/create/' . $carId);
            exit;
        }
        
        // Validate rating (1-5)
        $rating = intval($rating);
        if ($rating < 1 || $rating > 5) {
            $_SESSION['error'] = 'Đánh giá không hợp lệ';
            header('Location: ' . BASE_URL . '/review/create/' . $carId);
            exit;
        }
        
        // Kiểm tra user đã mua xe này chưa
        $hasPurchased = $this->checkUserPurchased($userId, $carId);
        if (!$hasPurchased) {
            $_SESSION['error'] = 'Bạn chỉ có thể đánh giá xe đã mua';
            header('Location: ' . BASE_URL . '/car/' . $carId);
            exit;
        }
        
        // Kiểm tra đã đánh giá chưa
        if ($this->reviewModel->hasUserReviewed($userId, $carId)) {
            $_SESSION['error'] = 'Bạn đã đánh giá xe này rồi';
            header('Location: ' . BASE_URL . '/car/' . $carId);
            exit;
        }
        
        // Tạo đánh giá
        $reviewData = [
            'user_id' => $userId,
            'car_id' => $carId,
            'rating' => $rating,
            'comment' => $comment
        ];
        
        $reviewId = $this->reviewModel->createReview($reviewData);
        
        if ($reviewId) {
            $_SESSION['success'] = 'Cảm ơn bạn đã đánh giá!';
            header('Location: ' . BASE_URL . '/car/' . $carId);
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại.';
            header('Location: ' . BASE_URL . '/review/create/' . $carId);
        }
        exit;
    }

    // Kiểm tra user đã mua xe chưa
    private function checkUserPurchased($userId, $carId)
    {
        return $this->orderModel->checkUserPurchased($userId, $carId);
    }

    // Xóa đánh giá (user)
    public function deleteReview($reviewId)
    {
        SessionHelper::requireLogin();
        
        $review = $this->reviewModel->getById($reviewId);
        
        if (!$review) {
            $_SESSION['error'] = 'Đánh giá không tồn tại';
            header('Location: ' . BASE_URL . '/orders');
            exit;
        }
        
        // Kiểm tra quyền xóa
        if ($review['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Bạn không có quyền xóa đánh giá này';
            header('Location: ' . BASE_URL . '/car/' . $review['car_id']);
            exit;
        }
        
        $this->reviewModel->delete($reviewId);
        
        $_SESSION['success'] = 'Đã xóa đánh giá thành công';
        header('Location: ' . BASE_URL . '/car/' . $review['car_id']);
        exit;
    }

    // ===== ADMIN FUNCTIONS =====

    // Xóa đánh giá (Admin)
    public function adminDelete($reviewId)
    {
        SessionHelper::requireAdmin();
        
        $review = $this->reviewModel->getById($reviewId);
        if (!$review) {
            $_SESSION['error'] = 'Đánh giá không tồn tại';
            header('Location: ' . BASE_URL . '/admin/reviews');
            exit;
        }
        
        $this->reviewModel->delete($reviewId);
        
        $_SESSION['success'] = 'Đã xóa đánh giá thành công';
        header('Location: ' . BASE_URL . '/admin/reviews');
        exit;
    }
}
