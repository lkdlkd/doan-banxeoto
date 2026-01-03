<?php
$currentPage = 'cars';

// Lấy ID xe từ URL
$carId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Kết nối cơ sở dữ liệu
require_once __DIR__ . '/../../models/CarModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';

$carModel = new CarModel();
$reviewModel = new ReviewModel();

// Lấy thông tin chi tiết xe
$car = $carModel->getById($carId);

if (!$car) {
    header('Location: /cars');
    exit;
}

// Lấy đánh giá của xe
$reviews = $reviewModel->getByCarId($carId);

// Lấy xe tương tự (cùng thương hiệu hoặc danh mục)
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

include __DIR__ . '/../layouts/header.php';
?>

<style>
    /* Page Banner */
    .page-banner {
        position: relative;
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        padding: 60px 0 40px;
        overflow: hidden;
    }

    .page-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><path d="M0 0h100v100H0z" fill="none"/><path d="M50 0L100 50L50 100L0 50z" fill="%23D4AF37" opacity="0.03"/></svg>');
    }

    .banner-content {
        position: relative;
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
    }

    .breadcrumb a {
        color: #D4AF37;
        text-decoration: none;
        transition: opacity 0.3s;
    }

    .breadcrumb a:hover {
        opacity: 0.8;
    }

    .breadcrumb .divider {
        color: rgba(255, 255, 255, 0.3);
    }

    .breadcrumb .current {
        color: rgba(255, 255, 255, 0.6);
    }

    /* Car Detail */
    .car-detail-section {
        padding: 60px 0;
        background: #f5f2ed;
    }

    .container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
    }

    .car-detail-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 40px;
        margin-bottom: 50px;
    }

    /* Gallery */
    .car-gallery {
        background: #fff;
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    .main-image {
        width: 100%;
        height: 450px;
        border-radius: 15px;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .main-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .thumbnail-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }

    .thumbnail {
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        cursor: pointer;
        border: 3px solid transparent;
        transition: all 0.3s;
    }

    .thumbnail:hover,
    .thumbnail.active {
        border-color: #D4AF37;
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Info */
    .car-info {
        background: #fff;
        border-radius: 20px;
        padding: 35px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }

    .car-info h1 {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    .car-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #f5f5f5;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #666;
    }

    .meta-item i {
        color: #D4AF37;
    }

    .car-price {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: #D4AF37;
        margin-bottom: 25px;
    }

    .car-price small {
        font-size: 16px;
        color: #999;
        font-weight: 400;
    }

    .car-highlights {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 30px;
    }

    .highlight-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        background: #f9f7f3;
        border-radius: 10px;
    }

    .highlight-item i {
        font-size: 20px;
        color: #D4AF37;
    }

    .highlight-item span {
        font-size: 14px;
        color: #333;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-bottom: 25px;
    }

    .btn {
        padding: 16px 30px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        justify-content: center;
        transition: all 0.3s;
        cursor: pointer;
        border: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        color: #fff;
        flex: 1;
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
    }

    .btn-outline {
        background: #fff;
        color: #D4AF37;
        border: 2px solid #D4AF37;
    }

    .btn-outline:hover {
        background: #D4AF37;
        color: #fff;
    }

    .contact-seller {
        padding: 20px;
        background: #f9f7f3;
        border-radius: 12px;
        border-left: 4px solid #D4AF37;
    }

    .contact-seller h4 {
        font-size: 16px;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .contact-seller p {
        font-size: 14px;
        color: #666;
        margin-bottom: 15px;
    }

    .contact-seller a {
        color: #D4AF37;
        text-decoration: none;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .contact-seller a:hover {
        opacity: 0.8;
    }

    /* Tabs */
    .car-tabs {
        margin-top: 50px;
    }

    .tab-buttons {
        display: flex;
        gap: 10px;
        margin-bottom: 30px;
        border-bottom: 2px solid #eee;
    }

    .tab-btn {
        padding: 15px 30px;
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
        gap: 20px;
        background: #fff;
        padding: 30px;
        border-radius: 15px;
    }

    .spec-item {
        display: flex;
        justify-content: space-between;
        padding: 15px;
        background: #f9f7f3;
        border-radius: 10px;
    }

    .spec-label {
        font-weight: 600;
        color: #333;
    }

    .spec-value {
        color: #666;
    }

    .description {
        background: #fff;
        padding: 30px;
        border-radius: 15px;
        line-height: 1.8;
        color: #555;
    }

    /* Reviews */
    .reviews-section {
        background: #fff;
        padding: 30px;
        border-radius: 15px;
    }

    .review-summary {
        display: flex;
        gap: 40px;
        margin-bottom: 30px;
        padding-bottom: 30px;
        border-bottom: 2px solid #f5f5f5;
    }

    .rating-overview {
        text-align: center;
    }

    .rating-score {
        font-size: 48px;
        font-weight: 700;
        color: #D4AF37;
    }

    .rating-stars {
        color: #D4AF37;
        font-size: 20px;
        margin: 10px 0;
    }

    .rating-count {
        font-size: 14px;
        color: #999;
    }

    .review-card {
        padding: 20px;
        border-bottom: 1px solid #f5f5f5;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .reviewer-name {
        font-weight: 600;
        color: #333;
    }

    .review-date {
        font-size: 13px;
        color: #999;
    }

    .review-stars {
        color: #D4AF37;
        margin-bottom: 10px;
    }

    .review-text {
        color: #666;
        line-height: 1.6;
    }

    /* Similar Cars */
    .similar-cars {
        margin-top: 60px;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        color: #1a1a1a;
        text-align: center;
        margin-bottom: 40px;
    }

    .cars-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
    }

    .car-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        text-decoration: none;
        display: block;
    }

    .car-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
    }

    .car-card-image {
        height: 200px;
        overflow: hidden;
    }

    .car-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .car-card-body {
        padding: 20px;
    }

    .car-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 8px;
    }

    .car-card-meta {
        font-size: 13px;
        color: #999;
        margin-bottom: 12px;
    }

    .car-card-price {
        font-size: 18px;
        font-weight: 700;
        color: #D4AF37;
    }

    @media (max-width: 1024px) {
        .car-detail-grid {
            grid-template-columns: 1fr;
        }

        .cars-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .cars-grid {
            grid-template-columns: 1fr;
        }

        .specs-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<!-- Page Banner -->
<section class="page-banner">
    <div class="banner-content">
        <div class="breadcrumb">
            <a href="/">Trang Chủ</a>
            <span class="divider">›</span>
            <a href="/cars">Danh Sách Xe</a>
            <span class="divider">›</span>
            <span class="current"><?= htmlspecialchars($car['name']) ?></span>
        </div>
    </div>
</section>

<!-- Car Detail Section -->
<section class="car-detail-section">
    <div class="container">
        <div class="car-detail-grid">
            <!-- Gallery -->
            <div class="car-gallery">
                <?php
                // Lấy hình ảnh từ car_images
                $carImages = $carModel->getImages($car['id']);
                $mainImage = !empty($carImages) ? $carImages[0]['image_url'] : 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800';
                ?>
                <div class="main-image">
                    <img id="mainImage" src="<?= htmlspecialchars($mainImage) ?>" alt="<?= htmlspecialchars($car['name']) ?>">
                </div>
                <div class="thumbnail-grid">
                    <?php if (!empty($carImages)): ?>
                        <?php foreach (array_slice($carImages, 0, 4) as $index => $img): ?>
                            <div class="thumbnail <?= $index === 0 ? 'active' : '' ?>" onclick="changeImage(this, '<?= htmlspecialchars($img['image_url']) ?>')">
                                <img src="<?= htmlspecialchars($img['image_url']) ?>" alt="View <?= $index + 1 ?>">
                            </div>
                        <?php endforeach; ?>
                        <?php 
                        // Nếu có ít hơn 4 ảnh, điền thêm từ placeholder
                        $imageCount = count($carImages);
                        if ($imageCount < 1) {
                            $placeholders = [
                                'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800',
                                'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800',
                                'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800',
                                'https://images.unsplash.com/photo-1542282088-fe8426682b8f?w=800'
                            ];
                            for ($i = $imageCount; $i < 4; $i++): ?>
                                <div class="thumbnail" onclick="changeImage(this, '<?= $placeholders[$i] ?>')">
                                    <img src="<?= str_replace('w=800', 'w=200', $placeholders[$i]) ?>" alt="View <?= $i + 1 ?>">
                                </div>
                            <?php endfor;
                        }
                        ?>
                    <?php else: ?>
                        <?php 
                        // Nếu không có ảnh nào từ DB, dùng placeholder
                        $placeholders = [
                            'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800',
                            'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800',
                            'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800',
                            'https://images.unsplash.com/photo-1542282088-fe8426682b8f?w=800'
                        ];
                        foreach ($placeholders as $index => $placeholder): ?>
                            <div class="thumbnail <?= $index === 0 ? 'active' : '' ?>" onclick="changeImage(this, '<?= $placeholder ?>')">
                                <img src="<?= str_replace('w=800', 'w=200', $placeholder) ?>" alt="View <?= $index + 1 ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Info -->
            <div class="car-info">
                <h1><?= htmlspecialchars($car['name']) ?></h1>
                <div class="car-meta">
                    <div class="meta-item">
                        <i class="fas fa-copyright"></i>
                        <span><?= htmlspecialchars($car['brand_name'] ?? 'N/A') ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <span><?= htmlspecialchars($car['category_name'] ?? 'N/A') ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span><?= htmlspecialchars($car['year'] ?? 'N/A') ?></span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-star"></i>
                        <span>4.8 (<?= count($reviews) ?> đánh giá)</span>
                    </div>
                </div>

                <div class="car-price">
                    <?= formatPrice($car['price']) ?> <small>VNĐ</small>
                </div>

                <div class="car-highlights">
                    <div class="highlight-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <span><?= isset($car['mileage']) ? number_format($car['mileage']) . ' km' : '0 km' ?></span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-cog"></i>
                        <span><?php
                                if (isset($car['transmission'])) {
                                    echo $car['transmission'] == 'automatic' ? 'Tự động' : 'Số sàn';
                                } else {
                                    echo 'N/A';
                                }
                                ?></span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-gas-pump"></i>
                        <span><?php
                                if (isset($car['fuel'])) {
                                    $fuelMap = ['gasoline' => 'Xăng', 'diesel' => 'Dầu diesel', 'electric' => 'Điện'];
                                    echo $fuelMap[$car['fuel']] ?? 'N/A';
                                } else {
                                    echo 'N/A';
                                }
                                ?></span>
                    </div>
                    <div class="highlight-item">
                        <i class="fas fa-palette"></i>
                        <span><?= htmlspecialchars($car['color'] ?? 'N/A') ?></span>
                    </div>
                </div>

                <div class="action-buttons">
                    <button class="btn btn-primary btn-add-cart" onclick="addToCart(<?= $car['id'] ?>, this)">
                        <i class="fas fa-shopping-cart"></i>
                        Thêm Vào Giỏ Hàng
                    </button>
                    <a href="/cart" class="btn btn-outline">
                        <i class="fas fa-eye"></i>
                        Xem Giỏ Hàng
                    </a>
                    <button class="btn btn-outline" onclick="addToFavorite(<?= $car['id'] ?>)">
                        <i class="far fa-heart"></i>
                    </button>
                </div>

                <div class="contact-seller">
                    <h4>Cần tư vấn thêm?</h4>
                    <p>Liên hệ với chúng tôi để được hỗ trợ chi tiết về chiếc xe này</p>
                    <a href="/contact">
                        <i class="fas fa-phone"></i>
                        0368 920 249
                    </a>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="car-tabs">
            <div class="tab-buttons">
                <button class="tab-btn active" onclick="switchTab('specs')">Thông Số Kỹ Thuật</button>
                <button class="tab-btn" onclick="switchTab('description')">Mô Tả</button>
                <button class="tab-btn" onclick="switchTab('reviews')">Đánh Giá (<?= count($reviews) ?>)</button>
            </div>

            <div id="specs" class="tab-content active">
                <div class="specs-grid">
                    <div class="spec-item">
                        <span class="spec-label">Hãng xe:</span>
                        <span class="spec-value"><?= htmlspecialchars($car['brand_name'] ?? 'N/A') ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Loại xe:</span>
                        <span class="spec-value"><?= htmlspecialchars($car['category_name'] ?? 'N/A') ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Năm sản xuất:</span>
                        <span class="spec-value"><?= htmlspecialchars($car['year'] ?? 'N/A') ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Số km đã đi:</span>
                        <span class="spec-value"><?= isset($car['mileage']) ? number_format($car['mileage']) . ' km' : 'N/A' ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Hộp số:</span>
                        <span class="spec-value"><?php
                                                    if (isset($car['transmission'])) {
                                                        echo $car['transmission'] == 'automatic' ? 'Tự động' : 'Số sàn';
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Nhiên liệu:</span>
                        <span class="spec-value"><?php
                                                    if (isset($car['fuel'])) {
                                                        $fuelMap = ['gasoline' => 'Xăng', 'diesel' => 'Dầu diesel', 'electric' => 'Điện'];
                                                        echo $fuelMap[$car['fuel']] ?? 'N/A';
                                                    } else {
                                                        echo 'N/A';
                                                    }
                                                    ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Màu sắc:</span>
                        <span class="spec-value"><?= htmlspecialchars($car['color'] ?? 'N/A') ?></span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Tình trạng:</span>
                        <span class="spec-value"><?= isset($car['status']) && $car['status'] === 'available' ? 'Còn hàng' : 'Đã bán' ?></span>
                    </div>
                </div>
            </div>

            <div id="description" class="tab-content">
                <div class="description">
                    <?= nl2br(htmlspecialchars($car['description'] ?? 'Chưa có mô tả chi tiết cho xe này.')) ?>
                </div>
            </div>

            <div id="reviews" class="tab-content">
                <div class="reviews-section">
                    <?php if (!empty($reviews)): ?>
                        <div class="review-summary">
                            <div class="rating-overview">
                                <div class="rating-score">4.8</div>
                                <div class="rating-stars">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </div>
                                <div class="rating-count"><?= count($reviews) ?> đánh giá</div>
                            </div>
                        </div>

                        <?php foreach ($reviews as $review): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <span class="reviewer-name"><?= htmlspecialchars($review['user_name']) ?></span>
                                    <span class="review-date"><?= date('d/m/Y', strtotime($review['created_at'])) ?></span>
                                </div>
                                <div class="review-stars">
                                    <?php for ($i = 0; $i < 5; $i++): ?>
                                        <i class="<?= $i < $review['rating'] ? 'fas' : 'far' ?> fa-star"></i>
                                    <?php endfor; ?>
                                </div>
                                <div class="review-text"><?= nl2br(htmlspecialchars($review['comment'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p style="text-align: center; color: #999; padding: 40px;">Chưa có đánh giá nào cho xe này.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Similar Cars -->
        <?php if (!empty($similarCars)): ?>
            <div class="similar-cars">
                <h2 class="section-title">Xe Tương Tự</h2>
                <div class="cars-grid">
                    <?php foreach ($similarCars as $similarCar): ?>
                        <?php
                        $similarImages = $carModel->getImages($similarCar['id']);
                        $similarImage = !empty($similarImages) ? $similarImages[0]['image_url'] : 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=400';
                        ?>
                        <a href="/car/<?= $similarCar['id'] ?>" class="car-card">
                            <div class="car-card-image">
                                <img src="<?= htmlspecialchars($similarImage) ?>" alt="<?= htmlspecialchars($similarCar['name']) ?>">
                            </div>
                            <div class="car-card-body">
                                <h3 class="car-card-title"><?= htmlspecialchars($similarCar['name']) ?></h3>
                                <p class="car-card-meta"><?= htmlspecialchars($similarCar['brand_name'] ?? 'N/A') ?> • <?= $similarCar['year'] ?? 'N/A' ?></p>
                                <div class="car-card-price"><?= formatPrice($similarCar['price'] ?? 0) ?> VNĐ</div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
    function changeImage(thumbnail, imageUrl) {
        document.getElementById('mainImage').src = imageUrl;
        document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
        thumbnail.classList.add('active');
    }

    function switchTab(tabName) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
        event.target.classList.add('active');
        document.getElementById(tabName).classList.add('active');
    }

    function addToFavorite(carId) {
        <?php if (isset($_SESSION['user'])): ?>
            fetch('/favorite/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        car_id: carId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Đã thêm vào yêu thích!');
                    } else {
                        alert(data.message || 'Có lỗi xảy ra!');
                    }
                });
        <?php else: ?>
            alert('Vui lòng đăng nhập để thêm vào yêu thích!');
            window.location.href = '/login';
        <?php endif; ?>
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>