<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; }

require_once __DIR__ . '/../../../models/CategoryModel.php';
require_once __DIR__ . '/../../../models/CarModel.php';

$categoryModel = new CategoryModel();
$carModel = new CarModel();

$categories = $categoryModel->getAll();
$totalCategories = count($categories);

// Đếm số xe theo category
foreach ($categories as &$cat) {
    $cat['car_count'] = $carModel->countByCategory($cat['id']);
}

// Icons for categories
$icons = [
    'Sedan' => 'fa-car-side',
    'SUV' => 'fa-truck-monster', 
    'Coupe' => 'fa-car-alt',
    'Convertible' => 'fa-car',
    'Sports Car' => 'fa-flag-checkered',
    'Supercar' => 'fa-rocket',
    'Crossover' => 'fa-car-rear'
];

// Xử lý form
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add' || $action === 'edit') {
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $icon = trim($_POST['icon'] ?? 'fa-car');
        $image = '';
        
        // Xử lý image - ưu tiên file upload
        if (!empty($_FILES['image_file']['name'])) {
            $uploadDir = BASE_PATH . '/assets/images/categories/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileName = time() . '_' . basename($_FILES['image_file']['name']);
            $targetFile = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetFile)) {
                $image = BASE_URL . '/assets/images/categories/' . $fileName;
            }
        } elseif (!empty($_POST['image_url'])) {
            $image = trim($_POST['image_url']);
        }
        
        if (empty($name)) {
            $message = 'Vui lòng nhập tên danh mục';
            $messageType = 'error';
        } else {
            if ($action === 'add') {
                $categoryModel->create([
                    'name' => $name,
                    'description' => $description,
                    'icon' => $icon,
                    'image' => $image
                ]);
                $message = 'Thêm danh mục thành công!';
                $messageType = 'success';
            } else {
                $id = intval($_POST['id']);
                $data = [
                    'name' => $name,
                    'description' => $description,
                    'icon' => $icon
                ];
                if (!empty($image)) {
                    $data['image'] = $image;
                }
                $categoryModel->update($id, $data);
                $message = 'Cập nhật danh mục thành công!';
                $messageType = 'success';
            }
            
            // Refresh data
            $categories = $categoryModel->getAll();
            $totalCategories = count($categories);
            foreach ($categories as &$cat) {
                $cat['car_count'] = $carModel->countByCategory($cat['id']);
            }
        }
    } elseif ($action === 'delete') {
        $id = intval($_POST['id']);
        $categoryModel->delete($id);
        $message = 'Xóa danh mục thành công!';
        $messageType = 'success';
        
        // Refresh data
        $categories = $categoryModel->getAll();
        $totalCategories = count($categories);
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-categories.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>
<body>
    <?php $activePage = 'categories'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Quản lý danh mục</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Danh sách danh mục (<?= $totalCategories ?>)</h2>
                <button class="btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Thêm danh mục
                </button>
            </div>

            <!-- Stats -->
            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-icon purple"><i class="fas fa-layer-group"></i></div>
                    <div class="stat-info">
                        <h3><?= $totalCategories ?></h3>
                        <p>Danh mục</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon blue"><i class="fas fa-car"></i></div>
                    <div class="stat-info">
                        <h3><?= array_sum(array_column($categories, 'car_count')) ?></h3>
                        <p>Tổng xe</p>
                    </div>
                </div>
            </div>

            <?php if ($totalCategories === 0): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-layer-group"></i>
                </div>
                <h3>Chưa có danh mục nào</h3>
                <p>Bắt đầu thêm danh mục để phân loại các dòng xe.</p>
                <button class="btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Thêm danh mục đầu tiên
                </button>
            </div>
            <?php else: ?>
            <!-- Categories Grid -->
            <div class="categories-grid">
                <?php foreach ($categories as $cat): ?>
                <div class="category-card">
                    <div class="category-content">
                        <?php if (!empty($cat['image'])): ?>
                        <div class="category-image">
                            <img src="<?= $cat['image'] ?>" alt="<?= htmlspecialchars($cat['name']) ?>">
                        </div>
                        <?php else: ?>
                        <div class="category-icon">
                            <i class="fas <?= $cat['icon'] ?? $icons[$cat['name']] ?? 'fa-car' ?>"></i>
                        </div>
                        <?php endif; ?>
                        <h3 class="category-name"><?= htmlspecialchars($cat['name']) ?></h3>
                        <p class="category-desc"><?= htmlspecialchars($cat['description'] ?? 'Chưa có mô tả') ?></p>
                        <div class="category-stats">
                            <span class="category-stat">
                                <i class="fas fa-car"></i> <?= $cat['car_count'] ?> xe
                            </span>
                        </div>
                        <div class="category-actions">
                            <button class="category-action" onclick="openEditModal(<?= htmlspecialchars(json_encode($cat)) ?>)">
                                <i class="fas fa-edit"></i> Sửa
                            </button>
                            <button class="category-action delete" onclick="openDeleteModal(<?= $cat['id'] ?>, '<?= addslashes($cat['name']) ?>')">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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
                        <label>Icon</label>
                        <select name="icon" id="categoryIcon" class="form-control">
                            <option value="fa-car">🚗 Car</option>
                            <option value="fa-car-side">🚙 Car Side</option>
                            <option value="fa-car-alt">🏎️ Car Alt</option>
                            <option value="fa-truck-monster">🚙 SUV/Truck</option>
                            <option value="fa-flag-checkered">🏁 Sports</option>
                            <option value="fa-rocket">🚀 Supercar</option>
                            <option value="fa-car-rear">🚘 Crossover</option>
                        </select>
                    </div>
                    
                    <!-- Image Upload Section -->
                    <div class="image-upload-section">
                        <label>Hình ảnh danh mục (tùy chọn)</label>
                        
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
                                <input type="file" name="image_file" id="imageFile" accept="image/*">
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
                                <input type="url" name="image_url" id="imageUrl" placeholder="https://example.com/image.jpg">
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
                        <textarea name="description" id="categoryDescription" rows="3" placeholder="Mô tả ngắn về danh mục xe..."></textarea>
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
            <form method="POST" class="form-actions">
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
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus-circle"></i> Thêm danh mục';
            document.getElementById('formAction').value = 'add';
            document.getElementById('categoryId').value = '';
            document.getElementById('categoryName').value = '';
            document.getElementById('categoryDescription').value = '';
            document.getElementById('categoryIcon').value = 'fa-car';
            document.getElementById('imageUrl').value = '';
            document.getElementById('imageFile').value = '';
            removeImage();
            document.getElementById('categoryModal').classList.add('active');
        }

        // Open Edit Modal
        function openEditModal(category) {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Sửa danh mục';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('categoryId').value = category.id;
            document.getElementById('categoryName').value = category.name;
            document.getElementById('categoryDescription').value = category.description || '';
            document.getElementById('categoryIcon').value = category.icon || 'fa-car';
            
            if (category.image) {
                document.getElementById('imageUrl').value = category.image;
                document.getElementById('previewImg').src = category.image;
                document.getElementById('imagePreview').style.display = 'block';
            } else {
                removeImage();
            }
            
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
        const imageFile = document.getElementById('imageFile');
        const dropZone = document.getElementById('dropZone');
        
        imageFile.addEventListener('change', function(e) {
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
                imageFile.files = files;
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
        document.getElementById('imageUrl').addEventListener('input', function(e) {
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
            document.getElementById('imageFile').value = '';
            document.getElementById('imageUrl').value = '';
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
