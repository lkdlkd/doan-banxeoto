<?php 
$currentPage = 'home';
$pageTitle = 'Trang Chủ';
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
            <a href="/autocar/cars" class="cta-button">
                Xem Xe Ngay
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M5 12h14M12 5l7 7-7 7"/>
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

            <!-- Category Tabs -->
            <div class="category-tabs">
                <button class="tab-btn active" data-category="popular">Phổ Biến</button>
                <button class="tab-btn" data-category="premium">Sang Trọng</button>
                <button class="tab-btn" data-category="luxury">Cao Cấp</button>
                <button class="tab-btn gold" data-category="supercar">Siêu Xe</button>
            </div>

            <!-- Brands Container -->
            <div class="brands-container">
                <!-- Popular Brands -->
                <div class="brands-panel active" data-panel="popular">
                    <div class="brands-showcase-wrapper">
                        <button class="slider-nav prev" onclick="slideBrands('popular', -1)">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 18l-6-6 6-6"/>
                            </svg>
                        </button>
                        <div class="brands-showcase" id="brands-popular">
                            <a href="/autocar/cars?brand=toyota" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/toyota-logo.png" alt="Toyota">
                                    <span>Toyota</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=honda" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/honda-logo.png" alt="Honda">
                                    <span>Honda</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=mazda" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/mazda-logo.png" alt="Mazda">
                                    <span>Mazda</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=hyundai" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/hyundai-logo.png" alt="Hyundai">
                                    <span>Hyundai</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=kia" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/kia-logo.png" alt="Kia">
                                    <span>Kia</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=vinfast" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/vinfast-logo.png" alt="VinFast">
                                    <span>VinFast</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=ford" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/ford-logo.png" alt="Ford">
                                    <span>Ford</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=chevrolet" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/chevrolet-logo.png" alt="Chevrolet">
                                    <span>Chevrolet</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=mitsubishi" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/mitsubishi-logo.png" alt="Mitsubishi">
                                    <span>Mitsubishi</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=nissan" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/nissan-logo.png" alt="Nissan">
                                    <span>Nissan</span>
                                </div>
                            </a>
                        </div>
                        <button class="slider-nav next" onclick="slideBrands('popular', 1)">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Premium Brands -->
                <div class="brands-panel" data-panel="premium">
                    <div class="brands-showcase-wrapper">
                        <button class="slider-nav prev" onclick="slideBrands('premium', -1)">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 18l-6-6 6-6"/>
                            </svg>
                        </button>
                        <div class="brands-showcase" id="brands-premium">
                            <a href="/autocar/cars?brand=mercedes" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/mercedes-benz-logo.png" alt="Mercedes-Benz">
                                    <span>Mercedes-Benz</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=bmw" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/bmw-logo.png" alt="BMW">
                                    <span>BMW</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=audi" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/audi-logo.png" alt="Audi">
                                    <span>Audi</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=lexus" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/lexus-logo.png" alt="Lexus">
                                    <span>Lexus</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=volvo" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/volvo-logo.png" alt="Volvo">
                                    <span>Volvo</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=infiniti" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/infiniti-logo.png" alt="Infiniti">
                                    <span>Infiniti</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=genesis" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/genesis-logo.png" alt="Genesis">
                                    <span>Genesis</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=lincoln" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/lincoln-logo.png" alt="Lincoln">
                                    <span>Lincoln</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=cadillac" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/cadillac-logo.png" alt="Cadillac">
                                    <span>Cadillac</span>
                                </div>
                            </a>
                        </div>
                        <button class="slider-nav next" onclick="slideBrands('premium', 1)">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Luxury Brands -->
                <div class="brands-panel" data-panel="luxury">
                    <div class="brands-showcase-wrapper">
                        <button class="slider-nav prev" onclick="slideBrands('luxury', -1)">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 18l-6-6 6-6"/>
                            </svg>
                        </button>
                        <div class="brands-showcase" id="brands-luxury">
                            <a href="/autocar/cars?brand=porsche" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/porsche-logo.png" alt="Porsche">
                                    <span>Porsche</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=maserati" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/maserati-logo.png" alt="Maserati">
                                    <span>Maserati</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=bentley" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/bentley-logo.png" alt="Bentley">
                                    <span>Bentley</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=jaguar" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/jaguar-logo.png" alt="Jaguar">
                                    <span>Jaguar</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=land-rover" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/land-rover-logo.png" alt="Land Rover">
                                    <span>Land Rover</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=aston-martin" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/aston-martin-logo.png" alt="Aston Martin">
                                    <span>Aston Martin</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=maybach" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/maybach-logo.png" alt="Maybach">
                                    <span>Maybach</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=alfa-romeo" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/alfa-romeo-logo.png" alt="Alfa Romeo">
                                    <span>Alfa Romeo</span>
                                </div>
                            </a>
                        </div>
                        <button class="slider-nav next" onclick="slideBrands('luxury', 1)">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Supercar Brands -->
                <div class="brands-panel supercar" data-panel="supercar">
                    <div class="brands-showcase-wrapper">
                        <button class="slider-nav prev" onclick="slideBrands('supercar', -1)">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 18l-6-6 6-6"/>
                            </svg>
                        </button>
                        <div class="brands-showcase" id="brands-supercar">
                            <a href="/autocar/cars?brand=ferrari" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/ferrari-logo.png" alt="Ferrari">
                                    <span>Ferrari</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=lamborghini" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/lamborghini-logo.png" alt="Lamborghini">
                                    <span>Lamborghini</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=mclaren" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/mclaren-logo.png" alt="McLaren">
                                    <span>McLaren</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=bugatti" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/bugatti-logo.png" alt="Bugatti">
                                    <span>Bugatti</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=rolls-royce" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/rolls-royce-logo.png" alt="Rolls-Royce">
                                    <span>Rolls-Royce</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=koenigsegg" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/koenigsegg-logo.png" alt="Koenigsegg">
                                    <span>Koenigsegg</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=pagani" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/pagani-logo.png" alt="Pagani">
                                    <span>Pagani</span>
                                </div>
                            </a>
                            <a href="/autocar/cars?brand=rimac" class="brand-item">
                                <div class="brand-inner">
                                    <img src="https://www.carlogos.org/car-logos/rimac-logo.png" alt="Rimac">
                                    <span>Rimac</span>
                                </div>
                            </a>
                        </div>
                        <button class="slider-nav next" onclick="slideBrands('supercar', 1)">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 18l6-6-6-6"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div class="brands-footer">
                <a href="/autocar/cars" class="view-all-btn">
                    Xem Tất Cả Thương Hiệu
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Brands Slider Script -->
    <script>
        // Tab switching
        document.querySelectorAll('.category-tabs .tab-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const category = this.dataset.category;
                
                // Update active tab
                document.querySelectorAll('.category-tabs .tab-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Update active panel
                document.querySelectorAll('.brands-panel').forEach(p => p.classList.remove('active'));
                document.querySelector(`.brands-panel[data-panel="${category}"]`).classList.add('active');
            });
        });

        // Slider function
        function slideBrands(category, direction) {
            const showcase = document.getElementById('brands-' + category);
            const scrollAmount = 220; // brand-item width + gap
            showcase.scrollBy({
                left: direction * scrollAmount * 2,
                behavior: 'smooth'
            });
        }

        // Enable drag to scroll
        document.querySelectorAll('.brands-showcase').forEach(showcase => {
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
        });
    </script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
