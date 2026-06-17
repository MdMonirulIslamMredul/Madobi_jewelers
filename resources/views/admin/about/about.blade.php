@extends('admin.master')
@section('title')
    About settings
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
                <div class="card-body ">
                    <form class="form-horizontal" action="{{route('store.about')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        @if($about_data!=null)
                        <input type="hidden" value="{{$about_data->id}}" name="id">
                        @endif

                        <h3>About Information</h3>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" rows="5" name="title" id="title" placeholder="About Title" required>
                        </div>
                        <div class="form-group">
                            <label>Mission Title</label>
                            <input type="text" class="form-control" rows="5" name="mission_title" id="title" placeholder="Mission Title" required>
                        </div>
                        <div class="form-group">
                            <label>Mission Details</label>
                            <textarea id="tinymce" class="editor form-control" col="10" row="3" name="mission_details" ></textarea>
                        </div>
                        <div class="form-group">
                            <label>Vission Title</label>
                            <input type="text" class="form-control" rows="5" name="vision_title" id="title" placeholder="Mission Title" required>
                        </div>
                        <div class="form-group">
                            <label>Vission Details</label>
                            <textarea id="tinymce" class="editor form-control" col="10" row="3" name="vision_details" ></textarea>
                        </div>
                        <div class="form-group">
                            <label>MV Photo</label>
                            <input type="file" name="mv_photo" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>About Photo</label>
                            <input type="file" name="about_photo" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Banner Image</label>
                            <input type="file" name="banner_image" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="tinymce" class="editor form-control" col="10" row="3" name="description" ></textarea>
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                    <tr>
                        <th>MV Photo</th>
                        <th>About Photo</th>
                        <th>Banner</th>
                        <th>Title</th>
                        <th>Mission</th>
                        <th>Vission</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($abouts as $about)
                            <tr>
                                <td><img src="{{ asset($about->mv_photo) }}" style="height: 100px"></td>
                                <td><img src="{{ asset($about->about_photo) }}" style="height: 100px"></td>
                                <td><img src="{{ asset($about->banner_image) }}" style="height: 100px"></td>
                                <td>{{ $about->title ?? null }}</td>
                                <td>{{ $about->mission_title ?? null }}</td>
                                <td>{{ $about->vision_title ?? null }}</td>
                                <td>
                                    <a href="{{ route('edit.about',['id'=>$about->id]) }}" class="btn btn-primary btn-sm editProduct">Edit</a>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    
@endsection
