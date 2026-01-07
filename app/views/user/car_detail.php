<?php
$currentPage = 'cars';

// Lấy ID xe từ URL
$carId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kết nối cơ sở dữ liệu
require_once __DIR__ . '/../../models/CarModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';
require_once __DIR__ . '/../../models/FavoriteModel.php';

$carModel = new CarModel();
$reviewModel = new ReviewModel();
$favoriteModel = new FavoriteModel();

// Kiểm tra xe đã được yêu thích chưa
$isFavorite = false;
if (isset($_SESSION['user_id'])) {
    $isFavorite = $favoriteModel->isFavorite($_SESSION['user_id'], $carId);
}

// Lấy thông tin chi tiết xe
$car = $carModel->getById($carId);

if (!$car) {
    header('Location: ' . BASE_URL . '/user/car_list');
    exit;
}

// Lấy danh sách ảnh của xe
$carImages = $carModel->getImages($carId);

// Lấy đánh giá của xe
$reviews = $reviewModel->getByCarId($carId);

// Lấy xe tương tự
$similarCars = $carModel->getSimilarCars($carId, $car['brand_id'], $car['category_id'], 4);

$pageTitle = htmlspecialchars($car['name']) . ' - Chi tiết xe';

// Hàm format tiền
function formatPrice($price)
{
    if ($price >= 1000000000) {
        return number_format($price / 1000000000, 1, '.', '') . ' Tỷ';
    } elseif ($price >= 1000000) {
        return number_format($price / 1000000, 0, '', '') . ' Triệu';
    }
    return number_format($price, 0, '', ',');
}

// Hàm chuyển đổi loại nhiên liệu sang tiếng Việt
function getFuelText($fuel)
{
    $fuelMap = [
        'gasoline' => 'Xăng',
        'diesel' => 'Dầu Diesel',
        'electric' => 'Điện',
        'hybrid' => 'Hybrid'
    ];
    return $fuelMap[$fuel] ?? ucfirst($fuel);
}

// Hàm chuyển đổi hộp số sang tiếng Việt
function getTransmissionText($transmission)
{
    $transMap = [
        'automatic' => 'Tự động',
        'manual' => 'Số sàn',
        'semi-automatic' => 'Bán tự động'
    ];
    return $transMap[$transmission] ?? ucfirst($transmission);
}

// Hàm chuyển đổi hệ dẫn động sang tiếng Việt
function getDrivetrainText($drivetrain)
{
    $driveMap = [
        'FWD' => 'Cầu trước',
        'RWD' => 'Cầu sau',
        'AWD' => '4 bánh toàn thời gian',
        '4WD' => '4 bánh bán thời gian'
    ];
    return $driveMap[$drivetrain] ?? $drivetrain;
}

include __DIR__ . '/../layouts/header.php';
?>

