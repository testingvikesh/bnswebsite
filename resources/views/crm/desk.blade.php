@extends('layouts.front')

@section('title', 'CRM Sessions')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => 'Call List',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM'],
            ['label' => 'Sessions'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @include('crm.partials.toolbar', ['active' => 'desk', 'isAdmin' => false, 'employee' => $employee, 'sessionNo' => 0])

            @php
                $call = $call ?? '';
                $callStatus = $callStatus ?? '';
                $deskQuery = function (array $extra = []) use ($search, $status, $call, $callStatus) {
                    return array_filter([
                        'q' => array_key_exists('q', $extra) ? $extra['q'] : ($search !== '' ? $search : null),
                        'status' => array_key_exists('status', $extra) ? $extra['status'] : $status,
                        'call' => array_key_exists('call', $extra) ? $extra['call'] : ($call !== '' ? $call : null),
                        'call_status' => array_key_exists('call_status', $extra) ? $extra['call_status'] : ($callStatus !== '' ? $callStatus : null),
                    ], fn ($value) => $value !== '' && $value !== null && $value !== 'all');
                };
            @endphp

            <div class="bns-crm-stats">
                <a href="{{ route('crm.desk') }}" class="bns-crm-stat{{ $status === 'all' && $call === '' && $callStatus === '' && $search === '' ? ' is-active' : '' }}">
                    <span>Assigned</span>
                    <strong>{{ number_format($totals['assigned']) }}</strong>
                </a>
                <a href="{{ route('crm.desk', $deskQuery(['status' => 'present'])) }}" class="bns-crm-stat bns-crm-stat--present{{ $status === 'present' ? ' is-active' : '' }}">
                    <span>Present</span>
                    <strong>{{ number_format($totals['present']) }}</strong>
                </a>
                <a href="{{ route('crm.desk', $deskQuery(['status' => 'absent'])) }}" class="bns-crm-stat bns-crm-stat--absent{{ $status === 'absent' ? ' is-active' : '' }}">
                    <span>Absent</span>
                    <strong>{{ number_format($totals['absent']) }}</strong>
                </a>
                <a href="{{ route('crm.payments') }}" class="bns-crm-stat bns-crm-stat--present">
                    <span>Payment done</span>
                    <strong>{{ number_format($totals['paid'] ?? 0) }}</strong>
                </a>
                <a href="{{ route('crm.desk', $deskQuery(['call' => 'done'])) }}" class="bns-crm-stat bns-crm-stat--present{{ $call === 'done' ? ' is-active' : '' }}">
                    <span>Call done</span>
                    <strong>{{ number_format($totals['call_done'] ?? 0) }}</strong>
                </a>
                <a href="{{ route('crm.desk', $deskQuery(['call' => 'remain'])) }}" class="bns-crm-stat bns-crm-stat--spot{{ $call === 'remain' ? ' is-active' : '' }}">
                    <span>Remain</span>
                    <strong>{{ number_format($totals['remain'] ?? 0) }}</strong>
                </a>
            </div>
            <p class="bns-crm-section-copy">Open a session to see Present and Absent lists. Click a count box to filter all sessions by type.</p>

            <form method="GET" action="{{ route('crm.desk') }}" class="bns-crm-filters">
                <div>
                    <label for="crmDeskSearch">Search</label>
                    <input id="crmDeskSearch" type="search" name="q" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
                </div>
                <div>
                    <label for="crmDeskStatus">Status</label>
                    <select id="crmDeskStatus" name="status">
                        <option value="all" @selected($status === 'all')>All</option>
                        <option value="present" @selected($status === 'present')>Present</option>
                        <option value="absent" @selected($status === 'absent')>Absent</option>
                    </select>
                </div>
                <div>
                    <label for="crmDeskCall">Calling</label>
                    <select id="crmDeskCall" name="call">
                        <option value="">All calling</option>
                        <option value="done" @selected($call === 'done')>Call done ({{ number_format($totals['call_done'] ?? 0) }})</option>
                        <option value="remain" @selected($call === 'remain')>Remain ({{ number_format($totals['remain'] ?? 0) }})</option>
                    </select>
                </div>
                <div>
                    <label for="crmDeskCallStatus">Call result</label>
                    <select id="crmDeskCallStatus" name="call_status">
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
                        <a href="{{ route('crm.desk') }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            <h3 class="bns-crm-section-title">Your sessions</h3>
            <p class="bns-crm-section-copy">Click a session for Present and Absent member lists.</p>

            <div class="bns-crm-sessions">
                @foreach($sessions as $item)
                    <a href="{{ route('crm.desk.session', $item['number']) }}" class="bns-crm-session">
                        <span class="bns-crm-session__no">Session {{ $item['number'] }}</span>
                        <strong>{{ $item['title'] }}</strong>
                        <em>{{ $item['date'] !== '' ? $item['date'] : 'Date to be announced' }}@if($item['time'] !== '') · {{ $item['time'] }}@endif</em>
                        <div class="bns-crm-session__counts">
                            <span>Assigned {{ number_format($item['assigned']) }}</span>
                            <span class="is-present">Present {{ number_format($item['present']) }}</span>
                            <span class="is-absent">Absent {{ number_format($item['absent']) }}</span>
                            <span class="is-present">Call done {{ number_format($item['call_done']) }}</span>
                            <span>Remain {{ number_format($item['remain']) }}</span>
                        </div>
                        <div class="bns-crm-session__bar" aria-hidden="true">
                            <span style="width: {{ $item['present_pct'] }}%"></span>
                        </div>
                        <span class="bns-crm-session__cta">Open present / absent <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                    </a>
                @endforeach
            </div>

            @if($showGrouped)
                @forelse($grouped as $sessionNo => $rows)
                    @php
                        $presentGroup = $rows->where('attendance_status', 'present')->values();
                        $absentGroup = $rows->where('attendance_status', 'absent')->values();
                    @endphp
                    <h3 class="bns-crm-section-title">{{ bns_intro_session_label((int) $sessionNo) }}</h3>
                    @if($status !== 'absent')
                        <section class="bns-crm-list-card">
                            <div class="bns-crm-list-card__head">
                                <h3>Present</h3>
                                <span>{{ number_format($presentGroup->count()) }} {{ Str::plural('member', $presentGroup->count()) }}</span>
                            </div>
                            @include('crm.partials.desk-assignments-table', ['assignments' => $presentGroup])
                        </section>
                    @endif
                    @if($status !== 'present')
                        <section class="bns-crm-list-card">
                            <div class="bns-crm-list-card__head">
                                <h3>Absent</h3>
                                <span>{{ number_format($absentGroup->count()) }} {{ Str::plural('member', $absentGroup->count()) }}</span>
                            </div>
                            @include('crm.partials.desk-assignments-table', ['assignments' => $absentGroup])
                        </section>
                    @endif
                @empty
                    <section class="bns-crm-list-card">
                        <div class="bns-crm-table-wrap">
                            <p class="bns-crm-empty">No members match these filters.</p>
                        </div>
                    </section>
                @endforelse
            @endif
        </div>
    </section>
</div>

@include('crm.partials.remark-modal')
@endsection

@push('scripts')
@include('crm.partials.remark-scripts')
@endpush
