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
        </style>

        <div class="main-content-container overflow-hidden">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
                <h3 class="mb-0 fw-semibold">Edit Credit Note</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb align-items-center mb-0 lh-1">
                        <li class="breadcrumb-item">
                            <a href="{{ route('dashboard') }}" class="text-decoration-none">
                                <i class="ri-home-8-line fs-15 text-primary me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('credit-notes.index') }}">Credit Notes</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>

            <form action="{{ route('credit-notes.update', encrypt($creditNote->id)) }}" method="POST"
                class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                    <h4 class="fw-semibold mb-3">Credit Note Information</h4>
                    <div class="row">
                        <div class="col-lg-4 mb-3">
                            <label class="label fs-16 mb-2">Customer <span class="text-danger">*</span></label>
                            <select id="customerSelect" name="customer_id" class="form-control select2" required>
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" data-gst="{{ $customer->gst_no }}"
                                        data-branch="{{ $customer->branch_id }}"
                                        data-payment-terms="{{ $customer->payment_terms_id }}"
                                        data-limit="{{ $customer->credit_limit }}"
                                        data-outstanding="{{ $customer->salesOrders->sum('net_amount') }}"
                                        {{ $creditNote->customer_id == $customer->id ? 'selected' : '' }}>
                                        {{ $customer->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>


                        <div class="col-lg-4 mb-3">
                            <label class="label fs-16 mb-2">Branch <span class="text-danger">*</span></label>
                            <select name="branch_id" class="form-control" required>
                                <option value="">Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ $creditNote->branch_id == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->branch_name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>


                        <div class="col-lg-4 mb-3">
                            <label class="label fs-16 mb-2">Credit Note Date</label>
                            <input type="date" name="credit_note_date" class="form-control" style="height: 50px;"
                                value="{{ $creditNote->credit_note_date }}">
                        </div>

                        <div class="col-lg-4 mb-3">
                            <label class="label fs-16 mb-2">Sales Person</label>
                            <select name="sales_person_id" class="form-control">
                                <option value="">Select</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}"
                                        {{ $creditNote->sales_person_id == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->full_name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>


                        <div class="col-lg-4 mb-3">
                            <label class="label fs-16 mb-2">Invoice No</label>
                            <select name="invoice_id" id="invoiceSelect" class="form-control select2">
                                <option value="">Select Invoice</option>

                                <option value="{{ $creditNote->invoice_id }}" selected>
                                    {{-- {{ $creditNote->invoice->code }} | --}}
                                    {{-- ₹{{ $creditNote->invoice->net_amount }} --}}
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label class="label fs-16 mb-2">Referance Number </label>
                            <input type="text" name="reference_number" class="form-control"
                                value="{{ $creditNote->reference_number }}">
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label class="label fs-16 mb-2">Reason Type</label>
                            <select name="reason_type" id="reason_type" class="form-control">
                                <option value="">Select Reason Type</option>
                                <option value="sales_return"
                                    {{ $creditNote->reason_type == 'sales_return' ? 'selected' : '' }}>Sales Return
                                </option>
                                <option value="pricing_error"
                                    {{ $creditNote->reason_type == 'pricing_error' ? 'selected' : '' }}>Pricing Error
                                </option>
                                <option value="deficiency_in_service"
                                    {{ $creditNote->reason_type == 'deficiency_in_service' ? 'selected' : '' }}>Deficiency
                                    In Service</option>
                                <option value="correction_in_invoice"
                                    {{ $creditNote->reason_type == 'correction_in_invoice' ? 'selected' : '' }}>Correction
                                    In Invoice</option>
                                <option value="change_in_pos"
                                    {{ $creditNote->reason_type == 'change_in_pos' ? 'selected' : '' }}>Change In POS
                                </option>
                                <option value="finalization_of_provisional_assessment"
                                    {{ $creditNote->reason_type == 'finalization_of_provisional_assessment' ? 'selected' : '' }}>
                                    Finalization Of Provisional Assessment</option>
                                <option value="other" {{ $creditNote->reason_type == 'other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                        </div>


                    </div>
                </div>

                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
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

                                @foreach ($creditNote->creditNoteDetails as $index => $item)
                                    <tr class="{{ $item->invoice_detail_id ? 'invoice-row' : 'manual-row' }}">

                                        <input type="hidden" name="items[{{ $index }}][invoice_detail_id]"
                                            value="{{ $item->invoice_detail_id }}">

                                        <td>
                                            @if ($item->invoice_detail_id)
                                                {{-- Invoice item: text only --}}
                                                {{ $item->product->name }}
                                                <input type="hidden" name="items[{{ $index }}][product_id]"
                                                    value="{{ $item->product_id }}">
                                            @else
                                                {{-- Manual item: selectable --}}
                                                <select name="items[{{ $index }}][product_id]"
                                                    class="form-control productSelect">
                                                    <option value="">Select Product</option>
                                                    @foreach ($finishedGoods as $fg)
                                                        <option value="{{ $fg->id }}"
                                                            {{ $item->product_id == $fg->id ? 'selected' : '' }}>
                                                            {{ $fg->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>

                                        <td>
                                            <input type="text" name="items[{{ $index }}][batch_id]"
                                                class="form-control batchInput" value="{{ $item->batch_id }}"
                                                {{ $item->invoice_detail_id ? 'readonly' : '' }}>
                                        </td>

                                        <td>
                                            <input type="number" name="items[{{ $index }}][quantity]"
                                                class="form-control qty" value="{{ $item->quantity }}">
                                        </td>

                                        <td>
                                            <input type="number" name="items[{{ $index }}][unit_price]"
                                                class="form-control price" required min='0'
                                                value="{{ $item->unit_price }}">
                                        </td>

                                        <td>
                                            <input type="number" name="items[{{ $index }}][discount_percent]"
                                                class="form-control discount" value="{{ $item->discount_percent }}"
                                                readonly>
                                        </td>

                                        <td>
                                            <input type="number" name="items[{{ $index }}][gst_percent]"
                                                class="form-control gst" readonly value="{{ $item->gst_percent }}">
                                        </td>

                                        <td>
                                            <input type="text" name="items[{{ $index }}][total_amount]"
                                                class="form-control total" value="{{ $item->total_amount }}" readonly>
                                        </td>

                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button id="addRow" type="button" class="btn btn-primary text-white mt-3">+ Add Item</button>
                    </div>
                </div>

                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                    <h4 class="fw-semibold mb-3">Final Billing</h4>
                    <div class="row">

                        <div class="col-lg-3 mb-3">
                            <label class="label fs-16 mb-2"> Sub Total</label>
                            <input type="text" id="total" name="total_amount" class="form-control"
                                style="height: 50px;" readonly value="{{ $creditNote->total_amount ?? 0 }}">
                        </div>


                        <div class="col-lg-3 mb-3">
                            <label class="label fs-16 mb-2">Grand Total</label>
                            <input type="text" id="grandTotal" name="grand_total" class="form-control"
                                style="height: 50px;" readonly value="{{ $creditNote->net_amount ?? 0 }}">
                        </div>
                    </div>
                </div>

        </div>
        <div class="mt-3">
            <button type="submit" id="submitBtn" class="btn btn-primary text-white px-4">Update Credit Note</button>
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

                let gst = $('#customerSelect option:selected').data('gst') || 'Unregistered';
                let limit = $('#customerSelect option:selected').data('limit') || 0;

                $("#gstText").text("GST Treatment: " + gst);
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
                    // checkCreditLimit();
                });
            });


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
                        <input class="form-control qty" type="number" min="1" value="1" 
                            name="items[${rowIndex}][quantity]">
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

            $(document).on("click", ".removeRow", function() {
                $(this).closest("tr").remove();
                calculateGrandTotal();
            });
            $(document).on("change", ".productSelect", function() {
                let row = $(this).closest("tr");
                row.find(".hidden-product-id").val($(this).val());
            });


            $(document).on('input', '.qty, .price', function() {
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
                // calculateRowTotal(idx);
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
                        html += `00000000000
                    <tr>
                        <td>${b.batch_number}</td>
                        <td>${b.manufacturing_date}</td>
                        <td>${b.expiry_date}</td>
                        <td>${b.available_quantity}</td>
                        <td>${b.unit_cost}</td>
                        <td>${b.gst_percent}</td>
                        <td>
                            <button class="btn btn-primary text-white btn-sm selectBatch"
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

            // function loadCustomerInvoices(customerId, selectedInvoiceId = null) {

            //     if (!customerId) return;

            //     $.get(`/customer-invoices/${customerId}`, function (res) {

            //         let options = `<option value="">Select Invoice</option>`;

            //         res.forEach(inv => {
            //             let selected = selectedInvoiceId == inv.id ? 'selected' : '';
            //             options += `
    //                 <option value="${inv.id}" ${selected}>
    //                     ${inv.code} | ${inv.date} | ₹${inv.net_amount}
    //                 </option>`;
            //         });

            //         // $('#invoiceSelect').html(options).trigger('change');
            //         $('#invoiceSelect').html(options);

            //             if (!isEditPageLoad && selectedInvoiceId) {
            //                 $('#invoiceSelect').val(selectedInvoiceId).trigger('change');
            //             }

            //     });
            // }

            function loadCustomerInvoices(customerId, selectedInvoiceId = null) {

                if (!customerId) return;

                $.get(`/customer-invoices/${customerId}`, function(res) {

                    let options = `<option value="">Select Invoice</option>`;

                    res.forEach(inv => {
                        let selected = selectedInvoiceId == inv.id ? 'selected' : '';
                        options += `
                            <option value="${inv.id}" ${selected}>
                                ${inv.code} | ${inv.date} | ₹${inv.net_amount}
                            </option>`;
                    });

                    $('#invoiceSelect').html(options);

                    if (selectedInvoiceId) {
                        $('#invoiceSelect').val(selectedInvoiceId);
                    }
                });
            }


            $(document).ready(function() {

                $('#customerSelect').select2();

                let customerId = $('#customerSelect').val();
                let creditType = $('#creditNoteType').val();
                let selectedInvoiceId = "{{ $creditNote->invoice_id ?? '' }}";

                if (creditType === 'invoice') {
                    $('#invoiceDiv').show();
                    loadCustomerInvoices(customerId, selectedInvoiceId);
                } else {
                    $('#invoiceDiv').hide();
                }

                calculateGrandTotal();
            });

            // $('#customerSelect').on('change', function () {

            //     let customerId = $(this).val();
            //     let type = $('#creditNoteType').val();

            //     if (!customerId) return;

            //     // if (type === 'invoice') {

            //         loadCustomerInvoices(customerId);

            //         $('#invoiceSelect').val('').trigger('change');
            //         $('#itemsTable tbody').html('');

            //     // } else {
            //         $('#itemsTable tbody').html('');
            //         $('#addRow').trigger('click');
            //     // }
            // });

            $('#customerSelect').on('change', function() {

                let customerId = $(this).val();
                if (!customerId) return;

                loadCustomerInvoices(customerId);

                $('#itemsTable tbody').html('');
            });


            let isEditPageLoad = {{ $creditNote->invoice_id ? 'true' : 'false' }};

            $('#invoiceSelect').on('change', function() {

                let invoiceId = $(this).val();
                if (!invoiceId) return;

                // EDIT PAGE: first auto-trigger skip
                if (isEditPageLoad) {
                    isEditPageLoad = false;
                    return;
                }

                $.get(`/invoice-items/${invoiceId}`, function(res) {

                    // sirf invoice rows hatao
                    $('#itemsTable tbody tr.invoice-row').remove();

                    let startIndex = $('#itemsTable tbody tr').length;

                    res.items.forEach((item, i) => {

                        let index = startIndex + i;

                        let row = `
                        <tr class="invoice-row">
                            <input type="hidden"
                                name="items[${index}][invoice_detail_id]"
                                value="${item.invoice_detail_id}">

                            <td>${item.product_name}
                                <input type="hidden"
                                    name="items[${index}][product_id]"
                                    value="${item.product_id}">
                            </td>

                            <td>${item.batch_no}
                                <input type="hidden"
                                    name="items[${index}][batch_id]"
                                    value="${item.batch_no}">
                            </td>

                            <td><input type="number" name="items[${index}][quantity]"
                                    value="${item.quantity}" class="form-control qty"></td>

                            <td><input type="number" name="items[${index}][unit_price]"
                                    value="${item.price}" class="form-control price"></td>

                            <td><input type="number" name="items[${index}][discount_percent]"
                                    value="${item.discount}" class="form-control discount" readonly></td>

                            <td><input type="number" name="items[${index}][gst_percent]"
                                    value="${item.gst}" class="form-control gst"></td>

                            <td><input type="text" name="items[${index}][total_amount]"
                                    value="${item.total}" class="form-control total" readonly></td>

                            <td><button type="button" class="btn btn-danger btn-sm removeRow">X</button></td>
                        </tr>`;

                        $('#itemsTable tbody').append(row);
                    });

                    reindexRows();
                    calculateGrandTotal();
                });
            });


            $(document).ready(function() {

                let type = $('#creditNoteType').val();
                let customerId = $('#customerSelect').val();
                let selectedInvoiceId = "{{ $creditNote->invoice_id ?? '' }}";

                if (type === 'invoice') {
                    $('#invoiceDiv').show();
                    loadCustomerInvoices(customerId, selectedInvoiceId);
                } else {
                    $('#invoiceDiv').hide();
                }

                calculateGrandTotal();
            });

            function reindexRows() {
                $('#itemsTable tbody tr').each(function(index) {

                    $(this).find('input, select').each(function() {

                        let name = $(this).attr('name');
                        if (!name) return;

                        name = name.replace(/items\[\d+\]/, 'items[' + index + ']');
                        $(this).attr('name', name);
                    });
                });
            }

            $(document).ready(function() {

                let customerId = $('#customerSelect').val();
                let selectedInvoiceId = "{{ $creditNote->invoice_id ?? '' }}";

                if (customerId) {
                    loadCustomerInvoices(customerId, selectedInvoiceId);
                }

                calculateGrandTotal();
            });

            $(document).on("click", ".removeRow", function() {
                $(this).closest("tr").remove();
                reindexRows();
                calculateGrandTotal();
            });
        </script>
    @endpush
