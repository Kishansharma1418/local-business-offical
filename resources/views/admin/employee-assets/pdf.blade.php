<!DOCTYPE html>
<html>

<head>
    <title>Expense Statement</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            font-size: 13px;
            margin: 40px;
            color: #000;
        }

        .header-section {
            text-align: left;
            margin-bottom: 20px;
        }

        .header-section h2 {
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .header-info {
            margin-top: 10px;
            font-size: 14px;
        }

        .header-info table td {
            padding: 3px 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tfoot td {
            font-weight: bold;
            background-color: #fafafa;
        }

        .footer-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="header-section">
        <h2>DD PHARMACEUTICAL PVT LTD</h2>
        <h3 style="text-align:center; margin:5px 0;">EXPENSE STATEMENT</h3>
        <table class="header-info">
            <tr>
                <td><strong>Name:</strong> {{ $employee->full_name ?? 'All Employees' }}</td>
                <td><strong>Month:</strong> {{ $month ?? 'All Months' }}</td>
            </tr>
        </table>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Distance</th>
                <th>HQ Allow</th>
                <th>Ex Stn Allow</th>
                <th>Out Stn Allow</th>
                <th>Rly/Bus Tkt Amount</th>
                <th> Amount</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $expense)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($expense->start_date)->format('d-m-Y') }}</td>
                    <td>{{ $expense->type ?? 'N/A' }}</td>

                    {{-- <td>{{ $expense->city?->name ?? '-' }}</td> --}}
                      <td>{{ number_format($expense->distance, 2) }}</td>
                    <td>{{ number_format($expense->hq_allow, 2) }}</td>
                    <td>{{ number_format($expense->ex_stn_allow, 2) }}</td>
                    <td>{{ number_format($expense->out_stn_allow, 2) }}</td>
                    <td>{{ number_format($expense->bus_ticket_amount, 2) }}</td>
                    <td>{{ number_format($expense->amount, 2) }}</td>
                    <td>{{ number_format($expense->total_amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
       <tfoot>
    <tr>
        <td colspan="2" style="text-align:right;">Total</td>
        <td>{{ number_format($data->sum('distance'), 2) }}</td>
        <td>{{ number_format($data->sum('hq_allow'), 2) }}</td>
        <td>{{ number_format($data->sum('ex_stn_allow'), 2) }}</td>
        <td>{{ number_format($data->sum('out_stn_allow'), 2) }}</td>
        <td>{{ number_format($data->sum('bus_ticket_amount'), 2) }}</td>
        <td>{{ number_format($data->sum('amount'), 2) }}</td>
        <td>{{ number_format($data->sum('total_amount'), 2) }}</td>
    </tr>
</tfoot>

    </table>

    <div class="footer-section">
        <div>Prepared by: <strong>{{ $employee->full_name ?? 'Admin' }}</strong></div>
        <div>Date: <strong>{{ now()->format('d-m-Y') }}</strong></div>
    </div>
</body>

</html>
