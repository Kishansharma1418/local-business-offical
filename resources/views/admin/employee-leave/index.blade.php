<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Leave List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Leave List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                {{-- @can('add-employee-leave') --}}
                <button class="btn btn-primary fw-normal text-white fs-16 border-0 p-3" data-bs-toggle="modal"
                    data-bs-target="#addLeaveModal">
                    + Add Leave
                </button>
                {{-- @endcan --}}
                {{-- <button class="btn btn-primary fw-normal text-white fs-16 border-0 p-3" id="approveAllBtn">
                    Approve All
                </button> --}}
            </div>

            <div class="default-table-area mx-minus-1 ">
                <div class="table-responsive overflow-none">
                    <table class="table" id="leaveTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                {{-- <th>Category</th> --}}
                                <th>Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Days</th>
                                <th>Status</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Leave Modal --}}
    <div class="modal fade" id="addLeaveModal" tabindex="-1" aria-labelledby="addLeaveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 600px;">
            <form class="modal-content bg-white" method="POST" id="addLeaveForm">
                @csrf
                <input type="hidden" id="class_route" value="{{ route('leaves.store') }}">

                <div class="modal-header border-border-color-40 p-20">
                    <h1 class="modal-title fs-18 fw-medium mb-0">Add Leave</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-20 pb-0">
                    <div class="row">

                        @if (auth()->user()->hasRole('admin'))
                            <div class="col-lg-6 mb-20">
                                <label class="label">Employee <span class="text-danger">*</span></label>
                                <select name="employee_id" class="form-control" data-rule-required="true">
                                    <option value="">Select Employee</option>
                                    @foreach ($employee as $emp)
                                        <option value="{{ $emp->id }}">{{ $emp->full_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- <div class="col-lg-6 mb-20">

                            <label class="label">Leave Category <span class="text-danger">*</span></label>
                            <select name="leave_category" class="form-control" data-rule-required="true">
                                <option value="">Select</option>
                                <option value="medical">Medical</option>
                                <option value="casual">Casual</option>
                                <option value="paid">Paid</option>
                                <option value="work from home">Work From Home</option>
                            </select>
                        </div> --}}

                        <div class="col-lg-6 mb-20">
                            <label class="label">Leave Type</label>
                            <select name="leave_type" class="form-control" data-rule-required="true">
                                <option value="full day">Full Day</option>
                                <option value="half day">Half Day</option>
                            </select>
                        </div>

                        <div class="col-lg-6 mb-20">
                            <label class="label">Select Date Range <span class="text-danger">*</span></label>
                            <input type="text" name="date_range" id="date_range" class="form-control"
                                placeholder="Select date range" data-rule-required="true">
                        </div>

                        <div class="col-lg-12 mb-20">
                            <label class="label">Description</label>
                            <textarea name="description" class="form-control" rows="2"></textarea>
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

    {{-- Edit Modal (content load via AJAX) --}}
    <div class="modal fade" id="edit_model" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true"></div>
@endsection

@push('scripts')
    {{-- Date Range Picker CSS/JS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {

            // DataTable init
            if (!$.fn.DataTable.isDataTable('#leaveTable')) {
                var dataTable = $('#leaveTable').DataTable({
                    processing: true,
                    serverSide: true,
                    autoWidth: true,
                    scrollX: false,
                    lengthMenu: [10, 20, 50, 100],
                    language: {
                        processing: '<div class="page_loader"><div class="loader_img"><div class="spinner-border"></div></div></div>'
                    },
                    ajax: "{{ route('leaves.index') }}",
                    columns: [{
                            data: 'full_name',
                            name: 'full_name'
                        },
                        // {
                        //     data: 'leave_category',
                        //     name: 'leave_category'
                        // },
                        {
                            data: 'leave_type',
                            name: 'leave_type'
                        },
                        {
                            data: 'start_date',
                            name: 'start_date'
                        },
                        {
                            data: 'end_date',
                            name: 'end_date'
                        },
                        {
                            data: 'total_days',
                            name: 'total_days'
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

            // Delete Leave
            $(document).on('click', '.deleteLeaveBtn', function() {
                if (!confirm('Are you sure want to delete this leave?')) {
                    return;
                }

                var id = $(this).data('id');
                $.ajax({
                    url: "{{ url('leaves') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        $('#leaveTable').DataTable().ajax.reload();
                        toastr.success(res.message);
                    }
                });
            });

            // Date range picker (Add form)
            $('#date_range').daterangepicker({
                autoApply: true,
                // minDate: moment().add(1, 'days'),
                // startDate: moment().add(1, 'days'),
                // endDate: moment().add(1, 'days'),
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });


            // On select, add hidden start_date / end_date
            $('#date_range').on('apply.daterangepicker', function(ev, picker) {
                const startDate = picker.startDate.format('YYYY-MM-DD');
                const endDate = picker.endDate.format('YYYY-MM-DD');

                $('#addLeaveForm input[name="start_date"]').remove();
                $('#addLeaveForm input[name="end_date"]').remove();

                $('<input>').attr({
                    type: 'hidden',
                    name: 'start_date',
                    value: startDate
                }).appendTo('#addLeaveForm');

                $('<input>').attr({
                    type: 'hidden',
                    name: 'end_date',
                    value: endDate
                }).appendTo('#addLeaveForm');
            });

            // Approve All
            $('#approveAllBtn').click(function() {
                if (!confirm("Are you sure you want to approve all pending leaves?")) {
                    return;
                }

                $.ajax({
                    url: "{{ route('leaves.approveAll') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.success) {
                            toastr.success(res.message);
                            $('#leaveTable').DataTable().ajax.reload();
                        }
                    }
                });
            });

            // Edit modal shown: init date range picker for edit_date_range
            $(document).on('shown.bs.modal', '#edit_model', function() {

                if ($('#edit_date_range').data('daterangepicker')) {
                    $('#edit_date_range').data('daterangepicker').remove();
                    $('#edit_date_range').off();
                }

                $('#edit_date_range').daterangepicker({
                    autoApply: true,
                    locale: {
                        format: 'DD/MM/YYYY'
                    }
                });

                $('#edit_date_range').on('apply.daterangepicker', function(ev, picker) {
                    const startDate = picker.startDate.format('YYYY-MM-DD');
                    const endDate = picker.endDate.format('YYYY-MM-DD');

                    $('#editLeaveForm input[name="start_date"]').remove();
                    $('#editLeaveForm input[name="end_date"]').remove();

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'start_date',
                        value: startDate
                    }).appendTo('#editLeaveForm');

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'end_date',
                        value: endDate
                    }).appendTo('#editLeaveForm');
                });
            });

            // Global form handlers (assuming functions defined in layout)
            if (typeof handleModalFormSubmit === 'function') {
                handleModalFormSubmit('#addLeaveForm', '#addLeaveModal', '#leaveTable',
                    'Leave created successfully');
            }

            if (typeof handleModalUpdateSubmit === 'function') {
                handleModalUpdateSubmit('#editLeaveForm', '#edit_model', '#leaveTable',
                    'Leave updated successfully');
            }
        });
    </script>
@endpush
