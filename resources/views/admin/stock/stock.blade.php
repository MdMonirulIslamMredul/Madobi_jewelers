@extends('admin.master')
@section('title')
স্টক
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3> স্টক</h3>
            </div>
        </div>
        <div class="card-body">
            <table id="config-table" class="table display table-striped border no-wrap">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">ট্রানজ্যাকশন আইডি</th>
                        <th class="text-center">সাপ্লায়ার</th>
                        <th class="text-center">ক্যাটেগরি</th>
                        <th class="text-center">প্রোডাক্ট</th>
                        <th class="text-center">পরিমাণ</th>
                        <th class="text-center">সর্বমোট মূল্য</th>
                        <th class="text-center">মোট প্রদান</th>
                        <th class="text-center">বকেয়া</th>
                        <th class="text-center">একশন্স</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $sl = 1; 
                    @endphp
                    @forelse ($purchases as $purchase)
                    <tr>
                        <td class="text-center">
                            <strong>{{ $sl }}</strong>
                        </td>
                        <td class="text-center">
                            {{ $purchase->transaction_id ? 'TR-'.$purchase->transaction_id : ($purchase->invoice_id ? 'INV-'.$purchase->invoice_id : 'N/A') }}
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-start">
                                <div>
                                    <img alt="avatar" src="{{ asset('user/' . ($purchase->user->image ?? 'default.png')) }}" class="rounded-circle" style="width:40px; height: 40px">
                                </div>
                                <div>
                                    {{ $purchase->user->name ?? 'N/A' }}<br><a href="tel:{{ $purchase->user->phone ?? '' }}">{{ $purchase->user->phone ?? 'N/A' }}</a>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $purchase->productCategory->category_name ?? 0}} </span>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $purchase->product->product_name ?? 0}}</span> 
                        </td>
                        <td class="text-center">
                            <span class="badge bg-info">{{ $purchase->bhori ?? 0}} ভরি, {{ $purchase->ana ?? 0}} আনা, {{ $purchase->roti ?? 0}} রতি, {{ $purchase->point ?? 0}} পয়েন্ট</span> <span class="badge bg-danger">({{$purchase->gram ?? 0}} গ্রাম )</span>
                        </td>

                        <td class="text-center">{{ $purchase->transaction->total_price ?? ($purchase->invoice->total_price ?? 0) }}</td>
                        <td class="text-center">{{ $purchase->transaction->total_payment ?? ($purchase->invoice->total_payment ?? 0) }}</td>
                        <td class="text-center">{{ $purchase->transaction->due_payment ?? ($purchase->invoice->due_payment ?? 0) }}</td>
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
                                    <a href="{{ $purchase->transaction_id ? route('purchase.edit', $purchase->transaction_id) : '#' }}"
                                        class="text-info" data-toggle="tooltip"
                                        data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    </a>
                                </div>
                                <div>
                                    <form action="{{route('stock.delete')}}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
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
                    <tr>
                        <td colspan="10" class="text-center">No Data Found!</td>
                    </tr>
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