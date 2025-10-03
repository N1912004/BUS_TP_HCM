<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân quyền đăng nhập</title>
    <link rel="stylesheet" href="backend/css/styles.css">
    <!-- Font Awesome để dùng icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="role-page">
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <a class="logo-link">
                <!-- dùng đường dẫn tương đối -->
                <img src="backend/img/logo/logo.png" alt="BusGo HCM Logo">
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
                        <a href="{{ route('auth.dashboard_admin') }}" class="option white">
                            <i class="fa-solid fa-user-tie"></i>
                            <span>Admin</span>
                        </a>
                    </div>

                    <div class="option blue"></div>
                    <div class="option blue"></div>
                    <div class="option white">
                        <a href="{{ route('auth.dashboard_user') }}" class="option white">
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
