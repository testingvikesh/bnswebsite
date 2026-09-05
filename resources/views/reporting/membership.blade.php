@extends('reporting.layouts.app')

@section('title', 'Membership Data Report')

@php
    $activeFilters = collect([
        $search, $statusFilter, $dateFrom, $dateTo,
    ])->filter(fn ($value) => filled($value))->count();

    $statusLabels = [
        'pending' => 'Pending Trustee',
        'trustee_verified' => 'Trustee Verified',
        'verified' => 'BNS Verified',
        'rejected' => 'Rejected',
        'refunded' => 'Refunded',
    ];
@endphp

@section('content')
<section class="bns-reporting-hero">
    <div class="bns-reporting-hero__top">
        <div>
            <span class="bns-reporting-hero__eyebrow">
                <i class="bi bi-person-vcard-fill"></i> Membership Dashboard
            </span>
            <h1>Membership Data Report</h1>
            <p>View all membership proof uploads with a two-step verification flow — Trustee verify first, then BNS verify.</p>
        </div>
        <a href="{{ route('reporting.membership.export', request()->query()) }}" class="btn bns-reporting-btn-export" target="_blank" rel="noopener">
            <i class="bi bi-file-earmark-pdf me-1"></i> Generate PDF
        </a>
    </div>
</section>

@include('reporting.partials.page-tabs', ['activeTab' => 'membership'])

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-2">
        <div class="bns-reporting-stat bns-reporting-stat--total">
            <div class="bns-reporting-stat__icon"><i class="bi bi-people-fill"></i></div>
            <div class="bns-reporting-stat__label">Total</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['total']) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="bns-reporting-stat bns-reporting-stat--pending">
            <div class="bns-reporting-stat__icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="bns-reporting-stat__label">Pending Trustee</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['pending']) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="bns-reporting-stat bns-reporting-stat--intro">
            <div class="bns-reporting-stat__icon"><i class="bi bi-shield-check"></i></div>
            <div class="bns-reporting-stat__label">Trustee Done</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['trustee_verified']) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="bns-reporting-stat bns-reporting-stat--today">
            <div class="bns-reporting-stat__icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="bns-reporting-stat__label">BNS Verified</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['verified']) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="bns-reporting-stat bns-reporting-stat--quick">
            <div class="bns-reporting-stat__icon"><i class="bi bi-x-circle-fill"></i></div>
            <div class="bns-reporting-stat__label">Rejected</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['rejected']) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-2">
        <div class="bns-reporting-stat bns-reporting-stat--inquiry">
            <div class="bns-reporting-stat__icon"><i class="bi bi-cash-coin"></i></div>
            <div class="bns-reporting-stat__label">Refunded</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['refunded']) }}</div>
        </div>
    </div>
</div>

