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

        <h3 class="mb-0">
            Raw Material Batch - {{ $rawMaterial->name }}
        </h3>

        <nav aria-label="breadcrumb">

            <ol class="breadcrumb align-items-center mb-0 lh-1">

                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('rawmaterial.index') }}">
                        Raw Material 
                    </a>
                </li>

                <li class="breadcrumb-item active"> 
                    Batch Management
                </li>

            </ol>

        </nav>

    </div>


    <div class="card bg-white rounded-10 border border-white mb-4">

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

                        @foreach ($batches as $month => $rows)
                        @php
                        $totalInward = $rows->sum('inward');
                        $totalOutward = $rows->sum('outward');
                        $balance = $rows->sum('balance');
                        @endphp
                        <tr class="month-row" data-month="{{ Str::slug($month) }}">
                            <td>
                                <strong>{{ $month }}</strong>
                            </td>
                            <td>{{ $totalInward }}</td>
                            <td>{{ $totalOutward }}</td>
                            <td>{{ $balance }}</td>
                            <td></td>
                        </tr>


                        @foreach ($rows as $batch)
                        <tr class="batch-row {{ Str::slug($month) }} d-none batch-detail-btn"
                            data-batch="{{ $batch->batch_no }}" data-qty="{{ $batch->inward }}"
                            data-product="{{ $batch->rawMaterial->name ?? '' }}"
                            data-created="{{ \Carbon\Carbon::parse($batch->created_at)->format('d F Y h:i A') }}">

                            <td style="padding-left:40px">

                                {{ $batch->batch_no }}

                            </td>
                            <td>{{ $batch->inward }}</td>
                            <td>{{ $batch->outward }}</td>
                            <td>{{ $batch->balance }}</td>
                            <td class="text-center">
                                <a href="{{ route('stock-ledger.index', ['raw_materail_batch_id' => $batch->id]) }}"
                                    title="View Stock Ledger">

                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">
                                        inventory_2
                                    </i>

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
@endsection


@push('scripts')
<script>
    $(document).on('click', '.month-row', function() {
        let month = $(this).data('month');
        $('.' + month).toggleClass('d-none');
    });


    $(document).ready(function() {
        $('#batchTable').DataTable({
            paging: true,
            searching: true,
            info: true,
            ordering: false,
            dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l>" +
                "<'col-sm-6 d-flex justify-content-end'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>"
        });

    });
</script>
@endpush