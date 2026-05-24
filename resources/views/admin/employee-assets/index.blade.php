<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0 fw-semibold">Employee Expenses</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Expenses List</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <button id="approveAll" class="btn btn-primary btn-sm text-white">
                            <i class="ri-check-double-line me-1"></i> Approve All
                        </button>
                        <select name="employee_id" id="employee_id" class="form-control form-select-sm w-auto"
                            style="height: 50px;">
                            <option value="">All Employees</option>
                            @foreach ($employee as $e)
                                <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                            @endforeach
                        </select>

                        <input type="month" id="searchMonth" class="form-control form-control-sm w-auto"
                            style="height: 50px;">


                    </div>

                    <div class="d-flex gap-2">
                        <button id="exportExcel" class="btn btn-success btn-sm text-white">
                            <i class="ri-file-excel-2-line me-1"></i> Excel
                        </button>
                        <button id="exportPDF" class="btn btn-danger btn-sm text-white">
                            <i class="ri-file-pdf-2-line me-1"></i> PDF
                        </button>
                    </div>
                </div>

                <hr class="my-3">

                <div class="default-table-area mx-minus-1">
                    <div class="table-responsive overflow-none">
                        <table class="table" id="assetsTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="fw-medium">Employee Name</th>
                                    <th class="fw-medium">Total Amount</th>
                                    <th class="fw-medium">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Status Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form id="statusForm" class="modal-content bg-white">
                @csrf
                @method('POST')
                <input type="hidden" id="status_id" name="status_id">
                <div class="modal-header border-border-color-40 p-20">
                    <h5 class="modal-title fs-18 fw-medium mb-0" id="statusModalLabel">Update Expense Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Status</label>
                            <select class="form-select form-control" name="status" id="status">
                                <option value="Pending">Pending</option>
                                <option value="Rejected">Rejected</option>

                            </select>
                        </div>
                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Reason (optional)</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Enter reason here..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-20 pt-0">
                    <button type="button" class="btn btn-danger fw-normal text-white"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-normal text-white">Update</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            const now = new Date();
            const prev = new Date(now.getFullYear(), now.getMonth() - 1, 1);

            const prevYear = prev.getFullYear();
            const prevMonth = String(prev.getMonth() + 1).padStart(2, '0');
            const prevValue = `${prevYear}-${prevMonth}`;

            $('#searchMonth').val(prevValue);
            document.getElementById('searchMonth').max = prevValue;
            var table = $('#assetsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('employee-expense.index') }}",
                    data: function(d) {
                        d.employee_id = $('#employee_id').val();
                        d.month = $('#searchMonth').val();
                    }
                },
                columns: [{
                        data: 'employee',
                        name: 'employee'
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
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
                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",

            });

            $('#employee_id, #searchMonth').on('change', function() {
                table.draw();
            });

            $('#exportExcel').click(function() {
                var employee_id = $('#employee_id').val();
                var month = $('#searchMonth').val();
                window.location.href = "{{ route('employee-assets.export.excel') }}" + "?employee_id=" +
                    employee_id + "&month=" + month;
            });

            $('#exportPDF').click(function() {
                var employee_id = $('#employee_id').val();
                var month = $('#searchMonth').val();
                window.location.href = "{{ route('employee-assets.export.pdf') }}" + "?employee_id=" +
                    employee_id + "&month=" + month;
            });

            $('#approveAll').click(function() {
                let employee_id = $('#employee_id').val();
                let month = $('#searchMonth').val();

                if (!confirm("Are you sure you want to approve all pending expenses?")) return;

                $.post("{{ route('employee-expense.approveAll') }}", {
                    employee_id: employee_id,
                    month: month,
                    _token: "{{ csrf_token() }}"
                }, function(res) {
                    toastr.success(res.message);
                    table.ajax.reload();
                }).fail((xhr) => {
                    let errorMessage = "Something went wrong!";

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    toastr.error(errorMessage);
                });
            });


            $(document).on('click', '.changeStatusBtn', function() {
                let id = $(this).data('id');
                console.log("Clicked Employee ID:", id);
                $('#status_id').val(id);
                $('#statusModal').modal('show');
            });

            $('#statusForm').submit(function(e) {
                e.preventDefault();

                let employee_id = $('#status_id').val();
                let month = $('#searchMonth').val();

                if (!employee_id) {
                    toastr.error("Employee ID missing!");
                    return;
                }

                $.ajax({
                    url: "/employee-expense/" + employee_id + "/update-status",
                    type: "POST",
                    data: $(this).serialize() + "&month=" + month,
                    success: function(res) {
                        if (res.error) {
                            toastr.error(res.message);
                            return;
                        }

                        toastr.success(res.message);
                        $('#statusModal').modal('hide');
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        let msg = "Something went wrong!";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }
                        toastr.error(msg);
                    }
                });
            });




        });
    </script>
@endpush
