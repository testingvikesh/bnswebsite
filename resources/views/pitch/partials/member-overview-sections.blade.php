@php($pitch = $pitch ?? [])
@php($sectionNumber = $sectionNumber ?? 2)
@php($showSectionNumbers = $showSectionNumbers ?? true)
@php($tableSectionIds = ['learning_structure' => 'weekly-monthly-yearly', 'weekly_schedule' => 'monday-to-sunday', 'schools_offered' => 'schools-5-3-2-1'])

@if(!empty($pitch['pillars']))
    <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="gyaan-navachar-samruddhi">
        @include('pitch.partials.section-head', [
            'number' => $showSectionNumbers ? $sectionNumber : null,
            'title' => $pitch['pillars_title'] ?? 'The Three Pillars of BNS',
            'icon' => 'fa-columns',
        ])
        @php($sectionNumber++)
        <div class="bns-pitch-detail__pillars">
            @foreach($pitch['pillars'] as $pillar)
                <article class="bns-pitch-detail__pillar-card">
                    <span class="bns-pitch-detail__pillar-icon" aria-hidden="true">{{ $pillar['icon'] ?? '' }}</span>
                    <h4>{{ $pillar['name'] ?? '' }}</h4>
                    <p>{!! bns_rich_text($pillar['meaning'] ?? '') !!}</p>
                </article>
            @endforeach
        </div>
    </div>
@endif

@if(!empty($pitch['five_sentences']))
    <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="bns-5-sentences">
        @include('pitch.partials.section-head', [
            'number' => $showSectionNumbers ? $sectionNumber : null,
            'title' => $pitch['five_sentences_title'] ?? 'BNS in 5 Simple Sentences',
            'icon' => 'fa-lightbulb',
        ])
        @php($sectionNumber++)
        <div class="bns-pitch-detail__sentence-grid">
            @foreach($pitch['five_sentences'] as $sentence)
                <article class="bns-pitch-detail__sentence-card">
                    <div class="bns-pitch-detail__card-head">
                        <span class="bns-pitch-detail__card-num">{{ $loop->iteration }}</span>
                        <h4>{{ $sentence['title'] ?? '' }}</h4>
                    </div>
                    <div class="bns-pitch-detail__card-body">
                        @if(!empty($sentence['text']))
                            <p class="bns-pitch-detail__card-text">{!! bns_rich_text($sentence['text']) !!}</p>
                        @endif
                        @if(!empty($sentence['bullets']))
                            @include('pitch.partials.star-points', ['items' => $sentence['bullets']])
                        @endif
                        @if(!empty($sentence['note']))
                            <p class="bns-pitch-detail__note">
                                <i class="fas fa-star" aria-hidden="true"></i>
                                {!! bns_rich_text($sentence['note']) !!}
                            </p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif

@foreach(['learning_structure', 'weekly_schedule', 'schools_offered'] as $tableKey)
    @php($table = $pitch[$tableKey] ?? null)
    @if(!empty($table['rows']))
        <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="{{ $tableSectionIds[$tableKey] ?? $tableKey }}">
            @include('pitch.partials.section-head', [
                'number' => $showSectionNumbers ? $sectionNumber : null,
                'title' => $table['title'] ?? '',
                'icon' => $tableKey === 'learning_structure' ? 'fa-chart-line' : ($tableKey === 'weekly_schedule' ? 'fa-calendar-alt' : 'fa-layer-group'),
            ])
            @php($sectionNumber++)

            @if($tableKey === 'schools_offered')
                <div class="bns-pitch-detail__program-grid">
                    @foreach($table['rows'] as $row)
                        <article class="bns-pitch-detail__program-card">
                            <span class="bns-pitch-detail__program-icon" aria-hidden="true"><i class="fas fa-star"></i></span>
                            <h4>{{ $row[0] ?? '' }}</h4>
                            <p>{!! bns_rich_text($row[1] ?? '') !!}</p>
                        </article>
                    @endforeach
                </div>
            @else
                <ul class="bns-pitch-detail__points bns-pitch-detail__points--grid">
                    @foreach($table['rows'] as $row)
                        <li class="bns-pitch-detail__point">
                            <span class="bns-pitch-detail__point-icon" aria-hidden="true">
                                <i class="fas fa-star"></i>
                            </span>
                            <span class="bns-pitch-detail__point-text">{!! bns_point_html(['lead' => ($row[0] ?? '').':', 'text' => $row[1] ?? '']) !!}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
@endforeach

@if(!empty($pitch['learning_journeys']))
    <div class="bns-pitch-detail__section wow fadeInUp" data-wow-duration="0.85s" id="year-wise-school-name">
        @include('pitch.partials.section-head', [
            'number' => $showSectionNumbers ? $sectionNumber : null,
            'title' => $pitch['learning_journey_title'] ?? 'Learning Journey',
            'icon' => 'fa-route',
        ])
        @php($sectionNumber++)
        <div class="bns-pitch-detail__journey-grid">
            @foreach($pitch['learning_journeys'] as $journey)
                <article class="bns-pitch-detail__journey-card">
                    <div class="bns-pitch-detail__card-head">
                        <span class="bns-pitch-detail__card-num"><i class="fas fa-star" aria-hidden="true"></i></span>
                        <h4><span aria-hidden="true">{{ $journey['icon'] ?? '' }}</span> {{ $journey['title'] ?? '' }}</h4>
                    </div>
                    <div class="bns-pitch-detail__card-body">
                        @if(($journey['type'] ?? '') === 'years')
                            <ul class="bns-pitch-detail__points">
                                @foreach(($journey['rows'] ?? []) as $row)
                                    <li class="bns-pitch-detail__point">
                                        <span class="bns-pitch-detail__point-icon" aria-hidden="true">
                                            <i class="fas fa-star"></i>
                                        </span>
                                        <span class="bns-pitch-detail__point-text">{!! bns_point_html(['lead' => $row[0] ?? '', 'text' => $row[1] ?? '']) !!}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            @if(!empty($journey['duration']))
                                <p class="bns-pitch-detail__duration">
                                    <i class="fas fa-star" aria-hidden="true"></i>
                                    {!! bns_rich_text($journey['duration']) !!}
                                </p>
                            @endif
                            @if(!empty($journey['focus_title']))
                                <p class="bns-pitch-detail__focus-title">{!! bns_rich_text($journey['focus_title']) !!}</p>
                            @endif
                            @if(!empty($journey['bullets']))
                                @include('pitch.partials.star-points', ['items' => $journey['bullets']])
                            @endif
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
@endif
