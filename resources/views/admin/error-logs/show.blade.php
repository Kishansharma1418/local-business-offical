@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Error Log Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('error-logs.index') }}" class="text-decoration-none">Error Logs</a>
                </li>
                <li class="breadcrumb-item active">View Error Log</li>
            </ol>
        </nav>
    </div>

    <!-- Error Details Card -->
    <div class="card bg-white p-20 rounded-10 border border-light mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="mb-3 text-danger">
                {{ $errorLog->module_name ?? 'Unknown Module' }}
                <span class="text-muted">({{ $errorLog->error_code ?? 'N/A' }})</span>
            </h4>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Status:</strong>
                    @php
                        $statusLabels = ['0' => 'Open', '1' => 'Resolved', '2' => 'In Progress', '3' => 'Ignored'];
                        $statusColors = ['0' => 'bg-danger', '1' => 'bg-success', '2' => 'bg-warning text-dark', '3' => 'bg-secondary'];
                    @endphp
                    <span class="badge {{ $statusColors[$errorLog->status] ?? 'bg-secondary' }}">
                        {{ $statusLabels[$errorLog->status] ?? 'Unknown' }}
                    </span>
                </div>

                <div class="col-md-3"><strong>Record ID:</strong> {{ $errorLog->record_id ?? '-' }}</div>
                <div class="col-md-3"><strong>User:</strong> {{ $errorLog->users?->full_name ?? 'Guest' }}</div>
                <div class="col-md-3"><strong>IP:</strong> {{ $errorLog->request_ip ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6"><strong>Device:</strong> {{ $errorLog->device_info ?? '-' }}</div>
                <div class="col-md-6"><strong>Created At:</strong> {{ $errorLog->created_at->format('d M, Y H:i:s') }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <strong>URL:</strong>
                    <a href="{{ $errorLog->error_url }}" target="_blank" class="text-primary text-decoration-underline">
                        {{ $errorLog->error_url }}
                    </a>
                </div>
            </div>

            <hr>

            <!-- Error Message -->
            <div class="mb-3">
                <h5 class="text-danger"><i class="ri-error-warning-line me-2"></i>Error Message</h5>
                <div class="bg-light p-3 rounded border">
                    <code>{{ $errorLog->error_msg }}</code>
                </div>
            </div>

            <!-- Function -->
            <div class="mb-3">
                <strong>Function Name:</strong> <span class="text-muted">{{ $errorLog->function_name ?? '-' }}</span>
            </div>

            <!-- Debugging / Raw Info Accordion -->
            <div class="accordion" id="debugAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingDebug">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseDebug" aria-expanded="false" aria-controls="collapseDebug">
                            View Raw JSON (For Developers)
                        </button>
                    </h2>
                    <div id="collapseDebug" class="accordion-collapse collapse" aria-labelledby="headingDebug"
                        data-bs-parent="#debugAccordion">
                        <div class="accordion-body bg-light p-3 rounded">
                            <pre class="mb-0">{{ json_encode($errorLog, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
