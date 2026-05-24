<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Loads the tenant from the {slug} route parameter for public-facing
 * dynamic tenant websites (e.g. /{slug}, /{slug}/products).
 * Aborts with 404 if slug is unknown, 503 if expired/inactive.
 */
class ResolveTenantBySlug
{
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');

        if (!$slug) {
            abort(404);
        }

        $tenant = Tenant::where('slug', $slug)->with('plan')->first();

        if (!$tenant) {
            abort(404, 'Business not found');
        }

        if (!$tenant->canManageContent()) {
            return response()->view('saas.frontend.expired', ['tenant' => $tenant], 503);
        }

        // Share with request + views
        $request->attributes->set('tenant', $tenant);
        view()->share('tenant', $tenant);

        return $next($request);
    }
}
