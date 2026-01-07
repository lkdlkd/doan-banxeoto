<?php
// Helper function format price
function formatPriceShort($price) {
    if ($price >= 1000000000) {
        return number_format($price / 1000000000, 1) . ' tỷ';
    } elseif ($price >= 1000000) {
        return number_format($price / 1000000, 0) . ' tr';
    }
    return number_format($price, 0, ',', '.') . ' đ';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê - Admin | AutoCar</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-stats.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        .statistics-container {
            padding: 30px;
            max-width: 1800px;
            margin: 0 auto;
        }
        .page-header {
            margin-bottom: 32px;
        }
        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0 0 8px 0;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .page-title i {
            color: var(--primary);
            font-size: 40px;
        }
        .page-subtitle {
            font-size: 15px;
            color: var(--gray-600);
            margin: 0;
        }
        
        /* Charts Grid */
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 24px;
            margin-bottom: 30px;
        }
        
        .chart-box {
            background: #fff;
            border-radius: 20px;
            padding: 28px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--gray-100);
        }
        
        .chart-box-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid var(--gray-100);
        }
        
        .chart-box-title {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .chart-box-title i {
            color: var(--primary);
            font-size: 20px;
        }
        
        .chart-canvas-container {
            position: relative;
            height: 320px;
        }
        
        .chart-canvas-container.tall {
            height: 400px;
        }
        
        /* Filter Buttons */
        .filter-btn {
            padding: 10px 20px;
            border: 2px solid var(--gray-200);
            background: #fff;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-600);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        
        .filter-btn:hover {
            border-color: var(--primary);
            background: var(--primary-light);
            color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
        }
        
        .filter-btn.active {
            border-color: var(--primary);
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            box-shadow: 0 4px 16px rgba(212, 175, 55, 0.3);
        }
        
        .date-selectors {
            display: flex;
            gap: 8px;
        }
        
        .date-selectors select {
            padding: 10px 16px;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-700);
            background: #fff;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .date-selectors select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
        }
        
        /* Top Customers */
        .customers-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        
        .customer-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px;
            background: linear-gradient(135deg, #fff 0%, #fafbfc 100%);
            border: 2px solid var(--gray-100);
            border-radius: 14px;
            transition: all 0.3s ease;
        }
        
        .customer-item:hover {
            border-color: var(--primary);
            transform: translateX(4px);
            box-shadow: 0 4px 16px rgba(212, 175, 55, 0.2);
        }
        
        .customer-rank {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 16px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }
        
        .customer-rank.top-1 {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .customer-rank.top-2 {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        
        .customer-rank.top-3 {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        
        .customer-info {
            flex: 1;
        }
        
        .customer-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--gray-800);
            margin: 0 0 4px 0;
        }
        
        .customer-email {
            font-size: 12px;
            color: var(--gray-500);
            margin: 0;
        }
        
        .customer-stats {
            text-align: right;
        }
        
        .customer-revenue {
            font-size: 16px;
            font-weight: 700;
            color: var(--primary);
            margin: 0 0 4px 0;
        }
        
        .customer-orders {
            font-size: 12px;
            color: var(--gray-500);
        }
    </style>
</head>
<body>

<div class="admin-container">
    <?php $activePage = 'statistics'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <div class="admin-main">
        <div class="statistics-container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-chart-pie"></i>
                    Thống kê & Báo cáo
                </h1>
                <p class="page-subtitle">Phân tích toàn diện về doanh thu, đơn hàng và khách hàng</p>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="fas fa-sack-dollar"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= formatPriceShort($overviewStats['total_revenue']) ?></h3>
                        <p>Tổng doanh thu</p>
                        <span class="stat-detail">
                            <i class="fas fa-infinity"></i>
                            Tất cả thời gian
                        </span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon gold">
                        <i class="fas fa-calendar-week"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= formatPriceShort($overviewStats['month_revenue']) ?></h3>
                        <p>Doanh thu tháng này</p>
                        <?php
                        $changePercent = $revenueComparison['change_percent'];
                        $trendClass = $changePercent >= 0 ? 'up' : 'down';
                        $trendIcon = $changePercent >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                        ?>
                        <span class="stat-trend <?= $trendClass ?>">
                            <i class="fas <?= $trendIcon ?>"></i>
                            <?= abs(round($changePercent, 1)) ?>% so với tháng trước
                        </span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($overviewStats['total_orders']) ?></h3>
                        <p>Đơn hàng hoàn thành</p>
                        <span class="stat-detail">
                            <i class="fas fa-check-double"></i>
                            TB: <?= formatPriceShort($avgStats['avg_order']) ?>/đơn
                        </span>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($overviewStats['total_customers']) ?></h3>
                        <p>Tổng khách hàng</p>
                        <span class="stat-detail">
                            <i class="fas fa-user-plus"></i>
                            Đã đăng ký
                        </span>
                    </div>
                </div>
            </div>

            <!-- Main Revenue Chart -->
            <div class="chart-box" style="margin-bottom: 24px;">
                <div class="chart-box-header">
                    <h2 class="chart-box-title">
                        <i class="fas fa-chart-line"></i>
                        Biểu đồ doanh thu
                    </h2>
                    <div style="display: flex; gap: 12px; align-items: center;">
                        <div style="display: flex; gap: 8px;">
                            <a href="?period=day&year=<?= $year ?>&month=<?= $month ?>" 
                               class="filter-btn <?= $period === 'day' ? 'active' : '' ?>">
                                <i class="fas fa-calendar-day"></i>
                                Theo ngày
                            </a>
                            <a href="?period=month&year=<?= $year ?>" 
                               class="filter-btn <?= $period === 'month' ? 'active' : '' ?>">
                                <i class="fas fa-calendar-alt"></i>
                                Theo tháng
                            </a>
                            <a href="?period=year" 
                               class="filter-btn <?= $period === 'year' ? 'active' : '' ?>">
                                <i class="fas fa-calendar"></i>
                                Theo năm
                            </a>
                        </div>
                        
                        <?php if ($period !== 'year'): ?>
                        <div class="date-selectors">
                            <?php if ($period === 'day'): ?>
                            <select onchange="window.location.href='?period=day&year=<?= $year ?>&month=' + this.value">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $m == $month ? 'selected' : '' ?>>
                                    Tháng <?= $m ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                            <?php endif; ?>
                            
                            <select onchange="window.location.href='?period=<?= $period ?>&year=' + this.value + '&month=<?= $month ?>'">
                                <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>>
                                    Năm <?= $y ?>
                                </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="chart-canvas-container tall">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="charts-grid">
                <!-- Brand Revenue Chart -->
                <div class="chart-box">
                    <div class="chart-box-header">
                        <h3 class="chart-box-title">
                            <i class="fas fa-copyright"></i>
                            Doanh thu theo thương hiệu
                        </h3>
                    </div>
                    <div class="chart-canvas-container">
                        <canvas id="brandChart"></canvas>
                    </div>
                </div>

                <!-- Order Status Chart -->
                <div class="chart-box">
                    <div class="chart-box-header">
                        <h3 class="chart-box-title">
                            <i class="fas fa-tasks"></i>
                            Trạng thái đơn hàng
                        </h3>
                    </div>
                    <div class="chart-canvas-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Payment Method Chart -->
                <div class="chart-box">
                    <div class="chart-box-header">
                        <h3 class="chart-box-title">
                            <i class="fas fa-credit-card"></i>
                            Phương thức thanh toán
                        </h3>
                    </div>
                    <div class="chart-canvas-container">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>

                <!-- Top Customers -->
                <div class="chart-box">
                    <div class="chart-box-header">
                        <h3 class="chart-box-title">
                            <i class="fas fa-crown"></i>
                            Khách hàng VIP
                        </h3>
                    </div>
                    <div class="customers-list">
                        <?php if (!empty($topCustomers)): ?>
                            <?php foreach ($topCustomers as $index => $customer): ?>
                            <div class="customer-item">
                                <div class="customer-rank <?= $index < 3 ? 'top-' . ($index + 1) : '' ?>">
                                    <?= $index + 1 ?>
                                </div>
                                <div class="customer-info">
                                    <h4 class="customer-name"><?= htmlspecialchars($customer['full_name']) ?></h4>
                                    <p class="customer-email"><?= htmlspecialchars($customer['email']) ?></p>
                                </div>
                                <div class="customer-stats">
                                    <p class="customer-revenue"><?= formatPriceShort($customer['total_spent']) ?></p>
                                    <p class="customer-orders"><?= $customer['total_orders'] ?> đơn hàng</p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p style="text-align: center; color: var(--gray-500); padding: 20px;">Chưa có dữ liệu</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
// Revenue Chart
const revenueLabels = <?= json_encode($chartLabels) ?>;
const revenueData = <?= json_encode($chartData) ?>;

const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const gradient = revenueCtx.createLinearGradient(0, 0, 0, 350);
gradient.addColorStop(0, 'rgba(212, 175, 55, 0.4)');
gradient.addColorStop(1, 'rgba(212, 175, 55, 0.05)');

new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Doanh thu',
            data: revenueData,
            borderColor: '#D4AF37',
            backgroundColor: gradient,
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointRadius: 6,
            pointBackgroundColor: '#D4AF37',
            pointBorderColor: '#fff',
            pointBorderWidth: 3,
            pointHoverRadius: 10,
            pointHoverBackgroundColor: '#B8960B',
            pointHoverBorderWidth: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 16,
                borderColor: '#D4AF37',
                borderWidth: 2,
                titleFont: { size: 14, weight: 'bold' },
                bodyFont: { size: 13 },
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        const value = context.parsed.y;
                        if (value >= 1000000000) return (value / 1000000000).toFixed(1) + ' tỷ';
                        if (value >= 1000000) return (value / 1000000).toFixed(0) + ' tr';
                        return value.toLocaleString('vi-VN') + ' đ';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: {
                    callback: function(value) {
                        if (value >= 1000000000) return (value / 1000000000).toFixed(1) + ' tỷ';
                        if (value >= 1000000) return (value / 1000000).toFixed(0) + ' tr';
                        return value.toLocaleString('vi-VN');
                    },
                    font: { size: 12, weight: '600' },
                    color: '#6b7280'
                }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 12, weight: '600' }, color: '#6b7280' }
            }
        }
    }
});

