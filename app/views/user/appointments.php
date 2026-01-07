<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../config/config.php';
}

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

<!-- Custom CSS for Appointments Page -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/appointments.css">

<!-- Banner -->
<div class="appointments-banner">
    <div class="appointments-banner-content">
        <h1>Lịch hẹn <span>xem xe</span></h1>
        <p>Quản lý và theo dõi tất cả lịch hẹn của bạn</p>
    </div>
</div>

<div class="appointments-page">
    <div class="container">
        <!-- Alert Messages using Bootstrap -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fas fa-check-circle"></i>
                <?= $_SESSION['success'];
                unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <?= $_SESSION['error'];
                unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($appointments)): ?>
            <div class="empty-state">
                <i class="fas fa-calendar-times"></i>
                <h2>Chưa có lịch hẹn nào</h2>
                <p>Bạn chưa đặt lịch xem xe nào. Hãy khám phá bộ sưu tập xe của chúng tôi!</p>
                <a href="/cars" class="btn-explore">
                    <i class="fas fa-car"></i>
                    Khám phá xe
                </a>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-4">
                <?php foreach ($appointments as $appointment): ?>
                    <div class="appointment-card">
                        <div class="appointment-header">
                            <div class="appointment-info">
                                <h3>Lịch hẹn #<?= str_pad($appointment['id'], 6, '0', STR_PAD_LEFT) ?></h3>
                                <span><i class="fas fa-calendar-plus"></i> Đặt lúc: <?= date('d/m/Y H:i', strtotime($appointment['created_at'])) ?></span>
                            </div>
                            <span class="appointment-status <?= $appointment['status'] ?? 'pending' ?>">
                                <?php
                                switch ($appointment['status'] ?? 'pending') {
                                    case 'pending':
                                        echo 'Chờ xác nhận';
                                        break;
                                    case 'confirmed':
                                        echo 'Đã xác nhận';
                                        break;
                                    case 'completed':
                                        echo 'Hoàn thành';
                                        break;
                                    case 'cancelled':
                                        echo 'Đã hủy';
                                        break;
                                    default:
                                        echo 'Chờ xác nhận';
                                }
                                ?>
                            </span>
                        </div>
                        <div class="appointment-body">
                            <div class="appointment-car">
                                <div class="car-image">
                                    <img src="<?= $appointment['car_image'] ?? 'https://via.placeholder.com/180x140' ?>"
                                        alt="<?= htmlspecialchars($appointment['car_name'] ?? 'Xe') ?>"
                                        loading="lazy">
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
                            <div class="d-flex gap-2">
                                <a href="/car/<?= $appointment['car_id'] ?>" class="btn btn-gold">
                                    <i class="fas fa-eye"></i> Xem xe
                                </a>
                                <?php if (($appointment['status'] ?? 'pending') === 'pending'): ?>
                                    <form method="POST" action="/appointment/cancel/<?= $appointment['id'] ?>" class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn hủy lịch hẹn này?');">
                                        <button type="submit" class="btn btn-outline-danger">
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