@push('styles')
    <style>
        /* === PDF MODAL BASE === */
        .invoice-view-modal {
            max-width: 700px;
        }

        .invoice-view-modal .modal-content {
            border-radius: 8px;
        }

        .pdf-wrapper {
            padding: 22px 26px;
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #222;
        }

        /* === TITLES === */
        .pdf-title {
            font-size: 20px;
            font-weight: 800;
            color: #004e92;
        }

        .pdf-sub {
            font-size: 11px;
            color: #444;
            margin-top: 5px;
        }

        /* === SECTION TITLE === */
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

        /* === TABLE === */
        .pdf-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }

        .pdf-table th,
        .pdf-table td {
            padding: 6px 8px;
            font-size: 10.6px;
            border: 1px solid #d3d7e3;
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

        /* === PDF ICON === */
        .pdf-icon {
            text-decoration: none;
        }
    </style>
@endpush
@extends('include.master')
@section('content')

    <style>
        table.table td,
        table.table th {
            background: white !important;
        }
    </style>


    <div class="main-content-container overflow-hidden">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4 mt-1">
            <h3 class="mb-0">Credit Note Details</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('credit-notes.index') }}">Credit Notes</a>
                    </li>
                    <li class="breadcrumb-item active">View</li>
                </ol>
            </nav>
        </div>

        {{-- MAIN CARD --}}
        <div class="card bg-white p-4 shadow-sm">

            <div class="mt-4 d-flex justify-content-between align-items-center mb-4">

                <!-- LEFT SIDE BUTTONS -->
                <div class="d-flex gap-2">
                    <button class="btn btn-warning text-white openRefundModal" data-credit-note-id="{{ $creditNote->id }}"
                        data-credit-note-number="{{ $creditNote->credit_note_number }}"
                        data-customer-id="{{ $creditNote->customer_id }}"
                        data-customer-name="{{ $creditNote->customer->name }}"
                        data-balance-due="{{ $creditNote->balance_due }}" data-amount="{{ $creditNote->net_amount }}">
                        <i class="ri-refund-2-line"></i> Refund
                    </button>

                    <a href="javascript:void(0)" class="btn btn-primary text-white" data-bs-toggle="modal"
                        data-bs-target="#applyToInvoiceModal">
                        Apply to Invoice
                    </a>
                </div>

                <!-- RIGHT SIDE BUTTON -->
                <button class="btn btn-primary btn-sm viewCreditNoteBtn text-white" data-id="{{ $creditNote->id }}"
                    title="View Credit Note">
                    View
                </button>



            </div>

            {{-- ACTIONS --}}
            <!-- <div class="mt-4 d-flex justify-content-between align-items-center">

                                    <a href="{{ route('credit-notes.pdf', encrypt($creditNote->id)) }}" target="_blank">
                                        <button class="btn btn-info text-white mb-3">
                                            <i class="fas fa-file-pdf"></i> Download PDF
                                        </button>
                                    </a>

                                    <a href="{{ route('credit-notes.edit', encrypt($creditNote->id)) }}" class="btn btn-primary text-white">
                                        <i class="fas fa-edit"></i> Edit Credit Note
                                    </a>
                                </div>
                     -->

            {{-- TITLE --}}
            <div class="d-flex justify-content-between align-items-center mb-2 mt-4">
                <h4 class="mb-0 text-primary">
                    {{ $creditNote->credit_note_number }}
                </h4>
            </div>

            {{-- BASIC INFO --}}
            <div class="row g-4">

                {{-- CUSTOMER INFO --}}
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h5 class="text-primary mb-3">Customer Information</h5>

                        <ul class="list-unstyled mb-0">
                            <li><strong>Code:</strong> {{ $creditNote->customer?->code }}</li>
                            <li><strong>Name:</strong> {{ $creditNote->customer?->name }}</li>
                            <li><strong>Mobile:</strong> {{ $creditNote->customer?->mobile_no }}</li>
                            <li><strong>Email:</strong> {{ $creditNote->customer?->email }}</li>
                            <li>
                                <strong>Address:</strong>
                                {{ $customerAddress
                                    ? $customerAddress->address_line1 .
                                        ', ' .
                                        ($customerAddress->cities->name ?? '') .
                                        ', ' .
                                        ($customerAddress->states->name ?? '') .
                                        ' - ' .
                                        ($customerAddress->pincode ?? '')
                                    : '-' }}
                            </li>
                             <li><strong>Gst Type:</strong> {{ $creditNote->customer?->gst_type }}</li>
                              <li><strong>Gst No:</strong> {{ $creditNote->customer?->gst_no }}</li>
                               <li>
                                            <strong>Place of Supply:</strong>
                                            {{ $creditNote->customer->states
                                                ? $creditNote->customer->states->name . ' (' . $creditNote->customer->states->iso2 . ')'
                                                : '-' }}
                                        </li>
                        </ul>
                    </div>
                </div>

                {{-- SALES INFO --}}
                <div class="col-md-6">
                    <div class="border rounded p-3 h-100">
                        <h5 class="text-primary mb-3">Sales Information</h5>

                        <ul class="list-unstyled mb-0">
                            <li><strong>Sales Person:</strong> {{ $creditNote->salesPerson?->full_name }}</li>
                            <li><strong>Branch:</strong> {{ $creditNote->branch?->branch_name }}</li>
                            <!-- <li><strong>Type:</strong> {{ ucfirst($creditNote->type) }}</li> -->
                            <li><strong>Reference Number:</strong> {{ $creditNote->reference_number }}</li>
                            <li><strong>Reason Type:</strong>
                                {{ ucfirst(str_replace('_', ' ', $creditNote->reason_type)) }}</li>
                            <li><strong>Date:</strong>
                               {{ formatDate($creditNote->credit_note_date) }}
                            </li>
                            <li><strong>Status:</strong>
                                <span class="badge bg-success">{{ ucfirst($creditNote->status) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            {{-- ITEMS --}}
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="text-primary mb-3">Credit Note Items</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Invoice Ref</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Disc %</th>
                                    <th>Disc Amt</th>
                                    <th>GST %</th>
                                    <th>GST Amt</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $gross = 0;
                                    $totalGst = 0;
                                    $totalDiscount = 0;
                                @endphp

                                @foreach ($creditNoteDetails as $item)
                                    @php
                                        $amount = $item->quantity * $item->unit_price;
                                        $discAmt = $amount * ($item->discount_percent / 100);
                                        $gstAmt = ($amount - $discAmt) * ($item->gst_percent / 100);

                                        $gross += $amount;
                                        $totalGst += $gstAmt;
                                        $totalDiscount += $discAmt;
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <strong>{{ $item->product?->name }}</strong><br>
                                            <small class="text-muted">
                                                Batch: {{ $item->batch_id ?? '-' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if ($item->invoice_detail_id)
                                                <span class="badge bg-info">Invoice Item</span>
                                            @else
                                                <span class="badge bg-secondary">Manual Item</span>
                                            @endif
                                        </td>

                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->unit_price, 2) }}</td>
                                        <td>{{ $item->discount_percent }}%</td>
                                        <td>{{ number_format($discAmt, 2) }}</td>
                                        <td>{{ $item->gst_percent }}%</td>
                                        <td>{{ number_format($gstAmt, 2) }}</td>

                                        <td class="fw-bold">
                                            {{ number_format($item->total_amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="viewCreditNoteModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered invoice-view-modal">


                    <div class="modal-content">

                        <!-- HEADER -->
                        <div class="modal-header d-flex align-items-center justify-content-between">

                            <h5 class="modal-title mb-0">Credit Note</h5>

                            <div class="d-flex align-items-center gap-2">
                                <!-- Download PDF -->
                                <a href="#" target="_blank" id="cnDownloadBtn" title="Download Credit Note PDF"
                                    class="text-danger fs-20 pdf-icon">
                                    <i class="fas fa-file-pdf"></i>
                                    <i class="fas fa-download ms-1 fs-14"></i>
                                </a>

                                <!-- Close -->
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                        </div>

                        <!-- BODY -->
                        <div class="modal-body">
                            <div class="pdf-wrapper">

                                <!-- HEADER -->
                                <table width="100%" style="margin-bottom:18px;">
                                    <tr>
                                        <td width="60%">
                                            <img src="{{ asset(setting('logo')) }}" style="max-height:60px;"><br>
                                            <strong>{{ setting('company_name') }}</strong><br>
                                            {{ setting('company_address') }}<br>
                                            Email: {{ setting('company_email') }}<br>
                                            Phone: {{ setting('company_phone') }}
                                        </td>

                                        <td width="40%" style="text-align:right;">
                                            <div class="pdf-title">Credit Note</div>
                                            <div class="pdf-sub">
                                                Date: <span id="vcn_date">-</span><br>
                                                Credit Note No: <span id="vcn_number">-</span>
                                            </div>
                                        </td>
                                    </tr>
                                </table>

                                <!-- CUSTOMER -->
                                <div class="section-title">Customer Details</div>
                                <table class="pdf-table">
                                    <tr>
                                        <th>Customer Code</th>
                                        <td id="vcn_customer_code">-</td>
                                        <th>Customer Name</th>
                                        <td id="vcn_customer_name">-</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td id="vcn_customer_email">-</td>
                                        <th>Phone</th>
                                        <td id="vcn_customer_phone">-</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td colspan="3" id="vcn_customer_address">-</td>
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

                                <!-- ITEMS -->
                                <div class="section-title">Credit Note Items</div>
                                <table class="pdf-table text-center">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Disc %</th>
                                            <th>Disc Amt</th>
                                            <th>GST %</th>
                                            <th>GST Amt</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="vcn_items"></tbody>
                                </table>

                                <!-- SUMMARY -->
                                <div class="section-title">Summary</div>
                                <table class="pdf-table">
                                    <tr>
                                        <th>Gross Amount</th>
                                        <td class="amount" id="vcn_gross">₹ 0.00</td>
                                    </tr>
                                    <tr>
                                        <th>GST Amount</th>
                                        <td class="amount" id="vcn_gst">₹ 0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Discount Amount</th>
                                        <td class="amount" id="vcn_discount">₹ 0.00</td>
                                    <tr class="total-row">
                                        <th>Net Amount</th>
                                        <td class="amount" id="vcn_net">₹ 0.00</td>
                                    </tr>
                                </table>

                            </div>
                        </div>

                    </div>
                </div>
            </div>



            {{-- TOTALS --}}
            <div class="row mt-4 g-3">
                <div class="col-md-3">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-muted mb-1">Gross Amount</h6>
                        <h4 class="mb-0">₹ {{ number_format($gross, 2) }}</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-muted mb-1">GST Amount</h6>
                        <h4 class="mb-0">₹ {{ number_format($totalGst, 2) }}</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-muted mb-1">Discount Amount</h6>
                        <h4 class="mb-0">₹ {{ number_format($totalDiscount, 2) }}</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded p-3 h-100">
                        <h6 class="text-muted mb-1">Net Amount</h6>
                        <h4 class="text-success mb-0">
                            ₹ {{ number_format($creditNote->net_amount, 2) }}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="refundModal" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
                    <div class="modal-content bg-white rounded-10">

                        <!-- HEADER -->
                        <div class="modal-header border-border-color-40 p-20">
                            <h5 class="modal-title fs-18 fw-medium mb-0">
                                Refund (<span id="cnNumber"></span>)
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <form id="refundForm">
                            @csrf

                            <!-- BODY -->
                            <div class="modal-body p-20 pb-0">

                                <!-- CUSTOMER + CREDIT NOTE -->
                                <div class="row mb-20">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="avatar bg-light rounded-circle d-flex align-items-center justify-content-center"
                                                style="width:40px;height:40px;">
                                                <i class="ri-user-3-line fs-4 text-muted"></i>
                                            </div>
                                            <div>
                                                <div class="text-muted fs-13">Customer Name</div>
                                                <div class="fw-medium" id="customerName">-</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 text-end">
                                        <div class="text-muted fs-13">Credit Note Number</div>
                                        <div class="fw-medium" id="creditNoteNumber">-</div>
                                    </div>
                                </div>

                                <!-- AMOUNT SECTION -->
                                <div class="bg-light rounded-10 mt-3">
                                    <div class="row align-items-center">
                                        <div class="col-md-6 mb-4">
                                            <label class="label mb-2">
                                                Amount <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">CAD</span> 
                                                <input type="number" step="0.01" name="amount" id="refund_amount" required min='0'
                                                    class="form-control fw-medium" data-rule-required="true">
                                            </div>
                                        </div>

                                        <div class="col-md-6 text-end">
                                            <div class="text-muted fs-13">Balance</div>
                                            <div class="fw-medium" id="balanceAmount">0.00</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- DATE + PAYMENT -->
                                <div class="row mb-20">
                                    <div class="col-md-6">
                                        <label class="label mb-2">
                                            Refunded On <span class="text-danger">*</span>
                                        </label>
                                        <input type="date" name="refund_order_date" value="{{ date('Y-m-d') }}"
                                            class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="label mb-2">Payment Mode</label>
                                        <select name="payment_method" class="form-select form-control">
                                            <option value="cash">Cash</option>
                                            <option value="bank">Bank</option>
                                            <option value="upi">UPI</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- REFERENCE -->
                                <div class="row mb-20">
                                    <div class="col-md-12">
                                        <label class="label mb-2">Reference #</label>
                                        <input type="text" name="reference_number" class="form-control">
                                    </div>
                                </div>

                                <!-- DESCRIPTION -->
                                <div class="mb-20">
                                    <label class="label mb-2">Description</label>
                                    <textarea name="remarks" rows="3" class="form-control"></textarea>
                                </div>

                                <input type="hidden" name="credit_note_id" id="credit_note_id">
                                <input type="hidden" name="customer_id" id="customer_id">

                            </div>

                            <!-- FOOTER -->
                            <div class="modal-footer border-0 p-20 pt-0">
                                <button type="button" class="btn btn-danger fw-normal text-white"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary fw-normal text-white">
                                    Save
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            {{-- APPLY TO INVOICE MODAL --}}
            <div class="modal fade" id="applyToInvoiceModal" tabindex="-1">
                <div class="modal-dialog modal-xl modal-dialog-centered">

                    <form class="modal-content bg-white" method="POST" id="applyCreditForm"
                        action="{{ route('credit-notes.apply-invoice', encrypt($creditNote->id)) }}">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title text-primary">
                                Apply Credit Note to Invoice
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            {{-- CREDIT NOTE SUMMARY --}}
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <small class="text-muted">Credit Note No</small>
                                        <h6 class="mb-0">{{ $creditNote->credit_note_number }}</h6>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <small class="text-muted">Total Credit</small>
                                        <h6 class="text-success mb-0">
                                            ₹ {{ number_format($creditNote->net_amount, 2) }}
                                        </h6>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="border rounded p-3">
                                        <small class="text-muted">Used Amount</small>
                                        <h6 class="text-warning mb-0">
                                            ₹ {{ number_format($creditNote->net_amount - $creditNote->balance_due, 2) }}
                                        </h6>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="border rounded p-3 bg-light">
                                        <small class="text-muted">Available Balance</small>
                                        <h6 class="text-danger mb-0">
                                            ₹ {{ number_format($creditNote->balance_due, 2) }}
                                        </h6>
                                    </div>
                                </div>
                            </div>

                            {{-- CUSTOMER INFO --}}
                            <div class="border rounded p-3 mb-4 font-size-16 fw-semibold">
                                <strong>Customer :</strong>
                                {{ $creditNote->customer->name }}
                                <span class="text-muted ms-2">
                                    ({{ $creditNote->customer->code }})
                                </span>
                            </div>

                            {{-- INVOICE LIST --}}
                            <h6 class="text-primary mb-2">Select Invoice(s)</h6>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Select</th>
                                            <th>Invoice No</th>
                                            <th>Date</th>
                                            <th>Invoice Total</th>
                                            <th>Paid</th>
                                            <th>Balance</th>
                                            <th>Apply Amount</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($openInvoices as $invoice)
                                            <tr>
                                                <td>
                                                    @php
                                                        $adjustableBalance =
                                                            $invoice->balance_amount > 0
                                                                ? $invoice->balance_amount
                                                                : $invoice->total_amount;
                                                    @endphp

                                                    <input type="checkbox" class="form-check-input invoice-check"
                                                        data-invoice-id="{{ $invoice->id }}"
                                                        data-balance="{{ $adjustableBalance }}">

                                                </td>

                                                <td>
                                                    <span class="fw-semibold">
                                                        {{ $invoice->code }}
                                                    </span>
                                                </td>

                                                <td>
                                                    {{ \Carbon\Carbon::parse($invoice->date)->format('d M Y') }}
                                                </td>

                                                <td>
                                                    ₹ {{ number_format($invoice->total_amount, 2) }}
                                                </td>

                                                <td>
                                                    @php
                                                        $paidAmount =
                                                            $invoice->balance_amount < $invoice->total_amount
                                                                ? $invoice->total_amount - $invoice->balance_amount
                                                                : 0;
                                                    @endphp
                                                    ₹ {{ number_format($paidAmount, 2) }}
                                                </td>

                                                @php
                                                    $adjustableBalance =
                                                        $invoice->balance_amount > 0
                                                            ? $invoice->balance_amount
                                                            : $invoice->total_amount;
                                                @endphp

                                                <td class="text-danger fw-semibold">
                                                    ₹ {{ number_format($adjustableBalance, 2) }}
                                                </td>

                                                <td>
                                                    <input type="number" name="apply[{{ $invoice->id }}]"
                                                        class="form-control form-control-sm text-end apply-amount"
                                                        max="{{ $adjustableBalance }}" step="0.01" readonly min='0'
                                                        placeholder="0.00">
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">
                                                    No open invoices available
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>

                            {{-- SUMMARY --}}
                            <div class="row mt-4">
                                <div class="col-md-4 offset-md-8">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th class="table-light">Total Applying</th>
                                            <td class="text-end fw-bold" id="totalApplying">
                                                ₹ 0.00
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="table-light">Remaining Credit</th>
                                            <td class="text-end text-danger fw-bold" id="remainingCredit">
                                                ₹ {{ number_format($creditNote->balance_due, 2) }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer justify-content-between">
                            <span class="text-muted">
                                Credit will be adjusted against selected invoice(s)
                            </span>

                            <div>
                                <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">
                                    Cancel
                                </button>
                                <button type="submit" class="btn btn-primary text-white">
                                    Apply Credit Note
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>

        </div>


        </form>
    </div>
    </div>



    {{-- Refund History --}}
    @if ($creditNote->refundOrders->count())
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold text-primary">
                    <i class="fas fa-undo-alt me-2"></i>Refund History
                </h5>

                <span class="badge bg-success fs-6 px-3 py-2">
                    Total Refunded :
                    ₹ {{ number_format($creditNote->refundOrders->sum('amount'), 2) }}
                </span>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">#</th>
                                <th>Refund No</th>
                                <th>Date</th>
                                <th>Mode</th>
                                <th>Reference</th>
                                <th>Remarks</th>
                                <th class="text-end">Amount</th>
                                <th class="text-center" width="160">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($creditNote->refundOrders as $refund)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td class="fw-semibold">
                                        {{ $refund->refund_order_number }}
                                    </td>

                                    <td>
                                        <i class="far fa-calendar-alt text-muted me-1"></i>
                                          {{ formatDate($refund->refund_order_date) }}
                                    </td>

                                    <td>
                                        @if ($refund->payment_method == 'adjustment')
                                            <span class="badge bg-info">
                                                <i class="fas fa-random me-1"></i> Adjustment
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-money-bill-wave me-1"></i> Direct
                                            </span>
                                        @endif
                                    </td>

                                    <td>{{ $refund->reference_number ?? '-' }}</td>
                                    <td>{{ $refund->remarks ?? '-' }}</td>

                                    <td class="text-end fw-bold text-success">
                                        ₹ {{ number_format($refund->amount, 2) }}
                                    </td>

                                    <td class="text-center">
                                        @if ($refund->payment_method == 'adjustment')
                                            <a href="{{ route('invoice-orders.show', encrypt($refund->invoice_order_id)) }}"
                                                target="_blank" title="View Invoice"
                                                class="btn btn-sm btn-primary text-white">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @else
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-check-circle text-success me-1"></i> Refunded
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr class="bg-light fw-bold">
                                <th colspan="6" class="text-end">
                                    Balance Due
                                </th>
                                <th class="text-end text-danger fs-6">
                                    ₹ {{ number_format($creditNote->balance_due, 2) }}
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info mt-4 d-flex align-items-center">
            <i class="fas fa-info-circle me-2"></i>
            No refunds have been made for this Credit Note yet.
        </div>
    @endif



    </div>

@endsection


<!-- @push('scripts')
    <script>
        document.querySelectorAll('.invoice-check').forEach(cb => {
            cb.addEventListener('change', function() {
                const row = this.closest('tr');
                const input = row.querySelector('.apply-amount');

                if (this.checked) {
                    input.readOnly = false;
                    input.focus();
                } else {
                    input.value = '';
                    input.readOnly = true;
                }

                calculateTotal();
            });
        });

        document.querySelectorAll('.apply-amount').forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        function calculateTotal() {
            let total = 0;

            document.querySelectorAll('.apply-amount').forEach(input => {
                if (!input.disabled && input.value) {
                    total += parseFloat(input.value);
                }
            });

            document.querySelector('#totalApplying').innerText =
                '₹ ' + total.toFixed(2);

            document.querySelector('#remainingCredit').innerText =
                '₹ ' + ({{ $creditNote->balance_due }} - total).toFixed(2);
        }
    </script>
@endpush -->


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const creditBalance = {{ $creditNote->balance_due }};

        let lastChangedInput = null;

        document.querySelectorAll('.invoice-check').forEach(cb => {
            cb.addEventListener('change', function() {
                const row = this.closest('tr');
                const input = row.querySelector('.apply-amount');

                if (this.checked) {
                    input.readOnly = false;
                    input.focus();
                } else {
                    input.value = '';
                    input.readOnly = true;
                }

                calculateTotal();
            });
        });

        document.querySelectorAll('.apply-amount').forEach(input => {
            input.addEventListener('input', function() {
                lastChangedInput = this;

                const row = this.closest('tr');
                const maxInvoiceBalance = parseFloat(
                    row.querySelector('.invoice-check').dataset.balance
                );

                let value = parseFloat(this.value) || 0;

                if (value > maxInvoiceBalance) {
                    alert('Apply amount cannot exceed Invoice Balance');
                    this.value = maxInvoiceBalance.toFixed(2);
                }

                calculateTotal();
            });
        });


        function calculateTotal() {
            let total = 0;

            document.querySelectorAll('.apply-amount').forEach(input => {
                if (!input.readOnly && input.value) {
                    total += parseFloat(input.value);
                }
            });

            /* ✅ Credit Note limit */
            if (total > creditBalance && lastChangedInput) {
                alert('Total applying amount cannot exceed Credit Note balance');

                let excess = total - creditBalance;
                let currentVal = parseFloat(lastChangedInput.value) || 0;

                let adjusted = Math.max(0, currentVal - excess);
                lastChangedInput.value = adjusted.toFixed(2);

                total = creditBalance;
            }

            document.querySelector('#totalApplying').innerText =
                '₹ ' + total.toFixed(2);

            document.querySelector('#remainingCredit').innerText =
                '₹ ' + (creditBalance - total).toFixed(2);
        }
    </script>
    <script>
        document.getElementById('applyCreditForm').addEventListener('submit', function(e) {

            let hasChecked = false;
            let isValid = true;

            document.querySelectorAll('.invoice-check').forEach(cb => {

                if (cb.checked) {
                    hasChecked = true;

                    const row = cb.closest('tr');
                    const input = row.querySelector('.apply-amount');
                    const value = parseFloat(input.value) || 0;

                    if (value <= 0) {
                        isValid = false;

                        e.preventDefault(); // ⛔ IMPORTANT

                        Swal.fire({
                            icon: 'warning',
                            title: 'Apply Amount Required',
                            text: 'Please enter apply amount for all selected invoices.',
                            confirmButtonColor: '#3085d6',
                        }).then(() => {
                            input.focus();
                        });
                    }
                }
            });

            if (!hasChecked) {
                e.preventDefault(); // ⛔ IMPORTANT

                Swal.fire({
                    icon: 'info',
                    title: 'No Invoice Selected',
                    text: 'Please select at least one invoice to apply credit.',
                    confirmButtonColor: '#3085d6',
                });
            }

        });
    </script>
    <script type="text/javascript">
        $(document).on('click', '.openRefundModal', function() {
            let creditNoteId = $(this).data('credit-note-id');
            let creditNoteNumber = $(this).data('credit-note-number');
            let customerId = $(this).data('customer-id');
            let customerName = $(this).data('customer-name');
            let amount = $(this).data('amount');
            let balanceDue = $(this).data('balance-due');

            $('#credit_note_id').val(creditNoteId);
            $('#customer_id').val(customerId);

            $('#cnNumber').text(creditNoteNumber);
            $('#creditNoteNumber').text(creditNoteNumber);
            $('#customerName').text(customerName);

            $('#refund_amount').val(balanceDue.toFixed(2));
            $('#balanceAmount').text(parseFloat(balanceDue).toFixed(2));

            $('#refundModal').modal('show');
        });

        $('#refund_amount').on('input', function() {
            let max = parseFloat($('#balanceAmount').text());
            let val = parseFloat($(this).val());

            if (val > max) {
                toastr.error('Refund amount cannot exceed balance');
                $(this).val(max);
            }
        });

        $('#refundForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);

            $.ajax({
                url: "{{ route('refund-orders.store') }}",
                type: "POST",
                data: form.serialize(),
                beforeSend: function() {
                    form.find('button[type=submit]').prop('disabled', true).text('Saving...');
                },
                success: function(res) {

                    if (res.status) {
                        toastr.success(res.message);

                        $('#refundModal').modal('hide');
                        form[0].reset();

                        setTimeout(function() {
                            location.reload();
                        }, 100);
                    }
                },
                error: function(xhr) {

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else {
                        toastr.error('Something went wrong');
                    }
                },
                complete: function() {
                    form.find('button[type=submit]').prop('disabled', false).text('Save');
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.viewCreditNoteBtn', function() {

            let id = $(this).data('id');

            $('#vcn_items').html('');

            $.get(`/credit-notes/${id}/details`, function(res) {

                $('#vcn_number').text(res.credit_note_number);
                $('#vcn_date').text(res.credit_note_date);

                $('#vcn_customer_name').text(res.customer?.name ?? '-');
                $('#vcn_customer_code').text(res.customer?.code ?? '-');
                $('#vcn_customer_email').text(res.customer?.email ?? '-');
                $('#vcn_customer_phone').text(res.customer?.mobile_no ?? '-');

                let addr = res.customer?.get_customer_address;
                let fullAddr = addr ?
                    `${addr.address_line1}, ${addr.cities?.name ?? ''}, ${addr.states?.name ?? ''} - ${addr.pincode ?? ''}` :
                    'NA';

                $('#vcn_customer_address').text(fullAddr);
                  $('#vso_customer_credit_limit').text(
                    res.customer?.credit_limit ?
                    '₹ ' + parseFloat(res.customer.credit_limit).toFixed(2) :
                    'NA'
                );
                $('#vso_customer_gst').text(res.customer?.gst_no ?? 'NA');
                $('#vso_customer_type').text(res.customer?.gst_type ?? 'NA');
                 $('#vso_customer_supply').text(
                            res.customer?.states ?
                            `${res.customer.states.name} (${res.customer.states.iso2})` :
                            'NA'
                        );
                let gross = 0,
                    gst = 0;
                    discount=0;

                res.credit_note_details.forEach((item, i) => {
                    $('#vcn_items').append(`
                <tr>
                    <td>${i + 1}</td>
                    <td>${item.product?.name ?? '-'}</td>
                    <td>${item.quantity}</td>
                    <td>₹ ${item.unit_price}</td>
                    <td>${item.discount_percent}%</td>
                    <td>₹ ${item.discount_amount}</td>
                    <td>${item.gst_percent}%</td>
                    <td>₹ ${item.gst_amount}</td>
                    <td class="fw-bold">₹ ${item.total_amount}</td>
                </tr>
            `);

                    gross += (item.quantity * item.unit_price);
                    gst += parseFloat(item.gst_amount || 0);
                    discount += parseFloat(item.discount_amount || 0);

                });

                $('#vcn_gross').text('₹ ' + gross.toFixed(2));
                $('#vcn_gst').text('₹ ' + gst.toFixed(2));
                $('#vcn_discount').text('₹ ' + discount.toFixed(2));
                $('#vcn_net').text('₹ ' + parseFloat(res.net_amount).toFixed(2));

                // PDF download link inside modal
                $('#cnDownloadBtn').attr(
                    'href',
                    `/credit-notes/pdf/${res.encrypted_id}`
                );

                $('#viewCreditNoteModal').modal('show');
            });
        });
    </script>
@endpush
