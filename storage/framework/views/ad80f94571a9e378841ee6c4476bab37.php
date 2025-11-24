<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân quyền đăng nhập</title>
    <link rel="stylesheet" href="<?php echo e(asset('backend/css/style.css')); ?>">
    <!-- Font Awesome để dùng icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="role-page">
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <a href="roles"  class="logo-link">
                <!-- dùng đường dẫn tương đối -->
                <img src="<?php echo e(asset('backend/logo/logo.png')); ?>" alt="BusGo HCM Logo">
                <span>BusGo HCM</span>
            </a>
        </div>
    </header>

    <main class="main">
        <div class="card">
            <div class="card-content">
                <h2>Hãy chọn quyền đăng nhập !</h2>
                <div class="options">

                    <div class="option white">
                        <a href="<?php echo e(route('auth.dashboard_admin')); ?>" class="option white">
                            <i class="fa-solid fa-user-tie"></i>
                            <span>Admin</span>
                        </a>
                    </div>

                    <div class="option blue"></div>
                    <div class="option blue"></div>
                    <div class="option white">
                        <a href="<?php echo e(route('auth.loginuser_get')); ?>" class="option white">
                        <i class="fa-solid fa-user"></i>
                        <span>Người dùng</span>
                        </a>
                    </div>
                
                </div>
            </div>
        </div>
    </main>
</body>

</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/laravelversion1.com/resources/views/backend/auth/roles.blade.php ENDPATH**/ ?>