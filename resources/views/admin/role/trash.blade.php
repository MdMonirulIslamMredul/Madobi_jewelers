@extends('admin.master')
@section('title')
    Role Trashed List
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>Role Trashed List</h3>
                    <a href="{{ route('role.index') }}" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Last Updated</th>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        @can('delete-role')
                        <th>Actions</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                        <tr>
                            <td>
                                <strong>{{ $roles->firstItem() + $loop->index }}</strong>
                            </td>
                            <td>{{ $role->updated_at->format('d-M-Y') }}</td>
                            <td>{{ $role->role_name }}</td>
                            <td>
                                @foreach ($role->permissions->chunk(3) as $key => $chunks)
                                <div class="row">
                                    <div class="col">
                                        @foreach ($chunks as $permission)
                                            <span class="badge bg-info">{{ $permission->permission_slug }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </td>
                            @can('delete-role')
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    @can('delete-role')
                                    <div>
                                        <a href="{{ route('role.restore', ['role_slug' => $role->role_slug]) }}"
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Restore"><i class="fa-solid fa-store"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('delete-role')
                                    <div>
                                        <form action="{{ route('role.forcedelete', ['role_slug' => $role->role_slug]) }}"
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
