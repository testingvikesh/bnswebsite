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
            ['label' => 'CRM', 'url' => route('crm.dashboard')],
            ['label' => 'Special Calling'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @include('crm.partials.toolbar', ['active' => 'special-calling', 'isAdmin' => true])

            @php
                $sessionFilter = (int) ($sessionFilter ?? 0);
                $search = $search ?? '';
                $queryKeep = array_filter([
                    'q' => $search !== '' ? $search : null,
                ]);
            @endphp

            <div class="bns-crm-stats">
                <a href="{{ route('crm.special-calling', $queryKeep) }}" class="bns-crm-stat bns-crm-stat--absent{{ $sessionFilter === 0 ? ' is-active' : '' }}">
                    <span>All sessions</span>
                    <strong>{{ number_format($allCount ?? $results->count()) }}</strong>
                </a>
                @foreach(($allowedSessions ?? bns_intro_session_allowed_numbers()) as $sessionNo)
                    <a href="{{ route('crm.special-calling', array_filter($queryKeep + ['session' => $sessionNo])) }}" class="bns-crm-stat bns-crm-stat--absent{{ $sessionFilter === (int) $sessionNo ? ' is-active' : '' }}">
                        <span>Session {{ $sessionNo }}</span>
                        <strong>{{ number_format($sessionTotals[$sessionNo] ?? 0) }}</strong>
                    </a>
                @endforeach
            </div>
            <p class="bns-crm-section-copy">Special Calling list = Absent + Not paid. Click a session to focus that list.</p>

            <form method="GET" action="{{ route('crm.special-calling') }}" class="bns-crm-filters">
                @if($sessionFilter > 0)
                    <input type="hidden" name="session" value="{{ $sessionFilter }}">
                @endif
                <div>
                    <label for="crmSpecialSearch">Search</label>
                    <input id="crmSpecialSearch" type="search" name="q" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
                </div>
                <div class="bns-crm-filters__actions">
                    <button type="submit"><i class="fas fa-search" aria-hidden="true"></i> Search</button>
                    @if($search !== '' || $sessionFilter > 0)
                        <a href="{{ route('crm.special-calling') }}" class="bns-crm-search__reset">Clear</a>
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
                    </h3>
                    <span>
                        {{ number_format($results->count()) }} {{ Str::plural('student', $results->count()) }}
                        · Absent · Not paid
                        @if($search !== '')
                            · matching “{{ $search }}”
                        @endif
                    </span>
                </div>
                <div class="bns-crm-table-wrap">
                    <table class="bns-crm-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Session</th>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Reg. No.</th>
                                <th>Program</th>
                                <th>Status</th>
                                <th>Calling team</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $index => $hit)
                                @php
                                    $item = $hit->inquiry;
                                    $assignmentKey = (int) $hit->session_number.'-'.(int) ($item->id ?? 0);
                                    $assignment = ($assignments ?? collect())->get($assignmentKey);
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('crm.session', ['session' => $hit->session_number, 'status' => 'absent']) }}">
                                            {{ bns_intro_session_label((int) $hit->session_number) }}
                                        </a>
                                    </td>
                                    <td><strong>{{ $item->full_name ?: '—' }}</strong></td>
                                    <td>
                                        <div>{{ $item->mobile ?: '—' }}</div>
                                        <div class="is-muted">{{ $item->email ?: '—' }}</div>
                                    </td>
                                    <td>{{ $item->registration_number ?: '—' }}</td>
                                    <td>{{ $item->interested_program ?: '—' }}</td>
                                    <td>
                                        <span class="bns-crm-badge bns-crm-badge--absent">Absent</span>
                                        <div class="is-muted">Not paid</div>
                                    </td>
                                    <td>
                                        @if($assignment)
                                            <strong>{{ $assignment->employee->name ?? '—' }}</strong>
                                            @if(!empty($assignment->employee?->facility))
                                                <div class="is-muted">{{ $assignment->employee->facility }}</div>
                                            @endif
                                            <div class="is-muted">{{ $assignment->lastCallStatusLabel() }}</div>
                                        @else
                                            <span class="is-muted">Unassigned</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="bns-crm-empty">No absent unpaid students found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </section>
</div>
@endsection
