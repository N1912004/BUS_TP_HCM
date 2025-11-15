<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Người dùng</title>
    <link rel="stylesheet" href="backend/css/styles.css">
    <!-- Font Awesome để dùng icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="login-page">
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <div class="logo-link">
                <img src="backend/img/logo/logo.png" alt="BusGo HCM Logo">
                <span>BusGo HCM</span>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="main">
        <div class="login-card">
            <h2>ĐĂNG NHẬP</h2>
            <div class="role-label">Bạn đang là Người dùng!</div>
            <?php if(session('error')): ?>
                <div class="alert alert-danger">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>

            <form method="POST" action="<?php echo e(route('auth.login_user')); ?>">
                <?php echo csrf_field(); ?>
               
                    <!-- Tên đăng nhập -->
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" placeholder="Tên đăng nhập">
                    <?php if($errors->has('username')): ?>
                        <span class="error-message">*<?php echo e($errors->first('username')); ?></span>
                    <?php endif; ?>
           
                <!-- Mật khẩu -->

                <label for="password">Mật khẩu</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Mật khẩu">
                    <i class="fa-regular fa-eye-slash toggle-password" id="togglePwd"></i>
                </div>
                <?php if($errors->has('password')): ?>
                    <span class="error-message">*<?php echo e($errors->first('password')); ?></span>
                <?php endif; ?>

                <!-- Liên kết phụ -->
                <div class="extra-links">
                    <a href="<?php echo e(route('auth.dashboard_sub')); ?>" class="register">Đăng kí tài khoản!</a> |
                    <a href="<?php echo e(route('auth.dashboard_reset_pass')); ?>" class="forgot">Quên mật khẩu?</a>
                </div>

                <!-- Nút đăng nhập -->
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
        </div>
        </form>

        
    </main>


    <!-- Script toggle password -->
    <script>
        const toggle = document.getElementById('togglePwd');
        const pwd = document.getElementById('password');

        toggle.addEventListener('click', () => {
            if (pwd.type === 'password') {
                pwd.type = 'text';
                toggle.classList.replace('fa-eye-slash', 'fa-eye');
            } else {
                pwd.type = 'password';
                toggle.classList.replace('fa-eye', 'fa-eye-slash');
            }
        });
    </script>
</body>

</html>
<?php /**PATH C:\Users\pc\Downloads\BUS_TP_HCM-main\BUS_TP_HCM-main\resources\views/backend/auth/login_user_bus.blade.php ENDPATH**/ ?>