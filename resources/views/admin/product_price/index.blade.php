@extends('admin.master')

@section('title')
প্রোডাক্ট প্রাইস লিস্ট
@endsection

@push('admin_style')
{{-- Add any page-specific CSS here --}}
@endpush

@section('body')
<div class="row mt-2">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">প্রোডাক্ট প্রাইস তালিকা</h3>
                <a href="{{ route('product-price.create') }}" class="btn btn-primary">নতুন যোগ করুন</a>
            </div>
            <div class="card-body">
                @if($prices->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width:5%">#</th>
                                <th class="text-center">প্রোডাক্ট নাম</th>
                                <th class="text-center">দাম (৳)</th>
                                <th class="text-center" style="width:15%">অ্যাকশন</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($prices as $index => $price)
                            <tr>
                                <td class="text-center">{{ $prices->firstItem() + $index }}</td>
                                <td class="text-center">{{ $price->product_name }}</td>
                                <td class="text-center">{{ number_format($price->price, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('product-price.edit', $price->id) }}" class="btn btn-sm btn-info" title="Edit"><i class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('product-price.destroy', $price->id) }}" method="POST" class="d-inline" onsubmit="return confirm('আপনি কি নিশ্চিত যে মুছে ফেলতে চান?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa-solid fa-trash-can"></i></button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $prices->links() }}
                @else
                <p class="text-center">কোনো প্রোডাক্ট প্রাইস রেকর্ড পাওয়া যায়নি।</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('admin_script')
{{-- Add any page-specific JS here --}}
@endpush