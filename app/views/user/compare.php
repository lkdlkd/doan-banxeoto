<?php
$currentPage = 'compare';
$pageTitle = 'So Sánh Xe - AutoCar';

// Load Compare Model
require_once __DIR__ . '/../../models/CompareModel.php';
$compareModel = new CompareModel();

// Xử lý action
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'remove' && isset($_GET['id'])) {
        $compareModel->removeFromCompare($_GET['id']);
        header('Location: /compare');
        exit;
    }
    if ($_GET['action'] === 'clear') {
        $compareModel->clearCompare();
        header('Location: /compare');
        exit;
    }
}

// Lấy danh sách xe so sánh
$compareItems = $compareModel->getCompareItems();
$compareCount = $compareModel->getCompareCount();
$maxCompare = $compareModel->getMaxCompare();

// Hàm format tiền
function formatPrice($price) {
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
/* Banner */
.compare-banner {
    position: relative;
    height: 280px;
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), 
                url('https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=1920&q=80') center/cover;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: -60px;
}

.compare-banner-content {
    text-align: center;
    color: #fff;
    position: relative;
    z-index: 1;
}

.compare-banner h1 {
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.compare-banner h1 span {
    color: #D4AF37;
}

.compare-banner p {
    font-size: 18px;
    color: rgba(255,255,255,0.9);
    text-shadow: 0 1px 5px rgba(0,0,0,0.3);
}

.compare-page {
    background: linear-gradient(135deg, #f9f7f3 0%, #f5f2ed 100%);
    min-height: 100vh;
    padding-bottom: 80px;
}

.container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 30px;
    position: relative;
    z-index: 2;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    padding: 25px 30px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.page-title {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #1a1a1a;
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 0;
}

.page-title svg {
    color: #D4AF37;
}

.item-count {
    font-size: 16px;
    color: #666;
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
}

.btn-clear {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 24px;
    background: transparent;
    border: 2px solid #ef4444;
    color: #ef4444;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s;
}

.btn-clear:hover {
    background: #ef4444;
    color: #fff;
}

/* Empty State */
.empty-compare {
    text-align: center;
    padding: 100px 40px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.empty-icon svg {
    color: rgba(212, 175, 55, 0.3);
    margin-bottom: 30px;
}

.empty-compare h2 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    color: #1a1a1a;
    margin-bottom: 15px;
}

.empty-compare p {
    color: #666;
    font-size: 16px;
    margin-bottom: 35px;
}

.btn-explore {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    text-decoration: none;
    font-weight: 700;
    border-radius: 8px;
    transition: all 0.3s;
}

.btn-explore:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
}

/* Need More Cars */
.need-more {
    display: flex;
    gap: 40px;
    align-items: center;
    padding: 50px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.current-car {
    flex: 1;
}

.add-more-prompt {
    flex: 1;
    text-align: center;
    padding: 40px;
    border: 3px dashed rgba(212, 175, 55, 0.3);
    border-radius: 12px;
    background: rgba(212, 175, 55, 0.05);
}

.add-more-prompt svg {
    color: #D4AF37;
    margin-bottom: 20px;
}

.add-more-prompt h3 {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.add-more-prompt p {
    color: #666;
    margin-bottom: 25px;
}

.btn-add {
    display: inline-block;
    padding: 12px 28px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    text-decoration: none;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s;
}

.btn-add:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
}

/* Compare Cards */
.compare-card {
    position: relative;
    background: #fff;
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s;
}

.compare-card:hover {
    border-color: #D4AF37;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.2);
}

.btn-remove-card {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 36px;
    height: 36px;
    background: rgba(239, 68, 68, 0.95);
    border: none;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s;
}

.btn-remove-card:hover {
    background: #ef4444;
    transform: scale(1.1);
}

.card-image {
    height: 200px;
    overflow: hidden;
    background: #f0f0f0;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s;
}

.compare-card:hover .card-image img {
    transform: scale(1.05);
}

.card-info {
    padding: 20px;
    text-align: center;
}

.brand-logo {
    width: 50px;
    height: auto;
    margin: 0 auto 10px;
    display: block;
}

.card-brand {
    font-size: 12px;
    color: #D4AF37;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 700;
    display: block;
    margin-bottom: 8px;
}

.card-name {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    color: #1a1a1a;
    margin: 0;
    font-weight: 700;
}

.card-name a {
    color: inherit;
    text-decoration: none;
    transition: color 0.3s;
}

.card-name a:hover {
    color: #D4AF37;
}

/* Compare Table */
.compare-wrapper {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 40px;
}

.compare-table {
    width: 100%;
    overflow-x: auto;
}

.compare-row {
    display: flex;
    border-bottom: 2px solid #f0f0f0;
    transition: background 0.2s;
}

.compare-row:last-child {
    border-bottom: none;
}

.compare-row:hover {
    background: rgba(212, 175, 55, 0.02);
}

.compare-label {
    flex: 0 0 220px;
    padding: 20px 25px;
    background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    border-right: 2px solid #e5e5e5;
    font-weight: 700;
    color: #1a1a1a;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.compare-label svg {
    color: #D4AF37;
    flex-shrink: 0;
}

.compare-cell {
    flex: 1;
    padding: 20px 25px;
    border-right: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: #333;
    font-size: 15px;
}

.compare-cell:last-child {
    border-right: none;
}

/* Images Row */
.images-row {
    background: #fff;
    border-bottom: 3px solid #D4AF37;
}

.images-row .compare-label {
    background: transparent;
}

.images-row .compare-cell {
    padding: 0;
}

.images-row .compare-card {
    width: 100%;
    border: none;
    border-radius: 0;
}

/* Price Row */
.highlight-row {
    background: linear-gradient(135deg, rgba(212,175,55,0.08) 0%, rgba(212,175,55,0.05) 100%);
}

.highlight-row .compare-label {
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    font-size: 15px;
}

.price-cell {
    flex-direction: column;
    gap: 5px;
}

.price-value {
    font-weight: 700;
    font-size: 24px;
    color: #D4AF37;
    font-family: 'Playfair Display', serif;
}

.price-unit {
    font-size: 13px;
    color: #666;
}

/* Add Cell */
.add-cell {
    background: rgba(212, 175, 55, 0.05);
}

.add-car-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 12px;
    padding: 40px 20px;
    color: #D4AF37;
    text-decoration: none;
    width: 100%;
    height: 100%;
    transition: all 0.3s;
    font-weight: 600;
}

.add-car-btn:hover {
    background: rgba(212, 175, 55, 0.1);
}

.add-car-btn svg {
    transition: transform 0.3s;
}

.add-car-btn:hover svg {
    transform: scale(1.1);
}

/* Status Badge */
.status-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.available {
    background: linear-gradient(135deg, rgba(34,197,94,0.15), rgba(34,197,94,0.1));
    color: #16a34a;
    border: 1px solid rgba(34,197,94,0.3);
}

.status-badge.sold {
    background: linear-gradient(135deg, rgba(239,68,68,0.15), rgba(239,68,68,0.1));
    color: #dc2626;
    border: 1px solid rgba(239,68,68,0.3);
}

/* Actions Row */
.actions-row {
    background: #fafafa;
}

.actions-row .compare-cell {
    flex-direction: column;
    gap: 12px;
    padding: 25px;
}

.btn-add-cart,
.btn-view-detail {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s;
    width: 100%;
    border: none;
    cursor: pointer;
}

.btn-add-cart {
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
}

.btn-add-cart:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
}

