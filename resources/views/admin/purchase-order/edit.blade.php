@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Purchase Order</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('purchase-order.index') }}" class="text-body fs-14 hover">
                            Purchase Order List
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Edit Purchase Order</li>
                </ol>
            </nav>
        </div>

        <form method="POST" action="{{ route('purchase-order.update', $po->id) }}">
            @csrf
            @method('PUT')

            <div class="card bg-white p-20 rounded-10 border mb-4">
                <h3 class="mb-20">Purchase Order Information</h3>

                <div class="row">

                    <!-- <div class="col-lg-4 mb-20">
                                                        <label class="label fs-16 mb-2">PO Number <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="po_number"
                                                            value="{{ old('po_number', $po->po_number) }}" required>
                                                    </div> -->

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Vendor <span class="text-danger">*</span></label>
                        <select class="form-control" name="vendor_id" required>
                            @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}"
                                    {{ old('vendor_id', $po->vendor_id) == $vendor->id ? 'selected' : '' }}>
                                    {{ $vendor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Broker </label>
                        <select class="form-control" name="broker_id">
                            <option value="">Select</option>
                            @foreach ($brokers as $broker)
                                <option value="{{ $broker->id }}"
                                    {{ old('broker_id', $po->broker_id) == $broker->id ? 'selected' : '' }}>
                                    {{ $broker->broker_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Branch <span class="text-danger">*</span></label>
                        <select class="form-control" name="branch_id" required>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branch_id', $po->branch_id) == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->branch_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Currency <span class="text-danger">*</span></label>
                        <select class="form-control" name="currency_id" required>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->code }}"
                                    {{ old('currency_id', $purchaseOrder->currency_id ?? 'INR') == $currency->code ? 'selected' : '' }}>
                                    {{ $currency->currency }} ({{ $currency->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Purchase Order Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="po_date"
                            value="{{ old('po_date', $po->po_date) }}" required>
                    </div>
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Delivery Terms</label>

                        <select name="delivery_term" id="delivery_term" class="form-control">
                            <option value="">Select Delivery Term</option>

                            <option value="Immediate"
                                {{ old('delivery_term', $po->delivery_term) == 'Immediate' ? 'selected' : '' }}>
                                Immediate
                            </option>

                            <option value="Within 7 Days"
                                {{ old('delivery_term', $po->delivery_term) == 'Within 7 Days' ? 'selected' : '' }}>
                                Within 7 Days
                            </option>

                            <option value="Within 15 Days"
                                {{ old('delivery_term', $po->delivery_term) == 'Within 15 Days' ? 'selected' : '' }}>
                                Within 15 Days
                            </option>

                            <option value="Within 30 Days"
                                {{ old('delivery_term', $po->delivery_term) == 'Within 30 Days' ? 'selected' : '' }}>
                                Within 30 Days
                            </option>
                        </select>
                    </div>


                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Delivery Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="delivery_date"
                            value="{{ old('delivery_date', $po->delivery_date) }}" required>
                    </div>
                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Payment Term <span class="text-danger">*</span></label>
                        <select class="form-control" name="payment_term_id" required>
                            <option value="">Select Payment Term</option>
                            @foreach ($paymentTerms as $term)
                                <option value="{{ $term->id }}"
                                    {{ old('payment_term_id', $po->payment_term_id) == $term->id ? 'selected' : '' }}>
                                    {{ $term->days }} {{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
            </div>

            {{-- ITEMS --}}
            <div class="card bg-white p-20 rounded-10 border mb-4">
                <h4>Purchase Order Items</h4>

                <table class="table table-bordered" id="poItemsTable">
                    <thead>
                        <tr>
                            <th>Raw Material</th>
                            <th>Qty</th>
                            <th>UOM</th>
                            <th>Price</th>
                            <th>Discount</th>
                            <th>GST</th>
                            <th>Total</th>
                            <th width="60">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($po->details as $i => $item)
                            <tr>
                                <!-- <td>
                                                        <select name="items[{{ $i }}][raw_material_id]" class="form-control">
                                                            @foreach ($rawMaterials as $rm)
    <option value="{{ $rm->id }}" data-uom="{{ $rm->uom_id }}"
                                                                    {{ $item->raw_material_id == $rm->id ? 'selected' : '' }}>
                                                                    {{ $rm->name }}
                                                                </option>
    @endforeach
                                                        </select>
                                                    </td> -->

                                <td>
                                    <input type="hidden" name="items[{{ $i }}][raw_material_id]"
                                        class="raw-material-id" value="{{ $item->raw_material_id }}">

                                    <input type="text" class="form-control raw-material-name"
                                        value="{{ $item->rawMaterial->name ?? '' }}" placeholder="Select Raw Material"
                                        readonly style="cursor:pointer" onclick="openRawMaterialPopup(this)" required>
                                </td>

                                <td>
                                    <input type="number" class="form-control qty"
                                        name="items[{{ $i }}][quantity_ordered]"
                                        value="{{ $item->quantity_ordered }}">
                                </td>

                                <td>
                                    <select class="form-control" name="items[{{ $i }}][uom_id]">
                                        <option value="" disabled selected>
                                            Select UOM
                                        </option>

                                        @foreach ($uoms as $uom)
                                            <option value="{{ $uom->id }}"
                                                {{ $item->uom_id == $uom->id ? 'selected' : '' }}>
                                                {{ $uom->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>


                                <td>
                                    <input type="number" class="form-control price"
                                        name="items[{{ $i }}][unit_price]" value="{{ $item->unit_price }}">
                                </td>

                                <td>
                                    <input type="number" class="form-control discount" placeholder="Discount %"
                                        name="items[{{ $i }}][discount]"
                                        value="{{ $item->discount_percent }}">
                                </td>

                                <td>
                                    <select name="items[{{ $i }}][gst_percent]" class="form-control gst">
                                        @foreach ($gstRates as $gst)
                                            <option value="{{ $gst->gst_rate_name }}"
                                                {{ $item->gst_percent == $gst->gst_rate_name ? 'selected' : '' }}>
                                                {{ $gst->gst_rate_name }}%
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="number" class="form-control total" value="{{ $item->total_price }}"
                                        readonly>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-end">
                    <button type="button" id="addRow" class="btn btn-primary text-white mt-3">+ Add Item</button>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6 offset-md-6">
                    <table class="table table-bordered">
                        <tr>
                            <th class="text-end">Sub Total</th>
                            <td>
                                <input type="text" id="subTotal" class="form-control text-end" readonly
                                    value="{{ $po->total_amount }}">
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end">Total Discount</th>
                            <td>
                                <input type="text" id="totalDiscount" class="form-control text-end" readonly
                                    value="{{ $po->discount_amount }}">
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end">Total GST</th>
                            <td>
                                <input type="text" id="totalGST" class="form-control text-end" readonly
                                    value="{{ $po->tax_amount }}">
                            </td>
                        </tr>
                        <tr>
                            <th class="text-end fw-bold">Grand Total</th>
                            <td>
                                <input type="text" id="grandTotal" class="form-control text-end fw-bold" readonly
                                    value="{{ $po->net_amount }}">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary text-white">Update Purchase Order</button>
                <a href="{{ route('purchase-order.index') }}" class="btn btn-danger text-white">Cancel</a>
            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const rawMaterials = @json($rawMaterials);
        let activeRowInput = null;

        function openRawMaterialPopup(input) {
            activeRowInput = input;

            let listHtml = rawMaterials.map(rm => `
            <div class="rm-item"
                data-id="${rm.id}"
                data-name="${rm.name}"
                data-uom="${rm.uom_id}"
                style="padding:8px;border-bottom:1px solid #eee;cursor:pointer">
              <div>
                <strong>${rm.name}</strong><br>
                <small>
                    ${[rm.category?.name, rm.sub_category?.name].filter(Boolean).join(' | ') }
                </small>
            </div>  
            </div>
        `).join('');

            Swal.fire({
                title: 'Select Raw Material',
                html: `
                <input type="text" id="rmSearch" class="form-control mb-2" placeholder="Search raw material...">
                <div id="rmList" style="max-height:300px;overflow:auto;text-align:left">
                    ${listHtml}
                </div>
            `,
                showConfirmButton: false,
                width: 500,
                didOpen: () => {
                    const searchInput = document.getElementById('rmSearch');
                    const items = document.querySelectorAll('.rm-item');

                    searchInput.addEventListener('keyup', function() {
                        let value = this.value.toLowerCase();
                        items.forEach(item => {
                            item.style.display = item.innerText.toLowerCase().includes(value) ?
                                'block' :
                                'none';
                        });
                    });

                    items.forEach(item => {
                        item.addEventListener('click', function() {
                            selectRawMaterial(
                                this.dataset.id,
                                this.dataset.name,
                                this.dataset.uom
                            );
                        });
                    });
                }
            });
        }

        function selectRawMaterial(id, name, uomId) {
            let row = activeRowInput.closest('tr');

            row.querySelector('.raw-material-id').value = id;
            row.querySelector('.raw-material-name').value = name;

            let uomSelect = row.querySelector('[name*="[uom_id]"]');
            if (uomSelect) {
                uomSelect.value = uomId;
            }

            Swal.close();
        }
    </script>

    <script>
        document.addEventListener('change', function(e) {
            if (e.target.matches('[name*="[raw_material_id]"]')) {
                let selected = e.target.options[e.target.selectedIndex];
                let uomId = selected.getAttribute('data-uom');
                let row = e.target.closest('tr');
                if (uomId && row) {
                    let uomSelect = row.querySelector('[name*="[uom_id]"]');
                    if (uomSelect) uomSelect.value = uomId;
                }
            }
        });
    </script>

    <script>
        let rowIndex = {{ $po->details->count() }};

        document.getElementById('addRow').addEventListener('click', function() {
            let tbody = document.querySelector('#poItemsTable tbody');
            let firstRow = tbody.rows[0];
            let newRow = firstRow.cloneNode(true);

            newRow.querySelectorAll('input, select').forEach(el => {
                el.value = '';
                el.name = el.name.replace(/\[\d+\]/, '[' + rowIndex + ']');
            });

            newRow.querySelector('.gst').value = '0';
            newRow.querySelector('.total').value = '0.00';

            tbody.appendChild(newRow);
            rowIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeRow')) {
                let rows = document.querySelectorAll('#poItemsTable tbody tr');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                    calculateGrandTotal();
                }
            }
        });
    </script>

    <script>
        document.addEventListener('input', function(e) {
            let row = e.target.closest('tr');
            if (!row) return;

            let qty = parseFloat(row.querySelector('.qty')?.value) || 0;
            let price = parseFloat(row.querySelector('.price')?.value) || 0;
            let discountPercent = parseFloat(row.querySelector('.discount')?.value) || 0;
            let gstPercent = parseFloat(row.querySelector('.gst')?.value) || 0;

            if (discountPercent > 100) {
                discountPercent = 100;
                row.querySelector('.discount').value = 100;
            }

            let gross = qty * price;
            let discountAmount = (gross * discountPercent) / 100;
            let taxable = gross - discountAmount;
            let gstAmount = (taxable * gstPercent) / 100;
            let total = taxable + gstAmount;

            row.querySelector('.total').value = total.toFixed(2);

            calculateGrandTotal();
        });
    </script>

    <script>
        function calculateGrandTotal() {
            let subTotal = 0;
            let totalDiscount = 0;
            let totalGST = 0;
            let grandTotal = 0;

            document.querySelectorAll('#poItemsTable tbody tr').forEach(row => {

                let qty = parseFloat(row.querySelector('.qty')?.value) || 0;
                let price = parseFloat(row.querySelector('.price')?.value) || 0;
                let discountPercent = parseFloat(row.querySelector('.discount')?.value) || 0;
                let gstPercent = parseFloat(row.querySelector('.gst')?.value) || 0;

                let rowGross = qty * price;
                let discountAmount = (rowGross * discountPercent) / 100;
                let taxable = rowGross - discountAmount;
                let gstAmount = (taxable * gstPercent) / 100;
                let rowTotal = taxable + gstAmount;

                subTotal += rowGross;
                totalDiscount += discountAmount;
                totalGST += gstAmount;
                grandTotal += rowTotal;
            });

            document.getElementById('subTotal').value = subTotal.toFixed(2);
            document.getElementById('totalDiscount').value = totalDiscount.toFixed(2);
            document.getElementById('totalGST').value = totalGST.toFixed(2);
            document.getElementById('grandTotal').value = grandTotal.toFixed(2);
        }
    </script>
    <script>
        function calculateDeliveryDate() {
            let term = $('#delivery_term').val();
            let poDateVal = $('input[name="po_date"]').val();
            let dateInput = $('input[name="delivery_date"]');

            if (!poDateVal || !term) {
                return;
            }
            dateInput.attr('min', poDateVal);
            if (!term) {
                return;
            }
            let baseDate = new Date(poDateVal);

            if (term === 'Immediate') {
                dateInput.val(poDateVal);
            } else if (term === 'Within 7 Days') {
                baseDate.setDate(baseDate.getDate() + 7);
                dateInput.val(baseDate.toISOString().split('T')[0]);
            } else if (term === 'Within 15 Days') {
                baseDate.setDate(baseDate.getDate() + 15);
                dateInput.val(baseDate.toISOString().split('T')[0]);
            } else if (term === 'Within 30 Days') {
                baseDate.setDate(baseDate.getDate() + 30);
                dateInput.val(baseDate.toISOString().split('T')[0]);
            }
        }

        // Delivery term change
        $('#delivery_term').on('change', function() {
            calculateDeliveryDate();
        });

        // PO date change
        $('input[name="po_date"]').on('change', function() {
            calculateDeliveryDate();
        });

        // ✅ EDIT PAGE LOAD KE TIME
        $(document).ready(function() {
            calculateDeliveryDate();
        });
    </script>
@endpush
