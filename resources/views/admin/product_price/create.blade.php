@extends('admin.master')

@section('title')
প্রোডাক্ট প্রাইস যোগ করুন
@endsection

@push('admin_style')
{{-- page CSS --}}
@endpush

@section('body')
<div class="row mt-2">
  <div class="col-lg-8 mx-auto">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
          <i class="fa-solid fa-circle-plus text-primary me-2 fa-lg"></i>
          <h4 class="card-title mb-0 font-weight-bold">নতুন প্রোডাক্ট প্রাইস যুক্ত করুন</h4>
        </div>
        <a href="{{ route('product-price.index') }}" class="btn btn-outline-secondary btn-sm">
          <i class="fa-solid fa-arrow-left me-1"></i> ফিরে যান
        </a>
      </div>
      <div class="card-body p-4">
        <form action="{{ route('product-price.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label font-weight-bold">প্রোডাক্ট নাম <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text bg-light"><i class="fa-solid fa-tag text-muted"></i></span>
              <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror" placeholder="যেমন: 22K Gold Bar" value="{{ old('product_name') }}" required>
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
                  <label class="form-label font-weight-bold">প্রতি ভরি ক্রয় মূল্য (৳) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text bg-white font-weight-bold">৳</span>
                    <input type="number" step="0.01" min="0" name="buying_price" id="buying_price" class="form-control @error('buying_price') is-invalid @enderror" placeholder="0.00" value="{{ old('buying_price') }}" required>
                  </div>
                  <small class="text-muted">প্রতি ভরি মূল্য লিখুন</small>
                  @error('buying_price')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <div>
                  <label class="form-label font-weight-bold">প্রতি গ্রাম ক্রয় মূল্য (৳) <span class="badge bg-secondary font-weight-normal">স্বয়ংক্রিয়</span></label>
                  <div class="input-group">
                    <span class="input-group-text bg-white font-weight-bold">৳</span>
                    <input type="number" step="0.01" min="0" name="buying_price_per_gram" id="buying_price_per_gram" class="form-control bg-white @error('buying_price_per_gram') is-invalid @enderror" placeholder="0.00" value="{{ old('buying_price_per_gram') }}" readonly>
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
                  <label class="form-label font-weight-bold">প্রতি ভরি বিক্রয় মূল্য (৳) <span class="text-danger">*</span></label>
                  <div class="input-group">
                    <span class="input-group-text bg-white font-weight-bold">৳</span>
                    <input type="number" step="0.01" min="0" name="selling_price" id="selling_price" class="form-control @error('selling_price') is-invalid @enderror" placeholder="0.00" value="{{ old('selling_price') }}" required>
                  </div>
                  <small class="text-muted">প্রতি ভরি মূল্য লিখুন</small>
                  @error('selling_price')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <div>
                  <label class="form-label font-weight-bold">প্রতি গ্রাম বিক্রয় মূল্য (৳) <span class="badge bg-secondary font-weight-normal">স্বয়ংক্রিয়</span></label>
                  <div class="input-group">
                    <span class="input-group-text bg-white font-weight-bold">৳</span>
                    <input type="number" step="0.01" min="0" name="selling_price_per_gram" id="selling_price_per_gram" class="form-control bg-white @error('selling_price_per_gram') is-invalid @enderror" placeholder="0.00" value="{{ old('selling_price_per_gram') }}" readonly>
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
            <input type="text" class="form-control" id="note" name="note" placeholder="যেমন: নতুন রেট নির্ধারণ বা BAJUS বিজ্ঞপ্তি অনুযায়ী" value="{{ old('note') }}">
          </div>

          <div class="mt-4 pt-2 border-top d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4 me-2">
              <i class="fa-solid fa-floppy-disk me-1"></i> সংরক্ষণ করুন
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
        if ($('#buying_price').val()) {
            $('#buying_price_per_gram').val(calculatePerGram($('#buying_price').val()));
        }
        if ($('#selling_price').val()) {
            $('#selling_price_per_gram').val(calculatePerGram($('#selling_price').val()));
        }
    });
</script>
@endpush
