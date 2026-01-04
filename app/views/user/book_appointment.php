<?php
$currentPage = 'appointment';
$pageTitle = 'Đặt Lịch Xem Xe - AutoCar';

// Check login
if (!isset($_SESSION['user_id'])) {
    header('Location: /login?redirect=' . $_SERVER['REQUEST_URI']);
    exit;
}

// Get car ID from URL
$carId = $matches[1] ?? null;
if (!$carId) {
    header('Location: /cars');
    exit;
}

// Load Models
require_once __DIR__ . '/../../models/CarModel.php';
$carModel = new CarModel();

// Get car details
$car = $carModel->getById($carId);
if (!$car) {
    $_SESSION['error'] = 'Xe không tồn tại';
    header('Location: /cars');
    exit;
}

// Get car images
$carImages = $carModel->getImages($carId);
$mainImage = !empty($carImages) ? $carImages[0]['image_url'] : 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800';

include __DIR__ . '/../layouts/header.php';
?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/cart.css">

<style>
.appointment-page {
    padding: 150px 0 80px;
    min-height: 100vh;
    background: linear-gradient(135deg, #f9f7f3 0%, #ffffff 100%);
}

.appointment-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.appointment-content {
    display: grid;
    grid-template-columns: 500px 1fr;
    gap: 30px;
    margin-top: 30px;
}

.car-preview {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.1);
    height: fit-content;
    position: sticky;
    top: 120px;
}

.car-preview-image {
    width: 100%;
    aspect-ratio: 16/10;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
}

.car-preview-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.car-preview-info h3 {
    font-size: 24px;
    color: #333;
    margin-bottom: 10px;
}

