<?php
$currentPage = 'about';
$pageTitle = 'Giới Thiệu';
include __DIR__ . '/../layouts/header.php';
?>

<style>
    /* About Hero */
    .about-hero {
        height: 450px;
        background: linear-gradient(135deg, rgba(26, 26, 26, 0.85) 0%, rgba(0, 0, 0, 0.8) 100%),
            url('https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=1920') center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .about-hero-content {
        max-width: 800px;
        padding: 0 20px;
    }

    .about-hero h1 {
        font-family: 'Playfair Display', serif;
        font-size: 52px;
        color: #fff;
        margin-bottom: 20px;
    }

    .about-hero h1 span {
        color: #D4AF37;
    }

    .about-hero p {
        font-size: 18px;
        color: rgba(255, 255, 255, 0.85);
        line-height: 1.7;
    }

    /* Main Content */
    .about-content {
        background: #f9f7f3;
        padding: 80px 0;
    }

    .about-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 30px;
    }

    /* Intro Section */
    .intro-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
        margin-bottom: 80px;
    }

    .intro-text h2 {
        font-family: 'Playfair Display', serif;
        font-size: 38px;
        color: #1a1a1a;
        margin-bottom: 25px;
    }

    .intro-text h2 span {
        color: #D4AF37;
    }

    .intro-text p {
        font-size: 16px;
        color: #555;
        line-height: 1.8;
        margin-bottom: 20px;
    }

    .intro-image {
        position: relative;
    }

    .intro-image img {
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    .intro-image::before {
        content: '';
        position: absolute;
        top: -15px;
        right: -15px;
        width: 100%;
        height: 100%;
        border: 3px solid #D4AF37;
        border-radius: 15px;
        z-index: -1;
    }

    /* Stats */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 25px;
        margin-bottom: 80px;
    }

    .stat-card {
        background: #fff;
        border-radius: 15px;
        padding: 35px 25px;
        text-align: center;
        border: 1px solid rgba(212, 175, 55, 0.15);
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        border-color: #D4AF37;
    }

    .stat-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }

    .stat-icon i {
        font-size: 28px;
        color: #fff;
    }

    .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 5px;
    }

    .stat-label {
        font-size: 14px;
        color: #666;
    }

    /* Services Section */
    .services-section {
        margin-bottom: 80px;
    }

    .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .section-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 38px;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    .section-header h2 span {
        color: #D4AF37;
    }

    .section-header p {
        font-size: 16px;
        color: #666;
        max-width: 600px;
        margin: 0 auto;
    }

    .services-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
    }

    .service-card {
        background: #fff;
        border-radius: 15px;
        padding: 40px 30px;
        text-align: center;
        border: 1px solid rgba(212, 175, 55, 0.1);
        transition: all 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
    }

    .service-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, rgba(212, 175, 55, 0.15) 0%, rgba(212, 175, 55, 0.05) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        transition: all 0.3s ease;
    }

    .service-card:hover .service-icon {
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    }

    .service-icon i {
        font-size: 32px;
        color: #D4AF37;
        transition: all 0.3s ease;
    }

    .service-card:hover .service-icon i {
        color: #fff;
    }

    .service-card h3 {
        font-family: 'Playfair Display', serif;
        font-size: 22px;
        color: #1a1a1a;
        margin-bottom: 15px;
    }

    .service-card p {
        font-size: 14px;
        color: #666;
        line-height: 1.7;
    }

    /* Why Choose Section */
    .why-section {
        background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
        border-radius: 20px;
        padding: 60px;
        margin-bottom: 80px;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: center;
    }

    .why-content h2 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        color: #fff;
        margin-bottom: 20px;
    }

    .why-content h2 span {
        color: #D4AF37;
    }

    .why-content>p {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.7);
        line-height: 1.7;
        margin-bottom: 30px;
    }

    .why-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .why-item {
        display: flex;
        align-items: flex-start;
        gap: 15px;
    }

    .why-item i {
        width: 40px;
        height: 40px;
        background: rgba(212, 175, 55, 0.2);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #D4AF37;
        font-size: 18px;
        flex-shrink: 0;
    }

    .why-item-content h4 {
        font-size: 16px;
        color: #fff;
        margin-bottom: 5px;
    }

    .why-item-content p {
        font-size: 14px;
        color: rgba(255, 255, 255, 0.6);
    }

    .why-image img {
        width: 100%;
        border-radius: 15px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    }

    /* Brands Section */
    .brands-section {
        text-align: center;
        margin-bottom: 80px;
    }

    .brands-grid {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 40px;
        margin-top: 40px;
    }

    .brand-item {
        width: 150px;
        height: 100px;
        background: #fff;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #eee;
        transition: all 0.3s ease;
        padding: 20px;
    }

    .brand-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-color: #D4AF37;
    }

    .brand-item img {
        max-width: 100%;
        max-height: 60px;
        width: auto;
        height: auto;
        object-fit: contain;
        filter: grayscale(0%);
        opacity: 1;
        transition: all 0.3s ease;
    }

    .brand-item:hover img {
        filter: grayscale(0%);
        opacity: 1;
        transform: scale(1.1);
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
        border-radius: 20px;
        padding: 60px;
        text-align: center;
    }

    .cta-section h2 {
        font-family: 'Playfair Display', serif;
        font-size: 36px;
        color: #fff;
        margin-bottom: 15px;
    }

    .cta-section p {
        font-size: 16px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 30px;
    }

    .cta-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .btn-cta {
        padding: 15px 35px;
        border-radius: 50px;
        font-size: 15px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .btn-cta.primary {
        background: #1a1a1a;
        color: #fff;
    }

    .btn-cta.primary:hover {
        background: #000;
        transform: translateY(-3px);
    }

    .btn-cta.secondary {
        background: #fff;
        color: #1a1a1a;
    }

    .btn-cta.secondary:hover {
        background: #f5f5f5;
        transform: translateY(-3px);
    }

    /* Responsive */
    @media (max-width: 992px) {

        .intro-section,
        .why-section {
            grid-template-columns: 1fr;
        }

        .services-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .about-hero h1 {
            font-size: 36px;
        }

        .services-grid {
            grid-template-columns: 1fr;
        }

        .why-section {
            padding: 40px 25px;
        }

        .cta-buttons {
            flex-direction: column;
        }
    }
</style>

<!-- Hero Section -->
<section class="about-hero">
    <div class="about-hero-content">
        <h1>Về <span>AutoCar</span> Luxury</h1>
        <p>Hơn 10 năm kinh nghiệm trong lĩnh vực phân phối xe hơi hạng sang, chúng tôi tự hào mang đến cho khách hàng những trải nghiệm tuyệt vời nhất</p>
    </div>
</section>

<!-- Main Content -->
<section class="about-content">
    <div class="about-container">
        <!-- Intro Section -->
        <div class="intro-section">
            <div class="intro-text">
                <h2>Đối Tác <span>Tin Cậy</span> Của Bạn</h2>
                <p>AutoCar Luxury được thành lập với sứ mệnh mang đến cho khách hàng Việt Nam những chiếc xe hơi đẳng cấp thế giới. Chúng tôi là đại lý ủy quyền chính thức của nhiều thương hiệu xe sang hàng đầu.</p>
                <p>Với đội ngũ tư vấn viên chuyên nghiệp, am hiểu sâu về từng dòng xe, chúng tôi cam kết hỗ trợ bạn tìm được chiếc xe phù hợp nhất với phong cách sống và nhu cầu của bạn.</p>
                <p>Mỗi chiếc xe tại AutoCar đều được kiểm tra kỹ lưỡng, đảm bảo chất lượng và nguồn gốc rõ ràng, mang đến sự an tâm tuyệt đối cho khách hàng.</p>
            </div>
            <div class="intro-image">
                <img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=600" alt="AutoCar Showroom">
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-number">10+</div>
                <div class="stat-label">Năm kinh nghiệm</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-car"></i>
                </div>
                <div class="stat-number">5,000+</div>
                <div class="stat-label">Xe đã bán</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">8,000+</div>
                <div class="stat-label">Khách hàng hài lòng</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-number">98%</div>
                <div class="stat-label">Đánh giá 5 sao</div>
            </div>
        </div>

        <!-- Services Section -->
        <div class="services-section">
            <div class="section-header">
                <h2>Dịch Vụ <span>Chuyên Nghiệp</span></h2>
                <p>Chúng tôi cung cấp đầy đủ các dịch vụ từ tư vấn, mua bán đến bảo dưỡng xe</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h3>Tư Vấn Mua Xe</h3>
                    <p>Đội ngũ chuyên gia tư vấn giàu kinh nghiệm, hỗ trợ bạn lựa chọn chiếc xe phù hợp nhất với nhu cầu và ngân sách</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-exchange-alt"></i>
                    </div>
                    <h3>Thu Đổi Xe Cũ</h3>
                    <p>Dịch vụ thu đổi xe cũ lấy xe mới với giá cao nhất thị trường, thủ tục nhanh gọn</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3>Hỗ Trợ Tài Chính</h3>
                    <p>Liên kết với các ngân hàng lớn, hỗ trợ vay mua xe với lãi suất ưu đãi, duyệt nhanh trong 24h</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Bảo Dưỡng & Sửa Chữa</h3>
                    <p>Xưởng dịch vụ hiện đại, kỹ thuật viên được đào tạo bài bản, phụ tùng chính hãng 100%</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Bảo Hiểm Xe</h3>
                    <p>Đối tác của các công ty bảo hiểm uy tín, cung cấp gói bảo hiểm toàn diện cho xe của bạn</p>
                </div>
                <div class="service-card">
                    <div class="service-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Giao Xe Tận Nơi</h3>
                    <p>Dịch vụ giao xe tận nhà trên toàn quốc, đảm bảo an toàn và đúng thời gian cam kết</p>
                </div>
            </div>
        </div>

        <!-- Why Choose Section -->
        <div class="why-section">
            <div class="why-content">
                <h2>Tại Sao Chọn <span>AutoCar</span>?</h2>
                <p>Chúng tôi không chỉ bán xe, chúng tôi mang đến trải nghiệm đẳng cấp và sự hài lòng tuyệt đối cho khách hàng</p>
                <div class="why-list">
                    <div class="why-item">
                        <i class="fas fa-check"></i>
                        <div class="why-item-content">
                            <h4>Xe Chính Hãng 100%</h4>
                            <p>Tất cả xe đều có nguồn gốc rõ ràng, giấy tờ đầy đủ</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <i class="fas fa-check"></i>
                        <div class="why-item-content">
                            <h4>Giá Cả Cạnh Tranh</h4>
                            <p>Cam kết giá tốt nhất thị trường, nhiều chương trình ưu đãi</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <i class="fas fa-check"></i>
                        <div class="why-item-content">
                            <h4>Bảo Hành Toàn Diện</h4>
                            <p>Chế độ bảo hành dài hạn, hậu mãi chu đáo tận tâm</p>
                        </div>
                    </div>
                    <div class="why-item">
                        <i class="fas fa-check"></i>
                        <div class="why-item-content">
                            <h4>Hỗ Trợ 24/7</h4>
                            <p>Đường dây nóng hỗ trợ khách hàng mọi lúc mọi nơi</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="why-image">
                <img src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=600" alt="Luxury Service">
            </div>
        </div>

        <!-- Brands Section -->
        <div class="brands-section">
            <div class="section-header">
                <h2>Thương Hiệu <span>Đối Tác</span></h2>
                <p>Đại lý ủy quyền chính thức của các thương hiệu xe sang hàng đầu thế giới</p>
            </div>
            <div class="brands-grid">
                <div class="brand-item">
                    <img src="https://www.carlogos.org/car-logos/mercedes-benz-logo.png" alt="Mercedes">
                </div>
                <div class="brand-item">
                    <img src="https://www.carlogos.org/car-logos/bmw-logo.png" alt="BMW">
                </div>
                <div class="brand-item">
                    <img src="https://www.carlogos.org/car-logos/audi-logo.png" alt="Audi">
                </div>
                <div class="brand-item">
                    <img src="https://www.carlogos.org/car-logos/porsche-logo.png" alt="Porsche">
                </div>
                <div class="brand-item">
                    <img src="https://www.carlogos.org/car-logos/lexus-logo.png" alt="Lexus">
                </div>
                <div class="brand-item">
                    <img src="https://www.carlogos.org/car-logos/land-rover-logo.png" alt="Land Rover">
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="cta-section">
            <h2>Sẵn Sàng Trải Nghiệm?</h2>
            <p>Hãy đến showroom của chúng tôi hoặc liên hệ ngay để được tư vấn miễn phí</p>
            <div class="cta-buttons">
                <a href="/cars" class="btn-cta primary">
                    <i class="fas fa-car"></i>
                    Xem Xe Ngay
                </a>
                <a href="/contact" class="btn-cta secondary">
                    <i class="fas fa-phone-alt"></i>
                    Liên Hệ Tư Vấn
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/../layouts/footer.php'; ?>