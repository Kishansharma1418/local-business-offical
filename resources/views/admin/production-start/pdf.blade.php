<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Production Voucher</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #000;
        }

        th, td {
            padding: 6px;
            vertical-align: middle;
        }

        .text-center { text-align: center; }
        .fw-bold { font-weight: bold; }

        .company-box {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            margin-bottom: 12px;
        }

        .company-box h3 {
            margin: 0;
        }

   .signature-box {
    height: 90px;
    text-align: left;   /* ✅ center se left */
    padding-left: 10px; /* thoda margin for neat look */
}

.signature-line {
    border-bottom: 1px solid #000;
    width: 160px;
    margin: 40px 0 6px 0; /* ✅ left aligned */
}

.signature-name {
    font-weight: bold;
    font-size: 13px;
    margin-top: 2px;
}

.signature-role {
    font-size: 11px;
}

.signature-date {
    font-size: 11px;
}

    </style>
</head>

<body>

<!-- Company Header -->
<div class="company-box">
    <h3>DD Pharmaceuticals Pvt. Ltd.</h3>
    <div>
        G-1/583,585,586, RIICO Industrial Area, Sitapura,<br>
        Tonk Road, Jaipur - 302022<br>
        <strong>ISO 9001:2015 Certified Company</strong><br>
        <strong>WHO-GMP Certified Company</strong>
    </div>
</div>

<!-- Batch Info -->
<table>
    <tr>
        <th colspan="4" class="text-center fw-bold">
            2.0 Batch Production Sheet
        </th>
    </tr>

    <tr>
        <th width="20%">Product Name</th>
        <td width="30%" class="fw-bold">
            {{ $batch->bomMaster->finishedGood->name }}
        </td>

        <th width="20%">Batch No.</th>
        <td width="30%">
            {{ $batch->batch_number }}
        </td>
    </tr>
</table>

<br>

<!-- Material Table -->
<table>
    <thead>
        <tr class="text-center">
            <th>S.No.</th>
            <th>Name of Material Issued</th>
            <th>Specification</th>
            <th>Quantity Used Including Overages If any</th>
            <th>Control Reference No.</th>
            <th>User's Signature & Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach($batch->items as $index => $item)
        <tr class="text-center">
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->material->name }}</td>
            <td>IP</td>
            <td class="fw-bold">
                {{ number_format($item->final_quantity, 3) }}
                {{ strtoupper($item->uom) }}
            </td>
            <td>{{ $item->control_ref ?? '-' }}</td>
            <td></td>
        </tr>
        @endforeach
    </tbody>
</table>

<br><br>

<table>
    <tr>
        <td width="100%">
            <strong>Verified by (Production)</strong><br>
            (Signature & Date)

            <div class="signature-box">
                @if($batch->status === 'COMPLETED' && $batch->verified_by_production)

                    <div class="signature-line"></div>

                    <div class="signature-name">
                        {{ optional($batch->verifiedByProduction)->full_name }}
                    </div>

                    <div class="signature-role">
                        Production
                    </div>

                    <div class="signature-date">
                        Date: {{ $batch->updated_at->format('d-m-Y') }}
                    </div>

                @else
                    <div class="signature-line"></div>
                @endif
            </div>
        </td>
    </tr>
</table>

<br>

<div style="font-size:11px;">
    <strong>Note:</strong> Attach sheet, if so required.
</div>

</body>
</html>
