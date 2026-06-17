@extends('admin.master')
@section('title')
    Permission list
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
                        <h3>Permission Information</h3>
                        <a href="{{ route('permission.trash') }}" class="btn btn-warning"><i class="fa-solid fa-trash-can-arrow-up fa-fw"></i> View Trash</a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('permission.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="module_id" class="form-label mb-2">Select Module</label>
                            <select id="defaultSelect" name="module_id"
                                class="form-select
                            @error('module_id')
                                is-invalid
                            @enderror">
                                <option selected>Choose a module</option>
                                @forelse ($modules as $module)
                                    <option value="{{ $module->id }}">{{ $module->module_name }}</option>
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
                        @enderror" rows="5" name="permission_name" value="{{ old('permission_name') }}" id="permission_name">
                            @error('permission_name')
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

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Last Updated</th>
                        <th>Module Name</th>
                        <th>Permission Name</th>
                        <th>Permission Slug</th>
                        @can('edit-permission')
                        <th>Actions</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($permissions as $permission)
                        <tr>
                            <td>
                                <strong>{{ $permissions->firstItem() + $loop->index }}</strong>
                            </td>
                            <td>{{ $permission->updated_at->format('d-M-Y') }}</td>
                            <td>{{ $permission->module->module_name }}</td>
                            <td>{{ $permission->permission_name }}</td>
                            <td>{{ $permission->permission_slug }}</td>
                            @can('edit-permission')
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    <div>
                                        <a href=""
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    @can('edit-permission')
                                    <div>
                                        <a href="{{ route('permission.edit', $permission->permission_slug) }}"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('delete-permission')
                                    <div>
                                        <form action="{{ route('permission.destroy', $permission->permission_slug) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-warning btn_custom show_confirm" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Delete">
                                                <i class="fa-solid fa-trash-can fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
                                </div>
                            </td>
                            @endcan
                        </tr>
                    @empty
                    @endforelse

                    </tbody>

                </table>
            </div>
        </div>
    </div>
    
@endsection

@push('admin_script')
    @include('admin.common.script')
@endpush
