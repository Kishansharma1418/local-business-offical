<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Role List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Role List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex align-items-center flex-wrap p-20 justify-content-between">

                <!-- LEFT SIDE (Add Role + Filter) -->
                <div class="d-flex align-items-center gap-3">

                    {{-- Add Role Button --}}
                    <button class="btn btn-primary fw-normal text-white fs-16 border-0 p-3" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        + Add Role
                    </button>

                    {{-- Filter Dropdown --}}
                    <select name="name" id="nameFilter" class="form-control form-select-sm"
                        style="width: 180px; height: 50px;">
                        <option value="">All Roles</option>
                        @foreach ($roles as $t)
                            <option value="{{ $t->name }}">{{ $t->name }}</option>
                        @endforeach
                    </select>

                </div>

                <a href="{{ route('permission.index') }}" class="btn btn-primary fw-normal text-white fs-16 border-0 p-3">
                    <i class="fa-solid fa-user-plus"></i> Permission
                </a>

            </div>

            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="roleTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium text-capitalize">name</th>
                                <th scope="col" class="fw-medium text-capitalize">guard</th>
                                <th scope="col" class="fw-medium text-capitalize">Created At</th>
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
            <form class="modal-content bg-white" method="Post" enctype="multipart/form-data" id="add_role_form">
                @csrf
                <input type="hidden" id="role_route" value="{{ route('roles.store') }}">

                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0" id="exampleModalLabel">Add New Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label">Role Name</label>
                                <div class="form-floating">
                                    <input type="name" class="form-control" id="floatingInput" name="name"
                                        placeholder="Role Name" data-rule-required="true">
                                    <label for="floatingInput">Role Name</label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 p-20 pt-0">
                    <button type="button" class="btn btn-danger fw-normal text-white"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-normal text-white">Create</button>
                </div>
            </form>
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
                        url: "{{ route('roles.index') }}",
                        data: function(d) {
                            d.name = $('#nameFilter').val();
                        }
                    },
                    columns: [

                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'guard_name',
                            name: 'guard_name'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',

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
            $(document).on('click', '.deleteBranchBtn', function() {
                if (confirm('Are you sure want to delete this Role?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('roles') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            dataTable.ajax.reload();
                            toastr.success(res.message);
                        }
                    });
                }
            });
        });
    </script>

    <script>
        handleModalFormSubmit('#add_role_form', '#exampleModal', '#roleTable', 'Role created successfully');
        handleModalUpdateSubmit('#edit_role_form', '#edit_model', '#roleTable', 'Role updated successfully');
    </script>
@endpush
