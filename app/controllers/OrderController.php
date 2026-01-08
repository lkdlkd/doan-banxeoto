<?php
// app/controllers/OrderController.php

require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/CarModel.php';
require_once __DIR__ . '/../models/CartModel.php';
require_once __DIR__ . '/../helpers/SessionHelper.php';

class OrderController
{
    private $orderModel;
    private $carModel;
    private $cartModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->carModel = new CarModel();
        $this->cartModel = new CartModel();
    }

    // Hiển thị trang đặt hàng
    public function showCheckout()
    {
        SessionHelper::requireLogin();

        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getCartItems();

        if (empty($cartItems)) {
            $_SESSION['error'] = 'Giỏ hàng trống';
            header('Location: /cart');
            exit;
        }

        include __DIR__ . '/../views/user/checkout.php';
    }

    // Xử lý đặt hàng
    public function placeOrder()
    {
        SessionHelper::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /cart');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $carId = $_POST['car_id'] ?? null;
        $paymentMethod = $_POST['payment_method'] ?? null;
        $depositPercentage = isset($_POST['deposit_percentage']) ? intval($_POST['deposit_percentage']) : null;
        $notes = $_POST['notes'] ?? '';

        // Validate
        if (!$carId || !$paymentMethod) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: /checkout');
            exit;
        }

        // Lấy thông tin xe
        $car = $this->carModel->getById($carId);
        if (!$car) {
            $_SESSION['error'] = 'Xe không tồn tại';
            header('Location: /cart');
            exit;
        }

        // Kiểm tra xe còn hàng hay không
        $currentStock = $car['stock'] ?? 0;
        if ($car['status'] !== 'available' || $currentStock <= 0) {
            $_SESSION['error'] = 'Xe này đã hết hàng';
            header('Location: /cart');
            exit;
        }

        // Tính deposit nếu có
        $depositAmount = null;
        if ($paymentMethod === 'deposit' && $depositPercentage) {
            if ($depositPercentage < 10 || $depositPercentage > 50) {
                $_SESSION['error'] = 'Phần trăm đặt cọc phải từ 10% đến 50%';
                header('Location: /checkout');
                exit;
            }
            $depositAmount = ($car['price'] * $depositPercentage) / 100;
        }

        // Tạo đơn hàng
        $orderData = [
            'user_id' => $userId,
            'car_id' => $carId,
            'price' => $car['price'],
            'deposit_percentage' => $depositPercentage,
            'deposit_amount' => $depositAmount,
            'payment_method' => $paymentMethod,
            'status' => 'pending',
            'notes' => $notes
        ];

        $orderId = $this->orderModel->create($orderData);

        if ($orderId) {
            // Trừ số lượng tồn kho
            $newStock = max(0, $currentStock - 1);
            $updateData = ['stock' => $newStock];
            
            // Nếu hết hàng thì set status = 'sold'
            if ($newStock <= 0) {
                $updateData['status'] = 'sold';
            }
            
            $this->carModel->update($carId, $updateData);

            // Xóa xe khỏi giỏ hàng
            $this->cartModel->removeFromCart($carId);

            $_SESSION['success'] = 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn sớm.';
            header('Location: /order/' . $orderId);
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra. Vui lòng thử lại.';
            header('Location: /checkout');
        }
        exit;
    }

    // Hiển thị chi tiết đơn hàng
    public function showOrder($orderId)
    {
        SessionHelper::requireLogin();

        $order = $this->orderModel->getOrderWithDetails($orderId);

        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: /orders');
            exit;
        }

        // Kiểm tra quyền xem đơn hàng
        if ($order['user_id'] != $_SESSION['user_id'] && $_SESSION['role'] !== 'admin') {
            $_SESSION['error'] = 'Bạn không có quyền xem đơn hàng này';
            header('Location: /orders');
            exit;
        }

        include __DIR__ . '/../views/user/order_detail.php';
    }

    // Hiển thị danh sách đơn hàng của user
    public function showMyOrders()
    {
        SessionHelper::requireLogin();

        $userId = $_SESSION['user_id'];
        $orders = $this->orderModel->getByUserId($userId);

        include __DIR__ . '/../views/user/orders.php';
    }

    // Hủy đơn hàng
    public function cancelOrder($orderId)
    {
        SessionHelper::requireLogin();

        $order = $this->orderModel->findById($orderId);

        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: /orders');
            exit;
        }

        // Kiểm tra quyền hủy
        if ($order['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Bạn không có quyền hủy đơn hàng này';
            header('Location: /orders');
            exit;
        }

        // Chỉ cho phép hủy đơn hàng pending
        if ($order['status'] !== 'pending') {
            $_SESSION['error'] = 'Không thể hủy đơn hàng đã được xác nhận';
            header('Location: /orders');
            exit;
        }

        // Khôi phục số lượng tồn kho khi hủy đơn
        $car = $this->carModel->getById($order['car_id']);
        if ($car) {
            $currentStock = $car['stock'] ?? 0;
            $newStock = $currentStock + 1;
            
            $updateData = ['stock' => $newStock];
            // Nếu xe đang hết hàng thì set lại available
            if ($car['status'] === 'sold') {
                $updateData['status'] = 'available';
            }
            
            $this->carModel->update($order['car_id'], $updateData);
        }

        $this->orderModel->updateStatus($orderId, 'cancelled');

        $_SESSION['success'] = 'Đã hủy đơn hàng thành công';
        header('Location: /orders');
        exit;
    }

    // ===== ADMIN FUNCTIONS =====

    // Hiển thị danh sách đơn hàng (Admin)
    public function adminList()
    {
        SessionHelper::requireAdmin();
        include __DIR__ . '/../views/admin/orders/list.php';
    }

    // Cập nhật trạng thái đơn hàng (Admin)
    public function adminUpdateStatus($orderId, $status)
    {
        SessionHelper::requireAdmin();

        $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['error'] = 'Trạng thái không hợp lệ';
            header('Location: ' . BASE_URL . '/admin/orders');
            exit;
        }

        $order = $this->orderModel->findById($orderId);
        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ' . BASE_URL . '/admin/orders');
            exit;
        }

        // Khôi phục số lượng tồn kho xe nếu đơn hàng bị hủy (admin)
        // Lưu ý: Stock đã được trừ khi user đặt hàng, nên chỉ cần khôi phục khi hủy
        if ($status === 'cancelled' && $order['status'] !== 'cancelled') {
            // Lấy thông tin xe hiện tại
            $car = $this->carModel->getById($order['car_id']);
            if ($car) {
                $currentStock = $car['stock'] ?? 0;
                $newStock = $currentStock + 1; // Cộng lại 1

                // Cập nhật stock và đảm bảo status là available nếu có hàng
                $updateData = ['stock' => $newStock];
                if ($car['status'] === 'sold') {
                    $updateData['status'] = 'available';
                }

                $this->carModel->update($order['car_id'], $updateData);
            }
        }

        $this->orderModel->updateStatus($orderId, $status);

        $_SESSION['success'] = 'Đã cập nhật trạng thái đơn hàng thành công';
        header('Location: ' . BASE_URL . '/admin/orders');
        exit;
    }

    // Xóa đơn hàng (Admin)
    public function adminDelete($orderId)
    {
        SessionHelper::requireAdmin();

        $order = $this->orderModel->findById($orderId);
        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ' . BASE_URL . '/admin/orders');
            exit;
        }

        // Khôi phục số lượng tồn kho xe nếu xóa đơn hàng pending (chưa bị hủy)
        if ($order['status'] !== 'cancelled') {
            $car = $this->carModel->getById($order['car_id']);
            if ($car) {
                $currentStock = $car['stock'] ?? 0;
                $newStock = $currentStock + 1;
                
                $updateData = ['stock' => $newStock];
                if ($car['status'] === 'sold') {
                    $updateData['status'] = 'available';
                }
                
                $this->carModel->update($order['car_id'], $updateData);
            }
        }

        $this->orderModel->delete($orderId);

        $_SESSION['success'] = 'Đã xóa đơn hàng thành công';
        header('Location: ' . BASE_URL . '/admin/orders');
        exit;
    }

    // Xem chi tiết đơn hàng (Admin)
    public function adminDetail($orderId)
    {
        SessionHelper::requireAdmin();

        $order = $this->orderModel->getOrderWithDetails($orderId);

        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại';
            header('Location: ' . BASE_URL . '/admin/orders');
            exit;
        }

        include __DIR__ . '/../views/admin/orders/detail.php';
    }
}
