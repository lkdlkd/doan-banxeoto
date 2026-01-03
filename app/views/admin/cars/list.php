<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; }

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
    <?php $activePage = 'cars'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Quản lý xe</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Danh sách xe (<?= $totalCars ?>)</h2>
                <a href="<?= BASE_URL ?>/admin/cars/add" class="btn-primary">
                    <i class="fas fa-plus"></i> Thêm xe mới
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
            <div class="card">
                <table class="cars-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Xe</th>
                            <th>Thương hiệu</th>
                            <th>Giá</th>
                            <th>Năm SX</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cars as $car): ?>
                        <tr data-brand="<?= $car['brand_id'] ?>" data-category="<?= $car['category_id'] ?>" data-status="<?= $car['status'] ?>">
                            <td><span class="car-id">#<?= $car['id'] ?></span></td>
                            <td>
                                <div class="car-info">
                                    <img src="<?= $car['image_url'] ?? 'https://via.placeholder.com/80x60' ?>" alt="">
                                    <div class="car-info-text">
                                        <h4><?= htmlspecialchars($car['name']) ?></h4>
                                        <p><?= htmlspecialchars($car['category_name'] ?? 'N/A') ?></p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="car-brand"><?= htmlspecialchars($car['brand_name'] ?? 'N/A') ?></span></td>
                            <td><span class="car-price"><?= number_format($car['price'], 0, ',', '.') ?> VNĐ</span></td>
                            <td><span class="car-year"><?= $car['year'] ?? 'N/A' ?></span></td>
                            <td>
                                <span class="car-status <?= $car['status'] ?>">
                                    <?= $car['status'] === 'available' ? 'Còn hàng' : 'Đã bán' ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-btns">
                                    <a href="<?= BASE_URL ?>/car/<?= $car['id'] ?>" class="action-btn view" title="Xem" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= BASE_URL ?>/admin/cars/edit?id=<?= $car['id'] ?>" class="action-btn edit" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="action-btn delete" title="Xóa" onclick="deleteCar(<?= $car['id'] ?>)">
                                        <i class="fas fa-trash"></i>
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
            
            const rows = document.querySelectorAll('.cars-table tbody tr');
            rows.forEach(row => {
                const matchBrand = !brand || row.dataset.brand === brand;
                const matchCategory = !category || row.dataset.category === category;
                const matchStatus = !status || row.dataset.status === status;
                const matchSearch = !search || row.textContent.toLowerCase().includes(search);
                
                row.style.display = matchBrand && matchCategory && matchStatus && matchSearch ? '' : 'none';
            });
        }
    </script>
</body>
</html>
