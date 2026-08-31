@extends('layouts.front')

@section('title', $mission['title'].' – '.$mission['subtitle'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/about-page.css') }}" />
@endpush

@section('content')
<div class="bns-about-page bns-mission-page">
    @include('partials.page-header', [
        'title' => $mission['title'].' – '.$mission['subtitle'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'About Us', 'url' => route('about')],
            ['label' => $mission['title']],
        ],
    ])

    <section class="bns-vision-content">
        <div class="container">
            <div class="bns-vision-card">
                <a href="{{ route('about') }}" class="bns-vision-back"><i class="fas fa-arrow-left"></i> Back to About Us</a>

                <div class="bns-vision-header">
                    <span class="bns-vision-header__label">Mission</span>
                    <h2>{{ $mission['section_title'] ?? 'Our Mission' }}</h2>
                </div>

                @if(!empty($mission['intro']))
                    @if(is_array($mission['intro']))
                        @foreach($mission['intro'] as $paragraph)
                            <p class="bns-vision-intro">{!! bns_rich_text($paragraph) !!}</p>
                        @endforeach
                    @else
                        <p class="bns-vision-intro">{!! bns_rich_text($mission['intro']) !!}</p>
                    @endif
                @endif

                <ol class="bns-vision-points">
                    @foreach($mission['points'] ?? [] as $point)
                        <li>{!! bns_rich_text($point) !!}</li>
                    @endforeach
                </ol>

                @if(!empty($mission['statement']))
                <blockquote class="bns-vision-statement">
                    <h3>{{ $mission['statement_title'] ?? 'Mission Statement' }}</h3>
                    <p>&ldquo;{!! bns_rich_text($mission['statement']) !!}&rdquo;</p>
                </blockquote>
                @endif

                @if(!empty($mission['taglines']))
                <div class="bns-vision-taglines">
                    @foreach($mission['taglines'] as $tagline)
                        <p class="bns-vision-tagline">{!! bns_rich_text($tagline) !!}</p>
                    @endforeach
                </div>
                @endif

                <div class="bns-vision-actions">
                    <button type="button" class="bns-vision-actions__btn bns-vision-actions__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                        <i class="fas fa-rocket"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
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
