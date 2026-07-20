@extends('admin.master')
@section('title')
Unsorted স্টক তালিকা
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
<div class="col-lg-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h3>Unsorted স্টক তালিকা</h3>
            </div>
        </div>
        <div class="card-body">
            <table id="config-table" class="table display table-striped border no-wrap">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center" style="white-space:normal;">তারিখ ও<br>ইনভয়েস আইডি</th>
                        <th class="text-center" style="white-space:normal;">ক্যাটাগরি<br>পণ্যের নাম</th>
                        <th class="text-center">ক্রয় আইডি</th>
                        <th class="text-center">পণ্যের ছবি</th>
                        <th class="text-center">ক্যারেট</th>
                        <th class="text-center">ওজন</th>
                        <th class="text-center">বিশুদ্ধতার ওজন</th>
                        <th class="text-center">বাজার মূল্য</th>
                        <th class="text-center">ক্রয় মূল্য</th>
                        <th class="text-center">পণ্যের বিবরণ</th>
                        <th class="text-center">একশন্স</th>
                    </tr>
                </thead>
                <tbody>
                    @php $sl = 1; @endphp
                    @forelse ($purchases as $purchase)
                    <tr>
                        <td class="text-center">
                            <strong>{{ $sl }}</strong>
                        </td>

                        {{-- তারিখ ও ইনভয়েস আইডি (clickable → user detail modal) --}}
                        <td class="text-center">
                            <button type="button"
                                class="btn btn-link p-0 text-decoration-none text-dark user-detail-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#userDetailModal"
                                data-user-id="{{ $purchase->user_id ?? '' }}"
                                data-user-name="{{ $purchase->user->name ?? '—' }} {{ $purchase->user->last_name ?? '' }}"
                                data-user-phone="{{ $purchase->user->phone ?? '—' }}"
                                data-user-phone2="{{ $purchase->user->phone2 ?? '' }}"
                                data-user-email="{{ $purchase->user->email ?? '—' }}"
                                data-user-role="{{ $purchase->user->role->role_name ?? '—' }}"
                                data-user-active="{{ $purchase->user->is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}"
                                data-user-image="{{ ($purchase->user->image && $purchase->user->image !== 'default_user.jpg') ? asset('user/' . $purchase->user->image) : asset('cover/default_user.jpg') }}"
                                data-user-photo1="{{ $purchase->user->photo1 ? asset('user/' . $purchase->user->photo1) : '' }}"
                                title="ব্যবহারকারীর বিবরণ দেখুন">
                                <div class="fw-semibold">{{ $purchase->order_date ? \Carbon\Carbon::parse($purchase->order_date)->format('d M Y') : '—' }}</div>
                                @if($purchase->invoice)
                                <span class="badge bg-dark mt-1">INV #{{ $purchase->invoice_id }}</span>
                                @endif
                                <div class="mt-1">
                                    <span class="badge" style="background:#0d6efd22;color:#0d6efd;font-size:10px;">
                                        <i class="fa-solid fa-user fa-xs me-1"></i>{{ $purchase->user->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </button>
                        </td>

                        {{-- ক্যাটাগরি ও পণ্যের নাম --}}
                        <td class="text-center">
                            <span class="badge bg-primary">{{ $purchase->productCategory->category_name ?? '—' }}</span><br>
                            <span class="badge bg-info mt-1">{{ $purchase->product->product_name ?? '—' }}</span>
                        </td>

                        {{-- ক্রয় আইডি --}}
                        <td class="text-center">
                            <strong>{{ $purchase->id }}</strong>
                        </td>

                        {{-- পণ্যের ছবি --}}
                        <td class="text-center">
                            <img src="{{ ($purchase->photo && $purchase->photo !== 'default-cover.jpg') ? asset('user/purchase/' . $purchase->photo) : asset('cover/default-cover.jpg') }}"
                                style="width:50px;height:50px;object-fit:cover;border-radius:6px;border:1px solid #dee2e6;"
                                alt="product">
                        </td>

                        {{-- ক্যারেট --}}
                        <td class="text-center">
                            <span class="badge" style="background:#6f42c1;">{{ $purchase->karat ?? '—' }}</span>
                        </td>

                        {{-- ওজন --}}
                        <td class="text-center">
                            <span class="badge bg-info">{{ $purchase->bhori ?? 0 }} ভরি, {{ $purchase->ana ?? 0 }} আনা, {{ $purchase->roti ?? 0 }} রতি, {{ $purchase->point ?? 0 }} পয়েন্ট</span><br>
                            <span class="badge bg-danger mt-1">({{ $purchase->gram ?? 0 }} গ্রাম)</span>
                        </td>

                        {{-- বিশুদ্ধতার ওজন (raw_gold) --}}
                        <td class="text-center">
                            {{ $purchase->raw_gold ?? '—' }} গ্রাম
                        </td>

                        {{-- বাজার মূল্য (actual_price) --}}
                        <td class="text-center">
                            ৳ {{ number_format($purchase->actual_price ?? 0, 2) }}
                        </td>

                        {{-- ক্রয় মূল্য (total_price) --}}
                        <td class="text-center">
                            ৳ {{ number_format($purchase->total_price ?? 0, 2) }}
                        </td>

                        {{-- পণ্যের বিবরণ --}}
                        <td class="text-center" style="max-width:180px;white-space:normal;">
                            {{ $purchase->details ?? '—' }}
                        </td>

                        {{-- একশন্স --}}
                        <td class="text-center">
                            <div class="action-btns d-flex align-items-center justify-content-center gap-1 flex-wrap">
                                <div>
                                    <a href="{{ $purchase->transaction_id ? route('purchase.show', $purchase->transaction_id) : '#' }}"
                                        class="text-success me-1" data-toggle="tooltip"
                                        data-placement="top" data-bs-original-title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ $purchase->transaction_id ? route('purchase.edit', $purchase->transaction_id) : '#' }}"
                                        class="text-info me-1" data-toggle="tooltip"
                                        data-placement="top" data-bs-original-title="Edit">
                                        <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    </a>
                                </div>
                                <div>
                                    <form action="{{ route('stock.delete') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                        <button type="submit" class="text-warning btn_custom show_confirm" data-toggle="tooltip"
                                            data-placement="top" data-bs-original-title="Delete">
                                            <i class="fa-solid fa-trash-can fa-fw"></i>
                                        </button>
                                    </form>
                                </div>

                                {{-- Send To Dropdown --}}
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle px-2 py-1"
                                        type="button"
                                        id="sendDropdown{{ $purchase->id }}"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        title="পাঠান">
                                        <i class="fa-solid fa-paper-plane fa-fw"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow"
                                        aria-labelledby="sendDropdown{{ $purchase->id }}">

                                        {{-- Send to Shop --}}
                                        <li>
                                            <form action="{{ route('stock.send-to-shop') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                <button type="submit"
                                                    class="dropdown-item d-flex align-items-center gap-2 text-primary">
                                                    <i class="fa-solid fa-store fa-fw"></i>
                                                    শপে পাঠান
                                                </button>
                                            </form>
                                        </li>

                                        {{-- Send to Warehouse --}}
                                        <li>
                                            <form action="{{ route('stock.send-to-warehouse') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                                                <button type="submit"
                                                    class="dropdown-item d-flex align-items-center gap-2 text-success">
                                                    <i class="fa-solid fa-warehouse fa-fw"></i>
                                                    গুদামে পাঠান
                                                </button>
                                            </form>
                                        </li>

                                    </ul>
                                </div>
                                {{-- /Send To Dropdown --}}

                            </div>
                        </td>
                    </tr>
                    @php $sl++ @endphp
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">No Data Found!</td>
                    </tr>
                    @endforelse

                </tbody>

            </table>
        </div>
    </div>
