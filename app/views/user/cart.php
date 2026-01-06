<?php
$currentPage = 'cart';
$pageTitle = 'Giỏ Hàng - AutoCar';

// Load Cart Model
require_once __DIR__ . '/../../models/CartModel.php';
$cartModel = new CartModel();

// Xử lý action
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'remove' && isset($_GET['id'])) {
        $cartModel->removeFromCart($_GET['id']);
        header('Location: /cart');
        exit;
    }
    if ($_GET['action'] === 'clear') {
        $cartModel->clearCart();
        header('Location: /cart');
        exit;
    }
}

// Lấy danh sách xe trong giỏ hàng
$cartItems = $cartModel->getCartItems();
$cartTotal = $cartModel->getCartTotal();
$cartCount = $cartModel->getCartCount();

include __DIR__ . '/../layouts/header.php';
?>

<style>
/* Banner */
.cart-banner {
    position: relative;
    height: 280px;
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), 
                url('https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?w=1920&q=80') center/cover;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: -60px;
}

.cart-banner-content {
    text-align: center;
    color: #fff;
    position: relative;
    z-index: 1;
}

.cart-banner h1 {
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.cart-banner h1 span {
    color: #D4AF37;
}

.cart-banner p {
    font-size: 18px;
    color: rgba(255,255,255,0.9);
    text-shadow: 0 1px 5px rgba(0,0,0,0.3);
}

/* Page Styling */
.cart-page {
    background: linear-gradient(135deg, #f9f7f3 0%, #f5f2ed 100%);
    min-height: 100vh;
    padding-bottom: 80px;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 30px;
    position: relative;
    z-index: 2;
}

/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    padding: 25px 30px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.page-title {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #1a1a1a;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
}

.page-title svg {
    color: #D4AF37;
}

.item-count {
    font-size: 16px;
    color: #666;
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
}

/* Empty Cart */
.empty-cart {
    text-align: center;
    padding: 100px 40px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.empty-icon svg {
    color: rgba(212, 175, 55, 0.3);
    margin-bottom: 30px;
}

.empty-cart h2 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    color: #1a1a1a;
    margin-bottom: 15px;
}

.empty-cart p {
    color: #666;
    font-size: 16px;
    margin-bottom: 35px;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.btn-explore {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    text-decoration: none;
    font-weight: 700;
    border-radius: 8px;
    transition: all 0.3s;
}

.btn-explore:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
}

/* Cart Content */
.cart-content {
    display: grid;
    grid-template-columns: 1fr 420px;
    gap: 30px;
    align-items: start;
}

/* Cart Items */
.cart-items {
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.cart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid #f0f0f0;
}

.cart-label {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    font-weight: 700;
    color: #1a1a1a;
}

.btn-clear {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: transparent;
    border: 2px solid #ef4444;
    color: #ef4444;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s;
}

.btn-clear:hover {
    background: #ef4444;
    color: #fff;
}

/* Cart Item */
.cart-item {
    display: flex;
    gap: 25px;
    padding: 25px;
    margin-bottom: 20px;
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    border-radius: 12px;
    border: 1px solid rgba(212, 175, 55, 0.15);
    transition: all 0.3s;
}

.cart-item:hover {
    border-color: #D4AF37;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.15);
}

.cart-item:last-child {
    margin-bottom: 0;
}

.item-image {
    flex: 0 0 200px;
    height: 140px;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}

.cart-item:hover .item-image img {
    transform: scale(1.05);
}

.sold-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 6px 14px;
    background: rgba(239, 68, 68, 0.95);
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    border-radius: 6px;
    text-transform: uppercase;
}

/* Item Info */
.item-info {
    flex: 1;
}

.item-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.brand-logo {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 6px;
}

.item-brand span {
    font-size: 13px;
    color: #D4AF37;
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 1px;
}

.item-name {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    color: #1a1a1a;
    margin: 0 0 15px 0;
    font-weight: 700;
}

.item-name a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s;
}

.item-name a:hover {
    color: #D4AF37;
}

.item-details {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
}

.detail {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #666;
}

.detail svg {
    color: #D4AF37;
    flex-shrink: 0;
}

