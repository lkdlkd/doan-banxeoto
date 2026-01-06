<?php if (!defined('BASE_URL')) { require_once __DIR__ . '/../../../config/config.php'; } ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'AutoCar - Siêu Xe Cao Cấp' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
            <div class="top-bar-left">
                <div class="top-bar-item">
                    <span class="icon">&#128205;</span>
                    <span>126 Nguyen Thien Thanh, Phuong 5, Tra Vinh</span>
                </div>
                <div class="top-bar-divider"></div>
                <div class="top-bar-item">
                    <span class="icon">&#128337;</span>
                    <span>Mon - Sun: 9:00 AM - 7:00 PM</span>
                </div>
            </div>
            <div class="top-bar-right">
                <a href="tel:0368920249" class="phone-link">
                    <span class="icon">&#128222;</span>
                    <span>Hotline: <strong>0368 920 249</strong></span>
                </a>
                <div class="top-bar-divider"></div>
                <a href="/compare" class="compare-btn" title="So sánh xe">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="17 1 21 5 17 9"></polyline>
                        <path d="M3 11V9a4 4 0 0 1 4-4h14"></path>
                        <polyline points="7 23 3 19 7 15"></polyline>
                        <path d="M21 13v2a4 4 0 0 1-4 4H3"></path>
                    </svg>
                    <span class="compare-count">0/4</span>
                </a>
                <div class="top-bar-divider"></div>
                <a href="/cart" class="cart-btn" title="Giỏ hàng">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-count">0</span>
                </a>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-dropdown">
                        <button class="user-btn">
                            <span class="user-greeting">Xin chào, <strong><?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?></strong></span>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <i class="fas fa-chevron-down dropdown-arrow"></i>
                        </button>
                        <div class="user-dropdown-menu">
                            <a href="/profile" class="dropdown-item">
                                <i class="fas fa-user"></i>
                                <span>Thông tin cá nhân</span>
                            </a>
                            <a href="/orders" class="dropdown-item">
                                <i class="fas fa-shopping-bag"></i>
                                <span>Đơn hàng của tôi</span>
                            </a>
                            <a href="/appointments" class="dropdown-item">
                                <i class="fas fa-calendar-check"></i>
                                <span>Lịch hẹn xem xe</span>
                            </a>
                            <a href="/favorites" class="dropdown-item">
                                <i class="fas fa-heart"></i>
                                <span>Xe yêu thích</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <a href="/admin/dashboard" class="dropdown-item">
                                <i class="fas fa-cog"></i>
                                <span>Quản trị</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <?php endif; ?>
                            <a href="/logout" class="dropdown-item logout">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Đăng xuất</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="/login" class="login-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                        <span>Đăng nhập</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Header -->
        <header>
            <a href="/" class="logo">
                <img src="/assets/images/logo.png" alt="AutoCar Logo" class="logo-img" style="height: 65px;">
                <div class="logo-text-header">
                    <span class="store-name">AUTOCAR</span>
                    <span class="store-slogan">Luxury & Performance</span>
                </div>
            </a>
        <nav>
            <ul>
                <li><a href="/" class="<?= ($currentPage ?? '') === 'home' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/about" class="<?= ($currentPage ?? '') === 'about' ? 'active' : '' ?>">About</a></li>
                <li><a href="/cars" class="<?= ($currentPage ?? '') === 'cars' ? 'active' : '' ?>">Cars</a></li>
                <li><a href="/contact" class="<?= ($currentPage ?? '') === 'contact' ? 'active' : '' ?>">Contact</a></li>
            </ul>
        </nav>
    </header>
