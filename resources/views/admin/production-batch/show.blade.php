@extends('include.master')

@section('content')
    <style>
        .main-content-container .card {
            background: #ffffff;
        }

        .main-content-container table,
        .main-content-container table th,
        .main-content-container table td {
            background: #ffffff !important;
        }

        .main-content-container .card {
            box-shadow: none;
            border: 1px solid #e5e7eb;
        }

        .main-content-container table th,
        .main-content-container table td {
            padding: 6px 8px;
            font-size: 13px;
        }

        .company-header {
            margin-bottom: 14px;
        }

        .main-content-container table {
            margin-bottom: 14px;
        }
    </style>

    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">View Requisition Batch</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('production-batch.index') }}" class="text-decoration-none">Requisition Batch</a>
                    </li>
                    <li class="breadcrumb-item active">View Requisition Batch</li>

                </ol>
            </nav>
        </div>

        <!-- ACTION BUTTONS -->


        <div class="card p-3">
            <div class="d-flex justify-content-end gap-2 mb-3">
                <a href="{{ route('production-batch.pdf', $batch->id) }}" class="btn btn-sm btn-primary text-white">
                    <i class="ri-file-pdf-line"></i> PDF
                </a>

                @if (!auth()->user()->hasRole('admin'))
                    @if (auth()->user()->hasRole('Chemist') && $batch->status === 'PENDING_CHEMIST')
                        <button class="btn btn-sm btn-primary text-white" data-bs-toggle="modal"
                            data-bs-target="#approvalModal">
                            Approve (Chemist)
                        </button>
                    @endif

                    @if (auth()->user()->hasRole('HEAD PRODUCTION') && $batch->status === 'PENDING_HEAD_PRODUCTION')
                        <button class="btn btn-sm btn-primary text-white" data-bs-toggle="modal"
                            data-bs-target="#approvalModal">
                            Approve (Requisition)
                        </button>
                    @endif

                    @if (auth()->user()->hasRole('HEAD QA') && $batch->status === 'PENDING_QA')
                        <button class="btn btn-sm btn-primary text-white" data-bs-toggle="modal"
                            data-bs-target="#approvalModal">
                            Approve (QA)
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

            <!-- BATCH DETAILS -->
            <table class="table table-bordered">
                <tr>
                    <th width="20%">Product Name</th>
                    <td width="30%" class="fw-semibold">{{ $batch->bomMaster->finishedGood->name }}</td>
                    <th width="20%">Batch No.</th>
                    <td width="30%">{{ $batch->batch_number }}</td>
                </tr>
                <tr>
                    <th>Material Req. No.</th>
                    <td colspan="3">{{ $batch->material_requisition_no }}</td>
                </tr>
                <tr>
                    <th>Line Clearance</th>
                    <td>{{ $batch->line_clearance_given_by ?? '-' }}</td>
                    {{-- <th>Mfg Date</th>
                    <td>{{ \Carbon\Carbon::parse($batch->mfg_date)->format('d-m-Y') }}</td> --}}
                </tr>
                <tr>
                    <th width="20%">RM Issued On</th>
                    <td width="30%">
                        {{ $batch->raw_material_issued_on
                            ? \Carbon\Carbon::parse($batch->raw_material_issued_on)->format('d-m-Y')
                            : '-' }}
                    </td>
                </tr>


            </table>

            <!-- MATERIAL TABLE -->
            <table class="table table-bordered text-center align-middle">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Material</th>
                        <th>Spec</th>
                        <th>Batch Qty</th>
                        <th>Overage</th>
                        <th>Total Qty</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($batch->items as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $item->material->name }}</td>
                             <td>{{ $item->material->specification ?? '-'}}</td>
                            <td>{{ number_format($item->base_quantity, 3) }} {{ strtoupper($item->uoms?->name) }}</td>
                            <td>{{ $item->overage_percent }}%</td>
                            <td class="fw-semibold">
                                {{ number_format($item->final_quantity, 3) }} {{ strtoupper($item->uoms?->name) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- APPROVALS -->
            <table class="table table-bordered text-center mt-3">
                <tr>
                    <td width="33%">
                        <strong>Requisition Chemist</strong><br>(Signature & Date)</br>
                        <div class="approval-box">
                            @if ($chemistApproval)
                                <div class="signature-line"></div>
                                <div class="approval-name">{{ $chemistApproval->approver->full_name }}</div>
                                <div class="approval-date">
                                    {{ \Carbon\Carbon::parse($chemistApproval->approval_date)->format('d-m-Y') }}</div>
                            @else
                                <div class="pending-text">Pending</div>
                            @endif
                        </div>
                    </td>

                    <td width="33%">
                        <strong>Head Production</strong><br>(Signature & Date)</br>
                        <div class="approval-box">
                            @if ($productionApproval)
                                <div class="signature-line"></div>
                                <div class="approval-name">{{ $productionApproval->approver->full_name }}</div>
                                <div class="approval-date">
                                    {{ \Carbon\Carbon::parse($productionApproval->approval_date)->format('d-m-Y') }}</div>
                            @else
                                <div class="pending-text">Pending</div>
                            @endif
                        </div>
                    </td>

                    <td width="33%">
                        <strong>Head QA</strong><br>(Signature & Date)</br>
                        <div class="approval-box">
                            @if ($qaApproval)
                                <div class="signature-line"></div>
                                <div class="approval-name">{{ $qaApproval->approver->full_name }}</div>
                                <div class="approval-date">
                                    {{ \Carbon\Carbon::parse($qaApproval->approval_date)->format('d-m-Y') }}</div>
                            @else
                                <div class="pending-text">Pending</div>
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

            <div class="small mt-2">
                <strong>Note:</strong> Attach sheet if required.
            </div>

        </div>
    </div>

    <div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered rounded-10" style="max-width: 550px;">
            <form method="POST" action="{{ route('production-batch.approve', $batch->id) }}"
                class="modal-content bg-white">
                @csrf

                <!-- HEADER -->
                <div class="modal-header border-border-color-40 p-20">
                    <h5 class="modal-title fs-18 fw-medium mb-0">
                        Update Approval Status
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
    </script>
@endpush
