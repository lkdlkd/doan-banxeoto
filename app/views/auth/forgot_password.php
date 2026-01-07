<?php
$pageTitle = 'Quên Mật Khẩu - AutoCar';

$message = '';
$messageType = '';
$showResetForm = false;
$resetToken = '';

// Xử lý form quên mật khẩu
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/../../models/UserModel.php';
    $userModel = new UserModel();

    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'request_reset') {
            // Yêu cầu reset mật khẩu
            $email = trim($_POST['email'] ?? '');

            if (empty($email)) {
                $message = 'Vui lòng nhập địa chỉ email';
                $messageType = 'error';
            } else {
                $user = $userModel->findByEmail($email);

                if ($user) {
                    // Tạo token reset
                    $token = bin2hex(random_bytes(32));
                    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

                    // Lưu token vào database (trong thực tế)
                    // $userModel->saveResetToken($user['id'], $token, $expiry);

                    // Hiển thị token (trong production sẽ gửi email)
                    $message = 'Link đặt lại mật khẩu đã được gửi đến email của bạn. Vui lòng kiểm tra hộp thư.';
                    $messageType = 'success';

                    // Demo: Hiển thị form reset ngay
                    $showResetForm = true;
                    $resetToken = $token;
                    $_SESSION['reset_user_id'] = $user['id'];
                    $_SESSION['reset_token'] = $token;
                } else {
                    $message = 'Email không tồn tại trong hệ thống';
                    $messageType = 'error';
                }
            }
        } elseif ($_POST['action'] === 'reset_password') {
            // Đặt lại mật khẩu
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $token = $_POST['token'] ?? '';

            if (empty($newPassword) || empty($confirmPassword)) {
                $message = 'Vui lòng điền đầy đủ thông tin';
                $messageType = 'error';
                $showResetForm = true;
                $resetToken = $token;
            } elseif (strlen($newPassword) < 6) {
                $message = 'Mật khẩu phải có ít nhất 6 ký tự';
                $messageType = 'error';
                $showResetForm = true;
                $resetToken = $token;
            } elseif ($newPassword !== $confirmPassword) {
                $message = 'Mật khẩu xác nhận không khớp';
                $messageType = 'error';
                $showResetForm = true;
                $resetToken = $token;
            } else {
                // Kiểm tra token
                if (isset($_SESSION['reset_token']) && $_SESSION['reset_token'] === $token && isset($_SESSION['reset_user_id'])) {
                    // Cập nhật mật khẩu
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $userModel->update($_SESSION['reset_user_id'], ['password' => $hashedPassword]);

                    // Xóa session
                    unset($_SESSION['reset_token']);
                    unset($_SESSION['reset_user_id']);

                    $message = 'Đặt lại mật khẩu thành công! Bạn có thể đăng nhập với mật khẩu mới.';
                    $messageType = 'success';
                } else {
                    $message = 'Token không hợp lệ hoặc đã hết hạn';
                    $messageType = 'error';
                }
            }
        }
    }
}

// Kiểm tra có token từ URL không
if (isset($_GET['token']) && isset($_SESSION['reset_token']) && $_SESSION['reset_token'] === $_GET['token']) {
    $showResetForm = true;
    $resetToken = $_GET['token'];
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
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
            background: url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') center center / cover no-repeat;
        }

        .bg-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(0, 0, 0, 0.8) 100%);
        }

        .container {
            width: 100%;
            max-width: 480px;
            padding: 20px;
            z-index: 10;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo a {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
        }

        .logo i {
            font-size: 2.2rem;
            color: #D4AF37;
        }

        .logo span {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .title {
            text-align: center;
            margin-bottom: 30px;
        }

        .title h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .title p {
            color: #666;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 0.9rem;
        }

        .alert-error {
            background: rgba(231, 76, 60, 0.1);
            border: 1px solid rgba(231, 76, 60, 0.3);
            color: #c0392b;
        }

        .alert-success {
            background: rgba(39, 174, 96, 0.1);
            border: 1px solid rgba(39, 174, 96, 0.3);
            color: #27ae60;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .input-wrapper input {
            width: 100%;
            padding: 15px 20px 15px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 1rem;
            font-family: inherit;
            transition: all 0.3s ease;
            background: #fff;
        }

        .input-wrapper input:focus {
            outline: none;
            border-color: #D4AF37;
        }

        .input-wrapper input:focus+i,
        .input-wrapper input:focus~i {
            color: #D4AF37;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #D4AF37 0%, #B8860B 100%);
            color: #000;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        }

        .links {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #eee;
        }

        .links a {
            color: #D4AF37;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: opacity 0.3s;
        }

        .links a:hover {
            opacity: 0.8;
        }

        .links .divider {
            color: #ddd;
            margin: 0 15px;
        }

        @media (max-width: 480px) {
            .card {
                padding: 40px 25px;
            }

            .title h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="container">
        <div class="card">
            <div class="logo">
                <a href="/">
                    <i class="fas fa-car"></i>
                    <span>AutoCar</span>
                </a>
            </div>

            <?php if (!$showResetForm): ?>
                <!-- Form yêu cầu reset mật khẩu -->
                <div class="title">
                    <h1>Quên Mật Khẩu?</h1>
                    <p>Nhập địa chỉ email bạn đã đăng ký, chúng tôi sẽ gửi link đặt lại mật khẩu</p>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?>">
                        <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="/forgot-password">
                    <input type="hidden" name="action" value="request_reset">

                    <div class="form-group">
                        <label>Địa chỉ Email</label>
                        <div class="input-wrapper">
                            <input type="email" name="email" placeholder="Nhập email của bạn" required>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i>
                        Gửi Link Đặt Lại
                    </button>
                </form>

            <?php else: ?>
                <!-- Form đặt lại mật khẩu -->
                <div class="title">
                    <h1>Đặt Lại Mật Khẩu</h1>
                    <p>Nhập mật khẩu mới cho tài khoản của bạn</p>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?= $messageType ?>">
                        <i class="fas fa-<?= $messageType === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endif; ?>

                <?php if ($messageType !== 'success'): ?>
                    <form method="POST" action="/forgot-password">
                        <input type="hidden" name="action" value="reset_password">
                        <input type="hidden" name="token" value="<?= htmlspecialchars($resetToken) ?>">

                        <div class="form-group">
                            <label>Mật khẩu mới</label>
                            <div class="input-wrapper">
                                <input type="password" name="new_password" placeholder="Tối thiểu 6 ký tự" required minlength="6">
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Xác nhận mật khẩu</label>
                            <div class="input-wrapper">
                                <input type="password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">
                            <i class="fas fa-key"></i>
                            Đặt Lại Mật Khẩu
                        </button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>

            <div class="links">
                <a href="/login">
                    <i class="fas fa-arrow-left"></i> Quay lại đăng nhập
                </a>
                <span class="divider">|</span>
                <a href="/register">Đăng ký tài khoản</a>
            </div>
        </div>
    </div>
</body>

</html>