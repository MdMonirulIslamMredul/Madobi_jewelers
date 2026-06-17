<?php $__env->startSection('title'); ?>
কারিগর প্রোডাক্ট ইডিট 
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
                        <h3>কারিগর প্রোডাক্ট ইডিট</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="<?php echo e(route('karigor.product.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="status" value="<?php echo e($karigor_product->status?? 'on-process'); ?>">
                        <input type="hidden" name="id" value="<?php echo e($karigor_product->id); ?>">
                        <div class="row">
                            <fieldset>
                                <div class="form-group mb-3">
                                    <input type="radio" id="old_karigor" name="karigor_type" checked value="old_karigor" />
                                    <label class="form-control" for="old_karigor">পুরাতন কারিগর</label>

                                    <input type="radio" id="new_karigor" name="karigor_type" value="new_karigor" />
                                    <label class="form-control" for="new_karigor">নতুন কারিগর</label>
                                </div>
                            </fieldset>
                            <div class="form-group mb-3 col-12 custom">
                                <label for="name" class="form-label mb-2">নাম</label>
                                <select class="form-control select2" name="karigor_id" required id="karigor_id">
                                    <option value="">_এখানে নির্বাচন করুন_</option>
                                    <?php $__currentLoopData = $karigors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php if($c->id == $karigor_product->karigor_id): ?>
                                            selected
                                        <?php endif; ?> value="<?php echo e($c->id); ?>"><?php echo e($c->name); ?> - <?php echo e($c->phone); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div id="karigor">
                                
                            </div>

                            <div class="form-group mb-3 col-6">
                                <label for="category" class="form-label mb-2">ক্যাটেগরি নির্বাচন করুন*</label>
                                <select class="form-control select2" name="category" id="category" required>
                                    <option value="">_এখানে নির্বাচন করুন_</option>
                                    <?php $__currentLoopData = $warehouse_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $warehouse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option <?php if($warehouse->id == $karigor_product->category_id): ?>
                                            selected
                                        <?php endif; ?> value="<?php echo e($warehouse->id); ?>"><?php echo e($warehouse->category_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="form-group mb-3 col-6">
                                <label for="product" class="form-label mb-2">প্রোডাক্ট নির্বাচন করুন*</label>
                                <select id="product" name="product"
                                        class="form-control select2" required>
                                        <option value="">এখানে নির্বাচন করুন</option>
                                    </select>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="karat" class="form-label mb-2">ক্যারাট</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['karat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    is-invalid
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" name="karat" value="<?php echo e($karigor_product->karat); ?>" id="karat">
                                    <?php $__errorArgs = ['karat'];
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
                            
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="bhori" class="form-label mb-2">ভরি</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['bhori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    is-invalid
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" id="bhori" name="bhori" value="<?php echo e($karigor_product->bhori); ?>" id="bhori">
                                    <?php $__errorArgs = ['bhori'];
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
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="ana" class="form-label mb-2">আনা</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['ana'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    is-invalid
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" id="ana" name="ana" value="<?php echo e($karigor_product->ana); ?>" id="ana">
                                    <?php $__errorArgs = ['ana'];
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
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="roti" class="form-label mb-2">রতি</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['roti'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    is-invalid
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" id="roti" name="roti" value="<?php echo e($karigor_product->roti); ?>" id="roti">
                                    <?php $__errorArgs = ['roti'];
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

                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label for="converted_category_id" class="form-label mb-2">রূপান্তরিত প্রোডাক্টের ক্যাটেগরি*</label>
                                    <select id="converted_category_id" name="converted_category_id" required
                                        class="form-select select2
                                    <?php $__errorArgs = ['converted_category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        is-invalid
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option selected>এখানে নির্বাচন করুন</option>
                                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option <?php if($category->id==$karigor_product->converted_category_id): ?>
                                                selected
                                            <?php endif; ?> value="<?php echo e($category->id); ?>"><?php echo e($category->category_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php $__errorArgs = ['converted_category_id'];
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
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label for="converted_product_id" class="form-label mb-2">রূপান্তরিত প্রোডাক্ট*</label>
                                    <select id="converted_product_id" name="converted_product_id" required
                                        class="form-control select2">
                                        <option value="">এখানে নির্বাচন করুন</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label for="qty" class="form-label mb-2">রূপান্তরিত প্রোডাক্টের কোয়ান্টিটি*</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['qty'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> required
                                    is-invalid
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" min="1" id="qty" name="qty" value="<?php echo e($karigor_product->qty); ?>" id="qty">
                                    <?php $__errorArgs = ['qty'];
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

                            <div class="form-group mb-3 col-3">
                                <label for="order_date" class="form-label mb-2">প্রদানের তারিখ</label>
                                <input type="date" class="form-control" name="order_date" id="order_date" value="<?php echo e($karigor_product->order_date); ?>">
                            </div>

                            <div class="form-group mb-3 col-3">
                                <label for="receive_date" class="form-label mb-2">গ্রহণের তারিখ</label>
                                <input type="date" class="form-control" name="receive_date" id="receive_date" value="<?php echo e($karigor_product->receive_date); ?>">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('input[type="radio"]').click(function() {
                var inputValue = $(this).attr("value");

                if (inputValue == "new_karigor") {
                    $("#karigor_id").removeAttr('required');
                    $(".custom").addClass('d-none');

                    var karigor = `<div class="row">
                            <div class="form-group mb-3 col-6">
                                <label for="name" class="form-label mb-2">কারিগরের নাম*</label>
                                <input type="text" class="form-control" required name="name" value="<?php echo e(old('name')); ?>" id="name">
                            </div>
                            <div class="form-group mb-3 col-6">
                                <label for="phone" class="form-label mb-2">মোবাইল*</label>
                                <input type="text" class="form-control" required name="phone" value="<?php echo e(old('phone')); ?>" id="phone">
                            </div>
                            <div class="form-group mb-3 col-6">
                                <label for="address" class="form-label mb-2">ঠিকানা</label>
                                <input type="text" class="form-control" name="address" value="<?php echo e(old('address')); ?>" id="address">
                            </div>
                            <div class="form-group mb-3 col-6">
                                <label for="details" class="form-label mb-2">ডিটেইলস</label>
                                <input type="text" class="form-control" name="details" value="<?php echo e(old('details')); ?>" id="details">
                            </div>
                        </div>`;
                    $("#karigor").html(karigor);
                } else {
                    $(".custom").removeClass('d-none');
                    $("#karigor").html("");
                }
            });

            // Load selected product for category
            getProducts("<?php echo e($karigor_product->category_id); ?>", "<?php echo e($karigor_product->product_id); ?>", 'product');
            getProducts("<?php echo e($karigor_product->converted_category_id); ?>", "<?php echo e($karigor_product->converted_product_id); ?>", 'converted_product_id');

            function getProducts(categoryId, selectedProductId, elementId) {
                axios.get(`${window.location.origin}/get-products/${categoryId}`).then(res => {
                    let products = res.data;
                    let element = $(`#${elementId}`);
                    element.removeAttr('disabled');
                    element.empty();
                    element.append(`<option>এখানে নির্বাচন করুন</option>`);
                    products.map((product) => {
                        element.append(
                            `<option value="${product.id}" ${selectedProductId == product.id ? 'selected' : ''}>${product.product_name}</option>`
                        );
                    });
                });
            }

            $('#category').on('change', function() {
                getProducts($(this).val(), null, 'product');
            });

            $('#converted_category_id').on('change', function() {
                getProducts($(this).val(), null, 'converted_product_id');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('admin_script'); ?>
<?php echo $__env->make('admin.common.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/karigor/karigorProductEdit.blade.php ENDPATH**/ ?>