@extends('layouts.front')

@section('title', $page->page_title)

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/whatsapp-page.css') }}" />
@endpush

@section('content')
<div class="bns-whatsapp-page">
    @include('partials.page-header', [
        'title' => $page->page_title,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page->page_title],
        ],
    ])

    <section class="bns-whatsapp-intro">
        <div class="container">
            @if($page->page_subtitle)
                <h2 class="bns-whatsapp-intro__subtitle">{{ $page->page_subtitle }}</h2>
            @endif
            <p class="bns-whatsapp-intro__text">{{ $page->page_intro }}</p>
            @if($page->page_intro_2)
                <p class="bns-whatsapp-intro__text">{{ $page->page_intro_2 }}</p>
            @endif
        </div>
    </section>

    <section class="bns-whatsapp-hub">
        <div class="container">
            @if($page->help_title)
            <div class="bns-whatsapp-card bns-whatsapp-card--help">
                <span class="bns-whatsapp-card__label">Support</span>
                <h3 class="bns-whatsapp-card__title">{{ $page->help_title }}</h3>
                @if($page->help_intro)<p class="bns-whatsapp-card__text">{{ $page->help_intro }}</p>@endif
                @if(!empty($page->help_items))
                <ul class="bns-whatsapp-checklist list-unstyled">
                    @foreach($page->help_items as $item)
                        <li><i class="fas fa-check-circle"></i>{{ $item }}</li>
                    @endforeach
                </ul>
                @endif
            </div>
            @endif

            <div class="row g-4 bns-whatsapp-row">
                <div class="col-lg-5">
                    <div class="bns-whatsapp-card bns-whatsapp-card--chat">
                        <span class="bns-whatsapp-card__label">WhatsApp</span>
                        <h3 class="bns-whatsapp-card__title">{{ $page->chat_title ?? 'Chat With Us' }}</h3>
                        @if($page->whatsapp_number)
                        <div class="bns-whatsapp-number">
                            <i class="fab fa-whatsapp"></i>
                            <div>
                                <span class="bns-whatsapp-number__label">WhatsApp Number</span>
                                <a href="{{ $page->whatsappLink() }}" target="_blank" rel="noopener" class="bns-whatsapp-number__value">{{ $page->whatsapp_number }}</a>
                            </div>
                        </div>
                        @endif
                        @if($page->availability_label)
                        <div class="bns-whatsapp-availability">
                            <span class="bns-whatsapp-availability__badge"><i class="fas fa-circle"></i> {{ $page->availability_label }}</span>
                            @if(!empty($page->availability_hours))
                                @foreach($page->availability_hours as $hour)
                                    <p>{{ $hour }}</p>
                                @endforeach
                            @endif
                        </div>
                        @endif
                        <a href="{{ $page->whatsappLink('Hi, I need support from Business Navachar School.') }}" class="bns-whatsapp-btn bns-whatsapp-btn--main" target="_blank" rel="noopener">
                            <i class="fab fa-whatsapp"></i> Start WhatsApp Chat
                        </a>
                    </div>
                </div>
                <div class="col-lg-7">
                    @if($page->before_chat_title)
                    <div class="bns-whatsapp-card bns-whatsapp-card--tips">
                        <h3 class="bns-whatsapp-card__title">{{ $page->before_chat_title }}</h3>
                        @if($page->before_chat_intro)<p class="bns-whatsapp-card__text">{{ $page->before_chat_intro }}</p>@endif
                        @if(!empty($page->before_chat_items))
                        <ul class="bns-whatsapp-tips list-unstyled">
                            @foreach($page->before_chat_items as $item)
                                <li><i class="fas fa-dot-circle"></i>{{ $item }}</li>
                            @endforeach
                        </ul>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            @if(!empty($page->quick_options))
            <div class="bns-whatsapp-card bns-whatsapp-card--options">
                <h3 class="bns-whatsapp-card__title">Quick WhatsApp Options</h3>
                <div class="row g-3">
                    @foreach($page->quick_options as $option)
                    <div class="col-md-6 col-lg-4">
                        <div class="bns-whatsapp-option">
                            <div class="bns-whatsapp-option__icon">{{ $option['icon'] ?? '💬' }}</div>
                            <h4 class="bns-whatsapp-option__title">{{ $option['label'] ?? '' }}</h4>
                            <a href="{{ $page->whatsappLink($option['message'] ?? '') }}" class="bns-whatsapp-btn bns-whatsapp-btn--sm" target="_blank" rel="noopener">
                                Chat Now <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($page->one_tap_actions))
            <div class="bns-whatsapp-card bns-whatsapp-card--tap">
                <h3 class="bns-whatsapp-card__title">One-Tap WhatsApp Actions</h3>
                <div class="bns-whatsapp-tap-actions">
                    @foreach($page->one_tap_actions as $action)
                        @php
                            $isWhatsapp = ($action['type'] ?? 'whatsapp') === 'whatsapp';
                            $href = $isWhatsapp
                                ? $page->whatsappLink($action['message'] ?? '')
                                : url($action['url'] ?? '/');
                        @endphp
                        <a href="{{ $href }}" class="bns-whatsapp-tap-btn" @if($isWhatsapp) target="_blank" rel="noopener" @endif>
                            <i class="fab fa-whatsapp"></i> {{ $action['label'] ?? 'Action' }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if($page->immediate_title)
            <div class="bns-whatsapp-immediate">
                <h3>{{ $page->immediate_title }}</h3>
                <div class="bns-whatsapp-immediate__actions">
                    @if($page->immediate_phone)
                        <a href="tel:{{ preg_replace('/\D+/', '', $page->immediate_phone) }}" class="bns-whatsapp-immediate__btn"><i class="fas fa-phone"></i> Call Admission Office</a>
                    @endif
                    @if($page->immediate_email)
                        <a href="mailto:{{ $page->immediate_email }}" class="bns-whatsapp-immediate__btn"><i class="fas fa-envelope"></i> Email Support</a>
                    @endif
                    @if($page->websiteUrl())
                        <a href="{{ $page->websiteUrl() }}" class="bns-whatsapp-immediate__btn" target="_blank" rel="noopener"><i class="fas fa-globe"></i> Visit Our Website</a>
                    @endif
                    @if($page->immediate_centre_url)
                        <a href="{{ url($page->immediate_centre_url) }}" class="bns-whatsapp-immediate__btn"><i class="fas fa-map-marker-alt"></i> Visit the Nearest BNS Centre</a>
                    @endif
                </div>
            </div>
            @endif

            @if($page->tagline_brand)
            <div class="bns-whatsapp-tagline">
                <p class="bns-whatsapp-tagline__brand">{{ $page->tagline_brand }}</p>
                @if($page->tagline_text)<p class="bns-whatsapp-tagline__text">{{ $page->tagline_text }}</p>@endif
                @if($page->tagline_subtext)<p class="bns-whatsapp-tagline__sub">{{ $page->tagline_subtext }}</p>@endif
                @if($page->tagline_hindi)<p class="bns-whatsapp-tagline__hindi">{{ $page->tagline_hindi }}</p>@endif
            </div>
            @endif
        </div>
    </section>
</div>
@endsection
