@extends('reporting.layouts.app')

@section('title', 'Admission Confirm Report')

@php
    $activeFilters = collect([
        $search, $programFilter, $paymentModeFilter, $dateFrom, $dateTo, $paidDate ?? '',
    ])->filter(fn ($value) => filled($value))->count();
@endphp

@section('content')
<section class="bns-reporting-hero">
    <div class="bns-reporting-hero__top">
        <div>
            <span class="bns-reporting-hero__eyebrow">
                <i class="bi bi-credit-card-2-front-fill"></i> Payment Dashboard
            </span>
            <h1>Admission Confirm Report</h1>
            <p>View every successful BNS payment with participant, program, amount, gateway transaction, response, and receipt details.</p>
        </div>
        <a href="{{ route('reporting.payments.export', request()->query()) }}" class="btn bns-reporting-btn-export">
            <i class="bi bi-file-earmark-excel me-1"></i> Export Payments
        </a>
    </div>
</section>

@include('reporting.partials.page-tabs', ['activeTab' => 'payments'])

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="bns-reporting-stat bns-reporting-stat--total">
            <div class="bns-reporting-stat__icon"><i class="bi bi-check-circle-fill"></i></div>
            <div class="bns-reporting-stat__label">Successful Payments</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['total']) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="bns-reporting-stat bns-reporting-stat--today">
            <div class="bns-reporting-stat__icon"><i class="bi bi-currency-rupee"></i></div>
            <div class="bns-reporting-stat__label">Total Collected</div>
            <div class="bns-reporting-stat__value bns-reporting-stat__value--amount">₹{{ number_format($stats['amount'], 2) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="bns-reporting-stat bns-reporting-stat--intro">
            <div class="bns-reporting-stat__icon"><i class="bi bi-calendar-check-fill"></i></div>
            <div class="bns-reporting-stat__label">Paid Today</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['today']) }}</div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="bns-reporting-stat bns-reporting-stat--quick">
            <div class="bns-reporting-stat__icon"><i class="bi bi-grid-fill"></i></div>
            <div class="bns-reporting-stat__label">Programs</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['programs']) }}</div>
        </div>
    </div>
</div>

@php
    $dateChips = $dateChips ?? collect();
    $paidDate = $paidDate ?? '';
    $chipQuery = array_filter([
        'q' => $search !== '' ? $search : null,
        'program' => $programFilter !== '' ? $programFilter : null,
        'payment_mode' => $paymentModeFilter !== '' ? $paymentModeFilter : null,
    ], fn ($value) => $value !== null && $value !== '');
@endphp
@if($dateChips->isNotEmpty())
<section class="bns-reporting-date-chips mb-4">
    <div class="bns-reporting-date-chips__label">Click an intro session date</div>
    <div class="bns-reporting-date-chips__row">
        @foreach($dateChips as $chip)
            <a
                href="{{ route('reporting.payments', array_merge($chipQuery, ['paid_date' => $chip['date']])) }}#payment-members"
                class="bns-reporting-date-chip{{ $paidDate === $chip['date'] ? ' is-active' : '' }}"
            >
                <span>{{ $chip['label'] }}</span>
                <strong>{{ number_format($chip['count']) }}</strong>
            </a>
        @endforeach
        @if($paidDate !== '')
            <a href="{{ route('reporting.payments', $chipQuery) }}" class="bns-reporting-date-chip bns-reporting-date-chip--all">All dates</a>
        @endif
    </div>
</section>
@endif

@if($programSummary->isNotEmpty())
<section class="bns-reporting-program-summary mb-4">
    <div class="bns-reporting-table-card__head">
        <div>
            <h3><i class="bi bi-bar-chart-fill me-1"></i> Program-wise Payment Summary</h3>
            <span>Successful payment count and collection across all programs.</span>
        </div>
    </div>
    <div class="bns-reporting-program-summary__grid">
        @foreach($programSummary as $program)
            <article class="bns-reporting-program-card">
                <div class="bns-reporting-program-card__icon"><i class="bi bi-mortarboard-fill"></i></div>
                <div>
                    <h4>{{ $program['program'] }}</h4>
                    <p>{{ number_format($program['count']) }} {{ Str::plural('payment', $program['count']) }}</p>
                </div>
                <strong>₹{{ number_format($program['amount'], 2) }}</strong>
            </article>
        @endforeach
    </div>
</section>
@endif

<div class="bns-reporting-filter">
    <div class="bns-reporting-filter__head">
        <div>
            <h2><i class="bi bi-funnel-fill text-danger me-1"></i> Filter Successful Payments</h2>
            <p>Search by participant or transaction and filter by program, payment mode, or date.</p>
        </div>
        @if($activeFilters > 0)
            <span class="badge rounded-pill text-bg-danger">{{ $activeFilters }} active</span>
        @endif
    </div>

    <form method="GET" action="{{ route('reporting.payments') }}" class="row g-3 align-items-end js-reporting-filter-form">
        <div class="col-md-4">
            <label class="form-label">Search</label>
            <input
                type="text"
                name="q"
                class="form-control"
                value="{{ $search }}"
                placeholder="Name, mobile, email, reg. no., transaction ID"
            >
        </div>
        <div class="col-md-3">
            <label class="form-label">Program</label>
            <select name="program" class="form-select">
                <option value="">All programs</option>
                @foreach($programOptions as $program)
                    <option value="{{ $program }}" @selected($programFilter === $program)>{{ $program }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Payment Mode</label>
            <input type="text" name="payment_mode" class="form-control" value="{{ $paymentModeFilter }}" placeholder="UPI, Card, etc.">
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
            <a href="{{ route('reporting.payments', ['date_from' => now()->toDateString(), 'date_to' => now()->toDateString()]) }}" class="btn btn-outline-secondary bns-reporting-btn-reset w-100">Today</a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('reporting.payments') }}" class="btn btn-outline-primary bns-reporting-btn-reset w-100">All Payments</a>
        </div>
    </form>
</div>

<section class="bns-reporting-table-card" id="payment-members">
    <div class="bns-reporting-table-card__head">
        <div>
            <h3><i class="bi bi-table me-1"></i> Successful Payment Details</h3>
            <span>
                Showing {{ number_format($stats['filtered']) }} {{ Str::plural('payment', $stats['filtered']) }}
                · ₹{{ number_format($stats['filtered_amount'], 2) }} collected
            </span>
        </div>
    </div>
    @include('reporting.partials.payments-table', [
        'rows' => $payments,
        'canManageRefunds' => $canManageRefunds ?? false,
        'membershipsByReg' => $membershipsByReg ?? [],
        'defaultRefundAmount' => $defaultRefundAmount ?? 3160,
    ])
</section>
@endsection

@push('scripts')
@include('reporting.partials.refund-otp-script')
@endpush
