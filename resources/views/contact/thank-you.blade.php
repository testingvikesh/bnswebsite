@extends('layouts.front')

@section('title', $messages['title'] ?? 'Thank You')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/contact-thank-you.css') }}" />
@endpush

@section('content')
<div class="bns-contact-thank-you">
    <section class="bns-contact-thank-you__hero">
        <div class="container">
            <div class="bns-contact-thank-you__card">
                <div class="bns-contact-thank-you__icon" aria-hidden="true">
                    <i class="fas fa-check-circle"></i>
                </div>
                <span class="bns-contact-thank-you__eyebrow">{{ $messages['eyebrow'] }}</span>
                <h1>{{ $messages['title'] }}</h1>
                <p class="bns-contact-thank-you__lead">{!! bns_rich_text($messages['thank_you']) !!}</p>
                <p class="bns-contact-thank-you__ref-label">{{ $messages['reference_label'] }}</p>
                <p class="bns-contact-thank-you__ref-number">{{ $thankYou['registration_number'] }}</p>
                <p class="bns-contact-thank-you__soon">{!! bns_rich_text($messages['contact_soon']) !!}</p>

                @if(!empty($thankYou['interested_program']) || !empty($thankYou['mobile']))
                    <div class="bns-contact-thank-you__meta">
                        @if(!empty($thankYou['interested_program']))
                            <span><strong>Program:</strong> {{ $thankYou['interested_program'] }}</span>
                        @endif
                        @if(!empty($thankYou['mobile']))
                            <span><strong>Mobile:</strong> {{ $thankYou['mobile'] }}</span>
                        @endif
                    </div>
                @endif

                <div class="bns-contact-thank-you__pay">
                    <a href="{{ route('pay-now') }}" class="bns-contact-thank-you__pay-btn">
                        <i class="fas fa-credit-card"></i> Pay Now
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="bns-contact-thank-you__details">
        <div class="container">
            <div class="bns-contact-thank-you__details-card">
                <h2>{{ $messages['details_section']['title'] ?? 'Office Address & Location' }}</h2>
                @if(!empty($messages['details_section']['subtitle']))
                    <p class="bns-contact-thank-you__details-intro">{!! bns_rich_text($messages['details_section']['subtitle']) !!}</p>
                @endif

                @php($primary = $messages['primary_location'] ?? [])
                <article class="bns-contact-thank-you__location bns-contact-thank-you__location--primary">
                    <h3><i class="fas fa-map-marker-alt"></i> {{ $primary['label'] ?? 'BNS Program Venue' }}</h3>
                    <p class="bns-contact-thank-you__location-brand">{{ $primary['brand'] ?? '' }}</p>
                    <p class="bns-contact-thank-you__location-address">{{ $primary['address'] ?? '' }}</p>
                    @if(!empty($primary['maps_url']))
                        <a href="{{ $primary['maps_url'] }}" class="bns-contact-thank-you__map-link" target="_blank" rel="noopener">
                            <i class="fas fa-external-link-alt"></i> Open in Google Maps
                        </a>
                    @endif
                    @if(!empty($primary['maps_embed_url']))
                        <div class="bns-contact-thank-you__map-embed bns-contact-thank-you__map-embed--primary">
                            <iframe
                                src="{{ $primary['maps_embed_url'] }}"
                                width="100%"
                                height="320"
                                style="border:0;"
                                allowfullscreen=""
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                title="{{ $primary['label'] ?? 'Venue map' }}"
                            ></iframe>
                        </div>
                    @endif
                </article>

                <div class="bns-contact-thank-you__office-note">
                    <p><strong>Admission Office:</strong> Business Navachar School (BNS), Rajkot, Gujarat 360005</p>
                    <p><strong>Helpline:</strong> +91 72086 28671 | <strong>WhatsApp:</strong> +91 70218 39703</p>
                </div>

                <div class="bns-contact-thank-you__whatsapp">
                    <p class="bns-contact-thank-you__whatsapp-hint">{!! bns_rich_text($messages['whatsapp']['hint']) !!}</p>
                    <a
                        href="{{ $messages['whatsapp']['url'] }}"
                        class="bns-contact-thank-you__whatsapp-btn"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i class="fab fa-whatsapp"></i>
                        <span>
                            <strong>{{ $messages['whatsapp']['button_label'] }}</strong>
                            @if(!empty($messages['whatsapp']['button_sub']))
                                <small>{{ $messages['whatsapp']['button_sub'] }}</small>
                            @endif
                        </span>
                    </a>

                    @if(!empty($messages['whatsapp_group']['url']))
                        <div class="bns-contact-thank-you__whatsapp-group">
                            @if(!empty($messages['whatsapp_group']['hint']))
                                <p class="bns-contact-thank-you__whatsapp-hint">{!! bns_rich_text($messages['whatsapp_group']['hint']) !!}</p>
                            @endif
                            <a
                                href="{{ $messages['whatsapp_group']['url'] }}"
                                class="bns-contact-thank-you__whatsapp-btn bns-contact-thank-you__whatsapp-btn--group"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <i class="fab fa-whatsapp"></i>
                                <span>
                                    <strong>{{ $messages['whatsapp_group']['button_label'] }}</strong>
                                    @if(!empty($messages['whatsapp_group']['button_sub']))
                                        <small>{{ $messages['whatsapp_group']['button_sub'] }}</small>
                                    @endif
                                </span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bns-contact-thank-you__actions">
                <a href="{{ url('/') }}" class="thm-btn">{{ $messages['cta']['home'] ?? 'Back to Home' }}</a>
                <a href="{{ route('syllabus') }}" class="thm-btn thm-btn--outline">{{ $messages['cta']['programs'] ?? 'View Syllabus' }}</a>
                <a href="{{ route('contact') }}" class="bns-contact-thank-you__link">{{ $messages['cta']['contact'] ?? 'Contact Us' }}</a>
            </div>
        </div>
    </section>
</div>
@endsection
