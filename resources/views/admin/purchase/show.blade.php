@extends('admin.master')
@section('title')
ক্রয় বিস্তারিত
@endsection
@push('admin_style')
@include('admin.common.style')
<style>
    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #212529;
    }
    .purchase-item-card {
        border: 1px solid #dee2e6;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,.07);
        margin-bottom: 1.5rem;
        background: #fff;
    }
    .purchase-item-card .item-card-header {
        background: linear-gradient(135deg, #343a40 0%, #495057 100%);
        color: #fff;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
    }
    .gold-badge {
        background: linear-gradient(135deg, #b8860b, #ffd700);
        color: #fff;
        border-radius: 6px;
        padding: 4px 10px;
        font-weight: 700;
        font-size: 0.9rem;
        display: inline-block;
        margin-bottom: 4px;
    }
    .section-divider {
        border-top: 2px solid #e9ecef;
        margin: 1.5rem 0;
    }
    .summary-box {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        color: #fff;
        border-radius: 12px;
        padding: 1.5rem;
    }
    .summary-box .s-label {
        font-size: 0.8rem;
        color: #adb5bd;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .summary-box .s-value {
        font-size: 1.4rem;
        font-weight: 700;
        color: #ffd700;
    }
    .purchase-photo {
        width: 100%;
        max-height: 150px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #dee2e6;
    }
    .badge-karat {
        background: #6f42c1;
        color: #fff;
        padding: 3px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }
</style>
@endpush
@section('body')

<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card">

            {{-- Header with Edit button --}}
            <div class="card-header py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold">ক্রয় বিস্তারিত</h4>
                        <small class="text-muted">Transaction #{{ $transaction->id }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('purchase.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left me-1"></i> তালিকায় ফিরুন
                        </a>
                        <a href="{{ route('purchase.edit', $transaction->id) }}" class="btn btn-warning btn-sm">
                            <i class="fa-solid fa-pen-to-square me-1"></i> সম্পাদনা করুন
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">

                {{-- Supplier Info --}}
                <div class="row mb-4 align-items-center">
                    <div class="col-auto">
                        <img src="{{ asset('user/' . $transaction->user->image) }}"
                             class="rounded-circle shadow"
                             style="width:70px;height:70px;object-fit:cover;border:3px solid #ffd700;">
                    </div>
                    <div class="col">
                        <div class="info-label">সাপ্লায়ার</div>
                        <div class="info-value fs-5">{{ $transaction->user->name }} {{ $transaction->user->last_name ?? '' }}</div>
                        <div class="text-muted"><i class="fa-solid fa-phone fa-sm me-1"></i>{{ $transaction->user->phone }}</div>
                        @if($transaction->user->role)
                            <span class="badge bg-secondary mt-1">{{ $transaction->user->role->role_name }}</span>
                        @endif
                    </div>
                    <div class="col-auto text-end">
                        @php $firstPurchase = $transaction->purchases->first(); @endphp
                        @if($firstPurchase?->order_date)
                        <div class="info-label">ক্রয়ের তারিখ</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($firstPurchase->order_date)->format('d M Y') }}</div>
                        @endif
                        @if($firstPurchase?->due_payment_date)
                        <div class="info-label mt-2">বকেয়া পরিশোধের তারিখ</div>
                        <div class="info-value text-danger">{{ \Carbon\Carbon::parse($firstPurchase->due_payment_date)->format('d M Y') }}</div>
                        @endif
                    </div>
                </div>

                <div class="section-divider"></div>

                {{-- Purchase Items --}}
                <h5 class="fw-bold mb-3">
                    <i class="fa-solid fa-boxes-stacked me-2 text-warning"></i>ক্রয়কৃত পণ্য সমূহ
                    <span class="badge bg-dark ms-2">{{ $transaction->purchases->count() }} টি</span>
                </h5>

                @foreach ($transaction->purchases as $index => $purchase)
                <div class="purchase-item-card">
                    <div class="item-card-header d-flex justify-content-between align-items-center">
                        <span class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-warning text-dark border">ক্রয় আইডি: #{{ $purchase->id }}</span>
                            <span>
                                QTY {{ $index + 1 }}
                                &mdash;
                                {{ $purchase->productCategory->category_name ?? '—' }}
                                /
                                {{ $purchase->product->product_name ?? '—' }}
                            </span>
                        </span>
                        <span class="badge-karat">{{ $purchase->karat }}</span>
                    </div>
                    <div class="card-body">
                        <div class="row g-3 align-items-start">

                            {{-- Photo --}}
                            <div class="col-lg-2 col-md-3 col-6">
                                <img src="{{ ($purchase->photo && $purchase->photo !== 'default-cover.jpg') ? asset('user/purchase/' . $purchase->photo) : asset('cover/default-cover.jpg') }}"
                                     class="purchase-photo" alt="product image">
                            </div>

                            {{-- Gold Measurements --}}
                            <div class="col-lg-5 col-md-9">
                                <div class="info-label mb-2">স্বর্ণের পরিমাণ</div>
                                <div class="mb-3">
                                    <span class="gold-badge me-1">{{ $purchase->bhori ?? 0 }} ভরি</span>
                                    <span class="gold-badge me-1">{{ $purchase->ana ?? 0 }} আনা</span>
                                    <span class="gold-badge me-1">{{ $purchase->roti ?? 0 }} রতি</span>
                                    <span class="gold-badge">{{ $purchase->point ?? 0 }} পয়েন্ট</span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="info-label">গ্রাম হিসাব</div>
                                        <div class="info-value">{{ $purchase->gram ?? '—' }} গ্রাম</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-label">পাকা সোনা</div>
                                        <div class="info-value">{{ $purchase->raw_gold ?? '—' }} গ্রাম</div>
                                    </div>
                                </div>
                                @if($purchase->details)
                                <div class="mt-3">
                                    <div class="info-label">ডিটেইলস</div>
                                    <div class="info-value text-muted" style="font-size:0.9rem;">{{ $purchase->details }}</div>
                                </div>
                                @endif
                            </div>

                            {{-- Pricing --}}
                            <div class="col-lg-5 col-md-12">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="info-label">একক মূল্য/ভরি</div>
                                        <div class="info-value">৳ {{ number_format($purchase->unit_price ?? 0, 2) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-label">এক গ্রামের মূল্য</div>
                                        <div class="info-value">৳ {{ number_format($purchase->one_gram_price ?? 0, 3) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-label">ক্রয় মূল্য</div>
                                        <div class="info-value text-primary fw-bold">৳ {{ number_format($purchase->total_price ?? 0, 2) }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="info-label">আসল মূল্য</div>
                                        <div class="info-value text-success fw-bold">৳ {{ number_format($purchase->actual_price ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Product Location History Timeline --}}
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold text-primary mb-3">
                                <i class="fa-solid fa-clock-rotate-left me-2"></i>অবস্থান পরিবর্তনের ইতিহাস (Location History)
                            </h6>
                            @if($purchase->locationHistories && count($purchase->locationHistories) > 0)
                                <div class="position-relative ps-4 ms-2 mt-2" style="border-left: 2px dashed #0d6efd;">
                                    @foreach($purchase->locationHistories as $history)
                                        <div class="position-relative mb-3">
                                            <div class="position-absolute bg-primary rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                                                 style="width: 24px; height: 24px; left: -29px; top: 2px;">
                                                <i class="fa-solid fa-location-dot fa-xs"></i>
                                            </div>
                                            <div class="card border-0 bg-light p-3 shadow-sm" style="border-radius:8px;">
                                                <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-1">
                                                    <span class="badge bg-white text-dark border p-2">
                                                        {{ str_replace(['is_hold', 'is_karigor', 'is_shop', 'is_warehouse'], ['Hold (হোল্ড)', 'Karigor (কারিগর)', 'Shop (শপ)', 'Warehouse (গুদাম)'], $history->from_location ?: 'নতুন ক্রয়') }}
                                                        &nbsp;<i class="fa-solid fa-arrow-right text-primary"></i>&nbsp;
                                                        <strong class="text-primary">{{ str_replace(['is_hold', 'is_karigor', 'is_shop', 'is_warehouse'], ['Hold (হোল্ড)', 'Karigor (কারিগর)', 'Shop (শপ)', 'Warehouse (গুদাম)'], $history->to_location) }}</strong>
                                                    </span>
                                                    <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $history->created_at ? $history->created_at->format('d M Y, h:i A') : '—' }}</small>
                                                </div>

                                                @if($history->karigor)
                                                    <div class="small text-dark mt-1">
                                                        👤 <strong>অর্পিত কারিগর:</strong> {{ $history->karigor->name }} {{ $history->karigor->last_name ?? '' }} (📱 {{ $history->karigor->phone ?? '—' }})
                                                    </div>
                                                @endif

                                                @if($history->task_type)
                                                    <div class="small text-dark mt-1">
                                                        🛠️ <strong>কাজের ধরন:</strong> {{ $history->task_type }}
                                                        @if($history->extra_raw_gold)
                                                            | 🪙 <strong>অতিরিক্ত র গোল্ড:</strong> {{ $history->extra_raw_gold }} গ্রাম
                                                        @endif
                                                    </div>
                                                @endif

                                                <div class="small text-muted mt-1">
                                                    <i class="fa-solid fa-user-check me-1"></i>স্থানান্তরকারী: <strong>{{ $history->transferredBy->name ?? 'System' }}</strong>
                                                    @if($history->note)
                                                        &nbsp;|&nbsp; 📝 {{ $history->note }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-muted small py-1">
                                    <i class="fa-solid fa-info-circle me-1"></i>কোন অবস্থান হিস্ট্রি পাওয়া যায়নি।
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
                @endforeach

                <div class="section-divider"></div>

                {{-- Payment Summary --}}
                <h5 class="fw-bold mb-3">
                    <i class="fa-solid fa-money-bill-wave me-2 text-success"></i>পেমেন্ট সারসংক্ষেপ
                </h5>
                <div class="summary-box">
                    <div class="row g-4 text-center">
                        <div class="col-6 col-md-3">
                            <div class="s-label">সর্বমোট মূল্য</div>
                            <div class="s-value">৳ {{ number_format($transaction->total_price ?? 0, 2) }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="s-label">অগ্রিম প্রদান</div>
                            <div class="s-value" style="color:#90ee90;">৳ {{ number_format($transaction->adv_payment ?? 0, 2) }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="s-label">মোট প্রদান</div>
                            <div class="s-value" style="color:#87ceeb;">৳ {{ number_format($transaction->total_payment ?? 0, 2) }}</div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="s-label">বকেয়া</div>
                            <div class="s-value" style="color:#ff6b6b;">৳ {{ number_format($transaction->due_payment ?? 0, 2) }}</div>
                        </div>
                    </div>
                </div>

            </div>{{-- /card-body --}}
        </div>{{-- /card --}}
    </div>
</div>

@endsection

@push('admin_script')
@include('admin.common.script')
@endpush
