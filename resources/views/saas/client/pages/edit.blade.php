@extends('saas.layouts.client')
@section('title', 'Edit Page: ' . $page->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Edit Page: <code>/{{ $page->slug }}</code></h4>
    <a href="{{ route('client.pages.index') }}" class="btn btn-light"><i class="ri-arrow-left-line"></i> Back</a>
</div>
<div class="card border-0 shadow-sm"><div class="card-body">
    <form method="POST" action="{{ route('client.pages.update', $page) }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Page Title *</label>
            <input type="text" name="title" value="{{ old('title', $page->title) }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" rows="14" class="form-control">{{ old('content', $page->content) }}</textarea>
            <small class="text-muted">HTML allowed. Use &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt; etc.</small>
        </div>
        <button class="btn btn-primary">Save Changes</button>
    </form>
</div></div>
@endsection
