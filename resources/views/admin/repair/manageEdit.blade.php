@extends('admin.master')
@section('title')
রিপেয়ার ম্যানেজ
@endsection

@push('admin_style')
@include('admin.common.style')
<style>
    .card-header h3 {
        font-size: 1.5rem;
        margin: 0;
    }
    .card-body p {
        margin-bottom: 0.75rem;
    }
    .card-body img {
        margin-bottom: 0.75rem;
    }
    .info-title {
        font-weight: bold;
        margin-right: 5px;
    }
    .info-content {
        display: inline-block;
        vertical-align: top;
    }
    .avatar {
        display: inline-block;
        vertical-align: middle;
        margin-right: 10px;
    }
</style>
@endpush

@section('body')
<div class="row mt-2">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3>রিপেয়ার ম্যানেজ করুন</h3>
            </div>
        </div>
    </div>
    <div class="card col-md-4">
        <div class="card-header text-center"><p class="info-title">কাস্টমার ইনফরমেশন</p> </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 text-center">
                    <img alt="avatar" src="{{ asset('user/' . $repair->user->image) }}" style="width:100px; height: 120px">
                </div>
                <div class="col-md-8">
                    <p><span class="info-title">নাম:</span> <span class="info-content">{{ $repair->user->name ?? 'N/A' }}</span></p>
                    <p><span class="info-title">মোবাইল:</span> <span class="info-content">{{ $repair->user->phone ?? 'N/A' }}</span></p>
                    <p><span class="info-title">ঠিকানা:</span> <span class="info-content">{{ $repair->user->address ?? 'N/A' }}</span></p>
                </div>
            </div>
        </div>
        <div class="card-footer">
                <div class="header text-center"><p class="info-title">রিপেয়ার প্রোডাক্ট ইনফরমেশন</p> </div>
                    <div class="row">
                        <div class="col-md-6 text-center mb-2">
                            <p><span class="info-title">আগের ছবি</span> </p>  
                                <img class="text-center" src="{{ asset($repair->prev_photo) }}" alt="Previous Photo" style="width:100px; height: 100px;">
                        </div>
                        <div class="col-md-6 text-center mb-2">
                            @if ($repair->converted_photo)
                            <p><span class="info-title">পরের ছবি</span> </p>  
                            <img src="{{ asset($repair->converted_photo) }}" alt="Converted Photo" style="width:120px; height: 100px;">
                            @else
                            <p><span class="info-title">পরের ছবি</span> </p>  
                            <img src="{{ asset('cover/default-cover.jpg' ) }}" alt="Converted Photo" style="width:100px; height: 80px;">
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><span class="info-title">ক্যাটেগরি:</span> <span class="info-content">{{ $repair->productCategory->category_name ?? 'N/A' }}</span></p>
                            <p><span class="info-title">প্রোডাক্ট:</span> <span class="info-content">{{ $repair->product->product_name }}</span></p>
                            <p><span class="info-title">ক্যারাট:</span> <span class="info-content">{{ $repair->karat }}</span></p>
                            <p><span class="info-title">কোয়ান্টিটি:</span> <span class="info-content">{{ $repair->qty }}</span></p>
                            <p><span class="info-title">পরিমাণ:</span> 
                                <span class="info-content">
                                    {{ $repair->bhori ?? 0}} ভরি, 
                                    {{ $repair->ana ?? 0}} আনা, 
                                    {{ $repair->roti ?? 0}} রতি,  
                                    {{ $repair->point ?? 0}} পয়েন্ট 
                                </span>
                            </p>
                            <p><span class="info-title">পরিমাণ (গ্রাম হিসাব):</span> <span class="info-content">{{ $repair->gram ?? 0}} গ্রাম</span></p>
                            <p><span class="info-title">অতিরিক্ত পরিমাণ:</span> 
                                <span class="info-content">
                                    {{ $repair->added_bhori ?? 0}} ভরি, 
                                    {{ $repair->added_ana ?? 0}} আনা, 
                                    {{ $repair->added_roti ?? 0}} রতি,  
                                    {{ $repair->added_point ?? 0}} পয়েন্ট 
                                </span>
                            </p>
                            <p><span class="info-title">অতিরিক্ত পরিমাণ (গ্রাম হিসাব):</span> <span class="info-content">{{ $repair->added_gram ?? 0}} গ্রাম</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><span class="info-title">ডিটেইলস:</span> <span class="info-content">{{ $repair->details }}</span></p>
                            <p><span class="info-title">মজুরি:</span> <span class="info-content">{{ $repair->wage }}</span></p>
                            <p><span class="info-title">বকেয়া:</span> <span class="info-content">{{ $repair->due }}</span></p>
                            <p><span class="info-title">পরিশোধ:</span> <span class="info-content">{{ $repair->paid }}</span></p>
                            <p><span class="info-title">মোট:</span> <span class="info-content">{{ $repair->total }}</span></p>
                            <p><span class="info-title">অর্ডার গ্রহণের তারিখ:</span> <span class="info-content">{{ $repair->order_date }}</span></p>
                            <p><span class="info-title">অর্ডার প্রদানের তারিখ:</span> <span class="info-content">{{ $repair->receive_date }}</span></p>
                            <p><span class="info-title">বকেয়া পরিশোধের তারিখ:</span> <span class="info-content">{{ $repair->due_payment_date }}</span></p>
                        </div>

                </div>

        </div>
    </div>
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>কারিগর নিয়োগ করুন</h3>
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
                    @include('admin.user.form')
                </div>
                <form id="form2" class="form-horizontal" action="{{ route('repair.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="repair_id" value="{{$repair->id}}">
                    <input type="hidden" name="status" value = "on-process">
                    <div class="row">
                        <div class="form-group mb-3 custom col-md-12">
                            <div class="row">
                            <div class="col-md-4">
                                <label for="user_id" class="form-label mb-2">কারিগর নির্বাচন করুন</label>
                            </div>
                            <div class="col-md-8"><span class="k_stock"></span></div>
                            </div>
                            <select id="user_id" name="user_id"
                                class="form-select select2 old karigor
                            @error('user_id')
                                is-invalid
                            @enderror">
                                <option value="">_ _</option>
                                @forelse ($users as $user)
                                <option @if ($user->id == $repair->karigor_id)
                                    selected
                                @endif value="{{ $user->id }}" data-image="{{ asset('user/' . $user->image) }}">
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
                        <div class="col-lg-2">
                            <div class="form-group mb-3">
                                <label for="added_bhori" class="form-label mb-2">অতিরিক্ত ভরি</label>
                                <input type="number" class="form-control @error('added_bhori')
                                is-invalid
                            @enderror" rows="5"  name="added_bhori" value="{{ $repair->added_bhori }}" id="added_bhori">
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
                            @enderror" rows="5" id="added_ana" name="added_ana" value="{{ $repair->added_ana }}">
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
                            @enderror" rows="5" id="added_roti" name="added_roti" value="{{ $repair->added_roti }}">
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
                            @enderror" rows="5" id="added_point" name="added_point" value="{{ $repair->added_point }}">
                                @error('added_point')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group mb-3">
                                <label for="added_gram" class="form-label mb-2">অতিরিক্ত (গ্রাম হিসাব)</label>
                                <input type="number" class="form-control @error('added_gram')
                                is-invalid
                            @enderror" rows="5" min="1" id="added_gram" readonly name="added_gram" value="{{ $repair->added_gram }}">
                                @error('added_gram')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group mb-3">
                                <label for="added_status" class="form-label mb-2">অতিরিক্ত কে দিবে?</label>
                                <select id="added_status" name="added_status" class="form-select">
                                    <option value="">_ _</option>
                                    <option @if ($repair->added_status == 'karigor')
                                        selected
                                    @endif value="karigor">কারিগর</option>
                                    <option @if ($repair->added_status == 'owner')
                                        selected
                                    @endif value="customer">মালিক</option>
                                    <option @if ($repair->added_status == 'customer')
                                        selected
                                    @endif value="customer">কাস্টমার</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6 text-center">
                            <label for="converted_photo" class="form-label">{{ __('প্রোডাক্টের পরের ছবি') }}</label>    
                            <div class="d-block position-relative">
                                @if($repair->converted_photo)
                                <img id="previewIm" src="{{ asset($repair->converted_photo) }}" alt="your image" width="300" height="180" onclick="document.getElementById('photoI').click();" style="cursor: pointer;">
                                @else
                                <img id="previewIm" src="{{ asset('cover/default-cover.jpg' ) }}" alt="your image" width="300" height="180" onclick="document.getElementById('photoI').click();" style="cursor: pointer;">
                                @endif
                                <input type="file" value="{{$repair->converted_photo}}" id="photoI" name="converted_photo" class="d-none" onchange="preview(this)">
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
        $('.karigor').on('change', function() {
            var karigor_id = $(this).val();
            var category_id = '{{ $repair->category_id }}';
            $.ajax({
                url: "{{ route('karigor.stock') }}",
                type: "POST",
                data: {
                    id: karigor_id,
                    cat_id: category_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    var stockInfo = '';
                    if(response.karigor_id) {
                        if(response.bhori <= 0 && response.ana <= 0 && response.roti <= 0 && response.point <= 0) {
                            stockInfo += '<span class="badge mb-2 bg-danger"><p><strong>(সিলেক্টেড কারিগরের স্টকে এই ক্যাটেগরি নেই!!!!)</strong> </p></span>';
                        } else {
                            stockInfo += '<span class="badge mb-2 bg-success"><p><strong>( সিলেক্টেড কারিগরের স্টক:</strong> ' + response.bhori + ' ভরি ' + response.ana + ' আনা ' + response.roti + ' রতি ' + response.point + ' পয়েন্ট )</p></span>';
                        }
                    } else {
                        stockInfo += '<span class="badge mb-2 bg-danger"><p><strong>(সিলেক্টেড কারিগরের স্টকে এই ক্যাটেগরি নেই!!!!)</strong> </p></span>';
                    }
                    $('.k_stock').html(stockInfo);
                }
            });
        });
    });
</script>

@endpush
