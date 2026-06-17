<?php $__env->startSection('title'); ?>
    ক্রয় রিপোর্ট
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_style'); ?>
<?php echo $__env->make('admin.common.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('body'); ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <?php if(request()->has('filter')): ?>
                    <div class="col-md-2 text-start" style="margin-top: 34px;">
                        <a href="<?php echo e(route('purchases.report.index', ['pdf' => 1] + request()->query())); ?>" class="btn btn-outline-success btn-lg">প্রিন্ট করুন</a>
                    </div>
                    <?php endif; ?>
                    <div class="col-10">
                        <form action="<?php echo e(route('purchases.report.index')); ?>" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="filter" class="col-form-label">Choose Daily/Weekly/Monthly/Yearly</label>
                                    <select class="form-control" name="filter" id="filter">
                                        <option>select</option>
                                        <option <?php if(request()->filter=='daily'): ?> selected <?php endif; ?> value="daily">দৈনিক</option>
                                        <option <?php if(request()->filter=='weekly'): ?> selected <?php endif; ?>  value="weekly">সাপ্তাহিক</option>
                                        <option <?php if(request()->filter=='monthly'): ?> selected <?php endif; ?> value="monthly">মাসিক</option>
                                        <option <?php if(request()->filter=='3-months'): ?> selected <?php endif; ?> value="3-months">ত্রৈমাসিক</option>
                                        <option <?php if(request()->filter=='6-months'): ?> selected <?php endif; ?> value="6-months">ষান্মাসিক</option>
                                        <option <?php if(request()->filter=='yearly'): ?> selected <?php endif; ?> value="yearly">বাৎসরিক</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="from_date" class="col-form-label">From Date</label>
                                    <input type="date" value="<?php echo e(request()->from_date); ?>" class="form-control" id="from_date" name="from_date">
                                </div>
                                <div class="col-md-2">
                                    <label for="to_date" class="col-form-label">To Date</label>
                                    <input type="date" value="<?php echo e(request()->to_date); ?>" class="form-control" id="to_date" name="to_date">
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="submit" class="btn btn-outline-primary" style="margin-top: 36px;">ফিল্টার করুন</button>
                                </div>
                            </div>
                        </form>
                    </div>   
                </div>
            </div>
            <div class="card-body">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>অর্ডারের তারিখ</th>
                            <th>সরবরাহকারী নাম</th>
                            <th>সরবরাহকারী মোবাইল</th>
                            <th>ক্যাটেগরি</th>
                            <th>প্রোডাক্ট</th>
                            <th>পরিমাণ</th>
                            <th>মূল্য</th>
                            <th>প্রদান</th>
                            <th>বকেয়া</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sl = 1; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                        $product = $products->firstWhere('id', $purchase->product_id)->product_name ?? 'N/A';
                        $cat = $categories->firstWhere('id', $purchase->category_id)->category_name ?? 'N/A';
                        ?>
                        <tr>
                            <td><strong><?php echo e($sl); ?></strong></td>
                            <td><?php echo e($purchase->order_date); ?></td>
                            <td><?php echo e($purchase->user->name); ?></td>
                            <td><?php echo e($purchase->user->phone); ?></td>
                            <td><?php echo e($cat); ?></td>
                            <td><?php echo e($product); ?></td>
                            <td><?php echo e($purchase->bhori ?? 0); ?> ভরি, <?php echo e($purchase->ana ?? 0); ?> আনা, <?php echo e($purchase->roti ?? 0); ?> রতি, <?php echo e($purchase->point ?? 0); ?> পয়েন্ট </td>
                            <td><?php echo e($purchase->total_price); ?></td>
                            <td><?php echo e($purchase->total_payment); ?></td>
                            <td><?php echo e($purchase->due_payment); ?></td>
                        </tr>
                        <?php $sl++ ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        No Data Found!
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-3">
                        <span class="badge bg-primary"><h6 class="mt-2">সর্বমোট ক্রয়: <?php echo e($totalBhori); ?> ভরি, <?php echo e($totalAna); ?> আনা, <?php echo e($totalRoti); ?> রতি , <?php echo e($totalPoint); ?> পয়েন্ট</h6></span>
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-success"><h6 class="mt-2">সর্বমোট মূল্য: <?php echo e($totalPrice); ?> /-</h6></span>
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-info"><h6 class="mt-2">সর্বমোট প্রদান: <?php echo e($totalAdvPayment); ?> /-</h6></span>
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-danger"><h6 class="mt-2">সর্বমোট বকেয়া: <?php echo e($totalDuePayment); ?> /-</h6></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_script'); ?>
<?php echo $__env->make('admin.common.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/report/purchases/index.blade.php ENDPATH**/ ?>