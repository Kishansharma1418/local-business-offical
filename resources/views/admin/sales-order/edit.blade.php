@extends('include.master')

@section('content')
    <style>
        .select2-container .select2-selection--single {
            height: 50px !important;
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

        #batchModal .btn-primary,
        #batchModal .btn-primary:hover,
        #batchModal .btn-primary:focus,
        #batchModal .btn-primary:active {
            color: #fff !important;
        }
    </style>

    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0 fw-semibold">Edit Sales Order</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('sale-orders.index') }}">Sales Orders</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div>

        <form action="{{ route('sale-orders.update', $salesOrder->id) }}" method="POST" class="needs-validation"
            novalidate>
            @csrf
            @method('PUT')

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h4 class="fw-semibold mb-3">Sales Order Information</h4>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="label fs-16 mb-2">Customer <span class="text-danger">*</span></label>
                        <select id="customerSelect" name="customer_id" class="form-control select2" required>
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" data-gst="{{ $customer->gst_no }}"
                                    data-branch="{{ $customer->branch_id }}"
                                    data-payment-terms="{{ $customer->payment_terms_id }}"
                                    data-limit="{{ $customer->credit_limit }}" data-gst-type="{{ $customer->gst_type }}"
                                    data-outstanding="{{ $customer->salesOrders->sum('net_amount') }}"
                                    {{ $salesOrder->customer_id == $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>

                        <div style="margin-top: 12px;">
                            <h5 id="gstText">GST Treatment: - {{ $salesOrder->customer->gst_type ?? '' }}
                                {{ $salesOrder->customer->gst_no ?? '' }}</h5>
                        </div>

                        <small class="creditlimitData" id="limitText">
                            Credit Limit: {{ $customerCreditLimit }}
                        </small>
                        <small class="outstandingText" id="outstandingText">
                            Outstanding: {{ $customerOutstanding }}
                        </small>
                    </div>


                    <div class="col-lg-3 mb-3">
                        <label class="label fs-16 mb-2">Branch <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-control" style="height: 50px;" required>
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ $salesOrder->branch_id == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <label class="label fs-16 mb-2">Payment Terms</label>
                        <select name="payment_terms_id" class="form-control" id="paymentTermsSelect" style="height: 50px;">
                            <option value="">Select</option>
                            @foreach ($paymentTerms as $term)
                                <option value="{{ $term->id }}"
                                    {{ $salesOrder->payment_terms_id == $term->id ? 'selected' : '' }}>
                                    {{ $term->days }} {{ $term->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <label class="label fs-16 mb-2">Order Date</label>
                        <input type="date" name="date" class="form-control" style="height: 50px;"
                            value="{{ $salesOrder->date }}">
                    </div>

                    <div class="col-lg-3 mb-3">
                        <label class="label fs-16 mb-2">Sales Person</label>
                        <select name="sales_person_id" class="form-control" style="height: 50px;">
                            <option value="">Select</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}"
                                    {{ $salesOrder->sales_person_id == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            <div class="card bg-white p-20 rounded-10 border border-white">
                <h4 class="fw-semibold mb-3">Sales Order Items</h4>

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

                            @foreach ($salesOrderDetails as $item)
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
                                                name="items[{{ $rowIndex }}][batch_id]" value="{{ $item->batch_id }}"
                                                readonly>

                                        </div>
                                    </td>

                                    <td><input class="form-control qty" type="number" step="1"
                                            name="items[{{ $rowIndex }}][quantity_ordered]"
                                            value="{{ $item->quantity_ordered }}" required></td>

                                    <td><input class="form-control price" type="number"
                                            name="items[{{ $rowIndex }}][unit_price]" value="{{ $item->unit_price }}"
                                            step="0.01" required min="0">
                                    </td>

                                    <td><input class="form-control discount" readonly type="number"
                                            name="items[{{ $rowIndex }}][discount_percent]"
                                            value="{{ $item->discount_percent }}"></td>

                                    <td><input class="form-control gst" type="number"
                                            name="items[{{ $rowIndex }}][gst_percent]"
                                            value="{{ $item->gst_percent }}" readonly></td>

                                    <td><input class="form-control total" type="text" readonly
                                            name="items[{{ $rowIndex }}][total_amount]"
                                            value="{{ $item->total_amount }}"></td>

                                    <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                                </tr>
                                @php $rowIndex++; @endphp
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-6">

                    </div>
                    <div class="col-lg-4">

                    </div>

                    <div class="col-lg-2 mb-3">
                        <label class="label fs-16 mb-2"> Sub Total</label>
                        <input type="text" id="total" name="total_amount" class="form-control"
                            style="height: 50px;" readonly value="{{ $salesOrder->total_amount ?? 0 }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button id="addRow" type="button" class="btn btn-primary text-white mt-3">+ Add Item</button>
                </div>
            </div>



    </div>
    <div class="mt-3 mb-3">
        <button type="submit" id="submitBtn" class="btn btn-primary text-white px-4">Update Sales Order</button>
        <button type="submit" id="draftBtn" class="btn btn-secondary text-white px-4">Save As Draft</button>
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

        let customerCreditLimit = 0;
        let customerOutstanding = 0;

        $('#customerSelect').on('change', function() {
            let id = $(this).val();
            if (!id) return;

            let gst = $('#customerSelect option:selected').data('gst');
            let limit = $('#customerSelect option:selected').data('limit') || 0;

            let gstType = $('#customerSelect option:selected').data('gst-type');
            $("#gstText").text("GST Treatment: " + gst + " " + gstType);
            $("#limitText").text("Credit Limit: ₹" + parseFloat(limit).toFixed(2));
            $("#outstandingText").text("Outstanding Amount: ₹" + parseFloat($('#customerSelect option:selected')
                .data('outstanding') || 0).toFixed(2));


            let customerBranch = $('#customerSelect option:selected').data('branch');
            if (customerBranch) {
                $('select[name="branch_id"]').val(customerBranch).trigger('change');
            }
            let paymentTermsId = $('#customerSelect option:selected').data('payment-terms');

            if (paymentTermsId) {
                $('#paymentTermsSelect')
                    .val(paymentTermsId)
                    .trigger('change');
            }
            $.get('/customer-credit-check', {
                customer_id: id
            }, function(res) {
                customerCreditLimit = parseFloat(res.credit_limit || 0);
                customerOutstanding = parseFloat(res.outstanding || 0);

                $('#customerCreditInfo').remove();
                checkCreditLimit();
            });
        });

        function checkCreditLimit() {

            let orderTotal = 0;

            $('.total').each(function() {
                orderTotal += parseFloat($(this).val() || 0);
            });

            let totalWithOutstanding = orderTotal + customerOutstanding;

            if (customerCreditLimit > 0 && totalWithOutstanding > customerCreditLimit) {
                Swal.fire({
                    icon: 'error',
                    title: 'Credit Limit Exceeded',
                    html: `
                    <b>Customer Limit:</b> ₹${customerCreditLimit}<br>
                    <b>Outstanding:</b> ₹${customerOutstanding}<br>
                    <b>Order Total:</b> ₹${orderTotal.toFixed(2)}<br><br>
            <span class="text-danger fw-bold">This order cannot be processed because the total exceeds the customer's allowed credit limit.</span>
                `
                });

                $('#submitBtn').prop('disabled', true);
            } else {
                $('#submitBtn').prop('disabled', false);
            }
        }

        function floatVal(v) {
            return parseFloat(v) || 0;
        }

        function calculateRowTotal(row) {
            let qty = floatVal(row.find(".qty").val());
            let price = floatVal(row.find(".price").val());
            let discount = floatVal(row.find(".discount").val());
            let gst = floatVal(row.find(".gst").val());

            if (discount < 0) discount = 0;
            if (discount > 100) discount = 100;

            let discAmt = price * (discount / 100);
            let afterDisc = price - discAmt;

            let gstAmt = afterDisc * (gst / 100);
            let final = (afterDisc + gstAmt) * qty;

            row.find(".total").val(final.toFixed(2));

            calculateGrandTotal();
        }

        function calculateGrandTotal() {
            let subtotal = 0;

            $(".total").each(function() {
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

        function fetchCustomerProductDiscount(customerId, productId, row) {
            if (!customerId || !productId) return;

            $.get('/customer-product-discount', {
                    customer_id: customerId,
                    product_id: productId
                },
                function(res) {

                    row.find(".discount").val(floatVal(res.discount));
                    calculateRowTotal(row);
                }
            );
        }


        $(document).on("input", ".qty, .price, .discount, .gst", function() {
            calculateRowTotal($(this).closest("tr"));
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
            calculateRowTotal(idx);
        });

        $(document).on("change", ".productSelect", function() {
            let row = $(this).closest("tr");
            let productId = $(this).val();
            let customerId = $("#customerSelect").val();

            fetchCustomerProductDiscount(customerId, productId, row);
        });

        $(document).on("click", ".selectBatch", function() {
            let row = window._currentRow;

            row.find(".batchInput").val($(this).data("batch"));
            row.find(".price").val($(this).data("price"));
            row.find(".gst").val($(this).data("gst"));

            calculateRowTotal(row);
            $("#batchModal").modal("hide");
        });

        $(document).on("input", "#overallDiscount", function() {
            calculateGrandTotal();
        });

        $(document).on('click', '.selectBatch', function() {
            let row = window._currentRow;
            let rowIndex = row.find(".productSelect").data("row");

            row.find('.batchInput').val($(this).data('batch'));
            row.find('.price').val($(this).data('price'));
            row.find('.gst').val($(this).data('gst'));

            calculateRowTotal(rowIndex);
            $('#batchModal').modal('hide');
        });


        $(document).on("click", "#addRow", function() {

            let rowIndex = $("#itemsTable tbody tr").length;

            let newRow = `
            <tr>
                <td>
                    <select class="form-control productSelect">
                        <option value="">Select Product</option>
                        @foreach ($finishedGoods as $fg)
                            <option value="{{ $fg->id }}">{{ $fg->name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="items[${rowIndex}][product_id]" class="hidden-product-id">
                </td>

                <td>
                    <div class="input-group">
                        <input readonly class="form-control batchInput" 
                            name="items[${rowIndex}][batch_id]">
                       
                    </div>
                </td>

                <td>
                    <input class="form-control qty" type="number" value="1" 
                        name="items[${rowIndex}][quantity_ordered]" required>
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
                    <input class="form-control gst" type="number" value="18"
                        name="items[${rowIndex}][gst_percent]">
                </td>

                <td>
                    <input class="form-control total" readonly
                        name="items[${rowIndex}][total_amount]">
                </td>

                <td>
                    <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                </td>
            </tr>
        `;

            $("#itemsTable tbody").append(newRow);
        });

        // $(document).on("click", ".removeRow", function() {
        //     $(this).closest("tr").remove();
        //     calculateGrandTotal();
        // });

        $(document).on("click", ".removeRow", function() {

            let rowCount = $("#itemsTable tbody tr").length;

            if (rowCount <= 1) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Action Not Allowed',
                    text: 'At least one item row is required.'
                });
                return;
            }

            $(this).closest("tr").remove();
            calculateGrandTotal();
            checkCreditLimit();
        });

        $(document).on("change", ".productSelect", function() {
            let row = $(this).closest("tr");
            row.find(".hidden-product-id").val($(this).val());
        });

        $(document).on('input', '#overallDiscount', function() {
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


        // $(document).on('input', '.qty, .price', function() {
        //     let val = floatVal($(this).val());
        //     if ($(this).hasClass('qty') && val <= 0) {
        //         $(this).val(1);
        //     }
        //     if ($(this).hasClass('price') && val < 0) {
        //         $(this).val(0);
        //     }
        //     if ($(this).hasClass('gst_percent') && val < 0) {
        //         $(this).val(0);
        //     }
        //     let idx = $(this).closest('tr').find('.productSelect').data('row');
        //     calculateRowTotal(idx);
        // });

        $(document).on('input', '.qty', function() {
            let val = $(this).val();

            if (val.includes('.')) {
                $(this).val(val.replace('.', ''));
                return;
            }

            if (parseInt(val) < 0) {
                $(this).val(1);
                return;
            }

            if (val === '') {
                return;
            }

            if (parseInt(val) === 0) {
                $(this).val(1);
            }

            let idx = $(this).closest('tr').find('.productSelect').data('row');
            calculateRowTotal(idx);
        });
        $(document).on('keydown', '.qty', function(e) {

            if (
                e.key === 'Backspace' ||
                e.key === 'Delete' ||
                e.key === 'ArrowLeft' ||
                e.key === 'ArrowRight' ||
                e.key === 'Tab'
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

        $(document).on("change", ".productSelect", function() {

            let row = $(this).closest("tr");
            window._currentRow = row;

            let productId = $(this).val();
            let customerId = $("#customerSelect").val();

            if (!customerId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Select Customer First',
                    text: 'Please select customer before selecting product.'
                });
                $(this).val('');
                $('#customerSelect').select2('open');
                return;
            }

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
                        <button class="btn btn-primary btn-sm selectBatch"
                            data-batch="${b.batch_number}"
                            data-price="${b.unit_cost}"
                            data-gst="${b.gst_percent}">
                            Select
                        </button>
                    </td>
                </tr>
            `;
                });

                $("#batchDataBody").html(html);
                $("#batchModal").modal("show");
            });
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
    </script>
@endpush
