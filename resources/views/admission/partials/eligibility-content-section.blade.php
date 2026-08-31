@if(!empty($data['intro_heading']))
    <h3 class="bns-eligibility-content__heading">{{ $data['intro_heading'] }}</h3>
@endif

@if(!empty($data['intro']))
    <p class="bns-eligibility-content__intro">{!! bns_rich_text($data['intro']) !!}</p>
@endif

@if(!empty($data['intro_secondary']))
    <p class="bns-eligibility-content__intro">{!! bns_rich_text($data['intro_secondary']) !!}</p>
@endif

@if(!empty($data['programs']))
<div class="bns-eligibility-programs">
    @foreach($data['programs'] as $program)
    <article class="bns-eligibility-program">
        <header class="bns-eligibility-program__header">
            @if(!empty($program['icon']))
                <span class="bns-eligibility-program__icon" aria-hidden="true">{{ $program['icon'] }}</span>
            @endif
            <h3 class="bns-eligibility-program__title">
                @if(!empty($program['number'])){{ $program['number'] }}. @endif{{ $program['title'] ?? '' }}
            </h3>
        </header>
        <div class="bns-eligibility-program__body">
            <h4 class="bns-eligibility-program__label">{{ $program['candidates_label'] ?? 'Eligible Candidates' }}</h4>
            <ul class="bns-admission-list list-unstyled">
                @foreach($program['candidates'] ?? [] as $candidate)
                    <li><i class="fas fa-check"></i> {!! bns_rich_text($candidate) !!}</li>
                @endforeach
            </ul>
            @if(!empty($program['age_group']))
            <div class="bns-eligibility-age">
                <strong>Age Group</strong>
                <span>{{ $program['age_group'] }}</span>
            </div>
            @endif
        </div>
    </article>
    @endforeach
</div>
@endif

@if(!empty($data['general_eligibility']))
<section class="bns-eligibility-section">
    <h3>{{ $data['general_eligibility']['title'] ?? 'General Eligibility' }}</h3>
    @if(!empty($data['general_eligibility']['intro']))
        <p class="bns-eligibility-section__intro">{!! bns_rich_text($data['general_eligibility']['intro']) !!}</p>
    @endif
    <ul class="bns-admission-list bns-admission-list--checks list-unstyled">
        @foreach($data['general_eligibility']['items'] ?? [] as $item)
            <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
        @endforeach
    </ul>
</section>
@endif

@if(!empty($data['admission_requirements']))
<section class="bns-eligibility-section">
    <h3>{{ $data['admission_requirements']['title'] ?? 'Admission Requirements' }}</h3>
    <ul class="bns-admission-list list-unstyled">
        @foreach($data['admission_requirements']['items'] ?? [] as $item)
            <li><i class="fas fa-check"></i> {!! bns_rich_text($item) !!}</li>
        @endforeach
    </ul>
</section>
@endif

@if(!empty($data['no_prior_experience']))
<section class="bns-eligibility-section bns-eligibility-section--highlight">
    <h3>{{ $data['no_prior_experience']['title'] ?? 'No Prior Business Experience Required' }}</h3>
    @if(!empty($data['no_prior_experience']['text']))
        <p class="bns-eligibility-section__intro">{!! bns_rich_text($data['no_prior_experience']['text']) !!}</p>
    @endif
    @if(!empty($data['no_prior_experience']['items']))
        <ul class="bns-admission-list list-unstyled">
            @foreach($data['no_prior_experience']['items'] as $item)
                <li><i class="fas fa-check"></i> {!! bns_rich_text($item) !!}</li>
            @endforeach
        </ul>
    @endif
    @if(!empty($data['no_prior_experience']['outro']))
        <p class="bns-eligibility-section__outro">{!! bns_rich_text($data['no_prior_experience']['outro']) !!}</p>
    @endif
</section>
@endif

@if(!empty($data['ideal_for']))
<section class="bns-eligibility-section">
    <h3>{{ $data['ideal_for']['title'] ?? 'Ideal For' }}</h3>
    <ul class="bns-admission-list bns-admission-list--checks list-unstyled">
        @foreach($data['ideal_for']['items'] ?? [] as $item)
            <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
        @endforeach
    </ul>
</section>
@endif

@if(!empty($data['why_choose']))
<section class="bns-eligibility-section bns-eligibility-section--highlight">
    <h3>{{ $data['why_choose']['title'] ?? 'Why Choose BNS?' }}</h3>
    @if(!empty($data['why_choose']['intro']))
        <p class="bns-eligibility-section__intro">{!! bns_rich_text($data['why_choose']['intro']) !!}</p>
    @endif
    <ul class="bns-eligibility-why list-unstyled">
        @foreach($data['why_choose']['items'] ?? [] as $item)
            <li>{!! bns_rich_text($item) !!}</li>
        @endforeach
    </ul>
    @if(!empty($data['why_choose']['outro']))
        <p class="bns-eligibility-section__outro">{!! bns_rich_text($data['why_choose']['outro']) !!}</p>
    @endif
</section>
@endif

@if(!empty($data['closing']))
<div class="bns-eligibility-closing">
    <h3>{{ $data['closing']['title'] ?? '' }}</h3>
    @if(!empty($data['closing']['years']))
        <ul class="bns-eligibility-journey list-unstyled">
            @foreach($data['closing']['years'] as $yearRow)
                <li>
                    <div class="bns-eligibility-journey__content">
                        <strong>{{ $yearRow['year'] ?? '' }}</strong>
                        <span>{{ $yearRow['label'] ?? '' }}</span>
                        @if(!empty($yearRow['description']))
                            <p class="bns-eligibility-journey__desc">{!! bns_rich_text($yearRow['description']) !!}</p>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
    @if(!empty($data['closing']['subtitle']))
        <p class="bns-eligibility-closing__subtitle">{!! bns_rich_text($data['closing']['subtitle']) !!}</p>
    @endif
    @if(!empty($data['closing']['tagline']))
        <p class="bns-eligibility-closing__tagline">{!! bns_rich_text($data['closing']['tagline']) !!}</p>
    @endif
    @if(!empty($data['closing']['hindi']))
        <p class="bns-eligibility-closing__hindi">{!! bns_rich_text($data['closing']['hindi']) !!}</p>
    @endif
    @if(empty($hide_eligibility_cta))
        <button type="button" class="bns-admission-hero-cta" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
            <i class="fas fa-rocket"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
        </button>
    @endif
</div>
@endif

@if(!empty($data['program_structure']))
<section class="bns-eligibility-section">
    <h3>{{ $data['program_structure']['title'] ?? 'Program Structure' }}</h3>
    <ul class="bns-admission-list list-unstyled">
        @foreach($data['program_structure']['items'] ?? [] as $item)
            <li><i class="fas fa-check"></i> {!! bns_rich_text($item) !!}</li>
        @endforeach
    </ul>
</section>
@endif

@if(!empty($data['journey_begin']))
<div class="bns-eligibility-closing bns-eligibility-closing--begin">
    <h3>{{ $data['journey_begin']['title'] ?? 'Your Journey Begins Here' }}</h3>
    @if(!empty($data['journey_begin']['tagline']))
        <p class="bns-eligibility-closing__tagline">{!! bns_rich_text($data['journey_begin']['tagline']) !!}</p>
    @endif
</div>
@endif

@if(!empty($data['taglines']))
<div class="bns-eligibility-taglines">
    @foreach($data['taglines'] as $tagline)
        <p class="bns-eligibility-tagline">{!! bns_rich_text($tagline) !!}</p>
    @endforeach
</div>
@endif
