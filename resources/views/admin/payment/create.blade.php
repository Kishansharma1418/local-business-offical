@extends('include.master')

@section('content')

<style>
.form-label {
    font-weight: 500;
}
.section-title {
    font-size: 18px;
    font-weight: 600;
}
input[readonly] {
    background-color: #f9fafb;
}
</style>

<div class="container-fluid">

    {{-- Heading --}}
    <div class="mb-4">
        <h4 class="section-title">
            Payment for {{ $invoice->invoice_no ?? 'Invoice' }}
        </h4>
    </div>

    <form action="{{ route('payment.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

       

        {{-- Row 1 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label text-danger">Customer Name*</label>
                <input type="text" class="form-control" readonly
                    value="">
            </div>

            <div class="col-md-6">
                <label class="form-label text-danger">Payment #*</label>
                <input type="text" name="code" class="form-control"
                   >
            </div>
        </div>

        {{-- Row 2 --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Amount Received (INR)</label>
                <input type="number" step="0.01" name="amount_paid"
                    class="form-control">
            </div>

            <div class="col-md-6">
                <label class="form-label">Bank Charges (if any)</label>
                <input type="number" step="0.01" name="bank_charges"
                    class="form-control">
            </div>
        </div>

        {{-- TDS --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Tax deducted?</label><br>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"
                        name="tax_deduction" value="no" checked>
                    <label class="form-check-label">No Tax deducted</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio"
                        name="tax_deduction" value="yes">
                    <label class="form-check-label">
                        Yes, TDS (Income Tax)
                    </label>
                </div>
            </div>

            <div class="col-md-6 d-none" id="withheldBox">
                <label class="form-label text-danger">Amount Withheld*</label>
                <input type="number" step="0.01"
                    name="amount_withheld"
                    class="form-control">
            </div>
        </div>

        <hr>

        {{-- Dates --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label text-danger">Payment Date*</label>
                <input type="date" name="payment_date"
                    class="form-control"
                    value="{{ date('Y-m-d') }}">
            </div>

            <div class="col-md-6">
                <label class="form-label">Payment Received On</label>
                <input type="date" name="payment_received_on"
                    class="form-control">
            </div>
        </div>

        {{-- Mode --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Payment Mode</label>
                <select name="payment_method" class="form-control">
                    <option value="Cash">Cash</option>
                    <option value="UPI">UPI</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Cheque">Cheque</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Reference #</label>
                <input type="text" name="reference_number"
                    class="form-control">
            </div>
        </div>

        {{-- Notes --}}
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3"></textarea>
            </div>
        </div>

        {{-- Attachments --}}
        <div class="row mb-4">
            <div class="col-md-12">
                <label class="form-label">Attachments</label>
                <input type="file" name="upload_receipt"
                    class="form-control">
                <small class="text-muted">
                    Max 5MB
                </small>
            </div>
        </div>

        {{-- Submit --}}
        <div class="text-end">
            <button class="btn btn-primary px-4">
                Save Payment
            </button>
        </div>

    </form>
</div>

@endsection
