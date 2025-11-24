<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đổi mật khẩu</title>
  <link rel="stylesheet" href="<?php echo e(asset('backend/css/style.css')); ?>">
</head>
<body class="forgot-page">
  <!-- Header -->
  <header class="header">
    <div class="logo">
      <a href="roles" class="logo-link">
        <img src="<?php echo e(asset('backend/logo/logo.png')); ?>" alt="BusGo HCM Logo">
        <span>BusGo HCM</span>
      </a>
    </div>
  </header>

  <!-- Main -->
  <main class="main">
    <div class="forgot-card">
      <h2>Quên mật khẩu</h2>
      <p class="desc">Nhập email đã đăng kí tài khoản để đổi mật khẩu mới.</p>

      <?php if(session('status')): ?>
          <div class="alert alert-success">
              <?php echo e(session('status')); ?>

          </div>
      <?php endif; ?>

      <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
          <div class="alert alert-danger"><?php echo e($message); ?></div>
      <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

      <form method="POST" action="<?php echo e(route('password.email')); ?>">
        <?php echo csrf_field(); ?>
        <label for="email">Địa chỉ email</label>
        <input type="email" id="email" name="email" placeholder="van@gmail.com" required>
        <button type="submit">Tiếp tục</button>
      </form>
    </div>
  </main>
</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/laravelversion1.com/resources/views/backend/auth/forgot_password.blade.php ENDPATH**/ ?>