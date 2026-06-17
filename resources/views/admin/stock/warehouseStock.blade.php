@extends('admin.master')
@section('title')
গুদাম স্টক
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>গুদাম স্টক</h3>
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
                            <th>একশন্স</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp
                        @forelse ($warehouses as $warehouse)
                        <tr>
                            <td>
                                <strong>{{ $sl }}</strong>
                            </td>
                            <td>{{ $warehouse->productCategory->category_name ?? 'N/A' }}</td>
                            <td>{{ $warehouse->product->product_name ?? 'N/A' }}</td>
                            <td>{{ $warehouse->qty }}</td>
                            <td>{{ $warehouse->karat }}</td>
                            <td>{{ $warehouse->bhori }}</td>
                            <td>{{ $warehouse->ana }}</td>
                            <td>{{ $warehouse->roti }}</td>
                            <td>{{ $warehouse->point }}</td>
                            <td>{{ $warehouse->gram }}</td>
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
                                        <form action="{{route('stock.delete')}}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="warehouse_id" value="{{ $warehouse->id }}">
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
