<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $tenant->business_name) · {{ $tenant->business_name }}</title>
    <meta name="description" content="{{ $tenant->tagline ?? $tenant->business_name }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@500;600;700;800&family=DM+Serif+Display&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: {{ $tenant->primary_color ?: '#e91e63' }};
            --brand-soft: {{ $tenant->accent_color ?: ($tenant->primary_color ?: '#e91e63') . '14' }};
            --brand-dark: {{ $tenant->primary_color ?: '#c2185b' }};
            --ink: {{ $tenant->text_color ?: '#111418' }};
            --muted:#6b7280;--line:#eceef3;
            --bg: {{ $tenant->background_color ?: '#fafbfc' }};
            --surface: {{ $tenant->background_color && $tenant->background_color !== '#ffffff' ? '#ffffff' : '#ffffff' }};
        }
        *{box-sizing:border-box;}
        body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;color:var(--ink);background:var(--bg);-webkit-font-smoothing:antialiased;line-height:1.6;}
        h1,h2,h3,h4,h5,h6{color:var(--ink);}
        h1,h2,h3,h4,h5{letter-spacing:-.01em;}
        .display-serif{font-family:'Playfair Display',serif;font-weight:700;letter-spacing:-.015em;}
        a{color:var(--brand);text-decoration:none;}
        a:hover{color:var(--brand-dark);}

        .btn-brand{background:var(--brand);color:#fff;border:0;border-radius:10px;padding:10px 20px;font-weight:600;transition:all .22s;}
        .btn-brand:hover{background:var(--brand-dark);color:#fff;transform:translateY(-1px);box-shadow:0 10px 24px -8px var(--brand);}
        .btn-brand-outline{background:transparent;color:var(--brand);border:1.5px solid var(--brand);border-radius:10px;padding:10px 20px;font-weight:600;}
        .btn-brand-outline:hover{background:var(--brand);color:#fff;}
        .text-brand{color:var(--brand)!important;}
        .bg-brand{background:var(--brand)!important;color:#fff;}
        .bg-brand-soft{background:var(--brand-soft)!important;}

        /* NAV */
        .site-nav{backdrop-filter:blur(20px);background:rgba(255,255,255,.92)!important;border-bottom:1px solid var(--line);padding:14px 0;}
        .site-nav .navbar-brand{font-weight:800;color:var(--ink)!important;font-size:1.25rem;letter-spacing:-.02em;display:flex;align-items:center;gap:10px;}
        .brand-mark{width:34px;height:34px;border-radius:10px;background:var(--brand);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-family:'DM Serif Display',serif;font-size:1rem;box-shadow:0 6px 16px -4px var(--brand);}
        .site-nav .nav-link{color:#374151!important;font-weight:500;font-size:.96rem;padding:8px 14px!important;border-radius:8px;transition:all .2s;}
        .site-nav .nav-link:hover{color:var(--brand)!important;background:var(--brand-soft);}
        .site-nav .nav-link.active{color:var(--brand)!important;font-weight:700;}
        .cart-btn{width:42px;height:42px;border-radius:50%;background:var(--brand-soft);color:var(--brand);display:inline-flex;align-items:center;justify-content:center;position:relative;transition:all .2s;}
        .cart-btn:hover{background:var(--brand);color:#fff;}
        .cart-badge{position:absolute;top:-4px;right:-4px;background:#ef4444;color:#fff;font-size:.65rem;font-weight:700;min-width:18px;height:18px;border-radius:9px;display:flex;align-items:center;justify-content:center;padding:0 4px;}

        /* Alert */
        .alert{border:0;border-radius:12px;}

        /* Cards */
        .product-card{border:1px solid var(--line);background:var(--surface);border-radius:16px;overflow:hidden;transition:all .3s;height:100%;display:flex;flex-direction:column;}
        .product-card:hover{transform:translateY(-6px);box-shadow:0 24px 60px -15px rgba(0,0,0,.15);border-color:transparent;}
        .product-card .p-img{aspect-ratio:4/5;overflow:hidden;background:linear-gradient(135deg,var(--brand-soft),#f3f4f6);display:flex;align-items:center;justify-content:center;position:relative;}
        .product-card .p-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s;}
        .product-card:hover .p-img img{transform:scale(1.06);}
        .product-card .p-placeholder{font-family:'DM Serif Display',serif;font-size:3rem;color:var(--brand);opacity:.35;}
        .product-card .p-badge{position:absolute;top:12px;left:12px;background:#fff;color:var(--ink);font-size:.7rem;font-weight:700;padding:4px 10px;border-radius:20px;letter-spacing:.05em;text-transform:uppercase;}
        .product-card .p-badge.sale{background:#ef4444;color:#fff;}
        .product-card .p-body{padding:16px 18px;flex:1;display:flex;flex-direction:column;}
        .product-card .p-cat{font-size:.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:.08em;font-weight:600;}
        .product-card .p-name{font-weight:700;font-size:1rem;color:var(--ink);margin:4px 0;line-height:1.35;}
        .product-card .p-name a{color:inherit;}
        .product-card .p-name a:hover{color:var(--brand);}
        .product-card .p-price{font-size:1.15rem;font-weight:800;color:var(--ink);}
        .product-card .p-mrp{text-decoration:line-through;color:var(--muted);font-size:.85rem;font-weight:500;margin-left:6px;}
        .product-card .p-foot{display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:12px;}
        .product-card .add-btn{background:var(--brand);color:#fff;border:0;border-radius:50%;width:38px;height:38px;display:inline-flex;align-items:center;justify-content:center;transition:all .22s;}
        .product-card .add-btn:hover{background:var(--brand-dark);transform:rotate(90deg);}

        /* Sections */
        .sec-eyebrow{display:inline-block;color:var(--brand);font-weight:700;font-size:.8rem;letter-spacing:.15em;text-transform:uppercase;margin-bottom:10px;}
        .sec-title{font-weight:800;font-size:clamp(1.6rem,3vw,2.4rem);letter-spacing:-.02em;line-height:1.15;}
        .divider-ornament{display:flex;align-items:center;justify-content:center;gap:14px;color:var(--brand);opacity:.65;margin:8px 0 24px;}
        .divider-ornament::before,.divider-ornament::after{content:'';width:40px;height:1px;background:var(--brand);opacity:.4;}

        /* Enquiry */
        .enquiry-bg{position:relative;overflow:hidden;background:var(--ink);color:#fff;padding:80px 0;}
        .enquiry-bg::before{content:'';position:absolute;top:-200px;right:-200px;width:500px;height:500px;background:radial-gradient(circle,var(--brand),transparent 60%);opacity:.3;border-radius:50%;}
        .enquiry-card{background:#fff;color:var(--ink);border-radius:20px;padding:34px 32px;box-shadow:0 30px 60px rgba(0,0,0,.25);}
        .contact-item{display:flex;gap:14px;align-items:flex-start;margin-bottom:16px;}
        .contact-ico{width:42px;height:42px;border-radius:10px;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;}

        /* WhatsApp */
        .whatsapp-btn{position:fixed;bottom:26px;right:26px;width:60px;height:60px;border-radius:50%;background:#25d366;color:#fff;display:flex;align-items:center;justify-content:center;font-size:28px;box-shadow:0 14px 30px rgba(37,211,102,.5);z-index:999;text-decoration:none;transition:all .22s;}
        .whatsapp-btn:hover{color:#fff;transform:scale(1.08);}
        .whatsapp-btn::before{content:'';position:absolute;inset:-4px;border-radius:50%;border:2px solid #25d366;animation:pulse 2s infinite;}
        @keyframes pulse{0%{transform:scale(1);opacity:1;}100%{transform:scale(1.4);opacity:0;}}
        .whatsapp-label{position:fixed;bottom:40px;right:96px;background:#111;color:#fff;padding:6px 14px;border-radius:20px;font-size:.85rem;font-weight:600;z-index:998;opacity:0;transform:translateX(10px);transition:all .25s;pointer-events:none;box-shadow:0 8px 20px rgba(0,0,0,.2);}
        .whatsapp-wrap:hover .whatsapp-label{opacity:1;transform:translateX(0);}

        /* Footer */
        footer.site-footer{background:#0a0d12;color:#9ca3af;padding:70px 0 30px;margin-top:80px;}
        footer.site-footer h6{color:#fff;font-weight:700;margin-bottom:18px;}
        footer.site-footer a{color:#9ca3af;display:block;padding:3px 0;transition:color .18s;}
        footer.site-footer a:hover{color:#fff;}
        footer.site-footer .social{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;border-radius:50%;background:rgba(255,255,255,.06);margin-right:8px;transition:all .2s;}
        footer.site-footer .social:hover{background:var(--brand);color:#fff;transform:translateY(-2px);}
        footer.site-footer .f-bottom{border-top:1px solid rgba(255,255,255,.08);margin-top:40px;padding-top:24px;display:flex;justify-content:space-between;flex-wrap:wrap;gap:10px;font-size:.86rem;}

        /* Utility */
        .rounded-lux{border-radius:24px;}
        .shadow-lux{box-shadow:0 20px 60px -20px rgba(0,0,0,.2);}
        .chip{display:inline-flex;align-items:center;gap:6px;background:#fff;border:1px solid var(--line);padding:6px 14px;border-radius:30px;font-size:.82rem;font-weight:600;color:var(--ink);}

        @media (max-width:768px){
            .whatsapp-btn{width:52px;height:52px;font-size:24px;}
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg site-nav sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ route('tenant.home', $tenant->slug) }}">
            @if($tenant->logo)
                <img src="{{ asset('storage/' . $tenant->logo) }}" style="height:34px;">
            @else
                <span class="brand-mark">{{ strtoupper(substr($tenant->business_name,0,1)) }}</span>
                <span>{{ $tenant->business_name }}</span>
            @endif
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navMenu"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navMenu">
            @php
                $rn = request()->route()?->getName();
                $isShop = $tenant->isShopMode();
                $listingsLabel = \App\Support\ListingFilters::listingsLabel($tenant->theme ?? 'boutique');
            @endphp
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link {{ $rn==='tenant.home'?'active':'' }}" href="{{ route('tenant.home', $tenant->slug) }}">Home</a></li>
                @if($isShop)
                    <li class="nav-item"><a class="nav-link {{ $rn==='tenant.products'?'active':'' }}" href="{{ route('tenant.products', $tenant->slug) }}">{{ $listingsLabel }}</a></li>
                @endif
                <li class="nav-item"><a class="nav-link {{ $rn==='tenant.about'?'active':'' }}" href="{{ route('tenant.about', $tenant->slug) }}">About</a></li>
                <li class="nav-item"><a class="nav-link {{ $rn==='tenant.contact'?'active':'' }}" href="{{ route('tenant.contact', $tenant->slug) }}">{{ $isShop ? 'Contact' : 'Enquire' }}</a></li>
                @if($isShop)
                <li class="nav-item ms-lg-2">
                    @php $cart = session('cart_' . $tenant->id, []); $cartCount = array_sum(array_column($cart, 'qty')); @endphp
                    <a class="cart-btn" href="{{ route('tenant.cart', $tenant->slug) }}" title="Cart">
                        <i class="fa fa-shopping-bag"></i>
                        @if($cartCount > 0)<span class="cart-badge">{{ $cartCount }}</span>@endif
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

@if(session('success'))
    <div class="container mt-3"><div class="alert alert-success alert-dismissible fade show"><i class="fa fa-circle-check me-2"></i>{{ session('success') }}<button class="btn-close" data-bs-dismiss="alert"></button></div></div>
@endif
@if(session('error'))
    <div class="container mt-3"><div class="alert alert-danger alert-dismissible fade show"><i class="fa fa-circle-exclamation me-2"></i>{{ session('error') }}<button class="btn-close" data-bs-dismiss="alert"></button></div></div>
@endif

@yield('content')

<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-5">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="brand-mark">{{ strtoupper(substr($tenant->business_name,0,1)) }}</span>
                    <strong style="color:#fff;font-size:1.2rem;">{{ $tenant->business_name }}</strong>
                </div>
                <p style="max-width:420px;">{{ $tenant->tagline ?: 'Built with love in Jaipur. Every product handpicked with care.' }}</p>
                <div class="mt-3">
                    @if($tenant->whatsapp)<a href="https://wa.me/{{ preg_replace('/\D/','',$tenant->whatsapp) }}" class="social" target="_blank"><i class="fab fa-whatsapp"></i></a>@endif
                    <a href="#" class="social"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social"><i class="fab fa-facebook"></i></a>
                </div>
            </div>
            @if($tenant->isShopMode())
            <div class="col-6 col-lg-2">
                <h6>Shop</h6>
                <a href="{{ route('tenant.home', $tenant->slug) }}">Home</a>
                <a href="{{ route('tenant.products', $tenant->slug) }}">All products</a>
                <a href="{{ route('tenant.cart', $tenant->slug) }}">Cart</a>
            </div>
            @else
            <div class="col-6 col-lg-2">
                <h6>Explore</h6>
                <a href="{{ route('tenant.home', $tenant->slug) }}">Home</a>
                <a href="{{ route('tenant.about', $tenant->slug) }}">About us</a>
                <a href="{{ route('tenant.contact', $tenant->slug) }}">Enquire</a>
            </div>
            @endif
            <div class="col-6 col-lg-2">
                <h6>Company</h6>
                <a href="{{ route('tenant.about', $tenant->slug) }}">About</a>
                <a href="{{ route('tenant.contact', $tenant->slug) }}">Contact</a>
            </div>
            <div class="col-lg-3">
                <h6>Visit us</h6>
                @if($tenant->address)<p class="small mb-1"><i class="fa fa-location-dot me-1"></i>{{ $tenant->address }}, {{ $tenant->city }}</p>@endif
                @if($tenant->phone)<p class="small mb-1"><i class="fa fa-phone me-1"></i>{{ $tenant->phone }}</p>@endif
                @if($tenant->email)<p class="small mb-0"><i class="fa fa-envelope me-1"></i>{{ $tenant->email }}</p>@endif
            </div>
        </div>
        <div class="f-bottom">
            <div>&copy; {{ date('Y') }} {{ $tenant->business_name }}. All rights reserved.</div>
            <div class="small">Powered by <a href="{{ route('landing') }}" style="color:var(--brand);font-weight:600;">LocalBiz</a></div>
        </div>
    </div>
</footer>

@if($tenant->whatsapp)
    <div class="whatsapp-wrap">
        <span class="whatsapp-label d-none d-md-block">Chat on WhatsApp</span>
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $tenant->whatsapp) }}?text=Hi%20{{ urlencode($tenant->business_name) }}%2C%20I%20am%20interested" target="_blank" class="whatsapp-btn" title="Chat on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
