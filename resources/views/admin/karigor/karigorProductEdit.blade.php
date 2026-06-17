@extends('admin.master')
@section('title')
কারিগর প্রোডাক্ট ইডিট 
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
                        <h3>কারিগর প্রোডাক্ট ইডিট</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('karigor.product.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="{{$karigor_product->status?? 'on-process'}}">
                        <input type="hidden" name="id" value="{{$karigor_product->id}}">
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
                                    @foreach ($karigors as $c)
                                        <option @if ($c->id == $karigor_product->karigor_id)
                                            selected
                                        @endif value="{{$c->id}}">{{$c->name}} - {{$c->phone}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="karigor">
                                {{-- hidden --}}
                            </div>

                            <div class="form-group mb-3 col-6">
                                <label for="category" class="form-label mb-2">ক্যাটেগরি নির্বাচন করুন*</label>
                                <select class="form-control select2" name="category" id="category" required>
                                    <option value="">_এখানে নির্বাচন করুন_</option>
                                    @foreach ($warehouse_category as $warehouse)
                                        <option @if ($warehouse->id == $karigor_product->category_id)
                                            selected
                                        @endif value="{{$warehouse->id}}">{{$warehouse->category_name}}</option>
                                    @endforeach
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
                                    <input type="number" class="form-control @error('karat')
                                    is-invalid
                                @enderror" rows="5" name="karat" value="{{ $karigor_product->karat }}" id="karat">
                                    @error('karat')
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
                                @enderror" rows="5" id="bhori" name="bhori" value="{{ $karigor_product->bhori }}" id="bhori">
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
                                @enderror" rows="5" id="ana" name="ana" value="{{ $karigor_product->ana }}" id="ana">
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
                                @enderror" rows="5" id="roti" name="roti" value="{{ $karigor_product->roti }}" id="roti">
                                    @error('roti')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="form-group mb-3">
                                    <label for="converted_category_id" class="form-label mb-2">রূপান্তরিত প্রোডাক্টের ক্যাটেগরি*</label>
                                    <select id="converted_category_id" name="converted_category_id" required
                                        class="form-select select2
                                    @error('converted_category_id')
                                        is-invalid
                                    @enderror">
                                        <option selected>এখানে নির্বাচন করুন</option>
                                        @forelse ($categories as $category)
                                            <option @if ($category->id==$karigor_product->converted_category_id)
                                                selected
                                            @endif value="{{ $category->id }}">{{ $category->category_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('converted_category_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
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
                                    <input type="number" class="form-control @error('qty') required
                                    is-invalid
                                @enderror" rows="5" min="1" id="qty" name="qty" value="{{ $karigor_product->qty }}" id="qty">
                                    @error('qty')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-3 col-3">
                                <label for="order_date" class="form-label mb-2">প্রদানের তারিখ</label>
                                <input type="date" class="form-control" name="order_date" id="order_date" value="{{ $karigor_product->order_date }}">
                            </div>

                            <div class="form-group mb-3 col-3">
                                <label for="receive_date" class="form-label mb-2">গ্রহণের তারিখ</label>
                                <input type="date" class="form-control" name="receive_date" id="receive_date" value="{{ $karigor_product->receive_date }}">
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
                                <input type="text" class="form-control" required name="name" value="{{ old('name') }}" id="name">
                            </div>
                            <div class="form-group mb-3 col-6">
                                <label for="phone" class="form-label mb-2">মোবাইল*</label>
                                <input type="text" class="form-control" required name="phone" value="{{ old('phone') }}" id="phone">
                            </div>
                            <div class="form-group mb-3 col-6">
                                <label for="address" class="form-label mb-2">ঠিকানা</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}" id="address">
                            </div>
                            <div class="form-group mb-3 col-6">
                                <label for="details" class="form-label mb-2">ডিটেইলস</label>
                                <input type="text" class="form-control" name="details" value="{{ old('details') }}" id="details">
                            </div>
                        </div>`;
                    $("#karigor").html(karigor);
                } else {
                    $(".custom").removeClass('d-none');
                    $("#karigor").html("");
                }
            });

            // Load selected product for category
            getProducts("{{ $karigor_product->category_id }}", "{{ $karigor_product->product_id }}", 'product');
            getProducts("{{ $karigor_product->converted_category_id }}", "{{ $karigor_product->converted_product_id }}", 'converted_product_id');

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
@endsection

@push('admin_script')
@include('admin.common.script')
@endpush
