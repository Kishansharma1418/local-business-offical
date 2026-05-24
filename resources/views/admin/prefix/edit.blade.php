@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Prefix</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('prefixes.index') }}" class="text-decoration-none">Prefix List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Prefix</li>
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

        <form method="POST" action="{{ route('prefixes.update', $prefix->id) }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Prefix Information</h3>

                        <div class="row">

                            {{-- Prefix --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Prefix <span class="text-danger">*</span></label>
                                <input type="text" name="prefix_name" class="form-control"
                                    value="{{ old('prefix_name', $prefix->prefix) }}" placeholder="INV / SO / EMP" required>
                            </div>

                            {{-- Module --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Module <span class="text-danger">*</span></label>
                                <select name="module" id="module" class="form-control" required>
                                    <option value="">Select Module</option>
                                    <option value="invoice" {{ $prefix->module == 'invoice' ? 'selected' : '' }}>Invoice
                                    </option>
                                    <option value="sales_order" {{ $prefix->module == 'sales_order' ? 'selected' : '' }}>
                                        Sales Order</option>
                                    <option value="credit_note" {{ $prefix->module == 'credit_note' ? 'selected' : '' }}>
                                        Credit Note</option>
                                    <option value="purchase_order"
                                        {{ $prefix->module == 'purchase_order' ? 'selected' : '' }}>Purchase Order</option>
                                    <option value="customer" {{ $prefix->module == 'customer' ? 'selected' : '' }}>Customer
                                    </option>
                                    <option value="employee" {{ $prefix->module == 'employee' ? 'selected' : '' }}>Employee
                                    </option>
                                </select>
                            </div>

                            {{-- Start From --}}
                            <div class="col-lg-6 mb-20 {{ in_array($prefix->module, ['invoice', 'sales_order', 'credit_note', 'purchase_order']) ? '' : 'd-none' }}"
                                id="startFromDiv">
                                <label class="label fs-16 mb-2">Start From <span class="text-danger">*</span></label>
                                <input type="number" name="start_from" class="form-control"
                                    value="{{ old('start_from', $prefix->start_from) }}">
                            </div>

                            {{-- Separator --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Separator</label>
                                <input type="text" name="separator" class="form-control"
                                    value="{{ old('separator', $prefix->separator ?? '-') }}">
                            </div>

                            {{-- Actions --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary text-white">
                                        Update Prefix
                                    </button>
                                    <a href="{{ route('prefixes.index') }}" class="btn btn-danger text-white">
                                        Cancel
                                    </a>
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
        document.getElementById('module').addEventListener('change', function() {
            const startFromDiv = document.getElementById('startFromDiv');

            if (this.value === 'invoice' || this.value === 'sales_order' || this.value === 'credit_note' || this
                .value === 'purchase_order') {
                startFromDiv.classList.remove('d-none');
            } else {
                startFromDiv.classList.add('d-none');
            }
        });
    </script>
@endpush
