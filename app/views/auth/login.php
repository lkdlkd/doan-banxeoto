<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - AutoCar Luxury</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: url('https://images.unsplash.com/photo-1542282088-fe8426682b8f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center center / cover no-repeat;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0.5) 50%, rgba(0,0,0,0.7) 100%);
        }

        .login-container {
            width: 100%;
            max-width: 900px;
            padding: 20px;
            z-index: 10;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px 50px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: center;
        }

        .login-branding {
            padding-right: 20px;
            border-right: 2px solid #f0f0f0;
        }

        .login-logo {
            text-align: left;
            margin-bottom: 25px;
        }

        .login-logo a {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .login-logo i {
            font-size: 2.2rem;
            color: #D4AF37;
        }

        .login-logo span {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .login-title {
            text-align: left;
            margin-bottom: 20px;
        }

        .login-title h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .login-title p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .login-form-wrapper {
            padding-left: 20px;
        }

        .login-form {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .form-group {
            position: relative;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 6px;
        }

        .form-group .input-wrapper {
            position: relative;
        }

        .form-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .form-group input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: 2px solid #e5e5e5;
            border-radius: 10px;
            font-size: 0.9rem;
            font-family: 'Montserrat', sans-serif;
            color: #333;
            background: #fff;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #D4AF37;
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
        }

        .form-group input::placeholder {
            color: #aaa;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #D4AF37;
            cursor: pointer;
        }

        .remember-me span {
            color: #555;
        }

        .forgot-password {
            color: #D4AF37;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #B8860B;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 0.9rem;
            font-weight: 600;
            font-family: 'Montserrat', sans-serif;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 18px 0;
            gap: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e5e5;
        }

        .divider span {
            color: #999;
            font-size: 0.75rem;
        }

        .social-login {
            display: flex;
            gap: 10px;
        }

        .btn-social {
            flex: 1;
            padding: 10px;
            border: 2px solid #e5e5e5;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #333;
        }

        .btn-social:hover {
            border-color: #D4AF37;
            background: #fffbf0;
        }

        .btn-social.google i { color: #ea4335; }
        .btn-social.facebook i { color: #1877f2; }

        .register-link {
            text-align: center;
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.85rem;
        }

        .register-link a {
            color: #D4AF37;
            text-decoration: none;
            font-weight: 600;
        }

        .register-link a:hover {
            color: #B8860B;
            text-decoration: underline;
        }

        .login-features {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
        }

        .login-features h3 {
            font-size: 0.85rem;
            font-weight: 600;
            color: #B8860B;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .features-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #555;
            font-size: 0.85rem;
        }

        .feature-item i {
            color: #D4AF37;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .error-message {
            background: #fff5f5;
            border: 1px solid #fed7d7;
            color: #c53030;
            padding: 12px 15px;
            border-radius: 10px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .login-card {
                grid-template-columns: 1fr;
                gap: 25px;
                padding: 35px 30px;
            }
            .login-branding {
                padding-right: 0;
                border-right: none;
                border-bottom: 2px solid #f0f0f0;
                padding-bottom: 25px;
            }
            .login-form-wrapper { padding-left: 0; }
            .login-features { display: none; }
            .login-logo, .login-title { text-align: center; }
        }

        @media (max-width: 480px) {
            .login-card { padding: 30px 20px; }
            .social-login { flex-direction: column; }
            .form-options {
                flex-direction: column;
                gap: 12px;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-branding">
                <div class="login-logo">
                    <a href="/">
                        <i class="fas fa-car"></i>
                        <span>AutoCar</span>
                    </a>
                </div>
                <div class="login-title">
                    <h1>Chào Mừng Trở Lại</h1>
                    <p>Đăng nhập để khám phá bộ sưu tập xe hạng sang độc quyền và trải nghiệm dịch vụ cao cấp</p>
                </div>
                <div class="login-features">
                    <h3>Quyền lợi thành viên</h3>
                    <div class="features-list">
                        <div class="feature-item">
                            <i class="fas fa-star"></i>
                            <span>Ưu đãi độc quyền cho thành viên</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-heart"></i>
                            <span>Lưu xe yêu thích & so sánh</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-bell"></i>
                            <span>Thông báo xe mới & khuyến mãi</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-headset"></i>
                            <span>Hỗ trợ tư vấn VIP 24/7</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="login-form-wrapper">
                <?php if (isset($error)): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
                <?php endif; ?>

                <form class="login-form" method="POST" action="/login">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" placeholder="Nhập địa chỉ email" required>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            <span>Ghi nhớ đăng nhập</span>
                        </label>
                        <a href="/forgot-password" class="forgot-password">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Đăng Nhập
                    </button>
                </form>

                <div class="divider">
                    <span>hoặc đăng nhập với</span>
                </div>

                <div class="social-login">
                    <button class="btn-social google">
                        <i class="fab fa-google"></i>
                        Google
                    </button>
                    <button class="btn-social facebook">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </button>
                </div>

                <div class="register-link">
                    Chưa có tài khoản? <a href="/register">Đăng ký ngay</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
