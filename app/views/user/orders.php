<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../config/config.php'; }

// Load model
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';

$orderModel = new OrderModel();
$reviewModel = new ReviewModel();
$userId = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của user
$orders = $orderModel->getByUserId($userId);

$pageTitle = 'Đơn hàng của tôi - AutoCar';
$currentPage = 'orders';

include __DIR__ . '/../layouts/header.php';
?>

<style>
/* Banner */
.orders-banner {
    position: relative;
    height: 280px;
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), 
                url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=1920&q=80') center/cover;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: -60px;
}

.orders-banner-content {
    text-align: center;
    color: #fff;
    position: relative;
    z-index: 1;
}

.orders-banner h1 {
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.orders-banner h1 span {
    color: #D4AF37;
}

.orders-banner p {
    font-size: 18px;
    color: rgba(255,255,255,0.9);
    text-shadow: 0 1px 5px rgba(0,0,0,0.3);
}

.orders-page {
    background: linear-gradient(135deg, #f9f7f3 0%, #f5f2ed 100%);
    min-height: 100vh;
    padding-bottom: 80px;
}

.orders-container {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 30px;
    position: relative;
    z-index: 2;
}

.orders-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 40px;
}

.stat-box {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: all 0.3s;
}

.stat-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(212,175,55,0.2);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(212,175,55,0.2) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
}

.stat-icon i {
    font-size: 22px;
    color: #D4AF37;
}

