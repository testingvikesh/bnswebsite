@extends('layouts.front')

@section('title', $vision['title'].' – '.$vision['subtitle'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/about-page.css') }}" />
@endpush

@section('content')
<div class="bns-about-page bns-vision-page">
    @include('partials.page-header', [
        'title' => $vision['title'].' – '.$vision['subtitle'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'About Us', 'url' => route('about')],
            ['label' => $vision['title']],
        ],
    ])

    <section class="bns-vision-content">
        <div class="container">
            <div class="bns-vision-card">
                <a href="{{ route('about') }}" class="bns-vision-back"><i class="fas fa-arrow-left"></i> Back to About Us</a>

                <div class="bns-vision-header">
                    <span class="bns-vision-header__label">Vision</span>
                    <h2>{{ $vision['section_title'] ?? 'Our Vision' }}</h2>
                </div>

                @if(!empty($vision['intro']))
                    <p class="bns-vision-intro">{!! bns_rich_text($vision['intro']) !!}</p>
                @endif

                <ol class="bns-vision-points">
                    @foreach($vision['points'] ?? [] as $point)
                        <li>{!! bns_rich_text($point) !!}</li>
                    @endforeach
                </ol>

                @if(!empty($vision['statement']))
                <blockquote class="bns-vision-statement">
                    <h3>{{ $vision['statement_title'] ?? 'Vision Statement' }}</h3>
                    <p>&ldquo;{!! bns_rich_text($vision['statement']) !!}&rdquo;</p>
                </blockquote>
                @endif

                @if(!empty($vision['taglines']))
                <div class="bns-vision-taglines">
                    @foreach($vision['taglines'] as $tagline)
                        <p class="bns-vision-tagline">{!! bns_rich_text($tagline) !!}</p>
                    @endforeach
                </div>
                @endif

                <div class="bns-vision-actions">
                    <button type="button" class="bns-vision-actions__btn bns-vision-actions__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                        <i class="fas fa-rocket"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
                    </button>
                    <a href="{{ route('about.mission') }}" class="bns-vision-actions__btn">
                        <i class="fas fa-bullseye"></i> Our Mission
                    </a>
                    <a href="{{ route('admissions.index') }}" class="bns-vision-actions__btn">
                        <i class="fas fa-graduation-cap"></i> Explore Admissions
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
