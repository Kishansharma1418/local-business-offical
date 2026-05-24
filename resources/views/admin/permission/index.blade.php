<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Permission List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('roles.index') }}" class="text-decoration-none">Roles List</a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Permission List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                <button class="btn btn-primary fw-normal text-white fs-16 border-0 p-3" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">+ Add Permission</button>

            </div>
            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="permissionTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">Permission Name</th>
                                <th scope="col" class="fw-medium">Main Group</th>
                                <th scope="col" class="fw-medium">Sub Group</th>
                                <th scope="col" class="fw-medium">Created At</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form class="modal-content bg-white" method="Post" enctype="multipart/form-data" id="add_permission_form">
                @csrf
                <input type="hidden" id="class_route" value="{{ route('permission.store') }}">

                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0" id="exampleModalLabel">Add New Permission</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label">Permission Name <span class="text-danger">*</span></label>
                                <div class="form-floating">
                                    <input type="name" class="form-control" id="floatingInput" name="name"
                                        placeholder="Permission Name" data-rule-required="true">
                                    <label for="floatingInput">Permission Name</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label">Main Group <span class="text-danger">*</span></label>
                                <div class="form-floating">
                                    <input type="name" class="form-control" id="floatingInput" name="main_group"
                                        data-rule-required="true" placeholder="Main Group">
                                    <label for="floatingInput">Main Group</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label">Sub Group <span class="text-danger">*</span></label>
                                <div class="form-floating">
                                    <input type="name" class="form-control" id="floatingInput" name="sub_group"
                                        placeholder="Main Group" data-rule-required="true">
                                    <label for="floatingInput">Sub Group</label>
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

            if (!$.fn.DataTable.isDataTable('#permissionTable')) {
                var dataTable = $('#permissionTable').DataTable({
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
                        url: "{{ route('permission.index') }}",
                        data: function(d) {}
                    },
                    columns: [

                        {
                            data: 'name',
                            name: 'name'
                        },

                        {
                            data: 'main_group',
                            name: 'main_group'
                        },
                        {
                            data: 'sub_group',
                            name: 'sub_group'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at',

                        },

                    ],
                    dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +

                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
                });
            }

        });
    </script>

    <script>
        handleModalFormSubmit('#add_permission_form', '#exampleModal', '#permissionTable',
            'Permission created successfully');
        handleModalUpdateSubmit('#edit_permission_form', '#edit_model', '#permissionTable',
            'Permission updated successfully');
    </script>
@endpush
