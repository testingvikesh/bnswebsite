@php
    $item = $inquiry;
    $digits = preg_replace('/\D+/', '', (string) ($item->mobile ?? ''));
    $tel = $digits !== '' ? 'tel:+91'.ltrim($digits, '0') : '';
    if (strlen($digits) >= 10) {
        $tel = 'tel:+91'.substr($digits, -10);
    }
@endphp
@extends('layouts.front')

@section('title', ($item->full_name ?? 'Member').' follow-up')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => $item->full_name ?? 'Member',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => $isAdmin ? route('crm.dashboard') : route('crm.desk')],
            ['label' => 'Follow-up'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @if(session('status'))
                <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
            @endif
            @include('crm.partials.toolbar', ['active' => $isAdmin ? 'session' : 'desk', 'isAdmin' => $isAdmin, 'employee' => $employee, 'sessionNo' => $assignment->session_number])

            <section class="bns-crm-list-card">
                <div class="bns-crm-list-card__head">
                    <h3>{{ $item->full_name ?? 'Member' }}</h3>
                    <span>Session {{ $assignment->session_number }} · {{ $assignment->attendance_status === 'present' ? 'Present' : 'Absent' }}</span>
                </div>
                <div class="bns-crm-member-meta">
                    <div><span>Mobile</span><strong>{{ $item->mobile ?: '—' }}</strong></div>
                    <div><span>Email</span><strong>{{ $item->email ?: '—' }}</strong></div>
                    <div><span>Reg. No.</span><strong>{{ $item->registration_number ?: '—' }}</strong></div>
                    <div><span>City</span><strong>{{ $item->city ?: '—' }}</strong></div>
                    <div><span>Program</span><strong>{{ $item->interested_program ?: '—' }}</strong></div>
                </div>
                <div class="bns-crm-call-row">
                    @if($tel !== '')
                        <a class="bns-crm-call-btn" href="{{ $tel }}">
                            <i class="fas fa-phone" aria-hidden="true"></i> Call {{ $item->mobile }}
                        </a>
                    @else
                        <span class="is-muted">No mobile number to call.</span>
                    @endif
                    <a href="{{ $isAdmin ? route('crm.session', $assignment->session_number) : route('crm.desk') }}" class="bns-crm-search__reset">Back to list</a>
                </div>
            </section>

            <h3 class="bns-crm-section-title">3 follow-ups</h3>
            <p class="bns-crm-section-copy">After the call, update Follow-up 1, then 2, then 3.</p>

            <div class="bns-crm-followups">
                @for($n = 1; $n <= 3; $n++)
                    @php($row = $followups[$n] ?? null)
                    <section class="bns-crm-list-card bns-crm-followup-card">
                        <div class="bns-crm-list-card__head">
                            <h3>Follow-up {{ $n }}</h3>
                            <span>
                                @if($row && $row->called_at)
                                    {{ $row->called_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                                @else
                                    Not saved yet
                                @endif
                            </span>
                        </div>
                        <form method="POST" action="{{ route('crm.desk.followup', [$assignment, $n]) }}" class="bns-crm-form">
                            @csrf
                            <label>Call result</label>
                            <select name="status" required>
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(($row->status ?? 'pending') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <label>Remark</label>
                            <textarea name="note" rows="3" placeholder="What was discussed / next step">{{ old('note', $row->note ?? '') }}</textarea>
                            <button type="submit" class="bns-mail-login__submit">Save follow-up {{ $n }}</button>
                        </form>
                    </section>
                @endfor
            </div>
        </div>
    </section>
</div>
@endsection
