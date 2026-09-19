@extends('admin.master')

@section('title')
প্রোডাক্ট প্রাইস লিস্ট
@endsection

@push('admin_style')
<style>
    .product-price-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        vertical-align: middle;
        border-bottom: 2px solid #e9ecef;
    }
    .product-price-table td {
        vertical-align: middle;
    }
    .action-btn-group {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-action-edit {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background-color: #0284c7;
        border: 1px solid #0284c7;
        color: #fff;
        transition: all 0.2s ease;
    }
    .btn-action-edit:hover {
        background-color: #0369a1;
        border-color: #0369a1;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(2, 132, 199, 0.3);
    }
    .btn-action-delete {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background-color: #ef4444;
        border: 1px solid #ef4444;
        color: #fff;
        transition: all 0.2s ease;
    }
    .btn-action-delete:hover {
        background-color: #dc2626;
        border-color: #dc2626;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(239, 68, 68, 0.3);
    }
    .btn-action-history {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background-color: #4f46e5;
        border: 1px solid #4f46e5;
        color: #fff;
        transition: all 0.2s ease;
    }
    .btn-action-history:hover {
        background-color: #4338ca;
        border-color: #4338ca;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(79, 70, 229, 0.3);
    }
    .price-badge-buy {
        background-color: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        font-size: 13px;
    }
    .price-badge-sell {
        background-color: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        font-size: 13px;
    }
</style>
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-tags text-primary me-2 fa-lg"></i>
                    <h4 class="card-title mb-0 font-weight-bold">প্রোডাক্ট প্রাইস তালিকা</h4>
                    <span class="badge bg-light text-dark ms-2 border px-2 py-1">{{ $prices->total() }} টি রেকর্ড</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('product-price.history') }}" class="btn btn-outline-primary d-inline-flex align-items-center">
                        <i class="fa-solid fa-clock-rotate-left me-1"></i> প্রাইস হিস্ট্রি
                    </a>
                    <a href="{{ route('product-price.create') }}" class="btn btn-primary d-inline-flex align-items-center">
                        <i class="fa-solid fa-plus me-1"></i> নতুন যোগ করুন
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($prices->count())
                <div class="table-responsive">
                    <table class="table table-hover table-bordered product-price-table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;">#</th>
                                <th>প্রোডাক্ট নাম</th>
                                <th class="text-center" style="width: 180px;">ক্রয় মূল্য (৳)</th>
                                <th class="text-center" style="width: 180px;">বিক্রয় মূল্য (৳)</th>
                                <th class="text-center" style="width: 140px;">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prices as $index => $price)
                            <tr>
                                <td class="text-center font-weight-bold text-muted">{{ $prices->firstItem() + $index }}</td>
                                <td>
                                    <span class="font-weight-bold text-dark">{{ $price->product_name }}</span>
                                    @if(isset($price->histories_count) && $price->histories_count > 0)
                                        <span class="badge bg-light text-muted border font-monospace ms-1" style="font-size: 10px;">
                                            {{ $price->histories_count }} টি আপডেট
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="price-badge-buy text-center">
                                        <div>৳ {{ $price->buying_price !== null ? number_format($price->buying_price, 2) : '-' }} <small style="font-size: 11px; opacity: 0.85;">/ভরি</small></div>
                                        @if($price->buying_price_per_gram || $price->buying_price)
                                            <div class="mt-1" style="font-size: 11.5px; opacity: 0.9;">
                                                ৳ {{ number_format($price->buying_price_per_gram ?? ($price->buying_price / 11.664), 2) }} <small>/গ্রাম</small>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="price-badge-sell text-center">
                                        <div>৳ {{ $price->selling_price !== null ? number_format($price->selling_price, 2) : ($price->price !== null ? number_format($price->price, 2) : '-') }} <small style="font-size: 11px; opacity: 0.85;">/ভরি</small></div>
                                        @if($price->selling_price_per_gram || $price->selling_price || $price->price)
                                            <div class="mt-1" style="font-size: 11.5px; opacity: 0.9;">
                                                ৳ {{ number_format($price->selling_price_per_gram ?? (($price->selling_price ?? $price->price) / 11.664), 2) }} <small>/গ্রাম</small>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="action-btn-group">
                                        <button type="button" class="btn-action-history" onclick="openPriceHistoryModal({{ $price->id }}, '{{ addslashes($price->product_name) }}')" title="প্রাইস হিস্ট্রি দেখুন" data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-clock-rotate-left"></i>
                                        </button>
                                        <a href="{{ route('product-price.edit', $price->id) }}" class="btn-action-edit" title="সম্পাদনা করুন" data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('product-price.destroy', $price->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই রেকর্ডটি মুছে ফেলতে চান?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action-delete" title="মুছে ফেলুন" data-bs-toggle="tooltip">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                    <div class="text-muted small">
                        মোট {{ $prices->total() }} টির মধ্যে {{ $prices->firstItem() }} থেকে {{ $prices->lastItem() }} দেখাচ্ছে
                    </div>
                    <div>
                        {{ $prices->links() }}
                    </div>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="fa-solid fa-boxes-stacked fa-3x text-muted mb-3 d-block"></i>
                    <h5 class="text-muted">কোনো প্রোডাক্ট প্রাইস রেকর্ড পাওয়া যায়নি।</h5>
                    <a href="{{ route('product-price.create') }}" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fa-solid fa-plus me-1"></i> প্রথম রেকর্ড যোগ করুন
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Price History Quick Modal -->
<div class="modal fade" id="priceHistoryQuickModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title font-weight-bold" id="quickModalTitle">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i> মূল্য পরিবর্তনের ইতিহাস
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded border">
                    <div>
                        <span class="text-muted small">প্রোডাক্ট:</span>
                        <strong id="modal_product_name" class="text-primary font-monospace ms-1 fs-6"></strong>
                    </div>
                    <div>
                        <a href="#" id="modal_full_history_link" class="btn btn-sm btn-outline-primary">
                            <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> সম্পূর্ণ হিস্ট্রি পেজ
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 40px;">#</th>
                                <th>তারিখ ও সময়</th>
                                <th class="text-center">ক্রয় মূল্য (৳/ভরি)</th>
                                <th class="text-center">বিক্রয় মূল্য (৳/ভরি)</th>
                                <th class="text-center">ধরণ</th>
                                <th>পরিবর্তনকারী</th>
                                <th>নোট</th>
                            </tr>
                        </thead>
                        <tbody id="quickModalTableBody">
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">
                                    <i class="fa-solid fa-spinner fa-spin me-1"></i> লোড হচ্ছে...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin_script')
<script>
    // Initialize tooltips
    $(function () {
        if (typeof $('[data-bs-toggle="tooltip"]').tooltip === 'function') {
            $('[data-bs-toggle="tooltip"]').tooltip();
        } else if (typeof $('[data-toggle="tooltip"]').tooltip === 'function') {
            $('[data-toggle="tooltip"]').tooltip();
        }
    });

    function openPriceHistoryModal(productId, productName) {
        var modal = $('#priceHistoryQuickModal');
        var tbody = $('#quickModalTableBody');
        $('#modal_product_name').text(productName);
        $('#modal_full_history_link').attr('href', "{{ route('product-price.history') }}?product_price_id=" + productId);

        tbody.html('<tr><td colspan="7" class="text-center py-3 text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> ইতিহাস লোড হচ্ছে...</td></tr>');
        modal.modal('show');

        $.ajax({
            url: "{{ url('admin/product-prices') }}/" + productId + "/history-json",
            type: "GET",
            success: function(res) {
                if (res.status === 'success' && res.data.length > 0) {
                    tbody.empty();
                    $.each(res.data, function(i, h) {
                        var badge = h.change_type === 'create' || h.change_type === 'initial'
                            ? '<span class="badge bg-info-subtle text-info border border-info">নতুন</span>'
                            : '<span class="badge bg-success-subtle text-success border border-success">হালনাগাদ</span>';

                        var tr = $('<tr></tr>');
                        tr.append('<td class="text-center text-muted font-weight-bold">' + (i + 1) + '</td>');
                        tr.append('<td><div class="font-weight-bold">' + h.effective_date + '</div></td>');
                        tr.append('<td class="text-center text-primary font-weight-bold">৳ ' + h.buying_price + '<small class="d-block text-muted" style="font-size:10px;">৳ ' + h.buying_price_per_gram + '/গ্রাম</small></td>');
                        tr.append('<td class="text-center text-success font-weight-bold">৳ ' + h.selling_price + '<small class="d-block text-muted" style="font-size:10px;">৳ ' + h.selling_price_per_gram + '/গ্রাম</small></td>');
                        tr.append('<td class="text-center">' + badge + '</td>');
                        tr.append('<td><small class="font-weight-bold">' + h.changed_by + '</small></td>');
                        tr.append('<td><small class="text-muted">' + h.note + '</small></td>');
                        tbody.append(tr);
                    });
                } else {
                    tbody.html('<tr><td colspan="7" class="text-center py-3 text-muted">কোনো ইতিহাস রেকর্ড পাওয়া যায়নি।</td></tr>');
                }
            },
            error: function() {
                tbody.html('<tr><td colspan="7" class="text-center text-danger py-3">ইতিহাস লোড করতে ত্রুটি হয়েছে।</td></tr>');
            }
        });
    }
</script>
@endpush