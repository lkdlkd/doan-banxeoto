<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../config/config.php'; }

// Load model
require_once __DIR__ . '/../../models/AppointmentModel.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$appointmentModel = new AppointmentModel();
$userId = $_SESSION['user_id'];

// Lấy danh sách lịch hẹn của user
try {
    $appointments = $appointmentModel->getAppointmentsByUser($userId);
} catch (Exception $e) {
    $appointments = [];
    error_log("Error fetching appointments: " . $e->getMessage());
}

$pageTitle = 'Lịch hẹn xem xe - AutoCar';
$currentPage = 'appointments';

include __DIR__ . '/../layouts/header.php';
?>

<style>
/* Banner */
.appointments-banner {
    position: relative;
    height: 350px;
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), 
                url('https://images.unsplash.com/photo-1501139083538-0139583c060f?w=1920&q=80') center/cover;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: -80px;
}

.appointments-banner-content {
    text-align: center;
    color: #fff;
    position: relative;
    z-index: 1;
    max-width: 800px;
    padding: 0 20px;
}

.appointments-banner h1 {
    font-family: 'Playfair Display', serif;
    font-size: 56px;
    font-weight: 700;
    margin: 0 0 16px 0;
    line-height: 1.2;
}

.appointments-banner h1 span {
    background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.appointments-banner p {
    font-size: 18px;
    font-weight: 400;
    opacity: 0.95;
    line-height: 1.6;
    margin: 0;
}

.appointments-page {
    padding: 120px 0 80px;
    background: linear-gradient(135deg, #f9f7f3 0%, #f5f2ed 100%);
    min-height: 100vh;
}

.appointments-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 30px;
}

.appointments-header {
    display: none; /* Now using banner instead */
}

.appointments-header h1 {
    display: none;
}

.appointments-header h1 span {
    display: none;
}

.appointments-header p {
    display: none;
}

/* Alert Messages */
.alert {
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 500;
    animation: slideDown 0.4s ease-out;
}

@keyframes slideDown {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.alert-success {
    background: rgba(16, 185, 129, 0.1);
    border: 1px solid rgba(16, 185, 129, 0.3);
    color: #059669;
}

.alert-error {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #dc2626;
}

.appointments-list {
    display: grid;
    gap: 24px;
}

.appointment-card {
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.appointment-card:hover {
    border-color: #D4AF37;
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(212,175,55,0.15);
}

.appointment-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    background: linear-gradient(135deg, rgba(212,175,55,0.08) 0%, rgba(212,175,55,0.03) 100%);
    border-bottom: 1px solid #f0f0f0;
}

.appointment-info h3 {
    font-family: 'Inter', sans-serif;
    font-size: 18px;
    color: #0a0a0a;
    margin: 0 0 6px 0;
    font-weight: 700;
}

.appointment-info span {
    color: #666;
    font-size: 13px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
}

.appointment-info span i {
    color: #D4AF37;
}

.appointment-status {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.appointment-status.pending {
    background: linear-gradient(135deg, #FFF3CD, #FFF9E6);
    color: #856404;
    border: 1px solid #FFE17B;
}

.appointment-status.confirmed {
    background: linear-gradient(135deg, #CCE5FF, #E7F3FF);
    color: #004085;
    border: 1px solid #80BDFF;
}

.appointment-status.completed {
    background: linear-gradient(135deg, #D1F4E0, #E8FAF0);
    color: #155724;
    border: 1px solid #7BDDA5;
}

.appointment-status.cancelled {
    background: linear-gradient(135deg, #F8D7DA, #FDEAEC);
    color: #721c24;
    border: 1px solid #F5A9B2;
}

.appointment-body {
    padding: 24px;
    display: flex;
    gap: 24px;
}

.appointment-car {
    flex: 1;
    display: flex;
    gap: 20px;
}

.car-image {
    width: 180px;
    height: 140px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid #e5e5e5;
}

.car-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.appointment-card:hover .car-image img {
    transform: scale(1.05);
}

.car-details h4 {
    font-family: 'Inter', sans-serif;
    font-size: 20px;
    color: #0a0a0a;
    margin: 0 0 10px 0;
    font-weight: 700;
    line-height: 1.3;
}

.car-brand {
    display: inline-block;
    background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);
    color: #000;
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 12px;
}

.car-price {
    font-size: 22px;
    font-weight: 800;
    color: #D4AF37;
    margin-top: 10px;
}

.appointment-datetime {
    flex-shrink: 0;
    width: 280px;
    padding: 18px;
    background: linear-gradient(135deg, rgba(212,175,55,0.06), rgba(212,175,55,0.02));
    border-radius: 12px;
    border: 1px solid rgba(212,175,55,0.15);
    display: flex;
    flex-direction: column;
    gap: 14px;
}

.datetime-item {
    display: flex;
    align-items: center;
    gap: 12px;
}

.datetime-item i {
    font-size: 20px;
    color: #D4AF37;
    width: 24px;
    text-align: center;
}

.datetime-item div strong {
    display: block;
    color: #888;
    font-size: 11px;
    text-transform: uppercase;
    margin-bottom: 4px;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.datetime-item div span {
    color: #0a0a0a;
    font-size: 15px;
    font-weight: 700;
}

.appointment-footer {
    padding: 18px 24px;
    background: rgba(249,247,243,0.5);
    border-top: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.customer-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.customer-info span {
    color: #666;
    font-size: 13px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 500;
}

.customer-info span i {
    color: #D4AF37;
    font-size: 14px;
    width: 16px;
    text-align: center;
}

.appointment-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border-radius: 10px;
    border: none;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);
    color: #000;
    box-shadow: 0 4px 10px rgba(212,175,55,0.2);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(212,175,55,0.35);
}

.btn-danger {
    background: #fff;
    color: #dc2626;
    border: 1.5px solid #fca5a5;
}

.btn-danger:hover {
    background: #fef2f2;
    border-color: #ef4444;
    transform: translateY(-2px);
}

.empty-state {
    text-align: center;
    padding: 100px 20px;
    background: #fff;
    border-radius: 20px;
    border: 1px solid #e5e5e5;
}

.empty-state i {
    font-size: 80px;
    color: rgba(212, 175, 55, 0.25);
    margin-bottom: 24px;
}

.empty-state h2 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    color: #0a0a0a;
    margin-bottom: 12px;
    font-weight: 700;
}

.empty-state p {
    color: #666;
    margin-bottom: 32px;
    font-size: 16px;
    font-weight: 500;
}

.btn-explore {
    background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%);
    color: #000;
    padding: 14px 32px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 6px 16px rgba(212,175,55,0.25);
}

.btn-explore:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 24px rgba(212,175,55,0.4);
}

/* Responsive */
@media (max-width: 968px) {
    .appointment-body {
        flex-direction: column;
    }
    
    .appointment-datetime {
        width: 100%;
        flex-direction: row;
        justify-content: space-between;
    }
}

@media (max-width: 768px) {
    .appointments-header h1 {
        font-size: 36px;
    }
    
    .appointment-car {
        flex-direction: column;
    }
    
    .car-image {
        width: 100%;
        height: 200px;
    }
    
    .appointment-datetime {
        flex-direction: column;
    }
    
    .appointment-footer {
        flex-direction: column;
        gap: 16px;
        align-items: flex-start;
    }
    
    .appointment-actions {
        width: 100%;
        flex-direction: column;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}

/* Legacy code cleanup - remove old appointment-items structure */
.appointment-items {
    display: none;
}
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
    display: none; /* Legacy code */
}

.appointment-item-price .price {
    display: none; /* Legacy code */
}

.appointment-details {
    display: none; /* Use customer-info instead */
}

.appointment-details span {
    display: none; /* Legacy code */
}

.appointment-details span i {
    display: none; /* Legacy code */
}

.btn-appointment {
    display: none; /* Legacy code */
}

.btn-appointment.primary {
    display: none; /* Legacy code */
}

.btn-appointment.primary:hover {
    display: none; /* Legacy code */
}

.btn-appointment.danger {
    display: none; /* Legacy code */
}

.btn-appointment.danger:hover {
    display: none; /* Legacy code */
}

/* Empty State - Legacy (now using empty-state) */
.empty-appointments {
    display: none;
}

.empty-appointments i {
    display: none;
}

.empty-appointments h3 {
    display: none;
}

.empty-appointments p {
    display: none;
}

.empty-appointments .btn-explore {
    display: none;
}

.empty-appointments .btn-explore:hover {
    display: none;
}

/* Alert Messages - keep these */
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
    .appointments-banner h1 {
        font-size: 36px;
    }
    
    .appointments-banner p {
        font-size: 16px;
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

<!-- Banner -->
<div class="appointments-banner">
    <div class="appointments-banner-content">
        <h1>Lịch hẹn <span>xem xe</span></h1>
        <p>Quản lý và theo dõi tất cả lịch hẹn của bạn</p>
    </div>
</div>

<div class="appointments-page">
    <div class="appointments-container">
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
        <div class="empty-state fade-in-section">
            <i class="fas fa-calendar-times"></i>
            <h2>Chưa có lịch hẹn nào</h2>
            <p>Bạn chưa đặt lịch xem xe nào. Hãy khám phá bộ sưu tập xe của chúng tôi!</p>
            <a href="/cars" class="btn-explore">
                <i class="fas fa-car"></i>
                Khám phá xe
            </a>
        </div>
        <?php else: ?>
        <div class="appointments-list">
            <?php foreach ($appointments as $appointment): ?>
            <div class="appointment-card fade-in-section">
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
                    <div class="appointment-car">
                        <div class="car-image">
                            <img src="<?= $appointment['car_image'] ?? 'https://via.placeholder.com/180x140' ?>" 
                                 alt="<?= htmlspecialchars($appointment['car_name'] ?? 'Xe') ?>"
                                 loading="lazy" class="lazy-image">
                        </div>
                        <div class="car-details">
                            <span class="car-brand"><?= htmlspecialchars($appointment['brand_name'] ?? '') ?></span>
                            <h4><?= htmlspecialchars($appointment['car_name'] ?? 'Xe') ?></h4>
                            <div class="car-price"><?= number_format($appointment['car_price'] ?? 0, 0, ',', '.') ?>₫</div>
                        </div>
                    </div>
                    <div class="appointment-datetime">
                        <div class="datetime-item">
                            <i class="fas fa-calendar-alt"></i>
                            <div>
                                <strong>Ngày hẹn</strong>
                                <span><?= date('d/m/Y', strtotime($appointment['appointment_date'])) ?></span>
                            </div>
                        </div>
                        <div class="datetime-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <strong>Giờ hẹn</strong>
                                <span><?= date('H:i', strtotime($appointment['appointment_time'])) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="appointment-footer">
                    <div class="customer-info">
                        <span><i class="fas fa-user"></i> <?= htmlspecialchars($appointment['full_name']) ?></span>
                        <span><i class="fas fa-phone"></i> <?= htmlspecialchars($appointment['phone']) ?></span>
                        <span><i class="fas fa-envelope"></i> <?= htmlspecialchars($appointment['email']) ?></span>
                    </div>
                    <div class="appointment-actions">
                        <a href="/car/<?= $appointment['car_id'] ?>" class="btn btn-primary">
                            <i class="fas fa-eye"></i> Xem xe
                        </a>
                        <?php if (($appointment['status'] ?? 'pending') === 'pending'): ?>
                        <form method="POST" action="/appointment/cancel/<?= $appointment['id'] ?>" style="display: inline;" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');">
                            <button type="submit" class="btn btn-danger">
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
