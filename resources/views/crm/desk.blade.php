@extends('layouts.front')

@section('title', 'CRM Call List')

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
            ['label' => 'Call List'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @include('crm.partials.toolbar', ['active' => 'desk', 'isAdmin' => false, 'employee' => $employee])

            <div class="bns-crm-stats">
                <div class="bns-crm-stat">
                    <span>Assigned</span>
                    <strong>{{ number_format($totals['assigned']) }}</strong>
                </div>
                <div class="bns-crm-stat bns-crm-stat--present">
                    <span>Present</span>
                    <strong>{{ number_format($totals['present']) }}</strong>
                </div>
                <div class="bns-crm-stat bns-crm-stat--absent">
                    <span>Absent</span>
                    <strong>{{ number_format($totals['absent']) }}</strong>
                </div>
                <div class="bns-crm-stat">
                    <span>Follow-ups done</span>
                    <strong>{{ number_format($totals['followups_done']) }}</strong>
                </div>
            </div>

            <form method="GET" action="{{ route('crm.desk') }}" class="bns-crm-search">
                <input type="hidden" name="status" value="{{ $status }}">
                <label for="crmDeskSearch">Search assigned members</label>
                <div class="bns-crm-search__row">
                    <input id="crmDeskSearch" type="search" name="q" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
                    <button type="submit"><i class="fas fa-search" aria-hidden="true"></i> Search</button>
                    @if($search !== '' || $status !== 'all')
                        <a href="{{ route('crm.desk') }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            <div class="bns-crm-pills">
                <a href="{{ route('crm.desk', ['status' => 'all', 'q' => $search]) }}" class="{{ $status === 'all' ? 'is-active' : '' }}">All</a>
                <a href="{{ route('crm.desk', ['status' => 'present', 'q' => $search]) }}" class="{{ $status === 'present' ? 'is-active' : '' }}">Present</a>
                <a href="{{ route('crm.desk', ['status' => 'absent', 'q' => $search]) }}" class="{{ $status === 'absent' ? 'is-active' : '' }}">Absent</a>
            </div>

            <section class="bns-crm-list-card">
                <div class="bns-crm-list-card__head">
                    <h3>Call list</h3>
                    <span>{{ number_format($assignments->count()) }} {{ Str::plural('member', $assignments->count()) }}</span>
                </div>
                <div class="bns-crm-table-wrap">
                    <table class="bns-crm-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Session</th>
                                <th>Attendance</th>
                                <th>Follow-ups</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($assignments as $assignment)
                                @php($item = $assignment->inquiry)
                                <tr>
                                    <td><strong>{{ $item->full_name ?? '—' }}</strong></td>
                                    <td>
                                        <div>{{ $item->mobile ?? '—' }}</div>
                                        <div class="is-muted">{{ $item->email ?? '—' }}</div>
                                    </td>
                                    <td>Session {{ $assignment->session_number }}</td>
                                    <td>
                                        <span class="bns-crm-badge bns-crm-badge--{{ $assignment->attendance_status }}">
                                            {{ $assignment->attendance_status === 'present' ? 'Present' : 'Absent' }}
                                        </span>
                                    </td>
                                    <td>
                                        @for($n = 1; $n <= 3; $n++)
                                            @php($fu = $assignment->followup($n))
                                            <span class="bns-crm-dot {{ $fu && $fu->isDone() ? 'is-done' : '' }}" title="Follow-up {{ $n }}">{{ $n }}</span>
                                        @endfor
                                    </td>
                                    <td>
                                        <a href="{{ route('crm.desk.show', $assignment) }}" class="bns-crm-mini-btn bns-crm-mini-btn--primary">Call / Follow-up</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="bns-crm-empty">No members in the call list. Paid members are listed under Payment Done.</td>
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
