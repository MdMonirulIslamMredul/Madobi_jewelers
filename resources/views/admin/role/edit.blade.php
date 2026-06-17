@extends('admin.master')
@section('title')
    Role Edit
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
                        <h3>Role Edit</h3>
                        <a href="{{ route('role.index') }}" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('role.update', $role->role_slug) }}" method="POST">
                        @csrf
                        @method('PUT')
                        {{-- <div class="form-group mb-3">
                            <label for="role_name" class="form-label mb-2">Role Name</label>
                            <input type="text" class="form-control @error('role_name')
                            is-invalid
                        @enderror" rows="5" name="role_name" value="{{ old('role_name') }}" id="role_name">
                            @error('role_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-icon-default-fullname">Role Note</label>
                            <div class="input-group input-group-merge">
                                <input type="text" name="role_note"
                                    class="form-control
                                @error('role_note')
                                    is-invalid
                                @enderror"
                                    value="{{ old('role_note') }}" placeholder="Enter Role Note">
                                @error('role_note')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div> --}}
                        <div class="my-3">
                            <strong class="@error('permissions') is-invalid

                            @enderror">Manage Permission for role</strong>
                            @error('permissions')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="saurav">
                                <label class="form-check-label" for="saurav">Select All</label>
                              </div>
                        </div>
                        <div class="mb-3">
                            @foreach ($modules->chunk(3) as $key => $chunks)
                                <div class="row">
                                    @foreach ($chunks as $module)
                                        <div class="col-lg-4 col-sm-6 mb-3">
                                            <h5 class="text-primary">Module: {{ $module->module_name }}</h5>
                                            <div class="mb-3">
                                                @foreach ($module->permissions as $permission)
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="permissions[]" type="checkbox"
                                                            value="{{ $permission->id }}"
                                                            id="{{ $permission->id }}"
                                                            @if (isset($role))
                                                                @foreach ($role->permissions as $rPermission)
                                                                    {{ $rPermission->id == $permission->id ? 'checked' : ''}}
                                                                @endforeach
                                                            @endif>
                                                        <label class="form-check-label"
                                                            for="{{ $permission->id }}">
                                                            {{ $permission->permission_name }} </label>
                                                    </div>
                                                @endforeach </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
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
<script>
    //Listern for click on select all checkbox
    $('#saurav').click(function(event){
        if(this.checked){
            //Loop each checkbox
            $(':checkbox').each(function(){
                this.checked = true;
            })
        }else{
            //Loop each checkbox
            $(':checkbox').each(function(){
                this.checked = false;
            })
        }
    });
</script>
@include('admin.common.script')
@endpush
