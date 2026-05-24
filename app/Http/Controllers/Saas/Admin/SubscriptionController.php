<?php

namespace App\Http\Controllers\Saas\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPayment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending_verification');
        $q = SubscriptionPayment::with(['tenant', 'plan', 'verifier'])->latest();

        if ($status && $status !== 'all') {
            $q->where('status', $status);
        }

        $payments = $q->paginate(20)->withQueryString();

        $counts = [
            'pending_verification' => SubscriptionPayment::where('status', 'pending_verification')->count(),
            'verified'             => SubscriptionPayment::where('status', 'verified')->count(),
            'rejected'             => SubscriptionPayment::where('status', 'rejected')->count(),
            'initiated'            => SubscriptionPayment::where('status', 'initiated')->count(),
        ];

        return view('saas.admin.subscriptions.index', compact('payments', 'status', 'counts'));
    }

    public function show(SubscriptionPayment $payment)
    {
        $payment->load(['tenant.plan', 'plan', 'verifier']);
        return view('saas.admin.subscriptions.show', compact('payment'));
    }

    public function verify(Request $request, SubscriptionPayment $payment)
    {
        abort_if(in_array($payment->status, ['verified', 'rejected']), 422, 'Already processed');

        $request->validate(['admin_note' => 'nullable|string|max:500']);

        $tenant = $payment->tenant;
        $plan   = $payment->plan;

        // extend from today OR from existing expiry (whichever is later)
        $base = ($tenant->expiry_date && $tenant->expiry_date->isFuture())
            ? Carbon::parse($tenant->expiry_date)
            : now();
        $newExpiry = $base->copy()->addDays((int) ($plan->duration_days ?? 30));

        $tenant->update([
            'plan_id'     => $plan->id,
            'status'      => 'active',
            'expiry_date' => $newExpiry->toDateString(),
        ]);

        $payment->update([
            'status'          => 'verified',
            'new_expiry_date' => $newExpiry->toDateString(),
            'verified_by'     => Auth::id(),
            'verified_at'     => now(),
            'admin_note'      => $request->input('admin_note'),
        ]);

        return redirect()
            ->route('admin.subscriptions.show', $payment)
            ->with('success', 'Payment verified. Tenant plan extended till ' . $newExpiry->format('d M Y') . '.');
    }

    public function reject(Request $request, SubscriptionPayment $payment)
    {
        abort_if(in_array($payment->status, ['verified', 'rejected']), 422, 'Already processed');

        $request->validate(['admin_note' => 'required|string|min:3|max:500']);

        $payment->update([
            'status'      => 'rejected',
            'admin_note'  => $request->input('admin_note'),
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.subscriptions.show', $payment)
            ->with('success', 'Payment marked as rejected. Client will see the reason on their dashboard.');
    }
}
