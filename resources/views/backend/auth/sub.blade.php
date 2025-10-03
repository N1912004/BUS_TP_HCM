<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Người dùng</title>
    <link rel="stylesheet" href="backend/css/styles.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="register-page">
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
        <div class="register-card">
            <h2>ĐĂNG KÝ</h2>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <form method="POST" action="{{ route('auth.register') }}" novalidate>
                @csrf
                <label for="fullname">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" value="{{ old('fullname') }}"
                    placeholder="Họ và tên">
                @error('fullname')
                    <span class="error-message">*{{ $message }}</span>
                @enderror
                <label for="username">Tên đăng ký</label>
                <input type="text" id="username" name="username" value="{{ old('username') }}"
                    placeholder="Tên đăng ký">
                @error('username')
                    <span class="error-message">*{{ $message }}</span>
                @enderror
                <label for="email">Địa chỉ email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}"
                    placeholder="Địa chỉ email">
                @error('email')
                    <span class="error-message">*{{ $message }}</span>
                @enderror
                <label for="password">Mật khẩu</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" value="{{ old('password') }}"
                        placeholder="Mật khẩu">
                    <i class="fa-regular fa-eye-slash toggle-password" data-target="password"></i>
                    @error('password')
                        <span class="error-message">*{{ $message }}</span>
                    @enderror
                </div>

                <label for="confirm">Xác nhận mật khẩu</label>
                <div class="input-group">
                    <input type="password" id="confirm" name="password_confirmation" value="{{ old('confirm') }}"
                        placeholder="Xác nhận mật khẩu">
                    <i class="fa-regular fa-eye-slash toggle-password" data-target="confirm"></i>
                    @error('password_confirmation')
                        <span class="error-message">*{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary block full-width m-b">Đăng ký</button>
                <div class="extra-links">
                    <a href="{{ route('auth.dashboard_user') }}" class="btn btn-primary">Đăng nhập!</a>

                </div>
            </form>
        </div>
    </main>

    <script>
        // Toggle show/hide password cho tất cả input-group
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', () => {
                const targetId = icon.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                }
            });
        });
    </script>
</body>

</html>
