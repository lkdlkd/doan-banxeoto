<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../config/config.php'; }

// Load model
require_once __DIR__ . '/../../models/OrderModel.php';

$orderModel = new OrderModel();
$userId = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của user
$orders = $orderModel->getByUserId($userId);

$pageTitle = 'Đơn hàng của tôi - AutoCar';
$currentPage = 'orders';

include __DIR__ . '/../layouts/header.php';
?>

<style>
.orders-page {
    padding: 120px 0 80px;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    min-height: 100vh;
}

.orders-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.orders-header {
    text-align: center;
    margin-bottom: 50px;
}

.orders-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 42px;
    color: #fff;
    margin-bottom: 10px;
}

.orders-header h1 span {
    color: #D4AF37;
}

.orders-header p {
    color: rgba(255,255,255,0.6);
    font-size: 16px;
}

.orders-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.order-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.order-card:hover {
    border-color: rgba(212, 175, 55, 0.5);
    transform: translateY(-2px);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: rgba(212, 175, 55, 0.05);
    border-bottom: 1px solid rgba(212, 175, 55, 0.1);
}

.order-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: #D4AF37;
    margin: 0 0 5px 0;
}

.order-info span {
    color: rgba(255,255,255,0.5);
    font-size: 13px;
}

.order-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
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
    padding: 24px;
}

.order-items {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.order-item {
    display: flex;
    gap: 16px;
    padding: 16px;
    background: rgba(255,255,255,0.02);
    border-radius: 12px;
}

.order-item-image {
    width: 120px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.order-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-item-info {
    flex: 1;
}

.order-item-info h4 {
    color: #fff;
    font-size: 16px;
    margin: 0 0 8px 0;
}

.order-item-info p {
    color: rgba(255,255,255,0.5);
    font-size: 13px;
    margin: 0;
}

.order-item-price {
    text-align: right;
}

.order-item-price .price {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: #D4AF37;
    font-weight: 600;
}

.order-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-top: 1px solid rgba(212, 175, 55, 0.1);
    background: rgba(0,0,0,0.2);
}

.order-total {
    display: flex;
    align-items: center;
    gap: 10px;
}

.order-total span {
    color: rgba(255,255,255,0.6);
    font-size: 14px;
}

.order-total .total-price {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    color: #D4AF37;
    font-weight: 700;
}

.order-actions {
    display: flex;
    gap: 10px;
}

.btn-order {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-order.primary {
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
}

.btn-order.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(212, 175, 55, 0.3);
}

.btn-order.secondary {
    background: transparent;
    border: 1px solid rgba(255,255,255,0.2);
    color: #fff;
}

.btn-order.secondary:hover {
    border-color: #D4AF37;
    color: #D4AF37;
}

/* Empty State */
.empty-orders {
    text-align: center;
    padding: 80px 20px;
}

.empty-orders i {
    font-size: 80px;
    color: rgba(212, 175, 55, 0.3);
    margin-bottom: 24px;
}

.empty-orders h3 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #fff;
    margin-bottom: 12px;
}

.empty-orders p {
    color: rgba(255,255,255,0.5);
    margin-bottom: 30px;
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

@media (max-width: 768px) {
    .orders-header h1 {
        font-size: 32px;
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
    }
}
</style>

<div class="orders-page">
    <div class="orders-container">
        <div class="orders-header">
            <h1>Đơn hàng <span>của tôi</span></h1>
            <p>Quản lý và theo dõi tất cả đơn hàng của bạn</p>
        </div>

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
                        <h3>Đơn hàng #<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></h3>
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
                                <p><?= htmlspecialchars($order['brand_name'] ?? '') ?></p>
                            </div>
                            <div class="order-item-price">
                                <span class="price"><?= number_format($order['total_amount'] ?? 0, 0, ',', '.') ?>₫</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-footer">
                    <div class="order-total">
                        <span>Tổng cộng:</span>
                        <span class="total-price"><?= number_format($order['total_amount'] ?? 0, 0, ',', '.') ?>₫</span>
                    </div>
                    <div class="order-actions">
                        <a href="/order/<?= $order['id'] ?>" class="btn-order primary">Xem chi tiết</a>
                        <?php if (($order['status'] ?? 'pending') === 'pending'): ?>
                        <button class="btn-order secondary" onclick="cancelOrder(<?= $order['id'] ?>)">Hủy đơn</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        window.location.href = '/order/cancel/' + orderId;
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
