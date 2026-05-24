@extends('saas.layouts.admin')
@section('title', 'Edit Plan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Plan: {{ $plan->name }}</h4>
    <a href="{{ route('admin.plans.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Back</a>
</div>
<div class="card shadow-sm border-0"><div class="card-body">
    <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
        @method('PUT')
        @include('saas.admin.plans._form')
        <div class="mt-4"><button class="btn btn-primary">Save Changes</button></div>
    </form>
</div></div>
@endsection
