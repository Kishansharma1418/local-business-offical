@extends('include.master')

@section('content')
<div class="main-content-container overflow-hidden">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
        <h3 class="mb-0">Add Raw Material</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb align-items-center mb-0 lh-1">
                <li class="breadcrumb-item">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                        <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                        Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('rawmaterial.index') }}" class="text-decoration-none">Raw Material List</a>
                </li>
                <li class="breadcrumb-item active">Add Raw Material</li>
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

    <form action="{{ route('rawmaterial.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf

        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <h3 class="mb-20">Raw Material Information</h3>

            <div class="row">

                {{-- Code --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Material Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" id="code"
                        value="{{ old('code') }}"
                        class="form-control"
                        placeholder="E.g. RM-001" required>
                </div>

                {{-- Name --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Material Name <span class="text-danger">*</span></label>
                    <input type="text" name="name"
                        value="{{ old('name') }}"
                        class="form-control"
                        placeholder="Enter Raw Material Name" required>
                </div>

                {{-- HSN --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">HSN Code <span class="text-danger">*</span></label>
                    <input type="text" name="hsn_code"
                        value="{{ old('hsn_code') }}"
                        class="form-control"
                        placeholder="E.g. 10063010" required>
                </div>

                {{-- Raw Category --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Raw Category <span class="text-danger">*</span></label>
                    <select name="raw_category_id" id="raw_category_id"
                        class="form-select form-control" required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('raw_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sub Category --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Sub Category</label>
                    <select name="sub_rawcategory_id" id="sub_rawcategory_id"
                        class="form-select form-control">
                        <option value="">Select Sub Category</option>
                    </select>
                </div>
<div class="col-lg-6 mb-20">
    <label class="label fs-16 mb-2">Branch <span class="text-danger">*</span></label>
    <select name="branch_id" class="form-control" required>
        <option value="">Select Branch</option>
        @foreach ($branches as $branch)
            <option value="{{ $branch->id }}"
                {{ old('branch_id') == $branch->id ? 'select    ed' : '' }}>
                {{ $branch->branch_name }}
            </option>
        @endforeach
    </select>
</div>
                {{-- UOM --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">UOM <span class="text-danger">*</span></label>
                    <select name="uom_id" class="form-select form-control" required>
                        <option value="">Select UOM</option>
                        @foreach ($uom as $uom)
                        <option value="{{ $uom->id }}"
                            {{ old('uom_id') == $uom->id ? 'selected' : '' }}>
                            {{ $uom->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Specification</label>
                    <select name="specification" class="form-control">
                        <option value="">Select Specification</option>
                        <option value="IP" {{ (isset($product) && $product->specification == 'IP') ? 'selected' : '' }}>IP</option>
                        <option value="BP" {{ (isset($product) && $product->specification == 'BP') ? 'selected' : '' }}>BP</option>
                        <option value="USP" {{ (isset($product) && $product->specification == 'USP') ? 'selected' : '' }}>USP</option>
                        <option value="JP" {{ (isset($product) && $product->specification == 'JP') ? 'selected' : '' }}>JP</option>
                        <option value="IH" {{ (isset($product) && $product->specification == 'IH') ? 'selected' : '' }}>IH</option>
                         <option value="OTHER" {{ (isset($product) && $product->specification == 'OHTER') ? 'selected' : '' }}>Others</option>

                    </select>
                </div>

                {{-- Description --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Description</label>
                    <textarea name="description" class="form-control"
                        placeholder="Enter description">{{ old('description') }}</textarea>
                </div>

                {{-- Lead Time --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Lead Time (Days)</label>
                    <input type="number" name="lead_time_days"
                        value="{{ old('lead_time_days') }}"
                        class="form-control lead_time_days"
                        min="0">
                </div>

                {{-- Status --}}
                <div class="col-lg-6 mb-20">
                    <label class="label fs-16 mb-2">Status</label>
                    <select name="status" class=" form-control">
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="col-lg-12 mt-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary text-white">
                            + Add Raw Material
                        </button>
                        <a href="{{ route('rawmaterial.index') }}"
                            class="btn btn-danger text-white">
                            Cancel
                        </a>
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

        // Parent → Sub Category
        $('#raw_category_id').change(function() {
            let categoryId = $(this).val();
            let sub = $('#sub_rawcategory_id');
            sub.html('<option value="">Loading...</option>');

            if (categoryId) {
                $.get("{{ url('get-subcategories') }}/" + categoryId, function(res) {
                    sub.html('<option value="">Select Sub Category</option>');
                    res.forEach(item => {
                        sub.append(`<option value="${item.id}">${item.name}</option>`);
                    });
                });
            } else {
                sub.html('<option value="">Select Sub Category</option>');
            }
        });

        // Prevent negative & e
        $(document).on('keydown', '.lead_time_days', function(e) {
            if (e.key === '-' || e.key === 'e' || e.key === '+') {
                e.preventDefault();
            }
        });

        // Real time unique check (optional)
        setupRealtimeValidation('RawMaterial', 'code', '#code');

    });
</script>
@endpush