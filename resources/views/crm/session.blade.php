@extends('layouts.front')

@section('title', 'CRM Session '.$sessionNo)

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => 'Session '.$sessionNo,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => route('crm.dashboard')],
            ['label' => 'Session '.$sessionNo],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @if(session('status'))
                <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
            @endif
            @include('crm.partials.toolbar', ['active' => 'session', 'isAdmin' => true, 'sessionNo' => $sessionNo])

            @php
                $team = $team ?? '';
                $call = $call ?? '';
                $callStatus = $callStatus ?? '';
                $sessionQuery = function (array $extra = []) use ($sessionNo, $search, $status, $team, $call, $callStatus) {
                    return array_filter([
                        'session' => $sessionNo,
                        'status' => $extra['status'] ?? $status,
                        'q' => array_key_exists('q', $extra) ? $extra['q'] : $search,
                        'team' => array_key_exists('team', $extra) ? $extra['team'] : $team,
                        'call' => array_key_exists('call', $extra) ? $extra['call'] : $call,
                        'call_status' => array_key_exists('call_status', $extra) ? $extra['call_status'] : $callStatus,
                    ], fn ($value) => $value !== '' && $value !== null);
                };
            @endphp

            <div class="bns-crm-session-head">
                <div>
                    <span class="bns-message-intro__label">Session {{ $sessionNo }}</span>
                    <h2>{{ $event['title'] ?? ('Introduction Session '.$sessionNo) }}</h2>
                    <p>
                        {{ $event['date'] ?? '' }}
                        @if(!empty($event['time'])) · {{ $event['time'] }}@endif
                    </p>
                </div>
                <a href="{{ route('reporting.index', ['session' => $sessionNo]) }}" class="bns-crm-mini-btn bns-crm-mini-btn--primary" target="_blank" rel="noopener">
                    <i class="fas fa-chart-line" aria-hidden="true"></i> Reporting
                </a>
            </div>

            <div class="bns-crm-stats">
                <a href="{{ route('crm.session', $sessionQuery(['status' => 'all'])) }}" class="bns-crm-stat{{ $status === 'all' ? ' is-active' : '' }}">
                    <span>Total</span>
                    <strong>{{ number_format($registered) }}</strong>
                </a>
                <a href="{{ route('crm.session', $sessionQuery(['status' => 'present'])) }}" class="bns-crm-stat bns-crm-stat--present{{ $status === 'present' ? ' is-active' : '' }}">
                    <span>Present</span>
                    <strong>{{ number_format($present) }}</strong>
                </a>
                <a href="{{ route('crm.session', $sessionQuery(['status' => 'absent'])) }}" class="bns-crm-stat bns-crm-stat--absent{{ $status === 'absent' ? ' is-active' : '' }}">
                    <span>Absent</span>
                    <strong>{{ number_format($absent) }}</strong>
                </a>
                <a href="{{ route('crm.session', $sessionQuery(['status' => 'paid'])) }}" class="bns-crm-stat bns-crm-stat--present{{ $status === 'paid' ? ' is-active' : '' }}">
                    <span>Payment done</span>
                    <strong>{{ number_format($paid ?? 0) }}</strong>
                </a>
                <a href="{{ route('crm.session', $sessionQuery(['call' => 'done'])) }}" class="bns-crm-stat bns-crm-stat--present{{ $call === 'done' ? ' is-active' : '' }}">
                    <span>Call done</span>
                    <strong>{{ number_format($callDoneCount ?? 0) }}</strong>
                </a>
                <a href="{{ route('crm.session', $sessionQuery(['call' => 'remain'])) }}" class="bns-crm-stat bns-crm-stat--spot{{ $call === 'remain' ? ' is-active' : '' }}">
                    <span>Remain</span>
                    <strong>{{ number_format($remainCount ?? 0) }}</strong>
                </a>
            </div>
            <p class="bns-crm-section-copy">Total = Present + Absent + Payment done. Call done + Remain = assigned calling list. Filter by team, calling, and call result together.</p>

            <form method="GET" action="{{ route('crm.session', $sessionNo) }}" class="bns-crm-filters">
                <div>
                    <label for="crmSessionSearch">Search</label>
                    <input
                        id="crmSessionSearch"
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Name, mobile, email, registration number"
                    >
                </div>
                <div>
                    <label for="crmSessionStatus">Status</label>
                    <select id="crmSessionStatus" name="status">
                        <option value="all" @selected($status === 'all')>All ({{ $presentRows->count() + $absentRows->count() + $paidRows->count() }})</option>
                        <option value="present" @selected($status === 'present')>Present ({{ $presentRows->count() }})</option>
                        <option value="absent" @selected($status === 'absent')>Absent ({{ $absentRows->count() }})</option>
                        <option value="paid" @selected($status === 'paid')>Payment done ({{ $paidRows->count() }})</option>
                    </select>
                </div>
                <div>
                    <label for="crmSessionTeam">Calling team</label>
                    <select id="crmSessionTeam" name="team">
                        <option value="">All calling team</option>
                        <option value="unassigned" @selected($team === 'unassigned')>Unassigned ({{ number_format($unassignedCount ?? 0) }})</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" @selected($team === (string) $employee->id)>
                                {{ $employee->name }} ({{ number_format($teamCounts[$employee->id] ?? 0) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="crmSessionCall">Calling</label>
                    <select id="crmSessionCall" name="call">
                        <option value="">All calling</option>
                        <option value="done" @selected($call === 'done')>Call done ({{ number_format($callDoneCount ?? 0) }})</option>
                        <option value="remain" @selected($call === 'remain')>Remain ({{ number_format($remainCount ?? 0) }})</option>
                    </select>
                </div>
                <div>
                    <label for="crmSessionCallStatus">Call result</label>
                    <select id="crmSessionCallStatus" name="call_status">
                        <option value="">All call results</option>
                        @foreach(($followupStatusOptions ?? []) as $value => $label)
                            <option value="{{ $value }}" @selected($callStatus === $value)>
                                {{ $label }} ({{ number_format($callStatusCounts[$value] ?? 0) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="bns-crm-filters__actions">
                    <button type="submit"><i class="fas fa-filter" aria-hidden="true"></i> Apply</button>
                    @if($search !== '' || $status !== 'all' || $team !== '' || $call !== '' || $callStatus !== '')
                        <a href="{{ route('crm.session', $sessionNo) }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            @if($status === 'all')
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>Present</h3>
                        <span>{{ number_format($presentRows->count()) }} {{ Str::plural('person', $presentRows->count()) }}</span>
                    </div>
                    @include('crm.partials.people-table', ['rows' => $presentRows, 'status' => 'present'])
                </section>
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>Absent</h3>
                        <span>{{ number_format($absentRows->count()) }} {{ Str::plural('person', $absentRows->count()) }}</span>
                    </div>
                    @include('crm.partials.people-table', ['rows' => $absentRows, 'status' => 'absent'])
                </section>
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>Payment done</h3>
                        <span>{{ number_format($paidRows->count()) }} {{ Str::plural('person', $paidRows->count()) }}</span>
                    </div>
                    @include('crm.partials.people-table', ['rows' => $paidRows, 'status' => 'paid'])
                </section>
            @else
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>{{ $status === 'present' ? 'Present' : ($status === 'absent' ? 'Absent' : 'Payment done') }}</h3>
                        <span>{{ number_format($listRows->count()) }} {{ Str::plural('person', $listRows->count()) }}@if($search !== '') matching “{{ $search }}”@endif</span>
                    </div>
                    @include('crm.partials.people-table', ['rows' => $listRows, 'status' => $status])
                </section>
            @endif
        </div>
    </section>
</div>
@include('crm.partials.remark-modal')
@endsection

@push('scripts')
@include('crm.partials.remark-scripts')
@endpush
