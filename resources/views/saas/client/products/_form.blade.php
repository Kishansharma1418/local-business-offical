@csrf
<div class="row g-3">
    <div class="col-md-8"><label class="form-label">Name *</label>
        <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">Category</label>
        @php $th = auth()->user()->tenant?->theme; @endphp
        <input type="text" name="category" value="{{ old('category', $product->category ?? '') }}" class="form-control"
               placeholder="@if($th==='property')e.g. Residential @elseif($th==='clinic')e.g. Consultation @else e.g. Sarees @endif"></div>

    @include('saas.client.products._listing_meta')

    <div class="col-md-4"><label class="form-label">Selling Price (₹) *</label>
        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price ?? '') }}" class="form-control" required></div>
    <div class="col-md-4"><label class="form-label">MRP (₹)</label>
        <input type="number" step="0.01" min="0" name="mrp" value="{{ old('mrp', $product->mrp ?? '') }}" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Stock</label>
        <input type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="form-control"></div>

    <div class="col-md-12"><label class="form-label">Short Description</label>
        <input type="text" name="short_description" value="{{ old('short_description', $product->short_description ?? '') }}" class="form-control" maxlength="500"></div>

    <div class="col-md-12"><label class="form-label">Full Description</label>
        <textarea name="description" rows="5" class="form-control">{{ old('description', $product->description ?? '') }}</textarea></div>

    <div class="col-md-6"><label class="form-label">Image</label>
        <input type="file" name="image" class="form-control" accept="image/*">
        @if(isset($product) && $product->image)
            <div class="mt-2"><img src="{{ asset('storage/' . $product->image) }}" style="height:90px;border-radius:8px;"></div>
        @endif
    </div>

    <div class="col-md-6 d-flex align-items-center gap-3">
        <div class="form-check mt-4">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" id="act" class="form-check-input" {{ old('is_active', $product->is_active ?? 1) ? 'checked' : '' }}>
            <label for="act" class="form-check-label">Active</label>
        </div>
        <div class="form-check mt-4">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" name="is_featured" value="1" id="feat" class="form-check-input" {{ old('is_featured', $product->is_featured ?? 0) ? 'checked' : '' }}>
            <label for="feat" class="form-check-label">Featured</label>
        </div>
    </div>
</div>
