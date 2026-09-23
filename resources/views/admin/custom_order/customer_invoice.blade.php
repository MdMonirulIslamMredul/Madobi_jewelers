@extends('admin.master')

@section('title')
প্রি-অর্ডার ইনভয়েস - {{ $order->order_no }}
@endsection

@push('admin_style')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* =========================================================
       Screen Preview Styling
       ========================================================= */
    .invoice-preview-wrapper {
        background-color: #f1f5f9;
        padding: 24px 0 60px;
        min-height: 100vh;
        font-family: 'Outfit', 'Hind Siliguri', 'Segoe UI', sans-serif;
    }

    .invoice-a4-sheet {
        background: #ffffff;
        width: 100%;
        max-width: 210mm;
        margin: 0 auto;
        padding: 10mm 12mm 10mm;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08), 0 1px 3px rgba(15, 23, 42, 0.05);
        border: 1px solid #e2e8f0;
        box-sizing: border-box;
        color: #0f172a;
        position: relative;
        font-size: 11px;
        line-height: 1.4;
    }

    /* Top Action Bar */
    .invoice-action-bar {
        max-width: 210mm;
        margin: 0 auto 16px;
    }

    /* Header Styling */
    .invoice-header-brand {
        text-align: center;
        margin-bottom: 6px;
    }

    .invoice-logo-img {
        max-height: 56px;
        width: auto;
        object-fit: contain;
        display: inline-block;
        margin-bottom: 2px;
    }

    .invoice-shop-title {
        font-family: 'Cinzel', serif;
        font-size: 22px;
        font-weight: 800;
        letter-spacing: 2.5px;
        color: #0f172a;
        margin: 0;
        line-height: 1.15;
        text-transform: uppercase;
    }

    .invoice-shop-subtitle-en {
        font-family: 'Cinzel', serif;
        font-size: 10px;
        letter-spacing: 3.5px;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        margin-top: 1px;
    }

    .invoice-shop-subtitle-bn {
        font-size: 11px;
        color: #334155;
        margin-top: 2px;
        font-weight: 500;
    }

    .invoice-double-divider {
        border: none;
        border-top: 1.5px solid #0f172a;
        border-bottom: 1px solid #0f172a;
        height: 4px;
        margin: 6px 0 8px;
    }

    .invoice-title-block {
        text-align: center;
        margin-bottom: 8px;
    }

    .invoice-main-heading-bn {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
    }

    .invoice-main-heading-en {
        font-family: 'Outfit', sans-serif;
        font-size: 12.5px;
        font-weight: 700;
        letter-spacing: 2px;
        color: #0f172a;
        margin: 0;
        text-transform: uppercase;
    }

    .invoice-tax-reg {
        font-size: 9.5px;
        font-weight: 600;
        color: #475569;
        letter-spacing: 0.8px;
        margin-top: 1px;
    }

    /* Upper 2-Column Info Grid */
    .invoice-info-grid {
        display: flex;
        gap: 12px;
        margin-bottom: 8px;
    }

    .invoice-gold-rates-card {
        flex: 1.05;
        border: 1.2px solid #0f172a;
        background: #fff;
    }

    .invoice-meta-card {
        flex: 1.15;
        border: 1.2px solid #0f172a;
        background: #fff;
    }

    .rate-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
    }

    .rate-table td {
        padding: 3px 8px;
        border-bottom: 1px solid #e2e8f0;
    }

    .rate-table tr:last-child td {
        border-bottom: none;
    }

    .meta-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
    }

    .meta-table td {
        padding: 3px 8px;
        border-bottom: 1px solid #e2e8f0;
    }

    .meta-table tr:last-child td {
        border-bottom: none;
    }

    /* Customer Info Section */
    .customer-info-section {
        border: 1.2px solid #0f172a;
        padding: 6px 10px;
        margin-bottom: 8px;
        background: #fafafa;
    }

    .customer-info-row {
        display: flex;
        flex-wrap: wrap;
        gap: 8px 16px;
        align-items: center;
    }

    .customer-field {
        font-size: 11px;
    }

    .customer-field .field-lbl {
        color: #475569;
        font-weight: 600;
    }

    .customer-field .field-val {
        font-weight: 700;
        color: #0f172a;
    }

    /* Products Table */
    .invoice-items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
        font-size: 10.5px;
    }

    .invoice-items-table th {
        background-color: #f8fafc;
        color: #0f172a;
        border: 1.2px solid #0f172a;
        padding: 5px 6px;
        font-weight: 700;
        text-align: center;
        line-height: 1.2;
    }

    .invoice-items-table td {
        border: 1px solid #cbd5e1;
        padding: 5px 6px;
        vertical-align: middle;
    }

    .invoice-items-table tfoot td {
        background-color: #f8fafc;
        border: 1.2px solid #0f172a;
        padding: 5px 6px;
        font-weight: 700;
    }

    .item-desc-primary {
        font-weight: 700;
        color: #0f172a;
        font-size: 11px;
    }

    .item-desc-sub {
        font-size: 9px;
        color: #64748b;
        margin-top: 1px;
    }

    /* Amount in Words Box */
    .words-box {
        border: 1px dashed #94a3b8;
        background: #f8fafc;
        padding: 5px 10px;
        margin-bottom: 8px;
        font-size: 10.5px;
    }

    .words-box .words-en {
        font-weight: 600;
        color: #1e293b;
    }

    .words-box .words-bn {
        font-weight: 600;
        color: #334155;
    }

    /* Bottom Grid: Settlement & Totals */
    .invoice-bottom-grid {
        display: flex;
        gap: 12px;
        margin-bottom: 8px;
        align-items: flex-start;
    }

    .settlement-side {
        flex: 1.3;
    }

    .totals-side {
        flex: 1;
    }

    /* Payment History Box */
    .invoice-payment-history {
        border: 1.2px solid #0f172a;
        background: #fff;
        margin-bottom: 8px;
        overflow: hidden;
    }

    .payment-history-header {
        background-color: #f1f5f9;
        padding: 4px 8px;
        font-size: 10px;
        font-weight: 700;
        border-bottom: 1px solid #0f172a;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #0f172a;
    }

    .payment-history-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5px;
    }

    .payment-history-table th {
        background: #f8fafc;
        padding: 3px 6px;
        border-bottom: 1px solid #cbd5e1;
        font-weight: 600;
        color: #475569;
    }

    .payment-history-table td {
        padding: 3px 6px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .payment-history-table tr:last-child td {
        border-bottom: none;
    }

    .payment-history-summary {
        background: #f8fafc;
        padding: 4px 8px;
        border-top: 1px solid #0f172a;
        font-size: 10px;
    }

    /* Stamp Box */
    .stamp-container {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 6px;
    }

    .stamp-box {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2px solid #059669;
        color: #059669;
        padding: 3px 12px;
        border-radius: 4px;
        transform: rotate(-3deg);
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
    }

    .stamp-partial {
        border-color: #d97706;
        color: #d97706;
    }

    .stamp-due {
        border-color: #dc2626;
        color: #dc2626;
    }

    .stamp-circle {
        width: 48px;
        height: 48px;
        border: 2px dashed #059669;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #059669;
        font-size: 7px;
        font-weight: 700;
        text-align: center;
        transform: rotate(5deg);
    }

    .stamp-circle-icon {
        font-size: 13px;
        font-weight: 900;
        line-height: 1;
    }

    /* Totals Table */
    .totals-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
        border: 1.2px solid #0f172a;
    }

    .totals-table td {
        padding: 3.5px 8px;
        border-bottom: 1px solid #e2e8f0;
    }

    .totals-table tr:last-child td {
        border-bottom: none;
    }

    .totals-table .total-row-highlight {
        background-color: #f8fafc;
        font-weight: 700;
        font-size: 11px;
    }

    .totals-table .grand-total-row {
        background-color: #0f172a;
        color: #ffffff;
        font-weight: 800;
        font-size: 12px;
    }

    .totals-table .grand-total-row td {
        border-bottom: none;
    }

    /* Pre-Order Terms & Signatures */
    .preorder-terms-card {
        border: 1px solid #cbd5e1;
        background: #fcfcfc;
        padding: 6px 10px;
        margin-bottom: 8px;
        font-size: 9px;
        color: #334155;
    }

    .preorder-terms-card ol {
        margin: 2px 0 0 16px;
        padding: 0;
    }

    .preorder-terms-card li {
        margin-bottom: 1px;
    }

    .invoice-signatures {
        display: flex;
        justify-content: space-between;
        margin-top: 18px;
        margin-bottom: 8px;
    }

    .sig-line {
        width: 180px;
        border-top: 1.2px solid #475569;
        text-align: center;
        padding-top: 3px;
        font-size: 10px;
        color: #334155;
    }

    /* Bottom Info Banner */
    .invoice-bottom-banner {
        background: #0f172a;
        color: #ffffff;
        padding: 6px 10px;
        text-align: center;
        font-size: 9px;
        line-height: 1.4;
        margin-top: 6px;
    }

    /* =========================================================
       Print Stylesheet (Exact 1-Page A4 Setup)
       ========================================================= */
    @media print {
        @page {
            size: A4 portrait;
            margin: 8mm 10mm 8mm;
        }

        body {
            background: #fff !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            color: #000 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        #main-wrapper,
        .left-sidebar,
        .topbar,
        .page-wrapper,
        .container-fluid,
        .no-print,
        header,
        footer,
        nav,
        .preloader {
            display: none !important;
        }

        body * {
            visibility: hidden;
        }

        #printable_invoice, #printable_invoice * {
            visibility: visible;
        }

        #printable_invoice {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
            page-break-inside: avoid;
        }

        .invoice-items-table th,
        .invoice-items-table td,
        .totals-table td,
        .rate-table td,
        .meta-table td,
        .invoice-payment-history,
        .payment-history-header,
        .payment-history-table th,
        .payment-history-table td,
        .payment-history-summary {
            border-color: #111 !important;
        }

        .invoice-double-divider {
            border-top-color: #000 !important;
            border-bottom-color: #000 !important;
        }

        .invoice-bottom-banner {
            background-color: #111 !important;
            color: #fff !important;
        }
    }
