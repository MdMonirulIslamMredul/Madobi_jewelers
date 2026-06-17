<?php $footer = \App\Models\FooterDetail::latest()->first() ?>
<footer class="footer">
    © <?php echo e(date('Y-m-d')); ?> <?php echo e($footer->credit?? 'Designed by Techweb BD IT'); ?>

</footer>
<?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/include/footer.blade.php ENDPATH**/ ?>