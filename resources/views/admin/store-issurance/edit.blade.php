@extends('include.master')

@section('content')

    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit  Store Issurance</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('store-issurance.index') }}" class="text-decoration-none">Store Issurance List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Store Issurance</li>
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

        <form action="{{ route('store-issurance.update', $productionBatch->id) }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 mb-4">
                        <h4 class="mb-3">Store Issurance Voucher</h4>

                        <div class="row">
         
                            {{-- BOM --}}
                            <div class="col-md-4 mb-3">
                                <label>BOM</label>
                                <select name="bom_master_id" class="form-control"  required  id="bom_master_id" 
                                Disabled>
                                    <option value="">Select BOM</option>
                                    @foreach($bomMasters as $bom)
                                        <option value="{{ $bom->id }}" {{ $productionBatch->bom_master_id == $bom->id ? 'selected' : '' }}>
                                            {{$bom->finishedGood?->name}} ({{ $bom->bom_number }}) ({{ $bom->bom_version }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            {{-- MFG --}}
                            <div class="col-md-3 mb-3">
                                <label>MFG Date</label>
                                <input type="date" name="mfg_date" readonly
                                    value="{{ $productionBatch->mfg_date }}"
                                    class="form-control" required>
                            </div>

                            {{-- EXP --}}
                            <div class="col-md-3 mb-3">
                                <label>Expiry Date</label>
                                <input type="date" name="expiry_date" readonly
                                    value="{{ $productionBatch->expiry_date }}"
                                    class="form-control" required>
                            </div>

                            {{-- Quantity --}}
                            <div class="col-md-3 mb-3">
                                <label>Production Qty (Tablet)</label>
                                <input type="number" step="0.01" readonly
                                    name="quantity"
                                    value="{{ $productionBatch->quantity }}"
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
                                        <th>Batch</th>
                                        <th>Warehouse</th>
                                        <th>Base Quantity</th>
                                        <th>Overage %</th>
                                        <th>Quantity</th>
                                        <th>UOM</th>
                                        <th>Weight By</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($productionBatch->items as $index => $item)
                                    <tr>
                                        <td>
                                            <input type="hidden" name="items[{{ $index }}][material_id]" value="{{ $item->material_id }}">
                                            <input type="text" class="form-control" value="{{ $item->material?->name }}" readonly>
                                        </td>

                                        <td>
                                    <button type="button"
                                        class="btn btn-sm btn-info batch-btn"
                                        data-row="{{ $index }}"
                                        onclick="openBatchPopup(
                                            {{ $item->material_id }},
                                            {{ $item->final_quantity }},
                                            {{ $index }}
                                        )">
                                        Select Batch
                                    </button>

                                            <input type="hidden"
                                                name="items[{{ $index }}][batch_data]"
                                                id="batch_data_{{ $index }}">
                                        </td>

                                        <td>
                                            <select name="items[{{ $index }}][warehouse_id]" class="form-control" required>
                                                @foreach($warehouses as $w)
                                                    <option value="{{ $w->id }}" {{ $item->warehouse_id == $w->id ? 'selected' : '' }}>{{ $w->warehouse_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>

                                        <td>
                                            <input type="number"
                                                   value="{{ $item->base_quantity }}"
                                                   class="form-control"
                                                   readonly>
                                        </td>

                                        <td>
                                            <input type="number"
                                                   value="{{ $item->overage_percent }}"
                                                   class="form-control"
                                                   readonly>
                                        </td>

                                        <td>
                                            <input type="number"
                                                   name="items[{{ $index }}][quantity]"
                                                   value="{{ $item->final_quantity }}"
                                                   class="form-control"
                                                   readonly>
                                        </td>

                                        <td>
                                             <select name="items[{{ $index }}][uom]" class="form-control" required>
                                                @foreach($uoms as $u)
                                                    <option value="{{ $w->id }}" {{ $item->uom == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                                @endforeach
                                            </select>
                                           
                                        </td>
                                   <td>
                                        <input type="text"
                                            name="items[{{ $index }}][weight_by]"
                                            value="{{ $item->weight_by }}"
                                            class="form-control"
                                           >
                                    </td>


                                    </tr>
                                    @endforeach
                                 
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-normal text-white">+ Update
                                Store Issurance</button>
                            <a href="{{ route('store-issurance.index') }}"
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
    </script>


<script>
//     function openBatchPopup(materialId, requiredQty, rowIndex) {
//     fetch(`/store/raw-batches?material_id=${materialId}`)
//         .then(res => res.json())
//         .then(batches => {

//             let html = `<div style="text-align:left">`;

//             batches.forEach(batch => {

//                 html += `
//                     <div style="margin-bottom:10px">
//                         <strong>Batch:</strong> ${batch.batch_no} <br>
//                         Exp: ${batch.expiry_date} <br>
//                         Available: ${batch.quantity} ${batch.uoms.name} <br>

//                         <input type="number"
//                             class="form-control batch-input"
//                             data-batch-id="${batch.id}"
//                             data-available="${batch.quantity}"
//                             placeholder="Enter qty"
//                             step="0.0001">
//                     </div>
//                     <hr>
//                 `;
//             });

//             html += `</div>`;

//             Swal.fire({
//                 title: `Required: ${requiredQty}`,
//                 html: html,
//                 width: 600,
//                 preConfirm: () => {

//                     let total = 0;
//                     let selected = [];

//                     document.querySelectorAll('.batch-input').forEach(input => {

//                         let qty = parseFloat(input.value) || 0;
//                         let available = parseFloat(input.dataset.available);

//                         if (qty > available) {
//                             Swal.showValidationMessage('Qty exceeds available stock');
//                             return false;
//                         }

//                         if (qty > 0) {
//                             total += qty;
//                             selected.push({
//                                 batch_id: input.dataset.batchId,
//                                 qty: qty
//                             });
//                         }
//                     });

//                     if (total != requiredQty) {
//                         Swal.showValidationMessage('Total qty must match required qty');
//                         return false;
//                     }

//                     return selected;
//                 }
//             }).then(result => {

//                 if (result.isConfirmed) {

//                         document.getElementById('batch_data_' + rowIndex)
//                             .value = JSON.stringify(result.value);

//                         // Make button green
//                         document.querySelector(`[data-row="${rowIndex}"]`)
//                             .classList.remove('btn-info');

//                         document.querySelector(`[data-row="${rowIndex}"]`)
//                             .classList.add('btn-success');

//                         document.querySelector(`[data-row="${rowIndex}"]`)
//                             .innerHTML = "Batch Selected ✓";
//                     }
//             });

//         });
// }

    // function openBatchPopup(materialId, requiredQty, rowIndex) {

    //     fetch(`/store/raw-batches?material_id=${materialId}`)
    //         .then(res => res.json())
    //         .then(batches => {

    //             let html = `
    //                 <div style="max-height:400px; overflow:auto;">
    //                 <table class="table table-bordered table-sm">
    //                     <thead style="position:sticky;top:0;background:#f8f9fa;">
    //                         <tr>
    //                             <th>Batch No</th>
    //                             <th>Expiry</th>
    //                             <th>Available</th>
    //                             <th>Enter Qty</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>
    //             `;

    //             batches.forEach(batch => {

    //                 let expDate = new Date(batch.expiry_date);
    //                 let today = new Date();
    //                 let isExpired = expDate < today;

    //                 html += `
    //                     <tr ${isExpired ? 'style="background:#ffe6e6;"' : ''}>
    //                         <td>
    //                             <strong>${batch.batch_no}</strong>
    //                         </td>
    //                         <td>
    //                             ${batch.expiry_date}
    //                         </td>
    //                         <td>
    //                             <span class="badge bg-info">
    //                                 ${batch.quantity} ${batch.uoms.name}
    //                             </span>
    //                         </td>
    //                         <td>
    //                             <input type="number"
    //                                 class="form-control batch-input"
    //                                 data-batch-id="${batch.id}"
    //                                 data-available="${batch.quantity}"
    //                                 placeholder="0"
    //                                 step="0.0001">
    //                         </td>
    //                     </tr>
    //                 `;
    //             });

    //             html += `
    //                     </tbody>
    //                 </table>
    //                 </div>

    //                 <div class="mt-3 text-end">
    //                     <strong>Total Selected: </strong>
    //                     <span id="selected_total">0</span> / ${requiredQty}
    //                 </div>
    //             `;

    //             Swal.fire({
    //                 title: `Select Batches (Required: ${requiredQty})`,
    //                 html: html,
    //                 width: 800,
    //                 showCancelButton: true,
    //                 confirmButtonText: 'Confirm Selection',
    //                 didOpen: () => {

    //                     document.querySelectorAll('.batch-input')
    //                         .forEach(input => {

    //                             input.addEventListener('input', function () {

    //                                 let total = 0;

    //                                 document.querySelectorAll('.batch-input')
    //                                     .forEach(i => {
    //                                         total += parseFloat(i.value) || 0;
    //                                     });

    //                                 document.getElementById('selected_total')
    //                                     .innerText = total.toFixed(0);
    //                             });
    //                         });
    //                 },
    //                 preConfirm: () => {

    //                     let total = 0;
    //                     let selected = [];

    //                     document.querySelectorAll('.batch-input')
    //                         .forEach(input => {

    //                             let qty = parseFloat(input.value) || 0;
    //                             let available = parseFloat(input.dataset.available);

    //                             if (qty > available) {
    //                                 Swal.showValidationMessage('Qty exceeds available stock');
    //                                 return false;
    //                             }

    //                             if (qty > 0) {
    //                                 total += qty;
    //                                 selected.push({
    //                                     batch_id: input.dataset.batchId,
    //                                     qty: qty
    //                                 });
    //                             }
    //                         });

    //                     if (parseFloat(total.toFixed(4)) !== parseFloat(requiredQty.toFixed(4))) {
    //                         Swal.showValidationMessage('Total must match required quantity');
    //                         return false;
    //                     }

    //                     return selected;
    //                 }
    //             }).then(result => {

    //                 if (result.isConfirmed) {

    //                     document.getElementById('batch_data_' + rowIndex)
    //                         .value = JSON.stringify(result.value);

    //                     let btn = document.querySelector(`[data-row="${rowIndex}"]`);

    //                     btn.classList.remove('btn-info');
    //                     btn.classList.add('btn-success');
    //                     btn.innerHTML = "Batch Selected ✓";
    //                 }
    //             });
    //         });
    // }

    function openBatchPopup(materialId, requiredQty, rowIndex) {

    fetch(`/store/raw-batches?material_id=${materialId}`)
        .then(res => res.json())
        .then(batches => {

            batches.sort((a, b) => new Date(a.expiry_date) - new Date(b.expiry_date));

            let html = `
                <div class="mb-2 text-start">
                    <button type="button" class="btn btn-sm btn-warning" id="fefo_btn">
                        ⚡ Auto FEFO Allocate
                    </button>
                </div>

                <div style="max-height:350px; overflow:auto;">
                <table class="table table-bordered table-sm">
                    <thead style="position:sticky;top:0;background:#f8f9fa;">
                        <tr>
                            <th>Batch</th>
                            <th>Expiry</th>
                            <th>Available</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            let today = new Date();

            batches.forEach(batch => {

                let expDate = new Date(batch.expiry_date);
                let diffMonths = (expDate - today) / (1000 * 60 * 60 * 24 * 30);

                let rowStyle = '';
                let disabled = '';

                if (expDate < today) {
                    rowStyle = 'background:#ffe6e6;';
                    disabled = 'disabled';
                } else if (diffMonths < 3) {
                    rowStyle = 'background:#fff3cd;';
                }

                html += `
                    <tr style="${rowStyle}">
                        <td><strong>${batch.batch_no}</strong></td>
                        <td>${batch.expiry_date}</td>
                        <td>
                            <span class="badge bg-info">
                                ${batch.quantity} ${batch.uoms.name}
                            </span>
                        </td>
                        <td>
                            <input type="number"
                                class="form-control batch-input"
                                data-batch-id="${batch.id}"
                                data-available="${batch.quantity}"
                                value="0"
                                step="0.0001"
                                ${disabled}>
                        </td>
                    </tr>
                `;
            });

            html += `
                    </tbody>
                </table>
                </div>

                <div class="mt-3">
                    <div class="progress" style="height:20px;">
                        <div id="qty_progress"
                            class="progress-bar bg-success"
                            style="width:0%">
                        </div>
                    </div>

                    <div class="mt-2 text-end">
                        <strong>
                            Selected:
                            <span id="selected_total">0</span>
                            /
                            ${requiredQty}
                        </strong>
                    </div>
                </div>
            `;

            Swal.fire({
                title: 'Batch Allocation',
                html: html,
                width: 900,
                showCancelButton: true,
                confirmButtonText: 'Confirm Allocation',

                didOpen: () => {

                    const updateTotals = () => {

                        let total = 0;

                        document.querySelectorAll('.batch-input')
                            .forEach(input => {
                                total += parseFloat(input.value) || 0;
                            });

                        document.getElementById('selected_total')
                            .innerText = total.toFixed(0);

                        let percent = (total / requiredQty) * 100;
                        percent = percent > 100 ? 100 : percent;

                        document.getElementById('qty_progress')
                            .style.width = percent + "%";
                    };

                    document.querySelectorAll('.batch-input')
                        .forEach(input => {

                            input.addEventListener('input', function () {

                                let available = parseFloat(this.dataset.available);
                                let val = parseFloat(this.value) || 0;

                                if (val > available) {
                                    this.value = available;
                                }

                                updateTotals();
                            });
                        });

                    document.getElementById('fefo_btn')
                        .addEventListener('click', function () {

                            let remaining = requiredQty;

                            document.querySelectorAll('.batch-input')
                                .forEach(input => input.value = 0);

                            document.querySelectorAll('.batch-input')
                                .forEach(input => {

                                    if (remaining <= 0) return;

                                    let available = parseFloat(input.dataset.available);

                                    if (available >= remaining) {
                                        input.value = remaining;
                                        remaining = 0;
                                    } else {
                                        input.value = available;
                                        remaining -= available;
                                    }
                                });

                            updateTotals();
                        });
                },

                preConfirm: () => {

                    let total = 0;
                    let selected = [];

                    document.querySelectorAll('.batch-input')
                        .forEach(input => {

                            let qty = parseFloat(input.value) || 0;

                            if (qty > 0) {
                                total += qty;
                                selected.push({
                                    batch_id: input.dataset.batchId,
                                    qty: qty
                                });
                            }
                        });

                    if (parseFloat(total.toFixed(0)) !== parseFloat(requiredQty.toFixed(0))) {
                        Swal.showValidationMessage('Total must match required quantity');
                        return false;
                    }

                    return selected;
                }
            }).then(result => {

                if (result.isConfirmed) {

                    document.getElementById('batch_data_' + rowIndex)
                        .value = JSON.stringify(result.value);

                    let btn = document.querySelector(`[data-row="${rowIndex}"]`);

                    btn.classList.remove('btn-info');
                    btn.classList.add('btn-success');
                    btn.innerHTML = "Allocated ✓";
                }
            });
        });
}
</script>

@endpush

