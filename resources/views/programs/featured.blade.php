@extends('layouts.front')

@section('title', $page['title'])

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/programs-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/home-audience.css') }}" />
@endpush

@section('content')
<div class="bns-programs-page">
    @include('partials.page-header', [
        'title' => $page['title'],
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page['title']],
        ],
    ])

    <section class="bns-programs-content">
        <div class="container">
            <div class="bns-programs-intro">
                <span class="bns-programs-intro__label">{{ $page['label'] ?? 'Programs' }}</span>
                @if(!empty($page['subtitle']))
                    <h2>{{ $page['subtitle'] }}</h2>
                @endif
                @foreach($page['intro'] ?? [] as $paragraph)
                    <p>{!! bns_rich_text($paragraph) !!}</p>
                @endforeach
            </div>

            <div class="bns-programs-list bns-audience__grid bns-audience__grid--pro">
                @foreach($page['programs'] ?? [] as $program)
                    @php($slug = $program['slug'] ?? '')
                    @php($card = ($audienceCards[$slug] ?? []) + ['desc' => $program['summary'] ?? ($audienceCards[$slug]['desc'] ?? '')])
                    @if($slug !== '' && array_key_exists($slug, config('audience_programs', [])))
                    <a href="{{ route('programs.show', $slug) }}" class="bns-audience-card bns-audience-card--pro" id="{{ $slug }}">
                        @include('home.partials.audience-card-pro', ['card' => $card])
                    </a>
                    @endif
                @endforeach
            </div>

            @if(!empty($page['benefits']))
            <section class="bns-programs-benefits">
                <h3>{{ $page['benefits']['title'] ?? 'Common Benefits' }}</h3>
                <ul class="bns-programs-benefits__grid list-unstyled">
                    @foreach($page['benefits']['items'] ?? [] as $item)
                        <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
                    @endforeach
                </ul>
            </section>
            @endif

            @if(!empty($page['closing']))
            <div class="bns-programs-closing">
                @if(!empty($page['closing']['headline']))
                    <p class="bns-programs-closing__headline">{!! bns_rich_text($page['closing']['headline']) !!}</p>
                @endif
                @if(!empty($page['closing']['brand']))
                    <p class="bns-programs-closing__brand">{!! bns_rich_text($page['closing']['brand']) !!}</p>
                @endif
                @if(!empty($page['closing']['subtitle']))
                    <p class="bns-programs-closing__subtitle">{!! bns_rich_text($page['closing']['subtitle']) !!}</p>
                @endif
                @if(!empty($page['closing']['tagline']))
                    <p class="bns-programs-closing__tagline">{!! bns_rich_text($page['closing']['tagline']) !!}</p>
                @endif
            </div>
            @endif
        </div>
    </section>
</div>
@endsection
