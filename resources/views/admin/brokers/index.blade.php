<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        {{-- PAGE HEADER --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Broker List</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-secondary">Broker List</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- CARD --}}
        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="default-table-area mx-minus-1">

                <div class="d-flex align-items-center flex-wrap gap-3 p-20">
                    {{-- @can('add-broker') --}}
                    <a href="{{ route('brokers.create') }}"
                        class="btn btn-primary d-flex align-items-center justify-content-center px-3 py-3 rounded-2 shadow-sm"
                        style="color:#fff; font-size:14px;">
                        + Add Broker
                    </a>
                    {{-- @endcan --}}

                    {{-- <div class="d-flex align-items-center gap-2">
                    <select name="broker_name" id="nameFilter"
                        class="form-control form-select-sm"
                        style="width:180px; height:50px;">
                        <option value="">All Brokers</option>
                        @foreach ($brokers as $b)
                            <option value="{{ $b->name }}">{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div> --}}
                </div>

                <div class="table-responsive overflow-none mt-3">
                    <table class="table" id="brokerTable">
                        <thead>
                            <tr>
                                <th>Broker Info</th>
                                <th>Commission</th>
                                <th>Status</th>

                                <th>created At</th>
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
            var table = $('#brokerTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('brokers.index') }}",
                    data: function(d) {
                        d.broker_name = $('#nameFilter').val();
                    }
                },
                columns: [{
                        data: 'broker_info',
                        name: 'broker_info',
                        orderable: false,
                        searchable: true
                    },
                    {
                        data: 'commission',
                        name: 'commission',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
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

            $('#nameFilter').change(function() {
                table.draw();
            });

            $(document).on('click', '.deleteBrokerBtn', function() {
                if (confirm('Are you sure want to delete this broker?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('brokers') }}/" + id,
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
