<?php

namespace App\Http\Controllers\Saas\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Tenant;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalTenants   = Tenant::count();
        $activeTenants  = Tenant::where('status', 'active')->where(function ($q) {
            $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', now()->toDateString());
        })->count();
        $expiredTenants = Tenant::whereDate('expiry_date', '<', now())->count();
        $totalPlans     = Plan::count();
        $totalOrders    = Order::withoutGlobalScopes()->count();
        $totalEnquiries = Enquiry::withoutGlobalScopes()->count();
        $revenue        = Order::withoutGlobalScopes()->where('payment_status', 'paid')->sum('total_amount');
        $subscriptionRevenue = Tenant::with('plan')->get()->sum(fn ($t) => (float) optional($t->plan)->price);

        // Monthly revenue (last 6 months)
        $months = collect(range(5, 0))->map(function ($i) {
            $date = Carbon::now()->subMonths($i);
            $revenue = Order::withoutGlobalScopes()
                ->where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('total_amount');
            return [
                'label' => $date->format('M Y'),
                'revenue' => (float) $revenue,
            ];
        });

        $recentTenants = Tenant::with('plan')->latest()->take(5)->get();
        $recentOrders  = Order::withoutGlobalScopes()->with('tenant')->latest()->take(5)->get();

        return view('saas.admin.dashboard', compact(
            'totalTenants', 'activeTenants', 'expiredTenants', 'totalPlans',
            'totalOrders', 'totalEnquiries', 'revenue', 'subscriptionRevenue',
            'months', 'recentTenants', 'recentOrders'
        ));
    }
}
