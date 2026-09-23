{{-- Reusable Customer Picker & Quick Add Component --}}
<div class="card border mb-3 shadow-none bg-light">
    <div class="card-body p-3">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
            <label class="form-label font-weight-bold mb-0 text-dark">
                <i class="fa-solid fa-user-check text-primary me-1"></i> গ্রাহক নির্বাচন (Customer Selection) <span class="text-danger">*</span>
            </label>
            <div class="d-flex gap-1">
                <button type="button" class="btn btn-sm btn-outline-success" id="quick_walkin_customer_btn" title="দ্রুত সাধারণ ক্যাশ ক্রেতা নির্বাচন করুন">
                    <i class="fa-solid fa-user-tag me-1"></i> ক্যাশ গ্রাহক (Walk-in)
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#quickAddCustomerModal">
                    <i class="fa-solid fa-user-plus me-1"></i> নতুন কাস্টমার
                </button>
            </div>
        </div>

        <!-- Hidden input holding selected customer id (no HTML5 required to avoid silent browser blocking) -->
        <input type="hidden" name="customer_id" id="selected_customer_id" value="{{ old('customer_id', $selectedCustomerId ?? '') }}">

        <!-- Prominent Customer Validation Error Alert -->
        <div id="customer_selection_error" class="alert alert-danger py-2 mb-2 d-none small">
            <i class="fa-solid fa-circle-exclamation me-1"></i> <strong>অনুগ্রহ করে বিক্রয়ের জন্য একজন গ্রাহক নির্বাচন করুন!</strong> উপরে নাম বা ফোন দিয়ে খুঁজুন, অথবা <strong>"ক্যাশ গ্রাহক (Walk-in)"</strong> বাটনে ক্লিক করুন।
        </div>

        <!-- Customer Search Bar -->
        <div class="position-relative" id="customer_search_container">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="text" id="customer_search_input" class="form-control" placeholder="গ্রাহকের নাম বা ফোন নম্বর দিয়ে খুঁজুন..." autocomplete="off">
                <button type="button" id="clear_customer_btn" class="btn btn-outline-secondary d-none" title="রিমুভ করুন">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <!-- Dropdown search results -->
            <div id="customer_search_results" class="list-group position-absolute w-100 shadow-lg border rounded-bottom d-none" style="z-index: 1050; max-height: 250px; overflow-y: auto;"></div>
        </div>

        <!-- Selected Customer Card -->
        <div id="selected_customer_card" class="mt-2 p-2 bg-white rounded border d-none">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1 font-weight-bold text-primary" id="card_customer_name"></h6>
                    <div class="small text-muted">
                        <span class="me-3"><i class="fa-solid fa-phone me-1"></i><span id="card_customer_phone"></span></span>
                        <span class="me-3 d-none" id="card_customer_phone2_wrap"><i class="fa-solid fa-phone-volume me-1"></i><span id="card_customer_phone2"></span></span>
                        <span class="me-3 d-none" id="card_customer_email_wrap"><i class="fa-solid fa-envelope me-1"></i><span id="card_customer_email"></span></span>
                    </div>
                    <div class="small text-muted mt-1 d-none" id="card_customer_address_wrap">
                        <i class="fa-solid fa-location-dot me-1"></i><span id="card_customer_address"></span>
                    </div>
                    <div class="small text-secondary mt-1 d-none" id="card_customer_extra_wrap">
                        <i class="fa-solid fa-circle-info me-1"></i><span id="card_customer_extra"></span>
                    </div>
                </div>
                <span class="badge bg-success-subtle text-success border border-success px-2 py-1">নির্বাচিত গ্রাহক</span>
            </div>
        </div>
        @error('customer_id')
            <div class="text-danger small mt-1">অনুগ্রহ করে একজন গ্রাহক নির্বাচন করুন।</div>
        @enderror
    </div>
</div>

