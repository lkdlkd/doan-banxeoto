<?php
$currentPage = 'orders';
$pageTitle = 'Chi Tiết Đơn Hàng #' . $order['id'] . ' - AutoCar';

include __DIR__ . '/../layouts/header.php';
?>

<style>
/* Banner */
.order-detail-banner {
    position: relative;
    height: 280px;
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), 
                url('https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=1920&q=80') center/cover;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: -60px;
}

.order-detail-banner-content {
    text-align: center;
    color: #fff;
    position: relative;
    z-index: 1;
}

.order-detail-banner h1 {
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.order-detail-banner h1 span {
    color: #D4AF37;
}

.order-detail-banner p {
    font-size: 18px;
    color: rgba(255,255,255,0.9);
    text-shadow: 0 1px 5px rgba(0,0,0,0.3);
}

/* Page Styling */
.order-detail-page {
    background: linear-gradient(135deg, #f9f7f3 0%, #f5f2ed 100%);
    min-height: 100vh;
    padding-bottom: 80px;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 30px;
    position: relative;
    z-index: 2;
}

/* Alert Messages */
.alert {
    padding: 18px 25px;
    border-radius: 12px;
    margin-bottom: 25px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.alert-success {
    background: linear-gradient(135deg, rgba(34,197,94,0.15), rgba(34,197,94,0.1));
    color: #16a34a;
    border: 2px solid rgba(34,197,94,0.3);
}

.alert-error {
    background: linear-gradient(135deg, rgba(239,68,68,0.15), rgba(239,68,68,0.1));
    color: #dc2626;
    border: 2px solid rgba(239,68,68,0.3);
}

/* Order Header */
.order-header {
    background: #fff;
    padding: 35px 40px;
    border-radius: 16px;
    margin-bottom: 30px;
    border: 2px solid rgba(212, 175, 55, 0.2);
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.order-title-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 25px;
}

.order-title {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    color: #1a1a1a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 15px;
}

.order-title svg {
    color: #D4AF37;
}

.order-number {
    color: #D4AF37;
}

/* Order Status */
.order-status {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 25px;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 13px;
    letter-spacing: 0.5px;
}

.status-pending {
    background: linear-gradient(135deg, rgba(251,191,36,0.2), rgba(251,191,36,0.15));
    color: #d97706;
    border: 2px solid rgba(251,191,36,0.4);
}

.status-confirmed {
    background: linear-gradient(135deg, rgba(59,130,246,0.2), rgba(59,130,246,0.15));
    color: #2563eb;
    border: 2px solid rgba(59,130,246,0.4);
}

.status-processing {
    background: linear-gradient(135deg, rgba(168,85,247,0.2), rgba(168,85,247,0.15));
    color: #9333ea;
    border: 2px solid rgba(168,85,247,0.4);
}

.status-completed {
    background: linear-gradient(135deg, rgba(34,197,94,0.2), rgba(34,197,94,0.15));
    color: #16a34a;
    border: 2px solid rgba(34,197,94,0.4);
}

.status-cancelled {
    background: linear-gradient(135deg, rgba(239,68,68,0.2), rgba(239,68,68,0.15));
    color: #dc2626;
    border: 2px solid rgba(239,68,68,0.4);
}

/* Order Info Grid */
.order-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.info-item {
    padding: 20px;
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    border-radius: 12px;
    border: 1px solid rgba(212, 175, 55, 0.15);
}

.info-item label {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-size: 13px;
    margin-bottom: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-item label svg {
    color: #D4AF37;
}

.info-item span {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a1a;
}

/* Order Sections */
.order-sections {
    display: grid;
    gap: 25px;
}

.order-section {
    background: #fff;
    padding: 35px 40px;
    border-radius: 16px;
    border: 2px solid rgba(212, 175, 55, 0.2);
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.section-title {
    font-family: 'Playfair Display', serif;
    color: #1a1a1a;
    font-size: 26px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
}

.section-title svg {
    color: #D4AF37;
    font-size: 24px;
}

/* Car Info */
.car-info {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 30px;
    align-items: start;
}

.car-image-wrapper {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid rgba(212, 175, 55, 0.3);
}

.car-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
}

.car-details h3 {
    font-family: 'Playfair Display', serif;
    color: #1a1a1a;
    font-size: 26px;
    margin-bottom: 10px;
    font-weight: 700;
}

.car-brand {
    color: #D4AF37;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
    margin-bottom: 20px;
    display: block;
}

/* Price Info */
.price-info {
    background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(212,175,55,0.05) 100%);
    padding: 25px;
    border-radius: 12px;
    margin-top: 20px;
    border: 1px solid rgba(212, 175, 55, 0.2);
}

.price-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    color: #333;
    font-size: 15px;
}

.price-row:last-child {
    margin-bottom: 0;
}

.price-row span:last-child {
    font-weight: 600;
    color: #1a1a1a;
}

.price-row.total {
    border-top: 2px solid #D4AF37;
    padding-top: 15px;
    margin-top: 15px;
    font-size: 18px;
    font-weight: 700;
}

.price-row.total span:first-child {
    color: #1a1a1a;
    font-family: 'Playfair Display', serif;
}

.price-row.total span:last-child {
    color: #D4AF37;
    font-size: 24px;
    font-family: 'Playfair Display', serif;
}

/* Payment Info Grid */
.payment-info-grid {
    display: grid;
    gap: 15px;
}

.payment-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 20px;
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    border-radius: 10px;
    border: 1px solid rgba(212, 175, 55, 0.15);
}

.payment-row label {
    color: #666;
    font-weight: 600;
    font-size: 14px;
}

.payment-row span {
    color: #1a1a1a;
    font-weight: 600;
    font-size: 15px;
}

.payment-highlight {
    color: #D4AF37 !important;
    font-size: 20px !important;
    font-family: 'Playfair Display', serif;
}

/* Notes Box */
.notes-box {
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    padding: 25px;
    border-radius: 12px;
    color: #333;
    line-height: 1.8;
    border: 1px solid rgba(212, 175, 55, 0.15);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    flex-wrap: wrap;
}

.btn {
    padding: 14px 28px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
    font-size: 15px;
}

.btn-primary {
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
}

.btn-secondary {
    background: #fff;
    color: #1a1a1a;
    border: 2px solid #D4AF37;
}

.btn-secondary:hover {
    background: rgba(212, 175, 55, 0.1);
    border-color: #B8860B;
}

.btn-danger {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
}

.btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
}

