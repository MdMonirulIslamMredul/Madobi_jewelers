@extends('admin.master')
@section('title')
ক্রয় সংশোধন
@endsection
@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
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
                    @include('admin.user.form')
                </div>
                <form id="form2" class="form-horizontal" action="{{ route('purchase.update', $transaction->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                    <div class="row">
                        <div class="form-group mb-3 custom col-12">
                            <label for="user_id" class="form-label mb-2">সাপ্লায়ার নির্বাচন করুন</label>
                            <select id="user_id" name="user_id"
                                class="form-select select2 old
                                @error('user_id') is-invalid @enderror">
                                <option value="">_ _</option>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}" data-image="{{ asset('user/' . $user->image) }}"
                                    {{ $transaction->user_id == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} - {{ $user->phone }} ({{ $user->role->role_name }})
                                </option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label for="qtr" class="form-label mb-2">কোয়ান্টিটি</label>
                                <input type="number" class="form-control @error('qtr') is-invalid @enderror" rows="5" min="1" id="qtr" name="qtr" value="{{ old('qtr', count($transaction->purchases)) }}">
                                @error('qtr')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div id="inputContainer">
                            @foreach ($transaction->purchases as $index => $purchase)
                            <div class="card mb-3" style="background-color: #c2bebe">
                                <div class="card-header">
                                    <h5 class="card-title">QTY <span class="card-number">{{ $index + 1 }}</span></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label mb-2">ক্যাটেগরি নির্বাচন করুন</label>
                                                <select name="category_id[]" class="form-select select2 category-select">
                                                    <option selected value="">এখানে নির্বাচন করুন</option>
                                                    @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ $purchase->category_id == $category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label mb-2">প্রোডাক্ট নির্বাচন করুন</label>
                                                <select name="product_id[]" class="form-control select2 product-select">
                                                    <option selected value="">এখানে নির্বাচন করুন</option>
                                                    @foreach ($purchase->productCategory->products ?? [] as $prod)
                                                    <option value="{{ $prod->id }}" {{ $purchase->product_id == $prod->id ? 'selected' : '' }}>{{ $prod->product_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="bhori" class="form-label mb-2">ভরি</label>
                                                <input type="number" class="form-control vori-input" rows="5" name="bhori[]" value="{{ old('bhori.' . $index, $purchase->bhori) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="ana" class="form-label mb-2">আনা</label>
                                                <input type="number" class="form-control ana-input" rows="5" name="ana[]" value="{{ old('ana.' . $index, $purchase->ana) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="roti" class="form-label mb-2">রতি</label>
                                                <input type="number" class="form-control roti-input" rows="5" name="roti[]" value="{{ old('roti.' . $index, $purchase->roti) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="point" class="form-label mb-2">পয়েন্ট</label>
                                                <input type="number" class="form-control point-input" rows="5" name="point[]" value="{{ old('point.' . $index, $purchase->point) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            @php
                                            $karatStdOptions = ['18K','21K','22K','24K'];
                                            $karatValue = old('karat.' . $index, $purchase->karat ?? '');
                                            $karatIsCustom = !in_array($karatValue, $karatStdOptions);
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="karat" class="form-label mb-2">ক্যারাট</label>
                                                <select name="karat[]" class="form-select karat-select">
                                                    @foreach($karatStdOptions as $kOpt)
                                                    <option value="{{ $kOpt }}" {{ $karatValue == $kOpt ? 'selected' : '' }}>{{ $kOpt }}</option>
                                                    @endforeach
                                                    <option value="Paeine" {{ $karatValue == 'Paeine' ? 'selected' : '' }}>Paeine</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="unit_price" class="form-label mb-2">একক মূল্য/ভরি</label>
                                                <input type="number" class="form-control unit_price_input" rows="5" name="unit_price[]" value="{{ old('unit_price.' . $index, $purchase->unit_price) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="one_gram_price" class="form-label mb-2">এক গ্রামের মূল্য</label>
                                                <input type="number" step="any" class="form-control one_gram_price_input" rows="5" min="1" name="one_gram_price[]" readonly value="{{ old('one_gram_price.' . $index, $purchase->one_gram_price) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="gram" class="form-label mb-2">গ্রাম হিসাব</label>
                                                <input type="number" id="gram" class="form-control" rows="5" min="1" name="gram[]" readonly value="{{ old('gram.' . $index, $purchase->gram) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="raw_gold" class="form-label mb-2">পাকা সোনা (গ্রাম)</label>
                                                <input type="number" step="any" class="form-control raw-gold-input" rows="5" name="raw_gold[]" id="raw_gold" value="{{ old('raw_gold.' . $index, $purchase->raw_gold) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="price" class="form-label mb-2">ক্রয় মূল্য</label>
                                                <input type="number" class="form-control price-input" readonly rows="5" name="price[]" value="{{ old('total_price.' . $index, $purchase->total_price) }}" id="price">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="actual_price" class="form-label mb-2">আসল মূল্য</label>
                                                <input type="number" step="any" class="form-control actual-price-input" rows="5" name="actual_price[]" value="{{ old('actual_price.' . $index, $purchase->actual_price) }}">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label mb-2" for="details">ডিটেইলস</label>
                                                <textarea name="details[]" class="form-control details-input" rows="4" placeholder="ডিটেইলস লিখুন...">{{ old('details.' . $index, $purchase->details) }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label class="form-label mb-2">{{ __('প্রোডাক্টের ছবি') }}</label>
                                                <div class="d-block position-relative photo-wrapper border rounded p-2 text-center" style="background: #f8f9fa;">
                                                    <img class="photo-preview img-fluid rounded" src="{{ $purchase->photo ? asset('user/purchase/' . $purchase->photo) : asset('cover/default-cover.jpg') }}" alt="your image" style="cursor: pointer; max-height: 100px; width: 100%; object-fit: cover;">
                                                    <input type="file" name="photo[]" class="photo-input d-none" accept="image/*">
                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-sm btn-secondary photo-browse-btn w-100"><i class="fa-solid fa-camera"></i> ছবি নির্বাচন</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <template id="inputTemplate">
                            <div class="card mb-3" style="background-color: #c2bebe">
                                <div class="card-header">
                                    <h5 class="card-title">QTY <span class="card-number"></span></h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label mb-2">ক্যাটেগরি নির্বাচন করুন</label>
                                                <select name="category_id[]" class="form-select select2 category-select">
                                                    <option selected value="">এখানে নির্বাচন করুন</option>
                                                    @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label mb-2">প্রোডাক্ট নির্বাচন করুন </label>
                                                <select name="product_id[]" class="form-control select2 product-select" disabled>
                                                    <option selected value="">এখানে নির্বাচন করুন</option>
                                                </select>
                                            </div>
                                        </div>

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
                                            @php
                                            $karatOptions = ['18K','21K','22K','24K'];
                                            $selectedKarat = old('karat.' . $index, $purchase->karat ?? '');
                                            $isCustom = !in_array($selectedKarat, $karatOptions);
                                            @endphp
                                            <div class="form-group mb-3">
                                                <label for="karat" class="form-label mb-2">ক্যারাট</label>
                                                <select name="karat[]" class="form-select karat-select">
                                                    @foreach($karatOptions as $opt)
                                                    <option value="{{ $opt }}" {{ $selectedKarat == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                                    @endforeach
                                                    <option value="Paeine" {{ $selectedKarat == 'Paeine' ? 'selected' : '' }}>Paeine</option>
                                                </select>
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
                                                <label for="one_gram_price" class="form-label mb-2">এক গ্রামের মূল্য</label>
                                                <input type="number" step="any" class="form-control one_gram_price_input" rows="5" min="1" name="one_gram_price[]" readonly id="one_gram_price">
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
                                                <label for="raw_gold" class="form-label mb-2">পাকা সোনা (গ্রাম)</label>
                                                <input type="number" step="any" class="form-control raw-gold-input" rows="5" name="raw_gold[]" id="raw_gold">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="price" class="form-label mb-2">ক্রয় মূল্য</label>
                                                <input type="number" class="form-control price-input" readonly rows="5" name="price[]" id="price">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="actual_price" class="form-label mb-2">আসল মূল্য</label>
                                                <input type="number" step="any" class="form-control actual-price-input" rows="5" name="actual_price[]" id="actual_price">
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label mb-2" for="details">ডিটেইলস</label>
                                                <textarea name="details[]" class="form-control details-input" rows="4" placeholder="ডিটেইলস লিখুন..."></textarea>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label class="form-label mb-2">{{ __('প্রোডাক্টের ছবি') }}</label>
                                                <div class="d-block position-relative photo-wrapper border rounded p-2 text-center" style="background: #f8f9fa;">
                                                    <img class="photo-preview img-fluid rounded" src="{{ asset('cover/default-cover.jpg') }}" alt="your image" style="cursor: pointer; max-height: 100px; width: 100%; object-fit: cover;">
                                                    <input type="file" name="photo[]" class="photo-input d-none" accept="image/*">
                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-sm btn-secondary photo-browse-btn w-100"><i class="fa-solid fa-camera"></i> ছবি নির্বাচন</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <label for="total_price" class="form-label mb-2">মোট মূল্য</label>
                                <input type="text" class="form-control @error('total_price')
                                is-invalid
                            @enderror" rows="5" id="total_price" name="total_price" value="{{ old('total_price',$transaction->total_price) }}">
                                @error('total_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <label for="adv_payment" class="form-label mb-2">অগ্রিম প্রদান</label>
                                <input type="text" class="form-control @error('adv_payment')
                                is-invalid
                            @enderror" rows="5" id="adv_payment" name="adv_payment" value="{{ old('adv_payment',$transaction->adv_payment) }}">
                                @error('adv_payment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <label for="due_payment" class="form-label mb-2">বকেয়া</label>
                                <input type="text" class="form-control @error('due_payment')
                                is-invalid
                            @enderror" rows="5" id="due_payment" name="due_payment" value="{{ old('due_payment',$transaction->due_payment) }}">
                                @error('due_payment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group mb-3">
                                <label for="total_payment" class="form-label mb-2">মোট প্রদান</label>
                                <input type="text" class="form-control @error('total_payment')
                                is-invalid
                            @enderror" rows="5" id="total_payment" name="total_payment" value="{{ old('total_payment',$transaction->total_payment) }}">
                                @error('total_payment')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="card bg-secondary rounded col-lg-4">
                            <label class="bg-secondary border-none mb-3" for="location">দোকান/গুদাম নির্বাচন করুন:</label>
                            <div class="m-5">
                                <fieldset>
                                    <div>
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <input type="radio" id="is_shop" name="location" value="is_shop" />
                                                <label class="form-control" for="is_shop">দোকান</label>
                                            </div>
                                            <div class="col-6">
                                                <input type="radio" id="is_warehouse" name="location" value="is_warehouse" />
                                                <label class="form-control" for="is_warehouse">গুদাম</label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="order_date" class="form-label mb-2">ক্রয়ের তারিখ</label>
                                <input type="date" class="form-control @error('order_date')
                                is-invalid
                            @enderror" rows="5" name="order_date" value="{{ old('order_date',$transaction->purchases[0]->order_date) }}" id="order_date">
                                @error('order_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <!-- <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="receive_date" class="form-label mb-2">অর্ডার গ্রহণের তারিখ</label>
                                <input type="date" class="form-control @error('receive_date')
                                is-invalid
                            @enderror" rows="5" name="receive_date" value="{{ old('receive_date',$transaction->purchases[0]->receive_date) }}" id="receive_date">
                                @error('receive_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div> -->
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="due_payment_date" class="form-label mb-2">বকেয়া পরিশোধের তারিখ</label>
                                <input type="date" class="form-control @error('due_payment_date')
                                is-invalid
                            @enderror" rows="5" name="due_payment_date" value="{{ old('due_payment_date',$transaction->purchases[0]->due_payment_date) }}" id="due_payment_date">
                                @error('due_payment_date')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
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

@endsection

@push('admin_script')
@include('admin.common.script')
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
                url: "{{ route('calculate.total') }}",
                type: "POST",
                data: {
                    total_price: total_price,
                    adv_payment: adv_payment,
                    _token: "{{ csrf_token() }}"
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
    // Per-card photo preview using event delegation
    $(document).on('change', '.photo-input', function() {
        var wrapper = $(this).closest('.photo-wrapper');
        var previewImg = wrapper.find('.photo-preview')[0];
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onloadend = function() {
                previewImg.src = reader.result;
            };
            reader.readAsDataURL(file);
        } else {
            previewImg.src = "{{ asset('cover/default-cover.jpg') }}";
        }
    });

    // Click preview image or browse button to open file picker
    $(document).on('click', '.photo-preview, .photo-browse-btn', function() {
        $(this).closest('.photo-wrapper').find('.photo-input').trigger('click');
    });
</script>
<script>
    $(document).ready(function() {
        // Initialize select2 on existing elements
        $('.select2').select2();

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

                    var card = clone.querySelector('.card');
                    container.appendChild(clone);

                    // Initialize select2 on the newly appended card
                    $(card).find('.select2').select2();
                }
            }

            // Add event listeners to new input fields after modifying DOM
            addInputEventListeners();
        });

        // Delegated event listener for category selection changes inside the dynamic cards
        $(document).on('change', '.category-select', function() {
            var category_id = $(this).val();
            var card = $(this).closest('.card-body');
            var productSelect = card.find('.product-select');

            if (category_id) {
                var url = window.location.href.includes('/admin/sells') ?
                    `${window.location.origin}/get-products-shop/${category_id}` :
                    `${window.location.origin}/get-products/${category_id}`;

                axios.get(url).then(res => {
                    let products = res.data;
                    productSelect.removeAttr('disabled');
                    productSelect.empty();
                    productSelect.append(`<option value="">এখানে নির্বাচন করুন</option>`);
                    products.forEach(product => {
                        productSelect.append(
                            `<option value="${product.id}">${product.product_name}</option>`
                        );
                    });
                    productSelect.trigger('change');
                });
            } else {
                productSelect.empty();
                productSelect.append(`<option value="">এখানে নির্বাচন করুন</option>`);
                productSelect.attr('disabled', 'disabled');
                productSelect.trigger('change');
            }
        });

        // Function to add event listeners to input fields
        function addInputEventListeners() {
            var container = document.getElementById('inputContainer');
            var voriInputs = container.querySelectorAll('.vori-input');
            var anaInputs = container.querySelectorAll('.ana-input');
            var rotiInputs = container.querySelectorAll('.roti-input');
            var pointInputs = container.querySelectorAll('.point-input');
            var priceInputs = container.querySelectorAll('.unit_price_input');
            var rawGoldInputs = container.querySelectorAll('.raw-gold-input');

            priceInputs.forEach(input => {
                input.addEventListener('input', CalculateTotal);
            });
            rawGoldInputs.forEach(input => {
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

            var karatSelects = container.querySelectorAll('.karat-select');
            karatSelects.forEach(select => {
                select.addEventListener('change', function() {
                    var cardBody = this.closest('.card-body');
                    calculateRawGold(cardBody);
                    CalculateTotalForCard(cardBody);
                });
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
                url: "{{ route('convert.to.gram') }}",
                method: 'POST',
                data: {
                    vori: vori,
                    ana: ana,
                    roti: roti,
                    point: point,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    card.querySelector('.form-control[id="gram"]').value = response.grams.toFixed(3);
                    calculateRawGold(card);
                    CalculateTotalForCard(card);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    console.log('Status:', status);
                    console.log('Response:', xhr.responseText);
                }
            });
        }

        // Calculation function
        function calculateRawGold(card) {
            var gramInput = card.querySelector('.form-control[id="gram"]');
            var karatSelect = card.querySelector('.karat-select');
            var rawGoldInput = card.querySelector('.raw-gold-input');

            if (!gramInput || !karatSelect || !rawGoldInput) return;

            var gram = parseFloat(gramInput.value) || 0;
            var karat = karatSelect.value;
            var rawGold = 0;

            if (karat === '22K') {
                rawGold = gram * 0.90;
            } else if (karat === '21K') {
                rawGold = gram * 0.86;
            } else if (karat === '18K') {
                rawGold = gram * 0.75;
            } else if (karat === '24K') {
                rawGold = gram * 0.98;
            } else if (karat === 'Paeine') {
                rawGold = gram * 0.50;
            }

            rawGoldInput.value = rawGold.toFixed(3);
        }

        function CalculateTotal() {
            CalculateTotalForCard(this.closest('.card-body'));
        }

        function CalculateTotalForCard(card) {
            var unit_price = parseFloat(card.querySelector('.unit_price_input').value) || 0;

            // এক গ্রামের মূল্য = একক মূল্য/ভরি ÷ 11.664
            var one_gram_price = unit_price ? (unit_price / 11.664) : 0;

            var oneGramPriceInput = card.querySelector('.one_gram_price_input');
            if (oneGramPriceInput) {
                oneGramPriceInput.value = one_gram_price ? one_gram_price.toFixed(3) : '';
            }

            // ক্রয় মূল্য = এক গ্রামের মূল্য × পাকা সোনা (গ্রাম)
            var raw_gold = parseFloat(card.querySelector('.raw-gold-input').value) || 0;
            var total_price = one_gram_price * raw_gold;

            card.querySelector('.form-control[id="price"]').value = total_price.toFixed(3);

            // মোট মূল্য = সব ক্রয় মূল্যের যোগফল
            CalculateTotalPrice();
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
@endpush