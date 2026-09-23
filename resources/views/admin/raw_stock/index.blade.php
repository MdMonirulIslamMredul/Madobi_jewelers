@extends('admin.master')

@section('title')
কাঁচামাল ক্রয় ও স্টক ব্যবস্থাপনা
@endsection

@push('admin_style')
<style>
    .kpi-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08) !important;
    }
    .nav-tabs .nav-link {
        color: #4b5563;
        font-weight: 600;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 20px;
        transition: all 0.2s ease;
    }
    .nav-tabs .nav-link:hover {
        color: #1f2937;
        border-color: #cbd5e1;
    }
    .nav-tabs .nav-link.active {
        color: #d97706;
        border-color: #d97706;
        background: transparent;
        font-weight: 700;
    }
    .table td, .table th {
        vertical-align: middle;
    }
    .table th {
        white-space: nowrap !important;
        font-size: 12px !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        background-color: #f8fafc !important;
        padding: 10px 12px !important;
    }
    .table td {
        padding: 10px 12px !important;
    }

    /* -------------------------------------------------------------
       HIGH CONTRAST THEME-PROOF BADGE SYSTEM (Fixes white text issue)
       ------------------------------------------------------------- */
    .badge {
        font-size: 11px !important;
        font-weight: 700 !important;
        line-height: 1.3 !important;
        padding: 4px 8px !important;
        border-radius: 4px !important;
        display: inline-block !important;
        text-shadow: none !important;
    }
    .badge-memo {
        background-color: #f1f5f9 !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
        font-family: Consolas, monospace !important;
        font-size: 12px !important;
        padding: 4px 8px !important;
    }
    .badge-cat-gold {
        background-color: #fef3c7 !important;
        color: #78350f !important;
        border: 1px solid #fcd34d !important;
    }
    .badge-cat-rupa {
        background-color: #f1f5f9 !important;
        color: #1e293b !important;
        border: 1px solid #cbd5e1 !important;
    }
    .badge-cat-diamond {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
        border: 1px solid #7dd3fc !important;
    }
    .badge-cat-platinum {
        background-color: #ede9fe !important;
        color: #5b21b6 !important;
        border: 1px solid #c4b5fd !important;
    }
    .badge-purity {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
        border: 1px solid #cbd5e1 !important;
    }
    .badge-paid {
        background-color: #dcfce7 !important;
        color: #14532d !important;
        border: 1px solid #86efac !important;
        font-weight: 700 !important;
        padding: 4px 10px !important;
        font-size: 12px !important;
    }
    .badge-due {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #fca5a5 !important;
        font-weight: 700 !important;
        padding: 4px 10px !important;
        font-size: 12px !important;
    }
    .badge-in {
        background-color: #dcfce7 !important;
        color: #14532d !important;
        border: 1px solid #86efac !important;
        font-weight: 700 !important;
    }
    .badge-out {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #fca5a5 !important;
        font-weight: 700 !important;
    }
    .badge-adjust {
        background-color: #e0f2fe !important;
        color: #0369a1 !important;
        border: 1px solid #7dd3fc !important;
        font-weight: 700 !important;
    }
    .badge-tab {
        background-color: #d97706 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        padding: 2px 8px !important;
    }
    .badge-tab-muted {
        background-color: #64748b !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        padding: 2px 8px !important;
    }
    .badge-card-gold {
        background-color: #f59e0b !important;
        color: #000000 !important;
        font-weight: 700 !important;
    }
    .badge-card-rupa {
        background-color: #475569 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
    }
    .badge-card-diamond {
        background-color: #0284c7 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
    }
    .badge-card-platinum {
        background-color: #7c3aed !important;
        color: #ffffff !important;
        font-weight: 700 !important;
    }
    .badge-ref-job {
        background-color: #eff6ff !important;
        color: #1d4ed8 !important;
        border: 1px solid #bfdbfe !important;
    }
    .badge-ref-order {
        background-color: #f0fdf4 !important;
        color: #15803d !important;
        border: 1px solid #bbf7d0 !important;
    }
</style>
@endpush

