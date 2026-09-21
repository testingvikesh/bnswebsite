@extends('sop.layouts.app')

@section('title', 'Intro Session Registers')
@section('page-title', 'Intro Session Registers')

@section('content')
@php
    $view = $view ?? 'registered';
    $session = (int) ($session ?? 0);
    $search = $search ?? '';
    $payment = $payment ?? '';
    $program = $program ?? '';
    $source = $source ?? '';
    $dateFrom = $date_from ?? '';
    $dateTo = $date_to ?? '';
    $sessions = $sessions ?? bns_introduction_sessions();
    $allowed = $allowed ?? bns_intro_session_allowed_numbers();
    $stats = $stats ?? ['registered' => 0, 'paid' => 0, 'filtered' => 0, 'session_totals' => [], 'session_paid' => []];
    $programOptions = $programOptions ?? [];
    $formSourceOptions = $formSourceOptions ?? [];
    $keep = array_filter([
        'view' => $view !== 'registered' ? $view : null,
        'session' => $session > 0 ? $session : null,
        'q' => $search !== '' ? $search : null,
        'payment' => $payment !== '' ? $payment : null,
        'program' => $program !== '' ? $program : null,
        'source' => $source !== '' ? $source : null,
        'date_from' => $dateFrom !== '' ? $dateFrom : null,
        'date_to' => $dateTo !== '' ? $dateTo : null,
    ]);
    $hasFilters = $search !== '' || $payment !== '' || $program !== '' || $source !== '' || $dateFrom !== '' || $dateTo !== '' || $session > 0;
    $linkKeep = function (array $overrides = []) use ($keep) {
        $merged = array_filter(array_merge($keep, $overrides), fn ($value) => $value !== null && $value !== '' && $value !== 0);
        return $merged;
    };
