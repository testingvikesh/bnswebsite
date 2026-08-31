@php
    $rows = $rows ?? collect();
    $canManageBnsVerify = (bool) ($canManageBnsVerify ?? false);
    $paymentsByReg = $paymentsByReg ?? [];
    $defaultRefundAmount = (float) ($defaultRefundAmount ?? 3160);
    $colspan = $canManageBnsVerify ? 8 : 7;
@endphp

<div class="bns-reporting-scroll-hint d-none d-lg-block">
    <i class="bi bi-arrows-expand me-1"></i> Scroll horizontally to see membership and verification details.
</div>

<div class="bns-reporting-table-wrap">
    <table class="table bns-reporting-table bns-reporting-membership-table mb-0 align-middle">
        <thead>
            <tr>
                <th>Submitted</th>
                <th>Member</th>
                <th>Membership No</th>
                <th>Registration No.</th>
                <th>Proof</th>
                <th>Overall Status</th>
                <th>1. Trustee Verify</th>
                @if($canManageBnsVerify)
                    <th>2. BNS Verify</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $upload)
                @php
                    $badge = match ($upload->status) {
                        'verified' => 'success',
                        'trustee_verified' => 'primary',
                        'rejected' => 'danger',
                        'refunded' => 'info',
                        default => 'warning',
                    };
                    $trusteeStatus = $upload->trustee_status ?: 'pending';
                    $photoUrl = $upload->photoUrl();
                    $payment = $paymentsByReg[trim((string) $upload->registration_number)] ?? null;
                    $modalId = 'bnsRefundModal-'.$upload->id;
                @endphp
                <tr>
                    <td class="text-muted small text-nowrap">
                        {{ $upload->created_at?->format('d M Y') ?: '—' }}<br>
                        <span class="opacity-75">{{ $upload->created_at?->format('h:i A') ?: '—' }} IST</span>
                    </td>
                    <td style="min-width:200px">
                        <div class="fw-bold">{{ $upload->membership_name }}</div>
                        <div class="small">{{ $upload->mobile ?: '—' }}</div>
                        <div class="small text-muted">{{ $upload->email ?: '—' }}</div>
                    </td>
                    <td class="fw-semibold text-nowrap">{{ $upload->membership_no }}</td>
                    <td><span class="bns-reporting-reg">{{ $upload->registration_number ?: '—' }}</span></td>
                    <td>
                        @if($photoUrl)
                            <a href="{{ $photoUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary text-nowrap">
                                <i class="bi bi-image"></i> View
                            </a>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge rounded-pill text-bg-{{ $badge }}">{{ $upload->statusLabel() }}</span>
                    </td>
                    <td style="min-width:280px">
                        @if($upload->canTrusteeVerify())
                            <form method="POST" action="{{ route('reporting.membership.trustee-verify', $upload) }}" class="bns-membership-verify-form">
                                @csrf
                                <label class="form-label small mb-1">Remarks</label>
                                <textarea name="trustee_remarks" class="form-control form-control-sm mb-2" rows="2" required placeholder="Enter trustee remarks"></textarea>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="submit" name="trustee_action" value="approved" class="btn btn-sm btn-success">
                                        <i class="bi bi-check-lg"></i> Verify
                                    </button>
                                    <button type="submit" name="trustee_action" value="rejected" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="small">
                                <strong>{{ $upload->trusteeStatusLabel() }}</strong>
                                @if($upload->trustee_remarks)
                                    <div class="text-muted mt-1">{{ $upload->trustee_remarks }}</div>
                                @endif
                                @if($upload->trustee_verified_at)
                                    <div class="text-muted mt-1">
                                        {{ $upload->trusteeVerifier?->name ?: 'Admin' }}
                                        · {{ \Illuminate\Support\Carbon::parse($upload->trustee_verified_at)->format('d M Y, h:i A') }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </td>
                    @if($canManageBnsVerify)
                        <td style="min-width:280px">
                            @if($upload->canBnsVerify())
                                <form method="POST" action="{{ route('reporting.membership.bns-verify', $upload) }}" class="bns-membership-verify-form">
                                    @csrf
                                    <label class="form-label small mb-1">Remarks</label>
                                    <textarea name="bns_remarks" class="form-control form-control-sm mb-2" rows="2" required placeholder="Enter BNS remarks"></textarea>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="submit" name="bns_action" value="approved" class="btn btn-sm btn-success">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                        <button type="submit" name="bns_action" value="rejected" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-x-lg"></i> Reject
                                        </button>
                                    </div>
                                </form>
                            @elseif($trusteeStatus === 'rejected')
                                <span class="text-muted small">Blocked — trustee rejected</span>
                            @elseif($trusteeStatus !== 'approved')
                                <span class="text-muted small">Awaiting trustee approval</span>
                            @else
                                <div class="small">
                                    <strong>{{ $upload->bnsStatusLabel() }}</strong>
                                    @if($upload->bns_remarks)
                                        <div class="text-muted mt-1">{{ $upload->bns_remarks }}</div>
                                    @endif
                                    @if($upload->bns_verified_at)
                                        <div class="text-muted mt-1">
                                            {{ $upload->bnsVerifier?->name ?: 'Admin' }}
                                            · {{ \Illuminate\Support\Carbon::parse($upload->bns_verified_at)->format('d M Y, h:i A') }}
                                        </div>
                                    @endif

                                    @if($upload->canRefund() && $payment)
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning mt-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#{{ $modalId }}"
                                        >
                                            <i class="bi bi-cash-coin"></i> Refund
                                        </button>
                                    @elseif($upload->canRefund() && ! $payment)
                                        <div class="text-danger small mt-2">No successful payment found for refund.</div>
                                    @elseif($payment && $payment->refund_merchant_txn_no)
                                        <div class="mt-2">
                                            @if($upload->status === 'refunded' || $payment->isRefunded())
                                                <div class="text-success small">
                                                    Refunded ₹{{ number_format((float) ($payment->refund_amount ?? 0), 2) }}
                                                    · {{ $payment->refund_merchant_txn_no }}
                                                </div>
                                            @elseif($payment->refund_status === 'failed')
                                                <div class="text-danger small mb-1">
                                                    Refund failed: {{ $payment->refund_response_description ?: 'Unknown error' }}
                                                </div>
                                                <button
                                                    type="button"
                                                    class="btn btn-sm btn-warning mb-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#{{ $modalId }}"
                                                >
                                                    <i class="bi bi-cash-coin"></i> Retry Refund
                                                </button>
                                            @endif
                                            <form method="POST" action="{{ route('reporting.membership.refund-status', $upload) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-arrow-repeat"></i> Check Refund Status
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </td>
                    @endif
                </tr>

                @if($canManageBnsVerify && $payment && ($upload->canRefund() || $payment->refund_status === 'failed'))
                    @include('reporting.partials.refund-modal', [
                        'upload' => $upload,
                        'payment' => $payment,
                        'modalId' => $modalId,
                        'defaultRefundAmount' => min($defaultRefundAmount, (float) $payment->amount),
                    ])
                @endif
            @empty
                <tr>
                    <td colspan="{{ $colspan }}" class="bns-reporting-empty">
                        <i class="bi bi-person-vcard"></i>
                        No membership records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bns-reporting-mobile-cards d-lg-none">
    @forelse($rows as $upload)
        @php
            $badge = match ($upload->status) {
                'verified' => 'success',
                'trustee_verified' => 'primary',
                'rejected' => 'danger',
                'refunded' => 'info',
                default => 'warning',
            };
            $trusteeStatus = $upload->trustee_status ?: 'pending';
            $photoUrl = $upload->photoUrl();
            $payment = $paymentsByReg[trim((string) $upload->registration_number)] ?? null;
            $modalId = 'bnsRefundModalMobile-'.$upload->id;
        @endphp
        <article class="bns-reporting-mobile-card">
            <div class="d-flex justify-content-between gap-2 mb-2">
                <div>
                    <div class="fw-bold">{{ $upload->membership_name }}</div>
                    <div class="small text-muted">{{ $upload->created_at?->format('d M Y, h:i A') }}</div>
                </div>
                <span class="badge rounded-pill text-bg-{{ $badge }} align-self-start">{{ $upload->statusLabel() }}</span>
            </div>
            <div class="small mb-1"><strong>Membership No:</strong> {{ $upload->membership_no }}</div>
            <div class="small mb-1"><strong>Reg. No:</strong> {{ $upload->registration_number ?: '—' }}</div>
            <div class="small mb-1"><strong>Mobile:</strong> {{ $upload->mobile ?: '—' }}</div>
            <div class="small mb-3"><strong>Email:</strong> {{ $upload->email ?: '—' }}</div>

            @if($photoUrl)
                <a href="{{ $photoUrl }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary mb-3">
                    <i class="bi bi-image"></i> View Proof
                </a>
            @endif

            <div class="border rounded p-3 mb-2 bg-light">
                <div class="fw-semibold mb-2">1. Trustee Verify</div>
                @if($upload->canTrusteeVerify())
                    <form method="POST" action="{{ route('reporting.membership.trustee-verify', $upload) }}">
                        @csrf
                        <textarea name="trustee_remarks" class="form-control form-control-sm mb-2" rows="2" required placeholder="Enter trustee remarks"></textarea>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" name="trustee_action" value="approved" class="btn btn-sm btn-success">Verify</button>
                            <button type="submit" name="trustee_action" value="rejected" class="btn btn-sm btn-outline-danger">Reject</button>
                        </div>
                    </form>
                @else
                    <div class="small">
                        <strong>{{ $upload->trusteeStatusLabel() }}</strong>
                        @if($upload->trustee_remarks)
                            <div class="text-muted mt-1">{{ $upload->trustee_remarks }}</div>
                        @endif
                    </div>
                @endif
            </div>

            @if($canManageBnsVerify)
                <div class="border rounded p-3 bg-light">
                    <div class="fw-semibold mb-2">2. BNS Verify</div>
                    @if($upload->canBnsVerify())
                        <form method="POST" action="{{ route('reporting.membership.bns-verify', $upload) }}">
                            @csrf
                            <textarea name="bns_remarks" class="form-control form-control-sm mb-2" rows="2" required placeholder="Enter BNS remarks"></textarea>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="submit" name="bns_action" value="approved" class="btn btn-sm btn-success">Approve</button>
                                <button type="submit" name="bns_action" value="rejected" class="btn btn-sm btn-outline-danger">Reject</button>
                            </div>
                        </form>
                    @elseif($trusteeStatus === 'rejected')
                        <span class="text-muted small">Blocked — trustee rejected</span>
                    @elseif($trusteeStatus !== 'approved')
                        <span class="text-muted small">Awaiting trustee approval</span>
                    @else
                        <div class="small">
                            <strong>{{ $upload->bnsStatusLabel() }}</strong>
                            @if($upload->bns_remarks)
                                <div class="text-muted mt-1">{{ $upload->bns_remarks }}</div>
                            @endif

                            @if($upload->canRefund() && $payment)
                                <button
                                    type="button"
                                    class="btn btn-sm btn-warning mt-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#{{ $modalId }}"
                                >
                                    <i class="bi bi-cash-coin"></i> Refund
                                </button>
                            @elseif($upload->status === 'refunded' && $payment)
                                <form method="POST" action="{{ route('reporting.membership.refund-status', $upload) }}" class="mt-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-arrow-repeat"></i> Check Refund Status
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                @if($upload->canRefund() && $payment)
                    @include('reporting.partials.refund-modal', [
                        'upload' => $upload,
                        'payment' => $payment,
                        'modalId' => $modalId,
                        'defaultRefundAmount' => min($defaultRefundAmount, (float) $payment->amount),
                    ])
                @endif
            @endif
        </article>
    @empty
        <div class="bns-reporting-empty">
            <i class="bi bi-person-vcard"></i>
            No membership records found.
        </div>
    @endforelse
</div>
