@php($section = $section ?? [])
@php($sectionNumber = $sectionNumber ?? 10)
@php($type = $type ?? 'what')
@php($sectionId = $sectionId ?? ($type === 'what' ? 'what-will-you-learn' : 'how-will-you-learn'))
@if(!empty($section))
    <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="{{ $sectionId }}">
        @include('pitch.partials.section-head', [
            'number' => $sectionNumber,
            'title' => $section['title'] ?? '',
            'icon' => $type === 'what' ? 'fa-book-open' : 'fa-clipboard-check',
        ])

        @if($type === 'what')
            @if(!empty($section['intro']))
                <p class="bns-pitch-detail__section-intro">
                    <i class="fas fa-star" aria-hidden="true"></i>
                    {!! bns_rich_text($section['intro']) !!}
                </p>
            @endif
            @if(!empty($section['journey']))
                <p class="bns-pitch-detail__journey-banner">{!! bns_rich_text($section['journey']) !!}</p>
            @endif
            @if(!empty($section['including_label']))
                <p class="bns-pitch-detail__including-label">{{ $section['including_label'] }}</p>
            @endif
            @if(!empty($section['items']))
                @include('pitch.partials.star-points', [
                    'items' => $section['items'],
                    'class' => 'bns-pitch-detail__points--grid',
                ])
            @endif
        @else
            @if(!empty($section['intro']))
                <p class="bns-pitch-detail__section-intro">
                    <i class="fas fa-star" aria-hidden="true"></i>
                    {!! bns_rich_text($section['intro']) !!}
                </p>
            @endif
            @if(!empty($section['methodology']))
                <p class="bns-pitch-detail__methodology-banner">{!! bns_rich_text($section['methodology']) !!}</p>
            @endif
            @if(!empty($section['items']))
                <ul class="bns-pitch-detail__check-points bns-pitch-detail__points--grid">
                    @foreach($section['items'] as $item)
                        <li class="bns-pitch-detail__check-point">
                            <span class="bns-pitch-detail__check-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                            <span class="bns-pitch-detail__check-text">{!! bns_rich_text($item) !!}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        @endif
    </div>
@endif
