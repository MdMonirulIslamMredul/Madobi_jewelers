@extends('admin.master')

@section('title')
কারিগর জব কার্ড - {{ $order->order_no }}
@endsection

@push('admin_style')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* =========================================================
       Screen Preview Styling
       ========================================================= */
    .karigor-preview-wrapper {
        background-color: #f1f5f9;
        padding: 24px 0 60px;
        min-height: 100vh;
        font-family: 'Outfit', 'Hind Siliguri', 'Segoe UI', sans-serif;
    }

    .karigor-a4-sheet {
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
    .karigor-action-bar {
        max-width: 210mm;
        margin: 0 auto 16px;
    }

    /* Header Styling */
    .karigor-header-brand {
        text-align: center;
        margin-bottom: 6px;
    }

    .karigor-logo-img {
        max-height: 52px;
        width: auto;
        object-fit: contain;
        display: inline-block;
        margin-bottom: 2px;
    }

    .karigor-shop-title {
        font-family: 'Cinzel', serif;
        font-size: 21px;
        font-weight: 800;
        letter-spacing: 2px;
        color: #0f172a;
        margin: 0;
        line-height: 1.15;
        text-transform: uppercase;
    }

    .karigor-dept-title {
        font-size: 11px;
        font-weight: 700;
        color: #d97706;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 1px;
    }

    .karigor-double-divider {
        border: none;
        border-top: 1.5px solid #0f172a;
        border-bottom: 1px solid #0f172a;
        height: 4px;
        margin: 6px 0 8px;
    }

    .karigor-title-block {
        text-align: center;
        margin-bottom: 8px;
    }

    .karigor-main-heading-bn {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .karigor-main-heading-en {
        font-family: 'Outfit', sans-serif;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 2px;
        color: #475569;
        margin: 0;
        text-transform: uppercase;
    }

    /* Info 2-Column Grid */
    .karigor-info-grid {
        display: flex;
        gap: 12px;
        margin-bottom: 8px;
    }

    .karigor-profile-card {
        flex: 1.1;
        border: 1.2px solid #0f172a;
        background: #fff;
    }

    .karigor-meta-card {
        flex: 1;
        border: 1.2px solid #0f172a;
        background: #fff;
    }

    .k-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
    }

    .k-table td {
        padding: 4px 8px;
        border-bottom: 1px solid #e2e8f0;
    }

    .k-table tr:last-child td {
        border-bottom: none;
    }

    /* Items Table */
    .karigor-items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 8px;
        font-size: 10.5px;
    }

    .karigor-items-table th {
        background-color: #f8fafc;
        color: #0f172a;
        border: 1.2px solid #0f172a;
        padding: 5px 6px;
        font-weight: 700;
        text-align: center;
        line-height: 1.2;
    }

    .karigor-items-table td {
        border: 1px solid #cbd5e1;
        padding: 5px 6px;
        vertical-align: middle;
    }

    .karigor-items-table tfoot td {
        background-color: #f8fafc;
        border: 1.2px solid #0f172a;
        padding: 5px 6px;
        font-weight: 700;
    }

    .design-preview-box {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #94a3b8;
        display: block;
    }

    /* Settlement & Handover Section */
    .settlement-card {
        border: 1.2px solid #0f172a;
        background: #fafafa;
        padding: 8px 10px;
        margin-bottom: 8px;
    }

    .settlement-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 8px;
        margin-top: 6px;
    }

    .settlement-box {
        background: #fff;
        border: 1px dashed #64748b;
        padding: 6px 8px;
        text-align: center;
        border-radius: 3px;
    }

    .settlement-box .lbl {
        font-size: 9px;
        font-weight: 700;
        color: #475569;
        display: block;
        margin-bottom: 4px;
    }

    .settlement-box .val-placeholder {
        font-size: 11px;
        font-weight: 700;
        color: #0f172a;
        min-height: 18px;
    }

    /* Guidelines */
    .instructions-card {
        border: 1px solid #cbd5e1;
        background: #fff;
        padding: 6px 10px;
        margin-bottom: 8px;
        font-size: 9px;
        color: #334155;
    }

    .instructions-card ol {
        margin: 2px 0 0 16px;
        padding: 0;
    }

    .instructions-card li {
        margin-bottom: 1px;
    }

    /* Signatures */
    .karigor-signatures {
        display: flex;
        justify-content: space-between;
        margin-top: 22px;
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

    /* Bottom Banner */
    .karigor-bottom-banner {
        background: #0f172a;
        color: #ffffff;
        padding: 5px 10px;
        text-align: center;
        font-size: 9px;
        line-height: 1.3;
        margin-top: 6px;
    }

    /* =========================================================
       Complete Seal / Workshop Stamp Styling
       ========================================================= */
    .karigor-complete-seal-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        margin-top: 8px;
        padding: 6px 14px;
        background: rgba(5, 150, 105, 0.04);
        border: 1.5px dashed #059669;
        border-radius: 4px;
    }

    .seal-stamp-box {
        display: inline-flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: 2.5px solid #059669;
        color: #059669;
        padding: 4px 18px 5px;
        border-radius: 4px;
        transform: rotate(-2.5deg);
        user-select: none;
        background: #fff;
    }

    .seal-stamp-box .seal-brand {
        font-family: 'Cinzel', serif;
        font-size: 8.5px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        line-height: 1.2;
    }

    .seal-stamp-box .seal-title {
        font-family: 'Outfit', sans-serif;
        font-size: 17px;
        font-weight: 900;
        letter-spacing: 3px;
        line-height: 1.1;
        margin: 1px 0;
        text-transform: uppercase;
    }

    .seal-stamp-box .seal-bangla {
        font-family: 'Hind Siliguri', sans-serif;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.2;
    }

    .seal-stamp-box .seal-date {
        font-size: 8.5px;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-top: 2px;
        border-top: 1px dashed #059669;
        padding-top: 2px;
        width: 100%;
        text-align: center;
    }

    .seal-stamp-circle {
        width: 66px;
        height: 66px;
        border: 2.5px solid #059669;
        border-radius: 50%;
        padding: 2px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #059669;
        transform: rotate(6deg);
        user-select: none;
        background: #fff;
    }

    .seal-stamp-circle .seal-inner-circle {
        width: 100%;
        height: 100%;
        border: 1px dashed #059669;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .seal-stamp-circle .seal-stars {
        font-size: 6.5px;
        letter-spacing: 2px;
        line-height: 1;
    }

    .seal-stamp-circle .seal-mid {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin: 1px 0;
    }

    .seal-stamp-circle .seal-mid i {
        font-size: 11px;
    }

    .seal-stamp-circle .seal-status {
        font-size: 8px;
        font-weight: 900;
        letter-spacing: 1.5px;
        font-family: 'Outfit', sans-serif;
        line-height: 1;
    }

    .seal-stamp-circle .seal-dept {
        font-size: 6.5px;
        font-weight: 700;
        letter-spacing: 1px;
        line-height: 1;
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

        #printable_karigor_sheet, #printable_karigor_sheet * {
            visibility: visible;
        }

        #printable_karigor_sheet {
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

        .karigor-items-table th,
        .karigor-items-table td,
        .k-table td,
        .settlement-card,
        .settlement-box,
        .instructions-card {
            border-color: #111 !important;
        }

        .karigor-double-divider {
            border-top-color: #000 !important;
            border-bottom-color: #000 !important;
        }

        .karigor-bottom-banner {
            background-color: #111 !important;
            color: #fff !important;
        }

        .karigor-complete-seal-wrap {
            border-color: #059669 !important;
            background: transparent !important;
        }

        .seal-stamp-box,
        .seal-stamp-circle {
            border-color: #059669 !important;
            color: #059669 !important;
            background: transparent !important;
        }
    }
