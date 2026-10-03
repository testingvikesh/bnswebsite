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
        'title' => bns_intro_session_label($sessionNo),
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => route('crm.desk')],
            ['label' => 'Session '.$sessionNo],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @if(session('status'))
                <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
            @endif
            @include('crm.partials.toolbar', ['active' => 'desk-session', 'isAdmin' => false, 'employee' => $employee, 'sessionNo' => $sessionNo])

            @php
                $call = $call ?? '';
                $callStatus = $callStatus ?? '';
                $sessionQuery = function (array $extra = []) use ($sessionNo, $search, $status, $call, $callStatus) {
                    return array_filter([
                        'session' => $sessionNo,
                        'q' => array_key_exists('q', $extra) ? $extra['q'] : ($search !== '' ? $search : null),
                        'status' => array_key_exists('status', $extra) ? $extra['status'] : $status,
                        'call' => array_key_exists('call', $extra) ? $extra['call'] : ($call !== '' ? $call : null),
                        'call_status' => array_key_exists('call_status', $extra) ? $extra['call_status'] : ($callStatus !== '' ? $callStatus : null),
                    ], fn ($value) => $value !== '' && $value !== null && $value !== 'all');
                };
            @endphp

            <div class="bns-crm-session-head">
                <div>
                    <span class="bns-message-intro__label">Session {{ $sessionNo }}</span>
                    <h2>{{ $event['title'] ?? bns_intro_session_label($sessionNo) }}</h2>
                    <p>
                        {{ $event['date'] ?? '' }}
                        @if(!empty($event['time'])) · {{ $event['time'] }}@endif
                    </p>
                </div>
                <a href="{{ route('crm.desk') }}" class="bns-crm-mini-btn">All sessions</a>
            </div>

            <div class="bns-crm-stats">
                <a href="{{ route('crm.desk.session', $sessionNo) }}" class="bns-crm-stat{{ $status === 'all' && $call === '' && $callStatus === '' ? ' is-active' : '' }}">
                    <span>Assigned</span>
                    <strong>{{ number_format($totals['assigned']) }}</strong>
                </a>
                <a href="{{ route('crm.desk.session', $sessionQuery(['status' => 'present'])) }}" class="bns-crm-stat bns-crm-stat--present{{ $status === 'present' ? ' is-active' : '' }}">
                    <span>Present</span>
                    <strong>{{ number_format($totals['present']) }}</strong>
                </a>
                <a href="{{ route('crm.desk.session', $sessionQuery(['status' => 'absent'])) }}" class="bns-crm-stat bns-crm-stat--absent{{ $status === 'absent' ? ' is-active' : '' }}">
                    <span>Absent</span>
                    <strong>{{ number_format($totals['absent']) }}</strong>
                </a>
                <a href="{{ route('crm.payments') }}" class="bns-crm-stat bns-crm-stat--present">
                    <span>Payment done</span>
                    <strong>{{ number_format($totals['paid'] ?? 0) }}</strong>
                </a>
                <a href="{{ route('crm.desk.session', $sessionQuery(['call' => 'done'])) }}" class="bns-crm-stat bns-crm-stat--present{{ $call === 'done' ? ' is-active' : '' }}">
                    <span>Call done</span>
                    <strong>{{ number_format($totals['call_done'] ?? 0) }}</strong>
                </a>
                <a href="{{ route('crm.desk.session', $sessionQuery(['call' => 'remain'])) }}" class="bns-crm-stat bns-crm-stat--spot{{ $call === 'remain' ? ' is-active' : '' }}">
                    <span>Remain</span>
                    <strong>{{ number_format($totals['remain'] ?? 0) }}</strong>
                </a>
            </div>
            <p class="bns-crm-section-copy">Present and Absent lists are for members assigned to you in this session.</p>

            <form method="GET" action="{{ route('crm.desk.session', $sessionNo) }}" class="bns-crm-filters">
                <div>
                    <label for="crmDeskSessionSearch">Search</label>
                    <input id="crmDeskSessionSearch" type="search" name="q" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
                </div>
                <div>
                    <label for="crmDeskSessionStatus">Status</label>
                    <select id="crmDeskSessionStatus" name="status">
                        <option value="all" @selected($status === 'all')>All</option>
                        <option value="present" @selected($status === 'present')>Present</option>
                        <option value="absent" @selected($status === 'absent')>Absent</option>
                    </select>
                </div>
                <div>
                    <label for="crmDeskSessionCall">Calling</label>
                    <select id="crmDeskSessionCall" name="call">
                        <option value="">All calling</option>
                        <option value="done" @selected($call === 'done')>Call done ({{ number_format($totals['call_done'] ?? 0) }})</option>
                        <option value="remain" @selected($call === 'remain')>Remain ({{ number_format($totals['remain'] ?? 0) }})</option>
                    </select>
                </div>
                <div>
                    <label for="crmDeskSessionCallStatus">Call result</label>
                    <select id="crmDeskSessionCallStatus" name="call_status">
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
                    @if($search !== '' || $status !== 'all' || $call !== '' || $callStatus !== '')
                        <a href="{{ route('crm.desk.session', $sessionNo) }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            @if($status === 'all')
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>Present</h3>
                        <span>{{ number_format($presentRows->count()) }} {{ Str::plural('member', $presentRows->count()) }}</span>
                    </div>
                    @include('crm.partials.desk-assignments-table', ['assignments' => $presentRows])
                </section>
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>Absent</h3>
                        <span>{{ number_format($absentRows->count()) }} {{ Str::plural('member', $absentRows->count()) }}</span>
                    </div>
                    @include('crm.partials.desk-assignments-table', ['assignments' => $absentRows])
                </section>
            @else
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>{{ $status === 'present' ? 'Present' : 'Absent' }}</h3>
                        <span>{{ number_format(($status === 'present' ? $presentRows : $absentRows)->count()) }} {{ Str::plural('member', ($status === 'present' ? $presentRows : $absentRows)->count()) }}</span>
                    </div>
                    @include('crm.partials.desk-assignments-table', ['assignments' => $status === 'present' ? $presentRows : $absentRows])
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
