<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; }

// Load models
require_once __DIR__ . '/../../../models/ReviewModel.php';
require_once __DIR__ . '/../../../models/CarModel.php';
require_once __DIR__ . '/../../../models/UserModel.php';

$reviewModel = new ReviewModel();
$carModel = new CarModel();

// Lấy danh sách reviews từ database
$reviews = $reviewModel->getAllWithDetails();

// Tính thống kê
$totalReviews = count($reviews);
$avgRating = $totalReviews > 0 ? round(array_sum(array_column($reviews, 'rating')) / $totalReviews, 1) : 0;
$positiveReviews = count(array_filter($reviews, fn($r) => $r['rating'] >= 4));

// Lấy danh sách xe để thêm review
$cars = $carModel->getAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đánh giá - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-reviews.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>
<body>
    <?php $activePage = 'reviews'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Quản lý đánh giá</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Danh sách đánh giá (<?= $totalReviews ?>)</h2>
            </div>

            <!-- Review Stats -->
            <div class="review-stats">
                <div class="review-stat">
                    <div class="review-stat-icon total"><i class="fas fa-comments"></i></div>
                    <div class="review-stat-info">
                        <h3><?= $totalReviews ?></h3>
                        <p>Tổng đánh giá</p>
                    </div>
                </div>
                <div class="review-stat">
                    <div class="review-stat-icon avg"><i class="fas fa-star"></i></div>
                    <div class="review-stat-info">
                        <h3><?= $avgRating ?></h3>
                        <p>Điểm trung bình</p>
                    </div>
                </div>
                <div class="review-stat">
                    <div class="review-stat-icon positive"><i class="fas fa-thumbs-up"></i></div>
                    <div class="review-stat-info">
                        <h3><?= $positiveReviews ?></h3>
                        <p>Đánh giá tích cực</p>
                    </div>
                </div>
                <div class="review-stat">
                    <div class="review-stat-icon pending"><i class="fas fa-clock"></i></div>
                    <div class="review-stat-info">
                        <h3><?= $totalReviews - $positiveReviews ?></h3>
                        <p>Đánh giá thấp</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label>Đánh giá:</label>
                    <select id="filterRating">
                        <option value="">Tất cả</option>
                        <option value="5">5 sao</option>
                        <option value="4">4 sao</option>
                        <option value="3">3 sao</option>
                        <option value="2">2 sao</option>
                        <option value="1">1 sao</option>
                    </select>
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchReview" placeholder="Tìm theo tên khách hàng, nội dung...">
                </div>
            </div>

            <?php if ($totalReviews === 0): ?>
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-comment-slash"></i>
                </div>
                <h3>Chưa có đánh giá nào</h3>
                <p>Hiện tại chưa có khách hàng nào gửi đánh giá. Các đánh giá sẽ xuất hiện ở đây khi khách hàng đánh giá xe.</p>
            </div>
            <?php else: ?>
            <!-- Reviews List -->
            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                <div class="review-card" data-rating="<?= $review['rating'] ?>">
                    <div class="review-header">
                        <div class="review-user">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($review['user_name'] ?? 'User') ?>&background=D4AF37&color=fff" alt="">
                            <div class="review-user-info">
                                <h4><?= htmlspecialchars($review['user_name'] ?? 'Khách hàng') ?></h4>
                                <p><?= htmlspecialchars($review['user_email'] ?? '') ?></p>
                            </div>
                        </div>
                        <div class="review-meta">
                            <div class="review-date"><?= date('d/m/Y - H:i', strtotime($review['created_at'])) ?></div>
                        </div>
                    </div>
                    <div class="review-car">
                        <img src="<?= $review['car_image'] ?? 'https://via.placeholder.com/150' ?>" alt="">
                        <div class="review-car-info">
                            <h5><?= htmlspecialchars($review['car_name'] ?? 'Xe') ?></h5>
                            <p><?= htmlspecialchars($review['brand_name'] ?? '') ?></p>
                        </div>
                    </div>
                    <div class="review-rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= $review['rating'] ? '' : 'empty' ?>"></i>
                        <?php endfor; ?>
                    </div>
                    <p class="review-content"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
                    <div class="review-actions">
                        <button class="action-btn delete" onclick="confirmDelete(<?= $review['id'] ?>, '<?= addslashes($review['user_name'] ?? 'Khách hàng') ?>')" title="Xóa">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Modal Xác Nhận Xóa -->
    <div class="modal" id="deleteModal">
        <div class="modal-content modal-small">
            <div class="modal-header">
                <h2><i class="fas fa-exclamation-triangle"></i> Xác nhận xóa</h2>
                <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="delete-warning">
                    <i class="fas fa-trash-alt"></i>
                    <p>Bạn có chắc chắn muốn xóa đánh giá của <strong id="deleteReviewUser"></strong>?</p>
                    <span class="warning-text">Hành động này không thể hoàn tác!</span>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <form id="deleteForm" method="POST" action="<?= BASE_URL ?>/admin/reviews/delete" style="display: inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="review_id" id="deleteReviewId">
                    <button type="submit" class="btn-danger">
                        <i class="fas fa-trash"></i> Xóa đánh giá
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

    <script>
        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
            document.body.style.overflow = '';
        }

        function confirmDelete(id, userName) {
            document.getElementById('deleteReviewId').value = id;
            document.getElementById('deleteReviewUser').textContent = userName;
            openModal('deleteModal');
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(this.id);
                }
            });
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    closeModal(modal.id);
                });
            }
        });

        // Filter by rating
        document.getElementById('filterRating').addEventListener('change', function() {
            const rating = this.value;
            const cards = document.querySelectorAll('.review-card');
            cards.forEach(card => {
                if (!rating || card.dataset.rating === rating) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Search
        document.getElementById('searchReview').addEventListener('input', function() {
            const search = this.value.toLowerCase();
            const cards = document.querySelectorAll('.review-card');
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                card.style.display = text.includes(search) ? 'block' : 'none';
            });
        });

        // Toast notification
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = 'toast ' + type + ' show';
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        <?php if (isset($_GET['success'])): ?>
        showToast('<?= htmlspecialchars($_GET['success']) ?>');
        <?php endif; ?>

        <?php if (isset($_GET['error'])): ?>
        showToast('<?= htmlspecialchars($_GET['error']) ?>', 'error');
        <?php endif; ?>
    </script>
</body>
</html>
