@extends('sop.layouts.app')

@section('title', 'Session Email Sending')
@section('page-title', 'Session Email Sending')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/intro-session-emails-admin.css') }}">
@endpush

@section('content')
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@php
    $sessionDate = $event['date'] ?? ('Session '.$session);
    $sessionTitle = $event['title'] ?? ('Introduction Session '.$session);
    $sessions = $sessions ?? bns_introduction_sessions();
    $listView = $listView ?? 'all';
    $emailTemplates = $emailTemplates ?? bns_message_email_templates();
    $selectedTemplate = $selectedTemplate ?? 'welcome-confirmation';
    $templateGroups = collect($emailTemplates)->groupBy('stage');
    $venueCard = $venueCard ?? bns_intro_session_venue_card($event ?? []);
    $address = $venueCard['address'] ?? [];
    $templatePreviews = $templatePreviews ?? [];
    $formSourceOptions = $formSourceOptions ?? config('reporting.form_sources', []);
    $programOptions = $programOptions ?? collect();
    $formSourceFilter = $formSourceFilter ?? '';
    $programFilter = $programFilter ?? '';
    $dateFrom = $dateFrom ?? '';
    $dateTo = $dateTo ?? '';
    $filterQuery = $filterQuery ?? array_filter([
        'session' => $session,
        'list' => $listView,
        'q' => $search !== '' ? $search : null,
        'form_source' => $formSourceFilter !== '' ? $formSourceFilter : null,
        'program' => $programFilter !== '' ? $programFilter : null,
        'date_from' => $dateFrom !== '' ? $dateFrom : null,
        'date_to' => $dateTo !== '' ? $dateTo : null,
        'template' => $selectedTemplate,
    ]);
    $activeFilterCount = collect([$search, $formSourceFilter, $programFilter, $dateFrom, $dateTo])
        ->filter(fn ($v) => filled($v))
        ->count();
@endphp

<div class="bns-ise">
<div class="row g-3 mb-4">
    @foreach($sessions as $sessionOption)
        @php $sessionNo = (int) ($sessionOption['session_number'] ?? 0); @endphp
        <div class="col-md-4 col-xl-2">
            <div class="sop-stat">
                <div class="sop-stat__icon bg-primary-subtle text-primary"><i class="bi bi-{{ $sessionNo }}-circle"></i></div>
                <div>
                    <div class="text-muted small">Session {{ $sessionNo }} (unique)</div>
                    <strong>{{ number_format($stats['session_'.$sessionNo] ?? 0) }}</strong>
                    <div class="small text-muted">{{ $sessionOption['date'] ?? '' }}</div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="col-md-4 col-xl-2">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-success-subtle text-success"><i class="bi bi-person-check"></i></div>
            <div><div class="text-muted small">Present (current)</div><strong>{{ number_format($stats['present'] ?? 0) }}</strong></div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-danger-subtle text-danger"><i class="bi bi-person-x"></i></div>
            <div><div class="text-muted small">Absent (current)</div><strong>{{ number_format($stats['absent'] ?? 0) }}</strong></div>
        </div>
    </div>
    <div class="col-md-4 col-xl-2">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-warning-subtle text-warning"><i class="bi bi-people"></i></div>
            <div><div class="text-muted small">Showing</div><strong>{{ number_format($stats['filtered']) }}</strong></div>
        </div>
    </div>
</div>

