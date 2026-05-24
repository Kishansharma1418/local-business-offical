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

        #batchModal .btn-primary,
        #batchModal .btn-primary:hover,
        #batchModal .btn-primary:focus,
        #batchModal .btn-primary:active {
            color: #fff !important;
        }
    </style>

    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0 fw-semibold">Add Sales Order</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('sale-orders.index') }}">Sales Orders</a></li>
                    <li class="breadcrumb-item active">Add Sales Order</li>
                </ol>
            </nav>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('sale-orders.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h4 class="fw-semibold mb-3">Sales Order Information</h4>
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <label class="label fs-16 mb-2">Customer <span class="text-danger">*</span></label>

                        <div class="d-flex gap-2">
                            <div class="flex-grow-1 ">
                                <select id="customerSelect" name="customer_id" class="form-control select2" required>
                                    <option value="">Select Customer</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{ $customer->id }}" data-gst="{{ $customer->gst_no }}"
                                            data-branch="{{ $customer->branch_id }}"
                                            data-payment-terms="{{ $customer->payment_terms_id }}"
                                            data-limit="{{ $customer->credit_limit }}"
                                            data-gst-type="{{ $customer->gst_type }}"
                                            data-outstanding="{{ $customer->salesOrders->sum('net_amount') }}">
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div style="margin-top: 12px;">
                                    <h5 id="gstText">GST Treatment: -</h5>
                                </div>

                                <small class="creditlimitData" id="limitText">
                                    Credit Limit: -
                                </small>

                                <small class="creditlimitData" id="outstandingText">
                                    Outstanding Amount: -
                                </small>

                            </div>
                            <button type="button" onclick="window.open('{{ route('customers.create') }}','_blank')"
                                class="btn btn-primary text-white d-flex align-items-center justify-content-center px-3"
                                style="height:55px;">+
                                Add
                            </button>


                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <label class="label fs-16 mb-2">Branch <span class="text-danger">*</span></label>
                        <select name="branch_id" class="form-control" required>
                            <option value="">Select Branch</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <label class="label fs-16 mb-2">Payment Terms</label>
                        <select name="payment_terms_id" class="form-control" id="paymentTermsSelect">
                            <option value="" selected>Select</option>
                            @foreach ($paymentTerms as $term)
                                <option value="{{ $term->id }}">{{ $term->days }} {{ $term->name }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 mb-3">
                        <label class="label fs-16 mb-2">Order Date <span class="text-danger">*</span></label>
                        <input type="date" name="date" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="col-lg-3 mb-3">
                        <label class="label fs-16 mb-2">Sales Person</label>
                        <select name="sales_person_id" class="form-control">
                            <option value="" selected>Select</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="card bg-white p-20 rounded-10 border border-white mb-0">
                <h4 class="fw-semibold mb-3">Sales Order Items</h4>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="itemsTable">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 25%">Product</th>
                                <th>Batch</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Discount %</th>
                                <th>GST %</th>
                                <th>Total</th>
                                <th style="width: 5%">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>
                                    <select name="items[0][product_id]" class="form-control productSelect select2"
                                        id="productSelect" data-row="0" required>
                                        <option value="">Select Product</option>
                                        @foreach ($finishedGoods as $fg)
                                            <option class="openBatchModal" value="{{ $fg->id }}">{{ $fg->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <div class="input-group">
                                        <input type="text" name="items[0][batch_id]" class="form-control batchInput"
                                            readonly>
                                        <!-- <button type="button" class="btn btn-outline-secondary openBatchModal">Select</button> -->
                                    </div>
                                </td>

                                <td><input type="number" name="items[0][quantity_ordered]" class="form-control qty"
                                        value="1" required></td>
                                <td><input type="number" name="items[0][unit_price]" class="form-control price"
                                        step="0.01" required min="0"></td>
                                <td><input type="number" name="items[0][discount_percent]" class="form-control discount"
                                        readonly step="0.01" value="0"></td>
                                <td><input type="number" name="items[0][gst_percent]" class="form-control gst"
                                        step="0.01" value="18" readonly></td>
                                <td><input type="text" name="items[0][total_amount]" class="form-control total"
                                        readonly></td>

                                <!-- <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td> -->
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class ="row">
                    <div class="col-lg-4 mb-3">

                    </div>
                    <div class="col-lg-6 mb-3">

                    </div>

                    <div class="col-lg-2 mb-3">
                        <label class="label fs-16 mb-2"> Subtotal</label>
                        <input type="text" id="subtotal" name="subtotal" class="form-control" readonly>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" id="addRow" class="btn btn-primary text-white mt-3">+ Add Item</button>
                </div>
            </div>
            <!-- <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                                    <h4 class="fw-semibold mb-3">Final Billing</h4>

                                    <div class="row">

                                        <div class="col-lg-3 mb-3">
                                            <label class="label fs-16 mb-2"> Subtotal</label>
                                            <input type="text" id="subtotal" name="subtotal" class="form-control" readonly>
                                        </div>
                                       

                                        <div class="col-lg-3 mb-3">
                                            <label class="label fs-16 mb-2">Grand Total</label>
                                            <input type="text" id="grandTotal" name="grand_total" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div> -->

            <div class="mt-1  mb-4">
                <button type="submit" id="submitBtn" class="btn btn-primary text-white px-4">Save Sales Order</button>
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
                                    <th>Discount</th>
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
        $('#draftBtn').click(function() {
            $('<input>').attr({
                type: 'hidden',
                name: 'is_draft',
                value: 1
            }).appendTo('form');
        });

        $(document).ready(() => {
            $('#customerSelect').select2();
            // $('#productSelect').select2();
        });

        function floatVal(v) {
            return parseFloat(v) || 0;
        }

        let globalDiscount = 0;

        function fetchCustomerProductDiscount(customerId, productId, rowIndex) {
            if (!customerId || !productId) return;

            $.get('/customer-product-discount', {
                customer_id: customerId,
                product_id: productId
            }, function(res) {
                globalDiscount = floatVal(res.discount);
                $(`input[name="items[${rowIndex}][discount_percent]"]`).val(globalDiscount);
                calculateRowTotal(rowIndex);
            });
        }



        $(document).on('change', '.productSelect', function() {
            let row = $(this).closest('tr');
            window._currentRow = row;

            let rowIndex = $(this).data('row');
            let productId = $(this).val();
            let customerId = $('#customerSelect').val();

            fetchCustomerProductDiscount(customerId, productId, rowIndex);

            if (productId) {
                openBatchModal(productId);
            }
        });

        function openBatchModal(productId) {
            let row = window._currentRow;
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
                        <td>${globalDiscount}</td>
                        <td>${b.gst_percent}</td>
                        <td>
                            <button class="btn btn-primary btn-sm selectBatch"
                                data-batch="${b.batch_number}"
                                data-price="${b.unit_cost}"
                                data-gst="${b.gst_percent}">
                                Select
                            </button>
                        </td>
                    </tr>`;
                });

                $('#batchDataBody').html(html);
                $('#batchModal').modal('show');
            });
        }
        $(document).on('click', '.openBatchModal', function() {
            let row = $(this).closest('tr');
            window._currentRow = row;

            let productId = row.find('.productSelect').val();
            if (!productId) {
                alert("Select Product First");
                return;
            }

            openBatchModal(productId);
        });
        $(document).on('click', '.selectBatch', function() {
            let row = window._currentRow;

            let batch = $(this).data('batch');
            let price = $(this).data('price');
            let gst = $(this).data('gst');

            let rowIndex = row.find('.productSelect').data('row');

            row.find(`input[name="items[${rowIndex}][batch_id]"]`).val(batch);
            row.find(`input[name="items[${rowIndex}][unit_price]"]`).val(price);
            row.find(`input[name="items[${rowIndex}][gst_percent]"]`).val(gst);

            row.find(`input[name="items[${rowIndex}][discount_percent]"]`).val(globalDiscount);

            calculateRowTotal(rowIndex);
            $('#batchModal').modal('hide');
        });

        let rowIndex = 1;

        $('#addRow').click(function() {
            let newRow =
                `<tr>
                <td>
                    <select name="items[${rowIndex}][product_id]" class="form-control productSelect " data-row="${rowIndex}">
                        <option value="">Select Product</option>
                        @foreach ($finishedGoods as $fg)
                            <option value="{{ $fg->id }}">{{ $fg->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <div class="input-group">
                        <input type="text" name="items[${rowIndex}][batch_id]" class="form-control batchInput" readonly>
                        <!-- <button type="button" class="btn btn-outline-secondary openBatchModal">Select</button> -->
                       
                    </div>
                </td>
                <td><input type="number" name="items[${rowIndex}][quantity_ordered]" required class="form-control qty" value="1"></td>
                <td><input type="number" name="items[${rowIndex}][unit_price]" class="form-control price" step="0.01"></td>
                <td><input type="number" name="items[${rowIndex}][discount_percent]" class="form-control discount" value="0" readonly></td>
                <td><input type="number" name="items[${rowIndex}][gst_percent]" class="form-control gst" value="18" readonly></td>
                <td><input type="text" name="items[${rowIndex}][total_amount]" class="form-control total" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
            </tr>`;

            $('#itemsTable tbody').append(newRow);
            rowIndex++;
        });

        $(document).on('click', '.removeRow', function() {
            $(this).closest('tr').remove();
        });


        let customerCreditLimit = 0;
        let customerOutstanding = 0;

        $('#customerSelect').on('change', function() {
            let id = $(this).val();
            if (!id) return;

            let gst = $('#customerSelect option:selected').data('gst');
            let gsttype = $('#customerSelect option:selected').data('gst-type') || '';
            let limit = $('#customerSelect option:selected').data('limit') || 0;

            $("#gstText").text("GST Treatment: " + gsttype + " " + gst + "");
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
            } else {
                $('#paymentTermsSelect').val('').trigger('change');
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

        function calculateRowTotal(idx) {
            let row = $(`select.productSelect[data-row="${idx}"]`).closest('tr');

            let qty = floatVal(row.find('.qty').val());
            let price = floatVal(row.find('.price').val());
            let discount = floatVal(row.find('.discount').val());
            let gst = floatVal(row.find('.gst').val());

            let amount = qty * price;
            let discAmt = amount * (discount / 100);
            let gstAmt = (amount - discAmt) * (gst / 100);

            let total = amount - discAmt + gstAmt;
            row.find('.total').val(total.toFixed(2));

            checkCreditLimit();
            calculateGrandTotal();

        }

        $(document).on('input', '.qty,.price,.discount,.gst', function() {
            let idx = $(this).closest('tr').find('.productSelect').data('row');
            calculateRowTotal(idx);
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

            // Recalculate row total
            let idx = $(this).closest('tr').find('.productSelect').data('row');
            calculateRowTotal(idx);
        });

        //     function calculateGrandTotal() {
        //     let subtotal = 0;

        //     $('.total').each(function(){
        //         subtotal += floatVal($(this).val());
        //     });

        //     let oDiscount = floatVal($('#overallDiscount').val());
        //     if(oDiscount < 0) { $('#overallDiscount').val(0); oDiscount = 0; }

        //     let discountAmt = subtotal * (oDiscount / 100);
        //     let grandTotal = subtotal - discountAmt;

        //     $("#subtotal").val(subtotal.toFixed(2));
        //     $("#grandTotal").val(grandTotal.toFixed(2));

        //     checkCreditLimit(); 
        // }

        function calculateGrandTotal() {
            let subtotal = 0;

            $(".total").each(function() {
                subtotal += floatVal($(this).val());
            });

            let discountType = $("#discountType").val();
            let discountVal = floatVal($("#overallDiscountInput").val());

            let discountAmt = 0;

            if (discountType === "percent") {
                discountAmt = subtotal * (discountVal / 100);
            }

            if (discountType === "amount") {
                discountAmt = discountVal;
            }

            let grandTotal = subtotal - discountAmt;

            $("#subtotal").val(subtotal.toFixed(2));
            $("#grandTotal").val(grandTotal.toFixed(2));

            checkCreditLimit();
        }


        $(document).on('input', '#overallDiscount', function() {
            calculateGrandTotal();
        });

        $(document).on('input', '#overallDiscount', function() {
            let val = floatVal($(this).val());

            if (val < 0) val = 0;

            if (val > 100) val = 100;

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

        function initProductSelect2() {
            $('.productSelect').select2({
                width: '100%'
            });
        }

        $(document).ready(function() {
            $('#customerSelect').select2();
            initProductSelect2();
        });
        $(document).on('select2:opening', '.productSelect', function(e) {
            if (!$('#customerSelect').val()) {
                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Select Customer First',
                    text: 'Please select a customer before choosing product.',
                }).then(() => {
                    $('#customerSelect').select2('open');
                });
            }
        });

        // Discount validation + auto update UI text
        $("#discountType").on("change", function() {
            let type = $(this).val();

            if (type === "percent") {
                $("#discountLabel").text("Overall Discount %");
                $("#overallDiscountInput").val(0);
            } else {
                $("#discountLabel").text("Overall Discount Amount");
                $("#overallDiscountInput").val(0);
            }

            calculateGrandTotal();
        });

        $("#overallDiscountInput").on("input", function() {
            let val = floatVal($(this).val());
            let type = $("#discountType").val();
            let subtotal = floatVal($("#subtotal").val());

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
    </script>
@endpush
