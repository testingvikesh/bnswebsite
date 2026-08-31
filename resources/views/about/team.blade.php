@extends('layouts.front')

@section('title', $page->page_title)

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/team.css') }}" />
@endpush

@php
    $leadershipCount = $leadershipMembers->count();
@endphp

@section('content')
<div class="bns-team-page">
    @include('partials.page-header', [
        'title' => $page->page_title,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'About Us', 'url' => route('about')],
            ['label' => $page->page_title],
        ],
    ])

    <section class="bns-team-intro">
        <span class="bns-team-intro__blob bns-team-intro__blob--one" aria-hidden="true"></span>
        <span class="bns-team-intro__blob bns-team-intro__blob--two" aria-hidden="true"></span>
        <div class="container">
            <div class="bns-team-intro__card">
                <span class="bns-team-intro__eyebrow"><i class="fas fa-users" aria-hidden="true"></i> Our People</span>
                <p class="bns-team-intro__subtitle">{{ $page->page_subtitle }}</p>
                <p class="bns-team-intro__text">{!! bns_rich_text($page->page_intro) !!}</p>

                @if($leadershipCount > 0)
                    <div class="bns-team-intro__stats">
                        <div class="bns-team-intro__stat">
                            <span class="bns-team-intro__stat-num">{{ $leadershipCount }}</span>
                            <span class="bns-team-intro__stat-label">Leadership</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="bns-team-hub">
        <div class="container">

            <div class="bns-team-section bns-team-section--leadership">
                <div class="bns-team-section__head">
                    <span class="bns-team-section__label">Leadership</span>
                    <h2 class="bns-team-section__title">{{ $page->leadership_title }}</h2>
                    <p class="bns-team-section__desc">Visionary leaders driving strategy, academic excellence, and national expansion at Business Navachar School.</p>
                </div>
                @if($leadershipMembers->isNotEmpty())
                    <div class="bns-team-leadership-stack">
                        @foreach(
                            $leadershipMembers
                                ->sortBy(fn ($member) => sprintf('%04d-%s', (int) ($member->sort_order ?? 999), strtolower($member->full_name ?? '')))
                                ->values() as $index => $member
                        )
                            @include('about.partials.leadership-card', [
                                'member' => $member,
                                'index' => $index,
                            ])
                        @endforeach
                    </div>
                @else
                    <div class="bns-team-empty">
                        <i class="fas fa-users" aria-hidden="true"></i>
                        <p>Leadership profiles will be published here soon.</p>
                    </div>
                @endif
            </div>

            @if($page->collab_title || $page->collab_description)
            <div class="bns-team-collab bns-team-block">
                @if($page->collab_badge)
                    <span class="bns-team-collab__badge">{{ $page->collab_badge }}</span>
                @endif
                @if($page->collab_title)
                    <h2 class="bns-team-collab__title">{{ $page->collab_title }}</h2>
                @endif
                @if($page->collab_description)
                    <p class="bns-team-collab__text">{!! bns_rich_text($page->collab_description) !!}</p>
                @endif
            </div>
            @endif

            @if(!empty($page->operations_teams))
            <div class="bns-team-section">
                <div class="bns-team-section__head">
                    <span class="bns-team-section__label">Behind the Scenes</span>
                    <h2 class="bns-team-section__title">{{ $page->operations_title }}</h2>
                </div>
                <div class="row g-4">
                    @foreach($page->operations_teams as $opsTeam)
                        <div class="col-xl-4 col-lg-6 col-md-6">
                            <div class="bns-team-ops-card">
                                <div class="bns-team-ops-card__icon">
                                    <i class="{{ $opsTeam['icon'] ?? 'fas fa-users' }}" aria-hidden="true"></i>
                                </div>
                                <h3 class="bns-team-ops-card__name">{{ $opsTeam['name'] ?? '' }}</h3>
                                <p class="bns-team-ops-card__desc">{!! bns_rich_text($opsTeam['description'] ?? '') !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($page->values_items))
            <div class="bns-team-values bns-team-block">
                <div class="bns-team-section__head bns-team-section__head--in-box">
                    <span class="bns-team-section__label">What We Stand For</span>
                    <h2 class="bns-team-section__title">{{ $page->values_title }}</h2>
                </div>
                <div class="bns-team-values__grid">
                    @foreach($page->values_items as $value)
                        <span class="bns-team-values__pill">
                            <i class="fas fa-check" aria-hidden="true"></i>{!! bns_rich_text($value) !!}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bns-team-join bns-team-block">
                <div class="bns-team-section__head">
                    <span class="bns-team-section__label">Careers</span>
                    <h2 class="bns-team-section__title">{{ $page->join_title }}</h2>
                </div>
                @if($page->join_intro)
                    <p class="bns-team-join__intro">{!! bns_rich_text($page->join_intro) !!}</p>
                @endif
                @if($page->join_looking_label)
                    <p class="bns-team-join__label">{{ $page->join_looking_label }}</p>
                @endif
                @if(!empty($page->join_roles))
                    <ul class="bns-team-join__roles">
                        @foreach($page->join_roles as $role)
                            <li><i class="fas fa-circle" aria-hidden="true"></i>{!! bns_rich_text($role) !!}</li>
                        @endforeach
                    </ul>
                @endif
                @if($page->join_cta_title)
                    <h3 class="bns-team-join__cta-title">{{ $page->join_cta_title }}</h3>
                @endif
                @if($page->join_cta_text)
                    <p class="bns-team-join__cta-text">{!! bns_rich_text($page->join_cta_text) !!}</p>
                @endif
                @if($page->join_contact_email)
                    <a href="mailto:{{ $page->join_contact_email }}" class="thm-btn">
                        Get in Touch <span class="fas fa-arrow-right" aria-hidden="true"></span>
                    </a>
                @endif
            </div>

        </div>
    </section>
</div>
@endsection
