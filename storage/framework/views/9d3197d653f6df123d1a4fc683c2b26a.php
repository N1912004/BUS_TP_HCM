<?php $__env->startSection('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title"><?php echo e(__('messages.bus_management')); ?></h4>

    <a href="<?php echo e(route('admin.buses.create')); ?>" class="btn btn-primary">
        + <?php echo e(__('messages.add_new_bus')); ?>

    </a>
</div>

<?php if(session('success')): ?>
<div class="alert alert-success"><?php echo e(session('success')); ?></div>
<?php endif; ?>

<div class="card card-table">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th><?php echo e(__('messages.bus_id')); ?></th>
                    <th><?php echo e(__('messages.bus_number')); ?></th>
                    <th><?php echo e(__('messages.bus_model')); ?></th>
                    <th><?php echo e(__('messages.bus_year')); ?></th>
                    <th><?php echo e(__('messages.bus_capacity')); ?></th>
                    <th><?php echo e(__('messages.bus_driver')); ?></th>
                    <th><?php echo e(__('messages.bus_route')); ?></th>
                    <th><?php echo e(__('messages.bus_status')); ?></th>
                    <th><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $buses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($bus->id); ?></td>
                    <td><?php echo e($bus->bus_number); ?></td>
                    <td><?php echo e($bus->model); ?></td>
                    <td><?php echo e($bus->year); ?></td>
                    <td><?php echo e($bus->capacity); ?></td>
                    <td><?php echo e($bus->driver->fullname ?? __('messages.not_available')); ?></td>
                    <td><?php echo e($bus->busRoute->ma_tuyen ?? __('messages.not_available')); ?></td>
                    <td>
                        <?php if($bus->status == 'active'): ?>
                            <span class="badge bg-success"><?php echo e(__('messages.status_active')); ?></span>
                        <?php elseif($bus->status == 'maintenance'): ?>
                            <span class="badge bg-warning"><?php echo e(__('messages.status_maintenance')); ?></span>
                        <?php else: ?>
                            <span class="badge bg-secondary"><?php echo e($bus->status); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.buses.edit', $bus->id)); ?>" class="btn btn-sm btn-warning"><?php echo e(__('messages.edit')); ?></a>
                        <form action="<?php echo e(route('admin.buses.destroy', $bus->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button onclick="return confirm('<?php echo e(__('messages.confirm_delete_bus')); ?>')" class="btn btn-sm btn-danger">
                                <?php echo e(__('messages.delete')); ?>

                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.index_admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/laravelversion1.com/resources/views/backend/admin/buses/index.blade.php ENDPATH**/ ?>