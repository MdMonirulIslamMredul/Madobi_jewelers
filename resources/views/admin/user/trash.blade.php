@extends('admin.master')
@section('title')
    User Trashed list
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
                        <h3>User Trashed list</h3>
                        <a href="{{ route('users.index') }}" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="config-table" class="table display table-striped border no-wrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Last Updated</th>
                                <th>Profile</th>
                                {{-- <th>Role Name</th> --}}
                                <th>User Name</th>
                                <th>User Email</th>
                                {{-- @can('edit-user')
                                    <th>User Status</th>
                                @endcan --}}
                                @can('edit-user')
                                    <th>Actions</th>
                                @endcan
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <strong>{{ $users->firstItem() + $loop->index }}</strong>
                                    </td>
                                    <td>{{ $user->updated_at->format('d-M-Y') }}</td>
                                    <td>
                                        @if ($user->adminProfileImage)
                                            <div class="avatar-container">
                                                <img alt="avatar"
                                                    src="{{ asset($user->adminProfileImage->admin_profile_image) }}"
                                                    class="rounded-circle" style="width:30px; height: 30px">
                                            </div>
                                        @elseif ($user->profile->profileImage??null)
                                            <div class="avatar-container">
                                                <img alt="avatar"
                                                    src="{{ asset($user->profile->profileImage->profile_image??null) }}"
                                                    class="rounded-circle" style="width:30px; height: 30px">
                                            </div>
                                        @else
                                            <div class="avatar-container">
                                                <img alt="avatar" src="{{ asset('profile/default_profile.png') }}"
                                                class="rounded-circle" style="width:30px; height: 30px">
                                            </div>
                                        @endif
                                    </td>
                                    {{-- <td>{{ $user->role->role_name }}</td> --}}
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    @can('edit-user')
                                        {{-- <td>
                                            @if ($user->email != 'admin@admin.com')
                                                <div class="form-check form-switch form-check-inline form-switch-success">
                                                    <input class="form-check-input toggle-class" type="checkbox" role="switch"
                                                        id="{{ $user->id }}" {{ $user->is_active ? 'checked' : '' }}
                                                        data-id="{{ $user->id }}">
                                                </div>
                                            @else
                                                <div class="form-check form-switch form-check-inline form-switch-success">
                                                    <input class="form-check-input" type="checkbox" role="switch" checked>
                                                </div>
                                            @endif
                                        </td> --}}
                                        {{-- <td>
                                            @if ($user->is_active == 1)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-warning">Inactive</span>
                                            @endif
                                        </td> --}}
                                    @endcan
                                    @can('delete-user')
                                    <td class="text-center">
                                        <div class="action-btns d-flex align-items-center">
                                            @can('delete-user')
                                            <div>
                                                <a href="{{ route('users.restore', ['id' => $user->id]) }}"
                                                    class="text-success me-2" data-toggle="tooltip"
                                                    data-placement="top" data-bs-original-title="Restore"><i class="fa-solid fa-store"></i>
                                                </a>
                                            </div>
                                            @endcan
                                            @can('delete-user')
                                            <div>
                                                <form action="{{ route('users.forcedelete', ['id' => $user->id]) }}"
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
    </div>
    
@endsection

@push('admin_script')
@include('admin.common.script')
@endpush
