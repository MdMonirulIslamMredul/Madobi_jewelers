@extends('admin.master')

@section('title')
শপ বিক্রয় তালিকা ও হিসাব
@endsection

@push('admin_style')
<style>
    .sales-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        vertical-align: middle;
    }
    .sales-table td {
        vertical-align: middle;
    }
    .kpi-card {
        border-radius: 8px;
        transition: transform 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
    }
    .due-amount-badge {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: #ffffff !important;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 5px;
        display: inline-block;
        box-shadow: 0 2px 6px rgba(220, 38, 38, 0.28);
        font-size: 10.5px;
        letter-spacing: 0.2px;
        white-space: nowrap;
    }
</style>
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-12">
        <!-- KPI Header Widgets -->
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="card kpi-card shadow-sm border-0 border-start border-primary border-4">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold">মোট বিক্রয় (Total Sales)</small>
                            <h4 class="mb-0 font-weight-bold text-primary">৳ {{ number_format($totalSalesAmount, 2) }}</h4>
                        </div>
                        <div class="bg-primary-subtle p-3 rounded text-primary">
                            <i class="fa-solid fa-receipt fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card kpi-card shadow-sm border-0 border-start border-success border-4">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold">মোট মুনাফা (Total Profit)</small>
                            <h4 class="mb-0 font-weight-bold text-success">৳ {{ number_format($totalProfitAmount, 2) }}</h4>
                        </div>
                        <div class="bg-success-subtle p-3 rounded text-success">
                            <i class="fa-solid fa-chart-line fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card kpi-card shadow-sm border-0 border-start border-danger border-4">
                    <div class="card-body p-3 d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted font-weight-bold">মোট বকেয়া (Total Due)</small>
                            <h4 class="mb-0 font-weight-bold text-danger">৳ {{ number_format($totalDueAmount, 2) }}</h4>
                        </div>
                        <div class="bg-danger-subtle p-3 rounded text-danger">
                            <i class="fa-solid fa-hand-holding-dollar fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-store text-primary me-2 fa-lg"></i>
                    <h4 class="card-title mb-0 font-weight-bold">শপের বিক্রয় তালিকা ও রিপোর্ট</h4>
                </div>
                <a href="{{ route('instant-sells.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="fa-solid fa-plus me-1"></i> নতুন শপ বিক্রয়
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filters -->
                <form method="GET" action="{{ route('instant-sells.index') }}" class="row g-2 mb-3">
                    <div class="col-md-5">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="ইনভয়েস নং, গ্রাহকের নাম বা ফোন নম্বর..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
                            <option value="">সকল পেমেন্ট স্ট্যাটাস</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>পরিশোধিত (Paid)</option>
                            <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>আংশিক / কিস্তি (Partial)</option>
                            <option value="due" {{ request('payment_status') == 'due' ? 'selected' : '' }}>বকেয়া (Due)</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-sm btn-secondary w-100">ফিল্টার</button>
                    </div>
                    @if(request()->hasAny(['search', 'payment_status']))
                    <div class="col-md-2">
                        <a href="{{ route('instant-sells.index') }}" class="btn btn-sm btn-outline-danger w-100">রিসেট</a>
                    </div>
                    @endif
                </form>

                @if($sells->count())
                <div class="table-responsive">
                    <table class="table table-hover table-bordered sales-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>ইনভয়েস নং ও তারিখ</th>
                                <th>গ্রাহক তথ্য</th>
                                <th class="text-center">পণ্য সংখ্যা</th>
                                <th class="text-end">ক্রয়মূল্য</th>
                                <th class="text-end">বিক্রয়মূল্য</th>
                                <th class="text-end">মুনাফা (Profit)</th>
                                <th class="text-end">পরিশোধ ও বকেয়া</th>
                                <th class="text-center">স্ট্যাটাস</th>
                                <th class="text-center" style="width: 160px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sells as $index => $sell)
                            <tr>
                                <td class="text-center text-muted">{{ $sells->firstItem() + $index }}</td>
                                <td>
                                    <strong class="text-primary">{{ $sell->invoice_no }}</strong>
                                    <small class="text-muted d-block">{{ $sell->created_at->format('d M, Y h:i A') }}</small>
                                </td>
                                <td>
                                    <strong>{{ $sell->customer->name ?? 'N/A' }} {{ $sell->customer->last_name ?? '' }}</strong>
                                    <small class="text-muted d-block"><i class="fa-solid fa-phone me-1"></i>{{ $sell->customer->phone ?? 'N/A' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">{{ $sell->items->count() }} টি</span>
                                </td>
                                <td class="text-end text-muted font-weight-bold">
                                    ৳ {{ number_format($sell->total_cost, 2) }}
                                </td>
                                <td class="text-end font-weight-bold text-dark">
                                    ৳ {{ number_format($sell->grand_total, 2) }}
                                    @if($sell->discount > 0)
                                        <small class="text-danger d-block">-৳ {{ number_format($sell->discount, 2) }} ছাড়</small>
                                    @endif
                                </td>
                                <td class="text-end font-weight-bold {{ $sell->total_profit >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $sell->total_profit >= 0 ? '+' : '' }}৳ {{ number_format($sell->total_profit, 2) }}
                                </td>
                                <td class="text-end">
                                    <div class="text-success font-weight-bold" style="font-size: 12px;">
                                        <i class="fa-solid fa-circle-check me-1"></i>পেইড: ৳ {{ number_format($sell->paid_amount, 2) }}
                                    </div>
                                    @if($sell->due_amount > 0)
                                        <div class="mt-1">
                                            <span class="due-amount-badge" title="বকেয়া টাকা">
                                                <i class="fa-solid fa-triangle-exclamation me-1"></i>বকেয়া: ৳ {{ number_format($sell->due_amount, 2) }}
                                            </span>
                                            @if($sell->due_date)
                                                <small class="text-danger font-weight-bold d-block mt-1" style="font-size: 10px;">
                                                    <i class="fa-regular fa-calendar-xmark me-1"></i>তারিখ: {{ \Carbon\Carbon::parse($sell->due_date)->format('d M, Y') }}
                                                </small>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($sell->payment_status === 'paid')
                                        <span class="badge bg-success-subtle text-success border border-success">পরিশোধিত</span>
                                    @elseif($sell->payment_status === 'partial')
                                        <span class="badge bg-warning-subtle text-warning border border-warning">আংশিক</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger">বকেয়া</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        @if($sell->items->count() === 1)
                                            @php
                                                $firstItem = $sell->items->first();
                                                $purchaseTargetId = $firstItem->purchase->transaction_id ?? $firstItem->purchase->id ?? $firstItem->purchase_id;
                                            @endphp
                                            @if($purchaseTargetId)
                                                <a href="{{ route('purchase.show', $purchaseTargetId) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="ক্রয় বিস্তারিত দেখুন">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            @endif
                                        @elseif($sell->items->count() > 1)
                                            <div class="dropdown d-inline-block">
                                                <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" title="ক্রয় বিস্তারিত দেখুন ({{ $sell->items->count() }} টি পণ্য)">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2" style="min-width: 220px; z-index: 1060;">
                                                    <li class="dropdown-header px-2 py-1 small fw-bold text-dark">পণ্য অনুযায়ী ক্রয় বিস্তারিত:</li>
                                                    @foreach($sell->items as $idx => $sItem)
                                                        @php
                                                            $sPid = $sItem->purchase->transaction_id ?? $sItem->purchase->id ?? $sItem->purchase_id;
                                                        @endphp
                                                        @if($sPid)
                                                            <li>
                                                                <a class="dropdown-item py-1 px-2 rounded small" href="{{ route('purchase.show', $sPid) }}" target="_blank">
                                                                    <i class="fa-solid fa-arrow-up-right-from-square me-1 text-primary"></i>
                                                                    #{{ $sItem->purchase_id }} - {{ $sItem->product_name ?? 'পণ্য ' . ($idx + 1) }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <a href="{{ route('instant-sells.show', $sell->id) }}" class="btn btn-sm btn-outline-primary" title="ইনভয়েস দেখুন ও প্রিন্ট করুন">
                                            <i class="fa-solid fa-file-invoice"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-info" title="পেমেন্ট ইতিহাস ও কিস্তি গ্রহণ" onclick="openPaymentHistoryModal('instant_sell', {{ $sell->id }})">
                                            <i class="fa-solid fa-hand-holding-dollar"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $sells->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fa-solid fa-receipt fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">কোনো বিক্রয় রেকর্ড পাওয়া যায়নি।</h5>
                    <a href="{{ route('instant-sells.create') }}" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fa-solid fa-plus me-1"></i> প্রথম বিক্রয় শুরু করুন
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('admin.sell.payment_history_modal')
@endsection
