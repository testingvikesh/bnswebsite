<article class="bns-orientation-pitch__accordion-item{{ !empty($openFirst) ? ' is-open' : '' }}">
    <button
        type="button"
        class="bns-orientation-pitch__accordion-trigger"
        aria-expanded="{{ !empty($openFirst) ? 'true' : 'false' }}"
        data-orientation-accordion
    >
        <span class="bns-orientation-pitch__accordion-num">{{ $section['number'] ?? ($index + 1) }}</span>
        <span class="bns-orientation-pitch__accordion-title">{{ $section['title'] ?? '' }}</span>
        <i class="fas fa-chevron-down" aria-hidden="true"></i>
    </button>
    <div class="bns-orientation-pitch__accordion-panel" @if(empty($openFirst)) hidden @endif>
        @if(!empty($section['points']) || !empty($section['paragraphs']))
            <ul class="bns-orientation-pitch__points">
                @foreach($section['points'] ?? $section['paragraphs'] ?? [] as $point)
                    <li class="bns-orientation-pitch__point">
                        <span class="bns-orientation-pitch__point-icon" aria-hidden="true"><i class="fas fa-circle"></i></span>
                        <span class="bns-orientation-pitch__point-text">{!! bns_point_html($point) !!}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        @if(!empty($section['bullets']))
            <ul class="bns-orientation-pitch__points">
                @foreach($section['bullets'] as $bullet)
                    <li class="bns-orientation-pitch__point">
                        <span class="bns-orientation-pitch__point-icon" aria-hidden="true"><i class="fas fa-circle"></i></span>
                        <span class="bns-orientation-pitch__point-text">{!! bns_point_html($bullet) !!}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        @if(!empty($section['footer_points']))
            <ul class="bns-orientation-pitch__points">
                @foreach($section['footer_points'] as $point)
                    <li class="bns-orientation-pitch__point">
                        <span class="bns-orientation-pitch__point-icon" aria-hidden="true"><i class="fas fa-circle"></i></span>
                        <span class="bns-orientation-pitch__point-text">{!! bns_point_html($point) !!}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</article>
