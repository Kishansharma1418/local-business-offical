@extends('saas.layouts.client')
@section('title', 'Website Pages')

@section('content')
<h4 class="mb-3">Website Pages</h4>
<p class="text-muted">Edit the content shown on your public website.</p>

<div class="row g-3">
    @foreach($pages as $page)
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5>{{ $page->title }}</h5>
                    <small class="text-muted text-uppercase">/{{ $page->slug }}</small>
                    <p class="mt-2 text-muted">{{ \Illuminate\Support\Str::limit(strip_tags($page->content), 120) ?: 'No content yet.' }}</p>
                    <a href="{{ route('client.pages.edit', $page) }}" class="btn btn-sm btn-primary"><i class="ri-edit-line"></i> Edit Content</a>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection
