@extends('admin.master')
@section('title')
    কারিগর স্টক
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
                        <h3>কারিগর স্টক</h3>
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
                    <form id="form2" class="form-horizontal" action="{{ route('karigor.stock.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group mb-3 custom col-12">
                                <label for="user_id" class="form-label mb-2">কারিগর নির্বাচন করুন</label>
                                <select id="user_id" name="user_id"
                                    class="form-select select2 old
                                @error('user_id')
                                    is-invalid
                                @enderror">
                                    <option value="">_ _</option>
                                    @forelse ($users as $user)
                                        <option value="{{ $user->id }}"
                                            data-image="{{ asset('user/' . $user->image) }}">
                                            {{ $user->name }} - {{ $user->phone }} ({{ $user->role->role_name }})
                                        </option>
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
                                    <select id="product_id" name="product_id" class="form-control select2" disabled>
                                        <option selected value="">এখানে নির্বাচন করুন</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="bhori" class="form-label mb-2">ভরি</label>
                                    <input type="number" class="form-control vori-input" rows="5" name="bhori"
                                        id="vori">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="ana" class="form-label mb-2">আনা</label>
                                    <input type="number" class="form-control ana-input" rows="5" name="ana"
                                        id="ana">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="roti" class="form-label mb-2">রতি</label>
                                    <input type="number" class="form-control roti-input" rows="5" name="roti"
                                        id="roti">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="point" class="form-label mb-2">পয়েন্ট</label>
                                    <input type="number" class="form-control point-input" rows="5" name="point"
                                        id="point">
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="gram" class="form-label mb-2">গ্রাম হিসাব</label>
                                    <input type="number" class="form-control" rows="5" min="1"
                                        name="gram" readonly id="gram">
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
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endpush
