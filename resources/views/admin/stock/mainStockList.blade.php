@extends('admin.master')
@section('title')
টোটাল স্টক তালিকা
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush

@section('body')
@php
    if (!isset($purchases)) {
        $purchases = \App\Models\Purchase::with(['invoice', 'productCategory', 'product', 'user'])->latest()->get();
    }
@endphp

<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-0"><i class="fa-solid fa-boxes-stacked me-2 text-primary"></i>টোটাল স্টক (পণ্য ও ওজন)</h3>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary fs-6">মোট পণ্য: {{ $purchases->count() }}</span>
                    <a href="{{route('stock.create')}}" class="btn btn-dark btn-sm">নতুন স্টক তৈরি করুন</a>
                </div>
            </div>
        </div>
        <div class="card-body">

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
                    '22K'       => ['bg'=>'linear-gradient(135deg,#f6d365,#fda085)', 'badge'=>'#e67e22'],
                    '21K'       => ['bg'=>'linear-gradient(135deg,#a18cd1,#fbc2eb)', 'badge'=>'#8e44ad'],
                    '18K'       => ['bg'=>'linear-gradient(135deg,#84fab0,#8fd3f4)', 'badge'=>'#16a085'],
                    'পায়েনে'   => ['bg'=>'linear-gradient(135deg,#f093fb,#f5576c)', 'badge'=>'#c0392b'],
                    'অন্যান্য' => ['bg'=>'linear-gradient(135deg,#c3cfe2,#c3cfe2)', 'badge'=>'#7f8c8d'],
                ];
            @endphp

            @if(count($summaryRows) > 0)
            <div class="mb-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <i class="fa-solid fa-chart-pie text-primary fs-5"></i>
                    <span class="fw-bold text-dark" style="font-size:15px;">ক্যারেট অনুযায়ী স্টক সারসংক্ষেপ</span>
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
                                    <span class="fw-bold text-info">{{ $totalPurity }} গ্রাম</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="mt-0 mb-3">
            @endif

            {{-- Main Stock Table --}}
            <table id="config-table" class="table display table-striped border no-wrap">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" style="white-space:normal;">তারিখ ও<br>ইনভয়েস আইডি</th>
                        <th class="text-center" style="white-space:normal;">ক্যাটাগরি<br>পণ্যের নাম</th>
                        <th class="text-center">ক্রয় আইডি</th>
                        <th class="text-center">ক্যারেট</th>
                        <th class="text-center">ওজন</th>
                        <th class="text-center">বিশুদ্ধতার ওজন</th>
                        <th class="text-center">ক্রয় মূল্য</th>
                        <th class="text-center">লোকেশন</th>
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

                        {{-- তারিখ ও ইনভয়েস আইডি (clickable → user detail modal) --}}
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

                        {{-- ক্যাটাগরি ও পণ্যের নাম --}}
                        <td class="text-center">
                            <button type="button"
                                class="btn btn-link p-0 text-decoration-none text-dark product-detail-btn"
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

                        {{-- ক্রয় আইডি --}}
                        <td class="text-center">
                            <strong>{{ $purchase->id }}</strong>
                        </td>

                        {{-- ক্যারেট --}}
                        <td class="text-center">
                            <span class="badge" style="background:#6f42c1;">{{ $purchase->karat ?? '—' }}</span>
                        </td>

                        {{-- ওজন --}}
                        <td class="text-center">
                            <span class="badge bg-info">{{ $purchase->bhori ?? 0 }} ভরি, {{ $purchase->ana ?? 0 }} আনা, {{ $purchase->roti ?? 0 }} রতি, {{ $purchase->point ?? 0 }} পয়েন্ট</span><br>
                            <span class="badge bg-danger mt-1">({{ $purchase->gram ?? 0 }} গ্রাম)</span>
                        </td>

                        {{-- বিশুদ্ধতার ওজন (raw_gold) --}}
                        <td class="text-center">
                            {{ $purchase->raw_gold ?? '—' }} গ্রাম
                        </td>

                        {{-- ক্রয় মূল্য (total_price) --}}
                        <td class="text-center">
                            ৳ {{ number_format($purchase->total_price ?? 0, 2) }}
                        </td>

                        {{-- লোকেশন --}}
                        <td class="text-center">
                            @if ($purchase->location == 'is_shop')
                                <span class="badge bg-success"><a href="{{route('shop.stock.list')}}" class="text-light text-decoration-none">দোকান (Shop)</a></span>
                            @elseif($purchase->location == 'is_warehouse')
                                <span class="badge bg-danger"><a href="{{route('warehouse.stock.list')}}" class="text-light text-decoration-none">গুদাম (Warehouse)</a></span>
                            @elseif($purchase->location == 'is_hold')
                                <span class="badge bg-warning text-dark"><a href="{{route('hold.stock')}}" class="text-dark text-decoration-none">Unsorted (Hold)</a></span>
                            @elseif($purchase->location == 'is_karigor')
                                <span class="badge bg-info text-dark"><a href="{{route('karigor.stock')}}" class="text-dark text-decoration-none">কারিগর (Karigor)</a></span>
                            @else
                                <span class="badge bg-secondary">{{ $purchase->location }}</span>
                            @endif
                        </td>

                        {{-- একশন্স --}}
                        <td class="text-center">
                            <div class="action-btns d-flex align-items-center justify-content-center gap-1 flex-wrap">
                                <div>
                                    <a href="{{ $purchase->transaction_id ? route('purchase.show', $purchase->transaction_id) : '#' }}"
                                        class="text-success me-1" data-toggle="tooltip"
                                        data-placement="top" data-bs-original-title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ $purchase->transaction_id ? route('purchase.edit', $purchase->transaction_id) : '#' }}"
                                        class="text-info me-1" data-toggle="tooltip"
                                        data-placement="top" data-bs-original-title="Edit">
                                        <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    </a>
                                </div>

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

                                        @if($purchase->location !== 'is_shop')
                                        <li>
                                            <form action="{{ route('stock.send-to-shop') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-primary">
                                                    <i class="fa-solid fa-store fa-fw"></i> শপে পাঠান
                                                </button>
                                            </form>
                                        </li>
                                        @endif

                                        @if($purchase->location !== 'is_warehouse')
                                        <li>
                                            <form action="{{ route('stock.send-to-warehouse') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-success">
                                                    <i class="fa-solid fa-warehouse fa-fw"></i> গুদামে পাঠান
                                                </button>
                                            </form>
                                        </li>
                                        @endif

                                        @if($purchase->location !== 'is_karigor')
                                        <li>
                                            <form action="{{ route('stock.send-to-karigor') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-info">
                                                    <i class="fa-solid fa-user-gear fa-fw"></i> কারিগরে পাঠান
                                                </button>
                                            </form>
                                        </li>
                                        @endif

                                        @if($purchase->location !== 'is_hold')
                                        <li>
                                            <form action="{{ route('stock.send-to-hold') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-warning">
                                                    <i class="fa-solid fa-rotate-left fa-fw"></i> হোল্ডে ফেরত পাঠান
                                                </button>
                                            </form>
                                        </li>
                                        @endif

                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @php $sl++ @endphp
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="fa-solid fa-box-open fa-2x text-muted mb-2 d-block"></i>
                            কোনো তথ্য পাওয়া যায়নি!
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>

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
                            <span class="badge bg-info fs-6 mt-1" id="prod-modal-name"></span>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="p-3 rounded" style="background:#f8f9fa;">
                                    <div class="text-muted small mb-1"><i class="fa-solid fa-hashtag me-1"></i>ক্রয় আইডি</div>
                                    <div class="fw-bold" id="prod-modal-id"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded" style="background:#f8f9fa;">
                                    <div class="text-muted small mb-1"><i class="fa-solid fa-gem me-1"></i>ক্যারেট</div>
                                    <div class="fw-bold text-purple" id="prod-modal-karat"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 rounded" style="background:#f8f9fa;">
                                    <div class="text-muted small mb-1"><i class="fa-solid fa-scale-balanced me-1"></i>ওজন</div>
                                    <div class="fw-bold" id="prod-modal-weight"></div>
                                    <div class="text-danger small mt-1" id="prod-modal-gram"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded" style="background:#f8f9fa;">
                                    <div class="text-muted small mb-1"><i class="fa-solid fa-flask me-1"></i>বিশুদ্ধতার ওজন</div>
                                    <div class="fw-bold text-success" id="prod-modal-raw-gold"></div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 rounded" style="background:#f8f9fa;">
                                    <div class="text-muted small mb-1"><i class="fa-solid fa-bangladeshi-taka-sign me-1"></i>ক্রয় মূল্য</div>
                                    <div class="fw-bold text-primary" id="prod-modal-total-price"></div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="p-3 rounded" style="background:#f8f9fa;">
                                    <div class="text-muted small mb-1"><i class="fa-solid fa-align-left me-1"></i>বিবরণ</div>
                                    <div class="fw-semibold" id="prod-modal-details"></div>
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
@endsection

@push('admin_script')
@include('admin.common.script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
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
                document.getElementById('prod-modal-total-price').textContent  = '৳ ' + (d.prodTotalPrice  || '0.00');
                document.getElementById('prod-modal-details').textContent      = d.prodDetails      || '—';

                var photoUrl = d.prodPhoto || '';
                document.getElementById('prod-modal-photo').src         = photoUrl;
                document.getElementById('prod-modal-photo-link').href   = photoUrl || '#';
            });
        });
    });
</script>
@endpush
