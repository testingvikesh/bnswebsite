@extends('layouts.front')

@section('title', 'CRM Today Attendance')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => ($isToday ?? true) ? 'Today attendance' : 'Attendance · '.$dateLabel,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => route('crm.dashboard')],
            ['label' => ($isToday ?? true) ? 'Today attendance' : 'Attendance'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @php
                $isToday = $isToday ?? true;
                $dateValue = $dateValue ?? now('Asia/Kolkata')->toDateString();
                $todayValue = $todayValue ?? $dateValue;
                $dateParam = $isToday ? null : $dateValue;
                $keepQuery = array_filter([
                    'date' => $dateParam,
                    'q' => $search !== '' ? $search : null,
                ]);
                $dayWord = $isToday ? 'today' : $dateLabel;
            @endphp
            @include('crm.partials.toolbar', ['active' => 'today-attendance', 'isAdmin' => true])

            <div class="bns-crm-stats">
                <a href="{{ route('crm.today-attendance', $keepQuery) }}" class="bns-crm-stat bns-crm-stat--present{{ ($view ?? 'all') !== 'spot' && ($sessionFilter ?? 0) === 0 ? ' is-active' : '' }}">
                    <span>{{ $isToday ? 'Today attendance' : 'Attendance' }}</span>
                    <strong>{{ number_format($total) }}</strong>
                </a>
                @foreach(($allowedSessions ?? bns_intro_session_allowed_numbers()) as $sessionNo)
                    <a href="{{ route('crm.today-attendance', array_filter(array_merge($keepQuery, ['session' => $sessionNo]))) }}" class="bns-crm-stat bns-crm-stat--present{{ ($view ?? '') !== 'spot' && ($sessionFilter ?? 0) === (int) $sessionNo ? ' is-active' : '' }}">
                        <span>Session {{ $sessionNo }}</span>
                        <strong>{{ number_format($sessionTotals[$sessionNo] ?? 0) }}</strong>
                    </a>
                @endforeach
                <a href="{{ route('crm.today-attendance', array_filter(array_merge($keepQuery, ['view' => 'spot']))) }}" class="bns-crm-stat bns-crm-stat--spot{{ ($view ?? '') === 'spot' ? ' is-active' : '' }}">
                    <span>Spot admission</span>
                    <strong>{{ number_format($spotTotal ?? 0) }}</strong>
                </a>
            </div>
            <p class="bns-crm-section-copy">Session-wise attendance for <strong>{{ $dateLabel }}</strong>. Change the date to see another day. Spot admission = entered, but no matching registered member.</p>

            <form method="GET" action="{{ route('crm.today-attendance') }}" class="bns-crm-search">
                @if(($view ?? '') === 'spot')
                    <input type="hidden" name="view" value="spot">
                @elseif(($sessionFilter ?? 0) > 0)
                    <input type="hidden" name="session" value="{{ $sessionFilter }}">
                @endif
                <label for="crmAttendanceDate">Attendance date</label>
                <div class="bns-crm-search__row">
                    <input
                        id="crmAttendanceDate"
                        type="date"
                        name="date"
                        class="bns-crm-search__date"
                        value="{{ $dateValue }}"
                        max="{{ $todayValue }}"
                        onchange="this.form.submit()"
                    >
                    @unless($isToday)
                        <a href="{{ route('crm.today-attendance', array_filter(['view' => ($view ?? '') === 'spot' ? 'spot' : null, 'session' => ($sessionFilter ?? 0) > 0 ? $sessionFilter : null, 'q' => $search !== '' ? $search : null])) }}" class="bns-crm-search__reset">Today</a>
                    @endunless
                </div>
                <label for="crmTodaySearch">Search attendance</label>
                <div class="bns-crm-search__row">
                    <input
                        id="crmTodaySearch"
                        type="search"
                        name="q"
                        value="{{ $search }}"
                        placeholder="Name, mobile, email, registration number"
                    >
                    <button type="submit"><i class="fas fa-search" aria-hidden="true"></i> Search</button>
                    @if($search !== '')
                        <a href="{{ route('crm.today-attendance', array_filter(['date' => $dateParam, 'view' => ($view ?? '') === 'spot' ? 'spot' : null, 'session' => ($sessionFilter ?? 0) > 0 ? $sessionFilter : null])) }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            @if(($view ?? 'all') === 'spot')
                <section class="bns-crm-list-card" id="spot-admission">
                    <div class="bns-crm-list-card__head">
                        <h3>Spot admission</h3>
                        <span>{{ number_format($spots->count()) }} {{ Str::plural('person', $spots->count()) }} unmatched {{ $dayWord }}</span>
                    </div>
                    <div class="bns-crm-table-wrap">
                        <table class="bns-crm-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Reg. No.</th>
                                    <th>Facility</th>
                                    <th>Time</th>
                                    <th>Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($spots as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $row->full_name ?: '—' }}</strong></td>
                                        <td>{{ $row->mobile ?: '—' }}</td>
                                        <td>{{ $row->email ?: '—' }}</td>
                                        <td>{{ $row->register_no ?: '—' }}</td>
                                        <td>{{ $row->facility_name ?: '—' }}</td>
                                        <td>{{ $row->attended_at?->timezone('Asia/Kolkata')->format('h:i A') ?: '—' }}</td>
                                        <td>{{ $row->reason ?: 'No matching member' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="bns-crm-empty">No unmatched attendance {{ $isToday ? 'today' : 'on '.$dateLabel }}.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            @else
            @forelse($groups as $sessionNo => $rows)
                @php
                    $event = bns_introduction_session((int) $sessionNo) ?? [];
                @endphp
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>
                            Session {{ $sessionNo }}
                            @if(!empty($event['title']))
                                · {{ $event['title'] }}
                            @endif
                        </h3>
                        <span>{{ number_format($rows->count()) }} {{ Str::plural('person', $rows->count()) }} present {{ $isToday ? 'today' : 'on '.$dateLabel }}</span>
                    </div>
                    <div class="bns-crm-table-wrap">
                        <table class="bns-crm-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Reg. No.</th>
                                    <th>Program</th>
                                    <th>Time</th>
                                    <th>Via</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $row->full_name ?: ($row->inquiry->full_name ?? '—') }}</strong></td>
                                        <td>
                                            <div>{{ $row->mobile ?: ($row->inquiry->mobile ?? '—') }}</div>
                                            <div class="is-muted">{{ $row->email ?: ($row->inquiry->email ?? '—') }}</div>
                                        </td>
                                        <td>{{ $row->registration_number ?: ($row->inquiry->registration_number ?? '—') }}</td>
                                        <td>{{ $row->program ?: ($row->inquiry->interested_program ?? '—') }}</td>
                                        <td>{{ $row->attended_at?->timezone('Asia/Kolkata')->format('h:i A') ?: '—' }}</td>
                                        <td>
                                            <span class="bns-crm-badge bns-crm-badge--present">{{ ucfirst((string) $row->marked_via) ?: 'Present' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="bns-crm-section-copy">
                        <a href="{{ route('crm.session', (int) $sessionNo) }}">Open session {{ $sessionNo }} present / absent</a>
                    </p>
                </section>
            @empty
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>No attendance {{ $isToday ? 'today' : 'on '.$dateLabel }}</h3>
                        <span>{{ $dateLabel }}</span>
                    </div>
                    <p class="bns-crm-empty" style="padding: 18px;">No one has been marked present {{ $isToday ? 'today' : 'on '.$dateLabel }} yet.</p>
                </section>
            @endforelse
            @endif
        </div>
    </section>
</div>
@endsection
