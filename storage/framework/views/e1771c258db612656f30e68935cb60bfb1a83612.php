<?php $__env->startSection('title'); ?>
    Profile Image
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
                    <h3 class="text-center">Update Profile Image</h3>
                    <?php if(!$adminProfileImage): ?>
                    <form class="form-horizontal" action="<?php echo e(route('admin.store.profile')); ?>" enctype="multipart/form-data" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label>Profile Image</label>
                            <input type="file" name="admin_profile_image"  class="form-control">
                            <?php if($adminProfileImage): ?>
                                <img src="<?php echo e(asset($adminProfileImage->admin_profile_image??null)); ?>" style="height: 200px">
                            <?php else: ?>
                                <img src="<?php echo e(asset('profile/default_profile.png')); ?>" style="height: 200px">
                            <?php endif; ?>
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                    <?php else: ?>
                    <form class="form-horizontal" action="<?php echo e(route('admin.update.profile')); ?>" enctype="multipart/form-data" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($adminProfileImage->id); ?>">
                        <div class="form-group">
                            <label>Profile Image</label>
                            <input type="file" name="admin_profile_image"  class="form-control">
                            <?php if($adminProfileImage): ?>
                                <img src="<?php echo e(asset($adminProfileImage->admin_profile_image??null)); ?>" class="img-fluid" style="height: 200px">
                            <?php else: ?>
                                <img src="<?php echo e(asset('profile/default_profile.png')); ?>" class="img-fluid" style="height: 200px">
                            <?php endif; ?>
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/profile/profile_image.blade.php ENDPATH**/ ?>