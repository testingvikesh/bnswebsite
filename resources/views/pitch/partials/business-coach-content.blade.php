@php($pitch = $pitch ?? [])
<section class="bns-pitch-detail">
    <div class="container">
        <div class="bns-pitch-detail__hero-card wow fadeInUp" data-wow-duration="0.8s">
            <span class="bns-pitch-detail__eyebrow">{{ $pitch['hero']['eyebrow'] ?? 'Member Overview' }}</span>
            <h2 class="bns-pitch-detail__brand">{{ $pitch['hero']['brand'] ?? 'BUSINESS NAVACHAR SCHOOL (BNS)' }}</h2>
            <p class="bns-pitch-detail__subtitle">{!! bns_rich_text($pitch['hero']['subtitle'] ?? '') !!}</p>
            <div class="bns-pitch-detail__taglines">
                <p class="bns-pitch-detail__tagline bns-pitch-detail__tagline--hindi">{!! bns_rich_text($pitch['hero']['tagline_hindi'] ?? '') !!}</p>
                <p class="bns-pitch-detail__tagline">{!! bns_rich_text($pitch['hero']['tagline_en'] ?? '') !!}</p>
            </div>
            <p class="bns-pitch-detail__welcome">
                <i class="fas fa-star" aria-hidden="true"></i>
                {!! bns_rich_text($pitch['hero']['welcome'] ?? '') !!}
            </p>

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

        @if(!empty($pitch['intro']))
            <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="about-bns">
                @include('pitch.partials.section-head', [
                    'number' => 1,
                    'title' => 'About BNS',
                    'icon' => 'fa-school',
                ])
                @include('pitch.partials.star-points', ['items' => $pitch['intro']])
            </div>
        @endif

        @if(!empty($pitch['our_vision']['items']))
            <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="vision">
                @include('pitch.partials.section-head', [
                    'number' => 2,
                    'title' => $pitch['our_vision']['title'] ?? 'Our Vision',
                    'icon' => 'fa-eye',
                ])
                @include('pitch.partials.star-points', [
                    'items' => $pitch['our_vision']['items'],
                    'class' => 'bns-pitch-detail__points--grid',
                ])
            </div>
        @endif

        @if(!empty($pitch['our_mission']['items']))
            <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="mission">
                @include('pitch.partials.section-head', [
                    'number' => 3,
                    'title' => $pitch['our_mission']['title'] ?? 'Our Mission',
                    'icon' => 'fa-bullseye',
                ])
                @include('pitch.partials.star-points', [
                    'items' => $pitch['our_mission']['items'],
                    'class' => 'bns-pitch-detail__points--grid',
                ])
            </div>
        @endif

        @include('pitch.partials.member-overview-sections', [
            'pitch' => $pitch,
            'sectionNumber' => 4,
        ])

        @php($sectionNumber = 10)
        @php($eligibility = $pitch['eligibility'] ?? null)
        @if(!empty($eligibility['rows']))
            @include('pitch.partials.eligibility-section', [
                'eligibility' => $eligibility,
                'sectionNumber' => $sectionNumber,
                'wrapperClass' => 'bns-pitch-detail',
                'sectionId' => 'eligibility',
            ])
            @php($sectionNumber++)
        @endif

        @if(!empty($pitch['what_will_you_learn']))
            @include('pitch.partials.learn-method-section', [
                'section' => $pitch['what_will_you_learn'],
                'sectionNumber' => $sectionNumber,
                'type' => 'what',
                'sectionId' => 'what-will-you-learn',
            ])
            @php($sectionNumber++)
        @endif

        @if(!empty($pitch['how_will_you_learn']))
            @include('pitch.partials.learn-method-section', [
                'section' => $pitch['how_will_you_learn'],
                'sectionNumber' => $sectionNumber,
                'type' => 'how',
                'sectionId' => 'how-will-you-learn',
            ])
            @php($sectionNumber++)
        @endif

        @foreach(['who_will_teach' => 'who-will-teach', 'certifications' => 'certifications'] as $sectionKey => $sectionId)
            @php($section = $pitch[$sectionKey] ?? null)
            @if(!empty($section['items']))
                <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="{{ $sectionId }}">
                    @include('pitch.partials.section-head', [
                        'number' => $sectionNumber,
                        'title' => $section['title'] ?? '',
                        'icon' => $sectionKey === 'who_will_teach' ? 'fa-chalkboard-teacher' : 'fa-certificate',
                    ])
                    @php($sectionNumber++)
                    @if(!empty($section['intro']))
                        <p class="bns-pitch-detail__section-intro">
                            <i class="fas fa-star" aria-hidden="true"></i>
                            {!! bns_rich_text($section['intro']) !!}
                        </p>
                    @endif
                    @include('pitch.partials.star-points', ['items' => $section['items'], 'class' => 'bns-pitch-detail__points--grid'])
                    @if(!empty($section['note']))
                        <p class="bns-pitch-detail__note bns-pitch-detail__note--boxed">
                            <i class="fas fa-star" aria-hidden="true"></i>
                            {!! bns_rich_text($section['note']) !!}
                        </p>
                    @endif
                </div>
            @endif
        @endforeach

        @if(!empty($pitch['orientation_introduction_session']))
            @include('pitch.partials.orientation-session-section', [
                'section' => $pitch['orientation_introduction_session'],
                'sectionNumber' => $sectionNumber,
                'sectionId' => 'free-introduction-session',
            ])
            @php($sectionNumber++)
        @endif

        @php($section = $pitch['master_sessions'] ?? null)
        @if(!empty($section['items']))
            <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="master-session">
                @include('pitch.partials.section-head', [
                    'number' => $sectionNumber,
                    'title' => $section['title'] ?? '',
                    'icon' => 'fa-microphone',
                ])
                @php($sectionNumber++)
                @if(!empty($section['intro']))
                    <p class="bns-pitch-detail__section-intro">
                        <i class="fas fa-star" aria-hidden="true"></i>
                        {!! bns_rich_text($section['intro']) !!}
                    </p>
                @endif
                @include('pitch.partials.star-points', ['items' => $section['items'], 'class' => 'bns-pitch-detail__points--grid'])
            </div>
        @endif

        @include('pitch.partials.member-pitch-closing-sections', [
            'pitch' => $pitch,
            'sectionNumber' => $sectionNumber,
        ])
    </div>
</section>
