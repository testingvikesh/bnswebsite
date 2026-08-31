@extends('layouts.front')

@section('title', $page['title'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/about-page.css') }}" />
@endpush

@section('content')
<div class="bns-about-page bns-why-page">
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
            <div class="bns-vision-card bns-why-card">
                <a href="{{ route('about') }}" class="bns-vision-back"><i class="fas fa-arrow-left"></i> Back to About Us</a>

                <div class="bns-vision-header">
                    <span class="bns-vision-header__label">{{ $page['label'] ?? 'Why' }}</span>
                    @if(!empty($page['subtitle']))
                        <h2>{{ $page['subtitle'] }}</h2>
                    @endif
                </div>

                @foreach($page['intro'] ?? [] as $paragraph)
                    <p class="bns-mission-intro">{!! bns_rich_text($paragraph) !!}</p>
                @endforeach

                @if(!empty($page['section_heading']))
                    <h3 class="bns-why-section-heading">{{ $page['section_heading'] }}</h3>
                @endif

                <div class="bns-why-reasons">
                    @foreach($page['reasons'] ?? [] as $reason)
                    <article class="bns-why-reason">
                        <div class="bns-why-reason__number">{{ $reason['number'] ?? '' }}</div>
                        <div class="bns-why-reason__body">
                            <h4>{!! bns_rich_text($reason['title'] ?? '') !!}</h4>
                            @if(!empty($reason['items']))
                            <ul class="bns-why-chain list-unstyled">
                                @foreach($reason['items'] as $item)
                                    <li>{!! bns_rich_text($item) !!}</li>
                                @endforeach
                            </ul>
                            @endif
                            @if(!empty($reason['text']))
                                <p>{!! bns_rich_text($reason['text']) !!}</p>
                            @endif
                            @if(!empty($reason['intro']))
                                <p class="bns-why-reason__intro">{!! bns_rich_text($reason['intro']) !!}</p>
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

                @if(!empty($page['for_everyone']))
                <section class="bns-why-extra">
                    <h3>{{ $page['for_everyone']['title'] ?? '' }}</h3>
                    @if(!empty($page['for_everyone']['intro']))
                        <p>{!! bns_rich_text($page['for_everyone']['intro']) !!}</p>
                    @endif
                    @if(!empty($page['for_everyone']['intro_2']))
                        <p class="bns-why-reason__intro">{!! bns_rich_text($page['for_everyone']['intro_2']) !!}</p>
                    @endif
                    @if(!empty($page['for_everyone']['programs']))
                    <ul class="bns-mission-programs list-unstyled">
                        @foreach($page['for_everyone']['programs'] as $program)
                            <li>
                                @if(!empty($program['icon']))<span class="bns-mission-programs__icon">{{ $program['icon'] }}</span>@endif
                                {{ $program['label'] ?? '' }}
                            </li>
                        @endforeach
                    </ul>
                    @endif
                    @if(!empty($page['for_everyone']['outro']))
                        <p class="bns-why-extra__outro">{!! bns_rich_text($page['for_everyone']['outro']) !!}</p>
                    @endif
                </section>
                @endif

                @if(!empty($page['approach']))
                <section class="bns-why-thousands">
                    <h3>{{ $page['approach']['title'] ?? 'The BNS Approach' }}</h3>
                    @if(!empty($page['approach']['intro']))
                        <p class="bns-why-extra__intro">{!! bns_rich_text($page['approach']['intro']) !!}</p>
                    @endif
                    <ul class="bns-why-checklist list-unstyled">
                        @foreach($page['approach']['items'] ?? [] as $item)
                            <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
                        @endforeach
                    </ul>
                </section>
                @endif

                @if(!empty($page['belief']))
                <section class="bns-why-belief">
                    <h3>{{ $page['belief']['title'] ?? 'Our Belief' }}</h3>
                    <p>{!! bns_rich_text($page['belief']['text'] ?? '') !!}</p>
                </section>
                @endif

                @if(!empty($page['why_thousands']))
                <section class="bns-why-thousands">
                    <h3>{{ $page['why_thousands']['title'] ?? '' }}</h3>
                    <ul class="bns-why-checklist list-unstyled">
                        @foreach($page['why_thousands']['items'] ?? [] as $item)
                            <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
                        @endforeach
                    </ul>
                </section>
                @endif

                @if(!empty($page['closing']))
                <div class="bns-why-closing">
                    @if(!empty($page['closing']['brand']))
                        <p class="bns-why-closing__brand">{!! bns_rich_text($page['closing']['brand']) !!}</p>
                    @endif
                    @if(!empty($page['closing']['subtitle']))
                        <p class="bns-why-closing__subtitle">{!! bns_rich_text($page['closing']['subtitle']) !!}</p>
                    @endif
                    @if(!empty($page['closing']['tagline']))
                        <p class="bns-why-closing__tagline">{!! bns_rich_text($page['closing']['tagline']) !!}</p>
                    @endif
                    @if(!empty($page['closing']['hindi']))
                        <p class="bns-why-closing__hindi">{!! bns_rich_text($page['closing']['hindi']) !!}</p>
                    @endif
                </div>
                @endif

                <div class="bns-vision-actions">
                    <button type="button" class="bns-vision-actions__btn bns-vision-actions__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                        <i class="fas fa-rocket"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
                    </button>
                    <a href="{{ route('about.why') }}" class="bns-vision-actions__btn">
                        <i class="fas fa-star"></i> Why BNS?
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
