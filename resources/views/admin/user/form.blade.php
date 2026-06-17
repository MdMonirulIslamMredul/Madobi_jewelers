@php
    $roles = App\Models\Role::whereNotIn('role_slug', ['super_admin', 'admin'])->get();
@endphp
<div class="card mb-3" style="background-color: #d4d4d4;">
    <form id="user_form" class="form-horizontal" action="{{ route('new.user.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="m-3">
            <div class="row">
                <div class="col-md-3 text-center">
                    <div class="col-md-12 mb-3">
                    <label for="profile" class="form-label mb-1">{{ __('Photo :') }}</label>    
                    <div class="d-block position-relative">
                        <img id="previewImage" src="{{ asset('profile/default_profile.png' ) }}" alt="your image" width="100" height="100" onclick="document.getElementById('photoInput').click();" style="cursor: pointer;">
                        <input type="file" id="photoInput" name="profile" class="d-none" onchange="previewPhoto(this)">
                    </div>
                </div>
                    <div class="mb-3 col-md-12 text-center">
                        <label for="photo1" class="form-label mb-1">{{ __('NID/Birth-certificate :') }}</label>    
                        <div class="d-block position-relative">
                            <img id="previewImage1" src="{{ asset('cover/default-cover.jpg' ) }}" alt="your image" width="200" height="120" onclick="document.getElementById('photo1Input').click();" style="cursor: pointer;">
                            <input type="file" id="photo1Input" name="photo1" class="d-none" onchange="previewPhoto1(this)">
                        </div>
                    </div> 
                    
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="role_id" class="form-label">Select Role *</label>
                            <select id="role_id" required name="role_id"
                                class="form-select
                            @error('role_id')
                                is-invalid
                            @enderror">
                                <option value="null">Choose a Role</option>
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
                            <label class="form-label" for="basic-icon-default-fullname">Name *</label>
                            <div class="input-group">
                                <input type="text" name="name" id="user_name"
                                    class="form-control
                        @error('name')
                            is-invalid
                        @enderror"
                                    value="{{ old('name') }}" placeholder="Enter Name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label" for="basic-icon-default-phone">Phone *</label>
                            <div class="input-group">
                                <input type="text" name="phone" id="user_phone"
                                    class="form-control
                        @error('phone')
                            is-invalid
                        @enderror"
                                    value="{{ old('phone') }}" placeholder="Enter Phone Number">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label" for="address">Address:</label>
                            <div class="input-group">
                                <textarea name="address" class="form-control" id="address" cols="100" rows="4" placeholder="Enter address here..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <button type="submit">done</button> --}}
    </form>
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
    function previewPhoto1(input) {
        var preview = document.getElementById('previewImage1');
        var file = input.files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('cover/default-cover.jpg') }}";
        }
    }
</script>
