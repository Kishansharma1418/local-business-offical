<?php

namespace App\Http\Controllers\Saas\Client;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;

        $productCount  = Product::count();
        $orderCount    = Order::count();
        $pendingOrders = Order::where('order_status', 'new')->count();
        $enquiryCount  = Enquiry::count();
        $revenue       = Order::where('payment_status', 'paid')->sum('total_amount');

        $months = collect(range(5, 0))->map(function ($i) {
            $date = Carbon::now()->subMonths($i);
            return [
                'label' => $date->format('M Y'),
                'orders' => Order::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
            ];
        });

        $recentOrders    = Order::with('items')->latest()->take(5)->get();
        $recentEnquiries = Enquiry::latest()->take(5)->get();

        return view('saas.client.dashboard', compact(
            'tenant', 'productCount', 'orderCount', 'pendingOrders',
            'enquiryCount', 'revenue', 'months', 'recentOrders', 'recentEnquiries'
        ));
    }

    public function expired()
    {
        $tenant = Auth::user()->tenant;
        return view('saas.client.expired', compact('tenant'));
    }

    public function paymentRequired()
    {
        $tenant = Auth::user()->tenant;
        $pending = $tenant?->subscriptionPayments()
            ->whereIn('status', ['initiated', 'pending_verification'])
            ->latest()
            ->first();

        return view('saas.client.payment-required', compact('tenant', 'pending'));
    }
}
