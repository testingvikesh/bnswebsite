@php($pitch = $pitch ?? [])
<section class="bns-pitch-detail">
    <div class="container">
        <div class="bns-pitch-detail__hero-card wow fadeInUp" data-wow-duration="0.8s">
            <span class="bns-pitch-detail__eyebrow">{{ $pitch['hero']['eyebrow'] ?? 'Growth Batch' }}</span>
            <h2 class="bns-pitch-detail__brand">{{ $pitch['hero']['brand'] ?? 'BUSINESS NAVACHAR SCHOOL' }}</h2>
            <p class="bns-pitch-detail__subtitle">{!! bns_rich_text($pitch['hero']['subtitle'] ?? '') !!}</p>
            @if(!empty($pitch['hero']['tagline_en']))
                <div class="bns-pitch-detail__taglines">
                    <p class="bns-pitch-detail__tagline">{!! bns_rich_text($pitch['hero']['tagline_en']) !!}</p>
                </div>
            @endif
            @if(!empty($pitch['hero']['welcome']))
                <p class="bns-pitch-detail__welcome">
                    <i class="fas fa-star" aria-hidden="true"></i>
                    {!! bns_rich_text($pitch['hero']['welcome']) !!}
                </p>
            @endif
            @if(!empty($pitch['hero_highlights']))
                <div class="bns-pitch-detail__highlights">
                    @foreach($pitch['hero_highlights'] as $highlight)
                        <article class="bns-pitch-detail__highlight-card">
                            <span class="bns-pitch-detail__highlight-icon" aria-hidden="true">
                                <i class="fas {{ $highlight['icon'] ?? 'fa-star' }}"></i>
                            </span>
                            <strong>{{ $highlight['value'] ?? '' }}</strong>
                            <span>{{ $highlight['label'] ?? '' }}</span>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>

        @foreach($pitch['sections'] ?? [] as $section)
            <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="{{ $section['id'] ?? '' }}">
                @include('pitch.partials.section-head', [
                    'number' => $section['number'] ?? null,
                    'title' => $section['title'] ?? '',
                    'icon' => $section['icon'] ?? 'fa-star',
                ])

                @if(!empty($section['intro']))
                    <p class="bns-pitch-detail__section-intro">{!! bns_rich_text($section['intro']) !!}</p>
                @endif

                @if(!empty($section['lead']))
                    <p class="bns-pitch-detail__section-lead">{!! bns_rich_text($section['lead']) !!}</p>
                @endif

                @if(!empty($section['tagline']))
                    <p class="bns-pitch-detail__journey-banner">{!! bns_rich_text($section['tagline']) !!}</p>
                @endif

                @if(!empty($section['before_table']))
                    <p class="bns-pitch-detail__block-label">{!! bns_rich_text($section['before_table']) !!}</p>
                @endif

                @foreach($section['tables'] ?? [] as $extraTable)
                    @if(!empty($extraTable['label']))
                        <p class="bns-pitch-detail__block-label">{!! bns_rich_text($extraTable['label']) !!}</p>
                    @endif
                    @include('pitch.partials.growth-table', ['table' => $extraTable])
                @endforeach

                @if(!empty($section['idea']))
                    <div class="bns-pitch-detail__idea">
                        @if(!empty($section['idea']['title']))
                            <p class="bns-pitch-detail__block-label">{!! bns_rich_text($section['idea']['title']) !!}</p>
                        @endif
                        @if(!empty($section['idea']['points']))
                            @include('pitch.partials.star-points', [
                                'items' => $section['idea']['points'],
                                'class' => 'bns-pitch-detail__points--grid',
                            ])
                        @endif
                        @if(!empty($section['idea']['text']))
                            <p class="bns-pitch-detail__note bns-pitch-detail__note--boxed">
                                <i class="fas fa-star" aria-hidden="true"></i>
                                {!! bns_rich_text($section['idea']['text']) !!}
                            </p>
                        @endif
                    </div>
                @endif

                @include('pitch.partials.growth-table', ['table' => $section['table'] ?? []])

                @if(!empty($section['block_label']))
                    <p class="bns-pitch-detail__block-label">{!! bns_rich_text($section['block_label']) !!}</p>
                @endif

                @if(!empty($section['table_2']))
                    @include('pitch.partials.growth-table', ['table' => $section['table_2']])
                @endif

                @if(!empty($section['points']))
                    @include('pitch.partials.star-points', [
                        'items' => $section['points'],
                        'class' => 'bns-pitch-detail__points--grid',
                    ])
                @endif

                @if(!empty($section['chips']))
                    <div class="bns-pitch-detail__chips">
                        @foreach($section['chips'] as $chip)
                            <span class="bns-pitch-detail__chip">{{ $chip }}</span>
                        @endforeach
                    </div>
                @endif

                @if(!empty($section['banners']))
                    @foreach($section['banners'] as $banner)
                        <p class="bns-pitch-detail__journey-banner">{!! bns_rich_text($banner) !!}</p>
                    @endforeach
                @endif

                @if(!empty($section['callout']))
                    <p class="bns-pitch-detail__methodology-banner">{!! bns_rich_text($section['callout']) !!}</p>
                @endif

                @if(!empty($section['note']))
                    <p class="bns-pitch-detail__note bns-pitch-detail__note--boxed">
                        <i class="fas fa-star" aria-hidden="true"></i>
                        {!! bns_rich_text($section['note']) !!}
                    </p>
                @endif
            </div>
        @endforeach

        @php($schoolTypes = config('business_school_types', []))
        @if(!empty($schoolTypes['rows']))
            <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="fees-structure">
                @include('pitch.partials.section-head', [
                    'number' => 28,
                    'title' => 'Fees Structure',
                    'icon' => 'fa-gem',
                ])
                @include('pitch.partials.school-types-table', [
                    'schoolTypes' => $schoolTypes,
                    'wrapperClass' => 'bns-pitch-detail__school-types',
                    'showTitle' => false,
                ])
            </div>
        @endif

        <div class="bns-pitch-detail__section bns-pitch-detail__section--closing wow fadeInUp" data-wow-duration="0.85s" id="join-bns">
            @include('pitch.partials.section-head', [
                'number' => 29,
                'title' => "Join India's First Weekly Business School",
                'icon' => 'fa-graduation-cap',
            ])
            <p class="bns-pitch-detail__closing-text">{!! bns_rich_text('Business Navachar School (BNS) is not just a course—it is a <strong>Business Learning, Mentoring, Networking, and Growth Ecosystem</strong> designed to help individuals transform ideas into successful businesses and <strong>lifelong prosperity</strong>.') !!}</p>
        </div>

        @if(!empty($pitch['cta']))
            @php($cta = $pitch['cta'])
            <div class="bns-pitch-detail__website wow fadeInUp" data-wow-duration="0.85s">
                <span class="bns-pitch-detail__website-icon" aria-hidden="true"><i class="fas fa-rocket"></i></span>
                <span class="bns-pitch-detail__website-label">{{ $cta['label'] ?? 'Next step' }}</span>
                <h3 class="bns-pitch-detail__section-title">{{ $cta['title'] ?? '' }}</h3>
                @if(!empty($cta['text']))
                    <p class="bns-pitch-detail__section-intro">{!! bns_rich_text($cta['text']) !!}</p>
                @endif
                @if(!empty($cta['buttons']))
                    <div class="bns-pitch-detail__cta-row">
                        @foreach($cta['buttons'] as $button)
                            <a class="bns-pitch-detail__cta-btn" href="{{ route($button['route'], $button['params'] ?? []) }}">
                                {{ $button['label'] ?? 'Continue' }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>
