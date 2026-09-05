@php
    $upload = $upload ?? null;
    $payment = $payment ?? null;
    $modalId = $modalId ?? 'bnsRefundModal';
    $defaultRefundAmount = number_format((float) ($defaultRefundAmount ?? 3160), 2, '.', '');
    $otpEmail = (string) config('reporting.refund_otp.email', 'mrupani2005@gmail.com');
@endphp

@if($upload && $payment)
<div
    class="modal fade"
    id="{{ $modalId }}"
    tabindex="-1"
    aria-labelledby="{{ $modalId }}Label"
    aria-hidden="true"
    data-refund-otp-url="{{ route('reporting.membership.refund-otp', $upload) }}"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">
                    <i class="bi bi-cash-coin text-warning me-1"></i> Process Refund
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('reporting.membership.refund', $upload) }}" class="bns-refund-form">
                @csrf
                <div class="modal-body">
                    <div class="mb-3 small">
                        <div class="fw-semibold mb-2">Transaction Details</div>
                        <div class="row g-2">
                            <div class="col-6"><span class="text-muted">Member</span><div class="fw-semibold">{{ $upload->membership_name }}</div></div>
                            <div class="col-6"><span class="text-muted">Membership No</span><div class="fw-semibold">{{ $upload->membership_no ?: '—' }}</div></div>
                            <div class="col-6"><span class="text-muted">Reg. No.</span><div class="fw-semibold">{{ $upload->registration_number ?: '—' }}</div></div>
                            <div class="col-6"><span class="text-muted">Mobile</span><div class="fw-semibold">{{ $upload->mobile ?: '—' }}</div></div>
                            <div class="col-6"><span class="text-muted">Merchant Txn</span><div class="fw-semibold text-break">{{ $payment->merchant_txn_no }}</div></div>
                            <div class="col-6"><span class="text-muted">Gateway Txn</span><div class="fw-semibold text-break">{{ $payment->txn_id ?: '—' }}</div></div>
                            <div class="col-6"><span class="text-muted">Payment ID</span><div class="fw-semibold text-break">{{ $payment->payment_id ?: '—' }}</div></div>
                            <div class="col-6"><span class="text-muted">Paid Amount</span><div class="fw-semibold">₹{{ number_format((float) $payment->amount, 2) }}</div></div>
                            <div class="col-6"><span class="text-muted">Paid At</span><div class="fw-semibold">{{ $payment->paid_at?->format('d M Y, h:i A') ?: '—' }}</div></div>
                            <div class="col-6"><span class="text-muted">Mode</span><div class="fw-semibold">{{ $payment->payment_mode ?: '—' }}</div></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="{{ $modalId }}-amount">Refund Amount (₹)</label>
                        <input
                            type="number"
                            step="0.01"
                            min="1"
                            max="{{ number_format((float) $payment->amount, 2, '.', '') }}"
                            name="refund_amount"
                            id="{{ $modalId }}-amount"
                            class="form-control bns-refund-amount"
                            value="{{ $defaultRefundAmount }}"
                            required
                        >
                        <div class="form-text">Default scholarship refund is ₹{{ number_format((float) $defaultRefundAmount, 2) }}. Max ₹{{ number_format((float) $payment->amount, 2) }}.</div>
                    </div>

                    <div class="alert alert-light border small mb-3">
                        OTP will be sent to <strong>{{ $otpEmail }}</strong>. Enter the OTP to complete the refund.
                    </div>

                    <div class="bns-refund-otp-feedback alert d-none mb-3" role="alert"></div>

                    <div class="bns-refund-otp-step d-none">
                        <label class="form-label" for="{{ $modalId }}-otp">Enter OTP</label>
                        <input
                            type="text"
                            name="otp"
                            id="{{ $modalId }}-otp"
                            class="form-control bns-refund-otp"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            maxlength="6"
                            pattern="[0-9]{6}"
                            placeholder="6-digit OTP"
                        >
                        <div class="form-text">Check the inbox for {{ $otpEmail }}.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-outline-primary bns-refund-send-otp">
                        <i class="bi bi-envelope me-1"></i> Send OTP
                    </button>
                    <button type="submit" class="btn btn-warning bns-refund-submit d-none" disabled>
                        <i class="bi bi-shield-check me-1"></i> Verify OTP &amp; Refund
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
