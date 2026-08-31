@extends('layouts.front')

@section('title', $why['title'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/about-page.css') }}" />
@endpush

@section('content')
<div class="bns-about-page bns-why-page">
    @include('partials.page-header', [
        'title' => $why['title'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'About Us', 'url' => route('about')],
            ['label' => 'Why BNS'],
        ],
    ])

    <section class="bns-vision-content">
        <div class="container">
            <div class="bns-vision-card bns-why-card">
                <a href="{{ route('about') }}" class="bns-vision-back"><i class="fas fa-arrow-left"></i> Back to About Us</a>

                <div class="bns-vision-header">
                    <span class="bns-vision-header__label">Why BNS</span>
                    @if(!empty($why['subtitle']))
                        <h2>{{ $why['subtitle'] }}</h2>
                    @endif
                </div>

                @foreach($why['intro'] ?? [] as $paragraph)
                    <p class="bns-mission-intro">{!! bns_rich_text($paragraph) !!}</p>
                @endforeach

                @if(!empty($why['section_heading']))
                    <h3 class="bns-why-section-heading">{{ $why['section_heading'] }}</h3>
                @endif

                <div class="bns-why-reasons">
                    @foreach($why['reasons'] ?? [] as $reason)
                    <article class="bns-why-reason">
                        <div class="bns-why-reason__number">{{ $reason['number'] ?? '' }}</div>
                        <div class="bns-why-reason__body">
                            <h4>{!! bns_rich_text($reason['title'] ?? '') !!}</h4>
                            @if(!empty($reason['text']))
                                <p>{!! bns_rich_text($reason['text']) !!}</p>
                            @endif
                            @if(!empty($reason['intro']))
                                <p class="bns-why-reason__intro">{!! bns_rich_text($reason['intro']) !!}</p>
                            @endif
                            @if(!empty($reason['items']))
                            <ul class="bns-why-list list-unstyled">
                                @foreach($reason['items'] as $item)
                                    <li><i class="fas fa-check"></i> {!! bns_rich_text($item) !!}</li>
                                @endforeach
                            </ul>
                            @endif
                            @if(!empty($reason['programs']))
                            <ul class="bns-mission-programs list-unstyled">
                                @foreach($reason['programs'] as $program)
                                    <li>
                                        @if(!empty($program['icon']))<span class="bns-mission-programs__icon">{{ $program['icon'] }}</span>@endif
                                        {{ $program['label'] ?? '' }}
                                    </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                    </article>
                    @endforeach
                </div>

                @if(!empty($why['why_thousands']))
                <section class="bns-why-thousands">
                    <h3>{{ $why['why_thousands']['title'] ?? 'Why Thousands Will Choose BNS' }}</h3>
                    <ul class="bns-why-checklist list-unstyled">
                        @foreach($why['why_thousands']['items'] ?? [] as $item)
                            <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
                        @endforeach
                    </ul>
                </section>
                @endif

                @if(!empty($why['closing']))
                <div class="bns-why-closing">
                    @if(!empty($why['closing']['brand']))
                        <p class="bns-why-closing__brand">{!! bns_rich_text($why['closing']['brand']) !!}</p>
                    @endif
                    @if(!empty($why['closing']['tagline']))
                        <p class="bns-why-closing__tagline">{!! bns_rich_text($why['closing']['tagline']) !!}</p>
                    @endif
                    @if(!empty($why['closing']['hindi']))
                        <p class="bns-why-closing__hindi">{!! bns_rich_text($why['closing']['hindi']) !!}</p>
                    @endif
                </div>
                @endif

                <div class="bns-vision-actions">
                    <button type="button" class="bns-vision-actions__btn bns-vision-actions__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                        <i class="fas fa-rocket"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
                    </button>
                    <a href="{{ route('admissions.index') }}" class="bns-vision-actions__btn">
                        <i class="fas fa-graduation-cap"></i> Explore Admissions
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
