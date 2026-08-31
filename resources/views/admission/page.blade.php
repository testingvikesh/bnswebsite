@extends('layouts.front')

@section('title', $page->page_title)

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admission-page.css') }}" />
@endpush

@section('content')
<div class="bns-admission-page">
    @include('partials.page-header', [
        'title' => $page->page_title,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Admissions', 'url' => route('admissions.index')],
            ['label' => $page->page_title],
        ],
    ])

    <section class="bns-admission-content">
        <div class="container">
            <div class="bns-admission-card bns-admission-card--section">
                <a href="{{ route('admissions.index') }}" class="bns-admission-back"><i class="fas fa-arrow-left"></i> Back to Admissions</a>
                @if(($slug ?? '') === 'eligibility-criteria' && !empty($eligibility))
                    @if(!empty($eligibility['subtitle']))
                        <h2 class="bns-admission-card__subtitle">{{ $eligibility['subtitle'] }}</h2>
                    @endif
                    @if(!empty($eligibility['intro']))
                        <p class="bns-admission-card__intro">{!! bns_rich_text($eligibility['intro']) !!}</p>
                    @endif
                    @include('admission.partials.eligibility-content', ['eligibility' => $eligibility])
                @elseif(($slug ?? '') === 'admission-process' && !empty($process))
                    @if(!empty($process['subtitle']))
                        <h2 class="bns-admission-card__subtitle">{{ $process['subtitle'] }}</h2>
                    @endif
                    @if(!empty($process['intro']))
                        <p class="bns-admission-card__intro">{!! bns_rich_text($process['intro']) !!}</p>
                    @endif
                    @include('admission.partials.process-content', ['process' => $process])
                @elseif(($slug ?? '') === 'faqs' && !empty($faqs))
                    @if(!empty($faqs['subtitle']))
                        <h2 class="bns-admission-card__subtitle">{{ $faqs['subtitle'] }}</h2>
                    @endif
                    @if(!empty($faqs['intro']))
                        <p class="bns-admission-card__intro">{!! bns_rich_text($faqs['intro']) !!}</p>
                    @endif
                    @include('admission.partials.faqs-content', ['faqs' => $faqs])
                @else
                @if($page->page_subtitle)
                    <h2 class="bns-admission-card__subtitle">{{ $page->page_subtitle }}</h2>
                @endif
                @if($page->page_intro)
                    <p class="bns-admission-card__intro">{!! bns_rich_text($page->page_intro) !!}</p>
                @endif
                @if(!empty($page->content_items))
                    <ul class="bns-admission-list list-unstyled">
                        @foreach($page->content_items as $item)
                            @if(is_string($item))
                                <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
                            @endif
                        @endforeach
                    </ul>
                @endif
                @endif
                @if($page->download_url && $page->download_url !== '#')
                    <a href="{{ $page->download_url }}" class="bns-admission-btn bns-admission-btn--primary mt-3" target="_blank" rel="noopener">
                        <i class="fas fa-download"></i> Download
                    </a>
                @endif
            </div>

            @if(!empty($showTrust))
                @include('admission.partials.trust', ['hub' => $hub ?? null])
            @endif

            @if(!empty($showAfterAdmission))
            <div class="bns-admission-card bns-admission-card--section">
                <h3>{{ $config['after_admission']['title'] }}</h3>
                <ul class="bns-admission-list list-unstyled">
                    @foreach($config['after_admission']['items'] as $item)
                        <li><i class="fas fa-star"></i> {!! bns_rich_text($item) !!}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(!empty($showDashboard))
            <div class="bns-admission-card bns-admission-card--section">
                <h3>{{ $config['student_dashboard']['title'] }}</h3>
                <div class="bns-admission-dashboard-grid">
                    @foreach($config['student_dashboard']['items'] as $item)
                        <span class="bns-admission-dashboard-item"><i class="fas fa-th-large"></i> {{ $item }}</span>
                    @endforeach
                </div>
                <p class="text-muted small mt-3 mb-0">Student portal login will be provided after admission confirmation.</p>
            </div>
            @endif

            @if(!empty($showOffice))
            <div class="bns-admission-card bns-admission-card--office">
                <h3>Admission Office</h3>
                <ul class="list-unstyled bns-admission-office">
                    <li><i class="fas fa-user-tie"></i> {{ $config['office']['counselor'] }}</li>
                    <li><i class="fab fa-whatsapp"></i> <a href="https://wa.me/{{ preg_replace('/\D+/', '', $config['office']['whatsapp']) }}">{{ $config['office']['whatsapp'] }}</a></li>
                    <li><i class="fas fa-phone"></i> <a href="tel:{{ preg_replace('/\D+/', '', $config['office']['phone']) }}">Call Now</a></li>
                    <li><i class="fas fa-envelope"></i> <a href="mailto:{{ $config['office']['email'] }}">{{ $config['office']['email'] }}</a></li>
                    <li><i class="fas fa-map-marker-alt"></i> {{ $config['office']['address'] }}</li>
                </ul>
                @if($config['office']['maps_embed_url'] ?? null)
                <div class="bns-admission-map mt-3">
                    <iframe src="{{ $config['office']['maps_embed_url'] }}" loading="lazy" title="BNS Admission Office"></iframe>
                </div>
                @endif
            </div>
            @endif

            @if(!in_array($slug ?? '', ['admission-process', 'faqs'], true))
            @include('admission.partials.ctas')
            @endif
        </div>
    </section>
</div>
@endsection
