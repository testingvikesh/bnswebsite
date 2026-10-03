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

    @once('bns-fee-reveal-script')
        <script>
        (function () {
            'use strict';

            document.addEventListener('click', function (event) {
                var toggle = event.target.closest('[data-bns-fee-reveal-toggle]');
                if (!toggle) return;

                var root = toggle.closest('[data-bns-fee-reveal]');
                if (!root) return;

                var amount = root.querySelector('[data-bns-fee-reveal-amount]');
                var icon = toggle.querySelector('i');
                var hint = toggle.querySelector('.bns-fee-reveal__hint');
                if (!amount) return;

                var isOpen = root.classList.toggle('is-open');
                amount.hidden = !isOpen;
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                toggle.setAttribute('aria-label', isOpen ? 'Hide fees' : 'Click to view fees');

                if (icon) {
                    icon.classList.toggle('fa-eye', !isOpen);
                    icon.classList.toggle('fa-eye-slash', isOpen);
                }
                if (hint) {
                    hint.hidden = isOpen;
                }
            });
        })();
        </script>
    @endonce
@endif
