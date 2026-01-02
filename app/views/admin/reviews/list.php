<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đánh giá - AutoCar Admin</title>
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

        /* Stats */
        .review-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .review-stat {
            background: #fff; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }
        .review-stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .review-stat-icon.total { background: rgba(52,152,219,0.15); color: #3498db; }
        .review-stat-icon.avg { background: rgba(212,175,55,0.15); color: #D4AF37; }
        .review-stat-icon.positive { background: rgba(46,204,113,0.15); color: #2ecc71; }
        .review-stat-icon.pending { background: rgba(241,196,15,0.15); color: #f1c40f; }
        .review-stat-info h3 { font-size: 24px; font-weight: 700; color: #1a1a1a; }
        .review-stat-info p { font-size: 13px; color: #999; }

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

        /* Reviews List */
        .reviews-list { display: flex; flex-direction: column; gap: 20px; }
        .review-card {
            background: #fff; border-radius: 15px; padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        }
        .review-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
        .review-user { display: flex; align-items: center; gap: 15px; }
        .review-user img { width: 50px; height: 50px; border-radius: 12px; object-fit: cover; }
        .review-user-info h4 { font-size: 15px; color: #1a1a1a; margin-bottom: 3px; }
        .review-user-info p { font-size: 12px; color: #999; }
        .review-meta { text-align: right; }
        .review-date { font-size: 12px; color: #999; margin-bottom: 5px; }
        .review-status { padding: 4px 12px; border-radius: 15px; font-size: 11px; font-weight: 600; }
        .review-status.approved { background: #d4edda; color: #155724; }
        .review-status.pending { background: #fff3cd; color: #856404; }
        .review-status.rejected { background: #f8d7da; color: #721c24; }

        .review-car { display: flex; align-items: center; gap: 12px; padding: 15px; background: #f9f7f3; border-radius: 10px; margin-bottom: 15px; }
        .review-car img { width: 80px; height: 55px; border-radius: 8px; object-fit: cover; }
        .review-car-info h5 { font-size: 14px; color: #1a1a1a; margin-bottom: 3px; }
        .review-car-info p { font-size: 12px; color: #999; }

        .review-rating { display: flex; gap: 5px; margin-bottom: 10px; }
        .review-rating i { color: #D4AF37; font-size: 16px; }
        .review-rating i.empty { color: #ddd; }

        .review-content { font-size: 14px; color: #555; line-height: 1.7; margin-bottom: 15px; }

        .review-actions { display: flex; gap: 10px; padding-top: 15px; border-top: 1px solid #f5f5f5; }
        .review-action {
            padding: 8px 16px; border: 2px solid #eee; border-radius: 8px;
            background: #fff; cursor: pointer; display: flex; align-items: center;
            gap: 6px; transition: all 0.3s ease; font-size: 13px;
        }
        .review-action:hover { border-color: #D4AF37; color: #D4AF37; }
        .review-action.approve { border-color: #2ecc71; color: #2ecc71; }
        .review-action.approve:hover { background: #2ecc71; color: #fff; }
        .review-action.reject { border-color: #e74c3c; color: #e74c3c; }
        .review-action.reject:hover { background: #e74c3c; color: #fff; }
        .review-action.delete:hover { border-color: #e74c3c; color: #e74c3c; }

        /* Pagination */
        .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 25px; }
        .pagination a, .pagination span { padding: 10px 15px; border-radius: 8px; font-size: 14px; text-decoration: none; }
        .pagination a { background: #fff; color: #333; border: 2px solid #eee; }
        .pagination a:hover { border-color: #D4AF37; color: #D4AF37; }
        .pagination span.current { background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); color: #fff; }

        @media (max-width: 1200px) { .review-stats { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 768px) { .admin-sidebar { transform: translateX(-100%); } .admin-main { margin-left: 0; } .review-stats { grid-template-columns: 1fr; } }
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
            <li><a href="/autocar/admin/users"><i class="fas fa-users"></i> Khách hàng</a></li>
            <li><a href="/autocar/admin/reviews" class="active"><i class="fas fa-star"></i> Đánh giá</a></li>
            <li class="sidebar-menu-title">Hệ thống</li>
            <li><a href="/autocar/admin/contacts"><i class="fas fa-envelope"></i> Liên hệ <span class="badge">3</span></a></li>
            <li><a href="/autocar/admin/settings"><i class="fas fa-cog"></i> Cài đặt</a></li>
            <li><a href="/autocar/"><i class="fas fa-globe"></i> Xem website</a></li>
            <li><a href="/autocar/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Quản lý đánh giá</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Danh sách đánh giá</h2>
            </div>

            <!-- Review Stats -->
            <div class="review-stats">
                <div class="review-stat">
                    <div class="review-stat-icon total"><i class="fas fa-comments"></i></div>
                    <div class="review-stat-info">
                        <h3>156</h3>
                        <p>Tổng đánh giá</p>
                    </div>
                </div>
                <div class="review-stat">
                    <div class="review-stat-icon avg"><i class="fas fa-star"></i></div>
                    <div class="review-stat-info">
                        <h3>4.8</h3>
                        <p>Điểm trung bình</p>
                    </div>
                </div>
                <div class="review-stat">
                    <div class="review-stat-icon positive"><i class="fas fa-thumbs-up"></i></div>
                    <div class="review-stat-info">
                        <h3>142</h3>
                        <p>Đánh giá tích cực</p>
                    </div>
                </div>
                <div class="review-stat">
                    <div class="review-stat-icon pending"><i class="fas fa-clock"></i></div>
                    <div class="review-stat-info">
                        <h3>8</h3>
                        <p>Chờ duyệt</p>
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
                        <option value="approved">Đã duyệt</option>
                        <option value="rejected">Từ chối</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Đánh giá:</label>
                    <select>
                        <option value="">Tất cả</option>
                        <option value="5">5 sao</option>
                        <option value="4">4 sao</option>
                        <option value="3">3 sao</option>
                        <option value="2">2 sao</option>
                        <option value="1">1 sao</option>
                    </select>
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tìm theo tên khách hàng, nội dung...">
                </div>
            </div>

            <!-- Reviews List -->
            <div class="reviews-list">
                <div class="review-card">
                    <div class="review-header">
                        <div class="review-user">
                            <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=3498db&color=fff" alt="">
                            <div class="review-user-info">
                                <h4>Nguyễn Văn A</h4>
                                <p>nguyenvana@email.com</p>
                            </div>
                        </div>
                        <div class="review-meta">
                            <div class="review-date">02/01/2026 - 14:30</div>
                            <span class="review-status pending">Chờ duyệt</span>
                        </div>
                    </div>
                    <div class="review-car">
                        <img src="https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=150" alt="">
                        <div class="review-car-info">
                            <h5>Mercedes-Benz S-Class 2024</h5>
                            <p>Sedan • 5.2 tỷ VNĐ</p>
                        </div>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-content">
                        Xe rất đẹp và sang trọng! Dịch vụ tư vấn chuyên nghiệp, nhân viên nhiệt tình. Showroom AutoCar thực sự là địa chỉ đáng tin cậy cho những ai đang tìm kiếm xe hạng sang. Chắc chắn sẽ giới thiệu cho bạn bè và người thân.
                    </p>
                    <div class="review-actions">
                        <button class="review-action approve"><i class="fas fa-check"></i> Duyệt</button>
                        <button class="review-action reject"><i class="fas fa-times"></i> Từ chối</button>
                        <button class="review-action"><i class="fas fa-reply"></i> Phản hồi</button>
                        <button class="review-action delete"><i class="fas fa-trash"></i> Xóa</button>
                    </div>
                </div>

                <div class="review-card">
                    <div class="review-header">
                        <div class="review-user">
                            <img src="https://ui-avatars.com/api/?name=Tran+Thi+B&background=2ecc71&color=fff" alt="">
                            <div class="review-user-info">
                                <h4>Trần Thị B</h4>
                                <p>tranthib@email.com</p>
                            </div>
                        </div>
                        <div class="review-meta">
                            <div class="review-date">01/01/2026 - 09:15</div>
                            <span class="review-status approved">Đã duyệt</span>
                        </div>
                    </div>
                    <div class="review-car">
                        <img src="https://images.unsplash.com/photo-1555215695-3004980ad54e?w=150" alt="">
                        <div class="review-car-info">
                            <h5>BMW 7 Series 2024</h5>
                            <p>Sedan • 6.8 tỷ VNĐ</p>
                        </div>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star empty"></i>
                    </div>
                    <p class="review-content">
                        Mình rất hài lòng với chiếc BMW 7 Series. Xe vận hành êm ái, nội thất cao cấp. Cảm ơn đội ngũ AutoCar đã hỗ trợ tận tình trong suốt quá trình mua xe. Giá cả hợp lý so với chất lượng.
                    </p>
                    <div class="review-actions">
                        <button class="review-action"><i class="fas fa-reply"></i> Phản hồi</button>
                        <button class="review-action delete"><i class="fas fa-trash"></i> Xóa</button>
                    </div>
                </div>

                <div class="review-card">
                    <div class="review-header">
                        <div class="review-user">
                            <img src="https://ui-avatars.com/api/?name=Le+Van+C&background=D4AF37&color=fff" alt="">
                            <div class="review-user-info">
                                <h4>Lê Văn C</h4>
                                <p>levanc@email.com</p>
                            </div>
                        </div>
                        <div class="review-meta">
                            <div class="review-date">30/12/2025 - 16:45</div>
                            <span class="review-status approved">Đã duyệt</span>
                        </div>
                    </div>
                    <div class="review-car">
                        <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=150" alt="">
                        <div class="review-car-info">
                            <h5>Porsche 911 Turbo S</h5>
                            <p>Sports Car • 15.5 tỷ VNĐ</p>
                        </div>
                    </div>
                    <div class="review-rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="review-content">
                        Chiếc Porsche 911 này đúng là giấc mơ của tôi! AutoCar có nguồn xe chất lượng, thủ tục nhanh gọn. Đặc biệt ấn tượng với showroom sang trọng và đội ngũ am hiểu về xe. 10/10 điểm!
                    </p>
                    <div class="review-actions">
                        <button class="review-action"><i class="fas fa-reply"></i> Phản hồi</button>
                        <button class="review-action delete"><i class="fas fa-trash"></i> Xóa</button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <a href="#"><i class="fas fa-chevron-left"></i></a>
                <span class="current">1</span>
                <a href="#">2</a>
                <a href="#">3</a>
                <a href="#"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
    </main>

    <script>
        function approveReview(id) {
            if (confirm('Duyệt đánh giá này?')) {
                window.location.href = '/autocar/admin/reviews/approve/' + id;
            }
        }
        function rejectReview(id) {
            if (confirm('Từ chối đánh giá này?')) {
                window.location.href = '/autocar/admin/reviews/reject/' + id;
            }
        }
        function deleteReview(id) {
            if (confirm('Xóa đánh giá này?')) {
                window.location.href = '/autocar/admin/reviews/delete/' + id;
            }
        }
    </script>
</body>
</html>
