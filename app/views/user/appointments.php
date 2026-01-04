<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../config/config.php'; }

// Load model
require_once __DIR__ . '/../../models/AppointmentModel.php';

$appointmentModel = new AppointmentModel();
$userId = $_SESSION['user_id'];

// Lấy danh sách lịch hẹn của user
$appointments = $appointmentModel->getAppointmentsByUser($userId);

$pageTitle = 'Lịch hẹn xem xe - AutoCar';
$currentPage = 'appointments';

include __DIR__ . '/../layouts/header.php';
?>

<style>
.appointments-page {
    padding: 120px 0 80px;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    min-height: 100vh;
}

.appointments-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.appointments-header {
    text-align: center;
    margin-bottom: 50px;
}

.appointments-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 42px;
    color: #fff;
    margin-bottom: 10px;
}

.appointments-header h1 span {
    color: #D4AF37;
}

.appointments-header p {
    color: rgba(255,255,255,0.6);
    font-size: 16px;
}

.appointments-list {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.appointment-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.appointment-card:hover {
    border-color: rgba(212, 175, 55, 0.5);
    transform: translateY(-2px);
}

.appointment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: rgba(212, 175, 55, 0.05);
    border-bottom: 1px solid rgba(212, 175, 55, 0.1);
}

.appointment-info h3 {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: #D4AF37;
    margin: 0 0 5px 0;
}

.appointment-info span {
    color: rgba(255,255,255,0.5);
    font-size: 13px;
}

.appointment-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.appointment-status.pending {
    background: rgba(245, 158, 11, 0.2);
    color: #f59e0b;
}

.appointment-status.confirmed {
    background: rgba(59, 130, 246, 0.2);
    color: #3b82f6;
}

.appointment-status.completed {
    background: rgba(16, 185, 129, 0.2);
    color: #10b981;
}

.appointment-status.cancelled {
    background: rgba(239, 68, 68, 0.2);
    color: #ef4444;
}

.appointment-body {
    padding: 24px;
}

