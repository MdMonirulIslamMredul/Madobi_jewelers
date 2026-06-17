@extends('admin.master')
@section('title')
    User Details
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
<div class="row">
    <div class="col-md-12">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center pb-3">
                <h2>User Details</h2>
                <a href="{{ route('users.index') }}" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>

            </div>
            <div class="widget-content widget-content-area br-8">
                <table class="table table-striped table-bordered table-hover">
                    <tbody>
                        <tr><th colspan="2"><h3>{{ $user->role->role_name }}</h3></th></tr>
                        <tr>
                            <th>Profile</th>
                            <td>
                                {{-- @dd($user->adminProfileImage); --}}
                                @if ($user->adminProfileImage)
                                    <div class="avatar-container">
                                        <img alt="avatar"
                                            src="{{ asset($user->adminProfileImage->admin_profile_image) }}"
                                            class="img-fluid" style="width:150px; height: 150px">
                                    </div>
                                @elseif ($user->profile->profileImage??null)
                                    <div class="avatar-container">
                                        <img alt="avatar"
                                            src="{{ asset($user->profile->profileImage->profile_image??null) }}"
                                            class="img-fluid" style="width:150px; height: 150px">
                                    </div>
                                @else
                                    <div class="avatar-container">
                                        <img alt="avatar" src="{{ asset('profile/default_profile.png') }}"
                                        class="img-fluid" style="width:150px; height: 150px">
                                    </div>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($user->is_active == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Created Date</th>
                            <td>{{ $user->created_at->format('d-M-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Last Updated</th>
                            <td>{{ $user->updated_at->format('d-M-Y') }}</td>
                        </tr>
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
