@extends('layouts.front')

@section('title', $testimonialsPage['page']['title'])

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/testimonials.css') }}" />
@endpush

@php
    $testimonialCount = $testimonials->count();
    $designations = $testimonials
        ->map(fn ($item) => trim((string) data_get($item, 'designation')))
        ->filter()
        ->unique()
        ->values();
    $categoryCount = $designations->count();
    $slugify = fn (string $value) => \Illuminate\Support\Str::slug($value) ?: 'general';
@endphp

@section('content')
<div class="bns-testimonials-page">
    @include('partials.page-header', [
        'title' => $testimonialsPage['page']['title'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $testimonialsPage['page']['title']],
        ],
    ])

    <section class="bns-testimonials-intro">
        <span class="bns-testimonials-intro__blob bns-testimonials-intro__blob--one" aria-hidden="true"></span>
        <span class="bns-testimonials-intro__blob bns-testimonials-intro__blob--two" aria-hidden="true"></span>
        <div class="container">
            <div class="bns-testimonials-intro__card">
                <i class="fas fa-quote-right bns-testimonials-intro__watermark" aria-hidden="true"></i>
                <span class="bns-testimonials-intro__eyebrow"><i class="fas fa-star" aria-hidden="true"></i> Real Voices, Real Impact</span>
                <p class="bns-testimonials-intro__subtitle">{{ $testimonialsPage['page']['subtitle'] }}</p>
                <p class="bns-testimonials-intro__text">{{ $testimonialsPage['page']['intro'] }}</p>

                @if($testimonialCount > 0)
                    <div class="bns-testimonials-stats">
                        <div class="bns-testimonials-stats__item">
                            <span class="bns-testimonials-stats__num">{{ $testimonialCount }}+</span>
                            <span class="bns-testimonials-stats__label">Shared Stories</span>
                        </div>
                        <div class="bns-testimonials-stats__divider" aria-hidden="true"></div>
                        <div class="bns-testimonials-stats__item">
                            <span class="bns-testimonials-stats__num">{{ max($categoryCount, 1) }}</span>
                            <span class="bns-testimonials-stats__label">Learner Categories</span>
                        </div>
                        <div class="bns-testimonials-stats__divider" aria-hidden="true"></div>
                        <div class="bns-testimonials-stats__item">
                            <span class="bns-testimonials-stats__num">100%</span>
                            <span class="bns-testimonials-stats__label">Genuine Feedback</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="bns-testimonials-hub">
        <div class="container">
            @if($testimonials->isNotEmpty())
                @if($designations->count() > 1)
                    <div class="bns-testimonials-filters" role="tablist" aria-label="Filter testimonials by category">
                        <button type="button" class="bns-testimonials-filter is-active" data-filter="all">
                            <i class="fas fa-layer-group" aria-hidden="true"></i> All Stories
                        </button>
                        @foreach($designations as $designation)
                            <button type="button" class="bns-testimonials-filter" data-filter="{{ $slugify($designation) }}">
                                {{ $designation }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <div class="row g-4" id="bnsTestimonialsGrid">
                    @foreach($testimonials as $index => $item)
                        @include('testimonials.partials.card', [
                            'item' => $item,
                            'index' => $index,
                            'filterKey' => $slugify(trim((string) data_get($item, 'designation'))),
                        ])
                    @endforeach
                </div>

                <p class="bns-testimonials-empty-filter" id="bnsTestimonialsNoMatch" hidden>
                    <i class="fas fa-filter" aria-hidden="true"></i> No stories in this category yet — check back soon.
                </p>
            @else
                <div class="bns-testimonials-empty">
                    <i class="fas fa-comment-dots" aria-hidden="true"></i>
                    <p>Testimonials will be published here soon.</p>
                </div>
            @endif
        </div>
    </section>

    <section class="bns-testimonials-cta">
        <div class="container">
            <div class="bns-testimonials-cta__inner">
                <div class="bns-testimonials-cta__text">
                    <h3>Ready to write your own success story?</h3>
                    <p>Join thousands of students, professionals, and business owners building their future with Business Navachar School.</p>
                </div>
                <button type="button" class="thm-btn bns-testimonials-cta__btn" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                    {{ config('site.apply_cta_label', 'Book Your Spot Now') }} <span class="fas fa-arrow-right"></span>
                </button>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var filters = document.querySelectorAll('.bns-testimonials-filter');
    var cards = document.querySelectorAll('#bnsTestimonialsGrid [data-filter-key]');
    var emptyState = document.getElementById('bnsTestimonialsNoMatch');
    if (!filters.length || !cards.length) return;

    filters.forEach(function (btn) {
        btn.addEventListener('click', function () {
            filters.forEach(function (b) { b.classList.remove('is-active'); });
            btn.classList.add('is-active');

            var key = btn.getAttribute('data-filter');
            var visibleCount = 0;

            cards.forEach(function (card) {
                var matches = key === 'all' || card.getAttribute('data-filter-key') === key;
                card.hidden = !matches;
                if (matches) visibleCount++;
            });

            if (emptyState) emptyState.hidden = visibleCount !== 0;
        });
    });
})();
</script>
@endpush
