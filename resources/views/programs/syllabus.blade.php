@extends('layouts.front')

@section('title', $page['title'] ?? 'Syllabus')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/programs-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/audience-program-page.css') }}" />
@endpush

@section('content')
<div class="bns-programs-page bns-syllabus-page">
    @include('partials.page-header', [
        'title' => $page['title'] ?? 'Syllabus',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page['title'] ?? 'Syllabus'],
        ],
    ])

    <section class="bns-programs-content">
        <div class="container">
            <div class="bns-programs-intro">
                <span class="bns-programs-intro__label">{{ $page['label'] ?? 'Syllabus' }}</span>
                @if(!empty($page['subtitle']))
                    <h2>{{ $page['subtitle'] }}</h2>
                @endif
                @foreach($page['intro'] ?? [] as $paragraph)
                    <p>{!! bns_rich_text($paragraph) !!}</p>
                @endforeach
            </div>

            <div class="bns-syllabus-grid">
                @foreach($programs as $item)
                    @php($modalId = 'bnsSyllabusModal-'.$item['slug'])
                    <button
                        type="button"
                        class="bns-syllabus-card"
                        id="syllabus-{{ $item['slug'] }}"
                        data-bs-toggle="modal"
                        data-bs-target="#{{ $modalId }}"
                        data-syllabus-slug="{{ $item['slug'] }}"
                    >
                        <span class="bns-syllabus-card__icon" aria-hidden="true">{{ $item['icon'] }}</span>
                        <span class="bns-syllabus-card__body">
                            <span class="bns-syllabus-card__title">{{ $item['title'] }}</span>
                            @if(!empty($item['audience']))
                                <span class="bns-syllabus-card__audience">{{ $item['audience'] }}</span>
                            @endif
                            @if(!empty($item['summary']))
                                <span class="bns-syllabus-card__summary">{!! bns_rich_text($item['summary']) !!}</span>
                            @endif
                            @if(!empty($item['duration']))
                                <span class="bns-syllabus-card__duration"><i class="fas fa-clock" aria-hidden="true"></i> {{ $item['duration'] }}</span>
                            @endif
                        </span>
                        <span class="bns-syllabus-card__cta">
                            View Syllabus <i class="fas fa-arrow-right" aria-hidden="true"></i>
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>
</div>
@endsection

@push('modals')
@foreach($programs as $item)
    @include('programs.audience.partials.program-structure-modal', [
        'program' => $item['program'],
        'modalId' => 'bnsSyllabusModal-'.$item['slug'],
    ])
@endforeach
@endpush

@push('scripts')
<script>
(function () {
    function openFromHash() {
        var hash = (window.location.hash || '').replace(/^#/, '');
        if (!hash) return;
        var slug = hash.replace(/^syllabus-/, '');
        var trigger = document.querySelector('[data-syllabus-slug="' + slug + '"]');
        if (trigger) {
            trigger.click();
        }
    }

    document.addEventListener('DOMContentLoaded', openFromHash);
    window.addEventListener('hashchange', openFromHash);

    document.querySelectorAll('[data-syllabus-slug]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slug = btn.getAttribute('data-syllabus-slug');
            if (slug && history.replaceState) {
                history.replaceState(null, '', '#syllabus-' + slug);
            }
        });
    });

    document.querySelectorAll('.bns-program-structure-modal').forEach(function (modal) {
        modal.addEventListener('hidden.bs.modal', function () {
            if (history.replaceState && (window.location.hash || '').indexOf('syllabus-') === 1) {
                history.replaceState(null, '', window.location.pathname + window.location.search);
            }
        });
    });
})();
</script>
@endpush
