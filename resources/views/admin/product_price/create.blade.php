@extends('admin.master')

@section('title')
প্রোডাক্ট প্রাইস যোগ করুন
@endsection

@push('admin_style')
{{-- page CSS --}}
@endpush

@section('body')
<div class="row mt-2">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title mb-0">নতুন প্রোডাক্ট প্রাইস যুক্ত করুন</h3>
      </div>
      <div class="card-body">
        <form action="{{ route('product-price.store') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label">প্রোডাক্ট নাম</label>
            <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}" required>
          </div>
          <div class="mb-3">
            <label class="form-label">দাম (৳)</label>
            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" required>
          </div>
          <button type="submit" class="btn btn-primary">সংরক্ষণ করুন</button>
          <a href="{{ route('product-price.index') }}" class="btn btn-secondary">বাতিল</a>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('admin_script')
{{-- page JS --}}
@endpush
