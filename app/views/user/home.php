<?php
$currentPage = 'home';
$pageTitle = 'Trang Chủ';

// Load Brand Model
require_once __DIR__ . '/../../models/BrandModel.php';
$brandModel = new BrandModel();

// Lấy tất cả brands có xe
$allBrands = $brandModel->getBrandsWithCarCount();

// Lọc brands có xe
$brandsWithCars = array_filter($allBrands, function ($brand) {
    return $brand['car_count'] > 0;
});

include __DIR__ . '/../layouts/header.php';
?>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-bg"></div>
    <div class="hero-content">
        <h1>
            TÌM KIẾM XE Ô TÔ<br>
            <span class="highlight">HOÀN HẢO</span>
        </h1>
        <p>Khám phá bộ sưu tập xe ô tô chất lượng cao với giá tốt nhất thị trường</p>
        <a href="/cars" class="cta-button">
            Xem Xe Ngay
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M5 12h14M12 5l7 7-7 7" />
            </svg>
        </a>
    </div>
</section>

<!-- Features Section -->
<section class="features" id="about">
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">🏆</div>
            <h3>Chất Lượng Đảm Bảo</h3>
            <p>Tất cả xe đều được kiểm tra kỹ lưỡng trước khi bán</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">💰</div>
            <h3>Giá Cả Hợp Lý</h3>
            <p>Cam kết giá tốt nhất, hỗ trợ trả góp lãi suất thấp</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">🔧</div>
            <h3>Bảo Hành Toàn Diện</h3>
            <p>Bảo hành chính hãng, hỗ trợ bảo dưỡng định kỳ</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">👨‍💼</div>
            <h3>Tư Vấn Chuyên Nghiệp</h3>
            <p>Đội ngũ tư vấn nhiệt tình, hỗ trợ 24/7</p>
        </div>
    </div>
</section>

<!-- Car Brands Section - Premium Design -->
<section class="car-brands">
    <div class="container">
        <div class="section-header">
            <h2>Khám Phá <span class="highlight">Thương Hiệu</span></h2>
            <p>Lựa chọn từ những thương hiệu xe hàng đầu thế giới</p>
        </div>

        <!-- Brands Container -->
        <div class="brands-container">
            <div class="brands-panel active">
                <div class="brands-showcase-wrapper">
                    <button class="slider-nav prev" onclick="slideBrands(-1)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 18l-6-6 6-6" />
                        </svg>
                    </button>
                    <div class="brands-showcase" id="brands-showcase">
                        <?php foreach ($brandsWithCars as $brand): ?>
                            <a href="/cars?brand_id=<?= $brand['id'] ?>" class="brand-item">
                                <div class="brand-inner">
                                    <?php if (!empty($brand['logo'])): ?>
                                        <img src="<?= htmlspecialchars($brand['logo']) ?>" alt="<?= htmlspecialchars($brand['name']) ?>">
                                    <?php else: ?>
                                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($brand['name']) ?>&background=D4AF37&color=000&size=100" alt="<?= htmlspecialchars($brand['name']) ?>">
                                    <?php endif; ?>
                                    <span><?= htmlspecialchars($brand['name']) ?></span>
                                    <small class="car-count"><?= $brand['car_count'] ?> xe</small>
                                </div>
                            </a>
                        <?php endforeach; ?>
                        <?php if (empty($brandsWithCars)): ?>
                            <p style="text-align: center; color: #999; padding: 40px;">Chưa có thương hiệu nào</p>
                        <?php endif; ?>
                    </div>
                    <button class="slider-nav next" onclick="slideBrands(1)">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 18l6-6-6-6" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="brands-footer">
            <a href="/cars" class="view-all-btn">
                Xem Tất Cả Thương Hiệu
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Brands Slider Script -->
<script>
    // Slider function
    function slideBrands(direction) {
        const showcase = document.getElementById('brands-showcase');
        const scrollAmount = 220; // brand-item width + gap
        showcase.scrollBy({
            left: direction * scrollAmount * 2,
            behavior: 'smooth'
        });
    }

    // Enable drag to scroll
    const showcase = document.getElementById('brands-showcase');
    if (showcase) {
        let isDown = false;
        let startX;
        let scrollLeft;

        showcase.addEventListener('mousedown', (e) => {
            isDown = true;
            showcase.style.cursor = 'grabbing';
            startX = e.pageX - showcase.offsetLeft;
            scrollLeft = showcase.scrollLeft;
        });

        showcase.addEventListener('mouseleave', () => {
            isDown = false;
            showcase.style.cursor = 'grab';
        });

        showcase.addEventListener('mouseup', () => {
            isDown = false;
            showcase.style.cursor = 'grab';
        });

        showcase.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - showcase.offsetLeft;
            const walk = (x - startX) * 2;
            showcase.scrollLeft = scrollLeft - walk;
        });
    }
</script>

