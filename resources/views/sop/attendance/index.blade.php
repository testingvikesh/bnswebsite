@extends('sop.layouts.app')

@section('title', 'Attendance Module')
@section('page-title', 'Attendance Module')

@section('content')
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif

@php
    $sessionDate = $event['date'] ?? ('Session '.$session);
    $sessionTitle = $event['title'] ?? ('Introduction Session '.$session);
    $filterQuery = array_filter([
        'session' => $session,
        'list' => $list,
        'search' => $search !== '' ? $search : null,
    ]);
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-primary-subtle text-primary"><i class="bi bi-people"></i></div>
            <div>
                <div class="text-muted small">Registered</div>
                <strong>{{ number_format($stats['registered']) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-success-subtle text-success"><i class="bi bi-person-check"></i></div>
            <div>
                <div class="text-muted small">Present</div>
                <strong>{{ number_format($stats['present']) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-danger-subtle text-danger"><i class="bi bi-person-x"></i></div>
            <div>
                <div class="text-muted small">Absent</div>
                <strong>{{ number_format($stats['absent']) }}</strong>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-warning-subtle text-warning"><i class="bi bi-envelope-check"></i></div>
            <div>
                <div class="text-muted small">With Email (list)</div>
                <strong>{{ number_format($stats['with_email']) }}</strong>
            </div>
        </div>
    </div>
</div>

<div class="sop-card p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div>
            <h5 class="mb-1">Session-wise Present / Absent + QR Mail</h5>
            <p class="text-muted mb-0 small">
                Send QR invite mail, then a <strong>volunteer at venue</strong> scans the QR, verifies the person, and taps Approve to mark Present.
                Current: <strong>{{ $sessionTitle }}</strong> · {{ $sessionDate }} · {{ $event['time'] ?? '' }}
            </p>
        </div>
        <div class="btn-group flex-wrap">
            @foreach($allowedSessions as $sessionNo)
                <a href="{{ route('controlpanel.attendance.index', array_merge($filterQuery, ['session' => $sessionNo])) }}"
                   class="btn btn-sm {{ $session === (int) $sessionNo ? 'btn-danger' : 'btn-outline-secondary' }}">
                    Session {{ $sessionNo }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <span class="small text-muted me-1">List:</span>
        <div class="btn-group">
            <a href="{{ route('controlpanel.attendance.index', array_merge($filterQuery, ['list' => 'all'])) }}"
               class="btn btn-sm {{ $list === 'all' ? 'btn-dark' : 'btn-outline-secondary' }}">
                All ({{ number_format($stats['registered']) }})
            </a>
            <a href="{{ route('controlpanel.attendance.index', array_merge($filterQuery, ['list' => 'present'])) }}"
               class="btn btn-sm {{ $list === 'present' ? 'btn-success' : 'btn-outline-success' }}">
                Present ({{ number_format($stats['present']) }})
            </a>
            <a href="{{ route('controlpanel.attendance.index', array_merge($filterQuery, ['list' => 'absent'])) }}"
               class="btn btn-sm {{ $list === 'absent' ? 'btn-danger' : 'btn-outline-danger' }}">
                Absent ({{ number_format($stats['absent']) }})
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('controlpanel.attendance.index') }}" class="row g-2 align-items-end">
        <input type="hidden" name="session" value="{{ $session }}">
        <input type="hidden" name="list" value="{{ $list }}">
        <div class="col-md-8">
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Name, email, mobile, registration no.">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-danger w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('controlpanel.attendance.index', ['session' => $session, 'list' => $list]) }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>
</div>

{{-- Bulk form fields (checkboxes use form= attribute so row action forms stay valid) --}}
<form method="POST" id="attendanceBulkForm">
    @csrf
    <input type="hidden" name="session" value="{{ $session }}">
</form>

<div class="sop-card">
    <div class="p-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <strong>Participants</strong>
            <div class="small text-muted">Showing {{ number_format($stats['filtered']) }} · Tick rows then use bulk actions</div>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="submit"
                    form="attendanceBulkForm"
                    formaction="{{ route('controlpanel.attendance.send-mail') }}"
                    class="btn btn-sm btn-primary"
                    onclick="return confirm('Send Attendance QR invite mail to selected participants?');">
                <i class="bi bi-qr-code"></i> Send QR Mail
            </button>
            <button type="submit"
                    form="attendanceBulkForm"
                    formaction="{{ route('controlpanel.attendance.bulk-mark') }}"
                    name="action" value="present"
                    class="btn btn-sm btn-success"
                    onclick="return confirm('Mark selected as Present?');">
                <i class="bi bi-check2-circle"></i> Mark Present
            </button>
            <button type="submit"
                    form="attendanceBulkForm"
                    formaction="{{ route('controlpanel.attendance.bulk-mark') }}"
                    name="action" value="absent"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Mark selected as Absent?');">
                <i class="bi bi-x-circle"></i> Mark Absent
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width:40px;">
                        <input type="checkbox" class="form-check-input" id="attendanceSelectAll" title="Select all">
                    </th>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Reg. No.</th>
                    <th>Status</th>
                    <th>QR Invite</th>
                    <th style="min-width:200px;">Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse($participants as $row)
                @php
                    $isPresent = in_array($row->id, $presentIds, true);
                    $invite = $invites->get($row->id);
                @endphp
                <tr>
                    <td>
                        <input type="checkbox" form="attendanceBulkForm" class="form-check-input attendance-row-check" name="inquiry_ids[]" value="{{ $row->id }}">
                    </td>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        <strong>{{ $row->full_name }}</strong>
                        @if($row->interested_program || $row->category)
                            <div class="small text-muted">{{ $row->interested_program ?: $row->category }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="small">{{ $row->email ?: '—' }}</div>
                        <div class="small text-muted">{{ $row->mobile ?: '' }}</div>
                    </td>
                    <td><code>{{ $row->registration_number ?: '—' }}</code></td>
                    <td>
                        @if($isPresent)
                            <span class="badge text-bg-success">Present</span>
                        @else
                            <span class="badge text-bg-secondary">Absent</span>
                        @endif
                    </td>
                    <td>
                        @if($invite)
                            <span class="badge {{ $invite->status === 'approved' ? 'text-bg-success' : ($invite->status === 'revoked' ? 'text-bg-dark' : 'text-bg-warning') }}">
                                {{ $invite->statusLabel() }}
                            </span>
                            @if($invite->invite_sent_at)
                                <div class="small text-muted mt-1">Sent {{ $invite->invite_sent_at->format('d M, H:i') }}</div>
                            @endif
                        @else
                            <span class="text-muted small">Not sent</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @if(! $isPresent)
                                <form method="POST" action="{{ route('controlpanel.attendance.mark') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="session" value="{{ $session }}">
                                    <input type="hidden" name="inquiry_id" value="{{ $row->id }}">
                                    <input type="hidden" name="action" value="present">
                                    <button type="submit" class="btn btn-sm btn-success">Present</button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('controlpanel.attendance.mark') }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="session" value="{{ $session }}">
                                    <input type="hidden" name="inquiry_id" value="{{ $row->id }}">
                                    <input type="hidden" name="action" value="absent">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Absent</button>
                                </form>
                            @endif
                            @if(filled($row->email))
                                <form method="POST" action="{{ route('controlpanel.attendance.send-mail') }}" class="d-inline"
                                      onsubmit="return confirm('Send QR mail to {{ e($row->email) }}?');">
                                    @csrf
                                    <input type="hidden" name="session" value="{{ $session }}">
                                    <input type="hidden" name="inquiry_ids[]" value="{{ $row->id }}">
                                    <button type="submit" class="btn btn-sm btn-outline-primary">Send QR</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">No participants found for this filter.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('attendanceSelectAll')?.addEventListener('change', function () {
    document.querySelectorAll('.attendance-row-check').forEach((el) => {
        el.checked = this.checked;
    });
});
</script>
@endpush
