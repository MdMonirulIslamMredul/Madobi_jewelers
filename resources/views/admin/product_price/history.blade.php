@extends('admin.master')

@section('title')
প্রোডাক্ট প্রাইস পরিবর্তনের ইতিহাস
@endsection

@push('admin_style')
<style>
    .history-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        vertical-align: middle;
        border-bottom: 2px solid #e9ecef;
        font-size: 13px;
    }
    .history-table td {
        vertical-align: middle;
        font-size: 13px;
    }
    .price-badge-buy {
        background-color: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
        font-size: 12px;
    }
    .price-badge-sell {
        background-color: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
        display: inline-block;
        font-size: 12px;
    }
</style>
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-clock-rotate-left text-primary me-2 fa-lg"></i>
                    <div>
                        <h4 class="card-title mb-0 font-weight-bold">প্রোডাক্ট প্রাইস পরিবর্তনের ইতিহাস</h4>
                        <small class="text-muted">সোনার ও ধাতুর রেট পরিবর্তনের সময়ানুক্রমিক রেকর্ড</small>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('product-price.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i> প্রাইস তালিকায় ফিরুন
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filters Section -->
                <form action="{{ route('product-price.history') }}" method="GET" class="row g-2 mb-4 p-3 bg-light rounded border align-items-end">
                    <div class="col-md-3">
                        <label class="form-label small font-weight-bold">প্রোডাক্ট ফিল্টার</label>
                        <select name="product_price_id" class="form-select form-select-sm">
                            <option value="">-- সকল প্রোডাক্ট --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}" {{ request('product_price_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->product_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label small font-weight-bold">নাম দিয়ে খুঁজুন</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="search" class="form-control" placeholder="যেমন: Gold 22k" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small font-weight-bold">শুরুর তারিখ</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label small font-weight-bold">শেষ তারিখ</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-2 d-flex gap-1">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="fa-solid fa-filter me-1"></i> ফিল্টার
                        </button>
                        <a href="{{ route('product-price.history') }}" class="btn btn-secondary btn-sm" title="রিসেট">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    </div>
                </form>

                @if($histories->count())
                <div class="table-responsive">
                    <table class="table table-hover table-bordered history-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th style="width: 150px;">তারিখ ও সময়</th>
                                <th style="width: 160px;">প্রোডাক্টের নাম</th>
                                <th class="text-center" style="width: 170px;">ক্রয় মূল্য (৳)</th>
                                <th class="text-center" style="width: 170px;">বিক্রয় মূল্য (৳)</th>
                                <th class="text-center" style="width: 110px;">পরিবর্তনের ধরণ</th>
                                <th style="width: 120px;">পরিবর্তনকারী</th>
                                <th>নোট / মন্তব্য</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($histories as $index => $history)
                            <tr>
                                <td class="text-center font-weight-bold text-muted">{{ $histories->firstItem() + $index }}</td>
                                <td>
                                    <div class="font-weight-bold text-dark">
                                        {{ $history->effective_date ? $history->effective_date->format('d M, Y') : $history->created_at->format('d M, Y') }}
                                    </div>
                                    <small class="text-muted">
                                        <i class="fa-regular fa-clock me-1"></i>
                                        {{ $history->effective_date ? $history->effective_date->format('h:i:s A') : $history->created_at->format('h:i:s A') }}
                                    </small>
                                </td>
                                <td>
                                    <span class="font-weight-bold text-primary">{{ $history->product_name }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="price-badge-buy text-center w-100">
                                        <div>৳ {{ number_format($history->buying_price, 2) }} <small>/ভরি</small></div>
                                        @if($history->buying_price_per_gram)
                                            <div class="mt-1" style="font-size: 11px; opacity: 0.9;">
                                                ৳ {{ number_format($history->buying_price_per_gram, 2) }} <small>/গ্রাম</small>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="price-badge-sell text-center w-100">
                                        <div>৳ {{ number_format($history->selling_price, 2) }} <small>/ভরি</small></div>
                                        @if($history->selling_price_per_gram)
                                            <div class="mt-1" style="font-size: 11px; opacity: 0.9;">
                                                ৳ {{ number_format($history->selling_price_per_gram, 2) }} <small>/গ্রাম</small>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    @if($history->change_type === 'create' || $history->change_type === 'initial')
                                        <span class="badge bg-info-subtle text-info border border-info px-2 py-1">
                                            <i class="fa-solid fa-plus-circle me-1"></i>নতুন এন্ট্রি
                                        </span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success px-2 py-1">
                                            <i class="fa-solid fa-arrows-rotate me-1"></i>হালনাগাদ
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-dark font-weight-bold" style="font-size: 12.5px;">
                                        <i class="fa-regular fa-user me-1 text-muted"></i>
                                        {{ $history->changer->name ?? 'Admin' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $history->note ?: '-' }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <div class="text-muted small">
                        মোট {{ $histories->total() }} টির মধ্যে {{ $histories->firstItem() }} থেকে {{ $histories->lastItem() }} দেখাচ্ছে
                    </div>
                    <div>
                        {{ $histories->links() }}
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fa-solid fa-clock-rotate-left fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">কোনো প্রাইস হিস্ট্রি রেকর্ড পাওয়া যায়নি।</h5>
                    <p class="text-muted small">কোনো প্রোডাক্ট প্রাইস তৈরি বা আপডেট করা হলে তার ইতিহাস এখানে সংরক্ষিত হবে।</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
