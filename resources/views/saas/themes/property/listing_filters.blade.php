@php
    use App\Support\ListingFilters;
    $theme = $tenant->theme ?? 'property';
@endphp
@if(ListingFilters::hasListingFilters($theme) && !empty($filterDefinitions))
<form method="GET" class="prop-filter-panel mb-5">
    <div class="prop-filter-head">
        <div>
            <div class="prop-filter-label">Find property</div>
            <div class="prop-filter-sub">Refine by type, budget zone & area</div>
        </div>
        <button type="submit" class="prop-filter-submit"><i class="fa fa-magnifying-glass me-2"></i>Search</button>
    </div>
    <div class="prop-filter-grid">
        <div class="prop-filter-field prop-filter-field-wide">
            <label>Keyword</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="3 BHK, villa, MI Road…">
        </div>
        @foreach($filterDefinitions as $key => $def)
            @php
                $opts = $def['options'] ?? [];
                if (empty($opts) && !empty($filterOptions[$key])) {
                    foreach ($filterOptions[$key] as $v) { $opts[$v] = $v; }
                }
            @endphp
            @if(!empty($opts) || $key === 'location')
                <div class="prop-filter-field">
                    <label>{{ $def['label'] }}</label>
                    @if($key === 'location' && empty($opts))
                        <input type="text" name="location" value="{{ request('location') }}" placeholder="e.g. Malviya Nagar">
                    @else
                        <select name="{{ $key }}">
                            <option value="">Any</option>
                            @foreach($opts as $val => $label)
                                <option value="{{ $val }}" @selected(request($key) == (string) $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            @endif
        @endforeach
    </div>
    @if(request()->except('page'))
        <a href="{{ route('tenant.products', $tenant->slug) }}" class="prop-filter-clear">Reset all filters</a>
    @endif
</form>
@endif
