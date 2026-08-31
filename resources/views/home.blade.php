@extends('layouts.front')

@section('title', 'BNS School')

@push('head')
@php($heroSlides = $heroSlides ?? config('home.hero_slides', []))
@php($firstSlide = $heroSlides[0] ?? config('home.hero_banner', []))
<link rel="preload" as="image" href="{{ $img($firstSlide['image'] ?? 'hero_slide_1') }}" fetchpriority="high">
@endpush

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/home-hero.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/home-audience.css') }}" />
@endpush

@section('content')
<div class="bns-home">
@include('home.partials.hero-slider', [
    'heroSlides' => $heroSlides ?? config('home.hero_slides', []),
    'img' => $img,
])

@if(!empty($heroHighlights))
<section class="bns-hero-highlights" aria-label="BNS highlights">
    <div class="bns-hero-highlights__track">
        <div class="bns-hero-highlights__inner">
            @foreach(array_merge($heroHighlights, $heroHighlights) as $item)
                <span class="bns-hero-highlights__item">
                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                    {!! bns_rich_text($item) !!}
                </span>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('home.partials.audience-section', [
    'audienceSection' => $audienceSection ?? config('home.audience_section', []),
    'audienceJourneys' => $audienceJourneys ?? [],
    'contactFormConfig' => $contactFormConfig ?? config('contact.form', []),
    'img' => $img,
])

</div>
@endsection

@push('scripts')
<script src="{{ bns_vasset('assets/js/home-hero.js') }}"></script>
@endpush
