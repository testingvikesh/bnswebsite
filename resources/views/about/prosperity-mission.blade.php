@extends('layouts.front')

@section('title', $page['title'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/about-page.css') }}" />
@endpush

@section('content')
<div class="bns-about-page bns-prosperity-page">
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
            <div class="bns-vision-card bns-prosperity-card">
                <a href="{{ route('about') }}" class="bns-vision-back"><i class="fas fa-arrow-left"></i> Back to About Us</a>

                <div class="bns-vision-header">
                    <span class="bns-vision-header__label">{{ $page['label'] ?? 'Prosperity' }}</span>
                    @if(!empty($page['subtitle']))
                        <h2>{{ $page['subtitle'] }}</h2>
                    @endif
                </div>

                @foreach($page['intro'] ?? [] as $paragraph)
                    <p class="bns-mission-intro">{!! bns_rich_text($paragraph) !!}</p>
                @endforeach

                @foreach(['national_mission', 'why_prosperity', 'role_of_education'] as $sectionKey)
                    @php $section = $page[$sectionKey] ?? null; @endphp
                    @if(!empty($section))
                    <section class="bns-prosperity-section">
                        <h3>{{ $section['title'] ?? '' }}</h3>
                        @if(!empty($section['intro']))
                            <p class="bns-prosperity-section__intro">{!! bns_rich_text($section['intro']) !!}</p>
                        @endif
                        @if(!empty($section['items']))
                        <ul class="bns-prosperity-list list-unstyled">
                            @foreach($section['items'] as $item)
                                <li><i class="fas fa-check"></i> {!! bns_rich_text($item) !!}</li>
                            @endforeach
                        </ul>
                        @endif
                        @if(!empty($section['outro']))
                            <p class="bns-prosperity-section__outro">{!! bns_rich_text($section['outro']) !!}</p>
                        @endif
                    </section>
                    @endif
                @endforeach

                @if(!empty($page['movement']))
                <section class="bns-prosperity-section bns-prosperity-section--highlight">
                    <h3>{{ $page['movement']['title'] ?? '' }}</h3>
                    @if(!empty($page['movement']['intro']))
                        <p class="bns-prosperity-section__intro">{!! bns_rich_text($page['movement']['intro']) !!}</p>
                    @endif
                    @if(!empty($page['movement']['programs']))
                    <ul class="bns-mission-programs list-unstyled">
                        @foreach($page['movement']['programs'] as $program)
                            <li>
                                @if(!empty($program['icon']))<span class="bns-mission-programs__icon">{{ $program['icon'] }}</span>@endif
                                {{ $program['label'] ?? '' }}
                            </li>
                        @endforeach
                    </ul>
                    @endif
                    @if(!empty($page['movement']['outro']))
                        <p class="bns-prosperity-section__outro">{!! bns_rich_text($page['movement']['outro']) !!}</p>
                    @endif
                </section>
                @endif

                @if(!empty($page['viksit_bharat']))
                <section class="bns-prosperity-viksit">
                    <h3>{{ $page['viksit_bharat']['title'] ?? '' }}</h3>
                    @if(!empty($page['viksit_bharat']['intro']))
                        <p class="bns-prosperity-section__intro">{!! bns_rich_text($page['viksit_bharat']['intro']) !!}</p>
                    @endif
                    <div class="bns-prosperity-tags">
                        @foreach($page['viksit_bharat']['items'] ?? [] as $item)
                            <span class="bns-prosperity-tag">{!! bns_rich_text($item) !!}</span>
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

                @if(!empty($page['belief']))
                <section class="bns-prosperity-belief">
                    <h3>{{ $page['belief']['title'] ?? 'Our Belief' }}</h3>
                    <ul class="bns-why-chain list-unstyled">
                        @foreach($page['belief']['lines'] ?? [] as $line)
                            <li>{!! bns_rich_text($line) !!}</li>
                        @endforeach
                    </ul>
                </section>
                @endif

                @if(!empty($page['closing']))
                <div class="bns-why-closing bns-prosperity-closing">
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
                        <i class="fas fa-rocket"></i> Join the Mission — {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
                    </button>
                    <a href="{{ route('about.vision') }}" class="bns-vision-actions__btn">
                        <i class="fas fa-eye"></i> Our Vision
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
