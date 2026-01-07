<?php
if (!defined('BASE_URL')) {
    require_once __DIR__ . '/../../../../config/config.php';
}

// Lấy thông báo từ session
$message = $_SESSION['success'] ?? $_SESSION['error'] ?? '';
$messageType = isset($_SESSION['success']) ? 'success' : 'error';

// Xóa thông báo sau khi hiển thị
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-stats.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-categories.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>

<body>
    <?php $activePage = 'categories';
    include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <div>
                <h1>Quản lý danh mục</h1>
                <p style="font-size: 13px; color: var(--gray-500); margin: 6px 0 0 0; font-weight: 500;">Phân loại dòng xe theo từng danh mục</p>
            </div>
            <div class="header-right">
                <button class="btn btn-primary btn-sm" onclick="openAddModal()" style="gap: 8px;">
                    <i class="fas fa-plus"></i> Thêm danh mục
                </button>
                <div class="header-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
                </div>
            </div>
        </header>

        <div class="admin-content">
            <!-- Stats -->
            <div class="stats-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); margin-bottom: 30px;">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fas fa-tags"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalCategories ?></h3>
                        <p>Danh mục</p>
                        <span class="stat-detail"><i class="fas fa-layer-group"></i> Đang hoạt động</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-car"></i></div>
                    <div class="stat-info">
                        <h3><?= array_sum(array_column($categories, 'car_count')) ?></h3>
                        <p>Tổng xe</p>
                        <span class="stat-detail"><i class="fas fa-check-circle"></i> Tất cả danh mục</span>
                    </div>
                </div>
            </div>

            <?php if ($totalCategories === 0): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3>Chưa có danh mục nào</h3>
                    <p>Bắt đầu thêm danh mục để phân loại các dòng xe.</p>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i> Thêm danh mục đầu tiên
                    </button>
                </div>
            <?php else: ?>
                <!-- Categories Table -->
                <div class="card">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">Icon</th>
                                <th>Tên danh mỦc</th>
                                <th>Mô tả</th>
                                <th style="width: 120px; text-align: center;">Số xe</th>
                                <th style="width: 120px; text-align: center;">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                                <tr>
                                    <td>
                                        <div class="table-icon">
                                            <i class="fas fa-tags"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="table-category-name"><?= htmlspecialchars($cat['name']) ?></div>
                                    </td>
                                    <td>
                                        <div class="table-desc"><?= htmlspecialchars($cat['description'] ?? 'Chưa có mô tả') ?></div>
                                    </td>
                                    <td style="text-align: center;">
                                        <span class="table-badge"><?= $cat['car_count'] ?> xe</span>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <button class="table-action edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($cat)) ?>)" title="Chỉnh sửa">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="table-action delete" onclick="openDeleteModal(<?= $cat['id'] ?>, '<?= addslashes($cat['name']) ?>')" title="Xóa">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
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

    <!-- Add/Edit Modal -->
    <div class="modal" id="categoryModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fas fa-layer-group"></i> Thêm danh mục</h3>
                <button class="modal-close" onclick="closeModal('categoryModal')">&times;</button>
            </div>
            <form id="categoryForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="categoryId">

                <div class="modal-body">
                    <div class="form-group">
                        <label>Tên danh mục <span class="required">*</span></label>
                        <input type="text" name="name" id="categoryName" required placeholder="VD: Sedan, SUV, Coupe...">
                    </div>

                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" id="categoryDescription" rows="5" placeholder="Mô tả chi tiết về danh mục xe..."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('categoryModal')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Lưu danh mục
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content modal-confirm">
            <div class="modal-body">
                <div class="confirm-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <h4>Xác nhận xóa?</h4>
                <p>Bạn có chắc chắn muốn xóa danh mục</p>
                <p class="item-name" id="deleteName"></p>
                <p style="color: #999; font-size: 0.85rem;">Hành động này không thể hoàn tác</p>
            </div>
            <form method="POST" action="/admin/categories/delete" class="form-actions">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" id="deleteId">
                <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="submit" class="btn-danger">
                    <i class="fas fa-trash"></i> Xóa
                </button>
            </form>
        </div>
    </div>

    <!-- Toast Notification -->
    <?php if ($message): ?>
        <div class="toast <?= $messageType ?>" id="toast">
            <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
            <span><?= htmlspecialchars($message) ?></span>
        </div>
    <?php endif; ?>

    <script>
        // Show toast notification
        <?php if ($message): ?>
            document.addEventListener('DOMContentLoaded', function() {
                const toast = document.getElementById('toast');
                setTimeout(() => toast.classList.add('show'), 100);
                setTimeout(() => toast.classList.remove('show'), 4000);
            });
        <?php endif; ?>

        // Open Add Modal
        function openAddModal() {
            const form = document.getElementById('categoryForm');
            form.action = '/admin/categories/add';
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Thêm danh mục';
            document.getElementById('formAction').value = 'add';
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryName').value = '';
            document.getElementById('categoryDescription').value = '';
            document.getElementById('categoryModal').classList.add('active');
        }

        // Open Edit Modal
        function openEditModal(category) {
            const form = document.getElementById('categoryForm');
            form.action = '/admin/categories/edit';
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Sửa danh mục';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('categoryId').value = category.id;
            document.getElementById('categoryName').value = category.name;
            document.getElementById('categoryDescription').value = category.description || '';
            document.getElementById('categoryModal').classList.add('active');
        }

        // Open Delete Modal
        function openDeleteModal(id, name) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteName').textContent = name;
            document.getElementById('deleteModal').classList.add('active');
        }

        // Close Modal
        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Close modal when clicking outside
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.remove('active');
                }
            });
        });

        // ESC to close modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal.active').forEach(m => m.classList.remove('active'));
            }
        });
    </script>
</body>

</html>