@extends('layouts.front')

@section('title', 'Attendance QR Approval')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/pay-now.css') }}" />
<style>
.bns-qr-approve {
    max-width: 560px;
    margin: 0 auto;
}
.bns-qr-approve__card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 16px 40px rgba(10, 29, 55, 0.08);
}
.bns-qr-approve__hero {
    padding: 28px 22px;
    text-align: center;
    color: #fff;
    background: linear-gradient(135deg, #0a1d37 0%, #14532d 52%, #0d2944 100%);
}
.bns-qr-approve__hero .eyebrow {
    display: inline-block;
    margin-bottom: 10px;
    padding: 6px 12px;
    border-radius: 999px;
    background: linear-gradient(135deg, #22c55e, #15803d);
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .06em;
    text-transform: uppercase;
}
.bns-qr-approve__hero h1 {
    margin: 0 0 8px;
    font-size: 1.45rem;
    font-weight: 800;
    color: #fff;
}
.bns-qr-approve__hero p {
    margin: 0;
    color: rgba(255,255,255,.88);
    font-size: .95rem;
}
.bns-qr-approve__body { padding: 22px; }
.bns-qr-approve__row {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    padding: 12px 14px;
    margin-bottom: 8px;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #f8fafc;
}
.bns-qr-approve__row strong {
    display: block;
    font-size: .72rem;
    letter-spacing: .04em;
    text-transform: uppercase;
    color: #94a3b8;
    margin-bottom: 2px;
}
.bns-qr-approve__row span {
    font-weight: 700;
    color: #0a1d37;
}
.bns-qr-approve__note {
    margin: 16px 0;
    padding: 14px 16px;
    border-radius: 12px;
    background: #fff7ed;
    border: 1px solid #fed7aa;
    color: #9a3412;
    font-size: .92rem;
    line-height: 1.55;
}
.bns-qr-approve__actions { text-align: center; margin-top: 8px; }
.bns-qr-approve__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-width: 220px;
    padding: 14px 22px;
    border: 0;
    border-radius: 999px;
    background: linear-gradient(135deg, #22c55e, #15803d);
    color: #fff;
    font-weight: 800;
    font-size: 1rem;
}
.bns-qr-approve__btn:disabled {
    opacity: .65;
    cursor: not-allowed;
}
.bns-qr-approve__status {
    text-align: center;
    padding: 18px;
    border-radius: 14px;
    font-weight: 700;
}
.bns-qr-approve__status.is-ok { background: #f0fdf4; color: #14532d; border: 1px solid #bbf7d0; }
.bns-qr-approve__status.is-bad { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
.bns-qr-approve__status.is-info { background: #f8fafc; color: #334155; border: 1px solid #e2e8f0; }
</style>
@endpush

@section('content')
@include('partials.page-header', [
    'title' => 'Attendance Approval',
    'bgImage' => asset('assets/images/backgrounds/page-header-bg.jpg'),
    'breadcrumbs' => [
        ['label' => 'Home', 'url' => url('/')],
        ['label' => 'Attendance', 'url' => route('attendance')],
        ['label' => 'QR Approval'],
    ],
])

<section class="bns-pay-now__section">
    <div class="container">
        <div class="bns-qr-approve">
            @if(session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <article class="bns-qr-approve__card">
                <div class="bns-qr-approve__hero">
                    <p class="eyebrow">Volunteer Verification</p>
                    <h1>Approve Session Attendance</h1>
                    <p>Verify the participant in person, then approve to mark Present.</p>
                </div>

                <div class="bns-qr-approve__body">
                    <div class="bns-qr-approve__row">
                        <div style="font-size:1.35rem;">👤</div>
                        <div><strong>Participant</strong><span>{{ $invite->full_name ?: '—' }}</span></div>
                    </div>
                    <div class="bns-qr-approve__row">
                        <div style="font-size:1.35rem;">🆔</div>
                        <div><strong>Registration No.</strong><span>{{ $invite->registration_number ?: '—' }}</span></div>
                    </div>
                    <div class="bns-qr-approve__row">
                        <div style="font-size:1.35rem;">📧</div>
                        <div><strong>Email / Mobile</strong><span>{{ $invite->email ?: '—' }} · {{ $invite->mobile ?: '—' }}</span></div>
                    </div>
                    <div class="bns-qr-approve__row">
                        <div style="font-size:1.35rem;">📅</div>
                        <div>
                            <strong>Session</strong>
                            <span>
                                {{ $event['title'] ?? ('Session '.$invite->session_number) }}
                                @if(!empty($event['date'])) · {{ $event['date'] }} @endif
                                @if(!empty($event['time'])) · {{ $event['time'] }} @endif
                            </span>
                        </div>
                    </div>

                    @if($alreadyApproved)
                        <div class="bns-qr-approve__status is-ok mt-3">
                            ✅ Attendance already approved / Present
                            @if($invite->approved_at)
                                <div class="small fw-normal mt-1">{{ $invite->approved_at->format('d M Y, h:i A') }}</div>
                            @endif
                        </div>
                    @elseif($revoked)
                        <div class="bns-qr-approve__status is-bad mt-3">⛔ This invite has been revoked.</div>
                    @elseif($expired)
                        <div class="bns-qr-approve__status is-bad mt-3">⌛ This QR invite has expired. Ask admin to resend.</div>
                    @elseif($canApprove)
                        <div class="bns-qr-approve__note">
                            <strong>Volunteer only:</strong> Confirm face + registration details match this participant before approving.
                        </div>
                        <form method="POST" action="{{ route('attendance.qr.approve', ['token' => $invite->token]) }}" class="bns-qr-approve__actions"
                              onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').textContent='Approving…';">
                            @csrf
                            <button type="submit" class="bns-qr-approve__btn">
                                <i class="fas fa-user-check"></i> Approve &amp; Mark Present
                            </button>
                        </form>
                    @else
                        <div class="bns-qr-approve__status is-info mt-3">This invite cannot be approved right now.</div>
                    @endif
                </div>
            </article>
        </div>
    </div>
</section>
@endsection
