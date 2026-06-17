
<div class="row">
    <div class="col-lg-12">
        <div class="card">

            <?php if(session('message')): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo e(session('message')); ?>

                </div>
            <?php endif; ?>
            <div class="card-body">
                <form class="form-horizontal" action="<?php echo e(route('store.footer')); ?>" enctype="multipart/form-data" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if($footer!=null): ?>
                        <input type="hidden" name="id" value="<?php echo e($footer->id); ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label>Footer Details</label>
                        <?php if($footer!=null): ?>
                            <textarea  class="form-control" rows="10" name="details"><?php echo e($footer->details); ?></textarea>
                        <?php else: ?>
                            <textarea  class="form-control" rows="10" name="details"></textarea>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label>Credit Details</label>
                        <?php if($footer!=null): ?>
                            <textarea class="form-control" row="3" name="credit"><?php echo e($footer->credit); ?></textarea>
                        <?php else: ?>
                            <textarea class="form-control" row="3" name="credit"></textarea>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">

                        <?php if($footer!=null): ?>
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

<?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/general/general-pages/footer.blade.php ENDPATH**/ ?>