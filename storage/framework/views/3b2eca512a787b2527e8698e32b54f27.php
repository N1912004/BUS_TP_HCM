<?php $__env->startSection('content'); ?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Quản lý Vé</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a></li>
                            <li><a href="#">Config option 2</a></li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-sm-5 m-b-xs">
                            <a href="<?php echo e(route('admin.tickets.create')); ?>" class="btn btn-primary btn-sm">Thêm Vé Mới</a>
                        </div>
                        <div class="col-sm-4 m-b-xs">
                            <!-- Optional: Filters or bulk actions -->
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="text" placeholder="Tìm kiếm" class="input-sm form-control"> <span class="input-group-btn">
                                <button type="button" class="btn btn-sm btn-primary"> Tìm!</button> </span>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người dùng</th>
                                <th>Loại Vé</th>
                                <th>Giá Vé</th>
                                <th>Có Thẻ SV</th>
                                <th>Tuổi</th>
                                <th>Ngày Tạo</th>
                                <th>Hành Động</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $__currentLoopData = $tickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($ticket->id); ?></td>
                                <td><?php echo e($ticket->user->fullname ?? 'N/A'); ?></td>
                                <td><?php echo e($ticket->ticket_type); ?></td>
                                <td><?php echo e(number_format($ticket->price, 0, ',', '.')); ?> VND</td>
                                <td>
                                    <?php if($ticket->has_student_card): ?>
                                        <span class="label label-primary">Có</span>
                                    <?php else: ?>
                                        <span class="label label-default">Không</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($ticket->age ?? 'N/A'); ?></td>
                                <td><?php echo e($ticket->created_at->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <a href="<?php echo e(route('admin.tickets.edit', $ticket->id)); ?>" class="btn btn-xs btn-warning">Sửa</a>
                                    <form action="<?php echo e(route('admin.tickets.destroy', $ticket->id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa không?')">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.index_admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BUS_TP_HCM\resources\views/backend/admin/tickets/index.blade.php ENDPATH**/ ?>