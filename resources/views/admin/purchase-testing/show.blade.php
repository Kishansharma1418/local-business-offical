<style>
    .table thead th {
        background-color: #fff !important;
    }

    .table tbody tr {
        background-color: #fff !important;
    }
</style>
@extends('include.master')

@section('content')

<div class="main-content-container overflow-hidden">

    {{-- ================= PAGE HEADER ================= --}}

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">

        <h3 class="mb-0">QA Purchase Testing</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">

                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('purchase-testing.index') }}" class="text-decoration-none">
                        QA Purchase Testing
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    View PO
                </li>

            </ol>
        </nav>

    </div>


    {{-- ================= PO DETAILS ================= --}}

    <div class="row g-4 mb-4">

        <!-- LEFT SIDE (DETAILS) -->
        <div class="col-md-6">

            <!-- PO DETAILS -->
            <div class="card border shadow-sm mb-4">
                <div class="card-body bg-white">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-primary mb-0">
                            PO No : {{ $po->po_number }}
                        </h5>

                        <span class="badge bg-{{ $statusColors[strtolower($po->status)] ?? 'secondary' }}">
                            {{ ucfirst($po->status) }}
                        </span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <strong>PO Date :</strong><br>
                            {{ formatDate($po->po_date) }}
                        </div>

                        <div class="col-md-12">
                            <strong>Delivery Date :</strong><br>
                            {{ formatDate($po->delivery_date) }}
                        </div>

                        <div class="col-md-12">
                            <strong>Branch :</strong><br>
                            {{ $po->branch->branch_name ?? '-' }}
                        </div>

                        <div class="col-md-12">
                            <strong>Currency :</strong><br>
                            {{ $po->currency->code ?? 'INR' }}
                        </div>
                    </div>

                </div>
            </div>

            <!-- VENDOR -->
            <div class="card border shadow-sm mb-4">
                <div class="card-body bg-white">
                    <h5 class="text-primary mb-3">Vendor Details</h5>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Name :</strong> {{ $po->vendor->name ?? '-' }}</li>
                        <li><strong>GST :</strong> {{ $po->vendor->gst_no ?? '-' }}</li>
                        <li><strong>Address :</strong> {{ $po->vendor->address_line1 ?? '-' }}</li>
                    </ul>
                </div>
            </div>

            <!-- BROKER -->
            <div class="card border shadow-sm mb-4">
                <div class="card-body bg-white">
                    <h5 class="text-primary mb-3">Broker Details</h5>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Code :</strong> {{ $po->broker->code ?? '-' }}</li>
                        <li><strong>Name :</strong> {{ $po->broker->broker_name ?? '-' }}</li>
                    </ul>
                </div>
            </div>

        </div>

        <!-- RIGHT SIDE (HISTORY TOP SE) -->
        <div class="col-md-6">

            <div class="card h-100 border shadow-sm">
                <div class="card-body bg-white">
                    <h5 class="text-primary mb-3">Purchase Order History</h5>

                    @if($purchaseorderapprovals->count())

                    <ul class="list-unstyled">

                        @foreach($purchaseorderapprovals as $row)

                        @php
                        $statusColor = match(strtolower($row->status)) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'sent' => 'info',
                        'accepted' => 'primary',
                        default => 'secondary'
                        };
                        @endphp

                        <li class="mb-3 p-2 border rounded">

                            <span class="badge bg-{{ $statusColor }}">
                                {{ ucfirst($row->status) }}
                            </span><br>

                            <strong>Approved By:</strong>
                            {{ $row->updatedBy->full_name ?? '-' }} <br>

                            <strong>Date:</strong>
                          {{ formatDate($row->created_at, 'd-m-Y h:i A') }}<br>

                            <strong>Status:</strong>
                            {{ $row->status ?? '-' }} <br>

                            @if(strtolower($row->status) == 'accepted')
                            <strong>Invoice Number:</strong>
                            {{ $po->invoice_number ?? '-' }} <br>

                            @if($po->invoice_file)
                            <a href="{{ asset('uploads/invoices/'.$po->invoice_file) }}" target="_blank">
                                View Invoice
                            </a>
                            @endif
                            @endif

                        </li>

                        @endforeach

                    </ul>

                    @else
                    <p class="text-muted">No history found</p>
                    @endif

                </div>
            </div>

        </div>

    </div>


    {{-- ================= QA SAMPLE COLLECTION ================= --}}

    @if($po->status == 'quarantine')

    <div class="card border shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0 text-primary">
                <i class="ri-flask-line me-1"></i>
                QA Sample Collection
            </h5>
        </div>

        <div class="card-body bg-white">

            <form action="{{ route('qa.sample.store',$po->id) }}" method="POST">

                @csrf

                <div class="table-responsive">

                    <table class="table table-bordered align-middle bg-white">
                        <thead class=" bg-white">

                            <tr>
                                <th width="60">#</th>
                                <th>Raw Material</th>
                                <th width="150">Received Qty</th>
                                <th width="150">Sample Qty</th>
                                <th width="180">UOM</th>
                                <th>MFG Date</th> <!-- New -->
                                <th>EXP Date</th> <!-- New -->
                                <th>Received Date</th> <!-- New -->
                                <th>Received By</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach($po->details as $item)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>{{ $item->rawMaterial->name }}</strong>
                                </td>

                                <td>
                                    {{ $item->received_quantity }} {{ $item->uom->name }}
                                </td>

                                <td>
                                    <input type="number"
                                        step="0.01"
                                        min="0"
                                        max="{{ $item->received_quantity }}"
                                        name="qa_received_qty[{{ $item->id }}]"
                                        class="form-control"
                                        required>
                                </td>

                                <td>

                                    {{-- Readonly visible field --}}
                                    <input type="text"
                                        class="form-control"
                                        value="{{ $item->uom->name }}"
                                        readonly>

                                    {{-- Hidden field for backend --}}
                                    <input type="hidden"
                                        name="qa_uom_id[{{ $item->id }}]"
                                        value="{{ $item->uom->id }}">

                                </td>
                                <td>{{ formatDate($item->mfg_date) }}</td>
                                <td>{{ formatDate($item->expiry_date) }}</td>
                                <td>{{ formatDate($item->updated_at) }}</td>
                                <td> {{ $item->updatedBy ? $item->updatedBy->full_name : '-' }}</td>
                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="text-start mt-3">

                    <button class="btn btn-primary text-white">
                        Send To QA
                    </button>

                </div>

            </form>

        </div>

    </div>

    @endif



    {{-- ================= QA REPORT ================= --}}

    @if($po->status == 'in_qa')

    <div class="card border shadow-sm mb-4">

        <div class="card-header bg-white">

            <h5 class="mb-0 text-primary">
                <i class="ri-file-text-line me-1"></i>
                QA Report Entry
            </h5>

        </div>

        <div class="card-body bg-white">

            <form action="{{ route('qa.report.store',$po->id) }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="60">#</th>
                                <th>Raw Material</th>
                                <th width="150">Sample Qty</th>
                                <th width="200">Analysis Report No</th>
                                <th width="200">Upload Report</th>
                                <th width="150">Status</th>
                                <th width="200">Remarks</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach($po->details as $item)

                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <strong>{{ $item->rawMaterial->name }}</strong>
                                </td>
                                <td>
                                    {{ $item->qa_received_qty ?? '-' }} {{ $item->uom->name }}
                                </td>

                                <td>

                                    <input type="text"
                                        name="analysis_report_no[{{ $item->id }}]"
                                        class="form-control"
                                        required>

                                </td>

                                <td>

                                    <input type="file"
                                        name="qa_report_file[{{ $item->id }}]"
                                        class="form-control"
                                        accept=".pdf,.jpg,.jpeg,.png">

                                </td>

                                <td>
                                    <select name="qa_status[{{ $item->id }}]" class="form-control" required>
                                        <!-- Placeholder option -->
                                        <option value="" disabled selected>Select</option>

                                        <!-- Actual options -->
                                        <option value="pass" {{ (isset($item->qa_status) && $item->qa_status == 'pass') ? 'selected' : '' }}>Pass</option>
                                        <option value="fail" {{ (isset($item->qa_status) && $item->qa_status == 'fail') ? 'selected' : '' }}>Fail</option>
                                    </select>
                                </td>

                                <td>

                                    <input type="text"
                                        name="qa_remarks[{{ $item->id }}]"
                                        class="form-control">

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                <div class="text-start mt-3">

                    <button class="btn btn-primary text-white">
                        Submit QA Report
                    </button>

                </div>

            </form>

        </div>

    </div>

    @endif
    {{-- ================= QA SUMMARY TABLE ================= --}}


    <div class="card border shadow-sm mb-4">

        <div class="card-header bg-white">
            <h5 class="mb-0 text-primary">
                <i class="ri-table-line me-1"></i>
                QA Summary
            </h5>
        </div>

        <div class="card-body bg-white">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Raw Material</th>
                            <th>Received Qty</th>
                            <th>Sample Qty</th>

                            <th>MFG Date</th>
                            <th>EXP Date</th>
                            <th>Updated Date</th>
                            <th>Received By</th>
                            <th>Analysis Report No</th>
                            <th>QA Status</th>
                            <th>Remarks</th>
                            <th>QA Report</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($po->details as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->rawMaterial->name }}</td>
                            <td>{{ $item->received_quantity }} {{ $item->uom->name }}</td>
                            <td>
                                {{ $item->qa_received_qty ? $item->qa_received_qty . ' ' . $item->uom->name : '-' }}
                            </td>
                            <td>{{ formatDate($item->mfg_date) }}</td>
                            <td>{{ formatDate($item->expiry_date) }}</td>
                            <td>{{ formatDate($item->updated_at) }}</td>
                            <td>{{ $item->updatedBy ? $item->updatedBy->full_name : '-' }}</td>
                            <td>{{ $item->analysis_report_no ?? '-' }}</td>
                            <td>{{ ucfirst($item->qa_status ?? '-') }}</td>
                            <td>{{ $item->qa_remarks ?? '-' }}</td>
                            <td>
                                @if($item->qa_report_file)
                                <a href="{{ asset('storage/qa_reports/'.$item->qa_report_file) }}" target="_blank">
                                    View File
                                </a>
                                @else
                                -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        </div>

    </div>



</div>

@endsection