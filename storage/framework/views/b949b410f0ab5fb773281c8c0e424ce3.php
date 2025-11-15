<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Người dùng</title>
    <link rel="stylesheet" href="<?php echo e(asset('backend/css/style.css')); ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="register-page">
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <div href="roles"  class="logo-link">
                <img src="<?php echo e(asset('public/backend/logo/logo.png')); ?>" alt="BusGo HCM Logo">
                <span>BusGo HCM</span>
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="main">
        <div class="register-card">
            <h2>ĐĂNG KÝ</h2>
            <?php if(session('error')): ?>
                <div class="alert alert-danger">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('auth.register')); ?>" novalidate>
                <?php echo csrf_field(); ?>
                <label for="fullname">Họ và tên</label>
                <input type="text" id="fullname" name="fullname" value="<?php echo e(old('fullname')); ?>"
                    placeholder="Họ và tên">
                <?php $__errorArgs = ['fullname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error-message">*<?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <label for="username">Tên đăng ký</label>
                <input type="text" id="username" name="username" value="<?php echo e(old('username')); ?>"
                    placeholder="Tên đăng ký">
                <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error-message">*<?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <label for="email">Địa chỉ email</label>
                <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>"
                    placeholder="Địa chỉ email">
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="error-message">*<?php echo e($message); ?></span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                <label for="password">Mật khẩu</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" value="<?php echo e(old('password')); ?>"
                        placeholder="Mật khẩu">
                    <i class="fa-regular fa-eye-slash toggle-password" data-target="password"></i>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message">*<?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <label for="confirm">Xác nhận mật khẩu</label>
                <div class="input-group">
                    <input type="password" id="confirm" name="password_confirmation" value="<?php echo e(old('confirm')); ?>"
                        placeholder="Xác nhận mật khẩu">
                    <i class="fa-regular fa-eye-slash toggle-password" data-target="confirm"></i>
                    <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error-message">*<?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <button type="submit" class="btn btn-primary block full-width m-b">Đăng ký</button>
                <div class="extra-links">
                    <a href="<?php echo e(route('auth.dashboard_user')); ?>" class="btn btn-primary">Đăng nhập!</a>

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
<?php /**PATH D:\BUS_TP_HCM\resources\views/backend/auth/sub.blade.php ENDPATH**/ ?>