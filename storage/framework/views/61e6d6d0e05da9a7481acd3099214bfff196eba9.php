
<div class="row">
    <div class="col-lg-12">
        <div class="card">

            <?php if(session('message')): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('store.links')); ?>" enctype="multipart/form-data" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if($links!=null): ?>
                        <input type="hidden" name="id" value="<?php echo e($links->id); ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Email</label>
                        <?php if($links!=null): ?>
                            <input type="email" class="form-control" rows="5" value="<?php echo e($links->email); ?>" name="email" id="email" placeholder="Email">
                        <?php else: ?>
                            <input type="email" class="form-control" rows="5" name="email" id="email" placeholder="Email">
                        <?php endif; ?>

                    </div>
                    <div class="form-group">
                        <label>Facebook</label>
                        <?php if($links!=null): ?>
                            <input type="text" class="form-control" rows="5" value="<?php echo e($links->facebook); ?>" name="facebook" id="facebook" placeholder="Facebook">
                        <?php else: ?>
                            <input type="text" class="form-control" rows="5" name="facebook" id="facebook" placeholder="Facebook">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Instagram</label>
                        <?php if($links!=null): ?>
                            <input type="text" class="form-control" rows="5" name="instagram" value="<?php echo e($links->instagram); ?>" id="instagram" placeholder="Instagram">
                        <?php else: ?>
                            <input type="text" class="form-control" rows="5" name="instagram" id="instagram" placeholder="Instagram">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>LinkedIn</label>
                        <?php if($links!=null): ?>
                            <input type="text" class="form-control" rows="5" name="linkedIn" value="<?php echo e($links->linkedIn); ?>" id="linkedIn" placeholder="LinkedIn">
                        <?php else: ?>
                            <input type="text" class="form-control" rows="5" name="linkedIn" id="linkedIn" placeholder="LinkedIn">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Youtube</label>
                        <?php if($links!=null): ?>
                            <input type="text" class="form-control" rows="5" name="youtube" value="<?php echo e($links->youtube); ?>" id="youtube" placeholder="Youtube">
                        <?php else: ?>
                            <input type="text" class="form-control" rows="5" name="youtube" id="youtube" placeholder="Youtube">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Number</label>
                        <?php if($links!=null): ?>
                            <input type="text" class="form-control" rows="5" name="number" value="<?php echo e($links->number); ?>" id="number" placeholder="Number">
                        <?php else: ?>
                            <input type="text" class="form-control" rows="5" name="number" id="number" placeholder="Number">
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <?php if($links!=null): ?>
                            <textarea class="form-control" row="3" name="address"><?php echo e($links->address); ?></textarea>
                        <?php else: ?>
                            <textarea class="form-control" row="3" name="address"></textarea>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Address Map Link</label>
                        <?php if($links!=null): ?>
                            <textarea  class="form-control" rows="10" name="map_link"><?php echo e($links->map_link); ?></textarea>
                        <?php else: ?>
                            <textarea  class="form-control" rows="10" name="map_link"></textarea>
                        <?php endif; ?>
                    </div>
                    <div class="table-responsive">

                        <?php if($links!=null): ?>
                            <button type="submit" class="btn btn-info">Update</button>
                        <?php else: ?>
                            <button type="submit" class="btn btn-info">Submit</button>
                        <?php endif; ?>
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

<?php /**PATH C:\laragon\www\Madobi_jewelers\resources\views/admin/general/general-pages/website_links.blade.php ENDPATH**/ ?>