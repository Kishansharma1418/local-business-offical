@extends('include.master')

@section('content')

<div class="main-content-container overflow-hidden">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">

        <h3 class="mb-0">Production QC Testing</h3>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">

                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('production-testing.index') }}">
                        Production Testing
                    </a>
                </li>

                <li class="breadcrumb-item active">
                    QC
                </li>

            </ol>
        </nav>

    </div>


    {{-- Batch Details --}}

    <div class="card border shadow-sm mb-4 bg-white">

        <div class="card-body">

            <div class="row">

                <div class="col-md-4">
                    <strong>Batch Number:</strong>
                    {{ $production->batch_number }}
                </div>

                <div class="col-md-4">
                    <strong>Finished Good:</strong>
                    {{ $production->bomMaster->finishedGood->name ?? '' }}
                </div>

                <div class="col-md-4">
                    <strong>MFG Date:</strong>
                    {{ \Carbon\Carbon::parse($production->mfg_date)->format('d-m-Y') }}
                </div>

            </div>

        </div>

    </div>

  <div class="card border shadow-sm mb-4 bg-white">
        <div class="card-body">

            <h5 class="text-primary mb-4">Production Flow Steps</h5>

            @php
                $completedStep = $production->current_step;
            @endphp

            <div style="position:relative; display:flex; justify-content:space-between; margin-bottom:10px;">

                {{-- Background connector line --}}
                <div style="position:absolute; top:22px; left:0; right:0; height:4px; background:#e5e7eb; z-index:0;"></div>

                @foreach ($processes as $index => $process)
                @php
                    $stepNumber = $index + 1;

                    if ($production->status == 'completed') {
                        $circleStyle = 'background:#16a34a; color:#fff;';
                        $statusBadge = '<span class="badge bg-success">Completed</span>';
                    } elseif ($completedStep > $stepNumber) {
                        $circleStyle = 'background:#16a34a; color:#fff;';
                        $statusBadge = '<span class="badge bg-success">Completed</span>';
                    } elseif ($completedStep == $stepNumber) {
                        $circleStyle = 'background:#2563eb; color:#fff; transform:scale(1.1);';
                        $statusBadge = '<span class="badge bg-warning text-dark">In Progress</span>';
                    } else {
                        $circleStyle = 'background:#e5e7eb; color:#374151;';
                        $statusBadge = '<span class="badge bg-secondary">Yet To Start</span>';
                    }
                @endphp

                <div style="position:relative; text-align:center; z-index:1; flex:1;">

                    <div style="width:45px; height:45px; border-radius:50%;
                                display:flex; align-items:center; justify-content:center;
                                margin:auto; font-weight:600; font-size:16px;
                                transition:0.3s ease; {{ $circleStyle }}">
                        {{ $stepNumber }}
                    </div>

                    <div style="margin-top:10px; font-size:13px; font-weight:600; color:#374151;">
                        {{ $process->bomType->name }}
                    </div>

                    <div class="small mt-1">
                        {!! $statusBadge !!}
                    </div>

                </div>
                @endforeach

            </div>

        </div>
    </div>

    {{-- QC REPORT UPLOAD --}}

    <div class="card border shadow-sm mb-4 bg-white">

        <div class="card-body">

            <h5 class="text-primary mb-3">
                Upload QC Report
            </h5>

            <form action="{{ route('production-testing.store') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <input type="hidden" name="production_id" value="{{ $production->id }}">
                <input type="hidden" name="step" value="{{ $production->current_step }}">

                <div class="row">

                    <div class="col-md-6">

                        <label>QC Report</label>

                        <input type="file"
                            name="qc_report"
                            class="form-control"
                            required>

                    </div>

                    <div class="col-md-6 d-flex align-items-end">

                        <button class="btn btn-primary text-white">
                            Upload Report
                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>



    {{-- QC REPORT HISTORY --}}
@if($qcReports->count() > 0)
    <div class="card border shadow-sm bg-white">

        <div class="card-body">

            <h5 class="text-primary mb-3">
                QC Reports
            </h5>

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="bg-white">

                        <tr>

                            <th>#</th>
                            <th>Report</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($qcReports as $qc)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>

                                <a href="{{ asset('storage/'.$qc->report_path) }}"
                                    target="_blank"
                                   >

                                    View Report

                                </a>

                            </td>

                            <td>

                                @if($qc->status == 'approved')

                                <span class="badge bg-success">
                                    Approved
                                </span>

                                @elseif($qc->status == 'rejected')

                                <span class="badge bg-danger">
                                    Rejected
                                </span>

                                @else

                                <span class="badge bg-warning">
                                    Pending
                                </span>

                                @endif

                            </td>

                            <td>

                                {{ $qc->remarks ?? '-' }}

                            </td>

                            <td>

                                @if(!$qc->status)

                                <form action="{{ route('production-testing.update',$production->id) }}"
                                    method="POST">

                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" name="qc_id" value="{{ $qc->id }}">

                                    <div class="d-flex gap-2">

                                        <select name="status" class="form-control" required>

                                            <option value="approved">
                                                Approve
                                            </option>

                                            <option value="rejected">
                                                Reject
                                            </option>

                                        </select>

                                        <input type="text"
                                            name="remarks"
                                            placeholder="Remarks"
                                            class="form-control"
                                            required>

                                        <button class="btn btn-primary text-white btn-sm">
                                            Submit
                                        </button>

                                    </div>

                                </form>

                                @endif

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>

    </div>

@endif
</div>

@endsection