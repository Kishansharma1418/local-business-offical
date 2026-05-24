<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Page 15 Packing PDF</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h3,
        h5,
        h6 {
            margin: 5px 0;
        }

        h3 {
            text-align: center;
            font-size: 16px;
        }

        h5 {
            text-align: center;
            font-size: 14px;
        }

        h6 {
            font-size: 12px;
            margin-top: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 6px 8px;
            text-align: left;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f7f7f7;
        }

        .section-title {
            background-color: #e3e3e3;
            font-weight: bold;
            padding: 5px 10px;
            border: 1px solid #000;
        }

        .input-value {
            font-weight: normal;
        }
    </style>
</head>

<body>

    <h3>D.D. Pharmaceuticals Pvt. Ltd.</h3>
    <h5>Page 15 Report</h5>

    <!-- 8.3 Leak Test Details -->
    <div class="section-title">8.3 Leak Test Details</div>
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>
                <th>Time</th>
                <th>Done By</th>
                <th>Result</th>
                <th>Remarks</th>
                <th>Verified By</th>
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i<6; $i++)
                @php
                $log=$page15Logs[$i] ?? null;
                @endphp
                <tr>
                <td>{{ $i+1 }}</td>
                <td class="input-value">{{ $log->leak_date ?? '' }}</td>
                <td class="input-value">{{ $log->leak_time ?? '' }}</td>
                <td class="input-value">{{ $log->leak_done_by ?? '' }}</td>
                <td class="input-value">{{ $log->leak_result ?? '' }}</td>
                <td class="input-value">{{ $log->leak_remarks ?? '' }}</td>
                <td class="input-value">{{ $log->leak_verified_by ?? '' }}</td>

                </tr>
                @endfor
        </tbody>
    </table>

    <!-- 9.0 Packing -->
    <div class="section-title">9.0 Packing</div>
    <table>
        <tr>
            <th>Previous Product</th>
            <td>{{ $page15Logs[0]->previous_product ?? '' }}</td>
            <th>Previous Product Batch No</th>
            <td>{{ $page15Logs[0]->previous_product_batch_no ?? '' }}</td>
        </tr>
        <tr>
            <th>Line Clearance Given By (Production)/Date</th>
            <td colspan="3">{{ $page15Logs[0]->line_clierence_by ?? '' }}</td>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th>S.No</th>
                <th>Date</th>
                <th> <span style="color: red;">*</span>Strip Checked By</th>
                <th> <span style="color: red;">*</span>Carton Packing Done By</th>
                <th>Packed Carton</th>
                <th>Rejected Carton</th>
                <th>Verified By</th>
            </tr>
        </thead>
        <tbody>
            @for($i=0; $i<6; $i++)
                @php
                $log=$page15Logs[$i] ?? null;
                @endphp
                <tr>
                <td>{{ $i+1 }}</td>
                <td class="input-value">{{ $log->packing_date ?? '' }}</td>
                <td class="input-value">{{ $log->strip_checked_by ?? '' }}</td>
                <td class="input-value">{{ $log->carton_packing_done_by ?? '' }}</td>
                <td class="input-value">{{ $log->packed_carton_count ?? '' }}</td>
                <td class="input-value">{{ $log->rejected_carton_count ?? '' }}</td>
                <td class="input-value">{{ $log->packing_verified_by ?? '' }}</td>
                </tr>
                @endfor
        </tbody>
    </table>
    <div style="margin-top: 10px; font-size: 11px;">
        <p style="margin: 3px 0;">
            <span style="color: red;">*</span> Note: Defects Such as misprint, cuts on the foil, missing tablets, improper sealing etc. shall be rejected during strip Checking.
        </p>
        <p style="margin: 3px 0;">
            <span style="color: red;">**</span> Note: Defects such as misprint, torn-out, deformed cartons etc. shall be rejected during carton packing.
        </p>
    </div>

</body>

</html>