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
                        $groupedPurchases = $purchases->groupBy(function($item) {
                            return $item->transaction_id ? 't_'.$item->transaction_id : 'i_'.$item->invoice_id;
                        });
                        $sl = 1; 
                    @endphp
                    @forelse ($groupedPurchases as $groupId => $group)
                    @php $firstPurchase = $group->first(); @endphp
                    <tr>
                        <td class="text-center">
                            <strong>{{ $sl }}</strong>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-start">
                                <div>
                                    <img alt="avatar" src="{{ asset('user/' . ($firstPurchase->user->image ?? 'default.png')) }}" class="rounded-circle" style="width:40px; height: 40px">
                                </div>
                                <div>
                                    {{ $firstPurchase->user->name ?? 'N/A' }}<br><a href="tel:{{ $firstPurchase->user->phone ?? '' }}">{{ $firstPurchase->user->phone ?? 'N/A' }}</a>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            @foreach($group as $purchase)
                            <span class="badge bg-primary">{{ $purchase->productCategory->category_name ?? 0}} </span><br>
                            @endforeach
                        </td>
                        <td class="text-center">
                            @foreach($group as $purchase)
                            <span class="badge bg-info">{{ $purchase->product->product_name ?? 0}}</span> <br>
                            @endforeach
                        </td>
                        <td class="text-center">
                            @foreach($group as $purchase)
                            <span class="badge bg-info">{{ $purchase->bhori ?? 0}} ভরি, {{ $purchase->ana ?? 0}} আনা, {{ $purchase->roti ?? 0}} রতি, {{ $purchase->point ?? 0}} পয়েন্ট</span> <span class="badge bg-danger">({{$purchase->gram ?? 0}} গ্রাম )</span><br>
                            @endforeach
                        </td>

                        <td class="text-center">{{ $firstPurchase->transaction->total_price ?? ($firstPurchase->invoice->total_price ?? 0) }}</td>
                        <td class="text-center">{{ $firstPurchase->transaction->total_payment ?? ($firstPurchase->invoice->total_payment ?? 0) }}</td>
                        <td class="text-center">{{ $firstPurchase->transaction->due_payment ?? ($firstPurchase->invoice->due_payment ?? 0) }}</td>
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
                                    <a href="{{ $firstPurchase->transaction_id ? route('purchase.edit', $firstPurchase->transaction_id) : '#' }}"
                                        class="text-info" data-toggle="tooltip"
                                        data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    </a>
                                </div>
                                <div>
                                    <form action="{{route('stock.delete')}}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="purchase_id" value="{{ $firstPurchase->id }}">
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
                        <td colspan="9" class="text-center">No Data Found!</td>
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