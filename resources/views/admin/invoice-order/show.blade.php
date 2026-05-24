@push('styles')
    <style>
        #debitItemsTable th {
            background: #f5f7ff;
            font-weight: 600;
            font-size: 13px;
        }

        #debitItemsTable td {
            vertical-align: middle;
        }

        #debitItemsTable input,
        #debitItemsTable select {
            font-size: 13px;
            padding: 6px 8px;
        }

        .rowTotal {
            font-weight: 700;
            color: #004e92;
        }


        .table,
        .table thead th,
        .table tbody td,
        .table tbody tr {
            background-color: #fff !important;
        }

        .btn-outline-primary.btn-sm:hover,
        .btn-outline-primary.btn-sm:focus {
            color: #fff !important;
        }

        /* Reduce modal body padding */
        #paymentModal .modal-body {
            padding: 16px 20px;
        }

        /* Reduce vertical space between rows */
        #paymentModal .row.mb-20 {
            margin-bottom: 12px !important;
        }

        /* Reduce label spacing */
        #paymentModal .label {
            margin-bottom: 4px !important;
            font-weight: 500;
        }

        /* Fix form-check spacing */
        #paymentModal .form-check {
            margin-bottom: 0;
        }

        /* Inline TDS + Amount Withheld alignment */
        #withheldWrapper {
            margin-top: 0 !important;
        }

        /* Notes textarea compact */
        #paymentModal textarea {
            resize: none;
        }

        /* Modal footer spacing */
        #paymentModal .modal-footer {
            padding-top: 8px;
        }

        #viewDebitNoteModal .modal-content {
            background-color: #ffffff !important;
        }
 
        /* ================= PDF MODAL THEME ================= */

        #viewDebitNoteModal .modal-dialog {
            max-width: 820px !important;
            /* A4 width feel */
        }

        #viewDebitNoteModal .modal-content {
            border-radius: 0;
            background: #fff;
        }

        /* remove bootstrap padding */
        #viewDebitNoteModal .modal-body {
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
            font-size: 20px;
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

        #viewDebitNoteModal .modal-dialog {
            max-width: 820px;
            width: 100%;
        }

        #viewDebitNoteModal .modal-content {
            border-radius: 0;
        }

        .invoice-view-modal {
            max-width: 720px;
        }
    </style>
@endpush

