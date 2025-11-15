<?php $__env->startSection('content'); ?>
<div class="container-fluid">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title">Sửa thông tin xe #<?php echo e($bus->id); ?></h4>
    <a href="<?php echo e(route('admin.buses.index')); ?>" class="btn btn-secondary">Quay lại</a>
  </div>

  <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
  <?php endif; ?>

  <div class="card">
    <div class="card-body">
      <form action="<?php echo e(route('admin.buses.update', $bus->id)); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Biển số -->
        <div class="mb-3">
          <label>Biển số</label>
          <input type="text" name="bus_number" class="form-control" value="<?php echo e(old('bus_number', $bus->bus_number)); ?>" required>
          <?php $__errorArgs = ['bus_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="text-danger"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Model, Năm, Sức chứa, Trạng thái giữ nguyên như cũ -->
        <div class="row">
          <div class="col-md-4 mb-3">
            <label>Model</label>
            <input type="text" name="model" class="form-control" value="<?php echo e(old('model', $bus->model)); ?>">
          </div>

          <div class="col-md-2 mb-3">
            <label>Năm</label>
            <input type="number" name="year" class="form-control" value="<?php echo e(old('year', $bus->year)); ?>">
          </div>

          <div class="col-md-3 mb-3">
            <label>Sức chứa</label>
            <input type="number" name="capacity" class="form-control" value="<?php echo e(old('capacity', $bus->capacity ?? 30)); ?>">
          </div>

          <div class="col-md-3 mb-3">
            <label>Trạng thái</label>
            <select name="status" class="form-control">
              <option value="active" <?php echo e(old('status', $bus->status) == 'active' ? 'selected' : ''); ?>>Hoạt động</option>
              <option value="maintenance" <?php echo e(old('status', $bus->status) == 'maintenance' ? 'selected' : ''); ?>>Bảo trì</option>
              <option value="retired" <?php echo e(old('status', $bus->status) == 'retired' ? 'selected' : ''); ?>>Ngừng hoạt động</option>
            </select>
          </div>
        </div>

        <!-- Tài xế -->
        <div class="mb-3">
          <label>Tài xế</label>
          <select id="driver_id" name="driver_id" class="form-control" onchange="loadRoutesByDriver(this.value)">
            <option value="">-- Chọn tài xế --</option>
            <?php $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($driver->id); ?>" <?php echo e(old('driver_id', $bus->driver_id) == $driver->id ? 'selected' : ''); ?>>
                <?php echo e($driver->fullname ?? $driver->username ?? $driver->id); ?>

              </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
        </div>

        <!-- Tuyến xe -->
        <div class="mb-3">
          <label for="bus_route_id" class="form-label">Tuyến xe</label>
          <select class="form-select <?php $__errorArgs = ['bus_route_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="bus_route_id" name="bus_route_id">
            <option value="">-- Chọn tuyến xe --</option>
            <!-- Tuyến sẽ được load từ JavaScript -->
          </select>
          <?php $__errorArgs = ['bus_route_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="invalid-feedback"><?php echo e($message); ?></div>
          <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div class="d-flex gap-2">
          <button class="btn btn-primary">Lưu thay đổi</button>
          <a href="<?php echo e(route('admin.buses.index')); ?>" class="btn btn-light">Hủy</a>
        </div>
      </form>
    </div>
  </div>
</div>

<?php $__env->startSection('scripts'); ?>
  <script>
 function loadRoutesByDriver(driverId, selectedRouteId = null) {
    const busRouteSelect = document.getElementById('bus_route_id');
    busRouteSelect.innerHTML = '<option value="">-- Chọn tuyến --</option>';

    if (!driverId) return;

    fetch(`<?php echo e(route('api.admin.routes.byDriver', ['driverId' => '__DRIVER_ID__'])); ?>`.replace('__DRIVER_ID__', driverId))
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            data.routes.forEach(route => {
                const option = document.createElement('option');
                option.value = route.id;
                option.textContent = `${route.ma_tuyen} - ${route.diem_di} → ${route.diem_den}`;
                if (selectedRouteId && route.id == selectedRouteId) {
                    option.selected = true;
                }
                busRouteSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching routes:', error));
}

// Initial load if a driver is already selected (e.g., on page load for editing)
document.addEventListener('DOMContentLoaded', function() {
    const driverId = document.getElementById('driver_id').value;
    const selectedBusRouteId = "<?php echo e(old('bus_route_id', $bus->bus_route_id)); ?>";
    if (driverId) {
        loadRoutesByDriver(driverId, selectedBusRouteId);
    }
});
  </script>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.index_admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BUS_TP_HCM\resources\views/backend/admin/buses/edit.blade.php ENDPATH**/ ?>