@php
    $pillars = $pillars ?? config('about.hub.pillars', []);
    $embedded = $embedded ?? false;
@endphp

@if(!empty($pillars))
@if($embedded)
<div class="bns-home-explore bns-home-explore--embedded">
    <div class="bns-home-explore__heading">
        <h3 class="bns-home-explore__title">Explore <span>BNS</span></h3>
    </div>
@else
<section class="bns-home-explore" aria-labelledby="bns-home-explore-title">
    <div class="container">
        <div class="bns-home-explore__header text-center">
            <h2 class="bns-home-explore__title" id="bns-home-explore-title">Explore <span>BNS</span></h2>
        </div>
@endif

        <div class="row g-4 bns-home-explore__grid">
            @foreach($pillars as $index => $pillar)
                <div class="col-md-6 col-lg-4{{ !$embedded ? ' wow fadeInUp' : '' }}" @if(!$embedded) data-wow-delay="{{ 60 + ($index * 40) }}ms" @endif>
                    <a href="{{ route($pillar['route']) }}" class="bns-home-explore-card">
                        <div class="bns-home-explore-card__icon">
                            <i class="{{ $pillar['icon'] ?? 'fas fa-link' }}" aria-hidden="true"></i>
                        </div>
                        <h3 class="bns-home-explore-card__title">{{ $pillar['label'] ?? '' }}</h3>
                        <p class="bns-home-explore-card__text">{!! bns_rich_text($pillar['description'] ?? '') !!}</p>
                        <span class="bns-home-explore-card__link">Read More <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                    </a>
                </div>
            @endforeach
        </div>

@if($embedded)
</div>
@else
    </div>
</section>
@endif
@endif
