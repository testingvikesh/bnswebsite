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

            @php
                $status = $status ?? '';
                $sessionFilter = (int) ($sessionFilter ?? 0);
                $showList = $showList ?? false;
                $filterKeep = array_filter([
                    'q' => $search !== '' ? $search : null,
                    'session' => $sessionFilter > 0 ? $sessionFilter : null,
                ]);
                $statusUrl = function (string $value) use ($filterKeep) {
                    return route('crm.dashboard', array_filter($filterKeep + ['status' => $value]));
                };
                $sessionUrl = function (int $sessionNo) use ($search, $status) {
                    return route('crm.dashboard', array_filter([
                        'q' => $search !== '' ? $search : null,
                        'status' => $status !== '' ? $status : null,
                        'session' => $sessionNo > 0 ? $sessionNo : null,
                    ]));
                };
                $statusHeading = match ($status) {
                    'present' => 'Present',
                    'absent' => 'Absent',
                    'paid' => 'Payment done',
                    'assigned' => 'Assigned',
                    'all' => 'All members',
                    default => 'Search results',
                };
            @endphp

            <div class="bns-crm-stats">
                <a href="{{ route('crm.dashboard') }}" class="bns-crm-stat{{ $status === '' && $sessionFilter === 0 && $search === '' ? ' is-active' : '' }}">
                    <span>Sessions</span>
                    <strong>{{ number_format($totals['sessions']) }}</strong>
                </a>
                <a href="{{ $statusUrl('all') }}" class="bns-crm-stat{{ $status === 'all' ? ' is-active' : '' }}">
                    <span>Total</span>
                    <strong>{{ number_format($totals['registered']) }}</strong>
                </a>
                <a href="{{ $statusUrl('present') }}" class="bns-crm-stat bns-crm-stat--present{{ $status === 'present' ? ' is-active' : '' }}">
                    <span>Present</span>
                    <strong>{{ number_format($totals['present']) }}</strong>
                </a>
                <a href="{{ $statusUrl('absent') }}" class="bns-crm-stat bns-crm-stat--absent{{ $status === 'absent' ? ' is-active' : '' }}">
                    <span>Absent</span>
                    <strong>{{ number_format($totals['absent']) }}</strong>
                </a>
                <a href="{{ $statusUrl('paid') }}" class="bns-crm-stat bns-crm-stat--present{{ $status === 'paid' ? ' is-active' : '' }}">
                    <span>Payment done</span>
                    <strong>{{ number_format($totals['paid'] ?? 0) }}</strong>
                </a>
                <a href="{{ $statusUrl('assigned') }}" class="bns-crm-stat{{ $status === 'assigned' ? ' is-active' : '' }}">
                    <span>Assigned</span>
                    <strong>{{ number_format($totals['assigned'] ?? 0) }}</strong>
                </a>
                <a href="{{ route('crm.today-attendance') }}" class="bns-crm-stat bns-crm-stat--present">
                    <span>Today attendance</span>
                    <strong>{{ number_format($totals['today_attendance'] ?? 0) }}</strong>
                </a>
            </div>
            <p class="bns-crm-section-copy">Click a box to filter the list. Total = Present + Absent + Payment done.</p>

            <div class="bns-crm-pills" role="tablist" aria-label="Filter by session">
                <a href="{{ $sessionUrl(0) }}" class="{{ $sessionFilter === 0 ? 'is-active' : '' }}">All sessions</a>
                @foreach($allowed ?? bns_intro_session_allowed_numbers() as $no)
                    <a href="{{ $sessionUrl((int) $no) }}" class="{{ $sessionFilter === (int) $no ? 'is-active' : '' }}">
                        S{{ $no }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('crm.dashboard') }}" class="bns-crm-search">
                @if($status !== '')
                    <input type="hidden" name="status" value="{{ $status }}">
                @endif
                @if($sessionFilter > 0)
                    <input type="hidden" name="session" value="{{ $sessionFilter }}">
                @endif
                <label for="crmSearch">Search{{ $sessionFilter > 0 ? ' this session' : ' all sessions' }}</label>
                <div class="bns-crm-search__row">
                    <input
                        id="crmSearch"
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Name, mobile, email, registration number"
                    >
                    <button type="submit"><i class="fas fa-search" aria-hidden="true"></i> Search</button>
                    @if($search !== '' || $status !== '' || $sessionFilter > 0)
                        <a href="{{ route('crm.dashboard') }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            @if($showList)
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>{{ $statusHeading }}</h3>
                        <span>
                            {{ number_format($results->count()) }} {{ Str::plural('person', $results->count()) }}
                            @if($sessionFilter > 0)
                                · {{ bns_intro_session_label($sessionFilter) }}
                            @endif
                            @if($search !== '')
                                matching “{{ $search }}”
                            @endif
                        </span>
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
