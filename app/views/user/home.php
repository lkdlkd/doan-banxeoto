<?php
// Load models để lấy dữ liệu
require_once __DIR__ . '/../../models/CarModel.php';
require_once __DIR__ . '/../../models/BrandModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';

$carModel = new CarModel();
$brandModel = new BrandModel();
$categoryModel = new CategoryModel();

// Lấy dữ liệu
$featuredCars = $carModel->getFeaturedCars(6);
$latestCars = $carModel->getLatestCars(8);
$brands = $brandModel->getBrandsWithCarCount();
$categories = $categoryModel->getCategoriesWithCarCount();

$pageTitle = 'Trang chủ - Bán Xe Ô Tô';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }
        
        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 100px 0 80px;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="40" fill="rgba(255,255,255,0.1)"/></svg>');
            opacity: 0.1;
        }
        
        .hero-content {
            position: relative;
            z-index: 1;
        }
        
        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }
        
        /* Search Box */
        .search-box {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            margin-top: -50px;
            position: relative;
            z-index: 10;
        }
        
        .search-box .form-control,
        .search-box .form-select {
            border-radius: 10px;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
        }
        
        .search-box .btn-search {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 40px;
            color: white;
            font-weight: 600;
            width: 100%;
        }
        
        .search-box .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        /* Section Titles */
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: #333;
        }
        
        .section-subtitle {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 40px;
        }
        
        /* Car Cards */
        .car-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: 100%;
        }
        
        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .car-card img {
            height: 200px;
            object-fit: cover;
        }
        
        .car-card .card-body {
            padding: 20px;
        }
        
        .car-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }
        
        .car-specs {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .car-spec-item {
            font-size: 0.85rem;
            color: #666;
        }
        
        .car-spec-item i {
            color: var(--primary-color);
            margin-right: 5px;
        }
        
        /* Brand & Category Cards */
        .brand-card, .category-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            height: 100%;
            cursor: pointer;
        }
        
        .brand-card:hover, .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .brand-card i, .category-card i {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }
        
        .brand-card h5, .category-card h5 {
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .badge-count {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 60px 0;
            margin: 80px 0;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        /* Features Section */
        .feature-box {
            text-align: center;
            padding: 30px;
            transition: all 0.3s;
        }
        
        .feature-box:hover {
            transform: translateY(-5px);
        }
        
        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .feature-icon i {
            font-size: 2rem;
            color: white;
        }
        
        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 80px 0;
            border-radius: 20px;
            margin: 60px 0;
        }
        
        .btn-cta {
            background: white;
            color: var(--primary-color);
            padding: 15px 40px;
            border-radius: 50px;
            font-weight: 600;
            border: none;
            font-size: 1.1rem;
        }
        
        .btn-cta:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <!-- Include Header -->
    <?php include __DIR__ . '/../layouts/header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content text-center">
                <h1 class="hero-title">Tìm Chiếc Xe Mơ Ước Của Bạn</h1>
                <p class="hero-subtitle">
                    <i class="fas fa-star me-2"></i>
                    Hơn 10,000+ xe chất lượng từ các thương hiệu hàng đầu
                </p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="/cars" class="btn btn-light btn-lg px-4">
                        <i class="fas fa-search me-2"></i>Xem tất cả xe
                    </a>
                    <a href="/register" class="btn btn-outline-light btn-lg px-4">
                        <i class="fas fa-user-plus me-2"></i>Đăng ký ngay
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Search Box -->
    <div class="container">
        <div class="search-box">
            <form action="/cars" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <select class="form-select" name="brand">
                            <option value="">Tất cả hãng xe</option>
                            <?php foreach ($brands as $brand): ?>
                                <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="category">
                            <option value="">Loại xe</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"><?= htmlspecialchars($category['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="min_price" placeholder="Giá từ">
                    </div>
                    <div class="col-md-2">
                        <input type="number" class="form-control" name="max_price" placeholder="Giá đến">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-search">
                            <i class="fas fa-search me-2"></i>Tìm kiếm
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Featured Cars -->
    <section class="py-5 mt-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Xe Nổi Bật</h2>
                <p class="section-subtitle">Những mẫu xe được quan tâm nhất</p>
            </div>
            <div class="row g-4">
                <?php if (!empty($featuredCars)): ?>
                    <?php foreach ($featuredCars as $car): ?>
                        <div class="col-md-4">
                            <div class="card car-card">
                                <img src="<?= htmlspecialchars($car['main_image'] ?? '/images/default-car.jpg') ?>" 
                                     class="card-img-top" alt="<?= htmlspecialchars($car['name']) ?>">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0"><?= htmlspecialchars($car['name']) ?></h5>
                                        <span class="badge bg-primary"><?= htmlspecialchars($car['brand_name'] ?? 'N/A') ?></span>
                                    </div>
                                    <p class="text-muted mb-2">
                                        <i class="fas fa-tag me-1"></i>
                                        <?= htmlspecialchars($car['category_name'] ?? 'N/A') ?>
                                    </p>
                                    <div class="car-specs mb-3">
                                        <span class="car-spec-item">
                                            <i class="fas fa-calendar"></i>
                                            <?= htmlspecialchars($car['year'] ?? '2024') ?>
                                        </span>
                                        <span class="car-spec-item">
                                            <i class="fas fa-tachometer-alt"></i>
                                            <?= number_format($car['mileage'] ?? 0) ?> km
                                        </span>
                                        <span class="car-spec-item">
                                            <i class="fas fa-cog"></i>
                                            <?= htmlspecialchars($car['transmission'] ?? 'Auto') ?>
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="car-price">
                                            <?= number_format($car['price']) ?> VNĐ
                                        </div>
                                        <a href="/cars/<?= $car['id'] ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>Chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">Chưa có xe nào được thêm vào hệ thống</p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="text-center mt-4">
                <a href="/cars" class="btn btn-outline-primary btn-lg">
                    Xem tất cả xe <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-car me-2"></i>10,000+
                        </div>
                        <div class="stat-label">Xe Có Sẵn</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-users me-2"></i>50,000+
                        </div>
                        <div class="stat-label">Khách Hàng</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-building me-2"></i>100+
                        </div>
                        <div class="stat-label">Showroom</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-item">
                        <div class="stat-number">
                            <i class="fas fa-award me-2"></i>15+
                        </div>
                        <div class="stat-label">Năm Kinh Nghiệm</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Brands Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Thương Hiệu Nổi Bật</h2>
                <p class="section-subtitle">Các hãng xe hàng đầu thế giới</p>
            </div>
            <div class="row g-4">
                <?php
                $brandIcons = [
                    'Toyota' => 'fa-car',
                    'Honda' => 'fa-car-side',
                    'Mercedes' => 'fa-car-alt',
                    'BMW' => 'fa-car-crash',
                    'Audi' => 'fa-car-rear',
                    'Ford' => 'fa-truck-pickup',
                ];
                $defaultIcon = 'fa-car';
                ?>
                <?php if (!empty($brands)): ?>
                    <?php foreach (array_slice($brands, 0, 6) as $brand): ?>
                        <div class="col-md-2 col-sm-4 col-6">
                            <div class="brand-card" onclick="window.location.href='/cars?brand=<?= $brand['id'] ?>'">
                                <i class="fas <?= $brandIcons[$brand['name']] ?? $defaultIcon ?>"></i>
                                <h5><?= htmlspecialchars($brand['name']) ?></h5>
                                <span class="badge-count"><?= $brand['car_count'] ?? 0 ?> xe</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">Chưa có thương hiệu nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Tại Sao Chọn Chúng Tôi?</h2>
                <p class="section-subtitle">Những lý do nên tin tưởng và lựa chọn</p>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h5>Đảm Bảo Chất Lượng</h5>
                        <p class="text-muted">Tất cả xe đều được kiểm tra kỹ lưỡng</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h5>Giá Cả Hợp Lý</h5>
                        <p class="text-muted">Giá tốt nhất trên thị trường</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h5>Hỗ Trợ 24/7</h5>
                        <p class="text-muted">Tư vấn nhiệt tình mọi lúc</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-file-contract"></i>
                        </div>
                        <h5>Thủ Tục Nhanh</h5>
                        <p class="text-muted">Hỗ trợ làm giấy tờ nhanh chóng</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container">
            <div class="cta-section text-center">
                <h2 class="display-5 fw-bold mb-4">Bạn Muốn Bán Xe Của Mình?</h2>
                <p class="lead mb-4">Đăng tin miễn phí và tiếp cận hàng ngàn khách hàng tiềm năng</p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/sell-car" class="btn btn-cta">
                        <i class="fas fa-plus-circle me-2"></i>Đăng Tin Bán Xe
                    </a>
                <?php else: ?>
                    <a href="/register" class="btn btn-cta">
                        <i class="fas fa-user-plus me-2"></i>Đăng Ký Ngay
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Include Footer -->
    <?php include __DIR__ . '/../layouts/footer.php'; ?>
</body>
</html>