</div>

{{-- User Detail Modal --}}
<div class="modal fade" id="userDetailModal" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header" style="background:linear-gradient(135deg,#1a1a2e,#16213e);">
                <h5 class="modal-title text-white d-flex align-items-center gap-2" id="userDetailModalLabel">
                    <i class="fa-solid fa-circle-user fa-lg"></i>
                    ব্যবহারকারীর বিবরণ
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <div id="modal-user-avatar-wrap" style="width:80px;height:80px;border-radius:50%;overflow:hidden;display:inline-block;border:3px solid #667eea;box-shadow:0 4px 14px rgba(102,126,234,.35);">
                        <img id="modal-user-avatar" src="" alt="user" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <h5 class="mt-2 mb-0 fw-bold" id="modal-user-name"></h5>
                    <span class="badge" id="modal-user-role" style="background:#667eea;"></span>
                </div>
                <hr>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:#f8f9fa;">
                            <div class="text-muted small mb-1"><i class="fa-solid fa-id-badge me-1"></i>ইউজার আইডি</div>
                            <div class="fw-semibold" id="modal-user-id"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:#f8f9fa;">
                            <div class="text-muted small mb-1"><i class="fa-solid fa-circle-dot me-1"></i>স্ট্যাটাস</div>
                            <div class="fw-semibold" id="modal-user-active"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:#f8f9fa;">
                            <div class="text-muted small mb-1"><i class="fa-solid fa-phone me-1"></i>ফোন ১</div>
                            <div class="fw-semibold" id="modal-user-phone"></div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-3 rounded" style="background:#f8f9fa;">
                            <div class="text-muted small mb-1"><i class="fa-solid fa-phone-volume me-1"></i>ফোন ২</div>
                            <div class="fw-semibold" id="modal-user-phone2"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded" style="background:#f8f9fa;">
                            <div class="text-muted small mb-1"><i class="fa-solid fa-envelope me-1"></i>ইমেইল</div>
                            <div class="fw-semibold" id="modal-user-email"></div>
                        </div>
                    </div>

                    {{-- Documents --}}
                    <div class="col-12">
                        <div class="p-3 rounded" style="background:#f8f9fa;">
                            <div class="text-muted small mb-2"><i class="fa-solid fa-id-card me-1"></i>ডকুমেন্টস</div>
                            <div class="d-flex gap-3 flex-wrap" id="modal-user-docs">
                                <div>
                                    <div class="text-muted" style="font-size:11px;margin-bottom:4px;">ব্যবহারকারীর ছবি</div>
                                    <a id="modal-user-image-link" href="#" target="_blank">
                                        <img id="modal-user-image"
                                             src=""
                                             alt="user image"
                                             style="width:90px;height:90px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;cursor:pointer;transition:transform .2s;"
                                             onmouseover="this.style.transform='scale(1.07)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                </div>
                                <div id="modal-user-photo1-wrap">
                                    <div class="text-muted" style="font-size:11px;margin-bottom:4px;">ফটো ১ (ডকুমেন্ট)</div>
                                    <a id="modal-user-photo1-link" href="#" target="_blank">
                                        <img id="modal-user-photo1"
                                             src=""
                                             alt="photo 1"
                                             style="width:90px;height:90px;object-fit:cover;border-radius:8px;border:2px solid #dee2e6;cursor:pointer;transition:transform .2s;"
                                             onmouseover="this.style.transform='scale(1.07)'"
                                             onmouseout="this.style.transform='scale(1)'">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- /Documents --}}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বন্ধ করুন</button>
            </div>
        </div>
    </div>
