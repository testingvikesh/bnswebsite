@extends('reporting.layouts.app')

@section('title', 'Santacruz School Reporting Status')

@php
    $sourceBadgeClass = function ($item) {
        return match ($item->resolvedFormSource()) {
            'intro-session-modal' => 'bns-reporting-badge--intro',
            'inquiry-modal' => 'bns-reporting-badge--inquiry',
            'register-quick-modal' => 'bns-reporting-badge--quick',
            'contact-page' => 'bns-reporting-badge--contact',
            default => 'bns-reporting-badge--unknown',
        };
    };

    $activeFilters = collect([
        $search, $formSourceFilter, $categoryFilter, $programFilter, $dateFrom, $dateTo,
    ])->filter(fn ($v) => filled($v))->count();

    $sessionGroups = $sessionGroups ?? [0 => collect()];
    $sessionEvents = $sessionEvents ?? [];
    $activeSession = (int) ($activeSession ?? 1);
    $allowedSessions = bns_intro_session_allowed_numbers();
    if (! in_array($activeSession, $allowedSessions, true)) {
        $activeSession = $allowedSessions[0] ?? 1;
    }
    $session1Absent = $session1Absent ?? collect();
    $tabQuery = request()->except('session');
    $todayDate = now()->toDateString();
    $isTodayActive = $dateFrom === $todayDate && $dateTo === $todayDate && $formSourceFilter === '';
    $isUniqueActive = $formSourceFilter === '' && $dateFrom === '' && $dateTo === '';
@endphp

@section('content')
<section class="bns-reporting-hero">
    <div class="bns-reporting-hero__top">
        <div>
            <span class="bns-reporting-hero__eyebrow"><i class="bi bi-graph-up-arrow"></i> Live Dashboard</span>
            <h1>Santacruz School Reporting Status</h1>
            <p>Track every enquiry from introduction sessions, general inquiries, confirm admissions, and the contact page — all in one place with powerful filters.</p>
        </div>
        <a href="{{ route('reporting.export', array_merge(request()->query(), ['session' => $activeSession])) }}" class="btn bns-reporting-btn-export js-reporting-export">
            <i class="bi bi-file-earmark-excel me-1"></i> Export to Excel
        </a>
    </div>
</section>

@include('reporting.partials.page-tabs', ['activeTab' => 'registration'])

<div class="bns-reporting-session-tabs mb-4" role="tablist" aria-label="Introduction sessions">
    @foreach($allowedSessions as $sessionNo)
        <a
            href="{{ route('reporting.index', array_merge($tabQuery, ['session' => $sessionNo])) }}"
            class="bns-reporting-session-tab{{ $activeSession === $sessionNo ? ' is-active' : '' }}"
            role="tab"
            data-session="{{ $sessionNo }}"
            aria-selected="{{ $activeSession === $sessionNo ? 'true' : 'false' }}"
        >
            <span class="bns-reporting-session-tab__label">Session {{ $sessionNo }}</span>
            <span class="bns-reporting-session-tab__meta">
                {{ ($sessionEvents[$sessionNo]['date'] ?? ('Session '.$sessionNo)) }}
                · {{ number_format($stats['session_'.$sessionNo] ?? 0) }} unique
            </span>
        </a>
    @endforeach
</div>

<div class="bns-reporting-filter">
    <div class="bns-reporting-filter__head">
        <div>
            <h2><i class="bi bi-funnel-fill text-danger me-1"></i> Filter Submissions</h2>
            <p>Search and narrow results by form type, program, category, or date range. Selecting a filter shows results immediately.</p>
        </div>
        @if($activeFilters > 0)
            <span class="badge rounded-pill text-bg-danger">{{ $activeFilters }} active</span>
        @endif
    </div>

    <form method="GET" action="{{ route('reporting.index') }}" class="row g-3 align-items-end js-reporting-filter-form">
        <input type="hidden" name="session" value="{{ $activeSession }}" class="js-reporting-session-field">
        <div class="col-md-4">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-control" value="{{ $search }}"
                   placeholder="Name, email, mobile, reg. no., city, business, message">
        </div>
        <div class="col-md-2">
            <label class="form-label">Form Source</label>
            <select name="form_source" class="form-select">
                <option value="">All sources</option>
                @foreach($formSourceOptions as $value => $label)
                    <option value="{{ $value }}" @selected($formSourceFilter === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
                <option value="">All categories</option>
                @foreach($categoryOptions as $option)
                    <option value="{{ $option }}" @selected($categoryFilter === $option)>{{ $option }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Program</label>
            <select name="program" class="form-select">
                <option value="">All programs</option>
                @foreach($programOptions as $option)
                    <option value="{{ $option }}" @selected($programFilter === $option)>{{ $option }}</option>
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
            <a href="{{ route('reporting.index', ['session' => $activeSession, 'date_from' => now()->toDateString(), 'date_to' => now()->toDateString()]) }}" class="btn btn-outline-secondary bns-reporting-btn-reset w-100 js-reporting-today-link">Today</a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('reporting.index', ['session' => $activeSession]) }}" class="btn btn-outline-primary bns-reporting-btn-reset w-100 js-reporting-all-link">All Records</a>
        </div>
    </form>
</div>

@foreach($allowedSessions as $sessionNo)
    @include('reporting.partials.session-panel', ['sessionNo' => $sessionNo])
@endforeach
@endsection
