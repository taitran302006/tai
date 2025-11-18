<?php
session_start();

// Nếu đã đăng nhập, redirect về trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';
$success = '';

// Xử lý đăng nhập
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $error = '❌ Vui lòng nhập email và mật khẩu!';
    } else {
        // Giả lập kiểm tra (thay bằng kiểm tra database thực)
        if ($email === 'admin@phuchau.local' && $password === 'admin123') {
            // Set session user in a consistent structure used across the app
            $_SESSION['user'] = [
                'id' => 1,
                'email' => $email,
                'name' => 'Admin',
                'is_admin' => 1
            ];
            $_SESSION['user_id'] = 1;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_name'] = 'Admin';

            // Redirect back to requested page if provided (prevent open redirect)
            $redirect = trim($_POST['redirect'] ?? $_GET['redirect'] ?? '');
            if ($redirect && strpos($redirect, '://') === false && strpos($redirect, '..') === false) {
                header('Location: ' . $redirect);
                exit;
            }

            header('Location: index.php?login=success');
            exit;
        } else {
            $error = '❌ Email hoặc mật khẩu không chính xác!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - PasGo Restaurant</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            display: flex;
            width: 100%;
            max-width: 1000px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        /* Left Side - Branding */
        .auth-left {
            background: linear-gradient(135deg, #FFB366 0%, #FF9933 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            min-height: 500px;
        }

        .auth-left h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .auth-left .logo {
            font-size: 4rem;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .auth-left p {
            font-size: 1rem;
            line-height: 1.6;
            opacity: 0.95;
            margin-bottom: 30px;
        }

        .auth-left .features {
            text-align: left;
            width: 100%;
        }

        .auth-left .features li {
            list-style: none;
            margin: 10px 0;
            font-size: 0.95rem;
            padding-left: 30px;
            position: relative;
        }

        .auth-left .features li:before {
            content: "✓";
            position: absolute;
            left: 0;
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* Right Side - Form */
        .auth-right {
            padding: 60px 40px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-right h2 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 10px;
        }

        .auth-right .subtitle {
            color: #999;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #f9f9f9;
        }

        .form-group input:focus {
            outline: none;
            border-color: #FFB366;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255, 179, 102, 0.1);
        }

        .form-group input::placeholder {
            color: #bbb;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 0.9rem;
        }

        .remember-forgot label {
            display: flex;
            align-items: center;
            cursor: pointer;
            color: #666;
            font-weight: 500;
            margin: 0;
        }

        .remember-forgot input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #FFB366;
        }

        .remember-forgot a {
            color: #FFB366;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .remember-forgot a:hover {
            color: #FF9933;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 14px 20px;
            background: linear-gradient(135deg, #FFB366 0%, #FF9933 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 179, 102, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 179, 102, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 0.95rem;
        }

        .signup-link a {
            color: #FFB366;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .signup-link a:hover {
            color: #FF9933;
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert {
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            border-left: 4px solid;
        }

        .alert-error {
            background: #fee;
            color: #c33;
            border-left-color: #c33;
        }

        .alert-success {
            background: #efe;
            color: #3c3;
            border-left-color: #3c3;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #ccc;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e0e0e0;
        }

        .divider span {
            padding: 0 10px;
            color: #999;
            font-size: 0.9rem;
        }

        .social-login {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .social-btn {
            flex: 1;
            padding: 12px;
            border: 2px solid #e0e0e0;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1.2rem;
            text-align: center;
        }

        .social-btn:hover {
            border-color: #FFB366;
            background: #fff9f5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .auth-container {
                flex-direction: column;
            }

            .auth-left {
                padding: 40px 30px;
                min-height: auto;
            }

            .auth-left h1 {
                font-size: 2rem;
            }

            .auth-left .logo {
                font-size: 3rem;
            }

            .auth-right {
                padding: 40px 30px;
            }

            .auth-right h2 {
                font-size: 1.7rem;
            }
        }

        @media (max-width: 480px) {
            .auth-container {
                border-radius: 15px;
            }

            .auth-left {
                padding: 30px 20px;
            }

            .auth-left h1 {
                font-size: 1.5rem;
            }

            .auth-left .logo {
                font-size: 2.5rem;
            }

            .auth-right {
                padding: 30px 20px;
            }

            .auth-right h2 {
                font-size: 1.4rem;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .social-login {
                flex-direction: column;
            }

            .social-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <!-- Left Side -->
        <div class="auth-left">
            <div class="logo">🍱</div>
            <h1>PasGo Restaurant</h1>
            <p>Trải nghiệm ẩm thực hàng đầu tại TP. Hồ Chí Minh</p>
            <ul class="features">
                <li>Đặt bàn trực tuyến dễ dàng</li>
                <li>Giao hàng nhanh chóng</li>
                <li>Khuyến mãi hấp dẫn hàng ngày</li>
                <li>Chất lượng thực phẩm đảm bảo</li>
            </ul>
        </div>

        <!-- Right Side -->
        <div class="auth-right">
            <h2>Đăng Nhập</h2>
            <p class="subtitle">Quay lại với chúng tôi để trải nghiệm tuyệt vời</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($_GET['registered'])): ?>
                <div class="alert alert-success">✅ Đăng ký thành công! Vui lòng đăng nhập.</div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="example@gmail.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật Khẩu</label>
                    <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                </div>

                <?php if (!empty($_GET['redirect'])): ?>
                    <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($_GET['redirect']); ?>">
                <?php endif; ?>

                <div class="remember-forgot">
                    <label>
                        <input type="checkbox" name="remember"> Ghi nhớ tôi
                    </label>
                    <a href="forgot_password.php">Quên mật khẩu?</a>
                </div>

                <button type="submit" name="login" class="btn-login">Đăng Nhập</button>
            </form>

            <div class="divider">
                <span>hoặc</span>
            </div>

            <div class="social-login">
                <button class="social-btn" title="Facebook">f</button>
                <button class="social-btn" title="Google">G</button>
                <button class="social-btn" title="Apple">🍎</button>
            </div>

            <p class="signup-link">
                Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
            </p>
        </div>
    </div>

    <script>
        // Demo tài khoản test
        document.addEventListener('DOMContentLoaded', function() {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            
            // Optional: Hiển thị thông tin demo khi focus
            emailInput.addEventListener('focus', function() {
                if (this.value === '') {
                    this.title = 'Demo: admin@phuchau.local';
                }
            });
        });
    </script>
</body>
</html>
