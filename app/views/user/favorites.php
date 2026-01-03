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
.favorites-page {
    padding: 120px 0 80px;
    background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
    min-height: 100vh;
}

.favorites-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.favorites-header {
    text-align: center;
    margin-bottom: 50px;
}

.favorites-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 42px;
    color: #fff;
    margin-bottom: 10px;
}

.favorites-header h1 span {
    color: #D4AF37;
}

.favorites-header p {
    color: rgba(255,255,255,0.6);
    font-size: 16px;
}

.favorites-count {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    background: rgba(212, 175, 55, 0.1);
    border: 1px solid rgba(212, 175, 55, 0.3);
    border-radius: 30px;
    color: #D4AF37;
    font-size: 14px;
    margin-top: 20px;
}

.favorites-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
}

.favorite-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
    position: relative;
}

.favorite-card:hover {
    border-color: rgba(212, 175, 55, 0.5);
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
}

.favorite-remove {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 40px;
    height: 40px;
    background: rgba(239, 68, 68, 0.9);
    border: none;
    border-radius: 50%;
    color: #fff;
    cursor: pointer;
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    opacity: 0;
}

.favorite-card:hover .favorite-remove {
    opacity: 1;
}

.favorite-remove:hover {
    background: #ef4444;
    transform: scale(1.1);
}

.favorite-image {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.favorite-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.favorite-card:hover .favorite-image img {
    transform: scale(1.05);
}

.favorite-badge {
    position: absolute;
    bottom: 16px;
    left: 16px;
    display: flex;
    gap: 8px;
}

.favorite-badge span {
    padding: 6px 12px;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(4px);
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    color: #fff;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.favorite-badge span.year {
    background: rgba(212, 175, 55, 0.9);
    color: #000;
}

.favorite-content {
    padding: 24px;
}

.favorite-brand {
    font-size: 12px;
    color: #D4AF37;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}

.favorite-name {
    font-family: 'Playfair Display', serif;
    font-size: 22px;
    color: #fff;
    margin: 0 0 16px 0;
}

.favorite-specs {
    display: flex;
    gap: 16px;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.favorite-spec {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: rgba(255,255,255,0.6);
}

.favorite-spec i {
    color: #D4AF37;
}

.favorite-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.favorite-price {
    font-family: 'Playfair Display', serif;
    font-size: 24px;
    color: #D4AF37;
    font-weight: 600;
}

.favorite-price span {
    font-size: 14px;
    color: rgba(255,255,255,0.5);
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
}

.btn-view {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
    color: #000;
    text-decoration: none;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 20px rgba(212, 175, 55, 0.3);
}

/* Empty State */
.empty-favorites {
    text-align: center;
    padding: 80px 20px;
}

.empty-favorites i {
    font-size: 80px;
    color: rgba(212, 175, 55, 0.3);
    margin-bottom: 24px;
}

.empty-favorites h3 {
    font-family: 'Playfair Display', serif;
    font-size: 28px;
    color: #fff;
    margin-bottom: 12px;
}

.empty-favorites p {
    color: rgba(255,255,255,0.5);
    margin-bottom: 30px;
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
    .favorites-header h1 {
        font-size: 32px;
    }
    
    .favorites-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<div class="favorites-page">
    <div class="favorites-container">
        <div class="favorites-header">
            <h1>Xe <span>yêu thích</span></h1>
            <p>Danh sách những chiếc xe bạn đã lưu</p>
            <?php if (!empty($favorites)): ?>
            <div class="favorites-count">
                <i class="fas fa-heart"></i>
                <?= count($favorites) ?> xe yêu thích
            </div>
            <?php endif; ?>
        </div>

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
                            <?= $car['fuel'] ?? 'Xăng' ?>
                        </div>
                        <div class="favorite-spec">
                            <i class="fas fa-cog"></i>
                            <?= $car['transmission'] ?? 'Tự động' ?>
                        </div>
                        <div class="favorite-spec">
                            <i class="fas fa-tachometer-alt"></i>
                            <?= number_format($car['mileage'] ?? 0) ?> km
                        </div>
                    </div>
                    <div class="favorite-footer">
                        <div class="favorite-price">
                            <?= number_format($car['price'] ?? 0, 0, ',', '.') ?><span>₫</span>
                        </div>
                        <a href="/car/<?= $car['car_id'] ?>" class="btn-view">
                            Xem chi tiết
                            <i class="fas fa-arrow-right"></i>
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
