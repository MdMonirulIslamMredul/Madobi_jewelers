@extends('admin.master')
@section('title')
    Edit Module
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h3>Edit Module</h3>
                            <a href="{{ route('module.index') }}" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>
                        </div>
                    </div>
                    <form class="form-horizontal" action="{{ route('module.update', $module->module_slug) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <h3>Update Module</h3>
                        <div class="form-group">
                            <label for="module_name" class="form-label mb-2">Module Name</label>
                            <input type="text" class="form-control @error('module_name')
                            is-invalid
                        @enderror" rows="5" name="module_name" value="{{ $module->module_name }}" id="module_name" placeholder="Module Name">
                            @error('module_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
