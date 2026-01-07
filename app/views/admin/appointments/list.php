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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-stats.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-orders.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>
<body>
    <?php $activePage = 'appointments'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <div>
                <h1>Quản lý lịch xem xe</h1>
                <p style="font-size: 13px; color: var(--gray-500); margin: 6px 0 0 0; font-weight: 500;">Theo dõi và quản lý lịch hẹn xem xe</p>
            </div>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <div class="page-header-content">
                    <h2>Danh sách lịch hẹn (<?= $totalAppointments ?>)</h2>
                    <p class="page-subtitle">Quản lý tất cả lịch hẹn xem xe từ khách hàng</p>
                </div>
            </div>

            <!-- Appointment Stats -->
            <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fas fa-calendar-alt"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalAppointments ?></h3>
                        <p>Tổng lịch hẹn</p>
                        <span class="stat-detail"><i class="fas fa-list"></i> Tất cả</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
                    <div class="stat-info">
                        <h3><?= $pendingAppointments ?></h3>
                        <p>Chờ xác nhận</p>
                        <span class="stat-detail"><i class="fas fa-hourglass-half"></i> Cần xử lý</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                    <div class="stat-info">
                        <h3><?= $confirmedAppointments ?></h3>
                        <p>Đã xác nhận</p>
                        <span class="stat-detail"><i class="fas fa-thumbs-up"></i> Đã duyệt</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
                    <div class="stat-info">
                        <h3><?= $completedAppointments ?></h3>
                        <p>Hoàn thành</p>
                        <span class="stat-detail"><i class="fas fa-check-double"></i> Đã xem xe</span>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label><i class="fas fa-filter"></i> Trạng thái</label>
                    <select id="filterStatus">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending">Chờ xác nhận</option>
                        <option value="confirmed">Đã xác nhận</option>
                        <option value="completed">Hoàn thành</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label><i class="fas fa-calendar"></i> Ngày hẹn</label>
                    <input type="date" id="filterDate">
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchAppointment" placeholder="Tìm theo tên, xe, SĐT...">
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
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Mã LH</th>
                            <th>Khách hàng</th>
                            <th>Xe</th>
                            <th style="width: 140px;">Ngày & Giờ</th>
                            <th style="width: 120px;">SĐT</th>
                            <th style="width: 130px;">Trạng thái</th>
                            <th style="width: 100px; text-align: center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                        <tr data-status="<?= $appointment['status'] ?>" data-date="<?= $appointment['appointment_date'] ?>">
                            <td><span class="order-id">#<?= str_pad($appointment['id'], 4, '0', STR_PAD_LEFT) ?></span></td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <strong style="font-size: 14px; color: var(--gray-900);"><?= htmlspecialchars($appointment['full_name'] ?? 'Khách hàng') ?></strong>
                                    <span style="font-size: 12px; color: var(--gray-500);"><?= htmlspecialchars($appointment['email'] ?? '') ?></span>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <strong style="font-size: 14px; color: var(--gray-900);"><?= htmlspecialchars($appointment['car_name'] ?? 'Xe') ?></strong>
                                    <span style="font-size: 12px; color: var(--gray-500);"><?= htmlspecialchars($appointment['brand_name'] ?? '') ?></span>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <span style="font-size: 13px; color: var(--gray-900); font-weight: 600;"><?= date('d/m/Y', strtotime($appointment['appointment_date'])) ?></span>
                                    <span style="font-size: 12px; color: var(--gray-500);"><i class="far fa-clock"></i> <?= date('H:i', strtotime($appointment['appointment_time'])) ?></span>
                                </div>
                            </td>
                            <td><span style="color: var(--gray-700); font-size: 13px;"><?= htmlspecialchars($appointment['phone']) ?></span></td>
                            <td>
                                <span class="status-badge <?= $appointment['status'] ?>">
                                    <?php 
                                    switch($appointment['status']) {
                                        case 'pending': echo '<i class="fas fa-clock"></i> Chờ xác nhận'; break;
                                        case 'confirmed': echo '<i class="fas fa-check"></i> Đã xác nhận'; break;
                                        case 'completed': echo '<i class="fas fa-check-double"></i> Hoàn thành'; break;
                                        case 'cancelled': echo '<i class="fas fa-times"></i> Đã hủy'; break;
                                        default: echo $appointment['status'];
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <?php if ($appointment['status'] === 'pending'): ?>
                                    <button class="action-btn" onclick="updateStatus(<?= $appointment['id'] ?>, 'confirmed')" title="Xác nhận">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="action-btn" onclick="updateStatus(<?= $appointment['id'] ?>, 'cancelled')" title="Hủy lịch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <?php elseif ($appointment['status'] === 'confirmed'): ?>
                                    <button class="action-btn" onclick="updateStatus(<?= $appointment['id'] ?>, 'completed')" title="Hoàn thành">
                                        <i class="fas fa-check-double"></i>
                                    </button>
                                    <?php endif; ?>
                                    <button class="action-btn" onclick="viewDetail(<?= $appointment['id'] ?>)" title="Xem chi tiết">
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
