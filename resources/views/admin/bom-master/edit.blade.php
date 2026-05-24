@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit BOM Master</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('bom-master.index') }}" class="text-decoration-none">BOM Master List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit BOM Master</li>
                </ol>
            </nav>
        </div>

        {{-- Validation Errors --}}
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
        <!--
            {{-- Success Message --}}
            @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif -->

        <form action="{{ route('bom-master.update', $bomMaster->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">BOM Master Information</h3>

                        <div class="row">

                            {{-- Product --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Product <span class="text-danger">*</span></label>
                                <select name="finished_good_id" class="form-select form-control" required>
                                    <opt ion value="">Select Product</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ old('finished_good_id', $bomMaster->finished_good_id) == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                </select>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2" >Product Type <span class="text-danger">*</span></label>
                                <select name="product_type" id="product_type" class="form-select form-control" required>
                                    <option value="">Select Product Type</option>
                                    @foreach ($productTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('product_type', $bomMaster->product_type) == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2" >Pack Config <span class="text-danger">*</span></label>
                                <select name="pack_config_id" id="pack_config_id" class="form-select form-control" required>
                                    <option value="">Select Pack Config</option>
                                    @foreach ($packConfig as $config)
                                        <option value="{{ $config->id }}"
                                            {{ old('pack_config_id', $bomMaster->pack_config_id) == $config->id ? 'selected' : '' }}>
                                            {{ $config->name }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>


                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">BOM Version <span class="text-danger">*</span></label>
                                <input type="text" name="bom_version" id="bom_version" readonly
                                    value="{{ old('bom_version', $bomMaster->bom_version) }}" class="form-control"
                                    placeholder="Enter BOM Version" required>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Branch</label>
                                <select name="branch_id" id="branch" class="form-control">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', $bomMaster->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                               <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2" id="batch_size_label">Batch Size <span class="text-danger">*</span></label>
                                <input type="text" name="batch_size" id="batch_size"
                                    value="{{ old('batch_size', $bomMaster->batch_size) }}" class="form-control"
                                    placeholder="Enter Batch Size" required>
                            </div>
                            <!-- 
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Batch Standard Type <span class="text-danger">*</span></label>
                                <select name="batch_uom" class="form-select form-control" required>
                                    <option value="">Select Standard Type</option>
                                    <option value="tablet" {{ old('batch_uom', $bomMaster->batch_uom) == 'tablet' ? 'selected' : ''  }}>Tablet</option>
                                
                                </select>
                            </div> -->

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Packing Type <span class="text-danger">*</span></label>
                                <select name="packing_type" class="form-select form-control" required>
                                    <option value="">Select Packing Type</option>
                                    <option value="strip"
                                        {{ old('packing_type', $bomMaster->packing_type) == 'strip' ? 'selected' : '' }}>
                                        Strip</option>
                                    <option value="bottle"
                                        {{ old('packing_type', $bomMaster->packing_type) == 'bottle' ? 'selected' : '' }}>
                                        Bottle</option>

                                </select>
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2" id="pack_size_label">Pack Size <span class="text-danger">*</span></label>
                                <input type="text" name="pack_size" id="pack_size"
                                    value="{{ old('pack_size', $bomMaster->pack_size) }}" class="form-control"
                                    placeholder="Enter Pack Size" required>
                            </div>


                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2" id="box_label">Box Size <span class="text-danger">*</span></label>
                                <input type="text" name="box_size" id="box_size"
                                    value="{{ old('box_size', $bomMaster->box_size) }}" class="form-control"
                                    placeholder="Enter Box Size" required>
                            </div>

                              <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2" id="quantity_label">
                                    No of Boxes <span class="text-danger">*</span>
                                </label>

                                <input type="number" step="0.01"
                                    name="no_of_boxes"
                                    value="{{ old('no_of_boxes', $bomMaster->no_of_boxes) }}"
                                    class="form-control"
                                    placeholder="Enter No of Boxes"
                                    readonly>

                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Bom Date <span class="text-danger">*</span></label>
                                <input type="date" name="bom_date"
                                    value="{{ old('bom_date', $bomMaster->bom_date) }}" class="form-control" required>
                            </div>

                            {{-- Remarks --}}
                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Remarks </label>
                                <textarea name="remarks" id="remarks" class="form-control" placeholder="Enter Remarks">{{ old('remarks', $bomMaster->remarks) }}
                                </textarea>
                            </div>

                            <hr>
                            <h5>BOM Items</h5>

                            <table class="table table-bordered" id="poItemsTable">
                                <thead>
                                    <tr>
                                        <th>Raw Material</th>
                                        <th>Location</th>
                                        <th>Qty</th>
                                        <th>UOM</th>
                                        <th>Overage %</th>
                                        <th width="60">X</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($bomMaster->items as $i => $item)
                                        <tr>
                                            <td>
                                                <input type="hidden" name="items[{{ $i }}][material_id]"
                                                    class="raw-material-id" value="{{ $item->material_id }}" required>

                                                <input type="text" class="form-control raw-material-name"
                                                    value="{{ $item->material->name ?? '' }}" readonly
                                                    onclick="openRawMaterialPopup(this)" required>
                                            </td>

                                            <td>
                                                <select name="items[{{ $i }}][warehouse_id]"
                                                    class="form-control" required>
                                                    <option value="">Select</option>
                                                    @foreach ($warehouses as $w)
                                                        <option value="{{ $w->id }}"
                                                            {{ $item->warehouse_id == $w->id ? 'selected' : '' }}>
                                                            {{ $w->warehouse_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number" name="items[{{ $i }}][quantity]"
                                                    class="form-control" step="0.001" value="{{ $item->quantity }}"
                                                    required>
                                            </td>

                                            <td>
                                                <select name="items[{{ $i }}][uom]" class="form-control"
                                                    required>
                                                    @foreach ($uoms as $u)
                                                        <option value="{{ $u->id }}"
                                                            {{ $item->uom == $u->name ? 'selected' : '' }}>
                                                            {{ strtoupper($u->name) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            <td>
                                                <input type="number" name="items[{{ $i }}][overage]"
                                                    class="form-control overage" value="{{ $item->overage }}"
                                                    step="0.01" min="0" max="100">
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm removeRow">X</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-end">

                                <button type="button" id="addRow" class="btn btn-primary text-white mt-2">+ Add
                                    Item</button>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary me-2 text-white">
                                    Update BOM
                                </button>
                                <a href="{{ route('bom-master.index') }}" class="btn btn-danger text-white">Cancel</a>
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
        document.addEventListener('DOMContentLoaded', function() {

            const form = document.querySelector('form.needs-validation');
            const submitBtn = form.querySelector('button[type="submit"]');

            form.addEventListener('submit', function(e) {

                let valid = true;
                let message = '';

                document.querySelectorAll('.raw-material-id').forEach((el, index) => {
                    if (!el.value) {
                        valid = false;
                        message += `Row ${index + 1}: Please select Raw Material<br>`;
                    }
                });

                // document.querySelectorAll('.overage').forEach((el, index) => {
                //     let val = parseFloat(el.value);
                //     if (isNaN(val) || val < 0 || val > 100) {
                //         valid = false;
                //         message += `Row ${index + 1}: Overage must be between 0 and 100<br>`;
                //     }
                // });

                if (!valid) {
                    e.preventDefault();
                    e.stopPropagation();

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        html: message
                    });

                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '+ Add BOM Master';

                    return false;
                }

                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Processing...';

            });

        });
    </script>
    <script>
        const rawMaterials = @json($rawMaterials);
        let activeRowInput = null;

        function openRawMaterialPopup(input) {
            activeRowInput = input;

            let listHtml = rawMaterials.map(rm => `
                <div class="rm-item"
                    data-id="${rm.id}"
                    data-name="${rm.name}"

                    style="padding:8px;border-bottom:1px solid #eee;cursor:pointer">
                    ${rm.name}
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

        function selectRawMaterial(id, name, uom) {
            let row = activeRowInput.closest('tr');

            row.querySelector('.raw-material-id').value = id;
            row.querySelector('.raw-material-name').value = name;
            // row.querySelector('.uom').value = uom;

            Swal.close();
        }
    </script>

    <script>
        // function calculateNoOfBoxes() {
        //     let packSize = parseInt(document.getElementById('pack_size').value) || 0;
        //     let boxSize = parseInt(document.getElementById('box_size').value) || 0;
        //     let batchSize = parseInt(document.getElementById('batch_size').value) || 0;

        //     if (packSize > 0 && boxSize > 0 && batchSize > 0) {
        //         let noOfBoxes = Math.ceil(batchSize / (packSize * boxSize));
        //         document.querySelector('[name="no_of_boxes"]').value = noOfBoxes;
        //     } else {
        //         document.querySelector('[name="no_of_boxes"]').value = '';
        //     }
        // }

        // ['pack_size', 'box_size', 'batch_size'].forEach(id => {
        //     document.getElementById(id).addEventListener('input', calculateNoOfBoxes);
        // });

         document.addEventListener("DOMContentLoaded", function () {

            function calculateNoOfBoxes() {

                let packSize  = parseFloat(document.getElementById('pack_size')?.value) || 0;
                let boxSize   = parseFloat(document.getElementById('box_size')?.value) || 0;
                let batchSize = parseFloat(document.getElementById('batch_size')?.value) || 0;

                let productTypeSelect = document.getElementById("product_type");
                if (!productTypeSelect) return;

                let selectedText = productTypeSelect.selectedOptions.length
                    ? productTypeSelect.selectedOptions[0].text.toLowerCase()
                    : '';

                let outputField = document.querySelector('[name="no_of_boxes"]');
                if (!outputField) return;

                if (packSize > 0 && boxSize > 0 && batchSize > 0) {

                    if (selectedText.includes("syrup") || selectedText.includes("vial")) {

                        let packSizeInLiter = packSize / 1000;
                        let totalLiterPerBox = packSizeInLiter * boxSize;

                        if (totalLiterPerBox > 0) {
                            outputField.value = Math.ceil(batchSize / totalLiterPerBox);
                        } else {
                            outputField.value = '';
                        }

                    } else {

                        let totalUnitsPerBox = packSize * boxSize;

                        if (totalUnitsPerBox > 0) {
                            outputField.value = Math.ceil(batchSize / totalUnitsPerBox);
                        } else {
                            outputField.value = '';
                        }
                    }

                } else {
                    outputField.value = '';
                }
            }

            ['pack_size', 'box_size', 'batch_size', 'product_type'].forEach(id => {
                let element = document.getElementById(id);
                if (element) {
                    element.addEventListener('input', calculateNoOfBoxes);
                    element.addEventListener('change', calculateNoOfBoxes);
                }
            });

        });
    </script>

    <script>
        document.addEventListener('change', function(e) {
            if (e.target.matches('[name*="[raw_material_id]"]')) {
                let selected = e.target.options[e.target.selectedIndex];
                // let uomId = selected.getAttribute('data-uom');
                // let row = e.target.closest('tr');
                // if (uomId) {
                //     row.querySelector('[name*="[batch_uom]"]').value = uomId;
                // }
            }
        });
    </script>

    <script>
        let rowIndex = {{ $bomMaster->items->count() }};

        document.getElementById('addRow').addEventListener('click', function() {
            let table = document.querySelector('#poItemsTable tbody');
            let newRow = table.rows[0].cloneNode(true);

            newRow.querySelectorAll('input, select').forEach(input => {
                input.value = '';
                input.name = input.name.replace(/\d+/, rowIndex);
            });

            table.appendChild(newRow);
            rowIndex++;
        });


        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeRow')) {
                let rows = document.querySelectorAll('#poItemsTable tbody tr');
                if (rows.length > 1) e.target.closest('tr').remove();
            }
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            const productTypeSelect = document.getElementById("product_type");
            const batchLabel = document.getElementById("batch_size_label");
            const packLabel = document.getElementById("pack_size_label");
            const boxLabel = document.getElementById("quantity_label");

            function updateLabels() {

                if (!productTypeSelect) return;

                let selectedText = productTypeSelect.options[productTypeSelect.selectedIndex]?.text.toLowerCase() || '';

                if (selectedText.includes("syrup") || selectedText.includes("vial")) {

                    batchLabel.innerHTML = 'Batch Size (Liters) <span class="text-danger">*</span>';
                    packLabel.innerHTML = 'Pack Size (ML) <span class="text-danger">*</span>';
                    boxLabel.innerHTML = 'No of Bottles <span class="text-danger">*</span>';

                } else if (selectedText.includes("tablet") || selectedText.includes("capsule")) {

                    batchLabel.innerHTML = 'Batch Size (Units) <span class="text-danger">*</span>';
                    packLabel.innerHTML = 'Pack Size (Units) <span class="text-danger">*</span>';
                    boxLabel.innerHTML = 'No of Boxes <span class="text-danger">*</span>';

                } else {

                    batchLabel.innerHTML = 'Batch Size <span class="text-danger">*</span>';
                    packLabel.innerHTML = 'Pack Size <span class="text-danger">*</span>';
                    boxLabel.innerHTML = 'No of Boxes <span class="text-danger">*</span>';
                }
            }

            productTypeSelect.addEventListener("change", updateLabels);

            updateLabels();
        });

       document.querySelector('[name="finished_good_id"]').addEventListener('change', function () {

    let productId = this.value;

    let lastVersions = @json(
        \App\Models\BomMaster::selectRaw('finished_good_id, MAX(CAST(bom_version as UNSIGNED)) as max_version')
        ->groupBy('finished_good_id')
        ->pluck('max_version', 'finished_good_id')
    );

    // ✅ SAME PRODUCT → keep old version
    if (productId == currentProductId) {
        document.getElementById('bom_version').value = currentVersion;
        return;
    }

    // ✅ NEW PRODUCT → generate new version
    let last = lastVersions[productId] || 0;
    let next = String(parseInt(last) + 1).padStart(2, '0');

    document.getElementById('bom_version').value = next;
});
    </script>
<script>
    const currentProductId = "{{ $bomMaster->finished_good_id }}";
    const currentVersion = "{{ $bomMaster->bom_version }}";
</script>
   
@endpush
