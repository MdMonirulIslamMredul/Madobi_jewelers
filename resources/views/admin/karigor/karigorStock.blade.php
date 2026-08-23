@extends('admin.master')
@section('title')
    কারিগর স্টক
@endsection
@push('admin_style')
    @include('admin.common.style')
@endpush
@section('body')
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3><i class="fa-solid fa-user-gear me-2 text-info"></i>কারিগর স্টক তালিকা</h3>
                        <span class="badge bg-info fs-6">মোট পণ্য: {{ isset($purchases) ? $purchases->count() : 0 }}</span>
                    </div>
                </div>
                <div class="card-body">

                    @if(isset($purchases) && $purchases->count() > 0)
                    {{-- ═══ Karat-wise Summary ═══ --}}
                    @php
                        $karatGroups = ['22K' => [], '21K' => [], '18K' => [], 'পায়েনে' => [], 'অন্যান্য' => []];
                        $knownKarats = ['22K', '21K', '18K', 'পায়েনে'];

                        foreach ($purchases as $p) {
                            $k = trim($p->karat ?? '');
                            if (!in_array($k, $knownKarats)) { $k = 'অন্যান্য'; }
                            $karatGroups[$k][] = $p;
                        }

                        $summaryRows = [];
                        foreach ($karatGroups as $label => $items) {
                            if (count($items) === 0) continue;
                            $summaryRows[$label] = [
                                'count'    => count($items),
                                'gross'    => round(array_sum(array_map(fn($i) => (float)($i->gram ?? 0), $items)), 4),
                                'purity'   => round(array_sum(array_map(fn($i) => (float)($i->raw_gold ?? 0), $items)), 4),
                            ];
                        }

                        $totalCount  = $purchases->count();
                        $totalGross  = round($purchases->sum(fn($i) => (float)($i->gram ?? 0)), 4);
                        $totalPurity = round($purchases->sum(fn($i) => (float)($i->raw_gold ?? 0)), 4);

                        $karatColors = [
                            '22K'       => ['bg'=>'linear-gradient(135deg,#d97706,#92400e)', 'badge'=>'#78350f', 'text'=>'#ffffff'],
                            '21K'       => ['bg'=>'linear-gradient(135deg,#7c3aed,#5b21b6)', 'badge'=>'#4c1d95', 'text'=>'#ffffff'],
                            '18K'       => ['bg'=>'linear-gradient(135deg,#059669,#047857)', 'badge'=>'#064e3b', 'text'=>'#ffffff'],
                            'পায়েনে'   => ['bg'=>'linear-gradient(135deg,#dc2626,#991b1b)', 'badge'=>'#7f1d1d', 'text'=>'#ffffff'],
                            'অন্যান্য' => ['bg'=>'linear-gradient(135deg,#475569,#1e293b)', 'badge'=>'#0f172a', 'text'=>'#ffffff'],
                        ];
                    @endphp

                    @if(count($summaryRows) > 0)
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="fa-solid fa-chart-pie text-primary fs-5"></i>
                            <span class="fw-bold text-dark" style="font-size:15px; color:#212529 !important;">ক্যারেট অনুযায়ী স্টক সারসংক্ষেপ</span>
                            <hr class="flex-grow-1 my-0 opacity-25">
                        </div>
                        <div class="row g-3">
                            @foreach($summaryRows as $label => $row)
                            @php $clr = $karatColors[$label] ?? $karatColors['অন্যান্য']; @endphp
                            <div class="col-sm-6 col-md-4 col-xl-2">
                                <div class="p-3 rounded-3 shadow-sm h-100 position-relative overflow-hidden"
                                     style="background:{{ $clr['bg'] }};color:#ffffff !important;">
                                    <div style="position:absolute;top:-14px;right:-14px;width:70px;height:70px;border-radius:50%;background:rgba(255,255,255,.15);"></div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold" style="font-size:18px;letter-spacing:1px;color:#ffffff !important;">{{ $label }}</span>
                                        <span class="badge rounded-pill bg-white text-dark fw-bold px-2 py-1" style="font-size:11px;">{{ $row['count'] }} টি</span>
                                    </div>
                                    <div class="d-flex flex-column gap-1" style="font-size:12px;color:#ffffff !important;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span style="opacity:.9;color:#ffffff !important;"><i class="fa-solid fa-weight-hanging fa-xs me-1"></i>গ্রস ওজন</span>
                                            <span class="fw-bold" style="color:#ffffff !important;">{{ $row['gross'] }} গ্রাম</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span style="opacity:.9;color:#ffffff !important;"><i class="fa-solid fa-atom fa-xs me-1"></i>বিশুদ্ধতা</span>
                                            <span class="fw-bold" style="color:#ffffff !important;">{{ $row['purity'] }} গ্রাম</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                            {{-- Grand Total Card --}}
                            <div class="col-sm-6 col-md-4 col-xl-2">
                                <div class="p-3 rounded-3 shadow-sm h-100 position-relative overflow-hidden"
                                     style="background:linear-gradient(135deg,#1a1a2e,#16213e);color:#fff;border:2px solid rgba(255,255,255,.15);">
                                    <div style="position:absolute;top:-14px;right:-14px;width:70px;height:70px;border-radius:50%;background:rgba(255,255,255,.07);"></div>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="fw-bold d-flex align-items-center gap-1" style="font-size:14px;">
                                            <i class="fa-solid fa-sigma fa-sm"></i> মোট সর্বমোট
                                        </span>
                                        <span class="badge rounded-pill bg-light text-dark fw-bold px-2 py-1" style="font-size:11px;">{{ $totalCount }} টি</span>
                                    </div>
                                    <div class="d-flex flex-column gap-1" style="font-size:12px;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span style="opacity:.75;"><i class="fa-solid fa-weight-hanging fa-xs me-1"></i>গ্রস ওজন</span>
                                            <span class="fw-bold text-warning">{{ $totalGross }} গ্রাম</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span style="opacity:.75;"><i class="fa-solid fa-atom fa-xs me-1"></i>বিশুদ্ধতা</span>
                                            <span class="fw-bold text-success">{{ $totalPurity }} গ্রাম</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Filter Bar --}}
                    <form method="GET" action="{{ route('karigor.stock') }}" class="mb-4 p-3 bg-light rounded-3 border">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-dark mb-1" style="color: #212529 !important;"><i class="fa-solid fa-filter me-1 text-primary"></i>ক্যাটাগরি</label>
                                <select name="category_id" class="form-select form-select-sm text-dark fw-semibold" style="color: #212529 !important; background-color: #ffffff !important;" onchange="this.form.submit()">
                                    <option value="all" {{ ($categoryId ?? 'all') === 'all' ? 'selected' : '' }}>সকল ক্যাটাগরি (All)</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (string)($categoryId ?? '') === (string)$cat->id ? 'selected' : '' }}>{{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-bold text-dark mb-1" style="color: #212529 !important;"><i class="fa-solid fa-gem me-1 text-warning"></i>ক্যারেট</label>
                                <select name="karat" class="form-select form-select-sm text-dark fw-semibold" style="color: #212529 !important; background-color: #ffffff !important;" onchange="this.form.submit()">
                                    <option value="all" {{ ($karat ?? 'all') === 'all' ? 'selected' : '' }}>সকল ক্যারেট</option>
                                    <option value="22K" {{ ($karat ?? '') === '22K' ? 'selected' : '' }}>22K</option>
                                    <option value="21K" {{ ($karat ?? '') === '21K' ? 'selected' : '' }}>21K</option>
                                    <option value="18K" {{ ($karat ?? '') === '18K' ? 'selected' : '' }}>18K</option>
                                    <option value="পায়েনে" {{ ($karat ?? '') === 'পায়েনে' ? 'selected' : '' }}>পায়েনে</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-dark mb-1" style="color: #212529 !important;"><i class="fa-solid fa-list-check me-1 text-info"></i>জব স্ট্যাটাস</label>
                                <select name="job_status" class="form-select form-select-sm text-dark fw-semibold" style="color: #212529 !important; background-color: #ffffff !important;" onchange="this.form.submit()">
                                    <option value="all" {{ ($jobStatus ?? 'all') === 'all' ? 'selected' : '' }}>সকল স্ট্যাটাস</option>
                                    <option value="in_progress" {{ ($jobStatus ?? '') === 'in_progress' ? 'selected' : '' }}>চলমান (In Progress)</option>
                                    <option value="completed" {{ ($jobStatus ?? '') === 'completed' ? 'selected' : '' }}>সম্পন্ন (Completed)</option>
                                    <option value="unassigned" {{ ($jobStatus ?? '') === 'unassigned' ? 'selected' : '' }}>অ্যাসাইন করা হয়নি (Unassigned)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-dark mb-1" style="color: #212529 !important;"><i class="fa-solid fa-user me-1 text-success"></i>কারিগর</label>
                                <select name="karigor_id" class="form-select form-select-sm text-dark fw-semibold" style="color: #212529 !important; background-color: #ffffff !important;" onchange="this.form.submit()">
                                    <option value="all" {{ ($karigorId ?? 'all') === 'all' ? 'selected' : '' }}>সকল কারিগর</option>
                                    @foreach($users as $kUser)
                                        <option value="{{ $kUser->id }}" {{ (string)($karigorId ?? '') === (string)$kUser->id ? 'selected' : '' }}>
                                            {{ $kUser->name }} ({{ $kUser->phone ?? '—' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <a href="{{ route('karigor.stock') }}" class="btn btn-outline-secondary btn-sm w-100" title="রিসেট">
                                    <i class="fa-solid fa-rotate-left"></i>
                                </a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="config-table" class="table display table-striped border no-wrap">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center" style="white-space:normal;">তারিখ ও<br>ইনভয়েস আইডি</th>
                                    <th class="text-center" style="white-space:normal;">ক্যাটাগরি<br>পণ্যের নাম</th>
                                    <th class="text-center">ক্রয় আইডি</th>
                                    <th class="text-center">ক্যারেট ও ওজন</th>
                                    <th class="text-center">বিশুদ্ধতার ওজন</th>
                                    <th class="text-center" style="white-space:normal;">জব স্ট্যাটাস ও<br>কারিগর</th>
                                    <th class="text-center">পণ্যের বিবরণ</th>
                                    <th class="text-center">একশন্স</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @forelse ($purchases as $purchase)
                                <tr>
                                    <td class="text-center">
                                        <strong>{{ $sl }}</strong>
                                    </td>

                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-link p-0 text-decoration-none text-dark user-detail-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#userDetailModal"
                                            data-user-id="{{ $purchase->user_id ?? '' }}"
                                            data-user-name="{{ $purchase->user->name ?? '—' }} {{ $purchase->user->last_name ?? '' }}"
                                            data-user-phone="{{ $purchase->user->phone ?? '—' }}"
                                            data-user-phone2="{{ $purchase->user->phone2 ?? '' }}"
                                            data-user-email="{{ $purchase->user->email ?? '—' }}"
                                            data-user-role="{{ $purchase->user->role->role_name ?? '—' }}"
                                            data-user-active="{{ $purchase->user->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}"
                                            data-user-image="{{ ($purchase->user->image && $purchase->user->image !== 'default_user.jpg') ? asset('user/' . $purchase->user->image) : asset('cover/default_user.jpg') }}"
                                            data-user-photo1="{{ $purchase->user->photo1 ? asset('user/' . $purchase->user->photo1) : '' }}"
                                            title="ব্যবহারকারীর বিবরণ দেখুন">
                                            <div class="fw-semibold">{{ $purchase->order_date ? \Carbon\Carbon::parse($purchase->order_date)->format('d M Y') : ($purchase->created_at ? \Carbon\Carbon::parse($purchase->created_at)->format('d M Y') : '—') }}</div>
                                            @if($purchase->invoice)
                                            <span class="badge bg-dark mt-1">INV #{{ $purchase->invoice_id }}</span>
                                            @endif
                                            <div class="mt-1">
                                                <span class="badge" style="background:#0d6efd22;color:#0d6efd;font-size:10px;">
                                                    <i class="fa-solid fa-user fa-xs me-1"></i>{{ $purchase->user->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </button>
                                    </td>

                                    <td class="text-center">
                                        <button type="button"
                                            class="btn btn-link p-0 text-decoration-none product-detail-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#productDetailModal"
                                            data-prod-id="{{ $purchase->id }}"
                                            data-prod-category="{{ $purchase->productCategory->category_name ?? '—' }}"
                                            data-prod-name="{{ $purchase->product->product_name ?? '—' }}"
                                            data-prod-karat="{{ $purchase->karat ?? '—' }}"
                                            data-prod-bhori="{{ $purchase->bhori ?? 0 }}"
                                            data-prod-ana="{{ $purchase->ana ?? 0 }}"
                                            data-prod-roti="{{ $purchase->roti ?? 0 }}"
                                            data-prod-point="{{ $purchase->point ?? 0 }}"
                                            data-prod-gram="{{ $purchase->gram ?? 0 }}"
                                            data-prod-raw-gold="{{ $purchase->raw_gold ?? '—' }}"
                                            data-prod-actual-price="{{ number_format($purchase->actual_price ?? 0, 2) }}"
                                            data-prod-total-price="{{ number_format($purchase->total_price ?? 0, 2) }}"
                                            data-prod-details="{{ $purchase->details ?? '—' }}"
                                            data-prod-photo="{{ ($purchase->photo && $purchase->photo !== 'default-cover.jpg') ? asset('user/purchase/' . $purchase->photo) : asset('cover/default-cover.jpg') }}"
                                            title="পণ্যের বিবরণ দেখুন">
                                            <span class="badge bg-primary">{{ $purchase->productCategory->category_name ?? '—' }}</span><br>
                                            <span class="badge bg-info mt-1">{{ $purchase->product->product_name ?? '—' }}</span>
                                        </button>
                                    </td>

                                    <td class="text-center">
                                        <strong>{{ $purchase->id }}</strong>
                                    </td>

                                    <td class="text-center">
                                        <span class="badge" style="background:#6f42c1;">{{ $purchase->karat ?? '—' }}</span><br>
                                        <span class="badge bg-info mt-1">{{ $purchase->bhori ?? 0 }} ভরি, {{ $purchase->ana ?? 0 }} আনা, {{ $purchase->roti ?? 0 }} রতি, {{ $purchase->point ?? 0 }} পয়েন্ট</span><br>
                                        <span class="badge bg-danger mt-1">({{ $purchase->gram ?? 0 }} গ্রাম)</span>
                                    </td>

                                    <td class="text-center">
                                        {{ $purchase->raw_gold ?? '—' }} গ্রাম
                                    </td>

                                    {{-- Job Status & Karigor --}}
                                    <td class="text-center">
                                        @if($purchase->activeKarigorJob)
                                            @php $activeJob = $purchase->activeKarigorJob; @endphp
                                            @if($activeJob->status === 'in_progress')
                                                <span class="badge bg-warning text-dark px-2 py-1 shadow-sm"><i class="fa-solid fa-spinner fa-spin me-1"></i>চলমান</span>
                                            @elseif($activeJob->status === 'completed')
                                                <span class="badge bg-success px-2 py-1 shadow-sm"><i class="fa-solid fa-circle-check me-1"></i>সম্পন্ন</span>
                                            @else
                                                <span class="badge bg-secondary px-2 py-1">{{ $activeJob->status }}</span>
                                            @endif
                                            <div class="mt-1 small fw-bold text-dark">
                                                👤 {{ $activeJob->karigor->name ?? '—' }} {{ $activeJob->karigor->last_name ?? '' }}
                                            </div>
                                                @if($activeJob->task_type === 'Repair')
                                                    <span class="badge px-2 py-1 fw-bold shadow-sm" style="background-color: #0d6efd !important; color: #ffffff !important; font-size: 11px;">
                                                        <i class="fa-solid fa-wrench me-1"></i>Repair
                                                    </span>
                                                @else
                                                    <span class="badge px-2 py-1 fw-bold shadow-sm" style="background-color: #6f42c1 !important; color: #ffffff !important; font-size: 11px;">
                                                        <i class="fa-solid fa-coins me-1"></i>Raw Gold (পাকা করা)
                                                    </span>
                                                @endif
                                                @if($activeJob->conversion_percentage)
                                                    <br><span class="badge bg-info text-dark mt-1 px-2 py-1"><i class="fa-solid fa-chart-line me-1"></i>{{ number_format($activeJob->conversion_percentage, 2) }}% Conversion</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="badge px-2 py-1 fw-bold shadow-sm" style="background-color:#495057 !important; color:#ffffff !important; font-size:12px;">
                                                <i class="fa-solid fa-user-slash me-1" style="color:#ffc107 !important;"></i>অ্যাসাইন করা হয়নি
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center" style="max-width:180px;white-space:normal;">
                                        {{ $purchase->details ?? '—' }}
                                    </td>

                                    <td class="text-center">
                                        <div class="action-btns d-flex align-items-center justify-content-center gap-2 flex-nowrap">
                                            {{-- View Icon --}}
                                            <a href="{{ $purchase->transaction_id ? route('purchase.show', $purchase->transaction_id) : '#' }}"
                                                class="text-success me-1" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="ভিউ করুন">
                                                <i class="fa-solid fa-eye fs-5"></i>
                                            </a>

                                            {{-- Edit Icon --}}
                                            <a href="{{ $purchase->transaction_id ? route('purchase.edit', $purchase->transaction_id) : '#' }}"
                                                class="text-info me-1" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="ইডিট করুন">
                                                <i class="fa-solid fa-pen-to-square fa-fw fs-5"></i>
                                            </a>

                                            {{-- Receive / Complete Job Button (If active job is in_progress) --}}
                                            @if($purchase->activeKarigorJob && $purchase->activeKarigorJob->status === 'in_progress')
                                                <button type="button"
                                                    class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 text-nowrap px-2 py-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#completeJobModal{{ $purchase->activeKarigorJob->id }}"
                                                    title="কারিগরের কাজ গ্রহণ ও স্টক আপডেট করুন">
                                                    <i class="fa-solid fa-circle-check"></i> কাজ গ্রহণ করুন
                                                </button>
                                            @endif

                                            {{-- Assign Job Button --}}
                                            <button type="button"
                                                class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 assign-job-btn text-nowrap px-2 py-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#assignJobModal"
                                                data-purchase-id="{{ $purchase->id }}"
                                                data-category="{{ $purchase->productCategory->category_name ?? '—' }}"
                                                data-product="{{ $purchase->product->product_name ?? '—' }}"
                                                data-karat="{{ $purchase->karat ?? '—' }}"
                                                data-bhori="{{ $purchase->bhori ?? 0 }}"
                                                data-ana="{{ $purchase->ana ?? 0 }}"
                                                data-roti="{{ $purchase->roti ?? 0 }}"
                                                data-point="{{ $purchase->point ?? 0 }}"
                                                data-gram="{{ $purchase->gram ?? 0 }}"
                                                data-raw-gold="{{ $purchase->raw_gold ?? '—' }}"
                                                title="Assign Job to Karigor">
                                                <i class="fa-solid fa-user-gear"></i> Assign Job
                                            </button>

                                            {{-- History Icon Button --}}
                                            {{-- <button type="button"
                                                class="btn btn-sm btn-outline-info d-inline-flex align-items-center gap-1 text-nowrap px-2 py-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#locationHistoryModal{{ $purchase->id }}"
                                                title="অবস্থান হিস্ট্রি দেখুন">
                                                <i class="fa-solid fa-clock-rotate-left"></i> হিস্ট্রি
                                            </button> --}}

                                            {{-- Move To Dropdown --}}
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-2 py-1"
                                                    type="button"
                                                    id="moveDropdown{{ $purchase->id }}"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    title="স্থানান্তর করুন">
                                                    <i class="fa-solid fa-arrows-turn-right fa-fw"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow"
                                                    aria-labelledby="moveDropdown{{ $purchase->id }}">

                                                    {{-- Move to Shop --}}
                                                    <li>
                                                        <form action="{{ route('stock.send-to-shop') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                            <button type="submit"
                                                                class="dropdown-item d-flex align-items-center gap-2 text-primary">
                                                                <i class="fa-solid fa-store fa-fw"></i>
                                                                শপে পাঠান
                                                            </button>
                                                        </form>
                                                    </li>

                                                    {{-- Move to Warehouse --}}
                                                    <li>
                                                        <form action="{{ route('stock.send-to-warehouse') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                            <button type="submit"
                                                                class="dropdown-item d-flex align-items-center gap-2 text-success">
                                                                <i class="fa-solid fa-warehouse fa-fw"></i>
                                                                গুদামে পাঠান
                                                            </button>
                                                        </form>
                                                    </li>

                                                    {{-- Move back to Hold --}}
                                                    <li>
                                                        <form action="{{ route('stock.send-to-hold') }}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                            <button type="submit"
                                                                class="dropdown-item d-flex align-items-center gap-2 text-warning">
                                                                <i class="fa-solid fa-rotate-left fa-fw"></i>
                                                                হোল্ডে ফেরত পাঠান
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Location History Modal for Purchase #{{ $purchase->id }} --}}
                                    <div class="modal fade" id="locationHistoryModal{{ $purchase->id }}" tabindex="-1" aria-labelledby="locationHistoryModalLabel{{ $purchase->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header text-white" style="background:linear-gradient(135deg,#0dcaf0,#0aa2c0);">
                                                    <h5 class="modal-title d-flex align-items-center gap-2" id="locationHistoryModalLabel{{ $purchase->id }}">
                                                        <i class="fa-solid fa-clock-rotate-left fa-lg"></i>
                                                        অবস্থান হিস্ট্রি (Purchase ID - {{ $purchase->id }})
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body p-4 text-start">
                                                    <div class="mb-3 p-3 rounded-3" style="background:#f8f9fa;border:1px solid #e9ecef;">
                                                        <div class="fw-bold text-dark mb-1">
                                                            <i class="fa-solid fa-gem text-primary me-1"></i>
                                                            {{ $purchase->productCategory->category_name ?? '' }} - {{ $purchase->product->product_name ?? '' }}
                                                        </div>
                                                        <div class="small text-muted">
                                                            ক্যারেট: <strong>{{ $purchase->karat ?? '—' }}</strong> | 
                                                            ওজন: <strong>{{ $purchase->bhori ?? 0 }}ভরি, {{ $purchase->ana ?? 0 }}আনা, {{ $purchase->roti ?? 0 }}রতি, {{ $purchase->point ?? 0 }}পয়েন্ট ({{ $purchase->gram ?? 0 }} গ্রাম)</strong>
                                                        </div>
                                                    </div>

                                                    @if($purchase->locationHistories && count($purchase->locationHistories) > 0)
                                                        <div class="position-relative ps-4 ms-2 mt-3" style="border-left: 2px dashed #0dcaf0;">
                                                            @foreach($purchase->locationHistories as $history)
                                                                <div class="position-relative mb-4">
                                                                    <div class="position-absolute bg-info rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm"
                                                                         style="width: 28px; height: 28px; left: -31px; top: 0;">
                                                                        <i class="fa-solid fa-arrow-right-long fa-xs"></i>
                                                                    </div>
                                                                    <div class="card border-0 shadow-sm p-3 bg-white" style="border-radius:10px;">
                                                                        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-1">
                                                                            <span class="badge bg-light text-dark border p-2">
                                                                                {{ str_replace(['is_hold', 'is_karigor', 'is_shop', 'is_warehouse'], ['Hold (হোল্ড)', 'Karigor (কারিগর)', 'Shop (শপ)', 'Warehouse (গুদাম)'], $history->from_location ?: 'নতুন ক্রয়') }}
                                                                                &nbsp;<i class="fa-solid fa-arrow-right text-primary"></i>&nbsp;
                                                                                <strong class="text-primary">{{ str_replace(['is_hold', 'is_karigor', 'is_shop', 'is_warehouse'], ['Hold (হোল্ড)', 'Karigor (কারিগর)', 'Shop (শপ)', 'Warehouse (গুদাম)'], $history->to_location) }}</strong>
                                                                            </span>
                                                                            <small class="text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $history->created_at ? $history->created_at->format('d M Y, h:i A') : '—' }}</small>
                                                                        </div>

                                                                        @if($history->karigor)
                                                                            <div class="small text-dark mb-1">
                                                                                👤 <strong>অর্পিত কারিগর:</strong> {{ $history->karigor->name }} {{ $history->karigor->last_name ?? '' }} (📱 {{ $history->karigor->phone ?? '—' }})
                                                                            </div>
                                                                        @endif

                                                                        @if($history->task_type)
                                                                            <div class="small text-dark mb-1">
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
                                                        <div class="text-center text-muted py-4">
                                                            <i class="fa-solid fa-timeline me-1"></i> কোন অবস্থান হিস্ট্রি পাওয়া যায়নি।
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if($purchase->activeKarigorJob && $purchase->activeKarigorJob->status === 'in_progress')
                                    @php $cJob = $purchase->activeKarigorJob; @endphp
                                    {{-- Complete Job Modal --}}
                                    <div class="modal fade" id="completeJobModal{{ $cJob->id }}" tabindex="-1" aria-labelledby="completeJobModalLabel{{ $cJob->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-header text-white" style="background:linear-gradient(135deg,#198754,#146c43);">
                                                    <h5 class="modal-title d-flex align-items-center gap-2" id="completeJobModalLabel{{ $cJob->id }}">
                                                        <i class="fa-solid fa-circle-check fa-lg"></i>
                                                        কারিগরের কাজ গ্রহণ ও সমাপ্তি [Job Id #{{ $cJob->id }}]
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('karigor-job.complete') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="job_id" value="{{ $cJob->id }}">

                                                    <div class="modal-body p-4 text-start">

                                                        {{-- Job Summary Header --}}
                                                        <div class="p-3 mb-4 rounded-3" style="background:#e8f5e9;border:1px solid #c8e6c9;">
                                                            <div class="fw-bold text-success mb-1">
                                                                <i class="fa-solid fa-user-gear me-1"></i> জব সামারি (Job Summary):
                                                            </div>
                                                            <div class="row g-2 small text-dark mt-1">
                                                                <div class="col-sm-4">
                                                                    <span class="text-muted">অর্পিত কারিগর:</span> <strong>{{ $cJob->karigor->name ?? '—' }}</strong>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <span class="text-muted">কাজের ধরন:</span> <strong class="text-primary">{{ $cJob->task_type }}</strong>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <span class="text-muted">প্রদত্ত গ্রস ওজন:</span> <strong>{{ $cJob->given_gross_weight ?? 0 }} গ্রাম</strong>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <span class="text-muted">প্রদত্ত র গোল্ড:</span> <strong class="text-success">{{ $cJob->given_purity_weight ?? 0 }} গ্রাম</strong>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <span class="text-muted">অতিরিক্ত প্রদত্ত র গোল্ড:</span> <strong>{{ $cJob->assigned_extra_raw_gold ?? 0 }} গ্রাম</strong>
                                                                </div>
                                                                <div class="col-sm-4">
                                                                    <span class="text-muted">অর্পণ তারিখ:</span> <strong>{{ $cJob->assigned_at ? $cJob->assigned_at->format('d M Y, h:i A') : '—' }}</strong>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        {{-- Return Inputs --}}
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label for="returned_raw_gold_{{ $cJob->id }}" class="form-label fw-bold">
                                                                    ১. ফেরত পাওয়া কাঁচা/পাকা সোনা (Returned Raw Gold) <span class="text-danger">*</span>
                                                                </label>
                                                                <div class="input-group">
                                                                    <input type="number" step="0.001" min="0" name="returned_raw_gold" id="returned_raw_gold_{{ $cJob->id }}" class="form-control form-control-lg fw-bold text-success returned-raw-gold-input" data-job-id="{{ $cJob->id }}" data-given-purity="{{ $cJob->given_purity_weight }}" placeholder="গ্রাম হিসাব লিখুন..." required>
                                                                    <span class="input-group-text bg-light fw-bold">গ্রাম (GM)</span>
                                                                </div>
                                                                <small class="text-muted">কারিগর থেকে ফেরত পাওয়া মোট কাঁচা সোনা।</small>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="used_extra_raw_gold_{{ $cJob->id }}" class="form-label fw-bold">
                                                                    ২. ব্যবহৃত অতিরিক্ত সোনা (Used Extra Raw Gold) <span class="text-muted fw-normal">(ঐচ্ছিক)</span>
                                                                </label>
                                                                <div class="input-group">
                                                                    <input type="number" step="0.001" min="0" name="used_extra_raw_gold" id="used_extra_raw_gold_{{ $cJob->id }}" class="form-control form-control-lg used-extra-gold-input" data-job-id="{{ $cJob->id }}" data-given-purity="{{ $cJob->given_purity_weight }}" value="{{ $cJob->assigned_extra_raw_gold }}" placeholder="ব্যবহৃত পরিমাণ...">
                                                                    <span class="input-group-text bg-light">গ্রাম (GM)</span>
                                                                </div>
                                                                <small class="text-muted">মেরামতে প্রকৃতপক্ষে ব্যবহৃত অতিরিক্ত সোনা।</small>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="returned_gross_weight_{{ $cJob->id }}" class="form-label fw-bold">
                                                                    ৩. ফেরত পণ্যের মোট ওজন (Returned Gross Weight) <span class="text-muted fw-normal">(ঐচ্ছিক)</span>
                                                                </label>
                                                                <div class="input-group">
                                                                    <input type="number" step="0.001" min="0" name="returned_gross_weight" id="returned_gross_weight_{{ $cJob->id }}" class="form-control" value="{{ $cJob->given_gross_weight }}" placeholder="মোট ওজন লিখুন...">
                                                                    <span class="input-group-text bg-light">গ্রাম (GM)</span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="notes_{{ $cJob->id }}" class="form-label fw-bold">
                                                                    ৪. মন্তব্য / রিমার্কস (Notes) <span class="text-muted fw-normal">(ঐচ্ছিক)</span>
                                                                </label>
                                                                <input type="text" name="notes" id="notes_{{ $cJob->id }}" class="form-control" placeholder="কোন বিশেষ বিবরণ থাকলে লিখুন...">
                                                            </div>
                                                        </div>

                                                        {{-- Dynamic Conversion % Box --}}
                                                        <div class="mt-4 p-3 rounded-3 bg-light border text-center">
                                                            <div class="row align-items-center">
                                                                <div class="col-6 border-end">
                                                                    <small class="text-muted d-block">অনুমানিক কনভার্সন শতকরা (Conversion %):</small>
                                                                    <span id="calc_conversion_pct_{{ $cJob->id }}" class="fs-4 fw-bold text-primary">— %</span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <small class="text-muted d-block">অনুমানিক ঘাটতি/ওয়েস্টেজ (Wastage Loss):</small>
                                                                    <span id="calc_wastage_loss_{{ $cJob->id }}" class="fs-4 fw-bold text-danger">— গ্রাম</span>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer bg-light">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                        <button type="submit" class="btn btn-success px-4 fw-bold">
                                                            <i class="fa-solid fa-check-double me-1"></i> কাজ সম্পন্ন ও স্টক আপডেট করুন
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </tr>
                                @php $sl++; @endphp
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">কোন কারিগর স্টক পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="alert alert-info text-center my-3">
                        <i class="fa-solid fa-info-circle me-2"></i>কোন কারিগর স্টক পাওয়া যায়নি।
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- User Detail Modal --}}
    <div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background:linear-gradient(135deg,#1a1a2e,#16213e);">
                    <h5 class="modal-title text-white d-flex align-items-center gap-2" id="userDetailModalLabel">
                        <i class="fa-solid fa-circle-user fa-lg"></i>
                        ব্যবহারকারীর বিবরণ
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <div id="modal-user-avatar-wrap" style="width:80px;height:80px;border-radius:50%;overflow:hidden;display:inline-block;border:3px solid #667eea;box-shadow:0 4px 14px rgba(102,126,234,.35);">
                            <img id="modal-user-avatar" src="" alt="user" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        <h5 class="mt-2 mb-0 fw-bold" id="modal-user-name"></h5>
                        <span class="badge" id="modal-user-role" style="background:#667eea;"></span>
                    </div>
                    <hr>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded" style="background:#f8f9fa;">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-id-badge me-1"></i>ইউজার আইডি</div>
                                <div class="fw-semibold" id="modal-user-id"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded" style="background:#f8f9fa;">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-circle-dot me-1"></i>স্ট্যাটাস</div>
                                <div class="fw-semibold" id="modal-user-active"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded" style="background:#f8f9fa;">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-phone me-1"></i>ফোন ১</div>
                                <div class="fw-semibold" id="modal-user-phone"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-3 rounded" style="background:#f8f9fa;">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-phone-volume me-1"></i>ফোন ২</div>
                                <div class="fw-semibold" id="modal-user-phone2"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 rounded" style="background:#f8f9fa;">
                                <div class="text-muted small mb-1"><i class="fa-solid fa-envelope me-1"></i>ইমেইল</div>
                                <div class="fw-semibold" id="modal-user-email"></div>
                            </div>
                        </div>

                        {{-- Documents --}}
                        <div class="col-12">
                            <div class="p-3 rounded" style="background:#f8f9fa;">
                                <div class="text-muted small mb-2"><i class="fa-solid fa-id-card me-1"></i>ডকুমেন্টস</div>
                                <div class="d-flex gap-3 flex-wrap" id="modal-user-docs">
                                    <div>
                                        <div class="text-muted" style="font-size:11px;margin-bottom:4px;">ব্যবহারকারীর ছবি</div>
                                        <a id="modal-user-image-link" href="#" target="_blank">
                                            <img id="modal-user-image"
                                                src=""
                                                alt="user image"
                                                style="width:90px;height:90px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;cursor:pointer;transition:transform .2s;"
                                                onmouseover="this.style.transform='scale(1.07)'"
                                                onmouseout="this.style.transform='scale(1)'">
                                        </a>
                                    </div>
                                    <div id="modal-user-photo1-wrap">
                                        <div class="text-muted" style="font-size:11px;margin-bottom:4px;">ফটো ১ (ডকুমেন্ট)</div>
                                        <a id="modal-user-photo1-link" href="#" target="_blank">
                                            <img id="modal-user-photo1"
                                                src=""
                                                alt="photo 1"
                                                style="width:90px;height:90px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;cursor:pointer;transition:transform .2s;"
                                                onmouseover="this.style.transform='scale(1.07)'"
                                                onmouseout="this.style.transform='scale(1)'">
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- /Documents --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                </div>
            </div>
        </div>
    </div>
    {{-- /User Detail Modal --}}

    {{-- Product Detail Modal --}}
    <div class="modal fade" id="productDetailModal" tabindex="-1" aria-labelledby="productDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background:linear-gradient(135deg,#134e5e,#71b280);">
                    <h5 class="modal-title text-white d-flex align-items-center gap-2" id="productDetailModalLabel">
                        <i class="fa-solid fa-gem fa-lg"></i>
                        পণ্যের বিবরণ
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-4">
                        {{-- Product Image --}}
                        <div class="col-md-4 text-center">
                            <a id="prod-modal-photo-link" href="#" target="_blank">
                                <img id="prod-modal-photo"
                                     src=""
                                     alt="product"
                                     style="width:100%;max-width:200px;height:200px;object-fit:cover;border-radius:12px;border:3px solid #71b280;box-shadow:0 6px 20px rgba(113,178,128,.3);cursor:pointer;transition:transform .2s;"
                                     onmouseover="this.style.transform='scale(1.03)'"
                                     onmouseout="this.style.transform='scale(1)'">
                            </a>
                            <div class="mt-2">
                                <span class="badge bg-primary fs-6" id="prod-modal-category"></span>
                            </div>
                            <div class="mt-1">
                                <span class="badge bg-info fs-6" id="prod-modal-name"></span>
                            </div>
                        </div>

                        {{-- Product Details --}}
                        <div class="col-md-8">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="p-3 rounded" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                                        <div class="text-muted small mb-1"><i class="fa-solid fa-hashtag me-1"></i>ক্রয় আইডি</div>
                                        <div class="fw-bold fs-5" id="prod-modal-id"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                                        <div class="text-muted small mb-1"><i class="fa-solid fa-fire-flame-curved me-1"></i>ক্যারেট</div>
                                        <div class="fw-bold fs-5" id="prod-modal-karat"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                                        <div class="text-muted small mb-1"><i class="fa-solid fa-scale-balanced me-1"></i>ওজন</div>
                                        <div class="fw-semibold" id="prod-modal-weight"></div>
                                        <div class="text-danger fw-semibold small mt-1" id="prod-modal-gram"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded" style="background:#f0fdf4;border:1px solid #bbf7d0;">
                                        <div class="text-muted small mb-1"><i class="fa-solid fa-atom me-1"></i>বিশুদ্ধতার ওজন</div>
                                        <div class="fw-semibold" id="prod-modal-raw-gold"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded" style="background:#fff7ed;border:1px solid #fed7aa;">
                                        <div class="text-muted small mb-1"><i class="fa-solid fa-tag me-1"></i>বাজার মূল্য</div>
                                        <div class="fw-bold text-warning" id="prod-modal-actual-price"></div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded" style="background:#fef2f2;border:1px solid #fecaca;">
                                        <div class="text-muted small mb-1"><i class="fa-solid fa-receipt me-1"></i>ক্রয় মূল্য</div>
                                        <div class="fw-bold text-danger" id="prod-modal-total-price"></div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded" style="background:#f8f9fa;border:1px solid #dee2e6;">
                                        <div class="text-muted small mb-1"><i class="fa-solid fa-align-left me-1"></i>পণ্যের বিবরণ</div>
                                        <div class="fw-semibold" id="prod-modal-details" style="white-space:pre-wrap;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Assign Job Modal --}}
    <div class="modal fade" id="assignJobModal" tabindex="-1" aria-labelledby="assignJobModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header" style="background:linear-gradient(135deg,#0d6efd,#0a58ca);">
                    <h5 class="modal-title text-white d-flex align-items-center gap-2" id="assignJobModalLabel">
                        <i class="fa-solid fa-user-gear fa-lg"></i>
                        Assign Job / কারিগর কাজ অর্পণ করুন
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('karigor-job.assign') }}" method="POST" id="assignJobForm">
                    @csrf
                    <input type="hidden" name="purchase_id" id="assign_purchase_id">

                    <div class="modal-body p-4">

                        {{-- Stock Item Summary Card --}}
                        <div class="p-3 mb-4 rounded-3" style="background:#eef2ff;border:1px solid #c7d2fe;">
                            <div class="fw-bold text-primary mb-1" style="font-size:14px;">
                                <i class="fa-solid fa-box-archive me-1"></i> নির্বাচিত স্টক আইটেম বিবরণ:
                            </div>
                            <div class="row g-3 text-dark mt-1">
                                <div class="col-sm-4">
                                    <span class="text-muted d-block small">ক্রয় আইডি:</span>
                                    <strong id="assign-item-id" class="fs-6">#—</strong>
                                </div>
                                <div class="col-sm-4">
                                    <span class="text-muted d-block small">ক্যাটাগরি - পণ্য:</span>
                                    <strong id="assign-item-cat-prod" class="fs-6">—</strong>
                                </div>
                                <div class="col-sm-4">
                                    <span class="text-muted d-block small">ক্যারেট ও ওজন:</span>
                                    <strong id="assign-item-weight" class="fs-6">—</strong>
                                </div>
                                <div class="col-12 mt-3 pt-2 border-top border-primary border-opacity-25">
                                    <div class="row g-2">
                                        <div class="col-sm-6">
                                            <span class="text-muted d-block small"><i class="fa-solid fa-weight-hanging text-warning me-1"></i>গ্রস ওজন (গ্রাম):</span>
                                            <strong id="assign-item-gram" class="text-primary fs-6">0 গ্রাম</strong>
                                        </div>
                                        <div class="col-sm-6">
                                            <span class="text-muted d-block small"><i class="fa-solid fa-atom text-success me-1"></i>বিশুদ্ধতার ওজন:</span>
                                            <strong id="assign-item-raw-gold" class="text-success fs-6">— গ্রাম</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 1. Searchable Karigor List --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold d-flex justify-content-between align-items-center">
                                <span><i class="fa-solid fa-user me-1 text-primary"></i> ১. কারিগর নির্বাচন করুন <span class="text-danger">*</span></span>
                                <small class="text-muted fw-normal">ফোন বা নাম দিয়ে ফিল্টার করুন</small>
                            </label>

                            <input type="hidden" name="karigor_id" id="selected_karigor_id" required>

                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" id="karigor_search_input" class="form-control" placeholder="কারিগরের নাম বা ফোন নম্বর দিয়ে খুঁজুন (e.g. 018...)...">
                            </div>

                            <div id="karigor_list_container" class="border rounded-3 p-2 bg-white" style="max-height: 220px; overflow-y: auto;">
                                @if(isset($users) && count($users) > 0)
                                    @foreach($users as $kuser)
                                        @php
                                            $userImg = ($kuser->image && $kuser->image !== 'default_user.jpg') ? asset('user/' . $kuser->image) : asset('cover/default_user.jpg');
                                        @endphp
                                        <div class="karigor-item-card p-2 rounded-2 mb-1 d-flex align-items-center justify-content-between border-bottom"
                                             data-id="{{ $kuser->id }}"
                                             data-name="{{ strtolower($kuser->name . ' ' . ($kuser->last_name ?? '')) }}"
                                             data-phone="{{ $kuser->phone ?? '' }}"
                                             style="cursor: pointer; transition: all 0.2s ease;">
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="{{ $userImg }}"
                                                     class="rounded-circle shadow-sm"
                                                     style="width:36px; height:36px; object-fit:cover; border:1.5px solid #dee2e6;" alt="avatar">
                                                <div>
                                                    <div class="fw-semibold text-dark fs-6" style="line-height:1.2;">
                                                        {{ $kuser->name }} {{ $kuser->last_name ?? '' }} - {{ $kuser->phone ?? 'ফোন নেই' }}
                                                        <span class="text-muted small">({{ $kuser->role->role_name ?? 'Karigor' }})</span>
                                                    </div>
                                                    @if($kuser->address)
                                                        <div class="text-muted small mt-1"><i class="fa-solid fa-location-dot fa-xs me-1"></i>{{ $kuser->address }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                            <i class="fa-solid fa-circle-check text-primary select-icon fs-5 ms-2" style="display:none;"></i>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center text-muted py-3">কোন কারিগর পাওয়া যায়নি</div>
                                @endif
                            </div>
                            <div class="form-text text-muted" id="karigor_select_hint">তালিকা থেকে কারিগর ক্লিক করে নির্বাচন করুন।</div>
                        </div>

                        {{-- 2. Task Type Selection --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fa-solid fa-list-check me-1 text-primary"></i> ২. কাজের ধরন নির্বাচন করুন (Task Type) <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <div class="form-check card p-3 m-0 shadow-sm border cursor-pointer h-100" style="border-radius:10px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <input class="form-check-input mt-0 fs-5" type="radio" name="task_type" id="task_type_repair" value="Repair" checked>
                                            <label class="form-check-label fw-bold cursor-pointer mb-0 text-dark" for="task_type_repair">
                                                <i class="fa-solid fa-wrench text-warning me-1"></i> Repair (মেরামত)
                                            </label>
                                        </div>
                                        <small class="text-muted mt-2 d-block ps-4">পণ্য মেরামত বা সাইজ ঠিক করার জন্য কারিগরকে অর্পণ।</small>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-check card p-3 m-0 shadow-sm border cursor-pointer h-100" style="border-radius:10px;">
                                        <div class="d-flex align-items-center gap-2">
                                            <input class="form-check-input mt-0 fs-5" type="radio" name="task_type" id="task_type_raw_gold" value="Raw Gold(Paka kora)">
                                            <label class="form-check-label fw-bold cursor-pointer mb-0 text-dark" for="task_type_raw_gold">
                                                <i class="fa-solid fa-coins text-warning me-1"></i> Raw Gold (Paka kora)
                                            </label>
                                        </div>
                                        <small class="text-muted mt-2 d-block ps-4">কাঁচা সোনা গলানো বা পাকা করার কাজ সম্পন্ন করার জন্য।</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. Extra Raw Gold Input --}}
                        <div class="mb-3">
                            <label for="extra_raw_gold" class="form-label fw-bold">
                                <i class="fa-solid fa-weight-hanging me-1 text-primary"></i> ৩. Extra Raw Gold (গ্রাম হিসাব) <span class="text-muted fw-normal">(ঐচ্ছিক / Optional)</span>
                            </label>
                            <div class="input-group">
                                <input type="number" step="0.001" min="0" name="extra_raw_gold" id="extra_raw_gold" class="form-control" placeholder="অতিরিক্ত র গোল্ডের পরিমাণ লিখুন (গ্রামে)...">
                                <span class="input-group-text bg-light">গ্রাম (GM)</span>
                            </div>
                            <div class="form-text text-muted">প্রয়োজন না থাকলে খালি রাখুন।</div>
                        </div>

                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa-solid fa-paper-plane me-1"></i> Assign Job
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- /Assign Job Modal --}}
@endsection

@push('admin_script')
    @include('admin.common.script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.2.2/axios.min.js"
        integrity="sha512-QTnb9BQkG4fBYIt9JGvYmxPpd6TBeKp6lsUrtiVQsrJ9sb33Bn9s0wMQO9qVBFbPX3xHRAsBHvXlcsrnJjExjg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Assign Job Modal Data Binding & Search Filter
            var assignModal = document.getElementById('assignJobModal');
            if (assignModal) {
                assignModal.addEventListener('show.bs.modal', function (event) {
                    var btn = event.relatedTarget;
                    if (!btn) return;
                    var d = btn.dataset;
                    document.getElementById('assign_purchase_id').value = d.purchaseId || '';
                    document.getElementById('assign-item-id').textContent = '#' + (d.purchaseId || '—');
                    document.getElementById('assign-item-cat-prod').textContent = (d.category || '—') + ' - ' + (d.product || '—');
                    document.getElementById('assign-item-weight').textContent = (d.karat || '—') + ' (' + (d.bhori || 0) + 'ভরি, ' + (d.ana || 0) + 'আনা, ' + (d.roti || 0) + 'রতি, ' + (d.point || 0) + 'পয়েন্ট)';
                    document.getElementById('assign-item-gram').textContent = (d.gram || '0') + ' গ্রাম';
                    document.getElementById('assign-item-raw-gold').textContent = (d.rawGold || '—') + ' গ্রাম';
                });
            }

            document.querySelectorAll('.assign-job-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var d = this.dataset;
                    document.getElementById('assign_purchase_id').value = d.purchaseId || '';
                    document.getElementById('assign-item-id').textContent = '#' + (d.purchaseId || '—');
                    document.getElementById('assign-item-cat-prod').textContent = (d.category || '—') + ' - ' + (d.product || '—');
                    document.getElementById('assign-item-weight').textContent = (d.karat || '—') + ' (' + (d.bhori || 0) + 'ভরি, ' + (d.ana || 0) + 'আনা, ' + (d.roti || 0) + 'রতি, ' + (d.point || 0) + 'পয়েন্ট)';
                    document.getElementById('assign-item-gram').textContent = (d.gram || '0') + ' গ্রাম';
                    document.getElementById('assign-item-raw-gold').textContent = (d.rawGold || '—') + ' গ্রাম';
                });
            });

            // Karigor List Selection & Search Filtering
            var karigorCards = document.querySelectorAll('.karigor-item-card');
            var hiddenKarigorInput = document.getElementById('selected_karigor_id');

            karigorCards.forEach(function(card) {
                card.addEventListener('click', function() {
                    karigorCards.forEach(function(c) {
                        c.style.background = '';
                        c.style.borderLeft = '';
                        var checkIcon = c.querySelector('.select-icon');
                        if (checkIcon) checkIcon.style.display = 'none';
                    });

                    this.style.background = '#eef2ff';
                    this.style.borderLeft = '4px solid #0d6efd';
                    var myCheck = this.querySelector('.select-icon');
                    if (myCheck) myCheck.style.display = 'inline-block';
                    if (hiddenKarigorInput) hiddenKarigorInput.value = this.dataset.id;
                });
            });

            document.getElementById('karigor_search_input')?.addEventListener('input', function() {
                var filter = this.value.toLowerCase().trim();
                karigorCards.forEach(function(card) {
                    var name = card.getAttribute('data-name') || '';
                    var phone = card.getAttribute('data-phone') || '';
                    if (name.indexOf(filter) !== -1 || phone.indexOf(filter) !== -1) {
                        card.style.setProperty('display', 'flex', 'important');
                    } else {
                        card.style.setProperty('display', 'none', 'important');
                    }
                });
            });

            document.querySelectorAll('.user-detail-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.getElementById('modal-user-id').textContent = this.dataset.userId || '—';
                    document.getElementById('modal-user-name').textContent = this.dataset.userName || '—';
                    document.getElementById('modal-user-phone').textContent = this.dataset.userPhone || '—';
                    document.getElementById('modal-user-phone2').textContent = this.dataset.userPhone2 || '—';
                    document.getElementById('modal-user-email').textContent = this.dataset.userEmail || '—';
                    document.getElementById('modal-user-role').textContent = this.dataset.userRole || '—';

                    var activeEl = document.getElementById('modal-user-active');
                    var isActive = this.dataset.userActive === 'সক্রিয়';
                    activeEl.textContent = this.dataset.userActive;
                    activeEl.style.color = isActive ? '#198754' : '#dc3545';

                    var imgUrl = this.dataset.userImage || '';
                    document.getElementById('modal-user-avatar').src = imgUrl;

                    document.getElementById('modal-user-image').src = imgUrl;
                    document.getElementById('modal-user-image-link').href = imgUrl || '#';

                    var photo1Url = this.dataset.userPhoto1 || '';
                    var photo1Wrap = document.getElementById('modal-user-photo1-wrap');
                    if (photo1Url) {
                        document.getElementById('modal-user-photo1').src = photo1Url;
                        document.getElementById('modal-user-photo1-link').href = photo1Url;
                        photo1Wrap.style.display = '';
                    } else {
                        photo1Wrap.style.display = 'none';
                    }
                });
            });

            document.querySelectorAll('.product-detail-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var d = this.dataset;
                    document.getElementById('prod-modal-id').textContent          = d.prodId          || '—';
                    document.getElementById('prod-modal-category').textContent     = d.prodCategory     || '—';
                    document.getElementById('prod-modal-name').textContent         = d.prodName         || '—';
                    document.getElementById('prod-modal-karat').textContent        = d.prodKarat        || '—';
                    document.getElementById('prod-modal-weight').textContent       = (d.prodBhori || 0) + ' ভরি, ' + (d.prodAna || 0) + ' আনা, ' + (d.prodRoti || 0) + ' রতি, ' + (d.prodPoint || 0) + ' পয়েন্ট';
                    document.getElementById('prod-modal-gram').textContent         = '(' + (d.prodGram || 0) + ' গ্রাম)';
                    document.getElementById('prod-modal-raw-gold').textContent     = (d.prodRawGold || '—') + ' গ্রাম';
                    document.getElementById('prod-modal-actual-price').textContent = '৳ ' + (d.prodActualPrice || '0.00');
                    document.getElementById('prod-modal-total-price').textContent  = '৳ ' + (d.prodTotalPrice  || '0.00');
                    document.getElementById('prod-modal-details').textContent      = d.prodDetails      || '—';

                    var photoUrl = d.prodPhoto || '';
                    document.getElementById('prod-modal-photo').src         = photoUrl;
                    document.getElementById('prod-modal-photo-link').href   = photoUrl || '#';
                });
            });

            // Live calculation for Job Completion Modal (Conversion % & Wastage)
            function updateJobCalc(jobId) {
                var rawInput = document.getElementById('returned_raw_gold_' + jobId);
                if (!rawInput) return;
                var givenPurity = parseFloat(rawInput.dataset.givenPurity || 0);
                var returnedVal = parseFloat(rawInput.value || 0);
                var usedExtraVal = parseFloat(document.getElementById('used_extra_raw_gold_' + jobId)?.value || 0);

                var pctEl = document.getElementById('calc_conversion_pct_' + jobId);
                var wastageEl = document.getElementById('calc_wastage_loss_' + jobId);

                if (givenPurity > 0 && returnedVal > 0) {
                    var pct = (returnedVal / givenPurity) * 100;
                    var wastage = (givenPurity + usedExtraVal) - returnedVal;

                    if (pctEl) pctEl.textContent = pct.toFixed(2) + ' %';
                    if (wastageEl) wastageEl.textContent = (wastage >= 0 ? wastage.toFixed(3) : 0) + ' গ্রাম';
                } else {
                    if (pctEl) pctEl.textContent = '— %';
                    if (wastageEl) wastageEl.textContent = '— গ্রাম';
                }
            }

            document.querySelectorAll('.returned-raw-gold-input, .used-extra-gold-input').forEach(function(inp) {
                inp.addEventListener('input', function() {
                    updateJobCalc(this.dataset.jobId);
                });
            });
        });
    </script>
@endpush
