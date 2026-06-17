@extends('admin.master')
@section('title')
কারিগর
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <h3>কারিগর স্টক</h3>
                    </div>
                    <div>
                        <a href="{{route('karigor.stock')}}" class="btn btn-dark">নতুন কারিগর স্টক তৈরি করুন</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>কারিগর</th>
                        <th>ক্যাটাগরি</th>
                        <th>পরিমাণ</th>
                        <th>পরিমাণ(গ্রাম হিসাব)</th>
                        <th>একশন</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp
                        @forelse ($karigors as $karigor)
                        <tr>
                            <td>
                                <strong>{{ $sl }}</strong>
                            </td>
                            <td class="text-center">
                                <div class="d-flex justify-content-start">
                                    <div>
                                        <img alt="avatar" src="{{ asset('user/' . $karigor->user->image) }}" class="rounded-circle" style="width:40px; height: 40px">
                                    </div>
                                    <div>
                                        {{ $karigor->user->name }}<br><a href="#">{{$karigor->user->phone}}</a>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $karigor->category->category_name ?? 'N/A'}}</td>
                            <td>{{ $karigor->bhori ?? 0}} ভরি, {{ $karigor->ana ?? 0}} আনা, {{ $karigor->roti ?? 0}} রতি, {{ $karigor->point ?? 0}} পয়েন্ট</td>
                            <td>{{ $karigor->gram ?? 0}} গ্রাম</td>
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    <div> 
                                        <a href=""
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="View" data-toggle="modal" data-target="#KarigorModal">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>     
                                    </div>
                                    <div>
                                        <a href="{{ route('karigor.edit', $karigor->id) }}"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <form action="#"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="karigor_id" value="{{ $karigor->id }}">
                                            <button type="submit" class="text-warning btn_custom show_confirm" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Delete">
                                                <i class="fa-solid fa-trash-can fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @php $sl++ @endphp
                        @empty
                        No Data Found!
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </div>

   
@endsection

@push('admin_script')
@include('admin.common.script')
@endpush
