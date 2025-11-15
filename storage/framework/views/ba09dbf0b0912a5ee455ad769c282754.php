<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quản lý tuyến xe</title>

  <!-- Bootstrap 5 CSS (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome (CDN) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Tùy chỉnh CSS nhỏ -->
  <style>

    body { background:#f4f8fb; min-height:100vh; }
    .sidebar { background: #e9f0f8; min-height: 100vh; border-right:1px solid rgba(0,0,0,0.04); }
    .brand { height:60px; display:flex; align-items:center; padding:0 16px; border-bottom:1px solid rgba(0,0,0,0.04); }
    .avatar { width:36px; height:36px; border-radius:50%; object-fit:cover; }
    .sidebar .nav-link { color:#18426a; }
    .card-table { box-shadow:none; border:0; }
    .table thead th { border-bottom:2px solid #e6eef7; }
    .table tbody tr td { vertical-align: middle; }
    .action-icons a { margin-left:8px; color:#0d6efd; }
    .action-icons a.delete { color:#dc3545; }
    .page-title { font-weight:600; color:#123; }
    .header-top { background:#fff; border-bottom: 1px solid rgba(0,0,0,0.05); }
    @media (max-width: 991px) {
      .sidebar { min-height: auto; }
    }
  </style>
</head>
<body>

<div class="container-fluid">
  <div class="row g-0">

    <!-- SIDEBAR -->
    <aside class="col-lg-2 sidebar p-0">
      <div class="brand">
        <img src="<?php echo e(asset('backend/logo/logo.png')); ?>" alt="Logo" style="width:36px;height:36px;" class="me-2">
        <div class="fw-bold">BusGo HCM</div>
      </div>

      <nav class="nav flex-column p-3">
        <div class="dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center mb-2" href="#" id="userManagementDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-users me-2"></i> Quản lý người dùng
          </a>
          <ul class="dropdown-menu" aria-labelledby="userManagementDropdown">
            <li><a class="dropdown-item" href="<?php echo e(route('admin.drivers.index') ?? '#'); ?>">Tài xế</a></li>
            <li><a class="dropdown-item" href="<?php echo e(route('admin.assistants.index') ?? '#'); ?>">Phụ xe</a></li>
          </ul>
        </div>
        <a class="nav-link d-flex align-items-center mb-2" href="<?php echo e(route('admin.tickets.index') ?? '#'); ?>">
          <i class="fa fa-ticket-alt me-2"></i> Quản lý vé
        </a>
        <a class="nav-link d-flex align-items-center mb-2 active" href="<?php echo e(route('admin.routes.index') ?? '#'); ?>">
          <i class="fa fa-route me-2"></i> Quản lý tuyến xe
        </a>
         <a class="nav-link d-flex align-items-center mb-2 active" href="<?php echo e(route('admin.buses.index') ?? '#'); ?>">
          <i class="fa fa-route me-2"></i> Quản lý xe buýt
        </a>
        <hr>
        <a class="nav-link text-danger d-flex align-items-center" href="#"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fa fa-sign-out-alt me-2"></i> Đăng xuất
        </a>
      </nav>
    </aside>

    <!-- MAIN -->
    <main class="col-lg-10 p-0">

      <!-- HEADER -->
      <header class="header-top d-flex justify-content-between align-items-center px-4" style="height:64px;">
        <div class="d-flex align-items-center">
          <h5 class="mb-0"> </h5>
        </div>

        <div class="d-flex align-items-center">
          <div class="me-3 text-end pe-3 border-end">
            <div style="font-size:14px; color:#444;">Xin chào</div>
            <div class="fw-bold" style="font-size:14px;"><?php echo e(Auth::user()->name ?? 'Quản trị viên'); ?></div>
          </div>

        <!-- HOME PAGE DISPLAY (Giống hình bạn gửi) -->




        </div>
      </header>

      <!-- CONTENT -->
      <section class="p-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="widget style1 navy-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-road fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> Tổng số tuyến </span>
                            <h2 class="font-bold" id="totalRoutes"><?php echo e($totalRoutes); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="widget style1 lazur-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-bus fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> Tổng số xe </span>
                            <h2 class="font-bold" id="totalBuses"><?php echo e($totalBuses); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="widget style1 yellow-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> Tổng số người dùng </span>
                            <h2 class="font-bold" id="totalUsers"><?php echo e($totalUsers); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="widget style1 red-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-user-secret fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> Tổng số tài xế </span>
                            <h2 class="font-bold" id="totalDrivers"><?php echo e($totalDrivers); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- HOME PAGE DISPLAY (Giống hình bạn gửi) -->
        
      
        <?php echo $__env->yieldContent('content'); ?>
      </section>

    </main>
  </div>
</div>


<!-- Hidden logout form -->
<form id="logout-form" action="<?php echo e(route('auth.logout') ?? url('/logout')); ?>" method="POST" style="display:none;">
  <?php echo csrf_field(); ?>
</form>

<!-- Bootstrap JS (Popper + Bootstrap) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 

<script>
    $(document).ready(function() {
        function updateStats() {
            $.ajax({
                url: '<?php echo e(route('api.admin.stats')); ?>', // This route needs to be defined
                method: 'GET',
                success: function(data) {
                    $('#totalRoutes').text(data.totalRoutes);
                    $('#totalBuses').text(data.totalBuses);
                    $('#totalUsers').text(data.totalUsers);
                    $('#totalDrivers').text(data.totalDrivers);
                },
                error: function(xhr) {
                    console.error('Lỗi khi lấy thống kê quản trị:', xhr);
                }
            });
        }

        // Update stats every 10 seconds
       // setInterval(updateStats, 10000);

        // Initial load
      // updateStats();
    });
</script>

<?php echo $__env->yieldContent('scripts'); ?>

</body>
</html>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/laravelversion1.com/resources/views/backend/admin/index_admin.blade.php ENDPATH**/ ?>