@php
    use App\Support\ListingFilters;
    $theme = $tenant->theme ?? 'clinic';
@endphp
@if(ListingFilters::hasListingFilters($theme) && !empty($filterDefinitions))
<form method="GET" class="clinic-filter-panel">
    <div class="title"><i class="fa fa-sliders"></i> Find the right service</div>
    <div class="clinic-filter-grid">
        <div style="grid-column:1/-1;">
            <label>Search</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Doctor, check-up, dental…">
        </div>
        @foreach($filterDefinitions as $key => $def)
            @php
                $opts = $def['options'] ?? [];
                if (empty($opts) && !empty($filterOptions[$key])) {
                    foreach ($filterOptions[$key] as $v) { $opts[$v] = $v; }
                }
            @endphp
            @if(!empty($opts))
                <div>
                    <label>{{ $def['label'] }}</label>
                    <select name="{{ $key }}">
                        <option value="">All</option>
                        @foreach($opts as $val => $label)
                            <option value="{{ $val }}" @selected(request($key) == (string) $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
        @endforeach
        @if(!empty($categories) && count($categories))
            <div>
                <label>Category</label>
                <select name="category">
                    <option value="">All</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" @selected(request('category') === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        @endif
    </div>
    <div class="clinic-filter-actions">
        <button type="submit" class="btn btn-brand"><i class="fa fa-search me-1"></i>Search</button>
        @if(request()->except('page'))
            <a href="{{ route('tenant.products', $tenant->slug) }}" class="btn btn-outline-secondary">Clear</a>
        @endif
    </div>
</form>
@endif
