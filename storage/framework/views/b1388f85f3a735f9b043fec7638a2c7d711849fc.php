<?php $__env->startSection('title'); ?>
টোটাল স্টক
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_style'); ?>
<?php echo $__env->make('admin.common.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('body'); ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3>টোটাল স্টক</h3>
                    </div>
                    <div>
                        <a href="<?php echo e(route('stock.create')); ?>" class="btn btn-dark">নতুন স্টক তৈরি করুন</a>
                    </div>
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
                            <th>লোকেশন</th>
                            <th>একশন্স</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sl = 1; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $stocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($sl); ?></strong>
                            </td>
                            <td><?php echo e($stock->productCategory->category_name ?? 'N/A'); ?></td>
                            <td><?php echo e($stock->product->product_name ?? 'N/A'); ?></td>
                            <td><?php echo e($stock->qty); ?></td>
                            <td><?php echo e($stock->karat); ?></td>
                            <td><?php echo e($stock->bhori); ?></td>
                            <td><?php echo e($stock->ana); ?></td>
                            <td><?php echo e($stock->roti); ?></td>
                            <td><?php echo e($stock->point); ?></td>
                            <td><?php echo e($stock->gram); ?></td>
                            <td>
                                <?php if($stock->location == 'is_shop'): ?>
                                <span class="badge bg-success"><a href="<?php echo e(route('shop.stock')); ?>" class="text-light">দোকান</a></span>
                                <?php elseif($stock->location == 'is_warehouse'): ?>
                                <span class="badge bg-danger"><a href="<?php echo e(route('warehouse.stock')); ?>" class="text-light">গুদাম</a></span>
                                <?php endif; ?>
                            </td>
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
                                        <a href="<?php echo e(route('stock.edit',$stock->id)); ?>"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <form action="<?php echo e(route('stock.delete')); ?>"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="stock_id" value="<?php echo e($stock->id); ?>">
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

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/stock/mainStock.blade.php ENDPATH**/ ?>