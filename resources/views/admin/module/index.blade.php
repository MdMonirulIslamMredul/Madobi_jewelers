@extends('admin.master')
@section('title')
    Module list
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
                        <h3>Module Information</h3>
                        <a href="{{ route('module.trash') }}" class="btn btn-warning"><i class="fa-solid fa-trash-can-arrow-up fa-fw"></i> View Trash</a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('module.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="module_name" class="form-label mb-2">Module Name</label>
                            <input type="text" class="form-control @error('module_name')
                            is-invalid
                        @enderror" rows="5" name="module_name" value="{{ old('module_name') }}" id="module_name">
                            @error('module_name')
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
                        <th>Module Slug</th>
                        @can('edit-module')
                        <th>Actions</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($modules as $module)
                        <tr>
                            <td>
                                <strong>{{ $modules->firstItem() + $loop->index }}</strong>
                            </td>
                            <td>{{ $module->updated_at->format('d-M-Y') }}</td>
                            <td>{{ $module->module_name }}</td>
                            <td>{{ $module->module_slug }}</td>
                            @can('edit-module')
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    <div>
                                        <a href=""
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    @can('edit-module')
                                    <div>
                                        <a href="{{ route('module.edit', $module->module_slug) }}"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('delete-module')
                                    <div>
                                        <form action="{{ route('module.destroy', $module->module_slug) }}"
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
