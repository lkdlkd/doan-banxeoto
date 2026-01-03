<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; }

// Dữ liệu được truyền từ controller:
// $brands, $categories

// Lấy thông báo từ session
$message = $_SESSION['success'] ?? $_SESSION['error'] ?? '';
$messageType = isset($_SESSION['success']) ? 'success' : 'error';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm xe mới - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-cars.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>
<body>
    <?php $activePage = 'cars'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <div class="breadcrumb">
                <a href="/admin">Dashboard</a>
                <i class="fas fa-chevron-right" style="font-size: 10px; color: #ccc;"></i>
                <a href="/admin/cars">Quản lý xe</a>
                <i class="fas fa-chevron-right" style="font-size: 10px; color: #ccc;"></i>
                <span>Thêm xe mới</span>
            </div>
            <div class="header-right">
                <div class="header-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
                </div>
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Thêm xe mới</h2>
                <a href="/admin/cars" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>

            <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?>">
                <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <?= htmlspecialchars($message) ?>
                <?php if ($messageType === 'success'): ?>
                <a href="/admin/cars" style="margin-left: 20px; color: inherit; font-weight: 600;">← Quay lại danh sách</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="/admin/cars/add" enctype="multipart/form-data">
                <div class="form-grid">
                    <!-- Left Column -->
                    <div class="form-column">
                        <!-- Basic Info -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-info-circle"></i> Thông tin cơ bản</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Tên xe <span class="required">*</span></label>
                                    <input type="text" name="name" required placeholder="VD: Mercedes-Benz S-Class 2024">
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Thương hiệu <span class="required">*</span></label>
                                        <select name="brand_id" required>
                                            <option value="">Chọn thương hiệu</option>
                                            <?php foreach ($brands as $brand): ?>
                                            <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Danh mục <span class="required">*</span></label>
                                        <select name="category_id" required>
                                            <option value="">Chọn danh mục</option>
                                            <?php foreach ($categories as $cat): ?>
                                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label>Giá bán (VNĐ) <span class="required">*</span></label>
                                        <input type="number" name="price" required placeholder="5000000000" min="0">
                                    </div>
                                    <div class="form-group">
                                        <label>Năm sản xuất</label>
                                        <input type="number" name="year" value="<?= date('Y') ?>" min="1990" max="<?= date('Y') + 1 ?>">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Mô tả ngắn</label>
                                    <textarea name="description" rows="4" placeholder="Mô tả ngắn về xe..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-cogs"></i> Thông số kỹ thuật</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label><i class="fas fa-road"></i> Số km đã đi</label>
                                        <input type="number" name="mileage" placeholder="0" min="0">
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-gas-pump"></i> Nhiên liệu</label>
                                        <select name="fuel_type">
                                            <option value="">Chọn loại nhiên liệu</option>
                                            <option value="Xăng">Xăng</option>
                                            <option value="Dầu Diesel">Dầu Diesel</option>
                                            <option value="Điện">Điện</option>
                                            <option value="Hybrid">Hybrid</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label><i class="fas fa-cog"></i> Hộp số</label>
                                        <select name="transmission">
                                            <option value="">Chọn loại hộp số</option>
                                            <option value="Tự động">Tự động</option>
                                            <option value="Số sàn">Số sàn</option>
                                            <option value="Bán tự động">Bán tự động</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label><i class="fas fa-palette"></i> Màu sắc</label>
                                        <input type="text" name="color" placeholder="VD: Đen, Trắng, Bạc...">
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Thông số chi tiết</label>
                                    <textarea name="specifications" rows="5" placeholder="Động cơ: 3.0L V6 Turbo&#10;Công suất: 450 HP&#10;Mô-men xoắn: 500 Nm&#10;..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="form-column">
                        <!-- Main Image -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-image"></i> Ảnh chính</h3>
                            </div>
                            <div class="card-body">
                                <div class="image-upload-section">
                                    <div class="upload-tabs">
                                        <button type="button" class="upload-tab active" onclick="switchTab('main', 'file')">
                                            <i class="fas fa-upload"></i> Tải từ máy
                                        </button>
                                        <button type="button" class="upload-tab" onclick="switchTab('main', 'url')">
                                            <i class="fas fa-link"></i> Dùng link
                                        </button>
                                    </div>
                                    
                                    <div class="upload-panel active" id="mainFilePanel">
                                        <div class="file-upload-area" id="mainDropZone">
                                            <input type="file" name="main_image" id="mainImageFile" accept="image/*">
                                            <div class="upload-icon">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                            </div>
                                            <h4>Kéo thả ảnh vào đây</h4>
                                            <p>hoặc click để chọn file</p>
                                            <span class="browse-btn">Chọn ảnh</span>
                                        </div>
                                    </div>
                                    
                                    <div class="upload-panel" id="mainUrlPanel">
                                        <div class="url-input-wrapper">
                                            <input type="url" name="main_image_url" id="mainImageUrl" placeholder="https://example.com/car.jpg">
                                            <i class="fas fa-link"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="image-preview-container" id="mainImagePreview" style="display: none;">
                                        <div class="image-preview-wrapper">
                                            <img id="mainPreviewImg" src="" alt="Preview">
                                            <div class="preview-actions">
                                                <button type="button" class="preview-action-btn remove" onclick="removeMainImage()">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery Images -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-images"></i> Thư viện ảnh</h3>
                            </div>
                            <div class="card-body">
                                <div class="image-upload-section">
                                    <div class="upload-tabs">
                                        <button type="button" class="upload-tab active" onclick="switchTab('gallery', 'file')">
                                            <i class="fas fa-upload"></i> Tải từ máy
                                        </button>
                                        <button type="button" class="upload-tab" onclick="switchTab('gallery', 'url')">
                                            <i class="fas fa-link"></i> Dùng link
                                        </button>
                                    </div>
                                    
                                    <div class="upload-panel active" id="galleryFilePanel">
                                        <div class="file-upload-area" id="galleryDropZone">
                                            <input type="file" name="gallery_images[]" id="galleryFiles" accept="image/*" multiple>
                                            <div class="upload-icon">
                                                <i class="fas fa-images"></i>
                                            </div>
                                            <h4>Chọn nhiều ảnh</h4>
                                            <p>PNG, JPG (tối đa 10 ảnh)</p>
                                            <span class="browse-btn">Chọn ảnh</span>
                                        </div>
                                    </div>
                                    
                                    <div class="upload-panel" id="galleryUrlPanel">
                                        <textarea name="gallery_urls" id="galleryUrls" rows="4" placeholder="Nhập mỗi URL trên một dòng:&#10;https://example.com/image1.jpg&#10;https://example.com/image2.jpg" style="width: 100%; padding: 14px 18px; border: 2px solid #e8e8e8; border-radius: 12px; font-size: 0.95rem; background: #fafafa; resize: vertical;"></textarea>
                                    </div>
                                    
                                    <div class="gallery-preview" id="galleryPreview"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Status & Options -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-sliders-h"></i> Trạng thái & Tùy chọn</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <div class="status-options">
                                        <label class="status-option available selected">
                                            <input type="radio" name="status" value="available" checked>
                                            <i class="fas fa-check-circle"></i>
                                            <span>Còn hàng</span>
                                        </label>
                                        <label class="status-option reserved">
                                            <input type="radio" name="status" value="reserved">
                                            <i class="fas fa-clock"></i>
                                            <span>Đã đặt</span>
                                        </label>
                                        <label class="status-option sold">
                                            <input type="radio" name="status" value="sold">
                                            <i class="fas fa-times-circle"></i>
                                            <span>Đã bán</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="form-group" style="margin-top: 20px;">
                                    <label class="toggle-switch">
                                        <input type="checkbox" name="featured" value="1">
                                        <span class="toggle-slider"></span>
                                        <span class="toggle-label">Đánh dấu là xe nổi bật</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-actions" style="background: transparent; padding: 0; border: none;">
                            <button type="reset" class="btn-secondary">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Lưu xe
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <!-- Toast Notification -->
    <?php if ($message): ?>
    <div class="toast <?= $messageType ?>" id="toast">
        <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
        <span><?= htmlspecialchars($message) ?></span>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('toast');
            setTimeout(() => toast.classList.add('show'), 100);
            setTimeout(() => toast.classList.remove('show'), 4000);
        });
    </script>
    <?php endif; ?>

    <script>
        // Switch tabs
        function switchTab(section, type) {
            const filePanel = document.getElementById(section + 'FilePanel');
            const urlPanel = document.getElementById(section + 'UrlPanel');
            const tabs = filePanel.closest('.image-upload-section').querySelectorAll('.upload-tab');
            
            tabs.forEach((tab, index) => {
                tab.classList.toggle('active', (type === 'file' && index === 0) || (type === 'url' && index === 1));
            });
            
            filePanel.classList.toggle('active', type === 'file');
            urlPanel.classList.toggle('active', type === 'url');
        }

        // Main image upload
        const mainImageFile = document.getElementById('mainImageFile');
        const mainDropZone = document.getElementById('mainDropZone');
        
        mainImageFile.addEventListener('change', function(e) {
            if (e.target.files[0]) {
                previewMainImage(e.target.files[0]);
            }
        });

        mainDropZone.addEventListener('dragover', e => { e.preventDefault(); mainDropZone.classList.add('dragover'); });
        mainDropZone.addEventListener('dragleave', e => { e.preventDefault(); mainDropZone.classList.remove('dragover'); });
        mainDropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const files = e.dataTransfer.files;
            if (files[0] && files[0].type.startsWith('image/')) {
                mainImageFile.files = files;
                previewMainImage(files[0]);
            }
        });

        function previewMainImage(file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('mainPreviewImg').src = e.target.result;
                document.getElementById('mainImagePreview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }

        document.getElementById('mainImageUrl').addEventListener('input', function(e) {
            if (e.target.value) {
                document.getElementById('mainPreviewImg').src = e.target.value;
                document.getElementById('mainImagePreview').style.display = 'block';
            }
        });

        function removeMainImage() {
            document.getElementById('mainPreviewImg').src = '';
            document.getElementById('mainImagePreview').style.display = 'none';
            document.getElementById('mainImageFile').value = '';
            document.getElementById('mainImageUrl').value = '';
        }

        // Gallery images upload
        const galleryFiles = document.getElementById('galleryFiles');
        const galleryDropZone = document.getElementById('galleryDropZone');
        const galleryPreview = document.getElementById('galleryPreview');

        galleryFiles.addEventListener('change', function(e) {
            previewGalleryImages(e.target.files);
        });

        galleryDropZone.addEventListener('dragover', e => { e.preventDefault(); galleryDropZone.classList.add('dragover'); });
        galleryDropZone.addEventListener('dragleave', e => { e.preventDefault(); galleryDropZone.classList.remove('dragover'); });
        galleryDropZone.addEventListener('drop', function(e) {
            e.preventDefault();
            this.classList.remove('dragover');
            const files = e.dataTransfer.files;
            galleryFiles.files = files;
            previewGalleryImages(files);
        });

        function previewGalleryImages(files) {
            galleryPreview.innerHTML = '';
            [...files].forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'gallery-item';
                        div.innerHTML = `
                            <img src="${e.target.result}" alt="Gallery ${index + 1}">
                            <button type="button" class="remove-gallery" onclick="this.parentElement.remove()">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        galleryPreview.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Status options
        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.status-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    </script>
</body>
</html>
