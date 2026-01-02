<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - AutoCar Admin</title>
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
        .header-search input { padding: 10px 15px 10px 40px; border: 2px solid #eee; border-radius: 10px; font-size: 14px; width: 250px; transition: all 0.3s ease; }
        .header-search input:focus { outline: none; border-color: #D4AF37; }
        .header-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }
        .header-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; }
        .header-profile img { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; }

        .admin-content { padding: 30px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .page-header h2 { font-family: 'Playfair Display', serif; font-size: 28px; color: #1a1a1a; }

        /* Stats Row */
        .order-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .order-stat {
            background: #fff; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }
        .order-stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .order-stat-icon.pending { background: rgba(241,196,15,0.15); color: #f1c40f; }
        .order-stat-icon.processing { background: rgba(52,152,219,0.15); color: #3498db; }
        .order-stat-icon.completed { background: rgba(46,204,113,0.15); color: #2ecc71; }
        .order-stat-icon.cancelled { background: rgba(231,76,60,0.15); color: #e74c3c; }
        .order-stat-info h3 { font-size: 24px; font-weight: 700; color: #1a1a1a; }
        .order-stat-info p { font-size: 13px; color: #999; }

        /* Filters */
        .filters-bar {
            background: #fff; border-radius: 15px; padding: 20px; margin-bottom: 25px;
            display: flex; gap: 15px; flex-wrap: wrap; align-items: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .filter-group { display: flex; align-items: center; gap: 10px; }
        .filter-group label { font-size: 13px; color: #666; font-weight: 500; }
        .filter-group select, .filter-group input {
            padding: 10px 15px; border: 2px solid #eee; border-radius: 8px; font-size: 14px; transition: all 0.3s ease;
        }
        .filter-group select:focus, .filter-group input:focus { outline: none; border-color: #D4AF37; }
        .filter-search { flex: 1; min-width: 200px; position: relative; }
        .filter-search input { width: 100%; padding: 10px 15px 10px 40px; }
        .filter-search i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999; }

        /* Table */
        .card { background: #fff; border-radius: 15px; padding: 25px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); }
        .orders-table { width: 100%; border-collapse: collapse; }
        .orders-table th, .orders-table td { padding: 15px; text-align: left; border-bottom: 1px solid #f5f5f5; }
        .orders-table th { font-size: 12px; color: #999; text-transform: uppercase; font-weight: 600; background: #fafafa; }
        .orders-table th:first-child { border-radius: 8px 0 0 8px; }
        .orders-table th:last-child { border-radius: 0 8px 8px 0; }
        .orders-table tr:hover { background: #fafafa; }

        .customer-info { display: flex; align-items: center; gap: 12px; }
        .customer-info img { width: 40px; height: 40px; border-radius: 10px; object-fit: cover; }
        .customer-info-text h4 { font-size: 14px; color: #1a1a1a; margin-bottom: 2px; }
        .customer-info-text p { font-size: 12px; color: #999; }

        .order-car { display: flex; align-items: center; gap: 10px; }
        .order-car img { width: 60px; height: 40px; border-radius: 6px; object-fit: cover; }
        .order-car span { font-size: 13px; color: #333; }

        .order-price { font-weight: 600; color: #D4AF37; }
        .order-date { font-size: 13px; color: #666; }

        .order-status { padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .order-status.pending { background: #fff3cd; color: #856404; }
        .order-status.processing { background: #cce5ff; color: #004085; }
        .order-status.completed { background: #d4edda; color: #155724; }
        .order-status.cancelled { background: #f8d7da; color: #721c24; }

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
        .pagination a, .pagination span { padding: 10px 15px; border-radius: 8px; font-size: 14px; text-decoration: none; transition: all 0.3s ease; }
        .pagination a { background: #fff; color: #333; border: 2px solid #eee; }
        .pagination a:hover { border-color: #D4AF37; color: #D4AF37; }
        .pagination span.current { background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); color: #fff; }

        /* Modal */
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
        .modal-content { background: #fff; border-radius: 15px; padding: 30px; width: 500px; max-width: 90%; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .modal-header h3 { font-family: 'Playfair Display', serif; font-size: 20px; }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #999; }
        .modal-body { margin-bottom: 20px; }
        .modal-footer { display: flex; gap: 10px; justify-content: flex-end; }
        .btn { padding: 12px 25px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; border: none; transition: all 0.3s ease; }
        .btn-primary { background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); color: #fff; }
        .btn-secondary { background: #f5f5f5; color: #333; }

        @media (max-width: 1200px) { .order-stats { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .admin-sidebar { transform: translateX(-100%); } .admin-main { margin-left: 0; } .order-stats { grid-template-columns: 1fr; } }
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
            <li><a href="/autocar/admin/orders" class="active"><i class="fas fa-shopping-cart"></i> Đơn hàng <span class="badge">5</span></a></li>
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
            <h1>Quản lý đơn hàng</h1>
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
                <h2>Danh sách đơn hàng</h2>
            </div>

            <!-- Order Stats -->
            <div class="order-stats">
                <div class="order-stat">
                    <div class="order-stat-icon pending"><i class="fas fa-clock"></i></div>
                    <div class="order-stat-info">
                        <h3>8</h3>
                        <p>Chờ duyệt</p>
                    </div>
                </div>
                <div class="order-stat">
                    <div class="order-stat-icon processing"><i class="fas fa-spinner"></i></div>
                    <div class="order-stat-info">
                        <h3>5</h3>
                        <p>Đang xử lý</p>
                    </div>
                </div>
                <div class="order-stat">
                    <div class="order-stat-icon completed"><i class="fas fa-check"></i></div>
                    <div class="order-stat-info">
                        <h3>42</h3>
                        <p>Hoàn thành</p>
                    </div>
                </div>
                <div class="order-stat">
                    <div class="order-stat-icon cancelled"><i class="fas fa-times"></i></div>
                    <div class="order-stat-info">
                        <h3>3</h3>
                        <p>Đã hủy</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label>Trạng thái:</label>
                    <select>
                        <option value="">Tất cả</option>
                        <option value="pending">Chờ duyệt</option>
                        <option value="processing">Đang xử lý</option>
                        <option value="completed">Hoàn thành</option>
                        <option value="cancelled">Đã hủy</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Từ ngày:</label>
                    <input type="date">
                </div>
                <div class="filter-group">
                    <label>Đến ngày:</label>
                    <input type="date">
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tìm theo mã đơn, tên khách hàng...">
                </div>
            </div>

            <!-- Orders Table -->
            <div class="card">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Xe</th>
                            <th>Giá trị</th>
                            <th>Ngày đặt</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>#ORD-1234</strong></td>
                            <td>
                                <div class="customer-info">
                                    <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=3498db&color=fff" alt="">
                                    <div class="customer-info-text">
                                        <h4>Nguyễn Văn A</h4>
                                        <p>0901234567</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="order-car">
                                    <img src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=100" alt="">
                                    <span>Mercedes S-Class</span>
                                </div>
                            </td>
                            <td class="order-price">5.2 tỷ</td>
                            <td class="order-date">02/01/2026</td>
                            <td><span class="order-status pending">Chờ duyệt</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view" onclick="viewOrder(1234)"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn edit" onclick="editOrder(1234)"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete" onclick="deleteOrder(1234)"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#ORD-1233</strong></td>
                            <td>
                                <div class="customer-info">
                                    <img src="https://ui-avatars.com/api/?name=Tran+Thi+B&background=2ecc71&color=fff" alt="">
                                    <div class="customer-info-text">
                                        <h4>Trần Thị B</h4>
                                        <p>0912345678</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="order-car">
                                    <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?w=100" alt="">
                                    <span>BMW 7 Series</span>
                                </div>
                            </td>
                            <td class="order-price">6.8 tỷ</td>
                            <td class="order-date">01/01/2026</td>
                            <td><span class="order-status processing">Đang xử lý</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#ORD-1232</strong></td>
                            <td>
                                <div class="customer-info">
                                    <img src="https://ui-avatars.com/api/?name=Le+Van+C&background=D4AF37&color=fff" alt="">
                                    <div class="customer-info-text">
                                        <h4>Lê Văn C</h4>
                                        <p>0923456789</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="order-car">
                                    <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=100" alt="">
                                    <span>Porsche 911</span>
                                </div>
                            </td>
                            <td class="order-price">15.5 tỷ</td>
                            <td class="order-date">30/12/2025</td>
                            <td><span class="order-status completed">Hoàn thành</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#ORD-1231</strong></td>
                            <td>
                                <div class="customer-info">
                                    <img src="https://ui-avatars.com/api/?name=Pham+D&background=e74c3c&color=fff" alt="">
                                    <div class="customer-info-text">
                                        <h4>Phạm D</h4>
                                        <p>0934567890</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="order-car">
                                    <img src="https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=100" alt="">
                                    <span>Audi Q8</span>
                                </div>
                            </td>
                            <td class="order-price">4.9 tỷ</td>
                            <td class="order-date">28/12/2025</td>
                            <td><span class="order-status cancelled">Đã hủy</span></td>
                            <td>
                                <div class="action-btns">
                                    <button class="action-btn view"><i class="fas fa-eye"></i></button>
                                    <button class="action-btn edit"><i class="fas fa-edit"></i></button>
                                    <button class="action-btn delete"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>#ORD-1230</strong></td>
                            <td>
                                <div class="customer-info">
                                    <img src="https://ui-avatars.com/api/?name=Hoang+E&background=9b59b6&color=fff" alt="">
                                    <div class="customer-info-text">
                                        <h4>Hoàng E</h4>
                                        <p>0945678901</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="order-car">
                                    <img src="https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=100" alt="">
                                    <span>Lexus LS 500</span>
                                </div>
                            </td>
                            <td class="order-price">7.2 tỷ</td>
                            <td class="order-date">25/12/2025</td>
                            <td><span class="order-status completed">Hoàn thành</span></td>
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

    <!-- Order Detail Modal -->
    <div class="modal" id="orderModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Chi tiết đơn hàng #ORD-1234</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Khách hàng:</strong> Nguyễn Văn A</p>
                <p><strong>Điện thoại:</strong> 0901234567</p>
                <p><strong>Email:</strong> nguyenvana@email.com</p>
                <p><strong>Xe:</strong> Mercedes-Benz S-Class 2024</p>
                <p><strong>Giá trị:</strong> 5,200,000,000 VNĐ</p>
                <p><strong>Trạng thái:</strong> Chờ duyệt</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal()">Đóng</button>
                <button class="btn btn-primary">Duyệt đơn</button>
            </div>
        </div>
    </div>

    <script>
        function viewOrder(id) {
            document.getElementById('orderModal').classList.add('active');
        }
        function closeModal() {
            document.getElementById('orderModal').classList.remove('active');
        }
        function editOrder(id) {
            window.location.href = '/autocar/admin/orders/edit/' + id;
        }
        function deleteOrder(id) {
            if (confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')) {
                window.location.href = '/autocar/admin/orders/delete/' + id;
            }
        }
    </script>
</body>
</html>
