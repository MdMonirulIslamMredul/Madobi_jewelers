@extends('admin.master')
@section('title')
কারিগর প্রোডাক্ট
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
                        @include('admin.user.form')
                    </div>
                    <form id="form2" class="form-horizontal" action="{{ route('karigor.product.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="status" value="on-process">
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
                            {{-- <div class="col-lg-3">
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
                            </div> --}}
                            
                            <div class="col-lg-2">
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
                            <div class="col-lg-2">
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
                            <div class="col-lg-2">
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
                            <div class="col-lg-6">
                                <div class="form-group mb-3">
                                    <label for="converted_category_id" class="form-label mb-2">রূপান্তরিত প্রোডাক্টের ক্যাটেগরি*</label>
                                    <select id="converted_category_id" name="converted_category_id" required
                                        class="form-select select2
                                    @error('converted_category_id')
                                        is-invalid
                                    @enderror">
                                        <option selected>এখানে নির্বাচন করুন</option>
                                        @forelse ($converted_categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
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
                        @php $sl = 1; @endphp
                        @forelse ($karigor_products as $kp)
                        <tr>
                            <td>
                                <strong>{{ $sl }}</strong>
                            </td>
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    <div>
                                        <form action="#"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="kp_id" value="{{ $kp->id }}">
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
                                        <a href="{{ route('karigor.product.edit', $kp->id) }}"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div style="margin-left: 2px;">
                                        <form class="form-horizontal" action="{{ route('karigor.product.status.update') }}" method="POST">
                                            @csrf
                                                <input type="hidden" name="kp_id" value="{{ $kp->id }}">
                                                <div class="input-group">
                                                    <div class="input-group-append" style="cursor: pointer">
                                                        @if ($kp->status=="on-process")
                                                        <input type="hidden" name="status" value="received">
                                                        <button type="submit" class="btn btn-sm btn-outline-success show_update" data-toggle="tooltip"
                                                        data-placement="top" data-bs-original-title="Change Status">
                                                        ✔</button> 

                                                        @else
                                                        <input type="hidden" name="status" value="on-process">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger show_update" data-toggle="tooltip"
                                                        data-placement="top" data-bs-original-title="Change Status">
                                                        ✗</button> 
                                                        
                                                        @endif                                      
                                                     </div>
                                                </div>
                                        </form>
                                    </div>
                                   
                                </div>
                            </td>
                            <td>
                                @if ($kp->status=='on-process')
                                    <span class="badge bg-danger">{{ $kp->status }}</span>
                                @else
                                    <span class="badge bg-success">{{ $kp->status }}</span>
                                @endif
                            </td>
                            <td><img alt="avatar"
                                src="{{ asset('user/' . $kp->user->image) }}"
                                class="rounded-circle" style="width:30px; height: 30px"> {{ $kp->user->name }}-<a href="#">{{$kp->user->phone}}</a>
                            </td>                            
                            <td>{{ $kp->productCategory->category_name ?? 'N/A'}}</td>
                            <td>{{ $kp->product->product_name ?? "N/A"}}</td>
                            <td>{{ $kp->bhori ?? 0}} ভরি, {{ $kp->ana ?? 0}} আনা, {{ $kp->roti ?? 0}} রতি , {{ $kp->point ?? 0 }} পয়েন্ট</td>
                            <td>{{ $kp->ConvertedproductCategory->category_name ?? "N/A"}}</td>
                            <td>{{ $kp->Convertedproduct->product_name ?? "N/A"}}</td>
                            <td>{{ $kp->order_date }}</td>
                            <td>{{ $kp->receive_date }}</td>
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
@push('admin_script')
@include('admin.common.script')
@endpush

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



@endsection


