<?php
// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tài Khoản - AutoCar Luxury</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background: linear-gradient(135deg, #f9f7f3 0%, #f5f2ed 100%);
            min-height: 100vh;
            color: #1a1a1a;
        }

        /* Header */
        .profile-header {
            background: linear-gradient(135deg, rgba(255,255,255,0.95) 0%, rgba(250,247,243,0.95) 100%),
                        url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=1920') center/cover no-repeat;
            padding: 120px 0 60px;
            position: relative;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 150px;
            background: linear-gradient(transparent, #f9f7f3);
        }

        .header-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 100;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(212,175,55,0.2);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon i {
            font-size: 20px;
            color: #000;
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .logo-text span {
            color: #D4AF37;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .nav-links a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #D4AF37;
        }

        .btn-logout {
            padding: 10px 25px;
            background: transparent;
            border: 1px solid #D4AF37;
            color: #D4AF37;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: #D4AF37;
            color: #000;
        }

        /* Profile Info Card */
        .profile-card {
            max-width: 400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            position: relative;
            box-shadow: 0 10px 40px rgba(212,175,55,0.3);
        }

        .profile-avatar i {
            font-size: 60px;
            color: #000;
        }

        .profile-avatar img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-edit {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 40px;
            height: 40px;
            background: #ffffff;
            border: 2px solid #D4AF37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .avatar-edit:hover {
            background: #D4AF37;
        }

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
        <a href="/autocar/" class="logo">
            <div class="logo-icon">
                <i class="fas fa-car"></i>
            </div>
            <span class="logo-text">Auto<span>Car</span></span>
        </a>
        <div class="nav-links">
            <a href="/autocar/">Trang chủ</a>
            <a href="/autocar/cars">Xe</a>
            <a href="/autocar/about">Giới thiệu</a>
            <a href="/autocar/contact">Liên hệ</a>
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
                        <a href="/autocar/cars" class="btn-primary">
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
                        <a href="/autocar/cars" class="btn-primary">
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
