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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-stats.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-reviews.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>
<body>
    <?php $activePage = 'reviews'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <div>
                <h1>Quản lý đánh giá</h1>
                <p style="font-size: 13px; color: var(--gray-500); margin: 6px 0 0 0; font-weight: 500;">Theo dõi và quản lý phản hồi từ khách hàng</p>
            </div>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <div class="page-header-content">
                    <h2>Danh sách đánh giá (<?= $totalReviews ?>)</h2>
                    <p class="page-subtitle">Quản lý tất cả đánh giá của khách hàng về các dòng xe</p>
                </div>
            </div>

            <!-- Review Stats -->
            <div class="stats-grid" style="grid-template-columns: repeat(4, 1fr); margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-comments"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalReviews ?></h3>
                        <p>Tổng đánh giá</p>
                        <span class="stat-detail"><i class="fas fa-list"></i> Tất cả</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon gold"><i class="fas fa-star"></i></div>
                    <div class="stat-info">
                        <h3><?= $avgRating ?></h3>
                        <p>Điểm trung bình</p>
                        <span class="stat-detail"><i class="fas fa-chart-line"></i> Trên 5 sao</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon green"><i class="fas fa-thumbs-up"></i></div>
                    <div class="stat-info">
                        <h3><?= $positiveReviews ?></h3>
                        <p>Đánh giá tích cực</p>
                        <span class="stat-detail"><i class="fas fa-smile"></i> ≥ 4 sao</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon orange"><i class="fas fa-exclamation-triangle"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalReviews - $positiveReviews ?></h3>
                        <p>Đánh giá thấp</p>
                        <span class="stat-detail"><i class="fas fa-frown"></i> < 4 sao</span>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label><i class="fas fa-star"></i> Đánh giá</label>
                    <select id="filterRating">
                        <option value="">Tất cả đánh giá</option>
                        <option value="5">⭐⭐⭐⭐⭐ 5 sao</option>
                        <option value="4">⭐⭐⭐⭐ 4 sao</option>
                        <option value="3">⭐⭐⭐ 3 sao</option>
                        <option value="2">⭐⭐ 2 sao</option>
                        <option value="1">⭐ 1 sao</option>
                    </select>
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchReview" placeholder="Tìm theo khách hàng, xe, nội dung...">
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
            <!-- Reviews Table -->
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Mã ĐG</th>
                            <th>Khách hàng</th>
                            <th>Xe</th>
                            <th style="width: 140px;">Đánh giá</th>
                            <th style="width: 130px;">Ngày đánh giá</th>
                            <th style="width: 130px; text-align: center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review): ?>
                        <tr data-rating="<?= $review['rating'] ?>">
                            <td><span class="order-id">#<?= str_pad($review['id'], 4, '0', STR_PAD_LEFT) ?></span></td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <strong style="font-size: 14px; color: var(--gray-900);"><?= htmlspecialchars($review['user_name'] ?? 'Khách hàng') ?></strong>
                                    <span style="font-size: 12px; color: var(--gray-500);"><?= htmlspecialchars($review['user_email'] ?? '') ?></span>
                                </div>
                            </td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <a href="<?= BASE_URL ?>/car/detail/<?= $review['car_id'] ?>" target="_blank" style="font-size: 14px; color: var(--primary); font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; white-space: nowrap;" title="Xem chi tiết xe">
                                        <span style="overflow: hidden; text-overflow: ellipsis; max-width: 180px;"><?= htmlspecialchars($review['car_name'] ?? 'Xe') ?></span>
                                        <i class="fas fa-external-link-alt" style="font-size: 10px; flex-shrink: 0;"></i>
                                    </a>
                                    <span style="font-size: 12px; color: var(--gray-500);"><?= htmlspecialchars($review['brand_name'] ?? '') ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="review-rating" style="display: flex; gap: 3px; font-size: 14px;">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star" style="color: <?= $i <= $review['rating'] ? '#f59e0b' : '#e5e7eb' ?>;"></i>
                                    <?php endfor; ?>
                                    <span style="margin-left: 6px; font-weight: 600; color: var(--gray-900);"><?= $review['rating'] ?>.0</span>
                                </div>
                            </td>
                            <td>
                                <span style="font-size: 13px; color: var(--gray-600);"><?= date('d/m/Y', strtotime($review['created_at'])) ?></span>
                                <br>
                                <span style="font-size: 12px; color: var(--gray-400);"><?= date('H:i', strtotime($review['created_at'])) ?></span>
                            </td>
                            <td>
                                <div class="table-actions" style="display: flex; gap: 6px; justify-content: center;">
                                    <button class="action-btn" onclick="toggleReviewDetail(<?= $review['id'] ?>)" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="action-btn" onclick="toggleReplyForm(<?= $review['id'] ?>)" title="Trả lời đánh giá">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                    <button class="action-btn" onclick='confirmDelete(<?= $review["id"] ?>, <?= json_encode($review["user_name"] ?? "Khách hàng") ?>)' title="Xóa đánh giá">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <!-- Expandable Detail Row -->
                        <tr id="detail-<?= $review['id'] ?>" class="expandable-row" style="display: none;">
                            <td colspan="6" style="padding: 0;">
                                <div style="background: linear-gradient(135deg, #fafbfc 0%, #ffffff 100%); padding: 32px; margin: 8px 16px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid rgba(212, 175, 55, 0.2);">
                                    <!-- Header Section -->
                                    <div style="display: flex; align-items: center; gap: 16px; padding-bottom: 24px; border-bottom: 2px solid #f3f4f6; margin-bottom: 24px;">
                                        <div style="width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, #D4AF37, #B8960B); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);">
                                            <i class="fas fa-user-circle" style="font-size: 36px; color: white;"></i>
                                        </div>
                                        <div style="flex: 1;">
                                            <strong style="font-size: 18px; color: #1f2937; display: block; margin-bottom: 6px; font-weight: 700;"><?= htmlspecialchars($review['user_name'] ?? 'Khách hàng') ?></strong>
                                            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                                                <span style="font-size: 14px; color: #6b7280; display: inline-flex; align-items: center; gap: 6px;">
                                                    <i class="fas fa-car" style="color: #D4AF37;"></i>
                                                    <strong style="color: #374151;"><?= htmlspecialchars($review['car_name'] ?? 'Xe') ?></strong>
                                                </span>
                                                <span style="font-size: 13px; color: #9ca3af; display: inline-flex; align-items: center; gap: 6px;">
                                                    <i class="far fa-clock"></i>
                                                    <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Rating Section -->
                                    <div style="text-align: center; padding: 28px; background: linear-gradient(135deg, #ffffff 0%, #fefcf7 100%); border-radius: 16px; margin-bottom: 24px; box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.04); border: 2px solid #f9fafb;">
                                        <div style="display: inline-flex; gap: 10px; font-size: 32px; margin-bottom: 12px;">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star" style="color: <?= $i <= $review['rating'] ? '#f59e0b' : '#e5e7eb' ?>; transition: all 0.3s ease; filter: drop-shadow(0 2px 4px rgba(245, 158, 11, 0.3));"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <div style="font-size: 20px; font-weight: 700; color: #1f2937; margin-bottom: 4px;"><?= $review['rating'] ?>.0<span style="font-size: 16px; color: #9ca3af; font-weight: 600;">/5.0</span></div>
                                        <div style="font-size: 13px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Đánh giá của khách hàng</div>
                                    </div>
                                    
                                    <!-- Comment Section -->
                                    <div>
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                            <div style="width: 4px; height: 20px; background: linear-gradient(135deg, #D4AF37, #B8960B); border-radius: 2px;"></div>
                                            <label style="font-size: 15px; font-weight: 700; color: #374151; margin: 0;">
                                                <i class="fas fa-comment-alt" style="color: #D4AF37; margin-right: 6px;"></i> Nội dung đánh giá
                                            </label>
                                        </div>
                                        <div style="padding: 20px 24px; background: white; border-radius: 12px; border: 2px solid #f3f4f6; position: relative; overflow: hidden;">
                                            <div style="position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: linear-gradient(135deg, #D4AF37, #B8960B);"></div>
                                            <p style="margin: 0; font-size: 15px; color: #374151; line-height: 1.8; white-space: pre-wrap;"><?= htmlspecialchars($review['comment']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Expandable Reply Row -->
                        <tr id="reply-<?= $review['id'] ?>" class="expandable-row" style="display: none;">
                            <td colspan="6" style="padding: 0;">
                                <div style="background: linear-gradient(135deg, #fafbfc 0%, #ffffff 100%); padding: 32px; margin: 8px 16px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08); border: 1px solid rgba(212, 175, 55, 0.2);">
                                    <form method="POST" action="<?= BASE_URL ?>/admin/reviews/reply">
                                        <!-- Reply Header -->
                                        <div style="display: flex; align-items: center; gap: 16px; padding: 20px 24px; background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%); border-radius: 12px; margin-bottom: 24px; border: 2px solid #d1fae5;">
                                            <div style="width: 48px; height: 48px; border-radius: 12px; background: linear-gradient(135deg, #10b981, #059669); display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                                                <i class="fas fa-reply" style="font-size: 20px; color: white;"></i>
                                            </div>
                                            <div style="flex: 1;">
                                                <div style="font-size: 16px; font-weight: 700; color: #1f2937; margin-bottom: 4px;">
                                                    Trả lời đánh giá của <span style="color: #059669;"><?= htmlspecialchars($review['user_name'] ?? 'Khách hàng') ?></span>
                                                </div>
                                                <div style="font-size: 13px; color: #6b7280;">
                                                    <i class="fas fa-car" style="color: #10b981; margin-right: 4px;"></i>
                                                    Đánh giá về: <strong style="color: #374151;"><?= htmlspecialchars($review['car_name'] ?? 'Xe') ?></strong>
                                                    <span style="margin: 0 8px; color: #d1d5db;">•</span>
                                                    <i class="fas fa-star" style="color: #f59e0b; margin-right: 4px;"></i>
                                                    <strong style="color: #374151;"><?= $review['rating'] ?>.0/5.0</strong>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                                        
                                        <!-- Form Group -->
                                        <div style="margin-bottom: 24px;">
                                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                                                <div style="width: 4px; height: 20px; background: linear-gradient(135deg, #D4AF37, #B8960B); border-radius: 2px;"></div>
                                                <label for="replyContent-<?= $review['id'] ?>" style="font-size: 15px; font-weight: 700; color: #374151; margin: 0;">
                                                    <i class="fas fa-comment-dots" style="color: #D4AF37; margin-right: 6px;"></i> Nội dung trả lời
                                                </label>
                                            </div>
                                            <textarea 
                                                id="replyContent-<?= $review['id'] ?>" 
                                                name="reply_content" 
                                                rows="6" 
                                                required 
                                                placeholder="Nhập nội dung trả lời cho khách hàng...&#10;&#10;Ví dụ: Cảm ơn quý khách đã tin tưởng và đánh giá cao dịch vụ của AutoCar. Chúng tôi rất vui khi..." 
                                                style="width: 100%; padding: 16px 20px; border: 2px solid #e5e7eb; border-radius: 12px; font-size: 14px; font-family: inherit; resize: vertical; transition: all 0.3s ease; line-height: 1.6;"
                                                onfocus="this.style.borderColor='#D4AF37'; this.style.boxShadow='0 0 0 3px rgba(212, 175, 55, 0.1)';" 
                                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                                            ></textarea>
                                            <div style="margin-top: 10px; padding: 12px 16px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-radius: 8px; border-left: 3px solid #3b82f6;">
                                                <small style="color: #1e40af; font-size: 13px; display: flex; align-items: center; gap: 8px; font-weight: 500;">
                                                    <i class="fas fa-info-circle" style="font-size: 16px;"></i>
                                                    <span>Trả lời của bạn sẽ được gửi qua email đến <strong><?= htmlspecialchars($review['user_email'] ?? 'khách hàng') ?></strong></span>
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <!-- Form Actions -->
                                        <div style="display: flex; gap: 12px; justify-content: flex-end; padding-top: 20px; border-top: 2px solid #f3f4f6;">
                                            <button type="button" class="btn-secondary" onclick="toggleReplyForm(<?= $review['id'] ?>)" style="padding: 12px 24px; border: 2px solid #e5e7eb; background: white; border-radius: 10px; font-weight: 600; font-size: 14px; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; color: #6b7280;" onmouseover="this.style.borderColor='#d1d5db'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(0, 0, 0, 0.1)';" onmouseout="this.style.borderColor='#e5e7eb'; this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                                <i class="fas fa-times"></i> Hủy
                                            </button>
                                            <button type="submit" style="padding: 12px 28px; border: none; background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(16, 185, 129, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(16, 185, 129, 0.3)';">
                                                <i class="fas fa-paper-plane"></i> Gửi trả lời
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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

        function toggleReviewDetail(reviewId) {
            const detailRow = document.getElementById('detail-' + reviewId);
            const replyRow = document.getElementById('reply-' + reviewId);
            
            // Close reply form if open
            if (replyRow.style.display !== 'none') {
                replyRow.style.display = 'none';
            }
            
            // Toggle detail row
            if (detailRow.style.display === 'none') {
                // Close all other detail rows
                document.querySelectorAll('.expandable-row').forEach(row => {
                    row.style.display = 'none';
                });
                detailRow.style.display = 'table-row';
            } else {
                detailRow.style.display = 'none';
            }
        }

        function toggleReplyForm(reviewId) {
            const replyRow = document.getElementById('reply-' + reviewId);
            const detailRow = document.getElementById('detail-' + reviewId);
            
            // Close detail view if open
            if (detailRow.style.display !== 'none') {
                detailRow.style.display = 'none';
            }
            
            // Toggle reply form
            if (replyRow.style.display === 'none') {
                // Close all other expandable rows
                document.querySelectorAll('.expandable-row').forEach(row => {
                    row.style.display = 'none';
                });
                replyRow.style.display = 'table-row';
            } else {
                replyRow.style.display = 'none';
            }
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
