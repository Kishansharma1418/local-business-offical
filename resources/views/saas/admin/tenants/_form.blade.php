@csrf
<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Business Name *</label>
        <input type="text" name="business_name" value="{{ old('business_name', $tenant->business_name ?? '') }}" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Slug (URL)</label>
        <input type="text" name="slug" value="{{ old('slug', $tenant->slug ?? '') }}" class="form-control" placeholder="auto from business name"></div>

    <div class="col-md-6"><label class="form-label">Phone</label>
        <input type="text" name="phone" value="{{ old('phone', $tenant->phone ?? '') }}" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Email</label>
        <input type="email" name="email" value="{{ old('email', $tenant->email ?? '') }}" class="form-control"></div>

    <div class="col-md-6"><label class="form-label">WhatsApp</label>
        <input type="text" name="whatsapp" value="{{ old('whatsapp', $tenant->whatsapp ?? '') }}" class="form-control" placeholder="e.g. 919876543210"></div>
    <div class="col-md-6"><label class="form-label">City</label>
        <input type="text" name="city" value="{{ old('city', $tenant->city ?? 'Jaipur') }}" class="form-control"></div>

    <div class="col-md-4"><label class="form-label">Theme *</label>
        <select name="theme" class="form-select" required>
            @foreach($themes as $th)<option value="{{ $th }}" @selected(old('theme', $tenant->theme ?? 'boutique') === $th)>{{ config('saas.themes.'.$th.'.label', ucfirst($th)) }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-4"><label class="form-label">Primary Color</label>
        <input type="text" name="primary_color" value="{{ old('primary_color', $tenant->primary_color ?? '#e91e63') }}" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Plan *</label>
        <select name="plan_id" class="form-select" required>
            <option value="">Choose Plan</option>
            @foreach($plans as $p)<option value="{{ $p->id }}" @selected(old('plan_id', $tenant->plan_id ?? '') == $p->id)>{{ $p->name }} (₹{{ number_format($p->price,0) }} / {{ $p->duration_days }}d)</option>@endforeach
        </select>
    </div>

    <div class="col-md-6"><label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            @foreach(['active','inactive','suspended'] as $s)<option value="{{ $s }}" @selected(old('status', $tenant->status ?? 'active')===$s)>{{ ucfirst($s) }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-6"><label class="form-label">Expiry Date</label>
        <input type="date" name="expiry_date" value="{{ old('expiry_date', isset($tenant) && $tenant->expiry_date ? $tenant->expiry_date->format('Y-m-d') : '') }}" class="form-control"></div>

    @if(!isset($tenant))
        <div class="col-12"><hr><h6 class="fw-bold">Owner Account (Client login)</h6></div>
        <div class="col-md-4"><label class="form-label">Owner Name *</label>
            <input type="text" name="owner_name" value="{{ old('owner_name') }}" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">Owner Email *</label>
            <input type="email" name="owner_email" value="{{ old('owner_email') }}" class="form-control" required></div>
        <div class="col-md-4"><label class="form-label">Password *</label>
            <input type="text" name="owner_password" value="{{ old('owner_password') }}" class="form-control" required minlength="6"></div>
    @endif
</div>
