@extends('sop.layouts.app')

@section('title', 'Payment Details')
@section('page-title', 'Payment — '.$payment->registration_number)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <a href="{{ route('controlpanel.payments.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to Payment Reports</a>
    <form method="POST" action="{{ route('controlpanel.payments.refresh', $payment) }}">
        @csrf
        <button type="submit" class="btn btn-outline-primary btn-sm"><i class="bi bi-arrow-clockwise"></i> Refresh from Gateway</button>
    </form>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="sop-card p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                <div>
                    <span class="badge text-bg-{{ $payment->statusBadgeClass() }} mb-2">{{ $payment->statusLabel() }}</span>
                    <h4 class="mb-1">{{ $payment->customer_name }}</h4>
                    <p class="text-muted mb-0 small">{{ $formLabel }} | {{ $payment->registration_number }}</p>
                </div>
                <div class="text-end">
                    <div class="h4 mb-0">₹ {{ number_format((float) $payment->amount, 2) }}</div>
                    <small class="text-muted">{{ $payment->currency_code === '356' ? 'INR' : $payment->currency_code }}</small>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6"><strong>Merchant Txn No</strong><div>{{ $payment->merchant_txn_no }}</div></div>
                <div class="col-md-6"><strong>Payment ID</strong><div>{{ $payment->payment_id ?: '—' }}</div></div>
                <div class="col-md-6"><strong>Gateway Txn ID</strong><div>{{ $payment->txn_id ?: '—' }}</div></div>
                <div class="col-md-6"><strong>Payment Mode</strong><div>{{ $payment->payment_mode ?: '—' }}</div></div>
                <div class="col-md-6"><strong>Bank / Sub Type</strong><div>{{ $payment->payment_sub_inst_type ?: '—' }}</div></div>
                <div class="col-md-6"><strong>Payment DateTime</strong><div>{{ $payment->payment_datetime ?: '—' }}</div></div>
                <div class="col-md-6"><strong>Response Code</strong><div>{{ $payment->response_code ?: '—' }}</div></div>
                <div class="col-md-6"><strong>Paid At</strong><div>{{ $payment->paid_at?->format('d M Y, h:i A') ?: '—' }}</div></div>
                <div class="col-12"><strong>Response Description</strong><div>{{ $payment->response_description ?: '—' }}</div></div>
            </div>
        </div>

        <div class="sop-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Customer Details</h6>
            <div class="row g-3">
                <div class="col-md-4"><strong>Email</strong><div>{{ $payment->customer_email }}</div></div>
                <div class="col-md-4"><strong>Mobile</strong><div>{{ $payment->customer_mobile }}</div></div>
                <div class="col-md-4"><strong>Form Type</strong><div>{{ $payment->form_type }}</div></div>
                <div class="col-md-6"><strong>Additional Param 1</strong><div>{{ $payment->addl_param1 ?: '—' }}</div></div>
                <div class="col-md-6"><strong>Additional Param 2</strong><div>{{ $payment->addl_param2 ?: '—' }}</div></div>
            </div>
        </div>

        @if($payment->callback_response)
        <div class="sop-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Gateway Callback Response</h6>
            <pre class="small bg-light p-3 rounded mb-0" style="white-space: pre-wrap;">{{ json_encode($payment->callback_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>
        @endif

        @if($payment->status_response)
        <div class="sop-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Status Check Response</h6>
            <pre class="small bg-light p-3 rounded mb-0" style="white-space: pre-wrap;">{{ json_encode($payment->status_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="sop-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Initiate Sale Request</h6>
            @if($payment->initiate_request)
                <pre class="small bg-light p-3 rounded" style="white-space: pre-wrap; max-height: 280px; overflow:auto;">{{ json_encode($payment->initiate_request, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            @else
                <p class="text-muted small mb-0">Not initiated yet.</p>
            @endif
        </div>

        <div class="sop-card p-4">
            <h6 class="fw-bold mb-3">Initiate Sale Response</h6>
            @if($payment->initiate_response)
                <pre class="small bg-light p-3 rounded mb-0" style="white-space: pre-wrap; max-height: 280px; overflow:auto;">{{ json_encode($payment->initiate_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            @else
                <p class="text-muted small mb-0">No gateway response yet.</p>
            @endif
        </div>
    </div>
</div>
@endsection
