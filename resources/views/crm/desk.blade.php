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

            @php
                $call = $call ?? '';
                $callStatus = $callStatus ?? '';
                $deskQuery = function (array $extra = []) use ($search, $status, $sessionFilter, $call, $callStatus) {
                    return array_filter([
                        'q' => array_key_exists('q', $extra) ? $extra['q'] : ($search !== '' ? $search : null),
                        'status' => array_key_exists('status', $extra) ? $extra['status'] : $status,
                        'session' => array_key_exists('session', $extra) ? $extra['session'] : (($sessionFilter ?? 0) ?: null),
                        'call' => array_key_exists('call', $extra) ? $extra['call'] : ($call !== '' ? $call : null),
                        'call_status' => array_key_exists('call_status', $extra) ? $extra['call_status'] : ($callStatus !== '' ? $callStatus : null),
                    ], fn ($value) => $value !== '' && $value !== null && $value !== 'all');
                };
            @endphp

            <div class="bns-crm-stats">
                <a href="{{ route('crm.desk', $deskQuery(['call' => '', 'call_status' => '', 'status' => 'all'])) }}" class="bns-crm-stat{{ $status === 'all' && $call === '' && $callStatus === '' ? ' is-active' : '' }}">
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

            <form method="GET" action="{{ route('crm.desk') }}" class="bns-crm-filters">
                <div>
                    <label for="crmDeskSearch">Search</label>
                    <input id="crmDeskSearch" type="search" name="q" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
                </div>
                <div>
                    <label for="crmDeskSession">Session</label>
                    <select id="crmDeskSession" name="session">
                        <option value="">All sessions</option>
                        @foreach(($allowedSessions ?? bns_intro_session_allowed_numbers()) as $no)
                            <option value="{{ $no }}" @selected((int) ($sessionFilter ?? 0) === (int) $no)>S{{ $no }}</option>
                        @endforeach
                    </select>
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
                    @if($search !== '' || $status !== 'all' || (int) ($sessionFilter ?? 0) > 0 || $call !== '' || $callStatus !== '')
                        <a href="{{ route('crm.desk') }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

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
                                    <td>{{ bns_intro_session_label((int) $assignment->session_number) }}</td>
                                    <td>
                                        <span class="bns-crm-badge bns-crm-badge--{{ $assignment->attendance_status }}">
                                            {{ $assignment->attendance_status === 'present' ? 'Present' : 'Absent' }}
                                        </span>
                                    </td>
                                    <td>
                                        @for($n = 1; $n <= 3; $n++)
                                            @php($fu = $assignment->followup($n))
                                            <button
                                                type="button"
                                                class="bns-crm-dot {{ $fu && $fu->isDone() ? 'is-done' : '' }} js-crm-remark"
                                                title="View follow-up {{ $n }} remark"
                                                data-name="{{ $item->full_name ?? 'Member' }}"
                                                data-followup="{{ $n }}"
                                                data-status="{{ $fu ? $fu->statusLabel() : 'Not saved yet' }}"
                                                data-when="{{ $fu && $fu->called_at ? $fu->called_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '' }}"
                                                data-note="{{ $fu && filled($fu->note) ? $fu->note : '' }}"
                                                data-status-value="{{ $fu?->status ?? 'pending' }}"
                                                data-save-url="{{ route('crm.desk.followup', ['assignment' => $assignment->id, 'followup' => $n]) }}"
                                            >{{ $n }}</button>
                                        @endfor
                                        <div class="is-muted">{{ $assignment->lastCallStatusLabel() }}</div>
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

@include('crm.partials.remark-modal')
@endsection

@push('scripts')
@include('crm.partials.remark-scripts')
@endpush
