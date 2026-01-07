<?php
$currentPage = 'cars';
$pageTitle = 'Danh Sách Xe';

// Kết nối cơ sở dữ liệu
require_once __DIR__ . '/../../models/BrandModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';
require_once __DIR__ . '/../../models/CarModel.php';
require_once __DIR__ . '/../../models/FavoriteModel.php';

$brandModel = new BrandModel();
$categoryModel = new CategoryModel();
$carModel = new CarModel();
$favoriteModel = new FavoriteModel();

// Lấy danh sách xe yêu thích của user (nếu đã đăng nhập)
$userFavorites = [];
if (isset($_SESSION['user_id'])) {
    $favorites = $favoriteModel->getFavoritesByUser($_SESSION['user_id']);
    $userFavorites = array_column($favorites, 'car_id');
}

// Lấy danh sách brands và categories từ DB
$brands = $brandModel->getBrandsWithCarCount();
$categories = $categoryModel->getCategoriesWithCarCount();

// Lấy tham số filter từ URL
$filterBrand = isset($_GET['brand']) ? (is_array($_GET['brand']) ? $_GET['brand'] : [$_GET['brand']]) : [];
$filterCategory = isset($_GET['category']) ? (is_array($_GET['category']) ? $_GET['category'] : [$_GET['category']]) : [];
$filterMinPrice = isset($_GET['min_price']) ? $_GET['min_price'] : null;
$filterMaxPrice = isset($_GET['max_price']) ? $_GET['max_price'] : null;
$filterKeyword = isset($_GET['keyword']) ? $_GET['keyword'] : null;
$filterYear = isset($_GET['year']) ? $_GET['year'] : null;

// Lấy danh sách xe (có filter nếu có) - Bao gồm cả xe đã bán
$brandFilter = !empty($filterBrand) ? implode(',', array_filter($filterBrand)) : null;
$categoryFilter = !empty($filterCategory) ? implode(',', array_filter($filterCategory)) : null;
$cars = $carModel->search($filterKeyword, $brandFilter, $categoryFilter, $filterMinPrice, $filterMaxPrice, true); // true = lấy tất cả

// Filter by year if specified
if ($filterYear && !empty($cars)) {
    $cars = array_filter($cars, function ($car) use ($filterYear) {
        return $car['year'] == $filterYear;
    });
}

// Pagination setup
$carsPerPage = 12;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalCars = count($cars);
$totalPages = ceil($totalCars / $carsPerPage);
$offset = ($currentPage - 1) * $carsPerPage;

// Get cars for current page
$carsOnPage = array_slice($cars, $offset, $carsPerPage);

// Tỷ giá USD (1 USD = 25,000 VND)
define('USD_RATE', 25000);

// Hàm format tiền VND
function formatPriceVND($price)
{
    if ($price >= 1000000000) {
        return number_format($price / 1000000000, 2, '.', '') . ' Tỷ';
    } elseif ($price >= 1000000) {
        return number_format($price / 1000000, 0, '', '') . ' Triệu';
    }
    return number_format($price, 0, '', ',');
}

// Hàm format tiền USD
function formatPriceUSD($price)
{
    $usd = $price / USD_RATE;
    if ($usd >= 1000000) {
        return '$' . number_format($usd / 1000000, 2) . 'M';
    } elseif ($usd >= 1000) {
        return '$' . number_format($usd / 1000, 0) . 'K';
    }
    return '$' . number_format($usd, 0);
}

// Các khoảng giá preset (VND)
$priceRanges = [
    ['min' => 0, 'max' => 500000000, 'label_vnd' => 'Dưới 500 Triệu', 'label_usd' => '< $20K'],
    ['min' => 500000000, 'max' => 1000000000, 'label_vnd' => '500 Triệu - 1 Tỷ', 'label_usd' => '$20K - $40K'],
    ['min' => 1000000000, 'max' => 2000000000, 'label_vnd' => '1 - 2 Tỷ', 'label_usd' => '$40K - $80K'],
    ['min' => 2000000000, 'max' => 5000000000, 'label_vnd' => '2 - 5 Tỷ', 'label_usd' => '$80K - $200K'],
    ['min' => 5000000000, 'max' => 10000000000, 'label_vnd' => '5 - 10 Tỷ', 'label_usd' => '$200K - $400K'],
    ['min' => 10000000000, 'max' => 20000000000, 'label_vnd' => '10 - 20 Tỷ', 'label_usd' => '$400K - $800K'],
    ['min' => 20000000000, 'max' => null, 'label_vnd' => 'Trên 20 Tỷ', 'label_usd' => '> $800K'],
];

include __DIR__ . '/../layouts/header.php';
?>

