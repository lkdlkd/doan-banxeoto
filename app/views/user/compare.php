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

// Hàm format tiền VND
function formatPriceVND($price)
{
    if ($price >= 1000000000) {
        return number_format($price / 1000000000, 2, '.', ',') . ' Tỷ';
    }
    return number_format($price, 0, ',', '.') . ' đ';
}

// Hàm format tiền USD
function formatPriceUSD($price)
{
    $usd = $price / 25000;
    return '$' . number_format($usd, 0, '.', ',');
}

include __DIR__ . '/../layouts/header.php';
?>

<style>
    /* ===== COMPARE PAGE - HORIZONTAL LAYOUT ===== */
    :root {
        --gold: #D4AF37;
        --dark-gold: #B8860B;
        --bg-light: #f9f7f3;
        --text-dark: #1a1a1a;
    }

    /* Banner */
    .compare-banner {
        position: relative;
        height: 350px;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.55) 100%),
            url('https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?w=1920&q=80') center/cover;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: -80px;
        overflow: hidden;
    }

    .compare-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(212, 175, 55, 0.3) 0%, transparent 60%);
    }

    .compare-banner-content {
        text-align: center;
        color: #fff;
        position: relative;
        z-index: 1;
    }

    .compare-banner h1 {
        font-family: 'Montserrat', sans-serif;
        font-size: 56px;
        font-weight: 900;
        margin-bottom: 15px;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        letter-spacing: -1px;
    }

    .compare-banner h1 span {
        color: var(--gold);
    }

    .compare-banner p {
        font-size: 20px;
        color: rgba(255, 255, 255, 0.95);
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.4);
        font-weight: 500;
    }

    /* Page Layout */
    .compare-page {
        background: linear-gradient(135deg, #f9f7f3 0%, #f5f2ed 100%);
        min-height: 100vh;
        padding-bottom: 80px;
    }

    .compare-container {
        max-width: 1600px;
        margin: 0 auto;
        padding: 0 30px;
        position: relative;
        z-index: 2;
    }

    /* Action Bar */
    .action-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 25px 35px;
        background: #fff;
        border-radius: 16px;
        margin-bottom: 35px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    .action-left h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .compare-count-badge {}

    .compare-count-badge {
        display: inline-block;
        padding: 6px 16px;
        background: linear-gradient(135deg, var(--gold) 0%, var(--dark-gold) 100%);
        color: #000;
        font-size: 13px;
        font-weight: 700;
        border-radius: 20px;
    }

    .btn-clear-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 13px 26px;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        border-radius: 12px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-clear-all:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.4);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 120px 40px;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.1) 0%, rgba(212, 175, 55, 0.05) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-state-icon svg {
        width: 60px;
        height: 60px;
        color: var(--gold);
    }

    .empty-state h2 {
        font-family: 'Montserrat', sans-serif;
        font-size: 32px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 15px;
    }

    .empty-state p {
        color: #666;
        font-size: 17px;
        margin-bottom: 40px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-browse {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 16px 35px;
        background: linear-gradient(135deg, var(--gold) 0%, var(--dark-gold) 100%);
        color: #000;
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
        border-radius: 12px;
        transition: all 0.3s;
    }

    .btn-browse:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
    }

    /* Compare Table - Horizontal Layout */
    .compare-table-wrapper {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    .compare-table {
        width: 100%;
        border-collapse: collapse;
    }

    .compare-table tr {
        border-bottom: 2px solid #f0f0f0;
        transition: background 0.2s;
    }

    .compare-table tr:hover {
        background: rgba(212, 175, 55, 0.02);
    }

    .compare-table tr:last-child {
        border-bottom: none;
    }

    .compare-table th {
        padding: 0;
        background: #fff;
        border-right: 1px solid #f0f0f0;
        border-bottom: 3px solid var(--gold);
        vertical-align: top;
    }

    .compare-table th:first-child {
        width: 180px;
        background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
    }

    .compare-table td {
        padding: 25px 20px;
        text-align: center;
        border-right: 1px solid #f0f0f0;
        font-size: 15px;
        color: #333;
        font-weight: 500;
        vertical-align: middle;
    }

    .compare-table td:first-child {
        background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 13px;
        color: var(--text-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: left;
        padding: 20px;
        border-right: 2px solid #e5e5e5;
    }

    .compare-table td:last-child,
    .compare-table th:last-child {
        border-right: none;
    }

    .label-icon {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .label-icon svg {
        color: var(--gold);
        flex-shrink: 0;
    }

    /* Car Card in Table */
    .car-card-cell {
        padding: 0 !important;
    }

    .car-card-horizontal {
        position: relative;
        background: #fff;
        transition: all 0.3s;
    }

    .car-card-horizontal:hover {
        background: rgba(212, 175, 55, 0.02);
    }

    .btn-remove-car {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 34px;
        height: 34px;
        background: rgba(239, 68, 68, 0.95);
        border: none;
        border-radius: 50%;
        color: #fff;
        cursor: pointer;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-remove-car:hover {
        background: #ef4444;
        transform: scale(1.15) rotate(90deg);
    }

    .car-image-small {
        height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .car-image-small img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
        display: block;
    }

    .car-image-small img:not([src]),
    .car-image-small img[src=""],
    .car-image-small img[src*="placeholder"] {
        opacity: 0.3;
    }

    .car-card-horizontal:hover .car-image-small img {
        transform: scale(1.08);
    }

    .car-info-compact {
        padding: 20px 15px;
        text-align: center;
        background: linear-gradient(to bottom, #fff 0%, #fafafa 100%);
    }

    .car-brand-badge {
        display: inline-block;
        padding: 5px 12px;
        background: linear-gradient(135deg, var(--gold) 0%, var(--dark-gold) 100%);
        color: #000;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .car-name-compact {
        font-family: 'Montserrat', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: var(--text-dark);
        margin: 0;
        line-height: 1.3;
        min-height: 40px;
    }

    .car-name-compact a {
        color: inherit;
        text-decoration: none;
        transition: color 0.3s;
    }

    .car-name-compact a:hover {
        color: var(--gold);
    }

    /* Add Car Cell */
    .add-car-cell {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.05) 0%, rgba(212, 175, 55, 0.02) 100%);
        padding: 0 !important;
    }

    .add-car-button {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 230px;
        gap: 12px;
        text-decoration: none;
        color: var(--gold);
        transition: all 0.3s;
        padding: 30px 20px;
    }

    .add-car-button:hover {
        background: rgba(212, 175, 55, 0.1);
    }

    .add-icon {
        width: 55px;
        height: 55px;
        border: 3px dashed var(--gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s;
    }

    .add-car-button:hover .add-icon {
        transform: rotate(90deg);
        border-style: solid;
    }

    .add-car-button span {
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 13px;
    }

    /* Price Row */
    .price-row td {
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.08) 0%, rgba(212, 175, 55, 0.03) 100%);
    }

    .price-row td:first-child {
        background: linear-gradient(135deg, var(--gold) 0%, var(--dark-gold) 100%) !important;
        color: #000;
    }

    .price-data {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .price-vnd {
        font-family: 'Montserrat', sans-serif;
        font-size: 24px;
        font-weight: 900;
        color: var(--gold);
        letter-spacing: -0.5px;
    }

    .price-usd {
        font-size: 14px;
        color: #666;
        font-weight: 600;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 7px 16px;
        border-radius: 25px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-badge.available {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.2) 0%, rgba(34, 197, 94, 0.1) 100%);
        color: #16a34a;
        border: 2px solid rgba(34, 197, 94, 0.3);
    }

    .status-badge.sold {
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.2) 0%, rgba(239, 68, 68, 0.1) 100%);
        color: #dc2626;
        border: 2px solid rgba(239, 68, 68, 0.3);
    }

    /* Actions Row */
    .actions-row td {
        background: #fafafa;
        padding: 25px 15px !important;
    }

    .actions-cell {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 18px;
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.3s;
        width: 100%;
        border: none;
        cursor: pointer;
        font-family: 'Montserrat', sans-serif;
        white-space: nowrap;
    }

    .btn-cart {
        background: linear-gradient(135deg, var(--gold) 0%, var(--dark-gold) 100%);
        color: #000;
    }

    .btn-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
    }

    .btn-detail {
        background: #fff;
        color: var(--text-dark);
        border: 2px solid var(--gold);
    }

    .btn-detail:hover {
        background: rgba(212, 175, 55, 0.1);
    }

    /* Responsive */
    @media (max-width: 1200px) {

        .compare-table th:first-child,
        .compare-table td:first-child {
            width: 150px;
            font-size: 12px;
            padding: 15px;
        }

        .compare-table td {
            padding: 18px 15px;
            font-size: 14px;
        }

        .car-image-small {
            height: 170px;
        }
    }

    @media (max-width: 768px) {
        .compare-banner h1 {
            font-size: 36px;
        }

        .action-bar {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }

        .compare-table-wrapper {
            overflow-x: auto;
        }

        .compare-table th:first-child,
        .compare-table td:first-child {
            width: 120px;
            font-size: 11px;
            padding: 12px;
        }

        .compare-table td {
            font-size: 13px;
            padding: 15px 12px;
        }

        .price-vnd {
            font-size: 20px;
        }

        .car-image-small {
            height: 140px;
        }

        .car-name-compact {
            font-size: 13px;
            min-height: 35px;
        }
    }
