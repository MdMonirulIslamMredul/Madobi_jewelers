<?php $__env->startSection('title'); ?>
    User Details
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_style'); ?>
<?php echo $__env->make('admin.common.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('body'); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center pb-3">
                <h2>User Details</h2>
                <a href="<?php echo e(route('users.index')); ?>" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>

            </div>
            <div class="widget-content widget-content-area br-8">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        <tr><th colspan="2"><h3><?php echo e($user->role->role_name); ?></h3></th></tr>
                        <tr>
                            <th>Profile</th>
                            <td>
                                
                                <?php if($user->adminProfileImage): ?>
                                    <div class="avatar-container">
                                        <img alt="avatar"
                                            src="<?php echo e(asset($user->adminProfileImage->admin_profile_image)); ?>"
                                            class="img-fluid" style="width:150px; height: 150px">
                                    </div>
                                <?php elseif($user->profile->profileImage??null): ?>
                                    <div class="avatar-container">
                                        <img alt="avatar"
                                            src="<?php echo e(asset($user->profile->profileImage->profile_image??null)); ?>"
                                            class="img-fluid" style="width:150px; height: 150px">
                                    </div>
                                <?php else: ?>
                                    <div class="avatar-container">
                                        <img alt="avatar" src="<?php echo e(asset('profile/default_profile.png')); ?>"
                                        class="img-fluid" style="width:150px; height: 150px">
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td><?php echo e($user->name); ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo e($user->email); ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                <?php if($user->is_active == 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Created Date</th>
                            <td><?php echo e($user->created_at->format('d-M-Y')); ?></td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td><?php echo e($user->updated_at->format('d-M-Y')); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_script'); ?>
<?php echo $__env->make('admin.common.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/user/view.blade.php ENDPATH**/ ?>