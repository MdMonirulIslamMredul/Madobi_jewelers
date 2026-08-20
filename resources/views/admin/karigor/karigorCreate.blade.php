@extends('admin.master')

@section('title')
    নতুন কারিগর তৈরি করুন
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0 text-white"><i class="fa-solid fa-user-plus me-2"></i>নতুন কারিগর তৈরি করুন</h4>
                <a href="{{ route('karigor.index') }}" class="btn btn-sm btn-outline-light">
                    <i class="fa-solid fa-arrow-left me-1"></i>তালিকায় ফিরে যান
                </a>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('karigor.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        {{-- First Name / Name --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="name" class="form-label fw-semibold">নাম <span class="text-danger">*</span></label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="কারিগর নাম লিখুন"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Last Name --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="last_name" class="form-label fw-semibold">পদবি / শেষ নাম</label>
                                <input type="text"
                                       name="last_name"
                                       id="last_name"
                                       class="form-control @error('last_name') is-invalid @enderror"
                                       placeholder="শেষ নাম লিখুন"
                                       value="{{ old('last_name') }}">
                                @error('last_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label fw-semibold">ফোন নম্বর</label>
                                <input type="text"
                                       name="phone"
                                       id="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="০১৭XXXXXXXX"
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="email" class="form-label fw-semibold">ইমেইল (ঐচ্ছিক)</label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="example@gmail.com"
                                       value="{{ old('email') }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label for="address" class="form-label fw-semibold">ঠিকানা (ঐচ্ছিক)</label>
                                <textarea name="address"
                                          id="address"
                                          rows="3"
                                          class="form-control @error('address') is-invalid @enderror"
                                          placeholder="বর্তমান/স্থায়ী ঠিকানা লিখুন">{{ old('address') }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Password (Optional) --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="password" class="form-label fw-semibold">পাসওয়ার্ড (ঐচ্ছিক)</label>
                                <input type="password"
                                       name="password"
                                       id="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="পাসওয়ার্ড না দিলে ডিফল্ট ১২৩৪৫৬৭৮ সেট হবে">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- Image (Optional) --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="image" class="form-label fw-semibold">কারিগর ছবি (ঐচ্ছিক)</label>
                                <input type="file"
                                       name="image"
                                       id="image_input"
                                       class="form-control @error('image') is-invalid @enderror"
                                       accept="image/*">
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="mt-2" id="image_preview_wrap" style="display:none;">
                                    <img id="image_preview" src="" alt="preview" style="width:70px;height:70px;object-fit:cover;border-radius:50%;border:2px solid #0d6efd;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 border-top pt-3 text-end">
                        <a href="{{ route('karigor.index') }}" class="btn btn-secondary me-2">বাতিল</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="fa-solid fa-floppy-disk me-1"></i>সংরক্ষণ করুন
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin_script')
@include('admin.common.script')
<script>
    document.getElementById('image_input')?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                document.getElementById('image_preview').src = evt.target.result;
                document.getElementById('image_preview_wrap').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
