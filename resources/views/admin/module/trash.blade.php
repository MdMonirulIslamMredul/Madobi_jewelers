@extends('admin.master')
@section('title')
    Module Trashed list
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h3>Trashed List</h3>
                    <a href="{{ route('module.index') }}" class="btn btn-info"><i class="fa-solid fa-angles-left fa-fw"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Last Updated</th>
                        <th>Module Name</th>
                        <th>Module Slug</th>
                        @can('delete-module')
                        <th>Actions</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                        @forelse ($modules as $module)
                        <tr>
                            <td>
                                <strong>{{ $modules->firstItem() + $loop->index }}</strong>
                            </td>
                            <td>{{ $module->updated_at->format('d-M-Y') }}</td>
                            <td>{{ $module->module_name }}</td>
                            <td>{{ $module->module_slug }}</td>
                            @can('delete-module')
                            <td class="text-center">
                                <div class="action-btns d-flex align-items-center">
                                    @can('delete-module')
                                    <div>
                                        <a href="{{ route('module.restore', ['module_slug' => $module->module_slug]) }}"
                                            class="text-success me-2" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Restore"><i class="fa-solid fa-store"></i>
                                        </a>
                                    </div>
                                    @endcan
                                    @can('delete-module')
                                    <div>
                                        <form action="{{ route('module.forcedelete', ['module_slug' => $module->module_slug]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger btn_custom show_confirm" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Force Delete">
                                                <i class="fa-solid fa-radiation"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endcan
                                </div>
                            </td>
                            @endcan
                        </tr>
                    @empty
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
