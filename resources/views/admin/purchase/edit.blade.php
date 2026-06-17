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
                                            <div class="form-group mb-3">
                                                <label for="karat" class="form-label mb-2">ক্যারাট</label>
                                                <input type="number" class="form-control" rows="5" name="karat[]" value="{{ old('karat.' . $index, $purchase->karat) }}">
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
                                                <label for="gram" class="form-label mb-2">গ্রাম হিসাব</label>
                                                <input type="number" id="gram" class="form-control" rows="5" min="1" name="gram[]" readonly value="{{ old('gram.' . $index, $purchase->gram) }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group mb-3">
                                                <label for="price" class="form-label mb-2">মূল্য</label>
                                                <input type="number" class="form-control price-input" readonly rows="5" name="price[]" value="{{ old('total_price.' . $index, $purchase->total_price) }}" id="price">
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
                                                <input type="radio" id="is_shop" name="location" value="is_shop" @if ($transaction->purchases[0]->location == 'is_shop')
                                                checked  
                                                @endif/>
                                                <label class="form-control" for="is_shop">দোকান</label>
                                            </div>
                                            <div class="col-6">
                                                <input type="radio" id="is_warehouse" name="location" value="is_warehouse" @if ($transaction->purchases[0]->location == 'is_warehouse')
                                                checked  
                                                @endif/>
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
                                <textarea name="details" class="form-control" id="details" cols="150" rows="7" placeholder="ডিটেইলস লিখুন...">{{$transaction->details}}</textarea>
                            </div>
                        </div>
                        <div class="mb-3 col-md-4 text-center">
                            <label for="photo" class="form-label">{{ __('প্রোডাক্টের ছবি') }}</label>    
                            <div class="d-block position-relative">
                                <img id="previewIm" src="{{ asset('user/purchase/'. $transaction->purchases[0]->photo ) }}" alt="your image" width="350" height="180" onclick="document.getElementById('photoI').click();" style="cursor: pointer;">
                                <input type="file" id="photoI" name="photo" class="d-none" onchange="preview(this)">
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="order_date" class="form-label mb-2">অর্ডারের তারিখ</label>
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
                        <div class="col-lg-4">
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
                        </div>
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
            preview.src = "{{ asset('cover/default-cover.jpg') }}";
        }
    }
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
                url: '{{ route('convert.to.gram') }}',
                method: 'POST',
                data: {
                    vori: vori,
                    ana: ana,
                    roti: roti,
                    point: point,
                    _token: "{{ csrf_token() }}"
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
                url: "{{ route('calculate') }}",
                type: "POST",
                data: {
                    unit_price: unit_price,
                    bhori: vori,
                    ana: ana,
                    roti: roti,
                    point: point,
                    _token: "{{ csrf_token() }}"
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
@endpush