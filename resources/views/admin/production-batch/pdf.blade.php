<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Requisition Batch PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
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
            padding: 6px;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        /* SIGNATURE LINE */
        .signature-line {
            border-bottom: 1px dashed #000;
            width: 150px;
            margin: 0 auto 8px;
        }

        /* NAME + DATE SEPARATOR */
        .approval-info {
            border-top: 1px solid #999;
            margin-top: 6px;
            padding-top: 4px;
        }

        /* NAME */
        .approval-name {
            font-weight: 700;
            font-size: 13px;
        }

        /* DATE */
        .approval-date {
            font-size: 11px;
            font-weight: 600;
            color: #444;
        }
    </style>
</head>

<body>

    <div class="text-center" style="border:1px solid #000; padding:10px; margin-bottom:10px;">
        <h3>DD Pharmaceuticals Pvt. Ltd.</h3>
        <div>
            G-1/583,585,586, RIICO Industrial Area, Sitapura,<br>
            Tonk Road, Jaipur - 302022<br>
            <strong>ISO 9001:2015 Certified Company</strong><br>
            <strong>WHO-GMP Certified Company</strong>
        </div>
    </div>

    <table>
        <tr>
            <th style="text-align:center;" colspan="4">
                Raw Materials Requisition Sheet (Active Ingredient(s), excipients etc.)
            </th>

        </tr>
        <tr>
            <th width="20%">Product Name</th>
            <td width="30%">{{ $batch->bomMaster->finishedGood->name }}</td>
            <th width="20%">Batch No.</th>
            <td width="30%">{{ $batch->batch_number }}</td>
        </tr>
        <tr>
            <th>Material Requisition No.</th>
            <td colspan="3">{{ $batch->material_requisition_no ?? '' }}</td>
        </tr>

        <tr>
            <th>Line Clearance Given By</th>
            <td>{{ $batch->line_clearance_given_by }}</td>
            <th>Date</th>
            <td>{{ \Carbon\Carbon::parse($batch->mfg_date)->format('d-m-Y') }}</td>
        </tr>
    </table>


    <table>
        <thead>
            <tr class="text-center">
                <th>S.No.</th>
                <th>Name of Material</th>
                <th>Specification</th>
                <!-- <th>Batch No.</th>
                <th>Control Ref.</th>
                <th>Analytical Ref.</th> -->
                <th>Qty For Batch</th>
                <th>Overage</th>
                <th>Qty Issued</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($batch->items as $i => $item)
                <tr class="text-center">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->material->name }}</td>
                    <td>IP</td>
                    <!-- <td>{{ $item->material->code }}</td>
                <td>{{ $item->control_ref ?? '-' }}</td>
                <td>{{ $item->analytical_ref ?? '-' }}</td> -->
                    <td>{{ $item->base_quantity }} {{ strtoupper($item->uoms?->name) }} </td>
                    <td>{{ $item->overage_percent }}%</td>
                    <td><strong>{{ number_format($item->final_quantity, 3) }} {{ strtoupper($item->uoms?->name) }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br><br>

    <!-- APPROVALS -->
    <table class="table table-bordered text-center mt-3">
        <tr>

            <!-- CHEMIST -->
            <td width="33%">
                <strong class="d-block">Requisition Sheet Created by</strong>
                <strong class="d-block">Requisition Chemist</strong>
                <span class="small d-block mb-2">(Signature & Date)</span>

                <div class="approval-box">
                    @if ($chemistApproval)
                        <div class="approval-info">
                            <div class="approval-name">
                                {{ $chemistApproval->approver->full_name }}
                            </div>
                            <div class="approval-date">
                                {{ \Carbon\Carbon::parse($chemistApproval->approval_date)->format('d-m-Y') }}
                            </div>
                        </div>
                    @else
                        <div class="pending-text">Pending</div>
                    @endif
                </div>
            </td>

            <!-- HEAD PRODUCTION -->
            <td width="33%">
                <strong class="d-block">Verified By Head, Production</strong>
                <span class="small d-block mb-2">(Signature & Date)</span>

                <div class="approval-box">
                    @if ($productionApproval)
                        <div class="approval-info">
                            <div class="approval-name">
                                {{ $productionApproval->approver->full_name }}
                            </div>
                            <div class="approval-date">
                                {{ \Carbon\Carbon::parse($productionApproval->approval_date)->format('d-m-Y') }}
                            </div>
                        </div>
                    @else
                        <div class="pending-text">Pending</div>
                    @endif
                </div>
            </td>

            <!-- HEAD QA -->
            <td width="33%">
                <strong class="d-block">Verified By Head, QA</strong>
                <span class="small d-block mb-2">(Signature & Date)</span>

                <div class="approval-box">
                    @if ($qaApproval)
                        <div class="approval-info">
                            <div class="approval-name">
                                {{ $qaApproval->approver->full_name }}
                            </div>
                            <div class="approval-date">
                                {{ \Carbon\Carbon::parse($qaApproval->approval_date)->format('d-m-Y') }}
                            </div>
                        </div>
                    @else
                        <div class="pending-text">Pending</div>
                    @endif
                </div>
            </td>

        </tr>
    </table>

</body>

</html>
