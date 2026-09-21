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
                <a href="{{ route('crm.payments') }}" class="bns-crm-stat bns-crm-stat--present">
                    <span>Payment done</span>
                    <strong>{{ number_format($totals['paid'] ?? 0) }}</strong>
                </a>
                <div class="bns-crm-stat">
                    <span>Follow-ups done</span>
                    <strong>{{ number_format($totals['followups_done']) }}</strong>
                </div>
            </div>

            <form method="GET" action="{{ route('crm.desk') }}" class="bns-crm-search">
                <input type="hidden" name="status" value="{{ $status }}">
                @if(($sessionFilter ?? 0) > 0)
                    <input type="hidden" name="session" value="{{ $sessionFilter }}">
                @endif
                <label for="crmDeskSearch">Search assigned members</label>
                <div class="bns-crm-search__row">
                    <input id="crmDeskSearch" type="search" name="q" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
                    <button type="submit"><i class="fas fa-search" aria-hidden="true"></i> Search</button>
                    @if($search !== '' || $status !== 'all' || (int) ($sessionFilter ?? 0) > 0)
                        <a href="{{ route('crm.desk') }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            <div class="bns-crm-pills">
                <a href="{{ route('crm.desk', array_filter(['status' => $status, 'q' => $search])) }}" class="{{ (int) ($sessionFilter ?? 0) === 0 ? 'is-active' : '' }}">All sessions</a>
                @foreach(($allowedSessions ?? bns_intro_session_allowed_numbers()) as $no)
                    <a href="{{ route('crm.desk', array_filter(['session' => $no, 'status' => $status, 'q' => $search])) }}" class="{{ (int) ($sessionFilter ?? 0) === (int) $no ? 'is-active' : '' }}">S{{ $no }}</a>
                @endforeach
            </div>

            <div class="bns-crm-pills">
                <a href="{{ route('crm.desk', array_filter(['session' => ($sessionFilter ?? 0) ?: null, 'status' => 'all', 'q' => $search])) }}" class="{{ $status === 'all' ? 'is-active' : '' }}">All</a>
                <a href="{{ route('crm.desk', array_filter(['session' => ($sessionFilter ?? 0) ?: null, 'status' => 'present', 'q' => $search])) }}" class="{{ $status === 'present' ? 'is-active' : '' }}">Present</a>
                <a href="{{ route('crm.desk', array_filter(['session' => ($sessionFilter ?? 0) ?: null, 'status' => 'absent', 'q' => $search])) }}" class="{{ $status === 'absent' ? 'is-active' : '' }}">Absent</a>
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
                                            >{{ $n }}</button>
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

<div class="modal fade bns-crm-remark-modal" id="crmRemarkModal" tabindex="-1" aria-labelledby="crmRemarkModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="bns-crm-remark-modal__eyebrow" id="crmRemarkModalFollowup">Follow-up</span>
                    <h5 class="modal-title" id="crmRemarkModalTitle">Remarks</h5>
                </div>
                @include('partials.modal-close-button', ['onLight' => true])
            </div>
            <div class="modal-body">
                <p class="bns-crm-remark-modal__meta" id="crmRemarkModalMeta"></p>
                <div class="bns-crm-remark-modal__note" id="crmRemarkModalNote"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('crmRemarkModal');
    if (!modalEl) {
        return;
    }

    function getModalInstance() {
        if (!window.bootstrap || !bootstrap.Modal) {
            return null;
        }
        if (typeof bootstrap.Modal.getOrCreateInstance === 'function') {
            return bootstrap.Modal.getOrCreateInstance(modalEl);
        }
        var existing = typeof bootstrap.Modal.getInstance === 'function'
            ? bootstrap.Modal.getInstance(modalEl)
            : null;
        return existing || new bootstrap.Modal(modalEl);
    }

    function showModal() {
        modalEl.classList.remove('bns-modal-is-closed');
        modalEl.style.removeProperty('display');
        var instance = getModalInstance();
        if (instance) {
            instance.show();
            return;
        }
        modalEl.classList.add('show');
        modalEl.style.display = 'block';
        modalEl.removeAttribute('aria-hidden');
        document.body.classList.add('modal-open');
    }

    document.querySelectorAll('.js-crm-remark').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var name = btn.getAttribute('data-name') || 'Member';
            var n = btn.getAttribute('data-followup') || '';
            var status = btn.getAttribute('data-status') || 'Not saved yet';
            var when = btn.getAttribute('data-when') || '';
            var note = (btn.getAttribute('data-note') || '').trim();

            document.getElementById('crmRemarkModalFollowup').textContent = 'Follow-up ' + n;
            document.getElementById('crmRemarkModalTitle').textContent = name;
            document.getElementById('crmRemarkModalMeta').textContent = when
                ? status + ' · ' + when
                : status;
            document.getElementById('crmRemarkModalNote').textContent = note !== ''
                ? note
                : 'No remark saved yet.';

            showModal();
        });
    });
});
</script>
@endpush
