<?php $__env->startSection('content'); ?>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Tạo Vé Mới</h5>
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
                        <form method="POST" action="<?php echo e(route('admin.tickets.store')); ?>" class="form-horizontal">
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Người dùng</label>
                                <div class="col-sm-10">
                                    <select name="user_id" class="form-control">
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($user->id); ?>">
                                                <?php echo e($user->id); ?> - <?php echo e($user->fullname); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="help-block m-b-none text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Loại Vé</label>
                                <div class="col-sm-10">
                                    <select name="ticket_type" id="ticket_type" class="form-control">
                                        <option value="student">Sinh viên</option>
                                        <option value="regular">Người thường</option>
                                        <option value="elderly">Người già</option>
                                    </select>
                                    <?php $__errorArgs = ['ticket_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="help-block m-b-none text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="form-group" id="student_card_group" style="display: none;">
                                <label class="col-sm-2 control-label">Có Thẻ Sinh Viên?</label>
                                <div class="col-sm-10">
                                    <div class="checkbox i-checks">
                                        <label> <input type="checkbox" name="has_student_card" value="1"> <i></i> Có
                                        </label>
                                    </div>
                                    <?php $__errorArgs = ['has_student_card'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="help-block m-b-none text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="form-group" id="age_group" style="display: none;">
                                <label class="col-sm-2 control-label">Tuổi</label>
                                <div class="col-sm-10">
                                    <input type="number" name="age" class="form-control" value="<?php echo e(old('age')); ?>">
                                    <?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="help-block m-b-none text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">Giá Vé</label>
                                <div class="col-sm-10">
                                    <input type="text" name="price" id="price_display" class="form-control"
                                        value="<?php echo e(old('price')); ?>" readonly>
                                    <input type="hidden" name="price_hidden" id="price_hidden">
                                    <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="help-block m-b-none text-danger"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="hr-line-dashed"></div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-2">
                                    <button class="btn btn-primary" type="submit">Lưu Vé</button>
                                    <a href="<?php echo e(route('admin.tickets.index')); ?>" class="btn btn-white">Hủy</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ticketTypeSelect = document.getElementById('ticket_type');
            const studentCardGroup = document.getElementById('student_card_group');
            const ageGroup = document.getElementById('age_group');
            const priceDisplay = document.getElementById('price_display');
            const priceHidden = document.getElementById('price_hidden');
            const hasStudentCardCheckbox = document.querySelector('input[name="has_student_card"]');
            const ageInput = document.querySelector('input[name="age"]');

            function updatePriceAndVisibility() {
                const selectedType = ticketTypeSelect.value;
                let price = 0;

                studentCardGroup.style.display = 'none';
                ageGroup.style.display = 'none';

                if (selectedType === 'student') {
                    studentCardGroup.style.display = 'block';
                    if (hasStudentCardCheckbox.checked) {
                        price = 3000;
                    } else {
                        price = 6000; // Default to regular price if no student card
                    }
                } else if (selectedType === 'regular') {
                    price = 6000;
                } else if (selectedType === 'elderly') {
                    ageGroup.style.display = 'block';
                    const age = parseInt(ageInput.value);
                    if (!isNaN(age) && age > 65) {
                        price = 0;
                    } else {
                        price = 6000; // Default to regular price if age condition not met
                    }
                }

                priceDisplay.value = price.toLocaleString('vi-VN'); // Format for display
                priceHidden.value = price; // Store actual numeric value
            }

            ticketTypeSelect.addEventListener('change', updatePriceAndVisibility);
            hasStudentCardCheckbox.addEventListener('change', updatePriceAndVisibility);
            ageInput.addEventListener('input', updatePriceAndVisibility);

            // Initial call to set correct state on page load
            updatePriceAndVisibility();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.admin.index_admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\BUS_TP_HCM\resources\views/backend/admin/tickets/create.blade.php ENDPATH**/ ?>