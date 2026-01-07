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
require_once __DIR__ . '/../../models/AppointmentModel.php';

$carModel = new CarModel();
$userModel = new UserModel();
$orderModel = new OrderModel();
$brandModel = new BrandModel();
$categoryModel = new CategoryModel();
$reviewModel = new ReviewModel();
$contactModel = new ContactModel();
$appointmentModel = new AppointmentModel();

// Lấy thống kê cơ bản
$cars = $carModel->getAll();
$users = $userModel->getAll();
$orders = $orderModel->getAllWithDetails();
$brands = $brandModel->getAll();
$categories = $categoryModel->getAll();
$reviews = $reviewModel->getAllWithDetails();
$contacts = $contactModel->getAll();
$appointments = $appointmentModel->getAllWithDetails();

$totalCars = count($cars);
$totalUsers = count($users);
$totalOrders = count($orders);
$totalBrands = count($brands);
$totalCategories = count($categories);
$totalReviews = count($reviews);
$totalContacts = count($contacts);
$totalAppointments = count($appointments);

// Đếm đơn hàng theo trạng thái
$pendingOrders = count(array_filter($orders, fn($o) => $o['status'] === 'pending'));
$confirmedOrders = count(array_filter($orders, fn($o) => $o['status'] === 'confirmed'));
$cancelledOrders = count(array_filter($orders, fn($o) => $o['status'] === 'cancelled'));

// Đếm lịch hẹn theo trạng thái  
$pendingAppointments = count(array_filter($appointments, fn($a) => $a['status'] === 'pending'));
$confirmedAppointments = count(array_filter($appointments, fn($a) => $a['status'] === 'confirmed'));

// Tính doanh thu
$totalRevenue = array_sum(array_map(fn($o) => $o['status'] === 'confirmed' ? $o['price'] : 0, $orders));
$pendingRevenue = array_sum(array_map(fn($o) => $o['status'] === 'pending' ? $o['price'] : 0, $orders));

// Xe còn hàng / đã bán
$availableCars = count(array_filter($cars, fn($c) => $c['status'] === 'available'));
$soldCars = count(array_filter($cars, fn($c) => $c['status'] === 'sold'));

// Đánh giá trung bình
$avgRating = count($reviews) > 0 ? array_sum(array_map(fn($r) => $r['rating'], $reviews)) / count($reviews) : 0;

// Format doanh thu
function formatRevenue($value) {
    if ($value >= 1000000000) {
        return number_format($value / 1000000000, 1) . ' tỷ';
    } elseif ($value >= 1000000) {
        return number_format($value / 1000000, 0) . ' triệu';
    }
    return number_format($value, 0, ',', '.');
}

// Top 5 xe bán chạy (theo số đơn hàng)
$carSales = [];
foreach ($orders as $order) {
    if ($order['status'] === 'confirmed') {
        $carId = $order['car_id'];
        if (!isset($carSales[$carId])) {
            $carSales[$carId] = [
                'name' => $order['car_name'],
                'count' => 0,
                'revenue' => 0
            ];
        }
        $carSales[$carId]['count']++;
        $carSales[$carId]['revenue'] += $order['price'];
    }
}
usort($carSales, fn($a, $b) => $b['count'] - $a['count']);
$topCars = array_slice($carSales, 0, 5);

// Top 5 khách hàng VIP (theo tổng chi tiêu)
$customerSpending = [];
foreach ($orders as $order) {
    if ($order['status'] === 'confirmed') {
        $userId = $order['user_id'];
        if (!isset($customerSpending[$userId])) {
            $customerSpending[$userId] = [
                'name' => $order['user_name'],
                'total' => 0,
                'orders' => 0
            ];
        }
        $customerSpending[$userId]['total'] += $order['price'];
        $customerSpending[$userId]['orders']++;
    }
}
usort($customerSpending, fn($a, $b) => $b['total'] - $a['total']);
$topCustomers = array_slice($customerSpending, 0, 5);

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
    <title>Dashboard - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-stats.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-dashboard.css">
