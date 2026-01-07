<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - AutoCar Luxury</title>
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
            overflow-x: hidden;
            padding: 40px 0;
        }

        .bg-image {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;
            background: url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center center / cover no-repeat;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.75) 0%, rgba(0, 0, 0, 0.55) 50%, rgba(0, 0, 0, 0.75) 100%);
        }

        .register-container {
            width: 100%;
            max-width: 1000px;
            padding: 20px;
            z-index: 10;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 40px 50px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 40px;
            align-items: start;
        }

        .register-info {
            padding-right: 20px;
            border-right: 2px solid #f0f0f0;
        }

        .register-logo {
            text-align: left;
            margin-bottom: 25px;
        }

        .register-logo a {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .register-logo i {
            font-size: 2.2rem;
            color: #D4AF37;
        }

        .register-logo span {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .register-title {
            text-align: left;
            margin-bottom: 20px;
        }

        .register-title h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.6rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .register-title p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .register-form-wrapper {
            padding-left: 20px;
        }

        .register-form {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
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

        .form-group i.input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 0.9rem;
            pointer-events: none;
        }

        .form-group input {
            width: 100%;
            padding: 11px 12px 11px 38px;
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

        .terms-checkbox {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 0.85rem;
            color: #555;
        }

        .terms-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #D4AF37;
            cursor: pointer;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .terms-checkbox a {
            color: #D4AF37;
            text-decoration: none;
            font-weight: 500;
        }

        .terms-checkbox a:hover {
            text-decoration: underline;
        }

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 16px 0;
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

        .social-register {
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

        .btn-social.google i {
            color: #ea4335;
        }

        .btn-social.facebook i {
            color: #1877f2;
        }

        .login-link {
            text-align: center;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 0.85rem;
        }

        .login-link a {
            color: #D4AF37;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            color: #B8860B;
            text-decoration: underline;
        }

        .benefits {
            background: linear-gradient(135deg, #fffbf0 0%, #fff9e6 100%);
            border-radius: 12px;
            padding: 15px 18px;
            margin-top: 20px;
        }

        .benefits-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #B8860B;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .benefits-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .benefit-item {
            font-size: 0.85rem;
            color: #555;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .benefit-item i {
            color: #D4AF37;
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }

        .why-choose {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
        }

        .why-choose h3 {
            font-size: 0.85rem;
            font-weight: 600;
            color: #B8860B;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .why-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .why-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .why-item i {
            color: #D4AF37;
            font-size: 1.2rem;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .why-content h4 {
            font-size: 0.9rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 4px;
        }

        .why-content p {
            font-size: 0.8rem;
            color: #666;
            line-height: 1.5;
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

        @media (max-width: 900px) {
            .register-card {
                grid-template-columns: 1fr;
                gap: 25px;
                padding: 35px 30px;
            }

            .register-info {
                padding-right: 0;
                border-right: none;
                border-bottom: 2px solid #f0f0f0;
                padding-bottom: 25px;
            }

            .register-form-wrapper {
                padding-left: 0;
            }

            .why-choose {
                display: none;
            }

            .register-logo,
            .register-title {
                text-align: center;
            }
        }

        @media (max-width: 520px) {
            .register-card {
                padding: 30px 20px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .social-register {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="register-container">
        <div class="register-card">
            <div class="register-info">
                <div class="register-logo">
                    <a href="/">
                        <i class="fas fa-car"></i>
                        <span>AutoCar</span>
                    </a>
                </div>
                <div class="register-title">
                    <h1>Tạo Tài Khoản Mới</h1>
                    <p>Tham gia cộng đồng yêu xe hạng sang và trải nghiệm dịch vụ tốt nhất</p>
                </div>

                <div class="benefits">
                    <div class="benefits-title">
                        <i class="fas fa-gift"></i>
                        Quyền lợi thành viên
                    </div>
                    <div class="benefits-list">
                        <div class="benefit-item">
                            <i class="fas fa-star"></i>
                            <span>Ưu đãi độc quyền cho thành viên</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-heart"></i>
                            <span>Lưu xe yêu thích & so sánh</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-bell"></i>
                            <span>Thông báo xe mới & khuyến mãi</span>
                        </div>
                        <div class="benefit-item">
                            <i class="fas fa-headset"></i>
                            <span>Hỗ trợ tư vấn VIP 24/7</span>
                        </div>
                    </div>
                </div>

                <div class="why-choose">
                    <h3>Tại sao chọn chúng tôi?</h3>
                    <div class="why-list">
                        <div class="why-item">
                            <i class="fas fa-shield-alt"></i>
                            <div class="why-content">
                                <h4>Bảo mật tuyệt đối</h4>
                                <p>Thông tin được mã hóa và bảo vệ an toàn</p>
                            </div>
                        </div>
                        <div class="why-item">
                            <i class="fas fa-check-circle"></i>
                            <div class="why-content">
                                <h4>Xe chính hãng 100%</h4>
                                <p>Cam kết nguồn gốc rõ ràng, giấy tờ đầy đủ</p>
                            </div>
                        </div>
                        <div class="why-item">
                            <i class="fas fa-award"></i>
                            <div class="why-content">
                                <h4>Đánh giá 5 sao</h4>
                                <p>Hơn 8000+ khách hàng tin tưởng</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="register-form-wrapper">
                <?php if (!empty($errors)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        <div>
                            <?php foreach ($errors as $error): ?>
                                <p><?= htmlspecialchars($error) ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <form class="register-form" method="POST" action="/register">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fullname">Họ và tên</label>
                            <div class="input-wrapper">
                                <input type="text" id="fullname" name="fullname" placeholder="Nguyễn Văn A" value="<?= htmlspecialchars($formData['fullname'] ?? '') ?>" required>
                                <i class="fas fa-user input-icon"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <div class="input-wrapper">
                                <input type="tel" id="phone" name="phone" placeholder="0901 234 567" value="<?= htmlspecialchars($formData['phone'] ?? '') ?>" required>
                                <i class="fas fa-phone input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" placeholder="email@example.com" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" required>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Mật khẩu</label>
                            <div class="input-wrapper">
                                <input type="password" id="password" name="password" placeholder="Tối thiểu 8 ký tự" required minlength="8">
                                <i class="fas fa-lock input-icon"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Xác nhận mật khẩu</label>
                            <div class="input-wrapper">
                                <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu" required>
                                <i class="fas fa-lock input-icon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="terms-checkbox">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            Tôi đồng ý với <a href="/terms">Điều khoản dịch vụ</a> và <a href="/privacy">Chính sách bảo mật</a>
                        </label>
                    </div>

                    <button type="submit" class="btn-register">
                        <i class="fas fa-user-plus"></i> Đăng Ký
                    </button>
                </form>

                <div class="divider">
                    <span>hoặc đăng ký với</span>
                </div>

                <div class="social-register">
                    <button class="btn-social google">
                        <i class="fab fa-google"></i>
                        Google
                    </button>
                    <button class="btn-social facebook">
                        <i class="fab fa-facebook-f"></i>
                        Facebook
                    </button>
                </div>

                <div class="login-link">
                    Đã có tài khoản? <a href="/login">Đăng nhập ngay</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>