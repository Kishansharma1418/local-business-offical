<header class="header-area position-sticky top-0 start-0 d-flex flex-wrap justify-content-between align-items-center gap-2" style="z-index: 10;">
    <div class="d-flex align-items-center gap-3">
        <button class="sidebar-menu-toggle-mobile btn btn-sm btn-light d-md-none">
            <i class="ri-menu-2-line fs-22"></i>
        </button>
        <h5 class="mb-0 fw-medium">{{ $panelLabel ?? 'Dashboard' }}</h5>
    </div>
    <div class="d-flex align-items-center gap-3 py-2 py-md-0">
        @auth
            @if(auth()->user()->role === 'client' && auth()->user()->tenant && ($tenantCanManage ?? auth()->user()->tenant->canManageContent()))
                <a href="{{ route('tenant.home', auth()->user()->tenant->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                    <i class="ri-external-link-line"></i> View My Website
                </a>
            @endif
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <span class="d-inline-flex align-items-center justify-content-center rounded-circle bg-primary text-white" style="width:32px;height:32px;font-weight:600;">
                        {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->email, 0, 1)) }}
                    </span>
                    <span class="d-none d-md-inline">{{ auth()->user()->name ?? auth()->user()->email }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @if(auth()->user()->role === 'client')
                        @if($tenantCanManage ?? auth()->user()->tenant?->canManageContent())
                            <li><a class="dropdown-item" href="{{ route('client.settings.edit') }}"><i class="ri-settings-3-line me-1"></i> Business Settings</a></li>
                        @else
                            <li><a class="dropdown-item" href="{{ route('client.subscription.index') }}"><i class="ri-secure-payment-line me-1"></i> Activate subscription</a></li>
                        @endif
                    @endif
                    <li>
                        <form method="POST" action="{{ route('logout') }}">@csrf
                            <button class="dropdown-item" type="submit"><i class="ri-logout-box-r-line me-1"></i> Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        @endauth
    </div>
</header>
