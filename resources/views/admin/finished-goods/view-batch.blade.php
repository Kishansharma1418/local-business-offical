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
            Batch Management - {{ $product->name }}
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
                    <a href="{{ route('finished-good.index') }}">
                        Finished Goods
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    Batch Management
                </li>
            </ol>
        </nav>
    </div>


    <div class="card bg-white rounded-10 border border-white mb-4">
        <div class="d-flex align-items-center flex-wrap gap-3 p-20">
            <div style="width:220px; ">
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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($months as $month)
                        @php
                        $rows = $batches[$month] ?? collect();
                        $totalInward = $rows->sum('total_inward'); // ✅
                        $totalOutward = $rows->sum('total_outward'); // ✅
                        $balance = $totalInward - $totalOutward;
                        @endphp


                        <tr class="month-row" data-month="{{ Str::slug($month) }}"
                            data-fy="{{ \Carbon\Carbon::parse($month)->month >= 4
                                        ? \Carbon\Carbon::parse($month)->year . '-' . (\Carbon\Carbon::parse($month)->year + 1)
                                        : \Carbon\Carbon::parse($month)->year - 1 . '-' . \Carbon\Carbon::parse($month)->year }}">
                            <td>
                                <strong>{{ $month }}</strong>
                            </td>
                            <td>{{ $totalInward }}</td>
                            <td>{{ $totalOutward }}</td>
                            <td>{{ $balance }}</td>
                            <td></td>

                        </tr>

                        @foreach ($rows as $batch)
                        <tr class="batch-row {{ Str::slug($month) }} d-none "
                            data-batch="{{ $batch->batch_number }}"
                            data-qty="{{ $batch->available_quantity }}"
                            data-date="{{ $batch->manufacturing_date }}"
                            data-product="{{ $batch->product->name ?? '' }}"
                            data-expiry="{{ $batch->expiry_date ?? '' }}"
                            data-mrp="{{$batch->mrp ?? ''}}"
                            data-created="{{ \Carbon\Carbon::parse($batch->created_at)->format('d F Y h:i A') }}">
                            <td style="padding-left:40px">
                                <div>{{ $batch->batch_number }}</div>
                                <small class="text-muted">
                                    MFG: {{ formatDate($batch->manufacturing_date) }}<br>
                                    EXP: {{ formatDate($batch->expiry_date) }}
                                </small>
                            </td>
                            <td>{{ $batch->total_inward }}</td> {{-- ✅ Inward --}}
                            <td>{{ $batch->total_outward }}</td> {{-- ✅ Outward --}}
                            <td>{{ $batch->ledger_balance }}</td>
                            <td>
                                <a href="{{ route('finished-good.stock-ledger') }}?product_id={{ encrypt($product->id) }}&batch_id={{ encrypt($batch->id) }}"
                                    title="Ledger">
                                    <i class="ri-book-line"></i>
                                </a>
                                <button type="button"
                                    class="btn btn-sm  batch-detail-btn py-0 px-2"
                                    title="View Details"
                                    data-batch="{{ $batch->batch_number }}"
                                    data-qty="{{ $batch->available_quantity }}"
                                    data-date="{{ formatDate($batch->manufacturing_date) }}"
                                    data-product="{{ $batch->product->name ?? '' }}"
                                    data-expiry="{{ formatDate($batch->expiry_date) }}"
                                    data-mrp="{{ $batch->mrp ?? '' }}"
                                    data-created="{{ formatDate($batch->created_at, 'd-m-Y h:i A') }}">
                                    <i class="ri-eye-line"></i>
                                </button>
                            </td>
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
                                        <th>Mrp</th>
                                        <td id="mrp"></td>
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


        $(document).on('click', '.batch-detail-btn', function(e) {
            e.stopPropagation();
            let batch = $(this).data('batch');
            let qty = $(this).data('qty');
            let date = $(this).data('date');
            let product = $(this).data('product');
            let expiry = $(this).data('expiry');
            let created = $(this).data('created');
            let mrp = $(this).data('mrp');


            $('#batch_number').text(batch);
            $('#available_qty').text(qty);
            $('#manufacturing_date').text(date);
            $('#product_name').text(product);
            $('#expiry_date').text(expiry);
            $('#mrp').text(mrp);
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