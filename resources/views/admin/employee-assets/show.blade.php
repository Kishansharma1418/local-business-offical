@extends('include.master')

@section('content')
<style>
    /* Table White Background */
    .table thead th {
        background: #f8f9fa !important;
    }

    .table {
        background-color: white !important;
        color: #333 !important;
    }

    .table tbody tr {
        background-color: white !important;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }

    .table td {
        background-color: white !important;
        border-color: #dee2e6 !important;
    }

    /* Form Elements White Background */
    .form-label {
        background-color: white !important;
        padding: 2px 6px;
        border-radius: 4px;
        display: inline-block;
        color: #333;
        margin-bottom: 4px;
    }

    #monthPicker {
        background-color: white !important;
        color: #333 !important;
        border: 1px solid #ced4da !important;
        border-radius: 4px;
    }

    /* Card & Container White Background */
    .card {
        background-color: white !important;
    }

    .card-body {
        background-color: white !important;
    }

    .main-content-container {
        background-color: white !important;
    }

    /* Modal White Background */
    .modal-content {
        background-color: white !important;
    }

    .modal-header {
        background-color: white !important;
    }

    .modal-body {
        background-color: white !important;
    }

    .modal-footer {
        background-color: white !important;
    }

    .rounded-10 {
        border-radius: 10px;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>

<div class="main-content-container">
    <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 mt-1">
        <h3 class="mb-0 fw-semibold">
            {{ $employee->full_name }} - Monthly Expenses
        </h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('employee-expense.index') }}" class="text-decoration-none">Employee Expense</a>
                </li>
                <li class="breadcrumb-item active">View Employee Expense</li>
            </ol>
        </nav>
    </div>

    <div class="card rounded-10 border mb-4">
        <div class="card-body">
            <div class="row mb-3 align-items-end">
                <div class="col-lg-3">
                    <label class="form-label">Select Month</label>
                    <input type="month" id="monthPicker" class="form-control form-control-sm"
                        max="{{ \Carbon\Carbon::now()->subMonth()->format('Y-m') }}"
                        value="{{ $month }}">
                </div>
                <div class="col-lg-9 d-flex justify-content-end">
                    <button id="approveAllBtn" class="btn btn-primary btn-sm text-white" data-employee="{{ $employee->id }}" data-month="{{ $month }}">
                        <span class="spinner-border spinner-border-sm d-none" id="approveSpinner"></span>
                        <i class="ri-check-double-line me-1"></i> Approve All
                    </button>
                </div>


            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Expense Type</th>
                            <th>Working Type</th>
                            <th>Travel From</th>
                            <th>Travel To</th>
                            <th>Distance (KM)</th>
                            <th>Other Amount</th>
                            <th>HQ</th>
                            <th>Ex Stn</th>
                            <th>Out Stn</th>
                            <th>Total</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($expenses as $exp)
                        <tr>
                            <td>
                                {{ \Carbon\Carbon::parse($exp->start_date)->format('Y-m-d') }} <br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($exp->start_date)->format('l') }}
                                </small>
                            </td>
                            <td>{{ ucfirst($exp->type) }}</td>

                            <td>{{ ucfirst(str_replace('_', ' ', $exp->working_type)) ?? 'N/A' }}</td>

                            <td style="max-width:150px; word-break:break-word;">
                                {{ $exp->travel_from ?? 'N/A' }}
                            </td>

                            <td style="max-width:150px; word-break:break-word;">
                                {{ $exp->travel_to ?? 'N/A' }}
                            </td>
                            <td>{{ $exp->distance ?? 'N/A' }}</td>

                            <td>{{ $exp->amount }}</td>
                            <td>{{ $exp->hq_allow }}</td>
                            <td>{{ $exp->ex_stn_allow }}</td>
                            <td>{{ $exp->out_stn_allow }}</td>

                            <td>{{ number_format($exp->total_amount, 2) }}</td>
                            <td>
                                @if($exp->image)
                                <a href="{{ asset('storage/' . $exp->image) }}" target="_blank" class="text-primary">View File</a>
                                @else
                                N/A
                                @endif

                            </td>
                            <td>
                                <span class="badge 
                                        @if($exp->status=='Verified') bg-success
                                        @elseif($exp->status=='Rejected') bg-danger
                                        @elseif($exp->status=='Expired') bg-secondary
                                        @else bg-warning @endif
                                    ">
                                    {{ $exp->status }}
                                </span>
                            </td>
                            <td>
                                <a href="javascript:void(0);"
                                    class="changeStatusBtn"
                                    data-id="{{ $exp->id }}"

                                    data-status="{{ $exp->status }}"
                                    data-reason="{{ $exp->reason ?? '' }}">
                                    <em class="fas fa-edit font-16"></em>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No Data Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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
                                <option value="Submited">Submited</option>
                                <option value="Verified">Verified</option>
                                <option value="Rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mb-20" id="reason_box">
                            <label class="label fs-16 mb-2">Reason</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3"
                                placeholder="Enter reason here..."></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 p-20 pt-0">
                    <button type="button" class="btn btn-danger fw-normal text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-normal text-white">
                        <span class="spinner-border spinner-border-sm d-none"></span>
                        Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
    $(document).ready(function() {
        $("#monthPicker").change(function() {
            let month = $(this).val();
            if (month) {
                window.location.href = "?month=" + month;
            }
        });

        $('#approveAllBtn').click(function() {
            if (!confirm('Are you sure? This will approve ALL expenses for this employee for the selected month.')) {
                return;
            }

            let $btn = $(this);
            let employeeId = $btn.data('employee');
            let month = $btn.data('month');
            let $spinner = $('#approveSpinner');

            $btn.prop('disabled', true);
            $spinner.removeClass('d-none');
            $btn.html('Approving...');

            $.ajax({
                url: "{{ route('employee-expense.approveAll') }}",
                type: "POST",
                data: {
                    employee_id: employeeId,
                    month: month,
                    _token: "{{ csrf_token() }}"
                },
                success: function(res) {
                    toastr.success(res.message || 'All expenses approved successfully!');
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    let errorMsg = 'Something went wrong!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    toastr.error(errorMsg);

                    $btn.prop('disabled', false);
                    $spinner.addClass('d-none');
                    $btn.html('Approve All Expenses');
                }
            });
        });

        $(document).on('click', '.changeStatusBtn', function() {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let reason = $(this).data('reason');

            $('#status_id').val(id);
            $('#status').val(status);
            $('#reason').val(reason);
            $('#statusModal').modal('show');
        });

        $('#reason_box').hide();

        $('#status').change(function() {
            let status = $(this).val();

            if (status === "Rejected") {
                $('#reason_box').show();
                $('#reason').attr('required', true);
            } else {
                $('#reason_box').hide();
                $('#reason').removeAttr('required');
                $('#reason').val('');
            }
        });

        $(document).on('click', '.changeStatusBtn', function() {

            let id = $(this).data('id');
            let status = $(this).data('status');
            let reason = $(this).data('reason');

            $('#status_id').val(id);
            $('#status').val(status);
            $('#reason').val(reason);

            // SHOW reason box IF status is already Rejected
            if (status === "Rejected") {
                $('#reason_box').show();
                $('#reason').attr('required', true);
            } else {
                $('#reason_box').hide();
                $('#reason').removeAttr('required');
            }

            $('#statusModal').modal('show');
        });



        $('#statusForm').submit(function(e) {
            e.preventDefault();
            let id = $('#status_id').val();
            let formData = $(this).serialize();
            let $submitBtn = $(this).find('button[type="submit"]');
            let $formSpinner = $submitBtn.find('.spinner-border');

            $submitBtn.prop('disabled', true);
            $formSpinner.removeClass('d-none');

            $.ajax({
                url: "/employee-assets/" + id + "/update-status",
                type: "POST",
                data: formData,
                success: function(res) {
                    toastr.success('Status updated successfully!');
                    $('#statusModal').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                },
                error: function(err) {
                    toastr.error('Something went wrong!');
                },
                complete: function() {
                    $submitBtn.prop('disabled', false);
                    $formSpinner.addClass('d-none');
                }
            });
        });
    });
</script>
@endpush