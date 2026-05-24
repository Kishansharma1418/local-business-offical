<style>
    .org-tree ul {
        padding-top: 20px;
        position: relative;
        padding-left: 0;
    }

    .org-tree li {
        float: left;
        text-align: center;
        list-style-type: none;
        position: relative;
        padding: 20px 10px 0 10px;
    }

    /* lines */

 .org-tree li::before,
.org-tree li::after {
    content: '';
    position: absolute;
    top: 0;
    right: 50%;
    border-top: 1px solid #ccc ;
    width: 50%;
    height: 20px;
}   
    .org-tree li::after {
        right: auto;
        left: 50%;
        border-left: 1px solid #ccc !important;
    }

    /* remove line for single child */

    .org-tree li:only-child::after,
    .org-tree li:only-child::before {
        display: none;
    }

    .org-tree li:only-child {
        padding-top: 0;
    }

    .org-tree li:first-child::before,
    .org-tree li:last-child::after {
        border: 0 none;
    }

    /* vertical line */

    .org-tree ul ul::before {
        content: '';
        position: absolute;
        top: 0;
        left: 50%;
        border-left: 1px solid #ccc;
        width: 0;
        height: 20px;
    }

    /* box */

    .tree-box {
        border: 1px solid #dee2e6;
        padding: 8px 12px;
        display: inline-block;
        border-radius: 6px;
        background: #f8f9fa;
        font-size: 13px;
    }

    .highlight {
        background: #e7f1ff;
        font-weight: 600;
    }