<!-- Quick Add Customer Modal -->
<div class="modal fade" id="quickAddCustomerModal" tabindex="-1" aria-labelledby="quickAddCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title fs-6" id="quickAddCustomerModalLabel"><i class="fa-solid fa-user-plus me-2"></i>নতুন গ্রাহক তৈরি করুন</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div id="quickAddCustomerForm">
                @csrf
                <div class="modal-body">
                    <div id="quickCustomerAlert" class="alert alert-danger d-none py-2 small"></div>

                    <div class="row g-2">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small font-weight-bold">নাম (First Name) <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="নাম লিখুন" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small font-weight-bold">পদবী (Last Name)</label>
                            <input type="text" name="last_name" class="form-control form-control-sm" placeholder="পদবী">
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6 mb-2">
                            <label class="form-label small font-weight-bold">ফোন নম্বর <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control form-control-sm" placeholder="017xxxxxxxx" required>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="form-label small font-weight-bold">বিকল্প ফোন (Phone 2)</label>
                            <input type="text" name="phone2" class="form-control form-control-sm" placeholder="ঐচ্ছিক">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small font-weight-bold">ইমেইল (Email)</label>
                        <input type="email" name="email" class="form-control form-control-sm" placeholder="customer@example.com (ঐচ্ছিক)">
                    </div>

                    <div class="mb-2">
                        <label class="form-label small font-weight-bold">ঠিকানা (Address)</label>
                        <textarea name="address" rows="2" class="form-control form-control-sm" placeholder="ঠিকানা লিখুন..."></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small font-weight-bold">অতিরিক্ত তথ্য (Extra Info)</label>
                        <textarea name="extra_info" rows="2" class="form-control form-control-sm" placeholder="এনআইডি, পেশা, রেফারেন্স ইত্যাদি..."></textarea>
                    </div>
                </div>
                <div class="modal-footer py-2 bg-light">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                    <button type="button" id="saveCustomerBtn" class="btn btn-sm btn-primary">
                        <i class="fa-solid fa-check me-1"></i> সংরক্ষণ করুন
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('admin_script')
<script>
    $(document).ready(function() {
        var searchInput = $('#customer_search_input');
        var resultsBox = $('#customer_search_results');
        var customerIdInput = $('#selected_customer_id');
        var customerCard = $('#selected_customer_card');
        var clearBtn = $('#clear_customer_btn');

        function selectCustomer(customer) {
            customerIdInput.val(customer.id);
            searchInput.val(customer.full_name + ' (' + customer.phone + ')');
            searchInput.prop('readonly', true).removeClass('is-invalid border-danger');
            resultsBox.addClass('d-none');
            clearBtn.removeClass('d-none');
            $('#customer_selection_error').addClass('d-none');

            $('#card_customer_name').text(customer.full_name);
            $('#card_customer_phone').text(customer.phone);

            if (customer.phone2) {
                $('#card_customer_phone2').text(customer.phone2);
                $('#card_customer_phone2_wrap').removeClass('d-none');
            } else {
                $('#card_customer_phone2_wrap').addClass('d-none');
            }

            if (customer.email && !customer.email.includes('@madobi.com')) {
                $('#card_customer_email').text(customer.email);
                $('#card_customer_email_wrap').removeClass('d-none');
            } else {
                $('#card_customer_email_wrap').addClass('d-none');
            }

            if (customer.address) {
                $('#card_customer_address').text(customer.address);
                $('#card_customer_address_wrap').removeClass('d-none');
            } else {
                $('#card_customer_address_wrap').addClass('d-none');
            }

            if (customer.extra_info) {
                $('#card_customer_extra').text(customer.extra_info);
                $('#card_customer_extra_wrap').removeClass('d-none');
            } else {
                $('#card_customer_extra_wrap').addClass('d-none');
            }

            customerCard.removeClass('d-none');
        }

        // Quick Walk-in Customer Button
        $('#quick_walkin_customer_btn').on('click', function() {
            var btn = $(this);
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> নির্বাচন হচ্ছে...');
            $.ajax({
                url: "{{ route('customers.walkin') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}" },
                success: function(res) {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-user-tag me-1"></i> ক্যাশ গ্রাহক (Walk-in)');
                    if (res.status === 'success') {
                        selectCustomer(res.data);
                    }
                },
                error: function() {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-user-tag me-1"></i> ক্যাশ গ্রাহক (Walk-in)');
                    alert('ক্যাশ গ্রাহক নির্বাচন করতে সমস্যা হয়েছে।');
                }
            });
        });

        clearBtn.on('click', function() {
            customerIdInput.val('');
            searchInput.val('').prop('readonly', false).focus();
            customerCard.addClass('d-none');
            clearBtn.addClass('d-none');
            resultsBox.addClass('d-none');
        });

        var searchTimeout = null;
        searchInput.on('input focus', function() {
            if (searchInput.prop('readonly')) return;

            var q = $(this).val().trim();
            clearTimeout(searchTimeout);

            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: "{{ route('customers.search') }}",
                    type: "GET",
                    data: { query: q },
                    success: function(res) {
                        resultsBox.empty();
                        if (res.status === 'success' && res.data.length > 0) {
                            $.each(res.data, function(i, item) {
                                var a = $('<a href="javascript:void(0)" class="list-group-item list-group-item-action py-2"></a>');
                                a.html('<strong>' + item.full_name + '</strong> <span class="badge bg-light text-dark border ms-2">' + item.phone + '</span>' + (item.address ? '<br><small class="text-muted">' + item.address + '</small>' : ''));
                                a.on('click', function() {
                                    selectCustomer(item);
                                });
                                resultsBox.append(a);
                            });
                            resultsBox.removeClass('d-none');
                        } else {
                            resultsBox.html('<div class="p-3 text-center text-muted small">কোনো গ্রাহক পাওয়া যায়নি।<br><button type="button" class="btn btn-xs btn-outline-primary mt-1" onclick="$(\'#quickAddCustomerModal\').modal(\'show\')">নতুন যোগ করুন</button></div>');
                            resultsBox.removeClass('d-none');
                        }
                    }
                });
            }, 250);
        });

        // Close search list when clicked outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#customer_search_container').length) {
                resultsBox.addClass('d-none');
            }
        });

        // Quick Add Customer Save Button Click
        $(document).on('click', '#saveCustomerBtn', function(e) {
            e.preventDefault();
            var alertBox = $('#quickCustomerAlert');
            var saveBtn = $('#saveCustomerBtn');

            var name = $('#quickAddCustomerForm input[name="name"]').val().trim();
            var phone = $('#quickAddCustomerForm input[name="phone"]').val().trim();

            if (!name) {
                alertBox.removeClass('d-none').text('গ্রাহকের নাম আবশ্যক।');
                $('#quickAddCustomerForm input[name="name"]').focus();
                return false;
            }
            if (!phone) {
                alertBox.removeClass('d-none').text('গ্রাহকের ফোন নম্বর আবশ্যক।');
                $('#quickAddCustomerForm input[name="phone"]').focus();
                return false;
            }

            alertBox.addClass('d-none').empty();
            saveBtn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> সংরক্ষণ হচ্ছে...');

            $.ajax({
                url: "{{ route('customers.quick_store') }}",
                type: "POST",
                data: $('#quickAddCustomerForm :input').serialize(),
                success: function(res) {
                    saveBtn.prop('disabled', false).html('<i class="fa-solid fa-check me-1"></i> সংরক্ষণ করুন');
                    if (res.status === 'success') {
                        $('#quickAddCustomerModal').modal('hide');
                        $('#quickAddCustomerForm input[type="text"], #quickAddCustomerForm input[type="email"], #quickAddCustomerForm textarea').val('');
                        selectCustomer(res.data);
                    }
                },
                error: function(xhr) {
                    saveBtn.prop('disabled', false).html('<i class="fa-solid fa-check me-1"></i> সংরক্ষণ করুন');
                    alertBox.removeClass('d-none');
                    if (xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        var msg = '';
                        $.each(errors, function(k, v) { msg += v[0] + '<br>'; });
                        alertBox.html(msg);
                    } else {
                        alertBox.text('কাস্টমার তৈরি করতে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।');
                    }
                }
            });
        });
    });
</script>
@endpush
