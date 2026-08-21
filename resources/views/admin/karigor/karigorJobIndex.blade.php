@extends('admin.master')
@section('title')
    অ্যাসাইন করা কারিগর জব
@endsection
@push('admin_style')
    @include('admin.common.style')
@endpush
@section('body')
    <div class="row mt-2">
        <div class="col-lg-12">
            
            {{-- Header Card --}}
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h3 class="m-0 text-dark fw-bold">
                            <i class="fa-solid fa-user-gear me-2 text-primary"></i>অ্যাসাইন করা কারিগর জব তালিকা
                        </h3>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('karigor.stock') }}" class="btn btn-outline-primary btn-sm fw-semibold">
                                <i class="fa-solid fa-boxes-stacked me-1"></i> কারিগর স্টক তালিকা
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Summary Stats Row --}}
                <div class="card-body bg-light border-top p-3">
                    <div class="row g-3">
                        <div class="col-md-2 col-sm-6">
                            <div class="p-3 bg-white rounded-3 border text-center shadow-sm">
                                <div class="text-muted small fw-bold mb-1">মোট জব</div>
                                <div class="fs-4 fw-bold text-dark">{{ number_format($totalJobs) }}</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="p-3 bg-white rounded-3 border border-warning text-center shadow-sm">
                                <div class="text-muted small fw-bold mb-1"><i class="fa-solid fa-spinner fa-spin text-warning me-1"></i>চলমান জব</div>
                                <div class="fs-4 fw-bold text-warning">{{ number_format($inProgressJobs) }}</div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6">
                            <div class="p-3 bg-white rounded-3 border border-success text-center shadow-sm">
                                <div class="text-muted small fw-bold mb-1"><i class="fa-solid fa-circle-check text-success me-1"></i>সম্পন্ন জব</div>
                                <div class="fs-4 fw-bold text-success">{{ number_format($completedJobs) }}</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-3 bg-white rounded-3 border text-center shadow-sm">
                                <div class="text-muted small fw-bold mb-1"><i class="fa-solid fa-weight-hanging text-primary me-1"></i>মোট প্রদত্ত কাঁচা সোনা</div>
                                <div class="fs-5 fw-bold text-primary">{{ number_format($totalGivenRawGold, 3) }} গ্রাম</div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="p-3 bg-white rounded-3 border text-center shadow-sm">
                                <div class="text-muted small fw-bold mb-1"><i class="fa-solid fa-coins text-success me-1"></i>মোট ফেরত কাঁচা সোনা</div>
                                <div class="fs-5 fw-bold text-success">{{ number_format($totalReturnedRawGold, 3) }} গ্রাম</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter & Main List Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    {{-- Filters Bar --}}
                    <form method="GET" action="{{ route('karigor.job') }}" class="mb-4">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-dark mb-1" style="color: #212529 !important;">স্ট্যাটাস ফিল্টার</label>
                                <select name="status" class="form-select form-select-sm text-dark fw-semibold" style="color: #212529 !important; background-color: #ffffff !important;" onchange="this.form.submit()">
                                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>সকল স্ট্যাটাস (All)</option>
                                    <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>চলমান (In Progress)</option>
                                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>সম্পন্ন (Completed)</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-bold text-dark mb-1" style="color: #212529 !important;">কাজের ধরন ফিল্টার</label>
                                <select name="task_type" class="form-select form-select-sm text-dark fw-semibold" style="color: #212529 !important; background-color: #ffffff !important;" onchange="this.form.submit()">
                                    <option value="all" {{ $taskType === 'all' ? 'selected' : '' }}>সকল টাইপ (All Types)</option>
                                    <option value="Repair" {{ $taskType === 'Repair' ? 'selected' : '' }}>Repair (মেরামত)</option>
                                    <option value="Raw Gold(Paka kora)" {{ $taskType === 'Raw Gold(Paka kora)' ? 'selected' : '' }}>Raw Gold (পাকা করা)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-dark mb-1" style="color: #212529 !important;">কারিগর ফিল্টার</label>
                                <select name="karigor_id" class="form-select form-select-sm text-dark fw-semibold" style="color: #212529 !important; background-color: #ffffff !important;" onchange="this.form.submit()">
                                    <option value="all" {{ $karigorId === 'all' ? 'selected' : '' }}>সকল কারিগর (All Karigors)</option>
                                    @foreach($karigors as $kUser)
                                        <option value="{{ $kUser->id }}" {{ (string)$karigorId === (string)$kUser->id ? 'selected' : '' }}>
                                            {{ $kUser->name }} ({{ $kUser->phone ?? '—' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end mt-4">
                                <a href="{{ route('karigor.job') }}" class="btn btn-outline-secondary btn-sm w-100">
                                    <i class="fa-solid fa-rotate-left me-1"></i> রিসেট ফিল্টার
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- Success Message Alert --}}
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ session()->get('message') }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Data Table --}}
                    <div class="table-responsive">
                        <table id="config-table" class="table display table-striped border align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">জব আইডি ও তারিখ</th>
                                    <th class="text-center">অর্পিত কারিগর</th>
                                    <th class="text-center">ক্যাটাগরি ও পণ্য (ক্রয় ID)</th>
                                    <th class="text-center">কাজের ধরন</th>
                                    <th class="text-center">প্রদত্ত ওজন ও সোনা</th>
                                    <th class="text-center">ফেরত হিসাব & কনভার্সন %</th>
                                    <th class="text-center">স্ট্যাটাস</th>
                                    <th class="text-center">একশন</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $sl = 1; @endphp
                                @forelse ($jobs as $job)
                                <tr>
                                    <td class="text-center fw-bold">{{ $sl }}</td>

                                    {{-- Job ID & Date --}}
                                    <td class="text-center">
                                        <span class="badge bg-dark px-2 py-1">Job #{{ $job->id }}</span>
                                        <div class="text-muted small mt-1">
                                            <i class="fa-regular fa-clock me-1"></i>{{ $job->assigned_at ? $job->assigned_at->format('d M Y, h:i A') : '—' }}
                                        </div>
                                    </td>

                                    {{-- Karigor Avatar & Details --}}
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-2">
                                            <img src="{{ ($job->karigor && $job->karigor->image && $job->karigor->image !== 'default_user.jpg') ? asset('user/' . $job->karigor->image) : asset('cover/default_user.jpg') }}"
                                                 alt="Avatar" class="rounded-circle border" style="width:36px;height:36px;object-fit:cover;">
                                            <div class="text-start">
                                                <div class="fw-bold text-dark">{{ $job->karigor->name ?? '—' }} {{ $job->karigor->last_name ?? '' }}</div>
                                                <small class="text-muted"><i class="fa-solid fa-phone fa-xs me-1"></i>{{ $job->karigor->phone ?? '—' }}</small>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Purchase Item Info --}}
                                    <td class="text-center">
                                        <span class="badge bg-primary">{{ $job->purchase->productCategory->category_name ?? '—' }}</span><br>
                                        <span class="badge bg-info text-dark mt-1">{{ $job->purchase->product->product_name ?? '—' }}</span>
                                        <div class="small fw-semibold text-muted mt-1">
                                            ক্রয় আইডি: <strong>#{{ $job->purchase_id }}</strong>
                                        </div>
                                    </td>

                                    {{-- Task Type --}}
                                    <td class="text-center">
                                        @if($job->task_type === 'Repair')
                                            <span class="badge px-2 py-1 fw-bold shadow-sm" style="background-color: #0d6efd !important; color: #ffffff !important; font-size: 12px;">
                                                <i class="fa-solid fa-wrench me-1"></i>Repair
                                            </span>
                                        @else
                                            <span class="badge px-2 py-1 fw-bold shadow-sm" style="background-color: #6f42c1 !important; color: #ffffff !important; font-size: 12px;">
                                                <i class="fa-solid fa-coins me-1"></i>Raw Gold (পাকা করা)
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Given Inputs --}}
                                    <td class="text-center small">
                                        <div><span class="text-muted">গ্রস ওজন:</span> <strong>{{ number_format($job->given_gross_weight ?? 0, 3) }} গ্রাম</strong></div>
                                        <div><span class="text-muted">কাঁচা সোনা:</span> <strong class="text-success">{{ number_format($job->given_purity_weight ?? 0, 3) }} গ্রাম</strong></div>
                                        @if($job->assigned_extra_raw_gold > 0)
                                            <div><span class="text-muted">অতিরিক্ত সোনা:</span> <strong class="text-warning">{{ number_format($job->assigned_extra_raw_gold, 3) }} গ্রাম</strong></div>
                                        @endif
                                    </td>

                                    {{-- Output & Conversion --}}
                                    <td class="text-center small">
                                        @if($job->status === 'completed')
                                            <div><span class="text-muted">ফেরত কাঁচা সোনা:</span> <strong class="text-success">{{ number_format($job->returned_raw_gold ?? 0, 3) }} গ্রাম</strong></div>
                                            <div><span class="text-muted">ওয়েস্টেজ ঘাটতি:</span> <strong class="text-danger">{{ number_format($job->wastage_gold ?? 0, 3) }} গ্রাম</strong></div>
                                            @if($job->conversion_percentage)
                                                <div class="mt-1">
                                                    <span class="badge bg-info text-dark px-2 py-1">
                                                        <i class="fa-solid fa-chart-line me-1"></i>{{ number_format($job->conversion_percentage, 2) }}% Conversion
                                                    </span>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-muted italic">— (চলমান) —</span>
                                        @endif
                                    </td>

                                    {{-- Status --}}
                                    <td class="text-center">
                                        @if($job->status === 'in_progress')
                                            <span class="badge bg-warning text-dark px-2 py-1 shadow-sm"><i class="fa-solid fa-spinner fa-spin me-1"></i>চলমান</span>
                                        @elseif($job->status === 'completed')
                                            <span class="badge bg-success px-2 py-1 shadow-sm"><i class="fa-solid fa-circle-check me-1"></i>সম্পন্ন</span>
                                        @else
                                            <span class="badge bg-secondary px-2 py-1">{{ $job->status }}</span>
                                        @endif
                                    </td>

                                    {{-- Action Buttons --}}
                                    <td class="text-center">
                                        <div class="d-flex align-items-center justify-content-center gap-1">
                                            @if($job->status === 'in_progress')
                                                <button type="button"
                                                    class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 text-nowrap px-2 py-1"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#completeJobModalIndex{{ $job->id }}"
                                                    title="কারিগরের কাজ গ্রহণ ও স্টক আপডেট করুন">
                                                    <i class="fa-solid fa-circle-check"></i> কাজ গ্রহণ
                                                </button>
                                            @endif

                                            @if($job->purchase && $job->purchase->transaction_id)
                                                <a href="{{ route('purchase.show', $job->purchase->transaction_id) }}"
                                                    class="btn btn-sm btn-outline-info px-2 py-1" title="ক্রয় বিবরণ দেখুন">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                {{-- Complete Job Modal for In-Progress Jobs --}}
                                @if($job->status === 'in_progress')
                                <div class="modal fade" id="completeJobModalIndex{{ $job->id }}" tabindex="-1" aria-labelledby="completeJobModalLabelIndex{{ $job->id }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-header text-white" style="background:linear-gradient(135deg,#198754,#146c43);">
                                                <h5 class="modal-title d-flex align-items-center gap-2" id="completeJobModalLabelIndex{{ $job->id }}">
                                                    <i class="fa-solid fa-circle-check fa-lg"></i>
                                                    কারিগরের কাজ গ্রহণ ও সমাপ্তি [Job Id #{{ $job->id }}]
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('karigor-job.complete') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="job_id" value="{{ $job->id }}">

                                                <div class="modal-body p-4 text-start">

                                                    {{-- Job Summary Header --}}
                                                    <div class="p-3 mb-4 rounded-3" style="background:#e8f5e9;border:1px solid #c8e6c9;">
                                                        <div class="fw-bold text-success mb-1">
                                                            <i class="fa-solid fa-user-gear me-1"></i> জব সামারি (Job Summary):
                                                        </div>
                                                        <div class="row g-2 small text-dark mt-1">
                                                            <div class="col-sm-4">
                                                                <span class="text-muted">অর্পিত কারিগর:</span> <strong>{{ $job->karigor->name ?? '—' }}</strong>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <span class="text-muted">কাজের ধরন:</span> <strong class="text-primary">{{ $job->task_type }}</strong>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <span class="text-muted">প্রদত্ত গ্রস ওজন:</span> <strong>{{ $job->given_gross_weight ?? 0 }} গ্রাম</strong>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <span class="text-muted">আনুমানিক কাঁচা সোনা:</span> <strong class="text-success">{{ $job->given_purity_weight ?? 0 }} গ্রাম</strong>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <span class="text-muted">অতিরিক্ত প্রদত্ত কাঁচা সোনা:</span> <strong>{{ $job->assigned_extra_raw_gold ?? 0 }} গ্রাম</strong>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <span class="text-muted">অর্পণ তারিখ:</span> <strong>{{ $job->assigned_at ? $job->assigned_at->format('d M Y, h:i A') : '—' }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- Return Inputs --}}
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label for="returned_raw_gold_idx_{{ $job->id }}" class="form-label fw-bold">
                                                                ১. ফেরত পাওয়া কাঁচা/পাকা সোনা (Returned Raw Gold) <span class="text-danger">*</span>
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="number" step="0.001" min="0" name="returned_raw_gold" id="returned_raw_gold_idx_{{ $job->id }}" class="form-control form-control-lg fw-bold text-success returned-raw-gold-input-idx" data-job-id="{{ $job->id }}" data-given-purity="{{ $job->given_purity_weight }}" placeholder="গ্রাম হিসাব লিখুন..." required>
                                                                <span class="input-group-text bg-light fw-bold">গ্রাম (GM)</span>
                                                            </div>
                                                            <small class="text-muted">কারিগর থেকে ফেরত পাওয়া মোট কাঁচা সোনা।</small>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="used_extra_raw_gold_idx_{{ $job->id }}" class="form-label fw-bold">
                                                                ২. ব্যবহৃত অতিরিক্ত সোনা (Used Extra Raw Gold) <span class="text-muted fw-normal">(ঐচ্ছিক)</span>
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="number" step="0.001" min="0" name="used_extra_raw_gold" id="used_extra_raw_gold_idx_{{ $job->id }}" class="form-control form-control-lg used-extra-gold-input-idx" data-job-id="{{ $job->id }}" data-given-purity="{{ $job->given_purity_weight }}" value="{{ $job->assigned_extra_raw_gold }}" placeholder="ব্যবহৃত পরিমাণ...">
                                                                <span class="input-group-text bg-light">গ্রাম (GM)</span>
                                                            </div>
                                                            <small class="text-muted">মেরামতে প্রকৃতপক্ষে ব্যবহৃত অতিরিক্ত সোনা।</small>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="returned_gross_weight_idx_{{ $job->id }}" class="form-label fw-bold">
                                                                ৩. ফেরত পণ্যের মোট ওজন (Returned Gross Weight) <span class="text-muted fw-normal">(ঐচ্ছিক)</span>
                                                            </label>
                                                            <div class="input-group">
                                                                <input type="number" step="0.001" min="0" name="returned_gross_weight" id="returned_gross_weight_idx_{{ $job->id }}" class="form-control" value="{{ $job->given_gross_weight }}" placeholder="মোট ওজন লিখুন...">
                                                                <span class="input-group-text bg-light">গ্রাম (GM)</span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label for="notes_idx_{{ $job->id }}" class="form-label fw-bold">
                                                                ৪. মন্তব্য / রিমার্কস (Notes) <span class="text-muted fw-normal">(ঐচ্ছিক)</span>
                                                            </label>
                                                            <input type="text" name="notes" id="notes_idx_{{ $job->id }}" class="form-control" placeholder="কোন বিশেষ বিবরণ থাকলে লিখুন...">
                                                        </div>
                                                    </div>

                                                    {{-- Dynamic Conversion % Box --}}
                                                    <div class="mt-4 p-3 rounded-3 bg-light border text-center">
                                                        <div class="row align-items-center">
                                                            <div class="col-6 border-end">
                                                                <small class="text-muted d-block">অনুমানিক কনভার্সন শতকরা (Conversion %):</small>
                                                                <span id="calc_conversion_pct_idx_{{ $job->id }}" class="fs-4 fw-bold text-primary">— %</span>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted d-block">অনুমানিক ঘাটতি/ওয়েস্টেজ (Wastage Loss):</small>
                                                                <span id="calc_wastage_loss_idx_{{ $job->id }}" class="fs-4 fw-bold text-danger">— গ্রাম</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer bg-light">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                    <button type="submit" class="btn btn-success px-4 fw-bold">
                                                        <i class="fa-solid fa-check-double me-1"></i> কাজ সম্পন্ন ও স্টক আপডেট করুন
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @php $sl++; @endphp
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">কোন কারিগর জব পাওয়া যায়নি।</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
@endsection

