@extends('admin.master')
@section('title')
    কারিগর
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush

@section('body')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3>কারিগর তালিকা</h3>
                    </div>
                    <div>
                        <a href="{{route('karigor.create')}}" class="btn btn-dark"><i class="fa-solid fa-plus me-1"></i>নতুন কারিগর তৈরি করুন</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="config-table" class="table display table-striped border no-wrap">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>কারিগর</th>
                                <th>ফোন নম্বর</th>
                                <th>ইমেইল</th>
                                <th>ঠিকানা</th>
                                <th class="text-center">একশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $sl = 1; @endphp
                            @forelse ($users as $user)
                            <tr>
                                <td>
                                    <strong>{{ $sl }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img alt="avatar" src="{{ ($user->image && $user->image !== 'default_user.jpg') ? asset('user/' . $user->image) : asset('cover/default_user.jpg') }}" class="rounded-circle" style="width:40px; height:40px; object-fit:cover;">
                                        <div>
                                            <div class="fw-bold">{{ $user->name ?? 'N/A' }} {{ $user->last_name ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $user->phone ?? '—' }}</td>
                                <td>{{ $user->email ?? '—' }}</td>
                                <td>{{ $user->address ?? '—' }}</td>
                                <td class="text-center">
                                    <div class="action-btns d-flex align-items-center justify-content-center gap-2">
                                        <a href="{{ route('karigor.edit', $user->id) }}"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" title="ইডিট করুন">
                                            <i class="fa-solid fa-pen-to-square fa-fw fs-5"></i>
                                        </a>
                                        <form action="{{ route('karigor.delete') }}" method="POST" onsubmit="return confirm('আপনি কি সত্যিই এই কারিগর মুছে ফেলতে চান?');">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <button type="submit" class="btn btn-link text-danger p-0 border-0" title="মুছে ফেলুন">
                                                <i class="fa-solid fa-trash-can fa-fw fs-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @php $sl++ @endphp
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">কোন কারিগর পাওয়া যায়নি!</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('admin_script')
@include('admin.common.script')
@endpush
