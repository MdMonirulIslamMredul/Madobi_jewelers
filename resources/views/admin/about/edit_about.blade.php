@extends('admin.master')
@section('title')
    About edit
@endsection
@section('body')
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="card">

                @if(session('message'))
                    <div class="alert alert-success" role="alert">
                        {{session('message')}}
                    </div>
                @endif
                <div class="card-body">
                    <form class="form-horizontal" action="{{route('update.about')}}" enctype="multipart/form-data" method="POST">
                        @csrf

                        <input type="hidden" value="{{$about->id}}" name="id">
                        <h3>Update</h3>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" rows="5" name="title" id="title" placeholder="About Title" required value="{{ $about->title }}">
                        </div>
                        <div class="form-group">
                            <label>Mission Title</label>
                            <input type="text" class="form-control" rows="5" name="mission_title" id="title" placeholder="Mission Title" required value="{{ $about->mission_title }}">
                        </div>
                        <div class="form-group">
                            <label>Mission Details</label>
                            <textarea id="tinymce" class="editor form-control" col="10" row="3" name="mission_details" >{{ $about->mission_details }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Vission Title</label>
                            <input type="text" class="form-control" rows="5" name="vision_title" id="title" placeholder="Mission Title" required value="{{ $about->vision_title }}">
                        </div>
                        <div class="form-group">
                            <label>Vission Details</label>
                            <textarea id="tinymce" class="editor form-control" col="10" row="3" name="vision_details" >{{ $about->vision_details }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>MV Photo</label>
                            <input type="file" name="mv_photo" class="form-control">
                        </div>
                        <div>
                            <img src="{{ asset($about->mv_photo) }}" style="height: 100px">
                        </div>
                        <div class="form-group">
                            <label>About Photo</label>
                            <input type="file" name="about_photo" class="form-control">
                        </div>
                        <div>
                            <td><img src="{{ asset($about->about_photo) }}" style="height: 100px"></td>
                        </div>
                        <div class="form-group">
                            <label>Banner Image</label>
                            <input type="file" name="banner_image" class="form-control">
                        </div>
                        <div>
                            <td><img src="{{ asset($about->banner_image) }}" style="height: 100px"></td>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="tinymce" class="editor form-control" col="10" row="3" name="description" >{{ $about->description }}</textarea>
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
