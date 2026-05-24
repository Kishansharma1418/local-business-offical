@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Add Employee Bank</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}" class="text-decoration-none text-body fs-14 hover">
                            Employee List
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-secondary">Add Employee Bank</span>
                    </li>
                </ol>
            </nav>
        </div>

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

        {{-- @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('employee.bank.store') }}" enctype="multipart/form-data"
            class="needs-validation" novalidate>
            @csrf
            <input type="hidden" name="employee_id" value="{{ $employee_id }}">

            <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                <h3 class="mb-3">Employee Bank Information</h3>
                <div class="row">


                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Bank Name <span class="text-danger">*</span></label>
                        <select name="bank_name" id="bank_name" class="form-control" required>
                            <option value="">Select Bank</option>
                            @foreach ($banks as $bank)
                                <option value="{{ $bank->id }}"
                                    {{ old('bank_name', $bankDetails->banks?->name ?? '') == $bank->name ? 'selected' : '' }}>
                                    {{ $bank->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Branch Address</label>
                        <input type="text" class="form-control" name="branch_address"
                            value="{{ old('branch_address', $bankDetails->branch_address ?? '') }}">
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">IFSC Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control text-uppercase" name="ifsc_code"
                            value="{{ old('ifsc_code', $bankDetails->ifsc_code ?? '') }}" required
                            placeholder="e.g. SBIN0005943" maxlength="11" pattern="[A-Z]{4}0[A-Z0-9]{6}"
                            title="Please enter valid IFSC Code (e.g. SBIN0005943)" required
                            oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')">
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Account Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="account_number"
                            value="{{ old('account_number', $bankDetails->account_number ?? '') }}" required
                            placeholder="Enter Account Number" minlength="9" maxlength="18" pattern="[0-9]{9,18}"
                            title="Please enter valid account number (9 to 18 digits)" required
                            oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,18)">
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Confirm Account Number <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="confirm_account_number"
                            value="{{ old('confirm_account_number', $bankDetails->confirm_account_number ?? '') }}"
                            required placeholder="Enter Account Number" minlength="9" maxlength="18" pattern="[0-9]{9,18}"
                            title="Please enter valid account number (9 to 18 digits)" required
                            oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,18)">
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Upload Bank Passbook</label>
                        <input type="file" class="form-control" name="bank_passbook" accept="image/*,application/pdf">

                        @if (!empty($bankDetails->bank_passbook))
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $bankDetails->bank_passbook) }}" target="_blank"
                                    class="text-primary">
                                    View Uploaded Passbook
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-4 mb-20">
                        <label class="label fs-16 mb-2">Upload Cancelled Cheque</label>
                        <input type="file" class="form-control" name="cheque" accept="image/*,application/pdf">

                        @if (!empty($bankDetails->cheque))
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $bankDetails->cheque) }}" target="_blank"
                                    class="text-primary">
                                    View Uploaded Cheque
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-12 mt-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary fw-normal text-white">
                                {{ $bankDetails ? 'Update Bank Details' : '+ Add Employee Bank' }}
                            </button>
                            <a href="{{ route('employee.index') }}"
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
