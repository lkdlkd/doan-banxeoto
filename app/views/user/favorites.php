<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../config/config.php'; }

// Load model
require_once __DIR__ . '/../../models/FavoriteModel.php';

$favoriteModel = new FavoriteModel();
$userId = $_SESSION['user_id'];

// Lấy danh sách xe yêu thích của user
$favorites = $favoriteModel->getByUserId($userId);

$pageTitle = 'Xe yêu thích - AutoCar';
$currentPage = 'favorites';

include __DIR__ . '/../layouts/header.php';
?>

<style>
/* Banner */
.favorites-banner {
    position: relative;
    height: 280px;
    background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 100%), 
                url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1920&q=80') center/cover;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: -60px;
}

.favorites-banner-content {
    text-align: center;
    color: #fff;
    position: relative;
    z-index: 1;
}

.favorites-banner h1 {
    font-family: 'Playfair Display', serif;
    font-size: 48px;
    font-weight: 700;
    margin-bottom: 10px;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.favorites-banner h1 span {
    color: #D4AF37;
}

.favorites-banner p {
    font-size: 18px;
    color: rgba(255,255,255,0.9);
    text-shadow: 0 1px 5px rgba(0,0,0,0.3);
}

.favorites-page {
    background: linear-gradient(135deg, #f9f7f3 0%, #f5f2ed 100%);
    min-height: 100vh;
    padding-bottom: 80px;
}

.favorites-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 30px;
    position: relative;
    z-index: 2;
}

.favorites-header {
    margin-bottom: 40px;
}

/* Stats Table */
.favorites-stats {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 2px solid rgba(212, 175, 55, 0.2);
    margin-bottom: 40px;
}

.stats-table {
    width: 100%;
    border-collapse: collapse;
}

.stats-table thead {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
}

.stats-table thead th {
    padding: 20px 25px;
    text-align: left;
    font-family: 'Playfair Display', serif;
    font-size: 16px;
    color: #D4AF37;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-right: 1px solid rgba(212, 175, 55, 0.2);
}

.stats-table thead th:last-child {
    border-right: none;
}

.stats-table thead th svg {
    margin-right: 8px;
    vertical-align: middle;
}

.stats-table tbody td {
    padding: 25px;
    text-align: center;
    border-right: 1px solid #f0f0f0;
    transition: all 0.3s;
}

.stats-table tbody td:last-child {
    border-right: none;
}

.stats-table tbody tr:hover td {
    background: rgba(212, 175, 55, 0.05);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(212,175,55,0.15) 0%, rgba(212,175,55,0.1) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 12px;
}

.stat-icon svg {
    color: #D4AF37;
}

.stat-value {
    font-size: 36px;
    font-weight: 700;
    color: #1a1a1a;
    font-family: 'Playfair Display', serif;
    margin-bottom: 8px;
    display: block;
}

.stat-label {
    font-size: 13px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.favorites-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 30px;
}

.favorite-card {
    background: #fff;
    border: 2px solid transparent;
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    background-clip: padding-box;
}

.favorite-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    border-radius: 20px;
    padding: 2px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 50%, #D4AF37 100%);
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor;
    mask-composite: exclude;
    opacity: 0;
    transition: opacity 0.4s;
}

.favorite-card:hover::before {
    opacity: 1;
}

.favorite-card::after {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(212,175,55,0.1) 0%, transparent 70%);
    opacity: 0;
    transition: opacity 0.4s;
}

.favorite-card:hover::after {
    opacity: 1;
}

.favorite-card:hover {
    transform: translateY(-12px) scale(1.02);
    box-shadow: 0 20px 40px rgba(212,175,55,0.3), 0 0 0 1px rgba(212,175,55,0.1);
}

.favorite-remove {
    position: absolute;
    top: 18px;
    right: 18px;
    width: 44px;
    height: 44px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border: 2px solid rgba(239, 68, 68, 0.3);
    border-radius: 50%;
    color: #ef4444;
    cursor: pointer;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
}

.favorite-remove:hover {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
    border-color: #ef4444;
    transform: scale(1.15) rotate(90deg);
    box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5);
}

.favorite-image {
    position: relative;
    height: 260px;
    overflow: hidden;
    background: linear-gradient(135deg, #f8f8f8 0%, #e8e8e8 100%);
}

.favorite-image::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(180deg, transparent 0%, rgba(0,0,0,0.4) 100%);
    opacity: 0;
    transition: opacity 0.4s;
}

.favorite-card:hover .favorite-image::after {
    opacity: 1;
}

.favorite-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
}

.favorite-card:hover .favorite-image img {
    transform: scale(1.12);
}

.favorite-badge {
    position: absolute;
    bottom: 18px;
    left: 18px;
    display: flex;
    gap: 10px;
    z-index: 2;
}