<!-- Gallery Section -->
<section class="gallery-section">
    <div class="container">
        <div class="section-header">
            <h2>Bộ Sưu Tập <span class="highlight">Xe Cao Cấp</span></h2>
            <p>Khám phá những mẫu xe đẳng cấp trong showroom của chúng tôi</p>
        </div>
        
        <div class="gallery-grid">
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&q=80" alt="Luxury Car">
                <div class="gallery-overlay">
                    <h3>Siêu Xe Thể Thao</h3>
                    <p>Trải nghiệm tốc độ đỉnh cao</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=600&q=80" alt="SUV">
                <div class="gallery-overlay">
                    <h3>SUV Sang Trọng</h3>
                    <p>Mạnh mẽ và tiện nghi</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?w=600&q=80" alt="Sedan">
                <div class="gallery-overlay">
                    <h3>Sedan Đẳng Cấp</h3>
                    <p>Lịch lãm và tinh tế</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1619405399517-d7fce0f13302?w=600&q=80" alt="Electric">
                <div class="gallery-overlay">
                    <h3>Xe Điện Tương Lai</h3>
                    <p>Công nghệ xanh tiên tiến</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=600&q=80" alt="Classic">
                <div class="gallery-overlay">
                    <h3>Xe Cổ Điển</h3>
                    <p>Giá trị vượt thời gian</p>
                </div>
            </div>
            <div class="gallery-item">
                <img src="https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=600&q=80" alt="Performance">
                <div class="gallery-overlay">
                    <h3>Hiệu Suất Cao</h3>
                    <p>Động lực mạnh mẽ</p>
                </div>
            </div>
        </div>

        <div class="gallery-footer">
            <a href="/cars" class="view-all-btn">
                Xem Toàn Bộ Bộ Sưu Tập
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-us">
    <div class="container">
        <div class="section-header" style="margin-bottom: 60px;">
            <h2 style="color: #fff;">Tại Sao Chọn <span class="highlight">Chúng Tôi</span></h2>
            <p style="color: #ccc;">Những lý do khiến khách hàng tin tưởng và lựa chọn dịch vụ của chúng tôi</p>
        </div>
        
        <div class="why-grid">
            <div class="why-item">
                <div class="why-icon">🎯</div>
                <h3>Cam Kết Chất Lượng</h3>
                <p>100% xe được kiểm tra kỹ lưỡng bởi đội ngũ chuyên gia, đảm bảo tình trạng hoàn hảo trước khi bàn giao</p>
            </div>
            <div class="why-item">
                <div class="why-icon">💎</div>
                <h3>Giá Cả Minh Bạch</h3>
                <p>Không phí ẩn, báo giá rõ ràng, hỗ trợ trả góp với lãi suất ưu đãi nhất thị trường</p>
            </div>
            <div class="why-item">
                <div class="why-icon">🛡️</div>
                <h3>Bảo Hành Dài Hạn</h3>
                <p>Chế độ bảo hành toàn diện, hỗ trợ bảo dưỡng định kỳ miễn phí trong năm đầu tiên</p>
            </div>
            <div class="why-item">
                <div class="why-icon">🚀</div>
                <h3>Giao Xe Nhanh Chóng</h3>
                <p>Quy trình mua xe đơn giản, giao xe tận nhà trong vòng 24-48 giờ sau khi hoàn tất thủ tục</p>
            </div>
            <div class="why-item">
                <div class="why-icon">📞</div>
                <h3>Hỗ Trợ 24/7</h3>
                <p>Đội ngũ tư vấn chuyên nghiệp, sẵn sàng hỗ trợ mọi lúc, mọi nơi khi bạn cần</p>
            </div>
            <div class="why-item">
                <div class="why-icon">🎁</div>
                <h3>Quà Tặng Hấp Dẫn</h3>
                <p>Nhiều ưu đãi, quà tặng giá trị cho khách hàng mua xe, tích điểm đổi thưởng hấp dẫn</p>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials">
    <div class="container">
        <div class="section-header" style="margin-bottom: 60px;">
            <h2>Khách Hàng <span class="highlight">Nói Gì</span></h2>
            <p>Những phản hồi chân thực từ khách hàng đã tin tưởng sử dụng dịch vụ</p>
        </div>
        
        <div class="testimonials-grid">
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Tôi rất hài lòng với dịch vụ tại đây. Xe được kiểm tra kỹ lưỡng, giá cả hợp lý và đội ngũ nhân viên rất nhiệt tình. Đặc biệt là chế độ bảo hành và hỗ trợ sau bán rất tốt!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">NV</div>
                    <div class="author-info">
                        <h4>Nguyễn Văn A</h4>
                        <p>Chủ xe Mercedes-Benz GLE 450</p>
                        <div class="rating">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Quy trình mua xe rất nhanh gọn, minh bạch. Tôi được tư vấn tận tình về các lựa chọn phù hợp với nhu cầu. Sau 6 tháng sử dụng, xe vẫn hoạt động rất tốt, không có vấn đề gì."</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">LT</div>
                    <div class="author-info">
                        <h4>Lê Thị B</h4>
                        <p>Chủ xe BMW X7 xDrive40i</p>
                        <div class="rating">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
            </div>
            
            <div class="testimonial-card">
                <div class="testimonial-content">
                    <p>"Showroom rất chuyên nghiệp, xe đa dạng và chất lượng. Nhân viên am hiểu sâu về sản phẩm, tư vấn nhiệt tình không ép buộc. Giá cả cạnh tranh, có nhiều chương trình ưu đãi hấp dẫn!"</p>
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar">TH</div>
                    <div class="author-info">
                        <h4>Trần Hoàng C</h4>
                        <p>Chủ xe Lamborghini Urus</p>
                        <div class="rating">⭐⭐⭐⭐⭐</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Gallery Section */
