<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">

            <h3 class="mb-0">Employee TDS List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">

                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">

                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>

                            <span class="text-body fs-14 hover">Dashboard</span>

                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">

                        <span class="text-secondary">Employee TDS</span>

                    </li>

                </ol>
            </nav>

        </div>


        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">

                <a href="{{ route('tds.create') }}"
                    class="text-decorntion-none btn btn-primary fw-normal text-white fs-16 border-0 p-3">

                    + Add TDS

                </a>

            </div>
            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="tdsTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">Employee</th>
                                <th scope="col" class="fw-medium">Financial Year</th>
                                <th scope="col" class="fw-medium">Month</th>
                                <th scope="col" class="fw-medium">Gross Salary</th>
                                <th scope="col" class="fw-medium">Taxable Salary</th>
                                <th scope="col" class="fw-medium">TDS %</th>
                                <th scope="col" class="fw-medium">TDS Amount</th>
                                <th scope="col" class="fw-medium text-center">Action</th>
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
        $(document).ready(function() {

            if (!$.fn.DataTable.isDataTable('#tdsTable')) {

                var dataTable = $('#tdsTable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    scrollX: false,
                    lengthMenu: [10, 20, 50, 100],
                    language: {
                        processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                    },
                    ajax: {
                        url: "{{ route('tds.index') }}"
                    },
                    columns: [

                        {
                            data: 'employee',
                            name: 'employee'
                        },

                        {
                            data: 'financial_year',
                            name: 'financial_year'
                        },

                        {
                            data: 'month',
                            name: 'month'
                        },

                        {
                            data: 'gross_salary',
                            name: 'gross_salary'
                        },

                        {
                            data: 'taxable_salary',
                            name: 'taxable_salary'
                        },

                        {
                            data: 'tds_percent',
                            name: 'tds_percent'
                        },

                        {
                            data: 'tds_amount',
                            name: 'tds_amount'
                        },

                        {
                            data: 'action',
                            name: 'action'
                        }

                    ],
                    dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",

                });

            }

        });
    </script>
@endpush
