@extends('layouts.front')

@section('title', $brochure['title'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/brochure-page.css') }}" />
@endpush

@section('content')
<div class="bns-brochure-page">
    @include('partials.page-header', [
        'title' => $brochure['title'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $brochure['title']],
        ],
    ])

    <section class="bns-brochure-intro">
        <div class="container">
            <p class="bns-brochure-intro__subtitle">{{ $brochure['subtitle'] }}</p>
            <p class="bns-brochure-intro__text">{{ $brochure['intro'] }}</p>
            @if($brochure['has_pdf'])
                <div class="bns-brochure-intro__actions">
                    <a href="{{ $brochure['download_url'] }}" class="bns-brochure-btn bns-brochure-btn--primary">
                        <i class="fas fa-download"></i> Download PDF
                    </a>
                    <button type="button" class="bns-brochure-btn bns-brochure-btn--outline" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                        <i class="fas fa-graduation-cap"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
                    </button>
                    <a href="{{ route('contact') }}" class="bns-brochure-btn bns-brochure-btn--outline">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
            @endif
        </div>
    </section>

    <section class="bns-brochure-viewer">
        <div class="container">
            @if($brochure['has_pdf'])
                <div class="bns-brochure-card">
                    <div class="bns-brochure-card__toolbar">
                        <span><i class="fas fa-file-pdf"></i> Official BNS Brochure</span>
                        <a href="{{ $brochure['download_url'] }}" class="bns-brochure-card__download">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                    <div class="bns-brochure-card__frame-wrap">
                        <iframe
                            src="{{ $brochure['url'] }}#toolbar=1&navpanes=0"
                            title="{{ $brochure['title'] }}"
                            class="bns-brochure-card__frame"
                        ></iframe>
                    </div>
                    <p class="bns-brochure-card__hint">
                        If the brochure does not display, <a href="{{ $brochure['download_url'] }}">download the PDF</a> or open it in a new tab:
                        <a href="{{ $brochure['url'] }}" target="_blank" rel="noopener noreferrer">View PDF</a>.
                    </p>
                </div>
            @else
                <div class="bns-brochure-empty">
                    <div class="bns-brochure-empty__icon"><i class="fas fa-file-pdf"></i></div>
                    <h2>Brochure Coming Soon</h2>
                    <p>The official BNS program brochure will be published here shortly. For program details and admission support, please contact us.</p>
                    <div class="bns-brochure-intro__actions">
                        <a href="{{ route('contact') }}" class="bns-brochure-btn bns-brochure-btn--primary">
                            <i class="fas fa-envelope"></i> Contact Admission Office
                        </a>
                        <a href="{{ route('admissions.index') }}" class="bns-brochure-btn bns-brochure-btn--outline">
                            <i class="fas fa-info-circle"></i> Admissions Hub
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
