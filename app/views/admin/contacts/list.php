<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý liên hệ - AutoCar Admin</title>
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
        .contact-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
        .contact-stat {
            background: #fff; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.05);
        }
        .contact-stat-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .contact-stat-icon.total { background: rgba(52,152,219,0.15); color: #3498db; }
        .contact-stat-icon.unread { background: rgba(212,175,55,0.15); color: #D4AF37; }
        .contact-stat-icon.replied { background: rgba(46,204,113,0.15); color: #2ecc71; }
        .contact-stat-icon.today { background: rgba(155,89,182,0.15); color: #9b59b6; }
        .contact-stat-info h3 { font-size: 24px; font-weight: 700; color: #1a1a1a; }
        .contact-stat-info p { font-size: 13px; color: #999; }

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

        /* Contact List */
        .contact-list { display: flex; flex-direction: column; gap: 15px; }
        .contact-card {
            background: #fff; border-radius: 15px; overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); cursor: pointer;
            transition: all 0.3s ease;
        }
        .contact-card:hover { box-shadow: 0 8px 30px rgba(0,0,0,0.1); }
        .contact-card.unread { border-left: 4px solid #D4AF37; }
        .contact-card.unread .contact-subject { font-weight: 600; }

        .contact-preview { padding: 20px; display: flex; align-items: center; gap: 20px; }
        .contact-sender { display: flex; align-items: center; gap: 15px; min-width: 250px; }
        .contact-sender img { width: 45px; height: 45px; border-radius: 10px; object-fit: cover; }
        .contact-sender-info h4 { font-size: 14px; color: #1a1a1a; margin-bottom: 3px; }
        .contact-sender-info p { font-size: 12px; color: #999; }

        .contact-content { flex: 1; }
        .contact-subject { font-size: 14px; color: #1a1a1a; margin-bottom: 4px; }
        .contact-excerpt { font-size: 13px; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .contact-meta { text-align: right; min-width: 120px; }
        .contact-date { font-size: 12px; color: #999; margin-bottom: 5px; }
        .contact-status { padding: 4px 10px; border-radius: 12px; font-size: 10px; font-weight: 600; display: inline-block; }
        .contact-status.unread { background: #fff3cd; color: #856404; }
        .contact-status.replied { background: #d4edda; color: #155724; }
        .contact-status.read { background: #e9ecef; color: #6c757d; }

        /* Contact Detail Modal */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.7); display: none; justify-content: center;
            align-items: center; z-index: 1000; padding: 20px;
        }
        .modal-overlay.active { display: flex; }
        .modal {
            background: #fff; border-radius: 20px; width: 100%; max-width: 700px;
            max-height: 90vh; overflow-y: auto;
        }
        .modal-header {
            padding: 25px 30px; border-bottom: 1px solid #eee;
            display: flex; justify-content: space-between; align-items: center;
        }
        .modal-header h3 { font-family: 'Playfair Display', serif; font-size: 22px; color: #1a1a1a; }
        .modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: #999; }
        .modal-close:hover { color: #1a1a1a; }

        .modal-body { padding: 30px; }
        .contact-detail-header { display: flex; align-items: center; gap: 15px; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #f5f5f5; }
        .contact-detail-header img { width: 60px; height: 60px; border-radius: 12px; }
        .contact-detail-info h4 { font-size: 18px; color: #1a1a1a; margin-bottom: 3px; }
        .contact-detail-info p { font-size: 13px; color: #888; }
        .contact-detail-info p a { color: #D4AF37; text-decoration: none; }

        .contact-detail-meta { display: flex; gap: 20px; margin-bottom: 20px; }
        .contact-detail-meta span { font-size: 13px; color: #666; }
        .contact-detail-meta span i { margin-right: 6px; color: #D4AF37; }

        .contact-detail-subject { font-size: 18px; font-weight: 600; color: #1a1a1a; margin-bottom: 15px; }
        .contact-detail-message { font-size: 14px; color: #555; line-height: 1.8; padding: 20px; background: #f9f7f3; border-radius: 12px; }

        .contact-reply { margin-top: 25px; padding-top: 25px; border-top: 1px solid #f5f5f5; }
        .contact-reply h5 { font-size: 15px; color: #1a1a1a; margin-bottom: 15px; }
        .contact-reply textarea {
            width: 100%; height: 120px; padding: 15px; border: 2px solid #eee; border-radius: 12px;
            font-family: 'Montserrat', sans-serif; font-size: 14px; resize: none;
        }
        .contact-reply textarea:focus { outline: none; border-color: #D4AF37; }

        .modal-actions { display: flex; gap: 10px; margin-top: 20px; justify-content: flex-end; }
        .modal-btn {
            padding: 12px 25px; border-radius: 10px; font-size: 14px; font-weight: 500;
            cursor: pointer; transition: all 0.3s ease;
        }
        .modal-btn.primary { background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); border: none; color: #fff; }
        .modal-btn.primary:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(212,175,55,0.4); }
        .modal-btn.secondary { background: #fff; border: 2px solid #eee; color: #666; }
        .modal-btn.secondary:hover { border-color: #D4AF37; color: #D4AF37; }
        .modal-btn.danger { background: #fff; border: 2px solid #e74c3c; color: #e74c3c; }
        .modal-btn.danger:hover { background: #e74c3c; color: #fff; }

        /* Pagination */
        .pagination { display: flex; justify-content: center; gap: 8px; margin-top: 25px; }
        .pagination a, .pagination span { padding: 10px 15px; border-radius: 8px; font-size: 14px; text-decoration: none; }
        .pagination a { background: #fff; color: #333; border: 2px solid #eee; }
        .pagination a:hover { border-color: #D4AF37; color: #D4AF37; }
        .pagination span.current { background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%); color: #fff; }

        @media (max-width: 1200px) { .contact-stats { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 992px) { .contact-sender { min-width: auto; } }
        @media (max-width: 768px) { .admin-sidebar { transform: translateX(-100%); } .admin-main { margin-left: 0; } .contact-stats { grid-template-columns: 1fr; } .contact-preview { flex-wrap: wrap; } }
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
            <li><a href="/autocar/admin/reviews"><i class="fas fa-star"></i> Đánh giá</a></li>
            <li class="sidebar-menu-title">Hệ thống</li>
            <li><a href="/autocar/admin/contacts" class="active"><i class="fas fa-envelope"></i> Liên hệ <span class="badge">3</span></a></li>
            <li><a href="/autocar/admin/settings"><i class="fas fa-cog"></i> Cài đặt</a></li>
            <li><a href="/autocar/"><i class="fas fa-globe"></i> Xem website</a></li>
            <li><a href="/autocar/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
        </ul>
    </aside>

    <main class="admin-main">
        <header class="admin-header">
            <h1>Quản lý liên hệ</h1>
            <div class="header-profile">
                <img src="https://ui-avatars.com/api/?name=Admin&background=D4AF37&color=fff" alt="Admin">
            </div>
        </header>

        <div class="admin-content">
            <div class="page-header">
                <h2>Hộp thư liên hệ</h2>
            </div>

            <!-- Contact Stats -->
            <div class="contact-stats">
                <div class="contact-stat">
                    <div class="contact-stat-icon total"><i class="fas fa-inbox"></i></div>
                    <div class="contact-stat-info">
                        <h3>48</h3>
                        <p>Tổng tin nhắn</p>
                    </div>
                </div>
                <div class="contact-stat">
                    <div class="contact-stat-icon unread"><i class="fas fa-envelope"></i></div>
                    <div class="contact-stat-info">
                        <h3>3</h3>
                        <p>Chưa đọc</p>
                    </div>
                </div>
                <div class="contact-stat">
                    <div class="contact-stat-icon replied"><i class="fas fa-reply"></i></div>
                    <div class="contact-stat-info">
                        <h3>42</h3>
                        <p>Đã phản hồi</p>
                    </div>
                </div>
                <div class="contact-stat">
                    <div class="contact-stat-icon today"><i class="fas fa-calendar-day"></i></div>
                    <div class="contact-stat-info">
                        <h3>2</h3>
                        <p>Hôm nay</p>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters-bar">
                <div class="filter-group">
                    <label>Trạng thái:</label>
                    <select>
                        <option value="">Tất cả</option>
                        <option value="unread">Chưa đọc</option>
                        <option value="read">Đã đọc</option>
                        <option value="replied">Đã phản hồi</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Thời gian:</label>
                    <select>
                        <option value="">Tất cả</option>
                        <option value="today">Hôm nay</option>
                        <option value="week">Tuần này</option>
                        <option value="month">Tháng này</option>
                    </select>
                </div>
                <div class="filter-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Tìm theo tên, email, nội dung...">
                </div>
            </div>

            <!-- Contact List -->
            <div class="contact-list">
                <div class="contact-card unread" onclick="openContactModal(1)">
                    <div class="contact-preview">
                        <div class="contact-sender">
                            <img src="https://ui-avatars.com/api/?name=Pham+Van+D&background=3498db&color=fff" alt="">
                            <div class="contact-sender-info">
                                <h4>Phạm Văn D</h4>
                                <p>phamvand@email.com</p>
                            </div>
                        </div>
                        <div class="contact-content">
                            <div class="contact-subject">Hỏi về xe Mercedes S-Class 2024</div>
                            <p class="contact-excerpt">Xin chào, tôi muốn hỏi về xe Mercedes S-Class 2024. Xe này còn hàng không ạ? Tôi muốn đặt lịch xem xe...</p>
                        </div>
                        <div class="contact-meta">
                            <div class="contact-date">2 giờ trước</div>
                            <span class="contact-status unread">Chưa đọc</span>
                        </div>
                    </div>
                </div>

                <div class="contact-card unread" onclick="openContactModal(2)">
                    <div class="contact-preview">
                        <div class="contact-sender">
                            <img src="https://ui-avatars.com/api/?name=Hoang+Thi+E&background=e74c3c&color=fff" alt="">
                            <div class="contact-sender-info">
                                <h4>Hoàng Thị E</h4>
                                <p>hoangthie@email.com</p>
                            </div>
                        </div>
                        <div class="contact-content">
                            <div class="contact-subject">Tư vấn mua xe cho gia đình</div>
                            <p class="contact-excerpt">Chào AutoCar, gia đình tôi đang cần mua một chiếc SUV 7 chỗ trong tầm giá 3-4 tỷ. Nhờ tư vấn giúp...</p>
                        </div>
                        <div class="contact-meta">
                            <div class="contact-date">5 giờ trước</div>
                            <span class="contact-status unread">Chưa đọc</span>
                        </div>
                    </div>
                </div>

                <div class="contact-card" onclick="openContactModal(3)">
                    <div class="contact-preview">
                        <div class="contact-sender">
                            <img src="https://ui-avatars.com/api/?name=Nguyen+Van+F&background=2ecc71&color=fff" alt="">
                            <div class="contact-sender-info">
                                <h4>Nguyễn Văn F</h4>
                                <p>nguyenvanf@email.com</p>
                            </div>
                        </div>
                        <div class="contact-content">
                            <div class="contact-subject">Cảm ơn dịch vụ tuyệt vời</div>
                            <p class="contact-excerpt">Cảm ơn đội ngũ AutoCar đã hỗ trợ tôi mua chiếc BMW X5 một cách nhanh chóng và chuyên nghiệp...</p>
                        </div>
                        <div class="contact-meta">
                            <div class="contact-date">Hôm qua</div>
                            <span class="contact-status replied">Đã phản hồi</span>
                        </div>
                    </div>
                </div>

                <div class="contact-card" onclick="openContactModal(4)">
                    <div class="contact-preview">
                        <div class="contact-sender">
                            <img src="https://ui-avatars.com/api/?name=Tran+Van+G&background=D4AF37&color=fff" alt="">
                            <div class="contact-sender-info">
                                <h4>Trần Văn G</h4>
                                <p>tranvang@email.com</p>
                            </div>
                        </div>
                        <div class="contact-content">
                            <div class="contact-subject">Hỏi về chính sách bảo hành</div>
                            <p class="contact-excerpt">Tôi muốn hỏi chi tiết về chính sách bảo hành cho các dòng xe nhập khẩu tại AutoCar...</p>
                        </div>
                        <div class="contact-meta">
                            <div class="contact-date">2 ngày trước</div>
                            <span class="contact-status replied">Đã phản hồi</span>
                        </div>
                    </div>
                </div>

                <div class="contact-card" onclick="openContactModal(5)">
                    <div class="contact-preview">
                        <div class="contact-sender">
                            <img src="https://ui-avatars.com/api/?name=Le+Thi+H&background=9b59b6&color=fff" alt="">
                            <div class="contact-sender-info">
                                <h4>Lê Thị H</h4>
                                <p>lethih@email.com</p>
                            </div>
                        </div>
                        <div class="contact-content">
                            <div class="contact-subject">Đặt lịch lái thử Porsche Cayenne</div>
                            <p class="contact-excerpt">Xin chào, tôi muốn đặt lịch lái thử xe Porsche Cayenne vào cuối tuần này. Xin hãy liên hệ lại...</p>
                        </div>
                        <div class="contact-meta">
                            <div class="contact-date">3 ngày trước</div>
                            <span class="contact-status read">Đã đọc</span>
                        </div>
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

    <!-- Contact Detail Modal -->
    <div class="modal-overlay" id="contactModal">
        <div class="modal">
            <div class="modal-header">
                <h3>Chi tiết liên hệ</h3>
                <button class="modal-close" onclick="closeContactModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="contact-detail-header">
                    <img src="https://ui-avatars.com/api/?name=Pham+Van+D&background=3498db&color=fff" alt="">
                    <div class="contact-detail-info">
                        <h4>Phạm Văn D</h4>
                        <p><a href="mailto:phamvand@email.com">phamvand@email.com</a></p>
                        <p><i class="fas fa-phone"></i> 0912 345 678</p>
                    </div>
                </div>

                <div class="contact-detail-meta">
                    <span><i class="far fa-clock"></i> 02/01/2026 - 14:30</span>
                    <span><i class="fas fa-tag"></i> Hỏi về sản phẩm</span>
                </div>

                <h4 class="contact-detail-subject">Hỏi về xe Mercedes S-Class 2024</h4>
                <div class="contact-detail-message">
                    Xin chào AutoCar,<br><br>
                    Tôi muốn hỏi về xe Mercedes S-Class 2024. Xe này còn hàng không ạ? Tôi muốn đặt lịch xem xe vào cuối tuần này.<br><br>
                    Ngoài ra, tôi cũng muốn biết thêm về các gói tài chính trả góp mà bên mình đang có. Mức lãi suất hiện tại như thế nào?<br><br>
                    Mong nhận được phản hồi sớm từ AutoCar.<br><br>
                    Trân trọng,<br>
                    Phạm Văn D
                </div>

                <div class="contact-reply">
                    <h5><i class="fas fa-reply"></i> Phản hồi khách hàng</h5>
                    <textarea placeholder="Nhập nội dung phản hồi..."></textarea>
                </div>

                <div class="modal-actions">
                    <button class="modal-btn danger"><i class="fas fa-trash"></i> Xóa</button>
                    <button class="modal-btn secondary" onclick="closeContactModal()">Đóng</button>
                    <button class="modal-btn primary"><i class="fas fa-paper-plane"></i> Gửi phản hồi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openContactModal(id) {
            document.getElementById('contactModal').classList.add('active');
        }
        function closeContactModal() {
            document.getElementById('contactModal').classList.remove('active');
        }
        document.getElementById('contactModal').addEventListener('click', function(e) {
            if (e.target === this) closeContactModal();
        });
    </script>
</body>
</html>
