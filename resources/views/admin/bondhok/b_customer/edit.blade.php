@extends('admin.master')
@section('title')
    বন্ধক কাস্টমার
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
                        <h3>বন্ধক কাস্টমার</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('bondhok.customer.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="c_id" value="{{$b_customer->id}}">
                            <div class="form-group mb-3 col-6">
                                <label for="name" class="form-label mb-2">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $b_customer->name }}" id="name">
                            </div>
                            <div class="form-group mb-3 col-6">
                                <label for="phone" class="form-label mb-2">Phone</label>
                                <input type="text" class="form-control" name="phone" value="{{ $b_customer->phone }}" id="phone">
                            </div>
                            <div class="form-group mb-3 col-12">
                                <label for="address" class="form-label mb-2">Address</label>
                                <input type="text" class="form-control" name="address" value="{{ $b_customer->address }}" id="address">
                            </div>
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
