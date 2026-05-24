@extends('include.master')

@section('content')

    <style>
        /* ONLY PAGE CONTENT */

        .main-content-container .card {
            background: #ffffff;
        }

        /* ONLY TABLE AREA */
        .main-content-container table,
        .main-content-container table th,
        .main-content-container table td {
            background: #ffffff !important;
        }

        /* CARD CLEAN */
        .main-content-container .card {
            box-shadow: none;
            border: 1px solid #e5e7eb;
        }

        /* TABLE SPACING */
        .main-content-container table th,
        .main-content-container table td {
            padding: 6px 8px;
            font-size: 13px;
            vertical-align: middle;
        }

        .main-content-container table {
            margin-bottom: 14px;
        }

        /* COMPANY HEADER */
        .company-header {
            margin-bottom: 14px;
        }

        /* SIGNATURE / APPROVAL */
        .signature-box {
            height: 90px;
            text-align: left;
            /* ✅ center se left */
            padding-left: 10px;
            /* thoda margin for neat look */
        }

        .signature-line {
            border-bottom: 1px solid #000;
            width: 160px;
            margin: 40px 0 6px 0;
            /* ✅ left aligned */
        }

        .signature-name {
            font-weight: bold;
            font-size: 13px;
            margin-top: 2px;
        }

        .signature-role {
            font-size: 11px;
        }

        .signature-date {
            font-size: 11px;
        }
    </style>

    <div class="main-content-container overflow-hidden">


        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">View Production Voucher</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('production-voucher.index') }}" class="text-decoration-none">Production
                            Voucher</a>
                    </li>
                    <li class="breadcrumb-item active">View Production Voucher</li>

                </ol>
            </nav>
        </div>

        <!-- ACTION BUTTONS -->


        <!-- CARD -->
        <div class="card p-3">
            <div class="d-flex justify-content-end gap-2 mb-3">

                <a href="{{ route('production-voucher.pdf', $batch->id) }}" class="btn btn-sm btn-primary text-white">
                    <i class="ri-file-pdf-line"></i> PDF
                </a>

                @if (!auth()->user()->hasRole('admin'))
                    @if (auth()->user()->hasRole('Production') && $batch->status === 'Pending_production')
                        <button id="productionApproveBtn" class="btn btn-sm btn-primary text-white d-none"
                            data-bs-toggle="modal" data-bs-target="#approvalModal">
                            Approve (Production)
                        </button>
                    @endif
                @endif
            </div>
            <!-- COMPANY -->
            <div class="company-header text-center">
                <h6 class="fw-bold mb-1">DD Pharmaceuticals Pvt. Ltd.</h6>
                <div class="small">
                    RIICO Industrial Area, Sitapura, Jaipur – 302022<br>
                    <strong>ISO 9001:2015 | WHO-GMP</strong>
                </div>
            </div>

            <!-- BASIC INFO -->
            <table class="table table-bordered">
                <tr>
                    <th width="20%">Product Name</th>
                    <td width="30%" class="fw-semibold">
                        {{ $batch->bomMaster->finishedGood->name }}
                    </td>
                    <th width="20%">Batch No.</th>
                    <td width="30%">{{ $batch->batch_number }}</td>
                </tr>
            

                <tr>
                    <th colspan="4" class="text-center fw-semibold">
                        2.0 Batch Production Sheet
                    </th>
                </tr>
            </table>

            <!-- ITEMS -->
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>S.No.</th>
                        <th>Material</th>
                        <th>Spec</th>
                        <th>Quantity Used</th>
                        <th>Control Ref</th>
                        <!-- @if (auth()->user()->hasRole('Production') && $batch->status === 'Pending_production')-->
                        <th>User Signature</th>
                        <!--@endif -->
                    </tr>
                </thead>
                <tbody>
                    @foreach ($batch->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->material->name }}</td>
                           <td>{{ $item->material->specification ?? '-' }}</td>
                            <td class="fw-semibold">
                                {{ number_format($item->final_quantity, 3) }} {{ strtoupper($item->uoms?->name) }}
                            </td>
                            <td>{{ $item->control_ref ?? '-' }}</td>

                            @if (auth()->user()->hasRole('Production') && $batch->status === 'Pending_production')
                                <td>
                                    <input type="checkbox" class="prod-check" data-item-id="{{ $item->id }}"
                                        {{ $item->received_checked_by ? 'checked disabled' : '' }}>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- SIGNATURE -->
            <table class="table table-bordered mt-3">
                <tr>
                    <td width="50%">
                        <strong>Verified By (Production)</strong>


                        @if ($batch->status === 'COMPLETED' && $batch->verified_by_production)
                            <div class="signature-name">
                                {{ optional($batch->verifiedByProduction)->full_name }}
                            </div>
                            <div class="signature-role">Production</div>
                            <div class="signature-date">
                                {{ $batch->updated_at->format('d-m-Y') }}
                            </div>
                        @else
                            <div class="pending-sign">Pending</div>
                        @endif

                    </td>
                </tr>
            </table>

            <div class="small mt-2">
                <strong>Note:</strong> Attach sheet if required.
            </div>

        </div>
    </div>

    <!-- MODAL -->
    <!-- Approval Modal (Leave Style) -->
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form method="POST" action="{{ route('production-voucher.approve', $batch->id) }}"
                class="modal-content bg-white">
                @csrf

                <!-- HEADER -->
                <div class="modal-header border-border-color-40 p-20">
                    <h5 class="modal-title fs-18 fw-medium mb-0">
                        Update Production Voucher Status
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body p-20 pb-0">
                    <div class="row">

                        <!-- STATUS -->
                        <div class="col-lg-12 mb-20">
                            <label class="label fs-16 mb-2">
                                Change Status
                            </label>

                            <select class="form-select form-control" id="decisionSelect" name="decision" required>
                                <option value="">Select Status</option>
                                <option value="APPROVED">Approved</option>
                                <option value="REJECTED">Rejected</option>
                            </select>
                        </div>

                        <!-- REASON -->
                        <div class="col-lg-12 mb-20 d-none" id="remarksContainer">
                            <label class="label fs-16 mb-2">
                                Reason
                            </label>

                            <textarea name="remarks" id="remarksField" class="form-control" rows="3"
                                placeholder="Enter reason or note here..."></textarea>
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 p-20 pt-0">
                    <button type="button" class="btn btn-danger fw-normal text-white" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary fw-normal text-white">
                        Update
                    </button>
                </div>

            </form>
        </div>
    </div>


@endsection

@push('scripts')
    <script>
        $('#decisionSelect').on('change', function() {
            if (this.value === 'REJECTED') {
                $('#remarksContainer').removeClass('d-none');
                $('#remarksField').attr('required', true);
            } else {
                $('#remarksContainer').addClass('d-none');
                $('#remarksField').removeAttr('required');
            }
        });

        $('.prod-check').on('change', function() {
            let checkbox = $(this);
            let itemId = checkbox.data('item-id');

            $.post("{{ route('production-voucher.item.verify') }}", {
                _token: "{{ csrf_token() }}",
                item_id: itemId
            }, function() {
                checkbox.prop('disabled', true);
                checkAllVerified();
            });
        });

        function checkAllVerified() {
            let total = $('.prod-check').length;
            let checked = $('.prod-check:checked').length;

            if (total === checked) {
                $('#productionApproveBtn').removeClass('d-none');
            }
        }
    </script>
@endpush
