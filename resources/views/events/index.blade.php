@extends('layouts.front')

@section('title', $page['title'])

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/events-page.css') }}" />
@endpush

@section('content')
<div class="bns-events-page">
    @include('partials.page-header', [
        'title' => $page['title'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page['title']],
        ],
    ])

    <section class="bns-events-content">
        <div class="container">
            <div class="bns-events-intro">
                <span class="bns-events-intro__label">{{ $page['label'] ?? 'Events' }}</span>
                @if(!empty($page['subtitle']))
                    <h2>{{ $page['subtitle'] }}</h2>
                @endif
                @if(!empty($page['intro']))
                    <p>{!! bns_rich_text($page['intro']) !!}</p>
                @endif
            </div>

            @if($spotlightEvents->isNotEmpty())
            <section class="bns-events-spotlight" id="introduction-session">
                <div class="bns-events-spotlight__head">
                    <span class="bns-events-spotlight__eyebrow"><i class="fas fa-bolt" aria-hidden="true"></i> Featured Event</span>
                    <h2 class="bns-events-spotlight__title">{{ $page['spotlight_title'] ?? 'Induction Seminars' }}</h2>
                    @if(!empty($page['spotlight_intro']))
                        <p class="bns-events-spotlight__intro">{!! bns_rich_text($page['spotlight_intro']) !!}</p>
                    @endif
                </div>
                <div class="bns-events-spotlight__grid">
                    @foreach($spotlightEvents as $event)
                        @include('events.partials.spotlight-card', ['event' => $event, 'resolveCta' => $resolveCta])
                    @endforeach
                </div>
            </section>
            @endif

            @if($otherEvents->isNotEmpty())
            <section class="bns-events-more">
                <h2 class="bns-events-more__title">{{ $page['more_title'] ?? 'More Upcoming Events' }}</h2>
                <div class="bns-events-grid">
                    @foreach($otherEvents as $event)
                        @include('events.partials.card', ['event' => $event, 'resolveCta' => $resolveCta])
                    @endforeach
                </div>
            </section>
            @endif

            @if(!empty($calendar['rows']))
            <section class="bns-events-calendar">
                <div class="bns-events-calendar__header">
                    <h3>{{ $calendar['title'] ?? 'Monthly Event Calendar' }}</h3>
                    @if(!empty($calendar['month']))
                        <span class="bns-events-calendar__month">{{ $calendar['month'] }}</span>
                    @endif
                </div>
                <div class="bns-events-calendar__table-wrap">
                    <table class="bns-events-calendar__table">
                        <thead>
                            <tr>
                                @foreach($calendar['columns'] ?? [] as $col)
                                    <th>{{ $col }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($calendar['rows'] as $row)
                            <tr>
                                <td>{{ $row['date'] ?? '' }}</td>
                                <td>{{ $row['event'] ?? '' }}</td>
                                <td>{{ $row['location'] ?? '' }}</td>
                                <td>
                                    @if(!empty($row['cta']))
                                        @if(($row['cta']['route'] ?? 'register') === 'register')
                                            <button type="button" class="bns-events-calendar__status" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                                                {{ $row['status'] ?? 'Register Now' }}
                                            </button>
                                        @else
                                            <a href="{{ $resolveCta($row['cta']) }}" class="bns-events-calendar__status">
                                                {{ $row['status'] ?? 'Register Now' }}
                                            </a>
                                        @endif
                                    @else
                                        {{ $row['status'] ?? '' }}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            @endif

            @if(!empty($categories))
            <section class="bns-events-categories">
                <h3>Event Categories</h3>
                <div class="bns-events-categories__grid">
                    @foreach($categories as $category)
                        <span class="bns-events-category-tag">
                            @if(!empty($category['icon']))<span aria-hidden="true">{{ $category['icon'] }}</span>@endif
                            {{ $category['label'] ?? '' }}
                        </span>
                    @endforeach
                </div>
            </section>
            @endif

            @if(!empty($cta))
            <section class="bns-events-cta">
                @if(!empty($cta['title']))
                    <h3>{{ $cta['title'] }}</h3>
                @endif
                @if(!empty($cta['text']))
                    <p>{!! bns_rich_text($cta['text']) !!}</p>
                @endif
                @if(!empty($cta['button']))
                    @if(($cta['button']['route'] ?? 'register') === 'register')
                        <button type="button" class="bns-events-cta__btn" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                            {{ $cta['button']['label'] ?? 'Register Now' }} <i class="fas fa-arrow-right"></i>
                        </button>
                    @else
                        <a href="{{ $resolveCta($cta['button']) }}" class="bns-events-cta__btn">
                            {{ $cta['button']['label'] ?? 'Register Now' }} <i class="fas fa-arrow-right"></i>
                        </a>
                    @endif
                @endif
            </section>
            @endif
        </div>
    </section>
</div>
@endsection
