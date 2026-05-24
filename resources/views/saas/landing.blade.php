<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LocalBiz &middot; Launch your business online in 60 seconds</title>
    <meta name="description" content="Multi-tenant SaaS for Jaipur boutiques, furniture stores & service businesses. Website + orders + WhatsApp — all in one.">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --c1:#6c5ce7;--c2:#ff6b9d;--c3:#00d4ff;
            --ink:#0b1020;--ink-2:#3a3f5a;--muted:#6b7280;
            --bg:#fafbff;--surface:#ffffff;
            --grad:linear-gradient(135deg,#6c5ce7 0%,#a855f7 40%,#ff6b9d 100%);
            --grad-soft:linear-gradient(135deg,rgba(108,92,231,.08),rgba(255,107,157,.08));
        }
        *{box-sizing:border-box;}
        body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:var(--ink);background:var(--bg);-webkit-font-smoothing:antialiased;line-height:1.55;}
        h1,h2,h3,h4,h5{font-family:'Plus Jakarta Sans',sans-serif;letter-spacing:-.02em;}
        .display-serif{font-family:'Playfair Display',serif;font-weight:800;letter-spacing:-.01em;}
        .text-grad{background:var(--grad);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent;}
        .navbar{backdrop-filter:blur(18px);background:rgba(255,255,255,.78)!important;border-bottom:1px solid rgba(0,0,0,.05);padding:14px 0;}
        .navbar-brand{font-weight:800;font-size:1.35rem;}
        .brand-dot{display:inline-block;width:34px;height:34px;border-radius:10px;background:var(--grad);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:14px;margin-right:8px;box-shadow:0 6px 16px rgba(108,92,231,.35);}
        .btn-gradient{background:var(--grad);color:#fff;border:0;padding:11px 22px;border-radius:12px;font-weight:600;box-shadow:0 10px 24px rgba(108,92,231,.28);transition:all .25s;}
        .btn-gradient:hover{transform:translateY(-2px);color:#fff;box-shadow:0 14px 34px rgba(108,92,231,.4);}
        .btn-ghost{border:1.5px solid rgba(11,16,32,.12);color:var(--ink);padding:11px 22px;border-radius:12px;font-weight:600;background:#fff;}
        .btn-ghost:hover{border-color:var(--c1);color:var(--c1);}

        /* HERO */
        .hero{position:relative;padding:140px 0 100px;overflow:hidden;}
        .hero::before{content:'';position:absolute;top:-250px;right:-250px;width:700px;height:700px;background:radial-gradient(circle,rgba(108,92,231,.22),transparent 60%);border-radius:50%;}
        .hero::after{content:'';position:absolute;bottom:-300px;left:-200px;width:600px;height:600px;background:radial-gradient(circle,rgba(255,107,157,.18),transparent 60%);border-radius:50%;}
        .hero-inner{position:relative;z-index:2;}
        .hero-badge{display:inline-flex;align-items:center;gap:8px;background:#fff;border:1px solid rgba(108,92,231,.2);padding:8px 16px;border-radius:30px;font-size:.85rem;font-weight:600;color:var(--c1);box-shadow:0 6px 20px rgba(108,92,231,.08);}
        .hero h1{font-size:clamp(2.2rem,5vw,4.2rem);font-weight:800;line-height:1.06;margin:22px 0 18px;}
        .hero .lead{font-size:1.18rem;color:var(--ink-2);max-width:620px;}
        .hero-cta{display:flex;gap:12px;margin-top:30px;flex-wrap:wrap;}
        .hero-proof{display:flex;align-items:center;gap:14px;margin-top:36px;color:var(--muted);font-size:.92rem;}
        .avatars{display:flex;}
        .avatars span{width:36px;height:36px;border-radius:50%;border:2px solid #fff;margin-left:-10px;background:var(--grad);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;}
        .avatars span:first-child{margin-left:0;}
        .stars{color:#ffb400;}

        /* Dashboard mockup */
        .mockup{position:relative;background:#fff;border-radius:22px;padding:14px;box-shadow:0 30px 80px rgba(13,20,60,.18);border:1px solid rgba(0,0,0,.04);}
        .mockup-top{display:flex;gap:6px;padding:8px;}
        .mockup-top span{width:11px;height:11px;border-radius:50%;background:#e4e4e7;}
        .mockup-top span:nth-child(1){background:#ff5f57;}
        .mockup-top span:nth-child(2){background:#febc2e;}
        .mockup-top span:nth-child(3){background:#28c840;}
        .mockup-body{background:linear-gradient(180deg,#f8f9ff,#fff);border-radius:14px;padding:20px;min-height:380px;}
        .mk-topbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:18px;}
        .mk-chip{background:var(--grad);color:#fff;font-weight:600;font-size:.78rem;padding:5px 12px;border-radius:20px;}
        .mk-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:18px;}
        .mk-stat{background:#fff;border:1px solid #f0f0f5;border-radius:12px;padding:14px;}
        .mk-stat .n{font-size:1.6rem;font-weight:800;color:var(--ink);}
        .mk-stat .l{font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;}
        .mk-stat .u{font-size:.72rem;color:#10b981;font-weight:600;}
        .mk-chart{background:#fff;border:1px solid #f0f0f5;border-radius:12px;padding:16px;height:180px;position:relative;overflow:hidden;}
        .mk-bars{display:flex;align-items:flex-end;gap:6px;height:100%;padding-top:10px;}
        .mk-bars div{flex:1;background:var(--grad);border-radius:4px 4px 0 0;opacity:.85;}
        .float-card{position:absolute;background:#fff;padding:14px 18px;border-radius:14px;box-shadow:0 14px 40px rgba(13,20,60,.15);display:flex;align-items:center;gap:12px;font-weight:600;}
        .float-card.one{top:60px;left:-30px;animation:float 4s ease-in-out infinite;}
        .float-card.two{bottom:50px;right:-30px;animation:float 4s ease-in-out infinite 1.5s;}
        .float-card i{width:38px;height:38px;border-radius:10px;background:var(--grad);color:#fff;display:flex;align-items:center;justify-content:center;}
        .float-card.two i{background:#25d366;}
        @keyframes float{0%,100%{transform:translateY(0);}50%{transform:translateY(-10px);}}

        /* SECTIONS */
        section{padding:100px 0;}
        .eyebrow{display:inline-block;color:var(--c1);font-weight:700;font-size:.85rem;letter-spacing:.12em;text-transform:uppercase;margin-bottom:12px;}
        .sec-title{font-size:clamp(1.8rem,3.5vw,2.75rem);font-weight:800;line-height:1.15;}
        .sec-sub{color:var(--muted);font-size:1.08rem;max-width:640px;margin:0 auto;}

        /* Logos strip */
        .logos{padding:50px 0;border-top:1px solid #eef0f5;border-bottom:1px solid #eef0f5;background:#fff;}
        .logos-grid{display:flex;justify-content:space-between;align-items:center;gap:40px;flex-wrap:wrap;opacity:.6;}
        .logos-grid span{font-weight:700;font-size:1.15rem;color:#9ca3af;}

        /* Features */
        .feature{background:#fff;border:1px solid #eef0f5;border-radius:20px;padding:32px 28px;height:100%;transition:all .3s;position:relative;overflow:hidden;}
        .feature:hover{transform:translateY(-6px);box-shadow:0 22px 50px rgba(13,20,60,.08);border-color:transparent;}
        .feature::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--grad);transform:scaleX(0);transform-origin:left;transition:transform .4s;}
        .feature:hover::before{transform:scaleX(1);}
        .feat-ico{width:56px;height:56px;border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;margin-bottom:18px;background:var(--grad);}
        .feature h5{font-weight:700;margin-bottom:10px;}
        .feature p{color:var(--muted);margin:0;font-size:.96rem;}

        /* Steps */
        .steps-wrap{background:#0b1020;color:#fff;border-radius:24px;padding:70px 50px;position:relative;overflow:hidden;}
        .steps-wrap::before{content:'';position:absolute;top:-200px;right:-100px;width:500px;height:500px;background:radial-gradient(circle,rgba(168,85,247,.25),transparent 60%);border-radius:50%;}
        .step{position:relative;z-index:2;}
        .step-num{width:44px;height:44px;border-radius:12px;background:var(--grad);display:inline-flex;align-items:center;justify-content:center;font-weight:800;margin-bottom:16px;}
        .step h4{color:#fff;font-weight:700;}
        .step p{color:#a5aac5;}

        /* Plans */
        .plans-bg{background:#fff;}
        .plan{background:#fff;border:1.5px solid #eef0f5;border-radius:22px;padding:38px 30px;height:100%;transition:all .3s;position:relative;}
        .plan:hover{transform:translateY(-4px);box-shadow:0 20px 50px rgba(13,20,60,.08);}
        .plan.popular{border-color:transparent;background:linear-gradient(180deg,#fff,#fafbff);box-shadow:0 22px 60px rgba(108,92,231,.18);position:relative;}
        .plan.popular::before{content:'';position:absolute;inset:0;border-radius:22px;padding:2px;background:var(--grad);-webkit-mask:linear-gradient(#000 0 0) content-box,linear-gradient(#000 0 0);-webkit-mask-composite:xor;mask-composite:exclude;pointer-events:none;}
        .plan .badge-pop{position:absolute;top:-14px;left:50%;transform:translateX(-50%);background:var(--grad);color:#fff;font-size:.75rem;font-weight:700;padding:6px 14px;border-radius:20px;letter-spacing:.05em;}
        .plan h4{font-weight:700;}
        .plan .price{font-size:2.8rem;font-weight:800;line-height:1;margin:18px 0 4px;}
        .plan .price small{font-size:.95rem;color:var(--muted);font-weight:500;}
        .plan ul{list-style:none;padding:0;margin:24px 0;}
        .plan li{padding:7px 0;display:flex;align-items:center;gap:10px;color:var(--ink-2);font-size:.96rem;}
        .plan li i{color:#10b981;font-size:.85rem;background:#d1fae5;width:20px;height:20px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}

        /* Showcase */
        .showcase{background:linear-gradient(180deg,#fafbff,#fff);}
        .showcase-card{border-radius:20px;overflow:hidden;box-shadow:0 12px 40px rgba(13,20,60,.08);transition:all .3s;background:#fff;border:1px solid #eef0f5;text-decoration:none;color:inherit;display:block;height:100%;}
        .showcase-card:hover{transform:translateY(-6px);box-shadow:0 26px 60px rgba(13,20,60,.14);color:inherit;}
        .showcase-thumb{height:200px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:3rem;font-weight:800;font-family:'Playfair Display',serif;position:relative;overflow:hidden;}
        .showcase-thumb::after{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='60' height='60' viewBox='0 0 60 60'%3E%3Cpath d='M30 0 L60 30 L30 60 L0 30 Z' fill='%23fff' fill-opacity='0.06'/%3E%3C/svg%3E");}

        /* Testimonials */
        .testi{background:#fff;border:1px solid #eef0f5;border-radius:20px;padding:34px 30px;height:100%;}
        .testi p{font-size:1.05rem;color:var(--ink);line-height:1.65;}
        .testi-avatar{width:48px;height:48px;border-radius:50%;background:var(--grad);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;}

        /* CTA */
        .cta{background:var(--grad);border-radius:28px;padding:70px 50px;color:#fff;text-align:center;position:relative;overflow:hidden;}
        .cta::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Ccircle cx='40' cy='40' r='2' fill='%23fff' fill-opacity='0.15'/%3E%3C/svg%3E");}
        .cta > *{position:relative;}
        .cta h2{font-weight:800;font-size:clamp(1.8rem,3.5vw,2.75rem);}
        .btn-white-cta{background:#fff;color:var(--c1);padding:14px 28px;border-radius:12px;font-weight:700;border:0;}
        .btn-white-cta:hover{color:var(--c1);transform:translateY(-2px);box-shadow:0 12px 30px rgba(0,0,0,.15);}

        /* Footer */
        footer{background:#0b1020;color:#9ca3af;padding:60px 0 30px;}
        footer h6{color:#fff;font-weight:700;margin-bottom:16px;}
        footer a{color:#9ca3af;text-decoration:none;display:block;padding:4px 0;transition:color .2s;}
        footer a:hover{color:#fff;}
        .f-bottom{border-top:1px solid rgba(255,255,255,.08);padding-top:24px;margin-top:40px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;font-size:.88rem;}

        @media (max-width:991px){
            .hero{padding:100px 0 60px;}
            .mockup{margin-top:50px;}
            .float-card.one,.float-card.two{display:none;}
            section{padding:70px 0;}
            .steps-wrap,.cta{padding:50px 28px;}
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('landing') }}">
            <span class="brand-dot"><i class="fa fa-bolt"></i></span>
            LocalBiz
        </a>
        <div class="d-none d-lg-flex mx-auto gap-4">
            <a href="#features" class="text-decoration-none text-dark fw-medium">Features</a>
            <a href="#plans" class="text-decoration-none text-dark fw-medium">Pricing</a>
            <a href="#showcase" class="text-decoration-none text-dark fw-medium">Showcase</a>
            <a href="#testimonials" class="text-decoration-none text-dark fw-medium">Reviews</a>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('login') }}" class="btn btn-ghost btn-sm d-none d-sm-inline-block">Sign In</a>
            <a href="{{ route('register') }}" class="btn btn-gradient btn-sm">Get Started <i class="fa fa-arrow-right ms-1"></i></a>
        </div>
    </div>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="container hero-inner">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="hero-badge"><i class="fa fa-sparkles text-grad"></i> Built for Jaipur SMEs</span>
                <h1>Your <span class="text-grad">business online</span> in 60 seconds.</h1>
                <p class="lead">A beautiful website, product catalog, orders, WhatsApp and analytics — all in one plan. No code. No hosting headaches. Just growth.</p>
                <div class="hero-cta">
                    <a href="{{ route('register') }}" class="btn btn-gradient"><i class="fa fa-rocket me-2"></i>Start Free Trial</a>
                    <a href="#showcase" class="btn btn-ghost"><i class="fa fa-play-circle me-2"></i>See live stores</a>
                </div>
                <div class="hero-proof">
                    <div class="avatars">
                        <span>SB</span><span>RW</span><span>AG</span><span>MK</span>
                    </div>
                    <div>
                        <div class="stars"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                        <div>Trusted by 100+ local businesses</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="mockup">
                    <div class="mockup-top"><span></span><span></span><span></span></div>
                    <div class="mockup-body">
                        <div class="mk-topbar">
                            <div>
                                <div class="fw-bold">Dashboard</div>
                                <small class="text-muted">Welcome back, Priya 👋</small>
                            </div>
                            <span class="mk-chip">GROWTH PLAN</span>
                        </div>
                        <div class="mk-stats">
                            <div class="mk-stat"><div class="n">₹82K</div><div class="l">Revenue</div><div class="u">↑ 24%</div></div>
                            <div class="mk-stat"><div class="n">147</div><div class="l">Orders</div><div class="u">↑ 18%</div></div>
                            <div class="mk-stat"><div class="n">56</div><div class="l">Enquiries</div><div class="u">↑ 9%</div></div>
                        </div>
                        <div class="mk-chart">
                            <div class="fw-semibold mb-2" style="font-size:.85rem;">Sales — Last 7 days</div>
                            <div class="mk-bars">
                                <div style="height:35%;"></div>
                                <div style="height:55%;"></div>
                                <div style="height:40%;"></div>
                                <div style="height:70%;"></div>
                                <div style="height:85%;"></div>
                                <div style="height:62%;"></div>
                                <div style="height:92%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="float-card one"><i class="fa fa-bell"></i><div><div style="font-size:.8rem;color:#6b7280;font-weight:500;">New order</div>₹ 4,299</div></div>
                    <div class="float-card two"><i class="fab fa-whatsapp"></i><div><div style="font-size:.8rem;color:#6b7280;font-weight:500;">WhatsApp</div>+1 enquiry</div></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LOGO STRIP -->
<div class="logos">
    <div class="container">
        <div class="text-center mb-3"><small class="text-muted text-uppercase" style="letter-spacing:.15em;font-weight:600;">Powering businesses across Jaipur</small></div>
        <div class="logos-grid justify-content-center">
            <span><i class="fa fa-gem me-2"></i>Saanvi Boutique</span>
            <span><i class="fa fa-couch me-2"></i>Royal Wood</span>
            <span><i class="fa fa-gift me-2"></i>Pink City Gifts</span>
            <span><i class="fa fa-tools me-2"></i>QuickFix Services</span>
            <span><i class="fa fa-cut me-2"></i>Raja Tailors</span>
        </div>
    </div>
</div>

<!-- FEATURES -->
<section id="features">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow">Everything in one place</span>
            <h2 class="sec-title">Sell online without the tech headache</h2>
            <p class="sec-sub mt-3">We built the tools. You run your business. From website to checkout — it's all managed from one dashboard.</p>
        </div>
        <div class="row g-4">
            @php
                $features = [
                    ['icon'=>'fa-store','title'=>'Your own storefront','desc'=>'Mobile-first website on your own URL. Boutique, furniture or service theme — change with one click.'],
                    ['icon'=>'fa-box-open','title'=>'Product catalog','desc'=>'Unlimited products, photos, categories, stock, featured flags. Built-in inventory.'],
                    ['icon'=>'fa-cart-shopping','title'=>'Cart & checkout','desc'=>'Session-based cart, COD and Razorpay-ready online payments. Automatic order tracking.'],
                    ['icon'=>'fa-comments','title'=>'WhatsApp first','desc'=>'One-click WhatsApp floating button. New order? You get a pre-filled message instantly.'],
                    ['icon'=>'fa-chart-line','title'=>'Live analytics','desc'=>'Revenue, orders, enquiries — clean dashboards so you always know what is working.'],
                    ['icon'=>'fa-shield-halved','title'=>'Multi-tenant secure','desc'=>'Every tenant fully isolated. Plan-expiry checks, role-based access, admin oversight.'],
                ];
            @endphp
            @foreach($features as $f)
                <div class="col-md-6 col-lg-4">
                    <div class="feature">
                        <div class="feat-ico"><i class="fa {{ $f['icon'] }}"></i></div>
                        <h5>{{ $f['title'] }}</h5>
                        <p>{{ $f['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- STEPS -->
<section style="padding-top:0;">
    <div class="container">
        <div class="steps-wrap">
            <div class="text-center mb-5">
                <span class="eyebrow" style="color:#ff6b9d;">How it works</span>
                <h2 class="sec-title text-white mt-1">Go live in 3 simple steps</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4 step">
                    <div class="step-num">1</div>
                    <h4>Create account</h4>
                    <p>Sign up in 30 seconds — pick your business name and plan.</p>
                </div>
                <div class="col-md-4 step">
                    <div class="step-num">2</div>
                    <h4>Add products</h4>
                    <p>Upload photos, set prices, pick a theme. We handle the rest.</p>
                </div>
                <div class="col-md-4 step">
                    <div class="step-num">3</div>
                    <h4>Start selling</h4>
                    <p>Share your link on WhatsApp, Instagram, flyers — orders start flowing.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PLANS -->
<section id="plans" class="plans-bg">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow">Simple pricing</span>
            <h2 class="sec-title">Pick a plan, grow without limits</h2>
            <p class="sec-sub mt-3">All plans include free updates, SSL, and Jaipur-based support. Upgrade or cancel anytime.</p>
        </div>
        <div class="row g-4 justify-content-center">
            @forelse($plans as $i => $plan)
                <div class="col-md-6 col-lg-4">
                    <div class="plan {{ $i === 1 ? 'popular' : '' }}">
                        @if($i === 1)<span class="badge-pop">MOST POPULAR</span>@endif
                        <h4>{{ $plan->name }}</h4>
                        <p class="text-muted small mb-0">For {{ $i === 0 ? 'new' : ($i === 1 ? 'growing' : 'established') }} businesses</p>
                        <div class="price">₹{{ number_format($plan->price, 0) }}<small> / {{ $plan->duration_days }} days</small></div>
                        <ul>
                            <li><i class="fa fa-check"></i> Up to {{ $plan->max_products }} products</li>
                            @foreach((array)$plan->features as $feat)
                                <li><i class="fa fa-check"></i> {{ $feat }}</li>
                            @endforeach
                        </ul>
                        <a href="{{ route('register') }}" class="btn {{ $i === 1 ? 'btn-gradient' : 'btn-ghost' }} w-100">{{ $i === 1 ? 'Start Growth Plan' : 'Choose ' . $plan->name }}</a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted">No plans available.</div>
            @endforelse
        </div>
    </div>
</section>

@if($featuredTenants->count())
<!-- SHOWCASE -->
<section id="showcase" class="showcase">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow">Live stores</span>
            <h2 class="sec-title">Jaipur businesses already selling online</h2>
            <p class="sec-sub mt-3">Real businesses, real sales. See how they're using LocalBiz to grow.</p>
        </div>
        <div class="row g-4">
            @foreach($featuredTenants as $t)
                @php $color = $t->primary_color ?: '#6c5ce7'; @endphp
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route('tenant.home', $t->slug) }}" target="_blank" class="showcase-card">
                        <div class="showcase-thumb" style="background:linear-gradient(135deg,{{ $color }},#0b1020);">
                            {{ strtoupper(substr($t->business_name,0,2)) }}
                        </div>
                        <div class="p-4">
                            <h5 class="mb-1 fw-bold">{{ $t->business_name }}</h5>
                            <p class="text-muted small mb-2">{{ $t->tagline ?: ucfirst($t->theme).' theme store' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="fa fa-location-dot me-1"></i>{{ $t->city ?: 'Jaipur' }}</small>
                                <small class="fw-semibold" style="color:{{ $color }};">Visit <i class="fa fa-arrow-right"></i></small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- TESTIMONIALS -->
<section id="testimonials">
    <div class="container">
        <div class="text-center mb-5">
            <span class="eyebrow">Loved by owners</span>
            <h2 class="sec-title">Businesses are saying nice things</h2>
        </div>
        <div class="row g-4">
            @php
                $testi = [
                    ['q'=>'Pehle Instagram DMs mein sab order manage karti thi. Ab sab website pe aa raha hai, WhatsApp auto notify hota hai. Life sorted.','n'=>'Priya Sharma','r'=>'Saanvi Boutique','i'=>'PS'],
                    ['q'=>'Pro plan leke 15 din mein 3 dining table bech diye online. Setup simple tha, support bhi Jaipur se hi milta hai.','n'=>'Rakesh Jangid','r'=>'Royal Wood Crafts','i'=>'RJ'],
                    ['q'=>'Service business ka alag theme, appointment form, WhatsApp link — sab kuch ready mila. Worth every rupee.','n'=>'Mohit Kumar','r'=>'QuickFix Services','i'=>'MK'],
                ];
            @endphp
            @foreach($testi as $t)
                <div class="col-md-4">
                    <div class="testi">
                        <div class="stars mb-3"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
                        <p>"{{ $t['q'] }}"</p>
                        <div class="d-flex align-items-center gap-3 mt-4">
                            <div class="testi-avatar">{{ $t['i'] }}</div>
                            <div>
                                <div class="fw-bold">{{ $t['n'] }}</div>
                                <small class="text-muted">{{ $t['r'] }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA -->
<section style="padding-top:0;">
    <div class="container">
        <div class="cta">
            <h2 class="mb-3">Ready to take your business online?</h2>
            <p class="lead mb-4 opacity-90">Free 7-day trial. No credit card. Cancel anytime.</p>
            <a href="{{ route('register') }}" class="btn btn-white-cta btn-lg"><i class="fa fa-rocket me-2"></i>Start Free Trial</a>
        </div>
    </div>
</section>

<footer>
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="d-flex align-items-center mb-3">
                    <span class="brand-dot"><i class="fa fa-bolt"></i></span>
                    <strong style="color:#fff;font-size:1.25rem;">LocalBiz</strong>
                </div>
                <p>Multi-tenant SaaS for Jaipur-based boutiques, furniture stores, and service businesses. Website + orders + WhatsApp — all in one place.</p>
                <div class="d-flex gap-2 mt-3">
                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fab fa-whatsapp"></i></a>
                    <a href="#" class="btn btn-sm btn-outline-secondary"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Product</h6>
                <a href="#features">Features</a>
                <a href="#plans">Pricing</a>
                <a href="#showcase">Showcase</a>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Company</h6>
                <a href="#">About</a>
                <a href="#">Careers</a>
                <a href="#">Contact</a>
            </div>
            <div class="col-lg-4">
                <h6>Get the owner app</h6>
                <p class="small">Soon on Play Store — manage your orders & enquiries on the go.</p>
                <form class="d-flex gap-2 mt-2"><input class="form-control form-control-sm" placeholder="you@business.com"><button class="btn btn-gradient btn-sm" type="button">Notify me</button></form>
            </div>
        </div>
        <div class="f-bottom">
            <div>&copy; {{ date('Y') }} LocalBiz SaaS. Made with <i class="fa fa-heart text-danger"></i> in Jaipur.</div>
            <div><a href="#" class="d-inline me-3">Privacy</a><a href="#" class="d-inline">Terms</a></div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
