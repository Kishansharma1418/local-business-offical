@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        {{-- Header & Breadcrumb --}}
        <div class="d-flex justify-content-between align-items-center gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Documents</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('documents.index') }}" class="text-decoration-none">Employee Documents</a>
                    </li>
                    <li class="breadcrumb-item active">View Documents</li>
                </ol>
            </nav>
        </div>

        {{-- Documents Cards --}}
        <div class="row g-4">
            @forelse($documents as $doc)
                <div class="col-md-4">
                    <div class="card bg-white p-3 rounded-10 border border-light shadow-sm h-100">

                        {{-- Status Badge --}}
                        @php
                            $badgeClass = match ($doc->status) {
                                'Verified' => 'bg-success',
                                'Rejected' => 'bg-danger',
                                'Expired' => 'bg-warning',
                                default => 'bg-secondary',
                            };

                            // Filepaths array (single or multiple)
                            $paths = is_array(json_decode($doc->document_filepath, true))
                                ? json_decode($doc->document_filepath, true)
                                : [$doc->document_filepath];
                        @endphp

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">{{ $doc->document_type }}</h5>
                            <span class="badge {{ $badgeClass }}">{{ $doc->status }}</span>
                        </div>

                        {{-- Document File(s) --}}
                        @foreach ($paths as $path)
                            @php $ext = pathinfo($path, PATHINFO_EXTENSION); @endphp
                            @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']))
                                <img src="{{ asset('storage/' . $path) }}"
                                    style="width:100%; height:150px; object-fit:cover; margin-bottom:5px;" alt="Document">
                            @elseif(strtolower($ext) === 'pdf')
                                <a href="{{ asset('storage/' . $path) }}" target="_blank" class="d-block mb-2">
                                    View PDF
                                </a>
                            @else
                                <span>File Not Preview</span>
                            @endif
                        @endforeach

                        {{-- Change Status --}}
                        <form action="{{ route('documents.update_status', $doc->id) }}" method="POST" class="mt-3">
                            @csrf
                            @method('PATCH')
                            <div class="input-group">
                                <select name="status" class="form-select form-select-sm">
                                    <option value="Pending" {{ $doc->status == 'Pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="Verified" {{ $doc->status == 'Verified' ? 'selected' : '' }}>Verified
                                    </option>
                                    <option value="Rejected" {{ $doc->status == 'Rejected' ? 'selected' : '' }}>Rejected
                                    </option>
                                    <option value="Expired" {{ $doc->status == 'Expired' ? 'selected' : '' }}>Expired
                                    </option>
                                </select>
                                <button class="btn btn-primary btn-sm" type="submit">Update</button>
                            </div>
                        </form>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">
                    No documents found.
                </div>
            @endforelse
        </div>

    </div>
@endsection
