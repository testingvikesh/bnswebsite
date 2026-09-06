@extends('sop.layouts.app')

@section('title', 'Email Details')
@section('page-title', 'Email Details')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/email-dashboard-admin.css') }}">
@endpush

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <a href="{{ route('controlpanel.email-dashboard.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to Email Dashboard</a>
    @if($log->to_email)
        <a href="mailto:{{ $log->to_email }}" class="btn btn-outline-primary btn-sm">Reply / Open Mail</a>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="sop-card p-4">
            <h6 class="text-uppercase text-muted small fw-bold mb-3">Delivery</h6>
            <dl class="row mb-0 small">
                <dt class="col-5">Date</dt>
                <dd class="col-7">{{ $log->sent_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '—' }}</dd>
                <dt class="col-5">Process</dt>
                <dd class="col-7"><span class="bns-email-dash__process">{{ $log->processLabel() }}</span></dd>
                <dt class="col-5">Status</dt>
                <dd class="col-7">
                    @if($log->status === 'failed')
                        <span class="bns-email-dash__status bns-email-dash__status--failed">Failed</span>
                    @else
                        <span class="bns-email-dash__status bns-email-dash__status--sent">Sent</span>
                    @endif
                </dd>
                <dt class="col-5">Sent By</dt>
                <dd class="col-7">{{ $log->senderLabel() }}</dd>
                <dt class="col-5">Mailer</dt>
                <dd class="col-7">{{ $log->mailer ?: '—' }}</dd>
                <dt class="col-5">Mailable</dt>
                <dd class="col-7">{{ $log->mailable ?: '—' }}</dd>
            </dl>

            <h6 class="text-uppercase text-muted small fw-bold mt-4 mb-3">Addresses</h6>
            <dl class="row mb-0 small">
                <dt class="col-5">From</dt>
                <dd class="col-7">{{ $log->from_email ?: '—' }}</dd>
                <dt class="col-5">To</dt>
                <dd class="col-7"><a href="mailto:{{ $log->to_email }}">{{ $log->to_email }}</a></dd>
                <dt class="col-5">Cc</dt>
                <dd class="col-7">{{ $log->cc_email ?: '—' }}</dd>
            </dl>

            @if($log->error_message)
                <h6 class="text-uppercase text-muted small fw-bold mt-4 mb-2">Error</h6>
                <p class="small text-danger mb-0">{{ $log->error_message }}</p>
            @endif
        </div>
    </div>
    <div class="col-lg-8">
        <div class="sop-card p-4">
            <h5 class="mb-3">{{ $log->subject ?: 'No subject' }}</h5>
            @if($log->body_html)
                <iframe
                    class="bns-email-dash__preview"
                    title="Email preview"
                    sandbox=""
                    srcdoc="{{ htmlspecialchars($log->body_html, ENT_QUOTES, 'UTF-8') }}"
                ></iframe>
            @elseif($log->body_text)
                <pre class="bns-email-dash__text">{{ $log->body_text }}</pre>
            @else
                <p class="text-muted mb-0">No email body was stored for this message.</p>
            @endif
        </div>
    </div>
</div>
@endsection
