<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Advance Salary </h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Advance Salary </span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="default-table-area mx-minus-1">

                <div class="d-flex  align-items-center flex-wrap gap-3 p-20">
                    <a href="{{ route('loan-advances.create') }}"
                        class="text-decorntion-none btn btn-primary fw-normal text-white fs-16 border-0 p-3 fs-16 border-0"
                        style="color: #fff; font-size: 14px;">
                        + Add Advance Salary
                    </a>
                    <!-- <div class="d-flex align-items-center gap-2 ">
                                        <select name="employee_id" id="employee_id" class="form-control form-select-sm w-auto"
                                            style="height: 50px;">
                                            <option value="">All Employees</option>
                                            @foreach ($employee as $e)
                                <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                                @endforeach
                                        </select>
                                    </div> -->
                </div>

                <div class="table-responsive overflow-none mt-3">
                    <table class="table" id="loanAdvanceTable">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Advance Salary Amount</th>
                                <th>Month</th>
                                <th>Start Month</th>
                                <th>Deduction Amount</th>
                                <th>Status</th>
                                <th>Action</th>
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
    <script>
        $(function() {
            var table = $('#loanAdvanceTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('loan-advances.index') }}",
                    data: function(d) {
                        d.employee_id = $('#employee_id').val();

                    }
                },
                columns: [{
                        data: 'employee',
                        name: 'employee'

                    },
                    {
                        data: 'loan_amount',
                        name: 'loan_amount'
                    },
                    {
                        data: 'month',
                        name: 'month'
                    },
                    {
                        data: 'start_month',
                        name: 'start_month'
                    },
                    {
                        data: 'deduction_amount',
                        name: 'deduction_amount'
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
                    },
                ],
                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            });
            $('#employee_id, #searchMonth').on('change', function() {
                table.ajax.reload();
            });

            // Delete Record
            $(document).on('click', '.deleteRecordBtn', function() {
                if (confirm('Are you sure want to delete this record?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('loan-advances') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            table.ajax.reload();
                            toastr.success(res.message);
                        }
                    });
                }
            });
        });
    </script>
@endpush
