<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In &middot; LocalBiz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--c1:#6c5ce7;--c2:#ff6b9d;--ink:#0b1020;--muted:#6b7280;}
        *{box-sizing:border-box;}
        body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;margin:0;color:var(--ink);min-height:100vh;background:#fafbff;}
        .auth-wrap{display:grid;grid-template-columns:1fr 1fr;min-height:100vh;}
        .auth-panel{padding:60px 50px;display:flex;flex-direction:column;justify-content:space-between;color:#fff;background:linear-gradient(135deg,#0b1020 0%,#281d5a 60%,#6c5ce7 120%);position:relative;overflow:hidden;}
        .auth-panel::before{content:'';position:absolute;top:-200px;right:-200px;width:500px;height:500px;background:radial-gradient(circle,rgba(255,107,157,.3),transparent 60%);border-radius:50%;}
        .auth-panel::after{content:'';position:absolute;bottom:-150px;left:-150px;width:400px;height:400px;background:radial-gradient(circle,rgba(0,212,255,.15),transparent 60%);border-radius:50%;}
        .panel-inner{position:relative;z-index:2;}
        .brand{font-weight:800;font-size:1.3rem;display:flex;align-items:center;gap:10px;color:#fff;text-decoration:none;}
        .brand-dot{width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#ff6b9d,#6c5ce7);display:flex;align-items:center;justify-content:center;box-shadow:0 8px 20px rgba(255,107,157,.4);}
        .panel-hero{margin-top:60px;}
        .panel-hero h2{font-weight:800;font-size:2.6rem;line-height:1.1;margin-bottom:20px;letter-spacing:-.02em;}
        .panel-hero p{color:rgba(255,255,255,.75);font-size:1.08rem;line-height:1.6;max-width:430px;}
        .panel-stats{display:flex;gap:32px;margin-top:40px;}
        .panel-stats .n{font-size:2rem;font-weight:800;}
        .panel-stats .l{color:rgba(255,255,255,.65);font-size:.85rem;}
        .panel-testi{background:rgba(255,255,255,.08);backdrop-filter:blur(18px);border:1px solid rgba(255,255,255,.15);border-radius:18px;padding:22px 24px;margin-top:auto;}
        .panel-testi p{color:#fff;font-size:.98rem;line-height:1.6;margin:0 0 14px;}
        .panel-testi .who{display:flex;align-items:center;gap:12px;}
        .panel-testi .ava{width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,#ff6b9d,#6c5ce7);display:flex;align-items:center;justify-content:center;font-weight:700;}
        .auth-form{display:flex;flex-direction:column;justify-content:center;padding:60px 50px;position:relative;}
        .auth-form-inner{width:100%;max-width:420px;margin:0 auto;}
        .auth-form h3{font-weight:800;font-size:2rem;margin-bottom:8px;letter-spacing:-.02em;}
        .auth-form .sub{color:var(--muted);margin-bottom:32px;}
        .form-label{font-weight:600;font-size:.88rem;color:var(--ink);margin-bottom:6px;}
        .form-control{padding:13px 14px;border-radius:12px;border:1.5px solid #e5e7eb;font-size:.95rem;transition:all .2s;}
        .form-control:focus{border-color:var(--c1);box-shadow:0 0 0 4px rgba(108,92,231,.1);}
        .btn-gradient{background:linear-gradient(135deg,#6c5ce7,#a855f7,#ff6b9d);background-size:200% auto;color:#fff;border:0;padding:13px;font-weight:700;border-radius:12px;transition:all .3s;width:100%;box-shadow:0 10px 24px rgba(108,92,231,.3);}
        .btn-gradient:hover{background-position:right center;color:#fff;transform:translateY(-1px);}
        .divider{display:flex;align-items:center;gap:12px;color:var(--muted);font-size:.85rem;margin:26px 0;}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:#e5e7eb;}
        .mini-demo{background:#f7f8ff;border:1px dashed #d6d7f0;padding:14px 16px;border-radius:12px;font-size:.83rem;color:var(--muted);margin-top:16px;}
        .mini-demo b{color:var(--ink);}
        .back-link{position:absolute;top:30px;left:50px;color:var(--muted);text-decoration:none;font-size:.9rem;}
        .back-link:hover{color:var(--c1);}
        .forgot{color:var(--c1);text-decoration:none;font-size:.88rem;font-weight:600;}
        @media (max-width:991px){
            .auth-wrap{grid-template-columns:1fr;}
            .auth-panel{display:none;}
            .auth-form{padding:80px 24px 40px;}
            .back-link{top:20px;left:24px;}
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
                <h2>Welcome back.<br>Let's keep growing.</h2>
                <p>Manage your store, track orders, respond to enquiries — all from one clean dashboard built for Indian small businesses.</p>
                <div class="panel-stats">
                    <div><div class="n">100+</div><div class="l">Live stores</div></div>
                    <div><div class="n">₹2M+</div><div class="l">Orders processed</div></div>
                    <div><div class="n">4.9/5</div><div class="l">Owner rating</div></div>
                </div>
            </div>
            <div class="panel-testi">
                <p>"Pehle Instagram DMs mein order manage karti thi. Ab sab website pe organized hai — WhatsApp auto notify hota hai. Life sorted."</p>
                <div class="who">
                    <div class="ava">PS</div>
                    <div><div class="fw-semibold">Priya Sharma</div><small style="color:rgba(255,255,255,.65);">Saanvi Boutique, Jaipur</small></div>
                </div>
            </div>
        </div>
    </div>

    <div class="auth-form">
        <a href="{{ route('landing') }}" class="back-link"><i class="fa fa-arrow-left me-1"></i> Back to home</a>
        <div class="auth-form-inner">
            <h3>Sign in</h3>
            <p class="sub">Welcome back — please enter your details.</p>

            @if(session('error'))
                <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger py-2 small"><ul class="mb-0 ps-3">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="you@business.com" required autofocus>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label class="form-label mb-0">Password</label>
                        <a href="#" class="forgot">Forgot?</a>
                    </div>
                    <input type="password" name="password" class="form-control mt-1" placeholder="••••••••" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" class="form-check-input" id="r">
                    <label class="form-check-label small text-muted" for="r">Keep me signed in for 30 days</label>
                </div>
                <button class="btn-gradient">Sign in <i class="fa fa-arrow-right ms-2"></i></button>
            </form>

            <div class="divider">or</div>

            <p class="text-center mb-0 small text-muted">New to LocalBiz? <a href="{{ route('register') }}" class="text-decoration-none fw-semibold" style="color:var(--c1);">Create a business account</a></p>

            <div class="mini-demo">
                <i class="fa fa-info-circle me-1"></i> <b>Demo:</b> admin@localbiz.test · owner@saanviboutique.test · owner@royalwood.test <br>Password: <b>password</b>
            </div>
        </div>
    </div>
</div>
</body>
</html>
