<?php $__env->startPush('styles'); ?>
<style>
  /* Tinh chỉnh giao diện để giống hệ thống quản trị */
  .page-header {
    background: #f5fbff;
    border-bottom: 1px solid rgba(0,0,0,0.04);
    padding: 18px 20px;
    margin-bottom: 18px;
  }
  .page-title { font-weight: 600; color: #123; }
  .card-table { box-shadow: none; border: 0; }
  .table thead th {
    border-bottom: 2px solid #e6eef7;
    color: #3b6a8a;
    font-weight: 600;
  }
  .table tbody tr td { vertical-align: middle; color: #556b7a; }
  .action-icons a { margin-left: 8px; font-size: 1rem; }
  .action-icons a.edit { color: #0d6efd; }
  .action-icons a.view { color: #20c997; }
  .action-icons a.delete { color: #dc3545; }
  .btn-add-route {
    background: linear-gradient(180deg, #0d6efd, #0b5ed7);
    color: #fff;
  }
  .no-data { color: #98a9b8; }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">

  
  <div class="page-header d-flex justify-content-between align-items-center">
    <div>
      <h4 class="page-title mb-0">Quản lý tuyến xe</h4>
      <small class="text-muted">Danh sách các tuyến xe đang hoạt động</small>
    </div>

    <div>
      <a href="<?php echo e(route('admin.routes.create')); ?>" class="btn btn-primary">
        <i class="fa fa-plus me-2"></i> Thêm tuyến xe
      </a>
    </div>
  </div>

  
  <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
  <?php endif; ?>

  
  <div class="card mb-4">
    <div class="card-body">
      <form action="<?php echo e(route('admin.routes.index')); ?>" method="GET" class="d-flex align-items-center">
        <div class="flex-grow-1 me-2">
          <input type="text" name="bus_route_id" class="form-control" placeholder="Tìm theo ID tuyến xe" value="<?php echo e(request('bus_route_id')); ?>">
        </div>
        <div>
          <button type="submit" class="btn btn-primary">Tìm kiếm</button>
        </div>
        <?php if(request()->has('bus_route_id')): ?>
          <div class="ms-2">
            <a href="<?php echo e(route('admin.routes.index')); ?>" class="btn btn-secondary">Xóa tìm kiếm</a>
          </div>
        <?php endif; ?>
      </form>
    </div>
  </div>

  
  <div class="card card-table">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead class="bg-white">
            <tr>
              <th style="width: 90px">Mã tuyến</th>
              <th>Điểm đi</th>
              <th>Điểm đến</th>
              <th style="width: 140px">Thời gian bắt đầu</th>
              <th style="width: 140px">Thời gian kết thúc</th>
              <th style="width: 120px">Hành động</th>
            </tr>
          </thead>
          <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
              <tr>
                <td><strong><?php echo e($r->ma_tuyen); ?></strong></td>
                <td><?php echo e($r->diem_di); ?></td>
                <td><?php echo e($r->diem_den); ?></td>

                
                <td>
                  <?php echo e($r->thoi_gian_bat_dau ? \Carbon\Carbon::parse($r->thoi_gian_bat_dau)->format('H:i') : '--:--'); ?>

                </td>
                <td>
                  <?php echo e($r->thoi_gian_ket_thuc ? \Carbon\Carbon::parse($r->thoi_gian_ket_thuc)->format('H:i') : '--:--'); ?>

                </td>

                
                <td class="action-icons">
                  <a href="<?php echo e(route('admin.routes.edit', $r->id)); ?>" class="edit" title="Sửa">
                    <i class="fa fa-pen"></i>
                  </a>
                  <a href="<?php echo e(route('admin.routes.show', $r->id)); ?>" class="view" title="Xem">
                    <i class="fa fa-eye"></i>
                  </a>
                  <a href="#" class="delete" title="Xóa"
                     onclick="event.preventDefault(); if(confirm('Xác nhận xóa tuyến <?php echo e($r->ma_tuyen); ?>?')) document.getElementById('delete-route-<?php echo e($r->id); ?>').submit();">
                    <i class="fa fa-trash"></i>
                  </a>
                  <form id="delete-route-<?php echo e($r->id); ?>" action="<?php echo e(route('admin.routes.destroy', $r->id)); ?>" method="POST" style="display:none;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                  </form>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
              <tr>
                <td colspan="6" class="text-center py-4 no-data">Không có tuyến xe nào được hiển thị.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      
      <div class="p-3 d-flex justify-content-end">
        <?php if(isset($routes) && method_exists($routes, 'links')): ?>
          <?php echo e($routes->links()); ?>

        <?php endif; ?>
      </div>
    </div>
  </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.index_admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BUS_TP_HCM\resources\views/backend/admin/routes/index.blade.php ENDPATH**/ ?>