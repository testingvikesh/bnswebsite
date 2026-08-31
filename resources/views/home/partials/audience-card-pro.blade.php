@php
    $theme = $card['card_theme'] ?? [];
    $titleTop = $card['card_title_top'] ?? strtoupper($card['label'] ?? '');
    $titleMain = $card['card_title_main'] ?? '';
    $imageUrl = $card['image_url'] ?? (($img ?? null) ? $img($card['card_image'] ?? '') : '');
    $imageAlt = $card['image_alt'] ?? ($card['label'] ?? 'BNS Program');
@endphp

<div
    class="bns-audience-card__inner"
    style="--bns-card-bg: {{ $theme['bg'] ?? '#4a0e1c' }}; --bns-card-accent: {{ $theme['accent'] ?? '#ffb08f' }};"
>
    <div class="bns-audience-card__media">
        @if($imageUrl)
            <img
                src="{{ $imageUrl }}"
                alt="{{ $imageAlt }}"
                class="bns-audience-card__image"
                loading="lazy"
                decoding="async"
            >
        @endif
    </div>
    <div class="bns-audience-card__content">
        <span class="bns-audience-card__rule" aria-hidden="true"></span>
        <p class="bns-audience-card__title-top">{{ $titleTop }}</p>
        @if($titleMain !== '')
            <h3 class="bns-audience-card__title-main">{{ $titleMain }}</h3>
        @endif
        <span class="bns-audience-card__rule" aria-hidden="true"></span>
        @if(!empty($card['desc']))
            <p class="bns-audience-card__desc">{!! bns_rich_text($card['desc']) !!}</p>
        @endif
        <span class="bns-audience-card__cta">
            View Program <i class="fas fa-arrow-right" aria-hidden="true"></i>
        </span>
    </div>
</div>
