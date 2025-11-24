<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đặt lại mật khẩu</title>
  <link rel="stylesheet" href="<?php echo e(asset('backend/css/style.css')); ?>">
</head>
<body class="forgot-page">
  <!-- Header -->
  <header class="header">
    <div class="logo">
      <a href="<?php echo e(route('auth.roles')); ?>" class="logo-link">
        <img src="<?php echo e(asset('backend/logo/logo.png')); ?>" alt="BusGo HCM Logo">
        <span>BusGo HCM</span>
      </a>
    </div>
  </header>

  <!-- Main -->
  <main class="main">
    <div class="forgot-card">
      <h2>Đặt lại mật khẩu</h2>
      <p class="desc">Nhập mật khẩu mới của bạn.</p>

      <?php if(session('status')): ?>
          <div class="alert alert-success">
              <?php echo e(session('status')); ?>

          </div>
      <?php endif; ?>

      <form method="POST" action="<?php echo e(route('password.update')); ?>">
          <?php echo csrf_field(); ?>

          <input type="hidden" name="token" value="<?php echo e($token); ?>">
          <input type="hidden" name="email" value="<?php echo e($email ?? old('email')); ?>">

          <label for="password">Mật khẩu mới</label>
          <input id="password" type="password" name="password" placeholder="Nhập mật khẩu mới" required autocomplete="new-password">
          <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
              <div class="alert alert-danger"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

          <label for="password-confirm">Xác nhận mật khẩu</label>
          <input id="password-confirm" type="password" name="password_confirmation" placeholder="Xác nhận mật khẩu mới" required autocomplete="new-password">
          
          <button type="submit">Đặt lại mật khẩu</button>
      </form>
    </div>
  </main>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/laravelversion1.com/resources/views/backend/auth/reset_pass.blade.php ENDPATH**/ ?>