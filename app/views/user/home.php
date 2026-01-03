<?php 
$currentPage = 'home';
$pageTitle = 'Trang Chủ';

// Load Brand Model
require_once __DIR__ . '/../../models/BrandModel.php';
$brandModel = new BrandModel();

// Lấy tất cả brands có xe
$allBrands = $brandModel->getBrandsWithCarCount();

// Phân loại brands theo category
$brandsByCategory = [
    'popular' => [],
    'premium' => [],
    'luxury' => [],
    'supercar' => []
];

foreach ($allBrands as $brand) {
    if ($brand['car_count'] > 0) {
        $category = strtolower($brand['category'] ?? 'popular');
        if (isset($brandsByCategory[$category])) {
            $brandsByCategory[$category][] = $brand;
        } else {
            $brandsByCategory['popular'][] = $brand;
        }
    }
}

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
                            <?php foreach ($brandsByCategory['popular'] as $brand): ?>
                                <a href="/cars?brand_id=<?= $brand['id'] ?>" class="brand-item">
                                    <div class="brand-inner">
                                        <?php if (!empty($brand['logo_url'])): ?>
                                            <img src="<?= htmlspecialchars($brand['logo_url']) ?>" alt="<?= htmlspecialchars($brand['name']) ?>">
                                        <?php else: ?>
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($brand['name']) ?>&background=D4AF37&color=000&size=100" alt="<?= htmlspecialchars($brand['name']) ?>">
                                        <?php endif; ?>
                                        <span><?= htmlspecialchars($brand['name']) ?></span>
                                        <?php if ($brand['car_count'] > 0): ?>
                                            <small class="car-count"><?= $brand['car_count'] ?> xe</small>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <?php if (empty($brandsByCategory['popular'])): ?>
                                <p style="text-align: center; color: #999; padding: 40px;">Chưa có thương hiệu nào</p>
                            <?php endif; ?>
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
                            <?php foreach ($brandsByCategory['premium'] as $brand): ?>
                                <a href="/cars?brand_id=<?= $brand['id'] ?>" class="brand-item">
                                    <div class="brand-inner">
                                        <?php if (!empty($brand['logo_url'])): ?>
                                            <img src="<?= htmlspecialchars($brand['logo_url']) ?>" alt="<?= htmlspecialchars($brand['name']) ?>">
                                        <?php else: ?>
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($brand['name']) ?>&background=D4AF37&color=000&size=100" alt="<?= htmlspecialchars($brand['name']) ?>">
                                        <?php endif; ?>
                                        <span><?= htmlspecialchars($brand['name']) ?></span>
                                        <?php if ($brand['car_count'] > 0): ?>
                                            <small class="car-count"><?= $brand['car_count'] ?> xe</small>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <?php if (empty($brandsByCategory['premium'])): ?>
                                <p style="text-align: center; color: #999; padding: 40px;">Chưa có thương hiệu nào</p>
                            <?php endif; ?>
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
                            <?php foreach ($brandsByCategory['luxury'] as $brand): ?>
                                <a href="/cars?brand_id=<?= $brand['id'] ?>" class="brand-item">
                                    <div class="brand-inner">
                                        <?php if (!empty($brand['logo_url'])): ?>
                                            <img src="<?= htmlspecialchars($brand['logo_url']) ?>" alt="<?= htmlspecialchars($brand['name']) ?>">
                                        <?php else: ?>
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($brand['name']) ?>&background=D4AF37&color=000&size=100" alt="<?= htmlspecialchars($brand['name']) ?>">
                                        <?php endif; ?>
                                        <span><?= htmlspecialchars($brand['name']) ?></span>
                                        <?php if ($brand['car_count'] > 0): ?>
                                            <small class="car-count"><?= $brand['car_count'] ?> xe</small>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <?php if (empty($brandsByCategory['luxury'])): ?>
                                <p style="text-align: center; color: #999; padding: 40px;">Chưa có thương hiệu nào</p>
                            <?php endif; ?>
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
                            <?php foreach ($brandsByCategory['supercar'] as $brand): ?>
                                <a href="/cars?brand_id=<?= $brand['id'] ?>" class="brand-item">
                                    <div class="brand-inner">
                                        <?php if (!empty($brand['logo_url'])): ?>
                                            <img src="<?= htmlspecialchars($brand['logo_url']) ?>" alt="<?= htmlspecialchars($brand['name']) ?>">
                                        <?php else: ?>
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($brand['name']) ?>&background=D4AF37&color=000&size=100" alt="<?= htmlspecialchars($brand['name']) ?>">
                                        <?php endif; ?>
                                        <span><?= htmlspecialchars($brand['name']) ?></span>
                                        <?php if ($brand['car_count'] > 0): ?>
                                            <small class="car-count"><?= $brand['car_count'] ?> xe</small>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                            <?php if (empty($brandsByCategory['supercar'])): ?>
                                <p style="text-align: center; color: #999; padding: 40px;">Chưa có thương hiệu nào</p>
                            <?php endif; ?>
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
                <a href="/cars" class="view-all-btn">
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
