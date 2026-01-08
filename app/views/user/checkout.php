<?php
$currentPage = 'checkout';
$pageTitle = 'Đặt Hàng - AutoCar';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: /login?redirect=/checkout');
    exit;
}

// Load Models
require_once __DIR__ . '/../../models/CartModel.php';
require_once __DIR__ . '/../../models/CarModel.php';

$cartModel = new CartModel();
$carModel = new CarModel();

// Get cart items
$cartItems = $cartModel->getCartItems();
if (empty($cartItems)) {
    header('Location: /cart');
    exit;
}

$cartTotal = $cartModel->getCartTotal();

include __DIR__ . '/../layouts/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cart.css">

<style>
    /* Banner */
    .checkout-banner {
        position: relative;
        height: 350px;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.5) 100%),
            url('https://images.unsplash.com/photo-1461988625982-7e46a099bf4f?w=1920&q=80') center/cover;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: -80px;
    }

    .checkout-banner-content {
        text-align: center;
        color: #fff;
        position: relative;
        z-index: 1;
        max-width: 800px;
        padding: 0 20px;
    }

    .checkout-banner h1 {
        font-family: 'Playfair Display', serif;
        font-size: 56px;
        font-weight: 700;
        margin-bottom: 15px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .checkout-banner h1 span {
        color: #D4AF37;
    }

    .checkout-banner p {
        font-size: 20px;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
    }

    .checkout-banner .step-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 24px;
        background: rgba(212, 175, 55, 0.2);
        border: 2px solid #D4AF37;
        border-radius: 25px;
        font-size: 16px;
        font-weight: 600;
        color: #D4AF37;
        backdrop-filter: blur(10px);
        margin-top: 12px;
    }

    .checkout-banner .step-badge svg {
        width: 20px;
        height: 20px;
    }

    .checkout-page {
        padding: 100px 0 80px;
        min-height: 100vh;
        background: linear-gradient(135deg, #f9f7f3 0%, #ffffff 100%);
    }

    .checkout-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .checkout-page .page-title {
        color: #000;
    }

    .checkout-content {
        display: grid;
        grid-template-columns: 1fr 400px;
        gap: 30px;
        margin-top: 30px;
    }

    .order-form {
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
    }

    .form-section {
        margin-bottom: 30px;
    }

    .form-section h3 {
        font-size: 18px;
        color: #333;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #D4AF37;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        color: #555;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: #D4AF37;
        box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
    }

    .payment-options {
        display: grid;
        gap: 15px;
    }

    .payment-option {
        position: relative;
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-option:hover {
        border-color: #D4AF37;
        background: rgba(212, 175, 55, 0.05);
    }

    .payment-option input[type="radio"] {
        position: absolute;
        opacity: 0;
    }

    .payment-option input[type="radio"]:checked+.payment-label {
        color: #D4AF37;
        font-weight: 600;
    }

    .payment-option input[type="radio"]:checked~.payment-option {
        border-color: #D4AF37;
        background: rgba(212, 175, 55, 0.1);
    }

    .payment-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        color: #333;
        cursor: pointer;
    }

    .payment-description {
        font-size: 13px;
        color: #666;
        margin-top: 8px;
        padding-left: 30px;
    }

    .deposit-options {
        display: none;
        margin-top: 15px;
        padding: 15px;
        background: rgba(212, 175, 55, 0.05);
        border-radius: 6px;
    }

    .deposit-options.active {
        display: block;
    }

    .deposit-presets {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-top: 10px;
    }

    .deposit-preset {
        padding: 10px;
        text-align: center;
        border: 2px solid #ddd;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .deposit-preset:hover {
        border-color: #D4AF37;
        background: rgba(212, 175, 55, 0.1);
    }

    .deposit-preset.active {
        border-color: #D4AF37;
        background: #D4AF37;
        color: #fff;
    }

    .deposit-amount {
        margin-top: 15px;
        padding: 15px;
        background: #f8f8f8;
        border-radius: 6px;
        text-align: center;
    }

    .deposit-amount-label {
        font-size: 13px;
        color: #666;
        margin-bottom: 5px;
    }

    .deposit-amount-value {
        font-size: 24px;
        font-weight: 700;
        color: #D4AF37;
    }

    .btn-submit-order {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        color: #000;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-submit-order:hover {
        background: linear-gradient(135deg, #F4CF57 0%, #D4AF37 100%);
        box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
        transform: translateY(-2px);
    }

    @media (max-width: 968px) {
        .checkout-content {
            grid-template-columns: 1fr;
        }

        .deposit-presets {
            grid-template-columns: repeat(3, 1fr);
        }
    }
</style>

<!-- Banner -->
<div class="checkout-banner">
    <div class="checkout-banner-content">
        <h1>Thanh Toán <span>Đơn Hàng</span></h1>
        <p>Hoàn tất đơn hàng của bạn chỉ với vài bước đơn giản</p>
        <div class="step-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
            </svg>
            Bước cuối cùng
        </div>
    </div>
</div>

<main class="checkout-page">
    <div class="checkout-container">
        <h1 class="page-title">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                <line x1="1" y1="10" x2="23" y2="10"></line>
            </svg>
            Xác Nhận Đặt Hàng
        </h1>

        <div class="checkout-content">
            <!-- Order Form -->
            <div class="order-form">
                <form method="POST" action="/order/place" id="checkoutForm">
                    <!-- Chọn xe -->
                    <div class="form-section">
                        <h3>Chọn Xe</h3>
                        <div class="form-group">
                            <select name="car_id" id="carSelect" required onchange="updateTotal()">
                                <option value="">-- Chọn xe --</option>
                                <?php foreach ($cartItems as $item): ?>
                                    <option value="<?= $item['id'] ?>" data-price="<?= $item['price'] ?>">
                                        <?= htmlspecialchars($item['brand_name'] . ' ' . $item['name'] . ' - ' . number_format($item['price'], 0, ',', '.')) ?> ₫
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Phương thức thanh toán -->
                    <div class="form-section">
                        <h3>Phương Thức Thanh Toán</h3>
                        <div class="payment-options">
                            <div class="payment-option">
                                <input type="radio" name="payment_method" value="deposit" id="payment_deposit" required onchange="toggleDeposit()">
                                <label class="payment-label" for="payment_deposit">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M12 6v6l4 2"></path>
                                    </svg>
                                    Đặt Cọc (10% - 50%)
                                </label>
                                <div class="payment-description">Đặt cọc để giữ xe, thanh toán phần còn lại khi nhận xe</div>

                                <div class="deposit-options" id="depositOptions">
                                    <label>Chọn phần trăm đặt cọc:</label>
                                    <div class="deposit-presets">
                                        <div class="deposit-preset" data-percent="10" onclick="selectDeposit(10)">10%</div>
                                        <div class="deposit-preset" data-percent="20" onclick="selectDeposit(20)">20%</div>
                                        <div class="deposit-preset" data-percent="30" onclick="selectDeposit(30)">30%</div>
                                        <div class="deposit-preset" data-percent="40" onclick="selectDeposit(40)">40%</div>
                                        <div class="deposit-preset" data-percent="50" onclick="selectDeposit(50)">50%</div>
                                    </div>
                                    <input type="hidden" name="deposit_percentage" id="depositPercentage" value="">
                                    <div class="deposit-amount">
                                        <div class="deposit-amount-label">Số tiền cọc:</div>
                                        <div class="deposit-amount-value" id="depositAmount">0 ₫</div>
                                    </div>
                                </div>
                            </div>

                            <div class="payment-option">
                                <input type="radio" name="payment_method" value="bank_transfer" id="payment_bank" required onchange="toggleDeposit()">
                                <label class="payment-label" for="payment_bank">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                                        <line x1="1" y1="10" x2="23" y2="10"></line>
                                    </svg>
                                    Chuyển Khoản Ngân Hàng
                                </label>
                                <div class="payment-description">Thanh toán toàn bộ qua chuyển khoản ngân hàng</div>
                            </div>

                            <div class="payment-option">
                                <input type="radio" name="payment_method" value="cash" id="payment_cash" required onchange="toggleDeposit()">
                                <label class="payment-label" for="payment_cash">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    Tiền Mặt
                                </label>
                                <div class="payment-description">Thanh toán bằng tiền mặt khi nhận xe</div>
                            </div>
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="form-section">
                        <h3>Ghi Chú</h3>
                        <div class="form-group">
                            <textarea name="notes" rows="4" placeholder="Ghi chú thêm về đơn hàng (không bắt buộc)"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit-order">
                        Xác Nhận Đặt Hàng
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="cart-summary">
                <div class="summary-card">
                    <h3 class="summary-title">Thông Tin Đơn Hàng</h3>

                    <div class="summary-row">
                        <span>Người đặt:</span>
                        <span class="value"><?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?></span>
                    </div>

                    <div class="summary-row">
                        <span>Email:</span>
                        <span class="value"><?= htmlspecialchars($_SESSION['email'] ?? 'N/A') ?></span>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-row total">
                        <span>Tổng tiền xe:</span>
                        <span class="value" id="totalPrice">0 ₫</span>
                    </div>

                    <p class="note">
                        * Chúng tôi sẽ liên hệ xác nhận đơn hàng trong vòng 24h
                    </p>

                    <!-- Contact Box -->
                    <div class="contact-box">
                        <h4>Cần hỗ trợ?</h4>
                        <p>Liên hệ ngay với chúng tôi</p>
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
    </div>
</main>

<script>
    function updateTotal() {
        const select = document.getElementById('carSelect');
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price') || 0;

        document.getElementById('totalPrice').textContent = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(price);

        // Update deposit if active
        const depositPercentage = document.getElementById('depositPercentage').value;
        if (depositPercentage) {
            const depositAmount = (price * depositPercentage) / 100;
            document.getElementById('depositAmount').textContent = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(depositAmount);
        }
    }

    function toggleDeposit() {
        const depositRadio = document.getElementById('payment_deposit');
        const depositOptions = document.getElementById('depositOptions');

        if (depositRadio.checked) {
            depositOptions.classList.add('active');
        } else {
            depositOptions.classList.remove('active');
            document.getElementById('depositPercentage').value = '';
            document.querySelectorAll('.deposit-preset').forEach(preset => preset.classList.remove('active'));
        }
    }

    function selectDeposit(percent) {
        document.getElementById('depositPercentage').value = percent;

        // Update UI
        document.querySelectorAll('.deposit-preset').forEach(preset => {
            if (parseInt(preset.getAttribute('data-percent')) === percent) {
                preset.classList.add('active');
            } else {
                preset.classList.remove('active');
            }
        });

        // Calculate deposit amount
        const select = document.getElementById('carSelect');
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price') || 0;
        const depositAmount = (price * percent) / 100;

        document.getElementById('depositAmount').textContent = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(depositAmount);
    }

    // Validation
    document.getElementById('checkoutForm').addEventListener('submit', function(e) {
        const depositRadio = document.getElementById('payment_deposit');
        const depositPercentage = document.getElementById('depositPercentage').value;

        if (depositRadio.checked && !depositPercentage) {
            e.preventDefault();
            alert('Vui lòng chọn phần trăm đặt cọc');
            return false;
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        updateTotal();
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>