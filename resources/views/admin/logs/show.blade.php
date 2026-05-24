@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">
  
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Log Details</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        <span class="text-body fs-14 hover">Dashboard</span>
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('system-logs.index') }}" class="text-decoration-none">System Logs</a>
                </li>
                <li class="breadcrumb-item active">View Log</li>
            </ol>
        </nav>
    </div>

    <!-- Log Card -->
    <div class="card bg-white p-20 rounded-10 border border-light mb-4 shadow-sm">
        <div class="card-body">
            <h4 class="mb-3">{{ $log->module_name }} <span class="text-muted">({{ $log->action }})</span></h4>

            <div class="row mb-3">
                <div class="col-md-3">
                    <strong>Status:</strong>
                    <span class="badge {{ $log->status == '1' ? 'bg-success' : 'bg-danger' }}">
                        {{ $log->status == '1' ? 'Success' : 'Failed' }}
                    </span>
                </div>
                <div class="col-md-3"><strong>Record ID:</strong> {{ $log->record_id ?? '-' }}</div>
                <div class="col-md-3"><strong>User:</strong> {{ $log->user_id ? $log->users->full_name ?? $log->user_id : 'Guest' }}</div>
                <div class="col-md-3"><strong>IP:</strong> {{ $log->perform_ip ?? '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6"><strong>Device:</strong> {{ $log->perform_device ?? '-' }}</div>
                <div class="col-md-6"><strong>Created At:</strong> {{ formatDate($log->created_at, 'd-m-Y H:i:s') }}</div>
            </div>

            <!-- Old Data -->
            @if($log->old_data)
            <div class="accordion mb-3" id="oldDataAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingOld">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOld" aria-expanded="false" aria-controls="collapseOld">
                            Old Data
                        </button>
                    </h2>
                    <div id="collapseOld" class="accordion-collapse collapse" aria-labelledby="headingOld" data-bs-parent="#oldDataAccordion">
                        <div class="accordion-body bg-light p-3 rounded">
                            <pre class="mb-0">{{ json_encode($log->old_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($log->new_data)
            <div class="accordion mb-3" id="newDataAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingNew">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNew" aria-expanded="false" aria-controls="collapseNew">
                            New Data
                        </button>
                    </h2>
                    <div id="collapseNew" class="accordion-collapse collapse" aria-labelledby="headingNew" data-bs-parent="#newDataAccordion">
                        <div class="accordion-body bg-light p-3 rounded">
                            <pre class="mb-0">{{ json_encode($log->new_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
