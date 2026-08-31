@extends('layouts.front')

@section('title', $page['page_title'] ?? 'Pitch')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/home-orientation-pitch.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/home-coach-presentation.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/pitch-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/pitch-business-coach.css') }}" />
@endpush

@section('content')
@php($summaryItems = [
    ['id' => 'complete-overview', 'label' => 'Complete Over View'],
    ['id' => 'level-next-digital-business-school', 'label' => 'Level Next (Digital Business School)'],
    ['id' => 'why-name-bns', 'label' => 'Why Name BNS?'],
    ['id' => 'our-programs', 'label' => 'Our Programs'],
    ['id' => 'what-do-we-teach', 'label' => 'What Do We Teach?'],
    ['id' => 'gyaan-navachar-samruddhi', 'label' => '3 Pillars of BNS'],
    ['id' => 'bns-5-sentences', 'label' => 'BNS In 5 Simple Sentences'],
    ['id' => 'weekly-monthly-yearly', 'label' => 'Weekly Monthly and Yearly'],
    ['id' => 'monday-to-sunday', 'label' => 'Monday to Saturday'],
    ['id' => 'schools-5-3-2-1', 'label' => '5 -- 3 -- 2 -- 1 Year Structure'],
    ['id' => 'year-wise-school-name', 'label' => 'Yearwise Learning Journey'],
    ['id' => 'eligibility', 'label' => 'Eligibility'],
    ['id' => 'why-we-need-business-coaches', 'label' => 'Why We Need Business Coaches?'],
    ['id' => 'business-coach-benefits', 'label' => 'Business Coach Benifits'],
    ['id' => 'three-hundred-schools', 'label' => '300 Schools'],
    ['id' => 'vision-2047', 'label' => 'Vision 2047'],
    ['id' => 'business-coaches-and-bns', 'label' => 'Business Coaches And BNS'],
    ['id' => 'fees-structure', 'label' => 'Fees Structure'],
])
<div class="bns-pitch-page">
    @include('partials.page-header', [
        'title' => $page['page_title'] ?? 'Business Coach Pitch',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page['title'] ?? 'Pitch', 'url' => route('pitch')],
            ['label' => $page['page_title'] ?? 'Business Coach Pitch'],
        ],
    ])

    @include('home.partials.orientation-pitch', [
        'orientationPitch' => $orientationPitch ?? config('home_orientation_pitch', []),
        'memberPitch' => $memberPitch ?? config('business_coach_pitch', []),
    ])

    @include('home.partials.coach-presentation', [
        'coachPresentation' => $coachPresentation ?? config('home_coach_presentation', []),
    ])

    @php($schoolTypes = config('business_school_types', []))
    @if(!empty($schoolTypes['rows']))
        <section class="bns-orientation-pitch bns-orientation-pitch--school-types-only" id="fees-structure" aria-label="{{ $schoolTypes['title'] ?? 'Four Types of Business Schools' }}">
            <div class="container">
                <div class="bns-orientation-pitch__block bns-orientation-pitch__block--school-types wow fadeInUp" data-wow-duration="0.85s">
                    @include('pitch.partials.school-types-table', [
                        'schoolTypes' => $schoolTypes,
                        'wrapperClass' => 'bns-orientation-pitch__school-types',
                        'showTitle' => true,
                    ])
                </div>
            </div>
        </section>
    @endif

    <div class="bns-pitch-summary" id="bnsPitchSummary">
        <button type="button" class="bns-pitch-summary__toggle" id="bnsPitchSummaryToggle" aria-expanded="false" aria-controls="bnsPitchSummaryPanel">
            <i class="fas fa-list-ul" aria-hidden="true"></i>
            <span>Summary</span>
        </button>

        <div class="bns-pitch-summary__panel" id="bnsPitchSummaryPanel" hidden>
            <div class="bns-pitch-summary__panel-head">
                <strong>Business Coach Pitch Summary</strong>
                <button type="button" class="bns-pitch-summary__close" id="bnsPitchSummaryClose" aria-label="Close summary">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <nav class="bns-pitch-summary__nav" aria-label="Business Coach Pitch sections">
                <ol class="bns-pitch-summary__list">
                    @foreach($summaryItems as $item)
                        <li>
                            <a href="#{{ $item['id'] }}" class="bns-pitch-summary__link" data-pitch-summary-link>
                                <span class="bns-pitch-summary__num">{{ $loop->iteration }}</span>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@once('orientation-pitch-accordion-scripts')
<script>
(function () {
    document.querySelectorAll('.bns-orientation-pitch [data-orientation-accordion]').forEach(function (trigger) {
        trigger.addEventListener('click', function () {
            var list = trigger.closest('.bns-orientation-pitch__accordion-list');
            var item = trigger.closest('.bns-orientation-pitch__accordion-item');
            var panel = item ? item.querySelector('.bns-orientation-pitch__accordion-panel') : null;
            if (!list || !item || !panel) return;

            var isOpen = item.classList.contains('is-open');
            list.querySelectorAll('.bns-orientation-pitch__accordion-item').forEach(function (other) {
                other.classList.remove('is-open');
                var otherTrigger = other.querySelector('[data-orientation-accordion]');
                var otherPanel = other.querySelector('.bns-orientation-pitch__accordion-panel');
                if (otherTrigger) otherTrigger.setAttribute('aria-expanded', 'false');
                if (otherPanel) otherPanel.hidden = true;
            });

            if (!isOpen) {
                item.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
                panel.hidden = false;
            }
        });
    });
})();
</script>
@endonce
<script>
(function () {
    'use strict';

    var root = document.getElementById('bnsPitchSummary');
    var toggle = document.getElementById('bnsPitchSummaryToggle');
    var panel = document.getElementById('bnsPitchSummaryPanel');
    var closeBtn = document.getElementById('bnsPitchSummaryClose');
    if (!root || !toggle || !panel) return;

    function setOpen(open) {
        root.classList.toggle('is-open', open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        panel.hidden = !open;
    }

    toggle.addEventListener('click', function () {
        setOpen(!root.classList.contains('is-open'));
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            setOpen(false);
        });
    }

    root.querySelectorAll('[data-pitch-summary-link]').forEach(function (link) {
        link.addEventListener('click', function (event) {
            var href = link.getAttribute('href') || '';
            var id = href.replace(/^#/, '');
            var target = id ? document.getElementById(id) : null;
            if (!target) return;

            event.preventDefault();
            setOpen(false);
            target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            history.replaceState(null, '', '#' + id);
        });
    });
})();
</script>
@endpush