.stat-number {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 13px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card {
    background: #fff;
    border: 1px solid rgba(212, 175, 55, 0.15);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.order-card:hover {
    border-color: #D4AF37;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(212,175,55,0.15);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 28px;
    background: linear-gradient(135deg, #f9f9f9 0%, #ffffff 100%);
    border-bottom: 1px solid rgba(212, 175, 55, 0.1);
}

.order-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: #1a1a1a;
    margin: 0 0 5px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.order-id {
    color: #D4AF37;
    font-weight: 600;
}

.order-info span {
    color: #666;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.order-info span i {
    color: #D4AF37;
}

.order-status {
    padding: 8px 18px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
    letter-spacing: 0.5px;
}

.order-status.pending {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.order-status.confirmed {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.order-status.completed {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.order-status.cancelled {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.order-body {
    padding: 28px;
}

.order-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-item {
    display: flex;
    gap: 20px;
    padding: 20px;
    background: #f9f9f9;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    transition: all 0.3s;
}

.order-item:hover {
    background: #fff;
    border-color: #D4AF37;
}

.order-item-image {
    width: 140px;
    height: 90px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.order-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.order-item:hover .order-item-image img {
    transform: scale(1.05);
}

.order-item-info {
    flex: 1;
}

.order-item-info h4 {
    color: #1a1a1a;
    font-size: 18px;
    font-weight: 700;
    margin: 0 0 8px 0;
    font-family: 'Montserrat', sans-serif;
    letter-spacing: -0.3px;
}

.order-item-info p {
    color: #666;
    font-size: 13px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 6px;
}

.order-item-info p i {
    color: #D4AF37;
    font-size: 12px;
}

.order-item-price {
    text-align: right;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.order-item-price .price {
    font-family: 'Montserrat', sans-serif;
    font-size: 22px;
    color: #D4AF37;
    font-weight: 800;
    letter-spacing: -0.5px;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 28px;
    border-top: 2px solid rgba(212, 175, 55, 0.15);
    background: linear-gradient(135deg, #fafafa 0%, #ffffff 100%);
}

.order-total {
    display: flex;
    align-items: center;
    gap: 12px;
}

.order-total span {
    color: #666;
    font-size: 15px;
    font-weight: 500;
}

.order-total .total-price {
    font-family: 'Montserrat', sans-serif;
    font-size: 28px;
    color: #D4AF37;
    font-weight: 900;
    letter-spacing: -0.5px;
}

.order-actions {
    display: flex;
    gap: 12px;
}

.btn-order {
    padding: 11px 24px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-order.primary {
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
}

.btn-order.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
}

.btn-order.secondary {
    background: #fff;
    border: 2px solid #e5e5e5;
    color: #666;
}

.btn-order.secondary:hover {
    border-color: #D4AF37;
    color: #D4AF37;
}

.btn-order.review {
    background: transparent;
    border: 2px solid #D4AF37;
    color: #D4AF37;
}

.btn-order.review:hover {
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
}

/* Empty State */
.empty-orders {
    text-align: center;
    padding: 100px 20px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.empty-orders i {
    font-size: 80px;
    color: rgba(212, 175, 55, 0.3);
    margin-bottom: 24px;
}

.empty-orders h3 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #1a1a1a;
    margin-bottom: 12px;
}

.empty-orders p {
    color: #666;
    margin-bottom: 30px;
    font-size: 15px;
}

.empty-orders .btn-explore {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    text-decoration: none;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.empty-orders .btn-explore:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
}

/* Alert Messages */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    font-weight: 500;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert i {
    font-size: 20px;
}

.alert-success {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.alert-error {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

@media (max-width: 768px) {
    .orders-banner h1 {
        font-size: 36px;
    }
    
    .orders-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .order-header {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    
    .order-item {
        flex-direction: column;
    }
    
    .order-item-image {
        width: 100%;
        height: 150px;
    }
    
    .order-footer {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
}
</style>

<!-- Banner -->
<div class="orders-banner">
    <div class="orders-banner-content">
        <h1>Đơn hàng <span>của tôi</span></h1>
        <p>Quản lý và theo dõi tất cả đơn hàng của bạn</p>
    </div>
</div>

<div class="orders-page">
    <div class="orders-container">
        <!-- Stats -->
        <?php
        $totalOrders = count($orders);
        $pendingCount = count(array_filter($orders, fn($o) => $o['status'] === 'pending'));
        $confirmedCount = count(array_filter($orders, fn($o) => $o['status'] === 'confirmed'));
        $completedCount = count(array_filter($orders, fn($o) => $o['status'] === 'completed'));
        ?>
        
        <div class="orders-stats">
            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-number"><?= $totalOrders ?></div>
                <div class="stat-label">Tổng đơn</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?= $pendingCount ?></div>
                <div class="stat-label">Chờ xử lý</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-number"><?= $confirmedCount ?></div>
                <div class="stat-label">Đã xác nhận</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-number"><?= $completedCount ?></div>
                <div class="stat-label">Hoàn thành</div>
            </div>
        </div>

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

        <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <i class="fas fa-shopping-bag"></i>
            <h3>Chưa có đơn hàng nào</h3>
            <p>Bạn chưa có đơn hàng nào. Hãy khám phá bộ sưu tập xe của chúng tôi!</p>
            <a href="/cars" class="btn-explore">
                <i class="fas fa-car"></i>
                Khám phá xe
            </a>
        </div>
        <?php else: ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
            <div class="order-card">
                <div class="order-header">
                    <div class="order-info">
                        <h3><span class="order-id">#<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></span></h3>
                        <span><i class="fas fa-calendar-alt"></i> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                    </div>
                    <span class="order-status <?= $order['status'] ?? 'pending' ?>">
                        <?php 
                        switch($order['status'] ?? 'pending') {
                            case 'pending': echo 'Chờ xử lý'; break;
                            case 'confirmed': echo 'Đã xác nhận'; break;
                            case 'completed': echo 'Hoàn thành'; break;
                            case 'cancelled': echo 'Đã hủy'; break;
                            default: echo 'Chờ xử lý';
                        }
                        ?>
                    </span>
                </div>
                <div class="order-body">
                    <div class="order-items">
                        <div class="order-item">
                            <div class="order-item-image">
                                <img src="<?= $order['car_image'] ?? 'https://via.placeholder.com/120x80' ?>" alt="">
                            </div>
                            <div class="order-item-info">
                                <h4><?= htmlspecialchars($order['car_name'] ?? 'Xe') ?></h4>
                                <p><i class="fas fa-tag"></i> <?= htmlspecialchars($order['brand_name'] ?? '') ?></p>
                            </div>
                            <div class="order-item-price">
                                <span class="price"><?= number_format($order['car_price'] ?? 0, 0, ',', '.') ?> VNĐ</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="order-total">
                        <span>Tổng cộng:</span>
                        <span class="total-price"><?= number_format($order['price'] ?? 0, 0, ',', '.') ?>₫</span>
                    </div>
                    <div class="order-actions">
                        <a href="/order/<?= $order['id'] ?>" class="btn-order primary">Xem chi tiết</a>
                        <?php if (($order['status'] ?? 'pending') === 'pending'): ?>
                        <form method="POST" action="/order/cancel/<?= $order['id'] ?>" style="display: inline;" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                            <button type="submit" class="btn-order secondary">Hủy đơn</button>
                        </form>
                        <?php elseif (in_array($order['status'] ?? '', ['confirmed', 'completed'])): ?>
                            <?php 
                            // Kiểm tra đã đánh giá chưa
                            $hasReviewed = $reviewModel->hasUserReviewed($userId, $order['car_id']);
                            if (!$hasReviewed): 
                            ?>
                            <a href="<?= BASE_URL ?>/review/create/<?= $order['car_id'] ?>" class="btn-order review">
                                <i class="fas fa-star"></i> Đánh giá
                            </a>
                            <?php else: ?>
                            <span class="btn-order" style="cursor: default; opacity: 0.5;">
                                <i class="fas fa-check"></i> Đã đánh giá
                            </span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