@section('body')
<div class="py-3">

    {{-- Page Header --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4 pb-2 border-bottom">
        <div>
            <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                <i class="fa-solid fa-coins text-warning"></i>
                কাঁচামাল ক্রয় ও স্টক ব্যবস্থাপনা (Raw Stock & Purchase Management)
            </h3>
            <p class="text-muted small mb-0">
                কাঁচা সোনা, রূপা, হীরা ও প্লাটিনামের লাইভ মজুদ, ক্রয় হিসাব ও কারিগর লেনদেন অডিট ট্র্যাকিং
            </p>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-sm-0">
            <button type="button" class="btn btn-warning text-dark fw-bold shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addRawPurchaseModal">
                <i class="fa-solid fa-cart-plus"></i> নতুন কাঁচামাল ক্রয়
            </button>
            <button type="button" class="btn btn-outline-primary fw-semibold shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addRawStockModal">
                <i class="fa-solid fa-plus-circle"></i> কুইক স্টক ইন
            </button>
            <button type="button" class="btn btn-outline-secondary fw-semibold shadow-sm d-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#adjustRawStockModal">
                <i class="fa-solid fa-sliders"></i> স্টক সমন্বয়
            </button>
        </div>
    </div>

    {{-- Session Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="fa-solid fa-circle-check fs-5"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('message'))
        <div class="alert alert-info alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="fa-solid fa-info-circle fs-5"></i>
            <div>{{ session('message') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm d-flex align-items-center gap-2 mb-4" role="alert">
            <i class="fa-solid fa-triangle-exclamation fs-5"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
            <h6 class="fw-bold mb-1"><i class="fa-solid fa-circle-exclamation me-1"></i> অনুগ্রহ করে নিচের ত্রুটিগুলো সংশোধন করুন:</h6>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 4 Live Inventory Cards --}}
    <div class="row g-3 mb-4">
        {{-- 1. Raw Gold Card --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden kpi-card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 5px solid #f59e0b !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge badge-card-gold px-2 py-1">
                            <i class="fa-solid fa-circle-dot fa-xs me-1"></i> গোল্ড ক্যাটাগরি
                        </span>
                        <div class="rounded-circle bg-white bg-opacity-75 p-2 text-warning shadow-sm">
                            <i class="fa-solid fa-coins fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-dark fw-bold mb-1">কাঁচা সোনা (Raw Gold)</h6>
                    <div class="d-flex align-items-baseline gap-1 my-2">
                        <h2 class="fw-bolder text-dark mb-0 font-monospace">
                            {{ number_format($goldStock->gram ?? 0, 3) }}
                        </h2>
                        <span class="fs-6 fw-bold text-dark">গ্রাম</span>
                    </div>
                    <div class="bg-white bg-opacity-60 rounded p-2 small border border-warning border-opacity-25 text-dark fw-semibold">
                        <i class="fa-solid fa-scale-balanced me-1 text-warning"></i>
                        <span>{{ $goldStock->bhori ?? 0 }} ভরি</span> &bull;
                        <span>{{ $goldStock->ana ?? 0 }} আনা</span> &bull;
                        <span>{{ $goldStock->roti ?? 0 }} রতি</span> &bull;
                        <span>{{ $goldStock->point ?? 0 }} পয়েন্ট</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Raw Rupa Card --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden kpi-card" style="background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%); border-left: 5px solid #64748b !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge badge-card-rupa px-2 py-1">
                            <i class="fa-solid fa-circle-dot fa-xs me-1"></i> রূপা ক্যাটাগরি
                        </span>
                        <div class="rounded-circle bg-white bg-opacity-75 p-2 text-secondary shadow-sm">
                            <i class="fa-solid fa-ring fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-dark fw-bold mb-1">কাঁচা রূপা (Raw Rupa)</h6>
                    <div class="d-flex align-items-baseline gap-1 my-2">
                        <h2 class="fw-bolder text-dark mb-0 font-monospace">
                            {{ number_format($rupaStock->gram ?? 0, 3) }}
                        </h2>
                        <span class="fs-6 fw-bold text-dark">গ্রাম</span>
                    </div>
                    <div class="bg-white bg-opacity-60 rounded p-2 small border border-secondary border-opacity-25 text-dark fw-semibold">
                        <i class="fa-solid fa-scale-balanced me-1 text-secondary"></i>
                        <span>{{ $rupaStock->bhori ?? 0 }} ভরি</span> &bull;
                        <span>{{ $rupaStock->ana ?? 0 }} আনা</span> &bull;
                        <span>{{ $rupaStock->roti ?? 0 }} রতি</span> &bull;
                        <span>{{ $rupaStock->point ?? 0 }} পয়েন্ট</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. Diamond Card --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden kpi-card" style="background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%); border-left: 5px solid #0284c7 !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge badge-card-diamond px-2 py-1">
                            <i class="fa-solid fa-circle-dot fa-xs me-1"></i> ডায়মন্ড ক্যাটাগরি
                        </span>
                        <div class="rounded-circle bg-white bg-opacity-75 p-2 text-info shadow-sm">
                            <i class="fa-solid fa-gem fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-dark fw-bold mb-1">হীরা (Diamond Stock)</h6>
                    <div class="d-flex align-items-baseline gap-1 my-2">
                        <h2 class="fw-bolder text-dark mb-0 font-monospace">
                            {{ number_format($diamondStock->carat ?? 0, 3) }}
                        </h2>
                        <span class="fs-6 fw-bold text-dark">ক্যারেট</span>
                    </div>
                    <div class="bg-white bg-opacity-60 rounded p-2 small border border-info border-opacity-25 text-dark fw-semibold">
                        <i class="fa-solid fa-weight-hanging me-1 text-info"></i>
                        <span>ওজন: {{ number_format($diamondStock->gram ?? 0, 3) }} গ্রাম</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Platinum Card --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-3 h-100 overflow-hidden kpi-card" style="background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%); border-left: 5px solid #7c3aed !important;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge badge-card-platinum px-2 py-1">
                            <i class="fa-solid fa-circle-dot fa-xs me-1"></i> প্লাটিনাম ক্যাটাগরি
                        </span>
                        <div class="rounded-circle bg-white bg-opacity-75 p-2 shadow-sm" style="color:#7c3aed;">
                            <i class="fa-solid fa-cubes-stacked fa-lg"></i>
                        </div>
                    </div>
                    <h6 class="text-dark fw-bold mb-1">প্লাটিনাম (Platinum Stock)</h6>
                    <div class="d-flex align-items-baseline gap-1 my-2">
                        <h2 class="fw-bolder text-dark mb-0 font-monospace">
                            {{ number_format($platinumStock->gram ?? 0, 3) }}
                        </h2>
                        <span class="fs-6 fw-bold text-dark">গ্রাম</span>
                    </div>
                    <div class="bg-white bg-opacity-60 rounded p-2 small border border-primary border-opacity-25 text-dark fw-semibold">
                        <i class="fa-solid fa-scale-balanced me-1 text-primary"></i>
                        <span>{{ $platinumStock->bhori ?? 0 }} ভরি</span> &bull;
                        <span>{{ $platinumStock->ana ?? 0 }} আনা</span> &bull;
                        <span>{{ $platinumStock->roti ?? 0 }} রতি</span> &bull;
                        <span>{{ $platinumStock->point ?? 0 }} পয়েন্ট</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Purchase KPI Counters --}}
    <div class="row g-2 mb-4">
        <div class="col-md-3 col-6">
            <div class="p-2 bg-white rounded border shadow-sm d-flex align-items-center gap-3">
                <div class="rounded p-2 bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-file-invoice-dollar fs-5"></i>
                </div>
                <div>
                    <small class="text-muted d-block">মোট ক্রয় সংখ্যা</small>
                    <strong class="text-dark fs-6">{{ $purchases->total() }} টি</strong>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-2 bg-white rounded border shadow-sm d-flex align-items-center gap-3">
                <div class="rounded p-2 bg-info bg-opacity-10 text-info">
                    <i class="fa-solid fa-weight-scale fs-5"></i>
                </div>
                <div>
                    <small class="text-muted d-block">সর্বমোট ক্রয়কৃত ওজন</small>
                    <strong class="text-dark fs-6 font-monospace">{{ number_format($totalPurchasedGrams, 3) }} g</strong>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-2 bg-white rounded border shadow-sm d-flex align-items-center gap-3">
                <div class="rounded p-2 bg-success bg-opacity-10 text-success">
                    <i class="fa-solid fa-money-bill-wave fs-5"></i>
                </div>
                <div>
                    <small class="text-muted d-block">মোট পরিশোধিত টাকা</small>
                    <strong class="text-success fs-6 font-monospace">৳{{ number_format($totalPaidAmount, 0) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="p-2 bg-white rounded border shadow-sm d-flex align-items-center gap-3">
                <div class="rounded p-2 bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-hand-holding-dollar fs-5"></i>
                </div>
                <div>
                    <small class="text-muted d-block">মোট বকেয়া পরিমাণ</small>
                    <strong class="text-danger fs-6 font-monospace">৳{{ number_format($totalDueAmount, 0) }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabs Section --}}
    <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom p-0">
            <ul class="nav nav-tabs border-0 px-3" id="rawStockTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active d-flex align-items-center gap-2" id="purchases-tab" data-bs-toggle="tab" data-bs-target="#purchases-content" type="button" role="tab" aria-selected="true">
                        <i class="fa-solid fa-cart-shopping text-warning"></i>
                        কাঁচামাল ক্রয় তালিকা (Raw Purchases List)
                        <span class="badge badge-tab ms-1">{{ $purchases->total() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-2" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-content" type="button" role="tab" aria-selected="false">
                        <i class="fa-solid fa-clock-rotate-left text-primary"></i>
                        কাঁচামাল লেনদেন ও অডিট হিস্ট্রি (Audit & Movements)
                        <span class="badge badge-tab-muted ms-1">{{ $histories->total() }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="rawStockTabsContent">
            {{-- TAB 1: Raw Purchases List (Full CRUD) --}}
            <div class="tab-pane fade show active p-3" id="purchases-content" role="tabpanel">
                
                {{-- Filter Bar for Purchases --}}
                <div class="card bg-light border mb-3 rounded-2">
                    <div class="card-body p-3">
                        <form action="{{ route('raw-stock.index') }}" method="GET" class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted mb-1">অনুসন্ধান (মেমো / সাপ্লায়ার / ফোন)</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass"></i></span>
                                    <input type="text" name="search" class="form-control" placeholder="মেমো নং বা সাপ্লায়ার..." value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">ক্যাটাগরি</label>
                                <select name="purchase_category_id" class="form-select form-select-sm">
                                    <option value="all">সকল ক্যাটাগরি</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('purchase_category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">পেমেন্ট স্ট্যাটাস</label>
                                <select name="payment_status" class="form-select form-select-sm">
                                    <option value="all">সকল স্ট্যাটাস</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>পরিশোধিত (Paid)</option>
                                    <option value="due" {{ request('payment_status') == 'due' ? 'selected' : '' }}>বকেয়া রয়েছে (Due)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">হতে তারিখ</label>
                                <input type="date" name="purchase_from_date" class="form-control form-control-sm" value="{{ request('purchase_from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">পর্যন্ত তারিখ</label>
                                <input type="date" name="purchase_to_date" class="form-control form-control-sm" value="{{ request('purchase_to_date') }}">
                            </div>
                            <div class="col-md-1 d-flex gap-1">
                                <button type="submit" class="btn btn-sm btn-primary flex-grow-1" title="ফিল্টার করুন">
                                    <i class="fa-solid fa-filter"></i>
                                </button>
                                <a href="{{ route('raw-stock.index') }}" class="btn btn-sm btn-outline-secondary" title="রিসেট">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Purchases Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 border">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-3 py-3" style="width: 40px;">#</th>
                                <th>মেমো নং</th>
                                <th>তারিখ</th>
                                <th>ক্যাটাগরি ও বিবরণ</th>
                                <th>সাপ্লায়ার / বিক্রেতা</th>
                                <th class="text-end">ওজন (গ্রাম)</th>
                                <th>ভরি / আনা / রতি</th>
                                <th class="text-end">দর</th>
                                <th class="text-end">মোট মূল্য</th>
                                <th class="text-center">পরিশোধ / বকেয়া</th>
                                <th class="pe-3 text-center" style="width: 140px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($purchases as $idx => $p)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $purchases->firstItem() + $idx }}</td>
                                    <td>
                                        <span class="badge badge-memo">
                                            {{ $p->invoice_no }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ \Carbon\Carbon::parse($p->purchase_date)->format('d M, Y') }}</div>
                                    </td>
                                    <td>
                                        <strong class="text-dark d-block mb-1">{{ $p->material_name ?: ($p->productCategory->category_name ?? '—') }}</strong>
                                        <div class="d-flex flex-wrap align-items-center gap-1">
                                            @php
                                                $catSlug = strtolower($p->productCategory->category_slug ?? '');
                                                $catClass = 'badge-cat-gold';
                                                if (strpos($catSlug, 'rupa') !== false || strpos($catSlug, 'silver') !== false) {
                                                    $catClass = 'badge-cat-rupa';
                                                } elseif (strpos($catSlug, 'diamond') !== false) {
                                                    $catClass = 'badge-cat-diamond';
                                                } elseif (strpos($catSlug, 'platinum') !== false) {
                                                    $catClass = 'badge-cat-platinum';
                                                }
                                            @endphp
                                            <span class="badge {{ $catClass }}">{{ $p->productCategory->category_name ?? '' }}</span>
                                            @if($p->karat)
                                                <span class="badge badge-purity">{{ $p->karat }}</span>
                                            @endif
                                            @if($p->carat > 0)
                                                <span class="badge badge-cat-diamond">{{ number_format($p->carat, 3) }} ct</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $p->supplier_name ?: 'সাধারণ / বাজার' }}</div>
                                        @if($p->supplier_phone)
                                            <small class="text-muted"><i class="fa-solid fa-phone fa-xs me-1"></i>{{ $p->supplier_phone }}</small>
                                        @endif
                                    </td>
                                    <td class="text-end font-monospace">
                                        <strong class="text-primary fs-6">{{ number_format($p->gram, 3) }} g</strong>
                                    </td>
                                    <td>
                                        <small class="text-dark fw-semibold">
                                            {{ $p->bhori }}ভ {{ $p->ana }}আ {{ $p->roti }}র {{ $p->point }}প
                                        </small>
                                    </td>
                                    <td class="text-end font-monospace small">
                                        @if($p->unit_price > 0)
                                            ৳{{ number_format($p->unit_price, 2) }}
                                            <small class="text-muted d-block">{{ $p->rate_type === 'per_bhori' ? '/ভরি' : '/গ্রাম' }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-end font-monospace">
                                        <strong class="text-dark">৳{{ number_format($p->total_amount, 2) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        @if($p->due_amount <= 0)
                                            <span class="badge badge-paid">
                                                <i class="fa-solid fa-circle-check me-1"></i> Paid
                                            </span>
                                        @else
                                            <div class="badge badge-due mb-1">
                                                বকেয়া: ৳{{ number_format($p->due_amount, 0) }}
                                            </div>
                                            <div class="small text-muted font-monospace" style="font-size: 11px;">পরিশোধ: ৳{{ number_format($p->paid_amount, 0) }}</div>
                                        @endif
                                    </td>
                                    <td class="pe-3 text-center">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('raw-stock.purchase.invoice', $p->id) }}" class="btn btn-sm btn-outline-secondary" title="ভাউচার প্রিন্ট করুন" target="_blank" style="padding: 3px 8px;">
                                                <i class="fa-solid fa-print"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-edit-purchase" 
                                                data-id="{{ $p->id }}"
                                                data-category_id="{{ $p->category_id }}"
                                                data-invoice_no="{{ $p->invoice_no }}"
                                                data-supplier_name="{{ $p->supplier_name }}"
                                                data-supplier_phone="{{ $p->supplier_phone }}"
                                                data-purchase_date="{{ \Carbon\Carbon::parse($p->purchase_date)->format('Y-m-d') }}"
                                                data-material_name="{{ $p->material_name }}"
                                                data-karat="{{ $p->karat }}"
                                                data-gram="{{ $p->gram }}"
                                                data-carat="{{ $p->carat }}"
                                                data-unit_price="{{ $p->unit_price }}"
                                                data-rate_type="{{ $p->rate_type }}"
                                                data-total_amount="{{ $p->total_amount }}"
                                                data-paid_amount="{{ $p->paid_amount }}"
                                                data-due_amount="{{ $p->due_amount }}"
                                                data-payment_method="{{ $p->payment_method }}"
                                                data-notes="{{ $p->notes }}"
                                                title="সম্পাদন করুন"
                                                style="padding: 3px 8px;">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-purchase" 
                                                data-id="{{ $p->id }}"
                                                data-invoice="{{ $p->invoice_no }}"
                                                data-gram="{{ $p->gram }}"
                                                data-category="{{ $p->productCategory->category_name ?? '' }}"
                                                title="মুছে ফেলুন"
                                                style="padding: 3px 8px;">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-box-open fa-3x mb-3 d-block opacity-50"></i>
                                        <h6 class="fw-bold text-dark">কোন কাঁচামাল ক্রয়ের রেকর্ড পাওয়া যায়নি</h6>
                                        <p class="small text-muted mb-3">নতুন কাঁচা সোনা, রূপা বা ডায়মন্ড ক্রয় যুক্ত করতে নিচের বোতামে ক্লিক করুন।</p>
                                        <button type="button" class="btn btn-warning text-dark fw-bold btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addRawPurchaseModal">
                                            <i class="fa-solid fa-cart-plus me-1"></i> নতুন কাঁচামাল ক্রয় যোগ করুন
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($purchases->hasPages())
                    <div class="d-flex justify-content-end mt-3">
                        {{ $purchases->links() }}
                    </div>
                @endif
            </div>

            {{-- TAB 2: Audit History & Movements --}}
            <div class="tab-pane fade p-3" id="history-content" role="tabpanel">
                
                {{-- Filter Bar for History --}}
                <div class="card bg-light border mb-3 rounded-2">
                    <div class="card-body p-3">
                        <form action="{{ route('raw-stock.index') }}" method="GET" class="row g-2 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-muted mb-1">ক্যাটাগরি</label>
                                <select name="category_id" class="form-select form-select-sm">
                                    <option value="all">সকল ক্যাটাগরি</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">লেনদেনের ধরন</label>
                                <select name="type" class="form-select form-select-sm">
                                    <option value="all">সকল ধরন</option>
                                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>ইন / প্রাপ্তি (+)</option>
                                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>আউট / প্রদান (-)</option>
                                    <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>সমন্বয় (Adjust)</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">হতে তারিখ</label>
                                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-muted mb-1">পর্যন্ত তারিখ</label>
                                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-3 d-flex gap-2">
                                <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                                    <i class="fa-solid fa-filter me-1"></i> হিস্ট্রি ফিল্টার
                                </button>
                                <a href="{{ route('raw-stock.index') }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- History Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 border">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-3 py-3" style="width: 50px;">#</th>
                                <th>তারিখ ও সময়</th>
                                <th>ক্যাটাগরি</th>
                                <th class="text-center">ধরন</th>
                                <th class="text-end">পরিমাণ (গ্রাম)</th>
                                <th>ভরি / রতি বিবরণ</th>
                                <th class="text-end">স্টক পূর্ব &rarr; বর্তমান</th>
                                <th>কারণ / বিবরণ</th>
                                <th>রেফারেন্স (ক্রয় / কারিগর)</th>
                                <th class="pe-3">এন্ট্রি কারী</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($histories as $idx => $hist)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $histories->firstItem() + $idx }}</td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $hist->created_at ? $hist->created_at->format('d M Y') : '—' }}</div>
                                        <small class="text-muted">{{ $hist->created_at ? $hist->created_at->format('h:i A') : '' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-purity fw-bold px-2 py-1">
                                            {{ $hist->productCategory->category_name ?? '—' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @if($hist->type === 'in')
                                            <span class="badge badge-in px-2 py-1 fw-bold">
                                                <i class="fa-solid fa-arrow-down-long me-1"></i> IN (+)
                                            </span>
                                        @elseif($hist->type === 'out')
                                            <span class="badge badge-out px-2 py-1 fw-bold">
                                                <i class="fa-solid fa-arrow-up-long me-1"></i> OUT (-)
                                            </span>
                                        @else
                                            <span class="badge badge-adjust px-2 py-1 fw-bold">
                                                <i class="fa-solid fa-arrows-rotate me-1"></i> ADJUST
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <strong class="{{ $hist->type === 'in' ? 'text-success' : ($hist->type === 'out' ? 'text-danger' : 'text-primary') }} font-monospace fs-6">
                                            {{ $hist->type === 'in' ? '+' : ($hist->type === 'out' ? '-' : '') }}{{ number_format($hist->gram, 3) }} g
                                        </strong>
                                        @if($hist->carat > 0 && stripos($hist->productCategory->category_name ?? '', 'diamond') !== false)
                                            <small class="text-muted d-block">({{ number_format($hist->carat, 3) }} ct)</small>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-dark fw-semibold">
                                            {{ $hist->bhori }}ভ {{ $hist->ana }}আ {{ $hist->roti }}র {{ $hist->point }}প
                                        </small>
                                    </td>
                                    <td class="text-end font-monospace small">
                                        <span class="text-muted">{{ number_format($hist->previous_gram, 3) }}</span>
                                        <i class="fa-solid fa-arrow-right-long text-muted mx-1"></i>
                                        <strong class="text-dark">{{ number_format($hist->current_gram, 3) }} g</strong>
                                    </td>
                                    <td>
                                        <span class="text-dark">{{ $hist->reason ?? '—' }}</span>
                                        @if($hist->notes)
                                            <small class="text-muted d-block fst-italic">{{ $hist->notes }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hist->raw_material_purchase_id)
                                            <a href="{{ route('raw-stock.purchase.invoice', $hist->raw_material_purchase_id) }}" target="_blank" class="badge badge-cat-gold text-decoration-none">
                                                <i class="fa-solid fa-cart-shopping me-1"></i>মেমো #{{ $hist->rawMaterialPurchase->invoice_no ?? $hist->raw_material_purchase_id }}
                                            </a>
                                        @endif
                                        @if($hist->karigor)
                                            <div class="fw-semibold text-dark small">
                                                <i class="fa-solid fa-user-gear text-primary me-1"></i>
                                                {{ $hist->karigor->name }}
                                            </div>
                                        @endif
                                        @if($hist->karigor_job_id)
                                            <span class="badge badge-ref-job">Job #{{ $hist->karigor_job_id }}</span>
                                        @endif
                                        @if($hist->custom_order_id)
                                            <span class="badge badge-ref-order">Order #{{ $hist->customOrder->order_no ?? $hist->custom_order_id }}</span>
                                        @endif
                                        @if(!$hist->raw_material_purchase_id && !$hist->karigor && !$hist->karigor_job_id && !$hist->custom_order_id)
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td class="pe-3">
                                        <small class="text-muted">{{ $hist->createdBy->name ?? 'System' }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-box-open fa-2x mb-2 d-block opacity-50"></i>
                                        কোন লেনদেনের অডিট রেকর্ড পাওয়া যায়নি।
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($histories->hasPages())
                    <div class="d-flex justify-content-end mt-3">
                        {{ $histories->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>

</div>

{{-- ======================================================== --}}
{{-- MODAL 1: ADD RAW MATERIAL PURCHASE (নতুন কাঁচামাল ক্রয়)  --}}
{{-- ======================================================== --}}
<div class="modal fade" id="addRawPurchaseModal" tabindex="-1" aria-labelledby="addRawPurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold" id="addRawPurchaseModalLabel">
                    <i class="fa-solid fa-cart-plus me-1"></i> নতুন কাঁচামাল ক্রয় (Raw Material Purchase)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('raw-stock.purchase.store') }}" method="POST" id="addRawPurchaseForm">
                @csrf
                <div class="modal-body p-4">
                    
                    {{-- Row 1: Category & Invoice & Date --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ক্যাটাগরি <span class="text-danger">*</span></label>
                            <select name="category_id" id="add_purchase_cat_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ভাউচার / মেমো নং <span class="text-muted fw-normal">(ঐচ্ছিক)</span></label>
                            <input type="text" name="invoice_no" class="form-control font-monospace" placeholder="অটো জেনারেট হবে">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ক্রয়ের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="purchase_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    {{-- Row 2: Supplier Information --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সাপ্লায়ার / বিক্রেতার নাম</label>
                            <input type="text" name="supplier_name" class="form-control" placeholder="যেমন: আলমগীর বুলিয়ন / সাধারণ সরবরাহকারী">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সাপ্লায়ার ফোন নম্বর</label>
                            <input type="text" name="supplier_phone" class="form-control" placeholder="০১৭১XXXXXXX">
                        </div>
                    </div>

                    {{-- Row 3: Material Details & Karat --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ম্যাটেরিয়ালের বিবরণ / নাম</label>
                            <input type="text" name="material_name" class="form-control" placeholder="যেমন: ২৪ ক্যারেট পাকা সোনা / রূপার বাট">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ক্যারেট / পিউরিটি</label>
                            <select name="karat" class="form-select">
                                <option value="24K (পাকা/খাঁটি)">24K (পাকা/খাঁটি সোনা)</option>
                                <option value="22K">22K সোনা</option>
                                <option value="21K">21K সোনা</option>
                                <option value="18K">18K সোনা</option>
                                <option value="খাঁটি রূপা">খাঁটি রূপা (Pure Silver)</option>
                                <option value="Diamond">হীরা (Diamond)</option>
                                <option value="Platinum">প্লাটিনাম (Platinum)</option>
                                <option value="Traditional">অন্যান্য / ট্রেডিশনাল</option>
                            </select>
                        </div>
                    </div>

                    {{-- Row 4: Weight Input (Two-Way Conversion) --}}
                    <div class="p-3 bg-light rounded border mb-3">
                        <label class="form-label fw-bold text-primary mb-2 d-flex align-items-center gap-1">
                            <i class="fa-solid fa-scale-balanced"></i> ওজন নির্ধারণ (গ্রাম অথবা ভরি-আনা-রতি) <span class="text-danger">*</span>
                        </label>
                        <div class="row g-2 align-items-center">
                            <div class="col-md-4">
                                <label class="small fw-semibold text-muted">গ্রাম (Grams):</label>
                                <div class="input-group">
                                    <input type="number" step="0.001" min="0.001" name="gram" id="add_calc_gram" class="form-control fw-bold fs-6 font-monospace" placeholder="0.000" required>
                                    <span class="input-group-text bg-white">গ্রাম</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="small fw-semibold text-muted">ভরি &bull; আনা &bull; রতি &bull; পয়েন্ট:</label>
                                <div class="row g-1">
                                    <div class="col-3">
                                        <input type="number" min="0" id="add_calc_bhori" class="form-control form-control-sm text-center font-monospace" placeholder="ভরি">
                                    </div>
                                    <div class="col-3">
                                        <input type="number" min="0" max="15" id="add_calc_ana" class="form-control form-control-sm text-center font-monospace" placeholder="আনা">
                                    </div>
                                    <div class="col-3">
                                        <input type="number" min="0" max="5" id="add_calc_roti" class="form-control form-control-sm text-center font-monospace" placeholder="রতি">
                                    </div>
                                    <div class="col-3">
                                        <input type="number" min="0" max="9" id="add_calc_point" class="form-control form-control-sm text-center font-monospace" placeholder="পয়েন্ট">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-text text-muted small mt-1">
                            যেকোন একটি পরিবর্তন করলে অপরটি স্বয়ংক্রিয়ভাবে হিসাব হবে (১ ভরি = ১১.৬৬৪ গ্রাম = ১৬ আনা = ৯৬ রতি = ৯৬০ পয়েন্ট)।
                        </div>
                    </div>

                    {{-- Row 5: Price & Payment Details --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">দর (Unit Rate ৳)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" name="unit_price" id="add_unit_price" class="form-control font-monospace" placeholder="0.00">
                                <select name="rate_type" id="add_rate_type" class="form-select bg-light" style="max-width: 110px;">
                                    <option value="per_gram">প্রতি গ্রাম</option>
                                    <option value="per_bhori">প্রতি ভরি</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">মোট মূল্য (৳)</label>
                            <input type="number" step="0.01" min="0" name="total_amount" id="add_total_amount" class="form-control font-monospace fw-bold" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">পরিশোধিত টাকা (৳)</label>
                            <input type="number" step="0.01" min="0" name="paid_amount" id="add_paid_amount" class="form-control font-monospace text-success fw-bold" placeholder="0.00">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বকেয়া পরিমাণ (৳)</label>
                            <input type="number" step="0.01" min="0" name="due_amount" id="add_due_amount" class="form-control font-monospace text-danger fw-bold" readonly placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">পেমেন্ট মেথড</label>
                            <select name="payment_method" class="form-select">
                                <option value="Cash">নগদ (Cash)</option>
                                <option value="Bank">ব্যাংক (Bank Transfer)</option>
                                <option value="bKash">বিকাশ (bKash)</option>
                                <option value="Nagad">নগদ (Nagad)</option>
                                <option value="Cheque">চেক (Cheque)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">হীরার ক্যারেট (Diamond হলে)</label>
                            <input type="number" step="0.001" min="0" name="carat" class="form-control font-monospace" placeholder="0.000 ct">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">বিশেষ নোট / বিবরণ</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="নোট বা মেমোর বিশেষ বিবরণ লিখুন..."></textarea>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-warning text-dark fw-bold px-4">
                        <i class="fa-solid fa-check me-1"></i> কাঁচামাল ক্রয় নিশ্চিত করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 2: EDIT RAW MATERIAL PURCHASE (কাঁচামাল ক্রয় সম্পাদন) --}}
{{-- ======================================================== --}}
<div class="modal fade" id="editRawPurchaseModal" tabindex="-1" aria-labelledby="editRawPurchaseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="editRawPurchaseModalLabel">
                    <i class="fa-solid fa-pen-to-square me-1"></i> কাঁচামাল ক্রয় সম্পাদন ও স্টক আপডেট
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRawPurchaseForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-warning py-2 small d-flex align-items-center gap-2 mb-3">
                        <i class="fa-solid fa-triangle-exclamation fs-5"></i>
                        <div><strong>সতর্কতা:</strong> ওজন পরিবর্তন করলে সেই অনুপাতে মূল কাঁচামাল স্টকে স্বয়ংক্রিয়ভাবে বৃদ্ধি বা হ্রাস সমন্বয় করা হবে।</div>
                    </div>

                    {{-- Row 1: Category & Invoice & Date --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ক্যাটাগরি <span class="text-danger">*</span></label>
                            <select name="category_id" id="edit_category_id" class="form-select" required>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ভাউচার / মেমো নং <span class="text-danger">*</span></label>
                            <input type="text" name="invoice_no" id="edit_invoice_no" class="form-control font-monospace" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">ক্রয়ের তারিখ <span class="text-danger">*</span></label>
                            <input type="date" name="purchase_date" id="edit_purchase_date" class="form-control" required>
                        </div>
                    </div>

                    {{-- Row 2: Supplier Information --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সাপ্লায়ার / বিক্রেতার নাম</label>
                            <input type="text" name="supplier_name" id="edit_supplier_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">সাপ্লায়ার ফোন নম্বর</label>
                            <input type="text" name="supplier_phone" id="edit_supplier_phone" class="form-control">
                        </div>
                    </div>

                    {{-- Row 3: Material Details & Karat --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ম্যাটেরিয়ালের বিবরণ / নাম</label>
                            <input type="text" name="material_name" id="edit_material_name" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ক্যারেট / পিউরিটি</label>
                            <input type="text" name="karat" id="edit_karat" class="form-control" placeholder="যেমন: 24K, 22K, খাঁটি রূপা">
                        </div>
                    </div>

                    {{-- Row 4: Weight Input (Two-Way Conversion) --}}
                    <div class="p-3 bg-light rounded border mb-3">
                        <label class="form-label fw-bold text-primary mb-2 d-flex align-items-center gap-1">
                            <i class="fa-solid fa-scale-balanced"></i> ওজন নির্ধারণ <span class="text-danger">*</span>
                        </label>
                        <div class="row g-2 align-items-center">
                            <div class="col-md-4">
                                <label class="small fw-semibold text-muted">গ্রাম (Grams):</label>
                                <div class="input-group">
                                    <input type="number" step="0.001" min="0.001" name="gram" id="edit_calc_gram" class="form-control fw-bold fs-6 font-monospace" required>
                                    <span class="input-group-text bg-white">গ্রাম</span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <label class="small fw-semibold text-muted">ভরি &bull; আনা &bull; রতি &bull; পয়েন্ট:</label>
                                <div class="row g-1">
                                    <div class="col-3">
                                        <input type="number" min="0" id="edit_calc_bhori" class="form-control form-control-sm text-center font-monospace" placeholder="ভরি">
                                    </div>
                                    <div class="col-3">
                                        <input type="number" min="0" max="15" id="edit_calc_ana" class="form-control form-control-sm text-center font-monospace" placeholder="আনা">
                                    </div>
                                    <div class="col-3">
                                        <input type="number" min="0" max="5" id="edit_calc_roti" class="form-control form-control-sm text-center font-monospace" placeholder="রতি">
                                    </div>
                                    <div class="col-3">
                                        <input type="number" min="0" max="9" id="edit_calc_point" class="form-control form-control-sm text-center font-monospace" placeholder="পয়েন্ট">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Row 5: Price & Payment Details --}}
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">দর (Unit Rate ৳)</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" name="unit_price" id="edit_unit_price" class="form-control font-monospace">
                                <select name="rate_type" id="edit_rate_type" class="form-select bg-light" style="max-width: 110px;">
                                    <option value="per_gram">প্রতি গ্রাম</option>
                                    <option value="per_bhori">প্রতি ভরি</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">মোট মূল্য (৳)</label>
                            <input type="number" step="0.01" min="0" name="total_amount" id="edit_total_amount" class="form-control font-monospace fw-bold">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">পরিশোধিত টাকা (৳)</label>
                            <input type="number" step="0.01" min="0" name="paid_amount" id="edit_paid_amount" class="form-control font-monospace text-success fw-bold">
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">বকেয়া পরিমাণ (৳)</label>
                            <input type="number" step="0.01" min="0" name="due_amount" id="edit_due_amount" class="form-control font-monospace text-danger fw-bold" readonly>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">পেমেন্ট মেথড</label>
                            <select name="payment_method" id="edit_payment_method" class="form-select">
                                <option value="Cash">নগদ (Cash)</option>
                                <option value="Bank">ব্যাংক (Bank Transfer)</option>
                                <option value="bKash">বিকাশ (bKash)</option>
                                <option value="Nagad">নগদ (Nagad)</option>
                                <option value="Cheque">চেক (Cheque)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">হীরার ক্যারেট (Diamond)</label>
                            <input type="number" step="0.001" min="0" name="carat" id="edit_carat" class="form-control font-monospace">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold">বিশেষ নোট / বিবরণ</label>
                        <textarea name="notes" id="edit_notes" class="form-control" rows="2"></textarea>
                    </div>

                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fa-solid fa-check me-1"></i> আপডেট সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 3: DELETE PURCHASE CONFIRMATION                    --}}
{{-- ======================================================== --}}
<div class="modal fade" id="deletePurchaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">
                    <i class="fa-solid fa-trash-can me-1"></i> কাঁচামাল ক্রয় মুছে ফেলা নিশ্চিত করুন
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deletePurchaseForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body p-4 text-center">
                    <i class="fa-solid fa-triangle-exclamation fa-3x text-danger mb-3"></i>
                    <h5 class="fw-bold text-dark mb-2">আপনি কি এই ক্রয় রেকর্ডটি মুছে ফেলতে চান?</h5>
                    <p class="text-muted mb-3" id="deletePurchaseDesc">
                        মেমো নং: <strong class="text-dark" id="delete_memo"></strong><br>
                        এই ক্রয়টির <strong class="text-danger" id="delete_gram"></strong> গ্রাম মূল কাঁচামাল স্টক হতে কেটে নেওয়া হবে।
                    </p>
                </div>
                <div class="modal-footer bg-light justify-content-center">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">না, বাতিল</button>
                    <button type="submit" class="btn btn-danger px-4 fw-bold">
                        <i class="fa-solid fa-trash-can me-1"></i> হ্যাঁ, মুছে ফেলুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 4: DIRECT QUICK ADD RAW STOCK                      --}}
{{-- ======================================================== --}}
<div class="modal fade" id="addRawStockModal" tabindex="-1" aria-labelledby="addRawStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="addRawStockModalLabel">
                    <i class="fa-solid fa-plus-circle me-1"></i> কুইক কাঁচামাল স্টক যোগ করুন
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('raw-stock.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ক্যাটাগরি নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">পরিমাণ (গ্রাম) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.001" min="0.001" name="gram" class="form-control fw-bold font-monospace" placeholder="0.000" required>
                            <span class="input-group-text bg-light">গ্রাম (GM)</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">ক্যারেট (Diamond হলে ঐচ্ছিক)</label>
                        <input type="number" step="0.001" min="0" name="carat" class="form-control font-monospace" placeholder="0.000">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">প্রতি গ্রাম মূল্য (৳) <span class="text-muted fw-normal">(ঐচ্ছিক)</span></label>
                        <input type="number" step="0.01" min="0" name="cost_per_gram" class="form-control font-monospace" placeholder="0.00">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">স্টক যোগের কারণ / উৎস</label>
                        <input type="text" name="reason" class="form-control" placeholder="যেমন: ওপেনিং ব্যালেন্স / মহাজন থেকে প্রাপ্ত">
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">বিশেষ নোট</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="নোট লিখুন..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">
                        <i class="fa-solid fa-check me-1"></i> স্টক সংরক্ষণ করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ======================================================== --}}
{{-- MODAL 5: ADJUST RAW STOCK                                --}}
{{-- ======================================================== --}}
<div class="modal fade" id="adjustRawStockModal" tabindex="-1" aria-labelledby="adjustRawStockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: #334155;">
                <h5 class="modal-title fw-bold text-white" id="adjustRawStockModalLabel">
                    <i class="fa-solid fa-sliders me-1"></i> কাঁচামাল স্টক সমন্বয় (Adjustment)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('raw-stock.adjust') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold">ক্যাটাগরি নির্বাচন করুন <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                @php
                                    $stk = $rawStocks[$cat->id]->gram ?? 0;
                                @endphp
                                <option value="{{ $cat->id }}">
                                    {{ $cat->category_name }} (বর্তমান: {{ number_format($stk, 3) }} গ্রাম)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">নতুন সঠিক পরিমাণ (গ্রাম) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.001" min="0" name="target_gram" class="form-control fw-bold font-monospace" placeholder="0.000" required>
                            <span class="input-group-text bg-light">গ্রাম (GM)</span>
                        </div>
                        <div class="form-text text-muted">সমন্বয়ের পর মোট স্টক এই পরিমাণে নির্ধারিত হবে।</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">সমন্বয়ের কারণ <span class="text-danger">*</span></label>
                        <input type="text" name="reason" class="form-control" placeholder="যেমন: বাৎসরিক ভৌত গণনা / ঘাটতি সমন্বয়" required>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-bold">বিশেষ নোট</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="নোট লিখুন..."></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary px-3" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn text-white fw-bold px-4 shadow-sm" style="background-color: #334155; border-color: #334155;">
                        <i class="fa-solid fa-check me-1"></i> সমন্বয় নিশ্চিত করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('admin_script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // -----------------------------------------------------------------
    // Two-way Weight Conversion Calculator (Grams <-> Bhori, Ana, Roti, Point)
    // 1 Vori = 11.664 g = 960 points. 1 Ana = 60 pts. 1 Roti = 10 pts. 1 Pt = 0.01215 g
    // -----------------------------------------------------------------
    function gramToUnits(grams) {
        grams = parseFloat(grams) || 0;
        if (grams <= 0) return { bhori: '', ana: '', roti: '', point: '' };
        var totalPoints = Math.round(grams / 0.01215);
        var bhori = Math.floor(totalPoints / 960);
        var rem = totalPoints % 960;
        var ana = Math.floor(rem / 60);
        rem = rem % 60;
        var roti = Math.floor(rem / 10);
        var point = rem % 10;
        return { bhori: bhori, ana: ana, roti: roti, point: point };
    }

    function unitsToGram(bhori, ana, roti, point) {
        var b = parseInt(bhori) || 0;
        var a = parseInt(ana) || 0;
        var r = parseInt(roti) || 0;
        var p = parseInt(point) || 0;
        var totalPoints = (b * 960) + (a * 60) + (r * 10) + p;
        if (totalPoints <= 0) return '';
        return (totalPoints * 0.01215).toFixed(3);
    }

    function calculatePrices(prefix) {
        var gram = parseFloat(document.getElementById(prefix + '_calc_gram').value) || 0;
        var unitPrice = parseFloat(document.getElementById(prefix + '_unit_price').value) || 0;
        var rateType = document.getElementById(prefix + '_rate_type').value;
        var totalInput = document.getElementById(prefix + '_total_amount');
        var paidInput = document.getElementById(prefix + '_paid_amount');
        var dueInput = document.getElementById(prefix + '_due_amount');

        var total = 0;
        if (unitPrice > 0 && gram > 0) {
            if (rateType === 'per_bhori') {
                var ratePerGram = unitPrice / 11.664;
                total = Math.round(gram * ratePerGram * 100) / 100;
            } else {
                total = Math.round(gram * unitPrice * 100) / 100;
            }
            totalInput.value = total.toFixed(2);
        }

        var currentTotal = parseFloat(totalInput.value) || total;
        var paid = parseFloat(paidInput.value) || 0;
        var due = Math.max(0, currentTotal - paid);
        dueInput.value = due.toFixed(2);
    }

    function bindTwoWayCalculator(prefix) {
        var gramInput = document.getElementById(prefix + '_calc_gram');
        var bhoriInput = document.getElementById(prefix + '_calc_bhori');
        var anaInput = document.getElementById(prefix + '_calc_ana');
        var rotiInput = document.getElementById(prefix + '_calc_roti');
        var pointInput = document.getElementById(prefix + '_calc_point');

        var unitPriceInput = document.getElementById(prefix + '_unit_price');
        var rateTypeInput = document.getElementById(prefix + '_rate_type');
        var totalInput = document.getElementById(prefix + '_total_amount');
        var paidInput = document.getElementById(prefix + '_paid_amount');

        // Typing Gram -> Updates Bhori, Ana, Roti, Point
        gramInput.addEventListener('input', function () {
            var units = gramToUnits(this.value);
            bhoriInput.value = units.bhori;
            anaInput.value = units.ana;
            rotiInput.value = units.roti;
            pointInput.value = units.point;
            calculatePrices(prefix);
        });

        // Typing Units -> Updates Gram
        [bhoriInput, anaInput, rotiInput, pointInput].forEach(function (inp) {
            inp.addEventListener('input', function () {
                var g = unitsToGram(bhoriInput.value, anaInput.value, rotiInput.value, pointInput.value);
                gramInput.value = g;
                calculatePrices(prefix);
            });
        });

        // Pricing listeners
        unitPriceInput.addEventListener('input', function () { calculatePrices(prefix); });
        rateTypeInput.addEventListener('change', function () { calculatePrices(prefix); });
        totalInput.addEventListener('input', function () {
            var total = parseFloat(this.value) || 0;
            var paid = parseFloat(paidInput.value) || 0;
            document.getElementById(prefix + '_due_amount').value = Math.max(0, total - paid).toFixed(2);
        });
        paidInput.addEventListener('input', function () {
            var total = parseFloat(totalInput.value) || 0;
            var paid = parseFloat(this.value) || 0;
            document.getElementById(prefix + '_due_amount').value = Math.max(0, total - paid).toFixed(2);
        });
    }

    bindTwoWayCalculator('add');
    bindTwoWayCalculator('edit');

    // -----------------------------------------------------------------
    // Edit Purchase Button Handler
    // -----------------------------------------------------------------
    document.querySelectorAll('.btn-edit-purchase').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            var form = document.getElementById('editRawPurchaseForm');
            form.action = "{{ url('admin/raw-stocks/purchases') }}/" + id + "/update";

            document.getElementById('edit_category_id').value = this.getAttribute('data-category_id');
            document.getElementById('edit_invoice_no').value = this.getAttribute('data-invoice_no');
            document.getElementById('edit_purchase_date').value = this.getAttribute('data-purchase_date');
            document.getElementById('edit_supplier_name').value = this.getAttribute('data-supplier_name') || '';
            document.getElementById('edit_supplier_phone').value = this.getAttribute('data-supplier_phone') || '';
            document.getElementById('edit_material_name').value = this.getAttribute('data-material_name') || '';
            document.getElementById('edit_karat').value = this.getAttribute('data-karat') || '';
            
            var gram = parseFloat(this.getAttribute('data-gram')) || 0;
            document.getElementById('edit_calc_gram').value = gram > 0 ? gram : '';
            var units = gramToUnits(gram);
            document.getElementById('edit_calc_bhori').value = units.bhori;
            document.getElementById('edit_calc_ana').value = units.ana;
            document.getElementById('edit_calc_roti').value = units.roti;
            document.getElementById('edit_calc_point').value = units.point;

            document.getElementById('edit_unit_price').value = this.getAttribute('data-unit_price') || '';
            document.getElementById('edit_rate_type').value = this.getAttribute('data-rate_type') || 'per_gram';
            document.getElementById('edit_total_amount').value = this.getAttribute('data-total_amount') || '';
            document.getElementById('edit_paid_amount').value = this.getAttribute('data-paid_amount') || '';
            document.getElementById('edit_due_amount').value = this.getAttribute('data-due_amount') || '';
            document.getElementById('edit_payment_method').value = this.getAttribute('data-payment_method') || 'Cash';
            document.getElementById('edit_carat').value = this.getAttribute('data-carat') || '';
            document.getElementById('edit_notes').value = this.getAttribute('data-notes') || '';

            var modalEl = document.getElementById('editRawPurchaseModal');
            if (window.bootstrap && bootstrap.Modal) {
                var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.show();
            } else if (window.jQuery) {
                $(modalEl).modal('show');
            }
        });
    });

    // -----------------------------------------------------------------
    // Delete Purchase Button Handler
    // -----------------------------------------------------------------
    document.querySelectorAll('.btn-delete-purchase').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            var invoice = this.getAttribute('data-invoice');
            var gram = this.getAttribute('data-gram');
            var cat = this.getAttribute('data-category');

            document.getElementById('delete_memo').textContent = invoice;
            document.getElementById('delete_gram').textContent = gram + " (" + cat + ")";
            document.getElementById('deletePurchaseForm').action = "{{ url('admin/raw-stocks/purchases') }}/" + id;

            var modalEl = document.getElementById('deletePurchaseModal');
            if (window.bootstrap && bootstrap.Modal) {
                var modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                modal.show();
            } else if (window.jQuery) {
                $(modalEl).modal('show');
            }
        });
    });
});
</script>
@endpush
