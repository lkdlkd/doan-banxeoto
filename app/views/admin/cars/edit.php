<?php if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; } ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa xe - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-cars.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
</head>
<body>
    <!-- Sidebar -->
    <?php $activePage = 'cars'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="admin-main">
        <!-- Header -->
        <header class="admin-header">
            <div class="breadcrumb">
                <a href="/admin">Dashboard</a>
                <i class="fas fa-chevron-right" style="font-size: 10px; color: #ccc;"></i>
                <a href="/admin/cars">Quản lý xe</a>
                <i class="fas fa-chevron-right" style="font-size: 10px; color: #ccc;"></i>
                <span>Chỉnh sửa xe</span>
            </div>
            <div class="header-right">
                <div class="header-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="admin-content">
            <!-- Page Header -->
            <div class="page-header">
                <h2>Chỉnh sửa: <?= $car['ten_xe'] ?? 'Mercedes-Benz S-Class 2024' ?></h2>
                <a href="/admin/cars" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Quay lại
                </a>
            </div>

            <!-- Form -->
            <form action="/admin/cars/update/<?= $car['id'] ?? 1 ?>" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $car['id'] ?? 1 ?>">
                
                <div class="form-grid">
                    <!-- Left Column -->
                    <div>
                        <!-- Basic Info -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-info-circle" style="color: #D4AF37; margin-right: 10px;"></i>Thông tin cơ bản</h3>
                            </div>
                            <div class="form-group">
                                <label>Tên xe <span class="required">*</span></label>
                                <input type="text" name="ten_xe" class="form-control" value="<?= $car['ten_xe'] ?? 'Mercedes-Benz S-Class 2024' ?>" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Thương hiệu <span class="required">*</span></label>
                                    <select name="id_hang" class="form-control" required>
                                        <option value="">Chọn thương hiệu</option>
                                        <?php if (isset($brands)): ?>
                                            <?php foreach ($brands as $brand): ?>
                                                <option value="<?= $brand['id'] ?>" <?= ($car['id_hang'] ?? '') == $brand['id'] ? 'selected' : '' ?>><?= $brand['ten_hang'] ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="1" selected>Mercedes-Benz</option>
                                            <option value="2">BMW</option>
                                            <option value="3">Audi</option>
                                            <option value="4">Porsche</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Danh mục <span class="required">*</span></label>
                                    <select name="id_loai" class="form-control" required>
                                        <option value="">Chọn danh mục</option>
                                        <?php if (isset($categories)): ?>
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id'] ?>" <?= ($car['id_loai'] ?? '') == $category['id'] ? 'selected' : '' ?>><?= $category['ten_loai'] ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="1" selected>Sedan</option>
                                            <option value="2">SUV</option>
                                            <option value="3">Coupe</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Giá bán (VNĐ) <span class="required">*</span></label>
                                    <input type="number" name="gia" class="form-control" value="<?= $car['gia'] ?? 5200000000 ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Năm sản xuất <span class="required">*</span></label>
                                    <input type="number" name="nam_san_xuat" class="form-control" value="<?= $car['nam_san_xuat'] ?? 2024 ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Mô tả ngắn</label>
                                <textarea name="mo_ta" class="form-control"><?= $car['mo_ta'] ?? 'Xe sedan hạng sang với công nghệ tiên tiến và nội thất sang trọng.' ?></textarea>
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-cogs" style="color: #D4AF37; margin-right: 10px;"></i>Thông số kỹ thuật</h3>
                            </div>
                            <div class="specs-grid">
                                <div class="spec-input">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <input type="text" name="dong_co" value="<?= $car['dong_co'] ?? '3.0L I6 Turbo' ?>" placeholder="Động cơ">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-bolt"></i>
                                    <input type="text" name="cong_suat" value="<?= $car['cong_suat'] ?? '429 HP' ?>" placeholder="Công suất">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-cog"></i>
                                    <input type="text" name="hop_so" value="<?= $car['hop_so'] ?? '9G-Tronic' ?>" placeholder="Hộp số">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-road"></i>
                                    <input type="text" name="dan_dong" value="<?= $car['dan_dong'] ?? '4MATIC' ?>" placeholder="Dẫn động">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-gas-pump"></i>
                                    <input type="text" name="nhien_lieu" value="<?= $car['nhien_lieu'] ?? 'Xăng' ?>" placeholder="Nhiên liệu">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-users"></i>
                                    <input type="number" name="so_cho" value="<?= $car['so_cho'] ?? 5 ?>" placeholder="Số chỗ">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-palette"></i>
                                    <input type="text" name="mau_sac" value="<?= $car['mau_sac'] ?? 'Đen Obsidian' ?>" placeholder="Màu sắc">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <input type="text" name="so_km" value="<?= $car['so_km'] ?? '0' ?>" placeholder="Số km">
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-file-alt" style="color: #D4AF37; margin-right: 10px;"></i>Mô tả chi tiết</h3>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <textarea name="mo_ta_chi_tiet" class="form-control" style="min-height: 200px;"><?= $car['mo_ta_chi_tiet'] ?? 'Mercedes-Benz S-Class là biểu tượng của sự sang trọng và đẳng cấp. Với thiết kế tinh tế, công nghệ tiên tiến và hiệu suất vượt trội, S-Class mang đến trải nghiệm lái xe hoàn hảo nhất.' ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <!-- Current Images -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-images" style="color: #D4AF37; margin-right: 10px;"></i>Hình ảnh hiện tại</h3>
                            </div>
                            <div class="image-preview" id="currentImages">
                                <?php if (isset($car['images']) && count($car['images']) > 0): ?>
                                    <?php foreach ($car['images'] as $index => $image): ?>
                                        <div class="preview-item">
                                            <img src="<?= $image ?>" alt="Car image">
                                            <?php if ($index === 0): ?>
                                                <span class="main-badge">Ảnh chính</span>
                                            <?php endif; ?>
                                            <button type="button" class="remove" onclick="removeCurrentImage(this)"><i class="fas fa-times"></i></button>
                                            <input type="hidden" name="existing_images[]" value="<?= $image ?>">
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="preview-item">
                                        <img src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=300" alt="">
                                        <span class="main-badge">Ảnh chính</span>
                                        <button type="button" class="remove" onclick="removeCurrentImage(this)"><i class="fas fa-times"></i></button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Add New Images -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-cloud-upload-alt" style="color: #D4AF37; margin-right: 10px;"></i>Thêm ảnh mới</h3>
                            </div>
                            
                            <!-- Upload Tabs -->
                            <div class="upload-tabs">
                                <button type="button" class="upload-tab active" onclick="switchUploadTab(this, 'file')">
                                    <i class="fas fa-upload"></i> Tải từ máy
                                </button>
                                <button type="button" class="upload-tab" onclick="switchUploadTab(this, 'url')">
                                    <i class="fas fa-link"></i> Nhập URL
                                </button>
                            </div>

                            <!-- File Upload Tab -->
                            <div class="upload-content" id="fileUpload">
                                <div class="file-upload-area" id="newDropArea">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Kéo thả ảnh hoặc click để chọn</p>
                                    <span>PNG, JPG, WEBP (Tối đa 5MB mỗi ảnh)</span>
                                    <input type="file" id="newImageInput" name="new_images[]" multiple accept="image/*" hidden>
                                </div>
                            </div>

                            <!-- URL Tab -->
                            <div class="upload-content" id="urlUpload" style="display: none;">
                                <div class="form-group">
                                    <label>URL ảnh</label>
                                    <div class="url-input-group">
                                        <input type="url" id="newImageUrl" class="form-control" placeholder="https://example.com/image.jpg">
                                        <button type="button" class="btn-add-url" onclick="addImageFromUrl()">
                                            <i class="fas fa-plus"></i> Thêm
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- New Images Preview -->
                            <div class="image-preview-container" id="newImagesPreview" style="display: none; margin-top: 15px;">
                                <label>Ảnh mới thêm:</label>
                                <div class="image-preview" id="newPreviewList"></div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-toggle-on" style="color: #D4AF37; margin-right: 10px;"></i>Trạng thái</h3>
                            </div>
                            <div class="status-options">
                                <label class="status-option available <?= ($car['trang_thai'] ?? 'available') == 'available' ? 'selected' : '' ?>">
                                    <input type="radio" name="trang_thai" value="available" <?= ($car['trang_thai'] ?? 'available') == 'available' ? 'checked' : '' ?>>
                                    <i class="fas fa-check-circle"></i>
                                    <span>Còn hàng</span>
                                </label>
                                <label class="status-option reserved <?= ($car['trang_thai'] ?? '') == 'reserved' ? 'selected' : '' ?>">
                                    <input type="radio" name="trang_thai" value="reserved" <?= ($car['trang_thai'] ?? '') == 'reserved' ? 'checked' : '' ?>>
                                    <i class="fas fa-clock"></i>
                                    <span>Đã đặt</span>
                                </label>
                                <label class="status-option sold <?= ($car['trang_thai'] ?? '') == 'sold' ? 'selected' : '' ?>">
                                    <input type="radio" name="trang_thai" value="sold" <?= ($car['trang_thai'] ?? '') == 'sold' ? 'checked' : '' ?>>
                                    <i class="fas fa-times-circle"></i>
                                    <span>Đã bán</span>
                                </label>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-star" style="color: #D4AF37; margin-right: 10px;"></i>Tùy chọn</h3>
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="noi_bat" value="1" <?= ($car['noi_bat'] ?? 0) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                                    <span>Đánh dấu là xe nổi bật</span>
                                </label>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="khuyen_mai" value="1" <?= ($car['khuyen_mai'] ?? 0) ? 'checked' : '' ?> style="width: 18px; height: 18px;">
                                    <span>Đang có khuyến mãi</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" style="flex: 1;">
                                <i class="fas fa-save" style="margin-right: 8px;"></i>
                                Cập nhật
                            </button>
                            <button type="button" class="btn btn-danger" onclick="confirmDelete(<?= $car['id'] ?? 1 ?>, '<?= addslashes($car['ten_xe'] ?? 'xe này') ?>')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
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
                    <i class="fas fa-car"></i>
                    <p>Bạn có chắc chắn muốn xóa <strong id="deleteCarName"></strong>?</p>
                    <span class="warning-text">Hành động này không thể hoàn tác!</span>
                </div>
            </div>
            <div class="form-actions">
                <button type="button" class="btn-secondary" onclick="closeModal('deleteModal')">
                    <i class="fas fa-times"></i> Hủy
                </button>
                <button type="button" class="btn-danger" onclick="deleteCar()">
                    <i class="fas fa-trash"></i> Xóa xe
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

    <script>
        let deleteCarId = null;

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
            document.body.style.overflow = '';
        }

        function confirmDelete(id, name) {
            deleteCarId = id;
            document.getElementById('deleteCarName').textContent = name;
            openModal('deleteModal');
        }

        function deleteCar() {
            if (deleteCarId) {
                window.location.href = '/admin/cars/delete/' + deleteCarId;
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

        // Upload tabs
        function switchUploadTab(btn, type) {
            document.querySelectorAll('.upload-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            
            document.getElementById('fileUpload').style.display = type === 'file' ? 'block' : 'none';
            document.getElementById('urlUpload').style.display = type === 'url' ? 'block' : 'none';
        }

        // Drag and drop for new images
        const newDropArea = document.getElementById('newDropArea');
        const newImageInput = document.getElementById('newImageInput');

        newDropArea.addEventListener('click', () => newImageInput.click());
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            newDropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            newDropArea.addEventListener(eventName, () => newDropArea.classList.add('drag-over'));
        });

        ['dragleave', 'drop'].forEach(eventName => {
            newDropArea.addEventListener(eventName, () => newDropArea.classList.remove('drag-over'));
        });

        newDropArea.addEventListener('drop', function(e) {
            const files = e.dataTransfer.files;
            handleNewFiles(files);
        });

        newImageInput.addEventListener('change', function(e) {
            handleNewFiles(e.target.files);
        });

        function handleNewFiles(files) {
            const previewContainer = document.getElementById('newImagesPreview');
            const previewList = document.getElementById('newPreviewList');
            
            [...files].forEach(file => {
                if (!file.type.startsWith('image/')) return;
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContainer.style.display = 'block';
                    const div = document.createElement('div');
                    div.className = 'preview-item';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="remove" onclick="this.parentElement.remove(); checkPreviewEmpty();">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    previewList.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // Add image from URL
        function addImageFromUrl() {
            const urlInput = document.getElementById('newImageUrl');
            const url = urlInput.value.trim();
            
            if (!url) {
                showToast('Vui lòng nhập URL ảnh!', 'error');
                return;
            }

            const previewContainer = document.getElementById('newImagesPreview');
            const previewList = document.getElementById('newPreviewList');
            
            previewContainer.style.display = 'block';
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.innerHTML = `
                <img src="${url}" alt="Preview" onerror="this.parentElement.remove(); showToast('Không thể tải ảnh từ URL này!', 'error');">
                <button type="button" class="remove" onclick="this.parentElement.remove(); checkPreviewEmpty();">
                    <i class="fas fa-times"></i>
                </button>
                <input type="hidden" name="new_image_urls[]" value="${url}">
            `;
            previewList.appendChild(div);
            
            urlInput.value = '';
            showToast('Đã thêm ảnh từ URL!');
        }

        function checkPreviewEmpty() {
            const previewList = document.getElementById('newPreviewList');
            if (previewList.children.length === 0) {
                document.getElementById('newImagesPreview').style.display = 'none';
            }
        }

        // Remove current image
        function removeCurrentImage(btn) {
            btn.parentElement.remove();
        }

        // Status option selection
        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.status-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
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
