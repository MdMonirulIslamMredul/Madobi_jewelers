<?php $__env->startSection('title'); ?>
    User list
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
                        <h3>User Information</h3>
                        <a href="<?php echo e(route('users.trash')); ?>" class="btn btn-warning"><i
                                class="fa-solid fa-trash-can-arrow-up fa-fw"></i> View Trash</a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo e(route('users.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="mb-3 col-md-2">
                                <label for="image" class="form-label"><?php echo e(__('Users Image')); ?></label>
                                <div class="d-block position-relative">
                                    <img id="previewImage" src="<?php echo e(asset('profile/default_profile.png' )); ?>" alt="your image" width="150" height="150" onclick="document.getElementById('photoInput').click();" style="cursor: pointer;">
                                    <input type="file" id="photoInput" name="image" class="file-validation d-none" accept="image/*" onchange="previewPhoto(this)">
                                </div>
                            </div>
                            <div class="mb-3 col-md-10">
                                <label for="role_id" class="form-label">Select Role*</label>
                                <select id="defaultSelect" required name="role_id"
                                    class="form-select
                                <?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    is-invalid
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option selected>Choose a Role</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <option value="<?php echo e($role->id); ?>"><?php echo e($role->role_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['role_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($message); ?></strong>
                                    </span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="basic-icon-default-fullname">User's Name</label>
                                <div class="input-group">
                                    <input type="text" name="name"
                                        class="form-control
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('name')); ?>" placeholder="Enter User Name">
                                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="basic-icon-default-phone">User's Phone</label>
                                <div class="input-group">
                                    <input type="text" name="phone"
                                        class="form-control
                            <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('phone')); ?>" placeholder="Enter User Phone">
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="basic-icon-default-email">User's Email</label>
                                <div class="input-group">
                                    <input type="text" name="email"
                                        class="form-control
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('email')); ?>" placeholder="Enter User Email">
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="form-password-toggle mb-3 col-md-6">
                                <label class="form-label" for="basic-default-password">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password"
                                        class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="basic-default-password" placeholder=" ············"
                                        value="<?php echo e(old('password')); ?>" aria-describedby="basic-default-password">
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert">
                                            <strong><?php echo e($message); ?></strong>
                                        </span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <button type="submit" class="btn btn-info">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Last Updated</th>
                            <th>Profile</th>
                            <th>Role Name</th>
                            <th>User Name</th>
                            <th>User Phone</th>
                            <th>User Email</th>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-user')): ?>
                                <th>User Status</th>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-user')): ?>
                                <th>Actions</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sl = 1; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($sl); ?></strong>
                                </td>
                                <td><?php echo e($user->updated_at->format('d-M-Y')); ?></td>
                                <td>
                                    <?php if($user->adminProfileImage): ?>
                                        <div class="avatar-container">
                                            <img alt="avatar"
                                                src="<?php echo e(asset($user->adminProfileImage->admin_profile_image)); ?>"
                                                class="rounded-circle" style="width:30px; height: 30px">
                                        </div>
                                    <?php elseif($user->profile->profileImage ?? null): ?>
                                        <div class="avatar-container">
                                            <img alt="avatar"
                                                src="<?php echo e(asset($user->profile->profileImage->profile_image ?? null)); ?>"
                                                class="rounded-circle" style="width:30px; height: 30px">
                                        </div>
                                    <?php elseif($user->image): ?>
                                        <div class="avatar-container">
                                            <img alt="avatar"
                                                src="<?php echo e(asset('user/' . $user->image)); ?>"
                                                class="rounded-circle" style="width:30px; height: 30px">
                                        </div>
                                        
                                    <?php else: ?>
                                        <div class="avatar-container">
                                            <img alt="avatar" src="<?php echo e(asset('profile/default_profile.png')); ?>"
                                                class="rounded-circle" style="width:30px; height: 30px">
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($user->role->role_name); ?></td>
                                <td><?php echo e($user->name); ?></td>
                                <td><?php echo e($user->phone ?? 'N/A'); ?></td>
                                <td><?php echo e($user->email ?? 'N/A'); ?></td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-user')): ?>
                                    
                                    <td>
                                        <?php if($user->is_active == 1): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Inactive</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-user')): ?>
                                    <td class="text-center">
                                        <div class="action-btns d-flex">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-user')): ?>
                                                <div>
                                                    <a href="<?php echo e(route('users.show', $user->id)); ?>"
                                                        class="action-btn bs-tooltip me-2" data-toggle="tooltip"
                                                        data-placement="top" title="" data-bs-original-title="View">
                                                        <i class="fa-solid fa-eye text-success"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit-user')): ?>
                                                <?php if($user->email != 'admin@admin.com'): ?>
                                                    <div>
                                                        <a href="<?php echo e(route('users.edit', $user->id)); ?>"
                                                            class="action-btn bs-tooltip me-1" data-toggle="tooltip"
                                                            data-placement="top" title="" data-bs-original-title="Edit">
                                                            <i class="fa-regular fa-pen-to-square text-info"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-user')): ?>
                                                <?php if($user->email != 'admin@admin.com'): ?>
                                                    <div>
                                                        <form action="<?php echo e(route('users.destroy', $user->id)); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="submit"
                                                                class="action-btn bs-tooltip btn_custom show_confirm"
                                                                data-toggle="tooltip" data-placement="top" title=""
                                                                data-bs-original-title="Delete"><i
                                                                    class="fa-solid fa-trash-can text-warning"></i></button>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                            <?php $sl++ ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    
    <script>
        function previewPhoto(input) {
            var preview = document.getElementById('previewImage');
            var file = input.files[0];
            var reader = new FileReader();
    
            reader.onloadend = function () {
                preview.src = reader.result;
            }
    
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "<?php echo e(asset('web/media/avatars/user2.png')); ?>";
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_script'); ?>
    <?php echo $__env->make('admin.common.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/user/index.blade.php ENDPATH**/ ?>