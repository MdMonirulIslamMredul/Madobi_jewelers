@extends('admin.master')

@section('title')
নতুন কারিগর মজুরি যোগ করুন
@endsection

@section('body')
<div class="row mt-2">
    <div class="col-lg-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="p-2 bg-warning bg-opacity-10 text-warning rounded me-2">
                        <i class="fa-solid fa-coins fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="card-title mb-0 font-weight-bold text-dark">নতুন কারিগর মজুরি যুক্ত করুন</h4>
                        <small class="text-muted">ক্যাটাগরি ও টাইপ অনুযায়ী ভরি এবং গ্রামভিত্তিক মজুরি হার নির্ধারণ</small>
                    </div>
                </div>
                <a href="{{ route('karigor-mojuri.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fa-solid fa-arrow-left me-1"></i> তালিকায় ফিরে যান
                </a>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('karigor-mojuri.store') }}" method="POST" id="mojuriForm">
                    @csrf

                    <div class="row">
                        <!-- Category Choice List -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-dark">
                                ক্যাটাগরি (Category) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-layer-group text-muted"></i></span>
                                <select name="category_name" id="category_name" class="form-select @error('category_name') is-invalid @enderror" required>
                                    <option value="" disabled selected>ক্যাটাগরি নির্বাচন করুন</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}" {{ old('category_name') == $cat ? 'selected' : '' }}>
                                            {{ $cat }} @if($cat === 'Gold')(স্বর্ণ)@elseif($cat === 'Rupa')(রূপা)@elseif($cat === 'Diamond')(হীরা)@elseif($cat === 'Platinum')(প্লাটিনাম)@endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('category_name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Type Choice List -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label font-weight-bold text-dark">
                                টাইপ / ক্যারেট (Type) <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-award text-muted"></i></span>
                                <select name="type" id="type" class="form-select @error('type') is-invalid @enderror" required>
                                    <option value="" disabled selected>টাইপ নির্বাচন করুন</option>
                                    @foreach($types as $t)
                                        <option value="{{ $t }}" {{ old('type') == $t ? 'selected' : '' }}>
                                            {{ strtoupper($t) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('type')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Price Calculations Box -->
                    <div class="p-3 bg-light rounded border mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold text-primary mb-0">
                                <i class="fa-solid fa-calculator me-1"></i> মজুরি হার হিসাব (Wage Rate Calculation)
                            </h6>
                            <span class="badge bg-white text-secondary border font-monospace">
                                ১ ভরি = ১১.৬৬৪ গ্রাম
                            </span>
                        </div>

                        <div class="row">
                            <!-- Per Vori Tk (User Input) -->
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label font-weight-bold text-dark">
                                    প্রতি ভরি মজুরি (৳) <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white font-weight-bold">৳</span>
                                    <input type="number" step="0.01" min="0" name="per_vori_tk" id="per_vori_tk" 
                                           class="form-control form-control-lg @error('per_vori_tk') is-invalid @enderror" 
                                           placeholder="যেমন: ১৫০০.০০" value="{{ old('per_vori_tk') }}" required autofocus>
                                </div>
                                <small class="text-muted">ভরি হিসেবে কারিগরের মজুরি লিখুন</small>
                                @error('per_vori_tk')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Per Gram Tk (Auto Calculate) -->
                            <div class="col-md-6">
                                <label class="form-label font-weight-bold text-dark">
                                    প্রতি গ্রাম মজুরি (৳) 
                                    <span class="badge bg-info text-white font-weight-normal ms-1">স্বয়ংক্রিয় হিসাব</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white font-weight-bold text-primary">৳</span>
                                    <input type="number" step="0.01" min="0" name="per_gram_tk" id="per_gram_tk" 
                                           class="form-control form-control-lg bg-white font-weight-bold text-primary @error('per_gram_tk') is-invalid @enderror" 
                                           placeholder="0.00" value="{{ old('per_gram_tk') }}" readonly>
                                </div>
                                <small class="text-primary font-weight-bold">
                                    <i class="fa-solid fa-wand-magic-sparkles me-1"></i>সূত্র: প্রতি ভরি মজুরি ÷ ১১.৬৬৪ গ্রাম
                                </small>
                                @error('per_gram_tk')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Live Calculation Preview Card -->
                        <div class="mt-3 p-2 bg-white rounded border d-flex justify-content-between align-items-center" id="calculationPreview" style="display: none !important;">
                            <span class="small text-muted">
                                <i class="fa-solid fa-circle-info text-info me-1"></i>লাইভ প্রিভিউ:
                            </span>
                            <span class="small font-weight-bold text-dark" id="previewText">
                                ০.০০ ৳ / ভরি = ০.০০ ৳ / গ্রাম
                            </span>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('karigor-mojuri.index') }}" class="btn btn-outline-secondary px-4">
                            বাতিল
                        </a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="fa-solid fa-floppy-disk me-1"></i> সংরক্ষণ করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('admin_script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const perVoriInput = document.getElementById('per_vori_tk');
        const perGramInput = document.getElementById('per_gram_tk');
        const previewBox = document.getElementById('calculationPreview');
        const previewText = document.getElementById('previewText');
        const VORI_TO_GRAM = 11.664;

        function calculatePerGram() {
            const voriPrice = parseFloat(perVoriInput.value);
            if (!isNaN(voriPrice) && voriPrice > 0) {
                const gramPrice = (voriPrice / VORI_TO_GRAM).toFixed(2);
                perGramInput.value = gramPrice;
                previewBox.style.setProperty('display', 'flex', 'important');
                previewText.innerHTML = `৳ ${parseFloat(voriPrice).toLocaleString('en-US', {minimumFractionDigits: 2})} / ভরি &nbsp;&rarr;&nbsp; <span class="text-primary">৳ ${parseFloat(gramPrice).toLocaleString('en-US', {minimumFractionDigits: 2})} / গ্রাম</span>`;
            } else {
                perGramInput.value = '';
                previewBox.style.setProperty('display', 'none', 'important');
            }
        }

        perVoriInput.addEventListener('input', calculatePerGram);
        perVoriInput.addEventListener('change', calculatePerGram);

        // Run calculation on page load if old value exists
        if (perVoriInput.value) {
            calculatePerGram();
        }
    });
</script>
@endpush
@endsection
