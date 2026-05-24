<?php

namespace App\Http\Controllers\Saas\Auth;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Public self-signup for new business tenants.
 * Creates a Tenant + first Client User in one shot and auto-logs in.
 */
class RegisterController extends Controller
{
    public function showRegister()
    {
        $plans = Plan::where('is_active', true)->orderBy('price')->get();
        return view('saas.auth.register', compact('plans'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'nullable|string|max:20',
            'password'      => 'required|min:6|confirmed',
            'plan_id'       => 'nullable|exists:plans,id',
        ]);

        DB::transaction(function () use ($data, &$user) {
            $plan = $data['plan_id'] ? Plan::find($data['plan_id']) : Plan::where('slug', 'starter')->first();

            $slug = Str::slug($data['business_name']);
            $baseSlug = $slug;
            $i = 1;
            while (Tenant::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $i++;
            }

            $tenant = Tenant::create([
                'business_name' => $data['business_name'],
                'slug'          => $slug,
                'phone'         => $data['phone'] ?? null,
                'email'         => $data['email'],
                'whatsapp'      => $data['phone'] ?? null,
                'theme'         => 'boutique',
                'plan_id'       => $plan?->id,
                'status'        => 'inactive',
                'expiry_date'   => null,
            ]);

            $user = User::create([
                'name'      => $data['name'],
                'full_name' => $data['name'],
                'email'     => $data['email'],
                'phone'     => $data['phone'] ?? null,
                'password'  => Hash::make($data['password']),
                'role'      => 'client',
                'tenant_id' => $tenant->id,
                'status'    => '1',
                'user_type' => 'customer',
            ]);
        });

        Auth::login($user);
        return redirect()
            ->route('client.subscription.index')
            ->with('success', 'Account created! Pay with UPI to activate your dashboard and website.');
    }
}