.car-preview-brand {
    color: #D4AF37;
    font-size: 14px;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.car-preview-price {
    font-size: 28px;
    font-weight: 700;
    color: #D4AF37;
    margin-bottom: 15px;
}

.car-preview-specs {
    display: grid;
    gap: 10px;
    padding-top: 15px;
    border-top: 1px solid #eee;
}

.car-preview-spec {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
}

.car-preview-spec span:first-child {
    color: #666;
}

.car-preview-spec span:last-child {
    color: #333;
    font-weight: 500;
}

.appointment-form {
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 5px 30px rgba(0,0,0,0.1);
}

.form-section {
    margin-bottom: 30px;
}

.form-section h3 {
    font-size: 18px;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #D4AF37;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    display: block;
    font-size: 14px;
    color: #555;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-group label span.required {
    color: #dc3545;
}

.form-group input,
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 12px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: #D4AF37;
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.time-slots {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    margin-top: 10px;
}

.time-slot {
    padding: 12px;
    text-align: center;
    border: 2px solid #ddd;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
}

.time-slot input[type="radio"] {
    display: none;
}

.time-slot:hover {
    border-color: #D4AF37;
    background: rgba(212, 175, 55, 0.1);
}

.time-slot input[type="radio"]:checked + label {
    border-color: #D4AF37;
    background: #D4AF37;
    color: #fff;
}

.time-slot label {
    cursor: pointer;
    margin: 0;
    padding: 0;
}

.btn-submit-appointment {
    width: 100%;
    padding: 16px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.btn-submit-appointment:hover {
    background: linear-gradient(135deg, #F4CF57 0%, #D4AF37 100%);
    box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4);
    transform: translateY(-2px);
}

.info-note {
    padding: 15px;
    background: #f8f9fa;
    border-left: 4px solid #D4AF37;
    border-radius: 4px;
    font-size: 14px;
    color: #666;
    margin-bottom: 20px;
}

@media (max-width: 968px) {
    .appointment-content {
        grid-template-columns: 1fr;
    }
    
    .car-preview {
        position: relative;
        top: 0;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .time-slots {
        grid-template-columns: repeat(2, 1fr);
    }
}
</style>

<main class="appointment-page">
    <div class="appointment-container">
        <h1 class="page-title">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
            Đặt Lịch Xem Xe
        </h1>

        <div class="appointment-content">
            <!-- Car Preview -->
            <div class="car-preview">
                <div class="car-preview-image">
                    <img src="<?= htmlspecialchars($mainImage) ?>" alt="<?= htmlspecialchars($car['name']) ?>">
                </div>
                <div class="car-preview-info">
                    <div class="car-preview-brand"><?= htmlspecialchars($car['brand_name']) ?></div>
                    <h3><?= htmlspecialchars($car['name']) ?></h3>
                    <div class="car-preview-price"><?= number_format($car['price'], 0, ',', '.') ?> ₫</div>
                    <div class="car-preview-specs">
                        <div class="car-preview-spec">
                            <span>Năm sản xuất:</span>
                            <span><?= $car['year'] ?></span>
                        </div>
                        <div class="car-preview-spec">
                            <span>Hộp số:</span>
                            <span><?= $car['transmission'] == 'automatic' ? 'Tự động' : 'Số sàn' ?></span>
                        </div>
                        <div class="car-preview-spec">
                            <span>Số km đã đi:</span>
                            <span><?= number_format($car['mileage']) ?> km</span>
                        </div>
                        <div class="car-preview-spec">
                            <span>Loại xe:</span>
                            <span><?= htmlspecialchars($car['category_name']) ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Appointment Form -->
            <div class="appointment-form">
                <div class="info-note">
                    <strong>Lưu ý:</strong> Vui lòng đặt lịch trước ít nhất 24h. Chúng tôi sẽ xác nhận lịch hẹn với bạn qua điện thoại hoặc email.
                </div>

                <form method="POST" action="/appointment/create" id="appointmentForm">
                    <input type="hidden" name="car_id" value="<?= $carId ?>">

                    <!-- Thông tin cá nhân -->
                    <div class="form-section">
                        <h3>Thông Tin Liên Hệ</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Họ và tên <span class="required">*</span></label>
                                <input type="text" name="full_name" required value="<?= htmlspecialchars($_SESSION['full_name'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label>Số điện thoại <span class="required">*</span></label>
                                <input type="tel" name="phone" required placeholder="0xxx xxx xxx">
                            </div>
                            <div class="form-group full-width">
                                <label>Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Thời gian hẹn -->
                    <div class="form-section">
                        <h3>Chọn Thời Gian</h3>
                        <div class="form-group">
                            <label>Ngày xem xe <span class="required">*</span></label>
                            <input type="date" name="appointment_date" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>" onchange="updateTimeSlots()">
                        </div>
                        <div class="form-group">
                            <label>Khung giờ <span class="required">*</span></label>
                            <select name="appointment_time" required>
                                <option value="">-- Chọn khung giờ --</option>
                                <option value="09:00:00">09:00 - 10:00</option>
                                <option value="10:00:00">10:00 - 11:00</option>
                                <option value="11:00:00">11:00 - 12:00</option>
                                <option value="13:00:00">13:00 - 14:00</option>
                                <option value="14:00:00">14:00 - 15:00</option>
                                <option value="15:00:00">15:00 - 16:00</option>
                                <option value="16:00:00">16:00 - 17:00</option>
                                <option value="17:00:00">17:00 - 18:00</option>
                            </select>
                        </div>
                    </div>

                    <!-- Ghi chú -->
                    <div class="form-section">
                        <h3>Ghi Chú</h3>
                        <div class="form-group">
                            <textarea name="notes" rows="4" placeholder="Ghi chú thêm về lịch hẹn (không bắt buộc)"></textarea>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit-appointment">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: inline-block; vertical-align: middle; margin-right: 8px;">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Xác Nhận Đặt Lịch
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
function updateTimeSlots() {
    const dateInput = document.querySelector('input[name="appointment_date"]');
    const timeSelect = document.querySelector('select[name="appointment_time"]');
    
    if (dateInput.value) {
        timeSelect.disabled = false;
    }
}

// Validation
document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    const phone = document.querySelector('input[name="phone"]').value;
    const phoneRegex = /^[0-9]{10,11}$/;
    
    if (!phoneRegex.test(phone.replace(/\s/g, ''))) {
        e.preventDefault();
        alert('Số điện thoại không hợp lệ. Vui lòng nhập 10-11 số');
        return false;
    }
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
