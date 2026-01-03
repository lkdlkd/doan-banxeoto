<?php 
$currentPage = 'cars';
$pageTitle = 'Danh Sách Xe';

// Kết nối cơ sở dữ liệu
require_once __DIR__ . '/../../models/BrandModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';
require_once __DIR__ . '/../../models/CarModel.php';

$brandModel = new BrandModel();
$categoryModel = new CategoryModel();
$carModel = new CarModel();

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

// Lấy danh sách xe (có filter nếu có)
$brandFilter = !empty($filterBrand) ? implode(',', array_filter($filterBrand)) : null;
$categoryFilter = !empty($filterCategory) ? implode(',', array_filter($filterCategory)) : null;
$cars = $carModel->search($filterKeyword, $brandFilter, $categoryFilter, $filterMinPrice, $filterMaxPrice);

// Filter by year if specified
if ($filterYear && !empty($cars)) {
    $cars = array_filter($cars, function($car) use ($filterYear) {
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
function formatPriceVND($price) {
    if ($price >= 1000000000) {
        return number_format($price / 1000000000, 2, '.', '') . ' Tỷ';
    } elseif ($price >= 1000000) {
        return number_format($price / 1000000, 0, '', '') . ' Triệu';
    }
    return number_format($price, 0, '', ',');
}

// Hàm format tiền USD
function formatPriceUSD($price) {
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

    <!-- Page Banner -->
    <section class="page-banner">
        <div class="banner-bg"></div>
        <div class="banner-overlay"></div>
        <div class="banner-content">
            <h1>Khám Phá <span class="highlight">Bộ Sưu Tập Xe</span></h1>
            <p>Tìm kiếm chiếc xe hoàn hảo dành cho bạn</p>
            <div class="breadcrumb">
                <a href="/">Trang Chủ</a>
                <span class="divider">›</span>
                <span class="current">Danh Sách Xe</span>
            </div>
        </div>
    </section>

    <!-- Car List Section -->
    <section class="car-list-section">
        <div class="container">
            <div class="car-list-wrapper">
                
                <!-- Filter Sidebar -->
                <aside class="filter-sidebar">
                    <form method="GET" action="/cars" id="filterForm">
                    <div class="filter-header">
                        <h3>
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            Bộ Lọc Tìm Kiếm
                        </h3>
                        <button type="button" class="clear-filter" onclick="clearFilters()">Xóa tất cả</button>
                    </div>

                    <div class="filter-body">
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
                            <div class="filter-options scrollable">
                                <?php if (!empty($brands)): ?>
                                    <?php foreach ($brands as $brand): ?>
                                    <label class="checkbox-item">
                                        <input type="checkbox" name="brand[]" value="<?= $brand['id'] ?>" <?= in_array($brand['id'], $filterBrand) ? 'checked' : '' ?>>
                                        <span class="checkmark"></span>
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
                                        <span class="checkmark"></span>
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

                        <!-- Apply Button -->
                        <button type="submit" class="apply-filter-btn">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            Áp Dụng Bộ Lọc
                        </button>
                    </div>
                    </form>
                </aside>

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
                                if ($car['year'] >= 2025) {
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
                                    <img src="<?= htmlspecialchars($mainImage) ?>" alt="<?= htmlspecialchars($car['name']) ?>">
                                    <?php if ($badge): ?>
                                    <div class="car-badges">
                                        <span class="badge <?= $badgeClass ?>"><?= $badge ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="car-quick-actions">
                                        <button class="quick-btn favorite-btn" data-car-id="<?= $car['id'] ?>" title="Yêu thích">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                            </svg>
                                        </button>
                                        <button class="quick-btn compare-btn" onclick="addToCompare(<?= $car['id'] ?>, this)" title="So sánh">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <polyline points="17 1 21 5 17 9"></polyline>
                                                <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                                                <polyline points="7 23 3 19 7 15"></polyline>
                                                <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="car-overlay">
                                        <a href="/car/<?= $car['id'] ?>" class="view-details">Xem Chi Tiết</a>
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
                                        <span>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg> 
                                            <?= $transmission ?>
                                        </span>
                                        <?php if ($car['mileage'] > 0): ?>
                                        <span>
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                            </svg> 
                                            <?= number_format($car['mileage']) ?> km
                                        </span>
                                        <?php endif; ?>
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
                                        <a href="/car/<?= $car['id'] ?>" class="btn-view">Xem Chi Tiết</a>
                                        <button class="btn-add-cart" onclick="addToCart(<?= $car['id'] ?>, this)">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="9" cy="21" r="1"></circle>
                                                <circle cx="20" cy="21" r="1"></circle>
                                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                            </svg>
                                            Thêm
                                        </button>
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
                        
                        function buildPageUrl($page, $params) {
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

// Initialize price inputs on page load
window.addEventListener('DOMContentLoaded', function() {
    const priceSelect = document.querySelector('.price-filter');
    if (priceSelect && priceSelect.value) {
        updatePriceInputs(priceSelect);
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
