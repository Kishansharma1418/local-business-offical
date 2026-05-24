<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Page 16 PDF</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        h5 {
            text-align: center;
            margin-bottom: 15px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #e3e3e3;
        }

        td.text-start {
            text-align: left;
        }

        tr:nth-child(even) {
            background-color: #f7f7f7;
        }
    </style>
</head>

<body>
    <h3>D.D. Pharmaceuticals Pvt. Ltd.</h3>
    <h5>10.0 – Reconciliation of Packing Material</h5>
    <table>
        <tr>
            <th>Product Name</th>
            <td class="text-start">{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}</td>
            <th>Batch No</th>
            <td class="text-start">{{ $productionFlowStart->batch_number }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Particular</th>
                <th>Alu-Alu / Blister Foil</th>
                <th>PVC / Base Foil</th>
                <th>Carton</th>
            </tr>
        </thead>
        <tbody>
            @php
            $rows = [
            'std_qty'=>'Std Qty For Batch',
            'qty_issued'=>'Qty Issued (a)',
            'additional_required'=>'Additional Required (b)',
            'total_issued' => 'Total Issued A (a+b)',
            'packed_qty'=>'Packed Qty (B)',
            'sample_qty' => 'QC Sample + Control + Stability + Other sample (C)',
            'sample_qty'=>'Sample QC + Stability (C)',
            'specimen_qty'=>'Specimen Sample (D)',
            'total_packed' => 'Total Packed (B+C+D)',
            'rejection_qty'=>'Rejection (F)',
            'total_consumed' => 'Total Consumed For batch (X)=E+F',
            'returned_qty'=>'Returned to Store (Y)',
            'final_quantity' => 'Total = X+Y or equal to A',
            ];
            $page16Data = $page16Reconciliations->keyBy('material_type');
            @endphp

            @foreach($rows as $field => $label)
            <tr>
                <td class="text-start">{{ $label }}</td>
                @foreach(['alu','pvc','carton'] as $type)
                @php
                $record = $page16Data->get($type);
                $value = $record ? $record->$field : '';
                @endphp
                <td>{{ $value }}</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>