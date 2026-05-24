<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Credit Note - PDF</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            margin: 0;
            padding: 0;
            color: #222;
        }

        .wrapper {
            padding: 22px 26px;
        }

        .header {
            border-bottom: 1px solid #d8dce7;
            padding-bottom: 12px;
            margin-bottom: 18px;
            display: flex !important;
            justify-content: space-between !important;
        }

        .company-left {
            max-width: 45%;
        }

        .company-title {
            font-size: 20px;
            font-weight: 800;
            color: #004e92;
        }

        .company-small {
            font-size: 11px;
            color: #555;
            margin-bottom: 2px;
        }

        .company-address {
            font-size: 10.6px;
            margin-top: 4px;
            color: #333;
        }

        .pdf-title {
            font-size: 20px;
            text-align: right;
            font-weight: 800;
            color: #004e92;
        }

        .pdf-sub {
            font-size: 11px;
            text-align: right;
            margin-top: 5px;
            color: #444;
        }

        .section-title {
            background: #f1f5ff;
            border-left: 3px solid #004e92;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            margin: 16px 0 10px 0;
            color: #003570;
            border-radius: 4px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        th,
        td {
            padding: 6px 8px;
            font-size: 10.6px;
            border: 1px solid #d3d7e3;
        }

        th {
            background: #f5f7ff;
            font-weight: 700;
        }

        .amount {
            text-align: right;
            font-weight: 700;
            color: #004e92;
        }

        .total-row {
            background: #e8edff;
            font-weight: 800;
        }

        .net-box {
            padding: 10px 14px;
            border: 1.5px solid #d3d7e3;
            background: #fff;
            margin-top: 8px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 900;
            display: flex;
            justify-content: space-between;
        }

        .footer {
            border-top: 1px solid #d3d7e3;
            text-align: center;
            padding-top: 10px;
            font-size: 10px;
            color: #666;
            margin-top: 25px;
        }
    </style>
</head>

<body>

    <div class="wrapper">

        <table border="0" style="width: 100%; margin-bottom: 18px;">
            <tr>
                <td style="border: none; padding: 0;">
                    <img src="{{ public_path(setting('logo')) }}" style="max-height: 60px;">
                </td>
                <td style="border: none; padding: 0;"></td>
            </tr>
            <tr>
                <td style="border: none; padding: 0; font-size: 14px;">
                    {{ setting('company_name') }} <br>
                    {{ setting('company_address') }} <br>
                    Email: {{ setting('company_email') }} <br>
                    Phone: {{ setting('company_phone') }}
                </td>

                <td style="border: none; padding: 0; text-align: right;">
                    <div class="pdf-title">Credit Note</div>
                    <div class="pdf-sub">
                        Date: {{ \Carbon\Carbon::parse($creditNote->credit_note_date)->format('d/m/Y') }} <br>
                        Credit Note No: {{ $creditNote->credit_note_number }}
                    </div>
                </td>
            </tr>
        </table>



        <div class="section-title">Customer Details</div>

        <table>
            <tr>
                <th>Customer Code</th>
                <td>{{ $creditNote->customer?->code }}</td>
                <th>Customer Name</th>
                <td>{{ $creditNote->customer?->name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $creditNote->customer?->email ?? 'N/A' }}</td>
                <th>Phone</th>
                <td>{{ $creditNote->customer?->mobile_no ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Address</th>
                <td colspan="3">
                    {{ $creditNote->customer?->getCustomerAddress
                        ? $creditNote->customer?->getCustomerAddress->address_line1 .
                            ', ' .
                            ($creditNote->customer?->getCustomerAddress->address_line2 ?? '') .
                            ', ' .
                            ($creditNote->customer?->getCustomerAddress->cities?->name ?? '') .
                            ', ' .
                            ($creditNote->customer?->getCustomerAddress->states?->name ?? '') .
                            ', ' .
                            ($creditNote->customer?->getCustomerAddress->countries?->name ?? '') .
                            ' - ' .
                            ($creditNote->customer?->getCustomerAddress->pincode ?? '')
                        : 'N/A' }}
                </td>
            </tr>
            <tr>
                <th>Credit Limit</th>
                <td>{{ number_format($creditNote->customer?->credit_limit, 2) }}</td>
                <th>Gst Type</th>
                <td>{{$creditNote->customer?->gst_type ?? '-' }}</td>

            </tr>
            <tr>
                <th>Gst no</th>
                <td>{{ $creditNote->customer?->gst_no ?? '-'}}</td>

               <th>Place of Supply</th>
               <td>
                    {{ $creditNote->customer?->states
                        ? $creditNote->customer->states->name . ' (' . $creditNote->customer->states->iso2 . ')'
                        : '-' }}
                </td>
            </tr>
        </table>

        <div class="section-title">Sales Information</div>

        <table>
            <tr>
                <th>Sales Person</th>
                <td>{{ $creditNote->salesPerson?->full_name }}</td>
                <th>Branch</th>
                <td>{{ $creditNote->branch?->branch_name }}</td>
            </tr>


        </table>


        <div class="section-title">Credit Note Items</div>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Details</th>
                    <th>Qty Ordered</th>
                    <th>Unit Price</th>
                    <th>Discount (%)</th>
                    <th>Discount Amt</th>
                    <th>GST (%)</th>
                    <th>GST Amt</th>
                    <th>Total Amount</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $gross = 0;
                    $totalDiscount = 0;
                    $totalGst = 0;
                @endphp

                @foreach ($creditNoteDetails as $item)
                    @php
                        $amount = $item->quantity * $item->unit_price;
                        $discAmt = $amount * ($item->discount_percent / 100);
                        $taxable = $amount - $discAmt;
                        $gstAmt = $taxable * ($item->gst_percent / 100);

                        $gross += $amount;
                        $totalDiscount += $discAmt;
                        $totalGst += $gstAmt;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <!-- PRODUCT DETAILS IN ONE CELL -->
                        <td>
                            <strong>{{ $item->product->name }}</strong><br>

                            <span style="color:#444;">
                                <strong>Batch:</strong> {{ $item->batch_id ?? '-' }} <br>

                                <strong>Mfg:</strong>
                                {{ $item->manufacturing_date ? \Carbon\Carbon::parse($item->manufacturing_date)->format('d/m/y') : '-' }}
                                <br>

                                <strong>Exp:</strong>
                                {{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('d/m/y') : '-' }}
                            </span>
                        </td>

                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ $item->discount_percent }}</td>

                        @php
                            $amount = $item->quantity * $item->unit_price;
                            $discAmt = $amount * ($item->discount_percent / 100);
                            $gstAmt = ($amount - $discAmt) * ($item->gst_percent / 100);
                        @endphp

                        <td>₹ {{ number_format($discAmt, 2) }}</td>
                        <td>{{ $item->gst_percent }}</td>
                        <td>{{ number_format($gstAmt, 2) }}</td>
                        <td class="amount">{{ number_format($item->total_amount, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <!-- <div class="section-title">Summary</div>

        <table>
            <tr>
                <th>Total Amount</th>
                <td class="amount">₹ {{ number_format($creditNote->total_amount, 2) }}</td>
            </tr>
            <tr>
                <th>GST Amount</th>
                <td class="amount">₹ {{ number_format($creditNote->tax_amount, 2) }}</td>
            </tr>
            <tr>
                <th>Overall Bill Discount</th>
                <td class="amount">₹ {{ number_format($creditNote->overall_bill_discount_amount, 2) }}</td>
            </tr>
            <tr class="total-row">
                <th>Net Payable</th>
                <td class="amount">₹ {{ number_format($creditNote->net_amount, 2) }}</td>
            </tr>
        </table> -->
        <div class="section-title">Summary</div>

        <table>
            <tr>
                <th>Gross Amount</th>
                <td class="amount">₹ {{ number_format($gross, 2) }}</td>
            </tr>

            <tr>
                <th>Total Discount</th>
                <td class="amount">₹ {{ number_format($totalDiscount, 2) }}</td>
            </tr>

            <tr>
                <th>Taxable Amount</th>
                <td class="amount">
                    ₹ {{ number_format($gross - $totalDiscount, 2) }}
                </td>
            </tr>

            <tr>
                <th>GST / Tax Amount</th>
                <td class="amount">₹ {{ number_format($totalGst, 2) }}</td>
            </tr>

            <tr class="total-row">
                <th>Net Payable</th>
                <td class="amount">
                    ₹ {{ number_format($gross - $totalDiscount + $totalGst, 2) }}
                </td>
            </tr>
        </table>


        <div class="footer">
            This is a system generated sales order. No signature required.
        </div>

    </div>

</body>

</html>
