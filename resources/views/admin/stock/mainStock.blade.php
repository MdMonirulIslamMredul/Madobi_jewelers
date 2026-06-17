@extends('admin.master')
@section('title')
টোটাল স্টক
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3>টোটাল স্টক</h3>
                    </div>
                    <div>
                        <a href="{{route('stock.create')}}" class="btn btn-dark">নতুন স্টক তৈরি করুন</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>ক্যাটেগরি</th>
                            <th>প্রোডাক্ট</th>
                            <th>পরিমাণ</th>
                            <th>ক্যারেট</th>
                            <th>ভরি</th>
                            <th>আনা</th>
                            <th>রতি</th>
                            <th>পয়েন্ট</th>
                            <th>গ্রাম হিসাব</th>
                            <th>লোকেশন</th>
                            <th>একশন্স</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp
                        @forelse ($stocks as $stock)
                        <tr>
                            <td>
                                <strong>{{ $sl }}</strong>
                            </td>
                            <td>{{ $stock->productCategory->category_name ?? 'N/A' }}</td>
                            <td>{{ $stock->product->product_name ?? 'N/A' }}</td>
                            <td>{{ $stock->qty }}</td>
                            <td>{{ $stock->karat }}</td>
                            <td>{{ $stock->bhori }}</td>
                            <td>{{ $stock->ana }}</td>
                            <td>{{ $stock->roti }}</td>
                            <td>{{ $stock->point }}</td>
                            <td>{{ $stock->gram }}</td>
                            <td>
                                @if ($stock->location == 'is_shop')
                                <span class="badge bg-success"><a href="{{route('shop.stock')}}" class="text-light">দোকান</a></span>
                                @elseif($stock->location == 'is_warehouse')
                                <span class="badge bg-danger"><a href="{{route('warehouse.stock')}}" class="text-light">গুদাম</a></span>
                                @endif
                            </td>
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
                                        <a href="{{route('stock.edit',$stock->id)}}"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <form action="{{route('stock.delete')}}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="stock_id" value="{{ $stock->id }}">
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
@endpush
