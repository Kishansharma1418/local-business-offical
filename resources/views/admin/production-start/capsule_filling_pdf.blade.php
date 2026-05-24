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

        .note {
            font-size: 10px;
            margin-top: 10px;
        }
    </style>

</head>

<body>

    <div class="title">D.D. Pharmaceuticals Pvt. Ltd.</div>
    <div class="subtitle">5.1.2 IPQC Record (Capsule Filling)</div>

    <!-- Product + Batch -->
    <table class="table">
        <tr>
            <td><b>Product Name:</b> {{ $data->product_name }}</td>
            <td><b>Batch No:</b> {{ $data->batch_number }}</td>
        </tr>
    </table>

    <br>

    <!-- TABLE -->
    <table class="table">

        <thead>
            <tr>
                <th>S.No</th>
                <th>Date / Time</th>
                <th>Weight of 20 Capsules (gms)</th>
                <th>Leakage from Joints</th>
                <th>Cracks & Pinholes</th>
                <th>Other Physical Defects</th>
                <th>D.T (mins)</th>
                <th>Done By Sign / Date</th>
            </tr>
        </thead>

        <tbody>

            <tr>
                <td colspan="8"><b>Std. Limits</b></td>
            </tr>

            @foreach($data->records as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row['datetime'] }}</td>
                <td>{{ $row['weight'] }}</td>
                <td>{{ $row['leakage'] }}</td>
                <td>{{ $row['cracks'] }}</td>
                <td>{{ $row['defects'] }}</td>
                <td>{{ $row['dt'] }}</td>
                <td>{{ $row['sign'] }}</td>
            </tr>
            @endforeach

        </tbody>
    </table>

    <br>

    <!-- Bottom -->
    <table class="table">
        <tr>
            <td>
                <b>Filled Capsules Inspected By / Date</b><br>
             {{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}
            </td>

            <td>
                <b>Total Weight of Filled Capsules</b><br>
                {{ $data->total_filled_weight }}
            </td>

            <td>
                <b>Total Weight of Rejected Capsules</b><br>
                {{ $data->total_rejected_weight }}
            </td>
        </tr>
    </table>

    <br>

    <table class="table">
        <tr>
            <td>
                <b>Production Chemist/In-Charge</b><br>
             {{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}
            </td>

            <td>
                <b>Q.A Chemist/In-Charge</b><br>
              {{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}
            </td>
        </tr>
    </table>

    <p class="note">
        Note: Rejected Capsules include distorted shape, discoloration and other physical defects like leakage from joints, pinholes or cracks in the capsules.
    </p>

</body>

</html>