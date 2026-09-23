@extends('admin.master')

@section('title')
কাঁচামাল ক্রয় ভাউচার - {{ $purchase->invoice_no }}
@endsection

@push('admin_style')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Hind+Siliguri:wght@400;500;600;700&family=Outfit:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
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
        padding: 12mm 15mm;
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08), 0 1px 3px rgba(15, 23, 42, 0.05);
        border: 1px solid #e2e8f0;
        box-sizing: border-box;
        color: #0f172a;
        position: relative;
        font-size: 13px;
        line-height: 1.5;
        border-radius: 8px;
    }

    .invoice-action-bar {
        max-width: 210mm;
        margin: 0 auto 16px;
    }

    .voucher-header {
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 15px;
        margin-bottom: 20px;
    }

    .gold-accent {
        color: #b45309;
    }

    .voucher-table th {
        background: #f8fafc;
        font-size: 12px;
        text-transform: uppercase;
        color: #475569;
        font-weight: 700;
        padding: 10px 12px;
        border-bottom: 2px solid #cbd5e1;
    }

    .voucher-table td {
        padding: 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .total-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 15px;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        .invoice-a4-sheet, .invoice-a4-sheet * {
            visibility: visible;
        }
        .invoice-a4-sheet {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 10mm;
            box-shadow: none;
            border: none;
        }
        .invoice-action-bar, .left-sidebar, .topbar, footer {
            display: none !important;
        }
        .page-wrapper {
            margin-left: 0 !important;
            padding: 0 !important;
        }
    }
</style>
@endpush

