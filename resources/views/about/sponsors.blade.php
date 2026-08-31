@extends('layouts.front')

@section('title', $page['title'] ?? 'Meet Our Sponsors')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/team.css') }}" />
@endpush

@section('content')
<div class="bns-team-page bns-sponsors-page">
    @include('partials.page-header', [
        'title' => $page['title'] ?? 'Meet Our Sponsors',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'About Us', 'url' => route('about')],
            ['label' => $page['title'] ?? 'Meet Our Sponsors'],
        ],
    ])

    <section class="bns-team-intro">
        <span class="bns-team-intro__blob bns-team-intro__blob--one" aria-hidden="true"></span>
        <span class="bns-team-intro__blob bns-team-intro__blob--two" aria-hidden="true"></span>
        <div class="container">
            <div class="bns-team-intro__card">
                <span class="bns-team-intro__eyebrow"><i class="fas fa-handshake" aria-hidden="true"></i> Our Partners</span>
                @if(!empty($page['subtitle']))
                    <p class="bns-team-intro__subtitle">{{ $page['subtitle'] }}</p>
                @endif
                @if(!empty($page['intro']))
                    <p class="bns-team-intro__text">{!! bns_rich_text($page['intro']) !!}</p>
                @endif
            </div>
        </div>
    </section>

    <section class="bns-team-hub">
        <div class="container">
            @include('about.partials.sponsors-section', ['sponsors' => $sponsors])
            @include('about.partials.venue-partner-section', ['venuePartner' => $venuePartner])
        </div>
    </section>
</div>
@endsection
