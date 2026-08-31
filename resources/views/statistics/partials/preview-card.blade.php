{{-- Compact stat card for home preview --}}
@props(['stat'])

<article class="bns-stat-preview">
    @if(!empty($stat['icon']))
        <span class="bns-stat-preview__icon" aria-hidden="true">{{ $stat['icon'] }}</span>
    @endif
    <h3>{{ $stat['title'] ?? '' }}</h3>
    @if(!empty($stat['tagline']))
        <p>{{ $stat['tagline'] }}</p>
    @elseif(!empty($stat['items']))
        <p>{{ implode(' • ', array_slice($stat['items'], 0, 3)) }}@if(count($stat['items']) > 3) …@endif</p>
    @endif
</article>