</style>
@endpush

@section('body')
@php
    $logo = \App\Models\Logo::latest()->first();
    $logoUrl = asset('logo/logo-529489738.png');
    
    // Dynamic Gold Rates
    $p22 = $productPrices->get('Gold 22k') ?? $productPrices->get('Gold 22K');
    $p21 = $productPrices->get('Gold 21k') ?? $productPrices->get('Gold 21K');
    $p18 = $productPrices->get('Gold 18k') ?? $productPrices->get('Gold 18K');
    
    $items = $order->items && $order->items->count() > 0 ? $order->items : collect([$order]);
    $totalQty = $items->sum('quantity');
    $totalTargetGram = $items->sum('target_gram');
    $totalRawGold = $items->sum('raw_gold_needed');
    $totalAlloy = max(0, $totalTargetGram - $totalRawGold);
@endphp

<div class="invoice-preview-wrapper">
    <!-- Top Action Bar (Screen Only) -->
    <div class="invoice-action-bar no-print d-flex justify-content-between align-items-center">
        <a href="{{ route('custom-orders.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> কাস্টম অর্ডার তালিকা
        </a>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('custom-orders.karigor-invoice', $order->id) }}" class="btn btn-warning text-dark btn-sm shadow-sm px-3 font-weight-bold">
                <i class="fa-solid fa-hammer me-1"></i> কারিগর জব কার্ড (Work Order)
            </a>
            <button type="button" class="btn btn-outline-info btn-sm shadow-sm" onclick="openPaymentHistoryModal('custom_order', {{ $order->id }})">
                <i class="fa-solid fa-hand-holding-dollar me-1"></i> কিস্তি পরিশোধের ইতিহাস
            </button>
            <button type="button" class="btn btn-primary btn-sm shadow-sm px-3" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> প্রিন্ট গ্রাহক রসিদ (A4)
            </button>
        </div>
    </div>

    <!-- The A4 Physical Sheet Container -->
    <div class="invoice-a4-sheet" id="printable_invoice">
        
        <!-- Header: Shop Logo & Brand -->
        <div class="invoice-header-brand">
            <img src="{{ $logoUrl }}" alt="Madobi Jewelers Logo" class="invoice-logo-img">
            <h1 class="invoice-shop-title">MADOBI JEWELERS</h1>
            <div class="invoice-shop-subtitle-en">Gold, Diamond & Silver Jewellery</div>
            <div class="invoice-shop-subtitle-bn">সোনার ও রূপার আধুনিক অলংকার প্রস্তুতকারক ও বিক্রেতা</div>
        </div>

        <!-- Decorative Double Line -->
        <div class="invoice-double-divider"></div>

        <!-- Document Heading -->
        <div class="invoice-title-block">
            <div class="invoice-main-heading-bn">কাস্টম জুয়েলারি প্রি-অর্ডার মেমো ও ক্যাশ রসিদ</div>
            <div class="invoice-main-heading-en">CUSTOM JEWELLERY PRE-ORDER RECEIPT / INVOICE</div>
            <div class="invoice-tax-reg">TAX REG. NO: 310122866600003 &nbsp;|&nbsp; ভ্যাট / BIN: 004829174-0101 &nbsp;|&nbsp; ট্রেড লাইসেন্স নং: ১৮৪৯২</div>
        </div>

        <!-- Upper Info Grid (Gold Rates & Invoice/Delivery Meta) -->
        <div class="invoice-info-grid">
            <!-- Left Box: Gold Rates -->
            <div class="invoice-gold-rates-card">
                <table class="rate-table">
                    <tr>
                        <td style="width: 45%;">
                            <strong>Gold Rate 22 K</strong>
                            <span class="d-block text-muted" style="font-size: 9px;">সোনা ২২ ক্যারেট (দর/গ্রাম)</span>
                        </td>
                        <td class="text-end font-weight-bold" style="font-size: 11px;">
                            ৳ {{ $p22 ? number_format($p22->selling_price_per_gram, 2) : '২০,৪৪৭.৫৩' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Gold Rate 21 K</strong>
                            <span class="d-block text-muted" style="font-size: 9px;">সোনা ২১ ক্যারেট (দর/গ্রাম)</span>
                        </td>
                        <td class="text-end font-weight-bold" style="font-size: 11px;">
                            ৳ {{ $p21 ? number_format($p21->selling_price_per_gram, 2) : '১৯,৪৩৫.০১' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Gold Rate 18 K</strong>
                            <span class="d-block text-muted" style="font-size: 9px;">সোনা ১৮ ক্যারেট (দর/গ্রাম)</span>
                        </td>
                        <td class="text-end font-weight-bold" style="font-size: 11px;">
                            ৳ {{ $p18 ? number_format($p18->selling_price_per_gram, 2) : '১৬,৬৮৫.০১' }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Right Box: Invoice Meta, Barcode, Delivery Date -->
            <div class="invoice-meta-card">
                <table class="meta-table">
                    <tr>
                        <td style="width: 42%;" class="text-muted">
                            <strong>Order No</strong> <span style="font-size: 9px;">অর্ডার নং</span>
                        </td>
                        <td class="font-weight-bold text-end text-primary" style="font-size: 11.5px; letter-spacing: 0.5px;">
                            {{ $order->order_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <strong>Order Date</strong> <span style="font-size: 9px;">অর্ডার গ্রহণের তারিখ</span>
                        </td>
                        <td class="text-end font-weight-bold" style="font-size: 10px;">
                            {{ $order->created_at->format('d/m/Y h:i A') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #fffbeb;">
                            <strong class="text-danger">Delivery Date</strong> <span style="font-size: 9px;" class="text-danger">ডেলিভারির তারিখ</span>
                        </td>
                        <td class="text-end font-weight-bold text-danger" style="font-size: 11px; background-color: #fffbeb;">
                            <i class="fa-regular fa-calendar-check me-1"></i>
                            {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M, Y (l)') : 'চুক্তি অনুযায়ী নির্ধারিত হবে' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 2px 8px 1px; text-align: center; background: #fff;">
                            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 1px;">
                                {!! generateBarcodeSvg($order->order_no, 20, 0.95, 2.2) !!}
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 9px; color: #334155;">
                                <span><strong>Status:</strong> {{ ucfirst($order->status) }}</span>
                                <span><strong>গৃহীত হয়েছে:</strong> মেসার্স মাদবী জুয়েলার্স</span>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Customer Information Section -->
        <div class="customer-info-section">
            <div class="customer-info-row">
                <div class="customer-field" style="flex: 1.5; min-width: 220px;">
                    <span class="field-lbl">Customer Name / গ্রাহকের নাম:</span>
                    <span class="field-val ms-1">{{ $order->customer->name ?? 'N/A' }} {{ $order->customer->last_name ?? '' }}</span>
                </div>
                <div class="customer-field" style="flex: 1; min-width: 170px;">
                    <span class="field-lbl">Mobile / মোবাইল নং:</span>
                    <span class="field-val ms-1">{{ $order->customer->phone ?? 'N/A' }}</span>
                </div>
                <div class="customer-field" style="flex: 1.5; min-width: 200px;">
                    <span class="field-lbl">Address / ঠিকানা:</span>
                    <span class="field-val ms-1">{{ $order->customer->address ?? 'N/A' }}</span>
                </div>
                @if($order->customer->phone2)
                <div class="customer-field" style="flex: 1; min-width: 150px;">
                    <span class="field-lbl">Alt. Mobile:</span>
                    <span class="field-val ms-1">{{ $order->customer->phone2 }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Pre-Order Items Table -->
        <table class="invoice-items-table">
            <thead>
                <tr>
                    <th style="width: 28px;">নং<br><span style="font-size: 8.5px; font-weight: 500;">Sl</span></th>
                    <th style="text-align: left; padding-left: 8px;">পণ্যের বিবরণ ও কারিগরি নির্দেশনা<br><span style="font-size: 8.5px; font-weight: 500;">Item Description & Specifications</span></th>
                    <th style="width: 60px;">ক্যারেট<br><span style="font-size: 8.5px; font-weight: 500;">Karat</span></th>
                    <th style="width: 38px;">পরিমাণ<br><span style="font-size: 8.5px; font-weight: 500;">Pcs</span></th>
                    <th style="width: 110px;">কাঙ্ক্ষিত ওজন (গ্রাম/ভরি)<br><span style="font-size: 8.5px; font-weight: 500;">Gross Weight</span></th>
                    <th style="width: 80px;">পাকা সোনা<br><span style="font-size: 8.5px; font-weight: 500;">Pure Gold</span></th>
                    <th style="width: 65px;">খাদ<br><span style="font-size: 8.5px; font-weight: 500;">Alloy</span></th>
                    <th style="width: 105px; text-align: right; padding-right: 8px;">আনুমানিক মূল্য<br><span style="font-size: 8.5px; font-weight: 500;">Estimated Price</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $idx => $it)
                @php
                    $alloyW = max(0, (float)$it->target_gram - (float)$it->raw_gold_needed);
                @endphp
                <tr>
                    <td class="text-center font-weight-bold">{{ $idx + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            @if($it->design_photo)
                                <img src="{{ asset($it->design_photo) }}" style="width: 32px; height: 32px; object-fit: cover; border-radius: 3px; margin-right: 6px; border: 1px solid #cbd5e1;" alt="Design">
                            @endif
                            <div>
                                <div class="item-desc-primary">{{ $it->product_name }}</div>
                                <div class="item-desc-sub">
                                    {{ $it->category->category_name ?? 'কাস্টম জুয়েলারি' }}
                                    @if($it->details)
                                        &bull; <span class="text-dark font-weight-bold">নোট:</span> {{ $it->details }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center font-weight-bold">
                        <span class="badge bg-light text-dark border">{{ $it->karat ?? '22K' }}</span>
                    </td>
                    <td class="text-center font-weight-bold">{{ $it->quantity ?? 1 }}</td>
                    <td class="text-center">
                        <span class="font-weight-bold">{{ number_format($it->target_gram, 3) }}g</span>
                        <div style="font-size: 8.5px; color: #475569;">
                            ({{ (float)($it->target_bhori ?? 0) }}ভরি {{ (float)($it->target_ana ?? 0) }}আনা {{ (float)($it->target_roti ?? 0) }}রতি {{ (float)($it->target_point ?? 0) }}পয়েন্ট)
                        </div>
                    </td>
                    <td class="text-center font-weight-bold text-danger">
                        {{ number_format($it->raw_gold_needed, 3) }}g
                    </td>
                    <td class="text-center text-secondary font-weight-bold">
                        {{ number_format($alloyW, 3) }}g
                    </td>
                    <td class="text-end font-weight-bold" style="padding-right: 8px;">
                        ৳ {{ number_format($it->estimated_price, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-start" style="padding-left: 8px;">
                        <strong>Total ({{ $totalQty }} {{ $totalQty > 1 ? 'Items' : 'Item' }})</strong>
                        <span class="text-muted ms-1">/ সর্বমোট ({{ numberToBanglaDigit($totalQty) }} টি পণ্য)</span>
                    </td>
                    <td class="text-center font-weight-bold">{{ $totalQty }}</td>
                    <td class="text-center font-weight-bold">{{ number_format($totalTargetGram, 3) }}g</td>
                    <td class="text-center font-weight-bold text-danger">{{ number_format($totalRawGold, 3) }}g</td>
                    <td class="text-center font-weight-bold text-secondary">{{ number_format($totalAlloy, 3) }}g</td>
                    <td class="text-end font-weight-bold" style="padding-right: 8px;">
                        ৳ {{ number_format($order->estimated_price, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Amount in Words -->
        <div class="words-box">
            <div class="words-en">
                <span class="text-muted">Estimated Total In Words:</span> 
                {{ numberToEnglishWords($order->grand_total ?: $order->estimated_price) }} Taka Only
            </div>
            <div class="words-bn mt-1">
                <span class="text-muted">কথায় (আনুমানিক মোট):</span> 
                {{ numberToBanglaWords($order->grand_total ?: $order->estimated_price) }} টাকা মাত্র
            </div>
            <div class="d-flex justify-content-between text-muted mt-1" style="font-size: 9px;">
                <span>Printed On: {{ now()->format('d/m/Y h:i:s A') }}</span>
                <span><strong>বি.দ্র:</strong> গহনা তৈরির পর চূড়ান্ত ওজনের উপর ভিত্তি করে মোট মূল্য চূড়ান্ত করা হবে।</span>
            </div>
        </div>

        <!-- Bottom Grid: Settlement & Financial Totals -->
        <div class="invoice-bottom-grid">
            <!-- Left Side: Partial Payments History & Stamps -->
            <div class="settlement-side">
                <!-- Payment & Installment History Box -->
                <div class="invoice-payment-history">
                    <div class="payment-history-header">
                        <span>
                            <i class="fa-solid fa-clock-rotate-left me-1 text-primary"></i> 
                            <strong>Payment & Installments History</strong> / কিস্তি ও অগ্রিম পরিশোধের বিবরণ
                        </span>
                        <span class="badge bg-light text-dark border font-monospace" style="font-size: 8px;">
                            {{ $order->payments->count() }} {{ $order->payments->count() > 1 ? 'Payments' : 'Payment' }}
                        </span>
                    </div>
                    <table class="payment-history-table">
                        <thead>
                            <tr>
                                <th style="width: 32px; text-align: center;">ধাপ</th>
                                <th style="text-align: left;">তারিখ ও সময় (Date & Time)</th>
                                <th style="text-align: left;">মাধ্যম (Method)</th>
                                <th style="text-align: right; width: 85px;">পরিশোধ (৳)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->payments as $p)
                            @php
                                $methodMap = [
                                    'cash'  => 'নগদ (Cash)',
                                    'bkash' => 'বিকাশ (bKash)',
                                    'nagad' => 'নগদ (Nagad)',
                                    'bank'  => 'ব্যাংক (Bank)',
                                    'card'  => 'কার্ড (Card)',
                                ];
                                $methodName = $methodMap[strtolower($p->payment_method)] ?? ucfirst($p->payment_method);
                                $paymentDate = $p->payment_date ? $p->payment_date->format('d/m/Y h:i A') : $p->created_at->format('d/m/Y h:i A');
                            @endphp
                            <tr>
                                <td style="text-align: center; font-weight: 700; color: #475569;">
                                    #{{ $p->payment_step ?? $loop->iteration }}
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #0f172a;">{{ $paymentDate }}</div>
                                    @if($p->note && $p->note !== '-')
                                        <div style="font-size: 8px; color: #64748b;">{{ $p->note }}</div>
                                    @endif
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: #334155;">{{ $methodName }}</span>
                                    @if($p->transaction_reference && $p->transaction_reference !== '-')
                                        <span class="d-block text-muted" style="font-size: 8px;">Ref: {{ $p->transaction_reference }}</span>
                                    @endif
                                </td>
                                <td style="text-align: right; font-weight: 700; color: #059669;">
                                    ৳ {{ number_format($p->amount, 2) }}
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; color: #64748b; padding: 6px;">
                                    কোনো অগ্রিম পেমেন্ট জমা হয়নি (সম্পূর্ণ বকেয়া)
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Payment Summary Footer -->
                    <div class="payment-history-summary">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                            <span><strong>Total Paid / মোট পরিশোধ:</strong></span>
                            <span style="font-weight: 800; color: #059669; font-size: 11px;">
                                ৳ {{ number_format($order->paid_amount, 2) }}
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            @if($order->due_amount > 0)
                                <span>
                                    <strong class="text-danger">Remaining Due / অবশিষ্ট বকেয়া:</strong>
                                    <span style="font-size: 8.5px; color: #64748b;">(ডেলিভারির সময় বা নির্ধারিত সময়ে পরিশোধযোগ্য)</span>
                                </span>
                                <span style="font-weight: 800; color: #dc2626; font-size: 11.5px;">
                                    ৳ {{ number_format($order->due_amount, 2) }}
                                </span>
                            @else
                                <span><strong class="text-success">Payment Status / অবস্থা:</strong></span>
                                <span style="font-weight: 800; color: #059669; font-size: 10px;">
                                    <i class="fa-solid fa-circle-check me-1"></i>সম্পূর্ণ পরিশোধিত (PAID)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Dual Authentic Jewelry Stamps -->
                <div class="stamp-container">
                    @if($order->payment_status === 'paid')
                        <div class="stamp-box">
                            <span style="font-size: 12px; line-height: 1;">PAID</span>
                            <span style="font-size: 8px; letter-spacing: 0.5px; font-weight: 700;">পরিশোধিত</span>
                        </div>
                    @elseif($order->payment_status === 'partial')
                        <div class="stamp-box stamp-partial">
                            <span style="font-size: 10.5px; line-height: 1;">PARTIAL PAID</span>
                            <span style="font-size: 7.5px; letter-spacing: 0.3px; font-weight: 700;">আংশিক পরিশোধ</span>
                        </div>
                    @else
                        <div class="stamp-box stamp-due">
                            <span style="font-size: 12px; line-height: 1;">PRE-ORDER DUE</span>
                            <span style="font-size: 8px; letter-spacing: 0.5px; font-weight: 700;">বকেয়া প্রি-অর্ডার</span>
                        </div>
                    @endif

                    <div class="stamp-circle">
                        <div class="stamp-circle-icon">M</div>
                        <div>MADOBI</div>
                        <div style="font-size: 6.5px; line-height: 1; margin-top: 1px;">কাস্টম অর্ডার</div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Financial Totals Table -->
            <div class="totals-side">
                <table class="totals-table">
                    <tr>
                        <td style="width: 58%;" class="text-muted">
                            Estimated Price <span style="font-size: 9.5px;">/ আনুমানিক মূল্য</span>
                        </td>
                        <td class="text-end font-weight-bold">
                            ৳ {{ number_format($order->estimated_price, 2) }}
                        </td>
                    </tr>
                    @if($order->actual_price && $order->actual_price > 0 && $order->actual_price != $order->estimated_price)
                    <tr>
                        <td class="text-muted">
                            Final Billed Price <span style="font-size: 9.5px;">/ চূড়ান্ত প্রস্তুত মূল্য</span>
                        </td>
                        <td class="text-end font-weight-bold text-primary">
                            ৳ {{ number_format($order->actual_price, 2) }}
                        </td>
                    </tr>
                    @endif
                    @if($order->discount > 0)
                    <tr>
                        <td class="text-muted">
                            Special Discount <span style="font-size: 9.5px;">/ বিশেষ ছাড়</span>
                        </td>
                        <td class="text-end text-success font-weight-bold">
                            - ৳ {{ number_format($order->discount, 2) }}
                        </td>
                    </tr>
                    @endif
                    <tr class="grand-total-row">
                        <td>
                            GRAND TOTAL <span style="font-size: 10px; font-weight: 600;">/ সর্বমোট মূল্য</span>
                        </td>
                        <td class="text-end">
                            ৳ {{ number_format($order->grand_total ?: $order->estimated_price, 2) }}
                        </td>
                    </tr>
                    <tr class="total-row-highlight">
                        <td class="text-success">
                            Total Paid <span style="font-size: 9.5px;">/ মোট পরিশোধ (অগ্রিম)</span>
                        </td>
                        <td class="text-end text-success font-weight-bold">
                            ৳ {{ number_format($order->paid_amount, 2) }}
                        </td>
                    </tr>
                    <tr class="total-row-highlight" style="border-top: 1.5px solid #0f172a;">
                        <td class="text-danger">
                            Total Due <span style="font-size: 9.5px;">/ অবশিষ্ট বকেয়া</span>
                        </td>
                        <td class="text-end text-danger font-weight-bold" style="font-size: 12px;">
                            ৳ {{ number_format($order->due_amount, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted" style="font-size: 9.5px;">
                            Delivery Status <span style="font-size: 8.5px;">/ অবস্থা</span>
                        </td>
                        <td class="text-end font-weight-bold" style="font-size: 10px;">
                            @if($order->status === 'delivered')
                                <span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>ডেলিভারড</span>
                            @elseif($order->status === 'ready_for_delivery')
                                <span class="text-info"><i class="fa-solid fa-box-open me-1"></i>ডেলিভারির জন্য প্রস্তুত</span>
                            @else
                                <span class="text-warning"><i class="fa-solid fa-spinner fa-spin me-1"></i>তৈরি হচ্ছে</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Pre-Order Terms & Conditions -->
        <div class="preorder-terms-card">
            <strong style="color: #0f172a;"><i class="fa-solid fa-file-contract me-1"></i>কাস্টম জুয়েলারি প্রি-অর্ডার সংক্রান্ত নিয়ম ও শর্তাবলী (Terms & Conditions):</strong>
            <ol>
                <li>ডেলিভারি গ্রহণের সময় গ্রাহককে অবশ্যই মূল প্রি-অর্ডার মেমোটি প্রদর্শন করতে হবে।</li>
                <li>হাতে তৈরির কারণে অলংকারের চূড়ান্ত ওজনে সামান্য তারতম্য হতে পারে এবং প্রকৃত ওজন (Actual Weight) ও ক্যারেট অনুযায়ী চূড়ান্ত বিল সমন্বয় করা হবে।</li>
                <li>সম্ভাব্য ডেলিভারির তারিখের মধ্যে পণ্য হস্তান্তরের সর্বোচ্চ চেষ্টা করা হবে, তবে কারিগরি জটিলতার ক্ষেত্রে সময় বৃদ্ধি হতে পারে।</li>
                <li>ডেলিভারির সময় বা চূড়ান্ত হস্তান্তরের পূর্বে অবশিষ্ট সমুদয় বকেয়া পরিশোধ করতে হবে।</li>
            </ol>
        </div>

        <!-- Signatures -->
        <div class="invoice-signatures">
            <div class="sig-line">
                গ্রাহকের স্বাক্ষর<br>
                <span style="font-size: 8.5px; color: #64748b;">Customer's Signature</span>
            </div>
            <div class="sig-line">
                মেসার্স মাদবী জুয়েলার্স<br>
                <span style="font-size: 8.5px; color: #64748b;">Authorized Signatory</span>
            </div>
        </div>

        <!-- Bottom Banner: Address & Contacts -->
        <div class="invoice-bottom-banner">
            <div>
                <strong>MADOBI JEWELERS</strong> &bull; সোনার ও রূপার আধুনিক অলংকার প্রস্তুতকারক ও বিক্রেতা
            </div>
            <div style="font-size: 8.5px; opacity: 0.9; margin-top: 1px;">
                দোকান নং: ১২-১৩, জুয়েলারি মার্কেট, বায়তুল মোকাররম, ঢাকা-১০০০ &bull; ফোন: +৮৮০ ১২৩৪-৫৬৭৮৯০, +৮৮০ ১৮১২-৩৪৫৬৭৮
            </div>
            <div style="font-size: 8px; opacity: 0.8; margin-top: 1px;">
                কাস্টমার সাপোর্ট: support@madobijewelers.com &bull; ওয়েবসাইট: www.madobijewelers.com
            </div>
        </div>

    </div>
</div>
@endsection
