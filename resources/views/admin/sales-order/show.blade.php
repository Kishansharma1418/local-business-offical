    <style>
        table.table td,
        table.table th,
        table.table tr {
            background: white !important;
        }

        /* ================= PDF MODAL THEME ================= */

        #viewSalesOrderModal .modal-dialog {
            max-width: 700px !important;
            /* A4 width feel */
        }

        #viewSalesOrderModal .modal-content {
            border-radius: 0;
            background: #fff;
        }

        /* remove bootstrap padding */
        #viewSalesOrderModal .modal-body {
            padding: 0 !important;
        }

        /* wrapper exactly like PDF */
        .pdf-wrapper {
            padding: 22px 26px;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10.8px;
            color: #222;
        }

        /* titles */
        .pdf-title {
            font-size: 18px;
            font-weight: 800;
            color: #004e92;
        }

        .pdf-sub {
            font-size: 11px;
            color: #444;
        }

        /* section heading */
        .section-title {
            background: #f1f5ff;
            border-left: 3px solid #004e92;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            margin: 16px 0 10px;
            color: #003570;
            border-radius: 4px;
        }

        /* tables */
        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .pdf-table th,
        .pdf-table td {
            border: 1px solid #d3d7e3;
            padding: 6px 8px;
            font-size: 10.6px;
        }

        .pdf-table th {
            background: #f5f7ff;
            font-weight: 700;
        }

        .amount {
            text-align: right;
            font-weight: 700;
            color: #004e92;
        }

        .total-row {
            background: #e8edff;
            font-weight: 800;
        }

        /* footer */
        .pdf-footer {
            border-top: 1px solid #d3d7e3;
            text-align: center;
            padding-top: 10px;
            font-size: 10px;
            color: #666;
            margin-top: 25px;
        }

        #viewSalesOrderModal .modal-dialog {
            max-width: 820px;
            width: 100%;
        }

        #viewSalesOrderModal .modal-content {
            border-radius: 0;
        }

        .invoice-view-modal {
            max-width: 720px;
        }

        /* PDF header logo spacing */
        .pdf-wrapper img {
            display: block;
            margin-bottom: 6px;
        }

        /* Remove any bootstrap table background */
        .pdf-table th,
        .pdf-table td {
            background: #fff;
        }

        /* Match PDF header border look */
        .pdf-wrapper table {
            background: #fff;
        }

        /* Prevent modal scroll shadow */
        #viewSalesOrderModal .modal-content {
            box-shadow: none;
        }

        /* ===== Improve Text Readability ===== */

        /* overall text */
        #viewSalesOrderModal .pdf-wrapper {
            color: #1f2937;
            /* dark slate (better than pure black) */
        }

        /* table headers */
        #viewSalesOrderModal .pdf-table th {
            color: #111827;
            /* strong heading text */
            font-weight: 700;
        }

        /* table data */
        #viewSalesOrderModal .pdf-table td {
            color: #1f2937;
        }

        /* section titles */
        #viewSalesOrderModal .section-title {
            color: #0f172a;
            /* darker blue */
        }

        /* amount values */
        #viewSalesOrderModal .amount {
            color: #003b8f;
            /* darker professional blue */
        }

        /* small/meta text (email, phone etc.) */
        #viewSalesOrderModal .pdf-sub {
            color: #374151;
            /* darker gray */
        }

        /* ===== Company Header Readability ===== */

        /* Company name */
        #viewSalesOrderModal .pdf-wrapper strong {
            color: #0f172a;
            /* dark slate (clear & premium) */
            font-weight: 700;
        }

        /* Company address, email, phone */
        #viewSalesOrderModal .pdf-wrapper td {
            color: #1f2937;
            /* readable dark gray */
        }

        /* Header right side (Invoice / Sales Order title) */
        #viewSalesOrderModal .pdf-title {
            color: #003b8f;
            /* strong professional blue */
        }

        /* Date / Order No text */
        #viewSalesOrderModal .pdf-sub {
            color: #374151;
            /* darker than before */
        }
    </style>

    @extends('include.master')

    @section('content')
        <div class="main-content-container overflow-hidden">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
                <h3 class="mb-0">Sales Order Details</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb align-items-center mb-0 lh-1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                                <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                                <span class="text-body fs-14 hover">Dashboard</span>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('sale-orders.index') }}" class="text-decoration-none">Sales Order</a>
                        </li>
                        <li class="breadcrumb-item active">View Sales Order</li>

                    </ol>
                </nav>
            </div>

            <div class="card bg-white p-4 rounded-10 border border-light shadow-sm mb-4">
                <div class="card-body">

                    <div class="row mb-4">

                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="mb-0">
                                <i class="ri-user-3-line me-2 text-primary"></i>
                                {{ $salesOrder->code ?? 'N/A' }}
                            </h4>


                            <div class="d-flex align-items-center gap-3">
                                <span
                                    class="badge 
                                    {{ $salesOrder->approval_status == 'Approved'
                                        ? 'bg-success'
                                        : ($salesOrder->approval_status == 'Rejected'
                                            ? 'bg-danger'
                                            : 'bg-warning') }} text-white fs-14">
                                    {{ $salesOrder->approval_status }}
                                </span>

                                <button class="btn btn-primary viewSalesOrderBtn text-white"
                                    data-id="{{ $salesOrder->id }}">
                                    View
                                </button>

                                <button
                                    class="btn fw-normal text-white
                                    {{ $salesOrder->approval_status == 'Approved' ? 'btn-success' : 'btn-secondary' }}"
                                    data-bs-toggle="{{ $salesOrder->approval_status == 'Approved' ? 'modal' : '' }}"
                                    data-bs-target="{{ $salesOrder->approval_status == 'Approved' ? '#invoiceModal' : '' }}"
                                    {{ $salesOrder->approval_status != 'Approved' ? 'disabled' : '' }}>
                                    <i class="ri-file-add-line me-1"></i> Generate Invoice
                                </button>
                            </div>

                        </div>

                        <div class="row g-4">
                            {{-- Basic Info --}}


                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">

                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0 text-primary fw-semibold">Personal Information</h5>
                                        @if (Auth::check() && Auth::user()->user_type == 'admin')
                                            <button type="button"
                                                class="btn btn-primary fw-normal text-white changeStatusBtn"
                                                data-bs-toggle="modal" data-bs-target="#statusModal">
                                                <i class="ri-edit-2-line me-1"></i> Change Status
                                            </button>
                                        @endif
                                    </div>

                                    <ul class="list-unstyled mb-0">
                                        <li><strong>Customer Code:</strong> {{ $salesOrder->customer->code }}</li>
                                        <li><strong>Customer Name:</strong> {{ $salesOrder->customer->name }}</li>
                                        <li><strong>Contact Number:</strong> {{ $salesOrder->customer->mobile_no }}</li>
                                        <li>
                                            <strong>Customer Address:</strong>
                                            {{ $customerAddress
                                                ? $customerAddress->address_line1 .
                                                    ', ' .
                                                    ($customerAddress->cities->name ?? '') .
                                                    ', ' .
                                                    ($customerAddress->states->name ?? '') .
                                                    ', ' .
                                                    ($customerAddress->countries->name ?? '') .
                                                    ' - ' .
                                                    ($customerAddress->pincode ?? '')
                                                : '-' }}
                                        </li>

                                        <li><strong>Email:</strong> {{ $salesOrder->customer->email }}</li>
                                        <li><strong>Credit Limit:</strong>
                                            {{ number_format($salesOrder->customer->credit_limit, 2) }}</li>

                                        <li><strong>Gst Type:</strong>
                                            {{ $salesOrder->customer->gst_type ?? 'N/A' }}
                                        </li>

                                        <li><strong>Gst No:</strong>
                                            {{ $salesOrder->customer->gst_no ?? 'N/A' }}
                                        </li>

                                        <li>
                                            <strong>Place of Supply:</strong>
                                            {{ $salesOrder->customer->states
                                                ? $salesOrder->customer->states->name . ' (' . $salesOrder->customer->states->iso2 . ')'
                                                : 'N/A' }}
                                        </li>

                                    </ul>

                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <h5 class="mb-3 text-primary">Sales Information</h5>
                                    <ul class="list-unstyled mb-0">

                                        <li><strong>Sales Person:</strong> {{ $salesOrder->salesPerson?->full_name }}</li>
                                        <li><strong>Payment Terms:</strong>
                                            {{ $salesOrder->paymentTerms?->days }}{{ $salesOrder->paymentTerms?->name }}
                                        </li>
                                        <li>
                                            <strong>Due Date:</strong>
                                            {{ \Carbon\Carbon::parse($salesOrder->due_date)->format('d-m-Y') }}
                                        </li>
                                        <li><strong>Branch:</strong> {{ $salesOrder->branch?->branch_name }}
                                        </li>


                                        <li>
                                            <strong>
                                                Credit Limit:
                                                {{ $salesOrder->credit_limit }}
                                            </strong>
                                        </li>
                                        <li>
                                            <strong>Edit Reason:</strong>
                                            {{ !empty($salesOrder->remark) ? $salesOrder->remark : 'This invoice has not been edited.' }}
                                        </li>
                                    </ul>
                                    <div class="d-flex align-items-center justify-content-between mb-4">

                                    </div>
                                    @if ($salesOrderApprovals->count() > 0)
                                        @php
                                            $latest = $salesOrderApprovals->last();
                                        @endphp

                                        <div class="mt-2">
                                            <strong>Status Updated By:</strong> {{ $latest->approver->full_name ?? '-' }}
                                            <br>
                                            <strong>Status Updated On:</strong>
                                              {{ formatDate($latest->action_date, 'd-m-Y h:i A') }} <br>

                                            @if ($latest->approval_status == 'Rejected')
                                                <strong>Reason:</strong>
                                                <span class="text-danger">{{ $latest->remark }}</span>
                                            @endif
                                        </div>
                                    @endif

                                </div>
                            </div>

                        </div>

                        <div class="card bg-white  rounded-10 border border-light shadow-sm mt-4">
                            <div class="card-body">
                                <h5 class="mb-3 text-primary">
                                    <i class="ri-file-list-3-line me-2"></i>
                                    Order Items Details
                                </h5>

                                @if ($salesOrderDetails->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle "
                                            style="background:white !important;">
                                            <thead class="table-light" style="background:white !important;">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Product Details</th>
                                                    <!-- <th>Batch</th> -->
                                                    <th>Qty Ordered</th>
                                                    <th>Qty Delivered</th>
                                                    <th>Unit Price</th>
                                                    <th>Discount (%)</th>
                                                    <th>Discount Amt</th>
                                                    <th>GST (%)</th>
                                                    <th>GST Amt</th>
                                                    <th>Total Amount</th>
                                                    <!-- <th>Mfg Date</th>
                                                                                                                                                                     <th>Expiry</th> -->
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($salesOrderDetails as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>

                                                        <!-- PRODUCT DETAILS COMBINED -->
                                                        <td>
                                                            <strong>{{ $item->product->name }}</strong> <br>

                                                            <span class="text-muted">
                                                                <strong>Batch:</strong> {{ $item->batch_id ?? '-' }} <br>

                                                                <strong>Mfg:</strong>
                                                                {{ formatDate($item->manufacturing_date) }}
                                                                <br>

                                                                <strong>Expiry:</strong>
                                                               {{ formatDate($item->expiry_date) }}
                                                            </span>
                                                        </td>

                                                        <td>{{ $item->quantity_ordered }}</td>
                                                        <td>{{ $item->quantity_delivered }}</td>
                                                        <td>{{ number_format($item->unit_price, 2) }}</td>
                                                        <td>{{ $item->discount_percent }}%</td>
                                                        <td>{{ number_format($item->discount_amount, 2) }}</td>
                                                        <td>{{ $item->gst_percent }}%</td>
                                                        <td>{{ number_format($item->gst_amount, 2) }}</td>

                                                        <td class="fw-bold">
                                                            {{ number_format($item->total_amount, 2) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted">No items added in this sales order.</p>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-4">
                            @php
                                $totalProductDiscount = $salesOrder->salesOrderDetails->sum('discount_amount');
                                $grossWithoutGst = $salesOrder->salesOrderDetails->sum(function ($item) {
                                    return $item->unit_price * $item->quantity_ordered;
                                });
                            @endphp
                            <div class="col-md-3">
                                <div class="border rounded-3 p-3">
                                    <h6 class="text-muted">Gross Amount </h6>
                                    <h4 class="text-dark">₹ {{ number_format($grossWithoutGst, 2) }}</h4>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="border rounded-3 p-3">
                                    <h6 class="text-muted">GST Amount</h6>
                                    <h4 class="text-dark">₹ {{ number_format($salesOrder->tax_amount, 2) }}</h4>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="border rounded-3 p-3">
                                    <h6 class="text-muted">Total Product Discount</h6>
                                    <h4 class="text-dark">
                                        ₹ {{ number_format($totalProductDiscount, 2) }}
                                    </h4>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="border rounded-3 p-3">
                                    <h6 class="text-muted">Net Payable</h6>
                                    <h4 class="text-success">₹ {{ number_format($salesOrder->net_amount, 2) }}</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="d-flex gap-2">

                                <a href="{{ route('sale-orders.index') }}" class="btn btn-danger fw-normal text-white">
                                    <i class="ri-arrow-left-line me-1"></i> Back
                                </a>
                                <a href="javascript:void(0)" class="btn btn-primary fw-normal text-white open-sales-edit"
                                    data-id="{{ $salesOrder->id }}"
                                    data-url="{{ route('sale-orders.edit', encrypt($salesOrder->id)) }}">
                                    <i class="ri-edit-2-line me-1"></i> Edit Sales Order
                                </a>


                            </div>
                        </div>

                        <div class="card bg-white rounded-10 border border-light shadow-sm mt-4">
                            <div class="card-body">
                                <h5 class="mb-3 text-primary">
                                    <i class="ri-history-line me-2"></i>
                                    Approval History
                                </h5>

                                @if ($salesOrderApprovals->count() > 0)
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
                                            @foreach ($salesOrderApprovals as $row)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <span
                                                            class="badge 
                                                {{ $row->approval_status == 'Approved'
                                                    ? 'bg-success'
                                                    : ($row->approval_status == 'Rejected'
                                                        ? 'bg-danger'
                                                        : 'bg-warning') }}">
                                                            {{ $row->approval_status }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $row->remark ?? '-' }}</td>
                                                    <td>{{ $row->updatedBy->full_name ?? '-' }}</td>
                                                   <td>{{ formatDate($row->action_date, 'd-m-Y h:i A') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted">No approval history found.</p>
                                @endif
                            </div>
                        </div>

                        @if (isset($invoiceOrders) && $invoiceOrders->count() > 0)
                            <div class="card bg-white rounded-10 border border-light shadow-sm mt-4">
                                <div class="card-body">
                                    <h5 class="mb-3 text-primary">
                                        <i class="ri-bill-line me-2"></i>
                                        Invoice Orders
                                    </h5>

                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Invoice No</th>
                                                    <th>Customer Details</th>
                                                    <th>Payment Status</th>
                                                    <th>Total Qty</th>
                                                    <th>Amount</th>
                                                    <!-- <th>Unit Price</th> -->
                                                    <th>Balance Due</th>
                                                    <!-- <th>Action</th> -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($invoiceOrders as $invoice)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($invoice->date)->format('d M Y') }}
                                                        </td>

                                                        <td>
                                                            <strong>{{ $invoice->code ?? 'INV-' . $invoice->id }}</strong>
                                                        </td>

                                                        <td>
                                                            <strong>{{ $invoice->customer->name }}</strong> <br>
                                                            <span class="text-muted">
                                                                {{ $invoice->customer->email }} <br>
                                                                {{ $invoice->customer->mobile_no }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="">{{ $invoice->payment_status }}</span>
                                                        </td>
                                                        <td>
                                                            {{ $invoice->invoiceDetails->sum('quantity_delivered') }}
                                                        </td>

                                                        <td>
                                                            ₹ {{ number_format($invoice->net_amount, 2) }}
                                                        </td>


                                                        <td>
                                                            @php
                                                                $paymentReceived = $invoice->payments->sum(function (
                                                                    $p,
                                                                ) {
                                                                    return $p->amount_paid + $p->amount_withheld;
                                                                });

                                                                $paymentDue = $invoice->net_amount - $paymentReceived;
                                                            @endphp
                                                            ₹ {{ number_format($paymentDue, 2) }}
                                                        </td>


                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="modal fade" id="viewSalesOrderModal" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered invoice-view-modal pdf-modal">

                                <div class="modal-content">

                                    <!-- HEADER -->
                                    <div class="modal-header d-flex align-items-center justify-content-between">
                                        <h5 class="modal-title mb-0">Sales Order</h5>

                                        <div class="d-flex align-items-center gap-2">
                                            <a href="#" id="soDownloadBtn" target="_blank"
                                                title="Download Sales Order PDF" class="text-danger fs-20">
                                                <i class="fas fa-file-pdf"></i>
                                                <i class="fas fa-download ms-1 fs-14"></i>
                                            </a>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                    </div>

                                    <!-- BODY -->
                                    <div class="modal-body p-0">
                                        <div class="pdf-wrapper">

                                            <!-- COMPANY HEADER -->
                                            <table width="100%" style="margin-bottom:18px;">
                                                <tr>
                                                    <td style="border:none; padding:0;">
                                                        <img src="{{ asset(setting('logo')) }}" style="max-height:60px;">
                                                    </td>
                                                    <td style="border:none; padding:0;"></td>
                                                </tr>

                                                <tr>
                                                    <td style="border:none; padding:0; font-size:12px;">
                                                        <strong>{{ setting('company_name') }}</strong><br>
                                                        {{ setting('company_address') }}<br>
                                                        Email: {{ setting('company_email') }}<br>
                                                        Phone: {{ setting('company_phone') }}
                                                    </td>

                                                    <td style="border:none; padding:0; text-align:right;">
                                                        <div class="pdf-title">Sales Order</div>
                                                        <div class="pdf-sub">
                                                            Date: <span id="vso_date">-</span><br>
                                                            Order No: <span id="vso_code">-</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>


                                            <!-- CUSTOMER -->
                                            <div class="section-title">Customer Details</div>
                                            <table class="pdf-table">
                                                <tr>
                                                    <th width="20%">Customer Code</th>
                                                    <td width="30%" id="vso_customer_code">-</td>
                                                    <th width="20%">Customer Name</th>
                                                    <td width="30%" id="vso_customer_name">-</td>
                                                </tr>
                                                <tr>
                                                    <th>Contact Number</th>
                                                    <td id="vso_customer_mobile">-</td>
                                                    <th>Email</th>
                                                    <td id="vso_customer_email">-</td>
                                                </tr>
                                                <tr>
                                                    <th>Address</th>
                                                    <td colspan="3" id="vso_customer_address">-</td>
                                                </tr>
                                                <tr>
                                                    <th>Credit Limit</th>
                                                    <td id="vso_customer_credit_limit">-</td>
                                                    <th>GST Type</th>
                                                    <td id="vso_customer_type">-</td>
                                                </tr>

                                                <tr>

                                                    <th>GST No</th>
                                                    <td id="vso_customer_gst">-</td>
                                                    <th>Place of Supply</th>
                                                    <td id="vso_customer_supply">-</td>
                                                </tr>
                                            </table>


                                            <div class="section-title">Sales Information</div>

                                            <table class="pdf-table">
                                                <tr>
                                                    <th width="20%">Sales Person</th>
                                                    <td width="30%" id="vinv_sales_person">-</td>

                                                    <th width="20%">Branch</th>
                                                    <td width="30%" id="vinv_branch">-</td>
                                                </tr>

                                                <tr>
                                                    <th>Payment Terms</th>
                                                    <td colspan="3" id="vinv_payment_terms">-</td>
                                                </tr>

                                            </table>

                                            <!-- ITEMS -->
                                            <div class="section-title">Order Items</div>
                                            <table class="pdf-table text-center">
                                                <thead>
                                                    <tr>
                                                        <th width="40">#</th>
                                                        <th width='70'>Product</th>
                                                        <th width="70">Qty ordered</th>
                                                        <th width="70">Qty delivered</th>

                                                        <th width="90">unit price</th>
                                                        <th width="90">disc %</th>
                                                        <th width="90"> Disc Amt</th>
                                                        <th width="70">GST %</th>
                                                        <th width="90"> GST Amt</th>

                                                        <th width="110">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="vso_items"></tbody>
                                            </table>

                                            <!-- SUMMARY -->
                                            <div class="section-title">Summary</div>
                                            <table class="pdf-table">
                                                <tr>
                                                    <th>Gross Amount</th>
                                                    <td class="amount" id="vso_gross">₹ 0.00</td>
                                                </tr>

                                                <tr>
                                                    <th>GST Amount</th>
                                                    <td class="amount" id="vso_gst">₹ 0.00</td>
                                                </tr>
                                                <tr>
                                                    <th>Discount Amount</th>
                                                    <td class="amount" id="vso_discount">₹ 0.00</td>

                                                </tr>
                                                <tr>
                                                    <th>Net Amount</th>
                                                    <td class="amount" id="vso_net">₹ 0.00</td>
                                                </tr>
                                            </table>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>


                        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
                                <form id="statusForm" class="modal-content bg-white">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" id="status_id" name="status_id"
                                        value="{{ $salesOrder->id }}">
                                    <div class="modal-header border-border-color-40 p-20">
                                        <h5 class="modal-title fs-18 fw-medium mb-0" id="statusModalLabel">Update Sales
                                            Order
                                            Status</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-20 pb-0">
                                        <div class="row">
                                            <div class="col-lg-12 mb-20">
                                                <label class="label fs-16 mb-2">Status</label>
                                                <select class="form-select form-control" name="status" id="status">
                                                    <!-- <option value="Pending">Pending</option> -->
                                                    <option value="Approved">Approved</option>
                                                    <option value="Rejected">Rejected</option>
                                                </select>
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
                        <div class="modal fade" id="salesEditReasonModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
                                <form id="salesEditReasonForm" class="modal-content bg-white">
                                    @csrf

                                    <input type="hidden" id="salesOrderId">
                                    <input type="hidden" id="salesEditUrl">

                                    <div class="modal-header border-border-color-40 p-20">
                                        <h5 class="modal-title fs-18 fw-medium mb-0">
                                            Reason for Editing Sales Order
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body p-20 pb-0">
                                        <div class="mb-20">
                                            <label class="label">
                                                Reason <span class="text-danger">*</span>
                                            </label>

                                            <div class="form-floating">
                                                <textarea id="salesEditReason" class="form-control" rows="3" placeholder="Enter reason" style="height:100px"
                                                    required></textarea>
                                                <label>Enter minimum 3 character</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer border-0 p-20 pt-0">
                                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">
                                            Cancel
                                        </button>

                                        <button type="submit" id="salesReasonSubmitBtn"
                                            class="btn btn-primary text-white" disabled>
                                            Submit & Continue
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>


                        <div class="modal fade" id="invoiceModal" tabindex="-1">
                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                <form action="{{ route('invoice-orders.store') }}" method="POST" id="invoiceForm">
                                    @csrf
                                    <input type="hidden" name="sales_order_id" value="{{ $salesOrder->id }}">

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Generate Invoice</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <table class="table table-bordered align-middle">
                                                <thead>
                                                    <tr>
                                                        <th>Select</th>
                                                        <th>Product</th>
                                                        <th>Ordered Qty</th>
                                                        <th>Already Invoiced</th>
                                                        <th>Invoice Qty</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($salesOrderDetails as $item)
                                                        @php
                                                            $remainingQty =
                                                                $item->quantity_ordered - $item->quantity_delivered;
                                                        @endphp

                                                        <tr>
                                                            <td>
                                                                <input type="checkbox"
                                                                    class="form-check-input invoice-check"
                                                                    data-target="qty_{{ $item->id }}"
                                                                    name="selected_items[]" value="{{ $item->id }}"
                                                                    {{ $remainingQty > 0 ? 'checked' : 'disabled' }}>
                                                            </td>

                                                            <td>{{ $item->product->name }}</td>

                                                            <td>{{ $item->quantity_ordered }}</td>

                                                            <td>{{ $item->quantity_delivered }}</td>

                                                            <td>
                                                                <input type="number" name="items[{{ $item->id }}]"
                                                                    id="qty_{{ $item->id }}"
                                                                    class="form-control invoice-qty" min="0"
                                                                    max="{{ $remainingQty }}"
                                                                    value="{{ $remainingQty }}"
                                                                    {{ $remainingQty == 0 ? 'readonly' : '' }}>
                                                                <small class="text-muted">Max: {{ $remainingQty }}</small>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger text-white"
                                                data-bs-dismiss="modal">
                                                Cancel
                                            </button>
                                            <button type="submit" id="generateInvoiceBtn"
                                                class="btn btn-primary text-white">
                                                Generate Invoice
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        @endsection

        @push('scripts')
            <script>
                $(document).ready(function() {
                    $('.changeStatusBtn').on('click', function(e) {
                        e.preventDefault();
                        $('#statusModal').modal('show');
                    });

                    $('#status').on('change', function() {
                        if ($(this).val() === 'Rejected') {
                            $('#reason_box').show();
                        } else {
                            $('#reason_box').hide();
                        }
                    });

                    $('#reason_box').hide();

                    $('#statusForm').on('submit', function(e) {
                        e.preventDefault();
                        var form = $(this);
                        var url = "{{ route('sales-orders.change-status', $salesOrder->id) }}";

                        $.ajax({
                            type: "POST",
                            url: url,
                            data: form.serialize(),
                            beforeSend: function() {
                                form.find('button[type="submit"] .spinner-border').removeClass(
                                    'd-none');
                            },
                            success: function(response) {
                                location.reload();
                            },
                            error: function(xhr) {
                                alert('An error occurred while updating the status.');
                            },
                            complete: function() {
                                form.find('button[type="submit"] .spinner-border').addClass('d-none');
                            }
                        });
                    });
                });
            </script>

            <script>
                function toggleGenerateButton() {
                    let hasQty = false;

                    $('.invoice-qty').each(function() {
                        let val = parseFloat($(this).val()) || 0;
                        if (val > 0) {
                            hasQty = true;
                        }
                    });

                    if (hasQty) {
                        $('#generateInvoiceBtn').show();
                    } else {
                        $('#generateInvoiceBtn').hide();
                    }
                }

                // 🔁 On qty change
                $(document).on('input', '.invoice-qty', function() {
                    let max = parseInt($(this).attr('max')) || 0;
                    let val = parseInt($(this).val()) || 0;

                    if (val > max) $(this).val(max);
                    if (val < 0) $(this).val(0);

                    toggleGenerateButton();
                });

                // 🔁 On checkbox toggle
                $(document).on('change', '.invoice-check', function() {
                    let target = $('#' + $(this).data('target'));

                    if ($(this).is(':checked')) {
                        target.prop('readonly', false);
                        if (parseInt(target.val()) === 0) {
                            target.val(target.attr('max'));
                        }
                    } else {
                        target.val(0);
                        target.prop('readonly', true);
                    }

                    toggleGenerateButton();
                });
                $(document).on('click', '.open-sales-edit', function() {
                    $('#salesOrderId').val($(this).data('id'));
                    $('#salesEditUrl').val($(this).data('url'));
                    $('#salesEditReason').val('');
                    $('#salesReasonSubmitBtn').prop('disabled', true);
                    $('#salesEditReasonModal').modal('show');
                });

                $('#salesEditReason').on('input', function() {
                    $('#salesReasonSubmitBtn').prop(
                        'disabled',
                        $(this).val().trim().length < 3
                    );
                });

                $('#salesEditReasonForm').on('submit', function(e) {
                    e.preventDefault();

                    $.post("{{ route('sale-orders.save-edit-remark') }}", {
                        _token: "{{ csrf_token() }}",
                        sales_order_id: $('#salesOrderId').val(),
                        remark: $('#salesEditReason').val()
                    }, function() {
                        window.location.href = $('#salesEditUrl').val();
                    });
                });


                // 🔥 Initial check (modal open hote hi)
                $('#invoiceModal').on('shown.bs.modal', function() {
                    toggleGenerateButton();
                });
            </script>
            <script>
                $(document).on('click', '.viewSalesOrderBtn', function() {

                    let id = $(this).data('id');
                    $('#vso_items').html('');

                    $.get(`/sale-orders/${id}/details`, function(res) {

                        $('#vso_customer_code').text(res.customer?.code ?? '-');
                        $('#vso_customer_name').text(res.customer?.name ?? '-');
                        $('#vso_customer_mobile').text(res.customer?.mobile_no ?? '-');
                        $('#vso_customer_email').text(res.customer?.email ?? '-');

                        let addr = res.customer?.get_customer_address;

                        let fullAddress = '-';
                        if (addr) {
                            fullAddress =
                                (addr.address_line1 ?? '') + ', ' +
                                (addr.cities?.name ?? '') + ', ' +
                                (addr.states?.name ?? '') + ', ' +
                                (addr.countries?.name ?? '') + ' - ' +
                                (addr.pincode ?? '');
                        }

                        $('#vso_customer_address').text(fullAddress);

                        $('#vso_customer_credit_limit').text(
                            res.customer?.credit_limit ?
                            '₹ ' + parseFloat(res.customer.credit_limit).toFixed(2) :
                            'N/A'
                        );
                        $('#vso_customer_gst').text(res.customer?.gst_no ?? 'N/A');
                        $('#vso_customer_type').text(res.customer?.gst_type ?? 'N/A');
                        $('#vso_customer_supply').text(
                            res.customer?.states ?
                            `${res.customer.states.name} (${res.customer.states.iso2})` :
                            'N/A'
                        );


                        $('#vinv_sales_person').text(res.sales_person?.full_name ?? 'N/A');
                        $('#vinv_branch').text(res.branch?.branch_name ?? 'N/A');
                        $('#vinv_payment_terms').text(
                            res.payment_terms ?
                            res.payment_terms.days + ' (' + res.payment_terms.name + ')' :
                            'N/A'
                        );

                        $('#vso_date').text(res.date);
                        $('#vso_code').text(res.code);

                        let gross = 0;
                        let gst = 0;
                        let discount = 0;

                        res.items.forEach((item, i) => {

                            let qty = Number(item.quantity_ordered) || 0;
                            let price = Number(item.unit_price) || 0;

                            let total = qty * price;
                            gross += total;

                            let gstAmount = Number(item.gst_amount) || 0;
                            let discountAmount = Number(item.discount_amount) || 0;

                            gst += gstAmount;
                            discount += discountAmount;

                            $('#vso_items').append(`
                            <tr>
                                <td>${i + 1}</td>
                                <td>${item.product?.name ?? '-'}</td>
                                <td>${qty}</td>
                                <td>${item.quantity_delivered ?? 0}</td>
                                <td>₹ ${price.toFixed(2)}</td>
                                <td>${item.discount_percent ?? 0}%</td>
                                <td>₹ ${discountAmount.toFixed(2)}</td>
                                <td>${item.gst_percent ?? 0}%</td>
                                <td>₹ ${gstAmount.toFixed(2)}</td>
                                <td>₹ ${Number(item.total_amount).toFixed(2)}</td>
                            </tr>
                        `);
                        });

                        $('#vso_gross').text('₹ ' + gross.toFixed(2));
                        $('#vso_gst').text('₹ ' + gst.toFixed(2));
                        $('#vso_discount').text('₹ ' + discount.toFixed(2));
                        $('#vso_net').text('₹ ' + Number(res.net_amount).toFixed(2));

                        $('#soDownloadBtn').attr(
                            'href',
                            `/sale-orders/pdf/${res.encrypted_id}`
                        );

                        $('#viewSalesOrderModal').modal('show');

                    });
                });
            </script>
        @endpush
