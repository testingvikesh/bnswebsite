@extends('layouts.front')

@section('title', $about->heading)

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/about-page.css') }}" />
@endpush

@section('content')
<div class="bns-about-page">
    @include('partials.page-header', [
        'title' => $about->tagline ?: 'About Us',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $about->tagline ?: 'About Us'],
        ],
    ])

    <section class="about-one bns-about-page__intro">
        <div class="about-one__bg" style="background-image: url({{ $heroImage }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-xl-5 wow fadeInLeft" data-wow-duration="0.9s" data-wow-delay="80ms"></div>
                <div class="col-xl-7 wow fadeInRight" data-wow-duration="0.9s" data-wow-delay="120ms">
                    <div class="about-one__right">
                        <div class="about-one__right-shape-1">
                            <img src="{{ $shapeImage }}" alt="">
                        </div>
                        @include('about.partials.content-block', [
                            'about' => $about,
                            'showMoreLink' => false,
                            'hideTagline' => true,
                        ])
                        @if(!empty($hub['intro_2']))
                            <p class="about-one__text">{!! bns_rich_text($hub['intro_2']) !!}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if(!empty($hub['programs']))
    <section class="bns-about-programs">
        <div class="container">
            <p class="bns-about-programs__label">Programs for every learner</p>
            <div class="bns-about-programs__grid">
                @foreach($hub['programs'] as $program)
                    <span class="bns-about-programs__chip">
                        @if(!empty($program['icon']))<span class="bns-about-programs__icon">{{ $program['icon'] }}</span>@endif
                        {{ $program['label'] ?? '' }}
                    </span>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(!empty($hub['collaboration_title']))
    <section class="bns-about-collab">
        <div class="container">
            <div class="bns-about-collab__inner">
                <div class="bns-about-collab__logo" aria-hidden="true">
                    <img
                        src="{{ bns_vasset($hub['collaboration_logo'] ?? 'assets/images/partners/iim-ahmedabad-logo.png') }}"
                        alt="{{ $hub['collaboration_logo_alt'] ?? 'Faculties of IIM — Faculty Members' }}"
                        loading="lazy"
                        decoding="async"
                    >
                </div>
                <div>
                    <h3>{!! bns_rich_text($hub['collaboration_title']) !!}</h3>
                    <p>{!! bns_rich_text($hub['collaboration_text'] ?? '') !!}</p>
                </div>
            </div>
        </div>
    </section>
    @endif

    @if(!empty($hub['pillars']))
    <section class="bns-about-pillars">
        <div class="container">
            <div class="section-title text-center sec-title-animation animation-style1">
                <div class="section-title__tagline-box">
                    <div class="section-title__tagline-shape"></div>
                    <div class="section-title__tagline-shape-2"></div>
                    <span class="section-title__tagline">Discover BNS</span>
                </div>
                <h2 class="section-title__title title-animation">Vision, Mission &amp; <span>Values</span></h2>
            </div>
            <div class="row g-4">
                @foreach($hub['pillars'] as $pillar)
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="{{ 80 + $loop->index * 40 }}ms">
                    <a href="{{ route($pillar['route']) }}" class="bns-about-pillar">
                        <div class="bns-about-pillar__icon"><i class="{{ $pillar['icon'] ?? 'fas fa-link' }}"></i></div>
                        <h3>{{ $pillar['label'] ?? '' }}</h3>
                        <p>{!! bns_rich_text($pillar['description'] ?? '') !!}</p>
                        <span class="bns-about-pillar__link">Read More <i class="fas fa-arrow-right"></i></span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if(!empty($valuePreview))
    <section class="bns-about-values">
        <div class="container">
            <div class="section-title text-center sec-title-animation animation-style1">
                <div class="section-title__tagline-box">
                    <div class="section-title__tagline-shape"></div>
                    <div class="section-title__tagline-shape-2"></div>
                    <span class="section-title__tagline">Our Values</span>
                </div>
                <h2 class="section-title__title title-animation">What We <span>Stand For</span></h2>
            </div>
            <div class="row g-4 bns-about-values__grid">
                @foreach($valuePreview as $value)
                <div class="col-md-6 col-lg-4 wow fadeInUp" data-wow-delay="{{ 80 + $loop->index * 40 }}ms">
                    <div class="bns-about-values__card">
                        <div class="bns-about-values__icon"><span>{{ $value['number'] ?? $loop->iteration }}</span></div>
                        <h3>{{ $value['title'] ?? '' }}</h3>
                        <p>{!! bns_rich_text($value['text'] ?? '') !!}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center bns-about-values__more">
                <a href="{{ route('about.values') }}" class="bns-about-mv__link">View All 12 Core Values <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </section>
    @endif

    @if(!empty($philosophy))
    <section class="bns-about-philosophy">
        <div class="container">
            <div class="bns-about-philosophy__inner">
                @if(!empty($philosophy['hindi']))
                    <p class="bns-about-philosophy__hindi">{!! bns_rich_text($philosophy['hindi']) !!}</p>
                @endif
                @if(!empty($philosophy['english']))
                    <p class="bns-about-philosophy__english">{!! bns_rich_text($philosophy['english']) !!}</p>
                @endif
            </div>
        </div>
    </section>
    @endif
</div>
@endsection