</style>
@endpush

@section('body')
@php
    $logo = \App\Models\Logo::latest()->first();
    $logoUrl = asset('logo/logo-529489738.png');
    
    $itemsList = $items && $items->count() > 0 ? $items : collect([$order]);
    $totalQty = $itemsList->sum('quantity');
    $totalTargetGram = $itemsList->sum('target_gram');
    $totalRawGold = $itemsList->sum('raw_gold_needed');
    $totalAlloy = max(0, $totalTargetGram - $totalRawGold);

    // Resolve KarigorJob & Completion Status
    $resolvedJob = $activeJob ?? null;
    if (!$resolvedJob && isset($selectedKarigor) && $selectedKarigor) {
        $resolvedJob = \App\Models\KarigorJob::where('custom_order_id', $order->id)
            ->where('karigor_id', $selectedKarigor->id)
            ->latest()
            ->first();
    }
    if (!$resolvedJob && $order->karigor_job_id) {
        $resolvedJob = $order->karigorJob;
    }
    if (!$resolvedJob) {
        $resolvedJob = \App\Models\KarigorJob::where('custom_order_id', $order->id)->latest()->first();
    }

    $isJobCompleted = false;
    if ($resolvedJob && in_array(strtolower($resolvedJob->status), ['completed', 'complete'])) {
        $isJobCompleted = true;
    } elseif (in_array($order->status, ['ready_for_delivery', 'delivered'])) {
        $isJobCompleted = true;
    }

    $completedDate = null;
    if ($resolvedJob && $resolvedJob->completed_at) {
        $completedDate = $resolvedJob->completed_at->format('d M, Y (h:i A)');
    } elseif ($order->updated_at) {
        $completedDate = $order->updated_at->format('d M, Y (h:i A)');
    } else {
        $completedDate = now()->format('d M, Y');
    }
