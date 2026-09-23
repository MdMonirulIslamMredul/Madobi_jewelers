@extends('admin.master')

@section('title')
কারিগর মজুরি তালিকা
@endsection

@push('admin_style')
<style>
    .mojuri-table th {
        background-color: #f8fafc;
        color: #334155;
        font-weight: 700;
        vertical-align: middle;
        border-bottom: 2px solid #e2e8f0;
        font-size: 13.5px;
    }
    .mojuri-table td {
        vertical-align: middle;
        font-size: 14px;
        color: #1e293b;
    }
    .action-btn-group {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-action-edit {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background-color: #0284c7;
        border: 1px solid #0284c7;
        color: #fff;
        transition: all 0.2s ease;
    }
    .btn-action-edit:hover {
        background-color: #0369a1;
        border-color: #0369a1;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(2, 132, 199, 0.3);
    }
    .btn-action-delete {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background-color: #ef4444;
        border: 1px solid #ef4444;
        color: #fff;
        transition: all 0.2s ease;
    }
    .btn-action-delete:hover {
        background-color: #dc2626;
        border-color: #dc2626;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(239, 68, 68, 0.3);
    }
    .badge-category-gold {
        background-color: #fef3c7;
        color: #b45309;
        border: 1px solid #fde68a;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
    }
    .badge-category-rupa {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
    }
    .badge-category-diamond {
        background-color: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
    }
    .badge-category-platinum {
        background-color: #ede9fe;
        color: #6d28d9;
        border: 1px solid #ddd6fe;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
    }
    .rate-card {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s;
    }
    .rate-card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush

@section('body')
<div class="container-fluid mt-3">
    <!-- Header Row -->
    <div class="row mb-3 align-items-center">
        <div class="col-md-6">
            <h3 class="mb-1 text-dark font-weight-bold">
                <i class="fa-solid fa-coins text-warning me-2"></i>কারিগর মজুরি তালিকা
            </h3>
            <p class="text-muted small mb-0">প্রতি ভরি ও প্রতি গ্রাম হিসেবে কারিগরদের কাজের মজুরি হার নির্ধারণ</p>
        </div>
        <div class="col-md-6 text-md-end mt-2 mt-md-0">
            <a href="{{ route('karigor-mojuri.create') }}" class="btn btn-primary px-3 shadow-sm">
                <i class="fa-solid fa-plus me-1"></i> নতুন মজুরি হার যোগ করুন
            </a>
        </div>
    </div>

    <!-- Success Message Alert -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Category Summary Cards -->
    <div class="row g-3 mb-4">
        @php
            $goldCount = \App\Models\KarigorMojuri::where('category_name', 'Gold')->count();
            $rupaCount = \App\Models\KarigorMojuri::where('category_name', 'Rupa')->count();
            $diamondCount = \App\Models\KarigorMojuri::where('category_name', 'Diamond')->count();
            $platinumCount = \App\Models\KarigorMojuri::where('category_name', 'Platinum')->count();
        @endphp
        <div class="col-6 col-md-3">
            <div class="card rate-card shadow-sm border-0 border-start border-warning border-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted font-weight-bold d-block">Gold (স্বর্ণ)</small>
                        <h4 class="mb-0 font-weight-bold text-dark">{{ $goldCount }} টি রেট</h4>
                    </div>
                    <div class="rounded-circle p-2 bg-warning bg-opacity-10 text-warning">
                        <i class="fa-solid fa-gem fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card rate-card shadow-sm border-0 border-start border-secondary border-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted font-weight-bold d-block">Rupa (রূপা)</small>
                        <h4 class="mb-0 font-weight-bold text-dark">{{ $rupaCount }} টি রেট</h4>
                    </div>
                    <div class="rounded-circle p-2 bg-secondary bg-opacity-10 text-secondary">
                        <i class="fa-solid fa-ring fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card rate-card shadow-sm border-0 border-start border-info border-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted font-weight-bold d-block">Diamond (হীরা)</small>
                        <h4 class="mb-0 font-weight-bold text-dark">{{ $diamondCount }} টি রেট</h4>
                    </div>
                    <div class="rounded-circle p-2 bg-info bg-opacity-10 text-info">
                        <i class="fa-regular fa-gem fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card rate-card shadow-sm border-0 border-start border-primary border-4 p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted font-weight-bold d-block">Platinum (প্লাটিনাম)</small>
                        <h4 class="mb-0 font-weight-bold text-dark">{{ $platinumCount }} টি রেট</h4>
                    </div>
                    <div class="rounded-circle p-2 bg-primary bg-opacity-10 text-primary">
                        <i class="fa-solid fa-shield-halved fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0 font-weight-bold text-dark">
                        <i class="fa-solid fa-list me-2 text-primary"></i>মজুরি তালিকা (মোট: {{ $totalCount }} টি)
                    </h5>
                </div>
                <div class="col-md-6">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('karigor-mojuri.index') }}" class="d-flex justify-content-md-end gap-2 mt-2 mt-md-0">
                        <select name="category" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="">সকল ক্যাটাগরি</option>
                            <option value="Gold" {{ request('category') == 'Gold' ? 'selected' : '' }}>Gold</option>
                            <option value="Rupa" {{ request('category') == 'Rupa' ? 'selected' : '' }}>Rupa</option>
                            <option value="Diamond" {{ request('category') == 'Diamond' ? 'selected' : '' }}>Diamond</option>
                            <option value="Platinum" {{ request('category') == 'Platinum' ? 'selected' : '' }}>Platinum</option>
                        </select>
                        <select name="type" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="">সকল টাইপ</option>
                            <option value="22k" {{ request('type') == '22k' ? 'selected' : '' }}>22K</option>
                            <option value="21k" {{ request('type') == '21k' ? 'selected' : '' }}>21K</option>
                            <option value="18k" {{ request('type') == '18k' ? 'selected' : '' }}>18K</option>
                        </select>
                        @if(request('category') || request('type'))
                            <a href="{{ route('karigor-mojuri.index') }}" class="btn btn-sm btn-outline-secondary">রিসেট</a>
                        @endif
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 mojuri-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 60px;">#</th>
                            <th>ক্যাটাগরি (Category)</th>
                            <th>টাইপ / ক্যারেট (Type)</th>
                            <th class="text-end">প্রতি ভরি মজুরি (৳)</th>
                            <th class="text-end">প্রতি গ্রাম মজুরি (৳)</th>
                            <th class="text-center">হিসাব সূত্র</th>
                            <th class="text-center" style="width: 120px;">অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($mojuris as $idx => $m)
                        <tr>
                            <td class="text-center text-muted font-weight-bold">{{ $mojuris->firstItem() + $idx }}</td>
                            <td>
                                @php
                                    $catClass = match(strtolower($m->category_name)) {
                                        'gold'     => 'badge-category-gold',
                                        'rupa'     => 'badge-category-rupa',
                                        'diamond'  => 'badge-category-diamond',
                                        'platinum' => 'badge-category-platinum',
                                        default    => 'badge-category-gold'
                                    };
                                    $catIcon = match(strtolower($m->category_name)) {
                                        'gold'     => 'fa-gem text-warning',
                                        'rupa'     => 'fa-ring text-secondary',
                                        'diamond'  => 'fa-gem text-info',
                                        'platinum' => 'fa-shield-halved text-purple',
                                        default    => 'fa-circle'
                                    };
                                @endphp
                                <span class="{{ $catClass }}">
                                    <i class="fa-solid {{ $catIcon }} me-1"></i>{{ ucfirst($m->category_name) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border px-2 py-1 font-weight-bold fs-7">
                                    {{ strtoupper($m->type) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <span class="font-weight-bold text-dark fs-6">৳ {{ number_format($m->per_vori_tk, 2) }}</span>
                                <small class="text-muted d-block" style="font-size: 11px;">/ ভরি</small>
                            </td>
                            <td class="text-end">
                                <span class="font-weight-bold text-primary fs-6">৳ {{ number_format($m->per_gram_tk, 2) }}</span>
                                <small class="text-muted d-block" style="font-size: 11px;">/ গ্রাম</small>
                            </td>
                            <td class="text-center text-muted small">
                                <span class="badge bg-light text-muted border font-monospace">ভরি ÷ ১১.৬৬৪</span>
                            </td>
                            <td class="text-center">
                                <div class="action-btn-group">
                                    <a href="{{ route('karigor-mojuri.edit', $m->id) }}" class="btn-action-edit" title="সম্পাদনা করুন">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('karigor-mojuri.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিতভাবে এই কারিগর মজুরি হার মুছে ফেলতে চান?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-action-delete" title="মুছে ফেলুন">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fa-solid fa-folder-open fa-3x text-muted mb-3 d-block opacity-50"></i>
                                <p class="mb-2 fs-6">কোনো কারিগর মজুরি তথ্য পাওয়া যায়নি</p>
                                <a href="{{ route('karigor-mojuri.create') }}" class="btn btn-sm btn-primary">
                                    <i class="fa-solid fa-plus me-1"></i> প্রথম মজুরি হার যুক্ত করুন
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($mojuris->hasPages())
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-center">
                {{ $mojuris->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
