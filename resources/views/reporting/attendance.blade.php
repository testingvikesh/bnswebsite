@extends('reporting.layouts.app')

@section('title', 'Attendance Report')

@php
    $activeFilters = collect([
        $search, $sessionFilter, $dateFrom, $dateTo,
    ])->filter(fn ($value) => filled($value))->count();
@endphp

@section('content')
<section class="bns-reporting-hero">
    <div class="bns-reporting-hero__top">
        <div>
            <span class="bns-reporting-hero__eyebrow">
                <i class="bi bi-clipboard-check-fill"></i> Attendance Dashboard
            </span>
            <h1>Attendance Report</h1>
            <p>View every Introduction Session check-in marked from the public attendance page.</p>
        </div>
        <a href="{{ route('reporting.attendance.export', request()->query()) }}" class="btn bns-reporting-btn-export" target="_blank" rel="noopener">
            <i class="bi bi-file-earmark-pdf me-1"></i> Generate PDF
        </a>
    </div>
</section>

@include('reporting.partials.page-tabs', ['activeTab' => 'attendance'])

<div class="row g-3 mb-4">
    <div class="col-6 col-lg">
        <a href="{{ route('reporting.attendance') }}#attendance-records" class="bns-reporting-stat bns-reporting-stat--total{{ $sessionFilter === '' && $dateFrom === '' && $dateTo === '' && $search === '' ? ' is-active' : '' }}" title="Show all attendance">
            <div class="bns-reporting-stat__icon"><i class="bi bi-people-fill"></i></div>
            <div class="bns-reporting-stat__label">Total Attended</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['total']) }}</div>
        </a>
    </div>
    <div class="col-6 col-lg">
        <a href="{{ route('reporting.attendance', ['date_from' => now()->toDateString(), 'date_to' => now()->toDateString()]) }}#attendance-records" class="bns-reporting-stat bns-reporting-stat--today{{ $dateFrom === now()->toDateString() && $dateTo === now()->toDateString() ? ' is-active' : '' }}" title="Show today's attendance">
            <div class="bns-reporting-stat__icon"><i class="bi bi-calendar-check-fill"></i></div>
            <div class="bns-reporting-stat__label">Today</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['today']) }}</div>
        </a>
    </div>
    @foreach(($allowedSessions ?? bns_intro_session_allowed_numbers()) as $sessionNo)
    <div class="col-6 col-lg">
        <a
            href="{{ route('reporting.attendance', ['session' => $sessionNo]) }}#attendance-records"
            class="bns-reporting-stat {{ $sessionNo % 2 === 0 ? 'bns-reporting-stat--quick' : 'bns-reporting-stat--intro' }}{{ $sessionFilter === (string) $sessionNo ? ' is-active' : '' }}"
            title="Show Session {{ $sessionNo }} attendance"
        >
            <div class="bns-reporting-stat__icon"><i class="bi bi-{{ $sessionNo }}-circle-fill"></i></div>
            <div class="bns-reporting-stat__label">Session {{ $sessionNo }}</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['session_'.$sessionNo] ?? 0) }}</div>
        </a>
    </div>
    @endforeach
    <div class="col-6 col-lg">
        <a href="{{ route('reporting.attendance') }}#session1-absent" class="bns-reporting-stat bns-reporting-stat--missing" title="Jump to Session 1 Absent list">
            <div class="bns-reporting-stat__icon"><i class="bi bi-person-x-fill"></i></div>
            <div class="bns-reporting-stat__label">Session 1 Absent</div>
            <div class="bns-reporting-stat__value">{{ number_format($stats['session_1_absent'] ?? 0) }}</div>
        </a>
    </div>
</div>

<div class="bns-reporting-filter">
    <div class="bns-reporting-filter__head">
        <div>
            <h2><i class="bi bi-funnel-fill text-danger me-1"></i> Filter Attendance</h2>
            <p>Search by participant details and filter by session or attendance date.</p>
        </div>
        @if($activeFilters > 0)
            <span class="badge rounded-pill text-bg-danger">{{ $activeFilters }} active</span>
        @endif
    </div>

    <form method="GET" action="{{ route('reporting.attendance') }}" class="row g-3 align-items-end js-reporting-filter-form">
        <div class="col-md-4">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Name, mobile, email, reg. no., program">
        </div>
        <div class="col-md-2">
            <label class="form-label">Session</label>
            <select name="session" class="form-select">
                <option value="">All sessions</option>
                @foreach(($allowedSessions ?? bns_intro_session_allowed_numbers()) as $sessionNo)
                    <option value="{{ $sessionNo }}" @selected($sessionFilter === (string) $sessionNo)>Session {{ $sessionNo }}</option>
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
            <a href="{{ route('reporting.attendance') }}" class="btn btn-outline-primary bns-reporting-btn-reset w-100">Reset</a>
        </div>
    </form>
</div>

<section class="bns-reporting-table-card" id="attendance-records">
    <div class="bns-reporting-table-card__head">
        <div>
            <h3><i class="bi bi-table me-1"></i> Attendance List</h3>
            <span>
                Showing {{ number_format($stats['filtered']) }} {{ Str::plural('record', $stats['filtered']) }}
            </span>
        </div>
        <a href="{{ route('attendance') }}" class="btn btn-sm btn-outline-danger" target="_blank" rel="noopener">
            <i class="bi bi-box-arrow-up-right me-1"></i> Open Attendance Page
        </a>
    </div>
    @include('reporting.partials.attendance-table', ['rows' => $rows])
</section>

@php
    $session1Absent = $session1Absent ?? collect();
    $sourceBadgeClass = $sourceBadgeClass ?? function ($item) {
        return match ($item->resolvedFormSource()) {
            'intro-session-modal' => 'bns-reporting-badge--intro',
            'inquiry-modal' => 'bns-reporting-badge--inquiry',
            'register-quick-modal' => 'bns-reporting-badge--quick',
            'contact-page' => 'bns-reporting-badge--contact',
            default => 'bns-reporting-badge--unknown',
        };
    };
@endphp
@if($session1Absent->isNotEmpty())
<section class="bns-reporting-table-card mt-4" id="session1-absent">
    <div class="bns-reporting-table-card__head">
        <div>
            <h3><i class="bi bi-person-x me-1"></i> Session 1 Absent</h3>
            <span>
                Registered {{ number_format($stats['session_1_registered'] ?? 0) }}
                · Attended {{ number_format($stats['session_1_attended'] ?? 0) }}
                · Absent {{ number_format($session1Absent->count()) }} unique {{ Str::plural('user', $session1Absent->count()) }}
            </span>
        </div>
    </div>
    @include('reporting.partials.records-table', ['rows' => $session1Absent, 'sourceBadgeClass' => $sourceBadgeClass])
</section>
@endif
@endsection
