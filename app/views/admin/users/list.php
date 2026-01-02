<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý khách hàng - AutoCar Admin</title>
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
        .header-right { display: flex; align-items: center; gap: 20px; }
        .header-search { position: relative; }
        .header-search input { padding: 10px 15px 10px 40px; border: 2px solid #eee; border-radius: 10px; font-size: 14px; width: 250px; }
        .header-search input:focus { outline: none; border-color: #D4AF37; }
        .header-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }
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

        /* Stats */
        .user-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .user-stat {
            background: #fff; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }
        .user-stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .user-stat-icon.total { background: rgba(52,152,219,0.15); color: #3498db; }
        .user-stat-icon.active { background: rgba(46,204,113,0.15); color: #2ecc71; }
        .user-stat-icon.vip { background: rgba(212,175,55,0.15); color: #D4AF37; }
        .user-stat-icon.new { background: rgba(155,89,182,0.15); color: #9b59b6; }
        .user-stat-info h3 { font-size: 24px; font-weight: 700; color: #1a1a1a; }
        .user-stat-info p { font-size: 13px; color: #999; }

        /* Filters */
        .filters-bar {
            background: #fff; border-radius: 15px; padding: 20px; margin-bottom: 25px;
            display: flex; gap: 15px; flex-wrap: wrap; align-items: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .filter-group { display: flex; align-items: center; gap: 10px; }
        .filter-group label { font-size: 13px; color: #666; font-weight: 500; }
        .filter-group select { padding: 10px 15px; border: 2px solid #eee; border-radius: 8px; font-size: 14px; }
        .filter-group select:focus { outline: none; border-color: #D4AF37; }
        .filter-search { flex: 1; min-width: 200px; position: relative; }
        .filter-search input { width: 100%; padding: 10px 15px 10px 40px; border: 2px solid #eee; border-radius: 8px; font-size: 14px; }
        .filter-search input:focus { outline: none; border-color: #D4AF37; }
        .filter-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }

        /* Table */
        .card { background: #fff; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .users-table { width: 100%; border-collapse: collapse; }
        .users-table th, .users-table td { padding: 15px; text-align: left; border-bottom: 1px solid #f5f5f5; }
        .users-table th { font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; background: #fafafa; }
        .users-table th:first-child { border-radius: 8px 0 0 8px; }
        .users-table th:last-child { border-radius: 0 8px 8px 0; }
        .users-table tr:hover { background: #fafafa; }

        .user-info { display: flex; align-items: center; gap: 12px; }
        .user-info img { width: 45px; height: 45px; border-radius: 12px; object-fit: cover; }
        .user-info-text h4 { font-size: 14px; color: #1a1a1a; margin-bottom: 2px; }
        .user-info-text p { font-size: 12px; color: #999; }

        .user-badge { padding: 4px 10px; border-radius: 15px; font-size: 11px; font-weight: 600; }
        .user-badge.vip { background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); color: #fff; }
        .user-badge.normal { background: #eee; color: #666; }

        .user-status { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .user-status.active { background: #d4edda; color: #155724; }
        .user-status.inactive { background: #f8d7da; color: #721c24; }

        .user-orders { font-weight: 600; color: #333; }
        .user-spent { font-weight: 600; color: #D4AF37; }

        .action-btns { display: flex; gap: 8px; }
        .action-btn {
            width: 35px; height: 35px; border: none; border-radius: 8px; cursor: pointer;
            transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;
        }
        .action-btn.view { background: rgba(52,152,219,0.15); color: #3498db; }
        .action-btn.edit { background: rgba(212,175,55,0.15); color: #D4AF37; }
        .action-btn.delete { background: rgba(231,76,60,0.15); color: #e74c3c; }
        .action-btn:hover { transform: scale(1.1); }

        /* Pagination */
        .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 25px; }
        .pagination a, .pagination span { padding: 10px 15px; border-radius: 8px; font-size: 14px; text-decoration: none; }
        .pagination a { background: #fff; color: #333; border: 2px solid #eee; }
        .pagination a:hover { border-color: #D4AF37; color: #D4AF37; }
        .pagination span.current { background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); color: #fff; }

        @media (max-width: 1200px) { .user-stats { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .admin-sidebar { transform: translateX(-100%); } .admin-main { margin-left: 0; } .user-stats { grid-template-columns: 1fr; } }
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
            <li><a href="/autocar/admin/categories"><i class="fas fa-tags"></i> Danh mục</a></li>
            <li class="sidebar-menu-title">Kinh doanh</li>
            <li><a href="/autocar/admin/orders"><i class="fas fa-shopping-cart"></i> Đơn hàng <span class="badge">5</span></a></li>
            <li><a href="/autocar/admin/users" class="active"><i class="fas fa-users"></i> Khách hàng</a></li>
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
            <h1>Quản lý khách hàng</h1>
            <div class="header-right">
                <div class="header-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tìm kiếm...">
                </div>
                <div class="header-profile">
                    <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
                </div>
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Danh sách khách hàng</h2>
                <button class="btn-primary" onclick="addUser()">
                    <i class="fas fa-plus"></i>
                    Thêm khách hàng
                </button>
            </div>

            <!-- User Stats -->
            <div class="user-stats">
                <div class="user-stat">
                    <div class="user-stat-icon total"><i class="fas fa-users"></i></div>
                    <div class="user-stat-info">
                        <h3>156</h3>
                        <p>Tổng khách hàng</p>
                    </div>
                </div>
                <div class="user-stat">
                    <div class="user-stat-icon active"><i class="fas fa-user-check"></i></div>
                    <div class="user-stat-info">
                        <h3>142</h3>
                        <p>Đang hoạt động</p>
                    </div>
                </div>
                <div class="user-stat">
                    <div class="user-stat-icon vip"><i class="fas fa-crown"></i></div>
                    <div class="user-stat-info">
                        <h3>28</h3>
                        <p>Khách VIP</p>
                    </div>
                </div>
                <div class="user-stat">
                    <div class="user-stat-icon new"><i class="fas fa-user-plus"></i></div>
                    <div class="user-stat-info">
                        <h3>12</h3>
                        <p>Mới tháng này</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label>Loại:</label>
                    <select>
                        <option value="">Tất cả</option>
                        <option value="vip">VIP</option>
                        <option value="normal">Thường</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Trạng thái:</label>
                    <select>
                        <option value="">Tất cả</option>
                        <option value="active">Hoạt động</option>
                        <option value="inactive">Không hoạt động</option>
                    </select>
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tìm theo tên, email, số điện thoại...">
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Loại</th>
                            <th>Đơn hàng</th>
                            <th>Chi tiêu</th>
                            <th>Ngày đăng ký</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#1</td>
                            <td>
                                <div class="user-info">
                                    <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=3498db&color=fff" alt="">
                                    <div class="user-info-text">
                                        <h4>Nguyễn Văn A</h4>
                                        <p>nguyenvana@email.com • 0901234567</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="user-badge vip"><i class="fas fa-crown"></i> VIP</span></td>
                            <td class="user-orders">5</td>
                            <td class="user-spent">25.5 tỷ</td>
                            <td>15/06/2024</td>
                            <td><span class="user-status active">Hoạt động</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#2</td>
                            <td>
                                <div class="user-info">
                                    <img src="https://ui-avatars.com/api/?name=Tran+Thi+B&background=2ecc71&color=fff" alt="">
                                    <div class="user-info-text">
                                        <h4>Trần Thị B</h4>
                                        <p>tranthib@email.com • 0912345678</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="user-badge vip"><i class="fas fa-crown"></i> VIP</span></td>
                            <td class="user-orders">3</td>
                            <td class="user-spent">18.2 tỷ</td>
                            <td>20/08/2024</td>
                            <td><span class="user-status active">Hoạt động</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#3</td>
                            <td>
                                <div class="user-info">
                                    <img src="https://ui-avatars.com/api/?name=Le+Van+C&background=D4AF37&color=fff" alt="">
                                    <div class="user-info-text">
                                        <h4>Lê Văn C</h4>
                                        <p>levanc@email.com • 0923456789</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="user-badge normal">Thường</span></td>
                            <td class="user-orders">1</td>
                            <td class="user-spent">5.2 tỷ</td>
                            <td>10/10/2024</td>
                            <td><span class="user-status active">Hoạt động</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#4</td>
                            <td>
                                <div class="user-info">
                                    <img src="https://ui-avatars.com/api/?name=Pham+D&background=e74c3c&color=fff" alt="">
                                    <div class="user-info-text">
                                        <h4>Phạm D</h4>
                                        <p>phamd@email.com • 0934567890</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="user-badge normal">Thường</span></td>
                            <td class="user-orders">0</td>
                            <td class="user-spent">0</td>
                            <td>25/11/2024</td>
                            <td><span class="user-status inactive">Không hoạt động</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>#5</td>
                            <td>
                                <div class="user-info">
                                    <img src="https://ui-avatars.com/api/?name=Hoang+E&background=9b59b6&color=fff" alt="">
                                    <div class="user-info-text">
                                        <h4>Hoàng E</h4>
                                        <p>hoange@email.com • 0945678901</p>
                                    </div>
                                </div>
                            </td>
                            <td><span class="user-badge vip"><i class="fas fa-crown"></i> VIP</span></td>
                            <td class="user-orders">4</td>
                            <td class="user-spent">32.8 tỷ</td>
                            <td>05/03/2024</td>
                            <td><span class="user-status active">Hoạt động</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="pagination">
                    <a href="#"><i class="fas fa-chevron-left"></i></a>
                    <span class="current">1</span>
                    <a href="#">2</a>
                    <a href="#">3</a>
                    <a href="#"><i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
    </main>

    <script>
        function addUser() {
            window.location.href = '/autocar/admin/users/add';
        }
    </script>
</body>
</html>
