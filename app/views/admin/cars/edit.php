<?php 
if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../../config/config.php'; }

// Dữ liệu được truyền từ controller:
// $car, $brands, $categories, $carImages

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
    <title>Chỉnh sửa xe - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-common.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/admin-modal.css">
    <style>
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f3f4f6;
        }

        .page-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header h2 i {
            font-size: 24px;
            color: #D4AF37;
        }

        .btn-back {
            padding: 12px 24px;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            color: #6b7280;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            border-color: #D4AF37;
            color: #D4AF37;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.2);
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 450px;
            gap: 24px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 28px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 24px;
            border: 1px solid #f3f4f6;
        }

        .card-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid #f9fafb;
        }

        .card-header h3 {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .required {
            color: #ef4444;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #D4AF37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .spec-input {
            position: relative;
        }

        .spec-input i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
        }

        .spec-input input,
        .spec-input select {
            padding-left: 42px;
        }

        /* Image Upload */
        .image-preview {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .preview-item {
            position: relative;
            aspect-ratio: 16/10;
            border-radius: 12px;
            overflow: hidden;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }

        .preview-item:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            border-color: #D4AF37;
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-item .main-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: linear-gradient(135deg, #D4AF37, #B8960B);
            color: white;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .preview-item .remove {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 28px;
            height: 28px;
            background: rgba(239, 68, 68, 0.95);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .preview-item:hover .remove {
            opacity: 1;
        }

        .preview-item .remove:hover {
            background: #dc2626;
            transform: scale(1.1);
        }

        .file-upload-area {
            border: 3px dashed #d1d5db;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .file-upload-area:hover {
            border-color: #D4AF37;
            background: rgba(212, 175, 55, 0.05);
        }

        .file-upload-area.drag-over {
            border-color: #D4AF37;
            background: rgba(212, 175, 55, 0.1);
        }

        .file-upload-area i {
            font-size: 48px;
            color: #9ca3af;
            margin-bottom: 12px;
        }

        .file-upload-area p {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin: 0 0 8px 0;
        }

        .file-upload-area span {
            font-size: 13px;
            color: #6b7280;
        }

        .upload-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 20px;
        }

        .upload-tab {
            flex: 1;
            padding: 12px;
            background: #f9fafb;
            border: 2px solid transparent;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            color: #6b7280;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .upload-tab:hover {
            background: #f3f4f6;
        }

        .upload-tab.active {
            background: linear-gradient(135deg, #D4AF37, #B8960B);
            color: white;
            border-color: #D4AF37;
        }

        .url-input-group {
            display: flex;
            gap: 12px;
        }

        .url-input-group input {
            flex: 1;
        }

        .url-input-group button {
            padding: 12px 20px;
            border: none;
            background: linear-gradient(135deg, #D4AF37, #B8960B);
            color: white;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .url-input-group button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.4);
        }

        /* Status Options */
        .status-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .status-option {
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .status-option input[type="radio"] {
            display: none;
        }

        .status-option i {
            font-size: 28px;
        }

        .status-option span {
            font-weight: 600;
            font-size: 14px;
        }

        .status-option.available {
            color: #10b981;
        }

        .status-option.sold {
            color: #ef4444;
        }

        .status-option.selected {
            border-color: currentColor;
            background: rgba(var(--color-rgb), 0.05);
        }

        .status-option.available.selected {
            background: rgba(16, 185, 129, 0.05);
        }

        .status-option.sold.selected {
            background: rgba(239, 68, 68, 0.05);
        }

        .status-option:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        /* Form Actions */
        .form-actions {
            display: flex;
            gap: 12px;
            padding-top: 24px;
            border-top: 2px solid #f3f4f6;
        }

        .btn-primary {
            padding: 14px 32px;
            background: linear-gradient(135deg, #D4AF37, #B8960B);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 175, 55, 0.4);
        }

        .btn-danger {
            padding: 14px 32px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        }

        @media (max-width: 1024px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php $activePage = 'cars'; include __DIR__ . '/../layouts/sidebar.php'; ?>

    <main class="admin-main">
        <header class="admin-header">
            <h1><i class="fas fa-car" style="color: #D4AF37; margin-right: 12px;"></i>Chỉnh sửa xe</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2><i class="fas fa-edit"></i><?= htmlspecialchars($car['name'] ?? 'Xe') ?></h2>
                <a href="<?= BASE_URL ?>/admin/cars" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </div>

            <?php if ($message): ?>
            <div class="alert alert-<?= $messageType ?>">
                <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <?= htmlspecialchars($message) ?>
            </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>/admin/cars/edit" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $car['id'] ?? 0 ?>">
                <input type="hidden" name="deleted_image_ids" id="deletedImageIds" value="">
                
                <div class="form-grid">
                    <!-- Left Column -->
                    <div>
                        <!-- Basic Info -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-info-circle" style="color: #3b82f6;"></i>Thông tin cơ bản</h3>
                            </div>
                            <div class="form-group">
                                <label>Tên xe <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($car['name'] ?? '') ?>" required placeholder="Nhập tên xe">
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Thương hiệu <span class="required">*</span></label>
                                    <select name="brand_id" class="form-control" required>
                                        <option value="">Chọn thương hiệu</option>
                                        <?php foreach ($brands as $brand): ?>
                                            <option value="<?= $brand['id'] ?>" <?= ($car['brand_id'] ?? 0) == $brand['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($brand['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Danh mục <span class="required">*</span></label>
                                    <select name="category_id" class="form-control" required>
                                        <option value="">Chọn danh mục</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" <?= ($car['category_id'] ?? 0) == $category['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Giá bán (VNĐ) <span class="required">*</span></label>
                                    <input type="number" name="price" class="form-control" value="<?= $car['price'] ?? 0 ?>" required placeholder="0">
                                </div>
                                <div class="form-group">
                                    <label>Năm sản xuất <span class="required">*</span></label>
                                    <input type="number" name="year" class="form-control" value="<?= $car['year'] ?? date('Y') ?>" required placeholder="<?= date('Y') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-cogs" style="color: #f59e0b;"></i>Thông số kỹ thuật</h3>
                            </div>
                            
                            <div class="form-group">
                                <label>Số lượng tồn kho <span class="required">*</span></label>
                                <input type="number" name="stock" class="form-control" value="<?= $car['stock'] ?? 1 ?>" required placeholder="Số lượng xe" min="0">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-engine"></i> Loại động cơ</label>
                                <input type="text" name="engine" class="form-control" value="<?= htmlspecialchars($car['engine'] ?? '') ?>" placeholder="VD: V8 4.0L Twin-Turbo">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-bolt"></i> Công suất (HP)</label>
                                    <input type="number" name="horsepower" class="form-control" value="<?= $car['horsepower'] ?? '' ?>" placeholder="450" min="0">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-sync-alt"></i> Mô-men xoắn (Nm)</label>
                                    <input type="number" name="torque" class="form-control" value="<?= $car['torque'] ?? '' ?>" placeholder="500" min="0">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-tachometer-alt"></i> Tăng tốc 0-100 km/h (s)</label>
                                    <input type="number" name="acceleration" class="form-control" value="<?= $car['acceleration'] ?? '' ?>" step="0.1" placeholder="3.5" min="0">
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-road"></i> Hệ dẫn động</label>
                                    <select name="drivetrain" class="form-control">
                                        <option value="">Chọn hệ dẫn động</option>
                                        <option value="FWD" <?= ($car['drivetrain'] ?? '') === 'FWD' ? 'selected' : '' ?>>FWD (Cầu trước)</option>
                                        <option value="RWD" <?= ($car['drivetrain'] ?? '') === 'RWD' ? 'selected' : '' ?>>RWD (Cầu sau)</option>
                                        <option value="AWD" <?= ($car['drivetrain'] ?? '') === 'AWD' ? 'selected' : '' ?>>AWD (4 bánh toàn thời gian)</option>
                                        <option value="4WD" <?= ($car['drivetrain'] ?? '') === '4WD' ? 'selected' : '' ?>>4WD (4 bánh bán thời gian)</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label><i class="fas fa-user-friends"></i> Số chỗ ngồi</label>
                                    <select name="seats" class="form-control">
                                        <option value="">Chọn số chỗ</option>
                                        <option value="2" <?= ($car['seats'] ?? '') == '2' ? 'selected' : '' ?>>2 chỗ</option>
                                        <option value="4" <?= ($car['seats'] ?? '') == '4' ? 'selected' : '' ?>>4 chỗ</option>
                                        <option value="5" <?= ($car['seats'] ?? '') == '5' ? 'selected' : '' ?>>5 chỗ</option>
                                        <option value="7" <?= ($car['seats'] ?? '') == '7' ? 'selected' : '' ?>>7 chỗ</option>
                                        <option value="8" <?= ($car['seats'] ?? '') == '8' ? 'selected' : '' ?>>8 chỗ</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label><i class="fas fa-door-open"></i> Số cửa</label>
                                    <select name="doors" class="form-control">
                                        <option value="">Chọn số cửa</option>
                                        <option value="2" <?= ($car['doors'] ?? '') == '2' ? 'selected' : '' ?>>2 cửa</option>
                                        <option value="4" <?= ($car['doors'] ?? '') == '4' ? 'selected' : '' ?>>4 cửa</option>
                                        <option value="5" <?= ($car['doors'] ?? '') == '5' ? 'selected' : '' ?>>5 cửa</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="specs-grid">
                                <div class="spec-input">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <input type="number" name="mileage" class="form-control" value="<?= $car['mileage'] ?? 0 ?>" placeholder="Số km đã đi">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-palette"></i>
                                    <input type="text" name="color" class="form-control" value="<?= htmlspecialchars($car['color'] ?? '') ?>" placeholder="Màu sắc">
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-gas-pump"></i>
                                    <select name="fuel" class="form-control">
                                        <option value="gasoline" <?= ($car['fuel'] ?? '') === 'gasoline' ? 'selected' : '' ?>>Xăng</option>
                                        <option value="diesel" <?= ($car['fuel'] ?? '') === 'diesel' ? 'selected' : '' ?>>Dầu</option>
                                        <option value="electric" <?= ($car['fuel'] ?? '') === 'electric' ? 'selected' : '' ?>>Điện</option>
                                        <option value="hybrid" <?= ($car['fuel'] ?? '') === 'hybrid' ? 'selected' : '' ?>>Hybrid</option>
                                    </select>
                                </div>
                                <div class="spec-input">
                                    <i class="fas fa-cog"></i>
                                    <select name="transmission" class="form-control">
                                        <option value="automatic" <?= ($car['transmission'] ?? '') === 'automatic' ? 'selected' : '' ?>>Tự động</option>
                                        <option value="manual" <?= ($car['transmission'] ?? '') === 'manual' ? 'selected' : '' ?>>Số sàn</option>
                                        <option value="semi-automatic" <?= ($car['transmission'] ?? '') === 'semi-automatic' ? 'selected' : '' ?>>Bán tự động</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-file-alt" style="color: #10b981;"></i>Mô tả chi tiết</h3>
                            </div>
                            <div class="form-group" style="margin-bottom: 0;">
                                <textarea name="description" class="form-control" rows="8" placeholder="Nhập mô tả chi tiết về xe..."><?= htmlspecialchars($car['description'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <!-- Current Images -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-images" style="color: #8b5cf6;"></i>Hình ảnh hiện tại</h3>
                            </div>
                            <div class="image-preview" id="currentImages">
                                <?php if (!empty($carImages)): ?>
                                    <?php foreach ($carImages as $index => $image): ?>
                                        <div class="preview-item" data-image-id="<?= $image['id'] ?>">
                                            <img src="<?= htmlspecialchars($image['image_url']) ?>" alt="Car image">
                                            <?php if ($index === 0): ?>
                                                <span class="main-badge">Ảnh chính</span>
                                            <?php endif; ?>
                                            <button type="button" class="remove" onclick="removeCurrentImage(this, <?= $image['id'] ?>)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p style="text-align: center; color: #9ca3af; padding: 20px; grid-column: 1/-1;">Chưa có hình ảnh nào</p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Add New Images -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-cloud-upload-alt" style="color: #06b6d4;"></i>Thêm ảnh mới</h3>
                            </div>
                            
                            <div class="upload-tabs">
                                <button type="button" class="upload-tab active" onclick="switchUploadTab(this, 'file')">
                                    <i class="fas fa-upload"></i> Tải từ máy
                                </button>
                                <button type="button" class="upload-tab" onclick="switchUploadTab(this, 'url')">
                                    <i class="fas fa-link"></i> Nhập URL
                                </button>
                            </div>

                            <div class="upload-content" id="fileUpload">
                                <div class="file-upload-area" id="newDropArea">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Kéo thả ảnh hoặc click để chọn</p>
                                    <span>PNG, JPG, WEBP (Tối đa 5MB mỗi ảnh)</span>
                                    <input type="file" id="newImageInput" name="new_images[]" multiple accept="image/*" hidden>
                                </div>
                            </div>

                            <div class="upload-content" id="urlUpload" style="display: none;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>URL ảnh</label>
                                    <div class="url-input-group">
                                        <input type="url" id="newImageUrl" class="form-control" placeholder="https://example.com/image.jpg">
                                        <button type="button" onclick="addImageFromUrl()">
                                            <i class="fas fa-plus"></i> Thêm
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="image-preview-container" id="newImagesPreview" style="display: none; margin-top: 20px;">
                                <label style="font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px; display: block;">Ảnh mới thêm:</label>
                                <div class="image-preview" id="newPreviewList"></div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="card">
                            <div class="card-header">
                                <h3><i class="fas fa-toggle-on" style="color: #ec4899;"></i>Trạng thái</h3>
                            </div>
                            <div class="status-options">
                                <label class="status-option available <?= ($car['status'] ?? 'available') == 'available' ? 'selected' : '' ?>">
                                    <input type="radio" name="status" value="available" <?= ($car['status'] ?? 'available') == 'available' ? 'checked' : '' ?>>
                                    <i class="fas fa-check-circle"></i>
                                    <span>Còn hàng</span>
                                </label>
                                <label class="status-option sold <?= ($car['status'] ?? '') == 'sold' ? 'selected' : '' ?>">
                                    <input type="radio" name="status" value="sold" <?= ($car['status'] ?? '') == 'sold' ? 'checked' : '' ?>>
                                    <i class="fas fa-times-circle"></i>
                                    <span>Đã bán</span>
                                </label>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="form-actions">
                            <button type="submit" class="btn-primary" style="flex: 1;">
                                <i class="fas fa-save"></i> Cập nhật
                            </button>
                            <button type="button" class="btn-danger" onclick="if(confirm('Bạn có chắc chắn muốn xóa xe này?')) window.location.href='<?= BASE_URL ?>/admin/cars/delete/<?= $car['id'] ?>'">
                                <i class="fas fa-trash"></i> Xóa xe
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <div class="toast" id="toast"></div>

    <script>
        // Upload tabs
        function switchUploadTab(btn, type) {
            document.querySelectorAll('.upload-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            
            document.getElementById('fileUpload').style.display = type === 'file' ? 'block' : 'none';
            document.getElementById('urlUpload').style.display = type === 'url' ? 'block' : 'none';
        }

        // Drag and drop
        const newDropArea = document.getElementById('newDropArea');
        const newImageInput = document.getElementById('newImageInput');

        newDropArea.addEventListener('click', () => newImageInput.click());
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            newDropArea.addEventListener(eventName, e => {e.preventDefault(); e.stopPropagation();}, false);
        });

        ['dragenter', 'dragover'].forEach(eventName => {
            newDropArea.addEventListener(eventName, () => newDropArea.classList.add('drag-over'));
        });

        ['dragleave', 'drop'].forEach(eventName => {
            newDropArea.addEventListener(eventName, () => newDropArea.classList.remove('drag-over'));
        });

        newDropArea.addEventListener('drop', e => handleNewFiles(e.dataTransfer.files));
        newImageInput.addEventListener('change', e => handleNewFiles(e.target.files));

        function handleNewFiles(files) {
            const previewContainer = document.getElementById('newImagesPreview');
            const previewList = document.getElementById('newPreviewList');
            
            [...files].forEach(file => {
                if (!file.type.startsWith('image/')) return;
                
                const reader = new FileReader();
                reader.onload = e => {
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

        function removeCurrentImage(btn, imageId) {
            if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                const deletedIdsInput = document.getElementById('deletedImageIds');
                const currentIds = deletedIdsInput.value ? deletedIdsInput.value.split(',') : [];
                currentIds.push(imageId);
                deletedIdsInput.value = currentIds.join(',');
                
                btn.parentElement.remove();
                
                const currentImagesDiv = document.getElementById('currentImages');
                if (currentImagesDiv.querySelectorAll('.preview-item').length === 0) {
                    currentImagesDiv.innerHTML = '<p style="text-align: center; color: #9ca3af; padding: 20px; grid-column: 1/-1;">Chưa có hình ảnh nào</p>';
                }
                
                showToast('Ảnh sẽ bị xóa khi bạn lưu thay đổi', 'warning');
            }
        }

        document.querySelectorAll('.status-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.status-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

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
