@extends('include.master')

@section('content')
    <div class="main-content-container overflow-hidden">

        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Add Category</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('category.index') }}" class="text-decoration-none">Category List</a>
                    </li>
                    <li class="breadcrumb-item active">Add Category</li>
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

        <form action="{{ route('category.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="card bg-white p-20 rounded-10 border border-white mb-4">
                        <h3 class="mb-20">Category Information</h3>
                        <div class="row">

                            {{-- Department Code --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Category Code <span class="text-danger">*</span></label>
                                <input type="text" name="code" value="{{ old('code') }}"
                                    class="form-control" id="code" placeholder="E.g. MKT" required>
                            </div>

                            {{-- Department Name --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Category Name <span class="text-danger">*</span></label>
                                <input type="text" name="category_name" value="{{ old('category_name') }}"
                                    class="form-control" placeholder="E.g. Marketing" required>
                            </div>

                            {{-- Parent Department --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Parent Category</label>
                                <select name="parent_category_id" class="form-select form-control">
                                    <option value="">Select Parent Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('parent_category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                          
                            {{-- Description --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Description</label>
                                <textarea name="description" class="form-control" placeholder="Please Enter Description">{{ old('description') }}</textarea>
                            </div>
                          

                            {{-- Status --}}
                            <div class="col-lg-6 mb-20">
                                <label class="label fs-16 mb-2">Status</label>
                                <select name="status" class="form-select form-control">
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>

                            {{-- Actions --}}
                            <div class="col-lg-12 mt-3">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary fw-normal text-white">+ Add
                                        Category</button>
                                    <a href="{{ route('category.index') }}"
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
            setupRealtimeValidation('Category', 'code', '#code');
        });
    </script>
@endpush