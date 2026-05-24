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

    .rm-error {
        border: 1px solid #dc3545 !important;
        background-color: #fff5f5;
    }

    .rm-error-text {
        color: #dc3545;
        font-size: 12px;
        margin-top: 2px;
    }
</style>
<div class="main-content-container overflow-hidden">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Add Purchase Order</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('purchase-order.index') }}"
                        class="d-flex align-items-center text-decoration-none">
                        <span class="text-body fs-14 hover">Purchase Order List</span>
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    <span class="text-secondary">Add Purchase Order</span>
                </li>
            </ol>
        </nav>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Success/Error Messages --}}
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form id="purchaseOrderForm" method="POST" action="{{ route('purchase-order.store') }}"
        enctype="multipart/form-data" class="needs-validation" novalidate>
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                    <h3 class="mb-20">Purchase Order Information</h3>
                    <div class="row">



                        <div class="col-lg-4 mb-20">
                            <label class="label fs-16 mb-2">Vendor <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2">
                                <div class="flex-grow-1 ">
                                    <select class="form-control select2" name="vendor_id" required>
                                        <option value="">Select Vendor</option>
                                        @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }} ({{ $vendor->code }}) ({{ $vendor->phone }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 mb-20">
                            <label class="label fs-16 mb-2">Broker</label>
                            <select class="form-control select2" name="broker_id">
                                <option value="">Select Broker</option>
                                @foreach ($brokers as $broker)
                                <option value="{{ $broker->id }}"
                                    {{ old('broker_id') == $broker->id ? 'selected' : '' }}>
                                    {{ $broker->broker_name }}
                                </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="col-lg-4 mb-20">
                            <label class="label fs-16 mb-2">Branch <span class="text-danger">*</span></label>
                            <select class="form-control select2" name="branch_id" required>
                                <option value="">Select Branch</option>
                                @foreach ($bramches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
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
                                    {{ old('currency_id', 'INR') === $currency->code ? 'selected' : '' }}>
                                    {{ $currency->currency }} ({{ $currency->code }})
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-4 mb-20">
                            <label class="label fs-16 mb-2">Purchase Order Date <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('po_date') is-invalid @enderror"
                                name="po_date" value="{{ old('po_date', date('Y-m-d')) }}" required>
                            @error('po_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="col-lg-4 mb-20">
                            <label class="label fs-16 mb-2">Delivery Terms</label>
                            <select name="delivery_term" id="delivery_term" class="form-control ">
                                <option value="">Select Delivery Term</option>
                                <option value="Immediate">Immediate</option>
                                <option value="Within 7 Days">Within 7 Days</option>
                                <option value="Within 15 Days">Within 15 Days</option>
                                <option value="Within 30 Days">Within 30 Days</option>

                            </select>


                        </div>
                        <div class="col-lg-4 mb-20">
                            <label class="label fs-16 mb-2">Delivery Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('delivery_date') is-invalid @enderror"
                                name="delivery_date" value="{{ old('delivery_date') }}" required>
                            @error('delivery_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="col-lg-4 mb-20">
                            <label class="label fs-16 mb-2">Payment Term <span class="text-danger">*</span></label>
                            <select class="form-control" name="payment_term_id" required>
                                <option value="">Select Payment Term</option>
                                @foreach ($paymentTerms as $term)
                                <option value="{{ $term->id }}"
                                    {{ old('payment_term_id') == $term->id ? 'selected' : '' }}>
                                    {{ $term->days }} {{ $term->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="card bg-white p-20 rounded-10 border mb-4">
                            <h4 class="mb-3">Purchase Order Items</h4>

                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="poItemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Raw Material</th>
                                            <th>Qty</th>
                                            <th>UOM</th>
                                            <th>Unit Price</th>
                                            <th>Discount %</th>
                                            <th>GST</th>
                                            <th>Total</th>
                                            <th width="60">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <input type="hidden" name="items[0][raw_material_id]"
                                                    class="raw-material-id" required>

                                                <input type="text" class="form-control raw-material-name"
                                                    placeholder="Select Raw Material" readonly style="cursor:pointer"
                                                    onclick="openRawMaterialPopup(this)">
                                            </td>
                                            <td>
                                                <input type="number" min="1" step="1"
                                                    name="items[0][quantity_ordered]" class="form-control qty"
                                                    required>

                                            </td>
                                            <td>
                                                <select name="items[0][uom_id]" class="form-control" required>
                                                    <option value="">Select Uom</option>
                                                    @foreach ($uoms as $uom)
                                                    <option value="{{ $uom->id }}">{{ $uom->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>

                                                <input type="number" step="0.01" min="0"
                                                    name="items[0][unit_price]" class="form-control price" required>
                                            </td>

                                            <td>
                                                <input type="number" step="0.01" min="0" max="100"
                                                    placeholder="Discount %" name="items[0][discount]"
                                                    class="form-control discount">


                                            </td>

                                            <td>
                                                <select name="items[0][gst_percent]" class="form-control gst"
                                                    required>
                                                    @foreach ($gstRates as $gst)
                                                    <option value="{{ $gst->gst_rate_name }}">
                                                        {{ $gst->gst_rate_name }}%
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number" step="0.01" name="items[0][total_price]"
                                                    class="form-control total" readonly>
                                            </td>

                                            <td>
                                                <button type="button"
                                                    class="btn btn-danger btn-sm removeRow">X</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="button" id="addRow" class="btn btn-primary text-white mt-3">+ Add
                                    Item</button>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6 offset-md-6">
                                <table class="table table-bordered">
                                    <tr>
                                        <th class="text-end">Sub Total</th>
                                        <td>
                                            <input type="text" id="subTotal" class="form-control text-end"
                                                readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">Total Discount</th>
                                        <td>
                                            <input type="text" id="totalDiscount" class="form-control text-end"
                                                readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-end">Total GST</th>
                                        <td>
                                            <input type="text" id="totalGST" class="form-control text-end"
                                                readonly>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-end fw-bold">Grand Total</th>
                                        <td>
                                            <input type="text" id="grandTotal"
                                                class="form-control text-end fw-bold" readonly>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-lg-12 mt-3">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                    Purchase Order</button>
                                <a href="{{ route('purchase-order.index') }}"
                                    class="btn btn-danger fw-normal text-white">Cancel</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        rawName = row.querySelector('.raw-material-name');
        rawName.classList.remove('rm-error');
        row.querySelectorAll('.rm-error-text').forEach(el => el.remove());

        Swal.close();
    }
</script>

<script>
    document.addEventListener('change', function(e) {
        if (e.target.matches('[name*="[raw_material_id]"]')) {
            let selected = e.target.options[e.target.selectedIndex];
            let uomId = selected.getAttribute('data-uom');
            let row = e.target.closest('tr');
            if (uomId) {
                row.querySelector('[name*="[uom_id]"]').value = uomId;
            }
        }
    });
</script>

<script>
    let rowIndex = 1;

    document.getElementById('addRow').addEventListener('click', function() {
        let table = document.querySelector('#poItemsTable tbody');
        let row = table.rows[0].cloneNode(true);
        row.querySelectorAll('input, select').forEach(input => {
            input.value = '';
            input.name = input.name.replace(/\d+/, rowIndex);
        });
        row.querySelector('.gst').value = '0';

        table.appendChild(row);
        rowIndex++;
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('removeRow')) {
            let rows = document.querySelectorAll('#poItemsTable tbody tr');
            if (rows.length > 1) e.target.closest('tr').remove();
        }
    });


    document.addEventListener('input', function(e) {
        let row = e.target.closest('tr');
        if (!row) return;

        let qtyInput = row.querySelector('.qty');
        let priceInput = row.querySelector('.price');
        let discountInput = row.querySelector('.discount');
        let gstSelect = row.querySelector('.gst');

        let qty = Math.floor(parseFloat(qtyInput?.value) || 0);
        let price = parseFloat(priceInput?.value) || 0;
        let discountPercent = parseFloat(discountInput?.value) || 0;
        let gstPercent = parseFloat(gstSelect?.value) || 0;

        if (qty < 0) qty = 0;
        if (price < 0) price = 0;
        if (discountPercent < 0) discountPercent = 0;

        if (discountPercent > 100) {
            discountPercent = 100;
            discountInput.value = 100;
        }


        qtyInput.value = qty;
        priceInput.value = price;
        discountInput.value = discountPercent;

        let subTotal = qty * price;

        let discountAmount = (subTotal * discountPercent) / 100;

        let gstAmount = (subTotal - discountAmount) * (gstPercent / 100);

        let total = subTotal - discountAmount + gstAmount;

        if (total < 0) total = 0;

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

    $('#delivery_term').on('change', function() {
        calculateDeliveryDate();
    });

    $('input[name="po_date"]').on('change', function() {
        calculateDeliveryDate();
    });

    $(document).ready(function() {
        calculateDeliveryDate();
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('purchaseOrderForm');

        form.addEventListener('submit', function(e) {
            let isValid = true;

            document.querySelectorAll('#poItemsTable tbody tr').forEach(row => {

                let rawId = row.querySelector('.raw-material-id');
                let rawName = row.querySelector('.raw-material-name');

                // clear old errors
                rawName.classList.remove('rm-error');
                row.querySelectorAll('.rm-error-text').forEach(el => el.remove());

                if (!rawId.value || rawId.value === '') {
                    isValid = false;

                    rawName.classList.add('rm-error');

                    let error = document.createElement('div');
                    error.className = 'rm-error-text';
                    // error.innerText = 'Please select raw material';

                    rawName.parentNode.appendChild(error);
                }
            });

            if (!isValid) {
                e.preventDefault();
                e.stopPropagation();

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please select raw material in all rows'
                });
            }
        });

    });
</script>
@endpush