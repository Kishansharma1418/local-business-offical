<style>
    .main-content-container {
        background-color: #ffffff !important;
        min-height: 100vh;
        padding: 20px;
    }

    .card,
    .card-body,
    .table,
    .table thead,
    .table tbody,
    .table tr,
    .table td,
    .table th {
        background-color: #ffffff !important;
    }

    body {
        color: #000 !important;
    }

    .main-content-container,
    .card,
    .card-body,
    .table,
    .table td,
    .table th,
    .table tr {
        color: #000 !important;
    }
</style>

@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Purchase Order Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('purchase-order.index') }}" class="text-decoration-none">Purchase Order</a>
                </li>
                <li class="breadcrumb-item active">View Purchase Order</li>

            </ol>
        </nav>
    </div>

    <div class="mb-4">
        <a href="{{ route('purchase-order.pdf', encrypt($po->id)) }}" target="_blank"
            class="btn btn-primary fw-normal text-white">
            <i class="ri-file-pdf-line me-1"></i> Download PDF
        </a>
    </div>

    <div class="row g-4 mb-4">

        <!-- ================= LEFT SIDE ================= -->
        <div class="col-md-6">

            <!-- PO DETAILS -->
            <div class="card mb-4 border shadow-sm bg-white">
                <div class="card-body bg-white">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-primary mb-0">
                            PO No: {{ $po->po_number }}
                            <br><span style="line-height: 30px;"> Branch: {{ $po->branch->branch_name }}</span>
                        </h4>

                        @php
                        $statusColors = [
                        'draft' => 'secondary',
                        'approved' => 'success',
                        'sent' => 'info',
                        'accepted' => 'primary',
                        'partialreceived' => 'warning',
                        'quarantine' => 'dark',
                        'completed' => 'dark',
                        'rejected' => 'danger'
                        ];
                        @endphp

                        <div>
                            @if(!auth()->user()->hasRole('purchase') && auth()->user()->canany(['approve','issue','accept','reject'], $po))
                            <button type="button"
                                class="btn btn-primary fw-normal text-white changeStatusBtn"
                                data-bs-toggle="modal"
                                data-bs-target="#statusModal">
                                <i class="ri-edit-2-line me-1"></i> Change Status
                            </button>
                            @endif

                            @can('issue', $po)
                            <button type="button"
                                class="btn btn-primary text-white issueVendorBtn"
                                data-id="{{ $po->id }}">
                                Issue to Vendor
                            </button>
                            @endcan
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12"><strong>PO Date:</strong>
                           {{ formatDate($po->po_date) }}
                        </div>

                        <div class="col-md-12"><strong>Delivery Date:</strong>
                             {{ formatDate($po->delivery_date) }}
                        </div>

                        <div class="col-md-12"><strong>Currency:</strong>
                            {{ $po->currency->code ?? 'INR' }}
                        </div>

                        <div class="col-md-12"><strong>Payment Terms:</strong>
                            {{ $po->paymentTerm->days ?? '-' }}
                            {{ $po->paymentTerm->name ?? '-' }}
                        </div>
                    </div>

                </div>
            </div>

            <!-- VENDOR + BROKER -->
            <div class="card border shadow-sm">
                <div class="card-body bg-white">
                    <h5 class="text-primary mb-3">Vendor Details</h5>
                    <ul class="list-unstyled mb-4">
                        <li><strong>Name:</strong> {{ $po->vendor->name ?? '-' }}</li>
                        <li><strong>GST No:</strong> {{ $po->vendor->gst_no ?? '-' }}</li>
                        <li><strong>Address:</strong> {{ $po->vendor->address_line1 ?? '-' }}</li>
                    </ul>
 
                    <hr>

                    <h5 class="text-primary mb-3">Broker Details</h5>
                    <ul class="list-unstyled mb-0">
                        <li><strong>Code:</strong> {{ $po->broker->code ?? '-' }}</li>
                        <li><strong>Name:</strong> {{ $po->broker->broker_name ?? '-' }}</li>
                    </ul>
                </div>
            </div>

        </div>


        <!-- ================= RIGHT SIDE ================= -->
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

                            <strong>Approverd By:</strong>
                            {{ $row->updatedBy->full_name ?? '-' }} <br>

                            <strong>Date:</strong>
                          {{ formatDate($row->created_at, 'd-m-Y h:i A') }} <br>

                            <strong>Current Status:</strong>
                            {{ $row->status ?? '-' }} <br>

                            @if(strtolower($row->status) == 'accepted')
                            <strong>Invoice Number:</strong>
                            {{ $po->invoice_number ?? '-' }} <br>

                            @if($po->invoice_file)
                            <strong>Invoice File:</strong>
                            <a href="{{ asset('uploads/invoices/'.$po->invoice_file) }}" target="_blank">
                                View Invoice
                            </a><br>
                            @endif
                            @endif

                            @if(strtolower($row->status) == 'sent')
                            <span class="text-info fw-bold">
                                Issued to Vendor
                            </span>
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
    @role('admin|purchase|accounted')
    <div class="card mb-4 border shadow-sm bg-white">
        <div class="card-body bg-white">
            <h5 class="text-primary mb-3">
                <i class="ri-file-list-3-line me-1"></i> Purchase Order Items
            </h5>

            <div class="table-responsive">
                <table class="table table-bordered align-middle bg-white">
                    <thead class="bg-white">
                        <tr>
                            <th>#</th>
                            <th>Raw Material</th>
                            <th>Qty Ordered</th>
                            <th>UOM</th>
                            <th>Unit Price</th>
                            <th>Discount %</th>
                            <th>Discount Amt</th>
                            <th>GST %</th>
                            <th>GST Amt</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($po->details as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <strong>{{ $item->rawMaterial->name ?? '-' }}</strong><br>
                                <small class="text-muted">{{ $item->notes }}</small>
                            </td>
                            <td>{{ $item->quantity_ordered }}</td>
                            <td>{{ $item->uom->name ?? '-' }}</td>
                            <td>₹ {{ number_format($item->unit_price, 2) }}</td>
                            <td>{{ $item->discount_percent ?? 0 }}</td>
                            <td>{{ $item->discount_amount ?? 0 }}</td>
                            <td>{{ $item->gst_percent }}%</td>
                            <td>₹ {{ number_format($item->gst_amount, 2) }}</td>
                            <td class="fw-bold">₹ {{ number_format($item->total_price, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted">
                                No items found in this Purchase Order
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endrole

    @role('Store')
    @if(in_array(strtolower(trim($po->status)), ['accepted','partialreceived']))
    <div class="card mt-4 border shadow-sm">
        <div class="card-body">
            <h5 class="text-primary mb-3">
                <i class="ri-checkbox-circle-line me-1"></i>
                Receive Items
            </h5>

            <form action="{{ route('purchase-order.receive', $po->id) }}" method="POST" novalidate>
                @csrf

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Raw Material</th>
                            <th>Ordered Qty</th>
                            <th>Already Received</th>
                            <th>Receive Qty</th>
                            <th>Batch No</th>
                            <th>MFG Date</th>
                            <th>Expiry Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($po->details as $item)

                        @php
                        $remainingQty = $item->quantity_ordered - ($item->received_quantity ?? 0);
                        @endphp

                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input receive-check"
                                        type="checkbox"
                                        data-id="{{ $item->id }}"
                                        {{ $remainingQty <= 0 ? 'disabled' : '' }}>
                                </div>
                            </td>

                            <td>{{ $item->rawMaterial->name }}</td>

                            <td>{{ $item->quantity_ordered }} {{ $item->uom->name }}</td>

                            <td>{{ $item->received_quantity ?? 0 }}</td>

                            <td>
                                <input type="number"
                                    name="received_qty[{{ $item->id }}]"
                                    class="form-control receive-qty"
                                    data-id="{{ $item->id }}"
                                    min="1"
                                    max="{{ $remainingQty }}"
                                    {{ $remainingQty <= 0 ? 'disabled' : 'disabled' }}>
                            </td>

                            <td>
                                <input type="text"
                                    name="batch_no[{{ $item->id }}]"
                                    class="form-control batch-no"
                                    data-id="{{ $item->id }}"
                                    placeholder="Enter Batch No"
                                    disabled>
                            </td>
                            <td>
                                <input type="date"
                                    name="mfg_date[{{ $item->id }}]"
                                    class="form-control mfg_date"
                                    data-id="{{ $item->id }}"
                                    placeholder="Enter Batch No"

                                    disabled>
                            </td>
                            <td>
                                <input type="date"
                                    name="expiry_date[{{ $item->id }}]"
                                    class="form-control expiry_date"
                                    data-id="{{ $item->id }}"
                                    placeholder="Enter Batch No"
                                    disabled>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>


                </table>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="received_by" required>
                    <label class="form-check-label">
                        I Have Verified
                    </label>
                </div>

                <button type="submit" class="btn btn-primary text-white">
                    Confirm Receive
                </button>
            </form>
        </div>
    </div>
    @endif

    @role('Store')

    <div class="card mt-4 shadow-sm">
        <div class="card-body">
            <h5 class="text-primary mb-3">Received Item History</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Material</th>
                            <th>Total Qty</th>
                            <th>Received Qty</th>
                            <th>Remaining Qty</th>
                            <th>Batch No</th>
                            <th>MFG Date</th>
                            <th>Expiry Date</th>
                            <th> Received Date</th>
                            <th> Received By</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($po->details as $item)

                        @php
                        $total = $item->quantity_ordered ?? 0;
                        $received = $item->received_quantity ?? 0;
                        $remaining = $total - $received;
                        @endphp

                        <tr>
                            <td>{{ $item->rawMaterial->name ?? '-' }}</td>

                            <td>{{ $total }}</td>

                            <td class="text-success fw-bold">
                                {{ $received }}
                            </td>

                            <td class="text-danger fw-bold">
                                {{ $remaining }}
                            </td>

                            <td>{{ $item->batch_no ?? '-' }}</td>

                            <td>
                                {{ $item->mfg_date ? \Carbon\Carbon::parse($item->mfg_date)->format('d M Y') : '-' }}
                            </td>

                            <td>
                                {{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('d M Y') : '-' }}
                            </td>
                            <td>
                                {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d M Y h:i A') : '-' }}
                            </td>
                            <td>
                                {{ $item->updatedBy ? $item->updatedBy->full_name : '-' }}
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @endrole
    @if(strtolower($po->status) == 'quarantine')
    <div class="card mt-4 border shadow-sm">
        <div class="card-body">
            <!-- <h5 class="text-dark">Received Items (Locked)</h5> -->

            <!-- <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Raw Material</th>
                    <th>Received Qty</th>
                    <th>Batch No</th>
                </tr>
            </thead>
            <tbody>
                @foreach($po->details as $item)
                @if($item->received_quantity > 0)
                <tr>
                    <td>{{ $item->rawMaterial->name }}</td>
                    <td>{{ $item->received_quantity }}{{ $item->uom->name }}</td>
                    <td>{{ $item->batch_no }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table> -->

            <div class="alert alert-warning">
                Items moved to Quarantine. Editing disabled.
            </div>
        </div>
    </div>
    @endif
    @endrole

    @role('admin')
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="border rounded p-3">
                <h6 class="text-muted">Gross Amount</h6>
                <h5>₹ {{ number_format($po->total_amount, 2) }}</h5>
            </div>
        </div>

        <div class="col-md-3">
            <div class="border rounded p-3">
                <h6 class="text-muted">GST Amount</h6>
                <h5>₹ {{ number_format($po->tax_amount, 2) }}</h5>
            </div>
        </div>

        <div class="col-md-3">
            <div class="border rounded p-3">
                <h6 class="text-muted">Discount</h6>
                <h5>₹ {{ number_format($po->discount_amount, 2) }}</h5>
            </div>
        </div>

        <div class="col-md-3">
            <div class="border rounded p-3">
                <h6 class="text-muted">Net Amount</h6>
                <h5 class="text-success">₹ {{ number_format($po->net_amount, 2) }}</h5>
            </div>
        </div>
    </div>
    @endrole

    @if ($po->notes)
    <div class="card border shadow-sm">
        <div class="card-body">
            <h5 class="text-primary mb-2">Notes</h5>
            <p class="mb-0">{{ $po->notes }}</p>
        </div>
    </div>
    @endif

    <!-- @if(auth()->user()->hasRole('HEAD QA') && $po->status == 'quarantine')
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="text-primary">QA Sample Collection</h5>

                    <form action="{{ route('qa.sample.store', $po->id) }}" method="POST">
                        @csrf

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Raw Material</th>
                                    <th>Received Qty</th>
                                    <th>Sample Qty</th>
                                    <th>UOM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($po->details as $item)
                                <tr>
                                    <td>{{ $item->rawMaterial->name }}</td>
                                    <td>{{ $item->received_quantity }}{{ $item->uom->name }}</td>
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
                                        <select name="qa_uom_id[{{ $item->id }}]" class="form-control" required>
                                            <option value="">Select UOM</option>
                                            @foreach($uoms as $uom)
                                            <option value="{{ $uom->id }}">
                                                {{ $uom->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary text-white">
                            Send to QA
                        </button>
                    </form>
                </div>
            </div>
        @endif  -->

    @if(auth()->user()->hasRole('Store') && $po->status == 'store_check')
    <div class="card mt-4 border shadow-sm">
        <div class="card-body">
            <h5 class="text-primary">QC Result Review</h5>

            <form action="{{ route('store.stock.in', $po->id) }}" method="POST">
                @csrf

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Raw Material</th>
                            <th>Received Qty</th>
                            <th>QC Status</th>
                            <th>Analysis Report No</th>
                            <th>Upload Report</th>
                            <th>Stock In</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach($po->details as $item)

                        <tr>
                            <td>{{ $item->rawMaterial->name }}</td>
                            <td>{{ $item->received_quantity }} {{ $item->uom->name }}</td>

                            <td>
                                @if($item->qa_status == 'pass')
                                <span class="badge bg-success">Pass</span>
                                @elseif($item->qa_status == 'fail')
                                <span class="badge bg-danger">Fail</span>
                                @else
                                <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>

                            <td>{{ $item->analysis_report_no ?? '-' }}</td>

                            <td>
                                @if($item->qa_report_file)
                                <a href="{{ asset('storage/qa_reports/'.$item->qa_report_file) }}"
                                    target="_blank"
                                    class="btn btn-sm btn-primary text-white">
                                    View
                                </a>
                                @endif
                            </td>

                            <td>
                                @if($item->qa_status == 'pass')
                                <input type="checkbox"
                                    name="stock_in_items[]"
                                    value="{{ $item->id }}">
                                @else
                                -
                                @endif
                            </td>

                        </tr>

                        @endforeach

                    </tbody>
                </table>

                @role('Store')

                <button type="submit" class="btn btn-primary text-white">
                    Confirm Stock In
                </button>
                @endrole

            </form>
        </div>
    </div>
    @endif

    <!-- @if(auth()->user()->hasRole('HEAD QA') && $po->status == 'in_qa')
            <div class="card mt-4">
                <div class="card-body">
                    <h5>QA Report Entry</h5>

                    <form action="{{ route('qa.report.store', $po->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <table class="table table-bordered">
                          <thead>
                                <tr>
                                    <th>Raw Material</th>
                                    <th>Analysis Report No</th>
                                    <th>Upload Report</th>
                                    <th>Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($po->details as $item)
                                <tr>
                                    <td>{{ $item->rawMaterial->name }}</td>
                                    <td>
                                        <input type="text"
                                            name="analysis_report_no[{{ $item->id }}]"
                                            class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="file"
                                            name="qa_report_file[{{ $item->id }}]"
                                            class="form-control"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                    </td>
                                    <td>
                                        <select name="qa_status[{{ $item->id }}]"
                                            class="form-control" required>
                                            <option value="pass">Pass</option>
                                            <option value="fail">Fail</option>
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

                        <button type="submit" class="btn btn-primary text-white">
                            Submit QA Report
                        </button>
                    </form>
                </div>
            </div>
        @endif -->

    @role('admin')
    <div class="card bg-white rounded-10 border border-light shadow-sm mt-4">
        <div class="card-body">
            <h5 class="mb-3 text-primary">
                <i class="ri-history-line me-2"></i>
                Approval History
            </h5>

            @if ($purchaseorderapprovals->count() > 0)
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>Updated By</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseorderapprovals as $row)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <span
                                class="badge 
                                                    {{ $row->status == 'Approved' ? 'bg-success' : ($row->status == 'Rejected' ? 'bg-danger' : 'bg-warning') }}">
                                {{ $row->status }}
                            </span>
                        </td>
                        <td>{{ $row->comments ?? '-' }}</td>
                        <td>{{ $row->updatedBy->full_name ?? '-' }}</td>
                       <td>{{ formatDate($row->updated_at, 'd-m-Y h:i A') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p class="text-muted">No approval history found.</p>
            @endif
        </div>
    </div>
    @endrole

    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form id="statusForm" class="modal-content bg-white" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <input type="hidden" id="status_id" name="status_id" value="{{ $po->id }}">
                <div class="modal-header border-border-color-40 p-20">
                    <h5 class="modal-title fs-18 fw-medium mb-0" id="statusModalLabel">Update Purchase
                        Order
                        Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Status</label>
                            <select class="form-select form-control" name="status" id="status">

                                <option value="">Select Status</option>
                                @can('approve', $po)
                                <option value="approved">Approve</option>
                                @endcan
                                @can('reject', $po)
                                <option value="rejected">Reject</option>
                                @endcan
                                @can('issue', $po)
                                <option value="sent">Issue to Vendor</option>
                                @endcan

                                @can('accept', $po)
                                <option value="accepted">Accept</option>
                                @endcan

                                @can('receive', $po)
                                @if($po->status == 'accepted')
                                <option value="partialreceived">Partial Received</option>
                                @endif
                                <option value="completed">Mark Completed</option>
                                @endcan

                            </select>

                        </div>

                        <div class="col-lg-12 mb-20 d-none" id="invoice_box">
                            <label class="label fs-16 mb-2">Upload Invoice</label>
                            <input type="file" name="invoice" class="form-control">
                        </div>
                        <div class="col-lg-12 mb-20 d-none" id="invoice_number_box">
                            <label class="label fs-16 mb-2">Invoice Number</label>
                            <input type="text" name="invoice_number" class="form-control" placeholder="Enter Invoice Number">
                        </div>
                        <div class="col-lg-12 mb-20 d-none" id="expected_delivery_box">
                            <label class="label fs-16 mb-2">Expected Delivery Date</label>
                            <input type="date" name="expected_delivery_date" class="form-control">
                        </div>

                        <div class="col-lg-12 mb-20" id="reason_box">
                            <label class="label fs-16 mb-2">Reason</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Enter reason here..."></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 p-20 pt-0">
                    <button type="button" class="btn btn-danger fw-normal text-white"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-normal text-white">
                        <span class="spinner-border spinner-border-sm d-none"></span>
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('error'))
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "{{ session('error') }}"
    });
</script>
@endif

<script>
    $(document).ready(function() {

        $('.changeStatusBtn').on('click', function(e) {
            e.preventDefault();

            $('#statusForm')[0].reset();

            $('#invoice_box').addClass('d-none');

            $('#expected_delivery_box').addClass('d-none');
            $('#reason_box').addClass('d-none');

            $('#statusModal').modal('show');
        });


        $('#status').on('change', function() {

            let status = $(this).val().toLowerCase().trim();

            console.log("Selected Status:", status);

            $('#invoice_box').addClass('d-none');
            $('#invoice_number_box').addClass('d-none'); // NEW
            $('#expected_delivery_box').addClass('d-none');
            $('#reason_box').addClass('d-none');

            if (status === 'rejected') {
                $('#reason_box').removeClass('d-none');
            }

            if (status === 'accepted') {
                $('#invoice_box').removeClass('d-none');
                $('#invoice_number_box').removeClass('d-none'); // NEW
                $('#expected_delivery_box').removeClass('d-none');
            }
        });


        $('#statusForm').on('submit', function(e) {

            e.preventDefault();

            let form = $(this);
            let formData = new FormData(this);

            let url = "{{ url('purchase-orders/change-status') }}/" + $('#status_id').val();

            $.ajax({
                type: "POST",
                url: url,
                data: formData,
                processData: false,
                contentType: false,

                beforeSend: function() {
                    form.find('.spinner-border').removeClass('d-none');
                    form.find('button[type="submit"]').prop('disabled', true);
                },

                success: function(response) {
                    if (response.success) {
                        location.reload();
                    }
                },

                error: function(xhr) {
                    let message = 'Something went wrong';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    alert(message);
                },

                complete: function() {
                    form.find('.spinner-border').addClass('d-none');
                    form.find('button[type="submit"]').prop('disabled', false);
                }
            });

        });

    });
</script>

<script>
    $(document).ready(function() {

        $('.receive-check').on('change', function() {

            let row = $(this).closest('tr');

            let qtyInput = row.find('.receive-qty');
            let batchInput = row.find('.batch-no');
            let mfgInput = row.find('.mfg_date');
            let expiryInput = row.find('.expiry_date');

            if ($(this).is(':checked')) {
                qtyInput.prop('disabled', false).attr('required', true);
                batchInput.prop('disabled', false).attr('required', true);
                mfgInput.prop('disabled', false).attr('required', true);
                expiryInput.prop('disabled', false).attr('required', true);
            } else {
                qtyInput.prop('disabled', true).removeAttr('required').val('');
                batchInput.prop('disabled', true).removeAttr('required').val('');
                mfgInput.prop('disabled', true).removeAttr('required').val('');
                expiryInput.prop('disabled', true).removeAttr('required').val('');
            }
        });

        $('form[action*="receive"]').on('submit', function(e) {
            let anyChecked = $('.receive-check:checked').length > 0;


            let hasValidItem = false;
            let errorMessage = "";

            $('.receive-check:checked').each(function() {

                let row = $(this).closest('tr');

                let qty = parseFloat(row.find('.receive-qty').val());
                let max = parseFloat(row.find('.receive-qty').attr('max'));
                let batch = row.find('.batch-no').val();
                let mfg = row.find('.mfg_date').val();
                let expiry = row.find('.expiry_date').val();

                if (!qty || qty <= 0) {
                    errorMessage = "Receive quantity must be greater than 0.";
                    return false;
                }

                if (qty > max) {
                    errorMessage = "Receive quantity exceeds remaining quantity.";
                    return false;
                }

                if (!batch) {
                    errorMessage = "Batch number is required.";
                    return false;
                }

                if (!mfg) {
                    errorMessage = "MFG date is required.";
                    return false;
                }

                if (!expiry) {
                    errorMessage = "Expiry date is required.";
                    return false;
                }

                if (expiry <= mfg) {
                    errorMessage = "Expiry date must be greater than MFG date.";
                    return false;
                }

                hasValidItem = true;
            });

            if (!anyChecked) {
                e.preventDefault();
                e.stopImmediatePropagation(); // ✅ browser validation bhi rokو

                Swal.fire({
                    icon: 'warning',
                    title: 'No Item Selected',
                    text: 'Please select at least one raw material to receive.',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });

                // ✅ Button reset
                $(this).find('button[type="submit"]')
                    .prop('disabled', false)
                    .html('Confirm Receive');

                return false;
            }

        });
        $(document).on('click', '.issueVendorBtn', function() {

            let poId = $(this).data('id');

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to issue this PO to vendor?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Issue it!'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "/purchase-orders/change-status/" + poId,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: "sent"
                        },
                        success: function(res) {
                            if (res.success) {
                                location.reload();
                            }
                        }
                    });

                }
            });
        });
    });
</script>



@endpush