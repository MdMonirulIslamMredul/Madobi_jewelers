<?php $__env->startPush('admin_style'); ?>
<?php echo $__env->make('admin.common.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('body'); ?>

    <div class="row justify-content-center mt-3">
        <div class="">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        General Settings
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-logo" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Logo Settings</button>
                            <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-links" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Website Links</button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-footer" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Footer</button>
                        </div>
                        <div class="w-75 mx-auto">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-logo" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                <?php echo $__env->make('admin.general.general-pages.logo_settings', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                            <div class="tab-pane fade" id="v-pills-links" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                <?php echo $__env->make('admin.general.general-pages.website_links', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                            <div class="tab-pane fade" id="v-pills-footer" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                <?php echo $__env->make('admin.general.general-pages.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div> <!-- col -->


    </div> <!-- .row -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_script'); ?>
<?php echo $__env->make('admin.common.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Madobi_jewelers\resources\views/admin/general/general.blade.php ENDPATH**/ ?>