<style>
    .car-detail {
        padding: 40px 0;
        background: #f5f5f5;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .breadcrumb {
        background: white;
        padding: 15px 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .breadcrumb a {
        color: #D4AF37;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .breadcrumb span {
        color: #999;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 30px;
        margin-bottom: 40px;
    }

    .card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Gallery */
    .main-image {
        width: 100%;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 15px;
        background: #f0f0f0;
    }

    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .thumbnail-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
    }

    .thumbnail {
        height: 80px;
        border-radius: 6px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
    }

    .thumbnail.active,
    .thumbnail:hover {
        border-color: #D4AF37;
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Info */
    .car-name {
        font-size: 28px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    .car-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f0f0f0;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #666;
    }

    .meta-item i {
        color: #D4AF37;
    }

    .car-price {
        font-size: 36px;
        font-weight: 700;
        color: #D4AF37;
        margin-bottom: 25px;
    }

    .highlights {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 25px;
    }

    .highlight-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f9f9f9;
        border-radius: 8px;
    }

    .highlight-item i {
        font-size: 18px;
        color: #D4AF37;
    }

    .highlight-item span {
        font-size: 14px;
        color: #333;
    }

    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 25px;
    }

    .btn {
        padding: 14px 24px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s;
        cursor: pointer;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #D4AF37, #B8960B);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .btn-outline {
        background: white;
        color: #D4AF37;
        border: 2px solid #D4AF37;
    }

    .btn-outline:hover {
        background: #D4AF37;
        color: white;
    }

    .contact-box {
        padding: 20px;
        background: #fef9ed;
        border-radius: 8px;
        border-left: 4px solid #D4AF37;
    }

    .contact-box h4 {
        font-size: 16px;
        margin-bottom: 8px;
        color: #1a1a1a;
    }

    .contact-box p {
        font-size: 14px;
        color: #666;
        margin-bottom: 12px;
    }

    .contact-box a {
        color: #D4AF37;
        text-decoration: none;
        font-weight: 600;
    }

    /* Tabs */
    .tabs {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 40px;
    }

    .tab-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        border-bottom: 2px solid #f0f0f0;
    }

    .tab-btn {
        padding: 12px 24px;
        background: none;
        border: none;
        font-size: 15px;
        font-weight: 600;
        color: #666;
        cursor: pointer;
        position: relative;
        transition: all 0.3s;
    }

    .tab-btn.active {
        color: #D4AF37;
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: #D4AF37;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .specs-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .spec-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 15px;
        background: #f9f9f9;
        border-radius: 6px;
    }

    .spec-label {
        font-weight: 600;
        color: #333;
        font-size: 14px;
    }

    .spec-value {
        color: #666;
        font-size: 14px;
    }

    .description {
        line-height: 1.8;
        color: #555;
        font-size: 15px;
    }

    /* Reviews */
    .review-summary {
        display: flex;
        gap: 30px;
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 2px solid #f0f0f0;
    }

    .rating-box {
        text-align: center;
        padding: 20px;
        background: #fef9ed;
        border-radius: 8px;
    }

    .rating-score {
        font-size: 42px;
        font-weight: 700;
        color: #D4AF37;
    }

    .rating-stars {
        color: #D4AF37;
        font-size: 18px;
        margin: 8px 0;
    }

    .rating-count {
        font-size: 13px;
        color: #999;
    }

    .review-list {
        flex: 1;
    }

    .review-item {
        padding: 20px;
        background: #f9f9f9;
        border-radius: 8px;
        margin-bottom: 15px;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .reviewer-name {
        font-weight: 600;
        color: #1a1a1a;
    }

    .review-date {
        font-size: 13px;
        color: #999;
    }

    .review-rating {
        color: #D4AF37;
    }

    .review-text {
        color: #555;
        line-height: 1.6;
        font-size: 14px;
    }

    .no-reviews {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    /* Similar Cars */
    .similar-section {
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 24px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 20px;
    }

    .similar-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .similar-car {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
        text-decoration: none;
        color: inherit;
    }

    .similar-car:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    .similar-car-image {
        height: 180px;
        overflow: hidden;
    }

    .similar-car-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .similar-car-info {
        padding: 15px;
    }

    .similar-car-name {
        font-size: 15px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .similar-car-price {
        font-size: 18px;
        font-weight: 700;
        color: #D4AF37;
        margin-bottom: 8px;
    }

    .similar-car-meta {
        display: flex;
        gap: 12px;
        font-size: 12px;
        color: #999;
    }

    @media (max-width: 1024px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .specs-grid {
            grid-template-columns: 1fr;
        }

        .similar-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .similar-grid {
            grid-template-columns: 1fr;
        }

        .highlights {
            grid-template-columns: 1fr;
        }

        .page-banner h1 {
            font-size: 28px;
        }
    }

    /* Banner */
    .page-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        padding: 60px 0 70px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
    }

    .page-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background:
            radial-gradient(ellipse at 20% 50%, rgba(212, 175, 55, 0.15) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 50%, rgba(212, 175, 55, 0.1) 0%, transparent 50%),
            url('data:image/svg+xml,<svg width="60" height="60" xmlns="http://www.w3.org/2000/svg"><path d="M30 0L60 30L30 60L0 30z" fill="%23D4AF37" opacity="0.02"/></svg>');
    }

    .page-banner::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, transparent, #D4AF37, transparent);
    }

    .page-banner .container {
        position: relative;
        z-index: 1;
    }

    .banner-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #D4AF37, #B8960B);
        color: white;
        padding: 8px 20px;
        border-radius: 30px;
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 20px;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4);
    }

    .page-banner h1 {
        font-family: 'Playfair Display', serif;
        font-size: 48px;
        font-weight: 700;
        color: white;
        margin: 0 0 20px 0;
        text-shadow: 0 2px 20px rgba(0, 0, 0, 0.3);
        line-height: 1.2;
    }

    .page-banner .price-banner {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        background: linear-gradient(135deg, #D4AF37, #f0d078);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 25px;
        display: inline-block;
    }

    .page-banner .car-meta-banner {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .page-banner .meta-item {
        display: flex;
        align-items: center;
        gap: 10px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 15px;
        font-weight: 500;
        padding: 10px 18px;
        background: rgba(255, 255, 255, 0.08);
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s;
    }

    .page-banner .meta-item:hover {
        background: rgba(212, 175, 55, 0.2);
        border-color: rgba(212, 175, 55, 0.3);
        transform: translateY(-2px);
    }

    .page-banner .meta-item i {
        color: #D4AF37;
        font-size: 16px;
    }

    /* Add to cart button */
    .btn-cart {
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: white;
    }

    .btn-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(34, 197, 94, 0.3);
    }
</style>

<!-- Banner -->
<div class="page-banner">
    <div class="container">
        <div class="banner-badge">
            <i class="fas fa-star"></i>
            <?php
            $stock = $car['stock'] ?? 0;
            if ($car['status'] == 'available' && $stock > 0) {
                echo 'Còn hàng (' . $stock . ' xe)';
            } else {
                echo 'Đã hết hàng';
            }
            ?>
        </div>
        <h1><?= htmlspecialchars($car['name']) ?></h1>
        <div class="price-banner"><?= formatPrice($car['price']) ?> VND</div>
        <div class="car-meta-banner">
            <div class="meta-item">
                <i class="fas fa-building"></i>
                <?= htmlspecialchars($car['brand_name']) ?>
            </div>
            <div class="meta-item">
                <i class="fas fa-tag"></i>
                <?= htmlspecialchars($car['category_name']) ?>
            </div>
            <div class="meta-item">
                <i class="fas fa-calendar-alt"></i>
                <?= htmlspecialchars($car['year']) ?>
            </div>
            <div class="meta-item">
                <i class="fas fa-tachometer-alt"></i>
                <?= number_format($car['mileage']) ?> km
            </div>
            <div class="meta-item">
                <i class="fas fa-palette"></i>
                <?= htmlspecialchars($car['color']) ?>
            </div>
        </div>
    </div>
</div>

<div class="car-detail">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="<?= BASE_URL ?>"><i class="fas fa-home"></i> Trang chủ</a>
            <span>/</span>
            <a href="<?= BASE_URL ?>/user/car_list">Danh sách xe</a>
            <span>/</span>
            <span><?= htmlspecialchars($car['name']) ?></span>
        </div>

        <!-- Main Content -->
        <div class="detail-grid">
            <!-- Gallery -->
            <div class="card">
                <?php
                // Lấy ảnh chính - kiểm tra nếu là URL đầy đủ hay đường dẫn local
                $mainImage = '';
                if (!empty($carImages) && count($carImages) > 0) {
                    $mainImage = $carImages[0]['image_url'];
                }

                // Hàm kiểm tra URL đầy đủ
                function isFullUrl($url)
                {
                    return strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0;
                }

                $mainImageSrc = $mainImage ? (isFullUrl($mainImage) ? $mainImage : BASE_URL . '/' . $mainImage) : BASE_URL . '/assets/images/no-image.jpg';
                ?>
                <div class="main-image">
                    <img src="<?= htmlspecialchars($mainImageSrc) ?>" alt="<?= htmlspecialchars($car['name']) ?>" id="mainImage">
                </div>
                <div class="thumbnail-grid">
                    <?php
                    if (!empty($carImages) && is_array($carImages)) :
                        foreach ($carImages as $index => $imageData) :
                            $thumbSrc = isFullUrl($imageData['image_url']) ? $imageData['image_url'] : BASE_URL . '/' . $imageData['image_url'];
                    ?>
                            <div class="thumbnail <?= $index === 0 ? 'active' : '' ?>" onclick="changeImage('<?= htmlspecialchars($thumbSrc) ?>', this)">
                                <img src="<?= htmlspecialchars($thumbSrc) ?>" alt="Image <?= $index + 1 ?>">
                            </div>
                        <?php
                        endforeach;
                    else:
                        ?>
                        <div class="thumbnail active">
                            <img src="<?= BASE_URL ?>/assets/images/no-image.jpg" alt="No image">
                        </div>
                    <?php
                    endif;
                    ?>
                </div>
            </div>

            <!-- Info -->
            <div class="card">
                <h1 class="car-name"><?= htmlspecialchars($car['name']) ?></h1>

                <div class="car-meta">
                    <div class="meta-item">
                        <i class="fas fa-car"></i>
                        <?= htmlspecialchars($car['brand_name']) ?>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <?= htmlspecialchars($car['category_name']) ?>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <?= htmlspecialchars($car['year']) ?>
                    </div>
                </div>

                <div class="car-price"><?= formatPrice($car['price']) ?> VND</div>

                <div class="highlights">
                    <div class="highlight-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <span><?= number_format($car['mileage']) ?> km</span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-gas-pump"></i>
                        <span><?= getFuelText($car['fuel']) ?></span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-cog"></i>
                        <span><?= getTransmissionText($car['transmission']) ?></span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-palette"></i>
                        <span><?= htmlspecialchars($car['color']) ?></span>
                    </div>
                </div>

                <?php
                $stock = $car['stock'] ?? 0;
                if ($car['status'] == 'available' && $stock > 0) :
                ?>
                    <div class="action-buttons">
                        <a href="<?= BASE_URL ?>/user/add_to_cart?car_id=<?= $car['id'] ?>" class="btn btn-cart" onclick="addToCart(<?= $car['id'] ?>); return false;">
                            <i class="fas fa-shopping-cart"></i>
                            Thêm vào giỏ hàng
                        </a>
                        <a href="<?= BASE_URL ?>/appointment/book/<?= $car['id'] ?>" class="btn btn-primary">
                            <i class="fas fa-calendar-check"></i>
                            Đặt lịch xem xe
                        </a>
                        <a href="#" onclick="toggleFavorite(<?= $car['id'] ?>); return false;" class="btn btn-outline">
                            <i class="<?= $isFavorite ? 'fas' : 'far' ?> fa-heart"></i>
                            <?= $isFavorite ? 'Đã yêu thích' : 'Yêu thích' ?>
                        </a>
                    </div>
                <?php else : ?>
                    <div class="action-buttons">
                        <button class="btn btn-primary" disabled style="opacity: 0.5;">
                            <i class="fas fa-ban"></i>
                            Xe đã hết hàng
                        </button>
                    </div>
                <?php endif; ?>

                <div class="contact-box">
                    <h4><i class="fas fa-headset"></i> Cần tư vấn?</h4>
                    <p>Liên hệ với chúng tôi để được hỗ trợ</p>
                    <a href="<?= BASE_URL ?>/user/contact">Liên hệ ngay <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="switchTab(event, 'specs')">
                    <i class="fas fa-list-ul"></i> Thông số kỹ thuật
                </button>
                <button class="tab-btn" onclick="switchTab(event, 'description')">
                    <i class="fas fa-align-left"></i> Mô tả
                </button>
                <button class="tab-btn" onclick="switchTab(event, 'reviews')">
                    <i class="fas fa-star"></i> Đánh giá (<?= count($reviews) ?>)
                </button>
            </div>

            <!-- Specifications -->
            <div id="specs" class="tab-content active">
                <div class="specs-grid">
                    <div class="spec-item">
                        <span class="spec-label">Tên xe</span>
                        <span class="spec-value"><?= htmlspecialchars($car['name']) ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Thương hiệu</span>
                        <span class="spec-value"><?= htmlspecialchars($car['brand_name']) ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Danh mục</span>
                        <span class="spec-value"><?= htmlspecialchars($car['category_name']) ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Năm sản xuất</span>
                        <span class="spec-value"><?= htmlspecialchars($car['year']) ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Giá bán</span>
                        <span class="spec-value"><?= formatPrice($car['price']) ?> VND</span>
                    </div>
                    <?php if (!empty($car['stock'])) : ?>
                        <div class="spec-item">
                            <span class="spec-label">Số lượng</span>
                            <span class="spec-value"><?= htmlspecialchars($car['stock']) ?> xe</span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($car['engine'])) : ?>
                        <div class="spec-item">
                            <span class="spec-label">Động cơ</span>
                            <span class="spec-value"><?= htmlspecialchars($car['engine']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($car['horsepower'])) : ?>
                        <div class="spec-item">
                            <span class="spec-label">Công suất</span>
                            <span class="spec-value"><?= htmlspecialchars($car['horsepower']) ?> HP</span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($car['torque'])) : ?>
                        <div class="spec-item">
                            <span class="spec-label">Mô-men xoắn</span>
                            <span class="spec-value"><?= htmlspecialchars($car['torque']) ?> Nm</span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($car['acceleration'])) : ?>
                        <div class="spec-item">
                            <span class="spec-label">Tăng tốc 0-100 km/h</span>
                            <span class="spec-value"><?= htmlspecialchars($car['acceleration']) ?> giây</span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($car['drivetrain'])) : ?>
                        <div class="spec-item">
                            <span class="spec-label">Hệ dẫn động</span>
                            <span class="spec-value"><?= getDrivetrainText($car['drivetrain']) ?></span>
                        </div>
                    <?php endif; ?>
                    <div class="spec-item">
                        <span class="spec-label">Hộp số</span>
                        <span class="spec-value"><?= getTransmissionText($car['transmission']) ?></span>
                    </div>
                    <?php if (!empty($car['seats'])) : ?>
                        <div class="spec-item">
                            <span class="spec-label">Số chỗ ngồi</span>
                            <span class="spec-value"><?= htmlspecialchars($car['seats']) ?> chỗ</span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($car['doors'])) : ?>
                        <div class="spec-item">
                            <span class="spec-label">Số cửa</span>
                            <span class="spec-value"><?= htmlspecialchars($car['doors']) ?> cửa</span>
                        </div>
                    <?php endif; ?>
                    <div class="spec-item">
                        <span class="spec-label">Nhiên liệu</span>
                        <span class="spec-value"><?= getFuelText($car['fuel']) ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Số km đã đi</span>
                        <span class="spec-value"><?= number_format($car['mileage']) ?> km</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Màu sắc</span>
                        <span class="spec-value"><?= htmlspecialchars($car['color']) ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Số lượng tồn</span>
                        <span class="spec-value"><?= $car['stock'] ?? 1 ?> xe</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Trạng thái</span>
                        <span class="spec-value">
                            <?php
                            $stock = $car['stock'] ?? 0;
                            if ($car['status'] == 'available' && $stock > 0) {
                                echo 'Còn hàng (' . $stock . ' xe)';
                            } else {
                                echo 'Đã hết hàng';
                            }
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div id="description" class="tab-content">
                <div class="description">
                    <?= nl2br(htmlspecialchars($car['description'])) ?>
                </div>
            </div>

            <!-- Reviews -->
            <div id="reviews" class="tab-content">
                <?php if (!empty($reviews)) : ?>
                    <div class="review-summary">
                        <div class="rating-box">
                            <div class="rating-score">
                                <?php
                                $totalRating = 0;
                                foreach ($reviews as $review) {
                                    $totalRating += $review['rating'];
                                }
                                $avgRating = round($totalRating / count($reviews), 1);
                                echo $avgRating;
                                ?>
                            </div>
                            <div class="rating-stars">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $avgRating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                }
                                ?>
                            </div>
                            <div class="rating-count"><?= count($reviews) ?> đánh giá</div>
                        </div>
                        <div class="review-list">
                            <?php foreach ($reviews as $review) : ?>
                                <div class="review-item">
                                    <div class="review-header">
                                        <div>
                                            <div class="reviewer-name"><?= htmlspecialchars($review['user_name']) ?></div>
                                            <div class="review-date"><?= date('d/m/Y', strtotime($review['created_at'])) ?></div>
                                        </div>
                                        <div class="review-rating">
                                            <?php
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $review['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="review-text">
                                        <?= nl2br(htmlspecialchars($review['comment'])) ?>
                                    </div>
                                    <?php if (!empty($review['admin_reply'])) : ?>
                                        <div class="admin-reply" style="margin-top: 15px; padding: 15px; background: linear-gradient(135deg, #fef9ed 0%, #fffbf5 100%); border-radius: 8px; border-left: 3px solid #D4AF37;">
                                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                                <i class="fas fa-reply" style="color: #D4AF37;"></i>
                                                <span style="font-weight: 600; color: #B8860B;">Phản hồi từ AutoCar</span>
                                                <?php if (!empty($review['replied_at'])) : ?>
                                                    <span style="font-size: 12px; color: #999; margin-left: auto;"><?= date('d/m/Y H:i', strtotime($review['replied_at'])) ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div style="color: #555; font-size: 14px; line-height: 1.6;">
                                                <?= nl2br(htmlspecialchars($review['admin_reply'])) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="no-reviews">
                        <i class="fas fa-comment-slash" style="font-size: 48px; color: #ddd; margin-bottom: 15px;"></i>
                        <p>Chưa có đánh giá nào</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Similar Cars -->
        <?php if (!empty($similarCars)) : ?>
            <div class="similar-section">
                <h2 class="section-title">Xe tương tự</h2>
                <div class="similar-grid">
                    <?php foreach ($similarCars as $similarCar) :
                        // Lấy ảnh đầu tiên của xe tương tự từ image_url
                        $similarImage = !empty($similarCar['image_url']) ? $similarCar['image_url'] : '';
                        $similarImgSrc = $similarImage ? (isFullUrl($similarImage) ? $similarImage : BASE_URL . '/' . $similarImage) : BASE_URL . '/assets/images/no-image.jpg';
                    ?>
                        <a href="<?= BASE_URL ?>/car/<?= $similarCar['id'] ?>" class="similar-car">
                            <div class="similar-car-image">
                                <img src="<?= htmlspecialchars($similarImgSrc) ?>" alt="<?= htmlspecialchars($similarCar['name']) ?>">
                            </div>
                            <div class="similar-car-info">
                                <div class="similar-car-name"><?= htmlspecialchars($similarCar['name']) ?></div>
                                <div class="similar-car-price"><?= formatPrice($similarCar['price']) ?> VND</div>
                                <div class="similar-car-meta">
                                    <span><i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($similarCar['year']) ?></span>
                                    <span><i class="fas fa-tachometer-alt"></i> <?= number_format($similarCar['mileage'] / 1000) ?>K km</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Change main image
    function changeImage(src, element) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        element.classList.add('active');
    }

    // Switch tabs
    function switchTab(event, tabName) {
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));

        document.getElementById(tabName).classList.add('active');
        event.currentTarget.classList.add('active');
    }

    // Add to cart
    function addToCart(carId) {
        <?php if (!isset($_SESSION['user_id'])) : ?>
            alert('Vui lòng đăng nhập để sử dụng tính năng này!');
            window.location.href = '<?= BASE_URL ?>/auth/login';
            return;
        <?php endif; ?>

        fetch('<?= BASE_URL ?>/user/add_to_cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'car_id=' + carId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã thêm xe vào giỏ hàng!');
                    // Cập nhật số lượng giỏ hàng trên header nếu có
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount && data.cart_count) {
                        cartCount.textContent = data.cart_count;
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi thêm vào giỏ hàng!');
            });
    }

    // Toggle favorite
    function toggleFavorite(carId) {
        <?php if (!isset($_SESSION['user_id'])) : ?>
            alert('Vui lòng đăng nhập để sử dụng tính năng này!');
            window.location.href = '<?= BASE_URL ?>/auth/login';
            return;
        <?php endif; ?>

        // Kiểm tra trạng thái yêu thích hiện tại
        const isFavorite = <?= $isFavorite ? 'true' : 'false' ?>;
        const url = isFavorite ? '<?= BASE_URL ?>/favorites/remove' : '<?= BASE_URL ?>/favorites/add';

        fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    car_id: carId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra!');
            });
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>