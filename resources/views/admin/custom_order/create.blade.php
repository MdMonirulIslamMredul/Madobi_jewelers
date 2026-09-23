@extends('admin.master')

@section('title')
নতুন কাস্টম জুয়েলারি অর্ডার তৈরি করুন
@endsection

@push('admin_style')
<style>
    .item-card {
        border-radius: 8px;
        transition: all 0.2s ease-in-out;
    }
    .item-card:hover {
        border-color: #0d6efd !important;
    }
    .summary-card-stat {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 12px;
        border-left: 4px solid #0d6efd;
    }
</style>
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-11 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-gem text-primary me-2 fa-lg"></i>
                    <h4 class="card-title mb-0 font-weight-bold">নতুন কাস্টম জুয়েলারি অর্ডার (Custom Jewelry Order)</h4>
                </div>
                <a href="{{ route('custom-orders.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> অর্ডার তালিকা
                </a>
            </div>
            <div class="card-body p-4">
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('custom-orders.store') }}" method="POST" enctype="multipart/form-data" id="customOrderForm" novalidate>
                    @csrf

                    <!-- 1. Customer Picker Section -->
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fa-solid fa-user-tag text-primary me-1"></i> ১. গ্রাহক নির্বাচন (Customer Information)
                        </h6>
                        @include('admin.common.customer_picker')
                    </div>

                    <!-- 2. Quantity & Multiple Product Controls (Purchase-Style) -->
                    <div class="card border-primary border-2 mb-4 shadow-sm bg-white">
                        <div class="card-body p-3">
                            <div class="row align-items-center g-3">
                                <div class="col-md-6 col-lg-5">
                                    <label for="items_count_input" class="form-label font-weight-bold text-dark mb-1">
                                        <i class="fa-solid fa-list-ol text-primary me-1"></i> এই অর্ডারে মোট কতটি পণ্য/আইটেম তৈরি হবে? (Quantity)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light fw-bold"><i class="fa-solid fa-layer-group text-primary"></i></span>
                                        <input type="number" min="1" max="20" id="items_count_input" class="form-control form-control-lg font-weight-bold text-center text-primary" value="1">
                                        <button type="button" class="btn btn-primary font-weight-bold" id="btn_apply_qty" title="কার্ড সংখ্যা সেট করুন">
                                            <i class="fa-solid fa-check me-1"></i> সেট করুন
                                        </button>
                                    </div>
                                    <small class="text-muted">একই গ্রাহক একাধিক ভিন্ন ভিন্ন ক্যাটাগরির পণ্য একসাথে অর্ডার করতে পারেন</small>
                                </div>
                                <div class="col-md-6 col-lg-7 text-md-end">
                                    <div class="d-inline-flex align-items-center gap-2">
                                        <span class="badge bg-light text-dark border py-2 px-3 fs-6">
                                            মোট আইটেম: <strong id="total_items_badge" class="text-primary">১</strong> টি
                                        </span>
                                        <button type="button" class="btn btn-success font-weight-bold px-3 py-2 shadow-sm" id="btn_add_another_item">
                                            <i class="fa-solid fa-plus-circle me-1"></i> আরও একটি পণ্য যোগ করুন (+ Add Item)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Dynamic Items Cards Container -->
                    <div class="mb-4">
                        <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-3">
                            <i class="fa-solid fa-ring text-primary me-1"></i> ২. পণ্যের বিবরণ, ওজন ও কারিগর অ্যাসাইনমেন্ট (Products & Specifications)
                        </h6>

                        <div id="itemsContainer">
                            <!-- Dynamically generated items will be appended here -->
                        </div>
                    </div>

                    <!-- Hidden Template for Dynamic Item Card -->
                    <template id="itemCardTemplate">
                        <div class="card border mb-3 shadow-sm item-card bg-white" data-index="__INDEX__">
                            <div class="card-header bg-light py-2 px-3 d-flex justify-content-between align-items-center border-bottom">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-primary me-2 px-2 py-1 item-badge">পণ্য #__NUMBER__</span>
                                    <span class="font-weight-bold text-dark item-title-preview">নতুন জুয়েলারি আইটেম</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item-btn" title="এই পণ্যটি মুছুন" style="display: none;">
                                    <i class="fa-solid fa-trash-can me-1"></i> মুছে ফেলুন
                                </button>
                            </div>
                            <div class="card-body p-3">
                                <!-- Row 1: Category, Product, Karat & Rate, Quantity (pieces) -->
                                <div class="row g-3 mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label font-weight-bold">ক্যাটাগরি <span class="text-danger">*</span></label>
                                        <select name="items[__INDEX__][category_id]" class="form-select category-select">
                                            <option value="">ক্যাটাগরি নির্বাচন করুন</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label font-weight-bold">পণ্যের নাম (Product) <span class="text-danger">*</span></label>
                                        <select name="items[__INDEX__][product_name]" class="form-select product-select">
                                            <option value="">পণ্য নির্বাচন করুন</option>
                                            @foreach($products as $prod)
                                                <option value="{{ $prod->product_name }}" data-category="{{ $prod->category_id }}">{{ $prod->product_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label font-weight-bold">ক্যারেট ও রেট (Rate) <span class="text-danger">*</span></label>
                                        <select name="items[__INDEX__][karat]" class="form-select karat-select font-weight-bold">
                                            <option value="">ক্যারেট নির্বাচন করুন</option>
                                            @foreach($productPrices as $pp)
                                                <option value="{{ $pp->product_name }}" 
                                                        data-price="{{ $pp->selling_price }}" 
                                                        data-price-per-gram="{{ $pp->selling_price_per_gram }}"
                                                        {{ $loop->first ? 'selected' : '' }}>
                                                    {{ $pp->product_name }} - ৳{{ number_format($pp->selling_price, 0) }}/ভরি (গ্রাম: ৳{{ number_format($pp->selling_price_per_gram, 2) }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="items[__INDEX__][unit_price_per_gram]" class="item-unit-price-per-gram" value="{{ $productPrices->first()->selling_price_per_gram ?? 0 }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label font-weight-bold">পিসের সংখ্যা <span class="text-danger">*</span></label>
                                        <input type="number" name="items[__INDEX__][quantity]" class="form-control item-qty-input font-weight-bold" min="1" value="1">
                                    </div>
                                </div>

                                <!-- Row 2: Weight Units, Gram Calculation & Product Price -->
                                <div class="p-3 bg-light rounded border mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="font-weight-bold text-warning-emphasis mb-0 small">
                                            <i class="fa-solid fa-scale-balanced me-1"></i> কাঙ্ক্ষিত স্বর্ণের ওজন (ভরি-আনা-রতি-পয়েন্ট) ও পণ্যের মূল্য
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="badge bg-white text-dark border item-gram-display">০.০০০ গ্রাম</span>
                                            <span class="badge bg-success-subtle text-success border border-success item-price-badge">মূল্য: ৳ ০.০০</span>
                                        </div>
                                    </div>
                                    <div class="row g-2 align-items-end">
                                        <div class="col-6 col-md-2">
                                            <label class="form-label small font-weight-bold mb-1">ভরি</label>
                                            <input type="number" step="1" min="0" name="items[__INDEX__][target_bhori]" class="form-control weight-calc vori-input" value="0" placeholder="০">
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label class="form-label small font-weight-bold mb-1">আনা <span class="text-muted fw-normal" style="font-size: 0.75rem;">(০-১৫)</span></label>
                                            <input type="number" step="1" min="0" max="15" name="items[__INDEX__][target_ana]" class="form-control weight-calc ana-input" value="0" placeholder="০-১৫">
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label class="form-label small font-weight-bold mb-1">রতি <span class="text-muted fw-normal" style="font-size: 0.75rem;">(০-৫)</span></label>
                                            <input type="number" step="1" min="0" max="5" name="items[__INDEX__][target_roti]" class="form-control weight-calc roti-input" value="0" placeholder="০-৫">
                                        </div>
                                        <div class="col-6 col-md-2">
                                            <label class="form-label small font-weight-bold mb-1">পয়েন্ট <span class="text-muted fw-normal" style="font-size: 0.75rem;">(০-৯)</span></label>
                                            <input type="number" step="1" min="0" max="9" name="items[__INDEX__][target_point]" class="form-control weight-calc point-input" value="0" placeholder="০-৯">
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label small font-weight-bold mb-1">ওজন (গ্রাম) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" step="0.001" min="0" name="items[__INDEX__][target_gram]" class="form-control font-weight-bold gram-input" placeholder="0.000" value="0">
                                                <span class="input-group-text bg-white">গ্রাম</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-2">
                                            <label class="form-label small font-weight-bold text-success mb-1">এই পণ্যের মূল্য (৳) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-white text-success fw-bold">৳</span>
                                                <input type="number" step="0.01" min="0" name="items[__INDEX__][estimated_price]" class="form-control font-weight-bold text-success item-price-input" placeholder="0.00" value="0">
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted d-block mt-2" style="font-size: 0.78rem;">
                                        <i class="fa-solid fa-circle-info text-info me-1"></i>ওজনের নিয়মাবলী: <strong>১ ভরি = ১৬ আনা</strong> (আনা সর্বোচ্চ ১৫), <strong>১ আনা = ৬ রতি</strong> (রতি সর্বোচ্চ ৫), <strong>১ রতি = ১০ পয়েন্ট</strong> (পয়েন্ট সর্বোচ্চ ৯)
                                    </small>
                                </div>

                                <!-- Row 3: Raw Gold Allocation, Karigor Assignment, Design Image & Details -->
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <label class="form-label font-weight-bold text-danger mb-0">
                                                <i class="fa-solid fa-coins me-1"></i>প্রয়োজনীয় পাকা সোনা <span class="text-danger">*</span>
                                            </label>
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary item-alloy-display" style="font-size: 0.75rem;">খাদ: ০.০০০ গ্রাম</span>
                                        </div>
                                        <div class="input-group">
                                            <input type="number" step="0.001" min="0" name="items[__INDEX__][raw_gold_needed]" class="form-control font-weight-bold raw-gold-input" placeholder="0.000" value="0">
                                            <span class="input-group-text bg-light font-weight-bold">গ্রাম</span>
                                        </div>
                                        <small class="text-muted item-purity-note d-block">ক্যারট বিশুদ্ধতা অনুযায়ী ২৪K পাকা সোনা</small>
                                        <div class="mt-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="items[__INDEX__][is_raw_material_given]" id="raw_given___INDEX__" value="1" checked>
                                                <label class="form-check-label text-dark fw-semibold" for="raw_given___INDEX__" style="font-size: 0.76rem;">
                                                    <i class="fa-solid fa-minus-circle text-danger me-1"></i>কাঁচামাল প্রদান (স্টক থেকে বিয়োগ)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label font-weight-bold">
                                            <i class="fa-solid fa-user-gear me-1 text-primary"></i>কারিগর নির্বাচন <span class="text-danger">*</span>
                                        </label>
                                        <select name="items[__INDEX__][assigned_karigor_id]" class="form-select karigor-select">
                                            <option value="">কারিগর নির্বাচন করুন</option>
                                            @foreach($karigors as $kg)
                                                <option value="{{ $kg->id }}">
                                                    {{ $kg->name }} {{ $kg->last_name ?? '' }} ({{ $kg->phone ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">এই পণ্য তৈরির দায়িত্বে থাকা কারিগর</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label font-weight-bold">
                                            <i class="fa-solid fa-image me-1 text-info"></i>ডিজাইন ছবি (Reference Image)
                                        </label>
                                        <div class="d-flex align-items-center gap-1">
                                            <input type="file" name="items[__INDEX__][design_photo]" class="form-control item-photo-input" accept="image/*">
                                            <img src="" class="item-photo-preview rounded border d-none" style="width: 38px; height: 38px; object-fit: cover;" alt="Preview">
                                        </div>
                                        <small class="text-muted">এই পণ্যের ডিজাইন ছবি (ঐচ্ছিক)</small>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label font-weight-bold">
                                            <i class="fa-solid fa-pen-to-square me-1 text-muted"></i>ডিজাইন বা বিশেষ নির্দেশনা
                                        </label>
                                        <input type="text" name="items[__INDEX__][details]" class="form-control item-details-input" placeholder="যেমন: চুড়িতে ফুল খোদাই হবে, মাপ ২/৪">
                                        <small class="text-muted">সাইজ, খোদাই বা বিশেষ কোনো মাপ</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- 4. Overall Totals & Billing Card -->
                    <div class="card border mb-3 shadow-none bg-light">
                        <div class="card-body p-4">
                            <h6 class="font-weight-bold text-success mb-3">
                                <i class="fa-solid fa-money-bill-wave me-1"></i> ৩. অর্ডারের মোট হিসাব, অগ্রিম ও ডেলিভারি তথ্য
                            </h6>

                            <!-- Live Cumulative Badges -->
                            <div class="row g-3 mb-4">
                                <div class="col-6 col-md-3">
                                    <div class="summary-card-stat border-primary">
                                        <small class="text-muted d-block fw-bold">সর্বমোট পণ্য সংখ্যা:</small>
                                        <h5 class="mb-0 text-primary font-weight-bold" id="summary_total_qty_text">১ টি</h5>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="summary-card-stat border-warning">
                                        <small class="text-muted d-block fw-bold">সর্বমোট কাঙ্ক্ষিত ওজন:</small>
                                        <h5 class="mb-0 text-warning-emphasis font-weight-bold" id="summary_total_gram_text">০.০০০ গ্রাম</h5>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="summary-card-stat border-danger">
                                        <small class="text-muted d-block fw-bold">সর্বমোট পাকা সোনা (Pure):</small>
                                        <h5 class="mb-0 text-danger font-weight-bold" id="summary_total_raw_gold_text">০.০০০ গ্রাম</h5>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="summary-card-stat border-secondary">
                                        <small class="text-muted d-block fw-bold">সর্বমোট খাদ (Alloy):</small>
                                        <h5 class="mb-0 text-secondary font-weight-bold" id="summary_total_alloy_text">০.০০০ গ্রাম</h5>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold">আনুমানিক মোট মূল্য (Estimated Total)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" step="0.01" min="0" name="estimated_price" id="estimated_price" class="form-control font-weight-bold" placeholder="0.00" value="{{ old('estimated_price', 0) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold">অগ্রিম গ্রহণ (Advance Payment)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" step="0.01" min="0" name="advance_payment" id="advance_payment" class="form-control font-weight-bold text-success" placeholder="0.00" value="{{ old('advance_payment', 0) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold">অবশিষ্ট বকেয়া (Estimated Due)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="text" id="estimated_due_display" class="form-control font-weight-bold text-danger bg-white" readonly value="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold">পেমেন্ট মাধ্যম</label>
                                    <select name="payment_method" class="form-select">
                                        <option value="cash">নগদ (Cash)</option>
                                        <option value="bkash">বিকাশ (bKash)</option>
                                        <option value="nagad">নগদ (Nagad)</option>
                                        <option value="bank">ব্যাংক (Bank)</option>
                                        <option value="card">কার্ড (Card)</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold">সম্ভাব্য ডেলিভারি তারিখ</label>
                                    <input type="date" name="delivery_date" class="form-control" value="{{ old('delivery_date') }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label font-weight-bold">ট্রানজেকশন রেফারেন্স (ঐচ্ছিক)</label>
                                    <input type="text" name="transaction_reference" class="form-control" placeholder="TrxID">
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold">রেফারেন্স ডিজাইন ছবি (Design Photo)</label>
                                    <input type="file" name="design_photo" class="form-control" accept="image/*">
                                    <small class="text-muted">ক্যাটালগ বা গ্রাহকের দেয়া গহনার ছবির কপি</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label font-weight-bold">সামগ্রিক অর্ডার নোট / মন্তব্য</label>
                                    <textarea name="details" rows="2" class="form-control" placeholder="গ্রাহকের সাথে চুক্তি বা সামগ্রিক অর্ডার সংক্রান্ত যেকোনো বিশেষ নোট..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Prominent Alert Box for Validation Errors -->
                    <div id="order_error_alert" class="alert alert-danger d-none mt-4 mb-2 shadow-sm border-2 border-danger">
                        <div class="d-flex align-items-start">
                            <i class="fa-solid fa-circle-exclamation fa-2x text-danger me-3 flex-shrink-0 mt-1"></i>
                            <div>
                                <h6 class="alert-heading font-weight-bold mb-1 text-danger">অর্ডার গ্রহণ করতে নিচের প্রয়োজনীয় তথ্যগুলো পূরণ করুন:</h6>
                                <ul class="mb-0 ps-3 small text-danger" id="order_error_list"></ul>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('custom-orders.index') }}" class="btn btn-secondary px-4">বাতিল</a>
                        <button type="button" class="btn btn-primary px-4 py-2 font-weight-bold shadow-sm" id="submitOrderBtn">
                            <i class="fa-solid fa-check-circle me-1"></i> অর্ডার গ্রহণ ও কারিগরকে দায়িত্ব অর্পণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin_script')
<script>
    $(document).ready(function() {
        var VORI_TO_GRAM = 11.664;
        var ANA_TO_GRAM = VORI_TO_GRAM / 16;
        var ROTI_TO_GRAM = ANA_TO_GRAM / 6;
        var POINT_TO_GRAM = ROTI_TO_GRAM / 10;

        var allProducts = @json($products);
        var cardTemplate = $('#itemCardTemplate').html();
        var cardCounter = 0;

        // Add a new Item Card
        function addItemCard() {
            var index = cardCounter++;
            var html = cardTemplate.replace(/__INDEX__/g, index).replace(/__NUMBER__/g, $('.item-card').length + 1);
            var $card = $(html);
            $('#itemsContainer').append($card);
            reindexCards();
            return $card;
        }

        // Reindex all Cards
        function reindexCards() {
            var $cards = $('.item-card');
            var totalCount = $cards.length;

            $cards.each(function(i) {
                var cardIndex = i;
                var displayNum = i + 1;
                var $card = $(this);

                $card.attr('data-index', cardIndex);
                $card.find('.item-badge').text('পণ্য #' + displayNum);

                // Update input names
                $card.find(':input').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/items\[\d+\]/, 'items[' + cardIndex + ']');
                        $(this).attr('name', newName);
                    }
                });

                // Delete button visibility
                if (totalCount > 1) {
                    $card.find('.remove-item-btn').show();
                } else {
                    $card.find('.remove-item-btn').hide();
                }
            });

            $('#items_count_input').val(totalCount);
            $('#total_items_badge').text(totalCount);
            $('#summary_total_qty_text').text(totalCount + ' টি');

            calculateCumulativeTotals();
        }

        // Set quantity of cards via input / button
        function setQuantity(desiredCount) {
            desiredCount = parseInt(desiredCount) || 1;
            if (desiredCount < 1) desiredCount = 1;
            if (desiredCount > 25) desiredCount = 25;

            var currentCount = $('.item-card').length;

            if (desiredCount > currentCount) {
                for (var i = currentCount; i < desiredCount; i++) {
                    addItemCard();
                }
            } else if (desiredCount < currentCount) {
                // Remove trailing cards
                var toRemove = currentCount - desiredCount;
                $('.item-card').slice(-toRemove).remove();
                reindexCards();
            }
        }

        $('#btn_apply_qty').on('click', function() {
            setQuantity($('#items_count_input').val());
        });

        $('#items_count_input').on('change keyup', function(e) {
            if (e.keyCode === 13) {
                e.preventDefault();
                setQuantity($(this).val());
            }
        });

        $('#btn_add_another_item').on('click', function() {
            addItemCard();
            $('html, body').animate({
                scrollTop: $('.item-card').last().offset().top - 100
            }, 300);
        });

        // Remove a card
        $(document).on('click', '.remove-item-btn', function() {
            var $card = $(this).closest('.item-card');
            $card.fadeOut(200, function() {
                $(this).remove();
                reindexCards();
            });
        });

        // Dependent dropdown: Category -> Products
        function filterProductsForCard($card) {
            var catId = $card.find('.category-select').val();
            var $prodSelect = $card.find('.product-select');
            var currentVal = $prodSelect.val();

            $prodSelect.empty();
            $prodSelect.append('<option value="">পণ্য নির্বাচন করুন</option>');

            var filtered = allProducts;
            if (catId) {
                filtered = allProducts.filter(function(p) {
                    return p.category_id == catId;
                });
            }

            $.each(filtered, function(i, p) {
                var isSel = (currentVal && currentVal === p.product_name) ? 'selected' : '';
                $prodSelect.append('<option value="' + p.product_name + '" data-category="' + p.category_id + '" ' + isSel + '>' + p.product_name + '</option>');
            });
        }

        $(document).on('change', '.category-select', function() {
            var $card = $(this).closest('.item-card');
            filterProductsForCard($card);
        });

        $(document).on('change', '.product-select', function() {
            var $card = $(this).closest('.item-card');
            var catId = $(this).find(':selected').data('category');
            var prodName = $(this).val();

            if (catId && !$card.find('.category-select').val()) {
                $card.find('.category-select').val(catId);
            }

            // Update card header preview
            if (prodName) {
                $card.find('.item-title-preview').text(prodName);
            } else {
                $card.find('.item-title-preview').text('জুয়েলারির বিবরণ');
            }
        });

        // Auto-enforce limits on Weight inputs so user doesn't make mistakes:
        // ১ ভরি = ১৬ আনা (আনা: ০-১৫)
        // ১ আনা = ৬ রতি (রতি: ০-৫)
        // ১ রতি = ১০ পয়েন্ট (পয়েন্ট: ০-৯)
        $(document).on('input change', '.ana-input', function() {
            var val = parseFloat($(this).val());
            if (!isNaN(val)) {
                if (val > 15) $(this).val(15);
                else if (val < 0) $(this).val(0);
            }
        });

        $(document).on('input change', '.roti-input', function() {
            var val = parseFloat($(this).val());
            if (!isNaN(val)) {
                if (val > 5) $(this).val(5);
                else if (val < 0) $(this).val(0);
            }
        });

        $(document).on('input change', '.point-input', function() {
            var val = parseFloat($(this).val());
            if (!isNaN(val)) {
                if (val > 9) $(this).val(9);
                else if (val < 0) $(this).val(0);
            }
        });

        $(document).on('input change', '.vori-input', function() {
            var val = parseFloat($(this).val());
            if (!isNaN(val) && val < 0) {
                $(this).val(0);
            }
        });

        // Karat Purity Calculator
        // 24K = 24/24 (100%)
        // 22K = 22/24 (91.67%)
        // 21K = 21/24 (87.50%)
        // 18K = 18/24 (75.00%)
        // Traditional = 14/24 (58.33%)
        function getKaratPurityInfo(karatText) {
            if (!karatText) {
                return { ratio: 22 / 24, label: '২২ ক্যারেট (৯১.৬৭%)' };
            }
            var lower = karatText.toLowerCase();
            if (lower.indexOf('24') !== -1) {
                return { ratio: 24 / 24, label: '২৪ ক্যারেট (১০০%)' };
            } else if (lower.indexOf('22') !== -1) {
                return { ratio: 22 / 24, label: '২২ ক্যারেট (৯১.৬৭%)' };
            } else if (lower.indexOf('21') !== -1) {
                return { ratio: 21 / 24, label: '২১ ক্যারেট (৮৭.৫০%)' };
            } else if (lower.indexOf('18') !== -1) {
                return { ratio: 18 / 24, label: '১৮ ক্যারেট (৭৫.০০%)' };
            } else if (lower.indexOf('traditional') !== -1 || lower.indexOf('সনাতন') !== -1) {
                return { ratio: 14 / 24, label: 'সনাতন পদ্ধতি (১৪ ক্যারেট - ৫৮.৩৩%)' };
            }
            return { ratio: 22 / 24, label: '২২ ক্যারেট (৯১.৬৭%)' };
        }

        // Weight and Price Calculation per card
        function updateCardGramsAndPrice($card) {
            var vori = Math.max(0, parseFloat($card.find('.vori-input').val()) || 0);
            var ana = Math.min(15, Math.max(0, parseFloat($card.find('.ana-input').val()) || 0));
            var roti = Math.min(5, Math.max(0, parseFloat($card.find('.roti-input').val()) || 0));
            var point = Math.min(9, Math.max(0, parseFloat($card.find('.point-input').val()) || 0));

            var totalGrams = (vori * VORI_TO_GRAM) + (ana * ANA_TO_GRAM) + (roti * ROTI_TO_GRAM) + (point * POINT_TO_GRAM);

            if (totalGrams > 0) {
                $card.find('.gram-input').val(totalGrams.toFixed(3));
                $card.find('.item-gram-display').text(totalGrams.toFixed(3) + ' গ্রাম');
            } else {
                totalGrams = parseFloat($card.find('.gram-input').val()) || 0;
                $card.find('.item-gram-display').text(totalGrams.toFixed(3) + ' গ্রাম');
            }

            // Karat purity & Raw Gold calculation
            var selectedKaratText = $card.find('.karat-select').val() || '';
            var purityInfo = getKaratPurityInfo(selectedKaratText);

            var isManual = $card.data('raw-gold-manual') === true;
            var currentRawGold = parseFloat($card.find('.raw-gold-input').val()) || 0;

            var rawGoldVal;
            if (!isManual || currentRawGold === 0) {
                rawGoldVal = totalGrams * purityInfo.ratio;
                $card.find('.raw-gold-input').val(rawGoldVal.toFixed(3));
            } else {
                rawGoldVal = currentRawGold;
            }

            var alloyVal = Math.max(0, totalGrams - rawGoldVal);
            $card.find('.item-alloy-display').text('খাদ: ' + alloyVal.toFixed(3) + ' গ্রাম');
            $card.find('.item-purity-note').html('<i class="fa-solid fa-gem text-warning me-1"></i>' + purityInfo.label + ' বিশুদ্ধতায় ২৪K পাকা সোনা');

            // Rate per gram from product_price
            var $karatOption = $card.find('.karat-select option:selected');
            var pricePerGram = parseFloat($karatOption.data('price-per-gram')) || 0;
            var qty = parseInt($card.find('.item-qty-input').val()) || 1;

            $card.find('.item-unit-price-per-gram').val(pricePerGram.toFixed(2));

            var itemTotalPrice = totalGrams * pricePerGram * qty;
            $card.find('.item-price-input').val(itemTotalPrice.toFixed(2));
            $card.find('.item-price-badge').text('মূল্য: ৳ ' + itemTotalPrice.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

            calculateCumulativeTotals();
        }

        $(document).on('input keyup change', '.weight-calc, .gram-input, .item-qty-input', function() {
            var $card = $(this).closest('.item-card');
            updateCardGramsAndPrice($card);
        });

        $(document).on('change', '.karat-select', function() {
            var $card = $(this).closest('.item-card');
            $card.removeData('raw-gold-manual'); // Re-calculate for newly selected karat
            updateCardGramsAndPrice($card);
        });

        $(document).on('input keyup change', '.raw-gold-input', function() {
            var $card = $(this).closest('.item-card');
            $card.data('raw-gold-manual', true);
            var totalGrams = parseFloat($card.find('.gram-input').val()) || 0;
            var userRawGold = parseFloat($(this).val()) || 0;
            var alloyVal = Math.max(0, totalGrams - userRawGold);
            $card.find('.item-alloy-display').text('খাদ: ' + alloyVal.toFixed(3) + ' গ্রাম');
            calculateCumulativeTotals();
        });

        $(document).on('input keyup change', '.item-price-input', function() {
            var $card = $(this).closest('.item-card');
            var price = parseFloat($(this).val()) || 0;
            $card.find('.item-price-badge').text('মূল্য: ৳ ' + price.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            calculateCumulativeTotals();
        });

        // Item design photo preview
        $(document).on('change', '.item-photo-input', function() {
            var input = this;
            var $card = $(this).closest('.item-card');
            var $preview = $card.find('.item-photo-preview');
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $preview.attr('src', e.target.result).removeClass('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                $preview.addClass('d-none').attr('src', '');
            }
        });

        // Cumulative Totals Calculation
        function calculateCumulativeTotals() {
            var totalGrams = 0;
            var totalRawGold = 0;
            var totalQty = 0;
            var totalEstimatedPrice = 0;

            $('.item-card').each(function() {
                var g = parseFloat($(this).find('.gram-input').val()) || 0;
                var rg = parseFloat($(this).find('.raw-gold-input').val()) || 0;
                var qty = parseInt($(this).find('.item-qty-input').val()) || 1;
                var itemPrice = parseFloat($(this).find('.item-price-input').val()) || 0;

                totalGrams += g;
                totalRawGold += rg;
                totalQty += qty;
                totalEstimatedPrice += itemPrice;
            });

            var totalAlloy = Math.max(0, totalGrams - totalRawGold);

            $('#summary_total_gram_text').text(totalGrams.toFixed(3) + ' গ্রাম');
            $('#summary_total_raw_gold_text').text(totalRawGold.toFixed(3) + ' গ্রাম');
            $('#summary_total_alloy_text').text(totalAlloy.toFixed(3) + ' গ্রাম');

            // Estimated Financials (sum of all product items)
            $('#estimated_price').val(totalEstimatedPrice.toFixed(2));
            var advPayment = parseFloat($('#advance_payment').val()) || 0;
            var due = Math.max(0, totalEstimatedPrice - advPayment);

            $('#estimated_due_display').val(due.toFixed(2));
        }

        $('#estimated_price, #advance_payment').on('input keyup change', function() {
            calculateCumulativeTotals();
        });

        // Clear invalid feedback on input/change
        $(document).on('change input', '.category-select, .product-select, .karat-select, .karigor-select, .gram-input, .raw-gold-input', function() {
            if ($(this).val()) {
                $(this).removeClass('is-invalid border-danger');
            }
        });

        // Form Submission validation on #submitOrderBtn click
        $('#submitOrderBtn').on('click', function(e) {
            e.preventDefault();
            var errors = [];
            var $firstInvalid = null;

            // 1. Check Customer
            var custId = $('#selected_customer_id').val();
            if (!custId) {
                errors.push('অনুগ্রহ করে একজন গ্রাহক নির্বাচন করুন অথবা "ক্যাশ গ্রাহক (Walk-in)" বাটন চাপুন।');
                $('#customer_selection_error').removeClass('d-none');
                $('#customer_search_input').addClass('is-invalid border-danger');
                if (!$firstInvalid) $firstInvalid = $('#customer_search_container');
            } else {
                $('#customer_selection_error').addClass('d-none');
                $('#customer_search_input').removeClass('is-invalid border-danger');
            }

            // 2. Check Items
            var $cards = $('.item-card');
            if ($cards.length === 0) {
                errors.push('অর্ডারে কমপক্ষে একটি পণ্য যোগ করতে হবে।');
                if (!$firstInvalid) $firstInvalid = $('#itemsContainer');
            }

            // 3. Check Each Item Card
            $cards.each(function(i) {
                var num = i + 1;
                var $c = $(this);

                var cat = $c.find('.category-select').val();
                var prod = $c.find('.product-select').val();
                var karat = $c.find('.karat-select').val();
                var gram = parseFloat($c.find('.gram-input').val()) || 0;
                var rawGold = parseFloat($c.find('.raw-gold-input').val()) || 0;
                var karigor = $c.find('.karigor-select').val();

                if (!cat) {
                    errors.push('পণ্য #' + num + ': ক্যাটাগরি নির্বাচন করুন।');
                    $c.find('.category-select').addClass('is-invalid border-danger');
                    if (!$firstInvalid) $firstInvalid = $c.find('.category-select');
                } else {
                    $c.find('.category-select').removeClass('is-invalid border-danger');
                }

                if (!prod) {
                    errors.push('পণ্য #' + num + ': পণ্যের নাম নির্বাচন করুন।');
                    $c.find('.product-select').addClass('is-invalid border-danger');
                    if (!$firstInvalid) $firstInvalid = $c.find('.product-select');
                } else {
                    $c.find('.product-select').removeClass('is-invalid border-danger');
                }

                if (!karat) {
                    errors.push('পণ্য #' + num + ': ক্যারেট ও রেট নির্বাচন করুন।');
                    $c.find('.karat-select').addClass('is-invalid border-danger');
                    if (!$firstInvalid) $firstInvalid = $c.find('.karat-select');
                } else {
                    $c.find('.karat-select').removeClass('is-invalid border-danger');
                }

                if (gram <= 0) {
                    errors.push('পণ্য #' + num + ': কাঙ্ক্ষিত স্বর্ণের ওজন (গ্রাম) প্রদান করুন।');
                    $c.find('.gram-input').addClass('is-invalid border-danger');
                    if (!$firstInvalid) $firstInvalid = $c.find('.gram-input');
                } else {
                    $c.find('.gram-input').removeClass('is-invalid border-danger');
                }

                if (rawGold <= 0) {
                    errors.push('পণ্য #' + num + ': প্রয়োজনীয় পাকা সোনা (গ্রাম) উল্লেখ করুন।');
                    $c.find('.raw-gold-input').addClass('is-invalid border-danger');
                    if (!$firstInvalid) $firstInvalid = $c.find('.raw-gold-input');
                } else {
                    $c.find('.raw-gold-input').removeClass('is-invalid border-danger');
                }

                var ana = parseFloat($c.find('.ana-input').val()) || 0;
                var roti = parseFloat($c.find('.roti-input').val()) || 0;
                var point = parseFloat($c.find('.point-input').val()) || 0;

                if (ana > 15 || ana < 0) {
                    errors.push('পণ্য #' + num + ': আনা সর্বোচ্চ ১৫ হতে পারবে (১৬ আনা = ১ ভরি)।');
                    $c.find('.ana-input').addClass('is-invalid border-danger');
                    if (!$firstInvalid) $firstInvalid = $c.find('.ana-input');
                } else {
                    $c.find('.ana-input').removeClass('is-invalid border-danger');
                }

                if (roti > 5 || roti < 0) {
                    errors.push('পণ্য #' + num + ': রতি সর্বোচ্চ ৫ হতে পারবে (৬ রতি = ১ আনা)।');
                    $c.find('.roti-input').addClass('is-invalid border-danger');
                    if (!$firstInvalid) $firstInvalid = $c.find('.roti-input');
                } else {
                    $c.find('.roti-input').removeClass('is-invalid border-danger');
                }

                if (point > 9 || point < 0) {
                    errors.push('পণ্য #' + num + ': পয়েন্ট সর্বোচ্চ ৯ হতে পারবে (১০ পয়েন্ট = ১ রতি)।');
                    $c.find('.point-input').addClass('is-invalid border-danger');
                    if (!$firstInvalid) $firstInvalid = $c.find('.point-input');
                } else {
                    $c.find('.point-input').removeClass('is-invalid border-danger');
                }

                if (!karigor) {
                    errors.push('পণ্য #' + num + ': তৈরি করার জন্য কারিগর নির্বাচন করুন।');
                    $c.find('.karigor-select').addClass('is-invalid border-danger');
                    if (!$firstInvalid) $firstInvalid = $c.find('.karigor-select');
                } else {
                    $c.find('.karigor-select').removeClass('is-invalid border-danger');
                }
            });

            // Display errors if any
            if (errors.length > 0) {
                var $errList = $('#order_error_list');
                $errList.empty();
                $.each(errors, function(idx, msg) {
                    $errList.append('<li><i class="fa-solid fa-circle-arrow-right me-1"></i> ' + msg + '</li>');
                });
                $('#order_error_alert').removeClass('d-none');

                // Smooth scroll to the first invalid field or alert
                if ($firstInvalid && $firstInvalid.length) {
                    $('html, body').animate({
                        scrollTop: $firstInvalid.offset().top - 130
                    }, 300);
                    $firstInvalid.focus();
                } else {
                    $('html, body').animate({
                        scrollTop: $('#order_error_alert').offset().top - 130
                    }, 300);
                }
                return false;
            }

            // If valid: hide error alert, disable button, submit form
            $('#order_error_alert').addClass('d-none');
            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> অর্ডার সম্পন্ন হচ্ছে...');
            $('#customOrderForm')[0].submit();
        });

        // Initialize with 1 default card on page load
        addItemCard();
    });
</script>
@endpush
