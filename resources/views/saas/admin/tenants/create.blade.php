@extends('saas.layouts.admin')
@section('title', 'Create Tenant')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Create Tenant</h4>
    <a href="{{ route('admin.tenants.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Back</a>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.tenants.store') }}">
            @include('saas.admin.tenants._form')
            <div class="mt-4 d-flex gap-2"><button class="btn btn-primary">Create Tenant</button></div>
        </form>
    </div>
</div>
@endsection
