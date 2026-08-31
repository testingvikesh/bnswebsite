@php($section = $section ?? [])
@php($sectionNumber = $sectionNumber ?? null)
@php($sectionId = $sectionId ?? 'free-introduction-session')
@if(!empty($section['items']) || !empty($section['intro']))
    <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="{{ $sectionId }}">
        @include('pitch.partials.section-head', [
            'number' => $sectionNumber,
            'title' => $section['title'] ?? 'Orientation & Introduction Session',
            'icon' => 'fa-handshake',
        ])

        @if(!empty($section['intro']))
            <p class="bns-pitch-detail__section-intro">
                <i class="fas fa-star" aria-hidden="true"></i>
                {!! bns_rich_text($section['intro']) !!}
            </p>
        @endif

        @if(!empty($section['items']))
            <ul class="bns-pitch-detail__check-points bns-pitch-detail__check-points--session">
                @foreach($section['items'] as $item)
                    <li class="bns-pitch-detail__check-point">
                        <span class="bns-pitch-detail__check-icon" aria-hidden="true"><i class="fas fa-check"></i></span>
                        <span class="bns-pitch-detail__check-text">{!! bns_rich_text($item) !!}</span>
                    </li>
                @endforeach
            </ul>
        @endif

        @if(!empty($section['note']))
            <p class="bns-pitch-detail__note bns-pitch-detail__note--boxed">
                <i class="fas fa-star" aria-hidden="true"></i>
                {!! bns_rich_text($section['note']) !!}
            </p>
        @endif
    </div>
@endif
