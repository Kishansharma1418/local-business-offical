<style>
    input.form-control.form-control-sm {
        height: 43px;
    }
</style>
@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Documents</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Employee Documents</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="default-table-area mx-minus-1">

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                    <a href="{{ route('documents.create') }}"
                        class="btn btn-primary d-flex align-items-center justify-content-center px-3 py-3 rounded-2 shadow-sm"
                        style="color: #fff; font-size: 14px;">
                        + Add Document
                    </a>
                </div>

                <div class="table-responsive overflow-none mt-3">
                    <table class="table" id="documentsTable">
                        <thead>
                            <tr>

                                <th>Document Type</th>
                                <th>Document Number</th>
                                <th>Status</th>

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
            var table = $('#documentsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('documents.index') }}",
                columns: [

                    {
                        data: 'document_type',
                        name: 'document_type'
                    },
                    {
                        data: 'document_number',
                        name: 'document_number'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    // { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                dom: "<'row mb-3'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-6'i><'col-sm-6 d-flex justify-content-end'p>>",
            });

            // Delete Document
            $(document).on('click', '.deleteDocumentBtn', function() {
                if (confirm('Are you sure want to delete this document?')) {
                    var id = $(this).data('id');
                    $.ajax({
                        url: "{{ url('documents') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(res) {
                            table.ajax.reload();
                            alert(res.message);
                        }
                    });
                }
            });

            // Edit Document
            $(document).on('click', '.editDocumentBtn', function() {
                var id = $(this).data('id');
                window.location.href = "{{ url('documents') }}/" + id + "/edit";
            });
        });
    </script>
@endpush
