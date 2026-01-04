<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết lịch hẹn #<?= str_pad($appointment['id'], 5, '0', STR_PAD_LEFT) ?> - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-orders.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
    <style>
        .order-detail-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .detail-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .detail-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #1a1a1a;
            margin: 0;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #fff;
            color: #666;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: #f8f8f8;
            border-color: #D4AF37;
            color: #D4AF37;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .detail-card {
            background: #fff;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .detail-card h3 {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #1a1a1a;
            margin: 0 0 1.5rem 0;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .car-detail {
            display: flex;
            gap: 1.5rem;
        }

        .car-detail-image {
            width: 250px;
            height: 180px;
            border-radius: 8px;
            overflow: hidden;
        }

        .car-detail-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .car-detail-info h4 {
            font-size: 1.25rem;
            color: #1a1a1a;
            margin: 0 0 0.5rem 0;
        }

        .car-detail-info p {
            color: #666;
            margin: 0.25rem 0;
            font-size: 0.95rem;
        }

        .car-detail-info .car-price {
            font-size: 1.5rem;
            color: #D4AF37;
            font-weight: 600;
            margin-top: 1rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 1rem 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #666;
            font-weight: 500;
        }

        .info-value {
            color: #1a1a1a;
            font-weight: 600;
            text-align: right;
        }

        .customer-detail {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f8f8f8;
            border-radius: 8px;
            margin-bottom: 1.5rem;
        }

        .customer-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
        }

        .customer-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1a1a1a;
            margin: 0 0 0.25rem 0;
        }

        .customer-email {
            color: #666;
            font-size: 0.9rem;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem;
            background: #f8f8f8;
            border-radius: 8px;
        }

        .contact-item i {
            color: #D4AF37;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .contact-item span {
            color: #1a1a1a;
            font-weight: 500;
        }

        .status-actions {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .status-current {
            padding: 1rem;
            background: #f8f8f8;
            border-radius: 8px;
            text-align: center;
        }

        .status-current label {
            display: block;
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .action-buttons .btn {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-confirm {
            background: #10b981;
            color: white;
        }

        .btn-confirm:hover {
            background: #059669;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .btn-cancel {
            background: #ef4444;
            color: white;
        }

        .btn-cancel:hover {
            background: #dc2626;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-complete {
            background: #3b82f6;
            color: white;
        }

        .btn-complete:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .btn-delete {
            background: #6b7280;
            color: white;
        }

        .btn-delete:hover {
            background: #4b5563;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }

        .notes-section {
            background: #fff9e6;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #D4AF37;
        }

        .notes-section h4 {
            color: #1a1a1a;
            margin: 0 0 0.75rem 0;
            font-size: 1rem;
        }

        .notes-section p {
            color: #666;
            margin: 0;
            line-height: 1.6;
        }

        .appointment-datetime {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .datetime-box {
            flex: 1;
            padding: 1.5rem;
            background: linear-gradient(135deg, #D4AF37 0%, #C4A030 100%);
            border-radius: 12px;
            text-align: center;
            color: white;
        }

        .datetime-box i {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .datetime-box label {
            display: block;
            font-size: 0.8rem;
            opacity: 0.9;
            margin-bottom: 0.5rem;
        }

        .datetime-box strong {
            display: block;
            font-size: 1.25rem;
            font-weight: 700;
        }

        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .car-detail {
                flex-direction: column;
            }

            .car-detail-image {
                width: 100%;
                height: 200px;
            }

            .appointment-datetime {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php $activePage = 'appointments'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Chi tiết lịch hẹn</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="order-detail-container">
                <div class="detail-header">
                    <h2>Lịch hẹn #<?= str_pad($appointment['id'], 5, '0', STR_PAD_LEFT) ?></h2>
                    <a href="<?= BASE_URL ?>/admin/appointments" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                        Quay lại
                    </a>
                </div>

                <div class="detail-grid">
                    <!-- Main Info -->
                    <div>
                        <!-- Car Info -->
                        <div class="detail-card">
                            <h3>Thông tin xe</h3>
                            <div class="car-detail">
                                <div class="car-detail-image">
                                    <img src="<?= $appointment['car_image'] ?? 'https://via.placeholder.com/250x180' ?>" alt="<?= htmlspecialchars($appointment['car_name']) ?>">
                                </div>
                                <div class="car-detail-info">
                                    <h4><?= htmlspecialchars($appointment['car_name']) ?></h4>
                                    <p><strong>Thương hiệu:</strong> <?= htmlspecialchars($appointment['brand_name']) ?></p>
                                    <p class="car-price"><?= number_format($appointment['car_price'], 0, ',', '.') ?>₫</p>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Info -->
                        <div class="detail-card" style="margin-top: 2rem;">
                            <h3>Thông tin khách hàng</h3>
                            <div class="customer-detail">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($appointment['full_name']) ?>&background=D4AF37&color=fff" alt="" class="customer-avatar">
                                <div>
                                    <h4 class="customer-name"><?= htmlspecialchars($appointment['full_name']) ?></h4>
                                    <p class="customer-email"><?= htmlspecialchars($appointment['user_email'] ?? '') ?></p>
                                </div>
                            </div>
                            
                            <div class="contact-info">
                                <div class="contact-item">
                                    <i class="fas fa-envelope"></i>
                                    <span><?= htmlspecialchars($appointment['email']) ?></span>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone"></i>
                                    <span><?= htmlspecialchars($appointment['phone']) ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <?php if (!empty($appointment['notes'])): ?>
                        <div class="detail-card" style="margin-top: 2rem;">
                            <div class="notes-section">
                                <h4><i class="fas fa-comment-dots"></i> Ghi chú của khách hàng</h4>
                                <p><?= nl2br(htmlspecialchars($appointment['notes'])) ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Sidebar -->
                    <div>
                        <!-- Appointment Info -->
                        <div class="detail-card">
                            <h3>Thông tin lịch hẹn</h3>
                            
                            <div class="appointment-datetime">
                                <div class="datetime-box">
                                    <i class="fas fa-calendar"></i>
                                    <label>Ngày hẹn</label>
                                    <strong><?= date('d/m/Y', strtotime($appointment['appointment_date'])) ?></strong>
                                </div>
                                <div class="datetime-box">
                                    <i class="fas fa-clock"></i>
                                    <label>Giờ hẹn</label>
                                    <strong><?= date('H:i', strtotime($appointment['appointment_time'])) ?></strong>
                                </div>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Mã lịch hẹn:</span>
                                <span class="info-value">#<?= str_pad($appointment['id'], 5, '0', STR_PAD_LEFT) ?></span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Ngày tạo:</span>
                                <span class="info-value"><?= date('d/m/Y H:i', strtotime($appointment['created_at'])) ?></span>
                            </div>

                            <div class="status-current">
                                <label>Trạng thái hiện tại</label>
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
                            </div>

                            <div class="status-actions">
                                <div class="action-buttons">
                                    <?php if ($appointment['status'] === 'pending'): ?>
                                    <button class="btn btn-confirm" onclick="updateStatus(<?= $appointment['id'] ?>, 'confirmed')">
                                        <i class="fas fa-check-circle"></i> Xác nhận lịch hẹn
                                    </button>
                                    <button class="btn btn-cancel" onclick="updateStatus(<?= $appointment['id'] ?>, 'cancelled')">
                                        <i class="fas fa-times-circle"></i> Hủy lịch hẹn
                                    </button>
                                    <?php elseif ($appointment['status'] === 'confirmed'): ?>
                                    <button class="btn btn-complete" onclick="updateStatus(<?= $appointment['id'] ?>, 'completed')">
                                        <i class="fas fa-check-double"></i> Đánh dấu hoàn thành
                                    </button>
                                    <?php endif; ?>
                                    <button class="btn btn-delete" onclick="deleteAppointment(<?= $appointment['id'] ?>)">
                                        <i class="fas fa-trash"></i> Xóa lịch hẹn
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
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

        function deleteAppointment(id) {
            if (confirm('Bạn có chắc chắn muốn xóa lịch hẹn này? Hành động này không thể hoàn tác.')) {
                window.location.href = '<?= BASE_URL ?>/admin/appointments/delete/' + id;
            }
        }
    </script>
</body>
</html>
