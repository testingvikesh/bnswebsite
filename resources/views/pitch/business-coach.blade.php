@extends('layouts.front')

@section('title', $page['page_title'] ?? 'BNS Member Pitch')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/pitch-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/pitch-business-coach.css') }}" />
@endpush

@section('content')
<div class="bns-pitch-page bns-pitch-page--member">
    @include('partials.page-header', [
        'title' => $page['page_title'] ?? 'BNS Member Pitch',
        'subtitle' => $page['page_subtitle'] ?? null,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page['title'] ?? 'Pitch'],
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
</div>
@endsection
