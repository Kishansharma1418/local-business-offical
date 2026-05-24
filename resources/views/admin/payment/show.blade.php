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

    {{-- MAIN CARD --}}
    <div class="card bg-white p-4 rounded-10 border border-light shadow-sm">
        <div class="card-body">

            {{-- INVOICE TOP --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0">
                    <i class="ri-bill-line text-primary me-2"></i>
                    {{ $invoice->code ?? 'INV-'.$invoice->id }}
                </h4>

                <div class="d-flex gap-3 align-items-center">
                    <span class="badge 
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
                            <li><strong>Name:</strong> {{ $invoice->customer->name }}</li>
                            <li><strong>Mobile:</strong> {{ $invoice->customer->mobile_no }}</li>
                            <li><strong>Email:</strong> {{ $invoice->customer->email }}</li>
                            <li>
                                <strong>Address:</strong>
                                {{ $customerAddress
                                    ? $customerAddress->address_line1.', '.
                                      ($customerAddress->cities->name ?? '').', '.
                                      ($customerAddress->states->name ?? '')
                                    : '-' }}
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- INVOICE INFO --}}
                <div class="col-md-6">
                    <div class="border rounded-3 p-3 h-100">
                        <h5 class="text-primary mb-3">Invoice Information</h5>

                        <ul class="list-unstyled mb-0">
                            <li>
                                <strong>Invoice Date:</strong>
                                {{ \Carbon\Carbon::parse($invoice->date)->format('d M Y') }}
                            </li>

                            <li>
                                <strong>Sales Order:</strong>
                                {{ $invoice->salesOrder->code ?? '-' }}
                            </li>

                            <li>
                                <strong>Created By:</strong>
                                {{ $invoice->createdBy->full_name ?? '-' }}
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

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Invoice Qty</th>
                                    <th>Unit Price</th>
                                    <th>GST %</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($invoice->invoiceDetails as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <strong>{{ $item->product->name }}</strong><br>
                                        <small class="text-muted">
                                            Batch: {{ $item->batch_id ?? '-' }}
                                        </small>
                                    </td>

                                    <td>{{ $item->quantity_delivered }}</td>
                                    <td>₹ {{ number_format($item->unit_price,2) }}</td>
                                    <td>{{ $item->gst_percent }}%</td>

                                    <td class="fw-bold">
                                        ₹ {{ number_format($item->total_amount,2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- AMOUNT SUMMARY --}}
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="border rounded-3 p-3">
                        <h6>Total Amount</h6>
                        <h4>₹ {{ number_format($invoice->total_amount,2) }}</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded-3 p-3">
                        <h6>GST Amount</h6>
                        <h4>
                        ₹ {{ number_format($invoice->invoiceDetails->sum('gst_amount'), 2) }}
                    </h4>

                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded-3 p-3">
                        <h6>Discount</h6>
                        <h4>₹ {{ number_format($invoice->discount_amount,2) }}</h4>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="border rounded-3 p-3">
                        <h6>Net Payable</h6>
                        <h4 class="text-success">
                            ₹ {{ number_format($invoice->total_amount,2) }}
                        </h4>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <!-- <div class="mt-4 d-flex gap-2">
                <a href="{{ route('sale-orders.show', encrypt($invoice->sale_order_id)) }}"
                   class="btn btn-danger text-white">
                    <i class="ri-arrow-left-line me-1"></i> Back to Sales Order
                </a>

                <button onclick="window.print()" class="btn btn-secondary text-white">
                    <i class="ri-printer-line me-1"></i> Print
                </button>
            </div> -->

        </div>
    </div>
</div>
@endsection
