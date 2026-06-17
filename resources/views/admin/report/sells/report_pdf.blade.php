<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
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
    <h1>Sales Report</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Order Date</th>
                <th>Customer</th>
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
            @foreach ($sells as $sell)
            @php
            $product = $products->firstWhere('id', $sell->product_id)->product_name ?? 'N/A';
            $cat = $categories->firstWhere('id', $sell->category_id)->category_name ?? 'N/A';
            @endphp
            <tr>
                <td>{{ $sl }}</td>
                <td>{{ $sell->order_date }}</td>
                <td>{{ $sell->user->name }} ({{ $sell->user->phone }})</td>
                <td>{{ $cat }}</td>
                <td>{{ $product }}</td>
                <td>{{ $sell->bhori ?? 0 }} Bhori, {{ $sell->ana ?? 0 }} Ana, {{ $sell->roti ?? 0 }} Roti</td>
                <td>{{ $sell->total_price }}</td>
                <td>{{ $sell->total_payment }}</td>
                <td>{{ $sell->due_payment }}</td>
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