.detail.category {
    padding: 5px 12px;
    background: rgba(212, 175, 55, 0.1);
    border-radius: 6px;
    color: #B8860B;
    font-weight: 600;
}

/* Item Price & Action */
.item-price-action {
    flex: 0 0 auto;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    justify-content: space-between;
}

.item-price {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    font-weight: 700;
    color: #D4AF37;
    white-space: nowrap;
}

.btn-remove {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: transparent;
    border: 2px solid #ef4444;
    color: #ef4444;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    border-radius: 6px;
    transition: all 0.3s;
}

.btn-remove:hover {
    background: #ef4444;
    color: #fff;
    transform: translateY(-1px);
}

/* Cart Summary */
.cart-summary {
    position: sticky;
    top: 100px;
}

.summary-card {
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 2px solid rgba(212, 175, 55, 0.2);
}

.summary-title {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    color: #1a1a1a;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f0f0f0;
    font-weight: 700;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    color: #666;
    font-size: 15px;
}

.summary-row .value {
    font-weight: 600;
    color: #1a1a1a;
}

.summary-divider {
    height: 2px;
    background: linear-gradient(90deg, #D4AF37 0%, #B8860B 100%);
    margin: 20px 0;
}

.summary-row.total {
    font-size: 18px;
    color: #1a1a1a;
    font-weight: 700;
    margin-bottom: 0;
}

.summary-row.total .value {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    color: #D4AF37;
}

.note {
    font-size: 12px;
    color: #999;
    margin: 15px 0 25px 0;
    line-height: 1.6;
}

/* Summary Actions */
.summary-actions {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 25px;
}

.btn-checkout,
.btn-continue {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 14px 24px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 15px;
    text-decoration: none;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-checkout {
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
}

.btn-checkout:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
}

.btn-continue {
    background: #fff;
    color: #1a1a1a;
    border: 2px solid #D4AF37;
}

.btn-continue:hover {
    background: rgba(212, 175, 55, 0.1);
    border-color: #B8860B;
}

/* Contact Box */
.contact-box {
    padding: 25px;
    background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(212,175,55,0.05) 100%);
    border-radius: 12px;
    border: 2px solid rgba(212, 175, 55, 0.2);
}

.contact-box h4 {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.contact-box p {
    font-size: 14px;
    color: #666;
    margin-bottom: 15px;
    line-height: 1.5;
}

.phone-link {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    text-decoration: none;
    font-weight: 700;
    font-size: 15px;
    border-radius: 8px;
    transition: all 0.3s;
    width: 100%;
    justify-content: center;
}

.phone-link:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
}

@media (max-width: 1200px) {
    .cart-content {
        grid-template-columns: 1fr 380px;
    }
}

@media (max-width: 1024px) {
    .cart-content {
        grid-template-columns: 1fr;
    }
    
    .cart-summary {
        position: static;
    }
}

@media (max-width: 768px) {
    .cart-banner h1 {
        font-size: 36px;
    }
    
    .cart-item {
        flex-direction: column;
    }
    
    .item-image {
        flex: 0 0 auto;
        width: 100%;
        height: 200px;
    }
    
    .item-price-action {
        flex-direction: row;
        width: 100%;
        align-items: center;
        justify-content: space-between;
    }
}
</style>

<!-- Banner -->
<div class="cart-banner">
    <div class="cart-banner-content">
        <h1>Giỏ hàng <span>của bạn</span></h1>
        <p>Kiểm tra và hoàn tất đơn hàng của bạn</p>
    </div>
</div>

