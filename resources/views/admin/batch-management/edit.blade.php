@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Batch</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('batch-management.index') }}" class="text-decoration-none">Batch List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Batch</li>
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

        <form action="{{ route('batch-management.update', $batch->id) }}" method="POST" class="needs-validation"
            novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Batch Information</h3>

                        <div class="row">

                            {{-- Product --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Product <span class="text-danger">*</span></label>
                                <select name="product_id" class="form-select form-control" required>
                                    <option value="">Select Product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}"
                                            {{ old('product_id', $batch->product_id) == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Batch Number --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Batch Number <span class="text-danger">*</span></label>
                                <input type="text" name="batch_number"
                                    id='batch_number'value="{{ old('batch_number', $batch->batch_number) }}"
                                    class="form-control" required>
                            </div>

                            {{-- Manufacturing Date --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Manufacturing Date <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="manufacturing_date"
                                    value="{{ old('manufacturing_date', $batch->manufacturing_date) }}" class="form-control"
                                    required>
                            </div>

                            {{-- Expiry Date --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Expiry Date <span class="text-danger">*</span></label>
                                <input type="date" name="expiry_date"
                                    value="{{ old('expiry_date', $batch->expiry_date) }}" class="form-control" required>
                            </div>

                            {{-- Warehouse --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Warehouse <span class="text-danger">*</span></label>
                                <select name="warehouse_id" class=" form-control" required>
                                    <option value="">Select Warehouse</option>
                                    @foreach ($warehouses as $warehouse)
                                        <option value="{{ $warehouse->id }}"
                                            {{ old('warehouse_id', $batch->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                            {{ $warehouse->warehouse_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Available Quantity --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Available Quantity <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="available_quantity"
                                    value="{{ old('available_quantity', $batch->available_quantity) }}"
                                    class="form-control" required min="0" onkeydown="return event.key !== '-'"
                                    oninput="this.value = this.value < 0 ? 0 : this.value">
                            </div>

                            {{-- Unit Cost --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Unit Cost (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="unit_cost" value="{{ old('unit_cost', $batch->unit_cost) }}"
                                    class="form-control" required min="0">
                            </div>

                            {{-- Base Price --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Base Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="base_price" id="base_price"
                                    value="{{ old('base_price', $batch->base_price) }}" class="form-control" required
                                    min="0">
                            </div>

                            {{-- GST Percent --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">GST (%) <span class="text-danger">*</span></label>
                                <select name="gst_percent" id="gst_percent" class="form-control" required>
                                    <option value="">Select GST %</option>
                                    @foreach ($gstPercents as $gst)
                                        <option value="{{ $gst->gst_rate_name }}"
                                            {{ old('gst_percent', $batch->gst_percent) == $gst->gst_rate_name ? 'selected' : '' }}>
                                            {{ $gst->gst_rate_name }}%
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- MRP --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">MRP (₹) <span class="text-danger">*</span></label>
                                <input type="number" step="0.01" name="mrp" id="mrp"
                                    value="{{ old('mrp', $batch->mrp) }}" class="form-control" readonly required>
                            </div>


                            {{-- Actions --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">
                                        Update Batch
                                    </button>
                                    <a href="{{ route('batch-management.index') }}"
                                        class="btn btn-danger fw-normal text-white">
                                        Cancel
                                    </a>
                                </div>
                            </div>

                        </div> {{-- /.row --}}
                    </div> {{-- /.card --}}
                </div> {{-- /.col --}}
            </div> {{-- /.row --}}
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            const recordId = "{{ encrypt($batch->id) }}";
            setupRealtimeValidation('BatchManagement', 'batch_number', 'input[name="batch_number"]', recordId);
        });
    </script>
    <script>
        $(document).ready(function() {
            const recordId = "{{ encrypt($batch->id) }}";
            setupRealtimeValidation('BatchManagement', 'batch_number', 'input[name="batch_number"]', recordId);

            // 🧮 Function to auto-calculate MRP
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


            $('#base_price, #gst_percent').on('input change', calculateMRP);


            calculateMRP();
        });
    </script>
@endpush
