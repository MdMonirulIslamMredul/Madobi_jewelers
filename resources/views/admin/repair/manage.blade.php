@extends('admin.master')
@section('title')
রিপেয়ার টেবিল
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
                        <h3>রিপেয়ার টেবিল</h3>
                    </div>
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
                            <th>একশন্স</th>
                            <th>স্ট্যাটাস</th>
                            <th>অর্ডারের তারিখ</th>
                            <th>কাস্টমার</th>
                            <th>ক্যাটেগরি</th>
                            <th>প্রোডাক্ট</th>
                            <th>পরিমাণ</th>
                            <th>সর্বমোট মূল্য</th>
                            <th>মোট প্রদান</th>
                            <th>বকেয়া</th>
                            
                           
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp
                        @forelse ($repairs as $repair)
                        <tr>
                            <td>
                                <strong>{{ $sl }}</strong>
                            </td>
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
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
                                   
                                    <div style="margin-left: 2px;">
                                        <form class="form-horizontal" action="{{ route('karigor.product.status.update') }}" method="POST">
                                            @csrf
                                                <input type="hidden" name="repair_id" value="{{ $repair->id }}">
                                                <div class="input-group">
                                                    <div class="input-group-append" style="cursor: pointer">
                                                        @if ($repair->status=="on-process")
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
                                @if ($repair->status=='on-process')
                                    <span class="badge bg-danger">{{ $repair->status }}</span>
                                @else
                                    <span class="badge bg-success">{{ $repair->status }}</span>
                                @endif
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
    // For Create
        // To Get Product Data
        const getProducts = (category_id, selected = null) => {
            axios.get(`${window.location.origin}/get-products/${category_id}`).then(res => {
                let products = res.data
                let element = $('#product_id')
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

        $('#category_id').on('change', function() {
            getProducts($(this).val())
        })
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
