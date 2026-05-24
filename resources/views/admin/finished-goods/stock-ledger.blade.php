<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Stock Ledger</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('finished-good.index') }}" class="text-decoration-none">
                        Finished Goods
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    <span class="text-secondary">Stock Ledger</span>
                </li>
            </ol>
        </nav>
    </div>

    <div class="card bg-white rounded-10 border border-white mb-4">

        <div class="default-table-area mx-minus-1">
            <div class="table-responsive overflow-none">
                <table class="table" id="stockLedgerTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Code</th>
                            <th>Batch No.</th>
                            <th>Type</th>
                            <th>Inward Qty</th>
                            <th>Outward Qty</th>
                            <th>Balance Qty</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {

        var table = $('#stockLedgerTable').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: true,
            scrollX: false,
            lengthMenu: [10, 20, 50, 100],

            language: {
                processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
            },

            ajax: {
                url: '{{ route("finished-good.stock-ledger") }}',
                data: function (d) {
                    d.product_id       = '{{ $selectedProductId ?? "" }}';
                    d.batch_id         = '{{ $selectedBatchId ?? "" }}';
                }
            },

            columns: [
                { data: 'DT_RowIndex',      name: 'DT_RowIndex',      orderable: false, searchable: false, className: 'text-center' },
                { data: 'date',             name: 'date',             className: 'text-center' },
                { data: 'product_name',     name: 'product_name' },
                { data: 'product_code',     name: 'product_code',     className: 'text-center' },
                { data: 'batch_number',     name: 'batch_number',     className: 'text-center' },
                { data: 'transaction_type', name: 'transaction_type', className: 'text-center' },
                { data: 'inward_qty',       name: 'inward_qty',       className: 'text-end text-success fw-bold' },
                { data: 'outward_qty',      name: 'outward_qty',      className: 'text-end text-danger fw-bold' },
                { data: 'balance_qty',      name: 'balance_qty',      className: 'text-end fw-bold' },
            ],

            dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
        });

    });
</script>
@endpush