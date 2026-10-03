@php
    $amount = $amount ?? '';
    $variant = $variant ?? 'default';
    $showHint = $variant !== 'highlight';
@endphp
@if($amount !== '' && $amount !== null)
    <span class="bns-fee-reveal bns-fee-reveal--{{ $variant }}" data-bns-fee-reveal>
        <button
            type="button"
            class="bns-fee-reveal__toggle"
            data-bns-fee-reveal-toggle
            aria-expanded="false"
            aria-label="Click to view fees"
        >
            <i class="fas fa-eye" aria-hidden="true"></i>
            @if($showHint)
                <span class="bns-fee-reveal__hint">Click to view</span>
            @endif
        </button>
        <span class="bns-fee-reveal__amount" data-bns-fee-reveal-amount hidden>
            {!! bns_rich_text($amount) !!}
        </span>
    </span>

    @include('pitch.partials.fee-reveal-script')
@endif