@endphp

<div class="karigor-preview-wrapper">
    <!-- Top Action Bar (Screen Only) -->
    <div class="karigor-action-bar no-print d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('custom-orders.index') }}" class="btn btn-outline-secondary btn-sm shadow-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> কাস্টম অর্ডার তালিকা
            </a>
            <a href="{{ route('custom-orders.invoice', $order->id) }}" class="btn btn-outline-primary btn-sm shadow-sm">
                <i class="fa-solid fa-file-invoice-dollar me-1"></i> গ্রাহক ইনভয়েস (Customer Receipt)
            </a>
            @if($isJobCompleted)
                <span class="badge bg-success py-1.5 px-3 shadow-sm font-weight-bold" style="font-size: 11.5px;">
                    <i class="fa-solid fa-circle-check me-1"></i>কাজ সম্পন্ন (Completed)
                </span>
            @else
                <span class="badge bg-warning text-dark py-1.5 px-3 shadow-sm font-weight-bold" style="font-size: 11.5px;">
                    <i class="fa-solid fa-clock me-1"></i>চলমান কাজ (In Production)
                </span>
            @endif
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Karigor Filter Tabs if order has multiple karigors -->
            @if(isset($assignedKarigors) && $assignedKarigors->count() > 1)
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('custom-orders.karigor-invoice', $order->id) }}" class="btn {{ empty($karigor_id) ? 'btn-dark' : 'btn-outline-dark' }}">
                        সকল পণ্য (All)
                    </a>
                    @foreach($assignedKarigors as $kg)
                        <a href="{{ route('custom-orders.karigor-invoice', ['id' => $order->id, 'karigor_id' => $kg->id]) }}" 
                           class="btn {{ (isset($karigor_id) && $karigor_id == $kg->id) ? 'btn-dark' : 'btn-outline-dark' }}">
                            {{ $kg->name }}
                        </a>
                    @endforeach
                </div>
            @endif

            <button type="button" class="btn btn-primary btn-sm shadow-sm px-3" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> প্রিন্ট কার্যপত্র / জব কার্ড (A4)
            </button>
        </div>
    </div>

    <!-- The A4 Physical Sheet Container -->
    <div class="karigor-a4-sheet" id="printable_karigor_sheet">
        
        <!-- Header: Shop Logo & Brand -->
        <div class="karigor-header-brand">
            <img src="{{ $logoUrl }}" alt="Madobi Jewelers Logo" class="karigor-logo-img">
            <h1 class="karigor-shop-title">MADOBI JEWELERS</h1>
            <div class="karigor-dept-title"><i class="fa-solid fa-hammer me-1"></i>কারখানা ও কারিগর বিভাগ (Workshop & Artisan Department)</div>
        </div>

        <!-- Decorative Double Line -->
        <div class="karigor-double-divider"></div>

        <!-- Document Heading -->
        <div class="karigor-title-block">
            <div class="karigor-main-heading-bn">কারিগর জব অর্ডার ও স্বর্ণ বরাদ্দপত্র</div>
            <div class="karigor-main-heading-en">KARIGOR JOB CARD & RAW GOLD ALLOCATION ORDER</div>
        </div>

        <!-- Info Grid (Karigor Profile & Order Meta) -->
        <div class="karigor-info-grid">
            <!-- Left Box: Assigned Karigor Profile -->
            <div class="karigor-profile-card">
                <table class="k-table">
                    <tr>
                        <td style="width: 38%;" class="text-muted"><strong>নিযুক্ত কারিগর:</strong></td>
                        <td class="font-weight-bold text-primary" style="font-size: 11.5px;">
                            <i class="fa-solid fa-user-gear me-1"></i>
                            {{ $selectedKarigor ? $selectedKarigor->name . ' ' . ($selectedKarigor->last_name ?? '') : 'সকল নিয়োজিত কারিগর' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>কারিগর মোবাইল:</strong></td>
                        <td class="font-weight-bold">
                            {{ $selectedKarigor->phone ?? 'N/A' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>কারিগর আইডি:</strong></td>
                        <td>
                            <code>#KG-{{ $selectedKarigor ? str_pad($selectedKarigor->id, 4, '0', STR_PAD_LEFT) : 'ALL' }}</code>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>দায়িত্ব অর্পণকারী:</strong></td>
                        <td>
                            {{ auth()->user()->name ?? 'Manager / Admin' }} (মাদবী জুয়েলার্স)
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Right Box: Order Meta & Deadline -->
            <div class="karigor-meta-card">
                <table class="k-table">
                    <tr>
                        <td style="width: 42%;" class="text-muted"><strong>অর্ডার ট্র্যাকিং নং:</strong></td>
                        <td class="font-weight-bold text-end" style="font-size: 11px; letter-spacing: 0.5px;">
                            {{ $order->order_no }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted"><strong>কাজের তারিখ:</strong></td>
                        <td class="text-end">
                            {{ $order->created_at->format('d/m/Y h:i A') }}
                        </td>
                    </tr>
                    <tr style="background-color: #fffbeb;">
                        <td class="text-danger font-weight-bold">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i>ডেলিভারির ডেডলাইন:
                        </td>
                        <td class="text-end font-weight-bold text-danger" style="font-size: 11px;">
                            {{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M, Y (l)') : 'জরুরি ভিত্তিতে' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 2px 8px 1px; text-align: center; background: #fff;">
                            <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 1px;">
                                {!! generateBarcodeSvg($order->order_no, 20, 0.95, 2.2) !!}
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 9px; color: #475569; align-items: center;">
                                <span>কাস্টমার রেফারেন্স: #{{ $order->customer_id }}</span>
                                @if($isJobCompleted)
                                    <span class="badge bg-success text-white px-2 py-0.5" style="font-size: 8.5px; font-weight: 700;">
                                        <i class="fa-solid fa-check-circle me-1"></i>কাজ সম্পন্ন (COMPLETED)
                                    </span>
                                @else
                                    <span>স্ট্যাটাস: <strong>ইন-প্রোডাকশন</strong></span>
                                @endif
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Work Specifications Table -->
        <table class="karigor-items-table">
            <thead>
                <tr>
                    <th style="width: 25px;">নং<br><span style="font-size: 8.5px; font-weight: 500;">Sl</span></th>
                    <th style="width: 55px;">ডিজাইন ছবি<br><span style="font-size: 8.5px; font-weight: 500;">Image</span></th>
                    <th style="text-align: left; padding-left: 8px;">অলংকারের নাম ও কারিগরি নির্দেশনা<br><span style="font-size: 8.5px; font-weight: 500;">Product & Design Specs</span></th>
                    <th style="width: 50px;">ক্যারেট<br><span style="font-size: 8.5px; font-weight: 500;">Karat</span></th>
                    <th style="width: 35px;">পরিমাণ<br><span style="font-size: 8.5px; font-weight: 500;">Pcs</span></th>
                    <th style="width: 105px;">কাঙ্ক্ষিত মোট ওজন<br><span style="font-size: 8.5px; font-weight: 500;">Target Gross Wt</span></th>
                    <th style="width: 90px;">প্রদত্ত পাকা সোনা<br><span style="font-size: 8.5px; font-weight: 500;">Given Pure Gold</span></th>
                    <th style="width: 70px;">প্রয়োজনীয় খাদ<br><span style="font-size: 8.5px; font-weight: 500;">Alloy</span></th>
                </tr>
            </thead>
            <tbody>
                @foreach($itemsList as $idx => $it)
                @php
                    $alloyW = max(0, (float)$it->target_gram - (float)$it->raw_gold_needed);
                @endphp
                <tr>
                    <td class="text-center font-weight-bold">{{ $idx + 1 }}</td>
                    <td class="text-center">
                        @if($it->design_photo)
                            <img src="{{ asset($it->design_photo) }}" class="design-preview-box mx-auto" alt="Design">
                        @else
                            <div class="design-preview-box mx-auto d-flex align-items-center justify-content-center text-muted" style="font-size: 8px; background: #f8fafc;">
                                ছবি নেই
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 800; font-size: 11.5px; color: #0f172a;">{{ $it->product_name }}</div>
                        <div style="font-size: 9px; color: #475569;">
                            ক্যাটাগরি: {{ $it->category->category_name ?? 'জুয়েলারি' }}
                        </div>
                        @if($it->details)
                            <div style="font-size: 9.5px; background: #fffbeb; padding: 2px 6px; border-radius: 2px; border: 1px dashed #f59e0b; margin-top: 3px; color: #92400e;">
                                <strong><i class="fa-solid fa-pen-ruler me-1"></i>নির্দেশনা:</strong> {{ $it->details }}
                            </div>
                        @endif
                    </td>
                    <td class="text-center font-weight-bold">
                        <span class="badge bg-light text-dark border">{{ $it->karat ?? '22K' }}</span>
                    </td>
                    <td class="text-center font-weight-bold">{{ $it->quantity ?? 1 }}</td>
                    <td class="text-center">
                        <span class="font-weight-bold" style="font-size: 11px;">{{ number_format($it->target_gram, 3) }} গ্রাম</span>
                        <div style="font-size: 8.5px; color: #475569;">
                            ({{ (float)($it->target_bhori ?? 0) }}ভরি {{ (float)($it->target_ana ?? 0) }}আনা {{ (float)($it->target_roti ?? 0) }}রতি {{ (float)($it->target_point ?? 0) }}পয়েন্ট)
                        </div>
                    </td>
                    <td class="text-center font-weight-bold text-danger" style="font-size: 11px;">
                        {{ number_format($it->raw_gold_needed, 3) }} গ্রাম
                    </td>
                    <td class="text-center font-weight-bold text-secondary">
                        {{ number_format($alloyW, 3) }} গ্রাম
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" class="text-start" style="padding-left: 8px;">
                        <strong>মোট বরাদ্দের হিসাব ({{ $totalQty }} {{ $totalQty > 1 ? 'Items' : 'Item' }})</strong>
                    </td>
                    <td class="text-center font-weight-bold">{{ $totalQty }}</td>
                    <td class="text-center font-weight-bold">{{ number_format($totalTargetGram, 3) }} গ্রাম</td>
                    <td class="text-center font-weight-bold text-danger">{{ number_format($totalRawGold, 3) }} গ্রাম</td>
                    <td class="text-center font-weight-bold text-secondary">{{ number_format($totalAlloy, 3) }} গ্রাম</td>
                </tr>
            </tfoot>
        </table>

        <!-- Work Return & Settlement Record Box -->
        <div class="settlement-card">
            <div style="font-weight: 700; font-size: 10.5px; color: #0f172a; border-bottom: 1px solid #cbd5e1; padding-bottom: 3px; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="fa-solid fa-clipboard-check me-1 text-success"></i>কাজ সম্পন্নের পর স্বর্ণ ফেরত ও মজুরি সমন্বয়পত্র (Workshop Return Log):</span>
                @if($isJobCompleted)
                    <span class="badge bg-success text-white px-2 py-0.5" style="font-size: 8.5px;">
                        <i class="fa-solid fa-check-double me-1"></i>হস্তান্তর ও সমন্বয় সম্পন্ন (Settled)
                    </span>
                @else
                    <span class="text-muted" style="font-size: 9px;">(অলংকার হস্তান্তরের সময় শোরুম কর্তৃক পূরণীয়)</span>
                @endif
            </div>
            <div class="settlement-grid">
                <div class="settlement-box">
                    <span class="lbl">প্রস্তুত অলংকারের নিট ওজন:</span>
                    <div class="val-placeholder">
                        @if($isJobCompleted && ($resolvedJob->returned_gross_weight ?? $order->actual_weight_gram))
                            <strong class="text-dark">{{ number_format($resolvedJob->returned_gross_weight ?? $order->actual_weight_gram, 3) }} গ্রাম</strong>
                        @elseif($order->actual_weight_gram)
                            {{ number_format($order->actual_weight_gram, 3) }} গ্রাম
                        @else
                            ............ গ্রাম
                        @endif
                    </div>
                </div>
                <div class="settlement-box">
                    <span class="lbl">ফেরত পাকা সোনা (Raw Gold):</span>
                    <div class="val-placeholder">
                        @if($isJobCompleted)
                            <strong class="text-success">{{ number_format($resolvedJob->returned_raw_gold ?? 0, 3) }} গ্রাম</strong>
                        @else
                            ............ গ্রাম
                        @endif
                    </div>
                </div>
                <div class="settlement-box">
                    <span class="lbl">ঘাটতি / অপচয় (Wastage Gold):</span>
                    <div class="val-placeholder">
                        @if($isJobCompleted)
                            <strong class="text-warning">{{ number_format($resolvedJob->wastage_gold ?? 0, 3) }} গ্রাম</strong>
                        @else
                            ............ গ্রাম
                        @endif
                    </div>
                </div>
                <div class="settlement-box">
                    <span class="lbl">নির্ধারিত কারিগর মজুরি (৳):</span>
                    <div class="val-placeholder">
                        @if($order->karigor_fee > 0)
                            <strong class="text-primary">৳ {{ number_format($order->karigor_fee, 0) }}</strong>
                        @else
                            ৳ ............
                        @endif
                    </div>
                </div>
            </div>

            @if($isJobCompleted)
            <!-- The Official Complete Seal Stamped on Invoice -->
            <div class="karigor-complete-seal-wrap">
                <div class="seal-stamp-box">
                    <div class="seal-brand">MADOBI JEWELERS WORKSHOP</div>
                    <div class="seal-title">JOB COMPLETED</div>
                    <div class="seal-bangla">কাজ সম্পন্ন ও অলংকার গৃহীত</div>
                    <div class="seal-date">
                        <i class="fa-solid fa-calendar-check me-1"></i>সম্পন্ন: {{ $completedDate }}
                    </div>
                </div>
                <div class="seal-stamp-circle">
                    <div class="seal-inner-circle">
                        <div class="seal-stars">★ ★ ★</div>
                        <div class="seal-mid">
                            <i class="fa-solid fa-hammer"></i>
                            <div class="seal-status">SEALED</div>
                        </div>
                        <div class="seal-dept">WORKSHOP</div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Artisan Instructions -->
        <div class="instructions-card">
            <strong><i class="fa-solid fa-triangle-exclamation text-warning me-1"></i>কারিগরের জন্য জরুরি নিয়মাবলী:</strong>
            <ol>
                <li>উপরে উল্লেখিত রেফারেন্স ডিজাইন ছবি ও নির্দেশিত মাপ (Size/Engraving) অনুযায়ী নিখুঁত ফিনিশিংয়ে অলংকার তৈরি করতে হবে।</li>
                <li>কাজের জন্য বরাদ্দকৃত খাঁটি পাকা সোনা ছাড়া অনুমোদিত সীমার অতিরিক্ত খাদ মেশানো সম্পূর্ণরূপে নিষিদ্ধ।</li>
                <li>নির্ধারিত ডেলিভারি ডেডলাইনের পূর্বেই অলংকার প্রস্তুত করে শোরুম ম্যানেজারের নিকট জমা দিয়ে ওজন ও মজুরি নিশ্চিত করতে হবে।</li>
            </ol>
        </div>

        <!-- Signatures -->
        <div class="karigor-signatures">
            <div class="sig-line">
                কারিগর স্বাক্ষর ও তারিখ<br>
                <span style="font-size: 8.5px; color: #64748b;">Artisan Signature & Date</span>
                @if($isJobCompleted)
                    <div style="font-size: 9px; font-weight: 700; color: #059669; margin-top: 3px;">
                        <i class="fa-solid fa-circle-check me-1"></i>পণ্য সমর্পণ সম্পন্ন
                    </div>
                @endif
            </div>
            <div class="sig-line">
                দায়িত্বপ্রাপ্ত কর্মকর্তা / প্রস্তুতকারক<br>
                <span style="font-size: 8.5px; color: #64748b;">Workshop Manager / Authorized</span>
                @if($isJobCompleted)
                    <div style="font-size: 9px; font-weight: 700; color: #059669; margin-top: 3px;">
                        <i class="fa-solid fa-stamp me-1"></i>যাচাই ও কর্মশালা কর্তৃক গৃহীত
                    </div>
                @endif
            </div>
        </div>

        <!-- Bottom Banner -->
        <div class="karigor-bottom-banner">
            <div>
                <strong>MADOBI JEWELERS &bull; কারখানা ও ওয়ার্কশপ বিভাগ</strong>
            </div>
            <div style="font-size: 8px; opacity: 0.85; margin-top: 1px;">
                এই কার্যপত্রটি কারিগরের দায়িত্বপ্রাপ্ত কাজের দালিলিক প্রমাণ। কাজ সমাপ্তি পর্যন্ত যত্নসহকারে সংরক্ষণ করুন।
            </div>
        </div>

    </div>
</div>
@endsection
