@extends('admin.master')

@section('title')
কাস্টম জুয়েলারি অর্ডার তালিকা
@endsection

@push('admin_style')
<style>
    .order-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        vertical-align: middle;
    }
    .order-table td {
        vertical-align: middle;
    }
</style>
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-gem text-primary me-2 fa-lg"></i>
                    <h4 class="card-title mb-0 font-weight-bold">কাস্টম জুয়েলারি অর্ডার ব্যবস্থাপনা</h4>
                </div>
                <a href="{{ route('custom-orders.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                    <i class="fa-solid fa-plus me-1"></i> নতুন কাস্টম অর্ডার
                </a>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Status Filter Tabs -->
                <ul class="nav nav-pills mb-3">
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'all' ? 'active' : '' }}" href="{{ route('custom-orders.index', ['status' => 'all']) }}">
                            সকল অর্ডার ({{ $counts['all'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'in_production' ? 'active bg-warning text-dark' : '' }}" href="{{ route('custom-orders.index', ['status' => 'in_production']) }}">
                            <i class="fa-solid fa-fire-burner me-1"></i> কারিগরের কাজ চলছে ({{ $counts['in_production'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'ready_for_delivery' ? 'active bg-info' : '' }}" href="{{ route('custom-orders.index', ['status' => 'ready_for_delivery']) }}">
                            <i class="fa-solid fa-box-check me-1"></i> ডেলিভারির জন্য প্রস্তুত ({{ $counts['ready_for_delivery'] }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ $status === 'delivered' ? 'active bg-success' : '' }}" href="{{ route('custom-orders.index', ['status' => 'delivered']) }}">
                            <i class="fa-solid fa-circle-check me-1"></i> ডেলিভারি সম্পন্ন ({{ $counts['delivered'] }})
                        </a>
                    </li>
                </ul>

                <!-- Search Bar -->
                <form method="GET" action="{{ route('custom-orders.index') }}" class="row g-2 mb-3">
                    <input type="hidden" name="status" value="{{ $status }}">
                    <div class="col-md-6">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="অর্ডার নং, পণ্যের নাম, গ্রাহকের নাম বা ফোন..." value="{{ request('search') }}">
                            <button type="submit" class="btn btn-secondary">অনুসন্ধান</button>
                        </div>
                    </div>
                </form>

                @if($orders->count())
                <div class="table-responsive">
                    <table class="table table-hover table-bordered order-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>অর্ডার নং ও তারিখ</th>
                                <th>গ্রাহক</th>
                                <th>পণ্যের বিবরণ</th>
                                <th class="text-center">ওজন ও সোনা</th>
                                <th>নিযুক্ত কারিগর</th>
                                <th class="text-end">পরিশোধ ও বকেয়া</th>
                                <th class="text-center">স্ট্যাটাস</th>
                                <th class="text-center" style="width: 170px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $index => $order)
                            <tr>
                                <td class="text-center text-muted">{{ $orders->firstItem() + $index }}</td>
                                <td>
                                    <strong class="text-primary">{{ $order->order_no }}</strong>
                                    <small class="text-muted d-block">{{ $order->created_at->format('d M, Y') }}</small>
                                    @if($order->delivery_date && $order->status !== 'delivered')
                                        <small class="text-info d-block"><i class="fa-regular fa-clock me-1"></i>ডেলিভারি: {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $order->customer->name ?? 'N/A' }} {{ $order->customer->last_name ?? '' }}</strong>
                                    <small class="text-muted d-block"><i class="fa-solid fa-phone me-1"></i>{{ $order->customer->phone ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    <strong>{{ $order->product_name }}</strong>
                                    @if($order->items && $order->items->count() > 1)
                                        <span class="badge bg-primary-subtle text-primary border border-primary ms-1">{{ $order->items->count() }} টি পণ্য</span>
                                    @else
                                        <span class="badge bg-light text-dark border ms-1">{{ $order->karat }}</span>
                                    @endif
                                    <small class="text-muted d-block">{{ $order->category->category_name ?? 'কাস্টম' }} (মোট {{ $order->quantity }}টি পিস)</small>
                                </td>
                                <td class="text-center small">
                                    <div>কাঙ্ক্ষিত: <strong>{{ number_format($order->target_gram, 3) }} গ্রাম</strong></div>
                                    <div class="text-danger">কাঁচামাল: {{ number_format($order->raw_gold_needed, 3) }} গ্রাম</div>
                                    @if($order->actual_weight_gram)
                                        <div class="text-success font-weight-bold">প্রস্তুত: {{ number_format($order->actual_weight_gram, 3) }} গ্রাম</div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $order->karigor->name ?? 'N/A' }}</strong>
                                    <small class="text-muted d-block">{{ $order->karigor->phone ?? '' }}</small>
                                </td>
                                <td class="text-end small">
                                    <div>মোট: <strong>৳ {{ number_format($order->grand_total ?: $order->estimated_price, 2) }}</strong></div>
                                    <div class="text-success">পেইড: ৳ {{ number_format($order->paid_amount, 2) }}</div>
                                    @if($order->due_amount > 0)
                                        <div class="text-danger font-weight-bold">বকেয়া: ৳ {{ number_format($order->due_amount, 2) }}</div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($order->status === 'in_production')
                                        <span class="badge bg-warning-subtle text-warning border border-warning px-2 py-1">তৈরি হচ্ছে</span>
                                    @elseif($order->status === 'ready_for_delivery')
                                        <span class="badge bg-info-subtle text-info border border-info px-2 py-1">প্রস্তুত</span>
                                    @elseif($order->status === 'delivered')
                                        <span class="badge bg-success-subtle text-success border border-success px-2 py-1">ডেলিভারড</span>
                                    @else
                                        <span class="badge bg-secondary px-2 py-1">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        @if($order->status === 'in_production')
                                            <button type="button" class="btn btn-sm btn-warning text-dark py-1 px-2" title="কারিগর থেকে গ্রহণ করুন"
                                                onclick="openReceiveModal({{ $order->id }}, '{{ $order->order_no }}', '{{ addslashes($order->product_name) }}', {{ $order->target_gram }}, {{ $order->raw_gold_needed }}, '{{ $order->karat }}', '{{ addslashes($order->category->category_name ?? '') }}')">
                                                <i class="fa-solid fa-arrow-down-to-bracket me-1"></i> গ্রহণ
                                            </button>
                                        @elseif($order->status === 'ready_for_delivery')
                                            <button type="button" class="btn btn-sm btn-success py-1 px-2" title="ডেলিভারি ও চূড়ান্ত বিল"
                                                onclick="openDeliverModal({{ $order->id }}, '{{ $order->order_no }}', '{{ $order->product_name }}', {{ $order->actual_weight_gram ?? $order->target_gram }}, {{ $order->karigor_fee }}, {{ $order->estimated_price }}, {{ $order->paid_amount }}, {{ $order->due_amount }})">
                                                <i class="fa-solid fa-truck-ramp-box me-1"></i> ডেলিভারি
                                            </button>
                                        @endif

                                        <a href="{{ route('custom-orders.invoice', $order->id) }}" class="btn btn-sm btn-outline-success py-1 px-2" title="গ্রাহক ইনভয়েস (Customer Invoice)">
                                            <i class="fa-solid fa-file-invoice-dollar"></i>
                                        </a>

                                        <a href="{{ route('custom-orders.karigor-invoice', $order->id) }}" class="btn btn-sm btn-outline-warning text-dark py-1 px-2" title="কারিগর জব কার্ড (Karigor Job Card)">
                                            <i class="fa-solid fa-hammer"></i>
                                        </a>

                                        <a href="{{ route('custom-orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary py-1 px-2" title="অর্ডার বিবরণ">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <button type="button" class="btn btn-sm btn-outline-info py-1 px-2" title="কিস্তি ও পেমেন্ট ইতিহাস" onclick="openPaymentHistoryModal('custom_order', {{ $order->id }})">
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
                    {{ $orders->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fa-solid fa-gem fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">কোনো কাস্টম অর্ডার পাওয়া যায়নি।</h5>
                    <a href="{{ route('custom-orders.create') }}" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fa-solid fa-plus me-1"></i> নতুন অর্ডার গ্রহণ করুন
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal 1: Receive from Karigor -->
<div class="modal fade" id="receiveKarigorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark py-2">
                <h5 class="modal-title fs-6">
                    <i class="fa-solid fa-arrow-down-to-bracket me-1"></i>কারিগর থেকে পণ্য গ্রহণ (<span id="rcv_modal_order_no"></span>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="receiveKarigorForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <p class="mb-2 text-muted small">পণ্য: <strong class="text-dark" id="rcv_modal_prod_name"></strong></p>
                    <div class="alert alert-info py-2 small mb-3">
                        কাঙ্ক্ষিত ওজন ছিল: <strong id="rcv_modal_target_gram"></strong> গ্রাম | বরাদ্দ পাকা সোনা: <strong id="rcv_modal_raw_gold"></strong> গ্রাম
                    </div>

                    <div class="mb-3">
                        <label class="form-label font-weight-bold">প্রকৃত তৈরি ওজন (Actual Finished Weight) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.001" min="0.001" name="actual_weight_gram" id="rcv_actual_weight" class="form-control font-weight-bold" required>
                            <span class="input-group-text">গ্রাম</span>
                        </div>
                    </div>

                    <!-- Karigor Mojuri Auto-calculation Section -->
                    <div class="p-3 bg-light rounded border mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label font-weight-bold mb-0 text-dark">
                                <i class="fa-solid fa-coins text-warning me-1"></i>কারিগর মজুরি নির্ধারণ (Karigor Mojuri) <span class="text-danger">*</span>
                            </label>
                            <span class="badge bg-white text-primary border" id="rcv_mojuri_calc_badge">৳ 0.00</span>
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-md-7">
                                <label class="form-label small text-muted mb-1">মজুরি রেট তালিকা:</label>
                                <select id="rcv_mojuri_select" class="form-select form-select-sm">
                                    <option value="" data-per-gram="0" data-per-vori="0">-- মজুরি রেট নির্বাচন করুন --</option>
                                    @php
                                        $karigorMojurisList = $karigorMojuris ?? \App\Models\KarigorMojuri::orderBy('category_name', 'asc')->get();
                                    @endphp
                                    @foreach($karigorMojurisList as $km)
                                        <option value="{{ $km->id }}" 
                                                data-category="{{ strtolower($km->category_name) }}"
                                                data-type="{{ strtolower($km->type) }}"
                                                data-per-gram="{{ $km->per_gram_tk }}" 
                                                data-per-vori="{{ $km->per_vori_tk }}">
                                            {{ $km->category_name }} - {{ $km->type }} (৳{{ number_format($km->per_gram_tk, 2) }}/গ্রাম | ৳{{ number_format($km->per_vori_tk, 0) }}/ভরি)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small text-muted mb-1">প্রতি গ্রাম মজুরি (৳):</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" step="0.01" min="0" id="rcv_mojuri_rate" class="form-control text-end font-weight-bold" placeholder="0.00" value="0">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label small font-weight-bold mb-1">মোট কারিগর মজুরি (Total Karigor Fee) <span class="text-danger">*</span>:</label>
                            <div class="input-group">
                                <span class="input-group-text font-weight-bold text-success">৳</span>
                                <input type="number" step="1" min="0" name="karigor_fee" id="rcv_karigor_fee" class="form-control font-weight-bold text-success fs-6" placeholder="0" required>
                            </div>
                            <small class="text-muted d-block mt-1" style="font-size: 10px;" id="rcv_mojuri_formula_text">
                                হিসাব: প্রস্তুতকৃত নিট ওজন (গ্রাম) × প্রতি গ্রাম মজুরি রেট (পূর্ণ সংখ্যায় রাউন্ডিং)
                            </small>
                        </div>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small font-weight-bold">ফেরত দেওয়া সোনা (Returned Raw Gold)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.001" min="0" name="returned_raw_gold" class="form-control" value="0">
                                <span class="input-group-text">গ্রাম</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small font-weight-bold">ওয়েস্টেজ / ঘাটতি (Wastage Gold)</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.001" min="0" name="wastage_gold" class="form-control" value="0">
                                <span class="input-group-text">গ্রাম</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small font-weight-bold">নোট</label>
                        <input type="text" name="notes" class="form-control form-control-sm" placeholder="মন্তব্য (ঐচ্ছিক)">
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-sm btn-primary font-weight-bold">
                        <i class="fa-solid fa-check me-1"></i> গ্রহণ সম্পন্ন করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal 2: Deliver & Final Bill to Customer -->
<div class="modal fade" id="deliverOrderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white py-2">
                <h5 class="modal-title fs-6">
                    <i class="fa-solid fa-truck-ramp-box me-1"></i>চূড়ান্ত বিলিং ও গ্রাহককে ডেলিভারি (<span id="dlv_modal_order_no"></span>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deliverOrderForm" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">পণ্য: <strong class="text-dark" id="dlv_modal_prod_name"></strong></small>
                            <small class="text-muted d-block">চূড়ান্ত তৈরি ওজন: <strong class="text-primary" id="dlv_modal_actual_weight"></strong> গ্রাম</small>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted d-block">পূর্বের অগ্রিম পরিশোধ: <strong class="text-success">৳ <span id="dlv_modal_prev_paid"></span></strong></small>
                        </div>
                    </div>

                    <div class="card p-3 bg-light border mb-3">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">স্বর্ণের বিক্রয় মূল্য <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" step="0.01" min="0" name="actual_price" id="dlv_actual_price" class="form-control font-weight-bold dlv-calc" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">কারিগর মজুরি <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" step="0.01" min="0" name="karigor_fee" id="dlv_karigor_fee" class="form-control font-weight-bold dlv-calc" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold">ছাড় / ডিসকাউন্ট</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" step="0.01" min="0" name="discount" id="dlv_discount" class="form-control font-weight-bold dlv-calc" value="0">
                                </div>
                            </div>
                        </div>

                        <hr class="my-2">

                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fs-6 font-weight-bold text-dark">সর্বমোট বিল (Grand Total):</span>
                            <span class="fs-5 font-weight-bold text-primary">৳ <span id="dlv_grand_total_text">0.00</span></span>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div class="row g-3 mb-2">
                        <div class="col-md-6">
                            <label class="form-label font-weight-bold">এখন পরিশোধের পরিমাণ (Delivery Payment)</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" min="0" name="delivery_payment" id="dlv_payment_input" class="form-control form-control-lg font-weight-bold text-success" value="0">
                                <button type="button" class="btn btn-outline-success btn-sm" id="dlv_full_paid_btn">বাকি সব</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded border h-100 d-flex flex-column justify-content-center">
                                <small class="text-muted d-block">অবশিষ্ট বকেয়া থাকবে:</small>
                                <strong class="fs-5 text-danger">৳ <span id="dlv_due_text">0.00</span></strong>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <label class="form-label small font-weight-bold">পেমেন্ট মাধ্যম</label>
                            <select name="payment_method" class="form-select form-select-sm">
                                <option value="cash">নগদ (Cash)</option>
                                <option value="bkash">বিকাশ (bKash)</option>
                                <option value="nagad">নগদ (Nagad)</option>
                                <option value="bank">ব্যাংক (Bank)</option>
                                <option value="card">কার্ড (Card)</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small font-weight-bold">বকেয়া পরিশোধের তারিখ</label>
                            <input type="date" name="due_date" class="form-control form-control-sm">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small font-weight-bold">ট্রানজেকশন রেফারেন্স</label>
                            <input type="text" name="transaction_reference" class="form-control form-control-sm" placeholder="রেফারেন্স নম্বর">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small font-weight-bold">ডেলিভারি নোট</label>
                        <input type="text" name="delivery_notes" class="form-control form-control-sm" placeholder="মন্তব্য (ঐচ্ছিক)">
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="submit" class="btn btn-sm btn-success font-weight-bold px-3">
                        <i class="fa-solid fa-check-double me-1"></i> ডেলিভারি সম্পন্ন করুন
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.sell.payment_history_modal')
@endsection

@push('admin_script')
<script>
    function calculateReceiveMojuri() {
        var weight = parseFloat($('#rcv_actual_weight').val()) || 0;
        var rate = parseFloat($('#rcv_mojuri_rate').val()) || 0;
        var exactFee = weight * rate;
        var roundedFee = Math.round(exactFee);

        var isManual = $('#rcv_karigor_fee').data('manual') === true;

        if (!isManual) {
            $('#rcv_karigor_fee').val(roundedFee > 0 ? roundedFee : 0);
            $('#rcv_mojuri_calc_badge').html('৳ ' + roundedFee.toLocaleString('en-US') + ' <small class="text-muted">(' + weight.toFixed(3) + 'g × ৳' + rate.toFixed(2) + ')</small>');
        } else {
            var manualFee = parseFloat($('#rcv_karigor_fee').val()) || 0;
            var displayManual = (manualFee % 1 === 0) ? manualFee.toFixed(0) : manualFee.toFixed(2);
            $('#rcv_mojuri_calc_badge').html('৳ ' + displayManual + ' <span class="badge bg-warning text-dark ms-1">ম্যানুয়াল</span>');
        }

        if (rate > 0 && weight > 0) {
            var formulaHtml = 'হিসাব: <strong>' + weight.toFixed(3) + '</strong> গ্রাম × ৳<strong>' + rate.toFixed(2) + '</strong>/গ্রাম = ৳' + exactFee.toFixed(2);
            if (Math.abs(exactFee - roundedFee) > 0.001) {
                formulaHtml += ' ➔ <strong>৳ ' + roundedFee.toLocaleString('en-US') + '</strong> (রাউন্ডিং)';
            }
            $('#rcv_mojuri_formula_text').html(formulaHtml);
        } else {
            $('#rcv_mojuri_formula_text').text('হিসাব: প্রস্তুতকৃত নিট ওজন (গ্রাম) × প্রতি গ্রাম মজুরি রেট (পূর্ণ সংখ্যায় রাউন্ডিং)');
        }
    }

    function openReceiveModal(id, orderNo, prodName, targetGram, rawGold, karat, categoryName) {
        $('#rcv_modal_order_no').text(orderNo);
        $('#rcv_modal_prod_name').text(prodName);
        $('#rcv_modal_target_gram').text(targetGram);
        $('#rcv_modal_raw_gold').text(rawGold);
        $('#rcv_actual_weight').val(targetGram);
        $('#rcv_karigor_fee').data('manual', false);
        $('#receiveKarigorForm').attr('action', '/admin/custom-orders/' + id + '/receive');

        // Auto-match Karigor Mojuri option based on Karat and Category
        var matchedOption = null;
        var cleanKarat = (karat || '').toString().toLowerCase().replace(/[^a-z0-9]/g, '');
        var cleanCat = (categoryName || '').toString().toLowerCase().trim();

        // Priority 1: Match both Category and Karat/Type
        $('#rcv_mojuri_select option').each(function() {
            var optVal = $(this).val();
            if (!optVal) return;
            var optCat = ($(this).data('category') || '').toString().toLowerCase().trim();
            var optType = ($(this).data('type') || '').toString().toLowerCase().replace(/[^a-z0-9]/g, '');

            if (cleanCat && optCat && cleanCat.indexOf(optCat) !== -1 && cleanKarat && optType && (cleanKarat.indexOf(optType) !== -1 || optType.indexOf(cleanKarat) !== -1)) {
                matchedOption = this;
                return false;
            }
        });

        // Priority 2: Match Karat/Type only (e.g. 22k)
        if (!matchedOption && cleanKarat) {
            $('#rcv_mojuri_select option').each(function() {
                var optVal = $(this).val();
                if (!optVal) return;
                var optType = ($(this).data('type') || '').toString().toLowerCase().replace(/[^a-z0-9]/g, '');
                if (optType && (cleanKarat.indexOf(optType) !== -1 || optType.indexOf(cleanKarat) !== -1)) {
                    matchedOption = this;
                    return false;
                }
            });
        }

        // Priority 3: Match Category only
        if (!matchedOption && cleanCat) {
            $('#rcv_mojuri_select option').each(function() {
                var optVal = $(this).val();
                if (!optVal) return;
                var optCat = ($(this).data('category') || '').toString().toLowerCase().trim();
                if (optCat && (cleanCat.indexOf(optCat) !== -1 || optCat.indexOf(cleanCat) !== -1)) {
                    matchedOption = this;
                    return false;
                }
            });
        }

        if (matchedOption) {
            $('#rcv_mojuri_select').val($(matchedOption).val());
            var perGram = parseFloat($(matchedOption).data('per-gram')) || 0;
            $('#rcv_mojuri_rate').val(perGram.toFixed(2));
        } else {
            // Pick first available rate option if exists
            var firstOption = $('#rcv_mojuri_select option:eq(1)');
            if (firstOption.length && firstOption.val()) {
                $('#rcv_mojuri_select').val(firstOption.val());
                var perGram = parseFloat(firstOption.data('per-gram')) || 0;
                $('#rcv_mojuri_rate').val(perGram.toFixed(2));
            } else {
                $('#rcv_mojuri_select').val('');
                $('#rcv_mojuri_rate').val('0.00');
            }
        }

        calculateReceiveMojuri();
        $('#receiveKarigorModal').modal('show');
    }

    // Karigor Mojuri calculation event listeners
    $('#rcv_mojuri_select').on('change', function() {
        var opt = $(this).find(':selected');
        var perGram = parseFloat(opt.data('per-gram')) || 0;
        $('#rcv_mojuri_rate').val(perGram.toFixed(2));
        $('#rcv_karigor_fee').data('manual', false);
        calculateReceiveMojuri();
    });

    $('#rcv_mojuri_rate').on('input keyup change', function() {
        $('#rcv_karigor_fee').data('manual', false);
        calculateReceiveMojuri();
    });

    $('#rcv_actual_weight').on('input keyup change', function() {
        calculateReceiveMojuri();
    });

    $('#rcv_karigor_fee').on('input keyup', function() {
        $(this).data('manual', true);
        var manualFee = parseFloat($(this).val()) || 0;
        var displayManual = (manualFee % 1 === 0) ? manualFee.toFixed(0) : manualFee.toFixed(2);
        $('#rcv_mojuri_calc_badge').html('৳ ' + displayManual + ' <span class="badge bg-warning text-dark ms-1">ম্যানুয়াল</span>');
    });

    var currentPrevPaid = 0;

    function openDeliverModal(id, orderNo, prodName, actualWeight, karigorFee, estPrice, paidAmount, dueAmount) {
        currentPrevPaid = parseFloat(paidAmount) || 0;
        $('#dlv_modal_order_no').text(orderNo);
        $('#dlv_modal_prod_name').text(prodName);
        $('#dlv_modal_actual_weight').text(actualWeight);
        $('#dlv_modal_prev_paid').text(currentPrevPaid.toFixed(2));

        var roundedKarigorFee = Math.round(parseFloat(karigorFee) || 0);
        var initialPrice = estPrice > 0 ? (estPrice - roundedKarigorFee) : 0;
        $('#dlv_actual_price').val(initialPrice > 0 ? initialPrice.toFixed(2) : '');
        $('#dlv_karigor_fee').val(roundedKarigorFee > 0 ? roundedKarigorFee : (parseFloat(karigorFee) || 0));
        $('#dlv_discount').val(0);

        $('#deliverOrderForm').attr('action', '/admin/custom-orders/' + id + '/deliver');
        calculateDeliveryTotals();

        $('#deliverOrderModal').modal('show');
    }

    function calculateDeliveryTotals() {
        var price = parseFloat($('#dlv_actual_price').val()) || 0;
        var fee = parseFloat($('#dlv_karigor_fee').val()) || 0;
        var discount = parseFloat($('#dlv_discount').val()) || 0;

        var grandTotal = Math.max(0, price + fee - discount);
        $('#dlv_grand_total_text').text(grandTotal.toFixed(2));

        var paymentNow = parseFloat($('#dlv_payment_input').val()) || 0;
        var totalPaid = currentPrevPaid + paymentNow;
        var due = Math.max(0, grandTotal - totalPaid);

        $('#dlv_due_text').text(due.toFixed(2));
    }

    $('.dlv-calc, #dlv_payment_input').on('input keyup change', function() {
        calculateDeliveryTotals();
    });

    $('#dlv_full_paid_btn').on('click', function() {
        var price = parseFloat($('#dlv_actual_price').val()) || 0;
        var fee = parseFloat($('#dlv_karigor_fee').val()) || 0;
        var discount = parseFloat($('#dlv_discount').val()) || 0;
        var grandTotal = Math.max(0, price + fee - discount);
        var remaining = Math.max(0, grandTotal - currentPrevPaid);

        $('#dlv_payment_input').val(remaining.toFixed(2)).trigger('input');
    });
</script>
@endpush
