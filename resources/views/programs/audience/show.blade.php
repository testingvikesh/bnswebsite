@extends('layouts.front')

@section('title', $program['page_title'] ?? $program['title'] ?? 'Program')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/home-audience.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/audience-program-page.css') }}" />
@if(!empty($program['eligibility_content']))
<link rel="stylesheet" href="{{ bns_vasset('assets/css/admission-page.css') }}" />
@endif
@endpush

@section('content')
@php
    $details = $journey['steps']['details'] ?? [];
    $modalBoxCount = collect($program['boxes'] ?? [])->where('action', 'modal')->count();
    $audienceModalKeys = [
        'eligibility' => 'eligibility_content',
    ];
    $boxHasContent = static function (array $program, string $modalKey) use ($audienceModalKeys): bool {
        $contentKey = $audienceModalKeys[$modalKey] ?? $modalKey;

        return ! empty($program[$contentKey]);
    };
@endphp
<div class="bns-audience-program-page">
    <div class="bns-program-sticky-title" id="bnsProgramStickyTitle" aria-hidden="true">
        <div class="container bns-program-sticky-title__inner">
            <span class="bns-program-sticky-title__icon" aria-hidden="true">{{ $program['icon'] ?? '🎓' }}</span>
            <span class="bns-program-sticky-title__name">{{ $journey['label'] }}</span>
            <button
                type="button"
                class="bns-program-sticky-title__btn"
                data-bs-toggle="modal"
                data-bs-target="#bnsIntroSessionModal"
                data-register-program-id="{{ $journey['register_program_id'] ?? '' }}"
                data-contact-program="{{ $journey['contact_program'] ?? '' }}"
                data-contact-category="{{ $journey['contact_category'] ?? '' }}"
                data-program-title="{{ $journey['label'] ?? '' }}"
            >
                {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
            </button>
        </div>
    </div>

    @include('partials.page-header', [
        'title' => $program['page_title'] ?? $journey['label'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Programs', 'url' => route('programs.featured')],
            ['label' => $journey['label']],
        ],
    ])

    <section class="bns-audience-program-hero">
        <div class="container">
            <div class="bns-audience-program-hero__card wow fadeInUp" data-wow-duration="0.85s">
                <div class="bns-audience-program-hero__accent" aria-hidden="true"></div>
                <div class="bns-audience-program-hero__grid">
                    <div class="bns-audience-program-hero__visual">
                        <span class="bns-audience-program-hero__icon" aria-hidden="true">{{ $program['icon'] ?? '🎓' }}</span>
                        <span class="bns-audience-program-hero__badge">BNS Program</span>
                    </div>
                    <div class="bns-audience-program-hero__content">
                        @if(!empty($program['tagline']))
                            <span class="bns-audience-program-hero__tagline">{{ $program['tagline'] }}</span>
                        @endif
                        <h2 class="bns-audience-program-hero__title">{{ $journey['label'] }}</h2>
                        @if(!empty($program['intro']))
                            <p class="bns-audience-program-hero__text">{!! bns_rich_text($program['intro']) !!}</p>
                        @endif
                        <ul class="bns-audience-program-hero__meta list-unstyled">
                            @if(!empty($details['audience']))
                                <li><i class="fas fa-user-graduate" aria-hidden="true"></i> {{ $details['audience'] }}</li>
                            @endif
                            @if(!empty($details['duration']))
                                <li><i class="fas fa-clock" aria-hidden="true"></i> {{ $details['duration'] }}</li>
                            @endif
                            @if(!empty($details['goal']))
                                <li><i class="fas fa-bullseye" aria-hidden="true"></i> {!! bns_rich_text($details['goal']) !!}</li>
                            @endif
                        </ul>
                        <div class="bns-audience-program-hero__actions">
                            <button
                                type="button"
                                class="thm-btn"
                                data-bs-toggle="modal"
                                data-bs-target="#bnsIntroSessionModal"
                                data-register-program-id="{{ $journey['register_program_id'] ?? '' }}"
                                data-contact-program="{{ $journey['contact_program'] ?? '' }}"
                                data-contact-category="{{ $journey['contact_category'] ?? '' }}"
                                data-program-title="{{ $journey['label'] ?? '' }}"
                            >
                                {{ config('site.apply_cta_label', 'Book Your Spot Now') }} <span class="fas fa-arrow-right"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bns-audience-program">
        <div class="container">
            <div class="bns-audience-program__hub wow fadeInUp" data-wow-duration="0.8s">
                <div class="bns-audience-program__hub-head">
                    <h3 class="bns-audience-program__hub-title">Explore This <span>Program</span></h3>
                    <p>Choose a topic below — explore <strong>Program Structure</strong>, <strong>Vision</strong>, <strong>Mission</strong>, <strong>Why BNS</strong>, and more.</p>
                </div>

                <div class="row g-3 g-lg-4 bns-audience-program__grid">
                    @foreach($program['boxes'] ?? [] as $index => $box)
                        @php($modalKey = $box['modal'] ?? $box['key'])
                        @continue(! $boxHasContent($program, $modalKey))
                        @php($modalId = $box['modal_id'] ?? ('bns' . \Illuminate\Support\Str::studly($modalKey) . 'Modal'))
                        @php($modalCol = $modalBoxCount >= 4 ? 'col-lg-4 col-xl-3' : ($modalBoxCount >= 3 ? 'col-lg-4' : 'col-lg-6'))
                        <div class="col-sm-6 {{ $modalCol }} wow fadeInUp" data-wow-delay="{{ 40 + ($index * 35) }}ms">
                            <button
                                type="button"
                                class="bns-audience-program-box bns-audience-program-box--featured"
                                data-bs-toggle="modal"
                                data-bs-target="#{{ $modalId }}"
                            >
                                @include('programs.audience.partials.box-inner', ['box' => $box, 'index' => $index])
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="bns-audience-program-cta">
        <div class="container">
            <div class="bns-audience-program-cta__inner wow fadeInUp" data-wow-duration="0.8s">
                <div>
                    <h3>Ready to begin your entrepreneurial journey?</h3>
                    <p>Join the {{ $journey['label'] }} program at Business Navachar School.</p>
                </div>
                <div class="bns-audience-program-cta__actions">
                    <button
                        type="button"
                        class="thm-btn thm-btn--white"
                        data-bs-toggle="modal"
                        data-bs-target="#bnsIntroSessionModal"
                        data-register-program-id="{{ $journey['register_program_id'] ?? '' }}"
                        data-contact-program="{{ $journey['contact_program'] ?? '' }}"
                        data-contact-category="{{ $journey['contact_category'] ?? '' }}"
                        data-program-title="{{ $journey['label'] ?? '' }}"
                    >
                        {{ config('site.apply_cta_label', 'Book Your Spot Now') }} <span class="fas fa-arrow-right"></span>
                    </button>
                    <a href="{{ url('/') }}#bns-audience-title" class="bns-audience-program-cta__ghost">All Programs</a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('modals')
@if(!empty($program['mission']))
    @include('programs.audience.partials.points-modal', [
        'type' => 'mission',
        'modalId' => 'bnsMissionModal',
        'modalDialogClass' => 'modal-xl',
        'program' => $program,
        'journey' => $journey,
    ])
@endif
@if(!empty($program['vision']))
    @include('programs.audience.partials.points-modal', [
        'type' => 'vision',
        'modalId' => 'bnsVisionModal',
        'modalDialogClass' => 'modal-xl',
        'program' => $program,
        'journey' => $journey,
    ])
@endif
@if(!empty($program['vision_2047']))
    @include('programs.audience.partials.points-modal', [
        'type' => 'vision_2047',
        'modalId' => 'bnsVision2047Modal',
        'modalDialogClass' => 'modal-xl',
        'program' => $program,
        'journey' => $journey,
    ])
@endif
@if(!empty($program['core_values']))
    @include('programs.audience.partials.points-modal', [
        'type' => 'core_values',
        'modalId' => 'bnsCoreValuesModal',
        'modalDialogClass' => 'modal-xl',
        'program' => $program,
        'journey' => $journey,
    ])
@endif
@if(!empty($program['founder_message']))
    @include('programs.audience.partials.founder-modal', [
        'program' => $program,
        'journey' => $journey,
    ])
@endif
@if(!empty($program['why_bns']))
    @include('programs.audience.partials.why-bns-modal', [
        'program' => $program,
        'journey' => $journey,
        'contentKey' => 'why_bns',
        'modalId' => 'bnsWhyBnsModal',
    ])
@endif
@if(!empty($program['why_business_education']))
    @include('programs.audience.partials.why-bns-modal', [
        'program' => $program,
        'journey' => $journey,
        'contentKey' => 'why_business_education',
        'modalId' => 'bnsWhyBusinessEducationModal',
    ])
@endif
@if(!empty($program['prosperity_mission']))
    @include('programs.audience.partials.prosperity-mission-modal', [
        'program' => $program,
        'journey' => $journey,
    ])
@endif
@if(!empty($program['program_structure']))
    @include('programs.audience.partials.program-structure-modal', [
        'program' => $program,
        'journey' => $journey,
        'modalId' => 'bnsProgramStructureModal',
    ])
@endif
@if(!empty($program['certification']))
    @include('programs.audience.partials.certification-modal', [
        'program' => $program,
        'journey' => $journey,
    ])
@endif
@if(!empty($program['events_experiences']))
    @include('programs.audience.partials.events-modal', [
        'program' => $program,
        'journey' => $journey,
    ])
@endif
@if(!empty($program['eligibility_content']))
    @include('programs.audience.partials.eligibility-modal', [
        'program' => $program,
        'journey' => $journey,
    ])
@endif
@if(!empty($program['faqs']))
    @include('programs.audience.partials.faqs-modal', [
        'program' => $program,
        'journey' => $journey,
    ])
@endif
@endpush

@push('scripts')
<script>
(function () {
    'use strict';

    var bar = document.getElementById('bnsProgramStickyTitle');
    var hero = document.querySelector('.bns-audience-program-hero');
    if (bar && hero) {
        function toggleBar() {
            var showAfter = hero.getBoundingClientRect().bottom;
            var shouldShow = showAfter <= 0;
            bar.classList.toggle('is-visible', shouldShow);
            bar.setAttribute('aria-hidden', shouldShow ? 'false' : 'true');
        }

        window.addEventListener('scroll', toggleBar, { passive: true });
        window.addEventListener('resize', toggleBar);
        toggleBar();
    }
})();
</script>
@endpush
