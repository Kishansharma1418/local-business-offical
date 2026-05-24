<section class="enquiry-bg">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <span class="sec-eyebrow" style="color:var(--brand);">Let's talk</span>
                <h2 class="sec-title text-white mt-2">Have a question?<br>We'd love to help.</h2>
                <p class="mt-3" style="color:#9ca3af;max-width:420px;">Reach out via WhatsApp, phone or the form — our team gets back within a few hours.</p>
                <div class="mt-4">
                    @if($tenant->phone)
                        <div class="contact-item">
                            <div class="contact-ico"><i class="fa fa-phone"></i></div>
                            <div><small style="color:#9ca3af;">Call us</small><div class="fw-semibold text-white">{{ $tenant->phone }}</div></div>
                        </div>
                    @endif
                    @if($tenant->whatsapp)
                        <div class="contact-item">
                            <div class="contact-ico" style="color:#25d366;"><i class="fab fa-whatsapp"></i></div>
                            <div><small style="color:#9ca3af;">WhatsApp</small><div class="fw-semibold text-white">{{ $tenant->whatsapp }}</div></div>
                        </div>
                    @endif
                    @if($tenant->address)
                        <div class="contact-item">
                            <div class="contact-ico"><i class="fa fa-location-dot"></i></div>
                            <div><small style="color:#9ca3af;">Visit us</small><div class="fw-semibold text-white">{{ $tenant->address }}, {{ $tenant->city }}</div></div>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-7">
                <div class="enquiry-card">
                    <h4 class="mb-1 fw-bold">Send an enquiry</h4>
                    <p class="text-muted small mb-4">We'll reply on WhatsApp within a few hours.</p>
                    <form method="POST" action="{{ route('tenant.enquiry', $tenant->slug) }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="small fw-semibold mb-1">Your name</label>
                                <input name="name" class="form-control" placeholder="e.g. Aarti Gupta" required>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-semibold mb-1">Phone</label>
                                <input name="phone" class="form-control" placeholder="+91 98765 43210" required>
                            </div>
                            <div class="col-12">
                                <label class="small fw-semibold mb-1">Email <span class="text-muted">(optional)</span></label>
                                <input name="email" type="email" class="form-control" placeholder="you@example.com">
                            </div>
                            <div class="col-12">
                                <label class="small fw-semibold mb-1">Your message</label>
                                <textarea name="message" rows="3" class="form-control" placeholder="Tell us what you're looking for…"></textarea>
                            </div>
                            <div class="col-12 mt-2">
                                <button class="btn btn-brand px-4"><i class="fa fa-paper-plane me-2"></i>Send enquiry</button>
                                <small class="text-muted ms-2">We respect your privacy.</small>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
