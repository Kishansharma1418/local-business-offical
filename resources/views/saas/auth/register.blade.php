<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Business Account &middot; LocalBiz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--c1:#6c5ce7;--c2:#ff6b9d;--ink:#0b1020;--muted:#6b7280;}
        *{box-sizing:border-box;}
        body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;margin:0;color:var(--ink);min-height:100vh;background:#fafbff;}
        .auth-wrap{display:grid;grid-template-columns:1fr 1.15fr;min-height:100vh;}
        .auth-panel{padding:60px 50px;display:flex;flex-direction:column;justify-content:space-between;color:#fff;background:linear-gradient(135deg,#0b1020 0%,#4a148c 60%,#ff6b9d 120%);position:relative;overflow:hidden;}
        .auth-panel::before{content:'';position:absolute;top:-200px;right:-200px;width:500px;height:500px;background:radial-gradient(circle,rgba(255,107,157,.3),transparent 60%);border-radius:50%;}
        .auth-panel::after{content:'';position:absolute;bottom:-150px;left:-150px;width:400px;height:400px;background:radial-gradient(circle,rgba(108,92,231,.3),transparent 60%);border-radius:50%;}
        .panel-inner{position:relative;z-index:2;}
        .brand{font-weight:800;font-size:1.3rem;display:flex;align-items:center;gap:10px;color:#fff;text-decoration:none;}
        .brand-dot{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#ff6b9d,#6c5ce7);display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(255,107,157,.4);}
        .panel-hero h2{font-weight:800;font-size:2.4rem;line-height:1.1;margin:50px 0 16px;letter-spacing:-.02em;}
        .panel-hero p{color:rgba(255,255,255,.78);font-size:1.04rem;max-width:420px;}
        .perks{margin-top:36px;}
        .perk{display:flex;gap:14px;margin-bottom:18px;}
        .perk-ico{width:38px;height:38px;border-radius:10px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .perk b{display:block;color:#fff;font-weight:700;}
        .perk small{color:rgba(255,255,255,.7);}
        .trust-row{display:flex;gap:24px;margin-top:auto;padding-top:30px;border-top:1px solid rgba(255,255,255,.12);font-size:.82rem;color:rgba(255,255,255,.65);flex-wrap:wrap;}
        .trust-row span i{color:#00d4ff;margin-right:6px;}

        .auth-form{padding:50px;overflow-y:auto;position:relative;}
        .auth-form-inner{width:100%;max-width:540px;margin:0 auto;}
        .auth-form h3{font-weight:800;font-size:1.9rem;letter-spacing:-.02em;margin-bottom:6px;}
        .auth-form .sub{color:var(--muted);margin-bottom:28px;}
        .form-label{font-weight:600;font-size:.85rem;margin-bottom:4px;}
        .form-control{padding:11px 13px;border-radius:10px;border:1.5px solid #e5e7eb;font-size:.93rem;transition:all .2s;}
        .form-control:focus{border-color:var(--c1);box-shadow:0 0 0 4px rgba(108,92,231,.1);}
        .btn-gradient{background:linear-gradient(135deg,#6c5ce7,#a855f7,#ff6b9d);background-size:200% auto;color:#fff;border:0;padding:13px;font-weight:700;border-radius:12px;width:100%;transition:all .3s;box-shadow:0 10px 24px rgba(108,92,231,.3);}
        .btn-gradient:hover{background-position:right center;color:#fff;}
        .plan-option{border:2px solid #e5e7eb;padding:14px 14px;border-radius:14px;cursor:pointer;transition:all .2s;position:relative;height:100%;}
        .plan-option:hover{border-color:#cfc9ff;}
        .plan-option.active{border-color:var(--c1);background:linear-gradient(180deg,#f5f3ff,#fff);box-shadow:0 8px 24px rgba(108,92,231,.12);}
        .plan-option.active::after{content:'\f00c';font-family:'Font Awesome 6 Free';font-weight:900;position:absolute;top:10px;right:10px;width:22px;height:22px;background:var(--c1);color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.7rem;}
        .plan-option .pn{font-weight:700;}
        .plan-option .pp{font-size:1.3rem;font-weight:800;color:var(--c1);margin:4px 0;}
        .plan-option .pd{font-size:.78rem;color:var(--muted);}
        .back-link{position:absolute;top:20px;right:50px;color:var(--muted);text-decoration:none;font-size:.88rem;}
        .back-link:hover{color:var(--c1);}
        @media (max-width:991px){
            .auth-wrap{grid-template-columns:1fr;}
            .auth-panel{display:none;}
            .auth-form{padding:60px 24px 40px;}
            .back-link{top:18px;right:24px;}
        }
    </style>
</head>
<body>
<div class="auth-wrap">
    <div class="auth-panel">
        <div class="panel-inner">
            <a href="{{ route('landing') }}" class="brand">
                <span class="brand-dot"><i class="fa fa-bolt"></i></span> LocalBiz
            </a>
            <div class="panel-hero">
                <h2>Launch your store<br>in under 60 seconds.</h2>
                <p>No code. No hosting. No monthly agency bills. Just fill the form — we'll get you online before your chai gets cold.</p>
                <div class="perks">
                    <div class="perk"><div class="perk-ico"><i class="fa fa-globe"></i></div><div><b>Instant website</b><small>Your own URL, 3 themes to choose</small></div></div>
                    <div class="perk"><div class="perk-ico"><i class="fa fa-box-open"></i></div><div><b>Product catalog</b><small>Add products with photos + stock</small></div></div>
                    <div class="perk"><div class="perk-ico"><i class="fab fa-whatsapp"></i></div><div><b>WhatsApp orders</b><small>Auto-message on every new order</small></div></div>
                    <div class="perk"><div class="perk-ico"><i class="fa fa-headset"></i></div><div><b>Jaipur-based support</b><small>Hindi/English help, 10am-7pm</small></div></div>
                </div>
            </div>
            <div class="trust-row">
                <span><i class="fa fa-shield-halved"></i> SSL Secure</span>
                <span><i class="fa fa-lock"></i> Data isolated</span>
                <span><i class="fa fa-circle-check"></i> Free 7-day trial</span>
            </div>
        </div>
    </div>

    <div class="auth-form">
        <a href="{{ route('login') }}" class="back-link">Already have an account? <b style="color:var(--c1);">Sign in</b></a>
        <div class="auth-form-inner">
            <h3>Create your business account</h3>
            <p class="sub">Fill a few details — your dashboard is ready in seconds.</p>

            @if($errors->any())
                <div class="alert alert-danger py-2 small"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Business name *</label>
                        <input type="text" name="business_name" value="{{ old('business_name') }}" class="form-control" placeholder="e.g. Saanvi Boutique" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Your name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="+91 98765 43210">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-control" minlength="6" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Confirm password *</label>
                        <input type="password" name="password_confirmation" class="form-control" minlength="6" required>
                    </div>
                    @if($plans->count())
                        <div class="col-md-12 mt-4">
                            <label class="form-label">Choose your plan</label>
                            <div class="row g-2">
                                @foreach($plans as $i => $plan)
                                    <div class="col-md-4">
                                        <label class="plan-option d-block text-center {{ $i === 1 ? 'active' : '' }}">
                                            <input type="radio" name="plan_id" value="{{ $plan->id }}" class="d-none" {{ $i === 1 ? 'checked' : '' }}>
                                            <div class="pn">{{ $plan->name }}</div>
                                            <div class="pp">₹{{ number_format($plan->price, 0) }}</div>
                                            <div class="pd">{{ $plan->duration_days }} days · {{ $plan->max_products }} products</div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
                <button class="btn-gradient mt-4"><i class="fa fa-rocket me-2"></i>Create My Business</button>
                <p class="text-center small text-muted mt-3 mb-0">By signing up you agree to our <a href="#" style="color:var(--c1);">Terms</a> and <a href="#" style="color:var(--c1);">Privacy</a>.</p>
            </form>
        </div>
    </div>
</div>
<script>
document.querySelectorAll('.plan-option').forEach(el => {
    el.addEventListener('click', () => {
        document.querySelectorAll('.plan-option').forEach(x => x.classList.remove('active'));
        el.classList.add('active');
        el.querySelector('input').checked = true;
    });
});
</script>
</body>
</html>
