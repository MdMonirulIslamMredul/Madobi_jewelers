@extends('admin.master')

@section('title')
ইনভয়েস - {{ $sell->invoice_no }}
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
        border-bottom: 1px solid #cbd5e1;
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
        border-bottom: 1px solid #cbd5e1;
    }

    .meta-table tr:last-child td {
        border-bottom: none;
    }

    /* Customer Info Box */
    .customer-info-section {
        border-top: 1px solid #94a3b8;
        border-bottom: 1px solid #94a3b8;
        padding: 5px 4px;
        margin-bottom: 8px;
        font-size: 11px;
    }

    .customer-info-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }

    .customer-field {
        margin-bottom: 2px;
    }

    .field-lbl {
        color: #475569;
        font-weight: 600;
    }

    .field-val {
        color: #0f172a;
        font-weight: 700;
    }

    /* Items Table */
    .invoice-items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 6px;
        font-size: 10.5px;
    }

    .invoice-items-table th {
        background-color: #f8fafc;
        color: #0f172a;
        font-weight: 700;
        border: 1px solid #0f172a;
        padding: 4px 6px;
        text-align: center;
        vertical-align: middle;
        line-height: 1.25;
    }

    .invoice-items-table td {
        border: 1px solid #475569;
        padding: 4px 6px;
        vertical-align: middle;
        color: #0f172a;
    }

    .invoice-items-table tfoot td {
        font-weight: 700;
        background-color: #f8fafc;
        border: 1px solid #0f172a;
        padding: 4px 6px;
    }

    .item-desc-primary {
        font-weight: 700;
        font-size: 11px;
        color: #0f172a;
    }

    .item-desc-sub {
        font-size: 9.5px;
        color: #475569;
    }

    /* Exchange Items Table */
    .invoice-exchange-section {
        margin-top: 8px;
        margin-bottom: 6px;
    }

    .exchange-section-title {
        background-color: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        padding: 3px 8px;
        font-size: 10.5px;
        font-weight: 700;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 3px 3px 0 0;
    }

    .invoice-exchange-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
        margin-top: -1px;
    }

    .invoice-exchange-table th {
        background-color: #f8fafc;
        color: #0f172a;
        font-weight: 700;
        border: 1px solid #475569;
        padding: 3px 6px;
        text-align: center;
        vertical-align: middle;
        line-height: 1.2;
    }

    .invoice-exchange-table td {
        border: 1px solid #475569;
        padding: 3px 6px;
        vertical-align: middle;
        color: #0f172a;
    }

    .invoice-exchange-table tfoot td {
        font-weight: 700;
        background-color: #f0fdf4;
        border: 1px solid #475569;
        padding: 3px 6px;
    }

    /* Financial Summary & Settlement Grid */
    .invoice-bottom-grid {
        display: flex;
        gap: 14px;
        margin-top: 4px;
        align-items: flex-start;
    }

    .settlement-side {
        flex: 1.2;
    }

    .totals-side {
        flex: 1;
    }

    .totals-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11px;
        border: 1.2px solid #0f172a;
    }

    .totals-table td {
        padding: 3.5px 8px;
        border-bottom: 1px solid #cbd5e1;
    }

    .totals-table tr:last-child td {
        border-bottom: none;
    }

    .totals-grand-row {
        background-color: #f1f5f9;
        font-weight: 800;
        font-size: 12px;
        color: #0f172a;
        border-top: 1.5px solid #0f172a !important;
    }

    .words-box {
        margin: 4px 0 8px;
        padding: 4px 8px;
        background: #f8fafc;
        border: 1px dashed #94a3b8;
        font-size: 10px;
        line-height: 1.4;
    }

    .words-en {
        font-weight: 600;
        color: #0f172a;
    }

    .words-bn {
        color: #334155;
    }

    .settlement-details {
        font-size: 10.5px;
        line-height: 1.6;
    }

    /* Payment History Table in Invoice */
    .invoice-payment-history {
        border: 1.2px solid #0f172a;
        background: #fff;
        margin-bottom: 6px;
    }

    .payment-history-header {
        background-color: #f1f5f9;
        border-bottom: 1px solid #0f172a;
        padding: 3px 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 10px;
        font-weight: 700;
        color: #0f172a;
    }

    .payment-history-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5px;
    }

    .payment-history-table th {
        background-color: #f8fafc;
        border-bottom: 1px solid #cbd5e1;
        padding: 2.5px 5px;
        font-weight: 700;
        color: #475569;
        font-size: 8.5px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .payment-history-table td {
        padding: 3px 5px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        line-height: 1.25;
    }

    .payment-history-table tr:last-child td {
        border-bottom: none;
    }

    .payment-history-summary {
        background-color: #f8fafc;
        border-top: 1px solid #0f172a;
        padding: 4px 6px;
        font-size: 10px;
        line-height: 1.4;
    }

    /* Authentic Jewelry Stamps */
    .stamp-container {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 8px;
    }

    .stamp-box {
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
        opacity: 0.9;
    }

    .stamp-paid {
        color: #059669;
        border-color: #059669;
        transform: rotate(-6deg);
    }

    .stamp-partial {
        color: #d97706;
        border-color: #d97706;
        transform: rotate(-5deg);
    }

    .stamp-due {
        color: #dc2626;
        border-color: #dc2626;
        transform: rotate(-7deg);
    }

    .stamp-circle {
        width: 52px;
        height: 52px;
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
        letter-spacing: 1px;
        text-align: center;
        opacity: 0.9;
    }

    .stamp-circle-icon {
        font-family: 'Cinzel', serif;
        font-size: 13px;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 1px;
    }

    /* Signatures Section */
    .invoice-signatures {
        display: flex;
        justify-content: space-between;
        margin-top: 28px;
        padding-top: 8px;
        text-align: center;
    }

    .sig-col {
        flex: 1;
        padding: 0 10px;
    }

    .sig-line {
        border-top: 1.2px solid #0f172a;
        margin-bottom: 3px;
    }

    .sig-title-bn {
        font-size: 10.5px;
        font-weight: 700;
        color: #0f172a;
    }

    .sig-title-en {
        font-size: 9px;
        font-weight: 600;
        letter-spacing: 0.8px;
        color: #475569;
        text-transform: uppercase;
    }

    /* Footer Info & Dark Bar */
    .invoice-footer-info {
        margin-top: 14px;
        border-top: 1px solid #cbd5e1;
        padding-top: 5px;
        font-size: 9.5px;
        color: #475569;
        display: flex;
        justify-content: space-between;
        line-height: 1.4;
    }

    .invoice-bottom-banner {
        background-color: #0f172a;
        color: #ffffff;
        font-size: 9px;
        text-align: center;
        padding: 3px 8px;
        margin-top: 5px;
        border-radius: 2px;
        letter-spacing: 0.5px;
    }

    /* =========================================================
       PRINT SPECIFIC RULES (A4 Perfect 1-Page Fit)
       ========================================================= */
    @media print {
        @page {
            size: A4 portrait;
            margin: 6mm 8mm;
        }

        html, body {
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
        .invoice-exchange-table th,
        .invoice-exchange-table td,
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
    
    // Fetch current gold rates dynamically
    $productPrices = \App\Models\ProductPrice::all()->keyBy('product_name');
    $p22 = $productPrices->get('Gold 22k') ?? $productPrices->get('Gold 22K');
    $p21 = $productPrices->get('Gold 21k') ?? $productPrices->get('Gold 21K');
    $p18 = $productPrices->get('Gold 18k') ?? $productPrices->get('Gold 18K');
    
    $totalQty = count($sell->items);
    $totalGram = $sell->items->sum('gram');
    $totalBhori = $sell->items->sum('bhori');
@endphp

<div class="invoice-preview-wrapper">
    <!-- Top Action Bar (Screen Only) -->
    <div class="invoice-action-bar no-print d-flex justify-content-between align-items-center">
        <a href="{{ route('instant-sells.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> বিক্রয় তালিকা
        </a>
        <div class="d-flex align-items-center gap-2">
            <button type="button" class="btn btn-outline-info btn-sm shadow-sm" onclick="openPaymentHistoryModal('instant_sell', {{ $sell->id }})">
                <i class="fa-solid fa-hand-holding-dollar me-1"></i> কিস্তি পরিশোধের ইতিহাস
            </button>
            <button type="button" class="btn btn-primary btn-sm shadow-sm px-3" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> প্রিন্ট ইনভয়েস (A4)
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
            <div class="invoice-main-heading-bn">ট্যাক্স ইনভয়েস / ক্যাশ মেমো</div>
            <div class="invoice-main-heading-en">TAX INVOICE / CASH MEMO</div>
            <div class="invoice-tax-reg">TAX REG. NO: 310122866600003 &nbsp;|&nbsp; ভ্যাট / BIN: 004829174-0101 &nbsp;|&nbsp; ট্রেড লাইসেন্স নং: ১৮৪৯২</div>
        </div>

        <!-- Upper Info Grid (Gold Rates & Invoice/Salesman Meta) -->
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
                            ৳ {{ $p22 ? number_format($p22->selling_price_per_gram, 2) : '১৯,৭৫৫.০০' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Gold Rate 21 K</strong>
                            <span class="d-block text-muted" style="font-size: 9px;">সোনা ২১ ক্যারেট (দর/গ্রাম)</span>
                        </td>
                        <td class="text-end font-weight-bold" style="font-size: 11px;">
                            ৳ {{ $p21 ? number_format($p21->selling_price_per_gram, 2) : '১৮,৮৬০.০০' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Gold Rate 18 K</strong>
                            <span class="d-block text-muted" style="font-size: 9px;">সোনা ১৮ ক্যারেট (দর/গ্রাম)</span>
                        </td>
                        <td class="text-end font-weight-bold" style="font-size: 11px;">
                            ৳ {{ $p18 ? number_format($p18->selling_price_per_gram, 2) : '১৬,১৬৫.০০' }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Right Box: Invoice Meta, Barcode, Salesman -->
            <div class="invoice-meta-card">
                <table class="meta-table">
                    <tr>
                        <td style="width: 40%;" class="text-muted">
                            <strong>Invoice No</strong> <span style="font-size: 9px;">চালান নং</span>
                        </td>
                        <td class="font-weight-bold text-end" style="font-size: 11.5px; letter-spacing: 0.5px;">
                            {{ $sell->invoice_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            <strong>Date & Time</strong> <span style="font-size: 9px;">তারিখ ও সময়</span>
                        </td>
                        <td class="text-end" style="font-size: 10.5px;">
                            {{ $sell->created_at->format('d/m/Y H:i:s') }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 2px 8px 1px; text-align: center; background: #fff;">
                            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 1px;">
                                {!! generateBarcodeSvg($sell->invoice_no, 22, 0.95, 2.4) !!}
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 9.5px; color: #334155;">
                                <span><strong>Salesman:</strong> {{ $sell->seller->name ?? 'Admin' }}</span>
                                <span><strong>বিক্রেতা:</strong> {{ $sell->seller->name ?? 'Admin' }}</span>
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
                    <span class="field-val ms-1">{{ $sell->customer->name ?? 'N/A' }} {{ $sell->customer->last_name ?? '' }}</span>
                </div>
                <div class="customer-field" style="flex: 1; min-width: 180px;">
                    <span class="field-lbl">Mobile / মোবাইল নং:</span>
                    <span class="field-val ms-1">{{ $sell->customer->phone ?? 'N/A' }}</span>
                </div>
                <div class="customer-field" style="flex: 1.5; min-width: 200px;">
                    <span class="field-lbl">Address / ঠিকানা:</span>
                    <span class="field-val ms-1">{{ $sell->customer->address ?? 'ঢাকা, বাংলাদেশ' }}</span>
                </div>
                @if($sell->customer->extra_info)
                <div class="customer-field" style="flex: 1; min-width: 150px;">
                    <span class="field-lbl">ID / NID No:</span>
                    <span class="field-val ms-1">{{ $sell->customer->extra_info }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Details Table -->
        <table class="invoice-items-table">
            <thead>
                <tr>
                    <th style="width: 30px;">
                        নং<br><span style="font-size: 8.5px; font-weight: 500;">Sl#.</span>
                    </th>
                    <th style="width: 85px;">
                        স্টক কোড<br><span style="font-size: 8.5px; font-weight: 500;">Stock Code</span>
                    </th>
                    <th style="text-align: left; padding-left: 8px;">
                        পণ্যের বিবরণ<br><span style="font-size: 8.5px; font-weight: 500;">Description</span>
                    </th>
                    <th style="width: 42px;">
                        পরিমাণ<br><span style="font-size: 8.5px; font-weight: 500;">Pcs</span>
                    </th>
                    <th style="width: 80px;">
                        মোট ওজন<br><span style="font-size: 8.5px; font-weight: 500;">G.Wt</span>
                    </th>
                    <th style="width: 65px;">
                        পাথর/খাদ<br><span style="font-size: 8.5px; font-weight: 500;">St. Wt</span>
                    </th>
                    <th style="width: 80px;">
                        নিট ওজন<br><span style="font-size: 8.5px; font-weight: 500;">Net Wt</span>
                    </th>
                    <th style="width: 110px; text-align: right; padding-right: 8px;">
                        মোট মূল্য<br><span style="font-size: 8.5px; font-weight: 500;">Total Amount</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($sell->items as $idx => $item)
                @php
                    $stockCode = 'JD' . str_pad($item->purchase_id ?? $item->id, 6, '0', STR_PAD_LEFT);
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center font-weight-bold" style="font-size: 10px; letter-spacing: 0.3px;">
                        {{ $stockCode }}
                    </td>
                    <td>
                        <div class="item-desc-primary">{{ $item->product_name }}</div>
                        <div class="item-desc-sub">
                            {{ $item->category_name }} &bull; {{ $item->karat }} Karat / ক্যারেট
                            @if($item->bhori > 0 || $item->ana > 0)
                                &bull; <span style="color: #334155;">({{ (float)$item->bhori }}ভরি {{ (float)$item->ana }}আনা {{ (float)$item->roti }}রতি)</span>
                            @endif
                        </div>
                    </td>
                    <td class="text-center font-weight-bold">1</td>
                    <td class="text-center font-weight-bold">
                        {{ number_format($item->gram, 3) }}g
                    </td>
                    <td class="text-center text-muted">
                        0.00g
                    </td>
                    <td class="text-center font-weight-bold">
                        {{ number_format($item->gram, 3) }}g
                    </td>
                    <td class="text-end font-weight-bold" style="padding-right: 8px;">
                        {{ number_format($item->selling_price, 2) }}
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
                    <td class="text-center">{{ $totalQty }}</td>
                    <td class="text-center">{{ number_format($totalGram, 3) }}g</td>
                    <td class="text-center text-muted">0.00g</td>
                    <td class="text-center">{{ number_format($totalGram, 3) }}g</td>
                    <td class="text-end font-weight-bold" style="padding-right: 8px;">
                        ৳ {{ number_format($sell->subtotal, 2) }}
                    </td>
                </tr>
            </tfoot>
        </table>

        @if($sell->exchangeItems && $sell->exchangeItems->count() > 0)
        <!-- Old Jewelry Exchange Details Breakdown Table -->
        <div class="invoice-exchange-section">
            <div class="exchange-section-title">
                <span>
                    <i class="fa-solid fa-arrows-rotate me-1"></i>
                    <strong>পুরাতন গয়না এক্সচেঞ্জ বিবরণী</strong> (Exchange Details Breakdown)
                </span>
                <span class="badge bg-success text-white" style="font-size: 8px;">
                    {{ $sell->exchangeItems->count() }} {{ $sell->exchangeItems->count() > 1 ? 'Items' : 'Item' }}
                </span>
            </div>
            <table class="invoice-exchange-table">
                <thead>
                    <tr>
                        <th style="width: 30px;">নং<br><span style="font-size: 8px; font-weight: 500;">Sl#.</span></th>
                        <th style="width: 85px;">স্টক কোড<br><span style="font-size: 8px; font-weight: 500;">Hold Code</span></th>
                        <th style="text-align: left; padding-left: 8px;">আইটেম বিবরণী<br><span style="font-size: 8px; font-weight: 500;">Item Description</span></th>
                        <th style="width: 90px;">ধাতু ও ক্যারেট<br><span style="font-size: 8px; font-weight: 500;">Metal / Karat</span></th>
                        <th style="width: 140px;">ওজন বিবরণী<br><span style="font-size: 8px; font-weight: 500;">Weight (V-A-R-P & Gram)</span></th>
                        <th style="width: 100px;">ক্রয় রেট<br><span style="font-size: 8px; font-weight: 500;">Buy Rate (৳/g)</span></th>
                        <th style="width: 70px;">হার (%)<br><span style="font-size: 8px; font-weight: 500;">Rate %</span></th>
                        <th style="width: 110px; text-align: right; padding-right: 8px;">এক্সচেঞ্জ মূল্য<br><span style="font-size: 8px; font-weight: 500;">Exchange Val (৳)</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sell->exchangeItems as $eIdx => $exItem)
                    @php
                        $holdCode = $exItem->purchase_id ? ('EX-HD' . str_pad($exItem->purchase_id, 5, '0', STR_PAD_LEFT)) : 'EX-HOLD';
                    @endphp
                    <tr>
                        <td class="text-center">{{ $eIdx + 1 }}</td>
                        <td class="text-center font-weight-bold" style="font-size: 9.5px; color: #b45309;">
                            {{ $holdCode }}
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($exItem->photo && $exItem->photo !== 'default-cover.jpg')
                                    <img src="{{ asset('user/purchase/' . $exItem->photo) }}" alt="Photo" style="width: 22px; height: 22px; object-fit: cover; border-radius: 2px; border: 1px solid #cbd5e1;">
                                @endif
                                <div>
                                    <div class="item-desc-primary">{{ $exItem->product_description ?: 'পুরাতন গয়না' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="text-center font-weight-bold">
                            {{ $exItem->metal_type }} ({{ $exItem->karat }})
                        </td>
                        <td class="text-center">
                            {{ (float)$exItem->bhori }}ভ, {{ (float)$exItem->ana }}আ, {{ (float)$exItem->roti }}র, {{ (float)$exItem->point }}প
                            <span class="d-block text-muted font-weight-bold" style="font-size: 8.5px;">({{ number_format($exItem->gram, 3) }} গ্রাম)</span>
                        </td>
                        <td class="text-center">
                            ৳ {{ number_format($exItem->buy_rate_per_gram, 2) }}/g
                        </td>
                        <td class="text-center font-weight-bold text-primary">
                            {{ (float)$exItem->applied_rate_percent }}%
                        </td>
                        <td class="text-end font-weight-bold text-success" style="padding-right: 8px;">
                            ৳ {{ number_format($exItem->exchange_value, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-start" style="padding-left: 8px;">
                            <strong>মোট এক্সচেঞ্জ পণ্য (Total Exchange): {{ $sell->exchangeItems->count() }} টি</strong>
                        </td>
                        <td class="text-center font-weight-bold">
                            {{ number_format($sell->exchangeItems->sum('gram'), 3) }} গ্রাম
                        </td>
                        <td colspan="2"></td>
                        <td class="text-end font-weight-bold text-success" style="padding-right: 8px; font-size: 11px;">
                            -৳ {{ number_format($sell->total_exchange_amount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        <!-- Amount in Words -->
        <div class="words-box">
            @php
                $payableWordsAmt = ($sell->total_exchange_amount > 0 && isset($sell->net_payable)) ? $sell->net_payable : $sell->grand_total;
            @endphp
            <div class="words-en">
                <span class="text-muted">In Words:</span> 
                {{ numberToEnglishWords($payableWordsAmt) }} Taka Only
            </div>
            <div class="words-bn mt-1">
                <span class="text-muted">কথায়:</span> 
                {{ numberToBanglaWords($payableWordsAmt) }} টাকা মাত্র
            </div>
            @if($sell->excess_exchange_amount > 0)
            <div class="mt-1 font-weight-bold text-primary" style="font-size: 9.5px;">
                * এক্সচেঞ্জ অতিরিক্ত ব্যালেন্স / Surplus: ৳ {{ number_format($sell->excess_exchange_amount, 2) }} 
                ({{ in_array($sell->excess_exchange_action, ['customer_credit', 'credit']) ? 'গ্রাহকের ব্যালেন্সে জমা করা হয়েছে' : 'নগদ ফেরত দেওয়া হয়েছে' }})
            </div>
            @endif
            <div class="text-muted mt-1" style="font-size: 9px;">
                Printed Date & Time / প্রিন্টের সময়: {{ now()->format('d/m/Y h:i:s A') }}
            </div>
        </div>

        <!-- Bottom Grid: Settlement & Financial Totals -->
        <div class="invoice-bottom-grid">
            <!-- Left Side: Payment History & Settlement Details -->
            <div class="settlement-side">
                <!-- Payment & Installment History Box with Date -->
                <div class="invoice-payment-history">
                    <div class="payment-history-header">
                        <span>
                            <i class="fa-solid fa-clock-rotate-left me-1 text-primary"></i> 
                            <strong>Payment History</strong> / কিস্তি ও পরিশোধের বিবরণ
                        </span>
                        <span class="badge bg-light text-dark border font-monospace" style="font-size: 8px;">
                            {{ $sell->payments->count() }} {{ $sell->payments->count() > 1 ? 'Payments' : 'Payment' }}
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
                            @forelse($sell->payments as $p)
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
                                    কোনো পেমেন্ট জমা হয়নি (সম্পূর্ণ বকেয়া)
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
                                ৳ {{ number_format($sell->paid_amount, 2) }}
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            @if($sell->due_amount > 0)
                                <span>
                                    <strong class="text-danger">Due Amount / অবশিষ্ট বকেয়া:</strong>
                                    @if($sell->due_date)
                                        <span style="font-size: 8.5px; color: #64748b;">
                                            (পরিশোধের শেষ তারিখ: {{ \Carbon\Carbon::parse($sell->due_date)->format('d/m/Y') }})
                                        </span>
                                    @endif
                                </span>
                                <span style="font-weight: 800; color: #dc2626; font-size: 11.5px;">
                                    ৳ {{ number_format($sell->due_amount, 2) }}
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

                <div class="settlement-details">
                    <div class="text-muted" style="font-size: 9.5px;">
                        Confirmed on behalf of / গ্রাহকের সম্মতি: <strong>{{ $sell->customer->name ?? 'N/A' }}</strong>
                    </div>
                    <div class="text-muted" style="font-size: 9px;">
                        For <strong>MADOBI JEWELERS LTD.</strong>
                    </div>
                </div>

                <!-- Dual Authentic Jewelry Stamps -->
                <div class="stamp-container">
                    @if($sell->payment_status === 'paid')
                        <div class="stamp-box stamp-paid">
                            <span style="font-size: 12px; line-height: 1;">PAID</span>
                            <span style="font-size: 8px; letter-spacing: 0.5px; font-weight: 700;">পরিশোধিত</span>
                        </div>
                    @elseif($sell->payment_status === 'partial')
                        <div class="stamp-box stamp-partial">
                            <span style="font-size: 10.5px; line-height: 1;">PARTIAL</span>
                            <span style="font-size: 7.5px; letter-spacing: 0.3px; font-weight: 700;">আংশিক পরিশোধ</span>
                        </div>
                    @else
                        <div class="stamp-box stamp-due">
                            <span style="font-size: 12px; line-height: 1;">DUE</span>
                            <span style="font-size: 8px; letter-spacing: 0.5px; font-weight: 700;">বকেয়া</span>
                        </div>
                    @endif

                    <div class="stamp-circle">
                        <div class="stamp-circle-icon">M</div>
                        <div>DELIVERED</div>
                        <div style="font-size: 6.5px; line-height: 1; margin-top: 1px;">মাদবী</div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Totals Table -->
            <div class="totals-side">
                <table class="totals-table">
                    <tr>
                        <td style="width: 60%;" class="text-muted">
                            Net Total <span style="font-size: 9.5px;">/ নিট মোট</span>
                        </td>
                        <td class="text-end font-weight-bold">
                            ৳ {{ number_format($sell->subtotal, 2) }}
                        </td>
                    </tr>
                    @if($sell->karigor_mojuri_total > 0)
                    <tr>
                        <td class="text-muted">
                            Karigor Mojuri <span style="font-size: 9.5px;">/ কারিগর মজুরি</span>
                            @if($sell->karigor_mojuri_rate > 0)
                                <small class="text-muted d-block" style="font-size: 8.5px;">(@ ৳ {{ number_format($sell->karigor_mojuri_rate, 2) }}/g)</small>
                            @endif
                        </td>
                        <td class="text-end font-weight-bold text-dark">
                            +৳ {{ number_format($sell->karigor_mojuri_total, 2) }}
                        </td>
                    </tr>
                    @endif
                    @if($sell->discount > 0)
                    <tr>
                        <td class="text-danger">
                            Discount <span style="font-size: 9.5px;">/ ছাড়</span>
                        </td>
                        <td class="text-end font-weight-bold text-danger">
                            -৳ {{ number_format($sell->discount, 2) }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">
                            Total Taxable Amount <span style="font-size: 9.5px;">/ করযোগ্য মূল্য</span>
                        </td>
                        <td class="text-end font-weight-bold">
                            ৳ {{ number_format($sell->subtotal + ($sell->karigor_mojuri_total ?? 0) - $sell->discount, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">
                            Total VAT <span style="font-size: 9.5px;">/ মোট ভ্যাট</span>
                        </td>
                        <td class="text-end font-weight-bold">
                            ৳ {{ number_format($sell->vat_tax, 2) }}
                        </td>
                    </tr>
                    <tr class="totals-grand-row">
                        <td style="padding: 5px 8px;">
                            TOTAL <span style="font-size: 10px; font-weight: 700;">/ সর্বমোট বিল</span>
                        </td>
                        <td class="text-end" style="padding: 5px 8px; font-size: 13px;">
                            ৳ {{ number_format($sell->grand_total, 2) }}
                        </td>
                    </tr>
                    @if($sell->total_exchange_amount > 0)
                    <tr style="color: #166534;">
                        <td class="text-success font-weight-bold">
                            Exchange Deduct <span style="font-size: 9.5px;">/ এক্সচেঞ্জ কর্তন</span>
                        </td>
                        <td class="text-end font-weight-bold text-success">
                            -৳ {{ number_format($sell->total_exchange_amount, 2) }}
                        </td>
                    </tr>
                    <tr class="totals-grand-row" style="background: #fef08a; border-top: 1.5px solid #ca8a04;">
                        <td style="padding: 5px 8px; color: #854d0e;">
                            NET PAYABLE <span style="font-size: 10px; font-weight: 700;">/ চূড়ান্ত প্রদেয় বিল</span>
                        </td>
                        <td class="text-end font-weight-bold" style="padding: 5px 8px; font-size: 13px; color: #854d0e;">
                            ৳ {{ number_format($sell->net_payable ?? $sell->grand_total, 2) }}
                        </td>
                    </tr>
                    @if($sell->excess_exchange_amount > 0)
                    <tr style="background: #e0f2fe;">
                        <td class="text-primary font-weight-bold" style="font-size: 9.5px;">
                            Surplus <span style="font-size: 8.5px;">/ ফেরতযোগ্য ({{ in_array($sell->excess_exchange_action, ['customer_credit', 'credit']) ? 'গ্রাহক ব্যালেন্সে জমা' : 'নগদ ফেরত' }})</span>
                        </td>
                        <td class="text-end font-weight-bold text-primary" style="font-size: 11px;">
                            ৳ {{ number_format($sell->excess_exchange_amount, 2) }}
                        </td>
                    </tr>
                    @endif
                    @endif
                </table>
            </div>
        </div>

        <!-- Notes / Remarks if any -->
        @if($sell->notes)
        <div style="margin-top: 6px; padding: 3px 8px; background: #fff; border: 1px dashed #cbd5e1; font-size: 9.5px;">
            <strong>নোট / Note:</strong> {{ $sell->notes }}
        </div>
        @endif

        <!-- 3-Column Signatures (Exact Match to Demo Image) -->
        <div class="invoice-signatures">
            <div class="sig-col">
                <div class="sig-line"></div>
                <div class="sig-title-bn">গ্রাহকের স্বাক্ষর</div>
                <div class="sig-title-en">Customer's Signature</div>
            </div>
            <div class="sig-col">
                <div class="sig-line"></div>
                <div class="sig-title-bn">বিল প্রস্তুতকারক</div>
                <div class="sig-title-en">Invoice Checked By</div>
            </div>
            <div class="sig-col">
                <div class="sig-line"></div>
                <div class="sig-title-bn">অনুমোদিত স্বাক্ষর</div>
                <div class="sig-title-en">Authorised Signature</div>
                <div style="font-size: 8px; color: #475569; margin-top: 1px;">For MADOBI JEWELERS</div>
            </div>
        </div>

        <!-- Footer Address & Contacts -->
        <div class="invoice-footer-info">
            <div>
                <strong>MADOBI JEWELERS (মাদবী জুয়েলার্স)</strong><br>
                বায়তুল মোকাররম মার্কেট, ঢাকা-১০০০, বাংলাদেশ<br>
                হটলাইন: +৮৮০ ১৯১১-XXXXXX, ফোন: +৮৮০ ১৭২১-XXXXXX
            </div>
            <div class="text-end">
                সোনার ও রূপার খাঁটি অলংকার প্রস্তুতকারক<br>
                বিক্রয়ের সময় মেমো সাথে আনুন ও যত্নসহকারে সংরক্ষণ করুন<br>
                ট্রেড লাইসেন্স নং: ১৮৪৯২ &bull; মূসক / BIN: 004829174-0101
            </div>
        </div>

        <!-- Bottom Dark Banner -->
        <div class="invoice-bottom-banner">
            Email: info@madobijewelers.com &nbsp;|&nbsp; Web: www.madobijewelers.com &nbsp;|&nbsp; ১০০% খাঁটি মান ও আস্থার প্রতীক &nbsp;|&nbsp; Madobi Jewelers
        </div>

    </div>
</div>

@include('admin.sell.payment_history_modal')
@endsection
