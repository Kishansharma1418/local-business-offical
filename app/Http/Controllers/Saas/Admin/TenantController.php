<?php

namespace App\Http\Controllers\Saas\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\SubscriptionPayment;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    public function index(Request $request)
    {
        $q = Tenant::with('plan')->latest();

        if ($search = $request->get('search')) {
            $q->where(function ($qq) use ($search) {
                $qq->where('business_name', 'like', "%$search%")
                   ->orWhere('slug', 'like', "%$search%")
                   ->orWhere('phone', 'like', "%$search%")
                   ->orWhere('email', 'like', "%$search%");
            });
        }
        if ($status = $request->get('status')) {
            $q->where('status', $status);
        }

        $tenants = $q->paginate(15)->withQueryString();
        return view('saas.admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        $plans  = Plan::where('is_active', true)->orderBy('price')->get();
        $themes = array_keys(config('saas.themes', []));
        return view('saas.admin.tenants.create', compact('plans', 'themes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'slug'          => 'nullable|string|max:255|unique:tenants,slug|alpha_dash',
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email',
            'whatsapp'      => 'nullable|string|max:20',
            'city'          => 'nullable|string|max:255',
            'theme'         => 'required|in:' . implode(',', array_keys(config('saas.themes', []))),
            'primary_color' => 'nullable|string|max:20',
            'plan_id'       => 'required|exists:plans,id',
            'status'        => 'required|in:active,inactive,suspended',
            'expiry_date'   => 'nullable|date',
            'owner_name'    => 'required|string|max:255',
            'owner_email'   => 'required|email|unique:users,email',
            'owner_password'=> 'required|min:6',
        ]);

        DB::transaction(function () use ($data) {
            $plan = Plan::find($data['plan_id']);
            $expiry = $data['expiry_date'] ?? now()->addDays($plan->duration_days)->toDateString();

            $tenant = Tenant::create([
                'business_name' => $data['business_name'],
                'slug'          => $data['slug'] ?: Str::slug($data['business_name']),
                'phone'         => $data['phone']  ?? null,
                'email'         => $data['email']  ?? null,
                'whatsapp'      => $data['whatsapp'] ?? null,
                'city'          => $data['city']   ?? 'Jaipur',
                'theme'         => $data['theme'],
                'primary_color' => $data['primary_color'] ?? '#e91e63',
                'plan_id'       => $plan->id,
                'status'        => $data['status'],
                'expiry_date'   => $expiry,
            ]);

            if ($data['status'] === 'active') {
                SubscriptionPayment::create([
                    'tenant_id'       => $tenant->id,
                    'plan_id'         => $plan->id,
                    'amount'          => 0,
                    'upi_id'          => config('saas.upi.id'),
                    'status'          => 'verified',
                    'new_expiry_date' => $expiry,
                    'verified_by'     => Auth::id(),
                    'verified_at'     => now(),
                    'admin_note'      => 'Activated by admin (manual tenant setup)',
                ]);
            }

            User::create([
                'name'      => $data['owner_name'],
                'full_name' => $data['owner_name'],
                'email'     => $data['owner_email'],
                'phone'     => $data['phone'] ?? null,
                'password'  => Hash::make($data['owner_password']),
                'role'      => 'client',
                'tenant_id' => $tenant->id,
                'status'    => '1',
                'user_type' => 'customer',
            ]);
        });

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant created successfully.');
    }

    public function edit(Tenant $tenant)
    {
        $plans  = Plan::where('is_active', true)->orderBy('price')->get();
        $themes = array_keys(config('saas.themes', []));
        return view('saas.admin.tenants.edit', compact('tenant', 'plans', 'themes'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'slug'          => 'required|string|max:255|alpha_dash|unique:tenants,slug,' . $tenant->id,
            'phone'         => 'nullable|string|max:20',
            'email'         => 'nullable|email',
            'whatsapp'      => 'nullable|string|max:20',
            'city'          => 'nullable|string|max:255',
            'theme'         => 'required|in:' . implode(',', array_keys(config('saas.themes', []))),
            'primary_color' => 'nullable|string|max:20',
            'plan_id'       => 'required|exists:plans,id',
            'status'        => 'required|in:active,inactive,suspended',
            'expiry_date'   => 'nullable|date',
        ]);

        $tenant->update($data);
        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return back()->with('success', 'Tenant deleted.');
    }

    public function toggleStatus(Tenant $tenant)
    {
        $tenant->status = $tenant->status === 'active' ? 'inactive' : 'active';
        $tenant->save();
        return back()->with('success', 'Tenant status updated.');
    }

    public function extend(Request $request, Tenant $tenant)
    {
        $days = (int) $request->input('days', 30);
        $base = $tenant->expiry_date && $tenant->expiry_date->isFuture() ? $tenant->expiry_date : now();
        $tenant->expiry_date = $base->copy()->addDays($days);
        $tenant->save();
        return back()->with('success', "Expiry extended by $days days.");
    }
}