@section('body')
<div class="invoice-preview-wrapper">
    <div class="invoice-action-bar d-flex justify-content-between align-items-center">
        <a href="{{ route('raw-stock.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> র ম্যাটেরিয়াল স্টকে ফিরে যান
        </a>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-primary btn-sm px-3 shadow-sm">
                <i class="fa-solid fa-print me-1"></i> ভাউচার প্রিন্ট করুন
            </button>
        </div>
    </div>

    @php
        $logo = \App\Models\Logo::latest()->first();
    @endphp

    <div class="invoice-a4-sheet">
        {{-- Header --}}
        <div class="voucher-header d-flex justify-content-between align-items-center">
            <div>
                <h2 class="fw-bold mb-1 gold-accent" style="font-family: 'Cinzel', serif;">
                    {{ $logo->site_name ?? 'MADOBI JEWELERS' }}
                </h2>
                <div class="text-muted small">
                    <i class="fa-solid fa-location-dot me-1"></i> জুয়েলারি অ্যান্ড বুলিয়ন মার্চেন্ট<br>
                    কাঁচামাল ক্রয় ও জমা ভাউচার (Raw Material Purchase Voucher)
                </div>
            </div>
            <div class="text-end">
                <span class="badge bg-warning text-dark px-3 py-2 fs-6 fw-bold mb-1">
                    ভাউচার নং: {{ $purchase->invoice_no }}
                </span>
                <div class="small text-muted mt-1">
                    তারিখ: <strong>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M, Y') }}</strong>
                </div>
            </div>
        </div>

        {{-- Supplier & Purchase Info --}}
        <div class="row g-3 mb-4">
            <div class="col-6">
                <div class="p-3 bg-light rounded border h-100">
                    <div class="fw-bold text-dark text-uppercase small mb-2 border-bottom pb-1">
                        <i class="fa-solid fa-user-tag text-primary me-1"></i> বিক্রেতা / সাপ্লায়ার বিবরণ
                    </div>
                    <div class="fw-bold fs-6 text-dark">{{ $purchase->supplier_name ?: 'সাধারণ সরবরাহকারী / বাজার' }}</div>
                    @if($purchase->supplier_phone)
                        <div class="text-muted small"><i class="fa-solid fa-phone me-1"></i>{{ $purchase->supplier_phone }}</div>
                    @endif
                    <div class="text-muted small mt-1">
                        পেমেন্ট মেথড: <span class="badge bg-white text-dark border">{{ $purchase->payment_method ?: 'Cash' }}</span>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 bg-light rounded border h-100">
                    <div class="fw-bold text-dark text-uppercase small mb-2 border-bottom pb-1">
                        <i class="fa-solid fa-coins text-warning me-1"></i> ম্যাটেরিয়াল বিবরণ
                    </div>
                    <div>ক্যাটাগরি: <strong class="text-dark">{{ $purchase->productCategory->category_name ?? '—' }}</strong></div>
                    <div>উপাদান / বিবরণ: <strong class="text-dark">{{ $purchase->material_name ?: 'খাঁটি কাঁচামাল' }}</strong></div>
                    @if($purchase->karat)
                        <div>ক্যারেট / পিউরিটি: <span class="badge bg-dark text-white">{{ $purchase->karat }}</span></div>
                    @endif
                    <div class="small text-muted mt-1">এন্ট্রি কারী: {{ $purchase->createdBy->name ?? 'অ্যাডমিন' }}</div>
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="table-responsive mb-4">
            <table class="table voucher-table border">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>বিবরণ ও পিউরিটি</th>
                        <th class="text-end">ওজন (গ্রাম)</th>
                        <th>ভরি / আনা / রতি / পয়েন্ট</th>
                        <th class="text-end">দর (প্রতি গ্রাম/ভরি)</th>
                        <th class="text-end">মোট মূল্য (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <strong class="text-dark">{{ $purchase->material_name ?: ($purchase->productCategory->category_name . ' Raw') }}</strong>
                            @if($purchase->karat)
                                <span class="badge bg-secondary-subtle text-secondary border ms-1">{{ $purchase->karat }}</span>
                            @endif
                            @if($purchase->carat > 0)
                                <div class="small text-muted">Diamond Weight: {{ number_format($purchase->carat, 3) }} ct</div>
                            @endif
                        </td>
                        <td class="text-end fw-bold fs-6 font-monospace text-primary">
                            {{ number_format($purchase->gram, 3) }} g
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border px-2 py-1">
                                {{ $purchase->bhori }} ভরি {{ $purchase->ana }} আনা {{ $purchase->roti }} রতি {{ $purchase->point }} পয়েন্ট
                            </span>
                        </td>
                        <td class="text-end font-monospace">
                            @if($purchase->unit_price > 0)
                                ৳{{ number_format($purchase->unit_price, 2) }}
                                <small class="text-muted d-block">({{ $purchase->rate_type === 'per_bhori' ? 'প্রতি ভরি' : 'প্রতি গ্রাম' }})</small>
                            @else
                                —
                            @endif
                        </td>
                        <td class="text-end fw-bold font-monospace fs-6 text-dark">
                            ৳{{ number_format($purchase->total_amount, 2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Totals & Signatures --}}
        <div class="row g-3 mb-5">
            <div class="col-7">
                @if($purchase->notes)
                    <div class="p-3 bg-light rounded border">
                        <div class="fw-bold small text-muted mb-1">নোট / মন্তব্য:</div>
                        <div class="text-dark small">{{ $purchase->notes }}</div>
                    </div>
                @endif
            </div>
            <div class="col-5">
                <div class="total-box">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">মোট মূল্য:</span>
                        <span class="fw-bold font-monospace">৳{{ number_format($purchase->total_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>পরিশোধিত:</span>
                        <span class="fw-bold font-monospace">৳{{ number_format($purchase->paid_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between pt-2 border-top {{ $purchase->due_amount > 0 ? 'text-danger' : 'text-muted' }}">
                        <span class="fw-bold">বকেয়া পরিমাণ:</span>
                        <span class="fw-bold font-monospace fs-6">৳{{ number_format($purchase->due_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Signatures --}}
        <div class="row mt-5 pt-4">
            <div class="col-6 text-center">
                <div class="border-top pt-2 mx-5 text-muted small">
                    সরবরাহকারী / বিক্রেতার স্বাক্ষর
                </div>
            </div>
            <div class="col-6 text-center">
                <div class="border-top pt-2 mx-5 text-muted small">
                    কর্তৃপক্ষের স্বাক্ষর
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
