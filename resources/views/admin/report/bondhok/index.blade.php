@extends('admin.master')
@section('title')
বন্ধক রিপোর্ট
@endsection

@push('admin_style')
@include('admin.common.style')
@endpush
@section('body')
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    @if(request()->has('filter'))
                    <div class="col-md-2 text-start" style="margin-top: 34px;">
                        <a href="{{ route('bondhok.report.index', ['pdf' => 1] + request()->query()) }}" class="btn btn-outline-danger btn-lg">প্রিন্ট করুন</a>
                    </div>
                    @endif 
                    <div class="col-md-10">
                        <form action="{{ route('bondhok.report.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="filter" class="col-form-label">Choose Daily/Weekly/Monthly/Yearly</label>
                                    <select class="form-control" name="filter" id="filter">
                                        <option>select</option>
                                        <option @if (request()->filter=='daily') selected @endif value="daily">দৈনিক</option>
                                        <option @if (request()->filter=='weekly') selected @endif  value="weekly">সাপ্তাহিক</option>
                                        <option @if (request()->filter=='monthly') selected @endif value="monthly">মাসিক</option>
                                        <option @if (request()->filter=='3-months') selected @endif value="3-months">ত্রৈমাসিক</option>
                                        <option @if (request()->filter=='6-months') selected @endif value="6-months">ষান্মাসিক</option>
                                        <option @if (request()->filter=='yearly') selected @endif value="yearly">বাৎসরিক</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="from_date" class="col-form-label">From Date</label>
                                    <input type="date" value="{{request()->from_date}}" class="form-control" id="from_date" name="from_date">
                                </div>
                                <div class="col-md-2">
                                    <label for="to_date" class="col-form-label">To Date</label>
                                    <input type="date" value="{{request()->to_date}}" class="form-control" id="to_date" name="to_date">
                                </div>
                                <div class="col-md-2 text-end">
                                    <button type="submit" class="btn btn-outline-primary" style="margin-top: 36px;">ফিল্টার করুন</button>
                                </div>
                            </div>
                        </form>
                    </div>  
                </div>
            </div>
            <div class="card-body">
                <table id="config-table" class="table display table-striped border no-wrap">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>বন্ধকের তারিখ</th>
                            <th>কাস্টমার</th>
                            <th>প্রোডাক্ট</th>
                            <th>পরিমাণ</th>
                            <th>আসল টাকা</th>
                            <th>ইন্টারেস্ট %</th>
                            <th>মোট টাকা</th>
                            <th>প্রদান</th>
                            <th>বকেয়া</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp
                        @forelse ($bondhoks as $bondhok)
                         <tr>
                            <td><strong>{{ $sl }}</strong></td>
                            <td>{{ $bondhok->start_time }}</td>
                            <td>{{ $bondhok->user->name }} ({{ $bondhok->user->phone }})</td>
                            <td>{{ $bondhok->product->name }}</td>
                            <td>{{ $bondhok->bhori ?? 0 }} ভরি, {{ $bondhok->ana ?? 0 }} আনা, {{ $bondhok->roti ?? 0 }} রতি</td>
                            <td>{{ $bondhok->base_amount }}</td>
                            <td>{{ $bondhok->interest_rate }}%</td>
                            <td>{{ $bondhok->payable_amount }}</td>
                            <td>{{ $bondhok->paid }}</td>
                            <td>{{ $bondhok->due }}</td>
                        </tr>
                        @php $sl++ @endphp
                        @empty
                        No Data Found!
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="col-md-3">
                        <span class="badge bg-primary"><h6 class="mt-2">সর্বমোট বন্ধক: {{ $totalBhori}} ভরি, {{$totalAna}} আনা, {{$totalRoti}} রতি, {{$totalPoint}} পয়েন্ট</h6></span>
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-success"><h6 class="mt-2">সর্বমোট টাকা: {{ $totalPrice}} /-</h6></span>
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-info"><h6 class="mt-2">সর্বমোট প্রদান: {{ $totalAdvPayment}} /-</h6></span>
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-danger"><h6 class="mt-2">সর্বমোট বকেয়া: {{ $totalDuePayment}} /-</h6></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('admin_script')
@include('admin.common.script')
@endpush