<style>
    /* Banner */
    .cars-banner {
        position: relative;
        height: 350px;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.5) 100%),
            url('https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=1920&q=80') center/cover;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: -80px;
    }

    .cars-banner-content {
        text-align: center;
        color: #fff;
        position: relative;
        z-index: 1;
        max-width: 800px;
        padding: 0 20px;
    }

    .cars-banner h1 {
        font-family: 'Playfair Display', serif;
        font-size: 56px;
        font-weight: 700;
        margin-bottom: 15px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
    }

    .cars-banner h1 span {
        color: #D4AF37;
    }

    .cars-banner p {
        font-size: 20px;
        color: rgba(255, 255, 255, 0.9);
        text-shadow: 0 1px 5px rgba(0, 0, 0, 0.3);
        margin-bottom: 12px;
    }

    .cars-banner .count-badge {
        display: inline-block;
        padding: 8px 20px;
        background: rgba(212, 175, 55, 0.2);
        border: 2px solid #D4AF37;
        border-radius: 25px;
        font-size: 16px;
        font-weight: 700;
        color: #D4AF37;
        backdrop-filter: blur(10px);
    }

    /* Page Layout */
    .cars-page {
        background: linear-gradient(135deg, #f9f7f3 0%, #f5f2ed 100%);
        min-height: 100vh;
        padding: 100px 0 80px;
    }

    .cars-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 0 30px;
        position: relative;
        z-index: 2;
    }

    .cars-layout {
        display: block;
    }

    /* Filter Sidebar - Luxury Design */
    .filter-sidebar {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e5e5;
        margin-bottom: 40px;
    }

    .filter-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }

    .filter-header h3 {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        color: #fff;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
    }

    .filter-header h3 svg {
        color: #D4AF37;
    }

    .clear-filter {
        background: #fff;
        border: 1px solid #e5e5e5;
        color: #666;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .clear-filter:hover {
        background: #f9f9f9;
        color: #ef4444;
        border-color: #ef4444;
    }

    /* Filter Sections Horizontal Grid */
    .filter-sections {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
        margin-top: 16px;
    }

    /* Filter Group */
    .filter-group {
        margin-bottom: 0;
        background: #fff;
        padding: 14px;
        border-radius: 10px;
        border: 1px solid #e5e5e5;
        transition: all 0.2s;
    }

    .filter-group:hover {
        background: #fff;
        border-color: #D4AF37;
    }

    .filter-group label:first-child {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 13px;
        font-weight: 700;
        color: #0a0a0a;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Search Input */
    .search-input {
        position: relative;
    }

    .search-input input {
        width: 100%;
        padding: 10px 40px 10px 14px;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 500;
        color: #1a1a1a;
        transition: all 0.2s;
        background: #fff;
    }

    .search-input input::placeholder {
        color: #999;
    }

    .search-input input:focus {
        border-color: #D4AF37;
        outline: none;
    }

    .search-input svg {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        pointer-events: none;
    }

    /* Checkbox Items */
    .filter-options {
        max-height: 220px;
        overflow-y: auto;
        padding: 0 12px 0 4px;
    }

    .filter-options::-webkit-scrollbar {
        width: 3px;
    }

    .filter-options::-webkit-scrollbar-track {
        background: transparent;
    }

    .filter-options::-webkit-scrollbar-thumb {
        background: #e0e0e0;
        border-radius: 3px;
    }

    .filter-options::-webkit-scrollbar-thumb:hover {
        background: #D4AF37;
    }

    .checkbox-item {
        display: flex;
        align-items: center;
        padding: 8px 10px;
        cursor: pointer;
        border-radius: 6px;
        transition: all 0.2s;
        margin-bottom: 4px;
    }

    .checkbox-item:hover {
        background: #f5f5f5;
    }

    .checkbox-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #D4AF37;
        cursor: pointer;
        margin-right: 10px;
    }

    .checkbox-item input:checked~.label-text {
        color: #D4AF37;
    }

    .checkbox-item .label-text {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #0a0a0a;
        font-weight: 600;
        flex: 1;
    }

    .checkbox-item small {
        color: #999;
        font-size: 11px;
    }

    /* Select Inputs */
    .filter-select {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        color: #1a1a1a;
        background: #fff;
        cursor: pointer;
        transition: all 0.2s;
        font-weight: 500;
    }

    .filter-select:focus {
        border-color: #D4AF37;
        outline: none;
    }

    .filter-select:hover {
        border-color: #d4d4d4;
    }

    /* Apply Button */
    .apply-filter-btn {
        width: auto;
        max-width: 300px;
        margin: 16px auto 0;
        padding: 12px 32px;
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.3s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .apply-filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(212, 175, 55, 0.4);
    }

    .apply-filter-btn:active {
        transform: translateY(0);
    }

    /* Sort Bar */
    .sort-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 25px;
        background: #fff;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(212, 175, 55, 0.15);
    }

    .result-count {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 500;
        color: #4a4a4a;
    }

    .result-count strong {
        color: #D4AF37;
        font-weight: 700;
    }

    .sort-options {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .sort-options label {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: #4a4a4a;
    }

    .sort-select {
        padding: 10px 15px;
        border: 2px solid #e5e5e5;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
    }

    /* Cars Grid */
    .cars-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 35px;
        margin-bottom: 20px;
    }

    /* Car Card - Premium Design */
    .car-card {
        background: #ffffff;
        border: 2px solid #e8e8e8;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06),
            0 2px 8px rgba(0, 0, 0, 0.03);
        height: 100%;
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }

    .car-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        border-radius: 24px;
        padding: 4px;
        background: linear-gradient(135deg,
                #D4AF37 0%, #FFD700 20%, #B8860B 40%,
                #FFD700 60%, #D4AF37 80%, #FFD700 100%);
        background-size: 300% 300%;
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: all 0.6s;
        filter: blur(0px);
    }

    .car-card:hover::before {
        opacity: 1;
        animation: borderPulse 4s ease-in-out infinite;
        filter: blur(1px) brightness(1.3);
    }

    @keyframes borderPulse {

        0%,
        100% {
            background-position: 0% 50%;
            transform: scale(1);
        }

        25% {
            background-position: 50% 0%;
            transform: scale(1.01);
        }

        50% {
            background-position: 100% 50%;
            transform: scale(1);
        }

        75% {
            background-position: 50% 100%;
            transform: scale(1.01);
        }
    }

    .car-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
        opacity: 0;
        transition: all 0.8s;
        pointer-events: none;
    }

    .car-card:hover::after {
        opacity: 1;
        animation: shimmer 2s ease-in-out infinite;
    }

    @keyframes shimmer {
        0% {
            transform: translate(0, 0) rotate(0deg);
        }

        50% {
            transform: translate(-20%, -20%) rotate(180deg);
        }

        100% {
            transform: translate(0, 0) rotate(360deg);
        }
    }

    .car-card:hover {
        transform: translateY(-12px);
        box-shadow: 0 24px 48px rgba(212, 175, 55, 0.2),
            0 12px 24px rgba(0, 0, 0, 0.1);
        border-color: #D4AF37;
    }

    .car-image {
        position: relative;
        height: 220px;
        overflow: hidden;
        background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 50%, #f5f5f5 100%);
        border-radius: 20px 20px 0 0;
        box-shadow: inset 0 -4px 20px rgba(0, 0, 0, 0.05);
    }

    .car-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.4) 50%,
                transparent 100%);
        z-index: 3;
        transition: all 0.8s;
    }

    .car-card:hover .car-image::before {
        left: 100%;
    }

    .car-image::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg,
                rgba(212, 175, 55, 0.2) 0%,
                transparent 30%,
                transparent 70%,
                rgba(212, 175, 55, 0.2) 100%);
        opacity: 0;
        transition: opacity 0.6s;
        z-index: 2;
    }

    .car-card:hover .car-image::after {
        opacity: 0.3;
    }

    .car-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.7s cubic-bezier(0.34, 1.56, 0.64, 1),
            filter 0.5s;
        filter: brightness(1) contrast(1.02);
    }

    .car-card:hover .car-image img {
        transform: scale(1.12);
        filter: brightness(1.08) contrast(1.08) saturate(1.1);
    }

    .car-badges {
        position: absolute;
        top: 16px;
        left: 16px;
        z-index: 5;
    }

    .car-badges .badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 16px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
        backdrop-filter: blur(8px);
    }

    .badge::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .car-card:hover .badge::before {
        width: 200px;
        height: 200px;
    }

    .car-card:hover .badge {
        transform: translateY(-3px) scale(1.08);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.35),
            0 3px 12px rgba(0, 0, 0, 0.2);
    }

    .badge.new {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        animation: badgePulse 2s ease-in-out infinite;
    }

    .badge.hot {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
        animation: badgeFlicker 1.5s ease-in-out infinite;
    }

    .badge.premium {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: #fff;
        animation: badgeShine 3s ease-in-out infinite;
    }

    .badge.supercar {
        background: linear-gradient(135deg, #D4AF37 0%, #FFD700 50%, #B8860B 100%);
        background-size: 200% 200%;
        color: #000;
        animation: badgeGold 4s ease-in-out infinite;
    }

    .badge.sold {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: #fff;
        animation: none;
    }

    @keyframes badgePulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    @keyframes badgeFlicker {

        0%,
        100% {
            opacity: 1;
        }

        50% {
            opacity: 0.85;
        }
    }

    @keyframes badgeShine {

        0%,
        100% {
            filter: brightness(1);
        }

        50% {
            filter: brightness(1.2);
        }
    }

    @keyframes badgeGold {

        0%,
        100% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }
    }

    .car-quick-actions {
        position: absolute;
        top: 10px;
        right: 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        z-index: 10;
    }

    .compare-btn-wrapper {
        position: absolute;
        bottom: 10px;
        left: 10px;
        z-index: 10;
    }

    .quick-btn {
        width: 36px;
        height: 36px;
        min-width: 36px;
        min-height: 36px;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px) saturate(180%);
        border: 2px solid rgba(212, 175, 55, 0.4);
        border-radius: 50%;
        color: #666;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.5);
        flex-shrink: 0;
        position: relative;
        overflow: hidden;
    }

    .quick-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(212, 175, 55, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.5s, height 0.5s;
    }

    .quick-btn:hover::before {
        width: 100px;
        height: 100px;
    }

    .quick-btn:hover {
        background: linear-gradient(135deg, #D4AF37 0%, #FFD700 50%, #B8860B 100%);
        color: #000;
        border-color: #FFD700;
        transform: scale(1.2) rotate(10deg);
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.5),
            0 4px 12px rgba(0, 0, 0, 0.2),
            inset 0 2px 4px rgba(255, 255, 255, 0.3);
    }

    .quick-btn:active {
        transform: scale(1.1) rotate(5deg);
    }

    .quick-btn.favorited {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #fff;
        border-color: #ef4444;
        animation: heartBeat 1.5s ease-in-out infinite;
    }

    @keyframes heartBeat {

        0%,
        100% {
            transform: scale(1);
        }

        10%,
        30% {
            transform: scale(1.1);
        }

        20%,
        40% {
            transform: scale(1);
        }
    }

    /* Car Info */
    .car-info {
        padding: 18px 16px 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    /* View Detail Button - Hidden by default, shown on hover */
    .btn-view-detail {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.8);
        padding: 12px 24px;
        background: rgba(212, 175, 55, 0.92);
        backdrop-filter: blur(8px);
        border: 2px solid rgba(255, 255, 255, 0.8);
        color: #000;
        font-family: 'Inter', sans-serif;
        font-weight: 700;
        font-size: 13px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        cursor: pointer;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
        z-index: 15;
        white-space: nowrap;
    }

    .car-card:hover .btn-view-detail {
        opacity: 0.95;
        pointer-events: auto;
        transform: translate(-50%, -50%) scale(1);
    }

    .btn-view-detail:hover {
        transform: translate(-50%, -50%) scale(1.08);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.5),
            0 6px 18px rgba(212, 175, 55, 0.8);
    }

    .car-brand {
        display: inline-block;
        font-family: 'Inter', sans-serif;
        font-size: 10px;
        color: #000;
        background: linear-gradient(135deg, #D4AF37 0%, #FFD700 25%, #B8860B 50%, #FFD700 75%, #D4AF37 100%);
        background-size: 200% 200%;
        padding: 4px 10px;
        border-radius: 6px;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        font-weight: 900;
        margin-bottom: 8px;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.4),
            inset 0 1px 0 rgba(255, 255, 255, 0.3),
            inset 0 -1px 0 rgba(0, 0, 0, 0.1);
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        overflow: hidden;
    }

    .car-brand::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
        transition: left 0.6s;
    }

    .car-card:hover .car-brand {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.6),
            inset 0 2px 4px rgba(255, 255, 255, 0.4);
        animation: brandGlow 2s ease-in-out infinite;
    }

    .car-card:hover .car-brand::before {
        left: 100%;
    }

    @keyframes brandGlow {

        0%,
        100% {
            background-position: 0% 50%;
            filter: brightness(1);
        }

        50% {
            background-position: 100% 50%;
            filter: brightness(1.15);
        }
    }

    .car-name {
        font-family: 'Inter', sans-serif;
        font-size: 15px;
        color: #0a0a0a !important;
        margin: 0 0 8px 0;
        font-weight: 700;
        line-height: 1.3;
        min-height: 39px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .car-specs {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
        flex-wrap: wrap;
    }

    .car-specs span {
        display: flex;
        align-items: center;
        gap: 5px;
        font-family: 'Inter', sans-serif;
        font-size: 12px;
        font-weight: 500;
        color: #4a4a4a;
    }

    .car-specs svg {
        color: #D4AF37;
    }

    .car-price {
        padding: 12px;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.12) 0%, rgba(212, 175, 55, 0.06) 100%);
        border-radius: 10px;
        margin-bottom: 10px;
        margin-top: auto;
        border: 2px solid rgba(212, 175, 55, 0.25);
        position: relative;
        overflow: hidden;
    }

    .car-price::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.5s;
    }

    .car-card:hover .car-price::before {
        opacity: 1;
        animation: priceGlow 2s ease-in-out infinite;
    }

    @keyframes priceGlow {

        0%,
        100% {
            transform: translate(0, 0);
        }

        50% {
            transform: translate(10%, 10%);
        }
    }

    .price-main {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 6px;
        position: relative;
        z-index: 1;
    }

    .price-main .price {
        font-family: 'Inter', sans-serif;
        font-size: 20px;
        font-weight: 800;
        color: #D4AF37;
        text-shadow: 0 2px 4px rgba(212, 175, 55, 0.2);
    }

    .price-main .price-label {
        font-family: 'Inter', sans-serif;
        font-size: 11px;
        color: #888;
        font-weight: 600;
    }

    .price-usd {
        font-family: 'Inter', sans-serif;
        font-size: 11px;
        font-weight: 500;
        color: #666;
    }

    .car-actions {
        position: relative;
        z-index: 1;
    }

    .btn-add-cart {
        width: 100%;
        padding: 13px 20px;
        background: #fff;
        border: 3px solid #D4AF37;
        color: #0a0a0a;
        cursor: pointer;
        font-family: 'Inter', sans-serif;
        font-weight: 800;
        font-size: 13px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.8),
            inset 0 -2px 5px rgba(212, 175, 55, 0.1);
    }

    .btn-add-cart::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
        transition: left 0.6s;
    }

    .btn-add-cart:hover {
        background: linear-gradient(135deg, #D4AF37 0%, #FFD700 50%, #B8860B 100%);
        border-color: #FFD700;
        color: #0a0a0a;
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 8px 30px rgba(212, 175, 55, 0.5),
            0 0 40px rgba(212, 175, 55, 0.3),
            inset 0 1px 0 rgba(255, 255, 255, 0.6);
    }

    .btn-add-cart:hover::before {
        left: 100%;
    }

    .btn-add-cart:hover svg {
        transform: scale(1.2) rotate(15deg);
    }

    .btn-add-cart:active {
        transform: translateY(-2px) scale(0.98);
    }

    .btn-add-cart svg {
        transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    /* No Cars */
    .no-cars {
        grid-column: 1 / -1;
        text-align: center;
        padding: 80px 20px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .no-cars svg {
        color: rgba(212, 175, 55, 0.3);
        margin-bottom: 20px;
    }

    .no-cars h3 {
        font-family: 'Inter', sans-serif;
        font-size: 22px;
        color: #0a0a0a;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .no-cars p {
        font-family: 'Inter', sans-serif;
        font-size: 15px;
        color: #666;
        font-weight: 500;
        margin-bottom: 20px;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        margin-top: 50px;
    }

    .page-btn {
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        border: 2px solid #e5e5e5;
        background: #fff;
        color: #333;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .page-btn:hover:not(.active):not(:disabled) {
        border-color: #D4AF37;
        background: rgba(212, 175, 55, 0.1);
    }

    .page-btn.active {
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        color: #000;
        border-color: #D4AF37;
    }

    .page-btn:disabled {
        opacity: 0.4;
        cursor: not-allowed;
    }

    .page-dots {
        color: #999;
        font-weight: 700;
    }

    @media (max-width: 1024px) {
        .filter-sections {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    @media (max-width: 1400px) {
        .cars-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }
    }

    @media (max-width: 1024px) {
        .cars-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }
    }

    @media (max-width: 768px) {
        .cars-banner h1 {
            font-size: 40px;
        }

        .cars-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .sort-bar {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
    }
</style>

<!-- Banner -->
<div class="cars-banner">
    <div class="cars-banner-content">
        <h1>Bộ sưu tập <span>xe cao cấp</span></h1>
        <p>Khám phá và tìm kiếm chiếc xe hoàn hảo dành cho bạn</p>
        <div class="count-badge"><?= $totalCars ?> xe có sẵn</div>
    </div>
</div>

<div class="cars-page">
    <div class="cars-container">
        <div class="cars-layout">
            <form method="GET" action="/cars" id="filterForm">
                <div class="filter-sidebar">
                    <div class="filter-header">
                        <h3>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            Bộ Lọc Tìm Kiếm
                        </h3>
                        <button type="button" class="clear-filter" onclick="clearFilters()">Xóa tất cả</button>
                    </div>

                    <div class="filter-sections">
                        <!-- Search -->
                        <div class="filter-group">
                            <label>Tìm Kiếm Xe</label>
                            <div class="search-input">
                                <input type="text" name="keyword" placeholder="Nhập tên xe, model..." value="<?= htmlspecialchars($filterKeyword ?? '') ?>">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="11" cy="11" r="8"></circle>
                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                </svg>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="filter-group">
                            <label>Thương Hiệu</label>
                            <div class="filter-options">
                                <?php if (!empty($brands)): ?>
                                    <?php foreach ($brands as $brand): ?>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="brand[]" value="<?= $brand['id'] ?>" <?= in_array($brand['id'], $filterBrand) ? 'checked' : '' ?>>
                                            <span class="label-text"><?= htmlspecialchars($brand['name']) ?> <small>(<?= $brand['car_count'] ?>)</small></span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="no-data">Chưa có thương hiệu nào</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Price Range -->
                        <div class="filter-group">
                            <label>Khoảng Giá</label>
                            <select class="filter-select price-filter" name="price_range" onchange="updatePriceInputs(this)">
                                <option value="">Tất cả mức giá</option>
                                <?php
                                $selectedRange = '';
                                foreach ($priceRanges as $index => $range):
                                    $isSelected = ($filterMinPrice == $range['min'] && ($filterMaxPrice == $range['max'] || ($filterMaxPrice === null && $range['max'] === null)));
                                    if ($isSelected) $selectedRange = $index;
                                ?>
                                    <option value="<?= $index ?>" data-min="<?= $range['min'] ?>" data-max="<?= $range['max'] ?? '' ?>" <?= $isSelected ? 'selected' : '' ?>>
                                        <?= $range['label_vnd'] ?> (<?= $range['label_usd'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="hidden" name="min_price" id="min_price" value="<?= htmlspecialchars($filterMinPrice ?? '') ?>">
                            <input type="hidden" name="max_price" id="max_price" value="<?= htmlspecialchars($filterMaxPrice ?? '') ?>">
                            <div class="price-display">
                                <span class="currency-label">💰 VND | USD</span>
                            </div>
                        </div>

                        <!-- Body Type / Category -->
                        <div class="filter-group">
                            <label>Loại Xe</label>
                            <div class="filter-options">
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <label class="checkbox-item">
                                            <input type="checkbox" name="category[]" value="<?= $category['id'] ?>" <?= in_array($category['id'], $filterCategory) ? 'checked' : '' ?>>
                                            <span class="label-text"><?= htmlspecialchars($category['name']) ?> <small>(<?= $category['car_count'] ?>)</small></span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="no-data">Chưa có loại xe nào</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Year -->
                        <div class="filter-group">
                            <label>Năm Sản Xuất</label>
                            <select class="filter-select" name="year">
                                <option value="">Tất cả năm</option>
                                <option value="2025" <?= $filterYear == '2025' ? 'selected' : '' ?>>2025</option>
                                <option value="2024" <?= $filterYear == '2024' ? 'selected' : '' ?>>2024</option>
                                <option value="2023" <?= $filterYear == '2023' ? 'selected' : '' ?>>2023</option>
                                <option value="2022" <?= $filterYear == '2022' ? 'selected' : '' ?>>2022</option>
                                <option value="2021" <?= $filterYear == '2021' ? 'selected' : '' ?>>2021</option>
                                <option value="2020" <?= $filterYear == '2020' ? 'selected' : '' ?>>2020</option>
                                <option value="2019" <?= $filterYear == '2019' ? 'selected' : '' ?>>2019</option>
                                <option value="2018" <?= $filterYear == '2018' ? 'selected' : '' ?>>2018</option>
                                <option value="2017" <?= $filterYear == '2017' ? 'selected' : '' ?>>2017</option>
                                <option value="2016" <?= $filterYear == '2016' ? 'selected' : '' ?>>2016</option>
                                <option value="2015" <?= $filterYear == '2015' ? 'selected' : '' ?>>2015</option>
                                <option value="2014" <?= $filterYear == '2014' ? 'selected' : '' ?>>2014</option>
                                <option value="2013" <?= $filterYear == '2013' ? 'selected' : '' ?>>2013</option>
                                <option value="2012" <?= $filterYear == '2012' ? 'selected' : '' ?>>2012</option>
                                <option value="2011" <?= $filterYear == '2011' ? 'selected' : '' ?>>2011</option>
                                <option value="2010" <?= $filterYear == '2010' ? 'selected' : '' ?>>2010</option>
                                <option value="2009" <?= $filterYear == '2009' ? 'selected' : '' ?>>2009</option>
                                <option value="2008" <?= $filterYear == '2008' ? 'selected' : '' ?>>2008</option>
                                <option value="2007" <?= $filterYear == '2007' ? 'selected' : '' ?>>2007</option>
                                <option value="2006" <?= $filterYear == '2006' ? 'selected' : '' ?>>2006</option>
                                <option value="2005" <?= $filterYear == '2005' ? 'selected' : '' ?>>2005</option>
                                <option value="2004" <?= $filterYear == '2004' ? 'selected' : '' ?>>2004</option>
                            </select>
                        </div>
                    </div>

                    <!-- Apply Button -->
                    <button type="submit" class="apply-filter-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Áp Dụng Bộ Lọc
                    </button>
                </div>
            </form>
        </div>

        <!-- Car Grid -->
        <div class="car-grid-container">
            <!-- Sort Bar -->
            <div class="sort-bar">
                <div class="result-count">
                    Hiển thị <strong><?= min($offset + 1, $totalCars) ?>-<?= min($offset + $carsPerPage, $totalCars) ?></strong> trong tổng <strong><?= $totalCars ?></strong> xe
                </div>
                <div class="sort-options">
                    <label>Sắp xếp:</label>
                    <select class="sort-select">
                        <option value="newest">Mới nhất</option>
                        <option value="price-asc">Giá: Thấp - Cao</option>
                        <option value="price-desc">Giá: Cao - Thấp</option>
                        <option value="popular">Phổ biến nhất</option>
                    </select>
                    <div class="view-toggle">
                        <button class="view-btn active" data-view="grid">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <rect x="3" y="3" width="7" height="7"></rect>
                                <rect x="14" y="3" width="7" height="7"></rect>
                                <rect x="3" y="14" width="7" height="7"></rect>
                                <rect x="14" y="14" width="7" height="7"></rect>
                            </svg>
                        </button>
                        <button class="view-btn" data-view="list">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <rect x="3" y="4" width="18" height="4"></rect>
                                <rect x="3" y="10" width="18" height="4"></rect>
                                <rect x="3" y="16" width="18" height="4"></rect>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Cars Grid -->
            <div class="cars-grid">
                <?php if (!empty($carsOnPage)): ?>
                    <?php foreach ($carsOnPage as $index => $car):
                        // Lấy hình ảnh xe
                        $carImages = $carModel->getCarImages($car['id']);
                        $mainImage = !empty($carImages) ? $carImages[0]['image_url'] : 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=400';

                        // Xác định badge
                        $badge = '';
                        $badgeClass = '';

                        // Ưu tiên badge "Đã bán" nếu xe đã bán
                        if ($car['status'] === 'sold') {
                            $badge = 'Đã bán';
                            $badgeClass = 'sold';
                        } elseif ($car['year'] >= 2025) {
                            $badge = 'Mới';
                            $badgeClass = 'new';
                        } elseif ($car['price'] >= 15000000000) {
                            $badge = 'Siêu xe';
                            $badgeClass = 'supercar';
                        } elseif ($car['price'] >= 10000000000) {
                            $badge = 'Premium';
                            $badgeClass = 'premium';
                        } elseif ($index < 3) {
                            $badge = 'Hot';
                            $badgeClass = 'hot';
                        }

                        // Format giá
                        $priceVND = formatPriceVND($car['price']);
                        $priceUSD = formatPriceUSD($car['price']);

                        // Loại hộp số
                        $transmission = $car['transmission'] == 'automatic' ? 'Tự động' : 'Số sàn';
                    ?>
                        <div class="car-card">
                            <div class="car-image">
                                <img src="<?= htmlspecialchars($mainImage) ?>" alt="<?= htmlspecialchars($car['name']) ?>" loading="lazy" class="lazy-image">
                                <button class="btn-view-detail" onclick="window.location.href='/car/<?= $car['id'] ?>'">XEM CHI TIẾT</button>
                                <?php if ($badge): ?>
                                    <div class="car-badges">
                                        <span class="badge <?= $badgeClass ?>"><?= $badge ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="car-quick-actions">
                                    <button class="quick-btn favorite-btn <?= in_array($car['id'], $userFavorites) ? 'favorited' : '' ?>" data-car-id="<?= $car['id'] ?>" title="Yêu thích">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="<?= in_array($car['id'], $userFavorites) ? 'currentColor' : 'none' ?>" stroke="currentColor" stroke-width="2">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="compare-btn-wrapper">
                                    <button class="quick-btn compare-btn" onclick="addToCompare(<?= $car['id'] ?>, this)" title="So sánh">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="17 1 21 5 17 9"></polyline>
                                            <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                            <polyline points="7 23 3 19 7 15"></polyline>
                                            <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="car-info">
                                <div class="car-brand"><?= htmlspecialchars($car['brand_name']) ?></div>
                                <h3 class="car-name"><?= htmlspecialchars($car['name']) ?></h3>
                                <div class="car-specs">
                                    <span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>
                                            <line x1="3" y1="10" x2="21" y2="10"></line>
                                        </svg>
                                        <?= $car['year'] ?>
                                    </span>
                                    <?php if (!empty($car['horsepower'])): ?>
                                        <span>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon>
                                            </svg>
                                            <?= $car['horsepower'] ?> HP
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($car['acceleration'])): ?>
                                        <span>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M23 12l-2.44-2.79-.34-.34-.35-.4c-.23-.27-.64-.56-.94-.7L16 6H8l-2.93 1.77c-.3.14-.71.43-.94.7l-.35.4-.34.34L1 12v6a1 1 0 0 0 1 1h2c0-.55.45-1 1-1s1 .45 1 1h10c0-.55.45-1 1-1s1 .45 1 1h2a1 1 0 0 0 1-1v-6z"></path>
                                                <circle cx="7" cy="15" r="2"></circle>
                                                <circle cx="17" cy="15" r="2"></circle>
                                            </svg>
                                            0-100: <?= $car['acceleration'] ?>s
                                        </span>
                                    <?php endif; ?>
                                    <?php if (!empty($car['seats'])): ?>
                                        <span>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                            <?= $car['seats'] ?> chỗ
                                        </span>
                                    <?php endif; ?>
                                    <span>
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <polyline points="12 6 12 12 16 14"></polyline>
                                        </svg>
                                        <?= $transmission ?>
                                    </span>
                                </div>
                                <div class="car-price">
                                    <div class="price-main">
                                        <span class="price"><?= $priceVND ?></span>
                                        <span class="price-label">VNĐ</span>
                                    </div>
                                    <div class="price-usd">
                                        <span class="price-secondary"><?= $priceUSD ?></span>
                                    </div>
                                </div>
                                <div class="car-actions">
                                    <?php if ($car['status'] === 'sold'): ?>
                                        <button class="btn-add-cart" disabled style="opacity: 0.5; cursor: not-allowed; background: #999; border-color: #999;">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <line x1="15" y1="9" x2="9" y2="15"></line>
                                                <line x1="9" y1="9" x2="15" y2="15"></line>
                                            </svg>
                                            Xe đã bán
                                        </button>
                                    <?php else: ?>
                                        <button class="btn-add-cart" onclick="addToCart(<?= $car['id'] ?>, this)">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="9" cy="21" r="1"></circle>
                                                <circle cx="20" cy="21" r="1"></circle>
                                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                            </svg>
                                            Thêm vào giỏ hàng
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-cars">
                        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <h3>Không tìm thấy xe nào</h3>
                        <p>Vui lòng thử lại với bộ lọc khác</p>
                        <button class="btn-primary" onclick="clearFilters()" style="margin-top: 15px;">Xóa bộ lọc</button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php
                    // Build query string with current filters
                    $queryParams = [];
                    if (!empty($filterKeyword)) $queryParams['keyword'] = $filterKeyword;
                    if (!empty($filterBrand)) $queryParams['brand'] = $filterBrand;
                    if (!empty($filterCategory)) $queryParams['category'] = $filterCategory;
                    if ($filterMinPrice) $queryParams['min_price'] = $filterMinPrice;
                    if ($filterMaxPrice) $queryParams['max_price'] = $filterMaxPrice;
                    if ($filterYear) $queryParams['year'] = $filterYear;

                    function buildPageUrl($page, $params)
                    {
                        $params['page'] = $page;
                        return '/cars?' . http_build_query($params);
                    }
                    ?>

                    <!-- Previous Button -->
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= buildPageUrl($currentPage - 1, $queryParams) ?>" class="page-btn prev">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </a>
                    <?php else: ?>
                        <button class="page-btn prev" disabled>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                        </button>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <?php
                    $startPage = max(1, $currentPage - 2);
                    $endPage = min($totalPages, $currentPage + 2);

                    // Always show first page
                    if ($startPage > 1): ?>
                        <a href="<?= buildPageUrl(1, $queryParams) ?>" class="page-btn">1</a>
                        <?php if ($startPage > 2): ?>
                            <span class="page-dots">...</span>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Middle pages -->
                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <button class="page-btn active"><?= $i ?></button>
                        <?php else: ?>
                            <a href="<?= buildPageUrl($i, $queryParams) ?>" class="page-btn"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <!-- Always show last page -->
                    <?php if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <span class="page-dots">...</span>
                        <?php endif; ?>
                        <a href="<?= buildPageUrl($totalPages, $queryParams) ?>" class="page-btn"><?= $totalPages ?></a>
                    <?php endif; ?>

                    <!-- Next Button -->
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= buildPageUrl($currentPage + 1, $queryParams) ?>" class="page-btn next">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </a>
                    <?php else: ?>
                        <button class="page-btn next" disabled>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="9 18 15 12 9 6"></polyline>
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</section>

<script>
    // Price range update function
    function updatePriceInputs(select) {
        const selectedOption = select.options[select.selectedIndex];
        const minPrice = selectedOption.getAttribute('data-min') || '';
        const maxPrice = selectedOption.getAttribute('data-max') || '';
        document.getElementById('min_price').value = minPrice;
        document.getElementById('max_price').value = maxPrice;
    }

    // Clear all filters
    function clearFilters() {
        window.location.href = '/cars';
    }

    // Toggle favorite function
    function toggleFavorite(button, carId) {
        <?php if (isset($_SESSION['user_id'])): ?>
            // Check if already favorited
            const isFavorited = button.classList.contains('favorited');
            const endpoint = isFavorited ? '/favorites/remove' : '/favorites/add';

            fetch(endpoint, {
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
                        // Toggle visual state
                        if (isFavorited) {
                            button.classList.remove('favorited');
                            button.querySelector('svg').style.fill = 'none';
                        } else {
                            button.classList.add('favorited');
                            button.querySelector('svg').style.fill = 'currentColor';
                        }
                    } else {
                        alert(data.message || 'Có lỗi xảy ra!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra!');
                });
        <?php else: ?>
            alert('Vui lòng đăng nhập để thêm vào yêu thích!');
            window.location.href = '/login';
        <?php endif; ?>
    }

    // Initialize favorite buttons
    window.addEventListener('DOMContentLoaded', function() {
        const priceSelect = document.querySelector('.price-filter');
        if (priceSelect && priceSelect.value) {
            updatePriceInputs(priceSelect);
        }

        // Add sort functionality
        const sortSelect = document.querySelector('.sort-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                sortCars(this.value);
            });
        }

        // Add click event to favorite buttons
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const carId = this.getAttribute('data-car-id');
                toggleFavorite(this, carId);
            });
        });

        // Lazy loading animation for car cards - fade in on scroll
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '0';
                    entry.target.style.transform = 'translateY(30px)';

                    setTimeout(() => {
                        entry.target.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }, 50);

                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all car cards
        document.querySelectorAll('.car-card').forEach(card => {
            observer.observe(card);
        });
    });

    // Sort cars function
    function sortCars(sortBy) {
        const carGrid = document.querySelector('.cars-grid');
        const carCards = Array.from(carGrid.querySelectorAll('.car-card'));

        carCards.sort((a, b) => {
            switch (sortBy) {
                case 'newest':
                    // Sort by car ID (data-car-id attribute)
                    const idA = parseInt(a.querySelector('.favorite-btn').getAttribute('data-car-id'));
                    const idB = parseInt(b.querySelector('.favorite-btn').getAttribute('data-car-id'));
                    return idB - idA; // Descending (newest first)

                case 'price-asc':
                    const priceA = parsePrice(a.querySelector('.car-price').textContent);
                    const priceB = parsePrice(b.querySelector('.car-price').textContent);
                    return priceA - priceB; // Ascending

                case 'price-desc':
                    const priceADesc = parsePrice(a.querySelector('.car-price').textContent);
                    const priceBDesc = parsePrice(b.querySelector('.car-price').textContent);
                    return priceBDesc - priceADesc; // Descending

                case 'popular':
                    // Sort by views (if you have view count displayed)
                    // For now, just keep current order
                    return 0;

                default:
                    return 0;
            }
        });

        // Clear and re-append sorted cards
        carGrid.innerHTML = '';
        carCards.forEach(card => carGrid.appendChild(card));
    }

    // Helper function to parse price from text
    function parsePrice(priceText) {
        // Remove "VND", dots, commas and extract number
        return parseInt(priceText.replace(/[^\d]/g, ''));
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>