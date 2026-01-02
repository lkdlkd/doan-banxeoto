<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý danh mục - AutoCar Admin</title>
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

        /* Categories Grid */
        .categories-grid {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;
        }
        .category-card {
            background: #fff; border-radius: 15px; overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); transition: all 0.3s ease;
        }
        .category-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .category-image {
            height: 150px; background-size: cover; background-position: center;
            position: relative;
        }
        .category-image::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        }
        .category-count {
            position: absolute; bottom: 15px; left: 15px; color: #fff;
            font-size: 13px; display: flex; align-items: center; gap: 5px;
        }
        .category-content { padding: 20px; }
        .category-icon {
            width: 50px; height: 50px; border-radius: 12px; background: rgba(212,175,55,0.1);
            display: flex; align-items: center; justify-content: center;
            color: #D4AF37; font-size: 22px; margin-bottom: 15px;
        }
        .category-name { font-family: 'Playfair Display', serif; font-size: 20px; color: #1a1a1a; margin-bottom: 8px; }
        .category-desc { font-size: 13px; color: #999; line-height: 1.6; margin-bottom: 15px; }
        .category-actions { display: flex; gap: 10px; }
        .category-action {
            flex: 1; padding: 10px; border: 2px solid #eee; border-radius: 8px;
            background: #fff; cursor: pointer; display: flex; align-items: center;
            justify-content: center; gap: 8px; transition: all 0.3s ease; font-size: 13px;
        }
        .category-action:hover { border-color: #D4AF37; color: #D4AF37; }
        .category-action.delete:hover { border-color: #e74c3c; color: #e74c3c; }

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
        .icon-selector { display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; }
        .icon-option {
            width: 45px; height: 45px; border: 2px solid #eee; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; cursor: pointer;
            transition: all 0.3s ease; font-size: 18px; color: #666;
        }
        .icon-option:hover, .icon-option.selected { border-color: #D4AF37; color: #D4AF37; background: rgba(212,175,55,0.05); }
        .icon-option input { display: none; }
        .modal-footer { display: flex; gap: 15px; justify-content: flex-end; margin-top: 25px; }
        .btn { padding: 12px 25px; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all 0.3s ease; }
        .btn-secondary { background: #f5f5f5; color: #333; }

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
            <li><a href="/autocar/admin/brands"><i class="fas fa-copyright"></i> Thương hiệu</a></li>
            <li><a href="/autocar/admin/categories" class="active"><i class="fas fa-tags"></i> Danh mục</a></li>
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
            <h1>Quản lý danh mục</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Danh sách danh mục (7)</h2>
                <button class="btn-primary" onclick="openModal()">
                    <i class="fas fa-plus"></i>
                    Thêm danh mục
                </button>
            </div>

            <!-- Categories Grid -->
            <div class="categories-grid">
                <div class="category-card">
                    <div class="category-image" style="background-image: url('https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=400');">
                        <span class="category-count"><i class="fas fa-car"></i> 12 xe</span>
                    </div>
                    <div class="category-content">
                        <div class="category-icon"><i class="fas fa-car-side"></i></div>
                        <h3 class="category-name">Sedan</h3>
                        <p class="category-desc">Xe sedan hạng sang với 4 cửa, thiết kế thanh lịch phù hợp cho gia đình và doanh nhân.</p>
                        <div class="category-actions">
                            <button class="category-action" onclick="editCategory(1)"><i class="fas fa-edit"></i> Sửa</button>
                            <button class="category-action delete" onclick="deleteCategory(1)"><i class="fas fa-trash"></i> Xóa</button>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-image" style="background-image: url('https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=400');">
                        <span class="category-count"><i class="fas fa-car"></i> 8 xe</span>
                    </div>
                    <div class="category-content">
                        <div class="category-icon"><i class="fas fa-truck-monster"></i></div>
                        <h3 class="category-name">SUV</h3>
                        <p class="category-desc">Xe gầm cao đa dụng, không gian rộng rãi, phù hợp cho địa hình đa dạng.</p>
                        <div class="category-actions">
                            <button class="category-action" onclick="editCategory(2)"><i class="fas fa-edit"></i> Sửa</button>
                            <button class="category-action delete" onclick="deleteCategory(2)"><i class="fas fa-trash"></i> Xóa</button>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-image" style="background-image: url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400');">
                        <span class="category-count"><i class="fas fa-car"></i> 5 xe</span>
                    </div>
                    <div class="category-content">
                        <div class="category-icon"><i class="fas fa-flag-checkered"></i></div>
                        <h3 class="category-name">Sports Car</h3>
                        <p class="category-desc">Xe thể thao hiệu suất cao, thiết kế khí động học, dành cho những ai đam mê tốc độ.</p>
                        <div class="category-actions">
                            <button class="category-action" onclick="editCategory(3)"><i class="fas fa-edit"></i> Sửa</button>
                            <button class="category-action delete" onclick="deleteCategory(3)"><i class="fas fa-trash"></i> Xóa</button>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-image" style="background-image: url('https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=400');">
                        <span class="category-count"><i class="fas fa-car"></i> 4 xe</span>
                    </div>
                    <div class="category-content">
                        <div class="category-icon"><i class="fas fa-car-alt"></i></div>
                        <h3 class="category-name">Coupe</h3>
                        <p class="category-desc">Xe 2 cửa thể thao, đường nét mạnh mẽ, phong cách dành cho người trẻ năng động.</p>
                        <div class="category-actions">
                            <button class="category-action" onclick="editCategory(4)"><i class="fas fa-edit"></i> Sửa</button>
                            <button class="category-action delete" onclick="deleteCategory(4)"><i class="fas fa-trash"></i> Xóa</button>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-image" style="background-image: url('https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=400');">
                        <span class="category-count"><i class="fas fa-car"></i> 2 xe</span>
                    </div>
                    <div class="category-content">
                        <div class="category-icon"><i class="fas fa-wind"></i></div>
                        <h3 class="category-name">Convertible</h3>
                        <p class="category-desc">Xe mui trần sang trọng, trải nghiệm lái xe tự do giữa thiên nhiên.</p>
                        <div class="category-actions">
                            <button class="category-action" onclick="editCategory(5)"><i class="fas fa-edit"></i> Sửa</button>
                            <button class="category-action delete" onclick="deleteCategory(5)"><i class="fas fa-trash"></i> Xóa</button>
                        </div>
                    </div>
                </div>

                <div class="category-card">
                    <div class="category-image" style="background-image: url('https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=400');">
                        <span class="category-count"><i class="fas fa-car"></i> 2 xe</span>
                    </div>
                    <div class="category-content">
                        <div class="category-icon"><i class="fas fa-bolt"></i></div>
                        <h3 class="category-name">Electric</h3>
                        <p class="category-desc">Xe điện cao cấp, công nghệ tiên tiến, thân thiện với môi trường.</p>
                        <div class="category-actions">
                            <button class="category-action" onclick="editCategory(6)"><i class="fas fa-edit"></i> Sửa</button>
                            <button class="category-action delete" onclick="deleteCategory(6)"><i class="fas fa-trash"></i> Xóa</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add/Edit Category Modal -->
    <div class="modal" id="categoryModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Thêm danh mục mới</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="categoryForm" action="/autocar/admin/categories/store" method="POST">
                <input type="hidden" name="id" id="categoryId">
                <div class="form-group">
                    <label>Tên danh mục *</label>
                    <input type="text" name="ten_loai" id="categoryName" class="form-control" placeholder="VD: Sedan" required>
                </div>
                <div class="form-group">
                    <label>Icon</label>
                    <div class="icon-selector">
                        <label class="icon-option selected"><input type="radio" name="icon" value="fa-car-side" checked><i class="fas fa-car-side"></i></label>
                        <label class="icon-option"><input type="radio" name="icon" value="fa-truck-monster"><i class="fas fa-truck-monster"></i></label>
                        <label class="icon-option"><input type="radio" name="icon" value="fa-flag-checkered"><i class="fas fa-flag-checkered"></i></label>
                        <label class="icon-option"><input type="radio" name="icon" value="fa-car-alt"><i class="fas fa-car-alt"></i></label>
                        <label class="icon-option"><input type="radio" name="icon" value="fa-wind"><i class="fas fa-wind"></i></label>
                        <label class="icon-option"><input type="radio" name="icon" value="fa-bolt"><i class="fas fa-bolt"></i></label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mô tả</label>
                    <textarea name="mo_ta" id="categoryDesc" class="form-control" rows="3" placeholder="Mô tả về danh mục xe..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu danh mục</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('categoryModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Thêm danh mục mới';
            document.getElementById('categoryForm').reset();
            document.getElementById('categoryId').value = '';
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.remove('active');
        }

        function editCategory(id) {
            document.getElementById('categoryModal').classList.add('active');
            document.getElementById('modalTitle').textContent = 'Chỉnh sửa danh mục';
            document.getElementById('categoryId').value = id;
        }

        function deleteCategory(id) {
            if (confirm('Bạn có chắc chắn muốn xóa danh mục này?')) {
                window.location.href = '/autocar/admin/categories/delete/' + id;
            }
        }

        // Icon selector
        document.querySelectorAll('.icon-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.icon-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    </script>
</body>
</html>
