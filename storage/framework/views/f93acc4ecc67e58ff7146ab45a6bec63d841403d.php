<?php $__env->startSection('title'); ?>
কারিগর
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_style'); ?>
<?php echo $__env->make('admin.common.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('body'); ?>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3>কারিগর স্টক</h3>
                    </div>
                    <div>
                        <a href="<?php echo e(route('karigor.stock')); ?>" class="btn btn-dark">নতুন কারিগর স্টক তৈরি করুন</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>কারিগর</th>
                        <th>ক্যাটাগরি</th>
                        <th>পরিমাণ</th>
                        <th>পরিমাণ(গ্রাম হিসাব)</th>
                        <th>একশন</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        <?php $sl = 1; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $karigors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karigor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($sl); ?></strong>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-start">
                                    <div>
                                        <img alt="avatar" src="<?php echo e(asset('user/' . $karigor->user->image)); ?>" class="rounded-circle" style="width:40px; height: 40px">
                                    </div>
                                    <div>
                                        <?php echo e($karigor->user->name); ?><br><a href="#"><?php echo e($karigor->user->phone); ?></a>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($karigor->category->category_name ?? 'N/A'); ?></td>
                            <td><?php echo e($karigor->bhori ?? 0); ?> ভরি, <?php echo e($karigor->ana ?? 0); ?> আনা, <?php echo e($karigor->roti ?? 0); ?> রতি, <?php echo e($karigor->point ?? 0); ?> পয়েন্ট</td>
                            <td><?php echo e($karigor->gram ?? 0); ?> গ্রাম</td>
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    <div> 
                                        <a href=""
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="View" data-toggle="modal" data-target="#KarigorModal">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>     
                                    </div>
                                    <div>
                                        <a href="<?php echo e(route('karigor.edit', $karigor->id)); ?>"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <form action="#"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="karigor_id" value="<?php echo e($karigor->id); ?>">
                                            <button type="submit" class="text-warning btn_custom show_confirm" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Delete">
                                                <i class="fa-solid fa-trash-can fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php $sl++ ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        No Data Found!
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>

   
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_script'); ?>
<?php echo $__env->make('admin.common.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/karigor/karigor.blade.php ENDPATH**/ ?>