<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Customer Ledger</title>

    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #222;
        }

        .wrapper {
            padding: 22px 26px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #d6d9e0;
            padding: 6px 8px;
            font-size: 10.8px;
        }

        th {
            background: #f3f6ff;
            font-weight: 700;
        }

        .header-table td {
            border: none;
            padding: 4px 0;
        }

        .title {
            font-size: 20px;
            font-weight: 800;
            color: #004e92;
        }

        .sub {
            font-size: 11px;
            color: #555;
        }

        .section {
            margin-top: 16px;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: 700;
            color: #003570;
            border-left: 4px solid #004e92;
            background: #f1f5ff;
            padding: 6px 10px;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .debit {
            color: #b10000;
            font-weight: 700;
        }

        .credit {
            color: #006400;
            font-weight: 700;
        }

        .balance {
            font-weight: 800;
            color: #004e92;
        }

        .total-row {
            background: #e8edff;
            font-weight: 800;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            color: #666;
            border-top: 1px solid #d6d9e0;
            padding-top: 8px;
        }
    </style>
</head>

<body>
    <div class="wrapper">

        {{-- HEADER --}}
        <table class="header-table">
            <tr>
                <td>
                    <img src="{{ public_path(setting('logo')) }}" style="max-height:55px;">
                </td>
                <td class="right">
                    <div class="title">Customer Ledger</div>
                    <div class="sub">
                        Generated On: {{ now()->format('d/m/Y H:i:s') }}
                    </div>
                </td>
            </tr>

            <tr>
                <td class="sub">
                    <strong>{{ setting('company_name') }}</strong><br>
                    {{ setting('company_address') }}<br>
                    Email: {{ setting('company_email') }} |
                    Phone: {{ setting('company_phone') }}
                </td>
            </tr>
        </table>

        {{-- CUSTOMER DETAILS --}}
        <div class="section">Customer Details</div>

        <table>
            <tr>
                <th width="20%">Customer Code</th>
                <td width="30%">{{ $customer->code }}</td>
                <th width="20%">Customer Name</th>
                <td width="30%">{{ $customer->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $customer->email ?? 'N/A' }}</td>
                <th>Mobile</th>
                <td>{{ $customer->mobile_no ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td colspan="3">
                    {{ optional($customer->getCustomerAddress)->address_line1 }},
                    {{ optional($customer->getCustomerAddress)->address_line2 }},
                    {{ optional($customer->getCustomerAddress)->cities?->name }},
                    {{ optional($customer->getCustomerAddress)->states?->name }},
                    {{ optional($customer->getCustomerAddress)->countries?->name }}
                    - {{ optional($customer->getCustomerAddress)->pincode }}
                </td>
            </tr>
            <tr>
                <th>Credit Limit</th>
                <td>₹ {{ number_format($customer->credit_limit, 2) }}</td>
                <th>gst No</th>
                <td> {{ $customer->gst_no }}</td>
            </tr>

            <tr>
                <th>gst Type</th>
                <td> {{ $customer->gst_type }}</td>

                <th>Place of supply</th>
                <td> {{ $customer->states ? $customer->states->name . ' (' . $customer->states->iso2 . ')' : '-' }}
                </td>

            </tr>
        </table>

        {{-- LEDGER TABLE --}}
        <div class="section">Ledger Statement</div>

        <table width="100%" border="1" cellspacing="0" cellpadding="6">
            <thead>
                <tr style="text-align: center;">
                    <th style="text-align: center;">Date</th>
                    <th style="text-align: center;">Invoice No</th>
                    <th style="text-align: center;">Invoice Amount</th>
                    <th style="text-align: center;">Received</th>
                    <th style="text-align: center;">Due</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ledger as $row)
                    <tr style="text-align: center;">
                        <td style="text-align: center;">{{ \Carbon\Carbon::parse($row['date'])->format('d-m-Y') }}</td>
                        <td style="text-align: center;">{{ $row['invoice_no'] }}</td>
                        <td style="text-align: center;">{{ number_format($row['invoice_amount'], 2) }}</td>
                        <td style="text-align: center;">{{ number_format($row['received_amount'], 2) }}</td>
                        <td style="text-align: center;">{{ number_format($row['due_amount'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th>{{ number_format($totalInvoice, 2) }}</th>
                    <th>{{ number_format($totalReceived, 2) }}</th>
                    <th>{{ number_format($totalDue, 2) }}</th>
                </tr>
            </tfoot>
        </table>


        <div class="footer">
            This is a system generated customer ledger. No signature required.
        </div>

    </div>
</body>

</html>
