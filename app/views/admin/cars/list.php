<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../../config/config.php';
}

// Hàm rút gọn giá tiền
function formatPriceShort($price)
{
    if ($price >= 1000000000) {
        return number_format($price / 1000000000, 1, ',', '.') . ' tỷ';
    } elseif ($price >= 1000000) {
        return number_format($price / 1000000, 0, ',', '.') . ' tr';
    }
    return number_format($price, 0, ',', '.') . ' ₫';
}

// Dữ liệu được truyền từ controller:
// $cars (đã filter), $brands, $categories, $totalCars, $filteredCount, $availableCars, $soldCars

// Lấy filter hiện tại
$filterBrand = $_GET['brand'] ?? '';
$filterCategory = $_GET['category'] ?? '';
$filterStatus = $_GET['status'] ?? '';
$filterStock = $_GET['stock'] ?? '';
$filterSearch = $_GET['search'] ?? '';

// Tính toán số xe theo tồn kho (từ cars đã filter)
$outOfStockCars = 0;
$lowStockCars = 0;
$inStockCars = 0;
$totalStock = 0;
foreach ($cars as $c) {
    $stock = $c['stock'] ?? 0;
    $totalStock += $stock;
    if ($stock <= 0) $outOfStockCars++;
    elseif ($stock <= 5) $lowStockCars++;
    else $inStockCars++;
}

// Phân trang dựa trên số xe đã filter
$carsPerPage = isset($_GET['per_page']) ? intval($_GET['per_page']) : 10;
if (!in_array($carsPerPage, [10, 20, 50, 100])) $carsPerPage = 10;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalPages = ceil($filteredCount / $carsPerPage);
$offset = ($currentPage - 1) * $carsPerPage;
$carsOnPage = array_slice($cars, $offset, $carsPerPage);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý xe - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-cars.css">
</head>

