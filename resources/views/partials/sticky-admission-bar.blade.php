@php
    $stickyCtaResolved = app(\App\Services\StickyCtaService::class)->resolve();
    $buttons = $stickyCtaResolved['buttons'] ?? [];
    $stickyIntroSession = $stickyCtaResolved['intro'] ?? [];
    $stickyInquiry = $stickyCtaResolved['inquiry'] ?? [];
    $stickyRegister = $stickyCtaResolved['register'] ?? [];
    $buttonCount = count($buttons);
@endphp

@if(!empty($stickyCtaResolved['enabled']) && !empty($buttons))
<nav class="bns-sticky-cta" aria-label="Quick admission actions">
    <div class="bns-sticky-cta__inner bns-sticky-cta__inner--cols-{{ $buttonCount }}">
        @foreach($buttons as $button)
            @php
                $fullLabel = (string) ($button['label'] ?? '');
                $shortLabel = (string) ($button['short_label'] ?? $fullLabel);
            @endphp
            @if(($button['action'] ?? 'link') === 'modal')
                <button
                    type="button"
                    class="bns-sticky-cta__btn bns-sticky-cta__btn--{{ $button['style'] ?? 'primary' }}"
                    data-bs-toggle="modal"
                    data-bs-target="#{{ $button['modal'] ?? 'bnsIntroSessionModal' }}"
                    @foreach($button['data'] ?? [] as $dataKey => $dataValue)
                        data-{{ $dataKey }}="{{ $dataValue }}"
                    @endforeach
                >
                    <span class="bns-sticky-cta__label bns-sticky-cta__label--full">{{ $fullLabel }}</span>
                    <span class="bns-sticky-cta__label bns-sticky-cta__label--short">{{ $shortLabel }}</span>
                </button>
            @else
                <a
                    href="{{ $button['url'] ?? route('register') }}"
                    class="bns-sticky-cta__btn bns-sticky-cta__btn--{{ $button['style'] ?? 'outline' }}"
                >
                    <span class="bns-sticky-cta__label bns-sticky-cta__label--full">{{ $fullLabel }}</span>
                    <span class="bns-sticky-cta__label bns-sticky-cta__label--short">{{ $shortLabel }}</span>
                </a>
            @endif
        @endforeach
    </div>
</nav>

@include('partials.inquiry-modal', [
    'stickyInquiry' => $stickyInquiry,
])
@endif
