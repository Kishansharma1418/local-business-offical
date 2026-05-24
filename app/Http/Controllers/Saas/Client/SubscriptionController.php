<?php

namespace App\Http\Controllers\Saas\Client;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\SubscriptionPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index()
    {
        $tenant = Auth::user()->tenant;
        $plans = Plan::where('is_active', true)->orderBy('price')->get();
        $payments = $tenant ? $tenant->subscriptionPayments()->with('plan')->take(10)->get() : collect();

        return view('saas.client.subscription.index', compact('tenant', 'plans', 'payments'));
    }

    /**
     * Begin paying for a plan.  Creates a SubscriptionPayment row (status=initiated)
     * and shows the UPI intent / QR code page.
     */
    public function pay(Request $request, Plan $plan)
    {
        $tenant = Auth::user()->tenant;
        abort_unless($tenant, 403);

        // re-use most recent 'initiated' record for this plan if created < 30 min ago,
        // otherwise spin up a fresh one
        $payment = $tenant->subscriptionPayments()
            ->where('plan_id', $plan->id)
            ->where('status', 'initiated')
            ->where('created_at', '>', now()->subMinutes(30))
            ->latest()
            ->first();

        if (!$payment) {
            $payment = SubscriptionPayment::create([
                'tenant_id' => $tenant->id,
                'plan_id'   => $plan->id,
                'amount'    => $plan->price,
                'upi_id'    => config('saas.upi.id'),
                'status'    => 'initiated',
            ]);
        }

        return redirect()->route('client.subscription.show', $payment);
    }

    public function show(SubscriptionPayment $payment)
    {
        $this->authorizePayment($payment);

        $upiId   = config('saas.upi.id');
        $upiName = config('saas.upi.name');
        $amount  = number_format((float) $payment->amount, 2, '.', '');
        $note    = 'Sub ' . $payment->reference;

        // UPI deeplink – works in any Indian UPI app (GPay, PhonePe, Paytm, BHIM, …)
        $upiUrl = 'upi://pay?' . http_build_query([
            'pa' => $upiId,
            'pn' => $upiName,
            'am' => $amount,
            'cu' => 'INR',
            'tn' => $note,
            'tr' => $payment->reference,
        ]);

        // use a public QR image service (no composer package needed)
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=280x280&margin=10&data=' . urlencode($upiUrl);

        return view('saas.client.subscription.pay', [
            'payment' => $payment->load('plan'),
            'tenant'  => $payment->tenant,
            'upiId'   => $upiId,
            'upiName' => $upiName,
            'upiUrl'  => $upiUrl,
            'qrUrl'   => $qrUrl,
            'isMobile'=> $this->isMobile(request()),
        ]);
    }

    /**
     * Client has finished the UPI payment and is submitting the transaction id
     * for admin verification.
     */
    public function confirm(Request $request, SubscriptionPayment $payment)
    {
        $this->authorizePayment($payment);

        $data = $request->validate([
            'transaction_id' => 'required|string|min:6|max:40',
            'upi_app'        => 'nullable|string|max:30',
            'client_note'    => 'nullable|string|max:500',
            'screenshot'     => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('screenshot')) {
            $data['screenshot'] = $request->file('screenshot')->store('subscription_proofs', 'public');
        }

        $payment->update(array_merge($data, [
            'status' => 'pending_verification',
        ]));

        return redirect()
            ->route('client.subscription.index')
            ->with('success', 'Payment details submitted. Admin will verify within 2-4 hours and activate your plan.');
    }

    protected function authorizePayment(SubscriptionPayment $payment): void
    {
        abort_unless($payment->tenant_id === Auth::user()->tenant_id, 403);
    }

    protected function isMobile(Request $r): bool
    {
        $ua = (string) $r->header('User-Agent');
        return (bool) preg_match('/(android|iphone|ipad|ipod|mobile|opera mini)/i', $ua);
    }
}
