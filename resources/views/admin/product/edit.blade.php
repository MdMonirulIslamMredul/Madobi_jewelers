@extends('admin.master')
@section('title')
    Product list Edit
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h3>Product list Update</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('product.update', $product->product_slug) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="category_id" class="form-label mb-2">Select Category</label>
                            <select id="defaultSelect" name="category_id"
                                class="form-select
                            @error('category_id')
                                is-invalid
                            @enderror">
                                @forelse ($categories as $category)
                                    <option value="{{ $category->id }}" @if ($product->category_id == $category->id)
                                        selected
                                    @endif>{{ $category->category_name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="product_name" class="form-label mb-2">Product Name</label>
                            <input type="text" class="form-control @error('product_name')
                            is-invalid
                        @enderror" rows="5" name="product_name" value="{{ $product->product_name }}" id="product_name">
                            @error('product_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" name="is_active" type="checkbox" role="switch" id="activeStatus" @if ($product->is_active == 1)
                            checked
                        @endif>
                            <label class="form-check-label" for="activeStatus">Active/Not</label>
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('admin_script')
@include('admin.common.script')
@endpush
