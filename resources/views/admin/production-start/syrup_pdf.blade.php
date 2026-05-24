<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
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
    </style>
</head>

<body>

    <h4 class="text-center">D.D. Pharmaceuticals Pvt. Ltd.</h4>
    <p class="text-center">Syrup Filling cum IPQC Check</p>

    {{-- Product --}}
    <table>
        <tr>
            <td><b>Product Name</b></td>
            <td>{{ $data->product_name }}</td>
            <td><b>Batch No</b></td>
            <td>{{ $data->batch_number }}</td>
        </tr>
    </table>


    <p><b>9.1 Filling cum IPQC Check</b></p>
    <table>
        <tr>
            <td><b>Temperature (Limit: -23°C ±2°C)</b></td>
            <td>{{ $syrupFilling->temprature ?? '' }}</td>
            <td><b>Colour & Appearance</b></td>
            <td>{{ $syrupFilling->colour_appearance ?? '' }}</td>
        </tr>
        <tr>
            <td><b>PH</b></td>
            <td>{{ $syrupFilling->ph ?? '' }}</td>
            <td></td>
            <td></td>
        </tr>
    </table>
    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th>Date / Time</th>
                <th>Filled Volume 1</th>
                <th>Filled Volume 2</th>
                <th>ROPP Cap</th>
                <th>Checked By</th>
                <th>Verified By</th>
            </tr>
        </thead>
        <tbody>

            <tr>
                <td colspan="6"><b>Std. Limits</b></td>
            </tr>

            @for($i=0;$i<10;$i++)
                <tr>
                <td>{{ $data->datetime[$i] ?? '' }}</td>
                <td>{{ $data->filled_volume[1][$i] ?? '' }}</td>
                <td>{{ $data->filled_volume[2][$i] ?? '' }}</td>
                <td>{{ $data->ropp_cap[$i] ?? '' }}</td>
                <td>{{ $data->checked_by[$i] ?? '' }}</td>
                <td>{{ $data->verified_by[$i] ?? '' }}</td>
                </tr>
                @endfor

        </tbody>
    </table>

    <br>

    {{-- TOTAL --}}
    <table>
        <tr>
            <td><b>Total Filled Qty</b></td>
            <td>{{ $data->total_filled_qty }}</td>
        </tr>
    </table>

    <br>

    {{-- LINE CLEARANCE --}}
    <p><b>Line Clearance for Visual Inspection of Filled-Sealed Bottles</b></p>
    <table>
        <tr>
            <td><b>Previous Product</b></td>
            <td>{{ $data->prev_product }}</td>

            <td><b> Previous Batch</b></td>
            <td>{{ $data->prev_batch }}</td>
        </tr>

        <tr>
            <td><b>Line Clearance Given By (Production)/Date</b></td>
            <td colspan="3">{{ $data->line_clearance_by }}</td>
        </tr>
    </table>

    <br>

    {{-- VISUAL --}}
    <table>
        <tr>
            <td><b>Visual Inspection Commenced at</b></td>
            <td>{{ $data->inspection_start }}</td>

            <td><b>Visual Inspection Done By (Sign/Date)</b></td>
            <td>{{ $data->inspection_done_by }}</td>
        </tr>

        <tr>
            <td><b>Visual Inspection Completed at</b></td>
            <td>{{ $data->inspection_completed }}</td>

            <td><b>Verified By Sign/Date</b></td>
            <td>{{ $data->inspection_verified }}</td>
        </tr>
    </table>

</body>

</html>