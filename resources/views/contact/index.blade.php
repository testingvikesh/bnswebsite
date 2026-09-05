@extends('layouts.front')

@section('title', $page->page_title)

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/contact-page.css') }}" />
@endpush

@section('content')
<div class="bns-contact-page">
    @include('partials.page-header', [
        'title' => $page->page_title,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page->page_title],
        ],
    ])

    <section class="bns-contact-intro">
        <div class="container">
            @if($page->page_subtitle)
                <h2 class="bns-contact-intro__subtitle">{{ $page->page_subtitle }}</h2>
            @endif
            <p class="bns-contact-intro__text">{!! bns_rich_text($page->page_intro) !!}</p>
            @if($page->page_intro_2)
                <p class="bns-contact-intro__text">{!! bns_rich_text($page->page_intro_2) !!}</p>
            @endif
        </div>
    </section>

    <section class="bns-contact-hub">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-5">
                    @include('contact.partials.office-card', ['page' => $page])
                </div>
                <div class="col-lg-7">
                    <div class="bns-contact-card bns-contact-card--quick">
                        <span class="bns-contact-card__label">Quick Connect</span>
                        <h3 class="bns-contact-card__title">Need Immediate Assistance?</h3>
                        <div class="bns-contact-immediate__actions bns-contact-immediate__actions--stack">
                            @if($page->phone_helpline)
                                <a href="tel:{{ preg_replace('/\D+/', '', $page->phone_helpline) }}" class="bns-contact-immediate__btn"><i class="fas fa-phone"></i> {{ $page->phone_helpline }}</a>
                            @endif
                            @if($page->phone_office)
                                <a href="tel:{{ preg_replace('/\D+/', '', $page->phone_office) }}" class="bns-contact-immediate__btn"><i class="fas fa-phone"></i> {{ $page->phone_office }}</a>
                            @endif
                            @if($page->immediate_whatsapp)
                                <a href="https://wa.me/{{ preg_replace('/\D+/', '', $page->immediate_whatsapp) }}" class="bns-contact-immediate__btn" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> WhatsApp Support</a>
                            @endif
                            @if($page->email_admissions)
                                <a href="mailto:{{ $page->email_admissions }}" class="bns-contact-immediate__btn"><i class="fas fa-envelope"></i> Email Support</a>
                            @endif
                        </div>
                        <a href="#contact-form" class="thm-btn bns-contact-now-cta">Contact Now <span class="fas fa-arrow-right"></span></a>
                    </div>
                </div>
            </div>

            <div class="bns-contact-form-section">
                @include('contact.partials.form-card', ['page' => $page, 'formConfig' => $formConfig])
            </div>

            <div class="row g-4 bns-contact-sections">
                @if($page->admission_support_title)
                <div class="col-lg-6">
                    <div class="bns-contact-card bns-contact-card--section">
                        <span class="bns-contact-card__label">Guidance</span>
                        <h3 class="bns-contact-card__title">{{ $page->admission_support_title }}</h3>
                        @if($page->admission_support_intro)<p>{!! bns_rich_text($page->admission_support_intro) !!}</p>@endif
                        @if(!empty($page->admission_support_items))
                        <ul class="bns-contact-checklist list-unstyled">
                            @foreach($page->admission_support_items as $item)
                                <li><i class="fas fa-check-circle"></i>{!! bns_rich_text($item) !!}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                @endif
                @if($page->partnership_title)
                <div class="col-lg-6">
                    <div class="bns-contact-card bns-contact-card--section">
                        <span class="bns-contact-card__label">Partnerships</span>
                        <h3 class="bns-contact-card__title">{{ $page->partnership_title }}</h3>
                        @if($page->partnership_intro)<p>{!! bns_rich_text($page->partnership_intro) !!}</p>@endif
                        @if(!empty($page->partnership_items))
                        <ul class="bns-contact-checklist list-unstyled">
                            @foreach($page->partnership_items as $item)
                                <li><i class="fas fa-handshake"></i>{!! bns_rich_text($item) !!}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            @if($page->faculty_cta_title)
            <div class="bns-contact-banner bns-contact-banner--faculty">
                <h3>{{ $page->faculty_cta_title }}</h3>
                @if($page->faculty_cta_text)<p>{!! bns_rich_text($page->faculty_cta_text) !!}</p>@endif
                @if($page->faculty_cta_url)
                    <a href="{{ url($page->faculty_cta_url) }}" class="thm-btn thm-btn--light">Learn More <span class="fas fa-arrow-right"></span></a>
                @endif
            </div>
            @endif

            @if($page->media_title)
            <div class="bns-contact-card bns-contact-card--media">
                <h3>{{ $page->media_title }}</h3>
                @if($page->media_text)<p>{!! bns_rich_text($page->media_text) !!}</p>@endif
                @if($page->email_media)
                    <a href="mailto:{{ $page->email_media }}" class="bns-contact-media-email"><i class="fas fa-envelope"></i> {{ $page->email_media }}</a>
                @endif
            </div>
            @endif

            @if($page->mapsEmbedSrc())
            <div class="bns-contact-card bns-contact-card--map">
                <h3><i class="fas fa-map-marked-alt"></i> Find Us on Google Maps</h3>
                <div class="bns-contact-map">
                    <iframe src="{{ $page->mapsEmbedSrc() }}" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="BNS location on Google Maps"></iframe>
                </div>
                <a href="{{ $page->mapsUrl() }}" class="bns-contact-map-btn" target="_blank" rel="noopener noreferrer">
                    <i class="fas fa-directions"></i> Open in Google Maps
                </a>
            </div>
            @endif

            @if($page->immediate_title)
            <div class="bns-contact-immediate">
                <h3>{{ $page->immediate_title }}</h3>
                <div class="bns-contact-immediate__actions">
                    @if($page->phone_helpline)
                        <a href="tel:{{ preg_replace('/\D+/', '', $page->phone_helpline) }}" class="bns-contact-immediate__btn"><i class="fas fa-phone"></i> {{ $page->phone_helpline }}</a>
                    @endif
                    @if($page->phone_office)
                        <a href="tel:{{ preg_replace('/\D+/', '', $page->phone_office) }}" class="bns-contact-immediate__btn"><i class="fas fa-phone"></i> {{ $page->phone_office }}</a>
                    @endif
                    @if($page->immediate_whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', $page->immediate_whatsapp) }}" class="bns-contact-immediate__btn" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i> WhatsApp Us</a>
                    @endif
                    @if($page->immediate_apply_url)
                        @if(in_array(trim($page->immediate_apply_url, '/'), ['register', trim(url('/register'), '/')], true))
                            <button type="button" class="bns-contact-immediate__btn bns-contact-immediate__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal"><i class="fas fa-graduation-cap"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}</button>
                        @else
                            <a href="{{ url($page->immediate_apply_url) }}" class="bns-contact-immediate__btn bns-contact-immediate__btn--primary"><i class="fas fa-graduation-cap"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}</a>
                        @endif
                    @endif
                </div>
            </div>
            @endif

            @if($page->tagline_brand)
            <div class="bns-contact-tagline">
                <p class="bns-contact-tagline__brand">{{ $page->tagline_brand }}</p>
                @if($page->tagline_text)<p class="bns-contact-tagline__text">{!! bns_rich_text($page->tagline_text) !!}</p>@endif
                @if($page->tagline_subtext)<p class="bns-contact-tagline__sub">{!! bns_rich_text($page->tagline_subtext) !!}</p>@endif
                @if($page->tagline_hindi)<p class="bns-contact-tagline__hindi">{!! bns_rich_text($page->tagline_hindi) !!}</p>@endif
            </div>
            @endif
        </div>
    </section>
</div>
@endsection
