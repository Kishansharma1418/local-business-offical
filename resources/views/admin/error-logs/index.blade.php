<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Error Logs</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary"> Error Logs</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">


            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="errorlogsTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">ModuleName</th>
                                <th scope="col" class="fw-medium">Error Code</th>
                                <th scope="col" class="fw-medium">Function Name</th>
                                <th scope="col" class="fw-medium">Action By</th>
                                <th scope="col" class="fw-medium">Created At</th>
                                <th scope="col" class="fw-medium" style="text-align: center">Action</th>
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

            if (!$.fn.DataTable.isDataTable('#errorlogsTable')) {
                var dataTable = $('#errorlogsTable').DataTable({
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
                        url: "{{ route('error-logs.index') }}",
                        data: function(d) {}
                    },
                    columns: [

                        {
                            data: 'module_name',
                            name: 'module_name'
                        },

                        {
                            data: 'error_code',
                            name: 'error_code'
                        },

                         {
                            data: 'function_name',
                            name: 'function_name'
                        },

                        {
                            data: 'action_user',
                            name: 'action_user'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',

                        },
                        {
                            data: 'action_data',
                            name: 'action_data',
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
