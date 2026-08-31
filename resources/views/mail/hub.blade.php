@extends('layouts.front')

@section('title', $hub['title'] ?? 'BNS Mail')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal">
    @include('partials.page-header', [
        'title' => $hub['title'] ?? 'BNS Mail',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $hub['title'] ?? 'BNS Mail'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            <div class="bns-mail-toolbar">
                <div>
                    <span class="bns-message-intro__label">{{ $hub['label'] ?? 'Mail Portal' }}</span>
                    <h2 class="bns-mail-toolbar__title">{{ $hub['subtitle'] ?? 'Choose your mail workspace' }}</h2>
                </div>
                <form method="POST" action="{{ route('mail.logout') }}">
                    @csrf
                    <button type="submit" class="bns-mail-toolbar__logout">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
                    </button>
                </form>
            </div>

            @if(!empty($hub['intro']))
                <div class="bns-message-intro">
                    <p>{!! bns_rich_text($hub['intro']) !!}</p>
                </div>
            @endif

            <div class="bns-mail-hub">
                @php($student = $pages['student'] ?? [])
                @php($coach = $pages['business_coach'] ?? [])

                <a href="{{ route('mail.student') }}" class="bns-mail-hub__card bns-mail-hub__card--student">
                    <span class="bns-mail-hub__icon" aria-hidden="true"><i class="{{ $student['icon'] ?? 'fas fa-user-graduate' }}"></i></span>
                    <span class="bns-mail-hub__badge">{{ $student['badge'] ?? 'Student' }}</span>
                    <strong>{{ $student['title'] ?? 'BNS Student Mail' }}</strong>
                    <em>{{ $student['subtitle'] ?? 'Student Communication Sequence' }}</em>
                    <span class="bns-mail-hub__cta">Open Student Mail <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                </a>

                <a href="{{ route('mail.business-coach') }}" class="bns-mail-hub__card bns-mail-hub__card--coach">
                    <span class="bns-mail-hub__icon" aria-hidden="true"><i class="{{ $coach['icon'] ?? 'fas fa-chalkboard-teacher' }}"></i></span>
                    <span class="bns-mail-hub__badge">{{ $coach['badge'] ?? 'Business Coach' }}</span>
                    <strong>{{ $coach['title'] ?? 'BNS Business Coach Mail' }}</strong>
                    <em>{{ $coach['subtitle'] ?? 'Business Coach Communication Sequence' }}</em>
                    <span class="bns-mail-hub__cta">Open Coach Mail <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
