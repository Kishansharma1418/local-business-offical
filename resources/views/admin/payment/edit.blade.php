@extends('include.master')

@section('content')

<style>
    .select2-container .select2-selection--single {
        height: 42px !important;
        padding: 6px 12px !important;
        display: flex !important;
        align-items: center !important;
        border: 1px solid #dce1e7 !important;
        border-radius: 8px !important;
    }
    .select2-selection__rendered { line-height: 40px !important; }
    .select2-selection__arrow { top: 8px !important; }
    .select2-container { width: 100% !important; }
</style>

<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0 fw-semibold">Edit Invoice Order</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('sale-orders.index') }}">Invoice Orders</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>
    </div>
    <button class="btn btn-success fw-normal text-white"
        type="button"
        data-bs-toggle="modal"
        data-bs-target="#invoiceModal">
    <i class="ri-file-add-line me-1"></i> Add Generate Invoice
</button>

    <form action="{{ route('invoice-orders.update', $invoice->id) }}" method="POST" class="needs-validation" novalidate>
        @csrf
        @method('PUT')

        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <h4 class="fw-semibold mb-3">Invoice Order Information</h4>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label class="label fs-16 mb-2">Customer <span class="text-danger">*</span></label>
                    <select id="customerSelect" name="customer_id" class="form-control select2" required>
                        <option value="">Select Customer</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}"
                         
                                {{ $invoice->customer_id == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>

                   
                </div>


                <div class="col-lg-3 mb-3">
                    <label class="label fs-16 mb-2">Branch <span class="text-danger">*</span></label>
                    <select name="branch_id" class="form-control" required>
                        <option value="">Select Branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" 
                                {{ $invoice->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->branch_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

              

                <div class="col-lg-3 mb-3">
                    <label class="label fs-16 mb-2">Invoice Date</label>
                    <input type="date" name="date" class="form-control" 
                           value="{{ $invoice->date }}">
                </div>

             
            </div>
        </div>

        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <h4 class="fw-semibold mb-3">Invoice Order Items</h4>

            <div class="table-responsive">
                <table class="table table-bordered" id="itemsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Batch</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th>Discount %</th>
                            <th>GST %</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @php $rowIndex = 0; @endphp

                        @foreach ($invoiceDetails as $item)
                        <tr>
                            <td>
                                <select name="items[{{ $rowIndex }}][product_id]" 
                                        class="form-control productSelect" data-row="{{ $rowIndex }}">
                                    <option value="">Select Product</option>
                                    @foreach($finishedGoods as $fg)
                                        <option value="{{ $fg->id }}"
                                            {{ $item->product_id == $fg->id ? 'selected' : '' }}>
                                            {{ $fg->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <div class="input-group">
                                    <input type="text" class="form-control batchInput" 
                                           name="items[{{ $rowIndex }}][batch_id]"
                                           value="{{ $item->batch_id }}" readonly>

                                </div>
                            </td>

                            <td><input class="form-control qty" type="number" min="1"
                            step="1"
                                name="items[{{ $rowIndex }}][quantity_delivered]"
                                value="{{ $item->quantity_delivered }}"></td>

                            <td><input class="form-control price" type="number"
                                name="items[{{ $rowIndex }}][unit_price]"
                                value="{{ $item->unit_price }}"></td>

                            <td><input class="form-control discount" readonly type="number"
                                name="items[{{ $rowIndex }}][discount_percent]"
                                value="{{ $item->discount_percent }}"></td>

                            <td><input class="form-control gst" type="number"
                                name="items[{{ $rowIndex }}][gst_percent]"
                                value="{{ $item->gst_percent }}"></td>

                            <td><input class="form-control total" type="text" readonly
                                name="items[{{ $rowIndex }}][total_amount]"
                                value="{{ $item->total_amount }}"></td>

                           <!-- / <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td> -->
                        </tr>
                        @php $rowIndex++; @endphp
                        @endforeach
                    </tbody>


                </table>
            </div>
          
        </div>

        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <h4 class="fw-semibold mb-3">Final Billing</h4>
            <div class="row">

                <div class="col-lg-3 mb-3">
                    <label class="label fs-16 mb-2"> Sub Total</label>
                    <input type="text" id="total" name="total_amount" class="form-control" readonly value="{{ $invoice->total_amount ?? 0 }}">
                </div>

                <div class="col-lg-3 mb-3">
                    <label class="label fs-16 mb-2">Select Overall Discount Type</label>
                    <select name="overall_discount_type" class="form-control">
                        <option value="">Select Discount Type</option>
                        <option value="percent" {{ $invoice->overall_bill_discount_type == 'percent' ? 'selected' : '' }}>Percent (%)</option>
                        <option value="amount" {{ $invoice->overall_bill_discount_type == 'amount' ? 'selected' : '' }}>Amount</option>
                    </select>
                </div>
                <div class="col-lg-3 mb-3" >
                    <label class="label fs-16 mb-2">Overall Discount</label>
                    <input type="number" id="overallDiscount" name="overall_discount" class="form-control"  value="{{ $invoice->overall_bill_discount_percent ?? 0 }}" step="0.01">
                </div>

                <div class="col-lg-3 mb-3">
                    <label class="label fs-16 mb-2">Grand Total</label>
                    <input type="text" id="grandTotal" name="grand_total" class="form-control" readonly value="{{ $invoice->total_amount ?? 0 }}">
                </div>
            </div>
        </div>
  
        <div class="mt-3">
            <button type="submit" id="submitBtn" class="btn btn-primary text-white px-4">Update Invoice Order</button>
            <button type="submit" id="draftBtn" class="btn btn-secondary text-white px-4">Save  As Draft</button>

        </div>

    </form>


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
                                        @foreach($salesOrderDetails as $item)

                                        @php
                                        $key = $item->product_id . '_' . $item->batch_id;

                                        $ordered = $item->quantity_ordered;
                                        $oldInv  = $alreadyInvoiced[$key] ?? 0;
                                        $current = $currentInvoiceQty[$key] ?? 0;

                                        $remaining = $ordered - $oldInv - $current;
                                        if ($remaining < 0) $remaining = 0;
                                        @endphp

                                        <tr>
                                        <td>
                                            <input type="checkbox"
                                                class="form-check-input invoice-check"
                                                data-target="qty_{{ $key }}"
                                                name="selected_items[]"
                                                value="{{ $key }}"
                                                {{ $remaining > 0 ? 'checked' : 'disabled' }}>
                                        </td>

                                        <td>{{ $item->product->name }}</td>

                                        <td>{{ $ordered }}</td>

                                        <td>{{ $oldInv + $current }}</td>

                                        <td>
                                            <input type="number"
                                                id="qty_{{ $key }}"
                                                name="items[{{ $key }}]"
                                                class="form-control invoice-qty"
                                                min="0"
                                                max="{{ $remaining }}"
                                                value="{{ $remaining }}"
                                                {{ $remaining == 0 ? 'readonly' : '' }}>
                                            <small class="text-muted">Max: {{ $remaining }}</small>
                                        </td>
                                        </tr>

                                        @endforeach
                                        </tbody>


                                        </table>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">
                                            Cancel
                                        </button>
                                        <button type="submit" class="btn btn-primary text-white">
                                            Generate Invoice
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

</div>

@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    $(document).on('change', '.invoice-check', function () {
    let target = $('#' + $(this).data('target'));

    if ($(this).is(':checked')) {
        target.prop('readonly', false);
        if (target.val() == 0) {
            target.val(target.attr('max'));
        }
    } else {
        target.val(0);
        target.prop('readonly', true);
    }
});

$(document).on('input', '.invoice-qty', function () {
    let max = parseInt($(this).attr('max'));
    let val = parseInt($(this).val()) || 0;

    if (val > max) $(this).val(max);
    if (val < 0) $(this).val(0);
});

    $(document).ready(() => {
        $('#customerSelect').select2();
    });
    $('#draftBtn').click(function () {
        $('<input>').attr({
            type: 'hidden',
            name: 'is_draft',
            value: 1
        }).appendTo('form');
    });
   
  
 
    function floatVal(v){ return parseFloat(v) || 0; }


    function calculateRowTotal(row){
        let qty  = floatVal(row.find(".qty").val());
        let price = floatVal(row.find(".price").val());
        let discount = floatVal(row.find(".discount").val());
        let gst = floatVal(row.find(".gst").val());

        if(discount < 0) discount = 0;
        if(discount > 100) discount = 100;

        let discAmt = price * (discount / 100);
        let afterDisc = price - discAmt;

        let gstAmt = afterDisc * (gst / 100);
        let final = (afterDisc + gstAmt) * qty;

        row.find(".total").val(final.toFixed(2));

        calculateGrandTotal();
    }


    function calculateGrandTotal(){
        let subtotal = 0;

        $(".total").each(function(){
            subtotal += floatVal($(this).val());
        });

        // let overall = floatVal($("#overallDiscount").val());
        // if(overall < 0) overall = 0;
        // if(overall > 100) overall = 100;

        // let discountAmt = subtotal * (overall / 100);
        // let grandTotal = subtotal - discountAmt;
            let overall = floatVal($("#overallDiscount").val());
            let type = $('select[name="overall_discount_type"]').val();

            let discountAmt = 0;

            if (type === "percent") {
                if (overall > 100) overall = 100;
                discountAmt = subtotal * (overall / 100);
            }

            if (type === "amount") {
                if (overall > subtotal) overall = subtotal;
                discountAmt = overall;
            }

            let grandTotal = subtotal - discountAmt;

        $("#total").val(subtotal.toFixed(2));

        $("#grandTotal").val(grandTotal.toFixed(2));
    }

    


    // function fetchCustomerProductDiscount(customerId, productId, row){
    //     if(!customerId || !productId) return;

    //     $.get('/customer-product-discount', 
    //         { customer_id: customerId, product_id: productId }, 
    //         function(res){

    //             row.find(".discount").val(floatVal(res.discount));
    //             calculateRowTotal(row);
    //         }
    //     );
    // }


    $(document).on("input", ".qty, .price, .discount, .gst", function(){
        calculateRowTotal($(this).closest("tr"));
    });

    $(document).on("change", ".productSelect", function(){
        let row = $(this).closest("tr");
        let productId = $(this).val();
        let customerId = $("#customerSelect").val();

        fetchCustomerProductDiscount(customerId, productId, row);
    });

   

    $(document).on("input", "#overallDiscount", function(){
        calculateGrandTotal();
    });


    $(document).on("click", ".removeRow", function(){
        $(this).closest("tr").remove();
        calculateGrandTotal();
    });
    // $(document).on("change", ".productSelect", function(){
    //     let row = $(this).closest("tr");
    //     row.find(".hidden-product-id").val($(this).val());
    // });

    $(document).on('input', '#overallDiscount', function(){
        calculateGrandTotal();
    });

   $(document).on("input", "#overallDiscount", function () {
    let val = floatVal($(this).val());
    let type = $('select[name="overall_discount_type"]').val();
    let subtotal = floatVal($("#total").val());

    if (val < 0) val = 0;

    if (type === "percent") {
        if (val > 100) val = 100;
    }

    if (type === "amount") {
        if (val > subtotal) val = subtotal;
    }

    $(this).val(val);
    calculateGrandTotal();
});


       $(document).on('input', '.qty, .price', function () {
        let val = floatVal($(this).val());
        if ($(this).hasClass('qty') && val <= 0) {
            $(this).val(1);
        }
        if ($(this).hasClass('price') && val < 0) {
            $(this).val(0);
        }
         if ($(this).hasClass('gst_percent') && val < 0) {
            $(this).val(0);
        }
        let idx = $(this).closest('tr').find('.productSelect').data('row');
        calculateRowTotal(idx);
    });

   

$(document).on("change", 'select[name="overall_discount_type"]', function () {
    let type = $(this).val();

    if (type === "percent") {
        $("label[for='overallDiscount']").text("Overall Discount (%)");
        $("#overallDiscount").val(0);
    } else if (type === "amount") {
        $("label[for='overallDiscount']").text("Overall Discount (Amount)");
        $("#overallDiscount").val(0);
    }

    calculateGrandTotal();
});


</script>

@endpush
