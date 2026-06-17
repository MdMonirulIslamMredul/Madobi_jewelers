@extends('admin.master')
@section('title')
    স্টক তৈরি
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
                        <h3>স্টক তৈরি করুন</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form id="form2" class="form-horizontal" action="{{ route('stock.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">                         
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
                            <div class="col-lg-3">
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
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="unit_price" class="form-label mb-2">একক মূল্য/ভরি </label>
                                    <input type="number" class="form-control @error('unit_price')
                                    is-invalid
                                @enderror" rows="5" id="unit_price" name="unit_price" value="{{ old('unit_price') }}" id="unit_price">
                                    @error('unit_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
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
                            <div class="col-lg-3">
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
                                    <input type="number" class="form-control @error('bhori')
                                    is-invalid
                                @enderror" rows="5"  name="bhori" value="{{ old('bhori') }}" id="vori">
                                    @error('bhori')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
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
                                    @error('roti')
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

@endpush
