@extends('admin.master')
@section('title')
    Permission Edit
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
                        <h3>Permission Edit</h3>
                        <a href="{{ route('permission.index') }}" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('permission.update', $permission->permission_slug) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="module_id" class="form-label mb-2">Select Module</label>
                            <select id="defaultSelect" name="module_id"
                                class="form-select
                            @error('module_id')
                                is-invalid
                            @enderror">
                                <option selected>Choose a module</option>
                                @forelse ($modules as $module)
                                    <option value="{{ $module->id }}" @if ($permission->module_id == $module->id)
                                        selected
                                    @endif>{{ $module->module_name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('module_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="permission_name" class="form-label mb-2">Permission Name</label>
                            <input type="text" class="form-control @error('permission_name')
                            is-invalid
                        @enderror" rows="5" name="permission_name" value="{{ $permission->permission_name }}" id="permission_name">
                            @error('permission_name')
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
