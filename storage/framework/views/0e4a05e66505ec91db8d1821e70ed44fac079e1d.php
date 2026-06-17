<?php $__env->startSection('title'); ?>
দোকান ক্রয় তালিকা
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_style'); ?>
<?php echo $__env->make('admin.common.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('body'); ?>
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>দোকান তালিকা</h3>
                </div>
            </div>
            <div class="card-body">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">অর্ডারের তারিখ</th>
                            <th class="text-center">সাপ্লায়ার</th>
                            <th class="text-center">ক্যাটেগরি</th>
                            <th class="text-center">প্রোডাক্ট</th>
                            <th class="text-center">পরিমাণ</th>
                            <th class="text-center">সর্বমোট মূল্য</th>
                            <th class="text-center">মোট প্রদান</th>
                            <th class="text-center">বকেয়া</th>
                            <th class="text-center">একশন্স</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $sl = 1; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center">
                                <strong><?php echo e($sl); ?></strong>
                            </td>
                            <td class="text-center"><?php echo e($transaction->updated_at->format('d M Y')); ?></td>
                            <td class="text-center">
                                <div class="d-flex justify-content-start">
                                    <div>
                                        <img alt="avatar" src="<?php echo e(asset('user/' . $transaction->user->image)); ?>" class="rounded-circle" style="width:40px; height: 40px">
                                    </div>
                                    <div>
                                        <?php echo e($transaction->user->name); ?><br><a href="#"><?php echo e($transaction->user->phone); ?></a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center"><?php echo e($transaction->productCategory->category_name); ?></td>
                            <td class="text-center"><?php echo e($transaction->product->product_name); ?></td>
                            <td class="text-center">
                                <?php $__currentLoopData = $transaction->purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-info"><?php echo e($purchase->bhori ?? 0); ?> ভরি, <?php echo e($purchase->ana ?? 0); ?> আনা, <?php echo e($purchase->roti ?? 0); ?> রতি,  <?php echo e($purchase->point ?? 0); ?> পয়েন্ট</span> <span class="badge bg-danger">(<?php echo e($purchase->gram ?? 0); ?> গ্রাম )</span><br>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </td>
                            <td class="text-center"><?php echo e($transaction->total_price); ?></td>
                            <td class="text-center"><?php echo e($transaction->total_payment); ?></td>
                            <td class="text-center"><?php echo e($transaction->due_payment); ?></td>
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    <div>
                                        <a href=""
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="<?php echo e(route('purchase.edit', $transaction->id)); ?>"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
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
                                </div>
                            </td>
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
    //calculate total price
    $(document).ready(function() {
        $('#unit_price, #qtr, #bhori, #ana, #roti').on('input', function() {
            var unit_price = $('#unit_price').val();
            var qtr = $('#qtr').val();
            var bhori = $('#bhori').val();
            var ana = $('#ana').val();
            var roti = $('#roti').val();

            $.ajax({
                url: "<?php echo e(route('calculate')); ?>",
                type: "POST",
                data: {
                    unit_price: unit_price,
                    qtr: qtr,
                    bhori: bhori,
                    ana: ana,
                    roti: roti,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {
                    $('#total_price').val(response.total_price);
                }
            });
        });
    });

    //calculate sub total price
    $(document).ready(function() {
        $('#total_price, #adv_payment').on('input', function() {
            var total_price = $('#total_price').val();
            var adv_payment = $('#adv_payment').val();

            $.ajax({
                url: "<?php echo e(route('calculate.total')); ?>",
                type: "POST",
                data: {
                    total_price: total_price,
                    adv_payment: adv_payment,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {
                    $('#due_payment').val(response.due_payment);
                    $('#total_payment').val(response.total_payment);
                }
            });
        });
    });

</script>
<script>
    document.getElementById('karigorForm').addEventListener('submit', function(event) {
        const locationRadios = document.getElementsByName('location');
        let locationSelected = false;
        for (const radio of locationRadios) {
            if (radio.checked) {
                locationSelected = true;
                break;
            }
        }

        if (!locationSelected) {
            event.preventDefault();
            Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Please select (দোকান or গুদাম).",
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/purchase/shop.blade.php ENDPATH**/ ?>