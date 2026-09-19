@extends('admin.master')

@section('title')
প্রোডাক্ট প্রাইস সম্পাদনা
@endsection

@push('admin_style')
{{-- Add any page‑specific CSS here --}}
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-pen-to-square text-primary me-2 fa-lg"></i>
                    <h4 class="card-title mb-0 font-weight-bold">প্রোডাক্ট প্রাইস আপডেট করুন</h4>
                </div>
                <a href="{{ route('product-price.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> ফিরে যান
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('product-price.update', $price->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="product_name" class="form-label font-weight-bold">প্রোডাক্ট নাম <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-tag text-muted"></i></span>
                            <input type="text" class="form-control @error('product_name') is-invalid @enderror" id="product_name" name="product_name" value="{{ old('product_name', $price->product_name) }}" required>
                        </div>
                        @error('product_name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <!-- Buying Price Section -->
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded border">
                                <h6 class="font-weight-bold text-primary mb-3">
                                    <i class="fa-solid fa-cart-shopping me-1"></i> ক্রয় মূল্য সংক্রান্ত
                                </h6>
                                <div class="mb-3">
                                    <label for="buying_price" class="form-label font-weight-bold">প্রতি ভরি ক্রয় মূল্য (৳) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white font-weight-bold">৳</span>
                                        <input type="number" step="0.01" min="0" class="form-control @error('buying_price') is-invalid @enderror" id="buying_price" name="buying_price" value="{{ old('buying_price', $price->buying_price) }}" required>
                                    </div>
                                    <small class="text-muted">প্রতি ভরি মূল্য লিখুন</small>
                                    @error('buying_price')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="buying_price_per_gram" class="form-label font-weight-bold">প্রতি গ্রাম ক্রয় মূল্য (৳) <span class="badge bg-secondary font-weight-normal">স্বয়ংক্রিয়</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white font-weight-bold">৳</span>
                                        <input type="number" step="0.01" min="0" class="form-control bg-white @error('buying_price_per_gram') is-invalid @enderror" id="buying_price_per_gram" name="buying_price_per_gram" value="{{ old('buying_price_per_gram', $price->buying_price_per_gram ?? ($price->buying_price ? round($price->buying_price / 11.664, 2) : '')) }}" readonly>
                                    </div>
                                    <small class="text-info"><i class="fa-solid fa-calculator me-1"></i>১ ভরি ÷ ১১.৬৬৪ গ্রাম</small>
                                    @error('buying_price_per_gram')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Selling Price Section -->
                        <div class="col-md-6 mb-3">
                            <div class="p-3 bg-light rounded border">
                                <h6 class="font-weight-bold text-success mb-3">
                                    <i class="fa-solid fa-tag me-1"></i> বিক্রয় মূল্য সংক্রান্ত
                                </h6>
                                <div class="mb-3">
                                    <label for="selling_price" class="form-label font-weight-bold">প্রতি ভরি বিক্রয় মূল্য (৳) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white font-weight-bold">৳</span>
                                        <input type="number" step="0.01" min="0" class="form-control @error('selling_price') is-invalid @enderror" id="selling_price" name="selling_price" value="{{ old('selling_price', $price->selling_price ?? $price->price) }}" required>
                                    </div>
                                    <small class="text-muted">প্রতি ভরি মূল্য লিখুন</small>
                                    @error('selling_price')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div>
                                    <label for="selling_price_per_gram" class="form-label font-weight-bold">প্রতি গ্রাম বিক্রয় মূল্য (৳) <span class="badge bg-secondary font-weight-normal">স্বয়ংক্রিয়</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white font-weight-bold">৳</span>
                                        <input type="number" step="0.01" min="0" class="form-control bg-white @error('selling_price_per_gram') is-invalid @enderror" id="selling_price_per_gram" name="selling_price_per_gram" value="{{ old('selling_price_per_gram', $price->selling_price_per_gram ?? ($price->selling_price ? round($price->selling_price / 11.664, 2) : ($price->price ? round($price->price / 11.664, 2) : ''))) }}" readonly>
                                    </div>
                                    <small class="text-info"><i class="fa-solid fa-calculator me-1"></i>১ ভরি ÷ ১১.৬৬৪ গ্রাম</small>
                                    @error('selling_price_per_gram')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label for="note" class="form-label font-weight-bold">পরিবর্তনের কারণ বা নোট (ঐচ্ছিক)</label>
                        <input type="text" class="form-control" id="note" name="note" placeholder="যেমন: BAJUS নোটিশ অনুযায়ী রেট সমন্বয়" value="{{ old('note') }}">
                    </div>

                    <div class="mt-4 pt-2 border-top d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 me-2">
                            <i class="fa-solid fa-arrows-rotate me-1"></i> আপডেট করুন
                        </button>
                        <a href="{{ route('product-price.index') }}" class="btn btn-secondary px-4">
                            <i class="fa-solid fa-xmark me-1"></i> বাতিল
                        </a>
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

        function calculatePerGram(voriPrice) {
            var val = parseFloat(voriPrice);
            if (!isNaN(val) && val > 0) {
                return (val / VORI_TO_GRAM).toFixed(2);
            }
            return '';
        }

        $('#buying_price').on('input keyup change', function() {
            var perGram = calculatePerGram($(this).val());
            $('#buying_price_per_gram').val(perGram);
        });

        $('#selling_price').on('input keyup change', function() {
            var perGram = calculatePerGram($(this).val());
            $('#selling_price_per_gram').val(perGram);
        });

        // Trigger on load if initial values present
        if ($('#buying_price').val() && !$('#buying_price_per_gram').val()) {
            $('#buying_price_per_gram').val(calculatePerGram($('#buying_price').val()));
        }
        if ($('#selling_price').val() && !$('#selling_price_per_gram').val()) {
            $('#selling_price_per_gram').val(calculatePerGram($('#selling_price').val()));
        }
    });
</script>
@endpush
