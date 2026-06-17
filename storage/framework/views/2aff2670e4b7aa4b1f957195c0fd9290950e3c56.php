<?php $__env->startSection('title'); ?>
    ক্রয় সংশোধন
<?php $__env->stopSection(); ?>
<?php $__env->startPush('admin_style'); ?>
<?php echo $__env->make('admin.common.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('body'); ?>
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between mt-2">
                    <h3>ক্রয় সংশোধন</h3>
                </div>
            </div>
            <div class="card-body">
                <fieldset>
                    <div class="form-group mb-3">
                        <input type="radio" id="old_customer" name="customer_type" checked value="old_customer" />
                        <label class="form-control" for="old_customer">পুরাতন সাপ্লায়ার</label>
                
                        <input type="radio" id="new_customer" name="customer_type" value="new_customer" />
                        <label class="form-control" for="new_customer">নতুন সাপ্লায়ার</label>
                    </div>
                </fieldset>
                <div id="customer" class="d-none">
                    <?php echo $__env->make('admin.user.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <form id="form2" class="form-horizontal" action="<?php echo e(route('purchase.update', $transaction->id)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <input type="hidden" name="transaction_id" value="<?php echo e($transaction->id); ?>">
                    <div class="row">
                        <div class="form-group mb-3 custom col-12">
                            <label for="user_id" class="form-label mb-2">সাপ্লায়ার নির্বাচন করুন</label>
                            <select id="user_id" name="user_id"
                                class="form-select select2 old
                                <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">_ _</option>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($user->id); ?>" data-image="<?php echo e(asset('user/' . $user->image)); ?>" 
                                        <?php echo e($transaction->user_id == $user->id ? 'selected' : ''); ?>>
                                        <?php echo e($user->name); ?> - <?php echo e($user->phone); ?> (<?php echo e($user->role->role_name); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <div class="col-lg-5">
                            <div class="form-group mb-3">
                                <label for="category_id" class="form-label mb-2">ক্যাটেগরি নির্বাচন করুন</label>
                                <select id="category_id" name="category_id"
                                    class="form-select select2 <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option selected value="">এখানে নির্বাচন করুন</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" 
                                            <?php echo e($transaction->productCategory->id == $category->id ? 'selected' : ''); ?>>
                                            <?php echo e($category->category_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <div class="col-lg-5">
                            <div class="form-group mb-3">
                                <label for="product_id" class="form-label mb-2">প্রোডাক্ট নির্বাচন করুন </label>
                                <select id="product_id" name="product_id"
                                    class="form-control select2 <?php $__errorArgs = ['product_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option selected value="">এখানে নির্বাচন করুন</option>
                                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($product->id); ?>" 
                                            <?php echo e($transaction->product_id == $product->id ? 'selected' : ''); ?>>
                                            <?php echo e($product->product_name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <?php $__errorArgs = ['product_id'];
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
                                <label for="qtr" class="form-label mb-2">কোয়ান্টিটি</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['qtr'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" min="1" id="qtr" name="qtr" value="<?php echo e(old('qtr', count($transaction->purchases))); ?>">
                                <?php $__errorArgs = ['qtr'];
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
                        <div id="inputContainer">
                            <?php $__currentLoopData = $transaction->purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $purchase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card mb-3" style="background-color: #c2bebe">
                                <div class="card-header">
                                    <h5 class="card-title">QTY <span class="card-number"><?php echo e($index + 1); ?></span></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="bhori" class="form-label mb-2">ভরি</label>
                                                <input type="number" class="form-control vori-input" rows="5" name="bhori[]" value="<?php echo e(old('bhori.' . $index, $purchase->bhori)); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="ana" class="form-label mb-2">আনা</label>
                                                <input type="number" class="form-control ana-input" rows="5" name="ana[]" value="<?php echo e(old('ana.' . $index, $purchase->ana)); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="roti" class="form-label mb-2">রতি</label>
                                                <input type="number" class="form-control roti-input" rows="5" name="roti[]" value="<?php echo e(old('roti.' . $index, $purchase->roti)); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="point" class="form-label mb-2">পয়েন্ট</label>
                                                <input type="number" class="form-control point-input" rows="5" name="point[]" value="<?php echo e(old('point.' . $index, $purchase->point)); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="karat" class="form-label mb-2">ক্যারাট</label>
                                                <input type="number" class="form-control" rows="5" name="karat[]" value="<?php echo e(old('karat.' . $index, $purchase->karat)); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="unit_price" class="form-label mb-2">একক মূল্য/ভরি</label>
                                                <input type="number" class="form-control unit_price_input" rows="5" name="unit_price[]" value="<?php echo e(old('unit_price.' . $index, $purchase->unit_price)); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="gram" class="form-label mb-2">গ্রাম হিসাব</label>
                                                <input type="number" id="gram" class="form-control" rows="5" min="1" name="gram[]" readonly value="<?php echo e(old('gram.' . $index, $purchase->gram)); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="price" class="form-label mb-2">মূল্য</label>
                                                <input type="number" class="form-control price-input" readonly rows="5" name="price[]" value="<?php echo e(old('total_price.' . $index, $purchase->total_price)); ?>" id="price">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <template id="inputTemplate">
                            <div class="card mb-3" style="background-color: #c2bebe">
                                <div class="card-header">
                                    <h5 class="card-title">QTY <span class="card-number"></span></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="bhori" class="form-label mb-2">ভরি</label>
                                                <input type="number" class="form-control vori-input" rows="5" name="bhori[]" id="vori">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="ana" class="form-label mb-2">আনা</label>
                                                <input type="number" class="form-control ana-input" rows="5" name="ana[]" id="ana">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="roti" class="form-label mb-2">রতি</label>
                                                <input type="number" class="form-control roti-input" rows="5" name="roti[]" id="roti">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="point" class="form-label mb-2">পয়েন্ট</label>
                                                <input type="number" class="form-control point-input" rows="5" name="point[]" id="point">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="karat" class="form-label mb-2">ক্যারাট</label>
                                                <input type="number" class="form-control" rows="5" name="karat[]" id="karat">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="unit_price" class="form-label mb-2">একক মূল্য/ভরি</label>
                                                <input type="number" class="form-control unit_price_input" rows="5" name="unit_price[]" id="unit_price">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="gram" class="form-label mb-2">গ্রাম হিসাব</label>
                                                <input type="number" class="form-control" rows="5" min="1" name="gram[]" readonly id="gram">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="price" class="form-label mb-2">মূল্য</label>
                                                <input type="number" class="form-control price-input" readonly rows="5" name="price[]" id="price">
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </template>
                     
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <label for="total_price" class="form-label mb-2">মোট মূল্য</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['total_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" id="total_price" name="total_price" value="<?php echo e(old('total_price',$transaction->total_price)); ?>">
                                <?php $__errorArgs = ['total_price'];
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
                                <label for="adv_payment" class="form-label mb-2">অগ্রিম প্রদান</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['adv_payment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" id="adv_payment" name="adv_payment" value="<?php echo e(old('adv_payment',$transaction->adv_payment)); ?>">
                                <?php $__errorArgs = ['adv_payment'];
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
                                <label for="due_payment" class="form-label mb-2">বকেয়া</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['due_payment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" id="due_payment" name="due_payment" value="<?php echo e(old('due_payment',$transaction->due_payment)); ?>">
                                <?php $__errorArgs = ['due_payment'];
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
                                <label for="total_payment" class="form-label mb-2">মোট প্রদান</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['total_payment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" id="total_payment" name="total_payment" value="<?php echo e(old('total_payment',$transaction->total_payment)); ?>">
                                <?php $__errorArgs = ['total_payment'];
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
                        <div class="card bg-secondary rounded col-lg-4">
                            <label class="bg-secondary border-none mb-3" for="location">দোকান/গুদাম নির্বাচন করুন:</label>
                            <div class="m-5">
                                <fieldset>
                                    <div>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <input type="radio" id="is_shop" name="location" value="is_shop" <?php if($transaction->purchases[0]->location == 'is_shop'): ?>
                                                checked  
                                                <?php endif; ?>/>
                                                <label class="form-control" for="is_shop">দোকান</label>
                                            </div>
                                            <div class="col-6">
                                                <input type="radio" id="is_warehouse" name="location" value="is_warehouse" <?php if($transaction->purchases[0]->location == 'is_warehouse'): ?>
                                                checked  
                                                <?php endif; ?>/>
                                                <label class="form-control" for="is_warehouse">গুদাম</label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label" for="details">ডিটেইলস</label>
                            <div class="input-group">
                                <textarea name="details" class="form-control" id="details" cols="150" rows="7" placeholder="ডিটেইলস লিখুন..."><?php echo e($transaction->details); ?></textarea>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4 text-center">
                            <label for="photo" class="form-label"><?php echo e(__('প্রোডাক্টের ছবি')); ?></label>    
                            <div class="d-block position-relative">
                                <img id="previewIm" src="<?php echo e(asset('user/purchase/'. $transaction->purchases[0]->photo )); ?>" alt="your image" width="350" height="180" onclick="document.getElementById('photoI').click();" style="cursor: pointer;">
                                <input type="file" id="photoI" name="photo" class="d-none" onchange="preview(this)">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="order_date" class="form-label mb-2">অর্ডারের তারিখ</label>
                                <input type="date" class="form-control <?php $__errorArgs = ['order_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" name="order_date" value="<?php echo e(old('order_date',$transaction->purchases[0]->order_date)); ?>" id="order_date">
                                <?php $__errorArgs = ['order_date'];
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
                                <label for="receive_date" class="form-label mb-2">অর্ডার গ্রহণের তারিখ</label>
                                <input type="date" class="form-control <?php $__errorArgs = ['receive_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" name="receive_date" value="<?php echo e(old('receive_date',$transaction->purchases[0]->receive_date)); ?>" id="receive_date">
                                <?php $__errorArgs = ['receive_date'];
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
                                <label for="due_payment_date" class="form-label mb-2">বকেয়া পরিশোধের তারিখ</label>
                                <input type="date" class="form-control <?php $__errorArgs = ['due_payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                is-invalid
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="5" name="due_payment_date" value="<?php echo e(old('due_payment_date',$transaction->purchases[0]->due_payment_date)); ?>" id="due_payment_date">
                                <?php $__errorArgs = ['due_payment_date'];
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

                        
                        <div class="table-responsive">
                            <button type="submit" id="form_sub" class="btn btn-info">সংরক্ষণ করুন</button>
                        </div>
                    </div>

                </form>
            </div>
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
                $('#due_payment').val(response.due_payment.toFixed(3));
                $('#total_payment').val(response.total_payment.toFixed(3));
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
<script>
    $(document).ready(function() {
        // Function to handle dynamic input fields based on quarter value
        $('#qtr').on('input', function() {
            var qtrValue = parseInt(this.value) || 0;
            var container = document.getElementById('inputContainer');
            var existingCards = container.querySelectorAll('.card');

            if (qtrValue < existingCards.length) {
                for (var i = existingCards.length - 1; i >= qtrValue; i--) {
                    existingCards[i].remove();
                }
            } else {
                for (var i = existingCards.length; i < qtrValue; i++) {
                    var template = document.getElementById('inputTemplate');
                    var clone = document.importNode(template.content, true);
                    clone.querySelector('.card-number').textContent = i + 1;
                    container.appendChild(clone);
                }
            }

            // Add event listeners to new input fields after modifying DOM
            addInputEventListeners();
        });

        // Function to add event listeners to input fields
        function addInputEventListeners() {
            var container = document.getElementById('inputContainer');
            var voriInputs = container.querySelectorAll('.vori-input');
            var anaInputs = container.querySelectorAll('.ana-input');
            var rotiInputs = container.querySelectorAll('.roti-input');
            var pointInputs = container.querySelectorAll('.point-input');
            var priceInputs = container.querySelectorAll('.unit_price_input');

            // Event listeners for converting and calculating totals
            priceInputs.forEach(input => {
                input.addEventListener('input', CalculateTotal);
            });
            voriInputs.forEach(input => {
                input.addEventListener('input', convertToGrams);
                input.addEventListener('input', CalculateTotal);
            });
            anaInputs.forEach(input => {
                input.addEventListener('input', convertToGrams);
                input.addEventListener('input', CalculateTotal);
            });
            rotiInputs.forEach(input => {
                input.addEventListener('input', convertToGrams);
                input.addEventListener('input', CalculateTotal);
            });
            pointInputs.forEach(input => {
                input.addEventListener('input', convertToGrams);
                input.addEventListener('input', CalculateTotal);
            });
        }

        // Conversion function
        function convertToGrams() {
            var card = this.closest('.card-body');
            var vori = card.querySelector('.vori-input').value || 0;
            var ana = card.querySelector('.ana-input').value || 0;
            var roti = card.querySelector('.roti-input').value || 0;
            var point = card.querySelector('.point-input').value || 0;

            $.ajax({
                url: '<?php echo e(route('convert.to.gram')); ?>',
                method: 'POST',
                data: {
                    vori: vori,
                    ana: ana,
                    roti: roti,
                    point: point,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {
                    var gramInput = card.querySelector('.form-control[id="gram"]');
                    if (gramInput) {
                        gramInput.value = response.grams.toFixed(3);
                        CalculateTotal.apply(card); // Ensure CalculateTotal is called in the context of the card
                    } else {
                        console.error('Gram input element not found.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    console.log('Status:', status);
                    console.log('Response:', xhr.responseText);
                }
            });
        }

        // Calculation function
        function CalculateTotal() {
            var card = this.closest('.card-body');;
            var unit_price = card.querySelector('.unit_price_input').value || 0;
            var vori = card.querySelector('.vori-input').value || 0;
            var ana = card.querySelector('.ana-input').value || 0;
            var roti = card.querySelector('.roti-input').value || 0;
            var point = card.querySelector('.point-input').value || 0;

            $.ajax({
                url: "<?php echo e(route('calculate')); ?>",
                type: "POST",
                data: {
                    unit_price: unit_price,
                    bhori: vori,
                    ana: ana,
                    roti: roti,
                    point: point,
                    _token: "<?php echo e(csrf_token()); ?>"
                },
                success: function(response) {
                    var priceInput = card.querySelector('.form-control[id="price"]');
                    if (priceInput) {
                        priceInput.value = response.total_price.toFixed(3);
                    } else {
                        console.error('Price input element not found.');
                    }
                    CalculateTotalPrice(); // Recalculate total price after individual card total is updated
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    console.log('Status:', status);
                    console.log('Response:', xhr.responseText);
                }
            });
        }

        // Function to calculate total price
        function CalculateTotalPrice() {
            var total = 0;
            var priceInputs = document.querySelectorAll('.price-input');

            priceInputs.forEach(function(input) {
                var price = parseFloat(input.value) || 0;
                total += price;
            });

            $('#total_price').val(total.toFixed(3));
        }

        // Initial setup
        addInputEventListeners(); // Attach listeners on page load
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/madhobijewellers/public_html/resources/views/admin/purchase/edit.blade.php ENDPATH**/ ?>