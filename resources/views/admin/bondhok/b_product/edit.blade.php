@extends('admin.master')
@section('title')
    বন্ধক প্রোডাক্ট ক্যাটাগরি
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
                        <h3>বন্ধক প্রোডাক্ট ক্যাটাগরি</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('bondhok.product.update') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-label mb-2">Name</label>
                            <input type="hidden" name="p_id" value="{{ $b_product->id }}">
                            <input type="text" class="form-control" name="name" value="{{ $b_product->name }}" id="name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-info">Submit</button>
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
