@extends('saas.layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <h2>Welcome back, {{ auth()->user()->name ?? 'Admin' }} 👋</h2>
        <div class="sub">Here's what's happening across your SaaS today.</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.tenants.create') }}" class="btn btn-primary"><i class="fa fa-plus me-1"></i> New Tenant</a>
        <a href="{{ route('admin.plans.create') }}" class="btn btn-outline-primary"><i class="fa fa-price-tag"></i> New Plan</a>
    </div>
</div>

<div class="row g-3 mb-4">
    @php
        $cards = [
            ['label'=>'Total Tenants','value'=>$totalTenants,'icon'=>'fa-store','class'=>'bg-grad-pink','hint'=>'All businesses'],
            ['label'=>'Active','value'=>$activeTenants,'icon'=>'fa-circle-check','class'=>'bg-grad-green','hint'=>'Paying now'],
            ['label'=>'Expired','value'=>$expiredTenants,'icon'=>'fa-clock-rotate-left','class'=>'bg-grad-red','hint'=>'Need renewal'],
            ['label'=>'Plans','value'=>$totalPlans,'icon'=>'fa-price-tag','class'=>'bg-grad-purple','hint'=>'Subscription tiers'],
            ['label'=>'Total Orders','value'=>$totalOrders,'icon'=>'fa-bag-shopping','class'=>'bg-grad-blue','hint'=>'Across tenants'],
            ['label'=>'Enquiries','value'=>$totalEnquiries,'icon'=>'fa-comments','class'=>'bg-grad-orange','hint'=>'Lead volume'],
            ['label'=>'Order Revenue','value'=>'₹'.number_format($revenue,0),'icon'=>'fa-indian-rupee-sign','class'=>'bg-grad-green','hint'=>'Paid orders'],
            ['label'=>'MRR (Plans)','value'=>'₹'.number_format($subscriptionRevenue,0),'icon'=>'fa-arrows-rotate','class'=>'bg-grad-purple','hint'=>'Recurring revenue'],
        ];
    @endphp
    @foreach($cards as $c)
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon {{ $c['class'] }}"><i class="fa {{ $c['icon'] }}"></i></div>
                    <div>
                        <small class="text-muted text-uppercase" style="letter-spacing:.05em;font-size:.72rem;font-weight:700;">{{ $c['label'] }}</small>
                        <h4 class="mb-0 fw-bold" style="letter-spacing:-.01em;">{{ $c['value'] }}</h4>
                        <small class="text-muted" style="font-size:.78rem;">{{ $c['hint'] }}</small>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Revenue — Last 6 Months</h5><span class="badge-soft-success badge">Growth ↑</span></div>
            <div class="card-body"><div id="revenueChart"></div></div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Recent Tenants</h5><a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-light">View all</a></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                        @forelse($recentTenants as $t)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,{{ $t->primary_color ?? '#6c5ce7' }},#0b1020);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;">{{ strtoupper(substr($t->business_name,0,2)) }}</div>
                                        <div>
                                            <div class="fw-bold">{{ $t->business_name }}</div>
                                            <small class="text-muted">{{ optional($t->plan)->name ?? 'No plan' }} &middot; /{{ $t->slug }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <span class="badge badge-soft-{{ $t->status === 'active' ? 'success' : 'danger' }}">{{ ucfirst($t->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="text-center text-muted py-4">No tenants yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Latest Orders (all tenants)</h5><a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light">View all</a></div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Order #</th><th>Tenant</th><th>Customer</th><th>Amount</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($recentOrders as $o)
                    <tr>
                        <td><b style="color:var(--lb-1);">{{ $o->order_number }}</b></td>
                        <td>{{ $o->tenant?->business_name ?? '—' }}</td>
                        <td>
                            <div>{{ $o->customer_name }}</div>
                            <small class="text-muted">{{ $o->phone }}</small>
                        </td>
                        <td class="fw-bold">₹{{ number_format($o->total_amount, 0) }}</td>
                        <td><span class="badge badge-soft-{{ $o->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($o->payment_status) }}</span></td>
                        <td><span class="badge badge-soft-info">{{ ucfirst($o->order_status) }}</span></td>
                        <td class="text-muted small">{{ $o->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No orders yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
new ApexCharts(document.querySelector("#revenueChart"), {
    chart: { type: 'area', height: 300, toolbar:{show:false}, fontFamily: 'Plus Jakarta Sans, sans-serif' },
    series: [{ name: 'Revenue', data: @json($months->pluck('revenue')) }],
    xaxis: { categories: @json($months->pluck('label')), labels:{style:{colors:'#6b7280'}} },
    yaxis: { labels:{style:{colors:'#6b7280'}, formatter: v => '₹'+v.toLocaleString()} },
    colors: ['#6c5ce7'],
    stroke: { curve: 'smooth', width: 3 },
    dataLabels: { enabled: false },
    grid: { borderColor: '#eef0f5', strokeDashArray: 4 },
    fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.02, colorStops:[{offset:0,color:'#6c5ce7',opacity:.4},{offset:100,color:'#ff6b9d',opacity:.02}]} }
}).render();
</script>
@endpush
