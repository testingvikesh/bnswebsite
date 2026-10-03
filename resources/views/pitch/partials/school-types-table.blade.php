@php($schoolTypes = $schoolTypes ?? config('business_school_types', []))
@php($wrapperClass = $wrapperClass ?? 'bns-school-types')
@if(!empty($schoolTypes['rows']))
    <div class="{{ $wrapperClass }}">
        @if(!empty($showTitle))
            <h3 class="{{ $wrapperClass }}__title">{{ $schoolTypes['title'] ?? 'Four Types of Business Schools' }}</h3>
        @endif
        @if(!empty($schoolTypes['intro']))
            <p class="{{ $wrapperClass }}__intro">
                <i class="fas fa-star" aria-hidden="true"></i>
                {!! bns_rich_text($schoolTypes['intro']) !!}
            </p>
        @endif
        <div class="{{ $wrapperClass }}__table-wrap">
            <table class="{{ $wrapperClass }}__table">
                <thead>
                    <tr>
                        @foreach($schoolTypes['headers'] ?? [] as $index => $header)
                            <th @if($index > 0) class="{{ $wrapperClass }}__tier-head {{ $wrapperClass }}__tier-head--{{ $index }}" @endif>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($schoolTypes['rows'] as $row)
                        <tr>
                            @foreach($row as $index => $cell)
                                @php($isFeeAmount = $index > 0 && (str_contains((string) $cell, '₹') || preg_match('/\bGST\b/i', (string) $cell)))
                                <td @if($index === 0) class="{{ $wrapperClass }}__feature-cell" @endif>
                                    @if($index === 0)
                                        <i class="fas fa-star {{ $wrapperClass }}__cell-star" aria-hidden="true"></i>
                                    @endif
                                    @if($isFeeAmount)
                                        <span class="bns-fee-reveal" data-bns-fee-reveal>
                                            <button
                                                type="button"
                                                class="bns-fee-reveal__toggle"
                                                data-bns-fee-reveal-toggle
                                                aria-expanded="false"
                                                aria-label="Click to view fees"
                                            >
                                                <i class="fas fa-eye" aria-hidden="true"></i>
                                                <span class="bns-fee-reveal__hint">Click to view</span>
                                            </button>
                                            <span class="bns-fee-reveal__amount" data-bns-fee-reveal-amount hidden>
                                                {!! bns_rich_text($cell) !!}
                                            </span>
                                        </span>
                                    @else
                                        {!! bns_rich_text($cell) !!}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

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