.appointment-items {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.appointment-item {
    display: flex;
    gap: 16px;
    padding: 16px;
    background: rgba(255,255,255,0.02);
    border-radius: 12px;
}

.appointment-item-image {
    width: 120px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.appointment-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.appointment-item-info {
    flex: 1;
}

.appointment-item-info h4 {
    color: #fff;
    font-size: 16px;
    margin: 0 0 8px 0;
}

.appointment-item-info p {
    color: rgba(255,255,255,0.5);
    font-size: 13px;
    margin: 0;
}

.appointment-datetime {
    display: flex;
    gap: 20px;
    margin-top: 10px;
}

.appointment-datetime-item {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #e0e0e0;
    font-size: 14px;
}

.appointment-datetime-item i {
    color: #D4AF37;
}

.appointment-item-price {
    text-align: right;
}

.appointment-item-price .price {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: #D4AF37;
    font-weight: 600;
}

.appointment-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-top: 1px solid rgba(212, 175, 55, 0.1);
    background: rgba(0,0,0,0.2);
}

.appointment-details {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.appointment-details span {
    color: rgba(255,255,255,0.6);
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.appointment-details span i {
    color: #D4AF37;
    width: 16px;
}

.appointment-actions {
    display: flex;
    gap: 10px;
}

.btn-appointment {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-appointment.primary {
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
}

.btn-appointment.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(212, 175, 55, 0.3);
}

.btn-appointment.danger {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.btn-appointment.danger:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: #ef4444;
}

/* Empty State */
.empty-appointments {
    text-align: center;
    padding: 80px 20px;
}

.empty-appointments i {
    font-size: 80px;
    color: rgba(212, 175, 55, 0.3);
    margin-bottom: 24px;
}

.empty-appointments h3 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #fff;
    margin-bottom: 12px;
}

.empty-appointments p {
    color: rgba(255,255,255,0.5);
    margin-bottom: 30px;
}

.empty-appointments .btn-explore {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 14px 32px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    text-decoration: none;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.empty-appointments .btn-explore:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
}

/* Alert Messages */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 15px;
    font-weight: 500;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert i {
    font-size: 20px;
}

.alert-success {
    background: rgba(16, 185, 129, 0.15);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #10b981;
}

.alert-error {
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
}

@media (max-width: 768px) {
    .appointments-header h1 {
        font-size: 32px;
    }
    
    .appointment-header {
        flex-direction: column;
        gap: 12px;
        align-items: flex-start;
    }
    
    .appointment-item {
        flex-direction: column;
    }
    
    .appointment-item-image {
        width: 100%;
        height: 150px;
    }
    
    .appointment-datetime {
        flex-direction: column;
        gap: 8px;
    }
    
    .appointment-footer {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
}
</style>

<div class="appointments-page">
    <div class="appointments-container">
        <div class="appointments-header">
            <h1>Lịch hẹn <span>xem xe</span></h1>
            <p>Quản lý và theo dõi tất cả lịch hẹn của bạn</p>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($appointments)): ?>
        <div class="empty-appointments">
            <i class="fas fa-calendar-times"></i>
            <h3>Chưa có lịch hẹn nào</h3>
            <p>Bạn chưa đặt lịch xem xe nào. Hãy khám phá bộ sưu tập xe của chúng tôi!</p>
            <a href="/cars" class="btn-explore">
                <i class="fas fa-car"></i>
                Khám phá xe
            </a>
        </div>
        <?php else: ?>
        <div class="appointments-list">
            <?php foreach ($appointments as $appointment): ?>
            <div class="appointment-card">
                <div class="appointment-header">
                    <div class="appointment-info">
                        <h3>Lịch hẹn #<?= str_pad($appointment['id'], 6, '0', STR_PAD_LEFT) ?></h3>
                        <span><i class="fas fa-calendar-plus"></i> Đặt lúc: <?= date('d/m/Y H:i', strtotime($appointment['created_at'])) ?></span>
                    </div>
                    <span class="appointment-status <?= $appointment['status'] ?? 'pending' ?>">
                        <?php 
                        switch($appointment['status'] ?? 'pending') {
                            case 'pending': echo 'Chờ xác nhận'; break;
                            case 'confirmed': echo 'Đã xác nhận'; break;
                            case 'completed': echo 'Hoàn thành'; break;
                            case 'cancelled': echo 'Đã hủy'; break;
                            default: echo 'Chờ xác nhận';
                        }
                        ?>
                    </span>
                </div>
                <div class="appointment-body">
                    <div class="appointment-items">
                        <div class="appointment-item">
                            <div class="appointment-item-image">
                                <img src="<?= $appointment['car_image'] ?? 'https://via.placeholder.com/120x80' ?>" alt="">
                            </div>
                            <div class="appointment-item-info">
                                <h4><?= htmlspecialchars($appointment['car_name'] ?? 'Xe') ?></h4>
                                <p><?= htmlspecialchars($appointment['brand_name'] ?? '') ?></p>
                                <div class="appointment-datetime">
                                    <div class="appointment-datetime-item">
                                        <i class="fas fa-calendar"></i>
                                        <span><?= date('d/m/Y', strtotime($appointment['appointment_date'])) ?></span>
                                    </div>
                                    <div class="appointment-datetime-item">
                                        <i class="fas fa-clock"></i>
                                        <span><?= date('H:i', strtotime($appointment['appointment_time'])) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="appointment-item-price">
                                <span class="price"><?= number_format($appointment['car_price'] ?? 0, 0, ',', '.') ?>₫</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="appointment-footer">
                    <div class="appointment-details">
                        <span><i class="fas fa-user"></i> <?= htmlspecialchars($appointment['full_name']) ?></span>
                        <span><i class="fas fa-phone"></i> <?= htmlspecialchars($appointment['phone']) ?></span>
                        <span><i class="fas fa-envelope"></i> <?= htmlspecialchars($appointment['email']) ?></span>
                    </div>
                    <div class="appointment-actions">
                        <a href="/car/<?= $appointment['car_id'] ?>" class="btn-appointment primary">
                            <i class="fas fa-eye"></i> Xem xe
                        </a>
                        <?php if (($appointment['status'] ?? 'pending') === 'pending'): ?>
                        <form method="POST" action="/appointment/cancel/<?= $appointment['id'] ?>" style="display: inline;" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');">
                            <button type="submit" class="btn-appointment danger">
                                <i class="fas fa-times"></i> Hủy lịch
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
