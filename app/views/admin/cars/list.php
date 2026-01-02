<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý xe - AutoCar Admin</title>
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

        /* Content */
        .admin-content {
            padding: 30px;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            color: #1a1a1a;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212,175,55,0.4);
        }

        /* Filters */
        .filters-bar {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-group label {
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }

        .filter-group select,
        .filter-group input {
            padding: 10px 15px;
            border: 2px solid #eee;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .filter-group select:focus,
        .filter-group input:focus {
            outline: none;
            border-color: #D4AF37;
        }

        .filter-search {
            flex: 1;
            min-width: 200px;
            position: relative;
        }

        .filter-search input {
            width: 100%;
            padding: 10px 15px 10px 40px;
        }

        .filter-search i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        /* Car Table */
        .card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }

        .cars-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cars-table th,
        .cars-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f5f5f5;
        }

        .cars-table th {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            background: #fafafa;
        }

        .cars-table th:first-child {
            border-radius: 8px 0 0 8px;
        }

        .cars-table th:last-child {
            border-radius: 0 8px 8px 0;
        }

        .cars-table tr:hover {
            background: #fafafa;
        }

        .car-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .car-info img {
            width: 80px;
            height: 55px;
            object-fit: cover;
            border-radius: 8px;
        }

        .car-info-text h4 {
            font-size: 14px;
            color: #1a1a1a;
            margin-bottom: 3px;
        }

        .car-info-text p {
            font-size: 12px;
            color: #999;
        }

        .car-brand {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .car-brand img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .car-price {
            font-weight: 600;
            color: #D4AF37;
        }

        .car-status {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .car-status.available {
            background: #d4edda;
            color: #155724;
        }

        .car-status.sold {
            background: #f8d7da;
            color: #721c24;
        }

        .car-status.reserved {
            background: #fff3cd;
            color: #856404;
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 35px;
            height: 35px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .action-btn.view {
            background: rgba(52,152,219,0.15);
            color: #3498db;
        }

        .action-btn.edit {
            background: rgba(212,175,55,0.15);
            color: #D4AF37;
        }

        .action-btn.delete {
            background: rgba(231,76,60,0.15);
            color: #e74c3c;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 25px;
        }

        .pagination a,
        .pagination span {
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination a {
            background: #fff;
            color: #333;
            border: 2px solid #eee;
        }

        .pagination a:hover {
            border-color: #D4AF37;
            color: #D4AF37;
        }

        .pagination span.current {
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            color: #fff;
        }

        .pagination span.dots {
            background: transparent;
            border: none;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .filters-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .filter-search {
                width: 100%;
            }
        }

        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }
            .admin-main {
                margin-left: 0;
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
            <li><a href="/autocar/admin"><i class="fas fa-home"></i> Dashboard</a></li>
            
            <li class="sidebar-menu-title">Quản lý</li>
            <li><a href="/autocar/admin/cars" class="active"><i class="fas fa-car"></i> Quản lý xe</a></li>
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
            <h1>Quản lý xe</h1>
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

        <!-- Content -->
        <div class="admin-content">
            <!-- Page Header -->
            <div class="page-header">
                <h2>Danh sách xe (<?= count($cars ?? []) ?> xe)</h2>
                <a href="/autocar/admin/cars/add" class="btn-primary">
                    <i class="fas fa-plus"></i>
                    Thêm xe mới
                </a>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label>Thương hiệu:</label>
                    <select name="brand">
                        <option value="">Tất cả</option>
                        <?php foreach ($brands ?? [] as $brand): ?>
                            <option value="<?= $brand['id'] ?>"><?= $brand['ten_hang'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Danh mục:</label>
                    <select name="category">
                        <option value="">Tất cả</option>
                        <?php foreach ($categories ?? [] as $category): ?>
                            <option value="<?= $category['id'] ?>"><?= $category['ten_loai'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Trạng thái:</label>
                    <select name="status">
                        <option value="">Tất cả</option>
                        <option value="available">Còn hàng</option>
                        <option value="sold">Đã bán</option>
                        <option value="reserved">Đã đặt</option>
                    </select>
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tìm theo tên xe...">
                </div>
            </div>

            <!-- Cars Table -->
            <div class="card">
                <table class="cars-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Xe</th>
                            <th>Thương hiệu</th>
                            <th>Giá</th>
                            <th>Năm SX</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($cars) && count($cars) > 0): ?>
                            <?php foreach ($cars as $car): ?>
                                <tr>
                                    <td>#<?= $car['id'] ?></td>
                                    <td>
                                        <div class="car-info">
                                            <img src="<?= $car['hinh_anh'] ?? '/autocar/assets/images/cars/default.jpg' ?>" alt="">
                                            <div class="car-info-text">
                                                <h4><?= $car['ten_xe'] ?></h4>
                                                <p><?= $car['ten_loai'] ?? 'N/A' ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="car-brand">
                                            <span><?= $car['ten_hang'] ?? 'N/A' ?></span>
                                        </div>
                                    </td>
                                    <td class="car-price"><?= number_format($car['gia'], 0, ',', '.') ?> VNĐ</td>
                                    <td><?= $car['nam_san_xuat'] ?? 'N/A' ?></td>
                                    <td>
                                        <span class="car-status available">Còn hàng</span>
                                    </td>
                                    <td>
                                        <div class="action-btns">
                                            <button class="action-btn view" title="Xem"><i class="fas fa-eye"></i></button>
                                            <a href="/autocar/admin/cars/edit/<?= $car['id'] ?>" class="action-btn edit" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <button class="action-btn delete" title="Xóa" onclick="deleteCar(<?= $car['id'] ?>)"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Demo data -->
                            <tr>
                                <td>#1</td>
                                <td>
                                    <div class="car-info">
                                        <img src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=150" alt="">
                                        <div class="car-info-text">
                                            <h4>Mercedes-Benz S-Class 2024</h4>
                                            <p>Sedan hạng sang</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="car-brand">
                                        <span>Mercedes-Benz</span>
                                    </div>
                                </td>
                                <td class="car-price">5.200.000.000 VNĐ</td>
                                <td>2024</td>
                                <td><span class="car-status available">Còn hàng</span></td>
                                <td>
                                    <div class="action-btns">
                                        <button class="action-btn view" title="Xem"><i class="fas fa-eye"></i></button>
                                        <a href="/autocar/admin/cars/edit/1" class="action-btn edit" title="Sửa"><i class="fas fa-edit"></i></a>
                                        <button class="action-btn delete" title="Xóa"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#2</td>
                                <td>
                                    <div class="car-info">
                                        <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?w=150" alt="">
                                        <div class="car-info-text">
                                            <h4>BMW 7 Series 2024</h4>
                                            <p>Sedan hạng sang</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="car-brand">
                                        <span>BMW</span>
                                    </div>
                                </td>
                                <td class="car-price">6.800.000.000 VNĐ</td>
                                <td>2024</td>
                                <td><span class="car-status reserved">Đã đặt</span></td>
                                <td>
                                    <div class="action-btns">
                                        <button class="action-btn view" title="Xem"><i class="fas fa-eye"></i></button>
                                        <a href="/autocar/admin/cars/edit/2" class="action-btn edit" title="Sửa"><i class="fas fa-edit"></i></a>
                                        <button class="action-btn delete" title="Xóa"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#3</td>
                                <td>
                                    <div class="car-info">
                                        <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=150" alt="">
                                        <div class="car-info-text">
                                            <h4>Porsche 911 Turbo S</h4>
                                            <p>Sports Car</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="car-brand">
                                        <span>Porsche</span>
                                    </div>
                                </td>
                                <td class="car-price">15.500.000.000 VNĐ</td>
                                <td>2024</td>
                                <td><span class="car-status sold">Đã bán</span></td>
                                <td>
                                    <div class="action-btns">
                                        <button class="action-btn view" title="Xem"><i class="fas fa-eye"></i></button>
                                        <a href="/autocar/admin/cars/edit/3" class="action-btn edit" title="Sửa"><i class="fas fa-edit"></i></a>
                                        <button class="action-btn delete" title="Xóa"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>#4</td>
                                <td>
                                    <div class="car-info">
                                        <img src="https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=150" alt="">
                                        <div class="car-info-text">
                                            <h4>Audi RS e-tron GT</h4>
                                            <p>Electric Sedan</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="car-brand">
                                        <span>Audi</span>
                                    </div>
                                </td>
                                <td class="car-price">7.200.000.000 VNĐ</td>
                                <td>2024</td>
                                <td><span class="car-status available">Còn hàng</span></td>
                                <td>
                                    <div class="action-btns">
                                        <button class="action-btn view" title="Xem"><i class="fas fa-eye"></i></button>
                                        <a href="/autocar/admin/cars/edit/4" class="action-btn edit" title="Sửa"><i class="fas fa-edit"></i></a>
                                        <button class="action-btn delete" title="Xóa"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <a href="#"><i class="fas fa-chevron-left"></i></a>
                    <span class="current">1</span>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <span class="dots">...</span>
                    <a href="#">10</a>
                    <a href="#"><i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
    </main>

    <script>
        function deleteCar(id) {
            if (confirm('Bạn có chắc chắn muốn xóa xe này?')) {
                // Handle delete
                window.location.href = '/autocar/admin/cars/delete/' + id;
            }
        }
    </script>
</body>
</html>
