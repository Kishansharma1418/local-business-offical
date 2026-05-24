    @extends('include.master')

    @section('content')
        <div class="main-content-container overflow-hidden">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
                <h3 class="mb-0">Add Prefix</h3>

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
                        <li class="breadcrumb-item active">Add Prefix</li>
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

            <form method="POST" action="{{ route('prefixes.store') }}" class="needs-validation" novalidate>
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                            <h3 class="mb-20">Prefix Information</h3>
                            <div class="row">

                                {{-- Prefix --}}
                                <div class="col-lg-6 mb-20">
                                    <label class="label fs-16 mb-2">Prefix <span class="text-danger">*</span></label>
                                    <input type="text" name="prefix_name" class="form-control"
                                        placeholder="INV / SO / EMP" required>
                                </div>

                                {{-- Module --}}
                                <div class="col-lg-6 mb-20">
                                    <label class="label fs-16 mb-2">Module <span class="text-danger">*</span></label>
                                    <select name="module" id="module" class="form-control" required>
                                        <option value="">Select Module</option>
                                        <option value="invoice">Invoice</option>
                                        <option value="sales_order">Sales Order</option>
                                        <option value="credit_note">Credit Note</option>
                                        <option value="purchase_order">Purchase Order</option>
                                        <option value="customer">Customer</option>
                                        <option value="employee">Employee</option>
                                    </select>
                                </div>

                                {{-- Start From --}}
                                <div class="col-lg-6 mb-20 d-none" id="startFromDiv">
                                    <label class="label fs-16 mb-2">Start From <span class="text-danger">*</span></label>
                                    <input type="number" name="start_from" class="form-control" value="1">
                                </div>

                                {{-- Separator --}}
                                <div class="col-lg-6 mb-20">
                                    <label class="label fs-16 mb-2">Separator</label>
                                    <input type="text" name="separator" value="-" class="form-control">
                                </div>


                                {{-- Actions --}}
                                <div class="col-lg-12 mt-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                            Prefix</button>
                                        <a href="{{ route('prefixes.index') }}"
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
