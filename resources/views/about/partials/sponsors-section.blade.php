@php

    $sponsorMembers = collect($sponsors['members'] ?? [])->sortBy('sort_order')->values();

@endphp

@if($sponsorMembers->isNotEmpty())

<div class="bns-team-section bns-team-section--sponsors">

    <div class="bns-team-section__head">

        <span class="bns-team-section__label">{{ $sponsors['section_label'] ?? 'Partners' }}</span>

        <h2 class="bns-team-section__title">{{ $sponsors['title'] ?? 'Meet Our Sponsors' }}</h2>

        @if(!empty($sponsors['subtitle']))

            <p class="bns-team-section__desc">{!! bns_rich_text($sponsors['subtitle']) !!}</p>

        @endif

    </div>

    <div class="bns-team-sponsors-grid">

        @foreach($sponsorMembers as $sponsor)

            @include('about.partials.sponsor-card', ['sponsor' => $sponsor])

        @endforeach

    </div>

</div>

@endif

