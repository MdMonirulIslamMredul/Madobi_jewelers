@extends('admin.master')
@section('title')
    Profile Image
@endsection
@section('body')
    <div class="row mt-2">
        <div class="col-lg-12 ">
            <div class="card mt-3">
                @if(session('message'))
                    <div class="alert alert-success" role="alert">
                        {{session('message')}}
                    </div>
                @endif
                <div class="card-body">
                    <h3 class="text-center">Update Profile Image</h3>
                    @if (!$adminProfileImage)
                    <form class="form-horizontal" action="{{route('admin.store.profile')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Profile Image</label>
                            <input type="file" name="admin_profile_image"  class="form-control">
                            @if ($adminProfileImage)
                                <img src="{{ asset($adminProfileImage->admin_profile_image??null) }}" style="height: 200px">
                            @else
                                <img src="{{ asset('profile/default_profile.png') }}" style="height: 200px">
                            @endif
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                    @else
                    <form class="form-horizontal" action="{{route('admin.update.profile')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$adminProfileImage->id}}">
                        <div class="form-group">
                            <label>Profile Image</label>
                            <input type="file" name="admin_profile_image"  class="form-control">
                            @if ($adminProfileImage)
                                <img src="{{ asset($adminProfileImage->admin_profile_image??null) }}" class="img-fluid" style="height: 200px">
                            @else
                                <img src="{{ asset('profile/default_profile.png') }}" class="img-fluid" style="height: 200px">
                            @endif
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-info">Update</button>
                        </div>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
