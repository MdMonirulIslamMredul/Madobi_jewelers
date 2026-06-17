<!DOCTYPE html>
<html>
<head>
    <title>Purchases Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Purchases Report</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Order Date</th>
                <th>Seller Name</th>
                <th>Seller Phone</th>
                <th>Category</th>
                <th>Product</th>
                <th>Quantity (Bhori, Ana, Roti)</th>
                <th>Total Price</th>
                <th>Total Payment</th>
                <th>Due Payment</th>
            </tr>
        </thead>
        <tbody>
            @php $sl = 1; @endphp
            @foreach ($purchases as $purchase)
            @php
                        $product = $products->firstWhere('id', $purchase->product_id)->product_name ?? 'N/A';
                        $cat = $categories->firstWhere('id', $purchase->category_id)->category_name ?? 'N/A';
                        @endphp
                        <tr>
                            <td><strong>{{ $sl }}</strong></td>
                            <td>{{ $purchase->order_date }}</td>
                            <td>{{ $purchase->user->name }}</td>
                            <td>{{ $purchase->user->phone }}</td>
                            <td>{{ $cat }}</td>
                            <td>{{ $product }}</td>
                            <td>{{ $purchase->bhori ?? 0 }} Bhori, {{ $purchase->ana ?? 0 }} Ana, {{ $purchase->roti ?? 0 }} Roti</td>
                            <td>{{ $purchase->total_price }}</td>
                            <td>{{ $purchase->total_payment }}</td>
                            <td>{{ $purchase->due_payment }}</td>
                        </tr>
            @php $sl++ @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5">Total</th>
                <th>{{ $totalBhori }} Bhori, {{ $totalAna }} Ana, {{ $totalRoti }} Roti</th>
                <th>{{ $totalPrice }}</th>
                <th>{{ $totalAdvPayment }}</th>
                <th>{{ $totalDuePayment }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
