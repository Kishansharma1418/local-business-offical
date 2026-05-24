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

            <h3 class="mb-0">Batch Management</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">

                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        <span class="text-secondary">Batch Management</span>
                    </li>

                </ol>
            </nav>

        </div>


        <div class="card bg-white rounded-10 border border-white mb-4">


            <div class="d-flex align-items-center flex-wrap gap-3 p-20">

                <div style="width:220px; ">
                    <label class="form-label mb-1">Financial Year</label>

                    <select id="financial_year_filter" class="form-control">

                        <option value="">All Years</option>

                        @php
                            $currentYear = now()->year;
                            $startYear = $currentYear - 5; 
                        @endphp

                        @for ($year = $currentYear; $year >= $startYear; $year--)
                            <option value="{{ $year - 1 }}-{{ $year }}">
                                Apr {{ $year - 1 }} - Mar {{ $year }}
                            </option>
                        @endfor

                    </select>   

                </div>

            </div>

            <!-- Table -->
            <div class="default-table-area mx-minus-1">

                <div class="table-responsive overflow-none">

                    <table class="table" id="batchTable">

                        <thead>
                            <tr>
                                <th style="width:30%">Month / Batch</th>
                                <th>Inward</th>
                                <th>Outward</th>
                                <th>Balance</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($months as $month)
                                @php
                                    $rows = $batches[$month] ?? collect();

                                    $totalInward = $rows->sum('available_quantity');
                                    $totalOutward = 0;
                                    $balance = $totalInward;
                                @endphp

                                <tr class="month-row" data-month="{{ Str::slug($month) }}"
                                    data-fy="{{ \Carbon\Carbon::parse($month)->month >= 4
                                        ? \Carbon\Carbon::parse($month)->year . '-' . (\Carbon\Carbon::parse($month)->year + 1)
                                        : \Carbon\Carbon::parse($month)->year - 1 . '-' . \Carbon\Carbon::parse($month)->year }}">

                                    <td><strong>{{ $month }}</strong></td>
                                    <td>{{ $totalInward }}</td>
                                    <td>{{ $totalOutward }}</td>
                                    <td>{{ $balance }}</td>

                                </tr>

                                @foreach ($rows as $batch)
                                    <tr class="batch-row {{ Str::slug($month) }} d-none batch-detail-btn"
                                        data-batch="{{ $batch->batch_number }}"
                                        data-qty="{{ $batch->available_quantity }}"
                                        data-date="{{ $batch->manufacturing_date }}"
                                        data-product="{{ $batch->product->name ?? '' }}"
                                        data-expiry="{{ $batch->expiry_date ?? '' }}"
                                        data-created="{{ \Carbon\Carbon::parse($batch->created_at)->format('d F Y h:i A') }}">

                                        <td style="padding-left:40px">
                                            {{ $batch->batch_number }}
                                        </td>

                                        <td>{{ $batch->available_quantity }}</td>
                                        <td>0</td>
                                        <td>{{ $batch->available_quantity }}</td>

                                    </tr>
                                @endforeach
                            @endforeach

                        </tbody>

                    </table>
                    <div class="modal fade" id="batchDetailModal">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <div class="modal-header">
                                    <h5 class="modal-title">Batch Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>

                                <div class="modal-body">

                                    <table class="table table-bordered">

                                        <tr>
                                            <th>Product</th>
                                            <td id="product_name"></td>
                                        </tr>

                                        <tr>
                                            <th>Batch Number</th>
                                            <td id="batch_number"></td>
                                        </tr>

                                        <tr>
                                            <th>Available Quantity</th>
                                            <td id="available_qty"></td>
                                        </tr>

                                        <tr>
                                            <th>Manufacturing Date</th>
                                            <td id="manufacturing_date"></td>
                                        </tr>

                                        <tr>
                                            <th>Expiry Date</th>
                                            <td id="expiry_date"></td>
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


        // DataTable UI only

        $(document).ready(function() {
            $('#batchTable').DataTable({

                paging: true,
                searching: true,
                info: true,
                ordering: false,
                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l>" +
                    "<'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",


            });
            $(document).on('click', '.batch-detail-btn', function() {

                let batch = $(this).data('batch');
                let qty = $(this).data('qty');
                let date = $(this).data('date');
                let product = $(this).data('product');
                let expiry = $(this).data('expiry');
                let created = $(this).data('created');

                $('#batch_number').text(batch);
                $('#available_qty').text(qty);
                $('#manufacturing_date').text(date);
                $('#product_name').text(product);
                $('#expiry_date').text(expiry);
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
