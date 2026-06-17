<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="<?php echo e(asset('admin/assets/node_modules/jquery/dist/jquery.min.js')); ?>"></script>

<!-- Bootstrap Core JavaScript -->
<script src="<?php echo e(asset('admin/assets/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js')); ?>"></script>

<!-- Bootstrap-select JavaScript -->
<script src="<?php echo e(asset('admin/assets/node_modules/bootstrap-select/bootstrap-select.min.js')); ?>"></script>

<!-- Slimscrollbar scrollbar JavaScript -->
<script src="<?php echo e(asset('admin/dist/js/perfect-scrollbar.jquery.min.js')); ?>"></script>

<!-- Wave Effects -->
<script src="<?php echo e(asset('admin/dist/js/waves.js')); ?>"></script>

<!-- Menu sidebar -->
<script src="<?php echo e(asset('admin/dist/js/sidebarmenu.js')); ?>"></script>

<!-- Custom JavaScript -->
<script src="<?php echo e(asset('admin/dist/js/custom.min.js')); ?>"></script>

<!-- Morris JavaScript -->
<script src="<?php echo e(asset('admin/assets/node_modules/raphael/raphael-min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/node_modules/morrisjs/morris.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/node_modules/jquery-sparkline/jquery.sparkline.min.js')); ?>"></script>

<!-- Chart JS -->
<script src="<?php echo e(asset('admin/dist/js/dashboard1.js')); ?>"></script>

<!-- Dropify (Image Upload) -->
<script src="<?php echo e(asset('admin/assets/node_modules/dropify/dist/js/dropify.min.js')); ?>"></script>
<script>
    $(document).ready(function() {
        // Initialize Dropify
        $('.dropify').dropify();

        // Translated messages for Dropify
        $('.dropify-fr').dropify({
            messages: {
                default: 'Glissez-déposez un fichier ici ou cliquez',
                replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                remove: 'Supprimer',
                error: 'Désolé, le fichier trop volumineux'
            }
        });

        // Event listeners for Dropify
        var drEvent = $('#input-file-events').dropify();

        drEvent.on('dropify.beforeClear', function(event, element) {
            return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
        });

        drEvent.on('dropify.afterClear', function(event, element) {
            alert('File deleted');
        });

        drEvent.on('dropify.errors', function(event, element) {
            console.log('Has Errors');
        });

        var drDestroy = $('#input-file-to-destroy').dropify();
        drDestroy = drDestroy.data('dropify')
        $('#toggleDropify').on('click', function(e) {
            e.preventDefault();
            if (drDestroy.isDropified()) {
                drDestroy.destroy();
            } else {
                drDestroy.init();
            }
        });
    });
</script>

<!-- DataTables -->
<script src="<?php echo e(asset('admin/assets/node_modules/datatables.net/js/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/node_modules/datatables.net-bs4/js/dataTables.responsive.min.js')); ?>"></script>
<script>
    $(function () {
        // Initialize DataTables
        $('#myTable').DataTable();

        var table = $('#example').DataTable({
            "columnDefs": [{
                "visible": false,
                "targets": 2
            }],
            "order": [
                [2, 'asc']
            ],
            "displayLength": 25,
            "drawCallback": function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;
                api.column(2, { page: 'current' }).data().each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before('<tr class="group"><td colspan="5">' + group + '</td></tr>');
                        last = group;
                    }
                });
            }
        });

        // Order by the grouping
        $('#example tbody').on('click', 'tr.group', function () {
            var currentOrder = table.order()[0];
            if (currentOrder[0] === 2 && currentOrder[1] === 'asc') {
                table.order([2, 'desc']).draw();
            } else {
                table.order([2, 'asc']).draw();
            }
        });

        // Responsive table
        $('#config-table').DataTable({ responsive: true });
        $('#example23').DataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        });

        // Style DataTable buttons
        $('.buttons-copy, .buttons-csv, .buttons-print, .buttons-pdf, .buttons-excel').addClass('btn btn-primary me-1');
    });
</script>

<!-- Multiselect and Switchery -->
<script src="<?php echo e(asset('admin/assets/node_modules/switchery/dist/switchery.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/node_modules/select2/dist/js/select2.full.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/node_modules/dff/dff.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/node_modules/multiselect/js/jquery.multi-select.js')); ?>"></script>
<script>
    $(function () {
        // Initialize Switchery
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());
        });

        // Initialize Select2
        $(".select2").select2();
        $('.selectpicker').selectpicker();

        // Initialize Bootstrap TouchSpin
        $(".vertical-spin").TouchSpin({
            verticalbuttons: true
        });

        $("input[name='tch1']").TouchSpin({
            min: 0,
            max: 100,
            step: 0.1,
            decimals: 2,
            boostat: 5,
            maxboostedstep: 10,
            postfix: '%'
        });

        $("input[name='tch2']").TouchSpin({
            min: -1000000000,
            max: 1000000000,
            stepinterval: 50,
            maxboostedstep: 10000000,
            prefix: '$'
        });

        $("input[name='tch3']").TouchSpin();
        $("input[name='tch3_22']").TouchSpin({ initval: 40 });
        $("input[name='tch5']").TouchSpin({ prefix: "pre", postfix: "post" });

        // Initialize Multiselect
        $('#pre-selected-options').multiSelect();
        $('#optgroup').multiSelect({ selectableOptgroup: true });
        $('#public-methods').multiSelect();
        $('#select-all').click(function () {
            $('#public-methods').multiSelect('select_all');
            return false;
        });
        $('#deselect-all').click(function () {
            $('#public-methods').multiSelect('deselect_all');
            return false;
        });
        $('#refresh').on('click', function () {
            $('#public-methods').multiSelect('refresh');
            return false;
        });
        $('#add-option').on('click', function () {
            $('#public-methods').multiSelect('addOption', {
                value: 42,
                text: 'test 42',
                index: 0
            });
            return false;
        });

        // Initialize Select2 with AJAX
        $(".ajax").select2({
            ajax: {
                url: "https://api.github.com/search/repositories",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term, page: params.page };
                },
                processResults: function (data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.items,
                        pagination: { more: (params.page * 30) < data.total_count }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; },
            minimumInputLength: 1
        });
    });
</script>

<!-- Summernote (Text Editor) -->
<script src="<?php echo e(asset('admin/assets/node_modules/summernote/dist/summernote-bs4.min.js')); ?>"></script>
<script>
    $(function() {
        $('.summernote').summernote({
            height: 350,
            minHeight: null,
            maxHeight: null,
            focus: false
        });

        $('.inline-editor').summernote({
            airMode: true
        });
    });

    window.edit = function() {
        $(".click2edit").summernote();
    };

    window.save = function() {
        $(".click2edit").summernote('destroy');
    };
</script>

<?php echo $__env->yieldPushContent('admin_script'); ?>
<?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/include/script.blade.php ENDPATH**/ ?>