<div class="sidebar-area bg-white border-end">
    <div class="sidebar-brand">
        <div class="logo-circle">{{ strtoupper(substr(auth()->user()->tenant->business_name ?? 'B', 0, 2)) }}</div>
        <div>
            <div class="fw-bold">{{ auth()->user()->tenant->business_name ?? 'My Business' }}</div>
            <small class="text-secondary">Client Dashboard</small>
        </div>
    </div>
    <div class="sidebar-menu" data-simplebar>
        @php
            $r = request()->route()?->getName();
            $canManage = $tenantCanManage ?? auth()->user()->tenant?->canManageContent();
            $lockUrl = route('client.payment.required');
        @endphp
        <ul class="sidebar-menu-list px-2 py-3">
            @if(!$canManage)
                <li class="px-3 pb-2">
                    <div class="small rounded px-2 py-2" style="background:rgba(245,158,11,.15);color:#b45309;">
                        <i class="ri-lock-line me-1"></i> Pay &amp; verify UPI to unlock
                    </div>
                </li>
            @endif

            <li class="sidebar-menu-list-item {{ $r === 'client.dashboard' ? 'active' : '' }}">
                <a href="{{ $canManage ? route('client.dashboard') : $lockUrl }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded {{ $canManage ? '' : 'opacity-50' }}">
                    <i class="ri-dashboard-line"></i><span>Dashboard</span>
                </a>
            </li>

            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'client.products') ? 'active' : '' }}">
                <a href="{{ $canManage ? route('client.products.index') : $lockUrl }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded {{ $canManage ? '' : 'opacity-50' }}">
                    <i class="ri-shopping-bag-line"></i><span>Products</span>
                </a>
            </li>

            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'client.orders') ? 'active' : '' }}">
                <a href="{{ $canManage ? route('client.orders.index') : $lockUrl }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded {{ $canManage ? '' : 'opacity-50' }}">
                    <i class="ri-bill-line"></i><span>Orders</span>
                </a>
            </li>

            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'client.enquiries') ? 'active' : '' }}">
                <a href="{{ $canManage ? route('client.enquiries.index') : $lockUrl }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded {{ $canManage ? '' : 'opacity-50' }}">
                    <i class="ri-question-answer-line"></i><span>Enquiries</span>
                </a>
            </li>

            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'client.pages') ? 'active' : '' }}">
                <a href="{{ $canManage ? route('client.pages.index') : $lockUrl }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded {{ $canManage ? '' : 'opacity-50' }}">
                    <i class="ri-file-edit-line"></i><span>Website Pages</span>
                </a>
            </li>

            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'client.settings') ? 'active' : '' }}">
                <a href="{{ $canManage ? route('client.settings.edit') : $lockUrl }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded {{ $canManage ? '' : 'opacity-50' }}">
                    <i class="ri-settings-3-line"></i><span>Business Settings</span>
                </a>
            </li>

            <li class="sidebar-menu-list-item {{ str_starts_with($r ?? '', 'client.subscription') || str_starts_with($r ?? '', 'client.payment') ? 'active' : '' }}">
                <a href="{{ route('client.subscription.index') }}" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded">
                    <i class="ri-vip-crown-line"></i><span>Subscription</span>
                    @if(!$canManage)<span class="badge bg-warning text-dark ms-auto">Pay</span>@endif
                </a>
            </li>

            @if($canManage && auth()->user()->tenant)
            <li class="sidebar-menu-list-item">
                <a href="{{ route('tenant.home', auth()->user()->tenant->slug) }}" target="_blank" class="sidebar-menu-link d-flex align-items-center gap-2 py-2 px-3 rounded">
                    <i class="ri-external-link-line"></i><span>View Website</span>
                </a>
            </li>
            @endif
        </ul>
    </div>
</div>