@push('admin_script')
    @include('admin.common.script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function updateJobCalcIdx(jobId) {
                var rawInput = document.getElementById('returned_raw_gold_idx_' + jobId);
                if (!rawInput) return;
                var givenPurity = parseFloat(rawInput.dataset.givenPurity || 0);
                var returnedVal = parseFloat(rawInput.value || 0);
                var usedExtraVal = parseFloat(document.getElementById('used_extra_raw_gold_idx_' + jobId)?.value || 0);

                var pctEl = document.getElementById('calc_conversion_pct_idx_' + jobId);
                var wastageEl = document.getElementById('calc_wastage_loss_idx_' + jobId);

                if (givenPurity > 0 && returnedVal > 0) {
                    var pct = (returnedVal / givenPurity) * 100;
                    var wastage = (givenPurity + usedExtraVal) - returnedVal;

                    if (pctEl) pctEl.textContent = pct.toFixed(2) + ' %';
                    if (wastageEl) wastageEl.textContent = (wastage >= 0 ? wastage.toFixed(3) : 0) + ' গ্রাম';
                } else {
                    if (pctEl) pctEl.textContent = '— %';
                    if (wastageEl) wastageEl.textContent = '— গ্রাম';
                }
            }

            document.querySelectorAll('.returned-raw-gold-input-idx, .used-extra-gold-input-idx').forEach(function(inp) {
                inp.addEventListener('input', function() {
                    updateJobCalcIdx(this.dataset.jobId);
                });
            });
        });
    </script>
@endpush
