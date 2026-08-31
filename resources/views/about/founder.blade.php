@extends('layouts.front')

@section('title', $founder['title'].' – '.$founder['subtitle'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/about-page.css') }}" />
@endpush

@section('content')
<div class="bns-about-page bns-founder-page">
    @include('partials.page-header', [
        'title' => $founder['title'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'About Us', 'url' => route('about')],
            ['label' => $founder['title']],
        ],
    ])

    <section class="bns-vision-content">
        <div class="container">
            <div class="bns-vision-card bns-founder-card">
                <a href="{{ route('about') }}" class="bns-vision-back"><i class="fas fa-arrow-left"></i> Back to About Us</a>

                <div class="bns-founder-letter">
                    <div class="bns-founder-letter__header">
                        <span class="bns-vision-header__label">From the Founder</span>
                        <h2>{{ $founder['title'] }}</h2>
                    </div>

                    @if(!empty($founder['salutation']))
                        <p class="bns-founder-salutation">{{ $founder['salutation'] }}</p>
                    @endif

                    @foreach($founder['paragraphs'] ?? [] as $paragraph)
                        <p class="bns-founder-paragraph">{!! bns_rich_text($paragraph) !!}</p>
                    @endforeach

                    @if(!empty($founder['vision_heading']))
                        <div class="bns-founder-highlight">
                            <p class="bns-founder-highlight__label">{!! bns_rich_text($founder['vision_heading']) !!}</p>
                            <p class="bns-founder-highlight__text">{!! bns_rich_text($founder['vision_text'] ?? '') !!}</p>
                        </div>
                    @endif

                    @if(!empty($founder['belief']))
                        <p class="bns-founder-paragraph">{!! bns_rich_text($founder['belief']) !!}</p>
                    @endif

                    @if(!empty($founder['mission_invite']))
                        <p class="bns-founder-paragraph">{!! bns_rich_text($founder['mission_invite']) !!}</p>
                    @endif

                    @if(!empty($founder['invitation']))
                        <p class="bns-founder-invitation">{!! bns_rich_text($founder['invitation']) !!}</p>
                    @endif

                    @if(!empty($founder['closing_lines']))
                    <ul class="bns-founder-closing list-unstyled">
                        @foreach($founder['closing_lines'] as $line)
                            <li>{!! bns_rich_text($line) !!}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if(!empty($founder['signature']))
                    <div class="bns-founder-signature">
                        @if(!empty($founder['signature']['closing']))
                            <p class="bns-founder-signature__closing">{!! bns_rich_text($founder['signature']['closing']) !!}</p>
                        @endif
                        <p class="bns-founder-signature__name">{{ $founder['signature']['name'] ?? '' }}</p>
                        <p class="bns-founder-signature__title">{{ $founder['signature']['title'] ?? '' }}</p>
                        <p class="bns-founder-signature__org">{{ $founder['signature']['organization'] ?? '' }}</p>
                    </div>
                    @endif
                </div>

                <div class="bns-vision-actions">
                    <button type="button" class="bns-vision-actions__btn bns-vision-actions__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                        <i class="fas fa-rocket"></i> Join BNS — {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
                    </button>
                    <a href="{{ route('about.mission') }}" class="bns-vision-actions__btn">
                        <i class="fas fa-bullseye"></i> Our Mission
                    </a>
                    <a href="{{ route('about.vision') }}" class="bns-vision-actions__btn">
                        <i class="fas fa-eye"></i> Our Vision
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
