<?php
    $roles = App\Models\Role::whereNotIn('role_slug', ['super_admin', 'admin'])->get();
?>
<div class="card mb-3" style="background-color: #d4d4d4;">
    <form id="user_form" class="form-horizontal" action="<?php echo e(route('new.user.store')); ?>" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="m-3">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="col-md-12 mb-3">
                    <label for="profile" class="form-label mb-1"><?php echo e(__('Photo :')); ?></label>    
                    <div class="d-block position-relative">
                        <img id="previewImage" src="<?php echo e(asset('profile/default_profile.png' )); ?>" alt="your image" width="100" height="100" onclick="document.getElementById('photoInput').click();" style="cursor: pointer;">
                        <input type="file" id="photoInput" name="profile" class="d-none" onchange="previewPhoto(this)">
                    </div>
                </div>
                    <div class="mb-3 col-md-12 text-center">
                        <label for="photo1" class="form-label mb-1"><?php echo e(__('NID/Birth-certificate :')); ?></label>    
                        <div class="d-block position-relative">
                            <img id="previewImage1" src="<?php echo e(asset('cover/default-cover.jpg' )); ?>" alt="your image" width="200" height="120" onclick="document.getElementById('photo1Input').click();" style="cursor: pointer;">
                            <input type="file" id="photo1Input" name="photo1" class="d-none" onchange="previewPhoto1(this)">
                        </div>
                    </div> 
                    
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="role_id" class="form-label">Select Role *</label>
                            <select id="role_id" required name="role_id"
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
                                <option value="null">Choose a Role</option>
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
                            <label class="form-label" for="basic-icon-default-fullname">Name *</label>
                            <div class="input-group">
                                <input type="text" name="name" id="user_name"
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
                                    value="<?php echo e(old('name')); ?>" placeholder="Enter Name">
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
                            <label class="form-label" for="basic-icon-default-phone">Phone *</label>
                            <div class="input-group">
                                <input type="text" name="phone" id="user_phone"
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
                                    value="<?php echo e(old('phone')); ?>" placeholder="Enter Phone Number">
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
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="address">Address:</label>
                            <div class="input-group">
                                <textarea name="address" class="form-control" id="address" cols="100" rows="4" placeholder="Enter address here..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </form>
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
    function previewPhoto1(input) {
        var preview = document.getElementById('previewImage1');
        var file = input.files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "<?php echo e(asset('cover/default-cover.jpg')); ?>";
        }
    }
</script>
<?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/user/form.blade.php ENDPATH**/ ?>