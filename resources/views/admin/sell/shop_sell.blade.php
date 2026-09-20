@extends('admin.master')

@section('title')
শপের পণ্য বিক্রয় (Shop Sell)
@endsection

@push('admin_style')
<style>
    .product-select-table th, .cart-table th {
        background-color: #f1f5f9;
        color: #0f172a;
        font-weight: 700;
        font-size: 13.5px;
        vertical-align: middle;
        border-bottom: 2px solid #cbd5e1;
    }
    .product-select-table td, .cart-table td {
        vertical-align: middle;
        font-size: 13.5px;
        color: #0f172a !important;
    }
    .cost-price-badge {
        background-color: #f8fafc;
        color: #0f172a !important;
        border: 1px solid #cbd5e1;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        font-size: 13.5px;
        letter-spacing: 0.3px;
    }
    .purchase-id-badge {
        background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
        color: #1a1a1a !important;
        border: 1.5px solid #ffffff;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.18);
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        font-size: 13px;
        white-space: nowrap;
        letter-spacing: 0.2px;
    }
    .add-item-btn {
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 5px;
    }
    .profit-positive {
        color: #15803d;
        background-color: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 3px 8px;
        border-radius: 4px;
        font-weight: 600;
    }
    .profit-negative {
        color: #b91c1c;
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        padding: 3px 8px;
        border-radius: 4px;
        font-weight: 600;
    }
    .summary-card {
        background-color: #ffffff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px dashed #e2e8f0;
    }
    .summary-row:last-child {
        border-bottom: none;
    }

    /* =========================================================
       Invoice Preview Sheet & Modal Styles
       ========================================================= */
    .invoice-preview-sheet {
        background: #ffffff;
        width: 100%;
        max-width: 820px;
        margin: 0 auto;
        padding: 24px 28px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.08);
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        box-sizing: border-box;
        color: #0f172a;
        font-family: 'Outfit', 'Hind Siliguri', sans-serif;
        font-size: 11.5px;
        line-height: 1.4;
    }
    .preview-header-brand { text-align: center; margin-bottom: 6px; }
    .preview-logo-img { max-height: 52px; width: auto; object-fit: contain; margin-bottom: 2px; }
    .preview-shop-title {
        font-family: 'Cinzel', serif;
        font-size: 22px;
        font-weight: 800;
        letter-spacing: 2px;
        color: #0f172a;
        margin: 0;
        line-height: 1.15;
    }
    .preview-shop-subtitle-en {
        font-family: 'Cinzel', serif;
        font-size: 10px;
        letter-spacing: 3px;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
    }
    .preview-shop-subtitle-bn { font-size: 11px; color: #334155; margin-top: 1px; }
    .preview-double-divider {
        border: none;
        border-top: 1.5px solid #0f172a;
        border-bottom: 1px solid #0f172a;
        height: 4px;
        margin: 6px 0 8px;
    }
    .preview-title-block { text-align: center; margin-bottom: 8px; }
    .preview-heading-bn { font-size: 13px; font-weight: 700; color: #0f172a; margin: 0; }
    .preview-heading-en {
        font-family: 'Outfit', sans-serif;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        color: #0f172a;
        margin: 0;
    }
    .preview-tax-reg { font-size: 9.5px; font-weight: 600; color: #475569; margin-top: 1px; }
    .preview-info-grid { display: flex; gap: 12px; margin-bottom: 8px; }
    .preview-rates-card { flex: 1.05; border: 1.2px solid #0f172a; background: #fff; }
    .preview-meta-card { flex: 1.15; border: 1.2px solid #0f172a; background: #fff; }
    .preview-rate-table, .preview-meta-table { width: 100%; border-collapse: collapse; font-size: 10.5px; }
    .preview-rate-table td, .preview-meta-table td { padding: 3px 8px; border-bottom: 1px solid #cbd5e1; }
    .preview-rate-table tr:last-child td, .preview-meta-table tr:last-child td { border-bottom: none; }
    .preview-customer-section {
        border-top: 1px solid #94a3b8;
        border-bottom: 1px solid #94a3b8;
        padding: 5px 6px;
        margin-bottom: 8px;
        font-size: 11px;
    }
    .preview-customer-row { display: flex; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
    .preview-items-table { width: 100%; border-collapse: collapse; margin-bottom: 6px; font-size: 10.5px; }
    .preview-items-table th {
        background-color: #f8fafc;
        color: #0f172a;
        font-weight: 700;
        border: 1px solid #0f172a;
        padding: 4px 6px;
        text-align: center;
    }
    .preview-items-table td {
        border: 1px solid #475569;
        padding: 4px 6px;
        vertical-align: middle;
        color: #0f172a;
    }
    .preview-items-table tfoot td {
        font-weight: 700;
        background-color: #f8fafc;
        border: 1px solid #0f172a;
        padding: 4px 6px;
    }
    .preview-bottom-grid { display: flex; gap: 14px; margin-top: 6px; align-items: flex-start; }
    .preview-settlement-side { flex: 1.2; font-size: 10.5px; line-height: 1.6; }
    .preview-totals-side { flex: 1; }
    .preview-totals-table { width: 100%; border-collapse: collapse; font-size: 11px; border: 1.2px solid #0f172a; }
    .preview-totals-table td { padding: 3.5px 8px; border-bottom: 1px solid #cbd5e1; }
    .preview-totals-table tr:last-child td { border-bottom: none; }
    .preview-totals-grand { background-color: #f1f5f9; font-weight: 800; font-size: 12px; border-top: 1.5px solid #0f172a !important; }
    .preview-stamp-container { display: flex; align-items: center; gap: 12px; margin-top: 8px; }
    .preview-stamp-box {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px solid;
        padding: 2px 10px;
        border-radius: 4px;
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }
    .stamp-paid { color: #059669; border-color: #059669; transform: rotate(-5deg); }
    .stamp-partial { color: #d97706; border-color: #d97706; transform: rotate(-5deg); }
    .stamp-due { color: #dc2626; border-color: #dc2626; transform: rotate(-6deg); }
    .preview-stamp-circle {
        width: 48px;
        height: 48px;
        border: 1.8px dashed #0284c7;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #0284c7;
        transform: rotate(8deg);
        font-size: 7px;
        font-weight: 700;
        text-align: center;
    }
    .preview-words-box {
        margin: 4px 0 8px;
        padding: 4px 8px;
        background: #f8fafc;
        border: 1px dashed #94a3b8;
        font-size: 10px;
        line-height: 1.4;
    }
    .preview-signatures {
        display: flex;
        justify-content: space-between;
        margin-top: 24px;
        padding-top: 6px;
        text-align: center;
    }
    .preview-sig-col { flex: 1; padding: 0 10px; }
    .preview-sig-line { border-top: 1.2px solid #0f172a; margin-bottom: 3px; }
    .preview-sig-title { font-size: 10px; font-weight: 700; color: #0f172a; }
</style>
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="font-weight-bold text-dark mb-0">
                <i class="fa-solid fa-cash-register text-primary me-2"></i>শপের পণ্য সরাসরি বিক্রয় (Shop POS)
            </h4>
            <a href="{{ route('instant-sells.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-list me-1"></i> বিক্রয় তালিকা
            </a>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form action="{{ route('instant-sells.store') }}" method="POST" id="shopSellForm" novalidate>
            @csrf

            <!-- Step 1: Customer Picker -->
            @include('admin.common.customer_picker')

            <!-- Today's Product Prices (Live Gold Rates Bar - Compact Design) -->
            @php
                $productPrices = $productPrices ?? \App\Models\ProductPrice::all();
                $karigorMojuris = $karigorMojuris ?? \App\Models\KarigorMojuri::all();
            @endphp
            <div class="card shadow-sm border-0 mb-3 bg-white">
                <div class="card-header bg-white py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning text-dark border border-warning me-2 py-1 px-2 font-weight-bold">
                            <i class="fa-solid fa-chart-line text-dark me-1"></i> লাইভ রেট
                        </span>
                        <strong class="text-dark small mb-0">আজকের স্বর্ণ ও ধাতুর বাজার দর (Today,s Gold & Metal Rates)</strong>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <small class="text-muted" style="font-size: 11px;">
                            <i class="fa-solid fa-clock me-1"></i>হালনাগাদ: {{ \Carbon\Carbon::now()->format('d M, Y') }}
                        </small>
                        <a href="{{ route('product-price.index') }}" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 11px;">
                            <i class="fa-solid fa-pen-to-square me-1"></i>রেট পরিবর্তন
                        </a>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="row g-2">
                        @forelse($productPrices as $pPrice)
                            <div class="col-6 col-md-3">
                                <div class="p-2 rounded border bg-light h-100 position-relative">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="font-weight-bold text-dark" style="font-size: 12.5px;">
                                            <i class="fa-solid fa-gem text-warning me-1"></i>{{ $pPrice->product_name }}
                                        </span>
                                        <span class="badge bg-white text-primary border font-weight-bold" style="font-size: 10px;">বিক্রয় রেট</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-baseline mb-1">
                                        <span class="text-muted" style="font-size: 11px;">প্রতি ভরি:</span>
                                        <strong class="text-dark" style="font-size: 12.5px;">৳ {{ number_format($pPrice->selling_price, 2) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <span class="text-muted" style="font-size: 11px;">প্রতি গ্রাম:</span>
                                        <strong class="text-primary font-weight-bold" style="font-size: 12.5px;">৳ {{ number_format($pPrice->selling_price_per_gram, 2) }}</strong>
                                    </div>
                                    @if($pPrice->buying_price > 0)
                                    <div class="d-flex justify-content-between align-items-center pt-1 mt-1 border-top" style="font-size: 9.5px;">
                                        <span class="text-muted">ক্রয়: ৳ {{ number_format($pPrice->buying_price_per_gram, 1) }}/g</span>
                                        <span class="text-muted">৳ {{ number_format($pPrice->buying_price, 0) }}/ভরি</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-2 text-muted small">
                                কোনো প্রোডাক্ট প্রাইস পাওয়া যায়নি।
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column: Available Shop Products & Cart -->
                <div class="col-lg-8">
                    <!-- Available In-Stock Items Accordion / Card -->
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold text-dark">
                                <i class="fa-solid fa-store text-info me-2"></i>শপে মজুত পণ্য তালিকা (<span id="available_count">{{ $shopProducts->count() }}</span> টি)
                            </h6>
                            <input type="text" id="search_shop_products" class="form-control form-control-sm w-50" placeholder="মজুত পণ্য খুঁজুন...">
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 280px; overflow-y: auto;">
                                <table class="table table-hover table-sm product-select-table mb-0">
                                    <thead class="sticky-top bg-light">
                                        <tr>
                                            <th class="text-center" style="min-width: 125px;">ক্রয় আইডি</th>
                                            <th>ক্যাটাগরি ও পণ্য</th>
                                            <th class="text-center">ক্যারেট</th>
                                            <th class="text-center">ওজন</th>
                                            <th class="text-end">ক্রয়মূল্য (৳)</th>
                                            <th class="text-center" style="width: 115px;">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody id="shop_products_tbody">
                                        @forelse($shopProducts as $product)
                                        <tr id="available_tr_{{ $product->id }}" 
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->product->product_name ?? 'N/A' }}"
                                            data-cat="{{ $product->productCategory->category_name ?? 'N/A' }}"
                                            data-karat="{{ $product->karat }}"
                                            data-bhori="{{ $product->bhori ?? 0 }}"
                                            data-ana="{{ $product->ana ?? 0 }}"
                                            data-roti="{{ $product->roti ?? 0 }}"
                                            data-point="{{ $product->point ?? 0 }}"
                                            data-gram="{{ $product->gram > 0 ? $product->gram : (($product->bhori ?? 0) * 11.664) }}"
                                            data-cost="{{ (float)($product->total_price ?? 0) }}"
                                            data-actual-price="{{ (float)($product->actual_price ?? 0) }}"
                                            data-default-price="{{ (float)(($product->actual_price && $product->actual_price > 0) ? $product->actual_price : $product->total_price) }}">
                                            <td class="text-center">
                                                <span class="purchase-id-badge">ক্রয় আইডি: #{{ $product->id }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-dark">{{ $product->product->product_name ?? 'N/A' }}</strong>
                                                <span class="text-muted small d-block">{{ $product->productCategory->category_name ?? '-' }}</span>
                                            </td>
                                            <td class="text-center"><span class="badge bg-light text-dark border font-weight-bold">{{ $product->karat }}</span></td>
                                            <td class="text-center small text-dark">
                                                {{ $product->bhori ?? 0 }} ভরি, {{ $product->ana ?? 0 }} আনা, {{ $product->roti ?? 0 }} রতি, {{ $product->point ?? 0 }} পয়েন্ট
                                                <span class="text-muted d-block font-weight-bold">({{ number_format($product->gram, 3) }} গ্রাম)</span>
                                            </td>
                                            <td class="text-end">
                                                <span class="cost-price-badge">৳ {{ number_format($product->total_price, 2) }}</span>
                                                @if($product->actual_price > 0)
                                                    <small class="d-block text-success font-weight-bold" style="font-size: 10.5px;">ডিফল্ট: ৳ {{ number_format($product->actual_price, 2) }}</small>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-inline-flex gap-1">
                                                    <a href="{{ route('purchase.show', $product->transaction_id ?? $product->id) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="ক্রয় বিস্তারিত দেখুন">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-primary add-item-btn" data-id="{{ $product->id }}" title="বিক্রয় তালিকায় যোগ করুন">
                                                        <i class="fa-solid fa-plus me-1"></i> যোগ
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4 text-muted">
                                                শপে বর্তমানে কোনো পণ্য মজুত নেই।
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Cart Table -->
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 font-weight-bold text-success">
                                <i class="fa-solid fa-cart-shopping me-2"></i>বিক্রয়ের জন্য নির্বাচিত পণ্য (Cart)
                            </h6>
                            <span class="badge bg-success" id="cart_badge">০ টি পণ্য</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered cart-table mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 35px;" class="text-center">#</th>
                                            <th>পণ্যের বিবরণ</th>
                                            <th class="text-center" style="width: 120px;">ওজন</th>
                                            <th class="text-end" style="width: 120px;">ক্রয়মূল্য (Cost)</th>
                                            <th style="width: 185px;">রেট নির্বাচন (Rate)</th>
                                            <th class="text-end" style="width: 160px;">বিক্রয়মূল্য (Sell Price) <span class="text-danger">*</span></th>
                                            <th class="text-center" style="width: 120px;">লাভ / ক্ষতি (Profit)</th>
                                            <th class="text-center" style="width: 45px;">বাদ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cart_tbody">
                                        <tr id="empty_cart_tr">
                                            <td colspan="8" class="text-center py-4 text-muted">
                                                <i class="fa-solid fa-basket-shopping fa-2x mb-2 d-block text-muted"></i>
                                                উপরের তালিকা থেকে বিক্রয়ের জন্য পণ্য যোগ করুন।
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Checkout & Billing Summary -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="card-title mb-0 fs-6">
                                <i class="fa-solid fa-file-invoice-dollar me-2"></i>বিলিং ও পেমেন্ট হিসাব
                            </h5>
                        </div>
                        <div class="card-body p-3">
                            <div class="summary-card p-3 mb-3">
                                <div class="summary-row">
                                    <span class="text-dark font-weight-bold">মোট ক্রয়মূল্য (Cost):</span>
                                    <strong class="text-dark fs-6">৳ <span id="summary_total_cost">0.00</span></strong>
                                </div>
                                <div class="summary-row">
                                    <span class="text-muted">উপমোট (Subtotal):</span>
                                    <strong class="text-dark fs-6">৳ <span id="summary_subtotal">0.00</span></strong>
                                </div>

                                <!-- Karigor Mojuri Option -->
                                <div class="p-2 mb-2 mt-2 rounded bg-light border">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small font-weight-bold text-dark mb-0">
                                            <i class="fa-solid fa-coins text-warning me-1"></i>কারিগর মজুরি (Karigor Mojuri):
                                        </label>
                                        <span class="badge bg-secondary font-monospace" id="cart_total_grams_badge">মোট ওজন: 0.000 গ্রাম</span>
                                    </div>
                                    <div class="mb-2">
                                        <select name="karigor_mojuri_id" id="karigor_mojuri_select" class="form-select form-select-sm">
                                            <option value="" data-per-vori="0" data-per-gram="0">-- কারিগর মজুরি নির্বাচন করুন (ঐচ্ছিক) --</option>
                                            @foreach($karigorMojuris as $km)
                                                <option value="{{ $km->id }}" 
                                                        data-per-vori="{{ $km->per_vori_tk }}" 
                                                        data-per-gram="{{ $km->per_gram_tk }}"
                                                        data-category="{{ $km->category_name }}"
                                                        data-type="{{ $km->type }}">
                                                    {{ $km->category_name }} - {{ strtoupper($km->type) }} (ভরি: ৳ {{ number_format($km->per_vori_tk, 2) }} | গ্রাম: ৳ {{ number_format($km->per_gram_tk, 2) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label text-muted mb-0" style="font-size: 11px;">প্রতি গ্রাম মজুরি (৳):</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">৳</span>
                                                <input type="number" step="0.01" min="0" name="karigor_mojuri_rate" id="karigor_mojuri_rate" class="form-control text-end font-weight-bold" placeholder="0.00" value="0">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label text-muted mb-0" style="font-size: 11px;">মোট মজুরি (৳):</label>
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">৳</span>
                                                <input type="number" step="0.01" min="0" name="karigor_mojuri_total" id="karigor_mojuri_total" class="form-control text-end font-weight-bold text-primary bg-white" placeholder="0.00" value="0" readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-1" style="font-size: 10.5px;" id="mojuri_calc_formula">
                                        হিসাব: মোট ০.০০০ গ্রাম × ৳ ০.০০ = ৳ ০.০০
                                    </small>
                                </div>
                                <div class="summary-row">
                                    <span class="text-muted">ডিসকাউন্ট / ছাড়:</span>
                                    <div class="input-group input-group-sm w-50">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" step="0.01" min="0" name="discount" id="discount_input" class="form-control text-end" value="0">
                                    </div>
                                </div>
                                <div class="summary-row bg-light p-2 rounded mt-2">
                                    <span class="font-weight-bold text-dark fs-6">সর্বমোট বিল (Grand Total):</span>
                                    <strong class="text-primary fs-5">৳ <span id="summary_grand_total">0.00</span></strong>
                                </div>
                                <div class="summary-row p-2 rounded mt-1" id="profit_summary_wrap">
                                    <span class="font-weight-bold">মোট মুনাফা (Net Profit):</span>
                                    <strong class="fs-6" id="summary_net_profit">৳ 0.00</strong>
                                </div>
                            </div>

                            <!-- Payment Inputs -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">পরিশোধের পরিমাণ (Paid Amount)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white font-weight-bold">৳</span>
                                    <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount_input" class="form-control form-control-lg font-weight-bold text-success" value="0">
                                    <button type="button" id="full_paid_btn" class="btn btn-outline-success btn-sm">
                                        ফুল পেইড
                                    </button>
                                </div>
                            </div>

                            <div class="summary-row mb-3 p-2 bg-light rounded">
                                <span class="font-weight-bold text-danger">অবশিষ্ট বকেয়া (Due Amount):</span>
                                <strong class="text-danger fs-5">৳ <span id="summary_due_amount">0.00</span></strong>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-md-6">
                                    <label class="form-label small font-weight-bold">পেমেন্ট মাধ্যম</label>
                                    <select name="payment_method" class="form-select form-select-sm">
                                        <option value="cash">নগদ (Cash)</option>
                                        <option value="bkash">বিকাশ (bKash)</option>
                                        <option value="nagad">নগদ (Nagad)</option>
                                        <option value="bank">ব্যাংক (Bank)</option>
                                        <option value="card">কার্ড (Card)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small font-weight-bold">বকেয়া পরিশোধের তারিখ</label>
                                    <input type="date" name="due_date" class="form-control form-control-sm">
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="form-label small font-weight-bold">ট্রানজেকশন আইডি / নোট</label>
                                <input type="text" name="transaction_reference" class="form-control form-control-sm" placeholder="রেফারেন্স নম্বর (ঐচ্ছিক)">
                            </div>

                            <div class="mb-3">
                                <label class="form-label small font-weight-bold">মন্তব্য (Notes)</label>
                                <textarea name="notes" rows="2" class="form-control form-control-sm" placeholder="অতিরিক্ত কোনো তথ্য..."></textarea>
                            </div>

                            <!-- Required Field Validation Error Box -->
                            <div id="checkout_error_alert" class="alert alert-danger py-2 mb-3 d-none small">
                                <div class="font-weight-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i> অনুগ্রহ করে নিচের প্রয়োজনীয় তথ্যগুলো পূরণ করুন:</div>
                                <ul id="checkout_error_list" class="mb-0 ps-3"></ul>
                            </div>

                            <!-- Invoice Preview Button (Before Confirm) -->
                            <button type="button" id="previewInvoiceBtn" class="btn btn-outline-primary w-100 py-2 mb-2 fs-6 font-weight-bold shadow-sm">
                                <i class="fa-solid fa-file-invoice me-2"></i>ইনভয়েস প্রিভিউ দেখুন (Preview Invoice)
                            </button>

                            <button type="button" id="submitSellBtn" class="btn btn-success w-100 py-2 fs-6 font-weight-bold">
                                <i class="fa-solid fa-check-double me-2"></i>বিক্রয় সম্পন্ন ও ইনভয়েস প্রিন্ট
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Invoice Preview Modal -->
<div class="modal fade" id="invoicePreviewModal" tabindex="-1" aria-labelledby="invoicePreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white py-2 px-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-receipt text-warning fs-5"></i>
                    <h5 class="modal-title fs-6 font-weight-bold mb-0" id="invoicePreviewModalLabel">
                        ইনভয়েস খসড়া প্রিভিউ (Invoice Draft Preview - Before Confirm)
                    </h5>
                    <span class="badge bg-warning text-dark font-weight-bold" style="font-size: 11px;">যাচাইকরণ খসড়া</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm btn-outline-light py-1 px-2" id="printModalInvoiceBtn" title="প্রিভিউ প্রিন্ট করুন">
                        <i class="fa-solid fa-print me-1"></i> প্রিন্ট
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body p-3 bg-light" style="max-height: 80vh; overflow-y: auto;">
                <div class="alert alert-info py-2 px-3 small mb-3 d-flex align-items-center justify-content-between border-info">
                    <div>
                        <i class="fa-solid fa-circle-info me-2 text-info"></i>
                        <strong>নির্দেশনা:</strong> বিক্রয় চূড়ান্ত করার আগে গ্রাহকের নাম, পণ্যের বিবরণ, ওজন, রেট এবং সর্বমোট টাকার পরিমাণ ভালোভাবে মিলিয়ে নিন। কোনো ভুল থাকলে "তথ্য সংশোধন করুন" বাটনে ক্লিক করে পরিবর্তন করুন।
                    </div>
                </div>

                <!-- Live Render Container -->
                <div id="invoice_preview_render_area"></div>
            </div>
            <div class="modal-footer bg-white py-2 px-3 d-flex justify-content-between">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fa-solid fa-pen-to-square me-1"></i> তথ্য সংশোধন করুন (Edit Details)
                </button>
                <button type="button" class="btn btn-success font-weight-bold px-4 shadow-sm" id="confirmAndSellFromModalBtn">
                    <i class="fa-solid fa-check-double me-1"></i> সব তথ্য ঠিক আছে, বিক্রি সম্পন্ন করুন
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin_script')
<script>
    $(document).ready(function() {
        var cart = {}; // key: purchase_id -> item data
        var productPrices = @json($productPrices);

        // Live filter for available products
        $('#search_shop_products').on('input', function() {
            var val = $(this).val().toLowerCase().trim();
            $('#shop_products_tbody tr').each(function() {
                var rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.indexOf(val) > -1);
            });
        });

        // Add item to cart
        $(document).on('click', '.add-item-btn', function() {
            var tr = $(this).closest('tr');
            var id = tr.data('id');

            if (cart[id]) {
                alert('এই পণ্যটি ইতিমধ্যে তালিকায় যুক্ত করা হয়েছে।');
                return;
            }

            var defaultPrice = parseFloat(tr.data('default-price')) || parseFloat(tr.data('actual-price')) || parseFloat(tr.data('cost')) || 0;

            var item = {
                id: id,
                name: tr.data('name'),
                cat: tr.data('cat'),
                karat: tr.data('karat'),
                bhori: tr.data('bhori'),
                ana: tr.data('ana'),
                roti: tr.data('roti'),
                point: tr.data('point'),
                gram: parseFloat(tr.data('gram')) || 0,
                cost: parseFloat(tr.data('cost')) || 0,
                default_price: defaultPrice,
                sell_price: defaultPrice,
                selected_price_id: '',
                selected_rate_per_gram: 0
            };

            cart[id] = item;
            tr.addClass('table-active opacity-50');
            $(this).prop('disabled', true).text('যুক্ত');

            renderCart();
        });

        // Remove item from cart
        $(document).on('click', '.remove-cart-item', function() {
            var id = $(this).data('id');
            delete cart[id];

            var tr = $('#available_tr_' + id);
            tr.removeClass('table-active opacity-50');
            tr.find('.add-item-btn').prop('disabled', false).html('<i class="fa-solid fa-plus me-1"></i> যোগ');

            renderCart();
        });

        // Update selling price input
        $(document).on('input keyup change', '.cart-sell-price-input', function() {
            var id = $(this).data('id');
            var val = parseFloat($(this).val()) || 0;
            if (cart[id]) {
                cart[id].sell_price = val;
                updateItemProfitBadge(id);
                calculateTotals();
            }
        });

        function updateItemProfitBadge(id) {
            var item = cart[id];
            if (!item) return;
            var profit = item.sell_price - item.cost;
            var badge = $('#profit_badge_' + id);
            if (profit >= 0) {
                badge.removeClass('profit-negative').addClass('profit-positive').text('+৳ ' + profit.toFixed(2));
            } else {
                badge.removeClass('profit-positive').addClass('profit-negative').text('-৳ ' + Math.abs(profit).toFixed(2));
            }
        }

        function renderCart() {
            var tbody = $('#cart_tbody');
            var keys = Object.keys(cart);

            if (keys.length === 0) {
                tbody.html('<tr id="empty_cart_tr"><td colspan="8" class="text-center py-4 text-muted"><i class="fa-solid fa-basket-shopping fa-2x mb-2 d-block text-muted"></i>উপরের তালিকা থেকে বিক্রয়ের জন্য পণ্য যোগ করুন।</td></tr>');
                $('#cart_badge').text('০ টি পণ্য');
            } else {
                tbody.empty();
                $('#cart_badge').text(keys.length + ' টি পণ্য');
                $('#checkout_error_alert').addClass('d-none');

                $.each(keys, function(idx, id) {
                    var item = cart[id];
                    var profit = item.sell_price - item.cost;
                    var profitClass = profit >= 0 ? 'profit-positive' : 'profit-negative';
                    var profitText = profit >= 0 ? '+৳ ' + profit.toFixed(2) : '-৳ ' + Math.abs(profit).toFixed(2);

                    // Dropdown for rate selection from product_price table
                    var rateOptionsHtml = '<option value="" data-per-gram="0" data-per-vori="0">-- ডিফল্ট মূল্য (৳ ' + item.default_price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ') --</option>';
                    $.each(productPrices, function(pIdx, pp) {
                        var pPerGram = parseFloat(pp.selling_price_per_gram) || (parseFloat(pp.selling_price) / 11.664) || 0;
                        var isSelected = (String(item.selected_price_id) === String(pp.id)) ? 'selected' : '';
                        rateOptionsHtml += '<option value="' + pp.id + '" data-per-gram="' + pPerGram + '" data-per-vori="' + pp.selling_price + '" data-name="' + pp.product_name + '" ' + isSelected + '>' + pp.product_name + ' (৳ ' + pPerGram.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 2}) + '/g)</option>';
                    });

                    // Subtext showing calculation breakdown
                    var priceSubtext = '';
                    if (item.selected_rate_per_gram > 0) {
                        priceSubtext = '<small class="text-primary d-block text-end mt-1" style="font-size: 10.5px;">(' + item.gram.toFixed(3) + 'g × ৳' + item.selected_rate_per_gram.toFixed(2) + ')</small>';
                    } else {
                        priceSubtext = '<small class="text-muted d-block text-end mt-1" style="font-size: 10.5px;">(ডিফল্ট মূল্য)</small>';
                    }

                    var tr = $('<tr></tr>');
                    tr.append('<td class="text-center font-weight-bold text-muted">' + (idx + 1) + '</td>');
                    tr.append('<td><strong>' + item.name + '</strong> <span class="badge bg-light text-dark border ms-1">' + item.karat + '</span><br><small class="text-muted">' + item.cat + '</small><input type="hidden" name="items[' + idx + '][purchase_id]" value="' + item.id + '"></td>');
                    tr.append('<td class="text-center small">' + (item.bhori || 0) + ' ভরি, ' + (item.ana || 0) + ' আনা, ' + (item.roti || 0) + ' রতি, ' + (item.point || 0) + ' পয়েন্ট<br><small class="text-muted font-weight-bold">(' + parseFloat(item.gram).toFixed(3) + ' গ্রাম)</small></td>');
                    tr.append('<td class="text-end"><span class="cost-price-badge">৳ ' + item.cost.toFixed(2) + '</span></td>');
                    tr.append('<td><select class="form-select form-select-sm cart-rate-select" data-id="' + item.id + '" style="font-size: 12px;">' + rateOptionsHtml + '</select></td>');
                    tr.append('<td class="text-end"><div class="input-group input-group-sm"><span class="input-group-text">৳</span><input type="number" step="0.01" min="0" name="items[' + idx + '][selling_price]" class="form-control text-end font-weight-bold cart-sell-price-input" data-id="' + item.id + '" value="' + item.sell_price.toFixed(2) + '" required></div>' + priceSubtext + '</td>');
                    tr.append('<td class="text-center"><span id="profit_badge_' + item.id + '" class="' + profitClass + '">' + profitText + '</span></td>');
                    tr.append('<td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-cart-item py-0 px-2" data-id="' + item.id + '"><i class="fa-solid fa-trash-can"></i></button></td>');

                    tbody.append(tr);
                });
            }

            calculateTotals();
        }

        // Change rate from product_price dropdown
        $(document).on('change', '.cart-rate-select', function() {
            var id = $(this).data('id');
            var selectedOption = $(this).find(':selected');
            var priceId = $(this).val();
            var perGram = parseFloat(selectedOption.data('per-gram')) || 0;

            if (!cart[id]) return;

            cart[id].selected_price_id = priceId;
            cart[id].selected_rate_per_gram = perGram;

            if (priceId && perGram > 0) {
                // Calculate sell price: item weight in gram * rate per gram
                var calculated = parseFloat((cart[id].gram * perGram).toFixed(2));
                cart[id].sell_price = calculated;
            } else {
                // Not selected -> reset to default price!
                cart[id].sell_price = cart[id].default_price;
            }

            renderCart();
        });

        // Calculate checkout totals
        function calculateTotals() {
            var totalCost = 0;
            var subtotal = 0;
            var totalGrams = 0;

            $.each(cart, function(id, item) {
                totalCost += item.cost;
                subtotal += item.sell_price;
                totalGrams += (parseFloat(item.gram) || 0);
            });

            $('#cart_total_grams_badge').text('মোট ওজন: ' + totalGrams.toFixed(3) + ' গ্রাম');

            // Calculate Karigor Mojuri: totalGrams * per_gram_rate
            var mojuriRate = parseFloat($('#karigor_mojuri_rate').val()) || 0;
            var mojuriTotal = totalGrams * mojuriRate;
            $('#karigor_mojuri_total').val(mojuriTotal.toFixed(2));

            if (mojuriRate > 0 && totalGrams > 0) {
                $('#mojuri_calc_formula').html('হিসাব: মোট <strong>' + totalGrams.toFixed(3) + '</strong> গ্রাম × ৳ <strong>' + mojuriRate.toFixed(2) + '</strong> = <strong class="text-primary">৳ ' + mojuriTotal.toFixed(2) + '</strong>');
            } else {
                $('#mojuri_calc_formula').html('হিসাব: মোট ' + totalGrams.toFixed(3) + ' গ্রাম × ৳ ' + mojuriRate.toFixed(2) + ' = ৳ ' + mojuriTotal.toFixed(2));
            }

            var discount = parseFloat($('#discount_input').val()) || 0;
            var grandTotal = Math.max(0, subtotal + mojuriTotal - discount);
            var netProfit = grandTotal - totalCost;

            $('#summary_total_cost').text(totalCost.toFixed(2));
            $('#summary_subtotal').text(subtotal.toFixed(2));
            $('#summary_grand_total').text(grandTotal.toFixed(2));

            var profitWrap = $('#summary_net_profit');
            if (netProfit >= 0) {
                profitWrap.text('+৳ ' + netProfit.toFixed(2)).removeClass('text-danger').addClass('text-success font-weight-bold');
            } else {
                profitWrap.text('-৳ ' + Math.abs(netProfit).toFixed(2)).removeClass('text-success').addClass('text-danger font-weight-bold');
            }

            var paid = parseFloat($('#paid_amount_input').val()) || 0;
            var due = Math.max(0, grandTotal - paid);
            $('#summary_due_amount').text(due.toFixed(2));
        }

        // On Karigor Mojuri dropdown change -> auto-fill per gram rate and recalculate
        $('#karigor_mojuri_select').on('change', function() {
            var perGram = parseFloat($(this).find(':selected').data('per-gram')) || 0;
            $('#karigor_mojuri_rate').val(perGram.toFixed(2));
            calculateTotals();
        });

        // On manual edit of per gram mojuri rate -> recalculate total mojuri & grand total
        $('#karigor_mojuri_rate').on('input keyup change', function() {
            calculateTotals();
        });

        $('#discount_input').on('input keyup change', function() {
            calculateTotals();
        });

        $('#paid_amount_input').on('input keyup change', function() {
            calculateTotals();
        });

        $('#full_paid_btn').on('click', function() {
            var discount = parseFloat($('#discount_input').val()) || 0;
            var subtotal = 0;
            var totalGrams = 0;
            $.each(cart, function(id, item) { 
                subtotal += item.sell_price; 
                totalGrams += (parseFloat(item.gram) || 0);
            });
            var mojuriRate = parseFloat($('#karigor_mojuri_rate').val()) || 0;
            var mojuriTotal = totalGrams * mojuriRate;
            var grandTotal = Math.max(0, subtotal + mojuriTotal - discount);
            $('#paid_amount_input').val(grandTotal.toFixed(2)).trigger('input');
        });

        // Helper: Convert Number to Bangla Words
        function numberToBanglaWords(n) {
            if (isNaN(n) || n == 0) return 'শূন্য';
            n = Math.round(n);
            var ones = ['', 'এক', 'দুই', 'তিন', 'চার', 'পাঁচ', 'ছয়', 'সাত', 'আট', 'নয়', 'দশ', 
                        'এগারো', 'বারো', 'তেরো', 'চৌদ্দ', 'পনেরো', 'ষোলো', 'সতেরো', 'আঠারো', 'উনিশ', 'বিশ',
                        'একুশ', 'বাইশ', 'তেইশ', 'চব্বিশ', 'পঁচিশ', 'ছাব্বিশ', 'সাতাশ', 'আটাশ', 'উনত্রিশ', 'ত্রিশ',
                        'একত্রিশ', 'বত্রিশ', 'তেত্রিশ', 'চৌত্রিশ', 'পঁয়ত্রিশ', 'ছত্রিশ', 'সাঁইত্রিশ', 'আটত্রিশ', 'উনচল্লিশ', 'চল্লিশ',
                        'একচল্লিশ', 'বিয়াল্লিশ', 'তেতাল্লিশ', 'চুয়াল্লিশ', 'পঁয়তাল্লিশ', 'ছেচল্লিশ', 'সাতচল্লিশ', 'আটচল্লিশ', 'উনপঞ্চাশ', 'পঞ্চাশ',
                        'একান্ন', 'বায়ান্ন', 'তিপ্পান্ন', 'চুয়ান্ন', 'পঞ্চান্ন', 'ছাপ্পান্ন', 'সাতান্ন', 'আটান্ন', 'উনষাট', 'ষাট',
                        'একষট্টি', 'বাষট্টি', 'তেষট্টি', 'চৌষট্টি', 'পঁয়ষট্টি', 'ছেষট্টি', 'সাতষট্টি', 'আটষট্টি', 'উনসত্তর', 'সত্তর',
                        'একাত্তর', 'বাহাত্তর', 'তিয়াত্তর', 'চুয়াত্তর', 'পঁচাত্তর', 'ছিয়াত্তর', 'সাতাত্তর', 'আটাত্তর', 'উনআশি', 'আশি',
                        'একাশি', 'বিরাশি', 'তিরাশি', 'চুরাশি', 'পঁচাশি', 'ছিয়াশি', 'সাতানব্বই', 'আটানব্বই', 'নিরানব্বই'];

            function twoDigits(num) {
                return ones[num] || String(num);
            }

            var str = '';
            var crore = Math.floor(n / 10000000);
            n %= 10000000;
            var lakh = Math.floor(n / 100000);
            n %= 100000;
            var thousand = Math.floor(n / 1000);
            n %= 1000;
            var hundred = Math.floor(n / 100);
            var remainder = n % 100;

            if (crore > 0) {
                str += (crore < 100 ? twoDigits(crore) : crore) + ' কোটি ';
            }
            if (lakh > 0) {
                str += twoDigits(lakh) + ' লাখ ';
            }
            if (thousand > 0) {
                str += twoDigits(thousand) + ' হাজার ';
            }
            if (hundred > 0) {
                str += twoDigits(hundred) + ' শত ';
            }
            if (remainder > 0) {
                str += twoDigits(remainder) + ' ';
            }
            return str.trim();
        }

        // Shared Checkout Form Validation
        function validateCheckoutForm() {
            var errors = [];
            var customerId = $('#selected_customer_id').val();
            var cartKeys = Object.keys(cart);
            var paidVal = $('#paid_amount_input').val();
            var paidAmount = parseFloat(paidVal);

            // 1. Customer Selection
            if (!customerId) {
                errors.push('<strong>গ্রাহক নির্বাচন করুন:</strong> উপরে গ্রাহকের নাম বা ফোন দিয়ে সার্চ করুন অথবা <strong>"ক্যাশ গ্রাহক (Walk-in)"</strong> বাটনে ক্লিক করুন।');
                $('#customer_selection_error').removeClass('d-none');
                $('#customer_search_input').addClass('is-invalid border-danger');
            } else {
                $('#customer_selection_error').addClass('d-none');
                $('#customer_search_input').removeClass('is-invalid border-danger');
            }

            // 2. Cart Items
            if (cartKeys.length === 0) {
                errors.push('<strong>পণ্য নির্বাচন করুন:</strong> শপের মজুত তালিকা থেকে কমপক্ষে একটি পণ্য বিক্রয়ের তালিকায় যোগ করুন।');
            } else {
                var hasInvalidPrice = false;
                $('.cart-sell-price-input').each(function() {
                    var p = parseFloat($(this).val());
                    if (isNaN(p) || p <= 0) {
                        hasInvalidPrice = true;
                        $(this).addClass('is-invalid border-danger');
                    } else {
                        $(this).removeClass('is-invalid border-danger');
                    }
                });
                if (hasInvalidPrice) {
                    errors.push('<strong>বিক্রয়মূল্য দিন:</strong> কার্টের প্রতিটি পণ্যের সঠিক বিক্রয়মূল্য (৳) লিখুন।');
                }
            }

            // 3. Paid Amount
            if (paidVal === '' || isNaN(paidAmount) || paidAmount < 0) {
                errors.push('<strong>পরিশোধের পরিমাণ দিন:</strong> পেইড অ্যামাউন্ট সঠিক সংখ্যায় লিখুন (সম্পূর্ণ পরিশোধ হলে "ফুল পেইড" বাটনে ক্লিক করুন)।');
                $('#paid_amount_input').addClass('is-invalid border-danger');
            } else {
                $('#paid_amount_input').removeClass('is-invalid border-danger');
            }

            // If any error exists, display in alert box and scroll to it
            if (errors.length > 0) {
                var errList = $('#checkout_error_list');
                errList.empty();
                $.each(errors, function(idx, msg) {
                    errList.append('<li>' + msg + '</li>');
                });
                $('#checkout_error_alert').removeClass('d-none');

                if (!customerId) {
                    $('html, body').animate({
                        scrollTop: $("#customer_search_container").offset().top - 120
                    }, 300);
                    $('#customer_search_input').focus();
                } else if (cartKeys.length === 0) {
                    $('html, body').animate({
                        scrollTop: $("#shop_products_tbody").offset().top - 150
                    }, 300);
                } else {
                    $('html, body').animate({
                        scrollTop: $("#checkout_error_alert").offset().top - 100
                    }, 300);
                }
                return false;
            }

            $('#checkout_error_alert').addClass('d-none');
            return true;
        }

        // Generate Invoice Preview HTML
        function generateInvoicePreviewHtml() {
            var customerName = $('#card_customer_name').text().trim() || 'ক্যাশ ক্রেতা (Walk-in Customer)';
            var customerPhone = $('#card_customer_phone').text().trim() || '-';
            var customerAddress = $('#card_customer_address').text().trim() || 'ঢাকা, বাংলাদেশ';
            var customerExtra = $('#card_customer_extra').text().trim() || '';

            var now = new Date();
            var day = String(now.getDate()).padStart(2, '0');
            var month = String(now.getMonth() + 1).padStart(2, '0');
            var year = now.getFullYear();
            var timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
            var dateFormatted = day + '/' + month + '/' + year + ' ' + timeStr;

            var cartKeys = Object.keys(cart);
            var totalGrams = 0;
            var subtotal = 0;
            var itemsRowsHtml = '';

            $.each(cartKeys, function(idx, id) {
                var itm = cart[id];
                var g = parseFloat(itm.gram) || 0;
                totalGrams += g;
                subtotal += itm.sell_price;

                var rateText = '-';
                if (itm.selected_rate_per_gram > 0) {
                    rateText = '৳ ' + itm.selected_rate_per_gram.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '/g';
                } else {
                    rateText = 'ডিফল্ট রেট';
                }

                var weightDetails = (itm.bhori || 0) + 'ভ, ' + (itm.ana || 0) + 'আ, ' + (itm.roti || 0) + 'র, ' + (itm.point || 0) + 'প<br><small class="text-muted font-weight-bold">(' + g.toFixed(3) + ' গ্রাম)</small>';

                itemsRowsHtml += `
                <tr>
                    <td class="text-center font-weight-bold">${idx + 1}</td>
                    <td>
                        <strong class="text-dark">${itm.name}</strong>
                        <div class="small text-muted">${itm.cat}</div>
                    </td>
                    <td class="text-center"><span class="badge bg-light text-dark border font-weight-bold">${itm.karat}</span></td>
                    <td class="text-center">${weightDetails}</td>
                    <td class="text-center small">${rateText}</td>
                    <td class="text-end font-weight-bold" style="font-size: 11.5px;">৳ ${itm.sell_price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                </tr>`;
            });

            var mojuriId = $('#karigor_mojuri_select').val();
            var mojuriRate = parseFloat($('#karigor_mojuri_rate').val()) || 0;
            var mojuriTotal = parseFloat($('#karigor_mojuri_total').val()) || 0;

            var discount = parseFloat($('#discount_input').val()) || 0;
            var grandTotal = Math.max(0, subtotal + mojuriTotal - discount);
            var paid = parseFloat($('#paid_amount_input').val()) || 0;
            var due = Math.max(0, grandTotal - paid);
            var paymentMethod = $('select[name="payment_method"] option:selected').text();
            var dueDate = $('input[name="due_date"]').val();
            var notes = $('textarea[name="notes"]').val().trim();

            // Stamp HTML
            var stampHtml = '';
            if (due <= 0 && grandTotal > 0) {
                stampHtml = '<div class="preview-stamp-box stamp-paid"><span style="font-size: 12px; line-height: 1;">PAID</span><span style="font-size: 8px;">পরিশোধিত</span></div>';
            } else if (paid > 0) {
                stampHtml = '<div class="preview-stamp-box stamp-partial"><span style="font-size: 10.5px; line-height: 1;">PARTIAL</span><span style="font-size: 7.5px;">আংশিক</span></div>';
            } else {
                stampHtml = '<div class="preview-stamp-box stamp-due"><span style="font-size: 12px; line-height: 1;">DUE</span><span style="font-size: 8px;">বকেয়া</span></div>';
            }

            var mojuriRowHtml = '';
            if (mojuriTotal > 0) {
                mojuriRowHtml = `
                <tr>
                    <td class="text-muted">
                        Karigor Mojuri <span style="font-size: 9.5px;">/ কারিগর মজুরি</span>
                        ${mojuriRate > 0 ? '<small class="text-muted d-block" style="font-size: 8.5px;">(@ ৳ ' + mojuriRate.toFixed(2) + '/g)</small>' : ''}
                    </td>
                    <td class="text-end font-weight-bold text-dark">+৳ ${mojuriTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                </tr>`;
            }

            var discountRowHtml = '';
            if (discount > 0) {
                discountRowHtml = `
                <tr>
                    <td class="text-danger">Discount <span style="font-size: 9.5px;">/ ছাড়</span></td>
                    <td class="text-end font-weight-bold text-danger">-৳ ${discount.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                </tr>`;
            }

            var dueHtml = '';
            if (due > 0) {
                dueHtml = `
                <div>
                    <strong>Due Amount / অবশিষ্ট বকেয়া:</strong> 
                    <span class="font-weight-bold text-danger">৳ ${due.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                    ${dueDate ? '<span class="text-muted ms-1" style="font-size: 9.5px;">(তারিখ: ' + dueDate + ')</span>' : ''}
                </div>`;
            }

            var notesHtml = '';
            if (notes) {
                notesHtml = `
                <div style="margin-top: 6px; padding: 4px 8px; background: #fff; border: 1px dashed #cbd5e1; font-size: 9.5px;">
                    <strong>নোট / Note:</strong> ${notes}
                </div>`;
            }

            return `
            <div class="invoice-preview-sheet" id="invoice_modal_print_sheet">
                <!-- Header: Shop Logo & Brand -->
                <div class="preview-header-brand">
                    <img src="{{ asset('logo/logo-529489738.png') }}" alt="Madobi Jewelers Logo" class="preview-logo-img">
                    <h1 class="preview-shop-title">MADOBI JEWELERS</h1>
                    <div class="preview-shop-subtitle-en">Gold, Diamond & Silver Jewellery</div>
                    <div class="preview-shop-subtitle-bn">সোনার ও রূপার আধুনিক অলংকার প্রস্তুতকারক ও বিক্রেতা</div>
                </div>

                <!-- Divider -->
                <div class="preview-double-divider"></div>

                <!-- Document Heading -->
                <div class="preview-title-block">
                    <div class="preview-heading-bn">ট্যাক্স ইনভয়েস / ক্যাশ মেমো (খসড়া প্রিভিউ)</div>
                    <div class="preview-heading-en">TAX INVOICE / CASH MEMO (DRAFT)</div>
                    <div class="preview-tax-reg">TAX REG. NO: 310122866600003 &nbsp;|&nbsp; ভ্যাট / BIN: 004829174-0101 &nbsp;|&nbsp; ট্রেড লাইসেন্স নং: ১৮৪৯২</div>
                </div>

                <!-- Info Grid -->
                <div class="preview-info-grid">
                    <!-- Left: Gold Rates -->
                    <div class="preview-rates-card">
                        <table class="preview-rate-table">
                            <tr>
                                <td style="width: 50%;"><strong>Gold Rate 22 K</strong><span class="d-block text-muted" style="font-size: 9px;">সোনা ২২ ক্যারেট</span></td>
                                <td class="text-end font-weight-bold">৳ {{ isset($productPrices) && $productPrices->firstWhere('product_name', 'Gold 22k') ? number_format($productPrices->firstWhere('product_name', 'Gold 22k')->selling_price_per_gram, 2) : '১৯,৭৫৫.০০' }}/g</td>
                            </tr>
                            <tr>
                                <td><strong>Gold Rate 21 K</strong><span class="d-block text-muted" style="font-size: 9px;">সোনা ২১ ক্যারেট</span></td>
                                <td class="text-end font-weight-bold">৳ {{ isset($productPrices) && $productPrices->firstWhere('product_name', 'Gold 21K') ? number_format($productPrices->firstWhere('product_name', 'Gold 21K')->selling_price_per_gram, 2) : '১৮,৮৬০.০০' }}/g</td>
                            </tr>
                            <tr>
                                <td><strong>Gold Rate 18 K</strong><span class="d-block text-muted" style="font-size: 9px;">সোনা ১৮ ক্যারেট</span></td>
                                <td class="text-end font-weight-bold">৳ {{ isset($productPrices) && $productPrices->firstWhere('product_name', 'Gold 18K') ? number_format($productPrices->firstWhere('product_name', 'Gold 18K')->selling_price_per_gram, 2) : '১৬,১৬৫.০০' }}/g</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Right: Invoice Meta -->
                    <div class="preview-meta-card">
                        <table class="preview-meta-table">
                            <tr>
                                <td style="width: 40%;" class="text-muted"><strong>Invoice No</strong> <span style="font-size: 9px;">চালান নং</span></td>
                                <td class="font-weight-bold text-end text-primary" style="letter-spacing: 0.5px;">SL-DRAFT-PREVIEW</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>Date & Time</strong> <span style="font-size: 9px;">তারিখ</span></td>
                                <td class="text-end font-weight-bold">${dateFormatted}</td>
                            </tr>
                            <tr>
                                <td class="text-muted"><strong>Salesman</strong> <span style="font-size: 9px;">বিক্রেতা</span></td>
                                <td class="text-end font-weight-bold">{{ auth()->user()->name ?? 'Admin' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="preview-customer-section">
                    <div class="preview-customer-row">
                        <div style="flex: 1.5; min-width: 200px;">
                            <span class="text-muted font-weight-bold">Customer Name / গ্রাহক:</span>
                            <span class="font-weight-bold text-dark ms-1">${customerName}</span>
                        </div>
                        <div style="flex: 1; min-width: 150px;">
                            <span class="text-muted font-weight-bold">Mobile / মোবাইল:</span>
                            <span class="font-weight-bold text-dark ms-1">${customerPhone}</span>
                        </div>
                        <div style="flex: 1.5; min-width: 200px;">
                            <span class="text-muted font-weight-bold">Address / ঠিকানা:</span>
                            <span class="text-dark ms-1">${customerAddress}</span>
                        </div>
                        ${customerExtra ? `<div style="flex: 1; min-width: 140px;"><span class="text-muted font-weight-bold">ID / NID:</span> <span class="text-dark ms-1">${customerExtra}</span></div>` : ''}
                    </div>
                </div>

                <!-- Items Table -->
                <table class="preview-items-table">
                    <thead>
                        <tr>
                            <th style="width: 35px;">নং</th>
                            <th>পণ্যের বিবরণ</th>
                            <th style="width: 75px;">ক্যারেট</th>
                            <th style="width: 170px;">ওজন</th>
                            <th style="width: 100px;">দর / রেট</th>
                            <th style="width: 130px;" class="text-end">বিক্রয়মূল্য (৳)</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${itemsRowsHtml}
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-start ps-2">
                                <strong>সর্বমোট (${cartKeys.length} টি পণ্য)</strong>
                            </td>
                            <td class="text-center font-weight-bold">${totalGrams.toFixed(3)} গ্রাম</td>
                            <td></td>
                            <td class="text-end font-weight-bold" style="font-size: 12px;">৳ ${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        </tr>
                    </tfoot>
                </table>

                <!-- In Words -->
                <div class="preview-words-box">
                    <div><span class="text-muted">কথায়:</span> <strong>${numberToBanglaWords(grandTotal)}</strong> টাকা মাত্র</div>
                </div>

                <!-- Bottom Settlement & Totals Grid -->
                <div class="preview-bottom-grid">
                    <div class="preview-settlement-side">
                        <div><strong>Mode Of Settlement:</strong> <span class="font-weight-bold text-dark">${paymentMethod}</span></div>
                        <div><strong>পরিশোধিত টাকা (Paid):</strong> <span class="font-weight-bold text-success">৳ ${paid.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span></div>
                        ${dueHtml}
                        <div class="preview-stamp-container">
                            ${stampHtml}
                            <div class="preview-stamp-circle">
                                <div style="font-family: 'Cinzel', serif; font-size: 13px; font-weight: 800; line-height: 1;">M</div>
                                <div>DELIVERED</div>
                                <div style="font-size: 6.5px;">মাদবী</div>
                            </div>
                        </div>
                    </div>

                    <div class="preview-totals-side">
                        <table class="preview-totals-table">
                            <tr>
                                <td style="width: 60%;" class="text-muted">Net Total <span style="font-size: 9.5px;">/ নিট মোট</span></td>
                                <td class="text-end font-weight-bold">৳ ${subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                            </tr>
                            ${mojuriRowHtml}
                            ${discountRowHtml}
                            <tr>
                                <td class="text-muted">Total Taxable Amount <span style="font-size: 9.5px;">/ করযোগ্য</span></td>
                                <td class="text-end font-weight-bold">৳ ${(subtotal + mojuriTotal - discount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                            </tr>
                            <tr class="preview-totals-grand">
                                <td>TOTAL <span style="font-size: 10px; font-weight: 700;">/ সর্বমোট বিল</span></td>
                                <td class="text-end text-primary" style="font-size: 13px;">৳ ${grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                ${notesHtml}

                <!-- Signatures -->
                <div class="preview-signatures">
                    <div class="preview-sig-col">
                        <div class="preview-sig-line"></div>
                        <div class="preview-sig-title">গ্রাহকের স্বাক্ষর</div>
                        <small class="text-muted" style="font-size: 8.5px;">Customer Signature</small>
                    </div>
                    <div class="preview-sig-col">
                        <div class="preview-sig-line"></div>
                        <div class="preview-sig-title">বিক্রেতার স্বাক্ষর</div>
                        <small class="text-muted" style="font-size: 8.5px;">Salesman Signature</small>
                    </div>
                    <div class="preview-sig-col">
                        <div class="preview-sig-line"></div>
                        <div class="preview-sig-title">কর্তৃপক্ষের স্বাক্ষর</div>
                        <small class="text-muted" style="font-size: 8.5px;">Authorized Signature</small>
                    </div>
                </div>
            </div>`;
        }

        // Preview Invoice Button Click
        $(document).on('click', '#previewInvoiceBtn', function(e) {
            e.preventDefault();

            // Run validations first
            if (!validateCheckoutForm()) {
                return;
            }

            // Generate HTML and populate modal
            var previewHtml = generateInvoicePreviewHtml();
            $('#invoice_preview_render_area').html(previewHtml);

            // Open bootstrap modal
            var modalEl = document.getElementById('invoicePreviewModal');
            var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        });

        // Confirm & Sell from inside the preview modal
        $(document).on('click', '#confirmAndSellFromModalBtn', function() {
            var modalEl = document.getElementById('invoicePreviewModal');
            var modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }

            var btn = $('#submitSellBtn');
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>বিক্রয় সম্পন্ন হচ্ছে...');
            document.getElementById('shopSellForm').submit();
        });

        // Print from preview modal
        $(document).on('click', '#printModalInvoiceBtn', function() {
            var printContents = document.getElementById('invoice_preview_render_area').innerHTML;
            var printWindow = window.open('', '_blank');
            printWindow.document.write('<html><head><title>ইনভয়েস প্রিভিউ - Madobi Jewelers</title>');
            printWindow.document.write('<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">');
            printWindow.document.write('<style>' + $('style').filter(function() { return $(this).text().indexOf('.invoice-preview-sheet') > -1; }).text() + '</style>');
            printWindow.document.write('</head><body style="margin:0; padding: 10px; background:#fff;">' + printContents + '</body></html>');
            printWindow.document.close();
            printWindow.focus();
            setTimeout(function() { printWindow.print(); }, 400);
        });

        // Form validation on direct submit button click
        $(document).on('click', '#submitSellBtn', function(e) {
            e.preventDefault();

            if (!validateCheckoutForm()) {
                return false;
            }

            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i>বিক্রয় সম্পন্ন হচ্ছে...');
            document.getElementById('shopSellForm').submit();
        });
    });
</script>
@endpush
