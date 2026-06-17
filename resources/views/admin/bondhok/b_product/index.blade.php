@extends('admin.master')
@section('title')
    বন্ধক প্রোডাক্ট ক্যাটাগরি
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
                        <h3>বন্ধক প্রোডাক্ট ক্যাটাগরি</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('bondhok.product.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-label mb-2">Name</label>
                            <input type="text" class="form-control @error('name')
                            is-invalid
                        @enderror" rows="5" name="name" value="{{ old('name') }}" id="name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp
                        @forelse ($b_products as $category)
                        <tr>
                            <td>
                                <strong>{{ $sl }}</strong>
                            </td>
                            <td>{{ $category->name }}</td>
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    <div>
                                        <a href=""
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('bondhok.product.edit', $category->id) }}"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <form action="{{ route('bondhok.product.delete') }}"
                                            method="POST">
                                            @csrf
                                            <input type="hidden" name="p_id" value="{{ $category->id }}">
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
