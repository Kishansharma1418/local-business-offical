@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Add Production Batch</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('production-batch.index') }}" class="text-decoration-none">Production Batch List</a>
                    </li>
                    <li class="breadcrumb-item active">Add Production Batch</li>
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


        <form action="{{ route('production-batch.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 mb-4">
                        <h4 class="mb-3">Production Voucher</h4>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>BOM</label>
                                <select name="bom_master_id" class="form-control" required  id="bom_master_id">
                                    <option value="">Select BOM</option>
                                    @foreach($bomMasters as $bom)
                                        <option value="{{ $bom->id }}">
                                            {{$bom->finishedGood?->name}} ({{ $bom->bom_number }}) ({{ $bom->bom_version }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            {{-- MFG --}}
                            <div class="col-md-3 mb-3">
                                <label>MFG Date</label>
                                <input type="date" name="mfg_date"
                                    value="{{ date('Y-m-d') }}"
                                    class="form-control" required>
                            </div>

                            {{-- EXP --}}
                            <div class="col-md-3 mb-3">
                                <label>Expiry Date</label>
                                <input type="date" name="expiry_date"
                                    class="form-control" required>
                            </div>

                            {{-- Quantity --}}
                            <div class="col-md-3 mb-3">
                                <label>Production Qty (Tablet)</label>
                                <input type="number" step="0.01"
                                    name="quantity"
                                    class="form-control" required>
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
                                        <th>Warehouse</th>
                                        <th>Base Quantity</th>
                                        <th>Overage %</th>
                                        <th>Quantity</th>
                                        <th>UOM</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- AUTO FILLED --}}
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                Production Batch</button>
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
<script>
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
                                    @foreach($warehouses as $w)
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
</script>

@endpush