</style>

<!-- Banner -->
<div class="compare-banner">
    <div class="compare-banner-content">
        <h1>So sánh <span>siêu xe</span></h1>
        <p>Đối chiếu chi tiết để chọn xe hoàn hảo</p>
    </div>
</div>

<!-- Compare Page -->
<div class="compare-page">
    <div class="compare-container">

        <?php if ($compareCount > 0): ?>
            <!-- Action Bar -->
            <div class="action-bar">
                <div class="action-left">
                    <h2>
                        📊 Bảng so sánh
                        <span class="compare-count-badge"><?= $compareCount ?>/<?= $maxCompare ?> xe</span>
                    </h2>
                </div>
                <button class="btn-clear-all" onclick="if(confirm('Xóa tất cả xe khỏi danh sách so sánh?')) window.location.href='/compare?action=clear'">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"></polyline>
                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    </svg>
                    Xóa tất cả
                </button>
            </div>
        <?php endif; ?>

        <?php if (empty($compareItems)): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="17 1 21 5 17 9"></polyline>
                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                        <polyline points="7 23 3 19 7 15"></polyline>
                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                    </svg>
                </div>
                <h2>Danh sách so sánh trống</h2>
                <p>Thêm ít nhất 2 chiếc xe vào danh sách để bắt đầu so sánh chi tiết các thông số kỹ thuật</p>
                <a href="/cars" class="btn-browse">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16.39 15.56l3.35-6.15a5 5 0 0 0-.52-5.41"></path>
                        <path d="M4.43 13.63l-1.28 2.34a5 5 0 0 0 .52 5.4l3.29-3.28"></path>
                        <path d="M3 21h12"></path>
                        <path d="M21 3l-4 4"></path>
                    </svg>
                    Khám phá xe
                </a>
            </div>

        <?php elseif ($compareCount < 2): ?>
            <!-- Need one more car -->
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                </div>
                <h2>Cần thêm xe để so sánh</h2>
                <p>Bạn cần thêm ít nhất <?= 2 - $compareCount ?> xe nữa để bắt đầu so sánh</p>
                <a href="/cars" class="btn-browse">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    Thêm xe
                </a>
            </div>

        <?php else: ?>
            <!-- Compare Table -->
            <div class="compare-table-wrapper">
                <table class="compare-table">
                    <thead>
                        <tr>
                            <th></th>
                            <?php foreach ($compareItems as $item): ?>
                                <th class="car-card-cell">
                                    <div class="car-card-horizontal">
                                        <a href="/compare?action=remove&id=<?= $item['id'] ?>" class="btn-remove-car" title="Xóa">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                                <line x1="6" y1="6" x2="18" y2="18"></line>
                                            </svg>
                                        </a>
                                        <div class="car-image-small">
                                            <img src="<?= htmlspecialchars($item['image_url'] ?? 'https://via.placeholder.com/400x250?text=No+Image') ?>"
                                                alt="<?= htmlspecialchars($item['name']) ?>"
                                                loading="lazy"
                                                onerror="this.src='https://via.placeholder.com/400x250?text=No+Image'">
                                        </div>
                                        <div class="car-info-compact">
                                            <div class="car-brand-badge"><?= htmlspecialchars($item['brand_name']) ?></div>
                                            <h3 class="car-name-compact">
                                                <a href="/car/<?= $item['id'] ?>"><?= htmlspecialchars($item['name']) ?></a>
                                            </h3>
                                        </div>
                                    </div>
                                </th>
                            <?php endforeach; ?>

                            <?php if ($compareCount < $maxCompare): ?>
                                <th class="add-car-cell">
                                    <a href="/cars" class="add-car-button">
                                        <div class="add-icon">
                                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                        </div>
                                        <span>Thêm xe</span>
                                    </a>
                                </th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Price Row -->
                        <tr class="price-row">
                            <td>
                                <div class="label-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                    Giá bán
                                </div>
                            </td>
                            <?php foreach ($compareItems as $item): ?>
                                <td>
                                    <div class="price-data">
                                        <div class="price-vnd"><?= formatPriceVND($item['price']) ?></div>
                                        <div class="price-usd"><?= formatPriceUSD($item['price']) ?></div>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                            <?php if ($compareCount < $maxCompare): ?><td></td><?php endif; ?>
                        </tr>

                        <!-- Year -->
                        <tr>
                            <td>
                                <div class="label-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                    Năm SX
                                </div>
                            </td>
                            <?php foreach ($compareItems as $item): ?>
                                <td><?= $item['year'] ?></td>
                            <?php endforeach; ?>
                            <?php if ($compareCount < $maxCompare): ?><td></td><?php endif; ?>
                        </tr>

                        <!-- Category -->
                        <tr>
                            <td>
                                <div class="label-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                    </svg>
                                    Loại xe
                                </div>
                            </td>
                            <?php foreach ($compareItems as $item): ?>
                                <td><?= htmlspecialchars($item['category_name']) ?></td>
                            <?php endforeach; ?>
                            <?php if ($compareCount < $maxCompare): ?><td></td><?php endif; ?>
                        </tr>

                        <!-- Mileage -->
                        <tr>
                            <td>
                                <div class="label-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    Số KM
                                </div>
                            </td>
                            <?php foreach ($compareItems as $item): ?>
                                <td><?= number_format($item['mileage']) ?> km</td>
                            <?php endforeach; ?>
                            <?php if ($compareCount < $maxCompare): ?><td></td><?php endif; ?>
                        </tr>

                        <!-- Fuel -->
                        <tr>
                            <td>
                                <div class="label-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M3 22V3h10v10h7"></path>
                                        <path d="M13 13v9"></path>
                                        <path d="M20 3v18"></path>
                                    </svg>
                                    Nhiên liệu
                                </div>
                            </td>
                            <?php
                            $fuelTypes = ['gasoline' => 'Xăng', 'diesel' => 'Dầu Diesel', 'electric' => 'Điện', 'hybrid' => 'Hybrid'];
                            foreach ($compareItems as $item):
                                $fuel = $fuelTypes[$item['fuel']] ?? $item['fuel'];
                            ?>
                                <td><?= $fuel ?></td>
                            <?php endforeach; ?>
                            <?php if ($compareCount < $maxCompare): ?><td></td><?php endif; ?>
                        </tr>

                        <!-- Transmission -->
                        <tr>
                            <td>
                                <div class="label-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="3"></circle>
                                        <path d="M12 1v6m0 6v6"></path>
                                    </svg>
                                    Hộp số
                                </div>
                            </td>
                            <?php foreach ($compareItems as $item):
                                $trans = $item['transmission'] === 'automatic' ? 'Tự động' : 'Số sàn';
                            ?>
                                <td><?= $trans ?></td>
                            <?php endforeach; ?>
                            <?php if ($compareCount < $maxCompare): ?><td></td><?php endif; ?>
                        </tr>

                        <!-- Color -->
                        <tr>
                            <td>
                                <div class="label-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 2.69l5.66 5.66a8 8 0 1 1-11.31 0z"></path>
                                    </svg>
                                    Màu sắc
                                </div>
                            </td>
                            <?php foreach ($compareItems as $item): ?>
                                <td><?= htmlspecialchars($item['color'] ?? 'N/A') ?></td>
                            <?php endforeach; ?>
                            <?php if ($compareCount < $maxCompare): ?><td></td><?php endif; ?>
                        </tr>

                        <!-- Status -->
                        <tr>
                            <td>
                                <div class="label-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                    Trạng thái
                                </div>
                            </td>
                            <?php foreach ($compareItems as $item):
                                $statusClass = $item['status'] === 'available' ? 'available' : 'sold';
                                $statusText = $item['status'] === 'available' ? 'Sẵn sàng' : 'Đã bán';
                            ?>
                                <td>
                                    <span class="status-badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                            <?php endforeach; ?>
                            <?php if ($compareCount < $maxCompare): ?><td></td><?php endif; ?>
                        </tr>

                        <!-- Actions -->
                        <tr class="actions-row">
                            <td></td>
                            <?php foreach ($compareItems as $item): ?>
                                <td>
                                    <div class="actions-cell">
                                        <?php if ($item['status'] === 'available'): ?>
                                            <button class="btn-action btn-cart" onclick="addToCart(<?= $item['id'] ?>, this)">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                    <circle cx="9" cy="21" r="1"></circle>
                                                    <circle cx="20" cy="21" r="1"></circle>
                                                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                                </svg>
                                                Thêm giỏ
                                            </button>
                                        <?php endif; ?>
                                        <a href="/car/<?= $item['id'] ?>" class="btn-action btn-detail">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                            Chi tiết
                                        </a>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                            <?php if ($compareCount < $maxCompare): ?><td></td><?php endif; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function addToCart(carId, button) {
        const originalText = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<svg class="spin" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg> Đang thêm...';

        fetch('/cart/add', {
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
                    button.innerHTML = '✓ Đã thêm';
                    button.style.background = 'linear-gradient(135deg, #10b981 0%, #059669 100%)';
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                        button.style.background = '';
                    }, 2000);

                    // Update cart count
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount && data.cartCount) {
                        cartCount.textContent = data.cartCount;
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                    button.innerHTML = originalText;
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra. Vui lòng thử lại!');
                button.innerHTML = originalText;
                button.disabled = false;
            });
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>