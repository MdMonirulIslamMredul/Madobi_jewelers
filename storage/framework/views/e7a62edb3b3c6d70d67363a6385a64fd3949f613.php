
<div class="row">
    <div class="col-lg-12">
        <div class="card">

            <?php if(session('message')): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('store.logo')); ?>" enctype="multipart/form-data" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if($logo!=null): ?>
                        <input type="hidden" name="id" value="<?php echo e($logo->id); ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Website name</label>
                        <?php if($logo!=null): ?>
                            <input type="text" class="form-control" rows="5" value="<?php echo e($logo->site_name); ?>" name="site_name" id="email" placeholder="Website name">
                        <?php else: ?>
                            <input type="text" class="form-control" rows="5" name="site_name" id="email" placeholder="Website name">
                        <?php endif; ?>

                    </div>
                    <div class="form-group">
                        <label>Logo Image</label>
                        <input type="file" name="logo_image" class="form-control">
                        <?php if($logo!=null): ?>
                            <img src="<?php echo e(asset($logo->logo_image)); ?>" width="100" height="100" alt="">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Favicon</label>
                        <input type="file" name="favicon"  class="form-control">
                        <?php if($logo!=null): ?>
                            <img src="<?php echo e(asset($logo->favicon)); ?>" width="100" height="100" alt="">
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <button type="submit" class="btn btn-info">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/7.1.1/tinymce.min.js" referrerpolicy="origin"></script>
<script type="text/javascript">
    tinymce.init({
        selector: 'textarea#default'
    });
</script>

<?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/general/general-pages/logo_settings.blade.php ENDPATH**/ ?>