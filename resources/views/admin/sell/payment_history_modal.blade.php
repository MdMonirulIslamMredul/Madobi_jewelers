{{-- Reusable Payment History & Installment Collection Modal --}}
<div class="modal fade" id="paymentHistoryModal" tabindex="-1" aria-labelledby="paymentHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-2">
                <h5 class="modal-title fs-6" id="paymentHistoryModalLabel">
                    <i class="fa-solid fa-receipt me-2 text-warning"></i>পেমেন্ট ইতিহাস ও কিস্তি হিসাব (<span id="modal_invoice_or_order_no"></span>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-3">
                <!-- Summary Card -->
                <div class="row g-2 mb-3">
                    <div class="col-md-3">
                        <div class="p-2 border rounded bg-light text-center">
                            <small class="text-muted d-block">গ্রাহক</small>
                            <strong class="text-dark" id="modal_cust_name">-</strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-2 border rounded bg-light text-center">
                            <small class="text-muted d-block">মোট বিল</small>
                            <strong class="text-primary fs-6">৳ <span id="modal_grand_total">0.00</span></strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-2 border rounded bg-light text-center">
                            <small class="text-muted d-block">মোট পরিশোধিত</small>
                            <strong class="text-success fs-6">৳ <span id="modal_paid_amount">0.00</span></strong>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-2 border rounded bg-light text-center">
                            <small class="text-muted d-block">বর্তমান বকেয়া</small>
                            <strong class="text-danger fs-6">৳ <span id="modal_due_amount">0.00</span></strong>
                        </div>
                    </div>
                </div>

                <!-- Payment Steps History Table -->
                <h6 class="font-weight-bold text-secondary mb-2">
                    <i class="fa-solid fa-timeline me-1"></i> কিস্তি পরিশোধের বিবরণ (Payment Steps)
                </h6>
                <div class="table-responsive border rounded mb-3">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">ধাপ</th>
                                <th>তারিখ ও সময়</th>
                                <th class="text-end">পরিমাণ (৳)</th>
                                <th>মাধ্যম</th>
                                <th>রেফারেন্স</th>
                                <th>গ্রহণকারী</th>
                                <th>নোট</th>
                            </tr>
                        </thead>
                        <tbody id="modal_payments_tbody">
                            <tr>
                                <td colspan="7" class="text-center py-3 text-muted">লোড হচ্ছে...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Collect Next Installment Section (Visible if Due > 0) -->
                <div id="collect_payment_section" class="border rounded p-3 bg-light d-none">
                    <h6 class="font-weight-bold text-primary mb-3">
                        <i class="fa-solid fa-hand-holding-dollar me-1"></i> পরবর্তী কিস্তি / বকেয়া গ্রহণ করুন
                    </h6>
                    <form action="{{ route('sell-payments.collect') }}" method="POST" id="collectPaymentForm">
                        @csrf
                        <input type="hidden" name="payment_type" id="form_payment_type">
                        <input type="hidden" name="record_id" id="form_record_id">

                        <div class="row g-2">
                            <div class="col-md-5 mb-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small font-weight-bold mb-0">টাকার পরিমাণ (৳) <span class="text-danger">*</span></label>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none small text-primary font-weight-bold" id="full_due_pay_btn" title="সম্পূর্ণ বকেয়া পরিশোধ করতে ক্লিক করুন">
                                        ফুল পেইড
                                    </button>
                                </div>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" step="0.01" min="1" name="amount" id="collect_amount_input" class="form-control font-weight-bold text-end" placeholder="0.00" required>
                                </div>
                                <div id="remaining_due_display_wrap" class="mt-1">
                                    <span id="remaining_due_badge" class="badge bg-light text-dark border font-monospace py-1 px-2 d-inline-block">
                                        <i class="fa-solid fa-calculator me-1 text-muted"></i>অবশিষ্ট বকেয়া থাকবে: ৳ <span id="calc_remaining_due">0.00</span>
                                    </span>
                                </div>
                                <div id="overpay_error_msg" class="text-danger small font-weight-bold mt-1 d-none">
                                    <i class="fa-solid fa-circle-exclamation me-1"></i> টাকার পরিমাণ বর্তমান বকেয়া (৳ <span id="error_max_due">0.00</span>) এর চেয়ে বেশি হতে পারবে না!
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="form-label small font-weight-bold">পেমেন্ট মাধ্যম <span class="text-danger">*</span></label>
                                <select name="payment_method" class="form-select form-select-sm" required>
                                    <option value="cash">নগদ (Cash)</option>
                                    <option value="bkash">বিকাশ (bKash)</option>
                                    <option value="nagad">নগদ (Nagad)</option>
                                    <option value="bank">ব্যাংক (Bank)</option>
                                    <option value="card">কার্ড (Card)</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label small font-weight-bold">ট্রানজেকশন রেফারেন্স</label>
                                <input type="text" name="transaction_reference" class="form-control form-control-sm" placeholder="ব্যাংক/বিকাশ TrxID (ঐচ্ছিক)">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small font-weight-bold">মন্তব্য / নোট</label>
                            <input type="text" name="note" class="form-control form-control-sm" placeholder="যেমন: ২য় কিস্তি পরিশোধ">
                        </div>
                        <div class="text-end mt-2">
                            <button type="submit" class="btn btn-sm btn-success px-3 font-weight-bold">
                                <i class="fa-solid fa-check-circle me-1"></i> পেমেন্ট সংরক্ষণ করুন
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer py-2 bg-white">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>

@push('admin_script')
<script>
    var currentRecordRawDue = 0;

    function openPaymentHistoryModal(type, id) {
        var modal = $('#paymentHistoryModal');
        var tbody = $('#modal_payments_tbody');
        tbody.html('<tr><td colspan="7" class="text-center py-3 text-muted"><i class="fa-solid fa-spinner fa-spin me-1"></i> লোড হচ্ছে...</td></tr>');
        $('#collect_payment_section').addClass('d-none');
        $('#overpay_error_msg').addClass('d-none');
        $('#collect_amount_input').removeClass('is-invalid border-danger');

        $('#form_payment_type').val(type);
        $('#form_record_id').val(id);

        $.ajax({
            url: "{{ route('sell-payments.history') }}",
            type: "GET",
            data: { type: type, id: id },
            success: function(res) {
                if (res.status === 'success') {
                    var d = res.data;
                    currentRecordRawDue = parseFloat(d.raw_due) || 0;

                    $('#modal_invoice_or_order_no').text(d.no);
                    $('#modal_cust_name').text(d.customer_name + ' (' + d.customer_phone + ')');
                    $('#modal_grand_total').text(d.grand_total);
                    $('#modal_paid_amount').text(d.paid_amount);
                    $('#modal_due_amount').text(d.due_amount);

                    tbody.empty();
                    if (d.payments && d.payments.length > 0) {
                        $.each(d.payments, function(i, p) {
                            var tr = $('<tr></tr>');
                            tr.append('<td class="text-center font-weight-bold">#' + p.step + '</td>');
                            tr.append('<td>' + p.date + '</td>');
                            tr.append('<td class="text-end font-weight-bold text-success">৳ ' + p.amount + '</td>');
                            tr.append('<td><span class="badge bg-light text-dark border">' + p.method + '</span></td>');
                            tr.append('<td>' + p.reference + '</td>');
                            tr.append('<td>' + p.receiver + '</td>');
                            tr.append('<td class="small text-muted">' + p.note + '</td>');
                            tbody.append(tr);
                        });
                    } else {
                        tbody.html('<tr><td colspan="7" class="text-center py-3 text-muted">কোনো পেমেন্ট রেকর্ড পাওয়া যায়নি।</td></tr>');
                    }

                    if (currentRecordRawDue > 0) {
                        $('#collect_payment_section').removeClass('d-none');
                        $('#collect_amount_input').attr('max', currentRecordRawDue).val(currentRecordRawDue.toFixed(2));
                        updateRemainingDue();
                    } else {
                        $('#collect_payment_section').addClass('d-none');
                    }

                    modal.modal('show');
                }
            },
            error: function() {
                tbody.html('<tr><td colspan="7" class="text-center text-danger py-3">পেমেন্ট ইতিহাস লোড করতে ব্যর্থ হয়েছে।</td></tr>');
            }
        });
    }

    function updateRemainingDue() {
        var typedVal = $('#collect_amount_input').val();
        var typedAmount = parseFloat(typedVal);
        var submitBtn = $('#collectPaymentForm button[type="submit"]');

        if (isNaN(typedAmount) || typedAmount <= 0) {
            $('#calc_remaining_due').text(currentRecordRawDue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#remaining_due_badge').removeClass('bg-success text-white bg-warning-subtle text-dark border-warning bg-danger').addClass('bg-light text-dark border');
            $('#remaining_due_badge').html('<i class="fa-solid fa-calculator me-1 text-muted"></i>অবশিষ্ট বকেয়া থাকবে: ৳ <strong>' + currentRecordRawDue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong>');
            $('#overpay_error_msg').addClass('d-none');
            $('#collect_amount_input').removeClass('is-invalid border-danger');
            submitBtn.prop('disabled', false);
            return;
        }

        if (typedAmount > currentRecordRawDue + 0.001) {
            // Cannot be bigger than due amount
            var excess = typedAmount - currentRecordRawDue;
            $('#collect_amount_input').addClass('is-invalid border-danger');
            $('#error_max_due').text(currentRecordRawDue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            $('#overpay_error_msg').removeClass('d-none');
            $('#remaining_due_badge').removeClass('bg-success bg-light text-dark bg-warning-subtle border-warning').addClass('bg-danger text-white border-0');
            $('#remaining_due_badge').html('<i class="fa-solid fa-circle-exclamation me-1"></i>বকেয়ার চেয়ে ৳ ' + excess.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' বেশি!');
            submitBtn.prop('disabled', true);
        } else {
            var remainingDue = Math.max(0, currentRecordRawDue - typedAmount);
            $('#collect_amount_input').removeClass('is-invalid border-danger');
            $('#overpay_error_msg').addClass('d-none');
            submitBtn.prop('disabled', false);

            if (remainingDue <= 0.001) {
                $('#remaining_due_badge').removeClass('bg-danger bg-light text-dark bg-warning-subtle border-warning').addClass('bg-success text-white border-0');
                $('#remaining_due_badge').html('<i class="fa-solid fa-circle-check me-1"></i>অবশিষ্ট বকেয়া: ৳ ০.০০ (সম্পূর্ণ পরিশোধ হবে)');
            } else {
                $('#remaining_due_badge').removeClass('bg-danger bg-success text-white bg-light text-dark').addClass('bg-warning-subtle text-dark border border-warning');
                $('#remaining_due_badge').html('<i class="fa-solid fa-clock-rotate-left me-1 text-warning"></i>অবশিষ্ট বকেয়া থাকবে: ৳ <strong>' + remainingDue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + '</strong>');
            }
        }
    }

    $(document).ready(function() {
        // Live calculate remaining due on typing amount
        $(document).on('input keyup change', '#collect_amount_input', function() {
            updateRemainingDue();
        });

        // Click "ফুল পেইড" button to fill full due amount
        $(document).on('click', '#full_due_pay_btn', function(e) {
            e.preventDefault();
            $('#collect_amount_input').val(currentRecordRawDue.toFixed(2)).trigger('input');
        });

        // Form submission safety check
        $(document).on('submit', '#collectPaymentForm', function(e) {
            var typedAmount = parseFloat($('#collect_amount_input').val());
            if (isNaN(typedAmount) || typedAmount <= 0) {
                e.preventDefault();
                alert('অনুগ্রহ করে সঠিক পেমেন্টের পরিমাণ লিখুন।');
                $('#collect_amount_input').focus();
                return false;
            }
            if (typedAmount > currentRecordRawDue + 0.001) {
                e.preventDefault();
                alert('টাকার পরিমাণ বর্তমান বকেয়া (৳ ' + currentRecordRawDue.toFixed(2) + ') এর চেয়ে বেশি হতে পারবে না!');
                $('#collect_amount_input').focus();
                return false;
            }

            var btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-1"></i> সংরক্ষণ হচ্ছে...');
        });
    });
</script>
@endpush
