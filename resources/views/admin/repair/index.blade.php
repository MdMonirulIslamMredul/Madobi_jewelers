@extends('admin.master')
@section('title')
রিপেয়ার
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
                        <h3>রিপেয়ার করুন</h3>
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
                    <form id="form2" class="form-horizontal" action="{{ route('repair.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group mb-3 custom col-12">
                                <label for="user_id" class="form-label mb-2">কাস্টমার নির্বাচন করুন</label>
                                <select id="user_id" name="user_id"
                                    class="form-select select2 old
                                @error('user_id')
                                    is-invalid
                                @enderror">
                                    <option value="">_ _</option>
                                    @forelse ($users as $user)
                                    <option value="{{ $user->id }}" data-image="{{ asset('user/' . $user->image) }}">
                                        {{ $user->name }} - {{ $user->phone }} ({{ $user->role->role_name }})
                                    </option>
                                        {{-- <option value="{{ $user->id }}">{{ $user->name }}-{{$user->phone}}</option> --}}
                                    @empty
                                    @endforelse
                                </select>
                                @error('user_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="category_id" class="form-label mb-2">ক্যাটেগরি নির্বাচন করুন</label>
                                    <select id="category_id" name="category_id"
                                        class="form-select select2
                                    @error('category_id')
                                        is-invalid
                                    @enderror">
                                        <option selected value="">এখানে নির্বাচন করুন</option>
                                        @forelse ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label for="karat" class="form-label mb-2">ক্যারাট</label>
                                    <input type="number" class="form-control @error('karat')
                                    is-invalid
                                @enderror" rows="5" name="karat" value="{{ old('karat') }}" id="karat">
                                    @error('karat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label for="qtr" class="form-label mb-2">কোয়ান্টিটি</label>
                                    <input type="number" class="form-control @error('qtr')
                                    is-invalid
                                @enderror" rows="5" min="1" id="qtr" name="qtr" value="{{ old('qtr') }}" id="qtr">
                                    @error('qtr')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label for="gram" class="form-label mb-2">গ্রাম হিসাব</label>
                                    <input type="number" class="form-control @error('gram')
                                    is-invalid
                                @enderror" rows="5" min="1" id="gram" readonly name="gram" value="{{ old('gram') }}">
                                    @error('gram')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="bhori" class="form-label mb-2">ভরি</label>
                                    <input type="number" class="form-control @error('bhori') is-invalid @enderror" rows="5" name="bhori" value="{{ old('bhori') }}" id="vori">
                                    @error('bhori')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="bangla_vori" class="form-label mb-2">ভরি Bangla</label>
                                    <input type="text" class="form-control @error('bangla_vori') is-invalid @enderror" rows="5" name="bangla_vori" value="{{ old('bangla_vori') }}" id="bangla_vori" readonly>
                                    @error('bangla_vori')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div> --}}
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="ana" class="form-label mb-2">আনা</label>
                                    <input type="number" class="form-control @error('ana')
                                    is-invalid
                                @enderror" rows="5" id="ana" name="ana" value="{{ old('ana') }}">
                                    @error('ana')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="roti" class="form-label mb-2">রতি</label>
                                    <input type="number" class="form-control @error('roti')
                                    is-invalid
                                @enderror" rows="5" id="roti" name="roti" value="{{ old('roti') }}">
                                    @error('roti')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="point" class="form-label mb-2">পয়েন্ট</label>
                                    <input type="number" class="form-control @error('point')
                                    is-invalid
                                @enderror" rows="5" id="point" name="point" value="{{ old('point') }}">
                                    @error('point')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="added_bhori" class="form-label mb-2">অতিরিক্ত ভরি</label>
                                    <input type="number" class="form-control @error('added_bhori')
                                    is-invalid
                                @enderror" rows="5"  name="added_bhori" value="{{ old('added_bhori') }}" id="added_bhori">
                                    @error('added_bhori')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="added_ana" class="form-label mb-2">অতিরিক্ত আনা</label>
                                    <input type="number" class="form-control @error('added_ana')
                                    is-invalid
                                @enderror" rows="5" id="added_ana" name="added_ana" value="{{ old('added_ana') }}">
                                    @error('added_ana')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="added_roti" class="form-label mb-2">অতিরিক্ত রতি</label>
                                    <input type="number" class="form-control @error('added_roti')
                                    is-invalid
                                @enderror" rows="5" id="added_roti" name="added_roti" value="{{ old('added_roti') }}">
                                    @error('added_roti')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="added_point" class="form-label mb-2">অতিরিক্ত পয়েন্ট</label>
                                    <input type="number" class="form-control @error('added_point')
                                    is-invalid
                                @enderror" rows="5" id="added_point" name="added_point" value="{{ old('added_point') }}">
                                    @error('added_point')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label for="added_gram" class="form-label mb-2">অতিরিক্ত গ্রাম হিসাব</label>
                                    <input type="number" class="form-control @error('added_gram')
                                    is-invalid
                                @enderror" rows="5" min="1" id="added_gram" readonly name="added_gram" value="{{ old('added_gram') }}">
                                    @error('added_gram')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="wage" class="form-label mb-2">মজুরি</label>
                                    <input type="number" class="form-control @error('wage')
                                    is-invalid
                                @enderror" rows="5" id="wage" name="wage" value="{{ old('wage') }}">
                                    @error('roti')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="total" class="form-label mb-2">মোট মূল্য</label>
                                    <input type="text" class="form-control @error('total')
                                    is-invalid
                                @enderror" rows="5" id="total" name="total" value="{{ old('total') }}">
                                    @error('total')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="paid" class="form-label mb-2">অগ্রিম প্রদান</label>
                                    <input type="text" class="form-control @error('paid')
                                    is-invalid
                                @enderror" rows="5" id="paid" name="paid" value="{{ old('paid') }}">
                                    @error('paid')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="due" class="form-label mb-2">বকেয়া</label>
                                    <input type="text" class="form-control @error('due')
                                    is-invalid
                                @enderror" rows="5" id="due" name="due" value="{{ old('due') }}">
                                    @error('due')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="order_date" class="form-label mb-2">অর্ডার গ্রহণের তারিখ</label>
                                    <input type="date" class="form-control @error('order_date')
                                    is-invalid
                                @enderror" rows="5" name="order_date" value="{{ old('order_date') }}" id="order_date">
                                    @error('order_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="receive_date" class="form-label mb-2">অর্ডার প্রদানের তারিখ</label>
                                    <input type="date" class="form-control @error('receive_date')
                                    is-invalid
                                @enderror" rows="5" name="receive_date" value="{{ old('receive_date') }}" id="receive_date">
                                    @error('receive_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="due_payment_date" class="form-label mb-2">বকেয়া পরিশোধের তারিখ</label>
                                    <input type="date" class="form-control @error('due_payment_date')
                                    is-invalid
                                @enderror" rows="5" name="due_payment_date" value="{{ old('due_payment_date') }}" id="due_payment_date">
                                    @error('due_payment_date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label" for="details">ডিটেইলস</label>
                                <div class="input-group">
                                    <textarea name="details" class="form-control" id="details" cols="150" rows="7" placeholder="ডিটেইলস লিখুন..."></textarea>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <label for="photo" class="form-label">{{ __('প্রোডাক্টের ছবি') }}</label>    
                                <div class="d-block position-relative">
                                    <img id="previewIm" src="{{ asset('cover/default-cover.jpg' ) }}" alt="your image" width="300" height="180" onclick="document.getElementById('photoI').click();" style="cursor: pointer;">
                                    <input type="file" id="photoI" name="photo" class="d-none" onchange="preview(this)">
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

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>অর্ডারের তারিখ</th>
                            <th>কাস্টমার</th>
                            <th>ক্যাটেগরি</th>
                            <th>প্রোডাক্ট</th>
                            <th>পরিমাণ</th>
                            <th>সর্বমোট মূল্য</th>
                            <th>মোট প্রদান</th>
                            <th>বকেয়া</th>
                            <th>একশন্স</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp
                        @forelse ($repairs as $repair)
                        <tr>
                            <td>
                                <strong>{{ $sl }}</strong>
                            </td>
                            <td>{{ $repair->order_date }}</td>
                            <td>
                                <img alt="avatar"
                                    src="{{ asset('user/' . $repair->user->image) }}"
                                    class="rounded-circle" style="width:30px; height: 30px">

                                {{ $repair->user->name }}-{{ $repair->user->phone }}
                            </td>
                            <td>{{ $repair->productCategory->category_name ?? 'N/A' }}</td>
                            <td>{{ $repair->product->product_name ?? 'N/A' }}</td>
                            <td>{{ $repair->bhori ?? 0}} ভরি, {{ $repair->ana ?? 0}} আনা, {{ $repair->roti ?? 0}} রতি,  {{ $repair->point ?? 0}} পয়েন্ট </td>
                            <td>{{ $repair->total }}</td>
                            <td>{{ $repair->paid }}</td>
                            <td>{{ $repair->due }}</td>
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
                                        <a href="{{ route('repair.manage.edit', $repair->id) }}"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <form action=""
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
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
        $('#total, #paid').on('input', function() {
            var total_price = $('#total').val();
            var adv_payment = $('#paid').val();

            $.ajax({
                url: "{{ route('calculate.total') }}",
                type: "POST",
                data: {
                    total_price: total_price,
                    adv_payment: adv_payment,
                    _token: "{{ csrf_token() }}"
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
            preview.src = "{{ asset('cover/default-cover.jpg') }}";
        }
    }
</script>

{{-- <script>
    $(document).ready(function() {
        $('#vori').on('input', function() {
            var vori = $('#vori').val();

            // Use Laravel Blade to call PHP function and update the field
            $.ajax({
                url: '{{ route("convert.number.to.bangla") }}',
                type: 'POST',
                data: {
                    value: vori,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                    $('#bangla_vori').val(response);
                }
            });
        });
    });
</script> --}}
@endpush
