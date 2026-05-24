@extends('saas.layouts.client')
@section('title', 'Edit Product')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Product: {{ $product->name }}</h4>
    <a href="{{ route('client.products.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Back</a>
</div>
<div class="card border-0 shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('client.products.update', $product) }}" enctype="multipart/form-data">
        @method('PUT')
        @include('saas.client.products._form')
        <div class="mt-4"><button class="btn btn-primary">Save Changes</button></div>
    </form>
</div></div>
@endsection
