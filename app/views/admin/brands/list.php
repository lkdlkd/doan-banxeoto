<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý thương hiệu - AutoCar Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Montserrat', sans-serif; background: #f5f2ed; min-height: 100vh; }

        .admin-sidebar {
            position: fixed; left: 0; top: 0; width: 280px; height: 100vh;
            background: linear-gradient(180deg, #1a1a1a 0%, #2d2d2d 100%);
            padding: 25px 0; z-index: 100; overflow-y: auto;
        }
        .sidebar-logo {
            display: flex; align-items: center; gap: 12px; padding: 0 25px 30px;
            border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 25px; text-decoration: none;
        }
        .sidebar-logo img { height: 50px; }
        .sidebar-logo-text { display: flex; flex-direction: column; }
        .sidebar-logo-text .brand { font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700; color: #D4AF37; }
        .sidebar-logo-text .role { font-size: 11px; color: rgba(255,255,255,0.5); text-transform: uppercase; letter-spacing: 2px; }
        .sidebar-menu { list-style: none; padding: 0 15px; }
        .sidebar-menu-title { font-size: 11px; color: rgba(255,255,255,0.4); text-transform: uppercase; letter-spacing: 1.5px; padding: 15px 15px 10px; margin-top: 10px; }
        .sidebar-menu li a {
            display: flex; align-items: center; gap: 12px; padding: 14px 20px;
            color: rgba(255,255,255,0.7); text-decoration: none; border-radius: 10px;
            transition: all 0.3s ease; font-size: 14px;
        }
        .sidebar-menu li a:hover { background: rgba(212,175,55,0.1); color: #D4AF37; }
        .sidebar-menu li a.active { background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); color: #fff; }
        .sidebar-menu li a i { width: 20px; text-align: center; font-size: 16px; }
        .sidebar-menu li a .badge { margin-left: auto; background: #e74c3c; color: #fff; font-size: 10px; padding: 3px 8px; border-radius: 10px; }

        .admin-main { margin-left: 280px; min-height: 100vh; }
        .admin-header {
            background: #fff; padding: 20px 30px; display: flex; justify-content: space-between;
            align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 50;
        }
        .admin-header h1 { font-family: 'Playfair Display', serif; font-size: 24px; color: #1a1a1a; }
        .header-profile img { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; }

        .admin-content { padding: 30px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .page-header h2 { font-family: 'Playfair Display', serif; font-size: 28px; color: #1a1a1a; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 8px; padding: 12px 25px;
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); color: #fff;
            text-decoration: none; border-radius: 10px; font-weight: 600; font-size: 14px;
            transition: all 0.3s ease; border: none; cursor: pointer;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(212,175,55,0.4); }

        /* Brands Grid */
        .brands-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px;
        }
        .brand-card {
            background: #fff; border-radius: 15px; padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: all 0.3s ease;
            display: flex; flex-direction: column; align-items: center; text-align: center;
        }
        .brand-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .brand-logo {
            width: 100px; height: 100px; border-radius: 50%; background: #f9f7f3;
            display: flex; align-items: center; justify-content: center; margin-bottom: 20px;
            border: 3px solid #eee; overflow: hidden;
        }
        .brand-logo img { max-width: 70%; max-height: 70%; object-fit: contain; }
        .brand-name { font-family: 'Playfair Display', serif; font-size: 20px; color: #1a1a1a; margin-bottom: 5px; }
        .brand-country { font-size: 13px; color: #999; margin-bottom: 15px; }
        .brand-stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .brand-stat { text-align: center; }
        .brand-stat-value { font-size: 20px; font-weight: 700; color: #D4AF37; }
        .brand-stat-label { font-size: 11px; color: #999; text-transform: uppercase; }
        .brand-actions { display: flex; gap: 10px; }
        .brand-action {
            width: 40px; height: 40px; border: 2px solid #eee; border-radius: 10px;
            background: #fff; cursor: pointer; display: flex; align-items: center;
            justify-content: center; transition: all 0.3s ease;
        }
        .brand-action:hover { border-color: #D4AF37; color: #D4AF37; }
        .brand-action.delete:hover { border-color: #e74c3c; color: #e74c3c; }

        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: #fff; border-radius: 15px; padding: 30px; width: 500px; max-width: 90%; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .modal-header h3 { font-family: 'Playfair Display', serif; font-size: 22px; }
        .modal-close { background: none; border: none; font-size: 28px; cursor: pointer; color: #999; }
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #333; margin-bottom: 8px; }
        .form-control {
            width: 100%; padding: 12px 15px; border: 2px solid #eee; border-radius: 10px;
            font-size: 14px; font-family: 'Montserrat', sans-serif; transition: all 0.3s ease;
        }
        .form-control:focus { outline: none; border-color: #D4AF37; }
        .logo-upload {
            border: 2px dashed #ddd; border-radius: 10px; padding: 30px; text-align: center;
            cursor: pointer; transition: all 0.3s ease;
        }
        .logo-upload:hover { border-color: #D4AF37; }
        .logo-upload i { font-size: 40px; color: #ddd; margin-bottom: 10px; }
        .logo-upload p { font-size: 14px; color: #666; }
        .logo-upload input { display: none; }
        .logo-preview { width: 80px; height: 80px; margin: 0 auto 10px; }
        .logo-preview img { max-width: 100%; max-height: 100%; object-fit: contain; }
        .modal-footer { display: flex; gap: 15px; justify-content: flex-end; margin-top: 25px; }
        .btn { padding: 12px 25px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all 0.3s ease; }
        .btn-secondary { background: #f5f5f5; color: #333; }
        .btn-secondary:hover { background: #eee; }

        @media (max-width: 768px) { .admin-sidebar { transform: translateX(-100%); } .admin-main { margin-left: 0; } }
    </style>
</head>
<body>
    <aside class="admin-sidebar">
        <a href="/autocar/admin" class="sidebar-logo">
            <img src="/autocar/assets/images/logo.png" alt="Logo">
            <div class="sidebar-logo-text">
                <span class="brand">AUTOCAR</span>
                <span class="role">Admin Panel</span>
            </div>
        </a>
        <ul class="sidebar-menu">
            <li><a href="/autocar/admin"><i class="fas fa-home"></i> Dashboard</a></li>
            <li class="sidebar-menu-title">Quản lý</li>
            <li><a href="/autocar/admin/cars"><i class="fas fa-car"></i> Quản lý xe</a></li>
            <li><a href="/autocar/admin/brands" class="active"><i class="fas fa-copyright"></i> Thương hiệu</a></li>
            <li><a href="/autocar/admin/categories"><i class="fas fa-tags"></i> Danh mục</a></li>
            <li class="sidebar-menu-title">Kinh doanh</li>
            <li><a href="/autocar/admin/orders"><i class="fas fa-shopping-cart"></i> Đơn hàng <span class="badge">5</span></a></li>
            <li><a href="/autocar/admin/users"><i class="fas fa-users"></i> Khách hàng</a></li>
            <li><a href="/autocar/admin/reviews"><i class="fas fa-star"></i> Đánh giá</a></li>
            <li class="sidebar-menu-title">Hệ thống</li>
            <li><a href="/autocar/admin/contacts"><i class="fas fa-envelope"></i> Liên hệ <span class="badge">3</span></a></li>
            <li><a href="/autocar/admin/settings"><i class="fas fa-cog"></i> Cài đặt</a></li>
            <li><a href="/autocar/"><i class="fas fa-globe"></i> Xem website</a></li>
            <li><a href="/autocar/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Quản lý thương hiệu</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Danh sách thương hiệu (12)</h2>
                <button class="btn-primary" onclick="openModal()">
                    <i class="fas fa-plus"></i>
                    Thêm thương hiệu
                </button>
            </div>

            <!-- Brands Grid -->
            <div class="brands-grid">
                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="https://www.carlogos.org/car-logos/mercedes-benz-logo.png" alt="Mercedes-Benz">
                    </div>
                    <h3 class="brand-name">Mercedes-Benz</h3>
                    <p class="brand-country">🇩🇪 Đức</p>
                    <div class="brand-stats">
                        <div class="brand-stat">
                            <div class="brand-stat-value">8</div>
                            <div class="brand-stat-label">Xe</div>
                        </div>
                        <div class="brand-stat">
                            <div class="brand-stat-value">12</div>
                            <div class="brand-stat-label">Đã bán</div>
                        </div>
                    </div>
                    <div class="brand-actions">
                        <button class="brand-action" onclick="editBrand(1)"><i class="fas fa-edit"></i></button>
                        <button class="brand-action delete" onclick="deleteBrand(1)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>

                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="https://www.carlogos.org/car-logos/bmw-logo.png" alt="BMW">
                    </div>
                    <h3 class="brand-name">BMW</h3>
                    <p class="brand-country">🇩🇪 Đức</p>
                    <div class="brand-stats">
                        <div class="brand-stat">
                            <div class="brand-stat-value">6</div>
                            <div class="brand-stat-label">Xe</div>
                        </div>
                        <div class="brand-stat">
                            <div class="brand-stat-value">9</div>
                            <div class="brand-stat-label">Đã bán</div>
                        </div>
                    </div>
                    <div class="brand-actions">
                        <button class="brand-action" onclick="editBrand(2)"><i class="fas fa-edit"></i></button>
                        <button class="brand-action delete" onclick="deleteBrand(2)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>

                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="https://www.carlogos.org/car-logos/audi-logo.png" alt="Audi">
                    </div>
                    <h3 class="brand-name">Audi</h3>
                    <p class="brand-country">🇩🇪 Đức</p>
                    <div class="brand-stats">
                        <div class="brand-stat">
                            <div class="brand-stat-value">5</div>
                            <div class="brand-stat-label">Xe</div>
                        </div>
                        <div class="brand-stat">
                            <div class="brand-stat-value">7</div>
                            <div class="brand-stat-label">Đã bán</div>
                        </div>
                    </div>
                    <div class="brand-actions">
                        <button class="brand-action" onclick="editBrand(3)"><i class="fas fa-edit"></i></button>
                        <button class="brand-action delete" onclick="deleteBrand(3)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>

                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="https://www.carlogos.org/car-logos/porsche-logo.png" alt="Porsche">
                    </div>
                    <h3 class="brand-name">Porsche</h3>
                    <p class="brand-country">🇩🇪 Đức</p>
                    <div class="brand-stats">
                        <div class="brand-stat">
                            <div class="brand-stat-value">4</div>
                            <div class="brand-stat-label">Xe</div>
                        </div>
                        <div class="brand-stat">
                            <div class="brand-stat-value">5</div>
                            <div class="brand-stat-label">Đã bán</div>
                        </div>
                    </div>
                    <div class="brand-actions">
                        <button class="brand-action" onclick="editBrand(4)"><i class="fas fa-edit"></i></button>
                        <button class="brand-action delete" onclick="deleteBrand(4)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>

                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="https://www.carlogos.org/car-logos/lexus-logo.png" alt="Lexus">
                    </div>
                    <h3 class="brand-name">Lexus</h3>
                    <p class="brand-country">🇯🇵 Nhật Bản</p>
                    <div class="brand-stats">
                        <div class="brand-stat">
                            <div class="brand-stat-value">4</div>
                            <div class="brand-stat-label">Xe</div>
                        </div>
                        <div class="brand-stat">
                            <div class="brand-stat-value">6</div>
                            <div class="brand-stat-label">Đã bán</div>
                        </div>
                    </div>
                    <div class="brand-actions">
                        <button class="brand-action" onclick="editBrand(5)"><i class="fas fa-edit"></i></button>
                        <button class="brand-action delete" onclick="deleteBrand(5)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>

                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="https://www.carlogos.org/car-logos/bentley-logo.png" alt="Bentley">
                    </div>
                    <h3 class="brand-name">Bentley</h3>
                    <p class="brand-country">🇬🇧 Anh</p>
                    <div class="brand-stats">
                        <div class="brand-stat">
                            <div class="brand-stat-value">2</div>
                            <div class="brand-stat-label">Xe</div>
                        </div>
                        <div class="brand-stat">
                            <div class="brand-stat-value">3</div>
                            <div class="brand-stat-label">Đã bán</div>
                        </div>
                    </div>
                    <div class="brand-actions">
                        <button class="brand-action" onclick="editBrand(6)"><i class="fas fa-edit"></i></button>
                        <button class="brand-action delete" onclick="deleteBrand(6)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>

                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="https://www.carlogos.org/car-logos/rolls-royce-logo.png" alt="Rolls-Royce">
                    </div>
                    <h3 class="brand-name">Rolls-Royce</h3>
                    <p class="brand-country">🇬🇧 Anh</p>
                    <div class="brand-stats">
                        <div class="brand-stat">
                            <div class="brand-stat-value">2</div>
                            <div class="brand-stat-label">Xe</div>
                        </div>
                        <div class="brand-stat">
                            <div class="brand-stat-value">2</div>
                            <div class="brand-stat-label">Đã bán</div>
                        </div>
                    </div>
                    <div class="brand-actions">
                        <button class="brand-action" onclick="editBrand(7)"><i class="fas fa-edit"></i></button>
                        <button class="brand-action delete" onclick="deleteBrand(7)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>

                <div class="brand-card">
                    <div class="brand-logo">
                        <img src="https://www.carlogos.org/car-logos/ferrari-logo.png" alt="Ferrari">
                    </div>
                    <h3 class="brand-name">Ferrari</h3>
                    <p class="brand-country">🇮🇹 Ý</p>
                    <div class="brand-stats">
                        <div class="brand-stat">
                            <div class="brand-stat-value">2</div>
                            <div class="brand-stat-label">Xe</div>
                        </div>
                        <div class="brand-stat">
                            <div class="brand-stat-value">1</div>
                            <div class="brand-stat-label">Đã bán</div>
                        </div>
                    </div>
                    <div class="brand-actions">
                        <button class="brand-action" onclick="editBrand(8)"><i class="fas fa-edit"></i></button>
                        <button class="brand-action delete" onclick="deleteBrand(8)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add/Edit Brand Modal -->
    <div class="modal" id="brandModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Thêm thương hiệu mới</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="brandForm" action="/autocar/admin/brands/store" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="brandId">
                <div class="form-group">
                    <label>Tên thương hiệu *</label>
                    <input type="text" name="ten_hang" id="brandName" class="form-control" placeholder="VD: Mercedes-Benz" required>
                </div>
                <div class="form-group">
                    <label>Quốc gia</label>
                    <input type="text" name="quoc_gia" id="brandCountry" class="form-control" placeholder="VD: Đức">
                </div>
                <div class="form-group">
                    <label>Logo thương hiệu</label>
                    <label class="logo-upload" for="brandLogo">
                        <div class="logo-preview" id="logoPreview" style="display: none;">
                            <img src="" alt="">
                        </div>
                        <i class="fas fa-cloud-upload-alt" id="uploadIcon"></i>
                        <p>Click để tải logo</p>
                        <input type="file" name="logo" id="brandLogo" accept="image/*">
                    </label>
                </div>
                <div class="form-group">
                    <label>Mô tả</label>
                    <textarea name="mo_ta" id="brandDesc" class="form-control" rows="3" placeholder="Mô tả ngắn về thương hiệu..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu thương hiệu</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('brandModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Thêm thương hiệu mới';
            document.getElementById('brandForm').reset();
            document.getElementById('brandId').value = '';
            document.getElementById('logoPreview').style.display = 'none';
            document.getElementById('uploadIcon').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('brandModal').classList.remove('active');
        }

        function editBrand(id) {
            document.getElementById('brandModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Chỉnh sửa thương hiệu';
            document.getElementById('brandId').value = id;
            // Load brand data via AJAX here
        }

        function deleteBrand(id) {
            if (confirm('Bạn có chắc chắn muốn xóa thương hiệu này?')) {
                window.location.href = '/autocar/admin/brands/delete/' + id;
            }
        }

        // Logo preview
        document.getElementById('brandLogo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('logoPreview').innerHTML = '<img src="' + e.target.result + '" alt="">';
                    document.getElementById('logoPreview').style.display = 'block';
                    document.getElementById('uploadIcon').style.display = 'none';
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