.gallery-section {
    padding: 100px 50px;
    background: linear-gradient(to bottom, #f9f7f3 0%, #ffffff 100%);
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
    max-width: 1400px;
    margin: 0 auto 40px;
}

.gallery-item {
    position: relative;
    height: 320px;
    border-radius: 20px;
    overflow: hidden;
    cursor: pointer;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s ease;
    filter: brightness(0.85) contrast(1.1);
}

.gallery-item:hover img {
    transform: scale(1.15);
    filter: brightness(1) contrast(1.15);
}

.gallery-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 30px;
    background: linear-gradient(to top, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.6) 50%, transparent 100%);
    color: #fff;
    transform: translateY(0);
    opacity: 1;
    transition: all 0.4s ease;
}

.gallery-item:hover .gallery-overlay {
    background: linear-gradient(to top, rgba(0, 0, 0, 0.95) 0%, rgba(0, 0, 0, 0.75) 60%, transparent 100%);
    padding-bottom: 35px;
}

.gallery-overlay h3 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 8px;
    color: #D4AF37;
    font-family: 'Montserrat', sans-serif;
}

.gallery-overlay p {
    font-size: 14px;
    color: #ddd;
    margin: 0;
}

.gallery-footer {
    text-align: center;
    margin-top: 50px;
}

@media (max-width: 1024px) {
    .gallery-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .gallery-grid {
        grid-template-columns: 1fr;
    }
}

/* Why Choose Us Section */
.why-choose-us {
    padding: 100px 50px;
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
    position: relative;
    overflow: hidden;
}

.why-choose-us::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, transparent 70%);
    border-radius: 50%;
}

.why-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.why-item {
    background: rgba(255, 255, 255, 0.05);
    padding: 40px 30px;
    border-radius: 16px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(212, 175, 55, 0.2);
    transition: all 0.4s ease;
    text-align: center;
}

.why-item:hover {
    transform: translateY(-10px);
    background: rgba(212, 175, 55, 0.1);
    border-color: rgba(212, 175, 55, 0.5);
    box-shadow: 0 20px 50px rgba(212, 175, 55, 0.3);
}

.why-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 25px;
    font-size: 36px;
    box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
}

.why-item h3 {
    font-size: 22px;
    color: #D4AF37;
    margin-bottom: 15px;
    font-weight: 700;
}

.why-item p {
    color: #ccc;
    line-height: 1.6;
    font-size: 15px;
}

/* Testimonials Section */
.testimonials {
    padding: 100px 50px;
    background: #ffffff;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
}

.testimonial-card {
    background: linear-gradient(145deg, #f9f7f3 0%, #ffffff 100%);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
    border: 2px solid rgba(212, 175, 55, 0.1);
    transition: all 0.4s ease;
    position: relative;
}

.testimonial-card::before {
    content: '\201C';
    position: absolute;
    top: 20px;
    left: 30px;
    font-size: 80px;
    color: rgba(212, 175, 55, 0.2);
    font-family: Georgia, serif;
    line-height: 1;
}

.testimonial-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 45px rgba(212, 175, 55, 0.2);
    border-color: rgba(212, 175, 55, 0.3);
}

.testimonial-content {
    margin-bottom: 25px;
    position: relative;
    z-index: 1;
}

.testimonial-content p {
    font-size: 16px;
    line-height: 1.8;
    color: #333;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
}

.author-avatar {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #000;
    font-weight: 700;
    font-size: 24px;
    box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
}

.author-info h4 {
    font-size: 18px;
    color: #1a1a1a;
    margin-bottom: 5px;
    font-weight: 700;
}

.author-info p {
    font-size: 14px;
    color: #666;
    margin: 0;
}

.rating {
    color: #FFD700;
    font-size: 16px;
    margin-top: 5px;
}

@media (max-width: 768px) {
    .why-grid,
    .testimonials-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php include __DIR__ . '/../layouts/footer.php'; ?>