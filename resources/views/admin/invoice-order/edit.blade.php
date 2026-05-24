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

        .select2-selection__rendered {
            line-height: 40px !important;
        }

        .select2-selection__arrow {
            top: 8px !important;
        }

        .select2-container {
            width: 100% !important;
        }
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


        <form action="{{ route('invoice-orders.update', $invoice->id) }}" method="POST" class="needs-validation"
            novalidate>
            @csrf
            @method('PUT')

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h4 class="fw-semibold mb-3">Invoice Order Information</h4>
                <div class="row">
                    <div class="col-lg-3 mb-3">
                        <label class="label fs-16 mb-2">
                            Customer <span class="text-danger">*</span>
                        </label>

                        {{-- Display customer name --}}
                        <input type="text" class="form-control"  value="{{ $invoice->customer?->name }}" readonly>

                        <input type="hidden" id="customer_id" name="customer_id"
                            value="{{ $invoice->customer?->id }}">


                        @if ($invoice->customer)
                            <small>
                                GST Treatment:
                                {{ $invoice->customer->gst_type ?? '-' }}
                                {{ $invoice->customer->gst_no ?? '' }}
                            </small><br>

                            <small>
                                Credit Limit:
                                {{ $invoice->customer->credit_limit ?? '-' }},
                                Credit Days:
                                {{ $invoice->customer->credit_days ?? '-' }}
                            </small>
                        @endif
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
                        <input type="date" name="date" class="form-control" value="{{ $invoice->date }}">
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="label fs-16 mb-2">Payment Terms</label>
                        <select name="payment_terms_id" class="form-control" style="height: 50px;">
                            <option value="">Select</option>
                            @foreach ($paymentTerms as $term)
                                <option value="{{ $term->id }}"
                                    {{ $invoice->payment_terms_id == $term->id ? 'selected' : '' }}>
                                    {{ $term->days }} {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
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
                                    <th>Discount Amount</th>
                                    <th>Discount After Amount</th>
                                    <th>GST %</th>
                                    <th>GST Amount</th>
                                    <th>Total</th>
                                   
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
                                                @foreach ($finishedGoods as $fg)
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

                                        <td>

                                            @php
                                                $key = $item->product_id . '_' . $item->batch_id;
                                                $maxQty = $maxQtyMap[$key] ?? $item->quantity_delivered;
                                            @endphp

                                            <input type="number" class="form-control qty" max="{{ $maxQty }}"
                                                data-max="{{ $maxQty }}" value="{{ $item->quantity_delivered }}"
                                                name="items[{{ $rowIndex }}][quantity_delivered]">

                                          
                                        </td>

                                        <td><input class="form-control price" type="number"
                                                name="items[{{ $rowIndex }}][unit_price]"
                                                value="{{ $item->unit_price }}" step="0.01" required min='0'>
                                        </td>


                                        <td><input class="form-control discount" readonly type="number"
                                                name="items[{{ $rowIndex }}][discount_percent]"
                                                value="{{ $item->discount_percent }}"></td>

                                        <td><input class="form-control discount_amount" type="text" readonly
                                                name="items[{{ $rowIndex }}][discount_amount]"
                                                value="{{ $item->discount_amount }}"></td>

                                        @php
                                            $grossAmount = $item->unit_price * $item->quantity_delivered - $item->discount_amount;
                                        @endphp
                                       <td>
                                            <input class="form-control gross_amount" type="text" readonly
                                                name="items[{{ $rowIndex }}][gross_amount]"
                                                value="{{ $grossAmount }}">
                                       </td>

                                        <td><input class="form-control gst" type="number" readonly
                                                name="items[{{ $rowIndex }}][gst_percent]"
                                                value="{{ $item->gst_percent }}"></td>

                                        <td><input class="form-control gst_amount" type="text" readonly
                                        name="items[{{ $rowIndex }}][gst_amount]"
                                        value="{{ $item->gst_amount }}"></td>

                                        <td><input class="form-control total" type="text" readonly
                                                name="items[{{ $rowIndex }}][total_amount]"
                                                value="{{ $item->total_amount }}"></td>

                                        <!-- / <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td> -->
                                    </tr>
                                    @php $rowIndex++; @endphp
                                @endforeach
                            </tbody>


                        </table>
                        @if ($invoice->sale_order_id == null)
                            <div class="d-flex justify-content-end">
                                <button id="addRow" type="button" class="btn btn-primary text-white mt-3">+ Add
                                    Item</button>
                            </div>
                        @endif
                    </div>

                </div>

                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                    <h4 class="fw-semibold mb-3">Final Billing</h4>
                    <div class="row">

                        <div class="col-lg-3 mb-3">
                            <label class="label fs-16 mb-2">Discount After Amount</label>
                            <input type="text" id="total" name="total_amount" class="form-control" readonly value="{{ $invoice->total_amount ?? 0 }}">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label class="label fs-16 mb-2">Select Overall Discount Type</label>
                            <select name="overall_discount_type" class="form-control">
                                <option value="">Select Discount Type</option>

                                <option value="percent"
                                    {{ ($invoice->overall_bill_discount_type ?? 'percent') == 'percent' ? 'selected' : '' }}>
                                    Percent (%)
                                </option>

                                <option value="amount"
                                    {{ $invoice->overall_bill_discount_type == 'amount' ? 'selected' : '' }}>
                                    Amount
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label class="label fs-16 mb-2">Overall Discount</label>
                            <input type="number" id="overallDiscount" name="overall_discount" class="form-control"
                                value="{{ $invoice->overall_bill_discount_percent ?? 0 }}" step="0.01">
                        </div>

                        <div class="col-lg-3 mb-3">
                            <label class="label fs-16 mb-2">Grand Total</label>
                            <input type="text" id="grandTotal" name="grand_total" class="form-control" readonly
                                value="{{ $invoice->net_amount ?? 0 }}">
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" id="submitBtn" class="btn btn-primary text-white px-4">Update Invoice
                        Order</button>
                    <button type="submit" id="draftBtn" class="btn btn-secondary text-white px-4">Save As
                        Draft</button>

                </div>



        </form>


        <div class="modal fade" id="batchModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Product Batch Information</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Batch No</th>
                                    <th>Mfg</th>
                                    <th>Exp</th>
                                    <th>Stock</th>
                                    <th>Price</th>
                                    <th>GST %</th>
                                    <th>Select</th>
                                </tr>
                            </thead>
                            <tbody id="batchDataBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on('change', '.invoice-check', function() {
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

        $(document).on('input', '.invoice-qty', function() {
            let max = parseInt($(this).attr('max'));
            let val = parseInt($(this).val()) || 0;

            if (val > max) $(this).val(max);
            if (val < 0) $(this).val(0);
        });

        $(document).ready(() => {
            $('#customerSelect').select2();
        });

        $('#draftBtn').click(function() {
            $('<input>').attr({
                type: 'hidden',
                name: 'is_draft',
                value: 1
            }).appendTo('form');
        });

        function floatVal(v) {
            return parseFloat(v) || 0;
        }

        // function calculateRowTotal(row) {
        //     let qty = floatVal(row.find(".qty").val());
        //     let price = floatVal(row.find(".price").val());
        //     let discount = floatVal(row.find(".discount").val());
        //     let gst = floatVal(row.find(".gst").val());

        //     if (discount < 0) discount = 0;
        //     if (discount > 100) discount = 100;

        //     let discAmt = price * (discount / 100);
        //     let afterDisc = price - discAmt;

        //     let gstAmt = afterDisc * (gst / 100);
        //     let final = (afterDisc + gstAmt) * qty;

        //     row.find(".total").val(final.toFixed(2));

        //     calculateGrandTotal();
        // }

        // function calculateGrandTotal() {
        //     let subtotal = 0;

        //     $(".total").each(function() {
        //         subtotal += floatVal($(this).val());
        //     });

        //     // let overall = floatVal($("#overallDiscount").val());
        //     // if(overall < 0) overall = 0;
        //     // if(overall > 100) overall = 100;

        //     // let discountAmt = subtotal * (overall / 100);
        //     // let grandTotal = subtotal - discountAmt;
        //     let overall = floatVal($("#overallDiscount").val());
        //     let type = $('select[name="overall_discount_type"]').val();

        //     let discountAmt = 0;

        //     if (type === "percent") {
        //         if (overall > 100) overall = 100;
        //         discountAmt = subtotal * (overall / 100);
        //     }

        //     if (type === "amount") {
        //         if (overall > subtotal) overall = subtotal;
        //         discountAmt = overall;
        //     }

        //     let grandTotal = subtotal - discountAmt;

        //     $("#total").val(subtotal.toFixed(2));

        //     $("#grandTotal").val(grandTotal.toFixed(2));

        // }

        // function calculateRowTaxable(row) {
        //     let qty = floatVal(row.find(".qty").val());
        //     let price = floatVal(row.find(".price").val());
        //     let discount = floatVal(row.find(".discount").val());

        //     if (discount < 0) discount = 0;
        //     if (discount > 100) discount = 100;

        //     let base = price * qty;
        //     let discAmt = base * (discount / 100);
        //     let taxable = base - discAmt;

        //     row.data("taxable", taxable); // store taxable
        // }

        function calculateRowTaxable(row) {

            let qty = floatVal(row.find(".qty").val());
            let price = floatVal(row.find(".price").val());
            let discountPercent = floatVal(row.find(".discount").val());
            let gstPercent = floatVal(row.find(".gst").val());

            if (discountPercent > 100) discountPercent = 100;
            if (discountPercent < 0) discountPercent = 0;

            let baseAmount = qty * price;

            let discountAmount = baseAmount * (discountPercent / 100);
            let taxableAmount = baseAmount - discountAmount;

            let gstAmount = taxableAmount * (gstPercent / 100);


            let rowTotal = taxableAmount + gstAmount;

            row.find(".discount_amount").val(discountAmount.toFixed(2));
            row.find(".gross_amount").val(taxableAmount.toFixed(2));
            row.find(".gst_amount").val(gstAmount.toFixed(2));
            row.find(".total").val(rowTotal.toFixed(2));

            row.data("taxable", taxableAmount);
        }


        // function calculateGrandTotal() {

        //     let totalTaxable = 0;

        //     $("#itemsTable tbody tr").each(function () {
        //         calculateRowTaxable($(this));
        //         totalTaxable += floatVal($(this).data("taxable"));
        //     });

        //     let overall = floatVal($("#overallDiscount").val());
        //     let type = $('select[name="overall_discount_type"]').val();

        //     let overallDiscountAmt = 0;

        //     if (type === "percent") {
        //         if (overall > 100) overall = 100;
        //         overallDiscountAmt = totalTaxable * (overall / 100);
        //     }

        //     if (type === "amount") {
        //         if (overall > totalTaxable) overall = totalTaxable;
        //         overallDiscountAmt = overall;
        //     }

        //     let finalTotal = 0;

        //     $("#itemsTable tbody tr").each(function () {

        //         let row = $(this);
        //         let rowTaxable = floatVal(row.data("taxable"));
        //         let gst = floatVal(row.find(".gst").val());

        //         let ratio = rowTaxable / totalTaxable;
        //         let rowOverallDiscount = overallDiscountAmt * ratio;

        //         let finalTaxable = rowTaxable - rowOverallDiscount;
        //         let gstAmt = finalTaxable * (gst / 100);

        //         let rowTotal = finalTaxable + gstAmt;
        //         row.find(".total").val(rowTotal.toFixed(2));

        //         finalTotal += rowTotal;
        //     });

        //     $("#total").val(totalTaxable.toFixed(2));
        //     $("#grandTotal").val(finalTotal.toFixed(2));
        // }

        function calculateGrandTotal() {

            let totalTaxable = 0;

            $("#itemsTable tbody tr").each(function () {
                calculateRowTaxable($(this));
                totalTaxable += floatVal($(this).data("taxable"));
            });

            let overall = floatVal($("#overallDiscount").val());
            let type = $('select[name="overall_discount_type"]').val();

            let overallDiscountAmt = 0;

            if (type === "percent") {
                if (overall > 100) overall = 100;
                overallDiscountAmt = totalTaxable * (overall / 100);
            }

            if (type === "amount") {
                if (overall > totalTaxable) overall = totalTaxable;
                overallDiscountAmt = overall;
            }

            let finalTotal = 0;

            $("#itemsTable tbody tr").each(function () {

                let row = $(this);
                let rowTaxable = floatVal(row.data("taxable"));
                let gstPercent = floatVal(row.find(".gst").val());

                let ratio = rowTaxable / totalTaxable;
                let rowOverallDiscount = overallDiscountAmt * ratio;

                let finalTaxable = rowTaxable - rowOverallDiscount;
                let gstAmount = finalTaxable * (gstPercent / 100);

                let rowTotal = finalTaxable + gstAmount;

                row.find(".gst_amount").val(gstAmount.toFixed(2));
                row.find(".total").val(rowTotal.toFixed(2));

                finalTotal += rowTotal;
            });

            $("#total").val(totalTaxable.toFixed(2));
            $("#grandTotal").val(finalTotal.toFixed(2));
        }



        function fetchCustomerProductDiscount(customerId, productId, row) {
            if (!customerId || !productId) return;

            $.get('/customer-product-discount', {
                customer_id: customerId,
                product_id: productId
            }, function(res) {
                row.find(".discount").val(floatVal(res.discount));
                calculateGrandTotal();
            });
        }


        $(document).on("change", ".productSelect", function() {
            let row = $(this).closest("tr");
            let productId = $(this).val();
            let customerId = $("#customer_id").val();

            fetchCustomerProductDiscount(customerId, productId, row);
        });

        $(document).on("change", ".productSelect", function() {

            let row = $(this).closest("tr");
            window._currentRow = row;

            let productId = $(this).val();
            let customerId = $("#customer_id").val();
           //let customerId = $('input[name="customer_id"]').val();

            if (!productId) return;

            fetchCustomerProductDiscount(customerId, productId, row);

            $.get(`/finished-goods/batches/${productId}`, function(res) {
                   if (!res || res.length === 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Batch Not Available',
                        text: 'No batch available for this product. Please select another product.'
                    });

                    // 🔄 Reset product + batch fields
                    row.find('.productSelect').val('');
                    row.find('.batchInput').val('');
                    row.find('.price').val('');
                    row.find('.gst').val('');
                    row.find('.total').val('');

                    calculateGrandTotal();
                    return;
                }

                let html = "";

                res.forEach(b => {
                    html += `
                        <tr>
                            <td>${b.batch_number}</td>
                            <td>${b.manufacturing_date}</td>
                            <td>${b.expiry_date}</td>
                            <td>${b.available_quantity}</td>
                            <td>${b.unit_cost}</td>
                            <td>${b.gst_percent}</td>
                            <td>
                                <button class="btn btn-primary text-white btn-sm selectBatch" type="button"
                                    data-batch="${b.batch_number}"
                                    data-price="${b.unit_cost}"
                                    data-gst="${b.gst_percent}">
                                    Select
                                </button>
                            </td>
                        </tr>`;
                });

                $("#batchDataBody").html(html);
                $("#batchModal").modal("show");
            });
        });


        $(document).on("input", "#overallDiscount", function() {
            calculateGrandTotal();
        });


        $(document).on("click", "#addRow", function() {

            let rowIndex = $("#itemsTable tbody tr").length;

            let newRow = `
            <tr>
                <td>
                    <select name="items[${rowIndex}][product_id]" class="form-control productSelect">
                        <option value="">Select Product</option>
                        @foreach ($finishedGoods as $fg)
                            <option value="{{ $fg->id }}">{{ $fg->name }}</option>
                        @endforeach
                    </select>
                </td>

                <td>
                    <input readonly class="form-control batchInput"
                        name="items[${rowIndex}][batch_id]">
                </td>

                <td>
                    <input class="form-control qty" type="number" value="1"
                        name="items[${rowIndex}][quantity_delivered]" required>
                </td>

                <td>
                    <input class="form-control price" type="number"
                        name="items[${rowIndex}][unit_price]" step="0.01">
                </td>

                <td>
                    <input class="form-control discount" readonly type="number" value="0"
                        name="items[${rowIndex}][discount_percent]">
                </td>

                <td>
                    <input class="form-control discount_amount" type="text" readonly
                        name="items[${rowIndex}][discount_amount]" value="0">
                </td>

              <td>
                    <input class="form-control gross_amount" type="text" readonly
                        name="items[${rowIndex}][gross_amount]">
                </td>
            
                <td>
                    <input class="form-control gst" readonly type="number" value="18"
                        name="items[${rowIndex}][gst_percent]">
                </td>

                <td>
                    <input class="form-control gst_amount" type="text" readonly
                        name="items[${rowIndex}][gst_amount]">
                </td>
                

                <td>
                    <input class="form-control total" readonly
                        name="items[${rowIndex}][total_amount]">
                </td>

                <td>
                    <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                </td>
            </tr>`;


            $("#itemsTable tbody").append(newRow);
        });


        $(document).on("click", ".selectBatch", function(e) {
            e.preventDefault();

            let row = window._currentRow;

            row.find(".batchInput").val($(this).data("batch"));
            row.find(".price").val($(this).data("price"));
            row.find(".gst").val($(this).data("gst"));

            calculateGrandTotal();
            $("#batchModal").modal("hide");
        });



        $(document).on('click', '.selectBatch', function() {
            let row = window._currentRow;
            let rowIndex = row.find(".productSelect").data("row");

            row.find('.batchInput').val($(this).data('batch'));
            row.find('.price').val($(this).data('price'));
            row.find('.gst').val($(this).data('gst'));

            calculateGrandTotal();
            $('#batchModal').modal('hide');
        });

        $(document).on("click", ".removeRow", function() {
            $(this).closest("tr").remove();
            calculateGrandTotal();
        });
     

        $(document).on('input', '.gst', function() {
            let val = parseFloat($(this).val());

            if (isNaN(val)) {
                $(this).val(0);
                return;
            }

            if (val < 0) {
                $(this).val(0);
            }

            if (val > 100) {
                $(this).val(100);
            }

            let idx = $(this).closest('tr').find('.productSelect').data('row');
            calculateGrandTotal();
        });

        $(document).on("input", "#overallDiscount", function() {
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

        $(document).on("change", 'select[name="overall_discount_type"]', function() {
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

        // $(document).on('input', '.qty', function() {
        //     let row = $(this).closest('tr');
        //     let max = parseFloat($(this).data('max')) || 0;
        //     let val = parseFloat($(this).val()) || 0;

        //     if (val > max) {
        //         $(this).val(max);

        //         Swal.fire({
        //             icon: 'warning',
        //             title: 'Invalid Quantity',
        //             text: 'Invoice quantity cannot exceed remaining Sales Order quantity',
        //             timer: 1800,
        //             showConfirmButton: false
        //         });
        //     }

        //     if (val < 1) {
        //         $(this).val(1);
        //     }

        //     calculateRowTotal(row);
        // });
    </script>
    <script>
        window.hasSalesOrder = {{ $invoice->sale_order_id ? 'true' : 'false' }};

        $(document).on('input', '.qty', function() {
            let row = $(this).closest('tr');
            let val = floatVal($(this).val());

            if (val < 1) {
                $(this).val(1);
                val = 1;
            }

            if (window.hasSalesOrder) {
                let max = floatVal($(this).data('max'));

                if (max > 0 && val > max) {
                    $(this).val(max);

                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Quantity',
                        text: 'Invoice quantity cannot exceed remaining Sales Order quantity',
                        timer: 1800,
                        showConfirmButton: false
                    });
                }
            }

            calculateGrandTotal();
        });
    </script>
@endpush
