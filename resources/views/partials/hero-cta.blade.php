@props(['cta'])

@php
    $style = $cta['style'] ?? 'outline';
    $classes = 'bns-hero-cta bns-hero-cta--'.$style;
    $label = ($cta['icon'] ?? '').' '.($cta['label'] ?? '');
    $label = trim($label);
@endphp

@if(!empty($cta['is_video']))
    <a href="{{ $cta['url'] }}" class="{{ $classes }} video-popup" rel="noopener noreferrer">
        <span>{{ $label }}</span>
        <i class="fas fa-play" aria-hidden="true"></i>
    </a>
@elseif(($cta['type'] ?? '') === 'whatsapp' || !empty($cta['is_external']))
    <a href="{{ $cta['url'] }}" class="{{ $classes }}" target="_blank" rel="noopener noreferrer">
        <span>{{ $label }}</span>
        <i class="fas fa-arrow-right" aria-hidden="true"></i>
    </a>
@else
    <a href="{{ $cta['url'] }}" class="{{ $classes }}">
        <span>{{ $label }}</span>
        <i class="fas fa-arrow-right" aria-hidden="true"></i>
    </a>
@endif
