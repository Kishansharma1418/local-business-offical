<style>
    input.form-control.form-control-sm {
        height: 43px;
    }

    .month-row {
        background: #f5f5f5;
        font-weight: 600;
        cursor: pointer;
    }

    .batch-row {
        background: #fff;
    }
</style>

@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">

        <h3 class="mb-0">Raw Material Batch Management</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">

                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    <span class="text-secondary">Raw Material Batch</span>
                </li>

            </ol>
        </nav>

    </div>


    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="d-flex align-items-center flex-wrap gap-3 p-20">

            <div style="width:220px;">
                <label class="form-label mb-1">Financial Year</label>

                @php
                $currentYear = now()->year;
                $currentMonth = now()->month;

                // Current financial year calculate
                if ($currentMonth >= 4) {
                $fyStart = $currentYear;
                $fyEnd = $currentYear + 1;
                } else {
                $fyStart = $currentYear - 1;
                $fyEnd = $currentYear;
                }

                $startYear = $currentYear - 5;
                @endphp

                <select id="financial_year_filter" class="form-control">
                    <option value="">All Years</option>

                    @for ($year = $currentYear; $year >= $startYear; $year--)
                    @php
                    $optionStart = $year - 1;
                    $optionEnd = $year;
                    $value = $optionStart . '-' . $optionEnd;
                    $currentFY = $fyStart . '-' . $fyEnd;
                    @endphp

                    <option value="{{ $value }}"
                        {{ $value == $currentFY ? 'selected' : '' }}>
                        Apr {{ $optionStart }} - Mar {{ $optionEnd }}
                    </option>
                    @endfor
                </select>


            </div>

        </div>


        <div class="default-table-area mx-minus-1">

            <div class="table-responsive overflow-none">

                <table class="table" id="batchTable">

                    <thead>
                        <tr>
                            <th style="width:30%">Month / Batch</th>
                            <th>Inward</th>
                            <th>Outward</th>
                            <th>Balance</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($months as $month)
                        @php
                        $rows = $batches[$month] ?? collect();

                        $totalInward = $rows->sum('inward');
                        $totalOutward = $rows->sum('outward');
                        $balance = $rows->sum('balance');
                        @endphp

                        <tr class="month-row" data-month="{{ Str::slug($month) }}"
                            data-fy="{{ \Carbon\Carbon::parse($month)->month >= 4
                                        ? \Carbon\Carbon::parse($month)->year . '-' . (\Carbon\Carbon::parse($month)->year + 1)
                                        : \Carbon\Carbon::parse($month)->year - 1 . '-' . \Carbon\Carbon::parse($month)->year }}">

                            <td><strong>{{ $month }}</strong></td>
                            <td>{{ $totalInward }}</td>
                            <td>{{ $totalOutward }}</td>
                            <td>{{ $balance }}</td>
                            <td></td>

                        </tr>

                        @foreach ($rows as $batch)
                        <tr class="batch-row {{ Str::slug($month) }} d-none ">
                            <td style="padding-left:40px">
                                {{ $batch->batch_no }}
                                <br>
                                <small class="text-muted">
                                    Mfg: {{ formatDate($batch->PurchaseOrderDetail?->mfg_date) }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    Exp: {{ formatDate($batch->expiry_date) }}
                                </small>
                            </td>
                            <td>{{ $batch->inward }} {{ $batch->uom_name }}</td>
                            <td>{{ $batch->outward }} {{ $batch->uom_name }}</td>
                            <td>{{ $batch->balance }} {{ $batch->uom_name }}</td>
                            <td class="text-center">

                                <button class="btn btn-sm  batch-detail-btn "
                                    data-batch="{{ $batch->batch_no }}"
                                    data-qty="{{ $batch->inward }}"
                                    data-product="{{ $batch->rawMaterial->name ?? '' }}"
                                data-expiry_date="{{ formatDate($batch->expiry_date) }}"
                                    data-analytic_report_no="{{ $batch->analytic_report_no ?? '' }}"
                                    data-grn_no="{{ $batch->grn_no ?? '' }}"
                                    data-referance_no="{{ $batch->referance_no ?? '' }}"
                                    data-po_number="{{ $batch->purchaseOrder->po_number ?? '' }}"
                                    data-po_amount="{{ $batch->PurchaseOrderDetail->total_price ?? '' }}"
                                    data-mfg_date="{{ formatDate($batch->PurchaseOrderDetail->mfg_date ?? null) }}"
                                    data-uom="{{ $batch->uom_name }}"
                                   data-created="{{ formatDate($batch->created_at, 'd-m-Y h:i A') }}"
                                    title="View Details">
                                    <i class="ri-eye-line fs-16"></i>   
                                </button>

                                <a href="{{ route('stock-ledger.index', ['raw_materail_batch_id' => $batch->id]) }}"
                                    title="View Stock Ledger">
                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">inventory_2</i>
                                </a>

                            </td>

                        </tr>
                        @endforeach
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


{{-- Batch Details Modal --}}

<div class="modal fade" id="batchDetailModal">

    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Raw Material Batch Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <table class="table table-bordered">

                    <tr>
                        <th>Raw Material</th>
                        <td id="product_name"></td>
                    </tr>

                    <tr>
                        <th>Batch Number</th>
                        <td id="batch_number"></td>
                    </tr>

                    <tr>
                        <th>GRN Number</th>
                        <td id="grn_no"></td>
                    </tr>

                    <tr>
                        <th> Control Referance Number</th>
                        <td id="referance_no"></td>
                    </tr>
                    <tr>
                        <th>PO Number</th>
                        <td id="po_number"></td>
                    </tr>
                    <tr>
                        <th>Mrp</th>
                        <td id="po_amount"></td>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td id="available_qty"></td>
                    </tr>
                    <tr>
                        <th>Mfg Date</th>
                        <td id="mfg_date"></td>
                    </tr>
                    <tr>
                        <th>Expiry Date</th>
                        <td id="expiry_date"></td>
                    </tr>
                    <tr>
                        <th>Analytic Report No</th>
                        <td id="analytic_report_no"></td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td id="created_at"></td>
                    </tr>

                </table>

            </div>

        </div>
    </div>

</div>
@endsection



@push('scripts')
<script>
    $(document).on('click', '.month-row', function() {

        let month = $(this).data('month');

        $('.' + month).toggleClass('d-none');

    });

    $(document).ready(function() {

        $('#batchTable').DataTable({

            paging: false,
            searching: true,
            info: true,
            ordering: false,
            lengthChange: false,
            dom: "<'row mb-3'<'col-sm-6'><'col-sm-6 d-flex justify-content-end'f>>" +
                "<'row'<'col-sm-12'tr>>"

        });


        $(document).on('click', '.batch-detail-btn', function() {

            let batch = $(this).data('batch');
            let qty = $(this).data('qty');
            let product = $(this).data('product');
            let created = $(this).data('created');
            let expiry_date = $(this).data('expiry_date');
            let analytic_report_no = $(this).data('analytic_report_no');
            let grn_no = $(this).data('grn_no');
            let referance_no = $(this).data('referance_no');
            let po_number = $(this).data('po_number');
            let po_amount = $(this).data('po_amount');
            let mfg_date = $(this).data('mfg_date');
            let uom = $(this).data('uom');


            $('#batch_number').text(batch);
            $('#available_qty').text(qty + ' ' + uom);
            $('#expiry_date').text(expiry_date || 'N/A');
            $('#analytic_report_no').text(analytic_report_no || 'N/A');
            $('#grn_no').text(grn_no);
            $('#referance_no').text(referance_no);
            $('#po_number').text(po_number || 'N/A');
            $('#po_amount').text(po_amount || 'N/A');
            $('#mfg_date').text(mfg_date || 'N/A');
            $('#product_name').text(product);
            $('#created_at').text(created);

            var modal = new bootstrap.Modal(document.getElementById('batchDetailModal'));

            modal.show();

        });


        $('#financial_year_filter').on('change', function() {

            let fy = $(this).val();

            if (fy == '') {
                $('.month-row').show();
                $('.batch-row').hide();
                return;
            }

            $('.month-row').each(function() {

                if ($(this).data('fy') == fy) {
                    $(this).show();
                } else {
                    $(this).hide();
                }

            });

            $('.batch-row').hide();

        });

    });
</script>
@endpush