.favorite-badge span {
    padding: 10px 18px;
    background: rgba(255,255,255,0.98);
    backdrop-filter: blur(15px);
    border-radius: 10px;
    font-size: 13px;
    font-weight: 700;
    color: #1a1a1a;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    transition: all 0.3s;
}

.favorite-badge span:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.25);
}

.favorite-badge span.year {
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    box-shadow: 0 4px 20px rgba(212,175,55,0.5);
    font-weight: 800;
}

.favorite-content {
    padding: 30px;
}

.favorite-brand {
    font-size: 13px;
    color: #D4AF37;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin-bottom: 12px;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 10px;
}

.favorite-brand::before {
    content: '';
    width: 30px;
    height: 3px;
    background: linear-gradient(90deg, #D4AF37 0%, transparent 100%);
    border-radius: 2px;
}

.favorite-name {
    font-family: 'Playfair Display', serif;
    font-size: 26px;
    color: #1a1a1a;
    margin: 0 0 22px 0;
    font-weight: 700;
    line-height: 1.3;
    transition: color 0.3s;
}

.favorite-card:hover .favorite-name {
    color: #D4AF37;
}

.favorite-specs {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
    padding: 22px;
    background: linear-gradient(135deg, rgba(212,175,55,0.08) 0%, rgba(212,175,55,0.03) 100%);
    border-radius: 12px;
    border: 1px solid rgba(212,175,55,0.15);
}

.favorite-spec {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #666;
    flex: 1;
    transition: transform 0.3s;
}

.favorite-spec:hover {
    transform: translateY(-3px);
}

.favorite-spec svg {
    color: #D4AF37;
    margin-bottom: 4px;
    filter: drop-shadow(0 2px 4px rgba(212,175,55,0.3));
}

.favorite-spec strong {
    color: #1a1a1a;
    font-weight: 700;
    font-size: 13px;
}

.favorite-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 22px;
    border-top: 2px solid #f5f5f5;
    gap: 15px;
}

.favorite-price {
    display: flex;
    flex-direction: column;
    gap: 4px;
    flex-shrink: 0;
}

.favorite-price .label {
    font-size: 11px;
    color: #999;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-weight: 600;
}

.favorite-price .amount {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    color: #D4AF37;
    font-weight: 800;
    text-shadow: 0 2px 4px rgba(212,175,55,0.2);
    line-height: 1;
    white-space: nowrap;
}

.btn-view {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 13px 24px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    text-decoration: none;
    font-size: 13px;
    font-weight: 800;
    border-radius: 10px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    letter-spacing: 0.5px;
    text-transform: uppercase;
    position: relative;
    overflow: hidden;
    white-space: nowrap;
    flex-shrink: 0;
}

.btn-view::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.5s;
}

.btn-view:hover::before {
    left: 100%;
}

.btn-view:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 30px rgba(212, 175, 55, 0.6);
}

