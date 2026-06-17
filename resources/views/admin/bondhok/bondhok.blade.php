@extends('admin.master')
@section('title')
    বন্ধক
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3>বন্ধক</h3>
                    </div>
                </div>
                <div class="card-body">
                    <fieldset>
                        <div class="form-group mb-3">
                            <input type="radio" id="old_customer" name="customer_type" checked value="old_customer" />
                            <label class="form-control" for="old_customer">পুরাতন কাস্টমার</label>

                            <input type="radio" id="new_customer" name="customer_type" value="new_customer" />
                            <label class="form-control" for="new_customer">নতুন কাস্টমার</label>
                        </div>
                    </fieldset>
                    <div id="customer" class="d-none">
                        @include('admin.user.form')
                    </div>
                    <form id="form2" class="form-horizontal" action="{{ route('admin.bondhok.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group mb-3 col-12 custom">
                                <label for="name" class="form-label mb-2">কাস্টমার নাম</label>
                                <select class="form-control select2 old" name="user_id" id="user_id">
                                    <option value="">_ _</option>
                                    @foreach ($customer as $c)
                                    <option value="{{ $c->id }}" data-image="{{ asset('user/' . $c->image) }}">
                                        {{ $c->name }} - {{ $c->phone }} ({{ $c->role->role_name }})
                                    @endforeach
                                </select>
                            </div>
                            

                            <div class="form-group mb-3 col-md-10">
                                <label for="type_id" class="form-label mb-2">প্রোডাক্ট</label>
                                <select class="form-control select2" name="type_id" id="type_id">
                                    <option value="">_ _</option>
                                    @foreach ($product as $p)
                                        <option value="{{$p->id}}">{{$p->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="qtr" class="form-label mb-2">কোয়ান্টিটি</label>
                                    <input type="number" class="form-control @error('qtr')
                                    is-invalid
                                @enderror" rows="5" min="1" id="qtr" name="qtr" value="{{ old('qtr') }}">
                                    @error('qtr')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div id="inputContainer"></div>
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
                            <div class="form-group mb-3 col-4">
                                <label for="base_amount" class="form-label mb-2">আসল টাকা</label>
                                <input type="number" class="form-control" name="base_amount" id="base_amount">
                            </div>
                            <div class="form-group mb-3 col-4">
                                <label for="interest_rate" class="form-label mb-2">ইন্টারেস্ট(%)</label>
                                <input type="number" class="form-control" name="interest_rate" id="rate">
                            </div>
                            <div class="form-group mb-3 col-4">
                                <label for="payable_amount" class="form-label mb-2">মোট টাকার পরিমাণ</label>
                                <input type="number" class="form-control" name="payable_amount" id="payable_amount">
                            </div>

                            <div class="form-group mb-3 col-3">
                                <label for="start_time" class="form-label mb-2">প্রদানের তারিখ</label>
                                <input type="date" class="form-control" name="start_time" id="start_time">
                            </div>

                            <div class="form-group mb-3 col-3">
                                <label for="end_time" class="form-label mb-2">গ্রহণের তারিখ</label>
                                <input type="date" class="form-control" name="end_time" id="end_time">
                            </div>
                            <div class="mb-3 col-md-3">
                                <label class="form-label" for="details">ডিটেইলস</label>
                                <div class="input-group">
                                    <textarea name="details" class="form-control" id="details" cols="150" rows="4" placeholder="ডিটেইলস লিখুন..."></textarea>
                                </div>
                            </div>
                            <div class="mb-3 col-md-3 text-center">
                                <label for="photo" class="form-label">{{ __('বন্ধক সম্পর্কিত ছবি') }}</label>    
                                <div class="d-block position-relative">
                                    <img id="previewIm" src="{{ asset('cover/default-cover.jpg' ) }}" alt="your image" width="200" height="120" onclick="document.getElementById('photoI').click();" style="cursor: pointer;">
                                    <input type="file" id="photoI" name="photo" class="d-none" onchange="preview(this)">
                                </div>
                            </div> 
                        </div>
                        <div class="table-responsive">
                            <button type="submit" id="form_sub" class="btn btn-info">সংরক্ষণ করুন</button>
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
                        <th class="text-center">#</th>
                        <th class="text-center">বন্ধক কাস্টমার</th>
                        <th class="text-center">প্রোডাক্ট</th>
                        <th class="text-center">পরিমাণ</th>
                        <th class="text-center">মোট টাকা</th>
                        <th class="text-center">পরিশোধ</th>
                        <th class="text-center">বাকী</th>
                        {{-- <th>প্রদানের তারিখ</th> --}}
                        <th class="text-center">গ্রহণের তারিখ</th>
                        <th class="text-center">একশন</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp
                        @forelse ($transactions as $transaction)
                        <tr>
                            <td class="text-center">
                                <strong>{{ $sl }}</strong>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-start">
                                    <div>
                                        <img alt="avatar" src="{{ asset('user/' . $transaction->user->image) }}" class="rounded-circle" style="width:40px; height: 40px">
                                    </div>
                                    <div>
                                        {{ $transaction->user->name }}<br><a href="#">{{$transaction->user->phone}}</a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">{{ $transaction->bondhok_product->name ?? 'N/A'}}</td>
                            <td class="text-center">
                                @foreach ($transaction->bondhoks as $bondhok)
                                <span class="badge bg-info">{{ $bondhok->bhori ?? 0}} ভরি, {{ $bondhok->ana ?? 0}} আনা, {{ $bondhok->roti ?? 0}} রতি,  {{ $bondhok->point ?? 0}} পয়েন্ট</span> <span class="badge bg-danger">({{$bondhok->gram ?? 0}} গ্রাম )</span><br>
                                @endforeach
                            </td>
                            <td class="text-center">{{ $transaction->total_price ?? 'N/A'}}</td>
                            <td class="text-center">
                                <form class="form-horizontal" action="{{ route('bondhok.paid.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="paid" value="{{ $transaction->total_payment ?? 0 }}">
                                        <div class="input-group-append" style="cursor: pointer">
                                            <button type="submit" class="btn btn-info show_update" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Update">✔</button>                                        </div>
                                    </div>
                                </form>
                            </td>
                            <td class="text-center"> <span class="badge bg-danger"><h6>{{ $transaction->due_payment ?? 'N/A'}}</h6></span></td>
                            <td class="text-center">{{ $transaction->due_payment_date ?? 'N/A'}}</td>
                            {{-- <td>{{ $b->end_time ?? 'N/A'}}</td> --}}
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
                                        <a href="#"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <form action="{{ route('bondhok.delete') }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                                            <button type="submit" class="text-warning btn_custom show_confirm" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Delete">
                                                <i class="fa-solid fa-trash-can fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @php $sl++ @endphp
                        @empty
                        No Data Found!
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
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

    <script type="text/javascript">
     $(document).ready(function() {
        $('#base_amount, #rate').on('input', function() {
            var base_amount = parseFloat($('#base_amount').val());
            var rate = parseFloat($('#rate').val());

            // Calculate interest
            var interest = base_amount * (rate / 100);

            // Calculate payable amount
            var payable = base_amount + interest;

            // Set the payable amount
            $('#payable_amount').val(payable.toFixed(2)); // rounding to 2 decimal places
        });
    });
    </script>
    <script>
        $(document).ready(function() {
            $('#qtr').on('input', function() {
            var qtrValue = parseInt(this.value) || 0;
            var container = document.getElementById('inputContainer');
            container.innerHTML = '';
    
            for (var i = 1; i <= qtrValue; i++) {
                var template = document.getElementById('inputTemplate');
                var clone = document.importNode(template.content, true);
                clone.querySelector('.card-number').textContent = i;
                container.appendChild(clone);
            }
    
            // Add event listeners to the new input fields
            var voriInputs = container.querySelectorAll('.vori-input');
            var anaInputs = container.querySelectorAll('.ana-input');
            var rotiInputs = container.querySelectorAll('.roti-input');
            var pointInputs = container.querySelectorAll('.point-input');
            var priceInputs = container.querySelectorAll('.unit_price_input');
    
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
    
        });
        });
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
                    card.querySelector('.form-control[id="gram"]').value = response.grams.toFixed(3);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error:', error);
                    console.log('Status:', status);
                    console.log('Response:', xhr.responseText);
                }
            });
        }
        function CalculateTotal() {
            var card = this.closest('.card-body');
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
                        card.querySelector('.form-control[id="price"]').value = response.total_price.toFixed(3);
                        CalculateTotalPrice();
                    }
                });
        }
        function CalculateTotalPrice() {
            var total = 0;
            var priceInputs = document.querySelectorAll('.price-input');
            
            priceInputs.forEach(function(input) {
                var price = parseFloat(input.value) || 0;
                total += price;
            });
    
            $('#base_amount').val(total.toFixed(3));
        }
    </script>

@endsection

@push('admin_script')
@include('admin.common.script')
@endpush
