@php
    $listings = config('legal.privacy_listings', []);
@endphp

@if(!empty($listings))
<nav class="bns-privacy-listing" aria-label="Privacy policy sections">
    <p class="bns-privacy-listing__title">Privacy Policy — Quick Access</p>
    <div class="bns-privacy-listing__grid">
        @foreach($listings as $item)
            <a href="{{ route('legal.show', $item['slug']) }}"
               class="bns-privacy-listing__btn{{ ($currentSlug ?? '') === $item['slug'] ? ' is-active' : '' }}"
               @if(($currentSlug ?? '') === $item['slug']) aria-current="page" @endif>
                @if(!empty($item['icon']))
                    <span class="bns-privacy-listing__icon"><i class="{{ $item['icon'] }}" aria-hidden="true"></i></span>
                @endif
                <span class="bns-privacy-listing__label">{{ $item['title'] }}</span>
                @if(!empty($item['description']))
                    <span class="bns-privacy-listing__desc">{!! bns_rich_text($item['description']) !!}</span>
                @endif
            </a>
        @endforeach
    </div>
</nav>
@endif
