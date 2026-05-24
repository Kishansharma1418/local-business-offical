<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Salary Revision List ({{$employe->full_name}})</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}" class="text-decoration-none text-body fs-14 hover">
                            Employee List
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Employee Salary Revision List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                {{-- @can('add-salary-revision') --}}
                <a href="{{ route('employee.revisionsalarylist.create',$employee_id) }}" 
                   class="btn btn-primary fw-normal text-white fs-16 border-0 p-3">
                    + Add Salary Revision
                </a>
                {{-- @endcan --}}
            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="employeeSalaryRevisionTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">Salary Details</th>
                                <th scope="col" class="fw-medium">Revision Reason</th>
                                <th scope="col" class="fw-medium">Status</th>
                                <th scope="col" class="text-medium" style="text-align:center;">Created At</th>
                                <!-- <th scope="col" class="fw-medium" style="text-align:center;">Action</th> -->
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
    <script type="text/javascript">
        $(document).ready(function() {
            let employeeId = "{{ $employee_id }}";

            if (!$.fn.DataTable.isDataTable('#employeeSalaryRevisionTable')) {
                $('#employeeSalaryRevisionTable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    scrollX: false,
                    lengthMenu: [10, 20, 50, 100],
                    language: {
                        processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                    },
                    ajax: {
                        url: "{{ url('employee-salary-revision') }}/" + employeeId,
                        data: function(d) {}
                    },
                    columns: [
                        { data: 'details', name: 'details' },
                        { data: 'revision_reason', name: 'revision_reason'},
                        { data: 'status', name: 'status' },
                        { data: 'created_at', name: 'created_at' },
                        // { data: 'action', name: 'action', orderable: false, searchable: false },
                    ],
                    dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +
                         "<'row'<'col-sm-12'tr>>" +
                         "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
                });
            }
        });
    </script>
@endpush