<div class="sop-card p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
        <div>
            <h5 class="mb-1 bns-ise-toolbar__title">Send Welcome + Session Email</h5>
            <p class="text-muted mb-0 small">
                Session-wise list for <strong>{{ $sessionDate }}</strong>.
                Totals: Registered {{ number_format($stats['registered'] ?? 0) }},
                Present {{ number_format($stats['present'] ?? 0) }},
                Absent {{ number_format($stats['absent'] ?? 0) }}.
            </p>
        </div>
        <div class="d-flex flex-wrap gap-2 align-items-center">
            <div class="btn-group">
                @foreach($sessions as $sessionOption)
                    @php $sessionNo = (int) ($sessionOption['session_number'] ?? 0); @endphp
                    <a href="{{ route('controlpanel.intro-session-emails.index', array_merge($filterQuery, ['session' => $sessionNo])) }}"
                       class="btn btn-sm {{ $session === $sessionNo ? 'btn-danger' : 'btn-outline-secondary' }}">
                        Session {{ $sessionNo }} · {{ \Illuminate\Support\Str::before($sessionOption['date'] ?? '', '(') ?: ('Session '.$sessionNo) }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="d-flex flex-wrap gap-2 align-items-center mb-3">
        <span class="small text-muted me-1">Attendance list:</span>
        <div class="btn-group">
            <a href="{{ route('controlpanel.intro-session-emails.index', array_merge($filterQuery, ['list' => 'all'])) }}"
               class="btn btn-sm {{ $listView === 'all' ? 'btn-dark' : 'btn-outline-secondary' }}">
                All Registered ({{ number_format($stats['registered'] ?? 0) }})
            </a>
            <a href="{{ route('controlpanel.intro-session-emails.index', array_merge($filterQuery, ['list' => 'present'])) }}"
               class="btn btn-sm {{ $listView === 'present' ? 'btn-success' : 'btn-outline-success' }}">
                Present ({{ number_format($stats['present'] ?? 0) }})
            </a>
            <a href="{{ route('controlpanel.intro-session-emails.index', array_merge($filterQuery, ['list' => 'absent'])) }}"
               class="btn btn-sm {{ $listView === 'absent' ? 'btn-danger' : 'btn-outline-danger' }}">
                Absent ({{ number_format($stats['absent'] ?? 0) }})
            </a>
        </div>
        <a href="{{ route('controlpanel.intro-session-emails.export', $filterQuery) }}"
           class="btn btn-sm btn-success">
            <i class="bi bi-file-earmark-excel"></i> Export {{ ucfirst($listView === 'all' ? 'Full' : $listView) }} List
        </a>
        @if($activeFilterCount > 0)
            <span class="badge text-bg-danger rounded-pill">{{ $activeFilterCount }} filter{{ $activeFilterCount > 1 ? 's' : '' }} active</span>
        @endif
    </div>

    <form method="GET" action="{{ route('controlpanel.intro-session-emails.index') }}" class="row g-2 align-items-end">
        <input type="hidden" name="session" value="{{ $session }}">
        <input type="hidden" name="list" value="{{ $listView }}">
        <input type="hidden" name="template" value="{{ $selectedTemplate }}">
        <div class="col-md-4">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Name, email, mobile, reference no, program">
        </div>
        <div class="col-md-2">
            <label class="form-label">Form Type</label>
            <select name="form_source" class="form-select">
                <option value="">All types</option>
                @foreach($formSourceOptions as $value => $label)
                    <option value="{{ $value }}" @selected($formSourceFilter === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Program</label>
            <select name="program" class="form-select">
                <option value="">All programs</option>
                @foreach($programOptions as $option)
                    <option value="{{ $option }}" @selected($programFilter === $option)>{{ $option }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">From Date</label>
            <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">To Date</label>
            <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-danger w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('controlpanel.intro-session-emails.index', ['session' => $session, 'list' => $listView, 'template' => $selectedTemplate]) }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<form method="POST" action="{{ route('controlpanel.intro-session-emails.send') }}" id="bnsIntroSessionEmailForm">
    @csrf
    <input type="hidden" name="session" value="{{ $session }}">
    <input type="hidden" name="list" value="{{ $listView }}">
    <input type="hidden" name="q" value="{{ $search }}">
    <input type="hidden" name="form_source" value="{{ $formSourceFilter }}">
    <input type="hidden" name="program" value="{{ $programFilter }}">
    <input type="hidden" name="date_from" value="{{ $dateFrom }}">
    <input type="hidden" name="date_to" value="{{ $dateTo }}">

    <div class="sop-card bns-ise-toolbar">
        <div class="p-3 border-bottom">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                <div>
                    <strong class="bns-ise-toolbar__title">{{ $sessionTitle }}</strong>
                    <div class="small text-muted">
                        {{ $sessionDate }} · {{ $event['time'] ?? '' }}
                        · Showing {{ ucfirst($listView === 'all' ? 'all registered' : $listView) }}
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('controlpanel.intro-session-emails.export', $filterQuery) }}"
                       class="btn btn-sm btn-success">
                        <i class="bi bi-file-earmark-excel"></i> Export to Excel
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="bnsSelectAllEmails">Select All</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="bnsUnselectAllEmails">Unselect All</button>
                    <button type="submit" class="btn btn-sm btn-danger" id="bnsSendSelectedEmails"
                            onclick="return confirm('Send the selected email template to checked participants?');">
                        <i class="bi bi-send"></i> Submit &amp; Send Mail
                    </button>
                </div>
            </div>

            <div class="row g-3 align-items-start">
                <div class="col-lg-6">
                    <label class="form-label mb-1" for="bnsEmailTemplateSelect">
                        Select Email Template <span class="text-danger">*</span>
                    </label>
                    <select name="template" id="bnsEmailTemplateSelect" class="form-select" required>
                        @php $templateNo = 0; @endphp
                        @foreach($templateGroups as $stage => $templates)
                            <optgroup label="{{ $stage }}">
                                @foreach($templates as $template)
                                    @php $templateNo++; @endphp
                                    <option value="{{ $template['id'] }}" @selected($selectedTemplate === $template['id'])>
                                        {{ $templateNo }}. {{ $template['title'] }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    <div class="form-text">
                        Choose any Communication Sequence template. Preview updates live — emailed layout matches the front message style.
                    </div>
                    <div class="small text-muted mt-2">
                        Total templates: <strong>{{ count($emailTemplates) }}</strong>
                        (Welcome + Stages 1–10)
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bns-ise-preview" id="bnsEmailTemplatePreview">
                        <div class="bns-ise-preview__head">
                            <div>
                                <span class="eyebrow" data-preview-stage>Template Preview</span>
                                <strong data-preview-title>Select a template</strong>
                            </div>
                            <span class="badge text-bg-light text-dark">Front-style email</span>
                        </div>
                        <div class="bns-ise-preview__body" data-preview-body>
                            <span class="bns-ise-preview__empty">Template content will appear here.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:48px">
                            <input type="checkbox" class="form-check-input" id="bnsEmailMasterCheck" title="Select all">
                        </th>
                        <th style="width:64px">Sr. No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Reference No.</th>
                        <th>Attendance</th>
                        <th>Source</th>
                        <th>Program</th>
                        <th>Registered</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $presentIds = collect($attendance['present_rows'] ?? [])->pluck('id')->map(fn ($id) => (int) $id)->all();
                    @endphp
                    @forelse($participants as $participant)
                        @php
                            $hasEmail = filled($participant->email) && filter_var($participant->email, FILTER_VALIDATE_EMAIL);
                            $isPresent = in_array((int) $participant->id, $presentIds, true);
                        @endphp
                        <tr class="{{ $hasEmail ? '' : 'table-warning' }}">
                            <td>
                                @if($hasEmail)
                                    <input
                                        type="checkbox"
                                        class="form-check-input bns-email-row-check"
                                        name="ids[]"
                                        value="{{ $participant->id }}"
                                    >
                                @else
                                    <span class="text-muted small">No email</span>
                                @endif
                            </td>
                            <td class="text-muted">{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $participant->full_name ?: '—' }}</td>
                            <td class="small">{{ $participant->email ?: '—' }}</td>
                            <td class="small">{{ $participant->mobile ?: '—' }}</td>
                            <td><code>{{ $participant->registration_number ?: '—' }}</code></td>
                            <td>
                                @if($isPresent)
                                    <span class="badge bg-success">Present</span>
                                @else
                                    <span class="badge bg-danger">Absent</span>
                                @endif
                            </td>
                            <td class="small">{{ $participant->formSourceLabel() }}</td>
                            <td class="small">{{ $participant->interested_program ?: '—' }}</td>
                            <td class="small text-muted">{{ $participant->created_at?->format('d M Y, h:i A') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">No participants found for this session list.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</form>

<div class="sop-card mt-4" id="send-mail-log">
    <div class="p-3 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <strong>Send Mail Log</strong>
            <div class="small text-muted">History of session welcome emails sent from this module.</div>
        </div>
        <form method="GET" action="{{ route('controlpanel.intro-session-emails.index') }}" class="d-flex flex-wrap gap-2 align-items-center">
            <input type="hidden" name="session" value="{{ $session }}">
            <input type="hidden" name="list" value="{{ $listView }}">
            <input type="hidden" name="template" value="{{ $selectedTemplate }}">
            @if($search !== '')
                <input type="hidden" name="q" value="{{ $search }}">
            @endif
            <select name="log_session" class="form-select form-select-sm" style="width:auto">
                <option value="">All sessions</option>
                @foreach($sessions as $sessionOption)
                    @php $sessionNo = (int) ($sessionOption['session_number'] ?? 0); @endphp
                    <option value="{{ $sessionNo }}" @selected((string) $logSession === (string) $sessionNo)>
                        Session {{ $sessionNo }}
                    </option>
                @endforeach
            </select>
            <select name="log_status" class="form-select form-select-sm" style="width:auto">
                <option value="">All status</option>
                <option value="sent" @selected((string) $logStatus === 'sent')>Sent</option>
                <option value="skipped" @selected((string) $logStatus === 'skipped')>Skipped</option>
                <option value="failed" @selected((string) $logStatus === 'failed')>Failed</option>
            </select>
            <button type="submit" class="btn btn-sm btn-outline-secondary">Filter Log</button>
            <a href="{{ route('controlpanel.intro-session-emails.index', ['session' => $session, 'q' => $search, 'list' => $listView, 'template' => $selectedTemplate]) }}"
               class="btn btn-sm btn-outline-secondary">Reset</a>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Sent At</th>
                    <th>Session</th>
                    <th>Template</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Reference No.</th>
                    <th>Status</th>
                    <th>Sent By</th>
                    <th>Remark</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                    <tr>
                        <td class="small text-muted">{{ $log->sent_at?->format('d M Y, h:i A') ?? '—' }}</td>
                        <td>{{ $log->sessionLabel() }}</td>
                        <td class="small">{{ $log->templateLabel() }}</td>
                        <td class="fw-semibold">{{ $log->full_name ?: '—' }}</td>
                        <td class="small">{{ $log->email ?: '—' }}</td>
                        <td><code>{{ $log->registration_number ?: '—' }}</code></td>
                        <td>
                            <span class="badge {{ $log->statusBadgeClass() }}">{{ $log->statusLabel() }}</span>
                        </td>
                        <td class="small">{{ $log->sender?->name ?: ($log->sender?->full_name ?? '—') }}</td>
                        <td class="small text-muted">{{ $log->error_message ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No send mail logs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
        <div class="p-3 border-top">
            {{ $logs->links() }}
        </div>
    @endif
</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('bnsIntroSessionEmailForm');
    if (!form) return;

    var master = document.getElementById('bnsEmailMasterCheck');
    var selectAllBtn = document.getElementById('bnsSelectAllEmails');
    var unselectAllBtn = document.getElementById('bnsUnselectAllEmails');
    var templateSelect = document.getElementById('bnsEmailTemplateSelect');
    var previews = @json($templatePreviews);
    var previewUrl = @json($previewUrl ?? '');
    var sessionNo = @json((int) $session);
    var stageEl = document.querySelector('[data-preview-stage]');
    var titleEl = document.querySelector('[data-preview-title]');
    var bodyEl = document.querySelector('[data-preview-body]');
    var previewCache = previews;

    function rowChecks() {
        return Array.prototype.slice.call(form.querySelectorAll('.bns-email-row-check'));
    }

    function setAll(checked) {
        rowChecks().forEach(function (el) {
            el.checked = checked;
        });
        if (master) {
            master.checked = checked;
            master.indeterminate = false;
        }
    }

    function syncMaster() {
        if (!master) return;
        var checks = rowChecks();
        var checked = checks.filter(function (el) { return el.checked; }).length;
        master.checked = checks.length > 0 && checked === checks.length;
        master.indeterminate = checked > 0 && checked < checks.length;
    }

    function paintPreview(item) {
        if (!bodyEl) return;
        if (!item) {
            if (stageEl) stageEl.textContent = 'Template Preview';
            if (titleEl) titleEl.textContent = 'Select a template';
            bodyEl.className = 'bns-ise-preview__body';
            bodyEl.innerHTML = '<span class="bns-ise-preview__empty">Template content will appear here.</span>';
            return;
        }
        if (stageEl) stageEl.textContent = item.stage || 'Template Preview';
        // Rich front-style bodies already include their own hero/title — avoid duplicating it in the preview chrome.
        if (titleEl) {
            titleEl.textContent = item.preview_html
                ? ''
                : (item.title || item.id || 'Template');
            titleEl.style.display = item.preview_html ? 'none' : '';
        }
        if (item.preview_html) {
            bodyEl.className = 'bns-ise-preview__body bns-ise-preview__rich';
            bodyEl.innerHTML = item.preview_html;
        } else if (item.preview) {
            bodyEl.className = 'bns-ise-preview__body bns-ise-preview__body--plain';
            bodyEl.textContent = item.preview;
        } else {
            bodyEl.className = 'bns-ise-preview__body';
            bodyEl.innerHTML = '<span class="bns-ise-preview__empty">Loading front-style preview…</span>';
        }
    }

    function updatePreview() {
        if (!templateSelect || !bodyEl) return;
        var id = templateSelect.value;
        var cached = previewCache[id];
        if (cached && cached.preview_html) {
            paintPreview(cached);
            return;
        }

        paintPreview({ stage: 'Loading', title: (cached && cached.title) || 'Loading preview…', preview_html: '' });

        if (!previewUrl) return;
        var url = previewUrl + (previewUrl.indexOf('?') >= 0 ? '&' : '?') + 'template=' + encodeURIComponent(id) + '&session=' + encodeURIComponent(sessionNo);
        fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) { return res.ok ? res.json() : Promise.reject(res); })
            .then(function (data) {
                previewCache[id] = data;
                if (templateSelect.value === id) {
                    paintPreview(data);
                }
            })
            .catch(function () {
                if (templateSelect.value === id) {
                    bodyEl.className = 'bns-ise-preview__body';
                    bodyEl.innerHTML = '<span class="bns-ise-preview__empty">Could not load preview for this template.</span>';
                }
            });
    }

    if (master) {
        master.addEventListener('change', function () {
            setAll(master.checked);
        });
    }

    form.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('bns-email-row-check')) {
            syncMaster();
        }
    });

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function () {
            setAll(true);
        });
    }

    if (unselectAllBtn) {
        unselectAllBtn.addEventListener('click', function () {
            setAll(false);
        });
    }

    if (templateSelect) {
        templateSelect.addEventListener('change', updatePreview);
        updatePreview();
    }

    syncMaster();
})();
</script>
@endpush
