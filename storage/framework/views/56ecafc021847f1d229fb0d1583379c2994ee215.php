<?php $__env->startSection('title'); ?>
কারিগর প্রোডাক্ট
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
                        <h3>কারিগর প্রোডাক্ট</h3>
                    </div>
                </div>
                <div class="card-body">
                    <fieldset>
                        <div class="form-group mb-3">
                            <input type="radio" id="old_customer" name="customer_type" checked value="old_customer" />
                            <label class="form-control" for="old_customer">পুরাতন কারিগর</label>
                    
                            <input type="radio" id="new_customer" name="customer_type" value="new_customer" />
                            <label class="form-control" for="new_customer">নতুন কারিগর</label>
                        </div>
                    </fieldset>
                    <div id="customer" class="d-none">
                        <?php echo $__env->make('admin.user.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <form id="form2" class="form-horizontal" action="<?php echo e(route('karigor.product.store')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="status" value="on-process">
                        <div class="row">
                            <div class="form-group mb-3 custom col-12">
                                <label for="user_id" class="form-label mb-2">কারিগর নির্বাচন করুন</label>
                                <select id="user_id" name="user_id"
                                    class="form-select select2 old
                                <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    is-invalid
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="">_ _</option>
                                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <option value="<?php echo e($user->id); ?>" data-image="<?php echo e(asset('user/' . $user->image)); ?>">
                                        <?php echo e($user->name); ?> - <?php echo e($user->phone); ?> (<?php echo e($user->role->role_name); ?>)
                                    </option>
                                        
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <?php endif; ?>
                                </select>
                                <?php $__errorArgs = ['user_id'];
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
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label mb-2">ক্যাটেগরি নির্বাচন করুন</label>
                                    <select id="category_id" name="category_id"
                                        class="form-select select2
                                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        is-invalid
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option selected value="">এখানে নির্বাচন করুন</option>
                                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->category_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <?php endif; ?>
                                    </select>
                                    <?php $__errorArgs = ['category_id'];
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
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="product_id" class="form-label mb-2">প্রোডাক্ট নির্বাচন করুন </label>
                                    <select id="product_id" name="product_id"
                                        class="form-control select2" disabled>
                                        <option selected value="">এখানে নির্বাচন করুন</option>
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="col-lg-2">
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
unset($__errorArgs, $__bag); ?>" rows="5"  name="bhori" value="<?php echo e(old('bhori')); ?>" id="vori">
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
                            <div class="col-lg-2">
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
unset($__errorArgs, $__bag); ?>" rows="5" id="ana" name="ana" value="<?php echo e(old('ana')); ?>">
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
unset($__errorArgs, $__bag); ?>" rows="5" id="roti" name="roti" value="<?php echo e(old('roti')); ?>">
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
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="point" class="form-label mb-2">পয়েন্ট</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['point'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    is-invalid
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" id="point" name="point" value="<?php echo e(old('point')); ?>">
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
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="gram" class="form-label mb-2">গ্রাম হিসাব</label>
                                    <input type="number" class="form-control <?php $__errorArgs = ['gram'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    is-invalid
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" min="1" id="gram" readonly name="gram" value="<?php echo e(old('gram')); ?>">
                                    <?php $__errorArgs = ['gram'];
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
                            <div class="col-lg-6">
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
                                        <?php $__empty_1 = true; $__currentLoopData = $converted_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <option value="<?php echo e($category->id); ?>"><?php echo e($category->category_name); ?></option>
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
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="converted_product_id" class="form-label mb-2">রূপান্তরিত প্রোডাক্ট*</label>
                                    <select id="converted_product_id" name="converted_product_id" required
                                        class="form-control select2" disabled>
                                        <option value="">এখানে নির্বাচন করুন</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3 col-md-3">
                                <label class="form-label" for="details">ডিটেইলস</label>
                                <div class="input-group">
                                    <textarea name="details" class="form-control" id="details" cols="100" rows="5" placeholder="ডিটেইলস লিখুন..."></textarea>
                                </div>
                            </div>

                            <div class="form-group mb-3 col-3">
                                <label for="order_date" class="form-label mb-2">প্রদানের তারিখ</label>
                                <input type="date" class="form-control" name="order_date" id="order_date">
                            </div>

                            <div class="form-group mb-3 col-3">
                                <label for="receive_date" class="form-label mb-2">গ্রহণের তারিখ</label>
                                <input type="date" class="form-control" name="receive_date" id="receive_date">
                            </div>
                        </div>
                        <div class="table-responsive">
                            <button id="form_sub" type="submit" class="btn btn-info">Submit</button>
                        </div>
                    </form>
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
                        <th>একশন</th>
                        <th>স্ট্যাটাস</th>
                        <th>কারিগর</th>
                        <th>ক্যাটাগরি</th>
                        <th>প্রোডাক্ট</th>
                        <th>প্রদিত পরিমাণ</th>
                        <th>রূপান্তরিত ক্যাটাগরি</th>
                        <th>রূপান্তরিত প্রোডাক্ট</th>
                        <th>প্রদানের তারিখ</th>
                        <th>গ্রহণের তারিখ</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        <?php $sl = 1; ?>
                        <?php $__empty_1 = true; $__currentLoopData = $karigor_products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td>
                                <strong><?php echo e($sl); ?></strong>
                            </td>
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    <div>
                                        <form action="#"
                                            method="POST">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="kp_id" value="<?php echo e($kp->id); ?>">
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
                                        <a href="<?php echo e(route('karigor.product.edit', $kp->id)); ?>"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div style="margin-left: 2px;">
                                        <form class="form-horizontal" action="<?php echo e(route('karigor.product.status.update')); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                                <input type="hidden" name="kp_id" value="<?php echo e($kp->id); ?>">
                                                <div class="input-group">
                                                    <div class="input-group-append" style="cursor: pointer">
                                                        <?php if($kp->status=="on-process"): ?>
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
                                <?php if($kp->status=='on-process'): ?>
                                    <span class="badge bg-danger"><?php echo e($kp->status); ?></span>
                                <?php else: ?>
                                    <span class="badge bg-success"><?php echo e($kp->status); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><img alt="avatar"
                                src="<?php echo e(asset('user/' . $kp->user->image)); ?>"
                                class="rounded-circle" style="width:30px; height: 30px"> <?php echo e($kp->user->name); ?>-<a href="#"><?php echo e($kp->user->phone); ?></a>
                            </td>                            
                            <td><?php echo e($kp->productCategory->category_name ?? 'N/A'); ?></td>
                            <td><?php echo e($kp->product->product_name ?? "N/A"); ?></td>
                            <td><?php echo e($kp->bhori ?? 0); ?> ভরি, <?php echo e($kp->ana ?? 0); ?> আনা, <?php echo e($kp->roti ?? 0); ?> রতি , <?php echo e($kp->point ?? 0); ?> পয়েন্ট</td>
                            <td><?php echo e($kp->ConvertedproductCategory->category_name ?? "N/A"); ?></td>
                            <td><?php echo e($kp->Convertedproduct->product_name ?? "N/A"); ?></td>
                            <td><?php echo e($kp->order_date); ?></td>
                            <td><?php echo e($kp->receive_date); ?></td>
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
<?php $__env->startPush('admin_script'); ?>
<?php echo $__env->make('admin.common.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>

<script>
   $(document).ready(function() {
        const getProducts = (converted_category_id, selected = null) => {
            axios.get(`${window.location.origin}/get-products/${converted_category_id}`).then(res => {
                let products = res.data
                let element = $('#converted_product_id')
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

        $('#converted_category_id').on('change', function() {
            getProducts($(this).val())
        })
    });
</script>



<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\Madobi_jewelers\resources\views/admin/karigor/k_product.blade.php ENDPATH**/ ?>