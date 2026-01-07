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
// $cars, $brands, $categories, $totalCars, $availableCars, $soldCars
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
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3><?= $availableCars ?></h3>
                        <p>Còn hàng</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-info">
                        <h3><?= $soldCars ?></h3>
                        <p>Đã bán</p>
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
            <div class="filters-bar">
                <div class="filter-group">
                    <label>Thương hiệu:</label>
                    <select id="filterBrand">
                        <option value="">Tất cả</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Danh mục:</label>
                    <select id="filterCategory">
                        <option value="">Tất cả</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Trạng thái:</label>
                    <select id="filterStatus">
                        <option value="">Tất cả</option>
                        <option value="available">Còn hàng</option>
                        <option value="sold">Đã bán</option>
                    </select>
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchCar" placeholder="Tìm theo tên xe...">
                </div>
            </div>

            <?php if ($totalCars === 0): ?>
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
                            <?php foreach ($cars as $car): ?>
                                <tr data-brand="<?= $car['brand_id'] ?>" data-category="<?= $car['category_id'] ?>" data-status="<?= $car['status'] ?>">
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
                                        <div class="stock-badge"><i class="fas fa-box"></i> Tồn: <?= $car['stock'] ?? 1 ?></div>
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
            <?php endif; ?>
        </div>
    </main>

    <script>
        function deleteCar(id) {
            if (confirm('Bạn có chắc chắn muốn xóa xe này?')) {
                window.location.href = '<?= BASE_URL ?>/admin/cars/delete/' + id;
            }
        }

        // Filters
        document.getElementById('filterBrand').addEventListener('change', filterCars);
        document.getElementById('filterCategory').addEventListener('change', filterCars);
        document.getElementById('filterStatus').addEventListener('change', filterCars);
        document.getElementById('searchCar').addEventListener('input', filterCars);

        function filterCars() {
            const brand = document.getElementById('filterBrand').value;
            const category = document.getElementById('filterCategory').value;
            const status = document.getElementById('filterStatus').value;
            const search = document.getElementById('searchCar').value.toLowerCase();

            const rows = document.querySelectorAll('.data-table tbody tr');
            let visibleCount = 0;

            rows.forEach(row => {
                const matchBrand = !brand || row.dataset.brand === brand;
                const matchCategory = !category || row.dataset.category === category;
                const matchStatus = !status || row.dataset.status === status;
                const matchSearch = !search || row.textContent.toLowerCase().includes(search);

                if (matchBrand && matchCategory && matchStatus && matchSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>