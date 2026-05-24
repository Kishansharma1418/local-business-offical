<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">User Login List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">User Login List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex align-items-center gap-2 p-20" style="position:absolute; left:61%; top:3%; ">
                <select name="full_name" id="nameFilter" class="form-control form-select-sm "
                    style="width: 180px; height: 50px;">
                    <option value="">All Users</option>
                    @foreach ($query as $t)
                        @if ($t->id != 1)
                            <option value="{{ $t->full_name }}">{{ $t->full_name }}</option>
                        @endif
                    @endforeach

                </select>
            </div>

            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="roleTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium text-capitalize">name</th>
                                <th scope="col" class="fw-medium text-capitalize">email</th>
                                <th scope="col" class="fw-medium text-capitalize">phone</th>

                                <th scope="col" class="fw-medium text-capitalize">user Type</th>
                                <th scope="col" class="fw-medium text-capitalize">Status</th>
                                <th scope="col" class="fw-medium text-capitalize" style="text-align: center">Action</th>


                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">

        </div>
    </div>

    <div class="modal fade" id="edit_model" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">

    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            if (!$.fn.DataTable.isDataTable('#roleTable')) {
                var dataTable = $('#roleTable').DataTable({
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
                        url: "{{ route('users.index') }}",
                        data: function(d) {
                            d.full_name = $('#nameFilter').val();
                        }
                    },
                    columns: [

                        {
                            data: 'full_name',
                            name: 'full_name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'user_type',
                            name: 'user_type'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },

                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false

                        }
                    ],
                    dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",

                });
            }
            $('#nameFilter').change(function() {
                dataTable.draw();
            });

        });
    </script>
@endpush
