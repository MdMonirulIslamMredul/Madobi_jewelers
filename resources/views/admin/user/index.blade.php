@extends('admin.master')
@section('title')
    User list
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
                        <h3>User Information</h3>
                        <a href="{{ route('users.trash') }}" class="btn btn-warning"><i
                                class="fa-solid fa-trash-can-arrow-up fa-fw"></i> View Trash</a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-2">
                                <label for="image" class="form-label">{{ __('Users Image') }}</label>
                                <div class="d-block position-relative">
                                    <img id="previewImage" src="{{ asset('profile/default_profile.png' ) }}" alt="your image" width="150" height="150" onclick="document.getElementById('photoInput').click();" style="cursor: pointer;">
                                    <input type="file" id="photoInput" name="image" class="file-validation d-none" accept="image/*" onchange="previewPhoto(this)">
                                </div>
                            </div>
                            <div class="mb-3 col-md-10">
                                <label for="role_id" class="form-label">Select Role*</label>
                                <select id="defaultSelect" required name="role_id"
                                    class="form-select
                                @error('role_id')
                                    is-invalid
                                @enderror">
                                    <option selected>Choose a Role</option>
                                    @forelse ($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->role_name }}</option>
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
                                <label class="form-label" for="basic-icon-default-fullname">User's Name</label>
                                <div class="input-group">
                                    <input type="text" name="name"
                                        class="form-control
                            @error('name')
                                is-invalid
                            @enderror"
                                        value="{{ old('name') }}" placeholder="Enter User Name">
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
                                        value="{{ old('phone') }}" placeholder="Enter User Phone">
                                    @error('phone')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label" for="basic-icon-default-email">User's Email</label>
                                <div class="input-group">
                                    <input type="text" name="email"
                                        class="form-control
                            @error('email')
                                is-invalid
                            @enderror"
                                        value="{{ old('email') }}" placeholder="Enter User Email">
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
                                        id="basic-default-password" placeholder=" ············"
                                        value="{{ old('password') }}" aria-describedby="basic-default-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="table-responsive">
                                <button type="submit" class="btn btn-info">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Last Updated</th>
                            <th>Profile</th>
                            <th>Role Name</th>
                            <th>User Name</th>
                            <th>User Phone</th>
                            <th>User Email</th>
                            @can('edit-user')
                                <th>User Status</th>
                            @endcan
                            @can('edit-user')
                                <th>Actions</th>
                            @endcan
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp
                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $sl }}</strong>
                                </td>
                                <td>{{ $user->updated_at->format('d-M-Y') }}</td>
                                <td>
                                    @if ($user->adminProfileImage)
                                        <div class="avatar-container">
                                            <img alt="avatar"
                                                src="{{ asset($user->adminProfileImage->admin_profile_image) }}"
                                                class="rounded-circle" style="width:30px; height: 30px">
                                        </div>
                                    @elseif ($user->profile->profileImage ?? null)
                                        <div class="avatar-container">
                                            <img alt="avatar"
                                                src="{{ asset($user->profile->profileImage->profile_image ?? null) }}"
                                                class="rounded-circle" style="width:30px; height: 30px">
                                        </div>
                                    @elseif ($user->image)
                                        <div class="avatar-container">
                                            <img alt="avatar"
                                                src="{{ asset('user/' . $user->image) }}"
                                                class="rounded-circle" style="width:30px; height: 30px">
                                        </div>
                                        
                                    @else
                                        <div class="avatar-container">
                                            <img alt="avatar" src="{{ asset('profile/default_profile.png') }}"
                                                class="rounded-circle" style="width:30px; height: 30px">
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $user->role->role_name }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->phone ?? 'N/A' }}</td>
                                <td>{{ $user->email ?? 'N/A'}}</td>
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
                                    <td>
                                        @if ($user->is_active == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-warning">Inactive</span>
                                        @endif
                                    </td>
                                @endcan
                                @can('edit-user')
                                    <td class="text-center">
                                        <div class="action-btns d-flex">
                                            @can('edit-user')
                                                <div>
                                                    <a href="{{ route('users.show', $user->id) }}"
                                                        class="action-btn bs-tooltip me-2" data-toggle="tooltip"
                                                        data-placement="top" title="" data-bs-original-title="View">
                                                        <i class="fa-solid fa-eye text-success"></i>
                                                    </a>
                                                </div>
                                            @endcan
                                            @can('edit-user')
                                                @if ($user->email != 'admin@admin.com')
                                                    <div>
                                                        <a href="{{ route('users.edit', $user->id) }}"
                                                            class="action-btn bs-tooltip me-1" data-toggle="tooltip"
                                                            data-placement="top" title="" data-bs-original-title="Edit">
                                                            <i class="fa-regular fa-pen-to-square text-info"></i>
                                                        </a>
                                                    </div>
                                                @endif
                                            @endcan
                                            @can('delete-user')
                                                @if ($user->email != 'admin@admin.com')
                                                    <div>
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="action-btn bs-tooltip btn_custom show_confirm"
                                                                data-toggle="tooltip" data-placement="top" title=""
                                                                data-bs-original-title="Delete"><i
                                                                    class="fa-solid fa-trash-can text-warning"></i></button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endcan
                                        </div>
                                    </td>
                                @endcan
                            </tr>
                            @php $sl++ @endphp
                        @empty
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    
    <script>
        function previewPhoto(input) {
            var preview = document.getElementById('previewImage');
            var file = input.files[0];
            var reader = new FileReader();
    
            reader.onloadend = function () {
                preview.src = reader.result;
            }
    
            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.src = "{{ asset('web/media/avatars/user2.png') }}";
            }
        }
    </script>
@endsection

@push('admin_script')
    @include('admin.common.script')
@endpush
