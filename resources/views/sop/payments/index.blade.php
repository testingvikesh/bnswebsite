@extends('sop.layouts.app')

@section('title', 'Payment Reports')
@section('page-title', 'Payment Reports')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/intro-session-emails-admin.css') }}">
@endpush

@section('content')
@php
    $emailTemplates = $emailTemplates ?? bns_message_email_templates();
    $selectedTemplate = $selectedTemplate ?? 'welcome-confirmation';
    $templateGroups = collect($emailTemplates)->groupBy('stage');
    $templatePreviews = $templatePreviews ?? [];
    $formTypeOptions = $formTypeOptions ?? [];
    $sessions = $sessions ?? bns_introduction_sessions();
    $allowedSessions = $allowedSessions ?? bns_intro_session_allowed_numbers();
    $previewUrl = $previewUrl ?? route('controlpanel.payments.email-preview');
    $sessionFilter = $sessionFilter ?? '';
@endphp

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

<div class="row g-3 mb-4">
    <div class="col-md-3 col-xl">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-primary-subtle text-primary"><i class="bi bi-receipt"></i></div>
            <div><div class="text-muted small">Total Payments</div><strong>{{ $stats['total'] }}</strong></div>
        </div>
    </div>
    <div class="col-md-3 col-xl">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-success-subtle text-success"><i class="bi bi-check-circle"></i></div>
            <div><div class="text-muted small">Successful</div><strong>{{ $stats['success'] }}</strong></div>
        </div>
    </div>
    <div class="col-md-3 col-xl">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="text-muted small">Pending / Initiated</div><strong>{{ $stats['pending'] }}</strong></div>
        </div>
    </div>
    <div class="col-md-3 col-xl">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-danger-subtle text-danger"><i class="bi bi-currency-rupee"></i></div>
            <div><div class="text-muted small">Collected</div><strong>₹ {{ number_format((float) $stats['amount_collected'], 2) }}</strong></div>
        </div>
    </div>
    <div class="col-md-3 col-xl">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-info-subtle text-info"><i class="bi bi-funnel"></i></div>
            <div><div class="text-muted small">Filtered / With Email</div><strong>{{ number_format($stats['filtered'] ?? 0) }} / {{ number_format($stats['with_email'] ?? 0) }}</strong></div>
        </div>
    </div>
</div>

