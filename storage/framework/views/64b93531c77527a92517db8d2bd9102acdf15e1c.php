<?php $__env->startSection('title'); ?>
    প্রোফাইল
<?php $__env->stopSection(); ?>
<?php $__env->startSection('body'); ?>
    <div class="row mt-2">
        <div class="col-lg-12 ">
            <div class="card mt-3">
                <?php if(session('message')): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo e(session('message')); ?>

                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h3 class="text-center">তথ্য আপডেট করুন</h3>
                    <form class="form-horizontal" action="<?php echo e(route('admin.update.profile')); ?>" enctype="multipart/form-data" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($user->id); ?>">
                        <div class="form-group">
                            <label>নাম</label>
                            <input type="text" class="form-control" rows="5" name="name" value="<?php echo e($user->name); ?>" id="name" placeholder="নাম">
                        </div>
                        <div class="form-group">
                            <label>ইমেইল</label>
                            <input type="email" class="form-control" rows="5" name="email" value="<?php echo e($user->email); ?>" id="email" placeholder="ইমেইল">
                        </div>

                        <div class="form-group">
                            <label>নিউ পাসওয়ার্ড</label>
                            <input type="text" class="form-control" rows="5" name="new_password" id="youtube" placeholder="নিউ পাসওয়ার্ড">
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-info">আপডেট</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/profile/profile.blade.php ENDPATH**/ ?>