<body>
    <?php $activePage = 'cars';
    include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Quản lý xe</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <div class="page-header-content">
                    <h2>Danh sách xe (<?= $totalCars ?>)</h2>
                    <p class="page-subtitle">Quản lý và theo dõi tất cả các xe trong kho</p>
                </div>
                <a href="<?= BASE_URL ?>/admin/cars/add" style="padding: 14px 28px; border-radius: 12px; font-weight: 700; font-size: 15px; display: inline-flex; align-items: center; gap: 10px; background: linear-gradient(135deg, #D4AF37, #B8960B); color: white; text-decoration: none; box-shadow: 0 4px 16px rgba(212, 175, 55, 0.3); transition: all 0.3s ease; border: none;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 24px rgba(212, 175, 55, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 16px rgba(212, 175, 55, 0.3)';">
                    <i class="fas fa-plus-circle"></i> Thêm xe mới
                </a>
            </div>

            <!-- Stats Cards -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-car"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalCars ?></h3>
                        <p>Tổng số xe</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #9c27b0, #7b1fa2);"><i class="fas fa-boxes"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalStock ?></h3>
                        <p>Tổng tồn kho</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3><?= $inStockCars ?></h3>
                        <p>Còn hàng (>5)</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #ff9800, #f57c00);"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-info">
                        <h3><?= $lowStockCars ?></h3>
                        <p>Sắp hết (1-5)</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-times-circle"></i></div>
                    <div class="stat-info">
                        <h3><?= $outOfStockCars ?></h3>
                        <p>Hết hàng (0)</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="fas fa-tags"></i></div>
                    <div class="stat-info">
                        <h3><?= count($brands) ?></h3>
                        <p>Thương hiệu</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <form method="GET" action="" class="filters-bar" id="filterForm">
                <input type="hidden" name="per_page" value="<?= $carsPerPage ?>">
                <div class="filter-group">
                    <label>Thương hiệu:</label>
                    <select name="brand" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['id'] ?>" <?= $filterBrand == $brand['id'] ? 'selected' : '' ?>><?= htmlspecialchars($brand['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Danh mục:</label>
                    <select name="category" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $filterCategory == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Trạng thái:</label>
                    <select name="status" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="available" <?= $filterStatus === 'available' ? 'selected' : '' ?>>Còn hàng</option>
                        <option value="sold" <?= $filterStatus === 'sold' ? 'selected' : '' ?>>Đã bán</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Tồn kho:</label>
                    <select name="stock" onchange="this.form.submit()">
                        <option value="">Tất cả</option>
                        <option value="out" <?= $filterStock === 'out' ? 'selected' : '' ?>>Hết hàng (0)</option>
                        <option value="low" <?= $filterStock === 'low' ? 'selected' : '' ?>>Sắp hết (1-5)</option>
                        <option value="instock" <?= $filterStock === 'instock' ? 'selected' : '' ?>>Còn hàng (>5)</option>
                    </select>
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" value="<?= htmlspecialchars($filterSearch) ?>" placeholder="Tìm theo tên xe..." onchange="this.form.submit()">
                </div>
                <?php if ($filterBrand || $filterCategory || $filterStatus || $filterStock || $filterSearch): ?>
                <a href="<?= BASE_URL ?>/admin/cars" class="btn-clear-filter">
                    <i class="fas fa-times-circle"></i>
                    <span>Xóa bộ lọc</span>
                </a>
                <?php endif; ?>
            </form>

            <?php if ($filteredCount === 0): ?>
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-car-side"></i>
                    </div>
                    <h3>Chưa có xe nào</h3>
                    <p>Bắt đầu thêm xe mới vào hệ thống để quản lý và bán hàng.</p>
                    <a href="<?= BASE_URL ?>/admin/cars/add" class="btn-primary">
                        <i class="fas fa-plus"></i> Thêm xe đầu tiên
                    </a>
                </div>
            <?php else: ?>
                <!-- Cars Table -->
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 45px;"></th>
                                <th style="width: 280px;">Thông tin xe</th>
                                <th style="width: 160px;">Giá bán</th>
                                <th style="width: 240px;">Thông số kỹ thuật</th>
                                <th style="width: 100px;">Trạng thái</th>
                                <th style="width: 120px;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($carsOnPage as $car): 
                                $stock = $car['stock'] ?? 0;
                                $stockLevel = $stock <= 0 ? 'out' : ($stock <= 5 ? 'low' : 'instock');
                            ?>
                                <tr data-brand="<?= $car['brand_id'] ?>" data-category="<?= $car['category_id'] ?>" data-status="<?= $car['status'] ?>" data-stock="<?= $stockLevel ?>">
                                    <td>
                                        <span class="table-id">#<?= str_pad($car['id'], 3, '0', STR_PAD_LEFT) ?></span>
                                    </td>
                                    <td>
                                        <img src="<?= $car['image_url'] ?? 'https://via.placeholder.com/50x38' ?>" alt="" class="table-thumb">
                                    </td>
                                    <td>
                                        <div class="table-info">
                                            <div class="table-name"><?= htmlspecialchars($car['name']) ?></div>
                                            <div class="table-meta">
                                                <span class="meta-badge brand"><?= htmlspecialchars($car['brand_name'] ?? 'N/A') ?></span>
                                                <span class="meta-badge"><?= $car['year'] ?? 'N/A' ?></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-price-main"><?= formatPriceShort($car['price']) ?></div>
                                        <?php if (!empty($car['acceleration'])): ?>
                                            <div class="table-price-badge"><i class="fas fa-rocket"></i><?= $car['acceleration'] ?>s</div>
                                        <?php endif; ?>
                                        <?php 
                                            $stockClass = $stock <= 0 ? 'stock-out' : ($stock <= 5 ? 'stock-low' : 'stock-instock');
                                        ?>
                                        <div class="stock-badge <?= $stockClass ?>">
                                            <i class="fas fa-<?= $stock <= 0 ? 'times-circle' : ($stock <= 5 ? 'exclamation-circle' : 'check-circle') ?>"></i> 
                                            <?= $stock ?> xe
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-specs-compact">
                                            <?php if (!empty($car['horsepower'])): ?>
                                                <div class="spec-row">
                                                    <span class="spec-value power-value"><?= $car['horsepower'] ?> HP</span>
                                                    <?php if (!empty($car['engine'])): ?>
                                                        <span class="spec-value"> • <?= htmlspecialchars($car['engine']) ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($car['seats']) || !empty($car['drivetrain'])): ?>
                                                <div class="spec-row">
                                                    <?php if (!empty($car['seats'])): ?><span class="spec-value"><?= $car['seats'] ?> chỗ</span><?php endif; ?>
                                                    <?php if (!empty($car['drivetrain'])): ?><span class="spec-value"> • <?= $car['drivetrain'] ?></span><?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="status-badge-wrapper">
                                            <span class="status-badge status-<?= $car['status'] ?>">
                                                <i class="fas fa-<?= $car['status'] === 'available' ? 'check-circle' : ($car['status'] === 'sold' ? 'times-circle' : 'clock') ?>"></i>
                                                <span class="status-text">
                                                    <?php
                                                    echo $car['status'] === 'available' ? 'Còn hàng' : ($car['status'] === 'sold' ? 'Đã bán' : 'Đã đặt');
                                                    ?>
                                                </span>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?= BASE_URL ?>/car/<?= $car['id'] ?>" class="action-btn action-view" title="Xem chi tiết" target="_blank">
                                                <i class="fas fa-eye"></i>
                                                <span class="action-label">Xem</span>
                                            </a>
                                            <a href="<?= BASE_URL ?>/admin/cars/edit?id=<?= $car['id'] ?>" class="action-btn action-edit" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                                <span class="action-label">Sửa</span>
                                            </a>
                                            <button class="action-btn action-delete" title="Xóa xe" onclick="deleteCar(<?= $car['id'] ?>)">
                                                <i class="fas fa-trash-alt"></i>
                                                <span class="action-label">Xóa</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php 
                // Build query string cho pagination
                $queryParams = [];
                if ($filterBrand) $queryParams['brand'] = $filterBrand;
                if ($filterCategory) $queryParams['category'] = $filterCategory;
                if ($filterStatus) $queryParams['status'] = $filterStatus;
                if ($filterStock) $queryParams['stock'] = $filterStock;
                if ($filterSearch) $queryParams['search'] = $filterSearch;
                $queryParams['per_page'] = $carsPerPage;
                
                function buildPageUrl($page, $params) {
                    $params['page'] = $page;
                    return '?' . http_build_query($params);
                }
                ?>
                <?php if ($totalPages > 1): ?>
                <div class="pagination-wrapper">
                    <div class="pagination-info">
                        Hiển thị <?= $offset + 1 ?> - <?= min($offset + $carsPerPage, $filteredCount) ?> trong tổng số <?= $filteredCount ?> xe
                        <?php if ($filteredCount < $totalCars): ?>
                            <span style="color: #D4AF37;">(đã lọc từ <?= $totalCars ?> xe)</span>
                        <?php endif; ?>
                    </div>
                    <div class="pagination">
                        <?php if ($currentPage > 1): ?>
                            <a href="<?= buildPageUrl(1, $queryParams) ?>" class="page-btn" title="Trang đầu">
                                <i class="fas fa-angle-double-left"></i>
                            </a>
                            <a href="<?= buildPageUrl($currentPage - 1, $queryParams) ?>" class="page-btn" title="Trang trước">
                                <i class="fas fa-angle-left"></i>
                            </a>
                        <?php endif; ?>
                        
                        <?php
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        
                        if ($startPage > 1): ?>
                            <a href="<?= buildPageUrl(1, $queryParams) ?>" class="page-btn">1</a>
                            <?php if ($startPage > 2): ?>
                                <span class="page-dots">...</span>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="<?= buildPageUrl($i, $queryParams) ?>" class="page-btn <?= $i === $currentPage ? 'active' : '' ?>"><?= $i ?></a>
                        <?php endfor; ?>
                        
                        <?php if ($endPage < $totalPages): ?>
                            <?php if ($endPage < $totalPages - 1): ?>
                                <span class="page-dots">...</span>
                            <?php endif; ?>
                            <a href="<?= buildPageUrl($totalPages, $queryParams) ?>" class="page-btn"><?= $totalPages ?></a>
                        <?php endif; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="<?= buildPageUrl($currentPage + 1, $queryParams) ?>" class="page-btn" title="Trang sau">
                                <i class="fas fa-angle-right"></i>
                            </a>
                            <a href="<?= buildPageUrl($totalPages, $queryParams) ?>" class="page-btn" title="Trang cuối">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="page-size-selector">
                        <label>Hiển thị:</label>
                        <select id="pageSize" onchange="changePageSize(this.value)">
                            <option value="10" <?= $carsPerPage == 10 ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= $carsPerPage == 20 ? 'selected' : '' ?>>20</option>
                            <option value="50" <?= $carsPerPage == 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= $carsPerPage == 100 ? 'selected' : '' ?>>100</option>
                        </select>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

    <script>
        function deleteCar(id) {
            if (confirm('Bạn có chắc chắn muốn xóa xe này?')) {
                window.location.href = '<?= BASE_URL ?>/admin/cars/delete/' + id;
            }
        }

        function changePageSize(size) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', size);
            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }
    </script>
</body>

</html>