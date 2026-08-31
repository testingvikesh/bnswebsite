@extends('layouts.front')

@section('title', $page['page_title'] ?? 'BNS Member Pitch')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/pitch-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/pitch-business-coach.css') }}" />
@endpush

@section('content')
@php
    $summaryItems = $pitch['summary'] ?? [
        ['id' => 'about-bns', 'label' => 'About BNS'],
        ['id' => 'vision', 'label' => 'Vision'],
        ['id' => 'mission', 'label' => 'Mission'],
        ['id' => 'gyaan-navachar-samruddhi', 'label' => 'Gyan Navachar and Samruddhi'],
        ['id' => 'bns-5-sentences', 'label' => 'BNS In 5 Simple sentences'],
        ['id' => 'weekly-monthly-yearly', 'label' => 'Weekly Monthly and Yearly'],
        ['id' => 'monday-to-sunday', 'label' => 'Monday to Sunday'],
        ['id' => 'schools-5-3-2-1', 'label' => '5 -- 3 -- 2 -- 1 Year'],
        ['id' => 'year-wise-school-name', 'label' => 'Year Wise School Name'],
        ['id' => 'eligibility', 'label' => 'Eligibility'],
        ['id' => 'what-will-you-learn', 'label' => 'What Will You Learn?'],
        ['id' => 'how-will-you-learn', 'label' => 'How will You Learn?'],
        ['id' => 'who-will-teach', 'label' => 'Who Will Teach?'],
        ['id' => 'certifications', 'label' => 'Certifications'],
        ['id' => 'free-introduction-session', 'label' => 'Free Introduction Session'],
        ['id' => 'master-session', 'label' => 'Master Session'],
        ['id' => 'learning-material', 'label' => 'Material?'],
        ['id' => 'medium-of-instruction', 'label' => 'Medium Of Instructions'],
        ['id' => 'batch-size', 'label' => 'Batch Size'],
        ['id' => 'practical-learning', 'label' => 'Practical Learning'],
        ['id' => 'fees-structure', 'label' => 'Fees Structure'],
        ['id' => 'join-bns', 'label' => "Join India's First Weekly Business School"],
    ];
@endphp
<div class="bns-pitch-page bns-pitch-page--member">
    @include('partials.page-header', [
        'title' => $page['page_title'] ?? 'BNS Member Pitch',
        'subtitle' => $page['page_subtitle'] ?? null,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page['title'] ?? 'Pitch', 'url' => route('pitch')],
            ['label' => $page['page_title'] ?? 'BNS Member Pitch'],
        ],
    ])

    @if(!empty($page['page_intro']))
        <section class="bns-pitch-member-intro">
            <div class="container">
                <div class="bns-pitch-member-intro__card wow fadeInUp" data-wow-duration="0.8s">
                    <p class="bns-pitch-member-intro__text">{!! bns_rich_text($page['page_intro']) !!}</p>
                </div>
            </div>
        </section>
    @endif

    @include('pitch.partials.business-coach-content', ['pitch' => $pitch])

    <div class="bns-pitch-summary" id="bnsPitchSummary">
        <button type="button" class="bns-pitch-summary__toggle" id="bnsPitchSummaryToggle" aria-expanded="false" aria-controls="bnsPitchSummaryPanel">
            <i class="fas fa-list-ul" aria-hidden="true"></i>
            <span>Summary</span>
        </button>

        <div class="bns-pitch-summary__panel" id="bnsPitchSummaryPanel" hidden>
            <div class="bns-pitch-summary__panel-head">
                <strong>Member Pitch Summary</strong>
                <button type="button" class="bns-pitch-summary__close" id="bnsPitchSummaryClose" aria-label="Close summary">
                    <i class="fas fa-times" aria-hidden="true"></i>
                </button>
            </div>
            <nav class="bns-pitch-summary__nav" aria-label="Member Pitch sections">
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
