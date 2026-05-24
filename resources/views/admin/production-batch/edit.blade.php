@extends('include.master')

@section('content')
    @php $isIssuance = $productionBatch->stage === 'ISSUANCE'; @endphp

    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Requisition Batch</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('production-batch.index') }}" class="text-decoration-none">Requisition Batch
                            List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Requisition Batch</li>
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


        <form action="{{ route('production-batch.update', $productionBatch->id) }}" method="POST" class="needs-validation"
            novalidate>
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 mb-4">
                        <h4 class="mb-3">Requisition Batch</h4>

                        <div class="row">

                            {{-- BOM --}}
                            <div class="col-md-4 mb-3">
                                <label class="label fs-16 mb-2">BOM <span class="text-danger">*</span></label>
                                <select name="bom_master_id" class="form-control" {{ $isIssuance ? 'disabled' : '' }}
                                    required id="bom_master_id">
                                    <option value="">Select BOM</option>
                                    @foreach ($bomMasters as $bom)
                                        <option value="{{ $bom->id }}"
                                            {{ $productionBatch->bom_master_id == $bom->id ? 'selected' : '' }}>
                                            {{ $bom->finishedGood?->name }} ({{ $bom->bom_number }})
                                            ({{ $bom->bom_version }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>





                            <!-- {{-- Quantity --}}
                                            <div class="col-md-3 mb-3">
                                                <label class="label fs-16 mb-2" id="batch_size_qty_label">Production Qty <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" {{ $isIssuance ? 'readonly' : '' }}
                                                    name="quantity"
                                                    value="{{ $productionBatch->quantity }}"
                                                    class="form-control" required>
                                            </div> -->

                            <div class="col-md-4 mb-3">
                                <label class="label fs-16 mb-2">No of Boxes <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" value="{{ $productionBatch->quantity }}"
                                    class="form-control" {{ $isIssuance ? 'readonly' : '' }} required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="label fs-16 mb-2">Pack Size</label>
                                <input type="text" name="pack_size" value="{{ $productionBatch->bomMaster->pack_size }}"
                                    class="form-control" readonly>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="label fs-16 mb-2">Box Size</label>
                                <input type="text" name="box_size" value="{{ $productionBatch->bomMaster->box_size }}"
                                    class="form-control" readonly>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="label fs-16 mb-2">Requisition Qty</label>
                                <input type="number" name="batch_size_qty" value="{{ $productionBatch->batch_size_qty }}"
                                    class="form-control" readonly>
                            </div>


                        </div>
                    </div>

                    <div class="card bg-white p-20 rounded-10 mt-4">
                        <h4 class="mb-3">Raw Material Consumption (Auto from BOM)</h4>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="itemsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>Raw Material</th>
                                        <th>Stock Status</th>
                                        <th>Warehouse</th>
                                        <th>Base Quantity</th>
                                        <th>Overage %</th>
                                        <th>Quantity</th>
                                        <th>UOM</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- @foreach ($productionBatch->items as $index => $item)
    <tr>
                                                    <td>
                                                        <input type="hidden" name="items[{{ $index }}][material_id]"
                                                            value="{{ $item->material_id }}">
                                                        <input type="text" class="form-control"
                                                            value="{{ $item->material?->name }}" readonly>
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-secondary">Checking...</span>
                                                    </td>

                                                    <td>
                                                        <select name="items[{{ $index }}][warehouse_id]"
                                                            class="form-control" required>
                                                            @foreach ($warehouses as $w)
    <option value="{{ $w->id }}"
                                                                    {{ $item->warehouse_id == $w->id ? 'selected' : '' }}>
                                                                    {{ $w->warehouse_name }}</option>
    @endforeach
                                                        </select>
                                                    </td>

                                                    <td>
                                                        <input type="number" value="{{ $item->base_quantity }}"
                                                            class="form-control" readonly>
                                                    </td>

                                                    <td>
                                                        <input type="number" value="{{ $item->overage_percent }}"
                                                            class="form-control" readonly>
                                                    </td>

                                                    <td>
                                                        <input type="number" name="items[{{ $index }}][quantity]"
                                                            value="{{ $item->final_quantity }}" class="form-control" readonly>
                                                    </td>

                                                    <td>
                                                        <input type="text" name="items[{{ $index }}][uom]"
                                                            value="{{ $item->uom }}" class="form-control" readonly>
                                                    </td>



                                                </tr>
    @endforeach -->

                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                Requisition Batch</button>
                            <a href="{{ route('production-batch.index') }}"
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
    @if (!$isIssuance)
        <script>
            document.getElementById('bom_master_id')
                ?.addEventListener('change', loadBomItems);

            document.querySelector('[name="quantity"]')
                ?.addEventListener('input', loadBomItems);
        </script>
    @endif

    <!-- <script>
        function loadBomItems() {

            let bomId = document.getElementById('bom_master_id').value;
            let productionQty = parseFloat(document.querySelector('[name="quantity"]').value);

            if (!bomId || !productionQty || productionQty <= 0) {
                document.querySelector('#itemsTable tbody').innerHTML = '';
                return;
            }

            fetch(`/bom/${bomId}/items`)
                .then(res => res.json())
                .then(data => {

                    let tbody = document.querySelector('#itemsTable tbody');
                    tbody.innerHTML = '';

                    let rowIndex = 0;

                    data.items.forEach(item => {

                        let baseQty = (item.quantity * productionQty) / data.batch_size;
                        let overagePercent = item.overage_percent ?? 0;
                        let finalQty = baseQty + (baseQty * overagePercent / 100);

                        tbody.innerHTML += `
                            <tr>
                                <td>
                                    <input type="hidden" name="items[${rowIndex}][material_id]" value="${item.material_id}">
                                    <input type="text" class="form-control" value="${item.material_name}" readonly>
                                </td>

                                <td>
                                    <select name="items[${rowIndex}][warehouse_id]" class="form-control" required>
                                        @foreach ($warehouses as $w)
                                            <option value="{{ $w->id }}">{{ $w->warehouse_name }}</option>
                                        @endforeach
                                    </select>
                                </td>

                                <td>
                                    <input type="number"
                                        value="${baseQty.toFixed(4)}"
                                        class="form-control"
                                        readonly>
                                </td>

                                <td>
                                    <input type="number"
                                        value="${overagePercent}"
                                        class="form-control"
                                        readonly>
                                </td>

                                <td>
                                    <input type="number"
                                        name="items[${rowIndex}][quantity]"
                                        value="${finalQty.toFixed(4)}"
                                        class="form-control"
                                        readonly>
                                </td>

                                <td>
                                input

                                <td>
                                    <input type="text"
                                        name="items[${rowIndex}][uom]"
                                        value="${item.uom}"
                                        class="form-control"
                                        readonly>
                                </td>
                            </tr>
                        `;

                        rowIndex++;
                    });
                });
        }

        document.getElementById('bom_master_id').addEventListener('change', loadBomItems);
        document.querySelector('[name="quantity"]').addEventListener('input', loadBomItems);
    </script> -->

    <script>
        let bomData = {};
        let hasStockIssue = false;

        function loadBomItems() {

            let bomId = document.getElementById('bom_master_id').value;
            if (!bomId) return;

            fetch(`/bom/${bomId}/items`)
                .then(res => res.json())
                .then(data => {

                    bomData = data;

                    document.querySelector('[name="pack_size"]').value = data.pack_size ?? '';
                    document.querySelector('[name="box_size"]').value = data.box_size ?? '';

                    autoCalculateProductionQty();
                });
        }

        function autoCalculateProductionQty() {

            let noOfBoxes = parseFloat(document.querySelector('[name="quantity"]').value);
            let boxSize = parseFloat(bomData.box_size);
            let packSize = parseFloat(bomData.pack_size);

            if (!noOfBoxes || !boxSize || !packSize) return;

            let productionQty = noOfBoxes * boxSize * packSize;

            document.querySelector('[name="batch_size_qty"]').value = productionQty;

            loadRawMaterial(productionQty);
        }

        function loadRawMaterial(productionQty) {

            let tbody = document.querySelector('#itemsTable tbody');
            tbody.innerHTML = '';
            hasStockIssue = false;

            let rowIndex = 0;

            bomData.items.forEach(item => {

                let baseQty = (item.quantity * productionQty) / bomData.batch_size;
                let overage = item.overage_percent ?? 0;
                let finalQty = baseQty + (baseQty * overage / 100);

                let row = document.createElement("tr");

                row.innerHTML = `
                    <td>
                        <input type="hidden" name="items[${rowIndex}][material_id]" value="${item.material_id}">
                        <input type="text" class="form-control" value="${item.material_name}" readonly>
                    </td>

                    <td class="stock-status text-center">
                        <span class="badge bg-secondary">Checking...</span>
                    </td>

                    <td>
                        <select name="items[${rowIndex}][warehouse_id]" class="form-control">
                            @foreach ($warehouses as $w)
                                <option value="{{ $w->id }}">{{ $w->warehouse_name }}</option>
                            @endforeach
                        </select>
                    </td>

                    <td><input class="form-control" value="${baseQty.toFixed(4)}" readonly></td>
                    <td><input class="form-control" value="${overage}" readonly></td>

                    <td>
                        <input class="form-control"
                            name="items[${rowIndex}][quantity]"
                            value="${finalQty.toFixed(4)}"
                            readonly>
                    </td>

                    <td><input class="form-control" value="${item.uom}" readonly></td>
                `;

                tbody.appendChild(row);

                checkStock(item.material_id, finalQty, row);

                rowIndex++;
            });
        }

        function checkStock(materialId, qty, rowElement) {

            fetch('/check-stock', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        material_id: materialId,
                        quantity: qty
                    })
                })
                .then(res => res.json())
                .then(data => {

                    let statusCell = rowElement.querySelector('.stock-status');

                    if (!data.status) {

                        hasStockIssue = true;

                        statusCell.innerHTML =
                            `<span class="badge bg-danger">${data.message}</span>`;

                    } else {

                        statusCell.innerHTML =
                            `<span class="badge bg-success">${data.message}</span>`;
                    }

                    toggleButton();
                });
        }

        function toggleButton() {

            let btn = document.querySelector('button[type="submit"]');

            if (hasStockIssue) {

                btn.classList.remove('btn-primary');
                btn.classList.add('btn-warning');
                btn.innerText = 'Save as Draft';

            } else {

                btn.classList.remove('btn-warning');
                btn.classList.add('btn-primary');
                btn.innerText = 'Update Requisition Batch';
            }
        }

        document.getElementById('bom_master_id')
            ?.addEventListener('change', loadBomItems);

        document.querySelector('[name="quantity"]')
            ?.addEventListener('input', autoCalculateProductionQty);

        document.addEventListener('DOMContentLoaded', function() {
            loadBomItems();
        });
    </script>


@endpush
