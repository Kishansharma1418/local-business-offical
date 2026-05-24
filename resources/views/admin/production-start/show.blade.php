@extends('include.master')

@section('content')
<style>
    .flow-wrapper {
        background: #ffffff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .flow-steps {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 40px;
    }

    .flow-steps::before {
        content: "";
        position: absolute;
        top: 22px;
        left: 0;
        right: 0;
        height: 4px;
        background: #e5e7eb;
        z-index: 0;
    }

    .team-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: 0.3s ease;
    }

    .team-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        transform: translateY(-3px);
    }

    .avatar-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .role-badge {
        background: #e0f2fe;
        color: #0369a1;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 20px;
    }


    .flow-step {
        position: relative;
        text-align: center;
        z-index: 1;
        flex: 1;
    }

    .flow-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #374151;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        font-weight: 600;
        transition: 0.3s ease;
    }

    .flow-title {
        margin-top: 10px;
        font-size: 13px;
        font-weight: 600;
    }

    .flow-completed .flow-circle {
        background: #16a34a;
        color: white;
    }

    .flow-active .flow-circle {
        background: #2563eb;
        color: white;
        transform: scale(1.1);
    }

    .step-detail-card {
        background: #f9fafb;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e5e7eb;
        margin-top: 20px;
    }

    .section-title {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #374151;
    }

    .material-list li {
        padding: 6px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .material-list li:last-child {
        border-bottom: none;
    }

    .complete-btn {
        margin-top: 15px;
    }

    .card-header {
        font-size: 14px;
        letter-spacing: 0.5px;
    }

    .table th {
        font-size: 13px;
        font-weight: 600;
    }

    .table td {
        font-size: 13px;
    }
</style>
<style>
    .ipqc-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
    }

    .ipqc-card .card-body {
        background: #ffffff !important;
        padding: 25px;
    }

    .ipqc-title {
        font-size: 18px;
        font-weight: 600;
    }

    .ipqc-subtitle {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 15px;
    }

    .form-control {
        border-radius: 6px;
        font-size: 13px;
        padding: 6px 8px;
    }

    .table {
        font-size: 13px;
        background: #ffffff;
    }

    .table thead th {
        background: #f9fafb !important;
        font-weight: 600;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
        background: #ffffff;
    }

    .table input {
        height: 32px;
        font-size: 12px;
    }

    .section-label {
        font-size: 13px;
        font-weight: 600;
    }

    .note-text {
        font-size: 12px;
        color: #6b7280;
    }

    .required-error {
        border: 1px solid red !important;
    }
</style>

<style>
    .ipqc-card {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
    }

    .ipqc-card .card-body {
        background: #ffffff !important;
        padding: 25px;
    }

    .title {
        font-size: 15px;
        font-weight: 400;
    }

    .subtitle {
        font-size: 13px;
        color: #6b7280;
    }

    .table th {
        background: #f9fafb !important;
        font-size: 13px;
    }

    .form-control {
        font-size: 13px;
        border-radius: 6px;
    }

    .required-error {
        border: 1px solid red !important;
    }
</style>

<style>
    .card {
        border-radius: 12px;
    }

    .card-body {
        background: #ffffff !important;
    }

    .form-control {
        border-radius: 8px;
    }

    label {
        font-weight: 600;
    }


    input[readonly] {
        background-color: #f8f9fa !important;
    }

    .is-invalid {
        border-color: red;
    }
