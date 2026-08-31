@extends('layouts.front')

@section('title', $page['title'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/statistics-page.css') }}" />
@endpush

@section('content')
<div class="bns-statistics-page">
    @include('partials.page-header', [
        'title' => $page['title'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page['title']],
        ],
    ])

    <section class="bns-statistics-content">
        <div class="container">
            <div class="bns-statistics-intro">
                <span class="bns-statistics-intro__label">{{ $page['label'] ?? 'Statistics' }}</span>
                @if(!empty($page['subtitle']))
                    <h2>{{ $page['subtitle'] }}</h2>
                @endif
                @if(!empty($page['section_heading']))
                    <p class="bns-statistics-intro__heading">{{ $page['section_heading'] }}</p>
                @endif
            </div>

            <div class="bns-statistics-grid">
                @foreach($page['stats'] ?? [] as $stat)
                <article class="bns-stat-card {{ !empty($stat['featured']) ? 'bns-stat-card--featured' : '' }}">
                    @if(!empty($stat['icon']))
                        <span class="bns-stat-card__icon" aria-hidden="true">{{ $stat['icon'] }}</span>
                    @endif
                    <h3>{{ $stat['title'] ?? '' }}</h3>
                    @if(!empty($stat['tagline']))
                        <p class="bns-stat-card__tagline">{{ $stat['tagline'] }}</p>
                    @endif
                    @if(!empty($stat['items']))
                    <ul class="bns-stat-card__list list-unstyled">
                        @foreach($stat['items'] as $item)
                            <li><i class="fas fa-check"></i> {{ $item }}</li>
                        @endforeach
                    </ul>
                    @endif
                </article>
                @endforeach
            </div>

            <div class="bns-statistics-actions">
                <a href="{{ route('programs.featured') }}" class="bns-statistics-actions__btn">
                    <i class="fas fa-graduation-cap"></i> Explore Programs
                </a>
                <button type="button" class="bns-statistics-actions__btn bns-statistics-actions__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                    <i class="fas fa-rocket"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
                </button>
            </div>
        </div>
    </section>
</div>
@endsection
