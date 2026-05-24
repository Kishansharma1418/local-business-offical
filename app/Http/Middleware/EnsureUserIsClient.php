<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsClient
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'client') {
            abort(403, 'Access denied. Client tenants only.');
        }

        $tenant = $user->tenant;

        if (!$tenant) {
            abort(403, 'No tenant associated with this account.');
        }

        view()->share('currentTenant', $tenant);
        view()->share('tenantCanManage', $tenant->canManageContent());

        return $next($request);
    }
}
