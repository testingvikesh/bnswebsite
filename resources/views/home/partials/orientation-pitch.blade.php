@php
    $pitch = $orientationPitch ?? config('home_orientation_pitch', []);
@endphp
@if(!empty($pitch['enabled']))
<section class="bns-orientation-pitch" aria-labelledby="bns-orientation-pitch-title">
    <div class="container">
        <header class="bns-orientation-pitch__header wow fadeInUp" data-wow-duration="0.8s">
            @if(!empty($pitch['header']['eyebrow']))
                <span class="bns-orientation-pitch__eyebrow">{{ $pitch['header']['eyebrow'] }}</span>
            @endif
            <h2 class="bns-orientation-pitch__title" id="bns-orientation-pitch-title">
                {{ $pitch['header']['title'] ?? 'Business Navachar School (BNS)' }}
            </h2>
            @foreach($pitch['header']['intro'] ?? [] as $line)
                <p class="bns-orientation-pitch__intro-line">{!! bns_rich_text($line) !!}</p>
            @endforeach
        </header>

        @php
            $overviewSections = $pitch['sections'] ?? [];
            $overviewFirstSections = array_slice($overviewSections, 0, 4);
            $overviewMidSections = array_slice($overviewSections, 4, 3);
            $overviewRestSections = array_slice($overviewSections, 7);
        @endphp

        @if(!empty($overviewFirstSections))
        <div class="bns-orientation-pitch__accordion bns-orientation-pitch__accordion--bottom wow fadeInUp" data-wow-duration="0.85s" id="complete-overview">
            <h3 class="bns-orientation-pitch__block-title">Complete Orientation Overview</h3>
            <div class="bns-orientation-pitch__accordion-list">
                @foreach($overviewFirstSections as $index => $section)
                    @include('home.partials.orientation-pitch-accordion-item', [
                        'section' => $section,
                        'index' => $index,
                        'openFirst' => $index === 0,
                    ])
                @endforeach
            </div>
        </div>
        @endif

        <div id="level-next-digital-business-school">
            @if(!empty($pitch['level_next_title']))
            <h3 class="bns-orientation-pitch__bridge-title wow fadeInUp" data-wow-duration="0.85s">{{ $pitch['level_next_title'] }}</h3>
            @endif

            @if(!empty($pitch['stats']))
            <div class="bns-orientation-pitch__stats wow fadeInUp" data-wow-duration="0.85s">
                @foreach($pitch['stats'] as $stat)
                    <article class="bns-orientation-pitch__stat">
                        <span class="bns-orientation-pitch__stat-icon" aria-hidden="true">
                            <i class="fas {{ $stat['icon'] ?? 'fa-star' }}"></i>
                        </span>
                        <strong class="bns-orientation-pitch__stat-value">{{ $stat['value'] }}</strong>
                        <span class="bns-orientation-pitch__stat-label">{{ $stat['label'] }}</span>
                    </article>
                @endforeach
            </div>
            @endif
        </div>

        @if(!empty($overviewMidSections))
        <div class="bns-orientation-pitch__accordion bns-orientation-pitch__accordion--mid wow fadeInUp" data-wow-duration="0.85s">
            <div class="bns-orientation-pitch__accordion-list">
                @foreach($overviewMidSections as $index => $section)
                    @include('home.partials.orientation-pitch-accordion-item', [
                        'section' => $section,
                        'index' => $index + 4,
                        'openFirst' => false,
                    ])
                @endforeach
            </div>
        </div>
        @endif

        <div class="bns-orientation-pitch__highlights wow fadeInUp" data-wow-duration="0.85s">
            <article class="bns-orientation-pitch__highlight bns-orientation-pitch__highlight--chain" id="why-name-bns">
                <h3>Why the Name "Business Navachar School"?</h3>
                <p class="bns-orientation-pitch__highlight-sub">Navachar means Innovation • New Thinking • New Ideas • Creative Solutions</p>
                <p class="bns-orientation-pitch__highlight-note">Every successful business starts with innovation.</p>
                <ul class="bns-orientation-pitch__chain list-unstyled">
                    @foreach($pitch['navachar_chain'] ?? [] as $line)
                        <li>{!! bns_rich_text($line) !!}</li>
                    @endforeach
                </ul>
            </article>

            <article class="bns-orientation-pitch__highlight bns-orientation-pitch__highlight--weekly">
                <h3>India's Weekly Business School</h3>
                <div class="bns-orientation-pitch__weekly-grid">
                    @foreach($pitch['weekly_model'] ?? [] as $item)
                        <div class="bns-orientation-pitch__weekly-item">
                            <strong>{{ $item['value'] }}</strong>
                            <span>{{ $item['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        @if(!empty($pitch['programs']))
        <div class="bns-orientation-pitch__block wow fadeInUp" data-wow-duration="0.85s" id="our-programs">
            <h3 class="bns-orientation-pitch__block-title">Our Programs</h3>
            <div class="bns-orientation-pitch__programs">
                @foreach($pitch['programs'] as $program)
                    <article class="bns-orientation-pitch__program">
                        <span class="bns-orientation-pitch__program-icon" aria-hidden="true">
                            <i class="fas {{ $program['icon'] ?? 'fa-book' }}"></i>
                        </span>
                        <h4>{{ $program['name'] }}</h4>
                        <p>{!! bns_rich_text($program['duration']) !!}</p>
                    </article>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($pitch['curriculum']))
        <div class="bns-orientation-pitch__block wow fadeInUp" data-wow-duration="0.85s" id="what-do-we-teach">
            <h3 class="bns-orientation-pitch__block-title">What Do We Teach?</h3>
            <p class="bns-orientation-pitch__journey">{!! bns_rich_text($pitch['curriculum_journey'] ?? 'Idea → Startup → Business → Growth → Scale → Corporate → Funding → IPO') !!}</p>
            <div class="bns-orientation-pitch__tags">
                @foreach($pitch['curriculum'] as $topic)
                    <span class="bns-orientation-pitch__tag">{!! bns_rich_text($topic) !!}</span>
                @endforeach
                <span class="bns-orientation-pitch__tag bns-orientation-pitch__tag--more">And many more</span>
            </div>
        </div>
        @endif

        <div class="bns-orientation-pitch__member-overview wow fadeInUp" data-wow-duration="0.85s">
            @include('pitch.partials.member-overview-sections', [
                'pitch' => $memberPitch ?? config('business_coach_pitch', []),
                'sectionNumber' => 2,
                'showSectionNumbers' => false,
            ])
        </div>

        <div class="bns-orientation-pitch__eligibility wow fadeInUp" data-wow-duration="0.85s">
            @include('pitch.partials.eligibility-section', [
                'eligibility' => ($memberPitch ?? config('business_coach_pitch', []))['eligibility'] ?? [],
                'sectionNumber' => 9,
                'wrapperClass' => 'bns-pitch-detail',
                'showSectionNumbers' => false,
            ])
        </div>

        <div class="bns-orientation-pitch__coach wow fadeInUp" data-wow-duration="0.85s">
            <div class="bns-orientation-pitch__coach-intro" id="why-we-need-business-coaches">
                <h3>Why We Need Business Coaches</h3>
                <p>A Business Coach is more than a teacher.</p>
                <p>Your experience can change someone's life. Your guidance can help create future entrepreneurs.</p>
                <ul class="bns-orientation-pitch__roles list-unstyled">
                    @foreach($pitch['coach_roles'] ?? [] as $role)
                        <li>
                            <i class="fas fa-check" aria-hidden="true"></i>
                            <span>{!! bns_rich_text($role) !!}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="bns-orientation-pitch__coach-benefits" id="business-coach-benefits">
                <h4>Business Coach Benefits</h4>
                <ul class="list-unstyled">
                    @foreach($pitch['coach_benefits'] ?? [] as $benefit)
                        <li>
                            <i class="fas fa-star" aria-hidden="true"></i>
                            <span>{!! bns_rich_text($benefit) !!}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="bns-orientation-pitch__vision wow fadeInUp" data-wow-duration="0.85s">
            <div class="bns-orientation-pitch__vision-main" id="three-hundred-schools">
                <span class="bns-orientation-pitch__vision-number">{{ $pitch['vision']['number'] ?? '300' }}</span>
                <h3>{{ $pitch['vision']['title'] ?? 'Business Navachar Schools Across India' }}</h3>
                <p>{!! bns_rich_text($pitch['vision']['text'] ?? 'Our dream is to establish 300 Business Navachar Schools across India, and in the future, across the world.') !!}</p>
                @if(!empty($pitch['vision_centers']))
                <p>{!! bns_rich_text($pitch['vision']['centers_intro'] ?? 'Every BNS School will become a center for:') !!}</p>
                    <ul class="bns-orientation-pitch__vision-centers list-unstyled">
                        @foreach($pitch['vision_centers'] as $center)
                            <li>
                                <i class="fas fa-check" aria-hidden="true"></i>
                                <span>{!! bns_rich_text($center) !!}</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            <div class="bns-orientation-pitch__vision-2047" id="vision-2047">
                <h4>{{ $pitch['vision_2047']['title'] ?? 'Vision 2047 — Viksit Bharat' }}</h4>
                <p>{!! bns_rich_text($pitch['vision_2047']['text'] ?? 'By the year 2047, we want to contribute to Viksit Bharat by creating entrepreneurs, startup founders, business owners, business leaders, employers, and wealth creators — instead of only job seekers.') !!}</p>
            </div>
        </div>

        @if(!empty($overviewRestSections))
        <div class="bns-orientation-pitch__accordion wow fadeInUp" data-wow-duration="0.85s">
            <h3 class="bns-orientation-pitch__block-title">More Orientation Topics</h3>
            <div class="bns-orientation-pitch__accordion-list">
                @foreach($overviewRestSections as $index => $section)
                    @include('home.partials.orientation-pitch-accordion-item', [
                        'section' => $section,
                        'index' => $index,
                        'openFirst' => false,
                    ])
                @endforeach
            </div>
        </div>
        @endif

        <footer class="bns-orientation-pitch__footer wow fadeInUp" data-wow-duration="0.85s">
            <h3>{{ $pitch['footer']['title'] ?? 'We Are Building a Movement' }}</h3>
            <ul class="bns-orientation-pitch__closing list-unstyled">
                @foreach($pitch['closing'] ?? [] as $line)
                    <li>{!! bns_rich_text($line) !!}</li>
                @endforeach
            </ul>
            <div class="bns-orientation-pitch__taglines">
                @foreach($pitch['taglines'] ?? [] as $tagline)
                    <p>{!! bns_rich_text($tagline) !!}</p>
                @endforeach
            </div>
            <p class="bns-orientation-pitch__thanks">{!! bns_rich_text($pitch['footer']['thanks'] ?? 'Thank You — Business Navachar School (BNS)') !!}</p>
        </footer>
    </div>
</section>
@endif
