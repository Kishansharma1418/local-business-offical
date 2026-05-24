@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Add GST Rate</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                      
                        <a href="{{ route('gst-rates.index') }}" class="text-decoration-none">GST Rate List</a>
                    
                    </li>
                    <li class="breadcrumb-item active">Add GST Rate</li>
                </ol>
            </nav>
        </div>

        {{-- Validation Errors --}}
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

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('gst-rates.store') }}" class="needs-validation" novalidate>
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">GST Rate Information</h3>
                        <div class="row">
                        
                            {{-- Gst Rate --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">GST Rate <span class="text-danger">*</span></label>
                                <input type="number" name="gst_rate_name" max="100"  required onkeydown="return event.key !== '-'"
                                    oninput="this.value = this.value < 0 ? 0 : this.value" value="{{ old('gst_rate_name') }}" class="form-control"
                                 placeholder="Please Enter GST Rate"  step="0.01" min="0"
                                    required>
                            </div>
                  
                            <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">CGST Rate <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="cgst_rate" readonly
                                value="{{ old('cgst_rate') }}" 
                                class="form-control" 
                                placeholder="Please Enter CGST Rate"
                                required>
                        </div>

                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">SGST Rate <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="sgst_rate" readonly
                                value="{{ old('sgst_rate') }}" 
                                class="form-control" 
                                placeholder="Please Enter SGST Rate"
                                required>
                        </div>

                        <div class="col-lg-6 mb-20">
                            <label class="label fs-16 mb-2">IGST Rate <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="igst_rate" readonly
                                value="{{ old('igst_rate') }}" 
                                class="form-control" 
                                placeholder="Please Enter IGST Rate"
                                required>
                        </div>

                            {{-- Actions --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                        GST Rate</button>
                                    <a href="{{ route('gst-rates.index') }}"
                                        class="btn btn-danger fw-normal text-white">Cancel</a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    $('form').on('submit', function (e) {
        let form = $(this)[0];

        if (!form.checkValidity()) {
            e.preventDefault(); 
            e.stopPropagation(); 
            $(this).addClass('was-validated'); 
            return false;
        }

        $(this).find('button[type="submit"]')
            .prop('disabled', true)
            .text('Processing...');
    });
});
</script>
@push('scripts')
<script>
$(document).ready(function () {
    // ✅ Auto-fill CGST, SGST, IGST when GST Rate changes
    $('input[name="gst_rate_name"]').on('input', function () {
        let gst = parseFloat($(this).val()) || 0;

        // Half GST for CGST and SGST
        let half = (gst / 2).toFixed(2);

        $('input[name="cgst_rate"]').val(half);
        $('input[name="sgst_rate"]').val(half);

        // Full GST for IGST
        $('input[name="igst_rate"]').val(gst.toFixed(2));
    });

    // ✅ Prevent multiple submit clicks
    $('form').on('submit', function (e) {
        let form = $(this)[0];

        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            $(this).addClass('was-validated');
            return false;
        }

        $(this).find('button[type="submit"]')
            .prop('disabled', true)
            .text('Processing...');
    });
});
</script>
@endpush

@endpush
