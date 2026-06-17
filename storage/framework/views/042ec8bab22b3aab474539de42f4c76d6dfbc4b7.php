<?php $__env->startSection('title'); ?>
    User Trashed list
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_style'); ?>
<?php echo $__env->make('admin.common.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('body'); ?>
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3>User Trashed list</h3>
                        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="config-table" class="table display table-striped border no-wrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Last Updated</th>
                                <th>Profile</th>
                                
                                <th>User Name</th>
                                <th>User Email</th>
                                
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-user')): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($users->firstItem() + $loop->index); ?></strong>
                                    </td>
                                    <td><?php echo e($user->updated_at->format('d-M-Y')); ?></td>
                                    <td>
                                        <?php if($user->adminProfileImage): ?>
                                            <div class="avatar-container">
                                                <img alt="avatar"
                                                    src="<?php echo e(asset($user->adminProfileImage->admin_profile_image)); ?>"
                                                    class="rounded-circle" style="width:30px; height: 30px">
                                            </div>
                                        <?php elseif($user->profile->profileImage??null): ?>
                                            <div class="avatar-container">
                                                <img alt="avatar"
                                                    src="<?php echo e(asset($user->profile->profileImage->profile_image??null)); ?>"
                                                    class="rounded-circle" style="width:30px; height: 30px">
                                            </div>
                                        <?php else: ?>
                                            <div class="avatar-container">
                                                <img alt="avatar" src="<?php echo e(asset('profile/default_profile.png')); ?>"
                                                class="rounded-circle" style="width:30px; height: 30px">
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td><?php echo e($user->name); ?></td>
                                    <td><?php echo e($user->email); ?></td>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-user')): ?>
                                        
                                        
                                    <?php endif; ?>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-user')): ?>
                                    <td class="text-center">
                                        <div class="action-btns d-flex align-items-center">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-user')): ?>
                                            <div>
                                                <a href="<?php echo e(route('users.restore', ['id' => $user->id])); ?>"
                                                    class="text-success me-2" data-toggle="tooltip"
                                                    data-placement="top" data-bs-original-title="Restore"><i class="fa-solid fa-store"></i>
                                                </a>
                                            </div>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-user')): ?>
                                            <div>
                                                <form action="<?php echo e(route('users.forcedelete', ['id' => $user->id])); ?>"
                                                    method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-danger btn_custom show_confirm" data-toggle="tooltip"
                                                    data-placement="top" data-bs-original-title="Force Delete">
                                                        <i class="fa-solid fa-radiation"></i>
                                                    </button>
                                                </form>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <?php endif; ?>
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

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Madobi_jewelers\resources\views/admin/user/trash.blade.php ENDPATH**/ ?>