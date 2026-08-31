@extends('layouts.front')

@section('title', $page['title'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/about-page.css') }}" />
@endpush

@section('content')
<div class="bns-about-page bns-vision2047-page">
    @include('partials.page-header', [
        'title' => $page['title'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'About Us', 'url' => route('about')],
            ['label' => $page['title']],
        ],
    ])

    <section class="bns-vision-content">
        <div class="container">
            <div class="bns-vision-card bns-vision2047-card">
                <a href="{{ route('about') }}" class="bns-vision-back"><i class="fas fa-arrow-left"></i> Back to About Us</a>

                <div class="bns-vision-header">
                    <span class="bns-vision-header__label">{{ $page['label'] ?? 'Vision 2047' }}</span>
                    @if(!empty($page['subtitle']))
                        <h2>{{ $page['subtitle'] }}</h2>
                    @endif
                </div>

                @foreach($page['intro'] ?? [] as $paragraph)
                    <p class="bns-mission-intro">{!! bns_rich_text($paragraph) !!}</p>
                @endforeach

                @if(!empty($page['our_vision']))
                <section class="bns-vision2047-highlight">
                    <h3>{{ $page['our_vision']['title'] ?? '' }}</h3>
                    <p>{!! bns_rich_text($page['our_vision']['text'] ?? '') !!}</p>
                </section>
                @endif

                @if(!empty($page['goals']))
                <section class="bns-vision2047-goals">
                    @if(!empty($page['goals_heading']))
                        <h3 class="bns-why-section-heading">{{ $page['goals_heading'] }}</h3>
                    @endif
                    <div class="bns-why-reasons">
                        @foreach($page['goals'] as $goal)
                        <article class="bns-why-reason">
                            <div class="bns-why-reason__number">{{ $goal['number'] ?? '' }}</div>
                            <div class="bns-why-reason__body">
                                <h4>{!! bns_rich_text($goal['title'] ?? '') !!}</h4>
                                @if(!empty($goal['text']))
                                    <p>{!! bns_rich_text($goal['text']) !!}</p>
                                @endif
                            </div>
                        </article>
                        @endforeach
                    </div>
                </section>
                @endif

                @if(!empty($page['commitment']))
                <section class="bns-prosperity-section">
                    <h3>{{ $page['commitment']['title'] ?? '' }}</h3>
                    @if(!empty($page['commitment']['intro']))
                        <p class="bns-prosperity-section__intro">{!! bns_rich_text($page['commitment']['intro']) !!}</p>
                    @endif
                    <ul class="bns-why-checklist list-unstyled">
                        @foreach($page['commitment']['items'] ?? [] as $item)
                            <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
                        @endforeach
                    </ul>
                </section>
                @endif

                @if(!empty($page['dream']))
                <section class="bns-prosperity-section bns-vision2047-dream">
                    <h3>{{ $page['dream']['title'] ?? '' }}</h3>
                    @if(!empty($page['dream']['intro']))
                        <p class="bns-prosperity-section__intro">{!! bns_rich_text($page['dream']['intro']) !!}</p>
                    @endif
                    <ul class="bns-prosperity-list list-unstyled">
                        @foreach($page['dream']['items'] ?? [] as $item)
                            <li><i class="fas fa-star"></i> {!! bns_rich_text($item) !!}</li>
                        @endforeach
                    </ul>
                </section>
                @endif

                @if(!empty($page['statement']))
                <section class="bns-vision2047-statement">
                    <h3>{{ $page['statement']['title'] ?? '' }}</h3>
                    <blockquote>
                        <i class="fas fa-quote-left" aria-hidden="true"></i>
                        {!! bns_rich_text($page['statement']['quote'] ?? '') !!}
                    </blockquote>
                </section>
                @endif

                @if(!empty($page['closing']))
                <div class="bns-why-closing bns-vision2047-closing">
                    @if(!empty($page['closing']['brand']))
                        <p class="bns-why-closing__brand">{!! bns_rich_text($page['closing']['brand']) !!}</p>
                    @endif
                    @if(!empty($page['closing']['subtitle']))
                        <p class="bns-why-closing__subtitle">{!! bns_rich_text($page['closing']['subtitle']) !!}</p>
                    @endif
                    @if(!empty($page['closing']['motto']))
                        <p class="bns-vision2047-closing__motto">{!! bns_rich_text($page['closing']['motto']) !!}</p>
                    @endif
                    @if(!empty($page['closing']['tagline']))
                        <p class="bns-why-closing__tagline">{!! bns_rich_text($page['closing']['tagline']) !!}</p>
                    @endif
                </div>
                @endif

                <div class="bns-vision-actions">
                    <button type="button" class="bns-vision-actions__btn bns-vision-actions__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                        <i class="fas fa-rocket"></i> Be Part of Vision 2047 — {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
                    </button>
                    <a href="{{ route('about.prosperity-mission') }}" class="bns-vision-actions__btn">
                        <i class="fas fa-flag"></i> Prosperity Mission
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
