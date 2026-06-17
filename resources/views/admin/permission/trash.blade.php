@extends('admin.master')
@section('title')
    Permission list
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>Trashed List</h3>
                    <a href="{{ route('permission.index') }}" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Last Updated</th>
                        <th>Module Name</th>
                        <th>Permission Name</th>
                        <th>Permission Slug</th>
                        @can('delete-permission')
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
                            @can('delete-permission')
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    @can('delete-permission')
                                    <div>
                                        <a href="{{ route('permission.restore', ['permission_slug' => $permission->permission_slug]) }}"
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Restore"><i class="fa-solid fa-store"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('delete-permission')
                                    <div>
                                        <form action="{{ route('permission.forcedelete', ['permission_slug' => $permission->permission_slug]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger btn_custom show_confirm" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Force Delete">
                                                <i class="fa-solid fa-radiation"></i>
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
