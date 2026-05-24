<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Stock Ledger List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1"> 
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                            <span class="text-secondary">Stock Ledger List</span>
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
                                <th>Date</th>
                                <th>Raw Material</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th class="text-center">Approved By</th>

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
        $(document).ready(function() {

            var dataTable = $('#stockLedgerTable').DataTable({
                processing: true,
                serverSide: true,
                autoWidth: true,
                scrollX: false,

                lengthMenu: [10, 20, 50, 100],

                language: {
                    processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                },

                ajax: {
                    url: "{{ route('stock-ledger.index') }}",
                    data: function (d) {
                        d.raw_materail_batch_id = "{{ request()->raw_materail_batch_id }}";
                    }
                },

                columns: [
                    {
                        data: 'created_at',
                        name: 'created_at',
                       
                    },
                    {
                        data: 'raw_material',
                        name: 'raw_material'
                    },

                    {
                        data: 'type',
                        name: 'type'
                    },
                   
                    {
                        data: 'uom',
                        name: 'uom'
                    },
                    {
                        data: 'approved_by',
                        name: 'approved_by'
                    },

                ],

                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            });

        });
    </script>
@endpush
