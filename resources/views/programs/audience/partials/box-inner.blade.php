<div class="bns-audience-program-box__inner">
    <span class="bns-audience-program-box__num" aria-hidden="true">{{ ($index ?? 0) + 1 }}</span>
    <div class="bns-audience-program-box__icon">
        <i class="{{ $box['icon'] ?? 'fas fa-link' }}" aria-hidden="true"></i>
    </div>
    <h3 class="bns-audience-program-box__title">{{ $box['label'] ?? '' }}</h3>
    @if(!empty($box['description']))
        <p class="bns-audience-program-box__text">{!! bns_rich_text($box['description']) !!}</p>
    @endif
    <span class="bns-audience-program-box__action">
        {{ $box['modal_cta'] ?? ('Read ' . ($box['label'] ?? 'More')) }}
        <i class="fas fa-arrow-right" aria-hidden="true"></i>
    </span>
</div>
