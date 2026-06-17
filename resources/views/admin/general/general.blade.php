@extends('admin.master')

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')

    <div class="row justify-content-center mt-3">
        <div class="">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        General Settings
                    </h3>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <button class="nav-link active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-logo" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false">Logo Settings</button>
                            <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-links" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false">Website Links</button>
                            <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-footer" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false">Footer</button>
                        </div>
                        <div class="w-75 mx-auto">
                        <div class="tab-content" id="v-pills-tabContent">
                            <div class="tab-pane fade show active" id="v-pills-logo" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                                @include('admin.general.general-pages.logo_settings')
                            </div>
                            <div class="tab-pane fade" id="v-pills-links" role="tabpanel" aria-labelledby="v-pills-messages-tab">
                                @include('admin.general.general-pages.website_links')
                            </div>
                            <div class="tab-pane fade" id="v-pills-footer" role="tabpanel" aria-labelledby="v-pills-settings-tab">
                                @include('admin.general.general-pages.footer')
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div> <!-- col -->


    </div> <!-- .row -->

@endsection

@push('admin_script')
@include('admin.common.script')
@endpush