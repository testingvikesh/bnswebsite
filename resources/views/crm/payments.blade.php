@extends('layouts.front')

@section('title', 'CRM Payment Done')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => 'Payment Done',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => $isAdmin ? route('crm.dashboard') : route('crm.desk')],
            ['label' => 'Payment Done'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @if(session('status'))
                <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
            @endif
            @include('crm.partials.toolbar', ['active' => 'payments', 'isAdmin' => $isAdmin, 'employee' => $employee])

            <div class="bns-crm-stats">
                <a href="{{ route('crm.payments', array_filter(['q' => $search ?: null])) }}" class="bns-crm-stat bns-crm-stat--present{{ ($sessionFilter ?? 0) === 0 ? ' is-active' : '' }}">
                    <span>All sessions</span>
                    <strong>{{ number_format($allCount ?? $payments->count()) }}</strong>
                </a>
                @foreach(($allowedSessions ?? bns_intro_session_allowed_numbers()) as $sessionNo)
                    <a href="{{ route('crm.payments', array_filter(['session' => $sessionNo, 'q' => $search ?: null])) }}" class="bns-crm-stat bns-crm-stat--present{{ ($sessionFilter ?? 0) === (int) $sessionNo ? ' is-active' : '' }}">
                        <span>Session {{ $sessionNo }}</span>
                        <strong>{{ number_format($sessionTotals[$sessionNo] ?? 0) }}</strong>
                    </a>
                @endforeach
            </div>
            <p class="bns-crm-section-copy">Session-wise payment done totals. Click a session to see that list.</p>

            <form method="GET" action="{{ route('crm.payments') }}" class="bns-crm-search">
                @if(($sessionFilter ?? 0) > 0)
                    <input type="hidden" name="session" value="{{ $sessionFilter }}">
                @endif
                <label for="crmPaySearch">Search payments</label>
                <div class="bns-crm-search__row">
                    <input id="crmPaySearch" type="search" name="q" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
                    <button type="submit"><i class="fas fa-search" aria-hidden="true"></i> Search</button>
                    @if($search !== '' || ($sessionFilter ?? 0) > 0)
                        <a href="{{ route('crm.payments') }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            @forelse($groups as $sessionNo => $rows)
                @php
                    $event = bns_introduction_session((int) $sessionNo) ?? [];
                @endphp
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>
                            @if((int) $sessionNo > 0)
                                Session {{ $sessionNo }}
                                @if(!empty($event['title'])) · {{ $event['title'] }}@endif
                            @else
                                Other / unmatched session
                            @endif
                        </h3>
                        <span>{{ number_format($rows->count()) }} {{ Str::plural('record', $rows->count()) }}</span>
                    </div>
                    <div class="bns-crm-table-wrap">
                        <table class="bns-crm-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Reg. No.</th>
                                    <th>Amount</th>
                                    <th>Paid on</th>
                                    <th>Admission</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rows as $payment)
                                    @php
                                        $item = $payment->matched_inquiry;
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $payment->customer_name ?: ($item->full_name ?? '—') }}</strong></td>
                                        <td>
                                            <div>{{ $payment->customer_mobile ?: ($item->mobile ?? '—') }}</div>
                                            <div class="is-muted">{{ $payment->customer_email ?: ($item->email ?? '—') }}</div>
                                        </td>
                                        <td>{{ $payment->registration_number ?: ($item->registration_number ?? '—') }}</td>
                                        <td>₹{{ $payment->formattedAmount() }}</td>
                                        <td>{{ $payment->paid_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '—' }}</td>
                                        <td>
                                            <span class="bns-crm-badge bns-crm-badge--paid">Payment done</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            @empty
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>Successful payments</h3>
                        <span>0 records</span>
                    </div>
                    <p class="bns-crm-empty" style="padding: 18px;">No successful payments found.</p>
                </section>
            @endforelse
        </div>
    </section>
</div>
@endsection
