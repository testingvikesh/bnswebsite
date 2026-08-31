@extends('layouts.front')

@section('title', $page->page_title)

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/social-page.css') }}" />
@endpush

@section('content')
<div class="bns-social-page">
    @include('partials.page-header', [
        'title' => $page->page_title,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page->page_title],
        ],
    ])

    <section class="bns-social-intro">
        <div class="container">
            @if($page->page_subtitle)
                <h2 class="bns-social-intro__subtitle">{{ $page->page_subtitle }}</h2>
            @endif
            <p class="bns-social-intro__text">{{ $page->page_intro }}</p>
            @if($page->page_intro_2)
                <p class="bns-social-intro__text">{{ $page->page_intro_2 }}</p>
            @endif
        </div>
    </section>

    <section class="bns-social-hub">
        <div class="container">
            @if($page->platforms_title && !empty($page->platforms))
            <div class="bns-social-section-head">
                <span class="bns-social-card__label">Connect</span>
                <h3 class="bns-social-section-head__title">{{ $page->platforms_title }}</h3>
            </div>
            <div class="row g-4">
                @foreach($page->platforms as $platform)
                <div class="col-md-6 col-lg-4">
                    <div class="bns-social-platform bns-social-platform--{{ $platform['accent'] ?? 'default' }}">
                        <div class="bns-social-platform__icon">{{ $platform['icon'] ?? '🔗' }}</div>
                        <h4 class="bns-social-platform__name">{{ $platform['name'] ?? '' }}</h4>
                        @if(!empty($platform['description']))
                            <p class="bns-social-platform__desc">{{ $platform['description'] }}</p>
                        @endif
                        <a href="{{ $platform['url'] ?? '#' }}" class="bns-social-platform__btn" target="_blank" rel="noopener noreferrer">
                            <span class="bns-social-platform__btn-dot" aria-hidden="true"></span>
                            {{ $platform['button_label'] ?? 'Follow' }}
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            @if($page->benefits_title && !empty($page->benefits_items))
            <div class="bns-social-card bns-social-card--benefits">
                <h3 class="bns-social-card__title">{{ $page->benefits_title }}</h3>
                <ul class="bns-social-benefits list-unstyled">
                    @foreach($page->benefits_items as $item)
                        <li><i class="fas fa-check-circle"></i>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if($page->movement_title)
            <div class="bns-social-banner">
                <h3>{{ $page->movement_title }}</h3>
                @if($page->movement_text)<p>{{ $page->movement_text }}</p>@endif
                @if($page->movement_text_2)<p>{{ $page->movement_text_2 }}</p>@endif
            </div>
            @endif

            @if($page->quick_connect_title && !empty($page->platforms))
            <div class="bns-social-card bns-social-card--quick">
                <h3 class="bns-social-card__title">{{ $page->quick_connect_title }}</h3>
                <div class="bns-social-quick">
                    @foreach($page->platforms as $platform)
                        @if(($platform['name'] ?? '') === 'WhatsApp Community')
                            @continue
                        @endif
                        @php
                            $quickIcon = match ($platform['accent'] ?? 'default') {
                                'facebook' => '🟦',
                                'instagram' => '🟪',
                                'youtube' => '🔴',
                                'linkedin' => '🔵',
                                'twitter' => '⚫',
                                'whatsapp' => '🟢',
                                'telegram' => '🔵',
                                'threads' => '🟣',
                                default => $platform['icon'] ?? '🔗',
                            };
                        @endphp
                        <a href="{{ $platform['url'] ?? '#' }}" class="bns-social-quick__btn bns-social-quick__btn--{{ $platform['accent'] ?? 'default' }}" target="_blank" rel="noopener noreferrer">
                            <span class="bns-social-quick__icon">{{ $quickIcon }}</span>
                            <span>{{ $platform['name'] ?? 'Social' }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($page->tagline_brand)
            <div class="bns-social-tagline">
                <p class="bns-social-tagline__brand">{{ $page->tagline_brand }}</p>
                @if($page->tagline_text)<p class="bns-social-tagline__text">{{ $page->tagline_text }}</p>@endif
                @if($page->tagline_subtext)<p class="bns-social-tagline__sub">{{ $page->tagline_subtext }}</p>@endif
                @if($page->tagline_hindi)<p class="bns-social-tagline__hindi">{{ $page->tagline_hindi }}</p>@endif
            </div>
            @endif
        </div>
    </section>
</div>
@endsection
