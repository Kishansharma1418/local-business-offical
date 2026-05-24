{{-- Expects $filterDefinitions, $filterOptions (optional), $tenant --}}
@php
    use App\Support\ListingFilters;
    $theme = $tenant->theme ?? 'boutique';
@endphp
@if(ListingFilters::hasListingFilters($theme) && !empty($filterDefinitions))
<form method="GET" class="listing-filter-bar row g-2 mb-4 p-3 rounded-lux align-items-end" style="background:#fff;border:1px solid var(--line);">
    <div class="col-md-4 col-lg-3">
        <label class="form-label small fw-semibold mb-1">Search</label>
        <div class="position-relative">
            <i class="fa fa-search position-absolute" style="left:12px;top:50%;transform:translateY(-50%);color:#9ca3af;font-size:.85rem;"></i>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Search…" style="padding-left:36px;">
        </div>
    </div>
    @foreach($filterDefinitions as $key => $def)
        @php
            $opts = $def['options'] ?? [];
            if (empty($opts) && !empty($filterOptions[$key])) {
                foreach ($filterOptions[$key] as $v) {
                    $opts[$v] = $v;
                }
            }
        @endphp
        @if(!empty($opts) || $key === 'location')
            <div class="col-6 col-md-4 col-lg-2">
                <label class="form-label small fw-semibold mb-1">{{ $def['label'] }}</label>
                @if($key === 'location' && empty($opts))
                    <input type="text" name="location" value="{{ request('location') }}" class="form-control form-control-sm" placeholder="Area name">
                @else
                    <select name="{{ $key }}" class="form-select form-select-sm">
                        <option value="">All</option>
                        @foreach($opts as $val => $label)
                            <option value="{{ $val }}" @selected(request($key) == (string) $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
        @endif
    @endforeach
    @if(!empty($categories) && count($categories))
        <div class="col-6 col-md-4 col-lg-2">
            <label class="form-label small fw-semibold mb-1">Category</label>
            <select name="category" class="form-select form-select-sm">
                <option value="">All</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    @endif
    <div class="col-6 col-md-auto">
        <button type="submit" class="btn btn-brand btn-sm w-100"><i class="fa fa-filter me-1"></i>Filter</button>
    </div>
    @if(request()->except('page'))
        <div class="col-6 col-md-auto">
            <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-outline-secondary btn-sm w-100">Clear</a>
        </div>
    @endif
</form>
@endif
