<?php $__env->startSection('content'); ?>
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title">Thêm xe mới</h4>
    <a href="<?php echo e(route('admin.buses.index')); ?>" class="btn btn-secondary">Quay lại</a>
  </div>

  <div class="card">
    <div class="card-body">
      <form action="<?php echo e(route('admin.buses.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <div class="mb-3">
          <label>Biển số</label>
          <input type="text" name="bus_number" class="form-control" value="<?php echo e(old('bus_number')); ?>" required>
          <?php $__errorArgs = ['bus_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="row">
          <div class="col-md-4 mb-3">
            <label><?php echo e(__('messages.bus_model')); ?></label>
            <input type="text" name="model" class="form-control" value="<?php echo e(old('model')); ?>">
          </div>
          <div class="col-md-2 mb-3">
            <label>Năm</label>
            <input type="number" name="year" class="form-control" value="<?php echo e(old('year')); ?>">
          </div>
          <div class="col-md-3 mb-3">
            <label>Sức chứa</label>
            <input type="number" name="capacity" class="form-control" value="<?php echo e(old('capacity', 30)); ?>">
          </div>
          <div class="col-md-3 mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
              <option value="active">Hoạt động</option>
              <option value="maintenance">Bảo trì</option>
              <option value="retired">Ngừng hoạt động</option>
            </select>
          </div>
        </div>

        <div class="mb-3">
          <label>Tuyến</label>
          <select name="bus_route_id" class="form-control">
            <option value="">-- Chọn tuyến --</option>
            <?php $__currentLoopData = $routes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($rt->id); ?>"><?php echo e($rt->ma_tuyen ?? $rt->id); ?> - <?php echo e($rt->diem_di ?? ''); ?> → <?php echo e($rt->diem_den ?? ''); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <div class="mb-3">
          <label>Tài xế</label>
          <select name="driver_id" class="form-control">
            <option value="">-- Chọn tài xế --</option>
            <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($d->id); ?>"><?php echo e($d->fullname ?? $d->username ?? $d->id); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <button class="btn btn-primary">Lưu</button>
        <a href="<?php echo e(route('admin.buses.index')); ?>" class="btn btn-light">Hủy</a>
      </form>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.index_admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BUS_TP_HCM\resources\views/backend/admin/buses/create.blade.php ENDPATH**/ ?>