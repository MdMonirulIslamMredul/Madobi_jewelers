@extends('admin.master')
@section('title')
    User Edit
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">@extends('admin.master')
                    @section('title')
                        User Edit
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
                                            <h3>User Edit Information</h3>
                                            <a href="{{ route('users.index') }}" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i>
                                                Back</a>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form class="form-horizontal" action="{{ route('users.update', $user->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="mb-3 col-md-12">
                                                    <label for="role_id" class="form-label">Select Role</label>
                                                    <select id="defaultSelect" name="role_id"
                                                        class="form-select
                                            @error('role_id')
                                                is-invalid
                                            @enderror">
                                                        @forelse ($roles as $role)
                                                            <option value="{{ $role->id }}"
                                                                @if ($role->id == $user->role_id) selected @endif>{{ $role->role_name }}
                                                            </option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                    @error('role_id')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="basic-icon-default-fullname">User Name</label>
                                                    <div class="input-group">
                                                        <input type="text" name="name"
                                                            class="form-control
                                                @error('name')
                                                    is-invalid
                                                @enderror"
                                                            value="{{ $user->name }}" placeholder="Enter User Name">
                                                        @error('name')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="basic-icon-default-phone">User's Phone</label>
                                                    <div class="input-group">
                                                        <input type="text" name="phone"
                                                            class="form-control
                                            @error('phone')
                                                is-invalid
                                            @enderror"
                                            value="{{ $user->phone }}" placeholder="Enter User Phone">
                                                        @error('phone')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="basic-icon-default-email">User Email</label>
                                                    <div class="input-group">
                                                        <input type="text" name="email"
                                                            class="form-control
                                                @error('email')
                                                    is-invalid
                                                @enderror"
                                                            value="{{ $user->email }}" placeholder="Enter User Email">
                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-password-toggle mb-3 col-md-6">
                                                    <label class="form-label" for="basic-default-password">Password</label>
                                                    <div class="input-group">
                                                        <input type="password" name="password"
                                                            class="form-control @error('password')
                                                    is-invalid
                                                @enderror"
                                                            id="basic-default-password" placeholder=" ············" value=""
                                                            aria-describedby="basic-default-password">
                                                        @error('password')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" name="is_active" type="checkbox" role="switch"
                                                        id="activeStatus" @if ($user->is_active == 1) checked @endif>
                                                    <label class="form-check-label" for="activeStatus">Active/Not</label>
                                                </div>
                                                <div class="table-responsive">
                                                    <button type="submit" class="btn btn-success">Update</button>
                                                </div>
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
                    
                    <div class="d-flex justify-content-between">
                        <h3>User Edit Information</h3>
                        <a href="{{ route('users.index') }}" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Select Role</label>
                            <select id="defaultSelect" name="role_id"
                                class="form-select
                        @error('role_id')
                            is-invalid
                        @enderror">
                                @forelse ($roles as $role)
                                    <option value="{{ $role->id }}"
                                    @if ($role->id == $user->role_id)
                                        selected
                                    @endif>{{ $role->role_name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('role_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">User Name</label>
                            <div class="input-group">
                                <input type="text" name="name"
                                    class="form-control
                            @error('name')
                                is-invalid
                            @enderror"
                                    value="{{ $user->name }}" placeholder="Enter User Name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-email">User's Phone</label>
                            <div class="input-group">
                                <input type="text" name="phone"
                                    class="form-control
                        @error('phone')
                            is-invalid
                        @enderror"
                                    value="{{ old('phone') }}" placeholder="Enter User Phone">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-email">User Email</label>
                            <div class="input-group">
                                <input type="text" name="email"
                                    class="form-control
                            @error('email')
                                is-invalid
                            @enderror"
                                    value="{{ $user->email }}" placeholder="Enter User Email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-password-toggle mb-3">
                            <label class="form-label" for="basic-default-password">Password</label>
                            <div class="input-group">
                                <input type="password" name="password"
                                    class="form-control @error('password')
                                is-invalid
                            @enderror"
                                    id="basic-default-password" placeholder=" ············" value=""
                                    aria-describedby="basic-default-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" name="is_active" type="checkbox" role="switch" id="activeStatus" @if ($user->is_active == 1)
                            checked
                        @endif>
                            <label class="form-check-label" for="activeStatus">Active/Not</label>
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
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
