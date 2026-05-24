@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Edit Department</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('departments.index') }}" class="text-decoration-none">Department List</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Department</li>
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

        <form action="{{ route('departments.update', $department->id) }}" method="POST" class="needs-validation"
            novalidate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Department Information</h3>
                        <div class="row">

                            {{-- Department Code --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Department Code <span class="text-danger">*</span></label>
                                <input type="text" name="code"
                                    value="{{ old('code', $department->code) }}" class="form-control"
                                    required>
                            </div>

                            {{-- Department Name --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Department Name <span class="text-danger">*</span></label>
                                <input type="text" name="department_name"
                                    value="{{ old('department_name', $department->department_name) }}" class="form-control"
                                    required>
                            </div>

                         

                            {{-- Branch --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Branch</label>
                                <select name="branch_id" class="form-select form-control">
                                    <option value="">-- None --</option>
                                    @foreach (\App\Models\Branch::all() as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', isset($department) ? $department->branch_id : '') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->branch_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            {{-- Department Head --}}
                            {{-- <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Department Head</label>
                                <select name="department_head_id" class="form-select form-control">
                                    <option value="">-- None --</option>
                                    @foreach (\App\Models\User::all() as $user)
                                        <option value="{{ $user->id }}"
                                            {{ old('department_head_id', $department->department_head_id) == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div> --}}

                            {{-- Email --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Email</label>
                                <input type="email" name="email" value="{{ old('email', $department->email) }}"
                                    class="form-control" placeholder="E.g. example@email.com">
                            </div>

                            {{-- Phone --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Phone</label>
                                <input type="text" name="phone" value="{{ old('phone', $department->phone) }}"
                                    class="form-control" placeholder="e.g. 9876543210" maxlength="10"
                                    pattern="[0-9]{10}" title="Please enter valid 10 digit mobile number" required
                                    oninput="this.value = this.value.replace(/[^0-9]/g,'').slice(0,10)">
                            </div>

                            {{-- Description --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Description</label>
                                <textarea name="description" class="form-control" placeholder="Please Enter Description">{{ old('description', $department->description) }}</textarea>
                            </div>

                            {{-- Status --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Status</label>
                                <select name="status" class="form-select form-control" required>
                                    <option value="Active"
                                        {{ old('status', $department->status) == 'Active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="Inactive"
                                        {{ old('status', $department->status) == 'Inactive' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- Actions --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">Update
                                        Department</button>
                                    <a href="{{ route('departments.index') }}"
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
    const recordId = "{{ encrypt($department->id) }}";

    setupRealtimeValidation('Department', 'code', 'input[name="code"]', recordId);
  
});
</script>
@endpush