@media (max-width: 1024px) {
    .car-info {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .order-detail-banner h1 {
        font-size: 36px;
    }
    
    .order-header,
    .order-section {
        padding: 25px 20px;
    }

    .order-title {
        font-size: 24px;
    }
    
    .order-title-row {
        flex-direction: column;
        align-items: flex-start;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn {
        justify-content: center;
    }
}
</style>

<!-- Banner -->
<div class="order-detail-banner">
    <div class="order-detail-banner-content">
        <h1>Chi tiết <span>đơn hàng</span></h1>
        <p>Theo dõi và quản lý đơn hàng của bạn</p>
    </div>
</div>

<main class="order-detail-page">
    <div class="container">
        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Order Header -->
        <div class="order-header">
            <div class="order-title-row">
                <h1 class="order-title">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    Đơn Hàng <span class="order-number">#<?= $order['id'] ?></span>
                </h1>
                <span class="order-status status-<?= $order['status'] ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
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
            </div>

            <div class="order-info-grid">
                <div class="info-item">
                    <label>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Ngày đặt hàng
                    </label>
                    <span><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                </div>
                <div class="info-item">
                    <label>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Khách hàng
                    </label>
                    <span><?= htmlspecialchars($order['user_name']) ?></span>
                </div>
                <div class="info-item">
                    <label>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        Email
                    </label>
                    <span><?= htmlspecialchars($order['user_email']) ?></span>
                </div>
            </div>
        </div>

        <div class="order-sections">
            <!-- Car Information -->
            <div class="order-section">
                <h2 class="section-title">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.5 16c-.83 0-1.5-.67-1.5-1.5S5.67 13 6.5 13s1.5.67 1.5 1.5S7.33 16 6.5 16zm11 0c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zM5 11l1.5-4.5h11L19 11H5z"></path>
                    </svg>
                    Thông Tin Xe
                </h2>
                <div class="car-info">
                    <div class="car-image-wrapper">
                        <img src="<?= htmlspecialchars($order['car_image'] ?? 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=400') ?>" 
                             alt="<?= htmlspecialchars($order['car_name']) ?>" 
                             class="car-image">
                    </div>
                    <div class="car-details">
                        <span class="car-brand"><?= htmlspecialchars($order['brand_name']) ?></span>
                        <h3><?= htmlspecialchars($order['car_name']) ?></h3>
                        
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
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                        <line x1="1" y1="10" x2="23" y2="10"></line>
                    </svg>
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
                            <span class="payment-highlight"><?= number_format($order['deposit_amount']) ?> VNĐ</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="payment-row">
                        <label>Tổng giá trị đơn hàng:</label>
                        <span class="payment-highlight"><?= number_format($order['price']) ?> VNĐ</span>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <?php if (!empty($order['notes'])): ?>
            <div class="order-section">
                <h2 class="section-title">
                    <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
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
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Quay Lại Danh Sách
            </a>
            
            <?php if ($order['status'] === 'pending'): ?>
                <form method="POST" action="/order/cancel/<?= $order['id'] ?>" style="display: inline;" 
                      onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                    <button type="submit" class="btn btn-danger">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                        Hủy Đơn Hàng
                    </button>
                </form>
            <?php endif; ?>
            
            <a href="/contact" class="btn btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
                Liên Hệ Hỗ Trợ
            </a>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
