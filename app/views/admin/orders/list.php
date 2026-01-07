<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; }

// Load models
require_once __DIR__ . '/../../../models/OrderModel.php';
require_once __DIR__ . '/../../../models/CarModel.php';

$orderModel = new OrderModel();

// Lấy danh sách orders từ database
$orders = $orderModel->getAllWithDetails();

// Tính thống kê
$totalOrders = count($orders);
$pendingOrders = count(array_filter($orders, fn($o) => $o['status'] === 'pending'));
$confirmedOrders = count(array_filter($orders, fn($o) => $o['status'] === 'confirmed'));
$cancelledOrders = count(array_filter($orders, fn($o) => $o['status'] === 'cancelled'));
$totalRevenue = array_sum(array_map(fn($o) => $o['status'] === 'confirmed' ? $o['price'] : 0, $orders));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-orders.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>
<body>
    <?php $activePage = 'orders'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <div>
                <h1>Quản lý đơn hàng</h1>
                <p style="font-size: 13px; color: var(--gray-500); margin: 6px 0 0 0; font-weight: 500;">Theo dõi và xử lý đơn đặt hàng</p>
            </div>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <div class="page-header-content">
                    <h2>Danh sách đơn hàng (<?= $totalOrders ?>)</h2>
                    <p class="page-subtitle">Quản lý tất cả đơn đặt xe từ khách hàng</p>
                </div>
            </div>

            <!-- Order Stats -->
            <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="fas fa-shopping-cart"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalOrders ?></h3>
                        <p>Tổng đơn hàng</p>
                        <span class="stat-detail"><i class="fas fa-calendar"></i> Tất cả</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <h3><?= $pendingOrders ?></h3>
                        <p>Chờ xử lý</p>
                        <span class="stat-detail"><i class="fas fa-hourglass-half"></i> Cần xử lý</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3><?= $confirmedOrders ?></h3>
                        <p>Đã xác nhận</p>
                        <span class="stat-detail"><i class="fas fa-thumbs-up"></i> Hoàn tất</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-coins"></i></div>
                    <div class="stat-info">
                        <h3 style="font-size: 28px;"><?= number_format($totalRevenue/1000000000, 1) ?> tỷ</h3>
                        <p>Doanh thu</p>
                        <span class="stat-detail"><i class="fas fa-chart-line"></i> Đã xác nhận</span>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label>Trạng thái</label>
                    <select id="filterStatus">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending">Chờ xử lý</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Thanh toán</label>
                    <select id="filterPayment">
                        <option value="">Tất cả hình thức</option>
                        <option value="bank_transfer">Chuyển khoản</option>
                        <option value="cash">Tiền mặt</option>
                        <option value="deposit">Đặt cọc</option>
                    </select>
                </div>
                <div class="filter-search">
                    <label>Tìm kiếm</label>
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchOrder" placeholder="Tìm theo mã đơn, tên khách hàng...">
                </div>
            </div>

            <?php if ($totalOrders === 0): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3>Chưa có đơn hàng nào</h3>
                <p>Hiện tại chưa có đơn đặt hàng nào. Các đơn hàng sẽ xuất hiện ở đây khi khách hàng đặt mua xe.</p>
            </div>
            <?php else: ?>
            <!-- Orders Table -->
            <div class="card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Mã ĐH</th>
                            <th style="width: 200px;">Khách hàng</th>
                            <th>Xe</th>
                            <th style="width: 120px; text-align: right;">Giá</th>
                            <th style="width: 130px; text-align: center;">Trạng thái</th>
                            <th style="width: 110px; text-align: center;">Ngày đặt</th>
                            <th style="width: 120px; text-align: center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                        <tr data-status="<?= $order['status'] ?>" data-payment="<?= $order['payment_method'] ?>">
                            <td><span class="order-id">#<?= str_pad($order['id'], 4, '0', STR_PAD_LEFT) ?></span></td>
                            <td>
                                <div class="table-info">
                                    <div class="table-name" style="font-size: 14px;"><?= htmlspecialchars($order['user_name'] ?? 'Khách hàng') ?></div>
                                    <div style="font-size: 12px; color: var(--gray-500);"><?= htmlspecialchars($order['user_email'] ?? '') ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="table-info">
                                    <div class="table-name" style="font-size: 14px;"><?= htmlspecialchars($order['car_name'] ?? 'Xe') ?></div>
                                    <div style="font-size: 12px; color: var(--gray-500);"><?= htmlspecialchars($order['brand_name'] ?? '') ?></div>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <div class="table-price-main"><?= number_format($order['price']/1000000000, 2) ?> tỷ</div>
                                <?php if ($order['payment_method'] === 'deposit'): ?>
                                <div style="font-size: 11px; color: var(--gray-500);">Cọc <?= ($order['deposit_percentage'] ?? 10) ?>%</div>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: center;">
                                <span class="status-badge <?= $order['status'] ?>">
                                    <?php 
                                    switch($order['status']) {
                                        case 'pending': echo 'CHỜ XỬ LÝ'; break;
                                        case 'confirmed': echo 'ĐÃ XÁC NHẬN'; break;
                                        case 'cancelled': echo 'ĐÃ HỦY'; break;
                                        default: echo strtoupper($order['status']);
                                    }
                                    ?>
                                </span>
                            </td>
                            <td style="text-align: center;">
                                <span class="order-date"><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <?php if ($order['status'] === 'pending'): ?>
                                    <button class="action-btn" onclick="updateStatus(<?= $order['id'] ?>, 'confirmed')" title="Xác nhận">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="action-btn" onclick="updateStatus(<?= $order['id'] ?>, 'cancelled')" title="Hủy đơn">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="action-btn" onclick="viewDetail(<?= $order['id'] ?>)" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content modal-confirm">
            <div class="modal-body">
                <div class="confirm-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4>Xác nhận xóa?</h4>
                <p>Bạn có chắc chắn muốn xóa đơn hàng</p>
                <p class="item-name" id="deleteName"></p>
                <p style="color: #999; font-size: 0.85rem;">Hành động này không thể hoàn tác</p>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="button" class="btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </div>
        </div>
    </div>

    <script>
        let deleteId = null;

        function openDeleteModal(id, name) {
            deleteId = id;
            document.getElementById('deleteName').textContent = name;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        function confirmDelete() {
            if (deleteId) {
                window.location.href = '<?= BASE_URL ?>/admin/orders/delete/' + deleteId;
            }
        }

        function updateStatus(id, status) {
            const statusText = status === 'confirmed' ? 'xác nhận' : 'hủy';
            if (confirm(`Bạn có chắc chắn muốn ${statusText} đơn hàng này?`)) {
                window.location.href = `<?= BASE_URL ?>/admin/orders/update-status/${id}/${status}`;
            }
        }

        function viewDetail(id) {
            window.location.href = `<?= BASE_URL ?>/admin/orders/detail/${id}`;
        }

        function deleteOrder(id) {
            if (confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')) {
                window.location.href = '<?= BASE_URL ?>/admin/orders/delete/' + id;
            }
        }

        // Filter by status
        document.getElementById('filterStatus').addEventListener('change', filterOrders);
        document.getElementById('filterPayment').addEventListener('change', filterOrders);

        function filterOrders() {
            const status = document.getElementById('filterStatus').value;
            const payment = document.getElementById('filterPayment').value;
            const rows = document.querySelectorAll('.orders-table tbody tr');
            
            rows.forEach(row => {
                const matchStatus = !status || row.dataset.status === status;
                const matchPayment = !payment || row.dataset.payment === payment;
                row.style.display = matchStatus && matchPayment ? '' : 'none';
            });
        }

        // Search
        document.getElementById('searchOrder').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            const rows = document.querySelectorAll('.orders-table tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(search) ? '' : 'none';
            });
        });

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.active').forEach(m => m.classList.remove('active'));
            }
        });
    </script>
</body>
</html>
