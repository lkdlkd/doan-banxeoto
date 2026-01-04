<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../config/config.php'; }

$pageTitle = 'Đánh giá xe';
include __DIR__ . '/../layouts/header.php';
?>

<style>
    .review-form-container {
        max-width: 800px;
        margin: 100px auto 50px;
        padding: 0 20px;
    }

    .review-form-card {
        background: #fff;
        border-radius: 16px;
        padding: 3rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .review-form-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 2px solid #f0f0f0;
    }

    .review-form-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        color: #1a1a1a;
        margin: 0 0 1rem 0;
    }

    .car-review-info {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding: 1.5rem;
        background: #f8f8f8;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .car-review-image {
        width: 120px;
        height: 80px;
        border-radius: 8px;
        overflow: hidden;
    }

    .car-review-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .car-review-details h3 {
        font-size: 1.25rem;
        color: #1a1a1a;
        margin: 0 0 0.25rem 0;
    }

    .car-review-details p {
        color: #666;
        margin: 0;
        font-size: 0.9rem;
    }

    .rating-input-group {
        margin-bottom: 2rem;
    }

    .rating-input-group label {
        display: block;
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 1rem;
    }

    .star-rating {
        display: flex;
        gap: 0.5rem;
        justify-content: center;
        margin: 1.5rem 0;
    }

    .star-rating input[type="radio"] {
        display: none;
    }

    .star-rating label {
        font-size: 3rem;
        color: #ddd;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #D4AF37;
        transform: scale(1.1);
    }

    .star-rating input[type="radio"]:checked ~ label {
        color: #D4AF37;
    }

    .comment-input-group {
        margin-bottom: 2rem;
    }

    .comment-input-group label {
        display: block;
        font-size: 1.1rem;
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.75rem;
    }

    .comment-input-group textarea {
        width: 100%;
        min-height: 150px;
        padding: 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        font-family: inherit;
        resize: vertical;
        transition: border-color 0.3s ease;
    }

    .comment-input-group textarea:focus {
        outline: none;
        border-color: #D4AF37;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    .btn {
        padding: 1rem 2.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: linear-gradient(135deg, #D4AF37 0%, #C4A030 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);
    }

    .btn-secondary {
        background: #f0f0f0;
        color: #666;
    }

    .btn-secondary:hover {
        background: #e0e0e0;
    }

    .rating-labels {
        display: flex;
        justify-content: space-between;
        margin-top: 0.5rem;
        font-size: 0.85rem;
        color: #999;
    }

    @media (max-width: 768px) {
        .review-form-card {
            padding: 2rem 1.5rem;
        }

        .car-review-info {
            flex-direction: column;
            text-align: center;
        }

        .car-review-image {
            width: 100%;
            height: 150px;
        }

        .star-rating label {
            font-size: 2.5rem;
        }
    }
</style>

<main class="review-form-container">
    <div class="review-form-card">
        <div class="review-form-header">
            <h1><i class="fas fa-star"></i> Đánh giá xe</h1>
            <p style="color: #666;">Chia sẻ trải nghiệm của bạn với chiếc xe này</p>
        </div>

        <!-- Car Info -->
        <div class="car-review-info">
            <div class="car-review-image">
                <img src="<?= $car['image_url'] ?? 'https://via.placeholder.com/120x80' ?>" alt="<?= htmlspecialchars($car['name']) ?>">
            </div>
            <div class="car-review-details">
                <h3><?= htmlspecialchars($car['name']) ?></h3>
                <p><?= htmlspecialchars($car['brand_name']) ?> • <?= number_format($car['price'], 0, ',', '.') ?>₫</p>
            </div>
        </div>

        <!-- Review Form -->
        <form action="<?= BASE_URL ?>/review/submit" method="POST">
            <input type="hidden" name="car_id" value="<?= $car['id'] ?>">

            <!-- Rating -->
            <div class="rating-input-group">
                <label><i class="fas fa-star" style="color: #D4AF37;"></i> Đánh giá của bạn</label>
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5" required>
                    <label for="star5" title="5 sao">★</label>
                    
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4" title="4 sao">★</label>
                    
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3" title="3 sao">★</label>
                    
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2" title="2 sao">★</label>
                    
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1" title="1 sao">★</label>
                </div>
                <div class="rating-labels">
                    <span>Rất tệ</span>
                    <span>Tệ</span>
                    <span>Tạm được</span>
                    <span>Tốt</span>
                    <span>Xuất sắc</span>
                </div>
            </div>

            <!-- Comment -->
            <div class="comment-input-group">
                <label><i class="fas fa-comment"></i> Nhận xét của bạn</label>
                <textarea name="comment" placeholder="Chia sẻ trải nghiệm của bạn về xe này... (không bắt buộc)"></textarea>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="<?= BASE_URL ?>/car/<?= $car['id'] ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Gửi đánh giá
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    // Reverse star rating order for proper visual effect
    const stars = document.querySelectorAll('.star-rating label');
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const input = document.querySelector(`#${this.getAttribute('for')}`);
            if (input) {
                input.checked = true;
            }
        });
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
