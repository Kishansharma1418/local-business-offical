@php
    use App\Support\ListingFilters;
    $theme = auth()->user()->tenant?->theme ?? 'boutique';
    $meta = old() ? array_merge(ListingFilters::defaultMeta($theme), array_filter([
        'property_type' => old('property_type'),
        'purpose' => old('purpose'),
        'bhk' => old('bhk'),
        'location' => old('location'),
        'area_sqft' => old('area_sqft'),
        'specialty' => old('specialty'),
        'consultation_type' => old('consultation_type'),
        'duration' => old('duration'),
    ])) : ($product->meta ?? ListingFilters::defaultMeta($theme));
@endphp

@if($theme === 'property')
    <div class="col-12"><hr><h6 class="fw-bold">Property details</h6></div>
    <div class="col-md-3">
        <label class="form-label">Property type</label>
        <select name="property_type" class="form-select">
            <option value="">— Select —</option>
            @foreach(config('saas.listing_filters.property.property_type.options', []) as $val => $label)
                <option value="{{ $val }}" @selected(($meta['property_type'] ?? '') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">For</label>
        <select name="purpose" class="form-select">
            <option value="">— Select —</option>
            @foreach(config('saas.listing_filters.property.purpose.options', []) as $val => $label)
                <option value="{{ $val }}" @selected(($meta['purpose'] ?? '') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">BHK</label>
        <select name="bhk" class="form-select">
            <option value="">— Select —</option>
            @foreach(config('saas.listing_filters.property.bhk.options', []) as $val => $label)
                <option value="{{ $val }}" @selected(($meta['bhk'] ?? '') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Area / Location</label>
        <input type="text" name="location" class="form-control" value="{{ $meta['location'] ?? '' }}" placeholder="e.g. Malviya Nagar">
    </div>
    <div class="col-md-4">
        <label class="form-label">Built-up area (sq.ft)</label>
        <input type="text" name="area_sqft" class="form-control" value="{{ $meta['area_sqft'] ?? '' }}" placeholder="e.g. 1200">
    </div>
@elseif($theme === 'clinic')
    <div class="col-12"><hr><h6 class="fw-bold">Clinic / service details</h6></div>
    <div class="col-md-4">
        <label class="form-label">Specialty</label>
        <select name="specialty" class="form-select">
            <option value="">— Select —</option>
            @foreach(config('saas.listing_filters.clinic.specialty.options', []) as $val => $label)
                <option value="{{ $val }}" @selected(($meta['specialty'] ?? '') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Consultation type</label>
        <select name="consultation_type" class="form-select">
            <option value="">— Select —</option>
            @foreach(config('saas.listing_filters.clinic.consultation_type.options', []) as $val => $label)
                <option value="{{ $val }}" @selected(($meta['consultation_type'] ?? '') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Duration</label>
        <select name="duration" class="form-select">
            <option value="">— Select —</option>
            @foreach(config('saas.listing_filters.clinic.duration.options', []) as $val => $label)
                <option value="{{ $val }}" @selected(($meta['duration'] ?? '') === $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
@endif
