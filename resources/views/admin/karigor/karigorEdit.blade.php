@extends('admin.master')
@section('title')
কারিগর ইডিট
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
                        <h3>কারিগর স্টক ইডিট</h3>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-6 text-center">
                                <img alt="avatar" src="{{ asset('user/' . $karigor->user->image) }}" style="width:150px; height: 150px">
                            </div>
                            <div class="col-md-6">
                                <p><span class="info-title">নাম:</span> <span class="info-content">{{ $karigor->user->name ?? 'N/A' }}</span></p>
                                <p><span class="info-title">মোবাইল:</span> <span class="info-content">{{ $karigor->user->phone ?? 'N/A' }}</span></p>
                                <p><span class="info-title">ঠিকানা:</span> <span class="info-content">{{ $karigor->user->address ?? 'N/A' }}</span></p>
                            </div>
                        </div>
                    </div>
                    
                    <form class="form-horizontal" action="{{ route('karigor.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="k_id" value="{{$karigor->id}}">
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="bhori" class="form-label mb-2">ভরি</label>
                                    <input type="number" class="form-control @error('bhori')
                                    is-invalid
                                @enderror" rows="5"  name="bhori" value="{{ $karigor->bhori }}" id="vori">
                                    @error('bhori')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="ana" class="form-label mb-2">আনা</label>
                                    <input type="number" class="form-control @error('ana')
                                    is-invalid
                                @enderror" rows="5" id="ana" name="ana" value="{{ $karigor->ana }}">
                                    @error('ana')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="roti" class="form-label mb-2">রতি</label>
                                    <input type="number" class="form-control @error('roti')
                                    is-invalid
                                @enderror" rows="5" id="roti" name="roti" value="{{ $karigor->roti }}">
                                    @error('roti')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group mb-3">
                                    <label for="point" class="form-label mb-2">পয়েন্ট</label>
                                    <input type="number" class="form-control @error('point')
                                    is-invalid
                                @enderror" rows="5" id="point" name="point" value="{{ $karigor->point }}">
                                    @error('roti')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <div class="form-group mb-3">
                                    <label for="gram" class="form-label mb-2">গ্রাম হিসাব</label>
                                    <input type="number" class="form-control @error('gram')
                                    is-invalid
                                @enderror" rows="5" min="1" id="gram" readonly name="gram" value="{{ $karigor->gram }}">
                                    @error('gram')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                            
                        <div class="table-responsive">
                            <button type="submit" class="btn btn-info">Submit</button>
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
