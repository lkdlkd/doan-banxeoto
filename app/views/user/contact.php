<?php 
$currentPage = 'contact';
$pageTitle = 'Liên Hệ';
include __DIR__ . '/../layouts/header.php'; 
?>

<style>
/* Contact Hero */
.contact-hero {
    height: 400px;
    background: linear-gradient(135deg, rgba(26,26,26,0.85) 0%, rgba(0,0,0,0.8) 100%),
                url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920') center/cover no-repeat;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.contact-hero-content h1 {
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    color: #fff;
    margin-bottom: 15px;
}

.contact-hero-content h1 span {
    color: #D4AF37;
}

.contact-hero-content p {
    font-size: 18px;
    color: rgba(255,255,255,0.8);
}

/* Contact Content */
.contact-content {
    background: #f9f7f3;
    padding: 80px 0;
}

.contact-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 30px;
}

/* Contact Info Cards */
.contact-info-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
    margin-bottom: 60px;
}

.contact-info-card {
    background: #fff;
    border-radius: 15px;
    padding: 35px 25px;
    text-align: center;
    border: 1px solid rgba(212,175,55,0.1);
    transition: all 0.3s ease;
}

.contact-info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.1);
    border-color: #D4AF37;
}

.contact-info-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
}

.contact-info-icon i {
    font-size: 28px;
    color: #fff;
}

.contact-info-card h3 {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.contact-info-card p {
    font-size: 14px;
    color: #666;
    line-height: 1.6;
}

.contact-info-card a {
    color: #D4AF37;
    text-decoration: none;
    font-weight: 500;
}

.contact-info-card a:hover {
    color: #B8860B;
}

/* Main Contact Section */
.contact-main {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 50px;
    margin-bottom: 60px;
}

/* Contact Form */
.contact-form-wrapper {
    background: #fff;
    border-radius: 20px;
    padding: 45px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
}

.contact-form-wrapper h2 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.contact-form-wrapper h2 span {
    color: #D4AF37;
}

.contact-form-wrapper > p {
    font-size: 14px;
    color: #666;
    margin-bottom: 30px;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    position: relative;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 500;
    color: #333;
    margin-bottom: 8px;
}

.form-group .input-wrapper {
    position: relative;
}

.form-group i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 16px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 14px 14px 14px 45px;
    border: 2px solid #e5e5e5;
    border-radius: 10px;
    font-size: 15px;
    font-family: 'Montserrat', sans-serif;
    color: #333;
    background: #fff;
    transition: all 0.3s ease;
}

.form-group textarea {
    min-height: 120px;
    resize: vertical;
    padding-top: 14px;
}

.form-group textarea + i {
    top: 20px;
    transform: none;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #D4AF37;
    box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #aaa;
}

.btn-submit {
    padding: 16px 40px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    border: none;
    border-radius: 10px;
    color: white;
    font-size: 15px;
    font-weight: 600;
    font-family: 'Montserrat', sans-serif;
    cursor: pointer;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-submit:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
}

/* Map Section */
.contact-map-wrapper {
    background: #fff;
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
}

.contact-map-wrapper h2 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #1a1a1a;
    margin-bottom: 20px;
}

.contact-map-wrapper h2 span {
    color: #D4AF37;
}

.map-container {
    border-radius: 15px;
    overflow: hidden;
    height: 350px;
}

.map-container iframe {
    width: 100%;
    height: 100%;
    border: none;
}

/* Showrooms Section */
.showrooms-section {
    margin-bottom: 60px;
}

.section-header {
    text-align: center;
    margin-bottom: 40px;
}

.section-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 36px;
    color: #1a1a1a;
    margin-bottom: 10px;
}

.section-header h2 span {
    color: #D4AF37;
}

.section-header p {
    font-size: 16px;
    color: #666;
}

.showrooms-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
}

.showroom-card {
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.showroom-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0,0,0,0.12);
}

.showroom-image {
    height: 200px;
    overflow: hidden;
}

.showroom-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.showroom-card:hover .showroom-image img {
    transform: scale(1.1);
}

.showroom-content {
    padding: 25px;
}

.showroom-content h3 {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    color: #1a1a1a;
    margin-bottom: 15px;
}

.showroom-info {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.showroom-info-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    font-size: 14px;
    color: #666;
}

.showroom-info-item i {
    color: #D4AF37;
    font-size: 16px;
    width: 20px;
    text-align: center;
    flex-shrink: 0;
    margin-top: 2px;
}

