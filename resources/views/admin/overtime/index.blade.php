<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Overtime</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        Overtime List
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                <a href="{{ route('overtime.create') }}"
                    class="text-decoration-none btn btn-primary fw-normal text-white p-3">
                    Add Overtime
                </a>
            </div>

            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="overtimeTable">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Hours</th>
                                <th>Rate / Hour</th>
                                <th>Total Amount</th>
                                <th>Created At</th>
                                <th style="text-align:center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            if (!$.fn.DataTable.isDataTable('#overtimeTable')) {

                var dataTable = $('#overtimeTable').DataTable({

                    processing: true,
                    serverSide: true,
                    lengthMenu: [10, 20, 50, 100],

                    ajax: {
                        url: "{{ route('overtime.index') }}"
                    },

                    columns: [

                        {
                            data: 'employee',
                            name: 'employee'
                        },
                        {
                            data: 'date',
                            name: 'date'
                        },
                        {
                            data: 'hours',
                            name: 'hours'
                        },
                        {
                            data: 'rate_per_hour',
                            name: 'rate_per_hour'
                        },
                        {
                            data: 'total_amount',
                            name: 'total_amount'
                        },
                        {
                            data: 'created_at',
                            name: 'created_at'
                        },
                        {
                            data: 'action',
                            name: 'action'
                        }

                    ],

                    dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6 d-flex justify-content-end'f>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",

                });

            }

        });
    </script>
@endpush
