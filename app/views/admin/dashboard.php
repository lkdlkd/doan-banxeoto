<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../config/config.php'; }

// Load models
require_once __DIR__ . '/../../models/CarModel.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/BrandModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';
require_once __DIR__ . '/../../models/ReviewModel.php';
require_once __DIR__ . '/../../models/ContactModel.php';

$carModel = new CarModel();
$userModel = new UserModel();
$orderModel = new OrderModel();
$brandModel = new BrandModel();
$categoryModel = new CategoryModel();
$reviewModel = new ReviewModel();
$contactModel = new ContactModel();

// Lấy thống kê
$cars = $carModel->getAll();
$users = $userModel->getAll();
$orders = $orderModel->getAllWithDetails();
$brands = $brandModel->getAll();
$categories = $categoryModel->getAll();
$reviews = $reviewModel->getAllWithDetails();
$contacts = $contactModel->getAll();

$totalCars = count($cars);
$totalUsers = count($users);
$totalOrders = count($orders);
$totalBrands = count($brands);
$totalCategories = count($categories);
$totalReviews = count($reviews);
$totalContacts = count($contacts);

// Đếm đơn hàng theo trạng thái
$pendingOrders = count(array_filter($orders, fn($o) => $o['status'] === 'pending'));
$confirmedOrders = count(array_filter($orders, fn($o) => $o['status'] === 'confirmed'));
$cancelledOrders = count(array_filter($orders, fn($o) => $o['status'] === 'cancelled'));

// Tính doanh thu (từ đơn confirmed)
$totalRevenue = array_sum(array_map(fn($o) => $o['status'] === 'confirmed' ? $o['price'] : 0, $orders));

// Xe còn hàng / đã bán
$availableCars = count(array_filter($cars, fn($c) => $c['status'] === 'available'));
$soldCars = count(array_filter($cars, fn($c) => $c['status'] === 'sold'));

// Format doanh thu
function formatRevenue($value) {
    if ($value >= 1000000000) {
        return number_format($value / 1000000000, 1) . ' tỷ';
    } elseif ($value >= 1000000) {
        return number_format($value / 1000000, 0) . ' triệu';
    }
    return number_format($value, 0, ',', '.');
}

// Lấy 5 đơn hàng gần nhất
$recentOrders = array_slice($orders, 0, 5);

// Lấy 5 reviews gần nhất
$recentReviews = array_slice($reviews, 0, 5);

// Tin nhắn chưa đọc
$unreadContacts = count(array_filter($contacts, fn($c) => ($c['status'] ?? 'unread') === 'unread'));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - AutoCar</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-dashboard.css">
</head>
<body>
    <?php $activePage = 'dashboard'; include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Dashboard</h1>
            <div class="header-right">
                <div class="header-notifications">
                    <i class="fas fa-bell"></i>
                    <?php if ($unreadContacts > 0): ?>
                    <span class="notif-badge"><?= $unreadContacts ?></span>
                    <?php endif; ?>
                </div>
                <div class="header-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
                </div>
            </div>
        </header>

        <div class="admin-content">
            <!-- Main Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-car"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalCars ?></h3>
                        <p>Tổng số xe</p>
                        <span class="stat-detail"><?= $availableCars ?> còn hàng / <?= $soldCars ?> đã bán</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalUsers ?></h3>
                        <p>Khách hàng</p>
                        <span class="stat-detail">Tài khoản đã đăng ký</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalOrders ?></h3>
                        <p>Đơn hàng</p>
                        <span class="stat-detail"><?= $pendingOrders ?> chờ xử lý</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-info">
                        <h3><?= formatRevenue($totalRevenue) ?></h3>
                        <p>Doanh thu</p>
                        <span class="stat-detail">Từ <?= $confirmedOrders ?> đơn hoàn thành</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Row -->
            <div class="quick-stats">
                <div class="quick-stat">
                    <i class="fas fa-copyright"></i>
                    <span><?= $totalBrands ?> thương hiệu</span>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-tags"></i>
                    <span><?= $totalCategories ?> danh mục</span>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-star"></i>
                    <span><?= $totalReviews ?> đánh giá</span>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-envelope"></i>
                    <span><?= $totalContacts ?> liên hệ</span>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="content-grid">
                <!-- Recent Orders -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-shopping-cart"></i> Đơn hàng gần đây</h2>
                        <a href="<?= BASE_URL ?>/admin/orders">Xem tất cả →</a>
                    </div>
                    <?php if (count($recentOrders) === 0): ?>
                    <div class="card-empty">
                        <i class="fas fa-inbox"></i>
                        <p>Chưa có đơn hàng nào</p>
                    </div>
                    <?php else: ?>
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Khách hàng</th>
                                <th>Xe</th>
                                <th>Giá trị</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $order): ?>
                            <tr>
                                <td>
                                    <div class="customer">
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($order['user_name'] ?? 'User') ?>&background=D4AF37&color=fff" alt="">
                                        <span><?= htmlspecialchars($order['user_name'] ?? 'Khách hàng') ?></span>
                                    </div>
                                </td>
                                <td><?= htmlspecialchars($order['car_name'] ?? 'N/A') ?></td>
                                <td class="price"><?= formatRevenue($order['price']) ?></td>
                                <td>
                                    <span class="status <?= $order['status'] ?>">
                                        <?php 
                                        switch($order['status']) {
                                            case 'pending': echo 'Chờ duyệt'; break;
                                            case 'confirmed': echo 'Xác nhận'; break;
                                            case 'cancelled': echo 'Đã hủy'; break;
                                            default: echo $order['status'];
                                        }
                                        ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>

                <!-- Recent Reviews -->
                <div class="card">
                    <div class="card-header">
                        <h2><i class="fas fa-star"></i> Đánh giá gần đây</h2>
                        <a href="<?= BASE_URL ?>/admin/reviews">Xem tất cả →</a>
                    </div>
                    <?php if (count($recentReviews) === 0): ?>
                    <div class="card-empty">
                        <i class="fas fa-comment-slash"></i>
                        <p>Chưa có đánh giá nào</p>
                    </div>
                    <?php else: ?>
                    <div class="reviews-list">
                        <?php foreach ($recentReviews as $review): ?>
                        <div class="review-item">
                            <div class="review-header">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($review['user_name'] ?? 'User') ?>&background=D4AF37&color=fff" alt="">
                                <div class="review-info">
                                    <h4><?= htmlspecialchars($review['user_name'] ?? 'Khách hàng') ?></h4>
                                    <p><?= htmlspecialchars($review['car_name'] ?? '') ?></p>
                                </div>
                                <div class="review-rating">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $review['rating'] ? '' : 'empty' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <p class="review-text"><?= htmlspecialchars(mb_substr($review['comment'], 0, 100)) ?>...</p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Order Status Summary -->
            <div class="status-summary">
                <h3><i class="fas fa-chart-pie"></i> Trạng thái đơn hàng</h3>
                <div class="status-cards">
                    <div class="status-card pending">
                        <div class="status-number"><?= $pendingOrders ?></div>
                        <div class="status-label">Chờ xử lý</div>
                    </div>
                    <div class="status-card confirmed">
                        <div class="status-number"><?= $confirmedOrders ?></div>
                        <div class="status-label">Đã xác nhận</div>
                    </div>
                    <div class="status-card cancelled">
                        <div class="status-number"><?= $cancelledOrders ?></div>
                        <div class="status-label">Đã hủy</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