</div>
{{-- /User Detail Modal --}}

@endsection

@push('admin_script')
@include('admin.common.script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.user-detail-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('modal-user-id').textContent     = this.dataset.userId    || '—';
            document.getElementById('modal-user-name').textContent   = this.dataset.userName  || '—';
            document.getElementById('modal-user-phone').textContent  = this.dataset.userPhone || '—';
            document.getElementById('modal-user-phone2').textContent = this.dataset.userPhone2 || '—';
            document.getElementById('modal-user-email').textContent  = this.dataset.userEmail || '—';
            document.getElementById('modal-user-role').textContent   = this.dataset.userRole  || '—';

            var activeEl = document.getElementById('modal-user-active');
            var isActive = this.dataset.userActive === 'সক্রিয়';
            activeEl.textContent = this.dataset.userActive;
            activeEl.style.color = isActive ? '#198754' : '#dc3545';

            // Avatar (user image)
            var imgUrl = this.dataset.userImage || '';
            document.getElementById('modal-user-avatar').src = imgUrl;

            // Document: user image
            document.getElementById('modal-user-image').src        = imgUrl;
            document.getElementById('modal-user-image-link').href  = imgUrl || '#';

            // Document: photo1
            var photo1Url = this.dataset.userPhoto1 || '';
            var photo1Wrap = document.getElementById('modal-user-photo1-wrap');
            if (photo1Url) {
                document.getElementById('modal-user-photo1').src       = photo1Url;
                document.getElementById('modal-user-photo1-link').href = photo1Url;
                photo1Wrap.style.display = '';
            } else {
                photo1Wrap.style.display = 'none';
            }
        });
    });
});
</script>
@endpush