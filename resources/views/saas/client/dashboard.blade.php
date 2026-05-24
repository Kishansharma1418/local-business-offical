@extends('saas.layouts.client')
@section('title', 'Dashboard')

@section('content')
<!-- Welcome banner -->
<div class="card mb-4" style="background:linear-gradient(135deg,#0b1020 0%,#1e3a8a 70%,#42a5f5 130%);color:#fff;border:0!important;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-100px;right:-100px;width:400px;height:400px;background:radial-gradient(circle,rgba(255,107,157,.25),transparent 60%);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-100px;left:-100px;width:300px;height:300px;background:radial-gradient(circle,rgba(0,212,255,.2),transparent 60%);border-radius:50%;"></div>
    <div class="card-body position-relative" style="padding:32px!important;">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge" style="background:rgba(255,255,255,.18);color:#fff;padding:6px 12px;border-radius:20px;font-weight:600;letter-spacing:.05em;">{{ strtoupper($tenant->plan->name ?? 'TRIAL') }} PLAN</span>
                <h3 class="mt-3 mb-1" style="font-weight:800;letter-spacing:-.02em;">Welcome back, {{ auth()->user()->name ?: 'there' }} 👋</h3>
                <p class="mb-0" style="color:rgba(255,255,255,.8);">Your website is live at <a href="{{ route('tenant.home', $tenant->slug) }}" target="_blank" style="color:#fff;text-decoration:underline;font-weight:600;">/{{ $tenant->slug }}</a> — share it everywhere.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('tenant.home', $tenant->slug) }}" target="_blank" class="btn" style="background:#fff;color:#1e3a8a;font-weight:700;"><i class="fa fa-external-link-alt me-1"></i> View Site</a>
                <a href="{{ route('client.products.create') }}" class="btn btn-outline-light"><i class="fa fa-plus me-1"></i> Add Product</a>
            </div>
        </div>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    @php
        $cards = [
            ['label'=>'Products','value'=>$productCount,'icon'=>'fa-box-open','class'=>'bg-grad-blue','hint'=>'In catalog'],
            ['label'=>'Total Orders','value'=>$orderCount,'icon'=>'fa-bag-shopping','class'=>'bg-grad-green','hint'=>'All time'],
            ['label'=>'New','value'=>$pendingOrders,'icon'=>'fa-bell','class'=>'bg-grad-orange','hint'=>'Need action'],
            ['label'=>'Enquiries','value'=>$enquiryCount,'icon'=>'fa-comments','class'=>'bg-grad-purple','hint'=>'Leads'],
            ['label'=>'Revenue','value'=>'₹'.number_format($revenue,0),'icon'=>'fa-indian-rupee-sign','class'=>'bg-grad-pink','hint'=>'Paid orders'],
        ];
    @endphp
    @foreach($cards as $c)
        <div class="col-xl col-md-6">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="stat-icon {{ $c['class'] }}"><i class="fa {{ $c['icon'] }}"></i></div>
                    <div>
                        <small class="text-muted text-uppercase" style="letter-spacing:.05em;font-size:.72rem;font-weight:700;">{{ $c['label'] }}</small>
                        <h4 class="mb-0 fw-bold">{{ $c['value'] }}</h4>
                        <small class="text-muted" style="font-size:.78rem;">{{ $c['hint'] }}</small>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Charts -->
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center"><h5 class="mb-0">Orders — Last 6 Months</h5><span class="badge badge-soft-info">Monthly view</span></div>
            <div class="card-body"><div id="ordersChart"></div></div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Latest Orders</h5><a href="{{ route('client.orders.index') }}" class="btn btn-sm btn-light">All</a>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <tbody>
                        @forelse($recentOrders as $o)
                            <tr>
                                <td>
                                    <div class="fw-bold" style="color:#1e3a8a;">{{ $o->order_number }}</div>
                                    <small class="text-muted">{{ $o->customer_name }} · {{ $o->created_at->diffForHumans() }}</small>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold">₹{{ number_format($o->total_amount,0) }}</div>
                                    <span class="badge badge-soft-info">{{ ucfirst($o->order_status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="text-center text-muted py-4">No orders yet — share your website on WhatsApp!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Quick actions -->
<div class="row g-3 mb-4">
    @php
        $quick = [
            ['t'=>'Add product','i'=>'fa-box-open','u'=>route('client.products.create'),'d'=>'Upload new items'],
            ['t'=>'Edit pages','i'=>'fa-file-pen','u'=>route('client.pages.index'),'d'=>'Update website content'],
            ['t'=>'Change theme','i'=>'fa-palette','u'=>route('client.settings.edit'),'d'=>'Switch look & feel'],
            ['t'=>'View enquiries','i'=>'fa-comments','u'=>route('client.enquiries.index'),'d'=>'Reply to leads'],
        ];
    @endphp
    @foreach($quick as $q)
        <div class="col-md-3 col-6">
            <a href="{{ $q['u'] }}" class="card text-decoration-none text-dark" style="transition:all .22s;">
                <div class="card-body text-center">
                    <div style="width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#42a5f5,#1976d2);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:1.2rem;margin-bottom:10px;"><i class="fa {{ $q['i'] }}"></i></div>
                    <div class="fw-bold">{{ $q['t'] }}</div>
                    <small class="text-muted">{{ $q['d'] }}</small>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Enquiries</h5><a href="{{ route('client.enquiries.index') }}" class="btn btn-sm btn-light">All</a>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Name</th><th>Phone</th><th>Message</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($recentEnquiries as $e)
                    <tr>
                        <td><b>{{ $e->name }}</b></td>
                        <td>{{ $e->phone }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($e->message, 80) }}</td>
                        <td><span class="badge badge-soft-{{ $e->status==='new'?'info':'success' }}">{{ ucfirst($e->status) }}</span></td>
                        <td class="text-muted small">{{ $e->created_at->diffForHumans() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No enquiries yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
new ApexCharts(document.querySelector("#ordersChart"), {
    chart: { type: 'bar', height: 300, toolbar:{show:false}, fontFamily: 'Plus Jakarta Sans, sans-serif' },
    series: [{ name: 'Orders', data: @json($months->pluck('orders')) }],
    xaxis: { categories: @json($months->pluck('label')), labels:{style:{colors:'#6b7280'}} },
    yaxis: { labels:{style:{colors:'#6b7280'}} },
    colors: ['#42a5f5'],
    dataLabels: { enabled: false },
    grid: { borderColor: '#eef0f5', strokeDashArray: 4 },
    plotOptions: { bar: { borderRadius: 8, columnWidth: '45%', distributed:false } },
    fill:{ type:'gradient', gradient:{ shade:'light', gradientToColors:['#1976d2'], shadeIntensity:1, type:'vertical', opacityFrom:.9, opacityTo:.65 } }
}).render();
</script>
@endpush
