<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đặt lại mật khẩu</title>
  <link rel="stylesheet" href="{{ asset('backend/css/style.css') }}">
</head>
<body class="forgot-page">
  <!-- Header -->
  <header class="header">
    <div class="logo">
      <a href="{{ route('auth.roles') }}" class="logo-link">
        <img src="{{ asset('backend/logo/logo.png') }}" alt="BusGo HCM Logo">
        <span>BusGo HCM</span>
      </a>
    </div>
  </header>

  <!-- Main -->
  <main class="main">
    <div class="forgot-card">
      <h2>Đặt lại mật khẩu</h2>
      <p class="desc">Nhập mật khẩu mới của bạn.</p>

      @if (session('status'))
          <div class="alert alert-success">
              {{ session('status') }}
          </div>
      @endif

      <form method="POST" action="{{ route('password.update') }}">
          @csrf

          <input type="hidden" name="token" value="{{ $token }}">
          <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

          <label for="password">Mật khẩu mới</label>
          <input id="password" type="password" name="password" placeholder="Nhập mật khẩu mới" required autocomplete="new-password">
          @error('password')
              <div class="alert alert-danger">{{ $message }}</div>
          @enderror

          <label for="password-confirm">Xác nhận mật khẩu</label>
          <input id="password-confirm" type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu mới" required autocomplete="new-password">
          
          <button type="submit">Đặt lại mật khẩu</button>
      </form>
    </div>
  </main>
</body>
</html>
