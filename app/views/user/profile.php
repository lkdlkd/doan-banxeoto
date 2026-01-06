<?php
$pageTitle = 'Tài khoản - AutoCar';
require_once __DIR__ . '/../layouts/header.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// Load models
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/OrderModel.php';

$userModel = new UserModel();
$orderModel = new OrderModel();

$user = $userModel->getUserById($_SESSION['user_id']);
$orders = $orderModel->getOrdersByUser($_SESSION['user_id']);

if (!$user) {
    header('Location: /login');
    exit;
}

// Đếm số lượng
$totalOrders = count($orders);
$pendingOrders = count(array_filter($orders, fn($o) => $o['status'] === 'pending'));
$completedOrders = count(array_filter($orders, fn($o) => $o['status'] === 'completed'));
?>

<style>
    /* Profile Banner */
    .profile-banner {
        position: relative;
        height: 300px;
        background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), 
                    url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=1920&q=80') center/cover;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: -80px;
    }

    .profile-banner-content {
        text-align: center;
        color: #fff;
        position: relative;
        z-index: 1;
    }

    .profile-banner h1 {
        font-family: 'Playfair Display', serif;
        font-size: 48px;
        font-weight: 700;
        margin-bottom: 10px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    .profile-banner p {
        font-size: 18px;
        color: rgba(255,255,255,0.9);
        text-shadow: 0 1px 5px rgba(0,0,0,0.3);
    }

    .profile-container {
        max-width: 1200px;
        margin: 0 auto 60px;
        padding: 0 30px;
        position: relative;
        z-index: 2;
    }

    .profile-grid {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 30px;
    }

    /* Sidebar */
    .profile-sidebar {
        background: #fff;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        height: fit-content;
    }

    .profile-avatar-section {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 25px;
        border-bottom: 1px solid #f0f0f0;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        box-shadow: 0 4px 15px rgba(212,175,55,0.2);
    }

    .profile-avatar i {
        font-size: 50px;
        color: #000;
    }

    .profile-name {
        font-family: 'Playfair Display', serif;
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #1a1a1a;
    }

    .profile-email {
        font-size: 13px;
        color: #666;
    }

    .profile-menu {
        list-style: none;
    }

    .profile-menu li {
        margin-bottom: 5px;
    }

    .profile-menu a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        color: #666;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        transition: all 0.3s;
    }

    .profile-menu a:hover,
    .profile-menu a.active {
        background: rgba(212, 175, 55, 0.1);
        color: #D4AF37;
    }

    .profile-menu i {
        width: 18px;
        text-align: center;
    }

    /* Main Content */
    .profile-main {
        background: #fff;
        border-radius: 12px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 28px;
        margin-bottom: 30px;
        color: #1a1a1a;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-title i {
        color: #D4AF37;
    }

    /* Tabs */
    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Form */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 25px;
        margin-bottom: 30px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        color: #333;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        background: #f9f9f9;
        border: 2px solid #e5e5e5;
        border-radius: 8px;
        color: #1a1a1a;
        font-size: 14px;
        font-family: 'Montserrat', sans-serif;
        transition: all 0.3s;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #D4AF37;
        background: #fff;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: flex-end;
        padding-top: 20px;
        border-top: 1px solid #f0f0f0;
    }

    .btn {
        padding: 12px 30px;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        color: #000;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #666;
    }

    .btn-secondary:hover {
        background: #e5e5e5;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: linear-gradient(135deg, #f9f9f9 0%, #ffffff 100%);
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        padding: 25px;
        text-align: center;
        transition: all 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border-color: #D4AF37;
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
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 12px;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Quick Links */
    .quick-links {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .quick-link {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 20px;
        background: #f9f9f9;
        border: 1px solid #e5e5e5;
        border-radius: 12px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s;
    }

    .quick-link:hover {
        background: #fff;
        border-color: #D4AF37;
        transform: translateX(5px);
    }

    .quick-link-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(212,175,55,0.1) 0%, rgba(212,175,55,0.2) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quick-link-icon i {
        font-size: 18px;
        color: #D4AF37;
    }

    .quick-link-content h4 {
        font-size: 15px;
        margin-bottom: 3px;
        color: #1a1a1a;
    }

    .quick-link-content p {
        font-size: 12px;
        color: #666;
        margin: 0;
    }

    /* Orders Table */
    .orders-table {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .orders-table thead {
        background: #f9f9f9;
    }

    .orders-table th {
        padding: 15px;
        text-align: left;
        font-size: 12px;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e5e5;
    }

    .orders-table td {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        font-size: 14px;
    }

    .orders-table tbody tr {
        transition: all 0.3s;
    }

    .orders-table tbody tr:hover {
        background: #f9f9f9;
    }

    .order-car {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .order-car-img {
        width: 60px;
        height: 40px;
        border-radius: 6px;
        object-fit: cover;
    }

    .order-car-name {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 3px;
    }

    .order-car-brand {
        font-size: 12px;
        color: #666;
    }

    .order-status {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .status-pending {
        background: rgba(255, 193, 7, 0.1);
        color: #ff9800;
    }

    .status-confirmed {
        background: rgba(33, 150, 243, 0.1);
        color: #2196F3;
    }

    .status-completed {
        background: rgba(76, 175, 80, 0.1);
        color: #4CAF50;
    }

    .status-cancelled {
        background: rgba(244, 67, 54, 0.1);
        color: #f44336;
    }

    .order-price {
        font-size: 16px;
        font-weight: 700;
        color: #D4AF37;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-state i {
        font-size: 60px;
        color: #e5e5e5;
        margin-bottom: 20px;
    }

    .empty-state h3 {
        font-size: 18px;
        color: #666;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #999;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
        
        .form-grid {
            grid-template-columns: 1fr;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .quick-links {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Profile Banner -->
<div class="profile-banner">
    <div class="profile-banner-content">
        <h1>Tài khoản của tôi</h1>
        <p>Quản lý thông tin cá nhân và đơn hàng</p>
    </div>
</div>

<div class="profile-container">
    <div class="profile-grid">
        <!-- Sidebar -->
        <aside class="profile-sidebar">
            <div class="profile-avatar-section">
                <div class="profile-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <h3 class="profile-name"><?= htmlspecialchars($user['full_name']) ?></h3>
                <p class="profile-email"><?= htmlspecialchars($user['email']) ?></p>
            </div>
            
            <ul class="profile-menu">
                <li>
                    <a href="#" class="active" data-tab="overview">
                        <i class="fas fa-th-large"></i>
                        <span>Tổng quan</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-tab="info">
                        <i class="fas fa-user-edit"></i>
                        <span>Thông tin cá nhân</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-tab="orders">
                        <i class="fas fa-shopping-bag"></i>
                        <span>Đơn hàng</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-tab="security">
                        <i class="fas fa-lock"></i>
                        <span>Đổi mật khẩu</span>
                    </a>
                </li>
                <li>
                    <a href="/appointments">
                        <i class="fas fa-calendar-check"></i>
                        <span>Lịch hẹn</span>
                    </a>
                </li>
                <li>
                    <a href="/favorites">
                        <i class="fas fa-heart"></i>
                        <span>Xe yêu thích</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="profile-main">
            <!-- Overview Tab -->
            <div id="overview-tab" class="tab-content active">
                <h2 class="section-title">
                    <i class="fas fa-tachometer-alt"></i>
                    Tổng quan tài khoản
                </h2>

                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <div class="stat-number"><?= $totalOrders ?></div>
                        <div class="stat-label">Đơn hàng</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stat-number"><?= $pendingOrders ?></div>
                        <div class="stat-label">Chờ xử lý</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-number"><?= $completedOrders ?></div>
                        <div class="stat-label">Hoàn thành</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Lịch hẹn</div>
                    </div>
                </div>

                <h3 style="font-size: 18px; margin: 30px 0 15px; color: #1a1a1a;">Truy cập nhanh</h3>
                <div class="quick-links">
                    <a href="/cars" class="quick-link">
                        <div class="quick-link-icon">
                            <i class="fas fa-car"></i>
                        </div>
                        <div class="quick-link-content">
                            <h4>Xem xe</h4>
                            <p>Khám phá các mẫu xe</p>
                        </div>
                    </a>
                    <a href="/cart" class="quick-link">
                        <div class="quick-link-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="quick-link-content">
                            <h4>Giỏ hàng</h4>
                            <p>Xem giỏ hàng của bạn</p>
                        </div>
                    </a>
                    <a href="/contact" class="quick-link">
                        <div class="quick-link-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="quick-link-content">
                            <h4>Liên hệ</h4>
                            <p>Hỗ trợ khách hàng</p>
                        </div>
                    </a>
                    <a href="#" data-tab="info" class="quick-link menu-link">
                        <div class="quick-link-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <div class="quick-link-content">
                            <h4>Cập nhật thông tin</h4>
                            <p>Chỉnh sửa tài khoản</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Personal Info Tab -->
            <div id="info-tab" class="tab-content">
                <h2 class="section-title">
                    <i class="fas fa-user-edit"></i>
                    Thông tin cá nhân
                </h2>

                <form method="POST" action="/profile/update">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Họ và tên *</label>
                            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" pattern="[0-9]{10}">
                        </div>
                        <div class="form-group">
                            <label>Ngày sinh</label>
                            <input type="date" name="birth_date" value="<?= htmlspecialchars($user['birth_date'] ?? '') ?>">
                        </div>
                        <div class="form-group full-width">
                            <label>Địa chỉ</label>
                            <input type="text" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="location.reload()">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>

            <!-- Orders Tab -->
            <div id="orders-tab" class="tab-content">
                <h2 class="section-title">
                    <i class="fas fa-shopping-bag"></i>
                    Đơn hàng của tôi
                </h2>

                <?php if (empty($orders)): ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>Chưa có đơn hàng</h3>
                        <p>Bạn chưa có đơn hàng nào. Hãy khám phá các mẫu xe của chúng tôi!</p>
                        <a href="/cars" class="btn btn-primary">Xem xe</a>
                    </div>
                <?php else: ?>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Xe</th>
                                <th>Giá</th>
                                <th>Trạng thái</th>
                                <th>Ngày đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= $order['id'] ?></td>
                                    <td>
                                        <div class="order-car">
                                            <?php if (!empty($order['car_image'])): ?>
                                                <img src="<?= $order['car_image'] ?>" alt="<?= htmlspecialchars($order['car_name']) ?>" class="order-car-img">
                                            <?php else: ?>
                                                <div class="order-car-img" style="background: #f0f0f0; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-car" style="color: #999;"></i>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="order-car-name"><?= htmlspecialchars($order['car_name']) ?></div>
                                                <div class="order-car-brand"><?= htmlspecialchars($order['brand_name']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="order-price"><?= number_format($order['car_price'], 0, ',', '.') ?> VNĐ</div>
                                    </td>
                                    <td>
                                        <?php
                                        $statusClass = 'status-' . $order['status'];
                                        $statusText = [
                                            'pending' => 'Chờ xử lý',
                                            'confirmed' => 'Đã xác nhận',
                                            'completed' => 'Hoàn thành',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                        ?>
                                        <span class="order-status <?= $statusClass ?>">
                                            <?= $statusText[$order['status']] ?? $order['status'] ?>
                                        </span>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($order['created_at'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- Security Tab -->
            <div id="info-tab" class="tab-content">
                <h2 class="section-title">
                    <i class="fas fa-user-edit"></i>
                    Thông tin cá nhân
                </h2>

                <form method="POST" action="/profile/update">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Họ và tên *</label>
                            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" pattern="[0-9]{10}">
                        </div>
                        <div class="form-group">
                            <label>Ngày sinh</label>
                            <input type="date" name="birth_date" value="<?= htmlspecialchars($user['birth_date'] ?? '') ?>">
                        </div>
                        <div class="form-group full-width">
                            <label>Địa chỉ</label>
                            <input type="text" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="location.reload()">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>

            <!-- Security Tab -->
            <div id="security-tab" class="tab-content">
                <h2 class="section-title">
                    <i class="fas fa-lock"></i>
                    Đổi mật khẩu
                </h2>

                <form method="POST" action="/profile/change-password">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Mật khẩu hiện tại *</label>
                            <input type="password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu mới *</label>
                            <input type="password" name="new_password" required minlength="6">
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu *</label>
                            <input type="password" name="confirm_password" required minlength="6">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="this.form.reset()">Hủy</button>
                        <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<script>
    // Tab switching
    document.querySelectorAll('[data-tab]').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const tabId = this.getAttribute('data-tab');
            
            // Update active menu
            document.querySelectorAll('.profile-menu a').forEach(a => a.classList.remove('active'));
            this.classList.add('active');
            
            // Update active tab content
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.getElementById(tabId + '-tab').classList.add('active');
        });
    });
    
    // Quick link menu items
    document.querySelectorAll('.quick-link.menu-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const tabId = this.getAttribute('data-tab');
            
            // Update active menu
            document.querySelectorAll('.profile-menu a').forEach(a => a.classList.remove('active'));
            document.querySelector(`.profile-menu a[data-tab="${tabId}"]`).classList.add('active');
            
            // Update active tab content
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            document.getElementById(tabId + '-tab').classList.add('active');
        });
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>

        .avatar-edit:hover i {
            color: #000;
        }

        .avatar-edit i {
            font-size: 14px;
            color: #D4AF37;
        }

        .profile-name {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            margin-bottom: 8px;
        }

        .profile-role {
            color: #D4AF37;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .profile-email {
            color: #666;
            font-size: 15px;
        }

        /* Main Content */
        .profile-content {
            max-width: 1200px;
            margin: -30px auto 60px;
            padding: 0 30px;
            position: relative;
            z-index: 2;
        }

        /* Tabs */
        .profile-tabs {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .tab-btn {
            padding: 15px 30px;
            background: #ffffff;
            border: 2px solid rgba(212, 175, 55, 0.2);
            border-radius: 12px;
            color: #666;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tab-btn:hover {
            border-color: rgba(212,175,55,0.5);
            color: #D4AF37;
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            border-color: #D4AF37;
            color: #000;
            font-weight: 600;
        }

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Profile Form */
        .profile-section {
            background: linear-gradient(145deg, #ffffff 0%, #fafafa 100%);
            border: 1px solid rgba(212, 175, 55, 0.15);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid rgba(212, 175, 55, 0.15);
        }}

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i {
            color: #D4AF37;
        }

        .btn-edit {
            padding: 10px 20px;
            background: transparent;
            border: 1px solid rgba(212,175,55,0.3);
            color: #D4AF37;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-edit:hover {
            background: rgba(212,175,55,0.1);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            color: #333;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 15px 18px;
            background: #ffffff;
            border: 2px solid rgba(212, 175, 55, 0.2);
            border-radius: 12px;
            color: #1a1a1a;
            font-size: 15px;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #D4AF37;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .form-group input:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        .stat-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card:hover {
            border-color: rgba(212,175,55,0.3);
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(212,175,55,0.15) 0%, rgba(184,134,11,0.15) 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .stat-icon i {
            font-size: 24px;
            color: #D4AF37;
        }

        .stat-value {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            color: #fff;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #666;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Orders List */
        .orders-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .order-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            padding: 25px;
            display: flex;
            gap: 25px;
            transition: all 0.3s;
        }

        .order-card:hover {
            border-color: rgba(212,175,55,0.3);
        }

        .order-image {
            width: 150px;
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .order-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .order-info {
            flex: 1;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .order-id {
            color: #D4AF37;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .order-car {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
        }

        .order-status {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .status-pending {
            background: rgba(241, 196, 15, 0.15);
            color: #f1c40f;
        }

        .status-processing {
            background: rgba(52, 152, 219, 0.15);
            color: #3498db;
        }

        .status-completed {
            background: rgba(39, 174, 96, 0.15);
            color: #27ae60;
        }

        .status-cancelled {
            background: rgba(231, 76, 60, 0.15);
            color: #e74c3c;
        }

        .order-details {
            display: flex;
            gap: 30px;
            color: #888;
            font-size: 14px;
        }

        .order-details span {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .order-details i {
            color: #D4AF37;
        }

        /* Favorites Grid */
        .favorites-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        @media (max-width: 992px) {
            .favorites-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .favorites-grid {
                grid-template-columns: 1fr;
            }
        }

        .favorite-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s;
        }

        .favorite-card:hover {
            border-color: rgba(212,175,55,0.3);
            transform: translateY(-5px);
        }

        .favorite-image {
            height: 180px;
            position: relative;
        }

        .favorite-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-remove-fav {
            position: absolute;
            top: 15px;
            right: 15px;
            width: 35px;
            height: 35px;
            background: rgba(0,0,0,0.6);
            border: none;
            border-radius: 50%;
            color: #e74c3c;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-remove-fav:hover {
            background: #e74c3c;
            color: #fff;
        }

        .favorite-info {
            padding: 20px;
        }

        .favorite-brand {
            color: #D4AF37;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .favorite-name {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            margin-bottom: 12px;
        }

        .favorite-price {
            font-size: 16px;
            font-weight: 600;
            color: #D4AF37;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 60px;
            color: #333;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 25px;
        }

        .btn-primary {
            padding: 15px 35px;
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            border: none;
            border-radius: 10px;
            color: #000;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(212,175,55,0.3);
        }

        /* Settings */
        .settings-section {
            margin-bottom: 30px;
        }

        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .setting-item:last-child {
            border-bottom: none;
        }

        .setting-info h4 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .setting-info p {
            color: #666;
            font-size: 13px;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            width: 50px;
            height: 26px;
        }

        .toggle-switch input {
            display: none;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: #333;
            border-radius: 26px;
            transition: 0.3s;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background: #fff;
            border-radius: 50%;
            transition: 0.3s;
        }

        .toggle-switch input:checked + .toggle-slider {
            background: #D4AF37;
        }

        .toggle-switch input:checked + .toggle-slider::before {
            transform: translateX(24px);
        }

        /* Password Change Form */
        .password-form {
            max-width: 500px;
        }

        .password-form .form-group {
            margin-bottom: 20px;
        }

        .btn-change-password {
            padding: 15px 30px;
            background: transparent;
            border: 1px solid #D4AF37;
            color: #D4AF37;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .btn-change-password:hover {
            background: #D4AF37;
            color: #000;
        }

        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.15);
            border: 1px solid rgba(40, 167, 69, 0.3);
            color: #5dd879;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.15);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ff6b7a;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-nav {
                padding: 15px 20px;
            }

            .nav-links {
                display: none;
            }

            .profile-header {
                padding: 100px 20px 60px;
            }

            .profile-section {
                padding: 25px;
            }

            .order-card {
                flex-direction: column;
            }

            .order-image {
                width: 100%;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <nav class="header-nav">
        <a href="/" class="logo">
            <div class="logo-icon">
                <i class="fas fa-car"></i>
            </div>
            <span class="logo-text">Auto<span>Car</span></span>
        </a>
        <div class="nav-links">
            <a href="/">Trang chủ</a>
            <a href="/cars">Xe</a>
            <a href="/about">Giới thiệu</a>
            <a href="/contact">Liên hệ</a>
            <a href="/logout" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </a>
        </div>
    </nav>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-card">
            <div class="profile-avatar">
                <?php if (!empty($user['avatar'])): ?>
                    <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar">
                <?php else: ?>
                    <i class="fas fa-user"></i>
                <?php endif; ?>
                <label class="avatar-edit">
                    <i class="fas fa-camera"></i>
                    <input type="file" accept="image/*" style="display: none;">
                </label>
            </div>
            <h1 class="profile-name"><?= htmlspecialchars($user['full_name'] ?? $user['username']) ?></h1>
            <p class="profile-role">
                <?= $user['role'] === 'admin' ? 'Quản trị viên' : 'Thành viên VIP' ?>
            </p>
            <p class="profile-email">
                <i class="fas fa-envelope"></i> <?= htmlspecialchars($user['email']) ?>
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="profile-content">
        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="stat-value"><?= $orderCount ?? 0 ?></div>
                <div class="stat-label">Đơn hàng</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <div class="stat-value"><?= $favoriteCount ?? 0 ?></div>
                <div class="stat-label">Yêu thích</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value"><?= $reviewCount ?? 0 ?></div>
                <div class="stat-label">Đánh giá</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar"></i>
                </div>
                <div class="stat-value"><?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?></div>
                <div class="stat-label">Ngày tham gia</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="profile-tabs">
            <button class="tab-btn active" data-tab="info">
                <i class="fas fa-user"></i> Thông tin
            </button>
            <button class="tab-btn" data-tab="orders">
                <i class="fas fa-shopping-bag"></i> Đơn hàng
            </button>
            <button class="tab-btn" data-tab="favorites">
                <i class="fas fa-heart"></i> Yêu thích
            </button>
            <button class="tab-btn" data-tab="settings">
                <i class="fas fa-cog"></i> Cài đặt
            </button>
        </div>

        <!-- Tab: Profile Info -->
        <div class="tab-content active" id="tab-info">
            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-user-circle"></i> Thông tin cá nhân
                    </h2>
                    <button class="btn-edit" onclick="toggleEdit()">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </button>
                </div>

                <form method="POST" action="/profile/update" id="profileForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="full_name" 
                                   value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" 
                                   disabled>
                        </div>
                        <div class="form-group">
                            <label>Tên đăng nhập</label>
                            <input type="text" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" 
                                   value="<?= htmlspecialchars($user['email']) ?>" 
                                   disabled>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone" 
                                   value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                                   disabled>
                        </div>
                        <div class="form-group full-width">
                            <label>Địa chỉ</label>
                            <textarea name="address" disabled><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div id="editButtons" style="display: none; margin-top: 20px;">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Lưu thay đổi
                        </button>
                        <button type="button" class="btn-edit" onclick="cancelEdit()" style="margin-left: 10px;">
                            Hủy
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tab: Orders -->
        <div class="tab-content" id="tab-orders">
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-shopping-bag"></i> Lịch sử đơn hàng
                    </h2>
                </div>

                <?php if (!empty($orders)): ?>
                    <div class="orders-list">
                        <?php foreach ($orders as $order): ?>
                            <div class="order-card">
                                <div class="order-image">
                                    <img src="<?= htmlspecialchars($order['car_image'] ?? 'https://via.placeholder.com/150x100') ?>" alt="Car">
                                </div>
                                <div class="order-info">
                                    <div class="order-header">
                                        <div>
                                            <p class="order-id">#<?= htmlspecialchars($order['id']) ?></p>
                                            <h3 class="order-car"><?= htmlspecialchars($order['car_name'] ?? 'Xe') ?></h3>
                                        </div>
                                        <span class="order-status status-<?= $order['status'] ?? 'pending' ?>">
                                            <?php
                                            $statusText = [
                                                'pending' => 'Chờ xử lý',
                                                'processing' => 'Đang xử lý',
                                                'completed' => 'Hoàn thành',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            echo $statusText[$order['status'] ?? 'pending'];
                                            ?>
                                        </span>
                                    </div>
                                    <div class="order-details">
                                        <span><i class="fas fa-calendar"></i> <?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                                        <span><i class="fas fa-money-bill"></i> <?= number_format($order['total_price'] ?? 0, 0, ',', '.') ?> VNĐ</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-shopping-cart"></i>
                        <h3>Chưa có đơn hàng</h3>
                        <p>Bạn chưa thực hiện đơn hàng nào. Hãy khám phá các mẫu xe sang trọng!</p>
                        <a href="/cars" class="btn-primary">
                            <i class="fas fa-car"></i> Xem xe ngay
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab: Favorites -->
        <div class="tab-content" id="tab-favorites">
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-heart"></i> Xe yêu thích
                    </h2>
                </div>

                <?php if (!empty($favorites)): ?>
                    <div class="favorites-grid">
                        <?php foreach ($favorites as $car): ?>
                            <div class="favorite-card">
                                <div class="favorite-image">
                                    <img src="<?= htmlspecialchars($car['image'] ?? 'https://via.placeholder.com/300x180') ?>" alt="<?= htmlspecialchars($car['name']) ?>">
                                    <button class="btn-remove-fav" onclick="removeFavorite(<?= $car['id'] ?>)">
                                        <i class="fas fa-heart-broken"></i>
                                    </button>
                                </div>
                                <div class="favorite-info">
                                    <p class="favorite-brand"><?= htmlspecialchars($car['brand_name'] ?? '') ?></p>
                                    <h3 class="favorite-name"><?= htmlspecialchars($car['name']) ?></h3>
                                    <p class="favorite-price"><?= number_format($car['price'], 0, ',', '.') ?> VNĐ</p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-heart"></i>
                        <h3>Chưa có xe yêu thích</h3>
                        <p>Hãy thêm các mẫu xe bạn yêu thích để theo dõi dễ dàng hơn!</p>
                        <a href="/cars" class="btn-primary">
                            <i class="fas fa-search"></i> Tìm xe
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab: Settings -->
        <div class="tab-content" id="tab-settings">
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-bell"></i> Thông báo
                    </h2>
                </div>

                <div class="settings-section">
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Thông báo Email</h4>
                            <p>Nhận thông báo về đơn hàng qua email</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Xe mới</h4>
                            <p>Nhận thông báo khi có xe mới phù hợp</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    <div class="setting-item">
                        <div class="setting-info">
                            <h4>Khuyến mãi</h4>
                            <p>Nhận thông tin ưu đãi và khuyến mãi</p>
                        </div>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <i class="fas fa-lock"></i> Đổi mật khẩu
                    </h2>
                </div>

                <form class="password-form" method="POST" action="/profile/change-password">
                    <div class="form-group">
                        <label>Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label>Mật khẩu mới</label>
                        <input type="password" name="new_password" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Xác nhận mật khẩu mới</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn-change-password">
                        <i class="fas fa-key"></i> Đổi mật khẩu
                    </button>
                </form>
            </div>

            <div class="profile-section" style="border-color: rgba(231,76,60,0.3);">
                <div class="section-header">
                    <h2 class="section-title" style="color: #e74c3c;">
                        <i class="fas fa-exclamation-triangle"></i> Vùng nguy hiểm
                    </h2>
                </div>

                <div class="setting-item">
                    <div class="setting-info">
                        <h4 style="color: #e74c3c;">Xóa tài khoản</h4>
                        <p>Xóa vĩnh viễn tài khoản và tất cả dữ liệu liên quan</p>
                    </div>
                    <button class="btn-edit" style="border-color: #e74c3c; color: #e74c3c;">
                        <i class="fas fa-trash"></i> Xóa tài khoản
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.dataset.tab;
                
                // Update active tab button
                document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Update active tab content
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                document.getElementById('tab-' + tabId).classList.add('active');
            });
        });

        // Edit profile toggle
        function toggleEdit() {
            const form = document.getElementById('profileForm');
            const inputs = form.querySelectorAll('input:not([type="hidden"]), textarea');
            const buttons = document.getElementById('editButtons');
            
            inputs.forEach(input => {
                if (input.name && input.name !== 'username') {
                    input.disabled = !input.disabled;
                }
            });
            
            buttons.style.display = buttons.style.display === 'none' ? 'block' : 'none';
        }

        function cancelEdit() {
            toggleEdit();
            document.getElementById('profileForm').reset();
        }

        // Remove favorite
        function removeFavorite(carId) {
            if (confirm('Bạn có chắc muốn xóa xe này khỏi danh sách yêu thích?')) {
                fetch('/favorite/remove/' + carId, {
                    method: 'POST'
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    }
                });
            }
        }
    </script>
</body>
</html>
