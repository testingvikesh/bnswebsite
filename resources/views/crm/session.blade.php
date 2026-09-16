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

            <div class="bns-crm-session-head">
                <div>
                    <span class="bns-message-intro__label">Session {{ $sessionNo }}</span>
                    <h2>{{ $event['title'] ?? ('Introduction Session '.$sessionNo) }}</h2>
                    <p>
                        {{ $event['date'] ?? '' }}
                        @if(!empty($event['time'])) · {{ $event['time'] }}@endif
                    </p>
                </div>
            </div>

            <div class="bns-crm-stats">
                <a href="{{ route('crm.session', ['session' => $sessionNo, 'status' => 'all', 'q' => $search]) }}" class="bns-crm-stat{{ $status === 'all' ? ' is-active' : '' }}">
                    <span>Total</span>
                    <strong>{{ number_format($registered) }}</strong>
                </a>
                <a href="{{ route('crm.session', ['session' => $sessionNo, 'status' => 'present', 'q' => $search]) }}" class="bns-crm-stat bns-crm-stat--present{{ $status === 'present' ? ' is-active' : '' }}">
                    <span>Present</span>
                    <strong>{{ number_format($present) }}</strong>
                </a>
                <a href="{{ route('crm.session', ['session' => $sessionNo, 'status' => 'absent', 'q' => $search]) }}" class="bns-crm-stat bns-crm-stat--absent{{ $status === 'absent' ? ' is-active' : '' }}">
                    <span>Absent</span>
                    <strong>{{ number_format($absent) }}</strong>
                </a>
                <a href="{{ route('crm.session', ['session' => $sessionNo, 'status' => 'paid', 'q' => $search]) }}" class="bns-crm-stat bns-crm-stat--present{{ $status === 'paid' ? ' is-active' : '' }}">
                    <span>Payment done</span>
                    <strong>{{ number_format($paid ?? 0) }}</strong>
                </a>
            </div>
            <p class="bns-crm-section-copy">Total = Present + Absent + Payment done.</p>

            <form method="GET" action="{{ route('crm.session', $sessionNo) }}" class="bns-crm-search">
                <input type="hidden" name="status" value="{{ $status }}">
                <label for="crmSessionSearch">Search this session</label>
                <div class="bns-crm-search__row">
                    <input
                        id="crmSessionSearch"
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Name, mobile, email, registration number"
                    >
                    <button type="submit"><i class="fas fa-search" aria-hidden="true"></i> Search</button>
                    @if($search !== '' || $status !== 'all')
                        <a href="{{ route('crm.session', $sessionNo) }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            <div class="bns-crm-pills" role="tablist">
                <a href="{{ route('crm.session', ['session' => $sessionNo, 'status' => 'all', 'q' => $search]) }}" class="{{ $status === 'all' ? 'is-active' : '' }}">All ({{ $presentRows->count() + $absentRows->count() + $paidRows->count() }})</a>
                <a href="{{ route('crm.session', ['session' => $sessionNo, 'status' => 'present', 'q' => $search]) }}" class="{{ $status === 'present' ? 'is-active' : '' }}">Present ({{ $presentRows->count() }})</a>
                <a href="{{ route('crm.session', ['session' => $sessionNo, 'status' => 'absent', 'q' => $search]) }}" class="{{ $status === 'absent' ? 'is-active' : '' }}">Absent ({{ $absentRows->count() }})</a>
                <a href="{{ route('crm.session', ['session' => $sessionNo, 'status' => 'paid', 'q' => $search]) }}" class="{{ $status === 'paid' ? 'is-active' : '' }}">Payment done ({{ $paidRows->count() }})</a>
            </div>

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
@endsection