@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Invoice Details</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('invoice-orders.index') }}" class="text-decoration-none">
                            Invoice Orders
                        </a>
                    </li>
                    <li class="breadcrumb-item active">View Invoice</li>
                </ol>
            </nav>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">


            <button class="btn btn-primary viewInvoiceBtn text-white" data-id="{{ $invoice->id }}">
                View
            </button>
            {{-- <button class="btn btn-warning text-white" data-bs-toggle="modal" data-bs-target="#debitNoteModal">
                <i class="ri-file-damage-line me-1"></i> Create Debit Note
            </button> --}}

        </div>


        {{-- MAIN CARD --}}
        <div class="card bg-white p-4 rounded-10 border border-light shadow-sm">
            <div class="card-body">

                {{-- INVOICE TOP --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">
                        <i class="ri-bill-line text-primary me-2"></i>
                        {{ $invoice->code ?? 'INV-' . $invoice->id }}
                    </h4>

                    <div class="d-flex gap-3 align-items-center">
                        <span
                            class="badge 
                        {{ $invoice->payment_status == 'Paid' ? 'bg-success' : 'bg-warning' }}">
                            {{ $invoice->payment_status }}
                        </span>


                    </div>
                </div>

                {{-- INFO SECTIONS --}}
                <div class="row g-4">

                    {{-- CUSTOMER INFO --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="text-primary mb-3">Customer Information</h5>

                            <ul class="list-unstyled mb-0">
                                <li><strong>Customer Code:</strong> {{ $invoice->customer->code }}</li>
                                <li><strong>Customer Name:</strong> {{ $invoice->customer->name }}</li>
                                <li><strong>Contact Number:</strong> {{ $invoice->customer->mobile_no }}</li>
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

                                <li><strong>Email:</strong> {{ $invoice->customer->email }}</li>
                                <li><strong>Credit Limit:</strong>
                                    {{ number_format($invoice->customer->credit_limit, 2) }}</li>
                                <li><strong>Gst Type:</strong>
                                    {{ $invoice->customer->gst_type ?? 'N/A' }}
                                </li>

                                <li><strong>Gst No:</strong>
                                    {{ $invoice->customer->gst_no ?? 'N/A' }}
                                </li>

                                 <li>
                                            <strong>Place of Supply:</strong>
                                            {{ $invoice->customer->states
                                                ? $invoice->customer->states->name . ' (' . $invoice->customer->states->iso2 . ')'
                                                : 'N/A' }}
                                        </li>
                            </ul>
                        </div>
                    </div>

                    {{-- INVOICE INFO --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">

                            {{-- HEADER ROW --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-primary mb-0">Invoice Information</h5>

                                <button class="btn btn-primary text-white" data-bs-toggle="modal"
                                    data-bs-target="#paymentModal">
                                    <i class="ri-wallet-3-line me-1"></i> Pay Invoice
                                </button>
                            </div>

                            {{-- INFO LIST --}}
                            <ul class="list-unstyled mb-0">
                                <li>
                                    <strong>Invoice Date:</strong>
                                     {{ formatDate($invoice->date) }}
                                </li>
                                <li>
                                    <strong>Branch:</strong>
                                    {{ $invoice->branch->branch_name ?? '-' }}
                                </li>
                                <li>
                                    <strong>Sales Person:</strong>
                                    {{ $invoice->salesPerson->full_name ?? '-' }}
                                </li>
                                <li>
                                    <strong>Payment Terms:</strong>
                                    {{ $invoice->paymentTerms?->days }} {{ $invoice->paymentTerms?->name }}
                                </li>
                                <li>
                                    <strong>Due Date:</strong>
                                    {{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }}
                                </li>
                                <li>
                                    <strong>Created By:</strong>
                                    {{ $invoice->createdBy->full_name ?? '-' }}
                                </li>
                                <li>
                                    <strong>Edit Reason:</strong>
                                    {{ !empty($invoice->remark) ? $invoice->remark : 'This invoice has not been edited.' }}
                                </li>
                            </ul>

                        </div>
                    </div>

                </div>

                {{-- ITEMS TABLE --}}
                <div class="card bg-white rounded-10 border border-light shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="text-primary mb-3">
                            <i class="ri-file-list-3-line me-2"></i>
                            Invoice Item Details
                        </h5>

                        <div class="table-responsive ">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Invoice Qty</th>
                                        <th>Unit Price</th>
                                        <th>GST %</th>
                                        <th>GST Amount</th>
                                        <th>Discount %</th>
                                        <th>Discount Amount</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($invoice->invoiceDetails as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                <strong>{{ $item->product->name }}</strong><br>
                                                <small class="text-muted">
                                                    Batch: {{ $item->batch_id ?? '-' }}
                                                </small>
                                            </td>

                                            <td>{{ $item->quantity_delivered }}</td>
                                            <td>₹ {{ number_format($item->unit_price, 2) }}</td>
                                            <td>{{ $item->gst_percent }}%</td>
                                            <td>₹ {{ number_format($item->gst_amount, 2) }}</td>

                                            <td>{{ $item->discount_percent }}%</td>

                                            <td>₹ {{ number_format($item->discount_amount, 2) }}</td>


                                            <td class="fw-bold">
                                                ₹ {{ number_format($item->total_amount, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- AMOUNT SUMMARY --}}
                @php
                    $grossWithoutGst = $invoice->invoiceDetails->sum(function ($item) {
                        return $item->unit_price * $item->quantity_delivered;
                    });

                    $paymentReceived = $invoice->payments->sum(function ($p) {
                        return $p->amount_paid + $p->amount_withheld;
                    });

                    $paymentDue = $invoice->net_amount - $paymentReceived;

                @endphp


                <div class="row mt-4 g-3 bg-white">

                    <h4 class="text-primary mb-3">Amount Summary</h4>

                    <table class="table table-bordered align-middle bg-white">
                        <tbody>
                            <tr>
                                <th class="bg-white" width="50%">Gross Amount</th>
                                <td class="bg-white">
                                    <strong>₹ {{ number_format($grossWithoutGst, 2) }}</strong>
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-white">GST Amount</th>
                                <td class="bg-white">
                                    <strong>
                                        ₹ {{ number_format($invoice->invoiceDetails->sum('gst_amount'), 2) }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-white">Product Discount</th>
                                <td class="bg-white">
                                    <strong>
                                        ₹ {{ number_format($invoice->invoiceDetails->sum('discount_amount'), 2) }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-white">Bill Discount</th>
                                <td class="bg-white text-success">
                                    <strong>
                                        ₹ {{ number_format($invoice->overall_bill_discount_amount, 2) }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-white">Net Payable</th>
                                <td class="bg-white text-success">
                                    <strong>
                                        ₹ {{ number_format($invoice->net_amount, 2) }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-white">Payment Received</th>
                                <td class="bg-white text-primary">
                                    <strong>
                                        ₹ {{ number_format($paymentReceived, 2) }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-white">Amount Adjusted With Credit</th>
                                <td class="bg-white text-info">
                                    <strong>
                                        ₹ {{ number_format($invoice->creditNotes->sum('used_amount'), 2) }}
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <th class="bg-white">Payment Due</th>
                                <td class="bg-white {{ $paymentDue > 0 ? 'text-danger' : 'text-success' }}">
                                    <strong>
                                        ₹ {{ number_format($paymentDue, 2) }}
                                    </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>



                <div class="modal fade" id="viewInvoiceModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered invoice-view-modal">

                        <div class="modal-content">

                            <div class="modal-header d-flex align-items-center justify-content-between">

                                <h5 class="modal-title mb-0">Invoice</h5>

                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('invoice-orders.pdf', encrypt($invoice->id)) }}" target="_blank"
                                        title="Download Invoice PDF" class="text-danger fs-20 pdf-icon">
                                        <i class="fas fa-file-pdf"></i>
                                        <i class="fas fa-download ms-1 fs-14"></i>
                                    </a>

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                            </div>


                            <div class="modal-body">
                                <div class="pdf-wrapper">

                                    {{-- HEADER --}}
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
                                                <div class="pdf-title">Invoice</div>
                                                <div class="pdf-sub">
                                                    Date: <span id="vinv_date">-</span><br>
                                                    Invoice No: <span id="vinv_number">-</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- CUSTOMER --}}
                                    <div class="section-title">Customer Details</div>
                                    <table class="pdf-table">
                                        <tr>
                                            <th>Customer Code</th>
                                            <td id="vinv_customer_code">-</td>
                                            <th>Customer Name</th>
                                            <td id="vinv_customer_name">-</td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td id="vinv_customer_email">-</td>
                                            <th>Phone</th>
                                            <td id="vinv_customer_phone">-</td>
                                        </tr>
                                        <tr>
                                            <th>Address</th>
                                            <td colspan="3" id="vinv_customer_address">-</td>
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

                                    {{-- SALES INFO --}}
                                    <div class="section-title">Invoice Information</div>
                                    <table class="pdf-table">
                                        <tr>
                                            <th>Branch</th>
                                            <td id="vinv_branch">-</td>
                                            <th>Sales Person</th>
                                            <td id="vinv_sales_person">-</td>
                                        </tr>
                                        <tr>
                                            <th>Payment Terms</th>
                                            <td colspan="3" id="vinv_payment_terms">-</td>
                                        </tr>
                                    </table>

                                    {{-- ITEMS --}}
                                    <div class="section-title">Invoice Items</div>
                                    <table class="pdf-table text-center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Product</th>
                                                <th>Batch</th>
                                                <th>Qty</th>
                                                <th>Rate</th>
                                                <th>GST</th>
                                                <th>Discount</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="vinv_items"></tbody>
                                    </table>

                                    {{-- SUMMARY --}}
                                    <div class="section-title">Summary</div>
                                    <table class="pdf-table">
                                        <tr>
                                            <th>Total Amount</th>
                                            <td class="amount" id="vinv_total">₹ 0.00</td>
                                        </tr>
                                        <tr>
                                            <th>GST Amount</th>
                                            <td class="amount" id="vinv_gst">₹ 0.00</td>
                                        </tr>
                                        <tr>
                                            <th>Discount</th>
                                            <td class="amount" id="vinv_discount">₹ 0.00</td>
                                        </tr>
                                        <tr class="total-row">
                                            <th>Net Payable</th>
                                            <td class="amount" id="vinv_net">₹ 0.00</td>
                                        </tr>
                                    </table>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- PAYMENTS TABLE --}}
                @if ($invoice->payments->count() > 0)
                    <div class="card bg-white rounded-10 border border-light shadow-sm mt-4">
                        <div class="card-body">

                            <h5 class="text-primary mb-3">
                                <i class="ri-wallet-3-line me-2"></i>
                                Payment History
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Payment Code</th>
                                            <th>Date</th>
                                            <th>Method</th>
                                            <th>Amount Paid</th>
                                            <th>TDS Amount</th>
                                            <th>Status</th>
                                            <th>Receipt</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($invoice->payments as $payment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <td>{{ $payment->code }}</td>

                                               <td>{{ formatDate($payment->payment_date) }}</td>

                                                <td>{{ $payment->payment_method }}</td>

                                                <td class="fw-bold">
                                                    ₹ {{ number_format($payment->amount_paid, 2) }}
                                                </td>

                                                <td>
                                                    ₹ {{ number_format($payment->amount_withheld, 2) }}
                                                </td>

                                                <td>
                                                    <span
                                                        class="badge
                                                    {{ $payment->amount_paid > 0 ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $invoice->payment_status }}
                                                    </span>
                                                </td>

                                                <td>
                                                    @if ($payment->upload_receipt)
                                                        <a href="{{ asset('storage/' . $payment->upload_receipt) }}"
                                                            target="_blank" class="btn btn-sm btn-outline-primary ">
                                                            View
                                                        </a>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm editPaymentBtn" title="Edit Payment"
                                                        data-id="{{ $payment->id }}"
                                                        data-amount="{{ $payment->amount_paid }}"
                                                        data-bank="{{ $payment->bank_charges }}"
                                                        data-date="{{ $payment->payment_date }}"
                                                        data-received="{{ $payment->payment_received_on }}"
                                                        data-withheld="{{ $payment->amount_withheld }}"
                                                        data-method="{{ $payment->payment_method }}"
                                                        data-reference="{{ $payment->reference_number }}"
                                                        data-notes="{{ $payment->notes }}"
                                                        data-gross="{{ $payment->amount_paid + $payment->amount_withheld }}"
                                                        data-tax="{{ $payment->tax_deduction }}">
                                                        <i class="ri-edit-2-line"></i>
                                                    </button>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($creditnotes->count() > 0)
                    <div class="card bg-white rounded-10 border border-light shadow-sm mt-4">
                        <div class="card-body">

                            <h5 class="text-primary mb-3">
                                <i class="ri-file-3-line me-2"></i>
                                Credit Notes
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Credit Note No</th>
                                            <th>Date</th>
                                            <th>Reason</th>
                                            <th>Total Amount</th>
                                            <th>Created By</th>
                                            <th>Status</th>
                                            <th width="140">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($creditnotes as $cn)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>

                                                <td class="text-center">
                                                    <strong>{{ $cn->credit_note_number }}</strong>
                                                </td>

                                                <td class="text-center">
                                                  {{ formatDate($cn->credit_note_date) }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $cn->reason_type ?? '-' }}
                                                </td>

                                                <td class="fw-bold text-end text-center">
                                                    ₹ {{ number_format($cn->net_amount, 2) }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $cn->createdBy->full_name ?? '-' }}
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge bg-success">
                                                        {{ ucfirst($cn->status ?? 'Final') }}
                                                    </span>
                                                </td>
 
                                                <td class="text-center d-flex justify-content-center gap-2">
                                                   
                                                    <a href="{{ route('credit-notes.show',encrypt($cn->id)) }}"
                                                        target="_blank" class="btn-sm" title="View Credit Note">
                                                        <i class="fas fa-eye"></i>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                @endif
                
                {{-- @if ($debitNotes->count() > 0)
                    <div class="card bg-white rounded-10 border border-light shadow-sm mt-4">
                        <div class="card-body">

                            <h5 class="text-primary mb-3">
                                <i class="ri-file-damage-line me-2"></i>
                                Debit Notes
                            </h5>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>#</th>
                                            <th>Debit Note No</th>
                                            <th>Date</th>
                                            <th>Reason</th>
                                            <th>Total Amount</th>
                                            <th>Created By</th>
                                            <th>Status</th>
                                            <th width="140">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($debitNotes as $dn)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>

                                                <td class="text-center">
                                                    <strong>{{ $dn->debit_note_number }}</strong>
                                                </td>

                                                <td class="text-center">
                                                    {{ \Carbon\Carbon::parse($dn->debit_note_date)->format('d M Y') }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $dn->reason_type ?? '-' }}
                                                </td>

                                                <td class="fw-bold text-end text-center">
                                                    ₹ {{ number_format($dn->net_amount, 2) }}
                                                </td>

                                                <td class="text-center">
                                                    {{ $dn->createdBy->full_name ?? '-' }}
                                                </td>

                                                <td class="text-center">
                                                    <span class="badge bg-success">
                                                        {{ ucfirst($dn->status ?? 'Final') }}
                                                    </span>
                                                </td>

                                                <td class="text-center d-flex justify-content-center gap-2">
                                                    <button style="border:none;" class="btn-sm editDebitNoteBtn"
                                                        title="Edit Payment" data-id="{{ $dn->id }}">
                                                        <i class="ri-edit-2-line"></i>
                                                    </button>

                                                    <button style="border:none;" class=" btn-sm viewDebitNoteBtn"
                                                        data-id="{{ $dn->id }}">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                    <a href="{{ route('debit-notes.pdf', $dn->id) }}" target="_blank"
                                                        class="btn-sm" title="Download PDF">
                                                        <i class="fas fa-file-pdf"></i>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>
                @endif --}}

            </div>
        </div>
        <div class="modal fade" id="paymentModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered rounded-10">

                <form id="paymentForm" action="{{ route('payment.store') }}" method="POST"
                    enctype="multipart/form-data" class="modal-content bg-white">
                    @csrf

                    <input type="hidden" name="invoice_order_id" value="{{ $invoice->id }}">
                    <input type="hidden" name="customer_id" value="{{ $invoice->customer_id }}">
                    <input type="hidden" name="payment_id" id="paymentId">
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-header border-border-color-40 p-20">
                        <h1 class="modal-title fs-18 fw-medium mb-0">
                            Payment for {{ $invoice->code }}
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body p-20">
                        <div class="row mb-20">
                            <div class="col-md-6">
                                <label class="label">Customer</label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $invoice->customer->name }}">
                            </div>

                            <div class="col-md-6">
                                <label class="label">Payment Code</label>
                                <input type="text" name="code" class="form-control"
                                    value="PAY-{{ time() }}">
                            </div>
                        </div>

                        @php
                            $paidGross = $invoice->payments->sum(fn($p) => $p->amount_paid + $p->amount_withheld);
                            $remaining = $invoice->net_amount - $paidGross;
                        @endphp

                        <p class="text-danger fw-semibold mb-2">
                            Remaining Amount : ₹ {{ number_format($remaining, 2) }}
                        </p>

                        <input type="hidden" id="remainingAmount" value="{{ $remaining }}">

                        <div class="row mb-20">
                            <div class="col-md-4">
                                <label class="label">Amount Paid</label>
                                <!-- <input type="number" step="0.01" name="amount_paid" id="amountPaid"
                                    class="form-control" required onkeydown="return event.key !== '-'"
                                    oninput="this.value = this.value < 0 ? 0 : this.value"> -->
                                <!-- <input type="number"
                                    step="0.01"
                                    min="0"
                                    name="amount_paid"
                                    id="amountPaid"
                                    class="form-control"
                                    onkeydown="return event.key !== '-'"
                                    oninput="this.value = this.value < 0 ? '' : this.value"
                                    onpaste="return !(/-/.test(event.clipboardData.getData('text')))" > -->
                            <input type="text"
                                name="amount_paid"
                                id="amountPaid"
                                class="form-control"
                                inputmode="decimal"
                                placeholder="0.00"
                                onpaste="return !(/-/.test(event.clipboardData.getData('text')))">


                                <small class="text-danger d-none" id="amountError">
                                    Amount exceeds remaining
                                </small>
                            </div>

                            <div class="col-md-4">
                                <label class="label">Payment Date</label>
                                <input type="date" name="payment_date" value="{{ date('Y-m-d') }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-4">
                                <label class="label">Received On</label>
                                <input type="date" name="payment_received_on" value="{{ date('Y-m-d') }}"
                                    class="form-control">
                            </div>
                        </div>

                        <div class="row mb-20 ">
                            <div class="col-md-3">
                                <label class="label">Tax Deduction</label>

                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tax_deduction"
                                            value="no" checked>
                                        <label class="form-check-label">No</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tax_deduction"
                                            value="yes">
                                        <label class="form-check-label">Yes (TDS)</label>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3 d-none" id="withheldWrapper">
                                <label class="label">Amount Withheld</label>
                                <!-- <input type="number" step="0.01" name="amount_withheld" id="withheldInput" required onkeydown="return event.key !== '-'"
                                    oninput="this.value = this.value < 0 ? 0 : this.value"
                                    class="form-control"> -->
                             <!-- <input type="number"
                                    step="0.01"
                                    min="0"
                                    name="amount_withheld"
                                    id="withheldInput"
                                    class="form-control"
                                    onkeydown="return event.key !== '-'"
                                    oninput="this.value = this.value < 0 ? '' : this.value"
                                    onpaste="return !(/-/.test(event.clipboardData.getData('text')))"
                                    > -->

                            <input type="text"
                            name="amount_withheld"
                            id="withheldInput"
                            class="form-control"
                            inputmode="decimal"
                            placeholder="0.00"
                          
                            >



                            </div>

                            <div class="col-md-3">
                                <label class="label">Payment Method</label>
                                <select name="payment_method" class="form-select form-control">
                                    <option>Cash</option>
                                    <option>UPI</option>
                                    <option>Bank Transfer</option>
                                    <option>Cheque</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="label">Reference No</label>
                                <input type="text" name="reference_number" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <label class="label">Notes</label>
                                <textarea name="notes" rows="2" class="form-control"></textarea>
                            </div>

                            <div class="col-md-3">
                                <label class="label">Upload Receipt</label>
                                <input type="file" name="upload_receipt" class="form-control">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer border-0 p-20 pt-0 justify-content-start">
                        <button type="button" class="btn btn-danger fw-normal text-white me-2" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" id="savePaymentBtn" class="btn btn-primary fw-normal text-white">
                            Save Payment
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <div class="modal fade" id="batchModal">

            <div class="modal-dialog modal-lg modal-dialog-centered">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select Batch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Batch No</th>
                                    <th>Expiry</th>
                                    <th>Stock</th>
                                    <th>MRP</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="batchList">
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>

        <div class="modal fade" id="debitNoteModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-centered">

                <form id="debitNoteForm" method="POST" action="{{ route('debit-notes.store') }}"
                    class="modal-content bg-white">
                    @csrf

                    {{-- Hidden --}}
                    <input type="hidden" name="invoice_order_id" value="{{ $invoice->id }}">
                    <input type="hidden" name="customer_id" value="{{ $invoice->customer_id }}">
                    <input type="hidden" name="branch_id" value="{{ $invoice->branch_id }}">
                    {{-- <input type="hidden" name="sales_person_id" value="{{ $invoice->sales_person_id }}"> --}}

                    <div class="modal-header">
                        <h5 class="modal-title">Create Debit Note</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        {{-- Customer --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="label">Customer</label>
                                <input type="text" class="form-control" readonly
                                    value="{{ $invoice->customer->name }}">

                                <small>GST Treatment: {{ $invoice->customer->gst_type ?? '-' }}
                                    {{ $invoice->customer->gst_no ?? '-' }}</small>
                            </div>

                            <div class="col-md-4">
                                <label class="label">Debit Note Date</label>
                                <input type="date" name="debit_note_date" value="{{ date('Y-m-d') }}"
                                    class="form-control">
                            </div>


                            <div class="col-md-4">
                                <label class="label">Reference No</label>
                                <input type="text" name="reference_number" class="form-control">
                            </div>

                            <div class="col-md-4 mt-3">
                                <label class="label">Debit Note Reason <span class="text-danger">*</span></label>
                                <select name="reason_type" class="form-control">
                                    <option value="">Select Reason</option>
                                    <option value="Correction In Invoice">Correction In Invoice</option>
                                    <option value="Change In Pos">Change In Pos</option>
                                    <option value="finalization_of_provisional_assessment">Finalization Of Provisional
                                        Assessment</option>
                                    <option value="Other">Other</option>
                                    <span class="text-danger" id="reasonTypeError"></span>
                                </select>
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="label">Payment Terms</label>

                                <select name="payment_terms_id" class="form-control">
                                    @foreach ($paymentTerms as $term)
                                        <option value="{{ $term->id }}"
                                            {{ $invoice->payment_terms_id == $term->id ? 'selected' : '' }}>
                                            {{ $term->days }} {{ $term->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="col-md-4 mt-3">
                                <label class="label">Sales Person</label>

                                <select name="sales_person_id" class="form-control">
                                    <option value="">Select Sales Person</option>

                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}"
                                            {{ $invoice->sales_person_id == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->full_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                        </div>

                        {{-- ITEMS --}}
                        <table class="table table-bordered table-sm align-middle text-center" id="debitItemsTable">

                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Batch</th>
                                    <th>Qty</th>
                                    <th>Rate</th>
                                    <th>Disc %</th>
                                    <th>GST %</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>
                                        <select name="items[0][product_id]" class="form-control productSelect"
                                            data-row="0">
                                            <option value="">Select</option>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input name="items[0][batch_id]" readonly class="form-control"></td>
                                    <td>
                                       <input type="number"
                                        name="items[0][quantity]"
                                        value="1"
                                        class="form-control calc text-center qty"
                                        inputmode="numeric"
                                        autocomplete="off">

                                    </td>

                                    <td><input type="number" step="0.01" name="items[0][unit_price]"
                                            class="form-control calc"></td>
                                    <td><input type="number" step="0.01" name="items[0][discount_percent]"
                                            class="form-control calc" readonly></td>
                                    <td><input type="number" step="0.01" name="items[0][gst_percent]"
                                            class="form-control calc" readonly></td>
                                    <td class="rowTotal">0.00</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm removeRow">×</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-primary text-white" id="addRow">
                            + Add Item
                        </button>

                        {{-- SUMMARY --}}
                        <div class="row mt-3">
                            <div class="col-md-4 offset-md-8">
                                <table class="table table-bordered">
                                    <tr class="table-light">
                                        <th>Total Amount</th>
                                        <td class="fw-bold text-end" id="grandTotal">₹ 0.00</td>
                                    </tr>
                                </table>

                            </div>
                        </div>

                    </div>

                    <div class="modal-footer justify-content-start">
                        <button class="btn btn-primary text-white">Save Debit Note</button>
                        <button class="btn btn-danger text-white" data-bs-dismiss="modal">Cancel</button>

                    </div>

                </form>
            </div>
        </div>

        <div class="modal fade" id="viewDebitNoteModal" tabindex="-1">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Debit Note</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="pdf-wrapper">

                            {{-- HEADER (TABLE BASED LIKE PDF) --}}
                            <table width="100%" style="margin-bottom:18px;">
                                <tr>
                                    <!-- LEFT -->
                                    <td width="60%" style="border:none; vertical-align:top;">
                                        <img src="{{ asset(setting('logo')) }}" style="max-height:60px;"><br>

                                        <strong>{{ setting('company_name') }}</strong><br>
                                        {{ setting('company_address') }}<br>
                                        Email: {{ setting('company_email') }}<br>
                                        Phone: {{ setting('company_phone') }}
                                    </td>

                                    <!-- RIGHT -->
                                    <td width="40%"
                                        style="border:none; text-align:right; vertical-align:top; padding-top:45px;">
                                        <div class="pdf-title">Debit Note</div>
                                        <div class="pdf-sub">
                                            Date: <span id="vdn_date">-</span><br>
                                            Debit Note No: <span id="vdn_number">-</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>


                            {{-- CUSTOMER DETAILS --}}
                            <div class="section-title">Customer Details</div>

                            <table class="pdf-table">
                                <tr>
                                    <th width="18%">Customer Code</th>
                                    <td width="32%" id="vdn_customer_code">-</td>
                                    <th width="18%">Customer Name</th>
                                    <td width="32%" id="vdn_customer_name">-</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td id="vdn_customer_email">-</td>
                                    <th>Phone</th>
                                    <td id="vdn_customer_phone">-</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td colspan="3" id="vdn_customer_address">-</td>
                                </tr>
                                 {{-- <tr>
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
                                        </tr> --}}
                            </table>

                            {{-- SALES INFO --}}
                            <div class="section-title">Sales Information</div>

                            <table class="pdf-table">
                                <tr>
                                    <th width="18%">Sales Person</th>
                                    <td width="32%" id="vdn_sales_person">-</td>
                                    <th width="18%">Branch</th>
                                    <td width="32%" id="vdn_branch">-</td>
                                </tr>
                                {{-- <tr>
                                    <th>Payment Terms</th>
                                    <td colspan="3" id="vdn_payment_terms">-</td>
                                </tr> --}}
                            </table>

                            {{-- ITEMS --}}
                            <div class="section-title">Debit Note Items</div>

                            <table class="pdf-table text-center">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>Batch</th>
                                        <th>Qty</th>
                                        <th>Rate</th>
                                        <th>Disc %</th>
                                        <th>Disc Amt</th>
                                        <th>GST %</th>
                                        <th>GST Amt</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody id="vdn_items"></tbody>
                            </table>

                            <div class="section-title">Summary</div>

                            <table class="pdf-table">
                                <tr>
                                    <th>Gross Amount</th>
                                    <td class="amount" id="vdn_total">₹ 0.00</td>
                                </tr>
                                <tr>
                                    <th>GST Amount</th>
                                    <td class="amount" id="vdn_gst">₹ 0.00</td>
                                </tr>
                                <tr>
                                    <th>Discount Amount</th>
                                    <td class="amount" id="vdn_discount">₹ 0.00</td>
                                </tr>
                                <tr class="total-row">
                                    <th>Net Amount</th>
                                    <td class="amount" id="vdn_net">₹ 0.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {

            const withheldWrapper = $('#withheldWrapper');
            const withheldInput = $('#withheldInput');

            function toggleWithheld() {
                const tax = $('input[name="tax_deduction"]:checked').val();

                if (tax === 'yes') {
                    withheldWrapper.removeClass('d-none');
                } else {
                    withheldWrapper.addClass('d-none');
                    withheldInput.val(0);
                }
            }

            // Radio change
            $('input[name="tax_deduction"]').on('change', function() {
                toggleWithheld();
            });

            // Modal open (Add payment)
            $('[data-bs-target="#paymentModal"]').on('click', function() {
                $('input[name="tax_deduction"][value="no"]').prop('checked', true);
                toggleWithheld();
            });

            // Edit payment
            $('.editPaymentBtn').on('click', function() {

                const tax = $(this).data('tax'); // yes / no
                const withheld = $(this).data('withheld') || 0;

                $('input[name="tax_deduction"][value="' + tax + '"]').prop('checked', true);
                withheldInput.val(withheld);

                toggleWithheld();
                $('#paymentModal').modal('show');
            });

        });
    </script>

    <script>
        function round2(val) {
            return Math.round((val + Number.EPSILON) * 100) / 100;
        }

        $(document).ready(function() {
            let remainingAmount = round2(parseFloat($('#remainingAmount').val()) || 0);
            let oldGross = 0;
            const amountInput = $('#amountPaid');
            const withheldInput = $('#withheldInput');

            const saveBtn = $('#savePaymentBtn');
            const errorText = $('#amountError');
            saveBtn.prop('disabled', true);

            function validate() {
                let paidRaw = amountInput.val();
                let withheldRaw = withheldInput.val();

                let paid = paidRaw ? round2(parseFloat(paidRaw)) : 0;
                let withheld = withheldRaw ? round2(parseFloat(withheldRaw)) : 0;


                // let paid = round2(parseFloat(amountInput.val()) || 0);
                // let withheld = round2(parseFloat(withheldInput.val()) || 0);
                let gross = round2(paid + withheld);
                let maxAllowed = round2(remainingAmount + oldGross);
                let taxYes = $('input[name="tax_deduction"]:checked').val() === 'yes';

                // if (paid < 0 || withheld < 0) {
                //         errorText.removeClass('d-none')
                //             .text('Negative amount is not allowed');
                //         saveBtn.prop('disabled', true);
                //         return;
                //     }

                if (paid < 0.01) {
                        errorText.removeClass('d-none')
                            .text('Amount Paid is mandatory');
                        saveBtn.prop('disabled', true);
                        return;
                    }


                if (taxYes && withheld > 0 && paid <= 0) {
                    errorText.removeClass('d-none')
                        .text('Amount Paid is required to apply TDS');
                    saveBtn.prop('disabled', true);
                    return;
                }

                if (gross > maxAllowed + 0.01) {
                    errorText.removeClass('d-none')
                        .text('Total (Amount Paid + TDS) cannot exceed invoice amount');
                    saveBtn.prop('disabled', true);

                    return;
                }
                errorText.addClass('d-none');
                saveBtn.prop('disabled', false);
            }
            amountInput.on('input', validate);
            withheldInput.on('input', validate);

            $('input[name="tax_deduction"]').on('change', function() {
                if ($(this).val() === 'yes') {
                    withheldInput.show().val('');
                } else {
                    withheldInput.hide().val(0);
                }
                validate();
            });

        $('#amountPaid, #withheldInput').on('input', function () {
            let val = this.value;

            val = val.replace(/-/g, '');

            val = val.replace(/[^0-9.]/g, '');

            const parts = val.split('.');
            if (parts.length > 2) {
                val = parts[0] + '.' + parts.slice(1).join('');
            }

            this.value = val;

            if (val === '' || val === '.') {
                $('#amountError').addClass('d-none');
                $('#savePaymentBtn').prop('disabled', true);
                return;
            }

            validate(); 
        });


            $('[data-bs-target="#paymentModal"]').on('click', function() {

                oldGross = 0;
                $('#paymentForm').attr('action', "{{ route('payment.store') }}");
                $('#formMethod').val('POST');

                $('#paymentForm')[0].reset();
                withheldInput.hide().val(0);

                saveBtn.prop('disabled', true);
                errorText.addClass('d-none');
            });

            $('.editPaymentBtn').on('click', function() {

                let paymentId = $(this).data('id');
                let paid = parseFloat($(this).data('amount')) || 0;
                let withheld = parseFloat($(this).data('withheld')) || 0;
                let gross = round2(paid + withheld);

                oldGross = gross;

                let updateUrl = "{{ url('payment') }}/" + paymentId;
                $('#paymentForm').attr('action', updateUrl);
                $('#formMethod').val('PUT');

                amountInput.val(paid);
                withheldInput.val(withheld);

                let tax = $(this).data('tax');
                $('input[name="tax_deduction"][value="' + tax + '"]').prop('checked', true);

                if (tax === 'yes') {
                    withheldInput.show();
                } else {
                    withheldInput.hide().val(0);
                }

                $('input[name="bank_charges"]').val($(this).data('bank') || 0);
                $('input[name="payment_date"]').val($(this).data('date'));
                $('input[name="payment_received_on"]').val($(this).data('received'));
                $('textarea[name="notes"]').val($(this).data('notes'));
                $('input[name="reference_number"]').val($(this).data('reference'));
                $('select[name="payment_method"]').val($(this).data('method'));

                errorText.addClass('d-none');
                validate();

                $('#paymentModal').modal('show');
            });
        });
    </script>

    <script>
        let index = 1;


        $('#addRow').on('click', function() {
            let row = $('#debitItemsTable tbody tr:first').clone();

            row.find('input,select').each(function() {
                let name = $(this).attr('name').replace(/\d+/, index);
                $(this).attr('name', name);

                if ($(this).attr('name').includes('[quantity]')) {
                    $(this).val(1); 
                } else {
                    $(this).val('');
                }
            });

            row.find('.productSelect').attr('data-row', index);
            row.find('.rowTotal').text('0.00');

            $('#debitItemsTable tbody').append(row);
            index++;

            calcTotal();
        });

        $(document).on('input', '.qty', function () {

            let val = $(this).val();

            if (val === '') return;

            if (val.includes('.')) {
                val = val.split('.')[0];
            }

            val = parseInt(val);

            if (isNaN(val) || val <= 0) {
                $(this).val(1);
                calcTotal();
                return;
            }

            $(this).val(val);
            calcTotal();
        });

        $(document).on('keydown', '.qty', function (e) {

            if (
                ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight'].includes(e.key)
            ) {
                return;
            }

            if (['.', '-', '+', 'e', 'E'].includes(e.key)) {
                e.preventDefault();
            }

            if (isNaN(e.key)) {
                e.preventDefault();
            }
        });


        function toggleRemoveBtn() {
            let count = $('#debitItemsTable tbody tr').length;
            $('.removeRow').prop('disabled', count === 1);
        }

        $(document).on('click', '.removeRow', function() {
            if ($('#debitItemsTable tbody tr').length > 1) {
                $(this).closest('tr').remove();
                calcTotal();
                // toggleRemoveBtn();
            }
        });


        $(document).on('input', '.calc', function() {
            calcTotal();
        });

        function calcTotal() {
            let grand = 0;

            $('#debitItemsTable tbody tr').each(function() {

                let qty = parseFloat($(this).find('[name*="quantity"]').val()) || 0;
                let rate = parseFloat($(this).find('[name*="unit_price"]').val()) || 0;
                let disc = parseFloat($(this).find('[name*="discount_percent"]').val()) || 0;
                let gst = parseFloat($(this).find('[name*="gst_percent"]').val()) || 0;

                let amount = qty * rate;
                let discAmt = amount * (disc / 100);
                let gstAmt = (amount - discAmt) * (gst / 100);
                let total = amount - discAmt + gstAmt;

                $(this).find('.rowTotal').text(total.toFixed(2));
                grand += total;
            });

            $('#grandTotal').text('₹ ' + grand.toFixed(2));
        }
    </script>

    <script>
        $('#debitNoteForm').on('submit', function(e) {

            let valid = true;
            let errorMsg = '';

            let reason = $('select[name="reason_type"]').val();
            if (!reason) {
                errorMsg = 'Please select Debit Note Reason';
                valid = false;
            }

            let rows = $('#debitItemsTable tbody tr');

            if (rows.length === 0) {
                errorMsg = 'At least one item is required';
                valid = false;
            }

            rows.each(function() {

                let product = $(this).find('[name*="product_id"]').val();
                let qty = parseFloat($(this).find('[name*="quantity"]').val()) || 0;
                let rate = parseFloat($(this).find('[name*="unit_price"]').val()) || 0;

                if (!product) {
                    errorMsg = 'Please select product in all rows';
                    valid = false;
                    return false;
                }

                if (qty <= 0) {
                    errorMsg = 'Quantity must be greater than 0';
                    valid = false;
                    return false;
                }

                if (rate <= 0) {
                    errorMsg = 'Unit price must be greater than 0';
                    valid = false;
                    return false;
                }
            });

            if (!valid) {
                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Validation Error',
                    text: errorMsg
                });
            }
        });
    </script>

    <script>
        let currentRow = null;

        $(document).on('change', '.productSelect', function() {

            const productId = $(this).val();
            if (!productId) return;

            currentRow = $(this).closest('tr');

            Swal.fire({
                title: 'Loading Batches...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.get(`/product/${productId}/batches`, function(batches) {

                if (!batches.length) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Batch Found',
                        text: 'This product has no available batches.'
                    });
                    return;
                }

                let table = `
                <table class="table table-bordered table-sm text-start">
                    <thead>
                        <tr>
                            <th>Batch No</th>
                            <th>Mfg</th>
                            <th>Expiry</th>
                            <th>Stock</th>
                            <th>MRP</th>
                            <th>GST %</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
            `;

                batches.forEach(b => {
                    table += `
                    <tr>
                        <td>${b.batch_number}</td>
                        <td>${b.manufacturing_date ?? '-'}</td>
                        <td>${b.expiry_date ?? '-'}</td>
                        <td>${b.available_quantity}</td>
                        <td>₹ ${b.unit_cost}</td>
                        <td>${b.gst_percent}</td>
                        <td>
                            <button class="btn btn-sm btn-primary text-white selectBatchSwal"
                                data-batch="${b.batch_number}"
                                data-price="${b.unit_cost}"
                                data-gst="${b.gst_percent}"
                                >
                                Select
                            </button>
                        </td>
                    </tr>
                `;
                });

                table += `</tbody></table>`;

                Swal.fire({
                    title: 'Select Batch',
                    html: table,
                    width: '800px',
                    showConfirmButton: false,
                    showCloseButton: true
                });
            });
        });

        function fetchCustomerProductDiscount(customerId, productId, rowIndex) {
            if (!customerId || !productId) return;

            $.get('/customer-product-discount', {

                customer_id: customerId,
                product_id: productId
            }, function(res) {
                let discount = parseFloat(res.discount) || 0;
                $(`input[name="items[${rowIndex}][discount_percent]"]`).val(discount);
                calcTotal();
            });
        }

        $(document).on('change', '.productSelect', function() {

            const row = $(this).closest('tr');
            const rowIndex = $(this).data('row');
            const productId = $(this).val();
            const customerId = {{ $invoice->customer_id }};

            if (!productId) return;

            fetchCustomerProductDiscount(customerId, productId, rowIndex);

            currentRow = row;
            // openBatchModal(productId);
        });

        $(document).on('click', '.selectBatchSwal', function() {

            const batchNo = $(this).data('batch');
            const price = $(this).data('price');
            const gst = $(this).data('gst');

            currentRow.find('input[name*="[batch_id]"]').val(batchNo);
            currentRow.find('input[name*="[unit_price]"]').val(price);
            currentRow.find('input[name*="[gst_percent]"]').val(gst);

            Swal.close();

            calcTotal();
        });
    </script>
    <script>
        $(document).on('click', '.viewDebitNoteBtn', function() {

            let id = $(this).data('id');

            $('#vdn_items').html('');

            $.get(`/debit-notes/${id}/details`, function(res) {

                $('#vdn_number').text(res.debit_note_number);
                $('#vdn_date').text(res.debit_note_date);
                $('#vdn_reason').text(res.reason_type ?? 'N/A');

                let totalGst = 0;
                let totaldistAmt = 0;
                let totalBaseAmount = 0;

                if (!res.debit_note_details || res.debit_note_details.length === 0) {
                    $('#vdn_items').html(`
                    <tr>
                        <td colspan="8" class="text-center text-muted">
                            No items found
                        </td>
                    </tr>
                `);
                } else {
                    res.debit_note_details.forEach((item, index) => {

                        let row = `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.product?.name ?? '-'}</td>
                            <td>${item.batch_id ?? '-'}</td>
                            <td>${item.quantity}</td>
                            <td>₹ ${parseFloat(item.unit_price).toFixed(2)}</td>
                            <td>${item.discount_percent ?? 0}%</td>
                              <td>${item.discount_amount ?? 0}</td>
                            <td>${item.gst_percent ?? 0}%</td>
                             <td>${item.gst_amount ?? 0}</td>
                            <td class="fw-bold">₹ ${parseFloat(item.total_amount).toFixed(2)}</td>
                        </tr>
                    `;

                        $('#vdn_items').append(row);
                        let qty = parseFloat(item.quantity || 0);
                        let price = parseFloat(item.unit_price || 0);


                        totalBaseAmount += (qty * price);
                        totalGst += parseFloat(item.gst_amount || 0);
                        totaldistAmt += parseFloat(item.discount_amount || 0);
                    });
                }
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

                $('#vdn_total').text('₹ ' + totalBaseAmount.toFixed(2));
                $('#vdn_gst').text('₹ ' + totalGst.toFixed(2));
                $('#vdn_discount').text('₹ ' + totaldistAmt.toFixed(2));
                $('#vdn_net').text('₹ ' + parseFloat(res.net_amount || 0).toFixed(2));
                $('#vdn_customer_name').text(res.customer?.name ?? '-');
                $('#vdn_customer_code').text(res.customer?.code ?? '-');
                $('#vdn_customer_email').text(res.customer?.email ?? '-');
                $('#vdn_customer_phone').text(res.customer?.mobile_no ?? '-');
                $('#vdn_customer_address').text(fullAddress);
                //  $('#vso_customer_gst').text(res.customer?.gst_no ?? '-');
                // $('#vso_customer_type').text(res.customer?.gst_type ?? '-');
                //  $('#vso_customer_supply').text(
                //             res.customer?.states ?
                //             `${res.customer.states.name} (${res.customer.states.iso2})` :
                //             '-'
                //         );

                $('#vdn_sales_person').text(res.sales_person?.full_name ?? '-');
                $('#vdn_branch').text(res.branch?.branch_name ?? '-');
                // $('#vdn_payment_terms').text(res.payment_terms?.days + ' (' + res.payment_terms?.name +
                //     ')' ?? '-');



                $('#viewDebitNoteModal').modal('show');
            });

        });
    </script>
    <script>
        window.allProducts = @json($products);
    </script>

    <script>
        $(document).on('click', '.editDebitNoteBtn', function() {

            let id = $(this).data('id');

            $.get(`/debit-notes/${id}/edit`, function(res) {

                $('#debitNoteForm')
                    .attr('action', `/debit-notes/${res.id}`);

                if (!$('#debitNoteForm input[name="_method"]').length) {
                    $('#debitNoteForm').append('<input type="hidden" name="_method" value="PUT">');
                } else {
                    $('#debitNoteForm input[name="_method"]').val('PUT');
                }

                $('input[name="debit_note_date"]').val(res.debit_note_date);
                $('input[name="reference_number"]').val(res.reference_number);
                $('select[name="reason_type"]').val(res.reason_type);

                $('#debitItemsTable tbody').html('');
                index = 0;

                res.debit_note_details.forEach((item, i) => {

                    let productOptions = `<option value="">Select</option>`;

                    window.allProducts.forEach(p => {
                        productOptions += `
                        <option value="${p.id}"
                            ${p.id == item.product_id ? 'selected' : ''}>
                            ${p.name}
                        </option>
                    `;
                    });

                    let row = `
                    <tr>
                        <td>
                            <select name="items[${i}][product_id]"
                                class="form-control productSelect"
                                data-row="${i}">
                                ${productOptions}
                            </select>
                        </td>

                        <td>
                            <input name="items[${i}][batch_id]"
                                class="form-control"
                                value="${item.batch_id}" readonly>
                        </td>

                        <td>
                            <input name="items[${i}][quantity]"
                                class="form-control calc text-center"
                                value="${item.quantity}">
                        </td>

                        <td>
                            <input name="items[${i}][unit_price]"
                                class="form-control calc"
                                value="${item.unit_price}">
                        </td>

                        <td>
                            <input name="items[${i}][discount_percent]"
                                class="form-control calc"
                                value="${item.discount_percent}" readonly>
                        </td>

                        <td>
                            <input name="items[${i}][gst_percent]"
                                class="form-control calc"
                                value="${item.gst_percent}" readonly>
                        </td>

                        <td class="rowTotal">
                            ${parseFloat(item.total_amount).toFixed(2)}
                        </td>

                        <td>
                            <button type="button"
                                class="btn btn-danger btn-sm removeRow">×</button>
                        </td>
                    </tr>
                `;

                    $('#debitItemsTable tbody').append(row);
                    index++;
                });


                calcTotal();

                $('#debitNoteModal').modal('show');
            });
        });
    </script>

    <script>
        $(document).on('click', '.viewInvoiceBtn', function() {

            let id = $(this).data('id');
            $('#vinv_items').html('');

            $.get(`/invoice-orders/${id}/details`, function(res) {

                $('#vinv_number').text(res.code ?? '-');
                $('#vinv_date').text(res.date ?? '-');

                $('#vinv_customer_code').text(res.customer?.code ?? '-');
                $('#vinv_customer_name').text(res.customer?.name ?? '-');
                $('#vinv_customer_email').text(res.customer?.email ?? '-');
                $('#vinv_customer_phone').text(res.customer?.mobile_no ?? '-');

                let addr = res.customer?.get_customer_address;
                let fullAddr = addr ?
                    `${addr.address_line1}, ${addr.cities?.name}, ${addr.states?.name}, ${addr.countries?.name} - ${addr.pincode}` :
                    'NA';

                $('#invv_customer_address').text(fullAddr);
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
                $('#vinv_branch').text(res.branch?.branch_name ?? 'N/A');
                $('#vinv_sales_person').text(res.sales_person?.full_name ?? 'N/A');
                $('#vinv_payment_terms').text(
                    res.payment_terms ?
                    `${res.payment_terms.days} ${res.payment_terms.name}` :
                    'N/A'
                );

                let total = 0,
                    gst = 0,
                    discount = 0;

                res.invoice_details.forEach((item, i) => {

                    total += parseFloat(item.total_amount);
                    gst += parseFloat(item.gst_amount);
                    discount += parseFloat(item.discount_amount);

                    $('#vinv_items').append(`
                <tr>
                    <td>${i+1}</td>
                    <td>${item.product?.name ?? '-'}</td>
                    <td>${item.batch_id ?? '-'}</td>
                    <td>${item.quantity_delivered}</td>
                    <td>₹ ${item.unit_price}</td>
                    <td>₹ ${item.gst_amount}</td>
                    <td>₹ ${item.discount_amount}</td>
                    <td class="fw-bold">₹ ${item.total_amount}</td>
                </tr>
            `);
                });

                $('#vinv_total').text('₹ ' + total.toFixed(2));
                $('#vinv_gst').text('₹ ' + gst.toFixed(2));
                $('#vinv_discount').text('₹ ' + discount.toFixed(2));
                $('#vinv_net').text('₹ ' + parseFloat(res.net_amount).toFixed(2));

                $('#viewInvoiceModal').modal('show');
            });
        });
    </script>
@endpush
