@extends('layouts.front')

@section('title', 'BNS CRM Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => $page['title'] ?? 'BNS CRM',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM'],
            ['label' => 'Dashboard'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @include('crm.partials.toolbar', ['active' => 'dashboard', 'isAdmin' => true])

            <div class="bns-crm-stats">
                <div class="bns-crm-stat">
                    <span>Sessions</span>
                    <strong>{{ number_format($totals['sessions']) }}</strong>
                </div>
                <div class="bns-crm-stat">
                    <span>Total</span>
                    <strong>{{ number_format($totals['registered']) }}</strong>
                </div>
                <div class="bns-crm-stat bns-crm-stat--present">
                    <span>Present</span>
                    <strong>{{ number_format($totals['present']) }}</strong>
                </div>
                <div class="bns-crm-stat bns-crm-stat--absent">
                    <span>Absent</span>
                    <strong>{{ number_format($totals['absent']) }}</strong>
                </div>
                <a href="{{ route('crm.payments') }}" class="bns-crm-stat bns-crm-stat--present">
                    <span>Payment done</span>
                    <strong>{{ number_format($totals['paid'] ?? 0) }}</strong>
                </a>
                <div class="bns-crm-stat">
                    <span>Assigned</span>
                    <strong>{{ number_format($totals['assigned'] ?? 0) }}</strong>
                </div>
                <a href="{{ route('crm.today-attendance') }}" class="bns-crm-stat bns-crm-stat--present">
                    <span>Today attendance</span>
                    <strong>{{ number_format($totals['today_attendance'] ?? 0) }}</strong>
                </a>
            </div>
            <p class="bns-crm-section-copy">Total = Present + Absent + Payment done.</p>

            <form method="GET" action="{{ route('crm.dashboard') }}" class="bns-crm-search">
                <label for="crmSearch">Search all sessions</label>
                <div class="bns-crm-search__row">
                    <input
                        id="crmSearch"
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Name, mobile, email, registration number"
                    >
                    <button type="submit"><i class="fas fa-search" aria-hidden="true"></i> Search</button>
                    @if($search !== '')
                        <a href="{{ route('crm.dashboard') }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            @if($search !== '')
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>Search results</h3>
                        <span>{{ number_format($results->count()) }} {{ Str::plural('match', $results->count()) }} for “{{ $search }}”</span>
                    </div>
                    @include('crm.partials.search-table', ['rows' => $results])
                </section>
            @endif

            <h3 class="bns-crm-section-title">All introduction sessions</h3>
            <p class="bns-crm-section-copy">Click a session to see present and absent lists.</p>

            <div class="bns-crm-sessions">
                @foreach($sessions as $item)
                    <a href="{{ route('crm.session', $item['number']) }}" class="bns-crm-session">
                        <span class="bns-crm-session__no">Session {{ $item['number'] }}</span>
                        <strong>{{ $item['title'] }}</strong>
                        <em>{{ $item['date'] !== '' ? $item['date'] : 'Date to be announced' }}@if($item['time'] !== '') · {{ $item['time'] }}@endif</em>
                        <div class="bns-crm-session__counts">
                            <span>Total {{ number_format($item['registered']) }}</span>
                            <span class="is-present">Present {{ number_format($item['present']) }}</span>
                            <span class="is-absent">Absent {{ number_format($item['absent']) }}</span>
                            <span class="is-present">Paid {{ number_format($item['paid'] ?? 0) }}</span>
                            <span>Assigned {{ number_format($item['assigned'] ?? 0) }}</span>
                        </div>
                        <div class="bns-crm-session__bar" aria-hidden="true">
                            <span style="width: {{ $item['present_pct'] }}%"></span>
                        </div>
                        <span class="bns-crm-session__cta">Open present / absent <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection
