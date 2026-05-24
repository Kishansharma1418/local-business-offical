<?php

namespace App\Http\Controllers\Saas;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Tenant;

class LandingController extends Controller
{
    public function index()
    {
        $plans = Plan::where('is_active', true)->orderBy('price')->get();
        $featuredTenants = Tenant::where('status', 'active')->latest()->take(6)->get();
        return view('saas.landing', compact('plans', 'featuredTenants'));
    }
}