<main class="cart-page">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                Giỏ Hàng
                <span class="item-count">(<?= $cartCount ?> xe)</span>
            </h1>
        </div>

        <?php if (empty($cartItems)): ?>
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="empty-icon">
                <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
            </div>
            <h2>Giỏ hàng trống</h2>
            <p>Bạn chưa có xe nào trong giỏ hàng. Hãy khám phá bộ sưu tập xe cao cấp của chúng tôi!</p>
            <a href="/cars" class="btn-explore">
                Khám Phá Ngay
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <?php else: ?>
        <!-- Cart Content -->
        <div class="cart-content">
            <!-- Cart Items -->
            <div class="cart-items">
                <!-- Clear All Button -->
                <div class="cart-header">
                    <span class="cart-label">Danh sách xe đã chọn</span>
                    <a href="/cart?action=clear" class="btn-clear" onclick="return confirm('Bạn có chắc muốn xóa tất cả xe khỏi giỏ hàng?')">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Xóa tất cả
                    </a>
                </div>

                <?php foreach ($cartItems as $item): ?>
                <div class="cart-item" data-id="<?= $item['id'] ?>">
                    <div class="item-image">
                        <img src="<?= htmlspecialchars($item['image_url'] ?? 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=400') ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>">
                        <?php if (isset($item['status']) && $item['status'] === 'sold'): ?>
                        <span class="sold-badge">Đã bán</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="item-info">
                        <div class="item-brand">
                            <?php if (!empty($item['brand_logo'])): ?>
                                <img src="<?= htmlspecialchars($item['brand_logo']) ?>" alt="<?= htmlspecialchars($item['brand_name']) ?>" class="brand-logo">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($item['brand_name'] ?? 'Brand') ?>&background=D4AF37&color=000&size=50" alt="<?= htmlspecialchars($item['brand_name']) ?>" class="brand-logo">
                            <?php endif; ?>
                            <span><?= htmlspecialchars($item['brand_name'] ?? 'N/A') ?></span>
                        </div>
                        <h3 class="item-name">
                            <a href="/car/<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                        </h3>
                        <div class="item-details">
                            <span class="detail">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                    <line x1="16" y1="2" x2="16" y2="6"></line>
                                    <line x1="8" y1="2" x2="8" y2="6"></line>
                                    <line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <?= $item['year'] ?? 'N/A' ?>
                            </span>
                            <span class="detail">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <?= isset($item['mileage']) ? number_format($item['mileage']) : '0' ?> km
                            </span>
                            <span class="detail">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <?= $item['transmission'] == 'automatic' ? 'Tự động' : 'Số sàn' ?>
                            </span>
                            <span class="detail category">
                                <?= htmlspecialchars($item['category_name'] ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="item-price-action">
                        <div class="item-price">
                            <?= number_format($item['price'] ?? 0, 0, ',', '.') ?> ₫
                        </div>
                        <a href="/cart?action=remove&id=<?= $item['id'] ?>" class="btn-remove" 
                           onclick="return confirm('Bạn có chắc muốn xóa xe này khỏi giỏ hàng?')">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                            Xóa
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <div class="summary-card">
                    <h3 class="summary-title">Tổng Đơn Hàng</h3>
                    
                    <div class="summary-row">
                        <span>Số lượng xe:</span>
                        <span class="value"><?= $cartCount ?> xe</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Phí đăng ký xe:</span>
                        <span class="value">Tính sau</span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Phí trước bạ:</span>
                        <span class="value">Tính sau</span>
                    </div>
                    
                    <div class="summary-divider"></div>
                    
                    <div class="summary-row total">
                        <span>Tổng tiền xe:</span>
                        <span class="value"><?= number_format($cartTotal, 0, ',', '.') ?> ₫</span>
                    </div>
                    
                    <p class="note">
                        * Giá chưa bao gồm chi phí đăng ký, trước bạ và các phí phát sinh khác.
                    </p>
                    
                    <div class="summary-actions">
                        <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/checkout" class="btn-checkout">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                <line x1="1" y1="10" x2="23" y2="10"></line>
                            </svg>
                            Tiến Hành Đặt Hàng
                        </a>
                        <?php else: ?>
                        <a href="/login?redirect=/cart" class="btn-checkout">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg>
                            Đăng Nhập Để Đặt Hàng
                        </a>
                        <?php endif; ?>
                        
                        <a href="/cars" class="btn-continue">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Tiếp Tục Xem Xe
                        </a>
                    </div>
                    
                    <!-- Contact Box -->
                    <div class="contact-box">
                        <h4>Cần tư vấn?</h4>
                        <p>Đội ngũ tư vấn chuyên nghiệp sẵn sàng hỗ trợ bạn 24/7</p>
                        <a href="tel:0368920249" class="phone-link">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            Hotline: 0368 920 249
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
