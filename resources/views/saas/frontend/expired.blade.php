<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>{{ $tenant->business_name }} — Unavailable</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container text-center py-5">
        <div class="card border-0 shadow-sm mx-auto" style="max-width:560px;">
            <div class="card-body p-5">
                <div style="font-size:56px;">😔</div>
                <h3 class="fw-bold">{{ $tenant->business_name ?? 'Website' }}</h3>

                @if(!$tenant->hasVerifiedPayment())
                <p class="text-muted mb-2">This website is <strong>not live yet</strong>.</p>

                @elseif($tenant->isExpired())
                <p class="text-muted mb-2">This website is temporarily unavailable.</p>

                @if($tenant->expiry_date)
                <p class="small text-muted mb-0">
                    Your plan expired on
                    <strong>{{ $tenant->expiry_date->format('d M Y') }}</strong>.
                    Please renew from dashboard.
                </p>
                @endif

                @elseif($tenant->status !== 'active')
                <p class="text-muted mb-2">This website is temporarily unavailable.</p>

                @else
                <p class="text-muted">Please check back soon.</p>
                @endif
            </div>
        </div>
    </div>
</body>

</html>