<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 10px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        .label-box {
            border: 1px solid #000;
            padding: 6px;
        }

        .row {
            width: 100%;
        }

        .col-6 {
            width: 50%;
            display: inline-block;
        }

        .col-4 {
            width: 33.33%;
            display: inline-block;
        }

        .note {
            font-size: 10px;
            margin-top: 10px;
        }
    </style>

</head>

<body>

    <div class="title">D.D. Pharmaceuticals Pvt. Ltd.</div>
    <div class="subtitle">IPQC Record (Compression)</div>

    <!-- Product + Batch -->
    <table class="table">
        <tr>
            <td><b>Product Name:</b> {{ $data->product_name }}</td>
            <td><b>Batch No:</b> {{ $data->batch_number }}</td>
        </tr>
    </table>

    <!-- MAIN TABLE -->
    <table class="table">

        <thead>
            <tr>
                <th>No</th>
                <th>Date / Time</th>
                <th>Weight of 20 Tablets (gm)</th>
                <th>D.T (mins)</th>
                <th>Hardness</th>
                <th>Friability (%)</th>
                <th>Thickness (mm)</th>
                <th>Sign / Date</th>
                <th>Remarks</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data->records as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row['datetime'] }}</td>
                <td>{{ $row['weight20'] }}</td>
                <td>{{ $row['dt'] }}</td>
                <td>{{ $row['hardness'] }}</td>
                <td>{{ $row['friability'] }}</td>
                <td>{{ $row['thickness'] }}</td>
                <td>{{ $row['sign'] }}</td>
                <td>{{ $row['remarks'] }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>

    <br>

    <!-- Bottom Section -->
    <table class="table">
        <tr>
            <td>
                <b>Uncoated Tablets Inspected By / Date</b><br>
                {{ $data->inspected_by }}
            </td>

            <td>
                <b>Total Weight of Uncoated Tablets</b><br>
                {{ $data->total_weight_uncoated }}
            </td>

            <td>
                <b>Total Weight of Rejected Tablets</b><br>
                {{ $data->total_weight_rejected }}
            </td>
        </tr>
    </table>

    <br>

    <table class="table">
        <tr>
            <td>
                <b>Production Chemist / In-Charge</b><br>
                {{ $data->inspected_by }}
            </td>

            <td>
                <b>QA Chemist / In-Charge</b><br>
                {{ $data->inspected_by }}
            </td>
        </tr>
    </table>

    <div class="note">
        Note: Rejected Uncoated Tablets also include disfigured Tablets in appearance.
    </div>

</body>

</html>