.btn-view svg {
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-view:hover svg {
    transform: translateX(5px);
}

/* Empty State */
.empty-favorites {
    text-align: center;
    padding: 100px 20px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.empty-favorites i {
    font-size: 80px;
    color: rgba(212, 175, 55, 0.3);
    margin-bottom: 24px;
}

.empty-favorites h3 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #1a1a1a;
    margin-bottom: 12px;
}

.empty-favorites p {
    color: #666;
    margin-bottom: 30px;
    font-size: 15px;
}

.empty-favorites .btn-explore {
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

.empty-favorites .btn-explore:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(212, 175, 55, 0.3);
}

@media (max-width: 768px) {
    .favorites-banner h1 {
        font-size: 36px;
    }
    
    .stats-table {
        font-size: 13px;
    }
    
    .stats-table thead th {
        padding: 15px 12px;
        font-size: 13px;
    }
    
    .stats-table tbody td {
        padding: 15px 10px;
    }
    
    .stat-icon {
        width: 40px;
        height: 40px;
    }
    
    .stat-value {
        font-size: 28px;
    }
    
    .stat-label {
        font-size: 11px;
    }
    
    .favorites-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Banner -->
<div class="favorites-banner">
    <div class="favorites-banner-content">
        <h1>Xe <span>yêu thích</span></h1>
        <p>Danh sách những chiếc xe bạn đã lưu</p>
    </div>
</div>

<div class="favorites-page">
    <div class="favorites-container">
        <?php if (!empty($favorites)): ?>
            <?php
            // Tính toán thống kê
            $totalFavorites = count($favorites);
            $availableCars = count(array_filter($favorites, fn($c) => ($c['status'] ?? 'available') === 'available'));
            $totalValue = array_sum(array_column($favorites, 'price'));
            $brands = array_unique(array_column($favorites, 'brand_name'));
            $totalBrands = count($brands);
            ?>
            
            <!-- Stats Table -->
            <div class="favorites-stats">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                                Xe yêu thích
                            </th>
                            <th>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                Còn hàng
                            </th>
                            <th>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                </svg>
                                Thương hiệu
                            </th>
                            <th>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="1" x2="12" y2="23"></line>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                </svg>
                                Tổng giá trị
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="stat-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
                                    </svg>
                                </div>
                                <span class="stat-value"><?= $totalFavorites ?></span>
                                <span class="stat-label">Xe</span>
                            </td>
                            <td>
                                <div class="stat-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                </div>
                                <span class="stat-value"><?= $availableCars ?></span>
                                <span class="stat-label">Sẵn có</span>
                            </td>
                            <td>
                                <div class="stat-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                        <line x1="7" y1="7" x2="7.01" y2="7"></line>
                                    </svg>
                                </div>
                                <span class="stat-value"><?= $totalBrands ?></span>
                                <span class="stat-label">Hãng</span>
                            </td>
                            <td>
                                <div class="stat-icon">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="1" x2="12" y2="23"></line>
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                                    </svg>
                                </div>
                                <span class="stat-value"><?= number_format($totalValue / 1000000000, 1) ?>B</span>
                                <span class="stat-label">VNĐ</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <?php if (empty($favorites)): ?>
        <div class="empty-favorites">
            <i class="fas fa-heart"></i>
            <h3>Chưa có xe yêu thích</h3>
            <p>Bạn chưa lưu xe nào vào danh sách yêu thích. Hãy khám phá và thêm xe bạn thích!</p>
            <a href="/cars" class="btn-explore">
                <i class="fas fa-car"></i>
                Khám phá xe
            </a>
        </div>
        <?php else: ?>
        <div class="favorites-grid">
            <?php foreach ($favorites as $car): ?>
            <div class="favorite-card" data-id="<?= $car['car_id'] ?>">
                <button class="favorite-remove" onclick="removeFavorite(<?= $car['car_id'] ?>)" title="Xóa khỏi yêu thích">
                    <i class="fas fa-times"></i>
                </button>
                <div class="favorite-image">
                    <img src="<?= $car['image'] ?? 'https://via.placeholder.com/400x220' ?>" alt="<?= htmlspecialchars($car['car_name'] ?? '') ?>">
                    <div class="favorite-badge">
                        <span class="year"><?= $car['year'] ?? date('Y') ?></span>
                        <?php if (!empty($car['status']) && $car['status'] === 'available'): ?>
                        <span>Còn hàng</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="favorite-content">
                    <div class="favorite-brand"><?= htmlspecialchars($car['brand_name'] ?? '') ?></div>
                    <h3 class="favorite-name"><?= htmlspecialchars($car['car_name'] ?? '') ?></h3>
                    <div class="favorite-specs">
                        <div class="favorite-spec">
                            <i class="fas fa-gas-pump"></i>
                            <strong><?= $car['fuel'] ?? 'Xăng' ?></strong>
                        </div>
                        <div class="favorite-spec">
                            <i class="fas fa-cog"></i>
                            <strong><?= $car['transmission'] ?? 'Tự động' ?></strong>
                        </div>
                        <div class="favorite-spec">
                            <i class="fas fa-tachometer-alt"></i>
                            <strong><?= number_format($car['mileage'] ?? 0) ?> km</strong>
                        </div>
                    </div>
                    <div class="favorite-footer">
                        <div class="favorite-price">
                            <span class="label">Giá bán</span>
                            <span class="amount"><?php 
                                $price = $car['price'] ?? 0;
                                if ($price >= 1000000000) {
                                    echo number_format($price / 1000000000, 1) . ' tỷ';
                                } else if ($price >= 1000000) {
                                    echo number_format($price / 1000000, 0) . ' tr';
                                } else {
                                    echo number_format($price, 0, ',', '.');
                                }
                            ?></span>
                        </div>
                        <a href="/car/<?= $car['car_id'] ?>" class="btn-view">
                            Chi tiết
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12h14M12 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function removeFavorite(carId) {
    if (confirm('Bạn có chắc muốn xóa xe này khỏi danh sách yêu thích?')) {
        fetch('/favorites/remove', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ car_id: carId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Xóa card khỏi DOM với animation
                const card = document.querySelector(`.favorite-card[data-id="${carId}"]`);
                if (card) {
                    card.style.transform = 'scale(0.8)';
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        // Kiểm tra nếu hết xe thì reload trang
                        if (document.querySelectorAll('.favorite-card').length === 0) {
                            location.reload();
                        }
                    }, 300);
                }
            } else {
                alert(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra');
        });
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
