<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks dashboard management until the tenant has a verified UPI subscription
 * (or admin-granted verified payment) and an active, non-expired plan.
 */
class EnsureClientHasPaidSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenant = Auth::user()?->tenant;

        if (!$tenant) {
            abort(403, 'No tenant associated with this account.');
        }

        if ($tenant->canManageContent()) {
            return $next($request);
        }

        if (!$tenant->hasVerifiedPayment()) {
            return redirect()
                ->route('client.payment.required')
                ->with('warning', 'Please complete your subscription payment to unlock the dashboard.');
        }

        return redirect()->route('client.expired');
    }
}