<div class="sop-card p-4 mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h5 class="mb-1">Filter Payments</h5>
            <p class="text-muted small mb-0">Filter by status, program type, and introduction session — then select rows and send mail.</p>
        </div>
        <div class="btn-group">
            @foreach($allowedSessions as $sessionNo)
                @php
                    $sessionOption = collect($sessions)->firstWhere('session_number', $sessionNo);
                @endphp
                <a href="{{ route('controlpanel.payments.index', array_filter(['session' => $sessionNo, 'q' => $search ?: null, 'status' => $statusFilter ?: null, 'form_type' => $formTypeFilter ?: null, 'template' => $selectedTemplate])) }}"
                   class="btn btn-sm {{ (string) $sessionFilter === (string) $sessionNo ? 'btn-danger' : 'btn-outline-secondary' }}">
                    Session {{ $sessionNo }}
                    @if(!empty($sessionOption['date']))
                        · {{ \Illuminate\Support\Str::before($sessionOption['date'], '(') }}
                    @endif
                </a>
            @endforeach
            <a href="{{ route('controlpanel.payments.index', array_filter(['q' => $search ?: null, 'status' => $statusFilter ?: null, 'form_type' => $formTypeFilter ?: null, 'template' => $selectedTemplate])) }}"
               class="btn btn-sm {{ $sessionFilter === '' || $sessionFilter === null ? 'btn-dark' : 'btn-outline-secondary' }}">
                All Sessions
            </a>
        </div>
    </div>

    <form method="GET" class="row g-2 align-items-end">
        <input type="hidden" name="template" value="{{ $selectedTemplate }}">
        <div class="col-md-3">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Registration no, txn no, name, email, payment ID">
        </div>
        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                @foreach($statusOptions as $option)
                    <option value="{{ $option }}" @selected($statusFilter === $option)>{{ ucfirst($option) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Program Type</label>
            <select name="form_type" class="form-select">
                <option value="">All types</option>
                @foreach($formTypeOptions as $type => $meta)
                    <option value="{{ $type }}" @selected($formTypeFilter === $type)>{{ $meta['label'] ?? $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Session</label>
            <select name="session" class="form-select">
                <option value="">All sessions</option>
                @foreach($allowedSessions as $sessionNo)
                    <option value="{{ $sessionNo }}" @selected((string) $sessionFilter === (string) $sessionNo)>Session {{ $sessionNo }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-danger w-100">Filter</button>
        </div>
        <div class="col-md-1">
            <a href="{{ route('controlpanel.payments.index', ['template' => $selectedTemplate]) }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<form method="POST" action="{{ route('controlpanel.payments.send-mail') }}" id="bnsPaymentEmailForm">
    @csrf
    <input type="hidden" name="q" value="{{ $search }}">
    <input type="hidden" name="status" value="{{ $statusFilter }}">
    <input type="hidden" name="form_type" value="{{ $formTypeFilter }}">
    <input type="hidden" name="session" value="{{ $sessionFilter }}">

    <div class="sop-card mb-4">
        <div class="p-3 border-bottom">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                <div>
                    <strong>Select Template &amp; Send Mail</strong>
                    <div class="small text-muted">Same mail templates as Session Email Sending. Select payment rows with email, then send.</div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="bnsPaySelectAll">Select All</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="bnsPayUnselectAll">Unselect All</button>
                    <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Send the selected email template to checked payment applicants?');">
                        <i class="bi bi-send"></i> Submit &amp; Send Mail
                    </button>
                </div>
            </div>

            <div class="row g-3 align-items-start">
                <div class="col-lg-6">
                    <label class="form-label mb-1" for="bnsPayEmailTemplateSelect">
                        Select Email Template <span class="text-danger">*</span>
                    </label>
                    <select name="template" id="bnsPayEmailTemplateSelect" class="form-select" required>
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
                        Total templates: <strong>{{ count($emailTemplates) }}</strong> (Welcome + Stages 1–10)
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bns-ise-preview" id="bnsPayEmailTemplatePreview">
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
                            <input type="checkbox" class="form-check-input" id="bnsPayMasterCheck" title="Select all">
                        </th>
                        <th>Date</th>
                        <th>Registration No</th>
                        <th>Applicant</th>
                        <th>Program</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Txn / Payment ID</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        @php
                            $hasEmail = filled($payment->customer_email) && filter_var($payment->customer_email, FILTER_VALIDATE_EMAIL);
                        @endphp
                        <tr class="{{ $hasEmail ? '' : 'table-warning' }}">
                            <td>
                                @if($hasEmail)
                                    <input type="checkbox" class="form-check-input bns-pay-row-check" name="ids[]" value="{{ $payment->id }}">
                                @else
                                    <span class="text-muted small">No email</span>
                                @endif
                            </td>
                            <td class="small">{{ $payment->created_at?->format('d M Y, h:i A') }}</td>
                            <td class="fw-semibold">{{ $payment->registration_number }}</td>
                            <td>
                                <div>{{ $payment->customer_name }}</div>
                                <div class="small text-muted">{{ $payment->customer_email }}</div>
                            </td>
                            <td class="small">{{ config("payment.form_type_map.{$payment->form_type}.label", $payment->form_type) }}</td>
                            <td>₹ {{ number_format((float) $payment->amount, 2) }}</td>
                            <td><span class="badge text-bg-{{ $payment->statusBadgeClass() }}">{{ $payment->statusLabel() }}</span></td>
                            <td class="small">
                                <div>{{ $payment->merchant_txn_no }}</div>
                                @if($payment->payment_id)<div class="text-muted">PID: {{ $payment->payment_id }}</div>@endif
                            </td>
                            <td>
                                <a href="{{ route('controlpanel.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No payment records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($payments->hasPages())
            <div class="p-3">{{ $payments->links() }}</div>
        @endif
    </div>
</form>

<div class="sop-card" id="payment-send-mail-log">
    <div class="p-3 border-bottom">
        <strong>Payment Send Mail Log</strong>
        <div class="small text-muted">Emails sent from Payment Reports (batch keys starting with pay-).</div>
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
                        <td><span class="badge {{ $log->statusBadgeClass() }}">{{ $log->statusLabel() }}</span></td>
                        <td class="small">{{ $log->sender?->name ?: ($log->sender?->full_name ?? '—') }}</td>
                        <td class="small text-muted">{{ $log->error_message ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">No payment email logs yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="p-3 border-top">{{ $logs->links() }}</div>
    @endif
</div>
@endsection

@push('scripts')
<script>
(function () {
    var form = document.getElementById('bnsPaymentEmailForm');
    if (!form) return;

    var master = document.getElementById('bnsPayMasterCheck');
    var selectAllBtn = document.getElementById('bnsPaySelectAll');
    var unselectAllBtn = document.getElementById('bnsPayUnselectAll');
    var templateSelect = document.getElementById('bnsPayEmailTemplateSelect');
    var previewCache = @json($templatePreviews);
    var previewUrl = @json($previewUrl);
    var sessionNo = @json($sessionFilter !== '' && $sessionFilter !== null ? (int) $sessionFilter : (int) ($allowedSessions[0] ?? 1));
    var stageEl = document.querySelector('[data-preview-stage]');
    var titleEl = document.querySelector('[data-preview-title]');
    var bodyEl = document.querySelector('[data-preview-body]');

    function rowChecks() {
        return Array.prototype.slice.call(form.querySelectorAll('.bns-pay-row-check'));
    }

    function setAll(checked) {
        rowChecks().forEach(function (el) { el.checked = checked; });
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
            bodyEl.innerHTML = '<span class="bns-ise-preview__empty">Template content will appear here.</span>';
            return;
        }
        if (stageEl) stageEl.textContent = item.stage || 'Template Preview';
        if (titleEl) {
            titleEl.textContent = item.preview_html
                ? ''
                : (item.title || item.id || 'Template');
            titleEl.style.display = item.preview_html ? 'none' : '';
        }
        if (item.preview_html) {
            bodyEl.className = 'bns-ise-preview__body bns-ise-preview__rich';
            bodyEl.innerHTML = item.preview_html;
        } else {
            bodyEl.className = 'bns-ise-preview__body';
            bodyEl.innerHTML = '<span class="bns-ise-preview__empty">Loading preview…</span>';
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
        paintPreview({ stage: 'Loading', title: (cached && cached.title) || 'Loading…', preview_html: '' });
        if (!previewUrl) return;
        var url = previewUrl + '?template=' + encodeURIComponent(id) + '&session=' + encodeURIComponent(sessionNo);
        fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) { return res.ok ? res.json() : Promise.reject(res); })
            .then(function (data) {
                previewCache[id] = data;
                if (templateSelect.value === id) paintPreview(data);
            })
            .catch(function () {
                if (templateSelect.value === id) {
                    bodyEl.innerHTML = '<span class="bns-ise-preview__empty">Could not load preview.</span>';
                }
            });
    }

    if (master) master.addEventListener('change', function () { setAll(master.checked); });
    form.addEventListener('change', function (e) {
        if (e.target && e.target.classList.contains('bns-pay-row-check')) syncMaster();
    });
    if (selectAllBtn) selectAllBtn.addEventListener('click', function () { setAll(true); });
    if (unselectAllBtn) unselectAllBtn.addEventListener('click', function () { setAll(false); });
    if (templateSelect) {
        templateSelect.addEventListener('change', updatePreview);
        updatePreview();
    }
    syncMaster();
})();
</script>
@endpush
