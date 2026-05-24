<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Last Month Adjustments</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Last Month Adjustments</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="default-table-area mx-minus-1">

                <div class="d-flex justify-content-start align-items-center flex-wrap gap-3 p-20">

                    <!-- Add Button -->
                    <a href="{{ route('last-adjustments.create') }}"
                        class="text-decorntion-none btn btn-primary fw-normal text-white fs-16 border-0 p-3 fs-16 border-0"
                        style="color: #fff; font-size: 14px;">
                        + Add Adjustment
                    </a>

<!-- 
                    <select name="employee_id" id="employee_id" class="form-control form-select-sm w-auto "style="height: 50px;"
                        style="margin-left: 10px;">
                        <option value="">All Employees</option>
                        @foreach ($employee as $e)
                            <option value="{{ $e->id }}">{{ $e->full_name }}</option>
                        @endforeach
                    </select> -->
                    <input type="month" id="searchMonth" class="form-control form-control-sm w-auto" style="height: 50px;">
                </div>



                <div class="table-responsive overflow-none mt-3">
                    <table class="table" id="adjustmentTable">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Adjustment Month</th>
                                <th>Current Month</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Created At</th>
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

            var table = $('#adjustmentTable').DataTable({
                processing: true,
                serverSide: true,


                ajax: {
                    url: "{{ route('last-adjustments.index') }}",
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
                        data: 'adjustment_month',
                        name: 'adjustment_month'
                    },
                    {
                        data: 'current_month',
                        name: 'current_month'
                    },
                    {
                        data: 'adjustment_amount',
                        name: 'adjustment_amount'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
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

            // DELETE RECORD
            $(document).on('click', '.deleteRecordBtn', function() {
                if (confirm('Are you sure want to delete this record?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('last-adjustments') }}/" + id,
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
