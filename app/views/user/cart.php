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

<!-- Cart Page Styles -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cart.css">

<main class="cart-page">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="/">Trang chủ</a>
            <span class="separator">/</span>
            <span class="current">Giỏ hàng</span>
        </nav>

        <h1 class="page-title">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            Giỏ Hàng Của Bạn
            <span class="item-count">(<?= $cartCount ?> xe)</span>
        </h1>

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
                        <img src="<?= $item['image_url'] ?? 'https://via.placeholder.com/300x200?text=No+Image' ?>" 
                             alt="<?= htmlspecialchars($item['name']) ?>">
                        <?php if ($item['status'] === 'sold'): ?>
                        <span class="sold-badge">Đã bán</span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="item-info">
                        <div class="item-brand">
                            <img src="<?= $item['brand_logo'] ?>" alt="<?= $item['brand_name'] ?>" class="brand-logo">
                            <span><?= htmlspecialchars($item['brand_name']) ?></span>
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
                                <?= $item['year'] ?>
                            </span>
                            <span class="detail">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <polyline points="12 6 12 12 16 14"></polyline>
                                </svg>
                                <?= number_format($item['mileage']) ?> km
                            </span>
                            <span class="detail">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                </svg>
                                <?= ucfirst($item['transmission']) ?>
                            </span>
                            <span class="detail category">
                                <?= htmlspecialchars($item['category_name']) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="item-price-action">
                        <div class="item-price">
                            <?= number_format($item['price'], 0, ',', '.') ?> ₫
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
                        <?php if (isset($_SESSION['user'])): ?>
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
