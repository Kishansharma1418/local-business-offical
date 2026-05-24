@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Add Product</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('finished-good.index') }}" class="text-decoration-none">Products List</a>
                    </li>
                    <li class="breadcrumb-item active">Add Product</li>
                </ol>
            </nav>
        </div>
        {{-- Validation Errors --}} @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Please fix the
                    following errors:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>  
        @endif 

        <form action="{{ route('finished-good.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Product Information</h3>
                        <div class="row">

                            {{-- Product Code --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Product Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" id="code" value="{{ old('code') }}"
                                    class="form-control" placeholder="E.g. PRD-001" required>
                            </div>

                            {{-- Product Name --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                                    placeholder="Please Enter Product Name" required>
                            </div>


                            {{-- HSN Code --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">HSN Code <span class="text-danger">*</span></label>
                                <input type="text" name="hsn_code" value="{{ old('hsn_code') }}" class="form-control"
                                    placeholder="E.g. 10063010" required>
                            </div>

                            {{-- Category --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select form-control" required>
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Sub Category --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Sub Category </label>
                                <select name="sub_category_id" id="sub_category_id" class="form-select form-control">
                                    <option value="">Select Sub Category</option>
                                </select>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Branch <span class="text-danger">*</span></label>
                                <select name="branch_id" id="branch_id" class="form-select form-control" required>
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Unit of Measure --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">UOM (Unit of Measure) <span
                                        class="text-danger">*</span></label>
                                <select name="uom_id" required class="form-select form-control">
                                    <option value="">Select UOM</option>
                                    @foreach ($uoms as $uom)
                                        <option value="{{ $uom->id }}"
                                            {{ old('uom_id') == $uom->id ? 'selected' : '' }}>
                                            {{ $uom->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Description --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Description</label>
                                <textarea name="description" class="form-control" placeholder="Enter product description...">{{ old('description') }}</textarea>
                            </div>

                            {{-- Record Level --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Record Level</label>
                                <input type="number" name="record_level" value="{{ old('record_level') }}"
                                    class="form-control record_level" placeholder="Please Enter Minimum Quantity "
                                    min="0">
                            </div>

                            {{-- Total Quantity --}}
                            <!-- {{-- <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Total Quantity</label>
                                <input type="number" name="total_qty" value="{{ old('total_qty') }}" class="form-control total_qty"
                                    placeholder="Please Enter Total Quantity"  min="0">
                            </div> --}} -->

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Lead Time (Days)</label>
                                <input type="number" name="lead_time_days" value="{{ old('lead_time_days') }}"
                                    class="form-control lead_time_days" placeholder="E.g. 7" min="0">
                            </div>

                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Unit Cost</label>
                                <input type="number" step="0.01" min="0" name="unit_cost"
                                    value="{{ old('unit_cost') }}" class="form-control" placeholder="Enter Unit Cost">
                            </div>

                            <!-- Base Price -->
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Base Price</label>
                                <input type="number" step="0.01" min="0" name="base_price"
                                    value="{{ old('base_price') }}" class="form-control" placeholder="Enter Base Price">
                            </div>

                            <!-- GST Rate -->
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">GST Rate</label>
                                <select name="gst_percent" id="gst_percent" class="form-select form-control">
                                    <option value="">Select GST</option>
                                    @foreach ($gstrates as $gst)
                                        <option value="{{ preg_replace('/[^0-9.]/', '', $gst->gst_rate_name) }}"
                                            {{ old('gst_percent') == preg_replace('/[^0-9.]/', '', $gst->gst_rate_name) ? 'selected' : '' }}>
                                            {{ $gst->gst_rate_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- MRP -->
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">MRP</label>
                                <input type="number" step="0.01" min="0" name="mrp"
                                    value="{{ old('mrp') }}" class="form-control" placeholder="Auto Calculated">
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Status</label>
                                <select name="status" class="form-select form-control">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                        Product</button>
                                    <a href="{{ route('finished-good.index') }}"
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
        $(document).ready(function() {

            // Backend se aaya hua data
            let categories = @json($categories);

            $('#category_id').on('change', function() {
                let categoryId = $(this).val();
                let subCategorySelect = $('#sub_category_id');

                subCategorySelect.empty()
                    .append('<option value="">Select Sub Category</option>');

                if (!categoryId) return;

                // Selected category find karo
                let selectedCategory = categories.find(c => c.id == categoryId);

                if (selectedCategory && selectedCategory.subcategories) {
                    selectedCategory.subcategories.forEach(function(sub) {
                        subCategorySelect.append(
                            `<option value="${sub.id}">${sub.category_name}</option>`
                        );
                    });
                }
            });

            // Number fields validation
            $(document).on('keydown', '.lead_time_days, .record_level', function(e) {
                if (e.key === '-' || e.key === 'e' || e.key === '+') {
                    e.preventDefault();
                }
            });

        });
    </script>



    <script>
        $(document).ready(function() {
            setupRealtimeValidation('FinishedGood', 'code', '#code');
        });
    </script>
    <script>
        function calculateMRP() {

            let base = parseFloat($('[name="base_price"]').val()) || 0;
            let gst = parseFloat($('#gst_percent').val()) || 0;

            let mrp = base + (base * gst / 100);

            $('[name="mrp"]').val(mrp.toFixed(2));
        }

        $(document).on('input change', '[name="base_price"], #gst_percent', function() {
            calculateMRP();
        });
    </script>
@endpush
