@php($eligibility = $eligibility ?? ($memberPitch['eligibility'] ?? config('business_coach_pitch.eligibility', [])))
@php($sectionNumber = $sectionNumber ?? 9)
@php($wrapperClass = $wrapperClass ?? 'bns-pitch-detail')
@php($showSectionNumbers = $showSectionNumbers ?? true)
@php($sectionId = $sectionId ?? 'eligibility')
@if(!empty($eligibility['rows']))
    <div class="{{ $wrapperClass }}__section wow fadeInUp" data-wow-duration="0.85s" id="{{ $sectionId }}">
        @include('pitch.partials.section-head', [
            'number' => $showSectionNumbers ? $sectionNumber : null,
            'title' => $eligibility['title'] ?? 'Eligibility',
            'icon' => 'fa-user-check',
        ])
        <div class="{{ $wrapperClass }}__eligibility-grid{{ count($eligibility['rows']) === 5 ? ' '.$wrapperClass.'__eligibility-grid--five' : '' }}">
            @foreach($eligibility['rows'] as $row)
                <article class="{{ $wrapperClass }}__eligibility-card">
                    <span class="{{ $wrapperClass }}__eligibility-icon" aria-hidden="true"><i class="fas fa-star"></i></span>
                    <h4>{{ $row[0] ?? '' }}</h4>
                    <p>{!! bns_rich_text($row[1] ?? '') !!}</p>
                </article>
            @endforeach
        </div>
    </div>
@endif