.showroom-info-item a {
    color: #D4AF37;
    text-decoration: none;
}

.showroom-info-item a:hover {
    color: #B8860B;
}

/* Social Contact */
.social-section {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    border-radius: 20px;
    padding: 50px;
    text-align: center;
}

.social-section h2 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    color: #fff;
    margin-bottom: 10px;
}

.social-section h2 span {
    color: #D4AF37;
}

.social-section > p {
    font-size: 16px;
    color: rgba(255,255,255,0.7);
    margin-bottom: 30px;
}

.social-icons {
    display: flex;
    justify-content: center;
    gap: 20px;
}

.social-icon {
    width: 60px;
    height: 60px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 24px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.social-icon:hover {
    background: #D4AF37;
    transform: translateY(-5px);
}

.social-icon.facebook:hover { background: #1877f2; }
.social-icon.instagram:hover { background: linear-gradient(45deg, #f09433, #e6683c, #dc2743, #cc2366, #bc1888); }
.social-icon.youtube:hover { background: #ff0000; }
.social-icon.tiktok:hover { background: #000; }
.social-icon.zalo:hover { background: #0068ff; }

/* Responsive */
@media (max-width: 992px) {
    .contact-info-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .contact-main {
        grid-template-columns: 1fr;
    }
    
    .showrooms-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .contact-hero h1 {
        font-size: 36px;
    }
    
    .contact-info-grid {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .showrooms-grid {
        grid-template-columns: 1fr;
    }
    
    .social-icons {
        flex-wrap: wrap;
    }
}
</style>

<!-- Hero Section -->
<section class="contact-hero">
    <div class="contact-hero-content">
        <h1>Liên Hệ <span>Với Chúng Tôi</span></h1>
        <p>Chúng tôi luôn sẵn sàng lắng nghe và hỗ trợ bạn</p>
    </div>
</section>

<!-- Main Content -->
<section class="contact-content">
    <div class="contact-container">
        <!-- Contact Info Cards -->
        <div class="contact-info-grid">
            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Địa Chỉ</h3>
                <p>123 Nguyễn Văn Linh<br>Quận 7, TP. Hồ Chí Minh</p>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <h3>Hotline</h3>
                <p><a href="tel:1900636689">1900 636 689</a></p>
                <p style="font-size:12px;color:#999;margin-top:5px;">24/7 hỗ trợ khách hàng</p>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <p><a href="mailto:info@autocar.vn">info@autocar.vn</a></p>
                <p style="font-size:12px;color:#999;margin-top:5px;">Phản hồi trong 24h</p>
            </div>
            <div class="contact-info-card">
                <div class="contact-info-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <h3>Giờ Làm Việc</h3>
                <p>T2 - CN: 8:00 - 20:00</p>
                <p style="font-size:12px;color:#999;margin-top:5px;">Kể cả ngày lễ</p>
            </div>
        </div>

        <!-- Main Contact Section -->
        <div class="contact-main">
            <!-- Contact Form -->
            <div class="contact-form-wrapper">
                <h2>Gửi <span>Tin Nhắn</span></h2>
                <p>Điền thông tin bên dưới, chúng tôi sẽ liên hệ lại trong thời gian sớm nhất</p>
                
                <?php if (isset($_SESSION['contact_success'])): ?>
                    <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                        <i class="fas fa-check-circle"></i> <?= $_SESSION['contact_success'] ?>
                    </div>
                    <?php unset($_SESSION['contact_success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['contact_error'])): ?>
                    <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
                        <i class="fas fa-exclamation-circle"></i> <?= $_SESSION['contact_error'] ?>
                    </div>
                    <?php unset($_SESSION['contact_error']); ?>
                <?php endif; ?>
                
                <form class="contact-form" method="POST" action="/contact">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="name">Họ và tên *</label>
                            <div class="input-wrapper">
                                <input type="text" id="name" name="name" placeholder="Nguyễn Văn A" value="<?= htmlspecialchars($_SESSION['contact_old']['name'] ?? '') ?>" required>
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại *</label>
                            <div class="input-wrapper">
                                <input type="tel" id="phone" name="phone" placeholder="0901 234 567" value="<?= htmlspecialchars($_SESSION['contact_old']['phone'] ?? '') ?>" required>
                                <i class="fas fa-phone"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" placeholder="email@example.com" value="<?= htmlspecialchars($_SESSION['contact_old']['email'] ?? '') ?>">
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Chủ đề</label>
                        <div class="input-wrapper">
                            <select id="subject" name="subject">
                                <option value="">-- Chọn chủ đề --</option>
                                <option value="tuvan">Tư vấn mua xe</option>
                                <option value="baogia">Báo giá</option>
                                <option value="laixethu">Đặt lịch lái thử</option>
                                <option value="baoduong">Bảo dưỡng/Sửa chữa</option>
                                <option value="khac">Khác</option>
                            </select>
                            <i class="fas fa-list"></i>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Nội dung tin nhắn *</label>
                        <div class="input-wrapper">
                            <textarea id="message" name="message" placeholder="Nhập nội dung tin nhắn của bạn..." required><?= htmlspecialchars($_SESSION['contact_old']['message'] ?? '') ?></textarea>
                            <i class="fas fa-comment-alt"></i>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Gửi Tin Nhắn
                    </button>
                </form>
            </div>

            <!-- Map -->
            <div class="contact-map-wrapper">
                <h2>Vị Trí <span>Showroom</span></h2>
                <div class="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3920.0410785887916!2d106.69976537573949!3d10.731738459930556!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f94c75f8b3d%3A0x5b7a0d66c1e5a7f6!2zMTIzIE5ndXnhu4VuIFbEg24gTGluaCwgVMOibiBQaMO6LCBRdeG6rW4gNywgVGjDoG5oIHBo4buRIEjhu5MgQ2jDrSBNaW5oLCBWaeG7h3QgTmFt!5e0!3m2!1svi!2s!4v1704189600000!5m2!1svi!2s" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>

        <!-- Showrooms Section -->
        <div class="showrooms-section">
            <div class="section-header">
                <h2>Hệ Thống <span>Showroom</span></h2>
                <p>3 showroom lớn tại các vị trí trung tâm, tiện lợi cho khách hàng</p>
            </div>
            <div class="showrooms-grid">
                <div class="showroom-card">
                    <div class="showroom-image">
                        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=500" alt="Showroom Quận 7">
                    </div>
                    <div class="showroom-content">
                        <h3>Showroom Quận 7</h3>
                        <div class="showroom-info">
                            <div class="showroom-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>123 Nguyễn Văn Linh, Quận 7</span>
                            </div>
                            <div class="showroom-info-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:02812345678">028 1234 5678</a>
                            </div>
                            <div class="showroom-info-item">
                                <i class="fas fa-clock"></i>
                                <span>8:00 - 20:00 (Cả tuần)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="showroom-card">
                    <div class="showroom-image">
                        <img src="https://images.unsplash.com/photo-1554435493-93422e8220c8?w=500" alt="Showroom Quận 1">
                    </div>
                    <div class="showroom-content">
                        <h3>Showroom Quận 1</h3>
                        <div class="showroom-info">
                            <div class="showroom-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>456 Lê Lợi, Quận 1</span>
                            </div>
                            <div class="showroom-info-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:02898765432">028 9876 5432</a>
                            </div>
                            <div class="showroom-info-item">
                                <i class="fas fa-clock"></i>
                                <span>8:00 - 20:00 (Cả tuần)</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="showroom-card">
                    <div class="showroom-image">
                        <img src="https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=500" alt="Showroom Thủ Đức">
                    </div>
                    <div class="showroom-content">
                        <h3>Showroom Thủ Đức</h3>
                        <div class="showroom-info">
                            <div class="showroom-info-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>789 Võ Văn Ngân, Thủ Đức</span>
                            </div>
                            <div class="showroom-info-item">
                                <i class="fas fa-phone"></i>
                                <a href="tel:02811223344">028 1122 3344</a>
                            </div>
                            <div class="showroom-info-item">
                                <i class="fas fa-clock"></i>
                                <span>8:00 - 20:00 (Cả tuần)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Section -->
        <div class="social-section">
            <h2>Kết Nối <span>Với Chúng Tôi</span></h2>
            <p>Theo dõi AutoCar trên các nền tảng mạng xã hội để cập nhật thông tin mới nhất</p>
            <div class="social-icons">
                <a href="#" class="social-icon facebook" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="social-icon instagram" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="social-icon youtube" title="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="#" class="social-icon tiktok" title="TikTok">
                    <i class="fab fa-tiktok"></i>
                </a>
                <a href="#" class="social-icon zalo" title="Zalo">
                    <i class="fas fa-comment-dots"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
