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

// Load thêm dữ liệu cho favorites và appointments
require_once __DIR__ . '/../../models/FavoriteModel.php';
require_once __DIR__ . '/../../models/AppointmentModel.php';

$favoriteModel = new FavoriteModel();
$appointmentModel = new AppointmentModel();

$favorites = $favoriteModel->getFavoritesByUser($_SESSION['user_id']);
$appointments = $appointmentModel->getAppointmentsByUser($_SESSION['user_id']);
?>

<style>
    /* Profile Banner */
    .profile-banner {
        position: relative;
        height: 300px;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.5) 100%),
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
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .profile-banner p {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
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
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2);
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
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border-color: #D4AF37;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(212, 175, 55, 0.2) 100%);
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
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(212, 175, 55, 0.2) 100%);
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
                    <a href="#" data-tab="appointments">
                        <i class="fas fa-calendar-check"></i>
                        <span>Lịch hẹn</span>
                    </a>
                </li>
                <li>
                    <a href="#" data-tab="favorites">
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

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert" style="margin-bottom: 20px; padding: 12px 16px; background: rgba(76, 175, 80, 0.1); border: 1px solid rgba(76, 175, 80, 0.3); border-radius: 8px; color: #4CAF50;">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']);
                                                            unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert" style="margin-bottom: 20px; padding: 12px 16px; background: rgba(244, 67, 54, 0.1); border: 1px solid rgba(244, 67, 54, 0.3); border-radius: 8px; color: #f44336;">
                        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']);
                                                                    unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/profile/update" id="profileForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Họ và tên *</label>
                            <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                            <input type="hidden" name="email" value="<?= htmlspecialchars($user['email']) ?>">
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" pattern="[0-9]{10}" placeholder="0123456789">
                        </div>
                        <div class="form-group full-width">
                            <label>Địa chỉ</label>
                            <input type="text" name="address" value="<?= htmlspecialchars($user['address'] ?? '') ?>" placeholder="Nhập địa chỉ của bạn">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="location.reload()">Hủy</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Lưu thay đổi</button>
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
            <div id="security-tab" class="tab-content">
                <h2 class="section-title">
                    <i class="fas fa-lock"></i>
                    Đổi mật khẩu
                </h2>

                <?php if (isset($_SESSION['password_success'])): ?>
                    <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px 16px; background: rgba(76, 175, 80, 0.1); border: 1px solid rgba(76, 175, 80, 0.3); border-radius: 8px; color: #4CAF50;">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['password_success']);
                                                            unset($_SESSION['password_success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['password_error'])): ?>
                    <div class="alert alert-error" style="margin-bottom: 20px; padding: 12px 16px; background: rgba(244, 67, 54, 0.1); border: 1px solid rgba(244, 67, 54, 0.3); border-radius: 8px; color: #f44336;">
                        <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['password_error']);
                                                                    unset($_SESSION['password_error']); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/profile/change-password" id="passwordForm">
                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Mật khẩu hiện tại *</label>
                            <input type="password" name="current_password" required placeholder="Nhập mật khẩu hiện tại">
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu mới *</label>
                            <input type="password" name="new_password" id="new_password" required minlength="6" placeholder="Tối thiểu 6 ký tự">
                        </div>
                        <div class="form-group">
                            <label>Xác nhận mật khẩu *</label>
                            <input type="password" name="confirm_password" id="confirm_password" required minlength="6" placeholder="Nhập lại mật khẩu mới">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="this.form.reset()">Hủy</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-key"></i> Đổi mật khẩu</button>
                    </div>
                </form>
            </div>

            <!-- Favorites Tab -->
            <div id="favorites-tab" class="tab-content">
                <h2 class="section-title">
                    <i class="fas fa-heart"></i>
                    Xe yêu thích
                </h2>

                <?php if (empty($favorites)): ?>
                    <div class="empty-state">
                        <i class="fas fa-heart"></i>
                        <h3>Chưa có xe yêu thích</h3>
                        <p>Bạn chưa thêm xe nào vào danh sách yêu thích.</p>
                        <a href="/cars" class="btn btn-primary">Khám phá xe</a>
                    </div>
                <?php else: ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                        <?php foreach ($favorites as $fav): ?>
                            <div style="background: #fff; border: 1px solid #e5e5e5; border-radius: 12px; overflow: hidden; transition: all 0.3s;">
                                <div style="position: relative; height: 200px;">
                                    <img src="<?= htmlspecialchars($fav['image'] ?? 'https://via.placeholder.com/280x200') ?>"
                                        alt="<?= htmlspecialchars($fav['car_name']) ?>"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                    <button onclick="removeFavorite(<?= $fav['car_id'] ?>)"
                                        style="position: absolute; top: 10px; right: 10px; width: 35px; height: 35px; border-radius: 50%; background: rgba(0,0,0,0.6); border: none; color: #ff4444; cursor: pointer; transition: all 0.3s;">
                                        <i class="fas fa-heart-broken"></i>
                                    </button>
                                </div>
                                <div style="padding: 15px;">
                                    <div style="color: #D4AF37; font-size: 12px; text-transform: uppercase; margin-bottom: 5px;">
                                        <?= htmlspecialchars($fav['brand_name']) ?>
                                    </div>
                                    <h4 style="font-size: 16px; margin: 0 0 10px 0; color: #1a1a1a;">
                                        <?= htmlspecialchars($fav['car_name']) ?>
                                    </h4>
                                    <div style="font-size: 18px; font-weight: 700; color: #D4AF37; margin-bottom: 10px;">
                                        <?= number_format($fav['price'], 0, ',', '.') ?> VNĐ
                                    </div>
                                    <a href="/car/<?= $fav['car_id'] ?>" class="btn btn-primary" style="width: 100%; text-align: center; padding: 10px;">
                                        <i class="fas fa-eye"></i> Xem chi tiết
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Appointments Tab -->
            <div id="appointments-tab" class="tab-content">
                <h2 class="section-title">
                    <i class="fas fa-calendar-check"></i>
                    Lịch hẹn xem xe
                </h2>

                <?php if (empty($appointments)): ?>
                    <div class="empty-state">
                        <i class="fas fa-calendar-times"></i>
                        <h3>Chưa có lịch hẹn</h3>
                        <p>Bạn chưa đặt lịch xem xe nào.</p>
                        <a href="/cars" class="btn btn-primary">Đặt lịch xem xe</a>
                    </div>
                <?php else: ?>
                    <div style="display: flex; flex-direction: column; gap: 20px;">
                        <?php foreach ($appointments as $apt): ?>
                            <div style="background: #fff; border: 1px solid #e5e5e5; border-radius: 12px; padding: 20px;">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 15px;">
                                    <div>
                                        <h4 style="margin: 0 0 5px 0; font-size: 16px;">#<?= str_pad($apt['id'], 6, '0', STR_PAD_LEFT) ?></h4>
                                        <span style="font-size: 12px; color: #666;">
                                            <i class="fas fa-calendar-plus"></i> <?= date('d/m/Y H:i', strtotime($apt['created_at'])) ?>
                                        </span>
                                    </div>
                                    <span class="order-status status-<?= $apt['status'] ?? 'pending' ?>">
                                        <?php
                                        switch ($apt['status'] ?? 'pending') {
                                            case 'pending':
                                                echo 'Chờ xác nhận';
                                                break;
                                            case 'confirmed':
                                                echo 'Đã xác nhận';
                                                break;
                                            case 'completed':
                                                echo 'Hoàn thành';
                                                break;
                                            case 'cancelled':
                                                echo 'Đã hủy';
                                                break;
                                            default:
                                                echo 'Chờ xác nhận';
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div style="display: flex; gap: 20px; align-items: start;">
                                    <img src="<?= htmlspecialchars($apt['car_image'] ?? 'https://via.placeholder.com/150x100') ?>"
                                        alt="<?= htmlspecialchars($apt['car_name']) ?>"
                                        style="width: 150px; height: 100px; border-radius: 8px; object-fit: cover;">
                                    <div style="flex: 1;">
                                        <div style="background: linear-gradient(135deg, #D4AF37, #B8960B); color: #000; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; display: inline-block; margin-bottom: 8px;">
                                            <?= htmlspecialchars($apt['brand_name']) ?>
                                        </div>
                                        <h4 style="margin: 0 0 8px 0; font-size: 18px;"><?= htmlspecialchars($apt['car_name']) ?></h4>
                                        <div style="font-size: 20px; font-weight: 700; color: #D4AF37; margin-bottom: 12px;">
                                            <?= number_format($apt['car_price'], 0, ',', '.') ?> VNĐ
                                        </div>
                                        <div style="display: flex; gap: 20px; font-size: 14px; color: #666;">
                                            <span><i class="fas fa-calendar-alt" style="color: #D4AF37;"></i> <?= date('d/m/Y', strtotime($apt['appointment_date'])) ?></span>
                                            <span><i class="fas fa-clock" style="color: #D4AF37;"></i> <?= date('H:i', strtotime($apt['appointment_time'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center;">
                                    <div style="font-size: 13px; color: #666;">
                                        <div><i class="fas fa-user"></i> <?= htmlspecialchars($apt['full_name']) ?></div>
                                        <div><i class="fas fa-phone"></i> <?= htmlspecialchars($apt['phone']) ?></div>
                                    </div>
                                    <div style="display: flex; gap: 10px;">
                                        <a href="/car/<?= $apt['car_id'] ?>" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">
                                            <i class="fas fa-eye"></i> Xem xe
                                        </a>
                                        <?php if (($apt['status'] ?? 'pending') === 'pending'): ?>
                                            <form method="POST" action="/appointment/cancel/<?= $apt['id'] ?>" style="display: inline;"
                                                onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');">
                                                <button type="submit" class="btn btn-secondary" style="padding: 8px 16px; font-size: 13px; background: #fff; color: #dc2626; border: 1.5px solid #fca5a5;">
                                                    <i class="fas fa-times"></i> Hủy lịch
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>

<script>
    // Remove favorite function
    function removeFavorite(carId) {
        if (confirm('Bạn có chắc muốn xóa xe này khỏi danh sách yêu thích?')) {
            fetch('/favorites/remove', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        car_id: carId
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                });
        }
    }

    // Check URL hash on page load to open correct tab
    window.addEventListener('DOMContentLoaded', function() {
        const hash = window.location.hash.substring(1); // Remove #
        if (hash) {
            const menuLink = document.querySelector(`.profile-menu a[data-tab="${hash}"]`);
            if (menuLink) {
                // Remove active from all
                document.querySelectorAll('.profile-menu a').forEach(a => a.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));

                // Activate the target
                menuLink.classList.add('active');
                const targetTab = document.getElementById(hash + '-tab');
                if (targetTab) {
                    targetTab.classList.add('active');
                }
            }
        }
    });

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
            const menuLink = document.querySelector(`.profile-menu a[data-tab="${tabId}"]`);
            if (menuLink) menuLink.classList.add('active');

            // Update active tab content
            document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
            const tabContent = document.getElementById(tabId + '-tab');
            if (tabContent) tabContent.classList.add('active');
        });
    });

    // Handle form submission with validation
    const profileForm = document.querySelector('#info-tab form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            const phone = this.querySelector('input[name="phone"]').value;
            if (phone && !/^[0-9]{10}$/.test(phone)) {
                e.preventDefault();
                alert('Số điện thoại phải có 10 chữ số');
                return false;
            }
        });
    }

    // Validate password match
    const passwordForm = document.getElementById('passwordForm');
    if (passwordForm) {
        passwordForm.addEventListener('submit', function(e) {
            const newPass = document.getElementById('new_password').value;
            const confirmPass = document.getElementById('confirm_password').value;

            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('Mật khẩu xác nhận không khớp!');
                return false;
            }

            if (newPass.length < 6) {
                e.preventDefault();
                alert('Mật khẩu mới phải có ít nhất 6 ký tự!');
                return false;
            }
        });
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>