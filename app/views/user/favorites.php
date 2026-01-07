<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../config/config.php';
}

// Load model
require_once __DIR__ . '/../../models/AppointmentModel.php';
require_once __DIR__ . '/../../models/FavoriteModel.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$favoriteModel = new FavoriteModel();
$userId = $_SESSION['user_id'];

// Lấy danh sách xe yêu thích của user
$favorites = $favoriteModel->getByUserId($userId);

$pageTitle = 'Xe yêu thích - AutoCar';
$currentPage = 'favorites';

include __DIR__ . '/../layouts/header.php';
?>

<!-- Custom CSS for Favorites Page -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/favorites.css">

<!-- Banner -->
<div class="favorites-banner">
    <div class="favorites-banner-content">
        <h1>Xe <span>yêu thích</span></h1>
        <p>Danh sách những chiếc xe bạn đã lưu</p>
    </div>
</div>

<div class="favorites-page">
    <div class="container pt-5">
        <?php if (!empty($favorites)): ?>
            <?php
            // Tính toán thống kê
            $totalFavorites = count($favorites);
            $availableCars = count(array_filter($favorites, fn($c) => ($c['status'] ?? 'available') === 'available'));
            $totalValue = array_sum(array_column($favorites, 'price'));
            $brands = array_unique(array_column($favorites, 'brand_name'));
            $totalBrands = count($brands);
            ?>

            <!-- Stats Table -->
            <div class="favorites-stats mb-4">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>
                                <i class="fas fa-heart"></i>
                                Xe yêu thích
                            </th>
                            <th>
                                <i class="fas fa-check-circle"></i>
                                Còn hàng
                            </th>
                            <th>
                                <i class="fas fa-tag"></i>
                                Thương hiệu
                            </th>
                            <th>
                                <i class="fas fa-dollar-sign"></i>
                                Tổng giá trị
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="stat-icon">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <span class="stat-value"><?= $totalFavorites ?></span>
                                <span class="stat-label">Xe</span>
                            </td>
                            <td>
                                <div class="stat-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <span class="stat-value"><?= $availableCars ?></span>
                                <span class="stat-label">Sẵn có</span>
                            </td>
                            <td>
                                <div class="stat-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <span class="stat-value"><?= $totalBrands ?></span>
                                <span class="stat-label">Hãng</span>
                            </td>
                            <td>
                                <div class="stat-icon">
                                    <i class="fas fa-money-bill"></i>
                                </div>
                                <span class="stat-value"><?= number_format($totalValue / 1000000000, 1) ?>B</span>
                                <span class="stat-label">VNĐ</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (empty($favorites)): ?>
            <div class="empty-favorites">
                <i class="fas fa-heart"></i>
                <h3>Chưa có xe yêu thích</h3>
                <p>Bạn chưa lưu xe nào vào danh sách yêu thích. Hãy khám phá và thêm xe bạn thích!</p>
                <a href="/cars" class="btn-explore">
                    <i class="fas fa-car"></i>
                    Khám phá xe
                </a>
            </div>
        <?php else: ?>
            <div class="favorites-grid">
                <?php foreach ($favorites as $car): ?>
                    <div class="favorite-card" data-id="<?= $car['car_id'] ?>">
                        <button class="favorite-remove" onclick="removeFavorite(<?= $car['car_id'] ?>)" title="Xóa khỏi yêu thích">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="favorite-image">
                            <img src="<?= $car['image'] ?? 'https://via.placeholder.com/400x220' ?>" alt="<?= htmlspecialchars($car['car_name'] ?? '') ?>">
                            <div class="favorite-badge">
                                <span class="year"><?= $car['year'] ?? date('Y') ?></span>
                                <?php if (!empty($car['status']) && $car['status'] === 'available'): ?>
                                    <span>Còn hàng</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="favorite-content">
                            <div class="favorite-brand"><?= htmlspecialchars($car['brand_name'] ?? '') ?></div>
                            <h3 class="favorite-name"><?= htmlspecialchars($car['car_name'] ?? '') ?></h3>
                            <div class="favorite-specs">
                                <div class="favorite-spec">
                                    <i class="fas fa-gas-pump"></i>
                                    <strong><?= $car['fuel'] ?? 'Xăng' ?></strong>
                                </div>
                                <div class="favorite-spec">
                                    <i class="fas fa-cog"></i>
                                    <strong><?= $car['transmission'] ?? 'Tự động' ?></strong>
                                </div>
                                <div class="favorite-spec">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <strong><?= number_format($car['mileage'] ?? 0) ?> km</strong>
                                </div>
                            </div>
                            <div class="favorite-footer">
                                <div class="favorite-price">
                                    <span class="label">Giá bán</span>
                                    <span class="amount"><?php
                                                            $price = $car['price'] ?? 0;
                                                            if ($price >= 1000000000) {
                                                                echo number_format($price / 1000000000, 1) . ' tỷ';
                                                            } else if ($price >= 1000000) {
                                                                echo number_format($price / 1000000, 0) . ' tr';
                                                            } else {
                                                                echo number_format($price, 0, ',', '.');
                                                            }
                                                            ?></span>
                                </div>
                                <a href="/car/<?= $car['car_id'] ?>" class="btn-view">
                                    Chi tiết
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    function removeFavorite(carId) {
        if (confirm('Bạn có chắc muốn xóa xe này khỏi danh sách yêu thích?')) {
            fetch('/favorites/remove', {
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
                        // Xóa card khỏi DOM với animation
                        const card = document.querySelector(`.favorite-card[data-id="${carId}"]`);
                        if (card) {
                            card.style.transform = 'scale(0.8)';
                            card.style.opacity = '0';
                            setTimeout(() => {
                                card.remove();
                                // Kiểm tra nếu hết xe thì reload trang
                                if (document.querySelectorAll('.favorite-card').length === 0) {
                                    location.reload();
                                }
                            }, 300);
                        }
                    } else {
                        alert(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Có lỗi xảy ra');
                });
        }
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>