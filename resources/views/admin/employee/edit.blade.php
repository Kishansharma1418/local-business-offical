@extends('include.master')
@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Employee</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}" class="d-flex align-items-center text-decoration-none">
                            <span class="text-body fs-14 hover">Employee List</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">
                        <span class="text-secondary">Edit Employee</span>
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
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('employee.update', encrypt($employee->id)) }}" enctype="multipart/form-data"
            class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Employee Information</h3>
                        <div class="row">
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Employee Type <span class="text-danger">*</span></label>
                                <select class="form-control" name="role" required>
                                    <option value="other" {{ old('role', $employee->role) == 'other' ? 'selected' : '' }}>
                                        Other
                                    </option>

                                    <option value="sales" {{ old('role', $employee->role) == 'sales' ? 'selected' : '' }}>
                                        Sales
                                    </option>
                                </select>
                            </div>

                            {{-- Name --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                    name="first_name" value="{{ old('first_name', $employee->first_name) }}" required>
                                @error('first_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Middle Name</label>
                                <input type="text" class="form-control" name="middle_name"
                                    value="{{ old('middle_name', $employee->middle_name) }}">
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                    name="last_name" value="{{ old('last_name', $employee->last_name) }}" required>
                                @error('last_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Gender & DOB --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Gender<span class="text-danger">*</span></label>
                                <select class="form-control" required name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="Male"
                                        {{ old('gender', $employee->gender) == 'Male' ? 'selected' : '' }}>
                                        Male</option>
                                    <option value="Female"
                                        {{ old('gender', $employee->gender) == 'Female' ? 'selected' : '' }}>
                                        Female</option>
                                    <option value="Other"
                                        {{ old('gender', $employee->gender) == 'Other' ? 'selected' : '' }}>
                                        Other</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">
                                    Date of Birth <span class="text-danger">*</span>
                                </label>

                                <input type="date" class="form-control" name="dob" required
                                    max="{{ now()->subYears(15)->format('Y-m-d') }}"
                                    value="{{ old('dob', $employee->dob ?? now()->subYears(15)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Father's Name</label>
                                <input type="text" class="form-control" name="fathers_name"
                                    value="{{ old('fathers_name', $employee->fathers_name) }}">
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Employee Code<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="code"
                                    value="{{ old('code', $employee->code) }}" required>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Reporting Manager</label>
                                <select name="reporting_id" class="form-select form-control">
                                    <option value="">Select Employee</option>

                                    @foreach ($employees as $emp)
                                        @if ($emp->id != $employee->id)
                                            <option value="{{ $emp->id }}"
                                                {{ old('reporting_id', $employee->reporting_id) == $emp->id ? 'selected' : '' }}>
                                                {{ $emp->full_name }}
                                            </option>
                                        @endif
                                    @endforeach

                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Sales Head<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sales_head"
                                    value="{{ old('sales_head', $employee->sales_head) }}" required>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Branch</label>
                                <select name="branch_id" id="branch" class="form-control">
                                    <option value="">Select Branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', $employee->branch_id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}</option>
                                    @endforeach
                                </select>   
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Department</label>
                                <select name="department_id" id="department" class="form-control">
                                    <option value="">Select Department</option>
                                    @foreach ($department as $depart)
                                        <option value="{{ $depart->id }}"
                                            {{ old('department_id', $employee->department_id) == $depart->id ? 'selected' : '' }}>
                                            {{ $depart->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Designation<span class="text-danger">*</span></label>
                                <select name="designation_id" id="designation_id" class="form-control">
                                    <option value="">Select Designation</option>
                                    @foreach ($designaions as $desinat)
                                        <option value="{{ $desinat->id }}"
                                            {{ old('designation_id', $employee->designation_id) == $desinat->id ? 'selected' : '' }}>
                                            {{ $desinat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Emails & Phone --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Official Email<span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="official_mail" required
                                    value="{{ old('official_mail', $employee->official_mail) }}"> 
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Personal Email</label>
                                <input type="email" class="form-control" name="personal_mail"
                                    value="{{ old('personal_mail', $employee->personal_mail) }}">
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Mobile No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="mobile_no"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    value="{{ old('mobile_no', $employee->mobile_no) }}" maxlength="10" required>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Alternative No</label>
                                <input type="text" class="form-control" name="alternative_no"
                                    value="{{ old('alternative_no', $employee->alternative_no) }}" maxlength="10">
                            </div>

                            {{-- Joining / Resignation --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Joining Date<span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="joining_date" id="joining_date"
                                    required value="{{ old('joining_date', $employee->joining_date) }}">
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Relieving Date</label>
                                <input type="date" class="form-control" id="relieving_date" name="relieving_date"
                                    value="{{ old('relieving_date', $employee->relieving_date) }}">
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Separation Type</label>
                                <select class="form-control" name="separation_type">
                                    <option value="">Select Separation Type</option>

                                    <option value="Resignation"
                                        {{ old('separation_type', $employee->separation_type) == 'Resignation' ? 'selected' : '' }}>
                                        Resignation</option>
                                    <option value="Termination"
                                        {{ old('separation_type', $employee->separation_type) == 'Termination' ? 'selected' : '' }}>
                                        Termination</option>
                                    <option value="Absconding"
                                        {{ old('separation_type', $employee->separation_type) == 'Absconding' ? 'selected' : '' }}>
                                        Absconding</option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Separation Remark</label>
                                <textarea class="form-control" name="separation_remarks" placeholder="Separation Remark">{{ old('relieving_date', $employee->separation_remarks) }}</textarea>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Relieving Approved Date</label>
                                <input type="date" class="form-control" id="relieving_approvel_date"
                                    name="relieving_approvel_date"
                                    value="{{ old('relieving_approvel_date', $employee->relieving_approvel_date) }}">
                            </div>

                            {{-- Country / State / City --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Country</label>
                                <select name="country_id" id="country" class="form-control">
                                    <option value="">Select Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('country_id', $employee->country_id) == $country->id ? 'selected' : '' }}>
                                            {{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">State</label>
                                <select name="state_id" id="state" class="form-control">
                                    <option value="">Select State</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">City</label>
                                <select name="city_id" id="city" class="form-control">
                                    <option value="">Select City</option>
                                </select>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Employee Image</label>
                                <input type="file" class="form-control" name="employee_image" accept="image/*">
                                @if ($employee->employee_image)
                                    <img src="{{ asset('storage/' . $employee->employee_image) }}" alt="Employee Image"
                                        style="max-width: 100px; margin-top: 10px;">
                                @endif
                            </div>

                            {{-- Address --}}
                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Address Line 1<span class="text-danger">*</span></label>
                                <textarea class="form-control" name="address_line1" placeholder="Residential Address" required>{{ old('address_line1', $employee->address_line1) }}</textarea>
                            </div>
                            <div class="col-lg-12 mb-20">
                                <label class="label fs-16 mb-2">Address Line 2</label>
                                <textarea class="form-control" name="address_line2">{{ old('address_line2', $employee->address_line2) }}</textarea>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Pincode</label>
                                <input type="text" class="form-control" name="pincode"
                                    value="{{ old('pincode', $employee->pincode) }}" maxlength="6"
                                    pattern="^[1-9][0-9]{5}$" title="Enter a valid 6-digit Indian Pincode"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);">
                            </div>


                            {{-- Marital Status / Blood Group --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Marital Status</label>
                                <select class="form-control" name="marital_status">
                                    <option value="">Select</option>
                                    <option value="Single"
                                        {{ old('marital_status', $employee->marital_status) == 'Single' ? 'selected' : '' }}>
                                        Single</option>
                                    <option value="Married"
                                        {{ old('marital_status', $employee->marital_status) == 'Married' ? 'selected' : '' }}>
                                        Married</option>
                                    <option value="Other"
                                        {{ old('marital_status', $employee->marital_status) == 'Other' ? 'selected' : '' }}>
                                        Other
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Blood Group</label>
                                <input type="text" class="form-control" name="blood_group"
                                    value="{{ old('blood_group', $employee->blood_group) }}">
                            </div>

                            {{-- Emergency Contact --}}


                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Emergency Contact Name</label>
                                <input type="text" class="form-control" name="emergancy_contact_name"
                                    value="{{ old('emergancy_contact_name', $employee->emergancy_contact_name) }}">
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Emergency Contact Number</label>
                                <input type="text" class="form-control" name="emergancy_contact_number"
                                    value="{{ old('emergancy_contact_number', $employee->emergancy_contact_number) }}">
                            </div>

                            {{-- Employee Type --}}
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Employment type<span class="text-danger">*</span></label>
                                <select class="form-control" name="employee_type" required>
                                    <option value="">Select Type</option>
                                    <option value="Permanent"
                                        {{ old('employee_type', $employee->employee_type) == 'Permanent' ? 'selected' : '' }}>
                                        Permanent</option>
                                    <option value="Contract"
                                        {{ old('employee_type', $employee->employee_type) == 'Contract' ? 'selected' : '' }}>
                                        Contract</option>
                                    <option value="Intern"
                                        {{ old('employee_type', $employee->employee_type) == 'Intern' ? 'selected' : '' }}>
                                        Intern
                                    </option>
                                    <option value="Consultant"
                                        {{ old('employee_type', $employee->employee_type) == 'Consultant' ? 'selected' : '' }}>
                                        Consultant</option>
                                </select>
                            </div>

                            {{-- PAN / Aadhaar / Bank --}}
                            {{-- <div class="col-lg-4 mb-20">
                            <label class="label fs-16 mb-2">PAN No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="pan_no" value="{{ old('pan_no', $employee->pan_no) }}">
                        </div>
                        <div class="col-lg-4 mb-20">
                            <label class="label fs-16 mb-2">Aadhaar No</label>
                            <input type="text" class="form-control" name="aadhaar_no" value="{{ old('aadhaar_no', $employee->aadhaar_no) }}">
                        </div> --}}

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">PAN No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pan_no"
                                    value="{{ old('pan_no', $employee->pan_no) }}" maxlength="10"
                                    pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}" title="Enter valid PAN (e.g. ABCDE1234F)"
                                    style="text-transform:uppercase;" required>
                            </div>

                            <!-- Aadhaar No -->
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Aadhaar No <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="aadhaar_no"
                                    value="{{ old('aadhaar_no', $employee->aadhaar_no) }}" maxlength="12"
                                    pattern="^\d{12}$" title="Enter valid 12-digit Aadhaar number"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12);" required>
                            </div>

                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">UAN Number</label>
                                <input type="text" class="form-control" name="uan_no"
                                    value="{{ old('uan_no', $employee->uan_no) }}">
                            </div>


                            {{-- Status / Login --}}


                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">PF Aplicable<span class="text-danger">*</span></label>
                                <select class="form-control" name="pf_aplicable" required>
                                    <option value="1"
                                        {{ old('pf_aplicable', $employee->pf_aplicable) == '1' ? 'selected' : '' }}>
                                        Yes</option>
                                    <option value="0"
                                        {{ old('pf_aplicable', $employee->pf_aplicable) == '0' ? 'selected' : '' }}>
                                        No</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-20" id="pfNumberSection">
                                <label class="label fs-16 mb-2">
                                    PF Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('pf_number') is-invalid @enderror"
                                    name="pf_number" value="{{ old('pf_number', $employee->pf_number ?? '') }}">
                                @error('pf_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">ESI Aplicable<span class="text-danger">*</span></label>
                                <select class="form-control" name="esi_aplicable" required>
                                    <option value="1"
                                        {{ old('esi_aplicable', $employee->esi_aplicable) == '1' ? 'selected' : '' }}>
                                        Yes</option>
                                    <option value="0"
                                        {{ old('esi_aplicable', $employee->esi_aplicable) == '0' ? 'selected' : '' }}>
                                        No</option>
                                </select>
                            </div>



                            <div class="col-lg-4 mb-20" id="esiNumberSection">
                                <label class="label fs-16 mb-2">
                                    ESI Number <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('esi_number') is-invalid @enderror"
                                    name="esi_number" value="{{ old('esi_number', $employee->esi_number ?? '') }}">
                                @error('esi_number')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Is Login Allowed<span class="text-danger">*</span></label>
                                <select class="form-control" name="is_login" id="is_login" required>
                                    <option value="1"
                                        {{ old('is_login', $employee->is_login) == '1' ? 'selected' : '' }}>
                                        Yes</option>
                                    <option value="0"
                                        {{ old('is_login', $employee->is_login) == '0' ? 'selected' : '' }}>
                                        No</option>
                                </select>
                            </div>
                            <div class="col-lg-4 mb-20" id="roleSection">
                                <label class="label fs-16 mb-2">Role<span class="text-danger"></span></label>
                                <select name="role_id" id="role" class="form-control">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', $employee->role_id) == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4 mb-20">
                                <label class="label fs-16 mb-2">Status</label>
                                <select class="form-control" name="status">
                                    <option value="1"
                                        {{ old('status', $employee->status) == '1' ? 'selected' : '' }}>
                                        Active</option>
                                    <option value="0"
                                        {{ old('status', $employee->status) == '0' ? 'selected' : '' }}>
                                        Inactive</option>
                                </select>
                            </div>

                            {{-- Submit --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">Update
                                        Employee</button>
                                    <a href="{{ route('employee.index') }}"
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
            function loadStates(countryID, selectedState = null) {
                if (countryID) {
                    $.getJSON('/get-states/' + countryID, function(data) {
                        $('#state').empty().append('<option value="">Select State</option>');
                        $('#city').empty().append('<option value="">Select City</option>');
                        $.each(data, function(k, v) {
                            $('#state').append('<option value="' + v.id + '" ' + (selectedState == v
                                .id ? 'selected' : '') + '>' + v.name + '</option>');
                        });
                    });
                }
            }

            function loadCities(stateID, selectedCity = null) {
                if (stateID) {
                    $.getJSON('/get-cities/' + stateID, function(data) {
                        $('#city').empty().append('<option value="">Select City</option>');
                        $.each(data, function(k, v) {
                            $('#city').append('<option value="' + v.id + '" ' + (selectedCity == v
                                .id ? 'selected' : '') + '>' + v.name + '</option>');
                        });
                    });
                }
            }

            var selectedCountry = '{{ old('country_id', $employee->country_id) }}';
            var selectedState = '{{ old('state_id', $employee->state_id) }}';
            var selectedCity = '{{ old('city_id', $employee->city_id) }}';

            loadStates(selectedCountry, selectedState);
            loadCities(selectedState, selectedCity);

            $('#country').change(function() {
                var countryID = $(this).val();
                loadStates(countryID);
            });
            $('#state').change(function() {
                var stateID = $(this).val();
                loadCities(stateID);
            });
        });
    </script>
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

    <script>
        $(document).ready(function() {
            const recordId = "{{ encrypt($employee->id) }}";

            setupRealtimeValidation('Employee', 'code', 'input[name="code"]', recordId);

        });
    </script>
    <script>
        $(document).ready(function() {

            function toggleRoleSection() {
                const isLogin = $('select[name="is_login"]').val();

                if (isLogin == "1") {
                    $("#roleSection").show();
                } else {
                    $("#roleSection").hide();
                    $("#role").val("");
                }
            }
            toggleRoleSection();
            $('select[name="is_login"]').on('change', function() {
                toggleRoleSection();
            });

        });
    </script>
    <script>
        $(document).ready(function() {

            function toggleRoleRequired() {
                let loginVal = $("#is_login").val();

                if (loginVal == "1") {
                    $("#roleSection").show();
                    $("#role").attr("required", true);
                    $("#roleSection label .text-danger").text("*");
                } else {
                    $("#roleSection").hide();
                    $("#role").removeAttr("required");
                    $("#roleSection label .text-danger").text("");
                    $("#role").val("");
                }
            }

            toggleRoleRequired();

            $("#is_login").change(function() {
                toggleRoleRequired();
            });
            $(document).ready(function() {

                function togglePF() {
                    if ($('select[name="pf_aplicable"]').val() == "1") {
                        $("#pfNumberSection").show();
                        $('input[name="pf_number"]').attr('required', true);
                    } else {
                        $("#pfNumberSection").hide();
                        $('input[name="pf_number"]').removeAttr('required').val('');
                    }
                }

                function toggleESI() {
                    if ($('select[name="esi_aplicable"]').val() == "1") {
                        $("#esiNumberSection").show();
                        $('input[name="esi_number"]').attr('required', true);
                    } else {
                        $("#esiNumberSection").hide();
                        $('input[name="esi_number"]').removeAttr('required').val('');
                    }
                }

                // Page Load
                togglePF();
                toggleESI();

                // On Change
                $('select[name="pf_aplicable"]').change(togglePF);
                $('select[name="esi_aplicable"]').change(toggleESI);

            });
        });
    </script>
    <script>
        function setRelievingMin() {
            let joinDate = document.getElementById("joining_date").value;
            if (joinDate) {
                document.getElementById("relieving_date").setAttribute("min", joinDate);
            }
        }

        document.getElementById("joining_date").addEventListener("change", setRelievingMin);


        window.addEventListener("load", setRelievingMin);
    </script>
    <script>
        function setApprovedMinDate() {
            let relievingDate = document.getElementById("relieving_date").value;
            if (relievingDate) {
                document.getElementById("relieving_approvel_date").setAttribute("min", relievingDate);
            }
        }

        document.getElementById("relieving_date").addEventListener("change", setApprovedMinDate);

        window.addEventListener("load", setApprovedMinDate);
    </script>
@endpush
