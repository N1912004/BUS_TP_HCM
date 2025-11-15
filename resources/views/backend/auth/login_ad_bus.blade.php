<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Admin</title>
    <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
    <!-- Font Awesome để dùng icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="login-page">
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <a href=""     class="logo-link">
                <!-- nhớ đổi sang đường dẫn tương đối images/logo.png -->
                <img src="{{ asset('backend/logo/logo.png') }}" alt="BusGo HCM Logo">
                <span>BusGo HCM</span>
            </a>
        </div>
    </header>

    <!-- Main -->
    <main class="main">
        <div class="login-card">
            <h2>ĐĂNG NHẬP</h2>
            <div class="role-label">Bạn đang là Admin ! </div>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('auth.login_admin') }}">
                @csrf
                <label for="username">Tên đăng nhập</label>
                <input type="text" name="username" placeholder="Tên đăng nhập">
                @if ($errors->has('username'))
                    <span class="error-message">*{{ $errors->first('username') }}</span>
                @endif
                <label for="password">Mật khẩu</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" placeholder="Mật khẩu">
                    <i class="fa-regular fa-eye-slash toggle-password" id="togglePwd"></i>
                </div>
                @if ($errors->has('password'))
                    <span class="error-message">*{{ $errors->first('password') }}</span>
                @endif
                <button type="submit">Đăng nhập</button>
            </form>
        </div>
    </main>
</body>

</html>
