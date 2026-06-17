@extends('admin.master')
@section('title')
    প্রোফাইল
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
                    <h3 class="text-center">তথ্য আপডেট করুন</h3>
                    <form class="form-horizontal" action="{{route('admin.update.profile')}}" enctype="multipart/form-data" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="form-group">
                            <label>নাম</label>
                            <input type="text" class="form-control" rows="5" name="name" value="{{$user->name}}" id="name" placeholder="নাম">
                        </div>
                        <div class="form-group">
                            <label>ইমেইল</label>
                            <input type="email" class="form-control" rows="5" name="email" value="{{$user->email}}" id="email" placeholder="ইমেইল">
                        </div>

                        <div class="form-group">
                            <label>নিউ পাসওয়ার্ড</label>
                            <input type="text" class="form-control" rows="5" name="new_password" id="youtube" placeholder="নিউ পাসওয়ার্ড">
                        </div>
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-info">আপডেট</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
@endsection
