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

<!-- Compare Page Styles -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/compare.css">

<main class="compare-page">
    <div class="container">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="/">Trang chủ</a>
            <span class="separator">/</span>
            <a href="/cars">Danh sách xe</a>
            <span class="separator">/</span>
            <span class="current">So sánh xe</span>
        </nav>

        <div class="page-header">
            <h1 class="page-title">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
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
        <!-- Compare Table -->
        <div class="compare-wrapper">
            <div class="compare-table">
                <!-- Car Images Row -->
                <div class="compare-row images-row">
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
