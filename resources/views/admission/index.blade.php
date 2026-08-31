@extends('layouts.front')

@section('title', $hub->page_title)

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admission-page.css') }}" />
@endpush

@section('content')
<div class="bns-admission-page">
    @include('partials.page-header', [
        'title' => $hub->page_title,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $hub->page_title],
        ],
    ])

    <section class="bns-admission-hub-intro">
        <div class="container">
            @if($hub->page_subtitle)
                <h2 class="bns-admission-hub-intro__subtitle">{{ $hub->page_subtitle }}</h2>
            @endif
            <p class="bns-admission-hub-intro__text">{!! bns_rich_text($hub->page_intro) !!}</p>
            @if($hub->page_intro_2)
                <p class="bns-admission-hub-intro__text">{!! bns_rich_text($hub->page_intro_2) !!}</p>
            @endif
            <button type="button" class="bns-admission-hero-cta" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                <i class="fas fa-rocket"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }} — Start Online Admission
            </button>
        </div>
    </section>

    <section class="bns-admission-hub">
        <div class="container">
            @foreach($hub->groupedMenu() as $groupName => $items)
            <div class="bns-admission-hub-group">
                <h3 class="bns-admission-hub-group__title">{{ $groupName }}</h3>
                <div class="row g-4">
                    @foreach($items as $item)
                    <div class="col-md-6 col-lg-4">
                        @if(($item['slug'] ?? '') === 'apply-now')
                        <button type="button" class="bns-admission-hub-card bns-admission-hub-card--featured" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                            <div class="bns-admission-hub-card__icon"><i class="{{ $item['icon'] ?? 'fas fa-link' }}"></i></div>
                            <h4 class="bns-admission-hub-card__title">{{ $item['label'] ?? '' }}</h4>
                            @if(!empty($item['description']))
                                <p class="bns-admission-hub-card__desc">{!! bns_rich_text($item['description']) !!}</p>
                            @endif
                            <span class="bns-admission-hub-card__link">
                                Start Application
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </button>
                        @else
                        <a href="{{ $hub->menuUrl($item) }}" class="bns-admission-hub-card">
                            <div class="bns-admission-hub-card__icon"><i class="{{ $item['icon'] ?? 'fas fa-link' }}"></i></div>
                            <h4 class="bns-admission-hub-card__title">{{ $item['label'] ?? '' }}</h4>
                            @if(!empty($item['description']))
                                <p class="bns-admission-hub-card__desc">{!! bns_rich_text($item['description']) !!}</p>
                            @endif
                            <span class="bns-admission-hub-card__link">
                                Learn More
                                <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            <div class="row g-4 bns-admission-hub-extras">
                <div class="col-lg-6">
                    <div class="bns-admission-card">
                        <h3>{{ $hub->after_admission_title }}</h3>
                        <ul class="bns-admission-list list-unstyled">
                            @foreach($hub->after_admission_items ?? [] as $item)
                                <li><i class="fas fa-star"></i> {!! bns_rich_text($item) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="bns-admission-card">
                        <h3>{{ $hub->dashboard_title }}</h3>
                        <div class="bns-admission-dashboard-grid">
                            @foreach($hub->dashboard_items ?? [] as $item)
                                <span class="bns-admission-dashboard-item"><i class="fas fa-th-large"></i> {{ $item }}</span>
                            @endforeach
                        </div>
                        <p class="text-muted small mt-3 mb-0">Student portal access is provided after admission confirmation.</p>
                    </div>
                </div>
            </div>

            <div class="bns-admission-card bns-admission-card--office">
                <h3>Admission Office</h3>
                <div class="row g-4">
                    <div class="col-lg-6">
                        <ul class="list-unstyled bns-admission-office">
                            <li><i class="fas fa-user-tie"></i> {{ $hub->office_counselor }}</li>
                            <li><i class="fab fa-whatsapp"></i> <a href="https://wa.me/{{ preg_replace('/\D+/', '', $hub->office_whatsapp) }}">{{ $hub->office_whatsapp }}</a></li>
                            <li><i class="fas fa-phone"></i> <a href="tel:{{ preg_replace('/\D+/', '', $hub->office_phone) }}">Call Now</a></li>
                            <li><i class="fas fa-envelope"></i> <a href="mailto:{{ $hub->office_email }}">{{ $hub->office_email }}</a></li>
                            <li><i class="fas fa-map-marker-alt"></i> {{ $hub->office_address }}</li>
                        </ul>
                    </div>
                    @if($hub->maps_embed_url)
                    <div class="col-lg-6">
                        <div class="bns-admission-map"><iframe src="{{ $hub->maps_embed_url }}" loading="lazy" title="BNS Admission Office"></iframe></div>
                    </div>
                    @endif
                </div>
            </div>

            @include('admission.partials.trust', ['hub' => $hub])
            @include('admission.partials.ctas')

            @if($hub->tagline_brand)
            <div class="bns-admission-tagline">
                <p class="bns-admission-tagline__brand">{{ $hub->tagline_brand }}</p>
                @if($hub->tagline_text)<p class="bns-admission-tagline__text">{!! bns_rich_text($hub->tagline_text) !!}</p>@endif
                @if($hub->tagline_subtext)<p class="bns-admission-tagline__sub">{!! bns_rich_text($hub->tagline_subtext) !!}</p>@endif
                @if($hub->tagline_hindi)<p class="bns-admission-tagline__hindi">{!! bns_rich_text($hub->tagline_hindi) !!}</p>@endif
            </div>
            @endif
        </div>
    </section>
</div>
@endsection
