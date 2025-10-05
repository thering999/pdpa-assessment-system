<?php
/**
 * Main Entry Point - Login/Register Page
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/controllers/AuthController.php';

$authController = new AuthController();

// Redirect to dashboard if already logged in
if (AuthController::isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

// Handle login
if (isset($_POST['action']) && $_POST['action'] === 'login') {
    $result = $authController->login();
    if ($result['success']) {
        header('Location: ' . $result['redirect']);
        exit;
    } else {
        $error = $result['message'];
    }
}

// Handle registration
if (isset($_POST['action']) && $_POST['action'] === 'register') {
    $result = $authController->register();
    if ($result['success']) {
        $success = $result['message'];
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>ระบบประเมิน PDPA</h2>
            <p>PDPA Assessment System for CII</p>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <div id="login-form">
                <h3 style="margin-bottom: 1rem;">เข้าสู่ระบบ</h3>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="login">
                    
                    <div class="form-group">
                        <label for="username">ชื่อผู้ใช้</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">เข้าสู่ระบบ</button>
                </form>
                
                <p style="text-align: center; margin-top: 1rem;">
                    ยังไม่มีบัญชี? <a href="#" onclick="showRegister(); return false;" style="color: var(--primary-color);">ลงทะเบียน</a>
                </p>
            </div>
            
            <div id="register-form" style="display: none;">
                <h3 style="margin-bottom: 1rem;">ลงทะเบียน</h3>
                <form method="POST" action="">
                    <input type="hidden" name="action" value="register">
                    
                    <div class="form-group">
                        <label for="reg_username">ชื่อผู้ใช้</label>
                        <input type="text" id="reg_username" name="username" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_email">อีเมล</label>
                        <input type="email" id="reg_email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_password">รหัสผ่าน (อย่างน้อย 6 ตัวอักษร)</label>
                        <input type="password" id="reg_password" name="password" class="form-control" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label for="full_name">ชื่อ-นามสกุล</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="organization">หน่วยงาน/องค์กร</label>
                        <input type="text" id="organization" name="organization" class="form-control">
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">ลงทะเบียน</button>
                </form>
                
                <p style="text-align: center; margin-top: 1rem;">
                    มีบัญชีแล้ว? <a href="#" onclick="showLogin(); return false;" style="color: var(--primary-color);">เข้าสู่ระบบ</a>
                </p>
            </div>
        </div>
    </div>
    
    <script>
        function showRegister() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('register-form').style.display = 'block';
        }
        
        function showLogin() {
            document.getElementById('register-form').style.display = 'none';
            document.getElementById('login-form').style.display = 'block';
        }
    </script>
</body>
</html>