<div class="bns-reporting-filter">
    <div class="bns-reporting-filter__head">
        <div>
            <h2><i class="bi bi-funnel-fill text-danger me-1"></i> Filter Membership Data</h2>
            <p>Search by member details and filter by verification status or submission date.</p>
        </div>
        @if($activeFilters > 0)
            <span class="badge rounded-pill text-bg-danger">{{ $activeFilters }} active</span>
        @endif
    </div>

    <form method="GET" action="{{ route('reporting.membership') }}" class="row g-3 align-items-end js-reporting-filter-form">
        <div class="col-md-4">
            <label class="form-label">Search</label>
            <input
                type="text"
                name="q"
                class="form-control"
                value="{{ $search }}"
                placeholder="Name, membership no, email, mobile, reg. no."
            >
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                @foreach($statusOptions as $option)
                    <option value="{{ $option }}" @selected($statusFilter === $option)>
                        {{ $statusLabels[$option] ?? ucfirst(str_replace('_', ' ', $option)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">From Date</label>
            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">To Date</label>
            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn bns-reporting-btn-filter w-100">
                <i class="bi bi-search me-1"></i> Apply
            </button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('reporting.membership') }}" class="btn btn-outline-primary bns-reporting-btn-reset w-100">Reset</a>
        </div>
    </form>
</div>

<section class="bns-reporting-table-card">
    <div class="bns-reporting-table-card__head">
        <div>
            <h3><i class="bi bi-table me-1"></i> Membership Verification Details</h3>
            <span>
                Showing {{ number_format($stats['filtered']) }} {{ Str::plural('record', $stats['filtered']) }}
            </span>
        </div>
    </div>
    @include('reporting.partials.membership-table', [
        'rows' => $uploads,
        'canManageBnsVerify' => $canManageBnsVerify ?? false,
        'paymentsByReg' => $paymentsByReg ?? [],
        'defaultRefundAmount' => $defaultRefundAmount ?? 3160,
    ])
</section>
@endsection

@push('scripts')
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    document.querySelectorAll('.modal[data-refund-otp-url]').forEach(function (modal) {
        const form = modal.querySelector('.bns-refund-form');
        const sendBtn = modal.querySelector('.bns-refund-send-otp');
        const submitBtn = modal.querySelector('.bns-refund-submit');
        const amountInput = modal.querySelector('.bns-refund-amount');
        const otpInput = modal.querySelector('.bns-refund-otp');
        const otpStep = modal.querySelector('.bns-refund-otp-step');
        const feedback = modal.querySelector('.bns-refund-otp-feedback');
        const otpUrl = modal.getAttribute('data-refund-otp-url');

        if (!form || !sendBtn || !submitBtn || !amountInput || !otpInput || !otpStep || !feedback || !otpUrl) {
            return;
        }

        function showFeedback(message, ok) {
            feedback.textContent = message;
            feedback.classList.remove('d-none', 'alert-success', 'alert-danger');
            feedback.classList.add(ok ? 'alert-success' : 'alert-danger');
        }

        sendBtn.addEventListener('click', async function () {
            if (!amountInput.checkValidity()) {
                amountInput.reportValidity();
                return;
            }

            sendBtn.disabled = true;
            sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Sending...';

            try {
                const response = await fetch(otpUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({
                        refund_amount: amountInput.value,
                    }),
                });

                const data = await response.json().catch(function () {
                    if (response.status === 419) {
                        return { success: false, message: 'Session expired. Refresh the page and try Send OTP again.' };
                    }
                    return { success: false, message: 'Unable to send OTP. Please try again.' };
                });

                if (!response.ok || !data.success) {
                    showFeedback(data.message || 'Unable to send OTP. Please try again.', false);
                    return;
                }

                showFeedback(data.message || 'OTP sent successfully.', true);
                otpStep.classList.remove('d-none');
                submitBtn.classList.remove('d-none');
                submitBtn.disabled = false;
                otpInput.required = true;
                otpInput.focus();
                amountInput.readOnly = true;
                sendBtn.innerHTML = '<i class="bi bi-arrow-repeat me-1"></i> Resend OTP';
            } catch (error) {
                showFeedback('Unable to send OTP. Please try again.', false);
            } finally {
                sendBtn.disabled = false;
                if (!sendBtn.innerHTML.includes('Resend')) {
                    sendBtn.innerHTML = '<i class="bi bi-envelope me-1"></i> Send OTP';
                }
            }
        });

        amountInput.addEventListener('input', function () {
            if (amountInput.readOnly) {
                return;
            }
        });

        modal.addEventListener('hidden.bs.modal', function () {
            feedback.classList.add('d-none');
            feedback.textContent = '';
            otpStep.classList.add('d-none');
            submitBtn.classList.add('d-none');
            submitBtn.disabled = true;
            otpInput.required = false;
            otpInput.value = '';
            amountInput.readOnly = false;
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="bi bi-envelope me-1"></i> Send OTP';
        });
    });
})();
</script>
@endpush
