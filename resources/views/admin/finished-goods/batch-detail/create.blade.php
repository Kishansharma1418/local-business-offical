@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">{{ $batchmanagement ? 'Edit' : 'Add' }} Batch Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('finished-good.index') }}" class="text-decoration-none text-body fs-14 hover">
                            Finished Goods List
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-secondary">{{ $batchmanagement ? 'Edit' : 'Add' }} Batch Details</span>
                    </li>
                </ol>
            </nav>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Session Error --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Form -->
        <form method="POST"
            action="{{ $batchmanagement ? route('batch-management.update', $batchmanagement->id) : route('batch-management.store') }}"
            enctype="multipart/form-data" class="needs-validation" novalidate>
            @csrf
            @if ($batchmanagement)
                @method('PUT')
            @endif

            <input type="hidden" name="finished_goods_id" value="{{ $finishGoods->id }}">

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h3 class="mb-3">Batch Details</h3>

                <div class="row">

                    <input type="hidden" name="product_id" value="{{ $finishGoods->id }}">
                    <!-- Batch Number -->
                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Batch Number <span class="text-danger">*</span></label>
                        <input type="text" name="batch_number" id='batch_number' class="form-control"
                            value="{{ old('batch_number', $batchmanagement->batch_number ?? '') }}" required>
                    </div>

                    <!-- Manufacturing Date -->
                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Manufacturing Date<span class="text-danger">*</span></label>
                        <input type="date" name="manufacturing_date" class="form-control"
                            value="{{ old('manufacturing_date', $batchmanagement->manufacturing_date ?? '') }}"required>
                    </div>

                    <!-- Expiry Date -->
                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Expiry Date<span class="text-danger">*</span></label>
                        <input type="date" name="expiry_date" class="form-control"
                            value="{{ old('expiry_date', $batchmanagement->expiry_date ?? '') }}"required>
                    </div>

                    <!-- Warehouse -->
                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Warehouse <span class="text-danger">*</span></label>
                        <select name="warehouse_id" class=" form-control" required>
                            <option value="">Select Warehouse</option>
                            @foreach ($warehouses as $wh)
                                <option value="{{ $wh->id }}"
                                    {{ old('warehouse_id', $batchmanagement->warehouse_id ?? '') == $wh->id ? 'selected' : '' }}>
                                    {{ $wh->warehouse_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                    <!-- Available Quantity -->
                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Available Quantity<span class="text-danger">*</span></label>
                        <input type="number" name="available_quantity" class="form-control" required onkeydown="return event.key !== '-'"
                                    oninput="this.value = this.value < 0 ? 0 : this.value"
                            value="{{ old('available_quantity', $batchmanagement->available_quantity ?? '') }}"required>
                    </div>

                    <!-- Unit Cost -->
                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Unit Cost<span class="text-danger">*</span></label>
                        <input type="text" name="unit_cost" class="form-control" required onkeydown="return event.key !== '-'"
                                    oninput="this.value = this.value < 0 ? 0 : this.value"
                            value="{{ old('unit_cost', $batchmanagement->unit_cost ?? '') }}"required>
                    </div>

                    <!-- Base Price -->
                    <!-- Base Price -->
                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">Base Price (₹)<span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="base_price" id="base_price" class="form-control" required min='0'
                            value="{{ old('base_price', $batchmanagement->base_price ?? '') }}" required>
                    </div>

                    <!-- GST Percent -->
                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">GST (%)<span class="text-danger">*</span></label>
                        <select name="gst_percent" id="gst_percent" class=" form-control" required>
                            <option value="">Select GST %</option>
                            @foreach ([5, 12, 20, 40] as $gst)
                                <option value="{{ $gst }}"
                                    {{ old('gst_percent', $batchmanagement->gst_percent ?? '') == $gst ? 'selected' : '' }}>
                                    {{ $gst }}%
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- MRP -->
                    <div class="col-lg-6 mb-20">
                        <label class="label fs-16 mb-2">MRP (₹)<span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="mrp" id="mrp" class="form-control"
                            value="{{ old('mrp', $batchmanagement->mrp ?? '') }}" readonly required>
                    </div>


                    <!-- Buttons -->
                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-normal text-white">

                                {{ $batchmanagement ? 'Update Batch Details' : '+ Add Batch Details' }}
                            </button>
                            <a href="{{ route('finished-good.index') }}"
                                class="btn btn-danger fw-normal text-white">Cancel</a>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('form').on('submit', function(e) {
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
@push('scripts')
    <script>
        $(document).ready(function() {
            setupRealtimeValidation('BatchManagement', 'batch_number', '#batch_number');
        });
    </script>
    @push('scripts')
        <script>
            $(document).ready(function() {
                // ✅ Real-time validation setup
                setupRealtimeValidation('BatchManagement', 'batch_number', '#batch_number');

                // 🧮 Auto-calculate MRP function
                function calculateMRP() {
                    let basePrice = parseFloat($('#base_price').val());
                    let gstPercent = parseFloat($('#gst_percent').val());

                    if (!isNaN(basePrice) && !isNaN(gstPercent)) {
                        let mrp = basePrice + (basePrice * gstPercent / 100);
                        $('#mrp').val(mrp.toFixed(2));
                    } else {
                        $('#mrp').val('');
                    }
                }

                // Trigger calculation on change/input
                $('#base_price, #gst_percent').on('input change', calculateMRP);

                // 🧾 Recalculate on edit page load (if data already present)
                calculateMRP();

                // ✅ Disable double submit
                $('form').on('submit', function(e) {
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
