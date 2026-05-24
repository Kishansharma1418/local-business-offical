<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>

@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        {{-- Page Header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Currency List</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-secondary">Currency List</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Card --}}
        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="default-table-area mx-minus-1">
                <div class="table-responsive overflow-none">
                    <table class="table" id="currencyTable">
                        <thead>
                            <tr>
                                <th>Currency</th>
                                <th>Country</th>
                                <th>Code</th>
                                <th>Symbol</th>
                                <th>Minor Unit</th>

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
        $(document).ready(function() {

            let table = $('#currencyTable').DataTable({
                processing: true,
                serverSide: true,
                lengthMenu: [10, 20, 50, 100],
                ajax: "{{ route('currencies.index') }}",
                columns: [{
                        data: 'currency',
                        name: 'currency'
                    },
                    {
                        data: 'country',
                        name: 'country'
                    },
                    {
                        data: 'code',
                        name: 'code'
                    },
                    {
                        data: 'symbol',
                        name: 'symbol'
                    },
                    {
                        data: 'minor_unit',
                        name: 'minor_unit'
                    }

                ],
                dom: "<'row mb-3'<'col-sm-6'l><'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            });

        });
    </script>
@endpush