</style>
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">View Production Start</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('production-start.index') }}" class="text-decoration-none">Production
                        Start</a>
                </li>
                <li class="breadcrumb-item active">View Production Start</li>

            </ol>
        </nav>
    </div>
    @if ($productionFlowStart->status == 'completed')
    <div class="alert alert-success text-center fw-bold">
        🎉 Production Completed Successfully
    </div>
    @endif


    @if ($productionFlowStart->status == 'completed' && !$productionFlowStart->stock_in_done)
    <button class="btn btn-primary text-white mt-3"
        data-bs-toggle="modal"
        data-bs-target="#finishedGoodsModal">
        Stock In Finished Goods
    </button>

    @endif

    <div class="modal fade" id="finishedGoodsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Finished Goods Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST"
                    action="{{ route('production-start.stock-in',$productionFlowStart->id) }}"
                    enctype="multipart/form-data"
                    id="stockInForm">
                    @csrf

                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-6">
                                <label>Product Name</label>
                                <input type="text" class="form-control"
                                    value="{{ $productionFlowStart->bomMaster->finishedGood->name }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label>Batch No</label>
                                <input type="text" class="form-control"
                                    value="{{ $productionFlowStart->batch_number }}" readonly>
                            </div>

                        </div>

                        <hr>

                        <h5>Finished Goods Transfer Quantity</h5>

                        <div class="row">

                            <div class="col-md-6">
                                <label>Quantity to Warehouse</label>
                                <input type="number" name="finished_goods_qty" class="form-control si-req">
                            </div>

                            <div class="col-md-6">
                                <label>Batch Yield</label>
                                <input type="text" name="batch_yield" class="form-control si-req">
                            </div>

                        </div>

                        <hr>

                        <h5>Batch Release Sheet</h5>

                        <table class="table table-bordered">

                            <tr>
                                <th>Attachment</th>
                                <th>Status</th>
                            </tr>

                            <tr>
                                <td>Requisition Sheet for RM</td>
                                <td>
                                    <select name="requisition_sheet_rm" class="form-control attachment-select si-req" data-target="requisition_file">
                                        <option value="">Select Type</option>
                                        <option value="Yes">✔ Attached</option>
                                        <option value="No">✖ Not Attached</option>
                                    </select>

                                    <input type="file"
                                        name="requisition_sheet_rm_file"
                                        class="form-control mt-2 d-none requisition_file">
                                </td>
                            </tr>
                            <tr>
                                <td>Specimen of Carton</td>
                                <td>
                                    <select name="specimen_carton" class="form-control attachment-select si-req" data-target="carton_file">
                                        <option value="">Select Type</option>

                                        <option value="Yes">✔ Attached</option>
                                        <option value="No">✖ Not Attached</option>
                                    </select>
                                    <input type="file"
                                        name="specimen_carton_file"
                                        class="form-control mt-2 d-none carton_file">
                                </td>
                            </tr>

                            <tr>
                                <td>Specimen of Printed Foil</td>
                                <td>
                                    <select name="specimen_printed_foil" class="form-control attachment-select si-req" data-target="foil_file">
                                        <option value="">Select Type</option>
                                        <option value="Yes">✔ Attached</option>
                                        <option value="No">✖ Not Attached</option>
                                    </select>
                                    <input type="file"
                                        name="specimen_printed_foil_file"
                                        class="form-control mt-2 d-none foil_file">
                                </td>
                            </tr>

                            <tr>
                                <td>Test Report of Bulk Testing</td>
                                <td>
                                    <select name="bulk_testing_report" class="form-control attachment-select" data-target="bulk_testing_file">
                                        <option value="">Select Type</option>

                                        <option value="Yes">✔ Attached</option>
                                        <option value="No">✖ Not Attached</option>
                                    </select>
                                    <input type="file"
                                        name="bulk_testing_report_file" class="form-control mt-2 d-none bulk_testing_file">
                                </td>
                            </tr>

                            <tr>
                                <td>In-Process Checks</td>
                                <td>
                                    <select name="in_process_checks" class="form-control attachment-select" data-target="in_process_file">
                                        <option value="">Select Type</option>

                                        <option value="Yes">✔ Attached</option>
                                        <option value="No">✖ Not Attached</option>
                                    </select>
                                    <input type="file"
                                        name="in_process_checks_file" class="form-control mt-2 d-none in_process_file">
                                </td>
                            </tr>

                            <tr>
                                <td>Finished Product Report</td>
                                <td>
                                    <select name="finished_product_report" class="form-control attachment-select" data-target="finished_product_file">
                                        <option value="">Select Type</option>
                                        <option value="Yes">✔ Attached</option>
                                        <option value="No">✖ Not Attached</option>
                                    </select>
                                    <input type="file"
                                        name="finished_product_report_file" class="form-control mt-2 d-none finished_product_file">
                                </td>
                            </tr>

                            <tr>
                                <td>Incidence/Deviation/OOS/(if any)</td>
                                <td>
                                    <select name="if_any" class="form-control attachment-select" data-target="if_any_product_file">
                                        <option value="">Select Type</option>
                                        <option value="Yes">✔ Attached</option>
                                        <option value="No">✖ Not Attached</option>
                                    </select>
                                    <input type="file"
                                        name="if_any_file" class="form-control mt-2 d-none if_any_product_file">
                                </td>
                            </tr>

                            </td>
                            </tr>

                            <tr>
                                <td>
                                    Analytical report No. of Finished Product & Date<br>
                                    (Attach copy of Analytical report)
                                </td>
                                <td>
                                    <!-- Select -->
                                    <select name="analytic_report_no" class="form-control attachment-select" data-target="analytic_product_file">
                                        <option value="">Select Type</option>
                                        <option value="Yes">✔ Attached</option>
                                        <option value="No">✖ Not Attached</option>
                                    </select>

                                    <!-- Date Field -->
                                    <input type="date"
                                        name="analytic_report_date"
                                        class="form-control mt-2">

                                    <!-- File Upload -->
                                    <input type="file"
                                        name="analytic_report_no_file"
                                        class="form-control mt-2 d-none analytic_product_file">
                                </td>
                            </tr>

                            </td>
                            </tr>
                        </table>

                        <hr>

                        <div class="row">

                            <div class="col-md-4">
                                <label>Verified By Head Production</label>
                                <input type="text" name="verified_head_production" class="form-control si-req">
                            </div>

                            <div class="col-md-4">
                                <label>Verified By Head QC</label>
                                <input type="text" name="verified_head_qc" class="form-control si-req">
                            </div>

                            <div class="col-md-4">
                                <label>Verified By Head QA</label>
                                <input type="text" name="verified_head_qa" class="form-control si-req">
                            </div>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Qty Release for Sale</label>
                                <input type="number" name="release_qty" class="form-control si-req">
                            </div>

                            <div class="col-md-6">
                                <label>Batch Released By QA</label>
                                <input type="text" name="batch_released_by_qa" class="form-control si-req">
                            </div>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary text-white ">
                            Save & Stock In
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
    @php
    $viewStep =
    $productionFlowStart->status == 'Pending'
    ? null
    : request('view_step') ?? $productionFlowStart->current_step;

    $completedStep = $productionFlowStart->current_step;
    $qc = \App\Models\QualtyCheck::where('production_flow_start_id', $productionFlowStart->id)
    ->where('step_number', $viewStep)
    ->latest()
    ->first();

    $stepRecord = \App\Models\ProductionFlowStep::with('user')
    ->where('production_flow_start_id', $productionFlowStart->id)
    ->where('step_number', $viewStep)
    ->first();

    @endphp
    <div class="card p-3">

        <div class="card border-0 shadow-sm mb-4 bg-white ">
            <div class="card-body py-3">

                <div class="row align-items-center">

                    <div class="col-md-4">
                        <h5 class="fw-semibold mb-1 text-dark">
                            {{ $productionFlowStart->bomMaster->bom_number ?? 'BOM' }}
                        </h5>
                        <div class="small text-dark">
                            BOM Version:
                            <strong>{{ $productionFlowStart->bomMaster->bom_version ?? '-' }}</strong>
                        </div>
                        <div class="small text-dark">
                            Batch No:
                            <strong>{{ $productionFlowStart->batch_number }}</strong>
                        </div>
                        <div class="small text-dark">
                            MFG Date:
                            <strong>
                                {{ $productionFlowStart->mfg_date ? \Carbon\Carbon::parse($productionFlowStart->mfg_date)->format('d-m-Y') : '-' }}
                            </strong>
                        </div>


                    </div>

                    <div class="col-md-6">
                        <div class="small text-dark" style="text-align:left; font-size:16px;">
                            Product:
                            <strong>{{ $productionFlowStart->bomMaster->finishedGood->name ?? '-' }}</strong>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">


                        @if ($stepRecord && $stepRecord->step_status == 'completed')
                        <div class="small text-dark">
                            Completed By:
                            <strong>
                                {{ $stepRecord->user->full_name ?? 'N/A' }}
                            </strong>
                        </div>


                        <div class="small text-dark">
                            Created At:
                            <strong>
                                {{ $productionFlowStart->created_at->format('d-m-Y h:i A') }}

                            </strong>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ($productionFlowStart->status == 'Pending')
        <div class="alert alert-warning text-center">
            ⚠ Production Not Started Yet
            <br>
            Please Click <b>Start Production</b> to Begin
        </div>
        @endif

        <div class="flow-wrapper">

            <div class="flow-steps">
                @foreach ($processes as $index => $process)
                @php
                $stepName = strtolower(trim($process->bomType->name));

                $isPackingStep = str_contains($stepName, 'alu') ||
                str_contains($stepName, 'blister') ||
                str_contains($stepName, 'strip');

                $stepNumber = $index + 1;
                $state = '';

                if ($productionFlowStart->status == 'completed') {
                $state = 'flow-completed';
                } elseif ($completedStep > $stepNumber) {
                $state = 'flow-completed';
                } elseif ($viewStep == $stepNumber) {
                $state = 'flow-active';
                }

                $statusText = '<span class="badge bg-secondary">Yet To Start</span>';

                if ($productionFlowStart->status == 'completed') {
                $statusText = '<span class="badge bg-success">Completed</span>';
                } elseif ($completedStep > $stepNumber) {
                $statusText = '<span class="badge bg-success">Completed</span>';
                } elseif ($completedStep == $stepNumber) {
                $statusText = '<span class="badge bg-warning text-dark">In Progress</span>';
                }

                @endphp


                <div class="flow-step {{ $state }}">

                    @if ($productionFlowStart->status != 'Pending')
                    <a href="{{ route('production-start.show', [$productionFlowStart->id, 'view_step' => $stepNumber]) }}"
                        class="text-decoration-none text-dark">
                        @endif



                        <div class="flow-circle">
                            {{ $stepNumber }}
                        </div>

                        <div class="flow-title">
                            {{ $process->bomType->name }}

                        </div>
                        {{-- Start Production Button Only On First Step --}}
                        @if ($productionFlowStart->status == 'Pending' && $stepNumber == 1)
                        <div class="mt-2">
                            <button class="btn btn-primary btn-sm text-white" data-bs-toggle="modal"
                                data-bs-target="#startProductionModal">
                                Start Production
                            </button>
                        </div>
                        @endif
                        <div class="small text-muted mt-1">
                            {!! $statusText !!}

                        </div>
                        @if ($productionFlowStart->status != 'Pending')
                    </a>
                    @endif

                </div>
                @endforeach
            </div>


            @foreach ($processes as $index => $process)
            <!-- @php
                        $stepName = strtolower(trim($process->bomType->name));
                        
                        $isQcStep = $stepName === 'quality check';
                        
                    @endphp -->


            @php
            $stepName = strtolower(trim($process->bomType->name));

            $isQcStep = $stepName === 'quality check';

            $isPackingStep = str_contains($stepName, 'alu') ||
            str_contains($stepName, 'blister') ||
            str_contains($stepName, 'strip');

            $isCurrentStep = $viewStep == ($index + 1);
            $isCompletedProduction = $productionFlowStart->status == 'completed';
            @endphp
            @if ($viewStep == $index + 1)
            <div class="step-detail-card">

                <div class="row">

                    {{-- MATERIAL TABLE --}}
                    @if (!$isQcStep)
                    <div class="col-lg-12">
                        <div class="card border-0 shadow-sm  mb-4">
                            <div class="card-header bg-light fw-semibold bg-white text-dark">
                                Materials Required
                            </div>


                            <div class="card-body p-0 bg-white">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0 align-middle ">
                                        <thead class="table-light ">
                                            <tr>
                                                <th>Material</th>
                                                <th>Base Qty</th>
                                                <th>Overage %</th>
                                                <th>Final Qty</th>
                                                <th>Control Ref.</th>
                                                <th>Report No</th>
                                                <th>Weight By</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($process->items as $item)
                                            @php
                                            $materialId = $item->bomItem->material_id ?? null;

                                            $flowItem = $productionFlowStart->flowItems
                                            ->where('material_id', $materialId)
                                            ->first();
                                            @endphp

                                            <tr>
                                                <td>{{ $item->bomItem->material->name ?? '-' }}</td>

                                                <td>
                                                    {{ $flowItem->base_quantity ?? 0 }}
                                                </td>

                                                <td>
                                                    {{ $flowItem->overage_percent ?? 0 }}
                                                </td>

                                                <td class="fw-bold">

                                                    <span
                                                        class="{{ $flowItem && $flowItem->final_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                                        {{ $flowItem->final_quantity ?? 0 }}
                                                    </span>

                                                    {{-- <span class="text-dark ms-1">
                                                                            {{ $flowItem->uom }}
                                                    </span> --}}

                                                </td>
                                                <td>
                                                    {{ $flowItem->control_ref_no ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $flowItem->analytical_report_no ?? '-' }}
                                                </td>
                                                <td>
                                                    {{ $flowItem->weight_by ?? '-' }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-12 mt-4">

                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white fw-semibold">
                                Assigned Team
                            </div>

                            <div class="card-body">

                                <div class="row g-4">

                                    @if ($stepName == 'quality check')
                                    {{-- QC STEP (Only QC Team) --}}
                                    @foreach ($qcTeam->groupBy('role_id') as $roleId => $members)
                                    @php
                                    $roleName = optional($members->first()->role)->name;
                                    @endphp

                                    <div class="col-md-4">
                                        <div class="border rounded p-3 h-100 bg-white">

                                            <div class="fw-semibold mb-3 text-dark border-bottom pb-2">
                                                {{ $roleName ?? 'Role' }}
                                            </div>

                                            @foreach ($members as $team)
                                            <div class="mb-2 small">
                                                <i class="ri-user-line me-1 text-muted"></i>
                                                {{ $team->user->full_name ?? '-' }}
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    @php
                                    $stepTeams = $processTeams[$process->id] ?? collect();
                                    @endphp

                                    @if ($stepTeams->count())
                                    @foreach ($stepTeams as $roleId => $members)
                                    @php
                                    $roleName = optional($members->first()->role)->name;
                                    @endphp

                                    <div class="col-md-4">
                                        <div class="border rounded p-3 h-100 bg-white">

                                            <div
                                                class="fw-semibold mb-3 text-dark border-bottom pb-2">
                                                {{ $roleName ?? 'Role' }}
                                            </div>

                                            @foreach ($members as $team)
                                            <div class="mb-2 small">
                                                <i class="ri-user-line me-1 text-muted"></i>
                                                {{ $team->user->full_name ?? '-' }}
                                            </div>
                                            @endforeach

                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <div class="col-md-4">
                                        <div class="border rounded p-3 h-100 bg-light text-muted">
                                            No Team Assigned
                                        </div>
                                    </div>
                                    @endif
                                    @endif

                                </div>

                            </div>
                        </div>

                    </div>

                    {{-- @if ($stepRecord && $stepRecord->step_status == 'completed')
                <div class="alert alert-success mt-3">
                                        <strong>Completed By:</strong>
                                        {{ $stepRecord->user->full_name ?? 'N/A' }}
                    <br>
                    <strong>Completed At:</strong>
                    {{ \Carbon\Carbon::parse($stepRecord->completed_at)->format('d-m-Y H:i') }}
                </div>
                @endif --}}
                @php
                $isQcUser = $qcTeam->where('user_id', auth()->id())->count();
                @endphp
                @if ($stepName == 'quality check')
                @if ($isQcStep && $isQcUser)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-primary text-white">
                        Quality Check
                    </div>

                    <div class="card-body">

                        {{-- STEP 1: Upload Report --}}
                        <form method="POST"
                            action="{{ route('production-start.update', $productionFlowStart->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="action_type" value="QC_UPLOAD_REPORT">
                            <input type="hidden" name="step" value="{{ $viewStep }}">

                            <div class="row align-items-end">
                                <div class="col-md-6">
                                    <label class="form-label">Upload QC Report (PDF)</label>
                                    <input type="file" name="qc_report" class="form-control"
                                        accept="application/pdf" required>
                                </div>

                                <div class="col-md-3">
                                    <button class="btn btn-primary text-white w-100">
                                        Upload Report
                                    </button>
                                </div>
                            </div>
                        </form>

                        {{-- STEP 2: Show Status Form Only If Pending QC Exists --}}
                        @php
                        $pendingQc = \App\Models\QualtyCheck::where(
                        'production_flow_start_id',
                        $productionFlowStart->id,
                        )
                        ->where('step_number', $viewStep)
                        ->whereNull('status')
                        ->latest()
                        ->first();
                        @endphp

                        @if ($pendingQc)
                        <hr>

                        <input type="hidden" name="action_type" value="QC_UPLOAD_REPORT">
                        <input type="hidden" name="step" value="{{ $viewStep }}">

                        <div class="row align-items-end">
                            <div class="col-md-6">
                                <label class="form-label">Upload QC Report (PDF)</label>
                                <input type="file" name="qc_report" class="form-control"
                                    accept="application/pdf" required>
                            </div>

                            <div class="col-md-3">
                                <button class="btn btn-primary text-white w-100">
                                    Upload Report
                                </button>
                            </div>
                        </div>
                        </form>

                        {{-- STEP 2: Show Status Form Only If Pending QC Exists --}}
                        @php
                        $pendingQc = \App\Models\QualtyCheck::where(
                        'production_flow_start_id',
                        $productionFlowStart->id,
                        )
                        ->where('step_number', $viewStep)
                        ->whereNull('status')
                        ->latest()
                        ->first();
                        @endphp

                        @if ($pendingQc)
                        <hr>

                        <!-- <form method="POST"
                            action="{{ route('production-start.update', $productionFlowStart->id) }}">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="action_type" value="QC_UPDATE_STATUS">
                            <input type="hidden" name="qc_id" value="{{ $pendingQc->id }}">
                            <input type="hidden" name="step" value="{{ $viewStep }}">

                            <div class="row">
                                <div class="col-md-4">
                                    <label>Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="">Select</option>
                                        <option value="approved">Approve</option>
                                        <option value="rejected">Reject</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label>Remarks</label>
                                    <textarea name="remarks" class="form-control" required></textarea>
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-primary text-white w-100">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form> -->
                        @endif

                    </div>
                </div>
                @endif
                @endif
                @php
                $qcHistory = \App\Models\QualtyCheck::with('checker')
                ->where('production_flow_start_id', $productionFlowStart->id)
                ->where('step_number', $viewStep)
                ->latest()
                ->get();
                @endphp

                <!-- @if ($qcHistory->count())
                <div class="mt-4">
                    <h6 class="fw-bold">QC Attempt History</h6>

                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Report</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Checked By</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($qcHistory as $key => $history)
                            <tr>
                                <td>{{ $key + 1 }}</td>

                                <td>
                                    <a href="{{ asset('storage/' . $history->report_path) }}"
                                        target="_blank">
                                        View PDF
                                    </a>
                                </td>

                                <td>
                                    @if ($history->status)
                                    <span
                                        class="badge 
                                            {{ $history->status == 'approved' ? 'bg-success' : 'bg-danger' }}">
                                        {{ strtoupper($history->status) }}
                                    </span>
                                    @else
                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>
                                    @endif
                                </td>

                                <td>{{ $history->remarks ?? '-' }}</td>

                                <td>{{ $history->checker->full_name ?? 'N/A' }}</td>

                                <td>{{ $history->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif -->


                <!-- @if ($qc && $qc->report_path)
                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            QC Report Preview
                        </div>
                        <div class="card-body">

                            <p>
                                <strong>Status:</strong>
                                <span
                                    class="badge 
                                                            {{ $qc->status == 'approved' ? 'bg-success' : 'bg-danger' }}">
                                    {{ strtoupper($qc->status) }}
                                </span>
                            </p>

                            <p><strong>Remarks:</strong> {{ $qc->remarks }}</p>

                            <iframe src="{{ asset('storage/' . $qc->report_path) }}" width="80%"
                                height="500px" style="border:1px solid #ddd; border-radius:8px;">
                            </iframe>

                        </div>
                    </div>
                    @endif -->
            </div>
        </div>


        @else
        @php
        $isCurrentStep = $viewStep == $productionFlowStart->current_step;
        $isCompletedProduction = $productionFlowStart->status == 'completed';
        @endphp

        @if (!$isQcStep && $isCurrentStep && !$isCompletedProduction)
        <form method="POST"
            action="{{ route('production-start.update', $productionFlowStart->id) }}" class="mt-4">
            @csrf
            @method('PUT')

            <input type="hidden" name="action_type" value="COMPLETE_STEP">
            <input type="hidden" name="step" value="{{ $index + 1 }}">

            <button class="btn btn-primary text-white complete-btn">
                Complete & Move Next →
            </button>
        </form>
        @endif
        @endif

    </div>
    @endif


    @if($isPackingStep && $viewStep == ($index + 1))
    @php
    $isPackingSaved = !empty($packingDetail);
    @endphp
    <div class="d-flex gap-2 mb-3 mt-4">

        <button onclick="showSection('packingSection')" class="btn btn-outline-primary">
            8.0 Packing Details
        </button>

        <button onclick="showSection('page15Section')" class="btn btn-outline-success">
            8.3 / 9.0 Page 15
        </button>

        <button onclick="showSection('page16Section')" class="btn btn-outline-dark">
            10.0 Reconciliation
        </button>

    </div>
    <div id="packingSection" class="form-section d-none">

        <div class="card mt-4 shadow-sm">
            <div class="card-body">

                {{-- PDF BUTTON (ONLY AFTER SAVE) --}}
                @if($isPackingSaved)
                <a href="{{ route('production-start.packing-pdf',$productionFlowStart->id) }}"
                    class="btn btn-primary mb-3 text-white">
                    Download Packing PDF
                </a>
                @endif

                <h5 class="text-center fw-bold mb-3">
                    D.D. Pharmaceuticals Pvt. Ltd.
                </h5>

                <form method="POST"
                    id="packingForm"
                    action="{{ route('production-start.update', $productionFlowStart->id) }}">

                    @csrf
                    @method('PUT')

                    <input type="hidden" name="action_type" value="SAVE_PACKING_DETAILS">
                    <input type="hidden" name="step" value="{{ $viewStep }}">

                    {{-- Product Name + Batch --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Product Name</label>
                            <input type="text" class="form-control"
                                value="{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Batch No</label>
                            <input type="text" class="form-control"
                                value="{{ $productionFlowStart->batch_number }}" readonly>
                        </div>
                    </div>

                    <hr>
                    <h6 class="fw-bold">8.0 Line Clearance For Blister/Alu-Alu/Strip Machine</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Previous Product *</label>
                            <input type="text" name="previous_product"
                                class="form-control required-field"
                                value="{{ $packingDetail->previous_product ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-6">
                            <label>Previous Product Batch No *</label>
                            <input type="text" name="previous_batch_no"
                                class="form-control required-field"
                                value="{{ $packingDetail->previous_batch_no ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Line Clearance Given By (Production)/Date</label>
                        <input type="text" class="form-control"
                            value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                            readonly>
                    </div>

                    <hr>
                    <h6 class="fw-bold">8.1 Blister/Alu-Alu/Strip Machine</h6>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Date *</label>
                            <input type="date" name="packing_date"
                                class="form-control required-field"
                                value="{{ $packingDetail->packing_date ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-4">
                            <label>Machine I.D. *</label>
                            <input type="text" name="machine_id"
                                class="form-control required-field"
                                value="{{ $packingDetail->machine_id ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-4">
                            <label>M/c Operated By *</label>
                            <input type="text" name="machine_operator"
                                class="form-control required-field"
                                value="{{ $packingDetail->machine_operator ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label>BFR Temperature *</label>
                            <input type="text" name="bfr_temperature"
                                class="form-control required-field"
                                value="{{ $packingDetail->bfr_temperature ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label>SFR Temperature *</label>
                            <input type="text" name="sfr_temperature"
                                class="form-control required-field"
                                value="{{ $packingDetail->sfr_temperature ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label>Duration of Operation</label>
                            <input type="text" name="duration"
                                class="form-control"
                                value="{{ $packingDetail->duration ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Verified By / Date</label>
                        <input type="text" class="form-control"
                            value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                            readonly>
                    </div>

                    <hr>
                    <h6 class="fw-bold">8.2 Overprinting Details</h6>

                    {{-- CARTON --}}
                    <h6 class="mt-3">8.2.1 Carton</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Batch No</label>
                            <input type="text" name="carton_batch_no" class="form-control"
                                value="{{ $packingDetail->carton_batch_no ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-3">
                            <label>Mfd.</label>
                            <input type="date" name="carton_mfd" class="form-control"
                                value="{{ $packingDetail->carton_mfd ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-3">
                            <label>Exp.</label>
                            <input type="date" name="carton_exp" class="form-control"
                                value="{{ $packingDetail->carton_exp ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>M.R.P (Inclusive of all taxes) For strip of 10 tablets</label>
                            <input type="number" step="0.01" name="carton_mrp" class="form-control"
                                value="{{ $packingDetail->carton_mrp ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>Printed Date</label>
                            <input type="date" name="carton_printed_date" class="form-control"
                                value="{{ $packingDetail->carton_printed_date ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>
                        <!-- <div class="mt-3">
                            <label>Verified By / Date</label>
                            <input type="text" class="form-control"
                                value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                                readonly>
                        </div> -->
                    </div>

                    {{-- FOIL --}}
                    <h6 class="mt-4">8.2.2 Foil</h6>

                    <div class="row">
                        <div class="col-md-6">
                            <label>Batch No</label>
                            <input type="text" name="foil_batch_no" class="form-control"
                                value="{{ $packingDetail->foil_batch_no ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-3">
                            <label>Mfd.</label>
                            <input type="date" name="foil_mfd" class="form-control"
                                value="{{ $packingDetail->foil_mfd ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>
                        <div class="col-md-3">
                            <label>Exp.</label>
                            <input type="date" name="foil_exp" class="form-control"
                                value="{{ $packingDetail->foil_exp ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>M.R.P (Inclusive of all taxes) For strip of 10 tablets</label>
                            <input type="number" step="0.01" name="foil_mrp" class="form-control"
                                value="{{ $packingDetail->foil_mrp ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>Printed Date</label>
                            <input type="date" name="foil_printed_date" class="form-control"
                                value="{{ $packingDetail->foil_printed_date ?? '' }}"
                                {{ $isPackingSaved ? 'readonly' : '' }}>
                        </div>
                        <!-- <div class="mt-3">
                            <label>Verified By / Date</label>
                            <input type="text" class="form-control"
                                value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                                readonly>
                        </div> -->
                    </div>

                    @if(!$isPackingSaved)
                    <div class="mt-4 text-end">
                        <button class="btn btn-primary text-white">
                            Save Packing Details
                        </button>
                    </div>
                    @else
                    <div class="alert alert-success mt-3">
                        Packing Details Already Submitted ✅
                    </div>
                    @endif
                </form>

            </div>
        </div>
    </div>

    @php
    $isPage15Saved = $page15Logs->count() > 0;
    @endphp
    <div id="page15Section" class="form-section d-none">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- PDF BUTTON (AFTER SAVE ONLY) --}}
        @if($isPage15Saved)
        <div class="mb-3 text-end">
            <a href="{{ route('production-start.page15-pdf', $productionFlowStart->id) }}"
                class="btn btn-primary text-white">
                Download Page 15 PDF
            </a>
        </div>
        @endif

        <div class="card shadow-sm" style="background:#fff;">
            <div class="card-body">

                <form method="POST"
                    action="{{ route('production-start.update', $productionFlowStart->id) }}"
                    onsubmit="return validatePage15Form()">

                    @csrf
                    @method('PUT')

                    <input type="hidden" name="action_type" value="SAVE_PAGE15_BULK">
                    <input type="hidden" name="step" value="{{ $viewStep }}">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Product Name</label>
                            <input type="text" class="form-control"
                                value="{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}" readonly>
                        </div>
                        <div class="col-md-6">
                            <label>Batch No</label>
                            <input type="text" class="form-control"
                                value="{{ $productionFlowStart->batch_number }}" readonly>
                        </div>
                    </div>
                    <h6 class="fw-bold mt-3">8.3 Leak Test Details</h6>

                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
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
                            @for($i = 0; $i < 6; $i++)

                                @php
                                $log=$page15Logs[$i] ?? null;
                                @endphp

                                <tr>
                                <td>{{ $i+1 }}</td>

                                <td>
                                    <input type="date"
                                        name="rows[{{ $i }}][leak_date]"
                                        class="form-control leak_date"
                                        value="{{ $log->leak_date ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                </td>

                                <td>
                                    <input type="time"
                                        name="rows[{{ $i }}][leak_time]"
                                        class="form-control"
                                        value="{{ $log->leak_time ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                </td>

                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][leak_done_by]"
                                        class="form-control"
                                        value="{{ $log->leak_done_by ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                </td>

                                <td>
                                    <select name="rows[{{ $i }}][leak_result]"
                                        class="form-control"
                                        {{ $isPage15Saved ? 'disabled' : '' }}>
                                        <option value="">Select</option>
                                        <option value="Pass" {{ $log && $log->leak_result=='Pass' ? 'selected' : '' }}>Pass</option>
                                        <option value="Fail" {{ $log && $log->leak_result=='Fail' ? 'selected' : '' }}>Fail</option>
                                    </select>
                                </td>

                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][leak_remarks]"
                                        class="form-control"
                                        value="{{ $log->leak_remarks ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                </td>
                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][leak_verified_by]"
                                        class="form-control"
                                        value="{{ $log->leak_verified_by ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                    </tr>

                                    @endfor
                        </tbody>
                    </table>

                    <h6 class="fw-bold mt-4">9.0 Packing</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Previous Product</label>
                            <input type="text"
                                name="previous_product"
                                class="form-control"
                                value="{{ $page15Logs[0]->previous_product ?? '' }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Previous Product Batch No</label>
                            <input type="text"
                                name="previous_product_batch_no"
                                class="form-control"
                                value="{{ $page15Logs[0]->previous_product_batch_no ?? '' }}">
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold">
                                Line Clearance Given By (Production)/Date
                            </label>

                            <input type="text"
                                name="line_clierence_by"
                                class="form-control"
                                value="{{ $page15Logs[0]->line_clierence_by ?? '' }}">
                        </div>
                    </div>
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>S.No</th>
                                <th>Date</th>
                                <th> Strip Checked By <span class="text-danger">*</span></th>
                                <th>Carton Packing Done By <span class="text-danger">**</span></th>
                                <th>Packed Carton</th>
                                <th>Rejected Carton</th>
                                <th>Verified By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 0; $i < 6; $i++)

                                @php
                                $log=$page15Logs[$i] ?? null;
                                @endphp

                                <tr>
                                <td>{{ $i+1 }}</td>

                                <td>
                                    <input type="date"
                                        name="rows[{{ $i }}][packing_date]"
                                        class="form-control packing_date"
                                        value="{{ $log->packing_date ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                </td>

                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][strip_checked_by]"
                                        class="form-control"
                                        value="{{ $log->strip_checked_by ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                </td>

                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][carton_packing_done_by]"
                                        class="form-control"
                                        value="{{ $log->carton_packing_done_by ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                </td>

                                <td>
                                    <input type="number"
                                        name="rows[{{ $i }}][packed_carton_count]"
                                        class="form-control"
                                        value="{{ $log->packed_carton_count ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                </td>

                                <td>
                                    <input type="number"
                                        name="rows[{{ $i }}][rejected_carton_count]"
                                        class="form-control"
                                        value="{{ $log->rejected_carton_count ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                </td>
                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][packing_verified_by]"
                                        class="form-control"
                                        value="{{ $log->packing_verified_by ?? '' }}"
                                        {{ $isPage15Saved ? 'readonly' : '' }}>
                                    </tr>

                                    @endfor
                        </tbody>
                    </table>
                    <div class="mt-2">
                        <p class="mb-1">
                            <span class="text-danger">*</span> Note: Defects Such as misprint. cuts on the foil,missing tablets,improper sealing etc.shall be rejected during strip Checking.
                        </p>
                        <p class="mb-0">
                            <span class="text-danger">**</span>Note: Defects such as misprint,torn-out,deformed cartons etc. shall be rejected during carton packing.
                        </p>
                    </div>
                    @if(!$isPage15Saved)
                    <div class="text-end mt-4">
                        <button class="btn btn-primary text-white px-4">
                            Save Page
                        </button>
                    </div>
                    @else
                    <div class="alert alert-success mt-3 text-center">
                        Page Already Submitted ✅
                    </div>
                    @endif

                </form>

            </div>
        </div>
    </div>
    @php
    $page16Data = $page16Reconciliations->keyBy('material_type');
    $isPage16Saved = $page16Reconciliations->count() > 0;
    @endphp

    <div id="page16Section" class="form-section d-none">

        {{-- ✅ DOWNLOAD BUTTON ONLY AFTER SAVE --}}
        @if(!$page16Data->isEmpty())
        <div class="mb-3">
            <a href="{{ route('production-start.page16-pdf', $productionFlowStart->id) }}"
                class="btn btn-primary text-white">
                Download Page 16 PDF
            </a>
        </div>
        @endif

        <form id="page16Form" method="POST" action="{{ route('production-start.update',$productionFlowStart->id) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="action_type" value="SAVE_PAGE16">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Product Name</label>
                    <input type="text" class="form-control"
                        value="{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}" readonly>
                </div>
                <div class="col-md-6">
                    <label>Batch No</label>
                    <input type="text" class="form-control"
                        value="{{ $productionFlowStart->batch_number }}" readonly>
                </div>
            </div>
            <h5 class="fw-bold mt-4">10.0 Reconciliation of Packing Material</h5>

            <table class="table table-bordered text-center align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Particular</th>
                        <th>Alu-Alu / Blister Foil</th>
                        <th>PVC/Base Foil</th>
                        <th>Carton</th>
                    </tr>
                </thead>

                <tbody>

                    @php
                    $rows = [
                    'std_qty'=>'Std Qty For Batch',
                    'qty_issued'=>'Qty Issued (a)',
                    'additional_required'=>'Additional Required (b)',
                    'total_issued'=>'Total Issued A (a+b)',
                    'packed_qty'=>'Packed Qty (B)',
                    'sample_qty'=>' QC Sample + Control + Stability + Other sample (C) ',
                    'specimen_qty'=>'Specimen Sample (D)',
                    'total_packed'=>'Total Packed (B+C+D)',
                    'rejection_qty'=>'Rejection (F)',
                    'total_consumed'=>'Total Consumed For batch (X)=E+F',
                    'returned_qty'=>'Returned to Store (Y)',
                    'final_quantity'=> 'Total = X+Y or equal to A',
                    ];
                    @endphp

                    @php
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

                        <td>
                            <input
                                type="number"
                                step="0.01"
                                name="materials[{{ $type }}][{{ $field }}]"
                                class="form-control required-field"
                                value="{{ $value }}"
                                {{ $record ? 'readonly' : '' }}>
                        </td>

                        @endforeach

                    </tr>
                    @endforeach

                </tbody>
            </table>

            {{-- ✅ SAVE BUTTON --}}
            @if($page16Data->isEmpty())
            <div class="text-end mt-3">
                <button class="btn btn-success text-white">
                    Save Reconciliation
                </button>
            </div>
            @else
            <div class="alert alert-success mt-3">
                Page Already Submitted ✅
            </div>
            @endif

        </form>
    </div>
    @endif


    @if($stepName == 'coating' && $viewStep == ($index + 1))

    @php
    $thicknessData = $coatingCheck ? json_decode($coatingCheck->thickness,true) : [];
    $weightData = $coatingCheck ? json_decode($coatingCheck->weight,true) : [];
    $hardnessData = $coatingCheck ? json_decode($coatingCheck->hardness,true) : [];

    $isCoatingSaved = !empty($coatingCheck);
    @endphp
    <div class="card mt-4 shadow-sm ipqc-card">
        <div class="card-body">

            {{-- ✅ PDF button after save --}}
            @if($isCoatingSaved)
            <div class="text-end mb-3">
                <a href="{{ route('production.coating.pdf', $productionFlowStart->id) }}"
                    class="btn btn-primary text-white">
                    Download Coating PDF
                </a>
            </div>
            @endif

            <h5 class="text-center fw-bold title">
                D.D. Pharmaceuticals Pvt. Ltd.
            </h5>

            <p class="text-center subtitle mb-4">
                6.3 Inprocess check of Coated Tablets
            </p>

            <form id="coatingForm" method="POST"
                action="{{ route('production-start.update',$productionFlowStart->id) }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="action_type" value="SAVE_COATING_CHECK">
                <input type="hidden" name="step" value="{{ $viewStep }}">

                {{-- TOP --}}
                <div class="row mb-3 g-3">
                    <div class="col-md-6">
                        <label>Product Name</label>
                        <input type="text" class="form-control"
                            value="{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}"
                            readonly>
                    </div>

                    <div class="col-md-6">
                        <label>Batch No</label>
                        <input type="text" class="form-control"
                            value="{{ $productionFlowStart->batch_number }}"
                            readonly>
                    </div>
                </div>

                <div class="row">

                    {{-- THICKNESS --}}
                    <div class="col-md-4">
                        <h6 class="fw-bold">Thickness of Tablets</h6>
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i=1;$i<=20;$i++)
                                    <tr>
                                    <td>{{ $i }}</td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="thickness[]"
                                            class="form-control thickness required-field"
                                            value="{{ $thicknessData[$i-1] ?? '' }}"
                                            {{ $isCoatingSaved ? 'readonly' : '' }}
                                            onkeyup="calculateAverage('thickness','avg_thickness')">
                                    </td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>
                    </div>

                    {{-- WEIGHT --}}
                    <div class="col-md-4">
                        <h6 class="fw-bold">Weight of Tablets</h6>
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i=1;$i<=20;$i++)
                                    <tr>
                                    <td>{{ $i }}</td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="weight[]"
                                            class="form-control weight required-field"
                                            value="{{ $weightData[$i-1] ?? '' }}"
                                            {{ $isCoatingSaved ? 'readonly' : '' }}
                                            onkeyup="calculateAverage('weight','avg_weight')">
                                    </td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>
                    </div>

                    {{-- HARDNESS --}}
                    <div class="col-md-4">
                        <h6 class="fw-bold">Hardness of Tablets</h6>
                        <table class="table table-bordered text-center">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for($i=1;$i<=20;$i++)
                                    <tr>
                                    <td>{{ $i }}</td>
                                    <td>
                                        <input type="number" step="0.01"
                                            name="hardness[]"
                                            class="form-control hardness required-field"
                                            value="{{ $hardnessData[$i-1] ?? '' }}"
                                            {{ $isCoatingSaved ? 'readonly' : '' }}
                                            onkeyup="calculateAverage('hardness','avg_hardness')">
                                    </td>
                                    </tr>
                                    @endfor
                            </tbody>
                        </table>
                    </div>

                </div>

                {{-- AVERAGE --}}
                <div class="row mt-3 g-3">
                    <div class="col-md-4">
                        <label>Average Thickness</label>
                        <input type="text" id="avg_thickness" name="average_thickness"
                            value="{{ $coatingCheck->average_thickness ?? '' }}"
                            class="form-control required-field" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Average Weight (20 Tablets)</label>
                        <input type="text" id="avg_weight" name="average_weight"
                            value="{{ $coatingCheck->average_weight ?? '' }}"
                            class="form-control required-field" readonly>
                    </div>

                    <div class="col-md-4">
                        <label>Average Hardness (20 Tablets)</label>
                        <input type="text" id="avg_hardness" name="average_hardness"
                            value="{{ $coatingCheck->average_hardness ?? '' }}"
                            class="form-control required-field" readonly>
                    </div>
                </div>

                {{-- EXTRA --}}
                <div class="row mt-3 g-3">

                    <div class="col-md-4">
                        <label>Date Tablets Inspected</label>
                        <input type="date" name="inspection_date"
                            value="{{ $coatingCheck->inspection_date ?? '' }}"
                            class="form-control required-field"
                            {{ $isCoatingSaved ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4">
                        <label>Total Weight of Coated Tablets</label>
                        <input type="number" step="0.01" name="total_weight_coated"
                            value="{{ $coatingCheck->total_weight_coated ?? '' }}"
                            class="form-control required-field"
                            {{ $isCoatingSaved ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4">
                        <label>Total Weight of Rejected Tablets</label>
                        <input type="number" step="0.01" name="total_weight_rejected"
                            value="{{ $coatingCheck->total_weight_rejected ?? '' }}"
                            class="form-control required-field"
                            {{ $isCoatingSaved ? 'readonly' : '' }}>
                    </div>

                </div>

                <p class="small text-muted mt-3">
                    Rejected Coated Tablets include disfigured tablets, cracking of coating,
                    mottled surface, unbeveled edges, chipping, capping etc.
                </p>

                {{-- SIGN --}}
                <div class="row mt-4">
                    <div class="col-md-6">
                        <label>Production Chemist / Date</label>
                        <input type="text" class="form-control"
                            value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                            readonly>
                    </div>

                    <div class="col-md-6">
                        <label>QA Incharge / Date</label>
                        <input type="text" class="form-control"
                            value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                            readonly>
                    </div>
                </div>

                {{-- SUBMIT --}}
                @if(!$isCoatingSaved)
                <div class="text-end mt-4">
                    <button class="btn btn-primary text-white">Save Coating Check</button>
                </div>
                @else
                <div class="alert alert-success mt-3">
                    Coating Check Already Submitted ✅
                </div>
                @endif

            </form>
        </div>
    </div>

    @endif

    @php
    $dateTimeData = $compressionIpqc ? json_decode($compressionIpqc->datetime, true) : [];
    $weight20Data = $compressionIpqc ? json_decode($compressionIpqc->weight20, true) : [];
    $dtData = $compressionIpqc ? json_decode($compressionIpqc->dt, true) : [];
    $hardnessData = $compressionIpqc ? json_decode($compressionIpqc->hardness, true) : [];
    $friabilityData = $compressionIpqc ? json_decode($compressionIpqc->friability, true) : [];
    $thicknessData = $compressionIpqc ? json_decode($compressionIpqc->thickness, true) : [];
    $remarksData = $compressionIpqc ? json_decode($compressionIpqc->remarks, true) : [];

    $isCompressionSaved = !empty($compressionIpqc);
    @endphp
    @if($stepName == 'compression' && $viewStep == ($index + 1))

    <div class="card mt-4 shadow-sm ipqc-card">
        <div class="card-body">

            {{-- ✅ PDF button only after save --}}
            @if($isCompressionSaved)
            <div class="d-flex justify-content-end mb-3">
                <a href="{{ route('compression.pdf', $productionFlowStart->id) }}"
                    class="btn btn-primary text-white">
                    Download PDF
                </a>
            </div>
            @endif

            <div class="text-center mb-3">
                <h5 class="ipqc-title">D.D. Pharmaceuticals Pvt. Ltd.</h5>
                <p class="ipqc-subtitle">IPQC Record (Compression)</p>
            </div>

            <form id="ipqcForm" method="POST"
                action="{{ route('production-start.update',$productionFlowStart->id) }}">
                @csrf
                @method('PUT')

                <input type="hidden" name="action_type" value="SAVE_COMPRESSION_IPQC">
                <input type="hidden" name="step" value="{{ $viewStep }}">

                {{-- 🔹 Product Info --}}
                <div class="row mb-3 g-3">
                    <div class="col-md-6">
                        <label class="section-label">Product Name</label>
                        <input type="text" class="form-control"
                            value="{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}"
                            readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="section-label">Batch No</label>
                        <input type="text" class="form-control"
                            value="{{ $productionFlowStart->batch_number }}"
                            readonly>
                    </div>
                </div>

                {{-- 🔹 TABLE --}}
                <table class="table table-bordered text-center align-middle">
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
                        @for($i=1;$i<=10;$i++)
                            <tr>
                            <td>{{ $i }}</td>

                            <td>
                                <input type="datetime-local"
                                    name="datetime[]"
                                    value="{{ $dateTimeData[$i-1] ?? '' }}"
                                    class="form-control required-field"
                                    {{ $isCompressionSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="number" step="0.01"
                                    name="weight20[]"
                                    value="{{ $weight20Data[$i-1] ?? '' }}"
                                    class="form-control required-field"
                                    {{ $isCompressionSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="number" step="0.01"
                                    name="dt[]"
                                    value="{{ $dtData[$i-1] ?? '' }}"
                                    class="form-control required-field"
                                    {{ $isCompressionSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="number" step="0.01"
                                    name="hardness[]"
                                    value="{{ $hardnessData[$i-1] ?? '' }}"
                                    class="form-control required-field"
                                    {{ $isCompressionSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="number" step="0.01"
                                    name="friability[]"
                                    value="{{ $friabilityData[$i-1] ?? '' }}"
                                    class="form-control required-field"
                                    {{ $isCompressionSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="number" step="0.01"
                                    name="thickness[]"
                                    value="{{ $thicknessData[$i-1] ?? '' }}"
                                    class="form-control required-field"
                                    {{ $isCompressionSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="text"
                                    name="sign_date[]"
                                    class="form-control"
                                    value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                                    readonly>
                            </td>

                            <td>
                                <input type="text"
                                    name="remarks[]"
                                    value="{{ $remarksData[$i-1] ?? '' }}"
                                    class="form-control required-field"
                                    {{ $isCompressionSaved ? 'readonly' : '' }}>
                            </td>
                            </tr>
                            @endfor
                    </tbody>
                </table>

                {{-- 🔹 Bottom Section --}}
                <div class="row mt-4 g-3">

                    <div class="col-md-4">
                        <label class="section-label">Uncoated Tablets Inspected By / Date</label>
                        <input type="text"
                            name="inspected_by"
                            class="form-control"
                            value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                            readonly>
                    </div>

                    <div class="col-md-4">
                        <label class="section-label">Total Weight of Uncoated Tablets</label>
                        <input type="number" step="0.01"
                            name="total_weight_uncoated"
                            value="{{ $compressionIpqc->total_weight_uncoated ?? '' }}"
                            class="form-control required-field"
                            {{ $isCompressionSaved ? 'readonly' : '' }}>
                    </div>

                    <div class="col-md-4">
                        <label class="section-label">Total Weight of Rejected Tablets</label>
                        <input type="number" step="0.01"
                            name="total_weight_rejected"
                            value="{{ $compressionIpqc->total_weight_rejected ?? '' }}"
                            class="form-control required-field"
                            {{ $isCompressionSaved ? 'readonly' : '' }}>
                    </div>

                </div>

                <div class="row mt-4 g-3">
                    <div class="col-md-6">
                        <label class="section-label">Production Chemist / In-Charge</label>
                        <input type="text"
                            class="form-control"
                            value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                            readonly>
                    </div>

                    <div class="col-md-6">
                        <label class="section-label">QA Chemist / In-Charge</label>
                        <input type="text"
                            class="form-control"
                            value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                            readonly>
                    </div>
                </div>

                <p class="note-text mt-3">
                    Note: Rejected Uncoated Tablets also include disfigured Tablets in appearance.
                </p>

                {{-- 🔹 Submit --}}
                @if(!$isCompressionSaved)
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary text-white">
                        Save IPQC Record
                    </button>
                </div>
                @else
                <div class="alert alert-success mt-3">
                    Compression IPQC Already Submitted ✅
                </div>
                @endif

            </form>
        </div>
    </div>
</div>

@endif


@php
$capsuleRecords = $capsuleFilling->capsule_records ?? [];
$isCapsuleSaved = !empty($capsuleRecords);
@endphp

@if($stepName == 'granulation' && $viewStep == ($index + 1))

@php
$isCapsuleForm1Saved = !empty($capsuleForm1);
@endphp

{{-- BUTTONS --}}
<div class="d-flex gap-2 mb-3 mt-4 flex-wrap">
    <button type="button" onclick="showGranSection('granForm1Section')"
        class="btn btn-outline-primary">
        Form 1 — Batch Manufacturing Record
    </button>

    <button type="button" onclick="showGranSection('granForm2Section')"
        class="btn btn-outline-secondary">
        Form 2 — Precautions / Safety Measures
    </button>
    <button type="button" onclick="showGranSection('granForm3Section')"
        class="btn btn-outline-warning">
        Form 3 — Equipment Cleaning
    </button>
</div>

{{-- FORM 1 --}}
<div id="granForm1Section" class="gran-section d-none">
    <div class="card mt-2 shadow-sm" style="background:#fff;">
        <div class="card-body">

            <h5 class="text-center fw-bold">D.D. Pharmaceuticals Pvt. Ltd.</h5>
            <p class="text-center small">Batch Manufacturing Record — Granulation Form</p>

            <form method="POST"
                action="{{ route('production-start.update', $productionFlowStart->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="action_type" value="SAVE_CAPSULE_FORM1">

                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        <label>Document No</label>
                        <input type="text" name="document_no" class="form-control"
                            value="{{ $capsuleForm1->document_no ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-4">
                        <label>Mfg. License No</label>
                        <input type="text" name="mfg_license_no" class="form-control"
                            value="{{ $capsuleForm1->mfg_license_no ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-4">
                        <label>Generic Name</label>
                        <input type="text" name="generic_name" class="form-control"
                            value="{{ $capsuleForm1->generic_name ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                </div>

                <div class="row mb-3 g-3">
                    <div class="col-md-6">
                        <label>Product Name</label>
                        <input type="text" name="product_name" class="form-control"
                            value="{{ $capsuleForm1->product_name ?? $productionFlowStart->bomMaster?->finishedGood?->name }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-6">
                        <label>Composition</label>
                        <textarea name="composition" class="form-control" rows="2"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>{{ $capsuleForm1->composition ?? '' }}</textarea>
                    </div>
                </div>

                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        <label>Master Formula Record No</label>
                        <input type="text" name="master_formula_record_no" class="form-control"
                            value="{{ $capsuleForm1->master_formula_record_no ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-4">
                        <label>Batch No</label>
                        <input type="text" name="batch_no" class="form-control"
                            value="{{ $capsuleForm1->batch_no ?? $productionFlowStart->batch_number }}"
                            readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Shelf Life</label>
                        <input type="text" name="shelf_life" class="form-control"
                            value="{{ $capsuleForm1->shelf_life ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                </div>

                <div class="row mb-3 g-3">
                    <div class="col-md-3">
                        <label>Mfg Date</label>
                        <input type="date" name="mfg_date" class="form-control"
                            value="{{ $capsuleForm1?->mfg_date ? \Carbon\Carbon::parse($capsuleForm1->mfg_date)->format('Y-m-d') : ($productionFlowStart->mfg_date ? \Carbon\Carbon::parse($productionFlowStart->mfg_date)->format('Y-m-d') : '') }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-3">
                        <label>Exp Date</label>
                        <input type="date" name="exp_date" class="form-control"
                            value="{{ $capsuleForm1?->exp_date ? \Carbon\Carbon::parse($capsuleForm1->exp_date)->format('Y-m-d') : ($productionFlowStart->expiry_date ? \Carbon\Carbon::parse($productionFlowStart->expiry_date)->format('Y-m-d') : '') }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-3">
                        <label>Batch Size</label>
                        <input type="text" name="batch_size" class="form-control"
                            value="{{ $capsuleForm1->batch_size ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-3">
                        <label>Unit Packing</label>
                        <input type="text" name="unit_packing" class="form-control"
                            value="{{ $capsuleForm1->unit_packing ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                </div>

                <div class="row mb-3 g-3">
                    <div class="col-md-6">
                        <label>Batch Commenced On</label>
                        <input type="date" name="batch_commenced_on" class="form-control"
                            value="{{ $capsuleForm1?->batch_commenced_on ? \Carbon\Carbon::parse($capsuleForm1->batch_commenced_on)->format('Y-m-d') : '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-6">
                        <label>Batch Completed On</label>
                        <input type="date" name="batch_completed_on" class="form-control"
                            value="{{ $capsuleForm1?->batch_completed_on ? \Carbon\Carbon::parse($capsuleForm1->batch_completed_on)->format('Y-m-d') : '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                </div>

                <hr>
                <h6 class="fw-bold">Authorization</h6>

                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        <label>Issued By</label>
                        <input type="text" name="issued_by" class="form-control"
                            value="{{ $capsuleForm1->issued_by ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-4">
                        <label>Issued Date</label>
                        <input type="date" name="issued_date" class="form-control"
                            value="{{ $capsuleForm1?->issued_date ? \Carbon\Carbon::parse($capsuleForm1->issued_date)->format('Y-m-d') : '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-4">
                        <label>Received By</label>
                        <input type="text" name="received_by" class="form-control"
                            value="{{ $capsuleForm1->received_by ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                </div>

                <div class="row mb-3 g-3">
                    <div class="col-md-4">
                        <label>Received Date</label>
                        <input type="date" name="received_date" class="form-control"
                            value="{{ $capsuleForm1?->received_date ? \Carbon\Carbon::parse($capsuleForm1->received_date)->format('Y-m-d') : '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-4">
                        <label>Prepared By</label>
                        <input type="text" name="prepared_by" class="form-control"
                            value="{{ $capsuleForm1->prepared_by ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-4">
                        <label>Prepared Date</label>
                        <input type="date" name="prepared_date" class="form-control"
                            value="{{ $capsuleForm1?->prepared_date ? \Carbon\Carbon::parse($capsuleForm1->prepared_date)->format('Y-m-d') : '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                </div>

                <div class="row mb-3 g-3">
                    <div class="col-md-3">
                        <label>Reviewed By</label>
                        <input type="text" name="reviewed_by" class="form-control"
                            value="{{ $capsuleForm1->reviewed_by ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-3">
                        <label>Reviewed Date</label>
                        <input type="date" name="reviewed_date" class="form-control"
                            value="{{ $capsuleForm1?->reviewed_date ? \Carbon\Carbon::parse($capsuleForm1->reviewed_date)->format('Y-m-d') : '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-3">
                        <label>Approved By</label>
                        <input type="text" name="approved_by" class="form-control"
                            value="{{ $capsuleForm1->approved_by ?? '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-3">
                        <label>Approved Date</label>
                        <input type="date" name="approved_date" class="form-control"
                            value="{{ $capsuleForm1?->approved_date ? \Carbon\Carbon::parse($capsuleForm1->approved_date)->format('Y-m-d') : '' }}"
                            {{ $isCapsuleForm1Saved ? 'readonly' : '' }}>
                    </div>
                </div>

                @if(!$isCapsuleForm1Saved)
                <div class="text-end mt-4">
                    <button class="btn btn-primary text-white">Save Form</button>
                </div>
                @else
                <div class="alert alert-success mt-3">
                    Form Already Submitted ✅
                </div>
                @endif

            </form>
        </div>
    </div>
</div>
{{-- FORM 1 END --}}
<div id="granForm2Section" class="gran-section d-none">
    <div class="card mt-2 shadow-sm" style="background:#fff;">
        <div class="card-body">

            {{-- Header --}}
            <div class="text-center mb-3">
                <h5 class="fw-bold">D.D. Pharmaceuticals Pvt. Ltd.</h5>
                <p class="small text-muted mb-0">
                    G-1/583, RIICO INDUSTRIAL AREA,<br>
                    SITAPURA, TONK ROAD, JAIPUR (Raj.)-302022, INDIA
                </p>
            </div>

            {{-- Product + Batch row --}}
            <div class="row mb-4 g-3">
                <div class="col-md-8">
                    <label class="form-label fw-semibold small">Product Name</label>
                    <input type="text" class="form-control"
                        value="{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}" readonly>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Batch No.</label>
                    <input type="text" class="form-control"
                        value="{{ $productionFlowStart->batch_number }}" readonly>
                </div>
            </div>

            {{-- Page label --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0">Precautions / Safety Measures</h6>

            </div>

            {{-- Safety measures list --}}
            <ol class="list-group list-group-numbered">
                @php
                $safetyMeasures = [
                'Follow personal hygienic requirements rigidly.',
                'The operators must use proper safety measures like hand gloves, masks, caps etc. during all operations.',
                'All equipment\'s and machineries must be adequately guarded and earthed.',
                'Ensure that the equipment\'s & work station are clear of previous product.',
                'Ensure that the documents or materials not required for the planned process are removed.',
                'Before use, ensure that general cleaning & utensils cleaning are carried out as per respective S.O.P. & are suitable for use.',
                'Ensure that all the equipments and balances are cleaned as per respective S.O.P. before and after use.',
                'Ensure that the Room temperature & Humidity are within limits.',
                'Ensure that all the ingredients bear Control Reference Number.',
                'Ensure that all the weighed solid ingredients are individually tied in double polybags.',
                'Check the integrity of Sieves before and after each sifting operation.',
                'Care should be taken to ensure geometrical mixing of the ingredients, during mixing operations.',
                'Store the active ingredient (s) & excipient (s) those required for the batch in well closed containers lined with double polybags.',
                'All the materials at different stages of manufacturing should bear proper tags to indicate their status & identity.',
                ];
                @endphp

                @foreach($safetyMeasures as $measure)
                <li class="list-group-item border-0 py-2" style="background:transparent;">
                    <span class="ms-2">{{ $measure }}</span>
                </li>
                @endforeach
            </ol>

        </div>
    </div>
</div>

{{-- FORM 3 — Equipment Cleaning --}}
@php
$isCapsuleForm2Saved = $capsulecleningForm2->count() > 0;
$lineClearanceBy = $capsulecleningForm2->first()->line_clierence_given_by ?? '';
$lineClearanceDate = $capsulecleningForm2->first()->date ?? '';
@endphp

<div id="granForm3Section" class="gran-section d-none">
    <div class="card mt-2 shadow-sm" style="background:#fff;">
        <div class="card-body">

            <h5 class="text-center fw-bold">D.D. Pharmaceuticals Pvt. Ltd.</h5>
            <p class="text-center small text-muted mb-0">
                G-1/583, RIICO INDUSTRIAL AREA,<br>
                SITAPURA, TONK ROAD, JAIPUR (Raj.)-302022, INDIA
            </p>

            <form method="POST"
                action="{{ route('production-start.update', $productionFlowStart->id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="action_type" value="SAVE_CAPSULE_FORM2">

                {{-- Product + Batch --}}
                <div class="row mb-3 g-3">
                    <div class="col-md-8">
                        <label class="fw-semibold small">Product Name</label>
                        <input type="text" class="form-control"
                            value="{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="fw-semibold small">Batch No.</label>
                        <input type="text" class="form-control"
                            value="{{ $productionFlowStart->batch_number }}" readonly>
                    </div>
                </div>

                <h6 class="fw-bold">3.0 Equipment Cleaning Details</h6>

                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>S.No</th>
                                <th>Name of Equipment</th>
                                <th>Equipment ID</th>
                                <th>Previous Product Name</th>
                                <th>Previous Batch No.</th>
                                <th>Cleaned By (Production) Sign/Date</th>
                                <th>Verified By (Production) Sign/Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 0; $i < 15; $i++)
                                @php $equipRow=$capsulecleningForm2[$i] ?? null; @endphp
                                <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][equipment_name]"
                                        class="form-control"
                                        value="{{ $equipRow->equipment_name ?? '' }}"
                                        {{ $isCapsuleForm2Saved ? 'readonly' : '' }}>
                                </td>
                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][equipment_id]"
                                        class="form-control"
                                        value="{{ $equipRow->equipment_id ?? '' }}"
                                        {{ $isCapsuleForm2Saved ? 'readonly' : '' }}>
                                </td>
                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][previous_product_name]"
                                        class="form-control"
                                        value="{{ $equipRow->previous_product_name ?? '' }}"
                                        {{ $isCapsuleForm2Saved ? 'readonly' : '' }}>
                                </td>
                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][previous_batch_no]"
                                        class="form-control"
                                        value="{{ $equipRow->previous_batch_no ?? '' }}"
                                        {{ $isCapsuleForm2Saved ? 'readonly' : '' }}>
                                </td>
                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][cleaned_by]"
                                        class="form-control"
                                        value="{{ $equipRow->cleaned_by ?? '' }}"
                                        {{ $isCapsuleForm2Saved ? 'readonly' : '' }}>
                                </td>
                                <td>
                                    <input type="text"
                                        name="rows[{{ $i }}][verified_by]"
                                        class="form-control"
                                        value="{{ $equipRow->verified_by ?? '' }}"
                                        {{ $isCapsuleForm2Saved ? 'readonly' : '' }}>
                                </td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                </div>

                {{-- Line Clearance --}}
                <div class="row mt-3 g-3">
                    <div class="col-md-6">
                        <label class="fw-semibold small">Line Clearance Given By (Production)</label>
                        <input type="text" name="line_clierence_given_by" class="form-control"
                            value="{{ $lineClearanceBy }}"
                            {{ $isCapsuleForm2Saved ? 'readonly' : '' }}>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-semibold small">Date</label>
                        <input type="date" name="date" class="form-control"
                            value="{{ $lineClearanceDate ? \Carbon\Carbon::parse($lineClearanceDate)->format('Y-m-d') : '' }}"
                            {{ $isCapsuleForm2Saved ? 'readonly' : '' }}>
                    </div>
                </div>

                @if(!$isCapsuleForm2Saved)
                <div class="text-end mt-4">
                    <button class="btn btn-primary text-white">Save Form</button>
                </div>
                @else
                <div class="alert alert-success mt-3">
                    Equipment Cleaning Form Already Submitted ✅
                </div>
                @endif

            </form>
        </div>
    </div>
</div>
@endif

@if($stepName == 'capsule filling' && $viewStep == ($index + 1))

<div class="card mt-4 shadow-sm" style="background:#fff;">

    <div class="card-body">

        {{-- SUCCESS MESSAGE --}}
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- PDF BUTTON (AFTER SAVE ONLY) --}}
        @if($isCapsuleSaved)
        <a href="{{ route('capsule.pdf', $productionFlowStart->id) }}"
            class="btn btn-primary text-white mb-3">
            Download Capsule PDF
        </a>
        @endif

        <h5 class="text-center fw-bold">
            D.D. Pharmaceuticals Pvt. Ltd.
        </h5>

        <p class="text-center small">
            5.1.2 IPQC Record (Capsule Filling)
        </p>

        <form method="POST"
            action="{{ route('production-start.update',$productionFlowStart->id) }}"
            onsubmit="return validateCapsuleForm()">

            @csrf
            @method('PUT')

            <input type="hidden" name="action_type" value="SAVE_CAPSULE_FILLING">
            <input type="hidden" name="step" value="{{ $viewStep }}">

            {{-- Product + Batch --}}
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Product Name</label>
                    <input type="text" class="form-control"
                        value="{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label>Batch No</label>
                    <input type="text" class="form-control"
                        value="{{ $productionFlowStart->batch_number }}" readonly>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>S.No</th>
                            <th>Date / Time</th>
                            <th>Weight of 20 Capsules (gms)</th>
                            <th>Leakage from Joints</th>
                            <th>Cracks & Pinholes in Capsules</th>
                            <th>Other Physical Defects</th>
                            <th>D.T (mins)</th>
                            <th>Done By Sign / Date</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr class="table-light fw-bold">
                            <td colspan="8">Std. Limits</td>
                        </tr>

                        @for($i=1;$i<=10;$i++)
                            @php
                            $row=$capsuleRecords[$i-1] ?? [];
                            @endphp
                            <tr>

                            <td>{{ $i }}</td>

                            <td>
                                <input type="datetime-local"
                                    name="datetime[]"
                                    value="{{ $row['datetime'] ?? '' }}"
                                    class="form-control datetime"
                                    {{ $isCapsuleSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="number"
                                    step="0.01"
                                    name="weight[]"
                                    value="{{ $row['weight'] ?? '' }}"
                                    class="form-control weight"
                                    {{ $isCapsuleSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="text"
                                    name="leakage[]"
                                    value="{{ $row['leakage'] ?? '' }}"
                                    class="form-control"
                                    {{ $isCapsuleSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="text"
                                    name="cracks[]"
                                    value="{{ $row['cracks'] ?? '' }}"
                                    class="form-control"
                                    {{ $isCapsuleSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="text"
                                    name="defects[]"
                                    value="{{ $row['defects'] ?? '' }}"
                                    class="form-control"
                                    {{ $isCapsuleSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="number"
                                    step="0.01"
                                    name="dt[]"
                                    value="{{ $row['dt'] ?? '' }}"
                                    class="form-control"
                                    {{ $isCapsuleSaved ? 'readonly' : '' }}>
                            </td>

                            <td>
                                <input type="text"
                                    name="sign[]"
                                    class="form-control"
                                    value="{{ $row['sign'] ?? auth()->user()->full_name }}">
                            </td>

                            </tr>
                            @endfor

                    </tbody>

                </table>
            </div>

            {{-- BOTTOM --}}
            <div class="row mt-4">

                <div class="col-md-6">
                    <label>Filled Capsules Inspected By / Date</label>
                    <input type="text"
                        name="inspected_by"
                        class="form-control"
                        value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}">
                </div>

                <div class="col-md-3">
                    <label>Total Weight of Filled Capsules</label>
                    <input type="number"
                        step="0.01"
                        name="total_filled_weight"
                        class="form-control total_filled"
                        {{ $isCapsuleSaved ? 'readonly' : '' }}
                        value="{{ $capsuleFilling->total_weight_filled_capsules ?? '' }}">
                </div>

                <div class="col-md-3">
                    <label>Total Weight of Rejected Capsules</label>
                    <input type="number"
                        step="0.01"
                        name="total_rejected_weight"
                        class="form-control total_rejected"
                        {{ $isCapsuleSaved ? 'readonly' : '' }}
                        value="{{ $capsuleFilling->total_weight_rejected_capsules ?? '' }}">
                </div>

            </div>

            {{-- SIGN --}}
            <div class="row mt-4">

                <div class="col-md-6">
                    <label>Production Chemist/In-Charge</label>
                    <input type="text"
                        class="form-control"
                        value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                        readonly>
                </div>

                <div class="col-md-6">
                    <label>Q.A Chemist/In-Charge</label>
                    <input type="text"
                        class="form-control"
                        value="{{ auth()->user()->full_name }} / {{ now()->format('d-m-Y') }}"
                        readonly>
                </div>

            </div>

            <p class="small mt-3 text-muted">
                Note: Rejected Capsules include distorted shape, discoloration and other defects.
            </p>

            @if(!$isCapsuleSaved)
            <div class="text-end mt-4">
                <button class="btn btn-primary text-white">
                    Save IPQC Record
                </button>
            </div>
            @else
            <div class="alert alert-success mt-3">
                Capsule Filling IPQC Already Submitted ✅
            </div>
            @endif

        </form>

    </div>
</div>

@endif

@if($stepName == 'syrup filling' && $viewStep == ($index + 1))
@php
$syrupFilling = $productionFlowStart->syrupFilling ?? null;
$isSyrupSaved = !empty($syrupFilling);

$datetimeData = $syrupFilling->datetime ?? [];
$filledVolume1Data = $syrupFilling->filled_volume[1] ?? [];
$filledVolume2Data = $syrupFilling->filled_volume[2] ?? [];
$roppCapData = $syrupFilling->ropp_cap ?? [];
$checkedByData = $syrupFilling->checked_by ?? [];
$verifiedByData = $syrupFilling->verified_by ?? [];
@endphp
<div class="card mt-4 shadow-sm">
    <div class="card-body">

        {{-- ✅ PDF button only after save --}}
        @if($isSyrupSaved)
        <a href="{{ route('syrup.pdf', $productionFlowStart->id) }}"
            class="btn btn-primary text-white mb-3">
            Download Syrup PDF
        </a>
        @endif

        <h5 class="text-center fw-bold">D.D. Pharmaceuticals Pvt. Ltd.</h5>
        <p class="text-center small">Syrup Filling cum IPQC Check</p>

        {{-- ✅ Validation Errors --}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST"
            action="{{ route('production-start.update',$productionFlowStart->id) }}"
            id="syrupForm">
            @csrf
            @method('PUT')

            <input type="hidden" name="action_type" value="SAVE_SYRUP_FILLING">
            <input type="hidden" name="step" value="{{ $viewStep }}">

            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Product Name</label>
                    <input type="text" class="form-control"
                        value="{{ $productionFlowStart->bomMaster->finishedGood->name ?? '' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label>Batch No</label>
                    <input type="text" class="form-control"
                        value="{{ $productionFlowStart->batch_number }}" readonly>
                </div>
                <div class="col-md-12">
                    <label>9.1 Filling cum IPQC Check</label>

                </div>
                <div class="col-md-6">
                    <label>Temperature (Limit: -23°C ±2°C)</label>
                    <input type="text"
                        name="temprature"
                        class="form-control"
                        value="{{ $syrupFilling->temprature ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>Colour & Appearance</label>
                    <input type="text"
                        name="colour_appearance"
                        class="form-control"
                        value="{{ $syrupFilling->colour_appearance ?? '' }}">
                </div>

                <div class="col-md-6">
                    <label>PH</label>
                    <input type="text"
                        name="ph"
                        class="form-control"
                        value="{{ $syrupFilling->ph ?? '' }}">
                </div>

            </div>

            {{-- TABLE --}}
            <table class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>Date / Time</th>
                        <th>Filled Volume 1 (ml)</th>
                        <th>Filled Volume 2 (ml)</th>
                        <th>ROPP Cap Sealing (Intact/Damaged)</th>
                        <th>Checked By / Date</th>
                        <th>Verified By / Date</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-light fw-bold">
                        <td colspan="6">Std. Limits</td>
                    </tr>

                    @for($i=0; $i<10; $i++)
                        <tr>
                        <td>
                            <input type="datetime-local" name="datetime[]"
                                class="form-control"
                                value="{{ $datetimeData[$i] ?? '' }}"
                                {{ $isSyrupSaved ? 'readonly' : '' }}>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="filled_volume[1][]"
                                class="form-control"
                                value="{{ $filledVolume1Data[$i] ?? '' }}"
                                {{ $isSyrupSaved ? 'readonly' : '' }}>
                        </td>
                        <td>
                            <input type="number" step="0.01" name="filled_volume[2][]"
                                class="form-control"
                                value="{{ $filledVolume2Data[$i] ?? '' }}"
                                {{ $isSyrupSaved ? 'readonly' : '' }}>
                        </td>
                        <td>
                            <input type="text" name="ropp_cap[]" class="form-control"
                                value="{{ $roppCapData[$i] ?? '' }}"
                                {{ $isSyrupSaved ? 'readonly' : '' }}>
                        </td>
                        <td>
                            <input type="text" name="checked_by[]" class="form-control"
                                value="{{ $checkedByData[$i] ?? auth()->user()->full_name }}">
                        </td>
                        <td>
                            <input type="text" name="verified_by[]" class="form-control"
                                value="{{ $verifiedByData[$i] ?? '' }}">
                        </td>
                        </tr>
                        @endfor
                </tbody>
            </table>

            {{-- TOTAL --}}
            <div class="row mt-3">
                <div class="col-md-4">
                    <label>Total Filled Qty (Nos)</label>
                    <input type="number" name="total_filled_qty"
                        class="form-control" step="1"
                        value="{{ $syrupFilling->total_filled_qty ?? '' }}"
                        {{ $isSyrupSaved ? 'readonly' : '' }}>
                </div>
            </div>

            {{-- VISUAL --}}
            <div class="mt-4">
                <h6 class="fw-bold">Line Clearance for Visual Inspection of Filled-Sealed Bottles</h6>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>Previous Product</label>
                        <input type="text" class="form-control" name="prev_product"
                            value="{{ $syrupFilling->prev_product ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label>Previous Product Batch No.</label>
                        <input type="text" class="form-control" name="prev_batch"
                            value="{{ $syrupFilling->prev_batch ?? '' }}">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-6">
                        <label>Line Clearance Given By (Production)/Date</label>
                        <input type="text" class="form-control" name="line_clearance_by"
                            value="{{ $syrupFilling->line_clearance_by ?? '' }}">
                    </div>
                </div>

                <table class="table table-bordered text-center mt-2">
                    <thead>
                        <tr>
                            <th>Visual Inspection Commenced at</th>
                            <th>Visual Inspection Done By (Sign/Date)</th>
                            <th>Visual Inspection Completed at</th>
                            <th>Verified By Sign/Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" name="inspection_start"
                                    class="form-control" value="{{ $syrupFilling->inspection_start ?? '' }}"></td>
                            <td><input type="text" name="inspection_done_by"
                                    class="form-control" value="{{ $syrupFilling->inspection_done_by ?? auth()->user()->full_name }}"></td>
                            <td><input type="text" name="inspection_completed"
                                    class="form-control" value="{{ $syrupFilling->inspection_completed ?? '' }}"></td>
                            <td><input type="text" name="inspection_verified"
                                    class="form-control" value="{{ $syrupFilling->inspection_verified ?? '' }}"></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            @if(!$isSyrupSaved)
            <div class="text-end mt-4">
                <button class="btn btn-primary text-white">Save IPQC Record</button>
            </div>
            @else
            <div class="alert alert-success mt-3">
                Syrup Filling IPQC Already Submitted ✅
            </div>
            @endif

        </form>
    </div>
</div>
@endif

@endforeach

</div>
</div>

<div class="modal fade" id="startProductionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
        <form method="POST" action="{{ route('production-start.update', $productionFlowStart->id) }}"
            class="modal-content bg-white">
            @csrf
            @method('PUT')

            <input type="hidden" name="action_type" value="START_PRODUCTION">

            <!-- Modal Header -->
            <div class="modal-header border-border-color-40 p-20">
                <h1 class="modal-title fs-18 fw-medium mb-0">
                    Start Production
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body p-20 pb-0">
                <div class="row">

                    <!-- MFG Date -->
                    <div class="col-lg-12">
                        <div class="mb-20">
                            <label class="label">
                                MFG Date <span class="text-danger">*</span>
                            </label>
                            <div class="form-floating">
                                <input type="date" name="mfg_date" id="mfg_date" class="form-control"
                                    value="{{ date('Y-m-d') }}" placeholder="MFG Date" required>
                                <label>MFG Date</label>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Years -->
                    <div class="col-lg-12">
                        <div class="mb-20">
                            <label class="label">
                                Select Expiry Duration (Years) <span class="text-danger">*</span>
                            </label>
                            <div class="form-floating">
                                <select id="expiry_years" class="form-control" placeholder="Select Years" required>
                                    <option value="">Select Years</option>
                                    <option value="1">1 Year</option>
                                    <option value="2">2 Years</option>
                                    <option value="3">3 Years</option>
                                    <option value="4">4 Years</option>
                                    <option value="5">5 Years</option>
                                    <option value="6">6 Years</option>
                                    <option value="7">7 Years</option>
                                    <option value="8">8 Years</option>
                                    <option value="9">9 Years</option>
                                    <option value="10">10 Years</option>
                                </select>
                                <label>Select Expiry Duration</label>
                            </div>
                        </div>
                    </div>

                    <!-- Expiry Date -->
                    <div class="col-lg-12">
                        <div class="mb-20">
                            <label class="label">
                                Expiry Date <span class="text-danger">*</span>
                            </label>
                            <div class="form-floating">
                                <input type="date" name="expiry_date" id="expiry_date" class="form-control"
                                    placeholder="Expiry Date" readonly required>
                                <label>Expiry Date</label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-0 p-20 pt-0">
                <button type="button" class="btn btn-danger fw-normal text-white" data-bs-dismiss="modal">
                    Cancel
                </button>

                <button type="submit" class="btn btn-primary fw-normal text-white">
                    Start Production
                </button>
            </div>

        </form>
    </div>
</div>

@endsection


@push('scripts')

<script>
    function calculateAverage(className, outputId) {
        let sum = 0;
        let count = 0;

        document.querySelectorAll('.' + className).forEach(el => {
            let val = parseFloat(el.value);
            if (!isNaN(val)) {
                sum += val;
                count++;
            }
        });

        document.getElementById(outputId).value = (sum / count).toFixed(2);
    }
</script>
<script>
    document.querySelectorAll('.attachment-select').forEach(select => {

        select.addEventListener('change', function() {

            let target = this.dataset.target;

            let fileInput = document.querySelector('.' + target);

            if (this.value === 'Yes') {
                fileInput.classList.remove('d-none');
            } else {
                fileInput.classList.add('d-none');
            }

        });

    });
</script>
<script>
    function showSection(sectionId) {

        document.querySelectorAll('.form-section').forEach(function(section) {
            section.classList.add('d-none');
        });

        document.getElementById(sectionId).classList.remove('d-none');
    }
</script>
<script>
    document.getElementById('qc_report')?.addEventListener('change', function() {
        if (this.files.length > 0) {
            document.getElementById('qc_status').removeAttribute('disabled');
            document.getElementById('qc_remarks').removeAttribute('disabled');
        }
    });
</script>

<script>
    const mfgDateInput = document.getElementById('mfg_date');
    const expiryYearsSelect = document.getElementById('expiry_years');
    const expiryDateInput = document.getElementById('expiry_date');

    function calculateExpiryDate() {
        const mfgDateValue = mfgDateInput.value;
        const selectedYears = expiryYearsSelect.value;

        if (mfgDateValue && selectedYears) {
            let mfgDate = new Date(mfgDateValue);

            mfgDate.setFullYear(mfgDate.getFullYear() + parseInt(selectedYears));

            const year = mfgDate.getFullYear();
            const month = String(mfgDate.getMonth() + 1).padStart(2, '0');
            const day = String(mfgDate.getDate()).padStart(2, '0');

            expiryDateInput.value = `${year}-${month}-${day}`;
        }
    }

    mfgDateInput.addEventListener('change', calculateExpiryDate);

    expiryYearsSelect.addEventListener('change', calculateExpiryDate);
</script>

<script>
    document.getElementById('ipqcForm').addEventListener('submit', function(e) {

        let valid = true;

        document.querySelectorAll('.required-field').forEach(function(field) {
            if (field.value.trim() === '') {
                field.classList.add('required-error');
                valid = false;
            } else {
                field.classList.remove('required-error');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('⚠️ Please fill all required fields before submitting!');
        }
    });
</script>

<script>
    function calculateAverage(className, outputId) {
        let inputs = document.querySelectorAll('.' + className);
        let total = 0,
            count = 0;

        inputs.forEach(input => {
            if (input.value !== '') {
                total += parseFloat(input.value);
                count++;
            }
        });

        document.getElementById(outputId).value =
            count ? (total / count).toFixed(2) : '';
    }

    document.getElementById('coatingForm').addEventListener('submit', function(e) {

        let valid = true;

        document.querySelectorAll('.required-field').forEach(field => {
            if (field.value.trim() === '') {
                field.classList.add('required-error');
                valid = false;
            } else {
                field.classList.remove('required-error');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('⚠️ Please fill all fields!');
        }
    });
</script>

<script>
    document.getElementById('packingForm').addEventListener('submit', function(e) {

        let isValid = true;

        document.querySelectorAll('#packingForm .required-field').forEach(function(field) {

            if (field.value.trim() === '') {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }

        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill all required fields!');
        }
    });

    // Real-time validation
    document.querySelectorAll('.required-field').forEach(function(field) {
        field.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                this.classList.remove('is-invalid');
            }
        });
    });
</script>

<script>
    function validatePage15Form() {

        let hasData = false;

        document.querySelectorAll('.leak_date, .packing_date').forEach(function(input) {
            if (input.value !== '') {
                hasData = true;
            }
        });

        if (!hasData) {
            alert('Please fill the all fields!');
            return false;
        }

        return true;
    }
</script>

<script>
    function validateCapsuleForm() {

        let hasData = false;

        document.querySelectorAll('.datetime, .weight, .total_filled, .total_rejected')
            .forEach(function(input) {
                if (input.value !== '') {
                    hasData = true;
                }
            });

        if (!hasData) {
            alert('Kam se kam ek row fill karo!');
            return false;
        }

        return true;
    }
</script>

{{-- ✅ JAVASCRIPT VALIDATION --}}
<script>
    document.getElementById('page16Form').addEventListener('submit', function(e) {

        let isFilled = false;

        document.querySelectorAll('#page16Form .required-field').forEach(function(input) {
            if (input.value.trim() !== '') {
                isFilled = true;
            }
        });

        if (!isFilled) {
            e.preventDefault();
            alert('Please fill all fields');
        }

    });
</script>
<script>
    document.getElementById('syrupForm').addEventListener('submit', function(e) {

        let filled = false;

        document.querySelectorAll('#syrupForm input').forEach(function(input) {
            if (input.value.trim() !== '') {
                filled = true;
            }
        });

        if (!filled) {
            e.preventDefault();
            alert(' Please fill all required fields!');
        }
    });
</script>


<script>
    document.getElementById('stockInForm').addEventListener('submit', function(e) {

        let isValid = true;

        // ✅ Sab required fields check karo
        document.querySelectorAll('#stockInForm .si-req').forEach(function(field) {

            // Hidden fields skip karo
            if (field.closest('.d-none')) return;

            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('⚠️ Please fill all required fields!');
        }
    });

    // ✅ Real time error clear
    document.querySelectorAll('#stockInForm .si-req').forEach(function(field) {
        field.addEventListener('input', function() {
            if (this.value.trim()) this.classList.remove('is-invalid');
        });
        field.addEventListener('change', function() {
            if (this.value.trim()) this.classList.remove('is-invalid');
        });


    });
</script>

<script>
    function showGranSection(sectionId) {
        document.querySelectorAll('.gran-section').forEach(function(section) {
            section.classList.add('d-none');
        });
        document.getElementById(sectionId).classList.remove('d-none');
    }
</script>
@endpush