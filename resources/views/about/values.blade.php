@extends('layouts.front')

@section('title', $values['title'].' – Business Navachar School')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/about-page.css') }}" />
@endpush

@section('content')
<div class="bns-about-page bns-values-page">
    @include('partials.page-header', [
        'title' => $values['title'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'About Us', 'url' => route('about')],
            ['label' => $values['title']],
        ],
    ])

    <section class="bns-vision-content">
        <div class="container">
            <div class="bns-vision-card bns-values-card">
                <a href="{{ route('about') }}" class="bns-vision-back"><i class="fas fa-arrow-left"></i> Back to About Us</a>

                <div class="bns-vision-header">
                    <span class="bns-vision-header__label">Values</span>
                    <h2>{{ $values['section_title'] ?? 'Core Values' }}</h2>
                    @if(!empty($values['subtitle']))
                        <p class="bns-values-subtitle">{{ $values['subtitle'] }}</p>
                    @endif
                </div>

                @foreach($values['intro'] ?? [] as $paragraph)
                    <p class="bns-mission-intro">{!! bns_rich_text($paragraph) !!}</p>
                @endforeach

                <div class="row g-4 bns-values-grid">
                    @foreach($values['items'] ?? [] as $item)
                    <div class="col-md-6">
                        <article class="bns-values-item">
                            <span class="bns-values-item__number">{{ $item['number'] ?? '' }}</span>
                            <div>
                                <h3>{!! bns_rich_text($item['title'] ?? '') !!}</h3>
                                <p>{!! bns_rich_text($item['text'] ?? '') !!}</p>
                            </div>
                        </article>
                    </div>
                    @endforeach
                </div>

                @if(!empty($values['value_statement']))
                <section class="bns-values-statement">
                    <h3>{{ $values['value_statement']['title'] ?? 'Our Value Statement' }}</h3>
                    <ul class="bns-values-statement__lines list-unstyled">
                        @foreach($values['value_statement']['lines'] ?? [] as $line)
                            <li>{!! bns_rich_text($line) !!}</li>
                        @endforeach
                    </ul>
                </section>
                @endif

                @if(!empty($values['philosophy']))
                <section class="bns-values-philosophy">
                    <h3>{{ $values['philosophy']['title'] ?? 'Our Guiding Philosophy' }}</h3>
                    @if(!empty($values['philosophy']['hindi']))
                        <p class="bns-values-philosophy__hindi">{!! bns_rich_text($values['philosophy']['hindi']) !!}</p>
                    @endif
                    @if(!empty($values['philosophy']['english']))
                        <p class="bns-values-philosophy__english">{!! bns_rich_text($values['philosophy']['english']) !!}</p>
                    @endif
                </section>
                @endif

                <div class="bns-vision-actions">
                    <a href="{{ route('about.mission') }}" class="bns-vision-actions__btn">
                        <i class="fas fa-bullseye"></i> Our Mission
                    </a>
                    <a href="{{ route('about.vision') }}" class="bns-vision-actions__btn">
                        <i class="fas fa-eye"></i> Our Vision
                    </a>
                    <button type="button" class="bns-vision-actions__btn bns-vision-actions__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                        <i class="fas fa-rocket"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
