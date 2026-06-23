@extends('admin.master')

@section('title')
প্রোডাক্ট প্রাইস সম্পাদনা
@endsection

@push('admin_style')
{{-- Add any page‑specific CSS here --}}
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">প্রোডাক্ট প্রাইস আপডেট করুন</h3>
                <a href="{{ route('product-price.index') }}" class="btn btn-secondary">পেছনে যান</a>
            </div>
            <div class="card-body">
                <form action="{{ route('product-price.update', $price->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="product_name" class="form-label">প্রোডাক্ট নাম</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ old('product_name', $price->product_name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">দাম (৳)</label>
                        <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', $price->price) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">আপডেট করুন</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin_script')
{{-- Add any page‑specific JS here --}}
@endpush
