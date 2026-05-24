<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">BOM Master</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">BOM Master</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <!-- Add Button + Filters -->
            <div class="d-flex align-items-center flex-wrap gap-3 p-20">

                <a href="{{ route('bom-master.create') }}"
                    class="btn btn-primary d-flex align-items-center justify-content-center px-3 py-3 rounded-2 shadow-sm"
                    style="color: #fff; font-size: 14px; height: 45px;">
                    + Add BOM Master
                </a>

            </div>

            <!-- Table -->
            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="bomMasterTable">
                        <thead>
                            <tr>
                                <th>BOM Number</th>
                                <th>Finished Good</th>
                                <th>BOM Date</th>
                                <th>Batch Size</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            var table = $('#bomMasterTable').DataTable({
                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('bom-master.index') }}",
                    type: "GET",
                    data: function(d) {
                        d.finished_good_id = $('#finished_good_filter').val();
                    }
                },

                columns: [{
                        data: 'bom_number',
                        name: 'bom_number'
                    },
                    {
                        data: 'finished_good',
                        name: 'finished_good'
                    },
                    {
                        data: 'bom_date',
                        name: 'bom_date'
                    },
                    {
                        data: 'batch_size',
                        name: 'batch_size'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },

                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: "text-center"
                    }
                ],

                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l>" +
                    "<'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            });


            $('#product_filter').change(function() {
                table.ajax.reload();
            });

        });
    </script>
@endpush
