<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa xe - AutoCar Admin</title>
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

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #999;
            text-decoration: none;
        }

        .breadcrumb a:hover {
            color: #D4AF37;
        }

        .breadcrumb span {
            color: #333;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 20px;
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

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #fff;
            color: #333;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            border: 2px solid #eee;
        }

        .btn-back:hover {
            border-color: #D4AF37;
            color: #D4AF37;
        }

        /* Form */
        .form-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 25px;
        }

        .card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            margin-bottom: 25px;
        }

        .card-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f5f5f5;
        }

        .card-header h3 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            color: #1a1a1a;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-group label .required {
            color: #e74c3c;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #D4AF37;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }

        select.form-control {
            appearance: none;
            background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23999' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E") no-repeat right 15px center;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        /* Image Upload */
        .image-upload {
            border: 2px dashed #ddd;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-upload:hover {
            border-color: #D4AF37;
            background: rgba(212,175,55,0.02);
        }

        .image-upload i {
            font-size: 40px;
            color: #ddd;
            margin-bottom: 15px;
        }

        .image-upload p {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .image-upload span {
            font-size: 12px;
            color: #999;
        }

        .image-upload input {
            display: none;
        }

        .image-preview {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .preview-item {
            position: relative;
            aspect-ratio: 16/10;
            border-radius: 8px;
            overflow: hidden;
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-item .remove {
            position: absolute;
            top: 5px;
            right: 5px;
            width: 24px;
            height: 24px;
            background: rgba(231,76,60,0.9);
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .preview-item .main-badge {
            position: absolute;
            bottom: 5px;
            left: 5px;
            background: rgba(212,175,55,0.9);
            color: #fff;
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 4px;
        }

        /* Specs Grid */
        .specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .spec-input {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        .spec-input i {
            color: #D4AF37;
            width: 20px;
        }

        .spec-input input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 14px;
            font-family: 'Montserrat', sans-serif;
        }

        .spec-input input:focus {
            outline: none;
        }

        /* Status Options */
        .status-options {
            display: flex;
            gap: 15px;
        }

        .status-option {
            flex: 1;
            padding: 15px;
            border: 2px solid #eee;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .status-option:hover {
            border-color: #D4AF37;
        }

        .status-option.selected {
            border-color: #D4AF37;
            background: rgba(212,175,55,0.05);
        }

        .status-option input {
            display: none;
        }

        .status-option i {
            font-size: 24px;
            margin-bottom: 8px;
        }

        .status-option.available i { color: #2ecc71; }
        .status-option.reserved i { color: #f39c12; }
        .status-option.sold i { color: #e74c3c; }

        .status-option span {
            display: block;
            font-size: 13px;
            font-weight: 500;
        }

        /* Submit Buttons */
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .btn {
            padding: 14px 30px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-family: 'Montserrat', sans-serif;
        }

        .btn-primary {
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212,175,55,0.4);
        }

        .btn-danger {
            background: #e74c3c;
            color: #fff;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .btn-secondary {
            background: #f5f5f5;
            color: #333;
        }

        .btn-secondary:hover {
            background: #eee;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .form-grid {
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
            .form-row {
                grid-template-columns: 1fr;
            }
            .specs-grid {
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
            <li><a href="/autocar/admin"><i class="fas fa-home"></i> Dashboard</a></li>
            
            <li class="sidebar-menu-title">Quản lý</li>
            <li><a href="/autocar/admin/cars" class="active"><i class="fas fa-car"></i> Quản lý xe</a></li>
            <li><a href="/autocar/admin/brands"><i class="fas fa-copyright"></i> Thương hiệu</a></li>
            <li><a href="/autocar/admin/categories"><i class="fas fa-tags"></i> Danh mục</a></li>
            
            <li class="sidebar-menu-title">Kinh doanh</li>
            <li><a href="/autocar/admin/orders"><i class="fas fa-shopping-cart"></i> Đơn hàng</a></li>
            <li><a href="/autocar/admin/users"><i class="fas fa-users"></i> Khách hàng</a></li>
            <li><a href="/autocar/admin/reviews"><i class="fas fa-star"></i> Đánh giá</a></li>
            
            <li class="sidebar-menu-title">Hệ thống</li>
            <li><a href="/autocar/admin/contacts"><i class="fas fa-envelope"></i> Liên hệ</a></li>
            <li><a href="/autocar/admin/settings"><i class="fas fa-cog"></i> Cài đặt</a></li>
            <li><a href="/autocar/"><i class="fas fa-globe"></i> Xem website</a></li>
            <li><a href="/autocar/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <div class="breadcrumb">
                <a href="/autocar/admin">Dashboard</a>
                <i class="fas fa-chevron-right" style="font-size: 10px; color: #ccc;"></i>
                <a href="/autocar/admin/cars">Quản lý xe</a>
                <i class="fas fa-chevron-right" style="font-size: 10px; color: #ccc;"></i>
                <span>Chỉnh sửa xe</span>
            </div>
            <div class="header-right">
                <div class="header-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="admin-content">
            <!-- Page Header -->
            <div class="page-header">
                <h2>Chỉnh sửa: <?= $car['ten_xe'] ?? 'Mercedes-Benz S-Class 2024' ?></h2>
                <a href="/autocar/admin/cars" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>

            <!-- Form -->
            <form action="/autocar/admin/cars/update/<?= $car['id'] ?? 1 ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $car['id'] ?? 1 ?>">
                
                <div class="form-grid">
                    <!-- Left Column -->
                    <div>
                        <!-- Basic Info -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-info-circle" style="color: #D4AF37; margin-right: 10px;"></i>Thông tin cơ bản</h3>
                            </div>
                            <div class="form-group">
                                <label>Tên xe <span class="required">*</span></label>
                                <input type="text" name="ten_xe" class="form-control" value="<?= $car['ten_xe'] ?? 'Mercedes-Benz S-Class 2024' ?>" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Thương hiệu <span class="required">*</span></label>
                                    <select name="id_hang" class="form-control" required>
                                        <option value="">Chọn thương hiệu</option>
                                        <?php if (isset($brands)): ?>
                                            <?php foreach ($brands as $brand): ?>
                                                <option value="<?= $brand['id'] ?>" <?= ($car['id_hang'] ?? '') == $brand['id'] ? 'selected' : '' ?>><?= $brand['ten_hang'] ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="1" selected>Mercedes-Benz</option>
                                            <option value="2">BMW</option>
                                            <option value="3">Audi</option>
                                            <option value="4">Porsche</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Danh mục <span class="required">*</span></label>
                                    <select name="id_loai" class="form-control" required>
                                        <option value="">Chọn danh mục</option>
                                        <?php if (isset($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id'] ?>" <?= ($car['id_loai'] ?? '') == $category['id'] ? 'selected' : '' ?>><?= $category['ten_loai'] ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="1" selected>Sedan</option>
                                            <option value="2">SUV</option>
                                            <option value="3">Coupe</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Giá bán (VNĐ) <span class="required">*</span></label>
                                    <input type="number" name="gia" class="form-control" value="<?= $car['gia'] ?? 5200000000 ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Năm sản xuất <span class="required">*</span></label>
                                    <input type="number" name="nam_san_xuat" class="form-control" value="<?= $car['nam_san_xuat'] ?? 2024 ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Mô tả ngắn</label>
                                <textarea name="mo_ta" class="form-control"><?= $car['mo_ta'] ?? 'Xe sedan hạng sang với công nghệ tiên tiến và nội thất sang trọng.' ?></textarea>
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-cogs" style="color: #D4AF37; margin-right: 10px;"></i>Thông số kỹ thuật</h3>
                            </div>
                            <div class="specs-grid">
                                <div class="spec-input">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <input type="text" name="dong_co" value="<?= $car['dong_co'] ?? '3.0L I6 Turbo' ?>" placeholder="Động cơ">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-bolt"></i>
                                    <input type="text" name="cong_suat" value="<?= $car['cong_suat'] ?? '429 HP' ?>" placeholder="Công suất">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-cog"></i>
                                    <input type="text" name="hop_so" value="<?= $car['hop_so'] ?? '9G-Tronic' ?>" placeholder="Hộp số">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-road"></i>
                                    <input type="text" name="dan_dong" value="<?= $car['dan_dong'] ?? '4MATIC' ?>" placeholder="Dẫn động">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-gas-pump"></i>
                                    <input type="text" name="nhien_lieu" value="<?= $car['nhien_lieu'] ?? 'Xăng' ?>" placeholder="Nhiên liệu">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-users"></i>
                                    <input type="number" name="so_cho" value="<?= $car['so_cho'] ?? 5 ?>" placeholder="Số chỗ">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-palette"></i>
                                    <input type="text" name="mau_sac" value="<?= $car['mau_sac'] ?? 'Đen Obsidian' ?>" placeholder="Màu sắc">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <input type="text" name="so_km" value="<?= $car['so_km'] ?? '0' ?>" placeholder="Số km">
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-file-alt" style="color: #D4AF37; margin-right: 10px;"></i>Mô tả chi tiết</h3>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <textarea name="mo_ta_chi_tiet" class="form-control" style="min-height: 200px;"><?= $car['mo_ta_chi_tiet'] ?? 'Mercedes-Benz S-Class là biểu tượng của sự sang trọng và đẳng cấp. Với thiết kế tinh tế, công nghệ tiên tiến và hiệu suất vượt trội, S-Class mang đến trải nghiệm lái xe hoàn hảo nhất.' ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <!-- Current Images -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-images" style="color: #D4AF37; margin-right: 10px;"></i>Hình ảnh hiện tại</h3>
                            </div>
                            <div class="image-preview">
                                <?php if (isset($car['images']) && count($car['images']) > 0): ?>
                                    <?php foreach ($car['images'] as $index => $image): ?>
                                        <div class="preview-item">
                                            <img src="<?= $image ?>" alt="Car image">
                                            <?php if ($index === 0): ?>
                                                <span class="main-badge">Ảnh chính</span>
                                            <?php endif; ?>
                                            <button type="button" class="remove"><i class="fas fa-times"></i></button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="preview-item">
                                        <img src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=300" alt="">
                                        <span class="main-badge">Ảnh chính</span>
                                        <button type="button" class="remove"><i class="fas fa-times"></i></button>
                                    </div>
                                    <div class="preview-item">
                                        <img src="https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=300" alt="">
                                        <button type="button" class="remove"><i class="fas fa-times"></i></button>
                                    </div>
                                    <div class="preview-item">
                                        <img src="https://images.unsplash.com/photo-1619767886558-efdc259cde1a?w=300" alt="">
                                        <button type="button" class="remove"><i class="fas fa-times"></i></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div style="margin-top: 20px;">
                                <label class="image-upload" for="car-images">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Thêm ảnh mới</p>
                                    <span>PNG, JPG tối đa 5MB</span>
                                    <input type="file" id="car-images" name="hinh_anh[]" multiple accept="image/*">
                                </label>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-toggle-on" style="color: #D4AF37; margin-right: 10px;"></i>Trạng thái</h3>
                            </div>
                            <div class="status-options">
                                <label class="status-option available <?= ($car['trang_thai'] ?? 'available') == 'available' ? 'selected' : '' ?>">
                                    <input type="radio" name="trang_thai" value="available" <?= ($car['trang_thai'] ?? 'available') == 'available' ? 'checked' : '' ?>>
                                    <i class="fas fa-check-circle"></i>
                                    <span>Còn hàng</span>
                                </label>
                                <label class="status-option reserved <?= ($car['trang_thai'] ?? '') == 'reserved' ? 'selected' : '' ?>">
                                    <input type="radio" name="trang_thai" value="reserved" <?= ($car['trang_thai'] ?? '') == 'reserved' ? 'checked' : '' ?>>
                                    <i class="fas fa-clock"></i>
                                    <span>Đã đặt</span>
                                </label>
                                <label class="status-option sold <?= ($car['trang_thai'] ?? '') == 'sold' ? 'selected' : '' ?>">
                                    <input type="radio" name="trang_thai" value="sold" <?= ($car['trang_thai'] ?? '') == 'sold' ? 'checked' : '' ?>>
                                    <i class="fas fa-times-circle"></i>
                                    <span>Đã bán</span>
                                </label>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-star" style="color: #D4AF37; margin-right: 10px;"></i>Tùy chọn</h3>
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="noi_bat" value="1" <?= ($car['noi_bat'] ?? 0) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                                    <span>Đánh dấu là xe nổi bật</span>
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="khuyen_mai" value="1" <?= ($car['khuyen_mai'] ?? 0) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                                    <span>Đang có khuyến mãi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" style="flex: 1;">
                                <i class="fas fa-save" style="margin-right: 8px;"></i>
                                Cập nhật
                            </button>
                            <button type="button" class="btn btn-danger" onclick="deleteCar(<?= $car['id'] ?? 1 ?>)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Image preview
        document.getElementById('car-images').addEventListener('change', function(e) {
            const preview = document.querySelector('.image-preview');
            
            [...e.target.files].forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove" onclick="this.parentElement.remove()">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    preview.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        });

        // Status option selection
        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.status-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Delete car
        function deleteCar(id) {
            if (confirm('Bạn có chắc chắn muốn xóa xe này? Hành động này không thể hoàn tác.')) {
                window.location.href = '/autocar/admin/cars/delete/' + id;
            }
        }
    </script>
</body>
</html>
