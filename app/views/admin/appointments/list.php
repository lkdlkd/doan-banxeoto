<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; }

// Load models
require_once __DIR__ . '/../../../models/AppointmentModel.php';
require_once __DIR__ . '/../../../models/CarModel.php';

$appointmentModel = new AppointmentModel();

// Lấy danh sách appointments từ database
$appointments = $appointmentModel->getAllWithDetails();

// Tính thống kê
$totalAppointments = count($appointments);
$pendingAppointments = count(array_filter($appointments, fn($a) => $a['status'] === 'pending'));
$confirmedAppointments = count(array_filter($appointments, fn($a) => $a['status'] === 'confirmed'));
$completedAppointments = count(array_filter($appointments, fn($a) => $a['status'] === 'completed'));
$cancelledAppointments = count(array_filter($appointments, fn($a) => $a['status'] === 'cancelled'));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý lịch xem xe - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-orders.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>
<body>
    <?php $activePage = 'appointments'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Quản lý lịch xem xe</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Danh sách lịch hẹn (<?= $totalAppointments ?>)</h2>
            </div>

            <!-- Appointment Stats -->
            <div class="order-stats">
                <div class="order-stat">
                    <div class="order-stat-icon total"><i class="fas fa-calendar-alt"></i></div>
                    <div class="order-stat-info">
                        <h3><?= $totalAppointments ?></h3>
                        <p>Tổng lịch hẹn</p>
                    </div>
                </div>
                <div class="order-stat">
                    <div class="order-stat-icon pending"><i class="fas fa-clock"></i></div>
                    <div class="order-stat-info">
                        <h3><?= $pendingAppointments ?></h3>
                        <p>Chờ xác nhận</p>
                    </div>
                </div>
                <div class="order-stat">
                    <div class="order-stat-icon confirmed"><i class="fas fa-check-circle"></i></div>
                    <div class="order-stat-info">
                        <h3><?= $confirmedAppointments ?></h3>
                        <p>Đã xác nhận</p>
                    </div>
                </div>
                <div class="order-stat">
                    <div class="order-stat-icon revenue"><i class="fas fa-calendar-check"></i></div>
                    <div class="order-stat-info">
                        <h3><?= $completedAppointments ?></h3>
                        <p>Hoàn thành</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label>Trạng thái:</label>
                    <select id="filterStatus">
                        <option value="">Tất cả</option>
                        <option value="pending">Chờ xác nhận</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="completed">Hoàn thành</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Ngày hẹn:</label>
                    <input type="date" id="filterDate">
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchAppointment" placeholder="Tìm theo tên khách, xe...">
                </div>
            </div>

            <?php if ($totalAppointments === 0): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3>Chưa có lịch hẹn nào</h3>
                <p>Hiện tại chưa có lịch hẹn xem xe nào. Các lịch hẹn sẽ xuất hiện ở đây khi khách hàng đặt lịch.</p>
            </div>
            <?php else: ?>
            <!-- Appointments Table -->
            <div class="orders-table-container">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Khách hàng</th>
                            <th>Xe</th>
                            <th>Ngày hẹn</th>
                            <th>Giờ hẹn</th>
                            <th>Số điện thoại</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                        <tr data-status="<?= $appointment['status'] ?>" data-date="<?= $appointment['appointment_date'] ?>">
                            <td><span class="order-id">#<?= str_pad($appointment['id'], 5, '0', STR_PAD_LEFT) ?></span></td>
                            <td>
                                <div class="customer-info">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($appointment['full_name'] ?? 'User') ?>&background=D4AF37&color=fff" alt="">
                                    <div>
                                        <strong><?= htmlspecialchars($appointment['full_name'] ?? 'Khách hàng') ?></strong>
                                        <span><?= htmlspecialchars($appointment['email'] ?? '') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="car-info">
                                    <img src="<?= $appointment['car_image'] ?? 'https://via.placeholder.com/60' ?>" alt="">
                                    <div>
                                        <strong><?= htmlspecialchars($appointment['car_name'] ?? 'Xe') ?></strong>
                                        <span><?= htmlspecialchars($appointment['brand_name'] ?? '') ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><span class="order-date"><?= date('d/m/Y', strtotime($appointment['appointment_date'])) ?></span></td>
                            <td><span class="order-price"><?= date('H:i', strtotime($appointment['appointment_time'])) ?></span></td>
                            <td><span style="color: #6b7280;"><?= htmlspecialchars($appointment['phone']) ?></span></td>
                            <td>
                                <span class="order-status <?= $appointment['status'] ?>">
                                    <?php 
                                    switch($appointment['status']) {
                                        case 'pending': echo 'Chờ xác nhận'; break;
                                        case 'confirmed': echo 'Đã xác nhận'; break;
                                        case 'completed': echo 'Hoàn thành'; break;
                                        case 'cancelled': echo 'Đã hủy'; break;
                                        default: echo $appointment['status'];
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <div class="order-actions">
                                    <?php if ($appointment['status'] === 'pending'): ?>
                                    <button class="action-btn confirm" onclick="updateStatus(<?= $appointment['id'] ?>, 'confirmed')" title="Xác nhận">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="action-btn cancel" onclick="updateStatus(<?= $appointment['id'] ?>, 'cancelled')" title="Hủy lịch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php elseif ($appointment['status'] === 'confirmed'): ?>
                                    <button class="action-btn confirm" onclick="updateStatus(<?= $appointment['id'] ?>, 'completed')" title="Hoàn thành">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="action-btn" onclick="viewDetail(<?= $appointment['id'] ?>)" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn delete" onclick="openDeleteModal(<?= $appointment['id'] ?>, '#<?= str_pad($appointment['id'], 5, '0', STR_PAD_LEFT) ?>')" title="Xóa">
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

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content modal-confirm">
            <div class="modal-body">
                <div class="confirm-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4>Xác nhận xóa?</h4>
                <p>Bạn có chắc chắn muốn xóa lịch hẹn</p>
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
                window.location.href = '<?= BASE_URL ?>/admin/appointments/delete/' + deleteId;
            }
        }

        function updateStatus(id, status) {
            let statusText = '';
            switch(status) {
                case 'confirmed': statusText = 'xác nhận'; break;
                case 'cancelled': statusText = 'hủy'; break;
                case 'completed': statusText = 'đánh dấu hoàn thành'; break;
            }
            
            if (confirm(`Bạn có chắc chắn muốn ${statusText} lịch hẹn này?`)) {
                window.location.href = `<?= BASE_URL ?>/admin/appointments/update-status/${id}/${status}`;
            }
        }

        function viewDetail(id) {
            window.location.href = `<?= BASE_URL ?>/admin/appointments/detail/${id}`;
        }

        // Filter by status
        document.getElementById('filterStatus').addEventListener('change', filterAppointments);
        document.getElementById('filterDate').addEventListener('change', filterAppointments);

        function filterAppointments() {
            const status = document.getElementById('filterStatus').value;
            const date = document.getElementById('filterDate').value;
            const rows = document.querySelectorAll('.orders-table tbody tr');
            
            rows.forEach(row => {
                const matchStatus = !status || row.dataset.status === status;
                const matchDate = !date || row.dataset.date === date;
                row.style.display = matchStatus && matchDate ? '' : 'none';
            });
        }

        // Search
        document.getElementById('searchAppointment').addEventListener('input', function() {
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