@endphp

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <p class="text-muted mb-0">
        All Introduction Session registered members and payment-done members. Download Excel for the list you are viewing.
    </p>
    <a href="{{ route('controlpanel.intro-session-registers.export', $keep) }}" class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-1"></i> Excel download
    </a>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('controlpanel.intro-session-registers.index', $linkKeep(['view' => null, 'session' => $session ?: null])) }}" class="text-decoration-none">
            <div class="sop-stat {{ $view === 'registered' ? 'border-primary' : '' }}">
                <div class="sop-stat__icon bg-primary-subtle text-primary"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="text-muted small">Registered users</div>
                    <strong>{{ number_format($stats['registered']) }}</strong>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('controlpanel.intro-session-registers.index', $linkKeep(['view' => 'paid', 'session' => $session ?: null, 'payment' => null])) }}" class="text-decoration-none">
            <div class="sop-stat {{ $view === 'paid' ? 'border-success' : '' }}">
                <div class="sop-stat__icon bg-success-subtle text-success"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                    <div class="text-muted small">Payment done</div>
                    <strong>{{ number_format($stats['paid']) }}</strong>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-info-subtle text-info"><i class="bi bi-funnel"></i></div>
            <div>
                <div class="text-muted small">Showing now</div>
                <strong>{{ number_format($stats['filtered']) }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="sop-card p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h5 class="mb-1">{{ $view === 'paid' ? 'Payment done users' : 'Registered users' }}</h5>
            <p class="text-muted small mb-0">Filter by session, payment, program, source, or date, then download Excel.</p>
        </div>
        <div class="btn-group flex-wrap">
            <a href="{{ route('controlpanel.intro-session-registers.index', $linkKeep(['session' => null])) }}"
               class="btn btn-sm {{ $session === 0 ? 'btn-dark' : 'btn-outline-secondary' }}">
                All sessions
            </a>
            @foreach($allowed as $sessionNo)
                <a href="{{ route('controlpanel.intro-session-registers.index', $linkKeep(['session' => $sessionNo])) }}"
                   class="btn btn-sm {{ $session === (int) $sessionNo ? 'btn-danger' : 'btn-outline-secondary' }}">
                    S{{ $sessionNo }}
                    <span class="ms-1">{{ number_format($view === 'paid' ? ($stats['session_paid'][$sessionNo] ?? 0) : ($stats['session_totals'][$sessionNo] ?? 0)) }}</span>
                </a>
            @endforeach
        </div>
    </div>

    <form method="GET" action="{{ route('controlpanel.intro-session-registers.index') }}" class="row g-2 align-items-end">
        @if($view !== 'registered')
            <input type="hidden" name="view" value="{{ $view }}">
        @endif
        @if($session > 0)
            <input type="hidden" name="session" value="{{ $session }}">
        @endif
        <div class="col-md-4">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
        </div>
        @if($view !== 'paid')
            <div class="col-md-2">
                <label class="form-label">Payment</label>
                <select name="payment" class="form-select">
                    <option value="">All</option>
                    <option value="paid" @selected($payment === 'paid')>Payment done</option>
                    <option value="unpaid" @selected($payment === 'unpaid')>Not paid</option>
                </select>
            </div>
        @endif
        <div class="col-md-3">
            <label class="form-label">Program</label>
            <select name="program" class="form-select">
                <option value="">All programs</option>
                @foreach($programOptions as $option)
                    <option value="{{ $option }}" @selected($program === $option)>{{ $option }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Form source</label>
            <select name="source" class="form-select">
                <option value="">All sources</option>
                @foreach($formSourceOptions as $value => $label)
                    <option value="{{ $value }}" @selected($source === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">From date</label>
            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">To date</label>
            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" class="btn btn-sop-primary">
                <i class="bi bi-funnel me-1"></i> Apply
            </button>
            @if($hasFilters)
                <a href="{{ route('controlpanel.intro-session-registers.index', array_filter(['view' => $view !== 'registered' ? $view : null])) }}" class="btn btn-outline-secondary">Clear</a>
            @endif
        </div>
    </form>
</div>

<div class="sop-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Reg. No.</th>
                    <th>Session</th>
                    @if($view === 'paid')
                        <th>Amount</th>
                        <th>Paid at</th>
                    @else
                        <th>Program</th>
                        <th>Registered</th>
                        <th>Payment</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $index => $row)
                    @php
                        $inquiry = $row['inquiry'] ?? null;
                        $payment = $row['payment'] ?? null;
                        $sessionNo = (int) ($row['session'] ?? 0);
                    @endphp
                    <tr>
                        <td class="text-muted">{{ $index + 1 }}</td>
                        <td class="fw-semibold">
                            {{ $payment->customer_name ?? ($inquiry->full_name ?? '—') }}
                            @if($inquiry)
                                <div class="small text-muted fw-normal">{{ $inquiry->formSourceLabel() }}</div>
                            @endif
                        </td>
                        <td class="small">
                            <div>{{ $payment->customer_mobile ?? ($inquiry->mobile ?? '—') }}</div>
                            <div class="text-muted">{{ $payment->customer_email ?? ($inquiry->email ?? '—') }}</div>
                        </td>
                        <td class="small fw-semibold">{{ $payment->registration_number ?? ($inquiry->registration_number ?? '—') }}</td>
                        <td>{{ $sessionNo > 0 ? bns_intro_session_label($sessionNo) : '—' }}</td>
                        @if($view === 'paid')
                            <td class="fw-semibold text-success">₹{{ $payment ? number_format((float) $payment->amount, 2) : '—' }}</td>
                            <td class="small text-muted">{{ $payment?->paid_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '—' }}</td>
                        @else
                            <td class="small">{{ $inquiry->interested_program ?? '—' }}</td>
                            <td class="small text-muted">{{ $inquiry?->created_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '—' }}</td>
                            <td>
                                @if($payment)
                                    <span class="badge text-bg-success">Payment done</span>
                                    <div class="small text-muted mt-1">₹{{ number_format((float) $payment->amount, 2) }}</div>
                                @else
                                    <span class="badge text-bg-secondary">Not paid</span>
                                @endif
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
