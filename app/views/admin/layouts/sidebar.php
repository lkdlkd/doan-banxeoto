<aside class="admin-sidebar">
    <a href="<?= BASE_URL ?>/admin" class="sidebar-logo">
        <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="Logo">
        <div class="sidebar-logo-text">
            <span class="brand">AUTOCAR</span>
            <span class="role">Admin Panel</span>
        </div>
    </a>

    <ul class="sidebar-menu">
        <li><a href="<?= BASE_URL ?>/admin" class="<?= ($activePage ?? '') === 'dashboard' ? 'active' : '' ?>"><i class="fas fa-home"></i> Dashboard</a></li>
        
        <li class="sidebar-menu-title">Quản lý</li>
        <li><a href="<?= BASE_URL ?>/admin/cars" class="<?= ($activePage ?? '') === 'cars' ? 'active' : '' ?>"><i class="fas fa-car"></i> Quản lý xe</a></li>
        <li><a href="<?= BASE_URL ?>/admin/brands" class="<?= ($activePage ?? '') === 'brands' ? 'active' : '' ?>"><i class="fas fa-copyright"></i> Thương hiệu</a></li>
        <li><a href="<?= BASE_URL ?>/admin/categories" class="<?= ($activePage ?? '') === 'categories' ? 'active' : '' ?>"><i class="fas fa-tags"></i> Danh mục</a></li>
        
        <li class="sidebar-menu-title">Kinh doanh</li>
        <li><a href="<?= BASE_URL ?>/admin/orders" class="<?= ($activePage ?? '') === 'orders' ? 'active' : '' ?>"><i class="fas fa-shopping-cart"></i> Đơn hàng</a></li>
        <li><a href="<?= BASE_URL ?>/admin/appointments" class="<?= ($activePage ?? '') === 'appointments' ? 'active' : '' ?>"><i class="fas fa-calendar-alt"></i> Lịch xem xe</a></li>
        <li><a href="<?= BASE_URL ?>/admin/users" class="<?= ($activePage ?? '') === 'users' ? 'active' : '' ?>"><i class="fas fa-users"></i> Khách hàng</a></li>
        <li><a href="<?= BASE_URL ?>/admin/reviews" class="<?= ($activePage ?? '') === 'reviews' ? 'active' : '' ?>"><i class="fas fa-star"></i> Đánh giá</a></li>
        
        <li class="sidebar-menu-title">Hệ thống</li>
        <li><a href="<?= BASE_URL ?>/admin/contacts" class="<?= ($activePage ?? '') === 'contacts' ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Liên hệ</a></li>
        <li><a href="<?= BASE_URL ?>/admin/settings" class="<?= ($activePage ?? '') === 'settings' ? 'active' : '' ?>"><i class="fas fa-cog"></i> Cài đặt</a></li>
        <li><a href="<?= BASE_URL ?>/" class="<?= ($activePage ?? '') === 'website' ? 'active' : '' ?>"><i class="fas fa-globe"></i> Xem website</a></li>
        <li><a href="<?= BASE_URL ?>/logout"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a></li>
    </ul>
</aside>
