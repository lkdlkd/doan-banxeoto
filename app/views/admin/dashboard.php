<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AutoCar</title>
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
            background: #f5f2ed;
            min-height: 100vh;
        }

        /* Sidebar */
        .admin-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 25px 0;
            z-index: 100;
            overflow-y: auto;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 25px 30px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 25px;
            text-decoration: none;
        }

        .sidebar-logo img {
            height: 50px;
        }

        .sidebar-logo-text {
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo-text .brand {
            font-family: 'Playfair Display', serif;
            font-size: 20px;
            font-weight: 700;
            color: #D4AF37;
        }

        .sidebar-logo-text .role {
            font-size: 11px;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0 15px;
        }

        .sidebar-menu-title {
            font-size: 11px;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 15px 15px 10px;
            margin-top: 10px;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .sidebar-menu li a:hover {
            background: rgba(212,175,55,0.1);
            color: #D4AF37;
        }

        .sidebar-menu li a.active {
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            color: #fff;
        }

        .sidebar-menu li a i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-menu li a .badge {
            margin-left: auto;
            background: #e74c3c;
            color: #fff;
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 10px;
        }

        /* Main Content */
        .admin-main {
            margin-left: 280px;
            min-height: 100vh;
        }

        /* Top Header */
        .admin-header {
            background: #fff;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .admin-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 24px;
            color: #1a1a1a;
        }

        .admin-header h1 span {
            color: #D4AF37;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .header-search {
            position: relative;
        }

        .header-search input {
            padding: 10px 15px 10px 40px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 14px;
            width: 250px;
            transition: all 0.3s ease;
        }

        .header-search input:focus {
            outline: none;
            border-color: #D4AF37;
        }

        .header-search i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .header-notifications {
            position: relative;
            width: 40px;
            height: 40px;
            background: #f5f2ed;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .header-notifications:hover {
            background: #D4AF37;
            color: #fff;
        }

        .header-notifications .notif-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: #fff;
            font-size: 10px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .header-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }

        .header-profile img {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            object-fit: cover;
        }

        .header-profile-info {
            display: flex;
            flex-direction: column;
        }

        .header-profile-info .name {
            font-weight: 600;
            font-size: 14px;
            color: #1a1a1a;
        }

        .header-profile-info .role {
            font-size: 12px;
            color: #999;
        }

        /* Dashboard Content */
        .admin-content {
            padding: 30px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.blue { background: rgba(52,152,219,0.15); color: #3498db; }
        .stat-icon.green { background: rgba(46,204,113,0.15); color: #2ecc71; }
        .stat-icon.gold { background: rgba(212,175,55,0.15); color: #D4AF37; }
        .stat-icon.red { background: rgba(231,76,60,0.15); color: #e74c3c; }

        .stat-info h3 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .stat-info p {
            font-size: 13px;
            color: #999;
        }

        .stat-info .change {
            font-size: 12px;
            margin-top: 5px;
        }

        .stat-info .change.up { color: #2ecc71; }
        .stat-info .change.down { color: #e74c3c; }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #1a1a1a;
        }

        .card-header a {
            font-size: 13px;
            color: #D4AF37;
            text-decoration: none;
        }

        .card-header a:hover {
            text-decoration: underline;
        }

        /* Recent Orders Table */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
        }

        .orders-table th,
        .orders-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #f5f5f5;
        }

        .orders-table th {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
        }

        .orders-table td {
            font-size: 14px;
            color: #333;
        }

        .orders-table .customer {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .orders-table .customer img {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            object-fit: cover;
        }

        .orders-table .status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status.pending { background: #fff3cd; color: #856404; }
        .status.completed { background: #d4edda; color: #155724; }
        .status.cancelled { background: #f8d7da; color: #721c24; }
        .status.processing { background: #cce5ff; color: #004085; }

        /* Recent Activity */
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .activity-item {
            display: flex;
            gap: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f5f5f5;
        }

        .activity-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .activity-icon.order { background: rgba(52,152,219,0.15); color: #3498db; }
        .activity-icon.user { background: rgba(46,204,113,0.15); color: #2ecc71; }
        .activity-icon.car { background: rgba(212,175,55,0.15); color: #D4AF37; }

        .activity-content h4 {
            font-size: 14px;
            color: #1a1a1a;
            margin-bottom: 3px;
        }

        .activity-content p {
            font-size: 12px;
            color: #999;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }

        .quick-action-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px;
            border-radius: 10px;
            border: 2px solid #eee;
            background: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #333;
            font-size: 13px;
            font-weight: 500;
        }

        .quick-action-btn:hover {
            border-color: #D4AF37;
            background: rgba(212,175,55,0.05);
            color: #D4AF37;
        }

        .quick-action-btn i {
            font-size: 18px;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .content-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-main {
                margin-left: 0;
            }
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <a href="/autocar/admin" class="sidebar-logo">
            <img src="/autocar/assets/images/logo.png" alt="Logo">
            <div class="sidebar-logo-text">
                <span class="brand">AUTOCAR</span>
                <span class="role">Admin Panel</span>
            </div>
        </a>

        <ul class="sidebar-menu">
            <li><a href="/autocar/admin" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
            
            <li class="sidebar-menu-title">Quản lý</li>
            <li><a href="/autocar/admin/cars"><i class="fas fa-car"></i> Quản lý xe</a></li>
            <li><a href="/autocar/admin/brands"><i class="fas fa-copyright"></i> Thương hiệu</a></li>
            <li><a href="/autocar/admin/categories"><i class="fas fa-tags"></i> Danh mục</a></li>
            
            <li class="sidebar-menu-title">Kinh doanh</li>
            <li><a href="/autocar/admin/orders"><i class="fas fa-shopping-cart"></i> Đơn hàng <span class="badge">5</span></a></li>
            <li><a href="/autocar/admin/users"><i class="fas fa-users"></i> Khách hàng</a></li>
            <li><a href="/autocar/admin/reviews"><i class="fas fa-star"></i> Đánh giá</a></li>
            
            <li class="sidebar-menu-title">Hệ thống</li>
            <li><a href="/autocar/admin/contacts"><i class="fas fa-envelope"></i> Liên hệ <span class="badge">3</span></a></li>
            <li><a href="/autocar/admin/settings"><i class="fas fa-cog"></i> Cài đặt</a></li>
            <li><a href="/autocar/"><i class="fas fa-globe"></i> Xem website</a></li>
            <li><a href="/autocar/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <h1>Xin chào, <span>Admin</span>!</h1>
            <div class="header-right">
                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tìm kiếm...">
                </div>
                <div class="header-notifications">
                    <i class="fas fa-bell"></i>
                    <span class="notif-badge">3</span>
                </div>
                <div class="header-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
                    <div class="header-profile-info">
                        <span class="name">Administrator</span>
                        <span class="role">Super Admin</span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="admin-content">
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-car"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $totalCars ?? 33 ?></h3>
                        <p>Tổng số xe</p>
                        <span class="change up"><i class="fas fa-arrow-up"></i> +5 tháng này</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $totalUsers ?? 156 ?></h3>
                        <p>Khách hàng</p>
                        <span class="change up"><i class="fas fa-arrow-up"></i> +12 tháng này</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= $totalOrders ?? 28 ?></h3>
                        <p>Đơn hàng</p>
                        <span class="change up"><i class="fas fa-arrow-up"></i> +8 tháng này</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-info">
                        <h3>12.5B</h3>
                        <p>Doanh thu (VNĐ)</p>
                        <span class="change up"><i class="fas fa-arrow-up"></i> +15% so với tháng trước</span>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Orders -->
                <div class="card">
                    <div class="card-header">
                        <h2>Đơn hàng gần đây</h2>
                        <a href="/autocar/admin/orders">Xem tất cả →</a>
                    </div>
                    <table class="orders-table">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Xe</th>
                                <th>Giá trị</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="customer">
                                        <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=3498db&color=fff" alt="">
                                        <span>Nguyễn Văn A</span>
                                    </div>
                                </td>
                                <td>Mercedes S-Class</td>
                                <td>5.2 tỷ</td>
                                <td><span class="status completed">Hoàn thành</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="customer">
                                        <img src="https://ui-avatars.com/api/?name=Tran+Thi+B&background=2ecc71&color=fff" alt="">
                                        <span>Trần Thị B</span>
                                    </div>
                                </td>
                                <td>BMW X7</td>
                                <td>6.8 tỷ</td>
                                <td><span class="status processing">Đang xử lý</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="customer">
                                        <img src="https://ui-avatars.com/api/?name=Le+Van+C&background=D4AF37&color=fff" alt="">
                                        <span>Lê Văn C</span>
                                    </div>
                                </td>
                                <td>Porsche 911</td>
                                <td>8.5 tỷ</td>
                                <td><span class="status pending">Chờ duyệt</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="customer">
                                        <img src="https://ui-avatars.com/api/?name=Pham+D&background=e74c3c&color=fff" alt="">
                                        <span>Phạm D</span>
                                    </div>
                                </td>
                                <td>Audi Q8</td>
                                <td>4.9 tỷ</td>
                                <td><span class="status cancelled">Đã hủy</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Right Column -->
                <div>
                    <!-- Recent Activity -->
                    <div class="card" style="margin-bottom: 25px;">
                        <div class="card-header">
                            <h2>Hoạt động gần đây</h2>
                        </div>
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon order">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Đơn hàng mới #1234</h4>
                                    <p>5 phút trước</p>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon user">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Khách hàng mới đăng ký</h4>
                                    <p>15 phút trước</p>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon car">
                                    <i class="fas fa-car"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Thêm xe Mercedes GLC</h4>
                                    <p>1 giờ trước</p>
                                </div>
                            </div>
                            <div class="activity-item">
                                <div class="activity-icon order">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Đơn hàng #1230 hoàn thành</h4>
                                    <p>2 giờ trước</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h2>Thao tác nhanh</h2>
                        </div>
                        <div class="quick-actions">
                            <a href="/autocar/admin/cars/add" class="quick-action-btn">
                                <i class="fas fa-plus"></i>
                                Thêm xe
                            </a>
                            <a href="/autocar/admin/orders" class="quick-action-btn">
                                <i class="fas fa-list"></i>
                                Đơn hàng
                            </a>
                            <a href="/autocar/admin/users" class="quick-action-btn">
                                <i class="fas fa-users"></i>
                                Khách hàng
                            </a>
                            <a href="/autocar/admin/settings" class="quick-action-btn">
                                <i class="fas fa-cog"></i>
                                Cài đặt
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
