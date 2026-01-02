<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'AutoCar - Siêu Xe Cao Cấp' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/autocar/assets/css/style.css">
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
                <a href="/autocar/cart" class="cart-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-count">0</span>
                </a>
                <?php if (isset($_SESSION['user'])): ?>
                    <a href="/autocar/profile" class="login-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </a>
                <?php else: ?>
                    <a href="/autocar/login" class="login-btn">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                            <polyline points="10 17 15 12 10 7"></polyline>
                            <line x1="15" y1="12" x2="3" y2="12"></line>
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Main Header -->
        <header>
            <a href="/autocar/" class="logo">
                <img src="/autocar/assets/images/logo.png" alt="AutoCar Logo" class="logo-img" style="height: 65px;">
                <div class="logo-text-header">
                    <span class="store-name">AUTOCAR</span>
                    <span class="store-slogan">Luxury & Performance</span>
                </div>
            </a>
        <nav>
            <ul>
                <li><a href="/autocar/" class="<?= ($currentPage ?? '') === 'home' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/autocar/about" class="<?= ($currentPage ?? '') === 'about' ? 'active' : '' ?>">About</a></li>
                <li><a href="/autocar/cars" class="<?= ($currentPage ?? '') === 'cars' ? 'active' : '' ?>">Cars</a></li>
                <li><a href="/autocar/contact" class="<?= ($currentPage ?? '') === 'contact' ? 'active' : '' ?>">Contact</a></li>
            </ul>
        </nav>
    </header>
