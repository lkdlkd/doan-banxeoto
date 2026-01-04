<?php
// app/views/user/order_detail.php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../helpers/SessionHelper.php';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Đơn Hàng #<?= $order['id'] ?> - AutoCar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        .order-detail-container {
            max-width: 1000px;
            margin: 150px auto 50px;
            padding: 0 20px;
        }

        .order-header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            border: 1px solid #D4AF37;
        }

        .order-header h1 {
            color: #D4AF37;
            font-size: 32px;
            margin-bottom: 15px;
        }

        .order-status {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 14px;
        }

        .status-pending {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: #000;
        }

        .status-confirmed {
            background: linear-gradient(135deg, #2196F3 0%, #0d8bf2 100%);
            color: #fff;
        }

        .status-processing {
            background: linear-gradient(135deg, #9C27B0 0%, #7B1FA2 100%);
            color: #fff;
        }

        .status-completed {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            color: #fff;
        }

        .status-cancelled {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: #fff;
        }

        .order-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .info-item {
            color: #e0e0e0;
        }

        .info-item label {
            display: block;
            color: #999;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-item span {
            font-size: 16px;
            font-weight: 500;
        }

        .order-sections {
            display: grid;
            gap: 30px;
        }

        .order-section {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #333;
        }

        .section-title {
            color: #D4AF37;
            font-size: 24px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            font-size: 20px;
        }

        .car-info {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 25px;
            align-items: start;
        }

        .car-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #D4AF37;
        }

        .car-details h3 {
            color: #D4AF37;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .car-brand {
            color: #999;
            font-size: 16px;
            margin-bottom: 15px;
        }

        .price-info {
            background: rgba(212, 175, 55, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-top: 15px;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #e0e0e0;
        }

        .price-row.total {
            border-top: 2px solid #D4AF37;
            padding-top: 15px;
            margin-top: 15px;
            font-size: 20px;
            font-weight: 700;
            color: #D4AF37;
        }

        .payment-info-grid {
            display: grid;
            gap: 15px;
        }

        .payment-row {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }

        .payment-row label {
            color: #999;
        }

        .payment-row span {
            color: #e0e0e0;
            font-weight: 500;
        }

        .notes-box {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            color: #e0e0e0;
            line-height: 1.6;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #D4AF37 0%, #B8941F 100%);
            color: #000;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #333 0%, #444 100%);
            color: #fff;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #444 0%, #555 100%);
        }

        .btn-danger {
            background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%);
            color: #fff;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(244, 67, 54, 0.4);
        }

        @media (max-width: 768px) {
            .car-info {
                grid-template-columns: 1fr;
            }

            .order-header h1 {
                font-size: 24px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <div class="order-detail-container">
        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Order Header -->
        <div class="order-header">
            <h1><i class="fas fa-file-invoice"></i> Đơn Hàng #<?= $order['id'] ?></h1>
            <span class="order-status status-<?= $order['status'] ?>">
                <?php
                $statusLabels = [
                    'pending' => 'Chờ xác nhận',
                    'confirmed' => 'Đã xác nhận',
                    'processing' => 'Đang xử lý',
                    'completed' => 'Hoàn thành',
                    'cancelled' => 'Đã hủy'
                ];
                echo $statusLabels[$order['status']] ?? $order['status'];
                ?>
            </span>

            <div class="order-info-grid">
                <div class="info-item">
                    <label><i class="fas fa-calendar-alt"></i> Ngày đặt hàng:</label>
                    <span><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-user"></i> Khách hàng:</label>
                    <span><?= htmlspecialchars($order['user_name']) ?></span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-envelope"></i> Email:</label>
                    <span><?= htmlspecialchars($order['user_email']) ?></span>
                </div>
            </div>
        </div>

        <div class="order-sections">
            <!-- Car Information -->
            <div class="order-section">
                <h2 class="section-title">
                    <i class="fas fa-car"></i>
                    Thông Tin Xe
                </h2>
                <div class="car-info">
                    <img src="<?= htmlspecialchars($order['car_image'] ?? '/assets/images/default-car.jpg') ?>" 
                         alt="<?= htmlspecialchars($order['car_name']) ?>" 
                         class="car-image">
                    <div class="car-details">
                        <h3><?= htmlspecialchars($order['car_name']) ?></h3>
                        <p class="car-brand"><?= htmlspecialchars($order['brand_name']) ?></p>
                        
                        <div class="price-info">
                            <div class="price-row">
                                <span>Giá xe:</span>
                                <span><?= number_format($order['car_price']) ?> VNĐ</span>
                            </div>
                            
                            <?php if ($order['payment_method'] === 'deposit' && !empty($order['deposit_percentage'])): ?>
                                <div class="price-row">
                                    <span>Tiền cọc (<?= $order['deposit_percentage'] ?>%):</span>
                                    <span><?= number_format($order['deposit_amount']) ?> VNĐ</span>
                                </div>
                                <div class="price-row">
                                    <span>Còn lại:</span>
                                    <span><?= number_format($order['price'] - $order['deposit_amount']) ?> VNĐ</span>
                                </div>
                            <?php endif; ?>
                            
                            <div class="price-row total">
                                <span>Tổng thanh toán:</span>
                                <span><?= number_format($order['price']) ?> VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="order-section">
                <h2 class="section-title">
                    <i class="fas fa-credit-card"></i>
                    Thông Tin Thanh Toán
                </h2>
                <div class="payment-info-grid">
                    <div class="payment-row">
                        <label>Phương thức thanh toán:</label>
                        <span>
                            <?php
                            $paymentLabels = [
                                'deposit' => 'Đặt cọc',
                                'bank_transfer' => 'Chuyển khoản',
                                'cash' => 'Tiền mặt'
                            ];
                            echo $paymentLabels[$order['payment_method']] ?? $order['payment_method'];
                            ?>
                        </span>
                    </div>
                    
                    <?php if ($order['payment_method'] === 'deposit' && !empty($order['deposit_percentage'])): ?>
                        <div class="payment-row">
                            <label>Tỷ lệ đặt cọc:</label>
                            <span><?= $order['deposit_percentage'] ?>%</span>
                        </div>
                        <div class="payment-row">
                            <label>Số tiền đặt cọc:</label>
                            <span style="color: #D4AF37; font-size: 18px;"><?= number_format($order['deposit_amount']) ?> VNĐ</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="payment-row">
                        <label>Tổng giá trị đơn hàng:</label>
                        <span style="color: #D4AF37; font-size: 18px; font-weight: 700;">
                            <?= number_format($order['price']) ?> VNĐ
                        </span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if (!empty($order['notes'])): ?>
            <div class="order-section">
                <h2 class="section-title">
                    <i class="fas fa-sticky-note"></i>
                    Ghi Chú
                </h2>
                <div class="notes-box">
                    <?= nl2br(htmlspecialchars($order['notes'])) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="/orders" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i>
                Quay Lại Danh Sách
            </a>
            
            <?php if ($order['status'] === 'pending'): ?>
                <form method="POST" action="/order/cancel/<?= $order['id'] ?>" style="display: inline;" 
                      onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle"></i>
                        Hủy Đơn Hàng
                    </button>
                </form>
            <?php endif; ?>
            
            <a href="/contact" class="btn btn-primary">
                <i class="fas fa-phone"></i>
                Liên Hệ Hỗ Trợ
            </a>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