</style>
@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        {{-- Header & Breadcrumb --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Employee Details</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('employee.index') }}" class="text-decoration-none">Employee</a>
                    </li>
                    <li class="breadcrumb-item active">View Employee</li>
                </ol>
            </nav>
        </div>

        {{-- Employee Details Card --}}
        <div class="card bg-white p-4 rounded-10 border border-light shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="mb-0">
                        <i class="ri-user-3-line me-2 text-primary"></i>
                        {{ $employee->full_name }}
                    </h4>
                    <span class="badge {{ $employee->status ? 'bg-success' : 'bg-danger' }}">
                        {{ $employee->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="row g-4">
                    {{-- Basic Info --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Personal Information</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Employee Code:</strong> {{ $employee->code }}</li>
                                <li><strong>Gender:</strong> {{ $employee->gender }}</li>
                                <li><strong>Date of Birth:</strong>{{ formatDate($employee->dob) }}</li>
                                <li><strong>Marital Status:</strong> {{ $employee->marital_status ?? '-' }}</li>
                                <li><strong>Father's Name:</strong> {{ $employee->fathers_name ?? '-' }}</li>
                                <li><strong>Blood Group:</strong> {{ $employee->blood_group ?? '-' }}</li>

                            </ul>

                            <div style="text-align: left; margin-top: 10px;">
                                <strong>Employee Image:</strong><br>
                                @if ($employee->employee_image)
                                    <img src="{{ asset('storage/' . $employee->employee_image) }}" alt="Employee Image"
                                        style="max-width: 100px; margin-top: 10px;">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Contact Info --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Contact Information</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Official Email:</strong> {{ $employee->official_mail }}</li>
                                <li><strong>Personal Email:</strong> {{ $employee->personal_mail ?? '-' }}</li>
                                <li><strong>Mobile No:</strong> {{ $employee->mobile_no }}</li>
                                <li><strong>Alternate No:</strong> {{ $employee->alternative_no ?? '-' }}</li>
                                <li><strong>Emergency Contact:</strong> {{ $employee->emergancy_contact_name }}
                                    {{ $employee->emergancy_contact_number ?? '-' }}</li>
                            </ul>
                        </div>
                    </div>

                    {{-- Job Details --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Job Details</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Branch:</strong> {{ optional($employee->branches)->branch_name ?? '-' }}</li>
                                <li><strong>Employee Role:</strong> {{ optional($employee->roles)->name ?? '-' }}</li>
                                <li><strong>UAN Number:</strong> {{ $employee->uan_no }}</li>

                                <li><strong>Department:</strong>
                                    {{ optional($employee->departments)->department_name ?? '-' }}</li>
                                <li><strong>Designation:</strong> {{ $employee->designation?->name ?? '-' }}</li>
                                <li><strong>Reporting Manager:</strong> {{ $employee->employee->full_name ?? '-' }}
                                </li>
                                <li><strong>Joining Date:</strong>
                                      {{ formatDate($employee->joining_date) }}
                                </li>
                                <li><strong>Resignation Date:</strong>
                                    {{ $employee->relieving_date ? \Carbon\Carbon::parse($employee->relieving_date)->format('d M, Y') : '-' }}
                                </li>
                                <li><strong>Sales Head:</strong> {{ $employee->sales_head ?? '-' }}</li>
                                <li><strong>Employee Type:</strong> {{ ucfirst($employee->employee_type) }}</li>
                                <li><strong>Login Access:</strong>
                                    @if ($employee->is_login)
                                        <span class="badge bg-success">Enabled</span>
                                    @else
                                        <span class="badge bg-secondary">Disabled</span>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Address Details --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Address Details</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Address Line 1:</strong> {{ $employee->address_line1 ?? '-' }}</li>
                                <li><strong>Address Line 2:</strong> {{ $employee->address_line2 ?? '-' }}</li>
                                <li><strong>City:</strong> {{ optional($employee->cities)->name ?? '-' }}</li>
                                <li><strong>State:</strong> {{ optional($employee->states)->name ?? '-' }}</li>
                                <li><strong>Country:</strong> {{ optional($employee->countries)->name ?? '-' }}</li>
                                <li><strong>Pincode:</strong> {{ $employee->pincode ?? '-' }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Bank Details</h5>

                            @if ($employee->bankDetails)
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Bank Name:</strong> {{ $employee->bankDetails->banks->name }}</li>
                                    <li><strong>IFSC Code:</strong> {{ $employee->bankDetails->ifsc_code }}</li>
                                    <li><strong>Account Number:</strong> {{ $employee->bankDetails->account_number }}</li>
                                    <li><strong>Branch Address:</strong>
                                        {{ $employee->bankDetails->branch_address ?? '-' }}</li>

                                    <li>
                                        <strong>Passbook:</strong>
                                        @if ($employee->bankDetails->bank_passbook)
                                            <a href="{{ asset('storage/' . $employee->bankDetails->bank_passbook) }}"
                                                target="_blank" class="text-primary">
                                                View File
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </li>

                                    <li>
                                        <strong>Cheque:</strong>
                                        @if ($employee->bankDetails->cheque)
                                            <a href="{{ asset('storage/' . $employee->bankDetails->cheque) }}"
                                                target="_blank" class="text-primary">
                                                View File
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </li>
                                </ul>
                            @else
                                <p class="text-muted mb-0">Bank details not available.</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6 mt-4">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Latest Salary Revision</h5>

                            @if ($employee->latestRevision)
                                <ul class="list-unstyled mb-0">
                                    <li><strong>Effective From:</strong>
                                        {{ \Carbon\Carbon::parse($employee->latestRevision->effective_from)->format('d M, Y') }}
                                    </li>

                                    <li><strong>New Salary Total:</strong>
                                        ₹{{ number_format($employee->latestRevision->new_salary_total, 2) }}</li>

                                    <li><strong>Status:</strong>
                                        <span
                                            class="badge {{ $employee->latestRevision->status == 1 ? 'bg-success' : 'bg-warning' }}">
                                            {{ $employee->latestRevision->status == 1 ? 'Approved' : 'Pending' }}
                                        </span>
                                    </li>

                                    <li><strong>Revision Reason:</strong>
                                        {{ $employee->latestRevision->revision_reason ?? '-' }}
                                    </li>

                                </ul>
                            @else
                                <p class="text-muted mb-0">No salary revision added.</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mt-4">
                        <div class="border rounded-3 p-3 h-100">

                            <h5 class="mb-3 text-primary">Hierarchy Information</h5>

                            <div class="org-tree">

                                <ul>

                                    <li>

                                        <span class="tree-box">
                                            <strong>Role:</strong>
                                            {{ $employee->roles->name ?? '-' }}
                                        </span>

                                    </li>

                                    <li>

                                        <span class="tree-box">
                                            <strong>Reporting Manager:</strong>
                                            {{ $employee->manager->full_name ?? '-' }}
                                        </span>

                                        <ul>

                                            <li>

                                                <span class="tree-box highlight">
                                                    {{ $employee->full_name }}
                                                </span>

                                                @if ($employee->team->count() > 0)
                                                    <ul>

                                                        @foreach ($employee->team as $member)
                                                            <li>

                                                                <span class="tree-box">

                                                                    <i class="ri-user-3-line text-primary me-1"></i>

                                                                    {{ $member->full_name }}

                                                                    <span class="text-muted">
                                                                        ({{ $member->roles->name ?? '-' }})
                                                                    </span>

                                                                </span>

                                                            </li>
                                                        @endforeach

                                                    </ul>
                                                @endif

                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h5 class="mb-3 text-primary">Document Details</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>PAN No:</strong> {{ $employee->pan_no ?? '-' }}</li>
                                <li><strong>Aadhaar No:</strong> {{ $employee->aadhaar_no ?? '-' }}</li>
                            </ul>
                        </div>
                    </div>
                </div>



                <div class="col-lg-12 mt-3">
                    <div class="d-flex gap-2">

                        <a href="{{ route('employee.index') }}" class="btn btn-danger fw-normal text-white">
                            <i class="ri-arrow-left-line me-1"></i> Back
                        </a>
                        <a href="{{ route('employee.edit', encrypt($employee->id)) }}"
                            class="btn btn-primary fw-normal text-white">
                            <i class="ri-edit-2-line me-1"></i> Edit Employee
                        </a>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
