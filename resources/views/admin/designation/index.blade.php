<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Designation List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Designation List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">

            <div class="d-flex align-items-center flex-wrap gap-3 p-20">
                {{-- @can('add-designation') --}}
                <button class=" btn btn-primary fw-normal text-white fs-16 border-0 p-3" data-bs-toggle="modal"
                    data-bs-target="#exampleModal">+ Add Designation
                </button>
                {{-- @endcan --}}
                <div class="d-flex align-items-center gap-2">
                    <select name="name" id="nameFilter" class="form-control form-select-sm"
                        style="width: 180px; height: 50px;">
                        <option value="">All Designation</option>
                        @foreach ($designation as $t)
                            <option value="{{ $t->name }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>  
            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="designationTable">
                        <thead>
                            <tr>
                                <th scope="col" class="fw-medium">Name</th>
                                <th scope="col" class="fw-medium">Designation Code</th>
                                <th scope="col" class="fw-medium">Employee Count</th>
                                <th scope="col" class="fw-medium">Department</th>
                                <th scope="col" class="fw-medium">Status</th>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form class="modal-content bg-white" method="Post" enctype="multipart/form-data" id="add_uom_form">
                @csrf
                <input type="hidden" id="class_route" value="{{ route('designation.store') }}">

                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0" id="exampleModalLabel">Add Designation</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-20">
                                <label class="label">Designation Name <span class="text-danger">*</span></label>
                                <div class="form-floating">
                                    <input type="name" class="form-control" id="floatingInput" name="name"
                                        placeholder="Designation Name" data-rule-required="true">
                                    <label for="floatingInput">Designation Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-20">
                                <label class="label">Designation Code <span class="text-danger">*</span></label>
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="floatingInput" name="code"
                                        id="code" placeholder="Designation Code" data-rule-required="true">
                                    <label for="floatingInput">Designation Code</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Department<span class="text-danger">*</span></label>
                            <select name="department_id" data-rule-required="true" class="form-select form-control">
                                <option value="">Select Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ old('department_id') == $department->department_name ? 'selected' : '' }}>
                                        {{ $department->department_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-12">
                            <div class="mb-20">
                                <label class="label"> Description</label>
                                <div class="form-floating">
                                    <textarea name="description" class="form-control" id="description" cols="3" rows="16"
                                        placeholder="Example:Multiple strips/tablets in one box"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Status</label>
                            <select class="form-select form-control" name="status">
                                <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
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

            if (!$.fn.DataTable.isDataTable('#designationTable')) {
                var dataTable = $('#designationTable').DataTable({
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
                        url: "{{ route('designation.index') }}",
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
                            data: 'code',
                            name: 'code'
                        },
                        {
                            data: 'employee_count',
                            name: 'employee_count'
                        },

                        {
                            data: 'department',
                            name: 'department'
                        },

                        {
                            data: 'status',
                            name: 'status'
                        },

                        {
                            data: 'action',
                            name: 'action',
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
                if (confirm('Are you sure want to delete this Designation?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('designation') }}/" + id,
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
        handleModalFormSubmit('#add_uom_form', '#exampleModal', '#designationTable', 'Designation Created Successfully');
        handleModalUpdateSubmit('#edit_uom_form', '#edit_model', '#designationTable', 'Designation Updated Successfully');
    </script>

    <script>
        $(document).ready(function() {
            setupRealtimeValidation('Designation', 'code', 'input[name="code"]');

        });
    </script>
    <script>
        $(document).on('input', '#edit_model input[name="code"]', function() {
            const recordId = $(this).closest('form').find('input[name="id"]').val() || null;
            setupRealtimeValidation('Designation', 'code', this, recordId);
        });
    </script>
@endpush
