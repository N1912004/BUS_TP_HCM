<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title mb-0">Quản lý Phụ xe</h4>
    <div>
        <a href="<?php echo e(route('admin.assistants.create')); ?>" class="btn btn-primary">
            <i class="fa fa-plus me-2"></i> Thêm phụ xe
        </a>
    </div>
</div>

<div class="card card-table">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-white">
                    <tr>
                        <th style="width:80px">ID</th>
                        <th>Tên phụ xe</th>
                        <th>Ngày sinh</th>
                        <th>Giới tính</th>
                        <th>Địa chỉ</th>
                        <th>Số điện thoại</th>
                        <th>Tuyến xe</th>
                        <th>Tên đăng nhập</th>
                        <th style="width:90px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $assistants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assistant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($assistant->id); ?></td>
                        <td><?php echo e($assistant->fullname); ?></td>
                        <td><?php echo e($assistant->birthday ? \Carbon\Carbon::parse($assistant->birthday)->format('d/m/Y') : ''); ?></td>
                        <td><?php echo e($assistant->gender); ?></td>
                        <td><?php echo e($assistant->address); ?></td>
                        <td><?php echo e($assistant->phone_number); ?></td>
                        <td>
                            <?php if($assistant->busRoute): ?>
                                <?php echo e($assistant->busRoute->ma_tuyen); ?> - <?php echo e($assistant->busRoute->diem_di); ?> đến <?php echo e($assistant->busRoute->diem_den); ?>

                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($assistant->username); ?></td>
                        <td class="action-icons">
                            <a href="<?php echo e(route('admin.assistants.edit', $assistant->id)); ?>" title="Chỉnh sửa">
                                <i class="fa fa-edit"></i>
                            </a>
                            <form action="<?php echo e(route('admin.assistants.destroy', $assistant->id)); ?>" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa phụ xe này?');" style="display:inline-block;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline delete" title="Xóa">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">Không có phụ xe nào được tìm thấy.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.index_admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/laravelversion1.com/resources/views/backend/admin/assistants/index.blade.php ENDPATH**/ ?>