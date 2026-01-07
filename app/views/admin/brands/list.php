<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; }

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
    <title>Quản lý thương hiệu - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-stats.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-brands.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>
<body>
    <?php $activePage = 'brands'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <div>
                <h1>Quản lý thương hiệu</h1>
                <p style="font-size: 13px; color: var(--gray-500); margin: 6px 0 0 0; font-weight: 500;">Quản lý các thương hiệu xe tại AutoCar</p>
            </div>
            <div class="header-right">
                <button class="btn btn-primary btn-sm" onclick="openAddModal()" style="gap: 8px;">
                    <i class="fas fa-plus"></i> Thêm thương hiệu
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
                    <div class="stat-icon gold"><i class="fas fa-copyright"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalBrands ?></h3>
                        <p>Thương hiệu</p>
                        <span class="stat-detail"><i class="fas fa-building"></i> Đang hoạt động</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-car"></i></div>
                    <div class="stat-info">
                        <h3><?= array_sum(array_column($brands, 'car_count')) ?></h3>
                        <p>Tổng xe</p>
                        <span class="stat-detail"><i class="fas fa-layer-group"></i> Tất cả thương hiệu</span>
                    </div>
                </div>
            </div>

            <?php if ($totalBrands === 0): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-copyright"></i>
                </div>
                <h3>Chưa có thương hiệu nào</h3>
                <p>Bắt đầu thêm thương hiệu xe để quản lý danh mục sản phẩm.</p>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Thêm thương hiệu đầu tiên
                </button>
            </div>
            <?php else: ?>
            <!-- Brands Table -->
            <div class="card">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;">Logo</th>
                            <th>Tên thương hiệu</th>
                            <th>Mô tả</th>
                            <th style="width: 120px; text-align: center;">Số xe</th>
                            <th style="width: 120px; text-align: center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($brands as $brand): ?>
                        <tr>
                            <td>
                                <div class="table-logo">
                                    <img src="<?= $brand['logo'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($brand['name']) . '&background=D4AF37&color=fff&size=60' ?>" alt="<?= htmlspecialchars($brand['name']) ?>">
                                </div>
                            </td>
                            <td>
                                <div class="table-brand-name"><?= htmlspecialchars($brand['name']) ?></div>
                            </td>
                            <td>
                                <div class="table-desc"><?= htmlspecialchars($brand['description'] ?? 'Chưa có mô tả') ?></div>
                            </td>
                            <td style="text-align: center;">
                                <span class="table-badge"><?= $brand['car_count'] ?> xe</span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button class="table-action edit" onclick="openEditModal(<?= htmlspecialchars(json_encode($brand)) ?>)" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="table-action delete" onclick="openDeleteModal(<?= $brand['id'] ?>, '<?= addslashes($brand['name']) ?>')" title="Xóa">
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
    <div class="modal" id="brandModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle"><i class="fas fa-copyright"></i> Thêm thương hiệu</h3>
                <button class="modal-close" onclick="closeModal('brandModal')">&times;</button>
            </div>
            <form id="brandForm" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="id" id="brandId">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tên thương hiệu <span class="required">*</span></label>
                        <input type="text" name="name" id="brandName" required placeholder="VD: Mercedes-Benz, BMW, Audi...">
                    </div>
                    
                    <!-- Image Upload Section -->
                    <div class="image-upload-section">
                        <label>Logo thương hiệu</label>
                        
                        <div class="upload-tabs">
                            <button type="button" class="upload-tab active" onclick="switchUploadTab('file')">
                                <i class="fas fa-upload"></i> Tải từ máy
                            </button>
                            <button type="button" class="upload-tab" onclick="switchUploadTab('url')">
                                <i class="fas fa-link"></i> Dùng link
                            </button>
                        </div>
                        
                        <div class="upload-panel active" id="filePanel">
                            <div class="file-upload-area" id="dropZone">
                                <input type="file" name="logo_file" id="logoFile" accept="image/*">
                                <div class="upload-icon">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                </div>
                                <h4>Kéo thả ảnh vào đây</h4>
                                <p>hoặc click để chọn file</p>
                                <span class="browse-btn">Chọn ảnh</span>
                            </div>
                        </div>
                        
                        <div class="upload-panel" id="urlPanel">
                            <div class="url-input-wrapper">
                                <input type="url" name="logo_url" id="logoUrl" placeholder="https://example.com/logo.png">
                                <i class="fas fa-link"></i>
                            </div>
                            <p class="form-info"><i class="fas fa-info-circle"></i> Nhập URL hình ảnh từ internet</p>
                        </div>
                        
                        <div class="image-preview-container" id="imagePreview" style="display: none;">
                            <div class="image-preview-wrapper">
                                <img id="previewImg" src="" alt="Preview">
                                <div class="preview-actions">
                                    <button type="button" class="preview-action-btn view" onclick="viewImage()">
                                        <i class="fas fa-expand"></i>
                                    </button>
                                    <button type="button" class="preview-action-btn remove" onclick="removeImage()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea name="description" id="brandDescription" rows="3" placeholder="Mô tả ngắn về thương hiệu..."></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="closeModal('brandModal')">
                        <i class="fas fa-times"></i> Hủy
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Lưu thương hiệu
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
                <p>Bạn có chắc chắn muốn xóa thương hiệu</p>
                <p class="item-name" id="deleteName"></p>
                <p style="color: #999; font-size: 0.85rem;">Hành động này không thể hoàn tác</p>
            </div>
            <form method="POST" action="/admin/brands/delete" class="form-actions">
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
            const form = document.getElementById('brandForm');
            form.action = '/admin/brands/add';
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Thêm thương hiệu';
            document.getElementById('formAction').value = 'add';
            document.getElementById('brandId').value = '';
            document.getElementById('brandName').value = '';
            document.getElementById('brandDescription').value = '';
            document.getElementById('logoUrl').value = '';
            document.getElementById('logoFile').value = '';
            removeImage();
            document.getElementById('brandModal').classList.add('active');
        }

        // Open Edit Modal
        function openEditModal(brand) {
            const form = document.getElementById('brandForm');
            form.action = '/admin/brands/edit';
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Sửa thương hiệu';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('brandId').value = brand.id;
            document.getElementById('brandName').value = brand.name;
            document.getElementById('brandDescription').value = brand.description || '';
            
            if (brand.logo) {
                document.getElementById('logoUrl').value = brand.logo;
                document.getElementById('previewImg').src = brand.logo;
                document.getElementById('imagePreview').style.display = 'block';
            } else {
                removeImage();
            }
            
            document.getElementById('brandModal').classList.add('active');
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

        // Switch Upload Tab
        function switchUploadTab(tab) {
            document.querySelectorAll('.upload-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.upload-panel').forEach(p => p.classList.remove('active'));
            
            if (tab === 'file') {
                document.querySelector('.upload-tab:first-child').classList.add('active');
                document.getElementById('filePanel').classList.add('active');
            } else {
                document.querySelector('.upload-tab:last-child').classList.add('active');
                document.getElementById('urlPanel').classList.add('active');
            }
        }

        // File Upload Preview
        const logoFile = document.getElementById('logoFile');
        const dropZone = document.getElementById('dropZone');
        
        logoFile.addEventListener('change', function(e) {
            if (e.target.files[0]) {
                previewFile(e.target.files[0]);
            }
        });

        // Drag & Drop
        dropZone.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files[0] && files[0].type.startsWith('image/')) {
                logoFile.files = files;
                previewFile(files[0]);
            }
        });

        // Preview File
        function previewFile(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }

        // URL Input Preview
        document.getElementById('logoUrl').addEventListener('input', function(e) {
            if (e.target.value) {
                document.getElementById('previewImg').src = e.target.value;
                document.getElementById('imagePreview').style.display = 'block';
            }
        });

        // View Image Full
        function viewImage() {
            const src = document.getElementById('previewImg').src;
            window.open(src, '_blank');
        }

        // Remove Image
        function removeImage() {
            document.getElementById('previewImg').src = '';
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('logoFile').value = '';
            document.getElementById('logoUrl').value = '';
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
