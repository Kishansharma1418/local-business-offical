@extends('saas.layouts.client')
@section('title', 'Business Settings')

@section('content')
<style>
    .palette-card{cursor:pointer;border:1px solid rgba(15,23,42,.08);border-radius:12px;overflow:hidden;transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease;box-shadow:0 1px 2px rgba(15,23,42,.04);}
    .palette-card:hover{transform:translateY(-2px);box-shadow:0 1px 3px rgba(15,23,42,.05),0 10px 22px -10px rgba(15,23,42,.1);}
    .palette-card.active{border-color:#6366f1;box-shadow:0 0 0 2px rgba(99,102,241,.2),0 6px 16px -8px rgba(99,102,241,.2);}
    .palette-bar{height:46px;display:flex;}
    .palette-bar > span{flex:1;}
    .palette-meta{padding:8px 10px;background:#fff;font-size:.75rem;font-weight:600;display:flex;justify-content:space-between;color:#475569;}
    .color-field{position:relative;}
    .color-field input[type=color]{width:54px;height:44px;padding:2px;border:1px solid #e5e7eb;border-radius:10px;cursor:pointer;}
    .color-field .hex-box{flex:1;}
    .preview-card{border-radius:16px;overflow:hidden;border:1px solid #eef0f5;}
    .preview-head{padding:18px 22px;display:flex;align-items:center;gap:14px;}
    .preview-logo-dot{width:36px;height:36px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;color:#fff;}
    .preview-body{padding:26px 22px;}
    .preview-body .btn-live{border:0;color:#fff;padding:8px 20px;border-radius:999px;font-weight:600;}
    .mode-card{border:2px solid #e5e7eb;border-radius:14px;padding:18px;cursor:pointer;transition:.2s;background:#fff;height:100%;}
    .mode-card:hover{border-color:#cbd5e1;}
    .mode-card.active{border-color:#6366f1;background:linear-gradient(135deg,#f8fafc,#fff);box-shadow:0 1px 3px rgba(15,23,42,.05),0 8px 20px -10px rgba(99,102,241,.15);}
    .mode-card .mode-icon{width:46px;height:46px;border-radius:12px;background:linear-gradient(135deg,#6c5ce7,#a855f7);color:#fff;display:flex;align-items:center;justify-content:center;font-size:22px;margin-bottom:12px;}
    .logo-preview{width:110px;height:110px;border-radius:14px;border:1px dashed #cbd5e1;background:#f8fafc;display:flex;align-items:center;justify-content:center;overflow:hidden;}
    .logo-preview img{max-width:100%;max-height:100%;object-fit:contain;}
    .hint{font-size:.78rem;color:#64748b;}
</style>

<div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-1 fw-bold">Website Settings</h4>
        <div class="hint">Control your branding, colors, logo and how your public website looks.</div>
    </div>
    <a href="{{ route('tenant.home', $tenant->slug) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
        <i class="ri-external-link-line me-1"></i> Open my website
    </a>
</div>

<form method="POST" action="{{ route('client.settings.update') }}" enctype="multipart/form-data" id="settingsForm">
    @csrf @method('PUT')

    {{-- =================================================================
         Business info
    ================================================================= --}}
    <div class="card border-0 shadow-sm mb-4"><div class="card-body p-4">
        <h6 class="fw-bold mb-3">Business information</h6>
        <div class="row g-3">
            <div class="col-md-8"><label class="form-label">Business Name *</label>
                <input type="text" name="business_name" value="{{ old('business_name', $tenant->business_name) }}" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label">Website URL</label>
                <input type="text" value="{{ url('/'.$tenant->slug) }}" class="form-control" disabled>
                <div class="hint mt-1">Contact admin to change the slug.</div>
            </div>

            <div class="col-md-4"><label class="form-label">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $tenant->phone) }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', $tenant->email) }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">WhatsApp (with country code)</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp', $tenant->whatsapp) }}" class="form-control" placeholder="919876543210"></div>

            <div class="col-md-8"><label class="form-label">Address</label>
                <input type="text" name="address" value="{{ old('address', $tenant->address) }}" class="form-control"></div>
            <div class="col-md-4"><label class="form-label">City</label>
                <input type="text" name="city" value="{{ old('city', $tenant->city) }}" class="form-control"></div>

            <div class="col-md-12"><label class="form-label">Tagline</label>
                <input type="text" name="tagline" value="{{ old('tagline', $tenant->tagline) }}" class="form-control" placeholder="Short one-liner shown in hero banner"></div>

            <div class="col-md-12"><label class="form-label">About Your Business</label>
                <textarea name="about" rows="4" class="form-control">{{ old('about', $tenant->about) }}</textarea></div>
        </div>
    </div></div>

    {{-- =================================================================
         Website mode
    ================================================================= --}}
    @php $currentMode = old('website_mode', $tenant->website_mode ?? 'shop'); @endphp
    <div class="card border-0 shadow-sm mb-4"><div class="card-body p-4">
        <h6 class="fw-bold mb-1">What type of website do you need?</h6>
        <div class="hint mb-3">Choose the experience that fits your business. You can change this any time.</div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="mode-card {{ $currentMode==='shop' ? 'active':'' }}" id="mode-shop">
                    <input type="radio" name="website_mode" value="shop" class="d-none" {{ $currentMode==='shop' ? 'checked':'' }}>
                    <div class="mode-icon"><i class="ri-shopping-bag-3-line"></i></div>
                    <div class="fw-bold">Shop website <span class="badge bg-primary ms-1">Recommended</span></div>
                    <div class="hint mt-1">Products, shopping cart, checkout, COD / UPI orders. Ideal for boutiques, furniture, gifts, accessories.</div>
                </label>
            </div>
            <div class="col-md-6">
                <label class="mode-card {{ $currentMode==='simple' ? 'active':'' }}" id="mode-simple">
                    <input type="radio" name="website_mode" value="simple" class="d-none" {{ $currentMode==='simple' ? 'checked':'' }}>
                    <div class="mode-icon" style="background:linear-gradient(135deg,#0ea5e9,#6366f1);"><i class="ri-global-line"></i></div>
                    <div class="fw-bold">Simple info website</div>
                    <div class="hint mt-1">Home, about, services, enquiry form &amp; WhatsApp — no cart or products. Great for services, salons, consultants.</div>
                </label>
            </div>
        </div>
    </div></div>

    {{-- =================================================================
         Appearance: theme, logo, colors, palettes
    ================================================================= --}}
    <div class="card border-0 shadow-sm mb-4"><div class="card-body p-4">
        <h6 class="fw-bold mb-1">Appearance &amp; branding</h6>
        <div class="hint mb-3">Upload your logo and set the colors that match your brand.</div>

        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Theme</label>
                <select name="theme" class="form-select">
                    @foreach($themes as $th)<option value="{{ $th }}" @selected(old('theme', $tenant->theme)===$th)>{{ config('saas.themes.'.$th.'.label', ucfirst($th)) }}</option>@endforeach
                </select>
                <div class="hint mt-1">Sets the overall design style (Boutique, Furniture, Service).</div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Logo</label>
                <div class="d-flex gap-3 align-items-center">
                    <div class="logo-preview" id="logoPreview">
                        @if($tenant->logo)
                            <img src="{{ asset('storage/'.$tenant->logo) }}" alt="logo">
                        @else
                            <span class="text-muted small">No logo</span>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <input type="file" name="logo" class="form-control form-control-sm" accept="image/*" id="logoInput">
                        <div class="hint mt-1">PNG / SVG recommended. Max 2 MB.</div>
                        @if($tenant->logo)
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="rmLogo">
                                <label class="form-check-label small text-danger" for="rmLogo">Remove current logo</label>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-semibold">Banner (optional)</label>
                <input type="file" name="banner" class="form-control form-control-sm" accept="image/*">
                @if($tenant->banner)
                    <div class="mt-2"><img src="{{ asset('storage/'.$tenant->banner) }}" style="height:70px;border-radius:8px;"></div>
                    <div class="form-check mt-1">
                        <input class="form-check-input" type="checkbox" name="remove_banner" value="1" id="rmBanner">
                        <label class="form-check-label small text-danger" for="rmBanner">Remove banner</label>
                    </div>
                @endif
            </div>
        </div>

        <hr class="my-4">

        {{-- Palettes --}}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h6 class="fw-bold mb-1">Suggested color palettes</h6>
                <div class="hint">Click a palette to instantly apply those colors below.</div>
            </div>
        </div>
        <div class="row g-3 mb-4">
            @foreach($palettes as $p)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="palette-card" data-primary="{{ $p['primary'] }}" data-background="{{ $p['background'] }}" data-text="{{ $p['text'] }}" data-accent="{{ $p['accent'] }}">
                        <div class="palette-bar">
                            <span style="background:{{ $p['primary'] }}"></span>
                            <span style="background:{{ $p['accent'] }}"></span>
                            <span style="background:{{ $p['background'] }};border-left:1px solid #eee;"></span>
                            <span style="background:{{ $p['text'] }}"></span>
                        </div>
                        <div class="palette-meta">
                            <span>{{ $p['name'] }}</span>
                            <span class="text-uppercase" style="letter-spacing:.05em;">Apply</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Manual color pickers --}}
        <div class="row g-3">
            @php
                $fields = [
                    ['name'=>'primary_color',   'label'=>'Primary',       'value'=>$tenant->primary_color    ?? '#6c5ce7', 'help'=>'Buttons, links, highlights'],
                    ['name'=>'background_color','label'=>'Background',    'value'=>$tenant->background_color ?? '#ffffff', 'help'=>'Main page background'],
                    ['name'=>'text_color',      'label'=>'Text',          'value'=>$tenant->text_color       ?? '#111418', 'help'=>'Body text color'],
                    ['name'=>'accent_color',    'label'=>'Accent (soft)', 'value'=>$tenant->accent_color     ?? '#ece9ff', 'help'=>'Soft tint for badges / blocks'],
                ];
            @endphp
            @foreach($fields as $f)
                <div class="col-md-3">
                    <label class="form-label fw-semibold">{{ $f['label'] }}</label>
                    <div class="color-field d-flex align-items-center gap-2">
                        <input type="color" id="pick-{{ $f['name'] }}" value="{{ old($f['name'], $f['value']) }}">
                        <input type="text"  name="{{ $f['name'] }}" id="hex-{{ $f['name'] }}"
                               class="form-control hex-box" maxlength="7"
                               value="{{ old($f['name'], $f['value']) }}"
                               pattern="^#[0-9a-fA-F]{6}$">
                    </div>
                    <div class="hint mt-1">{{ $f['help'] }}</div>
                </div>
            @endforeach
        </div>

        {{-- Live preview --}}
        <hr class="my-4">
        <h6 class="fw-bold mb-2">Live preview</h6>
        <div class="preview-card" id="livePreview">
            <div class="preview-head" id="previewHead">
                <span class="preview-logo-dot" id="previewLogo">{{ strtoupper(substr($tenant->business_name,0,1)) }}</span>
                <div>
                    <div class="fw-bold" id="previewName" style="font-size:18px;">{{ $tenant->business_name }}</div>
                    <div class="small" id="previewTag" style="opacity:.7;">{{ $tenant->tagline ?: 'Your tagline will show here' }}</div>
                </div>
            </div>
            <div class="preview-body" id="previewBody">
                <p class="mb-3" id="previewText">This is how your website text will look on the selected background. Make sure the contrast looks comfortable to read.</p>
                <button type="button" class="btn-live" id="previewBtn">Shop Now</button>
                <span class="badge ms-2" id="previewBadge" style="padding:8px 12px;border-radius:999px;">New Arrivals</span>
            </div>
        </div>
    </div></div>

    <div class="d-flex gap-2">
        <button class="btn btn-primary px-4">Save Settings</button>
        <a href="{{ route('client.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>

<script>
(function(){
    // Mode card click highlight
    document.querySelectorAll('.mode-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.mode-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            card.querySelector('input').checked = true;
        });
    });

    // Palette click -> apply
    document.querySelectorAll('.palette-card').forEach(card => {
        card.addEventListener('click', () => {
            document.querySelectorAll('.palette-card').forEach(c => c.classList.remove('active'));
            card.classList.add('active');
            setColor('primary_color',    card.dataset.primary);
            setColor('background_color', card.dataset.background);
            setColor('text_color',       card.dataset.text);
            setColor('accent_color',     card.dataset.accent);
            updatePreview();
        });
    });

    function setColor(name, value){
        document.getElementById('pick-'+name).value = value;
        document.getElementById('hex-'+name).value  = value;
    }

    // Sync color picker <-> hex input
    ['primary_color','background_color','text_color','accent_color'].forEach(name => {
        const pick = document.getElementById('pick-'+name);
        const hex  = document.getElementById('hex-'+name);
        pick.addEventListener('input', e => { hex.value = e.target.value; updatePreview(); });
        hex.addEventListener('input', e => {
            const v = e.target.value.trim();
            if (/^#[0-9a-fA-F]{6}$/.test(v)) { pick.value = v; updatePreview(); }
        });
    });

    // Live preview on the right
    function updatePreview(){
        const p = document.getElementById('hex-primary_color').value;
        const b = document.getElementById('hex-background_color').value;
        const t = document.getElementById('hex-text_color').value;
        const a = document.getElementById('hex-accent_color').value;
        const prev = document.getElementById('livePreview');
        prev.style.background = b;
        prev.style.color = t;
        document.getElementById('previewHead').style.background = a;
        document.getElementById('previewHead').style.color = t;
        document.getElementById('previewBody').style.color = t;
        document.getElementById('previewLogo').style.background = p;
        document.getElementById('previewBtn').style.background = p;
        document.getElementById('previewBadge').style.background = a;
        document.getElementById('previewBadge').style.color = t;
    }
    updatePreview();

    // Logo file preview
    const logoInput = document.getElementById('logoInput');
    if (logoInput) {
        logoInput.addEventListener('change', e => {
            const f = e.target.files[0]; if (!f) return;
            const url = URL.createObjectURL(f);
            document.getElementById('logoPreview').innerHTML = '<img src="'+url+'" alt="logo">';
        });
    }
})();
</script>
@endsection
