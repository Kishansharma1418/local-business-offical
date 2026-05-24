<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Packing Details PDF</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #000;
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

        .subsection-title {
            font-weight: bold;
            margin-top: 10px;
        }

        .input-value {
            font-weight: normal;
        }

        .small-text {
            font-size: 11px;
        }
    </style>
</head>

<body>

    <h3>D.D. Pharmaceuticals Pvt. Ltd.</h3>
    <h5>Packing Details Report</h5>

    <table>
        <tr>
            <td>Product Name</td>
            <td class="input-value">{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}</td>
            <td>Batch No</td>
            <td class="input-value">{{ $productionFlowStart->batch_number }}</td>
        </tr>

    </table>
    <div class="section-title">8.0 Line Clearance For Blister/Alu-Alu/Strip Machine</div>
    <table>
        <tr>
            <td>Previous Product</td>
            <td class="input-value">{{ $packingDetail->previous_product ?? '' }}</td>
            <td>Previous Batch</td>
            <td class="input-value">{{ $packingDetail->previous_batch_no ?? '' }}</td>
        </tr>
        <tr>
            <td>Line Clearance Given By</td>
            <td class="input-value">{{ auth()->user()->full_name }}</td>
            <td>Date</td>
            <td class="input-value">{{ now()->format('d-m-Y') }}</td>
        </tr>
    </table>
    <div class="section-title">8.1 Blister/Alu-Alu/Strip Machine</div>
    <table>
        <tr>
            <td>Date</td>
            <td class="input-value">{{ $packingDetail->packing_date ?? '' }}</td>
            <td>Machine ID</td>
            <td class="input-value">{{ $packingDetail->machine_id ?? '' }}</td>
        </tr>
        <tr>
            <td>Machine Operator</td>
            <td class="input-value">{{ $packingDetail->machine_operator ?? '' }}</td>
            <td>Duration of Operation</td>
            <td class="input-value">{{ $packingDetail->duration ?? '' }}</td>
        </tr>
        <tr>
            <td>BFR Temperature</td>
            <td class="input-value">{{ $packingDetail->bfr_temperature ?? '' }}</td>
            <td>SFR Temperature</td>
            <td class="input-value">{{ $packingDetail->sfr_temperature ?? '' }}</td>
        </tr>
        <tr>
            <td>Verified By</td>
            <td class="input-value">{{ auth()->user()->full_name }}</td>
            <td>Date</td>
            <td class="input-value">{{ now()->format('d-m-Y') }}</td>
        </tr>
    </table>

    <div class="section-title">8.2 Overprinting Details</div>

    <h6 class="subsection-title">8.2.1 Carton</h6>
    <table>
        <tr>
            <td>Batch No</td>
            <td class="input-value">{{ $packingDetail->carton_batch_no ?? '' }}</td>
            <td>Mfd.</td>
            <td class="input-value">{{ $packingDetail->carton_mfd ?? '' }}</td>
        </tr>
        <tr>
            <td>Exp.</td>
            <td class="input-value">{{ $packingDetail->carton_exp ?? '' }}</td>
            <td>M.R.P</td>
            <td class="input-value">{{ $packingDetail->carton_mrp ?? '' }}</td>
        </tr>
        <tr>
            <td>Printed Date</td>
            <td class="input-value">{{ $packingDetail->carton_printed_date ?? '' }}</td>
            <td colspan="2"></td>
        </tr>
    </table>

    <h6 class="subsection-title">8.2.2 Foil</h6>
    <table>
        <tr>
            <td>Batch No</td>
            <td class="input-value">{{ $packingDetail->foil_batch_no ?? '' }}</td>
            <td>Mfd.</td>
            <td class="input-value">{{ $packingDetail->foil_mfd ?? '' }}</td>
        </tr>
        <tr>
            <td>Exp.</td>
            <td class="input-value">{{ $packingDetail->foil_exp ?? '' }}</td>
            <td>M.R.P</td>
            <td class="input-value">{{ $packingDetail->foil_mrp ?? '' }}</td>
        </tr>
        <tr>
            <td>Printed Date</td>
            <td class="input-value">{{ $packingDetail->foil_printed_date ?? '' }}</td>
            <td colspan="2"></td>
        </tr>
    </table>

</body>

</html>