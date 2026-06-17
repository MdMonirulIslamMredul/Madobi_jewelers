@extends('admin.master')
@section('title')
    Product list
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
                        <h3>Product list Information</h3>
                    </div>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('product.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="category_id" class="form-label mb-2">Select Category</label>
                            <select id="defaultSelect" name="category_id"
                                class="form-select
                            @error('category_id')
                                is-invalid
                            @enderror">
                                <option selected>Choose a Category</option>
                                @forelse ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @empty
                                @endforelse
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="product_name" class="form-label mb-2">Product Name</label>
                            <input type="text" class="form-control @error('product_name')
                            is-invalid
                        @enderror" rows="5" name="product_name" value="{{ old('product_name') }}" id="product_name">
                            @error('product_name')
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
                        <th>Last Updated</th>
                        <th>Category Name</th>
                        <th>Product Name</th>
                        <th>Product Slug</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                        <tr>
                            <td>
                                <strong>{{ $products->firstItem() + $loop->index }}</strong>
                            </td>
                            <td>{{ $product->updated_at->format('d-M-Y') }}</td>
                            <td>{{ $product->productCategory->category_name }}</td>
                            <td>{{ $product->product_name }}</td>
                            <td>{{ $product->product_slug }}</td>
                            <td>
                                @if ($product->is_active == 1)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-warning">Inactive</span>
                                @endif
                            </td>
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
                                        <a href="{{ route('product.edit', $product->product_slug) }}"
                                            class="text-info" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Edit"><i class="fa-solid fa-pen-to-square fa-fw"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <form action="{{ route('product.destroy', $product->product_slug) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-warning btn_custom show_confirm" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Delete">
                                                <i class="fa-solid fa-trash-can fa-fw"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
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
