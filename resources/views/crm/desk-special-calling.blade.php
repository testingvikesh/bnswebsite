@extends('layouts.front')

@section('title', 'CRM Special Calling')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => 'Special Calling',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => route('crm.desk')],
            ['label' => 'Special Calling'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @if(session('status'))
                <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
            @endif

            @include('crm.partials.toolbar', [
                'active' => 'desk-special-calling',
                'isAdmin' => false,
                'employee' => $employee,
                'sessionNo' => 0,
            ])

            @php
                $sessionFilter = (int) ($sessionFilter ?? 0);
                $search = $search ?? '';
                $call = $call ?? '';
                $callStatus = $callStatus ?? '';
                $callRound = (int) ($callRound ?? 0);
                $followupStatusOptions = $followupStatusOptions ?? \App\Models\CrmFollowup::statusOptions();
                $queryKeep = array_filter([
                    'q' => $search !== '' ? $search : null,
                    'call' => $call !== '' ? $call : null,
                    'call_status' => $callStatus !== '' ? $callStatus : null,
                    'call_round' => $callRound > 0 ? $callRound : null,
                ]);
                $callKeep = array_filter([
                    'q' => $search !== '' ? $search : null,
                    'session' => $sessionFilter > 0 ? $sessionFilter : null,
                    'call_status' => $callStatus !== '' ? $callStatus : null,
                    'call_round' => $callRound > 0 ? $callRound : null,
                ]);
                $resultKeep = array_filter([
                    'q' => $search !== '' ? $search : null,
                    'session' => $sessionFilter > 0 ? $sessionFilter : null,
                    'call' => $call !== '' ? $call : null,
                    'call_round' => $callRound > 0 ? $callRound : null,
                ]);
                $roundKeep = array_filter([
                    'q' => $search !== '' ? $search : null,
                    'session' => $sessionFilter > 0 ? $sessionFilter : null,
                    'call' => $call !== '' ? $call : null,
                    'call_status' => $callStatus !== '' ? $callStatus : null,
                ]);
            @endphp

            <div class="bns-crm-stats">
                <a href="{{ route('crm.desk.special-calling', $queryKeep) }}" class="bns-crm-stat bns-crm-stat--absent{{ $sessionFilter === 0 ? ' is-active' : '' }}">
                    <span>All sessions</span>
                    <strong>{{ number_format($allCount ?? $results->count()) }}</strong>
                </a>
                @foreach(($allowedSessions ?? bns_intro_session_allowed_numbers()) as $sessionNo)
                    <a href="{{ route('crm.desk.special-calling', array_filter($queryKeep + ['session' => $sessionNo])) }}" class="bns-crm-stat bns-crm-stat--absent{{ $sessionFilter === (int) $sessionNo ? ' is-active' : '' }}">
                        <span>Session {{ $sessionNo }}</span>
                        <strong>{{ number_format($sessionTotals[$sessionNo] ?? 0) }}</strong>
                    </a>
                @endforeach
            </div>
            <p class="bns-crm-section-copy">Your Special Calling list = members assigned to you who are Absent + Not paid.</p>

            <div class="bns-crm-stats">
                <a href="{{ route('crm.desk.special-calling', $callKeep) }}" class="bns-crm-stat{{ $call === '' && $callStatus === '' && $callRound === 0 ? ' is-active' : '' }}">
                    <span>Total{{ $sessionFilter > 0 ? ' · S'.$sessionFilter : '' }}</span>
                    <strong>{{ number_format($scopedCount ?? $results->count()) }}</strong>
                </a>
                <a href="{{ route('crm.desk.special-calling', array_filter($callKeep + ['call' => 'done'])) }}" class="bns-crm-stat bns-crm-stat--present{{ $call === 'done' ? ' is-active' : '' }}">
                    <span>Call done</span>
                    <strong>{{ number_format($callDone ?? 0) }}</strong>
                </a>
                <a href="{{ route('crm.desk.special-calling', array_filter($callKeep + ['call' => 'remain'])) }}" class="bns-crm-stat bns-crm-stat--spot{{ $call === 'remain' ? ' is-active' : '' }}">
                    <span>Remain</span>
                    <strong>{{ number_format($remain ?? 0) }}</strong>
                </a>
            </div>

            @include('crm.partials.call-result-stats', [
                'routeName' => 'crm.desk.special-calling',
                'resultKeep' => $resultKeep,
                'roundKeep' => $roundKeep,
                'callStatus' => $callStatus,
                'callRound' => $callRound,
                'followupStatusOptions' => $followupStatusOptions,
                'followupNumbers' => $followupNumbers ?? \App\Models\CrmFollowup::numbers(),
                'callStatusCounts' => $callStatusCounts ?? [],
                'callRoundCounts' => $callRoundCounts ?? [],
                'callAverage' => $callAverage ?? 0,
            ])

            <form method="GET" action="{{ route('crm.desk.special-calling') }}" class="bns-crm-filters">
                @if($sessionFilter > 0)
                    <input type="hidden" name="session" value="{{ $sessionFilter }}">
                @endif
                @if($callRound > 0)
                    <input type="hidden" name="call_round" value="{{ $callRound }}">
                @endif
                <div>
                    <label for="crmDeskSpecialSearch">Search</label>
                    <input id="crmDeskSpecialSearch" type="search" name="q" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
                </div>
                <div>
                    <label for="crmDeskSpecialCall">Calling</label>
                    <select id="crmDeskSpecialCall" name="call">
                        <option value="">All calling</option>
                        <option value="done" @selected($call === 'done')>Call done ({{ number_format($callDone ?? 0) }})</option>
                        <option value="remain" @selected($call === 'remain')>Remain ({{ number_format($remain ?? 0) }})</option>
                    </select>
                </div>
                <div>
                    <label for="crmDeskSpecialCallStatus">Call result</label>
                    <select id="crmDeskSpecialCallStatus" name="call_status">
                        <option value="">All call results</option>
                        @foreach($followupStatusOptions as $value => $label)
                            <option value="{{ $value }}" @selected($callStatus === $value)>
                                {{ $label }} ({{ number_format($callStatusCounts[$value] ?? 0) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="bns-crm-filters__actions">
                    <button type="submit"><i class="fas fa-filter" aria-hidden="true"></i> Apply</button>
                    @if($search !== '' || $sessionFilter > 0 || $call !== '' || $callStatus !== '' || $callRound > 0)
                        <a href="{{ route('crm.desk.special-calling') }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            <section class="bns-crm-list-card">
                <div class="bns-crm-list-card__head">
                    <h3>
                        Special Calling
                        @if($sessionFilter > 0)
                            · {{ bns_intro_session_label($sessionFilter) }}
                        @endif
                        @if($call === 'done')
                            · Call done
                        @elseif($call === 'remain')
                            · Remain
                        @endif
                        @if($callStatus !== '')
                            · {{ $followupStatusOptions[$callStatus] ?? $callStatus }}
                        @endif
                        @if($callRound > 0)
                            · {{ \App\Models\CrmFollowup::ordinalLabel($callRound) }} done
                        @endif
                    </h3>
                    <span>
                        {{ number_format($results->count()) }} {{ Str::plural('student', $results->count()) }}
                        · Absent · Not paid
                        @if($search !== '')
                            · matching “{{ $search }}”
                        @endif
                    </span>
                </div>
                @include('crm.partials.desk-assignments-table', ['assignments' => $results])
            </section>
        </div>
    </section>
</div>

@include('crm.partials.remark-modal')
@endsection

@push('scripts')
@include('crm.partials.remark-scripts')
@endpush
