@csrf
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name *</label>
        <input type="text" name="name" value="{{ old('name', $plan->name ?? '') }}" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $plan->slug ?? '') }}" class="form-control"></div>

    <div class="col-md-4"><label class="form-label">Price (₹) *</label>
        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $plan->price ?? 0) }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Duration (days) *</label>
        <input type="number" min="1" name="duration_days" value="{{ old('duration_days', $plan->duration_days ?? 30) }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Max Products *</label>
        <input type="number" min="1" name="max_products" value="{{ old('max_products', $plan->max_products ?? 20) }}" class="form-control" required></div>

    <div class="col-md-12"><label class="form-label">Features (one per line)</label>
        <textarea name="features" rows="5" class="form-control" placeholder="Free website&#10;WhatsApp button&#10;COD checkout">{{ old('features', isset($plan) ? implode("\n", (array)$plan->features) : '') }}</textarea>
    </div>
    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="isactive" {{ old('is_active', $plan->is_active ?? 1) ? 'checked' : '' }}>
            <label for="isactive" class="form-check-label">Active (visible to new sign-ups)</label>
        </div>
    </div>
</div>
