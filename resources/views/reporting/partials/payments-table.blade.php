@php
    $rows = $rows ?? collect();
    $canManageRefunds = (bool) ($canManageRefunds ?? false);
    $membershipsByReg = $membershipsByReg ?? [];
    $defaultRefundAmount = (float) ($defaultRefundAmount ?? 3160);
    $colspan = $canManageRefunds ? 13 : 12;
@endphp

<div class="bns-reporting-scroll-hint d-none d-lg-block">
    <i class="bi bi-arrows-expand me-1"></i> Scroll horizontally to see all payment details.
</div>

<div class="bns-reporting-table-wrap">
    <table class="table bns-reporting-table bns-reporting-payment-table mb-0 align-middle">
        <thead>
            <tr>
                <th>Paid On</th>
                <th>Participant</th>
                <th>Program</th>
                <th>Amount</th>
                <th>Payment Mode</th>
                <th>Registration No.</th>
                <th>Merchant Txn No.</th>
                <th>Gateway Txn ID</th>
                <th>Payment ID</th>
                <th>Response</th>
                <th>Related Details</th>
                <th>Receipt</th>
                @if($canManageRefunds)
                    <th>Action</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $payment)
                <tr>
                    <td class="text-muted small text-nowrap">
                        {{ $payment->paid_at?->format('d M Y') ?: '—' }}<br>
                        <span class="opacity-75">{{ $payment->paid_at?->format('h:i A') ?: '—' }} IST</span>
                    </td>
                    <td style="min-width:220px">
                        <div class="fw-bold">{{ $payment->customer_name ?: '—' }}</div>
                        <div class="small">{{ $payment->customer_mobile ?: '—' }}</div>
                        <div class="small text-muted">{{ $payment->customer_email ?: '—' }}</div>
                    </td>
                    <td style="min-width:170px">
                        <span class="bns-reporting-payment-program">{{ $payment->display_program }}</span>
                        <div class="small text-muted mt-1">{{ $payment->form_type ?: '—' }}</div>
                    </td>
                    <td class="text-nowrap">
                        <strong class="bns-reporting-payment-amount">₹{{ number_format((float) $payment->amount, 2) }}</strong>
                        <div><span class="badge rounded-pill text-bg-success">Paid</span></div>
                    </td>
                    <td class="small text-nowrap">
                        {{ $payment->payment_mode ?: '—' }}
                        @if($payment->payment_sub_inst_type)
                            <div class="text-muted">{{ $payment->payment_sub_inst_type }}</div>
                        @endif
                    </td>
                    <td><span class="bns-reporting-reg">{{ $payment->registration_number ?: '—' }}</span></td>
                    <td class="small text-nowrap">{{ $payment->merchant_txn_no ?: '—' }}</td>
                    <td class="small text-nowrap">{{ $payment->txn_id ?: '—' }}</td>
                    <td class="small text-nowrap">{{ $payment->payment_id ?: '—' }}</td>
                    <td style="min-width:180px">
                        <div class="small"><strong>Code:</strong> {{ $payment->response_code ?: '—' }}</div>
                        <div class="small text-muted">{{ Str::limit($payment->response_description ?: 'Payment successful', 70) }}</div>
                    </td>
                    <td style="min-width:180px">
                        <div class="small"><strong>Location:</strong> {{ $payment->display_location ?: '—' }}</div>
                        <div class="small"><strong>Business:</strong> {{ $payment->display_business ?: '—' }}</div>
                    </td>
                    <td>
                        <a
                            href="{{ route('payment.receipt', $payment->merchant_txn_no) }}"
                            class="btn btn-sm bns-reporting-btn-view text-nowrap"
                            target="_blank"
                            rel="noopener"
                        >
                            <i class="bi bi-receipt"></i> Receipt
                        </a>
                    </td>
                    @if($canManageRefunds)
                        @php
                            $modalId = 'bnsPayRefundModal-'.$payment->id;
                            $membership = $membershipsByReg[trim((string) $payment->registration_number)] ?? null;
                        @endphp
                        <td class="text-nowrap">
                            @if($payment->isRefunded())
                                <div class="text-success small mb-1">
                                    Refunded ₹{{ number_format((float) ($payment->refund_amount ?? 0), 2) }}
                                </div>
                                <form method="POST" action="{{ route('reporting.payments.refund-status', $payment) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-repeat"></i> Check Refund Status
                                    </button>
                                </form>
                            @elseif($payment->refund_status === 'failed')
                                <div class="text-danger small mb-1">
                                    Refund failed: {{ $payment->refund_response_description ?: 'Unknown error' }}
                                </div>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-warning mb-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#{{ $modalId }}"
                                >
                                    <i class="bi bi-cash-coin"></i> Retry Refund
                                </button>
                                @if($payment->refund_merchant_txn_no)
                                    <form method="POST" action="{{ route('reporting.payments.refund-status', $payment) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-arrow-repeat"></i> Check Refund Status
                                        </button>
                                    </form>
                                @endif
                            @elseif($payment->canOfferRefund())
                                <button
                                    type="button"
                                    class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#{{ $modalId }}"
                                >
                                    <i class="bi bi-cash-coin"></i> Refund
                                </button>
                            @endif
                        </td>
                    @endif
                </tr>
                @if($canManageRefunds && $payment->canOfferRefund())
                    @include('reporting.partials.refund-modal', [
                        'upload' => $membership,
                        'payment' => $payment,
                        'modalId' => $modalId,
                        'otpUrl' => route('reporting.payments.refund-otp', $payment),
                        'formAction' => route('reporting.payments.refund', $payment),
                        'defaultRefundAmount' => min($defaultRefundAmount, (float) $payment->amount),
                    ])
                @endif
            @empty
                <tr>
                    <td colspan="{{ $colspan }}" class="bns-reporting-empty">
                        <i class="bi bi-credit-card-2-front"></i>
                        No successful payments found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bns-reporting-mobile-cards">
    @forelse($rows as $payment)
        <article class="bns-reporting-mobile-card bns-reporting-payment-mobile-card">
            <div class="bns-reporting-mobile-card__head">
                <div>
                    <div class="bns-reporting-mobile-card__name">{{ $payment->customer_name ?: '—' }}</div>
                    <div class="bns-reporting-mobile-card__meta">
                        {{ $payment->paid_at?->format('d M Y, h:i A') ?: '—' }} IST
                    </div>
                </div>
                <strong class="bns-reporting-payment-amount">₹{{ number_format((float) $payment->amount, 2) }}</strong>
            </div>
            <div class="bns-reporting-mobile-card__meta">
                <div><strong>Status:</strong> <span class="badge rounded-pill text-bg-success">Paid</span></div>
                <div><strong>Program:</strong> {{ $payment->display_program }}</div>
                <div><strong>Registration No.:</strong> {{ $payment->registration_number ?: '—' }}</div>
                <div><strong>Mobile:</strong> {{ $payment->customer_mobile ?: '—' }}</div>
                <div><strong>Email:</strong> {{ $payment->customer_email ?: '—' }}</div>
                <div><strong>Mode:</strong> {{ $payment->payment_mode ?: '—' }}</div>
                <div><strong>Merchant Txn:</strong> {{ $payment->merchant_txn_no ?: '—' }}</div>
                <div><strong>Gateway Txn:</strong> {{ $payment->txn_id ?: '—' }}</div>
                <div><strong>Payment ID:</strong> {{ $payment->payment_id ?: '—' }}</div>
                <div><strong>Response:</strong> {{ $payment->response_code ?: '—' }} — {{ $payment->response_description ?: 'Payment successful' }}</div>
                <div><strong>Location:</strong> {{ $payment->display_location ?: '—' }}</div>
                <div><strong>Business:</strong> {{ $payment->display_business ?: '—' }}</div>
            </div>
            <a
                href="{{ route('payment.receipt', $payment->merchant_txn_no) }}"
                class="btn btn-sm bns-reporting-btn-view mt-3"
                target="_blank"
                rel="noopener"
            >
                <i class="bi bi-receipt"></i> View Receipt
            </a>
            @if($canManageRefunds)
                @php
                    $modalId = 'bnsPayRefundModalMobile-'.$payment->id;
                    $membership = $membershipsByReg[trim((string) $payment->registration_number)] ?? null;
                @endphp
                <div class="mt-2">
                    @if($payment->isRefunded())
                        <div class="text-success small mb-1">
                            Refunded ₹{{ number_format((float) ($payment->refund_amount ?? 0), 2) }}
                        </div>
                        <form method="POST" action="{{ route('reporting.payments.refund-status', $payment) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-arrow-repeat"></i> Check Refund Status
                            </button>
                        </form>
                    @elseif($payment->canOfferRefund())
                        <button
                            type="button"
                            class="btn btn-sm btn-warning"
                            data-bs-toggle="modal"
                            data-bs-target="#{{ $modalId }}"
                        >
                            <i class="bi bi-cash-coin"></i> Refund
                        </button>
                    @endif
                </div>
                @if($payment->canOfferRefund())
                    @include('reporting.partials.refund-modal', [
                        'upload' => $membership,
                        'payment' => $payment,
                        'modalId' => $modalId,
                        'otpUrl' => route('reporting.payments.refund-otp', $payment),
                        'formAction' => route('reporting.payments.refund', $payment),
                        'defaultRefundAmount' => min($defaultRefundAmount, (float) $payment->amount),
                    ])
                @endif
            @endif
        </article>
    @empty
        <div class="bns-reporting-empty p-4">
            <i class="bi bi-credit-card-2-front"></i>
            No successful payments found.
        </div>
    @endforelse
</div>