.btn-view-detail {
    background: #fff;
    color: #1a1a1a;
    border: 2px solid #D4AF37;
}

.btn-view-detail:hover {
    background: rgba(212, 175, 55, 0.1);
    border-color: #B8860B;
}

/* Alternating Row Colors */
.compare-row:nth-child(even) {
    background: #fafafa;
}

.compare-row:nth-child(even):hover {
    background: rgba(212, 175, 55, 0.05);
}

@media (max-width: 1024px) {
    .compare-label {
        flex: 0 0 150px;
        font-size: 13px;
        padding: 15px;
    }
    
    .compare-cell {
        padding: 15px;
        font-size: 14px;
    }
    
    .compare-table {
        overflow-x: auto;
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .need-more {
        flex-direction: column;
    }
    
    .compare-label {
        flex: 0 0 120px;
        font-size: 12px;
        padding: 12px;
    }
    
    .compare-cell {
        font-size: 13px;
        padding: 12px;
    }
    
    .price-value {
        font-size: 20px;
    }
}
</style>

<!-- Banner -->
<div class="compare-banner">
    <div class="compare-banner-content">
        <h1>So sánh <span>chi tiết</span></h1>
        <p>Đưa ra quyết định tốt nhất cho chiếc xe của bạn</p>
    </div>
</div>

<main class="compare-page">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="17 1 21 5 17 9"></polyline>
                    <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                    <polyline points="7 23 3 19 7 15"></polyline>
                    <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                </svg>
                So Sánh Xe
                <span class="item-count">(<?= $compareCount ?>/<?= $maxCompare ?> xe)</span>
            </h1>
            <?php if ($compareCount > 0): ?>
            <a href="/compare?action=clear" class="btn-clear" onclick="return confirm('Bạn có chắc muốn xóa tất cả xe khỏi danh sách so sánh?')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
                Xóa tất cả
            </a>
            <?php endif; ?>
        </div>

        <?php if (empty($compareItems)): ?>
        <!-- Empty State -->
        <div class="empty-compare">
            <div class="empty-icon">
                <svg width="120" height="120" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <polyline points="17 1 21 5 17 9"></polyline>
                    <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                    <polyline points="7 23 3 19 7 15"></polyline>
                    <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                </svg>
            </div>
            <h2>Chưa có xe nào để so sánh</h2>
            <p>Thêm ít nhất 2 xe vào danh sách so sánh để xem các thông số chi tiết bên cạnh nhau</p>
            <a href="/cars" class="btn-explore">
                Xem Danh Sách Xe
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <?php elseif ($compareCount < 2): ?>
        <!-- Need More Cars -->
        <div class="need-more">
            <div class="current-car">
                <?php $item = $compareItems[0]; ?>
                <div class="compare-card">
                    <a href="/compare?action=remove&id=<?= $item['id'] ?>" class="btn-remove-card">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </a>
                    <div class="card-image">
                        <img src="<?= $item['image_url'] ?? 'https://via.placeholder.com/400x250' ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                    </div>
                    <div class="card-info">
                        <span class="card-brand"><?= htmlspecialchars($item['brand_name']) ?></span>
                        <h3 class="card-name"><?= htmlspecialchars($item['name']) ?></h3>
                        <p class="card-price"><?= formatPrice($item['price']) ?> VNĐ</p>
                    </div>
                </div>
            </div>
            <div class="add-more-prompt">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <h3>Thêm xe để so sánh</h3>
                <p>Bạn cần thêm ít nhất 1 xe nữa để thực hiện so sánh</p>
                <a href="/cars" class="btn-add">Thêm Xe Khác</a>
            </div>
        </div>

        <?php else: ?>
                    <div class="compare-label"></div>
                    <?php foreach ($compareItems as $item): ?>
                    <div class="compare-cell">
                        <div class="compare-card">
                            <a href="/compare?action=remove&id=<?= $item['id'] ?>" class="btn-remove-card" title="Xóa khỏi so sánh">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </a>
                            <div class="card-image">
                                <img src="<?= $item['image_url'] ?? 'https://via.placeholder.com/400x250' ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            </div>
                            <div class="card-info">
                                <img src="<?= $item['brand_logo'] ?>" alt="<?= $item['brand_name'] ?>" class="brand-logo">
                                <span class="card-brand"><?= htmlspecialchars($item['brand_name']) ?></span>
                                <h3 class="card-name">
                                    <a href="/car/<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php if ($compareCount < $maxCompare): ?>
                    <div class="compare-cell add-cell">
                        <a href="/cars" class="add-car-btn">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            <span>Thêm xe</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Price Row -->
                <div class="compare-row highlight-row">
                    <div class="compare-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="12" y1="1" x2="12" y2="23"></line>
                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                        </svg>
                        Giá bán
                    </div>
                    <?php foreach ($compareItems as $item): ?>
                    <div class="compare-cell price-cell">
                        <span class="price-value"><?= formatPrice($item['price']) ?></span>
                        <span class="price-unit">VNĐ</span>
                    </div>
                    <?php endforeach; ?>
                    <?php if ($compareCount < $maxCompare): ?><div class="compare-cell"></div><?php endif; ?>
                </div>

                <!-- Year Row -->
                <div class="compare-row">
                    <div class="compare-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                        Năm sản xuất
                    </div>
                    <?php foreach ($compareItems as $item): ?>
                    <div class="compare-cell"><?= $item['year'] ?></div>
                    <?php endforeach; ?>
                    <?php if ($compareCount < $maxCompare): ?><div class="compare-cell"></div><?php endif; ?>
                </div>

                <!-- Category Row -->
                <div class="compare-row">
                    <div class="compare-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                        </svg>
                        Loại xe
                    </div>
                    <?php foreach ($compareItems as $item): ?>
                    <div class="compare-cell"><?= htmlspecialchars($item['category_name']) ?></div>
                    <?php endforeach; ?>
                    <?php if ($compareCount < $maxCompare): ?><div class="compare-cell"></div><?php endif; ?>
                </div>

                <!-- Mileage Row -->
                <div class="compare-row">
                    <div class="compare-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        Số km đã đi
                    </div>
                    <?php foreach ($compareItems as $item): ?>
                    <div class="compare-cell"><?= number_format($item['mileage']) ?> km</div>
                    <?php endforeach; ?>
                    <?php if ($compareCount < $maxCompare): ?><div class="compare-cell"></div><?php endif; ?>
                </div>

                <!-- Fuel Row -->
                <div class="compare-row">
                    <div class="compare-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 22V3h10v10h7"></path>
                            <path d="M13 13v9"></path>
                            <path d="M20 3v18"></path>
                        </svg>
                        Nhiên liệu
                    </div>
                    <?php foreach ($compareItems as $item): 
                        $fuelTypes = ['gasoline' => 'Xăng', 'diesel' => 'Dầu', 'electric' => 'Điện'];
                        $fuel = $fuelTypes[$item['fuel']] ?? $item['fuel'];
                    ?>
                    <div class="compare-cell"><?= $fuel ?></div>
                    <?php endforeach; ?>
                    <?php if ($compareCount < $maxCompare): ?><div class="compare-cell"></div><?php endif; ?>
                </div>

                <!-- Transmission Row -->
                <div class="compare-row">
                    <div class="compare-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"></circle>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        Hộp số
                    </div>
                    <?php foreach ($compareItems as $item): 
                        $transmission = $item['transmission'] === 'automatic' ? 'Tự động' : 'Số sàn';
                    ?>
                    <div class="compare-cell"><?= $transmission ?></div>
                    <?php endforeach; ?>
                    <?php if ($compareCount < $maxCompare): ?><div class="compare-cell"></div><?php endif; ?>
                </div>

                <!-- Color Row -->
                <div class="compare-row">
                    <div class="compare-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="13.5" cy="6.5" r="2.5"></circle>
                            <circle cx="6.5" cy="13.5" r="2.5"></circle>
                            <circle cx="17.5" cy="17.5" r="2.5"></circle>
                        </svg>
                        Màu sắc
                    </div>
                    <?php foreach ($compareItems as $item): ?>
                    <div class="compare-cell"><?= htmlspecialchars($item['color'] ?? 'N/A') ?></div>
                    <?php endforeach; ?>
                    <?php if ($compareCount < $maxCompare): ?><div class="compare-cell"></div><?php endif; ?>
                </div>

                <!-- Status Row -->
                <div class="compare-row">
                    <div class="compare-label">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Trạng thái
                    </div>
                    <?php foreach ($compareItems as $item): 
                        $statusClass = $item['status'] === 'available' ? 'available' : 'sold';
                        $statusText = $item['status'] === 'available' ? 'Còn hàng' : 'Đã bán';
                    ?>
                    <div class="compare-cell">
                        <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                    </div>
                    <?php endforeach; ?>
                    <?php if ($compareCount < $maxCompare): ?><div class="compare-cell"></div><?php endif; ?>
                </div>

                <!-- Actions Row -->
                <div class="compare-row actions-row">
                    <div class="compare-label"></div>
                    <?php foreach ($compareItems as $item): ?>
                    <div class="compare-cell">
                        <?php if ($item['status'] === 'available'): ?>
                        <button class="btn-add-cart" onclick="addToCart(<?= $item['id'] ?>, this)">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            Thêm vào giỏ
                        </button>
                        <?php endif; ?>
                        <a href="/car/<?= $item['id'] ?>" class="btn-view-detail">Xem chi tiết</a>
                    </div>
                    <?php endforeach; ?>
                    <?php if ($compareCount < $maxCompare): ?><div class="compare-cell"></div><?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