// Brand Chart
<?php
$brandLabels = array_column($brandStats, 'name');
$brandRevenue = array_column($brandStats, 'total_revenue');
?>
const brandData = {
    labels: <?= json_encode($brandLabels) ?>,
    datasets: [{
        data: <?= json_encode($brandRevenue) ?>,
        backgroundColor: [
            'rgba(212, 175, 55, 0.8)',
            'rgba(59, 130, 246, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(239, 68, 68, 0.8)',
            'rgba(139, 92, 246, 0.8)',
            'rgba(245, 158, 11, 0.8)'
        ],
        borderColor: '#fff',
        borderWidth: 3
    }]
};

new Chart(document.getElementById('brandChart'), {
    type: 'doughnut',
    data: brandData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 15, font: { size: 12, weight: '600' } }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 16,
                callbacks: {
                    label: function(context) {
                        const value = context.parsed;
                        return context.label + ': ' + (value >= 1000000000 ? (value / 1000000000).toFixed(1) + ' tỷ' : (value / 1000000).toFixed(0) + ' tr');
                    }
                }
            }
        }
    }
});

// Status Chart
<?php
$statusLabels = [];
$statusCounts = [];
$statusColors = ['rgba(245, 158, 11, 0.8)', 'rgba(16, 185, 129, 0.8)', 'rgba(239, 68, 68, 0.8)', 'rgba(59, 130, 246, 0.8)'];
foreach ($orderStatusStats as $stat) {
    $statusNames = ['pending' => 'Chờ xử lý', 'confirmed' => 'Đã xác nhận', 'cancelled' => 'Đã hủy', 'completed' => 'Hoàn thành'];
    $statusLabels[] = $statusNames[$stat['status']] ?? $stat['status'];
    $statusCounts[] = $stat['count'];
}
?>
new Chart(document.getElementById('statusChart'), {
    type: 'pie',
    data: {
        labels: <?= json_encode($statusLabels) ?>,
        datasets: [{
            data: <?= json_encode($statusCounts) ?>,
            backgroundColor: <?= json_encode($statusColors) ?>,
            borderColor: '#fff',
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: { padding: 15, font: { size: 12, weight: '600' } }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 16
            }
        }
    }
});

// Payment Chart
<?php
$paymentLabels = [];
$paymentCounts = [];
foreach ($paymentStats as $stat) {
    $paymentNames = ['bank_transfer' => 'Chuyển khoản', 'cash' => 'Tiền mặt', 'deposit' => 'Đặt cọc'];
    $paymentLabels[] = $paymentNames[$stat['payment_method']] ?? $stat['payment_method'];
    $paymentCounts[] = $stat['count'];
}
?>
new Chart(document.getElementById('paymentChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($paymentLabels) ?>,
        datasets: [{
            label: 'Số đơn hàng',
            data: <?= json_encode($paymentCounts) ?>,
            backgroundColor: [
                'rgba(212, 175, 55, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(59, 130, 246, 0.8)'
            ],
            borderColor: [
                '#D4AF37',
                '#10b981',
                '#3b82f6'
            ],
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 16
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0, 0, 0, 0.05)' },
                ticks: { font: { size: 12, weight: '600' }, color: '#6b7280' }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 12, weight: '600' }, color: '#6b7280' }
            }
        }
    }
});
</script>

</body>
</html>
