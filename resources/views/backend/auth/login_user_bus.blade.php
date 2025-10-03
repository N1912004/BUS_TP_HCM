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
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('auth.login_user') }}">
                @csrf
               
                    <!-- Tên đăng nhập -->
                    <label for="username">Tên đăng nhập</label>
                    <input type="text" id="username" name="username" placeholder="Tên đăng nhập">
                    @if ($errors->has('username'))
                        <span class="error-message">*{{ $errors->first('username') }}</span>
                    @endif
           
                <!-- Mật khẩu -->

                <label for="password">Mật khẩu</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Mật khẩu">
                    <i class="fa-regular fa-eye-slash toggle-password" id="togglePwd"></i>
                </div>
                @if ($errors->has('password'))
                    <span class="error-message">*{{ $errors->first('password') }}</span>
                @endif

                <!-- Liên kết phụ -->
                <div class="extra-links">
                    <a href="{{ route('auth.dashboard_sub') }}" class="register">Đăng kí tài khoản!</a> |
                    <a href="{{ route('auth.dashboard_reset_pass') }}" class="forgot">Quên mật khẩu?</a>
                </div>

                <!-- Nút đăng nhập -->
                <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
        </div>
        </form>

        {{-- <form method="POST" action="{{ route('auth.logout') }}" style="margin-top: 10px;">
            @csrf
            <button type="submit" class="btn btn-danger">Logout</button>
        </form> --}}
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
