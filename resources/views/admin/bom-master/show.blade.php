@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">BOM Master Details</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('bom-master.index') }}" class="text-decoration-none">
                            BOM Master List
                        </a>
                    </li>
                    <li class="breadcrumb-item active">View Details</li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">

                <h5 class="mb-0">BOM Master Information</h5>

                @if (Auth::check() && Auth::user()->user_type == 'admin' && $bomMaster->status != 1)
                    <button type="button" class="btn btn-primary btn-sm text-white changeStatusBtn" data-bs-toggle="modal"
                        data-bs-target="#statusModal">
                        Change Status
                    </button>
                @endif

            </div>

            <div class="row">

                <div class="col-md-6 mb-2">
                    <strong>BOM Number:</strong> {{ $bomMaster->bom_number }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>BOM Version:</strong> {{ $bomMaster->bom_version }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Finished Good:</strong>
                    {{ optional($bomMaster->finishedGood)->name }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Product Type:</strong>
                    {{ optional($bomMaster->productType)->name ?? '-' }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>BOM Date:</strong> {{ $bomMaster->bom_date }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Batch Size:</strong> {{ $bomMaster->batch_size }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Packing Type:</strong> {{ $bomMaster->packing_type }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Pack Config:</strong> {{ $bomMaster->packConfig?->name }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Pack Size:</strong> {{ $bomMaster->pack_size }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>Box Size:</strong> {{ $bomMaster->box_size }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong>No of Boxes:</strong> {{ $bomMaster->no_of_boxes }}
                </div>

                <div class="col-md-6 mb-2">
                    <strong> Branch:</strong>{{ $bomMaster->branches?->branch_name }}
                </div>

                <div class="col-md-6 mb-2 d-flex align-items-center gap-3">
                    <div>
                        <strong>Status:</strong>
                        <span class="badge {{ $bomMaster->status == 1 ? 'bg-success' : 'bg-warning' }}">
                            {{ $bomMaster->status == 1 ? 'Approved' : 'Pending' }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-2">
                <strong>Remarks:</strong><br>
                {{ $bomMaster->remarks ?? '-' }}
            </div>
        </div>
    </div>

    <div class="card bg-white p-4">
        <h5 class="mb-3">BOM Items</h5>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Raw Material</th>
                        <th>Location</th>
                        <!-- <th>Item Type</th> -->
                        <th>Quantity</th>

                        <th>Overage</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bomMaster->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ optional($item->material)->name }}</td>
                            <td>{{ optional($item->warehouse)->warehouse_name }}</td>
                            <!-- <td>{{ $item->item_type }}</td> -->
                            <td>
                                {{ $item->quantity }} {{ strtoupper($item->uoms?->name) }}
                            </td>
                            <td>{{ number_format($item->overage, 0) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No BOM Items Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


    </div>


    <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form id="statusForm" class="modal-content bg-white">
                @csrf
                @method('POST')
                <input type="hidden" id="status_id" name="status_id" value="{{ $bomMaster->id }}">
                <div class="modal-header border-border-color-40 p-20">
                    <h5 class="modal-title fs-18 fw-medium mb-0" id="statusModalLabel">Update Purchase
                        Order
                        Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-20 pb-0">
                    <div class="row">
                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">Status</label>
                            <select class="form-select form-control" name="status" id="status">
                                <!-- <option value="Pending">Pending</option> -->
                                <option value="1">Approved</option>
                                <option value="0">Pending</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mb-20" id="reason_box">
                            <label class="label fs-16 mb-2">Reason</label>
                            <textarea name="reason" id="reason" class="form-control" rows="3" placeholder="Enter reason here..."></textarea>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 p-20 pt-0">
                    <button type="button" class="btn btn-danger fw-normal text-white"
                        data-bs-dismiss="modal">Cancel</button>
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
            $('.changeStatusBtn').on('click', function(e) {
                e.preventDefault();
                $('#statusModal').modal('show');
            });

            $('#status').on('change', function() {
                if ($(this).val() === '0') {
                    $('#reason_box').show();
                } else {
                    $('#reason_box').hide();
                }
            });

            $('#reason_box').hide();

            $('#statusForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var url = "{{ url('bom/change-status') }}/" + $('#status_id').val();

                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    beforeSend: function() {
                        form.find('button[type="submit"] .spinner-border').removeClass(
                            'd-none');
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('An error occurred while updating the status.');
                    },
                    complete: function() {
                        form.find('button[type="submit"] .spinner-border').addClass('d-none');
                    }
                });
            });
        });
    </script>
@endpush
