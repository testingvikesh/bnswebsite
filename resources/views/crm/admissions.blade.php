@extends('layouts.front')

@section('title', 'CRM Admission Confirm')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => 'Admission Confirm',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => $isAdmin ? route('crm.dashboard') : route('crm.desk')],
            ['label' => 'Admission Confirm'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @if(session('status'))
                <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
            @endif
            @include('crm.partials.toolbar', ['active' => 'admissions', 'isAdmin' => $isAdmin, 'employee' => $employee])

            <div class="bns-crm-stats">
                <div class="bns-crm-stat bns-crm-stat--present">
                    <span>Admission confirmed</span>
                    <strong>{{ number_format($admissions->count()) }}</strong>
                </div>
                <div class="bns-crm-stat">
                    <span>Also paid</span>
                    <strong>{{ number_format($admissions->where('has_payment', true)->count()) }}</strong>
                </div>
            </div>

            <form method="GET" action="{{ route('crm.admissions') }}" class="bns-crm-search">
                <label for="crmAdmSearch">Search confirmed admissions</label>
                <div class="bns-crm-search__row">
                    <input id="crmAdmSearch" type="search" name="q" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
                    <button type="submit"><i class="fas fa-search" aria-hidden="true"></i> Search</button>
                    @if($search !== '')
                        <a href="{{ route('crm.admissions') }}" class="bns-crm-search__reset">Clear</a>
                    @endif
                </div>
            </form>

            <p class="bns-crm-section-copy">These members left the call list after Pay Now / payment (mobile match) or admission confirm.</p>

            <section class="bns-crm-list-card">
                <div class="bns-crm-list-card__head">
                    <h3>Confirmed admissions</h3>
                    <span>{{ number_format($admissions->count()) }} {{ Str::plural('member', $admissions->count()) }}</span>
                </div>
                <div class="bns-crm-table-wrap">
                    <table class="bns-crm-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Contact</th>
                                <th>Reg. No.</th>
                                <th>Program</th>
                                <th>Confirmed on</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admissions as $item)
                                <tr>
                                    <td><strong>{{ $item->full_name ?: '—' }}</strong></td>
                                    <td>
                                        <div>{{ $item->mobile ?: '—' }}</div>
                                        <div class="is-muted">{{ $item->email ?: '—' }}</div>
                                    </td>
                                    <td>{{ $item->registration_number ?: '—' }}</td>
                                    <td>{{ $item->interested_program ?: '—' }}</td>
                                    <td>
                                        {{ $item->admission_confirmed_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A')
                                            ?: ($item->created_at?->timezone('Asia/Kolkata')->format('d M Y') ?: '—') }}
                                    </td>
                                    <td>
                                        @if($item->has_payment)
                                            <span class="bns-crm-badge bns-crm-badge--paid">Payment done</span>
                                        @else
                                            <span class="is-muted">No payment</span>
                                        @endif
                                        <div>
                                            <span class="bns-crm-badge bns-crm-badge--admitted">Admission confirmed</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="bns-crm-empty">No confirmed admissions yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </section>
</div>
@endsection
