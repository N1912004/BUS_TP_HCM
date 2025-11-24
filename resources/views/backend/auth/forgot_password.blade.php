<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đổi mật khẩu</title>
  <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
</head>
<body class="forgot-page">
  <!-- Header -->
  <header class="header">
    <div class="logo">
      <a href="roles" class="logo-link">
        <img src="{{ asset('backend/logo/logo.png') }}" alt="BusGo HCM Logo">
        <span>BusGo HCM</span>
      </a>
    </div>
  </header>

  <!-- Main -->
  <main class="main">
    <div class="forgot-card">
      <h2>Quên mật khẩu</h2>
      <p class="desc">Nhập email đã đăng kí tài khoản để đổi mật khẩu mới.</p>

      @if (session('status'))
          <div class="alert alert-success">
              {{ session('status') }}
          </div>
      @endif

      @error('email')
          <div class="alert alert-danger">{{ $message }}</div>
      @enderror

      <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email">Địa chỉ email</label>
        <input type="email" id="email" name="email" placeholder="van@gmail.com" required>
        <button type="submit">Tiếp tục</button>
      </form>
    </div>
  </main>
</body>
</html>
