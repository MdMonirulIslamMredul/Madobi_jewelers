@extends('admin.master')

@section('title')
অর্ডার বিবরণ - {{ $order->order_no }}
@endsection

@push('admin_style')
<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #printable_order_card, #printable_order_card * {
            visibility: visible;
        }
        #printable_order_card {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .no-print {
            display: none !important;
        }
    }
    .order-box {
        background: #fff;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-10 mx-auto">
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">
            <a href="{{ route('custom-orders.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fa-solid fa-arrow-left me-1"></i> অর্ডার তালিকা
            </a>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('custom-orders.invoice', $order->id) }}" class="btn btn-outline-success btn-sm">
                    <i class="fa-solid fa-file-invoice-dollar me-1"></i> গ্রাহক ইনভয়েস (A4)
                </a>
                <a href="{{ route('custom-orders.karigor-invoice', $order->id) }}" class="btn btn-outline-warning text-dark btn-sm font-weight-bold">
                    <i class="fa-solid fa-hammer me-1"></i> কারিগর জব কার্ড (A4)
                </a>
                <button type="button" class="btn btn-outline-info btn-sm" onclick="openPaymentHistoryModal('custom_order', {{ $order->id }})">
                    <i class="fa-solid fa-hand-holding-dollar me-1"></i> কিস্তি পরিশোধের ইতিহাস
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                    <i class="fa-solid fa-print me-1"></i> প্রিন্ট ভিউ
                </button>
            </div>
        </div>

        <div class="order-box border" id="printable_order_card">
            <!-- Header -->
            <div class="row align-items-center mb-4 pb-3 border-bottom">
                <div class="col-sm-7">
                    @php
                        $logo = \App\Models\Logo::latest()->first();
                    @endphp
                    <h3 class="text-primary font-weight-bold mb-1">{{ $logo->site_name ?? 'Madobi Jewelers' }}</h3>
                    <p class="text-muted small mb-0">কাস্টম জুয়েলারি অর্ডার মেমো ও জব কার্ড</p>
                </div>
                <div class="col-sm-5 text-sm-end">
                    <h5 class="text-dark font-weight-bold mb-1">অর্ডার মেমো</h5>
                    <p class="mb-1 font-weight-bold text-primary fs-6">{{ $order->order_no }}</p>
                    <small class="text-muted d-block">অর্ডার তারিখ: {{ $order->created_at->format('d M, Y') }}</small>
                    @if($order->delivery_date)
                        <small class="text-info d-block font-weight-bold">ডেলিভারি তারিখ: {{ \Carbon\Carbon::parse($order->delivery_date)->format('d M, Y') }}</small>
                    @endif
                </div>
            </div>

            <!-- Customer & Status Info -->
            <div class="row mb-4">
                <div class="col-sm-6">
                    <div class="p-3 bg-light rounded border">
                        <small class="text-muted font-weight-bold d-block mb-1">গ্রাহকের তথ্য:</small>
                        <h6 class="font-weight-bold mb-1 text-dark">{{ $order->customer->name ?? 'N/A' }} {{ $order->customer->last_name ?? '' }}</h6>
                        <div class="small text-muted">
                            <div><i class="fa-solid fa-phone me-1"></i>ফোন: {{ $order->customer->phone ?? 'N/A' }}</div>
                            @if($order->customer->phone2)
                                <div><i class="fa-solid fa-phone-volume me-1"></i>বিকল্প ফোন: {{ $order->customer->phone2 }}</div>
                            @endif
                            @if($order->customer->address)
                                <div><i class="fa-solid fa-location-dot me-1"></i>ঠিকানা: {{ $order->customer->address }}</div>
                            @endif
                            @if($order->customer->extra_info)
                                <div><i class="fa-solid fa-circle-info me-1"></i>অন্যান্য: {{ $order->customer->extra_info }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 text-sm-end">
                    <div class="p-3 bg-light rounded border text-sm-end h-100">
                        <small class="text-muted font-weight-bold d-block mb-1">অর্ডার স্ট্যাটাস:</small>
                        @if($order->status === 'in_production')
                            <span class="badge bg-warning text-dark fs-6 px-3 py-1">তৈরি হচ্ছে (In Production)</span>
                        @elseif($order->status === 'ready_for_delivery')
                            <span class="badge bg-info text-white fs-6 px-3 py-1">ডেলিভারির জন্য প্রস্তুত</span>
                        @elseif($order->status === 'delivered')
                            <span class="badge bg-success text-white fs-6 px-3 py-1">ডেলিভারি সম্পন্ন (Delivered)</span>
                        @else
                            <span class="badge bg-secondary fs-6 px-3 py-1">{{ $order->status }}</span>
                        @endif

                        <div class="mt-2">
                            <small class="text-muted d-block">পেমেন্ট স্ট্যাটাস:</small>
                            @if($order->payment_status === 'paid')
                                <span class="badge bg-success-subtle text-success border border-success">পরিশোধিত</span>
                            @elseif($order->payment_status === 'partial')
                                <span class="badge bg-warning-subtle text-warning border border-warning">আংশিক / কিস্তি</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger">বকেয়া</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Jewelry Items Table (Multi-product support) -->
            <div class="card border mb-4">
                <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                    <span class="font-weight-bold text-dark">
                        <i class="fa-solid fa-gem me-1 text-primary"></i> অর্ডারের অন্তর্ভুক্ত জুয়েলারি পণ্যসমূহ ({{ $order->items->count() ?: 1 }} টি আইটেম)
                    </span>
                    <span class="badge bg-primary">মোট কোয়ান্টিটি: {{ $order->quantity }} টি</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 40px;">#</th>
                                <th>পণ্যের বিবরণ</th>
                                <th class="text-center">ক্যাটাগরি ও ক্যারেট</th>
                                <th class="text-center">পিস</th>
                                <th class="text-center">কাঙ্ক্ষিত ওজন</th>
                                <th class="text-end">দর/গ্রাম ও আনুমানিক মূল্য</th>
                                <th class="text-center">বরাদ্দ পাকা সোনা</th>
                                <th>নিযুক্ত কারিগর</th>
                                <th class="text-center">স্ট্যাটাস</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($order->items && $order->items->count() > 0)
                                @foreach($order->items as $idx => $item)
                                <tr>
                                    <td class="text-center">{{ $idx + 1 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->design_photo)
                                                <a href="{{ asset($item->design_photo) }}" target="_blank" class="me-2 flex-shrink-0" title="বড় আকারে ছবি দেখুন">
                                                    <img src="{{ asset($item->design_photo) }}" class="rounded border shadow-sm" style="width: 44px; height: 44px; object-fit: cover;" alt="{{ $item->product_name }}">
                                                </a>
                                            @endif
                                            <div>
                                                <strong class="text-dark">{{ $item->product_name }}</strong>
                                                @if($item->details)
                                                    <small class="text-muted d-block"><i class="fa-solid fa-pen-to-square me-1"></i>{{ $item->details }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border" style="color: #0f172a !important; font-weight: 600;">{{ $item->category->category_name ?? '-' }}</span>
                                        <span class="badge" style="background-color: #fef3c7 !important; color: #92400e !important; border: 1px solid #f59e0b !important; font-weight: 700; font-size: 11px;">
                                            {{ $item->karat }}
                                        </span>
                                    </td>
                                    <td class="text-center font-weight-bold">{{ $item->quantity }}</td>
                                    <td class="text-center">
                                        {{ $item->target_bhori ?? 0 }} ভরি, {{ $item->target_ana ?? 0 }} আনা, {{ $item->target_roti ?? 0 }} রতি, {{ $item->target_point ?? 0 }} পয়েন্ট
                                        <small class="text-muted d-block">({{ number_format($item->target_gram, 3) }} গ্রাম)</small>
                                    </td>
                                    <td class="text-end">
                                        <strong class="text-success">৳ {{ number_format($item->estimated_price, 2) }}</strong>
                                        @if($item->unit_price_per_gram > 0)
                                            <small class="text-muted d-block">(@ ৳{{ number_format($item->unit_price_per_gram, 2) }}/গ্রাম)</small>
                                        @endif
                                    </td>
                                    <td class="text-center text-danger font-weight-bold">
                                        {{ number_format($item->raw_gold_needed, 3) }} গ্রাম
                                        @php
                                            $alloy = max(0, $item->target_gram - $item->raw_gold_needed);
                                        @endphp
                                        @if($alloy > 0)
                                            <small class="text-secondary d-block fw-normal">(খাদ: {{ number_format($alloy, 3) }} গ্রাম)</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($item->karigor)
                                            <strong>{{ $item->karigor->name }} {{ $item->karigor->last_name ?? '' }}</strong>
                                            <small class="text-muted d-block">{{ $item->karigor->phone ?? '' }}</small>
                                        @else
                                            <span class="text-muted">অনির্ধারিত</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $itemStatus = $item->status;
                                            if (in_array($order->status, ['ready_for_delivery', 'delivered']) && $itemStatus === 'in_production') {
                                                $itemStatus = $order->status;
                                            } elseif ($item->karigorJob && in_array(strtolower($item->karigorJob->status), ['completed', 'complete']) && $itemStatus === 'in_production') {
                                                $itemStatus = 'ready_for_delivery';
                                            }
                                        @endphp

                                        @if($itemStatus === 'delivered')
                                            <span class="badge" style="background-color: #dcfce7 !important; color: #166534 !important; border: 1px solid #86efac !important; font-weight: 700; font-size: 11px;">
                                                <i class="fa-solid fa-check-double me-1"></i>ডেলিভারি সম্পন্ন (Delivered)
                                            </span>
                                        @elseif($itemStatus === 'ready_for_delivery')
                                            <span class="badge" style="background-color: #e0f2fe !important; color: #0369a1 !important; border: 1px solid #7dd3fc !important; font-weight: 700; font-size: 11px;">
                                                <i class="fa-solid fa-box-open me-1"></i>তৈরি প্রস্তুত (Ready)
                                            </span>
                                        @elseif($itemStatus === 'in_production')
                                            <span class="badge" style="background-color: #fef9c3 !important; color: #854d0e !important; border: 1px solid #fde047 !important; font-weight: 700; font-size: 11px;">
                                                <i class="fa-solid fa-hammer me-1"></i>তৈরি হচ্ছে (In Production)
                                            </span>
                                        @elseif($itemStatus === 'cancelled')
                                            <span class="badge" style="background-color: #fee2e2 !important; color: #991b1b !important; border: 1px solid #fca5a5 !important; font-weight: 700; font-size: 11px;">
                                                <i class="fa-solid fa-xmark me-1"></i>বাতিল (Cancelled)
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($itemStatus) }}</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center">1</td>
                                    <td>
                                        <strong>{{ $order->product_name }}</strong>
                                        @if($order->details)
                                            <small class="text-muted d-block">{{ $order->details }}</small>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark border" style="color: #0f172a !important; font-weight: 600;">{{ $order->category->category_name ?? '-' }}</span>
                                        <span class="badge" style="background-color: #fef3c7 !important; color: #92400e !important; border: 1px solid #f59e0b !important; font-weight: 700; font-size: 11px;">
                                            {{ $order->karat }}
                                        </span>
                                    </td>
                                    <td class="text-center font-weight-bold">{{ $order->quantity }}</td>
                                    <td class="text-center">
                                        {{ $order->target_bhori ?? 0 }} ভরি, {{ $order->target_ana ?? 0 }} আনা, {{ $order->target_roti ?? 0 }} রতি, {{ $order->target_point ?? 0 }} পয়েন্ট
                                        <small class="text-muted d-block">({{ number_format($order->target_gram, 3) }} গ্রাম)</small>
                                    </td>
                                    <td class="text-end text-success font-weight-bold">
                                        ৳ {{ number_format($order->estimated_price, 2) }}
                                    </td>
                                    <td class="text-center text-danger font-weight-bold">
                                        {{ number_format($order->raw_gold_needed, 3) }} গ্রাম
                                        @php
                                            $orderAlloy = max(0, $order->target_gram - $order->raw_gold_needed);
                                        @endphp
                                        @if($orderAlloy > 0)
                                            <small class="text-secondary d-block fw-normal">(খাদ: {{ number_format($orderAlloy, 3) }} গ্রাম)</small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($order->karigor)
                                            <strong>{{ $order->karigor->name }}</strong>
                                            <small class="text-muted d-block">{{ $order->karigor->phone ?? '' }}</small>
                                        @else
                                            <span class="text-muted">অনির্ধারিত</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($order->status === 'delivered')
                                            <span class="badge" style="background-color: #dcfce7 !important; color: #166534 !important; border: 1px solid #86efac !important; font-weight: 700; font-size: 11px;">
                                                <i class="fa-solid fa-check-double me-1"></i>ডেলিভারি সম্পন্ন (Delivered)
                                            </span>
                                        @elseif($order->status === 'ready_for_delivery')
                                            <span class="badge" style="background-color: #e0f2fe !important; color: #0369a1 !important; border: 1px solid #7dd3fc !important; font-weight: 700; font-size: 11px;">
                                                <i class="fa-solid fa-box-open me-1"></i>তৈরি প্রস্তুত (Ready)
                                            </span>
                                        @elseif($order->status === 'in_production')
                                            <span class="badge" style="background-color: #fef9c3 !important; color: #854d0e !important; border: 1px solid #fde047 !important; font-weight: 700; font-size: 11px;">
                                                <i class="fa-solid fa-hammer me-1"></i>তৈরি হচ্ছে (In Production)
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot class="table-light font-weight-bold">
                            <tr>
                                <td colspan="4" class="text-end">সর্বমোট:</td>
                                <td class="text-center text-primary">{{ number_format($order->target_gram, 3) }} গ্রাম</td>
                                <td class="text-end text-success font-weight-bold">৳ {{ number_format($order->estimated_price, 2) }}</td>
                                <td class="text-center text-danger">
                                    {{ number_format($order->raw_gold_needed, 3) }} গ্রাম
                                    @php
                                        $footerAlloy = max(0, $order->target_gram - $order->raw_gold_needed);
                                    @endphp
                                    @if($footerAlloy > 0)
                                        <small class="text-secondary d-block fw-normal">(খাদ: {{ number_format($footerAlloy, 3) }} গ্রাম)</small>
                                    @endif
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Financial Summary -->
            <div class="row justify-content-end mb-4">
                <div class="col-sm-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="text-muted">স্বর্ণের মূল্য:</td>
                            <td class="text-end font-weight-bold">৳ {{ number_format($order->actual_price ?: $order->estimated_price, 2) }}</td>
                        </tr>
                        @if($order->karigor_fee > 0)
                        <tr>
                            <td class="text-muted">কারিগর মজুরি (Fee):</td>
                            <td class="text-end font-weight-bold">৳ {{ number_format($order->karigor_fee, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->discount > 0)
                        <tr>
                            <td class="text-danger">ছাড় (Discount):</td>
                            <td class="text-end text-danger font-weight-bold">-৳ {{ number_format($order->discount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="border-top border-bottom">
                            <td class="fs-6 font-weight-bold text-dark">সর্বমোট বিল (Grand Total):</td>
                            <td class="text-end fs-6 font-weight-bold text-primary">৳ {{ number_format($order->grand_total ?: $order->estimated_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-success font-weight-bold">পরিশোধিত টাকা (Paid):</td>
                            <td class="text-end text-success font-weight-bold">৳ {{ number_format($order->paid_amount, 2) }}</td>
                        </tr>
                        @if($order->due_amount > 0)
                        <tr class="border-top">
                            <td class="text-danger font-weight-bold fs-6">অবশিষ্ট বকেয়া (Due):</td>
                            <td class="text-end text-danger font-weight-bold fs-6">৳ {{ number_format($order->due_amount, 2) }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>

            <!-- Payment Steps History -->
            @if($order->payments->count())
            <div class="mb-4">
                <h6 class="font-weight-bold text-secondary mb-2">কিস্তি ও পেমেন্ট পরিশোধের ইতিহাস:</h6>
                <table class="table table-sm table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">ধাপ</th>
                            <th>তারিখ</th>
                            <th class="text-end">পরিমাণ</th>
                            <th>মাধ্যম</th>
                            <th>রেফারেন্স</th>
                            <th>নোট</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->payments as $p)
                        <tr>
                            <td class="text-center font-weight-bold">#{{ $p->payment_step }}</td>
                            <td>{{ $p->payment_date->format('d M, Y h:i A') }}</td>
                            <td class="text-end font-weight-bold text-success">৳ {{ number_format($p->amount, 2) }}</td>
                            <td><span class="badge bg-light text-dark border">{{ strtoupper($p->payment_method) }}</span></td>
                            <td>{{ $p->transaction_reference ?? '-' }}</td>
                            <td>{{ $p->note ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            <!-- Signatures -->
            <div class="row mt-5 pt-4 text-center">
                <div class="col-sm-6">
                    <div style="border-top: 1px dashed #64748b; width: 60%; margin: 0 auto; padding-top: 6px;" class="small font-weight-bold text-muted">
                        গ্রাহকের স্বাক্ষর
                    </div>
                </div>
                <div class="col-sm-6">
                    <div style="border-top: 1px dashed #64748b; width: 60%; margin: 0 auto; padding-top: 6px;" class="small font-weight-bold text-muted">
                        কর্তৃপক্ষের স্বাক্ষর
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('admin.sell.payment_history_modal')
@endsection
