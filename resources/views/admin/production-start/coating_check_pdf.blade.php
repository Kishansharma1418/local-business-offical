<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Coating Check PDF</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 5px;
            text-align: center;
        }

        .section {
            margin-top: 15px;
        }

        .row {
            width: 100%;
            display: flex;
        }

        .col {
            width: 33%;
            float: left;
        }

        .box {
            margin-right: 10px;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <h4 class="text-center fw-bold">
        D.D. Pharmaceuticals Pvt. Ltd.
    </h4>

    <p class="text-center">
        6.3 Inprocess check of Coated Tablets
    </p>

    {{-- PRODUCT --}}
    <table class="section">
        <tr>
            <td><b>Product Name</b></td>
            <td>{{ $data->product_name }}</td>

            <td><b>Batch No</b></td>
            <td>{{ $data->batch_number }}</td>
        </tr>
    </table>

    {{-- THREE TABLES --}}
    <div class="section">

        <table>
            <tr>
                <td width="33%">
                    <b>Thickness of Tablets</b>
                    <table>
                        <tr>
                            <th>No</th>
                            <th>Value</th>
                        </tr>

                        @for($i=1;$i<=20;$i++)
                            <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $data->thickness[$i-1] ?? '' }}</td>
            </tr>
            @endfor

        </table>
        </td>

        <td width="33%">
            <b>Weight of Tablets</b>
            <table>
                <tr>
                    <th>No</th>
                    <th>Value</th>
                </tr>

                @for($i=1;$i<=20;$i++)
                    <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $data->weight[$i-1] ?? '' }}</td>
                    </tr>
                    @endfor

            </table>
        </td>

        <td width="33%">
            <b>Hardness of Tablets</b>
            <table>
                <tr>
                    <th>No</th>
                    <th>Value</th>
                </tr>

                @for($i=1;$i<=20;$i++)
                    <tr>
                    <td>{{ $i }}</td>
                    <td>{{ $data->hardness[$i-1] ?? '' }}</td>
                    </tr>
                    @endfor

            </table>
        </td>
        </tr>
        </table>

    </div>

    {{-- AVERAGES --}}
    <table class="section">
        <tr>
            <td><b>Average Thickness</b></td>
            <td>{{ $data->average_thickness }}</td>

            <td><b>Average Weight</b></td>
            <td>{{ $data->average_weight }}</td>

            <td><b>Average Hardness</b></td>
            <td>{{ $data->average_hardness }}</td>
        </tr>
    </table>

    {{-- EXTRA --}}
    <table class="section">
        <tr>
            <td><b>Date Inspected</b></td>
            <td>{{ $data->inspection_date }}</td>

            <td><b>Total Weight Coated</b></td>
            <td>{{ $data->total_weight_coated }}</td>

            <td><b>Total Rejected</b></td>
            <td>{{ $data->total_weight_rejected }}</td>
        </tr>
    </table>

    {{-- NOTE --}}
    <p class="section">
        <b>Note:</b> Rejected Coated Tablets include disfigured tablets, cracking of coating,
        mottled surface, unbeveled edges, chipping, capping etc.
    </p>

    {{-- SIGN --}}
    <table class="section">
        <tr>
            <td>
                <b>Production Chemist / Date</b><br><br>
                {{ $data->production_sign }}
            </td>

            <td>
                <b>QA Incharge / Date</b><br><br>
                {{ $data->qa_sign }}
            </td>
        </tr>
    </table>

</body>

</html>