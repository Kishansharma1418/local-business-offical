@extends('saas.layouts.client')
@section('title', 'Pay with UPI')

@section('content')
<style>
    .pay-wrap{max-width:980px;margin:0 auto;}
    .pay-hero{background:linear-gradient(135deg,#0b1020,#1f2937);color:#fff;border-radius:20px;padding:26px 30px;margin-bottom:20px;display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;}
    .pay-hero .amount{font-size:2.4rem;font-weight:800;}
    .pay-card{background:#fff;border-radius:16px;border:1px solid rgba(15,23,42,.08);box-shadow:0 1px 2px rgba(15,23,42,.04),0 8px 24px -10px rgba(15,23,42,.1);padding:28px;margin-bottom:16px;}
    .qr-box{display:inline-block;padding:14px;border-radius:18px;background:linear-gradient(135deg,#fafaff,#f1f5f9);border:1px solid #eef0f5;}
    .qr-box img{display:block;border-radius:12px;}
    .upi-id-box{background:#f8fafc;border:1px dashed #cbd5e1;border-radius:12px;padding:14px 18px;font-family:monospace;font-size:1.1rem;letter-spacing:.02em;display:flex;justify-content:space-between;align-items:center;}
    .step-num{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#6c5ce7,#a855f7);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;margin-right:10px;}
    .app-btn{display:inline-flex;align-items:center;gap:6px;padding:10px 16px;border:1px solid #e5e7eb;border-radius:12px;font-weight:600;color:#111;background:#fff;text-decoration:none;}
    .app-btn:hover{border-color:#6c5ce7;color:#6c5ce7;}
    .btn-upi{background:linear-gradient(135deg,#6366f1,#7c3aed);color:#fff;border:0;padding:15px 22px;border-radius:12px;font-weight:700;font-size:1.02rem;width:100%;box-shadow:0 1px 2px rgba(15,23,42,.06),0 6px 16px -6px rgba(99,102,241,.35);}
    .btn-upi:hover{color:#fff;opacity:.95;}
    .countdown{font-size:.8rem;color:#64748b;}
</style>

<div class="pay-wrap">
    {{-- Breadcrumb --}}
    <div class="mb-3"><a href="{{ route('client.subscription.index') }}" class="text-decoration-none small"><i class="ri-arrow-left-line"></i> Back to subscription</a></div>

    {{-- Hero --}}
    <div class="pay-hero">
        <div>
            <div style="opacity:.7;font-size:.8rem;text-transform:uppercase;letter-spacing:.1em;">Order reference</div>
            <div class="fw-bold">{{ $payment->reference }}</div>
            <div style="opacity:.75;" class="mt-1">{{ $payment->plan->name }} — {{ $payment->plan->duration_days ?? 30 }} days validity</div>
        </div>
        <div class="text-end">
            <div style="opacity:.7;font-size:.8rem;">Amount payable</div>
            <div class="amount">₹{{ number_format($payment->amount, 0) }}</div>
        </div>
    </div>

    <div class="row g-4">
        {{-- LEFT: device-aware payment options --}}
        <div class="col-lg-7">
            <div class="pay-card">
                {{-- Mobile: big "Pay now" button with UPI intent --}}
                <div class="d-block d-lg-none">
                    <h5 class="fw-bold mb-2">Pay now from this phone</h5>
                    <p class="text-muted small mb-3">Tap the button below and your UPI app (PhonePe, GPay, Paytm, BHIM…) will open automatically with the amount pre-filled.</p>
                    <a href="{{ $upiUrl }}" class="btn-upi text-center d-block" id="mobilePayBtn">
                        <i class="ri-smartphone-line me-1"></i> Pay ₹{{ number_format($payment->amount,0) }} via UPI app
                    </a>
                    <div class="text-center mt-3 countdown" id="mobileHint">
                        Didn't open? <a href="{{ $upiUrl }}">Tap here to retry</a>
                    </div>
                </div>

                {{-- Desktop / default: QR code --}}
                <div class="@if($isMobile) d-none d-lg-block @endif">
                    <h5 class="fw-bold mb-2">Scan &amp; pay with any UPI app</h5>
                    <p class="text-muted small mb-3">Open PhonePe, Google Pay, Paytm or any UPI app, tap <em>Scan</em> and point it at the QR code below. Pay exactly <strong>₹{{ number_format($payment->amount,0) }}</strong>.</p>
                    <div class="text-center">
                        <div class="qr-box">
                            <img src="{{ $qrUrl }}" alt="UPI QR Code" width="260" height="260">
                        </div>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <span class="app-btn"><i class="ri-smartphone-line"></i> PhonePe</span>
                            <span class="app-btn"><i class="ri-google-fill"></i> GPay</span>
                            <span class="app-btn"><i class="ri-paypal-line"></i> Paytm</span>
                            <span class="app-btn"><i class="ri-bank-line"></i> BHIM</span>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="small text-muted mb-2 fw-semibold">Or pay directly to this UPI ID:</div>
                <div class="upi-id-box">
                    <span id="upiId">{{ $upiId }}</span>
                    <button class="btn btn-sm btn-outline-primary" type="button" onclick="copyToClipboard('{{ $upiId }}')">
                        <i class="ri-file-copy-line"></i> Copy
                    </button>
                </div>
                <div class="small text-muted mt-2">Payee name: <strong>{{ $upiName }}</strong></div>
            </div>
        </div>

        {{-- RIGHT: confirm UTR form --}}
        <div class="col-lg-5">
            <div class="pay-card">
                <h5 class="fw-bold mb-2">After payment</h5>
                <p class="text-muted small mb-3">Once the amount is deducted, copy the <strong>UTR / transaction ID</strong> shown in your UPI app and paste it below. Our admin verifies every payment within 2-4 hours and activates your plan.</p>

                <div class="mb-3">
                    <div class="small fw-semibold mb-2"><span class="step-num">1</span> Scan / tap to pay</div>
                    <div class="small fw-semibold mb-2"><span class="step-num">2</span> Copy the UTR (12-digit reference)</div>
                    <div class="small fw-semibold"><span class="step-num">3</span> Submit it here to activate your plan</div>
                </div>

                <form method="POST" action="{{ route('client.subscription.confirm', $payment) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">UTR / Transaction ID *</label>
                        <input type="text" name="transaction_id" class="form-control" required minlength="6" maxlength="40" placeholder="e.g. 462531028473">
                        @error('transaction_id')<div class="text-danger small">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Which app did you use?</label>
                        <select name="upi_app" class="form-select">
                            <option value="">Select (optional)</option>
                            <option>PhonePe</option>
                            <option>Google Pay</option>
                            <option>Paytm</option>
                            <option>BHIM</option>
                            <option>Amazon Pay</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Payment screenshot (optional)</label>
                        <input type="file" name="screenshot" class="form-control" accept="image/*">
                        <div class="small text-muted mt-1">Helps admin verify faster. Max 4 MB.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Note (optional)</label>
                        <textarea name="client_note" class="form-control" rows="2" maxlength="500" placeholder="Anything we should know?"></textarea>
                    </div>
                    <button class="btn btn-success w-100 fw-semibold py-2">
                        <i class="ri-checkbox-circle-line me-1"></i> Submit for verification
                    </button>
                </form>

                <div class="alert alert-warning small mt-3 mb-0">
                    <i class="ri-shield-check-line"></i> Please do not refresh this page until payment is complete. Your plan will activate automatically once admin verifies the UTR.
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text){
    navigator.clipboard.writeText(text);
    alert('UPI ID copied: ' + text);
}

// On mobile, auto-trigger the UPI intent after a short delay so the
// chosen UPI app opens without the user having to tap again.
@if($isMobile)
    setTimeout(function(){
        window.location.href = "{{ $upiUrl }}";
    }, 900);
@endif
</script>
@endsection
