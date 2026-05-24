<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Salary Component List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Salary Component List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <!-- <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                  {{-- @can('add-salary-component') --}}
                <a href="{{ route('salary-component.create') }}"
                    class="text-decorntion-none btn btn-primary fw-normal text-white fs-16 border-0 p-3 fs-16 border-0">+
                    Add Salary Component </a>
                        {{-- @endcan --}}

            </div> -->
            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="salaryComponentTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">Component Name</th>
                                <th scope="col" class="fw-medium">Component Type</th>
                                <th scope="col" class="fw-medium">Calculation Type</th>
                               
                                <th scope="col" class="fw-medium">Status</th>
                                <th scope="col" class="fw-medium">Created At</th>
                                 <th scope="col" class="fw-medium">Updated At</th>
                                {{-- <th scope="col" class="fw-medium" style="text-align: center">Action</th> --}}
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            if (!$.fn.DataTable.isDataTable('#salaryComponentTable')) {
                var dataTable = $('#salaryComponentTable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    scrollX: false,
                    // responsive: true,
                    lengthMenu: [10, 20, 50, 100],
                    language: {
                        processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                    },
                    ajax: {
                        url: "{{ route('salary-component.index') }}",
                        data: function(d) {}
                    },
                    columns: [

                        {
                            data: 'component_name',
                            name: 'component_name'
                        },
                        {
                            data: 'component_type',
                            name: 'component_type'
                        },
                        {
                            data: 'calculation_type',
                            name: 'calculation_type'
                        },
                       
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at', 

                        },

                        {
                            data: 'updated_at',
                            name: 'updated_at',

                        },
                        // {
                        //     data: 'action',
                        //     name: 'action',

                        // }
                    ],
                    dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
                });
            }

        });
    </script>
@endpush
