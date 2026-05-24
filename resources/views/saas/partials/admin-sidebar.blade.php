<div class="sidebar-area bg-white border-end">
    <div class="sidebar-brand">
        <div class="logo-circle">LB</div>
        <div>
            <div class="fw-bold">LocalBiz</div>
            <small class="text-secondary">Super Admin</small>
        </div>
    </div>
    <div class="sidebar-menu" data-simplebar>
        <ul class="sidebar-menu-list px-2 py-3">
            @php $r = request()->route()?->getName(); @endphp

            <li class="sidebar-menu-list-item {{ $r === 'admin.dashboard' ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded">
                    <i class="ri-dashboard-line"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'admin.tenants') ? 'active' : '' }}">
                <a href="{{ route('admin.tenants.index') }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded">
                    <i class="ri-store-2-line"></i><span>Tenants (Businesses)</span>
                </a>
            </li>

            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'admin.plans') ? 'active' : '' }}">
                <a href="{{ route('admin.plans.index') }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded">
                    <i class="ri-price-tag-3-line"></i><span>Subscription Plans</span>
                </a>
            </li>

            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'admin.orders') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.index') }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded">
                    <i class="ri-shopping-bag-3-line"></i><span>All Orders</span>
                </a>
            </li>

            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'admin.enquiries') ? 'active' : '' }}">
                <a href="{{ route('admin.enquiries.index') }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded">
                    <i class="ri-question-answer-line"></i><span>All Enquiries</span>
                </a>
            </li>

            @php $pendingSubs = \App\Models\SubscriptionPayment::where('status','pending_verification')->count(); @endphp
            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'admin.subscriptions') ? 'active' : '' }}">
                <a href="{{ route('admin.subscriptions.index') }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded">
                    <i class="ri-bank-card-2-line"></i>
                    <span>UPI Payments</span>
                    @if($pendingSubs) <span class="badge bg-danger ms-auto">{{ $pendingSubs }}</span> @endif
                </a>
            </li>
        </ul>
    </div>
</div>
