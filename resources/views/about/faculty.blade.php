@extends('layouts.front')

@section('title', $page->page_title)

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/faculty.css') }}" />
@endpush

@section('content')
<div class="bns-faculty-page">
    @include('partials.page-header', [
        'title' => $page->page_title,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'About Us', 'url' => route('about')],
            ['label' => $page->page_title],
        ],
    ])

    <section class="bns-faculty-intro">
        <div class="container">
            @if($page->page_subtitle)
                <p class="bns-faculty-intro__subtitle">{{ $page->page_subtitle }}</p>
            @endif
            <p class="bns-faculty-intro__text">{!! bns_rich_text($page->page_intro) !!}</p>
        </div>
    </section>

    <section class="bns-faculty-hub">
        <div class="container">
            @if($facultyMembers->isNotEmpty())
                <div class="row g-4 bns-faculty-grid">
                    @foreach($facultyMembers as $index => $member)
                        @include('about.partials.faculty-card', ['member' => $member, 'index' => $index])
                    @endforeach
                </div>
            @else
                <div class="bns-faculty-empty">
                    <i class="fas fa-user-graduate" aria-hidden="true"></i>
                    <p>Our Visiting Expert Faculty profiles will be published here soon.</p>
                </div>
            @endif

            @if($page->excellence_title || !empty($page->excellence_paragraphs))
            <div class="bns-faculty-excellence bns-faculty-block">
                <div class="bns-faculty-excellence__head">
                    @if($page->excellence_label)
                        <span class="bns-faculty-excellence__label">{{ $page->excellence_label }}</span>
                    @endif
                    <h2 class="bns-faculty-excellence__title">{{ $page->excellence_title }}</h2>
                </div>
                @foreach($page->excellence_paragraphs ?? [] as $paragraph)
                    <p class="bns-faculty-excellence__text">{!! bns_rich_text($paragraph) !!}</p>
                @endforeach
            </div>
            @endif

            @if($page->tagline_brand || $page->tagline_text)
            <div class="bns-faculty-tagline bns-faculty-block">
                @if($page->tagline_brand)
                    <p class="bns-faculty-tagline__brand">{{ $page->tagline_brand }}</p>
                @endif
                @if($page->tagline_text)
                    <p class="bns-faculty-tagline__text">{!! bns_rich_text($page->tagline_text) !!}</p>
                @endif
            </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('modals')
@foreach($facultyMembers as $index => $member)
    @php($modalId = 'bnsFacultyModal-'.\Illuminate\Support\Str::slug((string) ($member->full_name ?: 'faculty')).'-'.$index)
    @include('about.partials.faculty-modal', ['member' => $member, 'modalId' => $modalId])
@endforeach
@endpush
