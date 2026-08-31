@php($presentation = $coachPresentation ?? config('home_coach_presentation', []))
@if(!empty($presentation['enabled']))
<section class="bns-coach-presentation" id="business-coaches-and-bns" aria-labelledby="bns-coach-presentation-title">
    <div class="container">
        <header class="bns-coach-presentation__header wow fadeInUp" data-wow-duration="0.8s">
            @if(!empty($presentation['header']['eyebrow']))
                <span class="bns-coach-presentation__eyebrow">{{ $presentation['header']['eyebrow'] }}</span>
            @endif
            <h2 class="bns-coach-presentation__title" id="bns-coach-presentation-title">
                {{ $presentation['header']['title'] ?? 'Business Coach Presentation Points' }}
            </h2>
            @if(!empty($presentation['header']['subtitle']))
                <p class="bns-coach-presentation__subtitle">{{ $presentation['header']['subtitle'] }}</p>
            @endif
        </header>

        @if(!empty($presentation['highlights']))
        <div class="bns-coach-presentation__highlights wow fadeInUp" data-wow-duration="0.85s">
            @foreach($presentation['highlights'] as $item)
                <article class="bns-coach-presentation__highlight-card">
                    <span class="bns-coach-presentation__highlight-icon" aria-hidden="true">
                        <i class="fas {{ $item['icon'] ?? 'fa-star' }}"></i>
                    </span>
                    <strong>{{ $item['value'] }}</strong>
                    <span>{{ $item['label'] }}</span>
                </article>
            @endforeach
        </div>
        @endif

        <div class="bns-coach-presentation__grid wow fadeInUp" data-wow-duration="0.85s">
            @foreach($presentation['sections'] ?? [] as $section)
                @php($type = $section['type'] ?? 'default')
                <article class="bns-coach-presentation__card bns-coach-presentation__card--{{ $type }}">
                    <div class="bns-coach-presentation__card-head">
                        <span class="bns-coach-presentation__card-num">{{ $section['number'] ?? '' }}</span>
                        <h3 class="bns-coach-presentation__card-title">{{ $section['title'] ?? '' }}</h3>
                    </div>

                    <div class="bns-coach-presentation__card-body">
                        @if(!empty($section['highlight']))
                            <p class="bns-coach-presentation__callout">
                                <i class="fas fa-star" aria-hidden="true"></i>
                                {!! bns_rich_text($section['highlight']) !!}
                            </p>
                        @endif

                        @foreach($section['paragraphs'] ?? [] as $paragraph)
                            <p class="bns-coach-presentation__para">{!! bns_rich_text($paragraph) !!}</p>
                        @endforeach

                        @if(!empty($section['points']))
                            <ul class="bns-coach-presentation__points">
                                @foreach($section['points'] as $point)
                                    <li class="bns-coach-presentation__point">
                                        <span class="bns-coach-presentation__point-icon" aria-hidden="true"><i class="fas fa-circle"></i></span>
                                        <span class="bns-coach-presentation__point-text">{!! bns_point_html($point) !!}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if(!empty($section['bullets']))
                            <ul class="bns-coach-presentation__points">
                                @foreach($section['bullets'] as $bullet)
                                    <li class="bns-coach-presentation__point">
                                        <span class="bns-coach-presentation__point-icon" aria-hidden="true"><i class="fas fa-circle"></i></span>
                                        <span class="bns-coach-presentation__point-text">{!! bns_point_html($bullet) !!}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if($type === 'growth_path' && !empty($section['steps']))
                            <ol class="bns-coach-presentation__path list-unstyled">
                                @foreach($section['steps'] as $stepIndex => $step)
                                    <li>
                                        <span class="bns-coach-presentation__point-icon" aria-hidden="true">
                                            <i class="fas {{ $stepIndex === 0 ? 'fa-play-circle' : 'fa-arrow-down' }}"></i>
                                        </span>
                                        <strong class="bns-em">{!! bns_rich_text($step, false) !!}</strong>
                                    </li>
                                @endforeach
                            </ol>
                        @endif

                        @if(!empty($section['tags']))
                            <ul class="bns-coach-presentation__points bns-coach-presentation__points--tags">
                                @foreach($section['tags'] as $tag)
                                    <li class="bns-coach-presentation__point">
                                        <span class="bns-coach-presentation__point-icon" aria-hidden="true"><i class="fas fa-circle"></i></span>
                                        <span class="bns-coach-presentation__point-text"><strong class="bns-em">{!! bns_rich_text($tag, false) !!}</strong></span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if($type === 'events' && !empty($section['events']))
                            <ul class="bns-coach-presentation__points bns-coach-presentation__points--events">
                                @foreach($section['events'] as $event)
                                    <li class="bns-coach-presentation__point">
                                        <span class="bns-coach-presentation__point-icon" aria-hidden="true"><i class="fas fa-circle"></i></span>
                                        <span class="bns-coach-presentation__point-text"><strong class="bns-em">{!! bns_rich_text($event, false) !!}</strong></span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @foreach($section['footer_paragraphs'] ?? [] as $paragraph)
                            <p class="bns-coach-presentation__para bns-coach-presentation__para--footer">{!! bns_rich_text($paragraph) !!}</p>
                        @endforeach

                        @if(!empty($section['footer_highlight']))
                            <p class="bns-coach-presentation__callout bns-coach-presentation__callout--footer">
                                <i class="fas fa-star" aria-hidden="true"></i>
                                {!! bns_rich_text($section['footer_highlight']) !!}
                            </p>
                        @endif

                        @if($type === 'closing' && !empty($section['taglines']))
                            <div class="bns-coach-presentation__taglines">
                                @foreach($section['taglines'] as $tagline)
                                    <p><i class="fas fa-circle" aria-hidden="true"></i> {!! bns_rich_text($tagline) !!}</p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
