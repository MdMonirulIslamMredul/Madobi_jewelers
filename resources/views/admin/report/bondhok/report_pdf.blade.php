<!DOCTYPE html>
<html>
<head>
    <title>Bondhok Report</title>
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
    <h1>Bondhok Report</h1>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Bondhok Date</th>
                <th>Customer</th>
                <th>Product</th>
                <th>Quantity (Bhori, Ana, Roti)</th>
                <th>Payable Amount</th>
                <th>Paid</th>
                <th>Due</th>
            </tr>
        </thead>
        <tbody>
            @php $sl = 1; @endphp
            @foreach ($bondhoks as $bondhok)
           <tr>
                <td><strong>{{ $sl }}</strong></td>
                <td>{{ $bondhok->start_time }}</td>
                <td>{{ $bondhok->user->name }} ({{ $bondhok->user->phone }})</td>
                <td>{{ $bondhok->product->name }}</td>
                <td>{{ $bondhok->bhori ?? 0 }} Bhori, {{ $bondhok->ana ?? 0 }} Ana, {{ $bondhok->roti ?? 0 }} Roti</td>
                <td>{{ $bondhok->payable_amount }}</td>
                <td>{{ $bondhok->paid }}</td>
                <td>{{ $bondhok->due }}</td>
            </tr>
            @php $sl++ @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4">Total</th>
                <th>{{ $totalBhori }} Bhori, {{ $totalAna }} Ana, {{ $totalRoti }} Roti</th>
                <th>{{ $totalPrice }}</th>
                <th>{{ $totalAdvPayment }}</th>
                <th>{{ $totalDuePayment }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
