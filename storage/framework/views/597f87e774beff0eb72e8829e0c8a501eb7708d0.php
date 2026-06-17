<?php $__env->startSection('title'); ?>
গুদাম স্টক
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_style'); ?>
<?php echo $__env->make('admin.common.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('body'); ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>গুদাম স্টক</h3>
                </div>
            </div>
            <div class="card-body">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ক্যাটেগরি</th>
                            <th>প্রোডাক্ট</th>
                            <th>পরিমাণ</th>
                            <th>ক্যারেট</th>
                            <th>ভরি</th>
                            <th>আনা</th>
                            <th>রতি</th>
                            <th>পয়েন্ট</th>
                            <th>গ্রাম হিসাব</th>
                            <th>একশন্স</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sl = 1; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $warehouses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($sl); ?></strong>
                            </td>
                            <td><?php echo e($warehouse->productCategory->category_name ?? 'N/A'); ?></td>
                            <td><?php echo e($warehouse->product->product_name ?? 'N/A'); ?></td>
                            <td><?php echo e($warehouse->qty); ?></td>
                            <td><?php echo e($warehouse->karat); ?></td>
                            <td><?php echo e($warehouse->bhori); ?></td>
                            <td><?php echo e($warehouse->ana); ?></td>
                            <td><?php echo e($warehouse->roti); ?></td>
                            <td><?php echo e($warehouse->point); ?></td>
                            <td><?php echo e($warehouse->gram); ?></td>
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    <div>
                                        <a href=""
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="#"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <form action="<?php echo e(route('stock.delete')); ?>"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="warehouse_id" value="<?php echo e($warehouse->id); ?>">
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

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Madobi_jewelers\resources\views/admin/stock/warehouseStock.blade.php ENDPATH**/ ?>