</head>
<body>
    <?php $activePage = 'dashboard'; include __DIR__ . '/layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <div>
                <h1>Dashboard <span>Analytics</span></h1>
                <p style="font-size: 13px; color: var(--gray-500); margin: 6px 0 0 0; font-weight: 500;">Tổng quan hệ thống AutoCar</p>
            </div>
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
                        <span class="stat-detail"><i class="fas fa-check-circle"></i> <?= $availableCars ?> còn hàng</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-users"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalUsers ?></h3>
                        <p>Khách hàng</p>
                        <span class="stat-detail"><i class="fas fa-user-plus"></i> Đã đăng ký</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalOrders ?></h3>
                        <p>Đơn hàng</p>
                        <span class="stat-detail"><i class="fas fa-clock"></i> <?= $pendingOrders ?> chờ xử lý</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon red"><i class="fas fa-dollar-sign"></i></div>
                    <div class="stat-info">
                        <h3><?= formatRevenue($totalRevenue) ?></h3>
                        <p>Doanh thu</p>
                        <span class="stat-detail"><i class="fas fa-chart-line"></i> <?= $confirmedOrders ?> đơn hoàn thành</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalAppointments ?></h3>
                        <p>Lịch hẹn</p>
                        <span class="stat-detail"><i class="fas fa-hourglass-half"></i> <?= $pendingAppointments ?> chờ duyệt</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon indigo"><i class="fas fa-star"></i></div>
                    <div class="stat-info">
                        <h3><?= number_format($avgRating, 1) ?></h3>
                        <p>Đánh giá TB</p>
                        <span class="stat-detail"><i class="fas fa-comment-dots"></i> <?= $totalReviews ?> đánh giá</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-copyright"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalBrands ?></h3>
                        <p>Thương hiệu</p>
                        <span class="stat-detail"><i class="fas fa-tags"></i> <?= $totalCategories ?> danh mục</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon teal"><i class="fas fa-envelope"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalContacts ?></h3>
                        <p>Liên hệ</p>
                        <span class="stat-detail"><i class="fas fa-envelope-open"></i> <?= $unreadContacts ?> chưa đọc</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Row -->
            <div class="quick-stats">
                <div class="quick-stat">
                    <i class="fas fa-box"></i>
                    <span><?= $soldCars ?> xe đã bán</span>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-money-bill-wave"></i>
                    <span><?= formatRevenue($pendingRevenue) ?> đang chờ</span>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-user-check"></i>
                    <span><?= $confirmedAppointments ?> lịch đã duyệt</span>
                </div>
                <div class="quick-stat">
                    <i class="fas fa-trophy"></i>
                    <span><?= count($topCars) ?> xe bán chạy</span>
                </div>
            </div>

            <!-- Top Stats Grid -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 30px;">
                <!-- Top Xe Bán Chạy -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3><i class="fas fa-fire"></i> Top Xe Bán Chạy</h3>
                    </div>
                    <?php if (count($topCars) === 0): ?>
                    <div style="text-align: center; padding: 40px; color: var(--gray-400);">
                        <i class="fas fa-car" style="font-size: 48px; margin-bottom: 16px;"></i>
                        <p>Chưa có dữ liệu xe bán</p>
                    </div>
                    <?php else: ?>
                    <div class="leaderboard">
                        <?php foreach ($topCars as $index => $car): ?>
                        <div class="leaderboard-item">
                            <div class="leaderboard-rank <?= $index === 0 ? 'top' : '' ?>"><?= $index + 1 ?></div>
                            <div class="leaderboard-info">
                                <h4><?= htmlspecialchars($car['name']) ?></h4>
                                <p><?= $car['count'] ?> đơn hàng • <?= formatRevenue($car['revenue']) ?> VND</p>
                            </div>
                            <div class="leaderboard-value"><?= formatRevenue($car['revenue']) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Top Khách Hàng VIP -->
                <div class="chart-card">
                    <div class="chart-header">
                        <h3><i class="fas fa-crown"></i> Top Khách Hàng VIP</h3>
                    </div>
                    <?php if (count($topCustomers) === 0): ?>
                    <div style="text-align: center; padding: 40px; color: var(--gray-400);">
                        <i class="fas fa-users" style="font-size: 48px; margin-bottom: 16px;"></i>
                        <p>Chưa có dữ liệu khách hàng</p>
                    </div>
                    <?php else: ?>
                    <div class="leaderboard">
                        <?php foreach ($topCustomers as $index => $customer): ?>
                        <div class="leaderboard-item">
                            <div class="leaderboard-rank <?= $index === 0 ? 'top' : '' ?>"><?= $index + 1 ?></div>
                            <div class="leaderboard-info">
                                <h4><?= htmlspecialchars($customer['name']) ?></h4>
                                <p><?= $customer['orders'] ?> đơn hàng • Chi tiêu <?= formatRevenue($customer['total']) ?></p>
                            </div>
                            <div class="leaderboard-value"><?= formatRevenue($customer['total']) ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
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
