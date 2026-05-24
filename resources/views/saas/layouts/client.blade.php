<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') &middot; {{ auth()->user()->tenant->business_name ?? 'Client' }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @include('saas.partials.dashboard-ui-styles', ['variant' => 'client'])
    <style>
        @stack('layout-styles')
    </style>
    @stack('styles')
</head>
<body class="bg-body-bg">

@include('saas.partials.client-sidebar')

<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        @include('saas.partials.topbar', ['panelLabel' => auth()->user()->tenant->business_name ?? 'Client'])

        <div class="main-content-container overflow-hidden">
            @if(($tenantCanManage ?? false) && auth()->user()->tenant && auth()->user()->tenant->expiry_date && auth()->user()->tenant->daysLeft() <= 7)
                <div class="alert alert-warning">
                    <i class="fa fa-triangle-exclamation me-2"></i>
                    Your plan expires in <b>{{ auth()->user()->tenant->daysLeft() }}</b> day(s) (on {{ auth()->user()->tenant->expiry_date->format('d M Y') }}). Please renew to avoid service interruption.
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show"><i class="fa fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show"><i class="fa fa-circle-exclamation me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@stack('scripts')
</body>
</html>
