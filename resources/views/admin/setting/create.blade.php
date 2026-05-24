@extends('include.master')

@section('content')

    <div class="main-content-container overflow-hidden">

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0 fw-semibold">
                {{ isset($setting) ? 'Edit Setting' : 'Add Setting' }}
            </h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        {{ isset($setting) ? 'Edit Setting' : 'Add Setting' }}
                    </li>
                </ol>
            </nav>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation"
            novalidate>
            @csrf

            @if (isset($setting))
                <input type="hidden" name="id" value="{{ $setting->id }}">
            @endif

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h4 class="fw-semibold mb-3">Setting Information</h4>

                <div class="row">

                    <!-- Logo -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Logo <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                        @if (isset($setting->logo))
                            <img src="{{ asset($setting->logo) }}" alt="Logo" height="60" width="100"
                                class="mt-2">
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Favicon</label>
                        <input type="file" class="form-control" name="favicon" accept="image/*">
                        @if (isset($setting->favicon))
                            <img src="{{ asset($setting->favicon) }}" alt="fav" height="60" width="100"
                                class="mt-2">
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Company Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="company_name"
                            value="{{ $setting->company_name ?? '' }}" required>
                    </div>

                    <!-- Company Email -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Company Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="company_email"
                            value="{{ $setting->company_email ?? '' }}" required>
                    </div>

                    <!-- Company Phone -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Company Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="company_phone"
                            value="{{ $setting->company_phone ?? '' }}" required>
                    </div>

                    <!-- Company Address -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Company Address <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="company_address"
                            value="{{ $setting->company_address ?? '' }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Dl No 1</label>
                        <input type="text" class="form-control" name="dl_no_1" value="{{ $setting->dl_no_1 ?? '' }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Dl No 2</label>
                        <input type="text" class="form-control" name="dl_no_2" value="{{ $setting->dl_no_2 ?? '' }}">

                    </div>


                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">CBN Registration No</label>
                        <input type="text" class="form-control" name="cbn_registration_no"
                            value="{{ $setting->cbn_registration_no ?? '' }}">
                    </div>

                    <!-- GST -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">GST Number</label>
                        <input type="text" class="form-control" name="gst" value="{{ $setting->gst ?? '' }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Policy No</label>
                        <input type="text" class="form-control" name="policy_no"
                            value="{{ $setting->policy_no ?? '' }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">Start Date</label>
                        <input type="date" class="form-control" name="start_date" id="start_date"
                            value="{{ !empty($setting->start_date) ? \Carbon\Carbon::parse($setting->start_date)->format('Y-m-d') : '' }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-medium">End Date</label>
                        <input type="date" class="form-control" name="end_date" id="end_date"
                            value="{{ !empty($setting->end_date) ? \Carbon\Carbon::parse($setting->end_date)->format('Y-m-d') : '' }}">
                    </div>


                </div>
            </div>

            <div class="col-lg-12 mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary text-white">
                    {{ isset($setting) ? 'Update Setting' : 'Save Setting' }}
                </button>
                <a href="{{ route('dashboard') }}" class="btn btn-danger text-white">Cancel</a>

            </div>
        </form>
    </div>
    <script>
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');

        startDate.addEventListener('change', function() {
            endDate.min = this.value;

            if (endDate.value && endDate.value < this.value) {
                endDate.value = '';
            }
        });

        if (startDate.value) {
            endDate.min = startDate.value;
        }
    </script>

@endsection
