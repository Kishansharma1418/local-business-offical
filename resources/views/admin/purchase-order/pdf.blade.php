<style>
    body {
        font-family: DejaVu Sans, Arial, sans-serif;
        font-size: 11.5px;
        color: #000;
        line-height: 1.4;
    }

    .container {
        padding: 30px 40px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td,
    th {
        padding: 7px 8px;
        vertical-align: middle;
    }

    .no-border td {
        border: none;
        padding: 4px 6px;
    }

    .border td,
    .border th {
        border: 0.5px solid #000;
    }

    .label {
        width: 6%;
        font-weight: bold;
    }

    .value {
        width: 38%;
    }

    .right {
        width: 15%;
        text-align: right;
    }


    .center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }

    .mt {
        margin-top: 18px;
    }

    .item-header th {
        font-weight: bold;
        text-align: center;
    }

    .total-row td {
        font-weight: bold;
    }

    .signature {
        margin-top: 50px;
    }

    .footer-note {
        margin-top: 35px;
        text-align: center;
        font-weight: bold;
        font-size: 11px;
    }

    .title {
        text-align: center;
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 15px;
        text-transform: uppercase;
    }

    .avoid-break {
        page-break-inside: avoid;
    }

    .avoid-break table,
    .avoid-break tr,
    .avoid-break td {
        page-break-inside: avoid;
    }

    .avoid-break table,
    .avoid-break tr,
    .avoid-break td {
        page-break-inside: avoid;
    }
</style>
<div class="title">Purchase Order</div>

<table class="no-border">
    <tr>
        <td class="label">To,</td>
        <td class="right">
            <strong>Date :</strong>
            {{ \Carbon\Carbon::parse($purchaseOrder->po_date)->format('d/m/Y') }}
            <br>
            <strong>PO Number :</strong>
            {{ $purchaseOrder->po_number }}
        </td>
    </tr>
    <tr>
        <td class="value bold">
            {{ $purchaseOrder->vendor->name }}<br>
            {{ $purchaseOrder->vendor?->countries?->name ?? 'India' }}<br>

        </td>
    </tr>

</table>
<div class="center bold" style="margin-top:5px;">
    Order Through : {{ $purchaseOrder->broker->broker_name ?? '-' }}
</div>
<div class="mt">
    Dear Sir,<br>
    Kindly supply us the following as per undernoted rates and terms negotiable :-
</div>

<table class="border mt">
    <thead>
        <tr class="item-header">
            <th width="5%">S.No.</th>
            <th width="20%">Item Name</th>
            <th width="10%">Rate</th>
            <th width="10%">Qty</th>
            <th width="10%">GST %</th>
            <th width="10%">GST Amt</th>
            <th width="10%">Disc %</th>
            <th width="10%">Disc Amt</th>
            <!-- <th width="15%">Amount</th> -->
        </tr>
    </thead>
    <tbody>
        @foreach ($purchaseOrder->details as $index => $item)
        @php
        $amount = $item->quantity_ordered * $item->unit_price;
        @endphp
        <tr>
            <td class="center">{{ $index + 1 }}</td>
            <td><strong>{{ $item->rawMaterial->name }}</strong></td>
            <td class="right">{{ number_format($item->unit_price, 2) }}</td>
            <td class="center">
                {{ $item->quantity_ordered }} {{ $item->uom->name ?? '' }}
            </td>
            <td class="right">{{ $item->gst_percent }} %</td>
            <td class="right">{{ number_format($item->gst_amount, 2) }}</td>
            <td class="right">{{ $item->discount_percent }} %</td>
            <td class="right">{{ number_format($item->discount_amount, 2) }}</td>
            <!-- <td class="right">{{ number_format($amount, 2) }}</td> -->
        </tr>
        @endforeach
    </tbody>
</table>
<table style="width:40%; margin-left:auto; margin-top:10px; border-collapse:collapse;">
    <tr>
        <td style="text-align:right; padding:4px 6px;">Total Amount</td>
        <td style="text-align:right; padding:4px 6px;">
            {{ number_format($purchaseOrder->total_amount, 2) }}
        </td>
    </tr>
    <tr>
        <td style="text-align:right; padding:4px 6px;">Tax Amount</td>
        <td style="text-align:right; padding:4px 6px;">
            {{ number_format($purchaseOrder->tax_amount, 2) }}
        </td>
    </tr>
    <tr>
        <td style="text-align:right; padding:4px 6px;">Discount Amount</td>
        <td style="text-align:right; padding:4px 6px;">
            {{ number_format($purchaseOrder->discount_amount, 2) }}
        </td>
    </tr>
    <tr>
        <td style="text-align:right; padding:6px 6px; font-weight:bold; border-top:1px solid #000;">
            Net Total
        </td>
        <td style="text-align:right; padding:6px 6px; font-weight:bold; border-top:1px solid #000;">
            {{ number_format($purchaseOrder->net_amount, 2) }}
        </td>
    </tr>
</table>

<div class="avoid-break">
    <table class="border mt">
        <!-- <tr>
            <td class="label">GST</td>
            <td class="value">As applicable</td>
        </tr> -->
        <tr>
            <td class="label">Delivery</td>
            <td class="value bold">
                {{
                    $purchaseOrder->delivery_term
                }}
            </td>
        </tr>
        <tr>
            <td class="label">Mode of Dispatch</td>
            <td class="value bold">Freight To Pay – Door Delivery</td>
        </tr>
        <tr>
            <td class="label">Insurance</td>
            <td class="value bold">
                Policy No. {{ $purchaseOrder->branch->policy_no ?? '-' }}
            </td>
        </tr>
        <tr>
            <td class="label">Payment Terms</td>
            <td class="value bold">
                {{ $purchaseOrder->paymentTerm->days }} days from date of receipt
            </td>
        </tr>
        <tr>
            <td class="label">Our DL No.</td>
            <td class="value bold">
             {{ $purchaseOrder->branch->dl_no ?? '-' }}
            </td>
        </tr>
        <tr>
            <td class="label">GST No.</td>
            <td class="value">{{ $purchaseOrder->branch->gst_number ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Billing Address</td>
            <td class="value">{{ $purchaseOrder->branch->address_line1 ?? '-' }}</td>
        </tr>

        <tr>
            <td class="label">CBN Registration Number</td>
            <td class="value">{{ $purchaseOrder->branch->cbn_no ?? '-' }}</td>
        </tr>
    </table>
</div>

<div class="signature">
    Thanking you,<br>
    Yours truly,<br>
    <strong>{{ setting('company_name') }}</strong>
</div>

<div class="footer-note">
    All matters are subject to Jaipur Jurisdiction
</div>
</body>

</html>