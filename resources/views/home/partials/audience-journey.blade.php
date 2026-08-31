@php
    $stepOrder = ['details', 'eligibility', 'session', 'syllabus', 'introduction', 'admission'];
@endphp

<div class="bns-audience-journey" id="bnsAudienceJourney" hidden>
    @foreach($audienceJourneys as $journeyId => $journey)
        <div class="bns-audience-journey__panel" data-journey-panel="{{ $journeyId }}" hidden>
            <div class="bns-audience-journey__head">
                <button type="button" class="bns-audience-journey__back" data-journey-back aria-label="Back to programs">
                    <i class="fas fa-arrow-left" aria-hidden="true"></i> Back
                </button>
                <div class="bns-audience-journey__selected">
                    <span class="bns-audience-journey__selected-icon" aria-hidden="true">{{ $journey['icon'] }}</span>
                    <div>
                        <p class="bns-audience-journey__selected-label">Selected Program</p>
                        <h3 class="bns-audience-journey__selected-title">{{ $journey['label'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="bns-audience-journey__steps-scroll">
                <nav class="bns-audience-journey__steps" aria-label="Program steps for {{ $journey['label'] }}">
                    @foreach($stepOrder as $stepIndex => $stepKey)
                        @php($step = $journey['steps'][$stepKey] ?? [])
                        <button
                            type="button"
                            class="bns-audience-journey__step{{ $stepIndex === 0 ? ' is-active' : '' }}"
                            data-journey-step="{{ $stepKey }}"
                            data-journey-id="{{ $journeyId }}"
                        >
                            <span class="bns-audience-journey__step-num">{{ $stepIndex + 1 }}</span>
                            <span class="bns-audience-journey__step-label">{{ $step['title'] ?? ucfirst($stepKey) }}</span>
                        </button>
                    @endforeach
                </nav>
            </div>

            <div class="bns-audience-journey__body">
                @php($details = $journey['steps']['details'] ?? [])
                <article class="bns-audience-step is-active" data-journey-content="details" data-journey-id="{{ $journeyId }}">
                    <h4 class="bns-audience-step__title">{{ $details['program_title'] ?? $journey['label'] }}</h4>
                    @if(!empty($details['audience']))
                        <p class="bns-audience-step__meta"><i class="fas fa-users"></i> {!! bns_rich_text($details['audience']) !!}</p>
                    @endif
                    @if(!empty($details['summary']))
                        <p class="bns-audience-step__text">{!! bns_rich_text($details['summary']) !!}</p>
                    @endif
                    @if(!empty($details['duration']))
                        <p class="bns-audience-step__badge"><i class="fas fa-clock"></i> {!! bns_rich_text($details['duration']) !!}</p>
                    @endif
                    @if(!empty($details['goal']))
                        <p class="bns-audience-step__goal"><strong>Goal:</strong> {!! bns_rich_text($details['goal']) !!}</p>
                    @endif
                    @if(!empty($details['learn_preview']))
                        <h5 class="bns-audience-step__sub">{{ $details['learn_heading'] ?? 'Highlights' }}</h5>
                        <ul class="bns-audience-step__list list-unstyled">
                            @foreach($details['learn_preview'] as $item)
                                <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
                            @endforeach
                        </ul>
                    @endif
                </article>

                @php($eligibility = $journey['steps']['eligibility'] ?? [])
                <article class="bns-audience-step" data-journey-content="eligibility" data-journey-id="{{ $journeyId }}" hidden>
                    <h4 class="bns-audience-step__title">{{ $eligibility['program_title'] ?? 'Eligibility' }}</h4>
                    <p class="bns-audience-step__sub">{{ $eligibility['candidates_label'] ?? 'Eligible Candidates' }}</p>
                    <ul class="bns-audience-step__list list-unstyled">
                        @foreach($eligibility['candidates'] ?? [] as $candidate)
                            <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($candidate) !!}</li>
                        @endforeach
                    </ul>
                    @if(!empty($eligibility['age_group']))
                        <p class="bns-audience-step__badge"><i class="fas fa-birthday-cake"></i> Age Group: {{ $eligibility['age_group'] }}</p>
                    @endif
                    @if(!empty($eligibility['general']))
                        <h5 class="bns-audience-step__sub">General Requirements</h5>
                        <ul class="bns-audience-step__list list-unstyled">
                            @foreach($eligibility['general'] as $item)
                                <li><i class="fas fa-check"></i> {!! bns_rich_text($item) !!}</li>
                            @endforeach
                        </ul>
                    @endif
                </article>

                @php($session = $journey['steps']['session'] ?? [])
                <article class="bns-audience-step" data-journey-content="session" data-journey-id="{{ $journeyId }}" hidden>
                    <h4 class="bns-audience-step__title">Session Information</h4>
                    <p class="bns-audience-step__text">{!! bns_rich_text($session['intro'] ?? '') !!}</p>
                    <ul class="bns-audience-step__list list-unstyled">
                        @foreach($session['items'] ?? [] as $item)
                            <li><i class="fas fa-calendar-check"></i> {!! bns_rich_text($item) !!}</li>
                        @endforeach
                    </ul>
                </article>

                @php($syllabus = $journey['steps']['syllabus'] ?? [])
                <article class="bns-audience-step" data-journey-content="syllabus" data-journey-id="{{ $journeyId }}" hidden>
                    <h4 class="bns-audience-step__title">{{ $syllabus['heading'] ?? 'Syllabus' }}</h4>
                    <ul class="bns-audience-step__list bns-audience-step__list--grid list-unstyled">
                        @foreach($syllabus['items'] ?? [] as $item)
                            <li><i class="fas fa-book-open"></i> {!! bns_rich_text($item) !!}</li>
                        @endforeach
                    </ul>
                    @if(!empty($syllabus['goal']))
                        <p class="bns-audience-step__goal"><strong>Program Goal:</strong> {!! bns_rich_text($syllabus['goal']) !!}</p>
                    @endif
                </article>

                @php($introduction = $journey['steps']['introduction'] ?? [])
                <article class="bns-audience-step" data-journey-content="introduction" data-journey-id="{{ $journeyId }}" hidden>
                    <h4 class="bns-audience-step__title">Introduction Session</h4>
                    <p class="bns-audience-step__text">{!! bns_rich_text($introduction['intro'] ?? '') !!}</p>
                    <ul class="bns-audience-step__list list-unstyled">
                        @foreach($introduction['items'] ?? [] as $item)
                            <li><i class="fas fa-chalkboard-teacher"></i> {!! bns_rich_text($item) !!}</li>
                        @endforeach
                    </ul>

                    @include('partials.introduction-session-form', [
                        'intro' => [
                            'program_label' => $journey['label'] ?? '',
                            'contact_program' => $journey['contact_program'] ?? '',
                            'contact_category' => $journey['contact_category'] ?? 'Other',
                        ],
                        'formId' => 'bnsHomeIntroSessionForm_'.$journeyId,
                    ])
                </article>

                @php($admission = $journey['steps']['admission'] ?? [])
                <article class="bns-audience-step" data-journey-content="admission" data-journey-id="{{ $journeyId }}" hidden>
                    <h4 class="bns-audience-step__title">Admission Form</h4>
                    <p class="bns-audience-step__text">{!! bns_rich_text($admission['intro'] ?? '') !!}</p>
                    <ul class="bns-audience-step__list list-unstyled">
                        <li><i class="fas fa-file-alt"></i> Fill your program-specific BNS admission form</li>
                        <li><i class="fas fa-upload"></i> Upload required documents</li>
                        <li><i class="fas fa-credit-card"></i> Pay registration fee online</li>
                        <li><i class="fas fa-check-circle"></i> Receive confirmation from the admission team</li>
                    </ul>
                    <div class="bns-audience-step__actions">
                        <button
                            type="button"
                            class="thm-btn"
                            data-bs-toggle="modal"
                            data-bs-target="#bnsIntroSessionModal"
                            data-register-program-id="{{ $journey['register_program_id'] ?? '' }}"
                            data-contact-program="{{ $journey['contact_program'] ?? '' }}"
                            data-contact-category="{{ $journey['contact_category'] ?? '' }}"
                            data-program-title="{{ $journey['label'] ?? '' }}"
                        >
                            Open Admission Form <span class="fas fa-arrow-right"></span>
                        </button>
                        <a href="{{ route('admissions.index') }}" class="bns-audience-btn bns-audience-btn--ghost">Admission Hub</a>
                    </div>
                </article>
            </div>

            <div class="bns-audience-journey__nav">
                <button type="button" class="bns-audience-journey__nav-btn" data-journey-prev disabled>
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <button type="button" class="bns-audience-journey__nav-btn bns-audience-journey__nav-btn--next" data-journey-next>
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    @endforeach
</div>
