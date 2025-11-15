<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title mb-0">Quản lý tài xế</h4>
    <div>
        <a href="<?php echo e(route('admin.drivers.create')); ?>" class="btn btn-primary">
            <i class="fa fa-plus me-2"></i> Thêm tài xế
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-white">
                    <tr>
                        <th>Họ tên</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Bằng lái</th>
                        <th>Tuyến xe</th>
                        <th>Tên đăng nhập</th>
                        <th>Mật khẩu</th>
                        <th style="width:100px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $drivers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($driver->fullname); ?></td>
                        <td><?php echo e($driver->birthday); ?></td>
                        <td><?php echo e($driver->gender); ?></td>
                        <td><?php echo e($driver->address); ?></td>
                        <td><?php echo e($driver->phone_number); ?></td>
                        <td><?php echo e($driver->license_number); ?></td>
                        <td><?php echo e($driver->busRoute->name ?? 'N/A'); ?></td>
                        <td><?php echo e($driver->username); ?></td>
                        <td><?php echo e($driver->password_plain ?? '••••••'); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.drivers.edit', $driver->id)); ?>" class="btn btn-sm btn-warning">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.drivers.destroy', $driver->id)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tài xế này?')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="text-center py-4 text-muted">
                            Danh sách tài xế sẽ hiển thị ở đây.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.index_admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/laravelversion1.com/resources/views/backend/admin/drivers/index.blade.php ENDPATH**/ ?>