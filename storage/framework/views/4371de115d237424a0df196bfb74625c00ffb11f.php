<?php $__env->startSection('title'); ?>
রিপেয়ার টেবিল
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
                        <h3>রিপেয়ার টেবিল</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>একশন্স</th>
                            <th>স্ট্যাটাস</th>
                            <th>অর্ডারের তারিখ</th>
                            <th>কাস্টমার</th>
                            <th>ক্যাটেগরি</th>
                            <th>প্রোডাক্ট</th>
                            <th>পরিমাণ</th>
                            <th>সর্বমোট মূল্য</th>
                            <th>মোট প্রদান</th>
                            <th>বকেয়া</th>
                            
                           
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sl = 1; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $repairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($sl); ?></strong>
                            </td>
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    <div>
                                        <form action=""
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-warning btn_custom show_confirm" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Delete">
                                                <i class="fa-solid fa-trash-can fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div>
                                        <a href=""
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="<?php echo e(route('repair.manage.edit', $repair->id)); ?>"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                   
                                    <div style="margin-left: 2px;">
                                        <form class="form-horizontal" action="<?php echo e(route('karigor.product.status.update')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                                <input type="hidden" name="repair_id" value="<?php echo e($repair->id); ?>">
                                                <div class="input-group">
                                                    <div class="input-group-append" style="cursor: pointer">
                                                        <?php if($repair->status=="on-process"): ?>
                                                        <input type="hidden" name="status" value="received">
                                                        <button type="submit" class="btn btn-sm btn-outline-success show_update" data-toggle="tooltip"
                                                        data-placement="top" data-bs-original-title="Change Status">
                                                        ✔</button> 

                                                        <?php else: ?>
                                                        <input type="hidden" name="status" value="on-process">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger show_update" data-toggle="tooltip"
                                                        data-placement="top" data-bs-original-title="Change Status">
                                                        ✗</button> 
                                                        
                                                        <?php endif; ?>                                      
                                                     </div>
                                                </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if($repair->status=='on-process'): ?>
                                    <span class="badge bg-danger"><?php echo e($repair->status); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?php echo e($repair->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($repair->order_date); ?></td>
                            <td>
                                <img alt="avatar"
                                    src="<?php echo e(asset('user/' . $repair->user->image)); ?>"
                                    class="rounded-circle" style="width:30px; height: 30px">

                                <?php echo e($repair->user->name); ?>-<?php echo e($repair->user->phone); ?>

                            </td>
                            <td><?php echo e($repair->productCategory->category_name ?? 'N/A'); ?></td>
                            <td><?php echo e($repair->product->product_name ?? 'N/A'); ?></td>
                            <td><?php echo e($repair->bhori ?? 0); ?> ভরি, <?php echo e($repair->ana ?? 0); ?> আনা, <?php echo e($repair->roti ?? 0); ?> রতি,  <?php echo e($repair->point ?? 0); ?> পয়েন্ট </td>
                            <td><?php echo e($repair->total); ?></td>
                            <td><?php echo e($repair->paid); ?></td>
                            <td><?php echo e($repair->due); ?></td>
                        </tr>
                        <?php $sl++ ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        No Data Found!
                        <?php endif; ?>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_script'); ?>
<?php echo $__env->make('admin.common.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js"
        integrity="sha512-QTnb9BQkG4fBYIt9JGvYmxPpd6TBeKp6lsUrtiVQsrJ9sb33Bn9s0wMQO9qVBFbPX3xHRAsBHvXlcsrnJjExjg=="
        crossorigin="anonymous" referrerpolicy="no-referrer">
</script>
<script>
    // For Create
        // To Get Product Data
        const getProducts = (category_id, selected = null) => {
            axios.get(`${window.location.origin}/get-products/${category_id}`).then(res => {
                let products = res.data
                let element = $('#product_id')
                element.removeAttr('disabled')
                element.empty()
                element.append(`<option>এখানে নির্বাচন করুন</option>`)
                products.map((product, index) => {
                    // console.log(product)
                    element.append(
                        `<option value="${product.id}" ${selected == product.id ?'selected' : ''}>${product.product_name}</option>`
                    )
                })
            })
        }

        $('#category_id').on('change', function() {
            getProducts($(this).val())
        })
</script>
<script>
    //calculate sub total price
    $(document).ready(function() {
        $('#total, #paid').on('input', function() {
            var total_price = $('#total').val();
            var adv_payment = $('#paid').val();

            $.ajax({
                url: "<?php echo e(route('calculate.total')); ?>",
                type: "POST",
                data: {
                    total_price: total_price,
                    adv_payment: adv_payment,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {
                    $('#due').val(response.due_payment.toFixed(3));
                }
            });
        });
    });

</script>
<script>
    function preview(input) {
        var preview = document.getElementById('previewIm');
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


<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/repair/manage.blade.php ENDPATH**/ ?>