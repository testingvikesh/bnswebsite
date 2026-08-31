@php
    $boxMap = collect($program['boxes'] ?? [])->keyBy('key');
    $sectionHead = function (string $key, string $fallback) use ($boxMap) {
        $box = $boxMap->get($key, []);

        return [
            'label' => $box['label'] ?? $fallback,
            'icon' => $box['icon'] ?? 'fas fa-circle',
        ];
    };
@endphp

@php($details = $journey['steps']['details'] ?? [])
@php($head = $sectionHead('details', 'Details'))
<section class="bns-program-section" id="program-details">
    <header class="bns-program-section__head">
        <span class="bns-program-section__icon"><i class="{{ $head['icon'] }}" aria-hidden="true"></i></span>
        <h4>{{ $head['label'] }}</h4>
    </header>
    <article class="bns-audience-step">
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
</section>

@php($eligibility = $journey['steps']['eligibility'] ?? [])
@php($head = $sectionHead('eligibility', 'Eligibility'))
<section class="bns-program-section" id="program-eligibility">
    <header class="bns-program-section__head">
        <span class="bns-program-section__icon"><i class="{{ $head['icon'] }}" aria-hidden="true"></i></span>
        <h4>{{ $head['label'] }}</h4>
    </header>
    <article class="bns-audience-step">
        @if(!empty($program['eligibility_content']))
            @include('programs.audience.partials.eligibility-content', ['program' => $program])
        @else
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
        @endif
    </article>
</section>

@php($session = $journey['steps']['session'] ?? [])
@php($head = $sectionHead('session', 'Session Information'))
<section class="bns-program-section" id="program-session">
    <header class="bns-program-section__head">
        <span class="bns-program-section__icon"><i class="{{ $head['icon'] }}" aria-hidden="true"></i></span>
        <h4>{{ $head['label'] }}</h4>
    </header>
    <article class="bns-audience-step">
        <p class="bns-audience-step__text">{!! bns_rich_text($session['intro'] ?? '') !!}</p>
        <ul class="bns-audience-step__list list-unstyled">
            @foreach($session['items'] ?? [] as $item)
                <li><i class="fas fa-calendar-check"></i> {!! bns_rich_text($item) !!}</li>
            @endforeach
        </ul>
    </article>
</section>

@php($syllabus = $journey['steps']['syllabus'] ?? [])
@php($head = $sectionHead('syllabus', 'Syllabus'))
<section class="bns-program-section" id="program-syllabus">
    <header class="bns-program-section__head">
        <span class="bns-program-section__icon"><i class="{{ $head['icon'] }}" aria-hidden="true"></i></span>
        <h4>{{ $head['label'] }}</h4>
    </header>
    <article class="bns-audience-step">
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
</section>

@php($introduction = $journey['steps']['introduction'] ?? [])
@php($head = $sectionHead('introduction', 'Introduction Session'))
<section class="bns-program-section" id="program-introduction">
    <header class="bns-program-section__head">
        <span class="bns-program-section__icon"><i class="{{ $head['icon'] }}" aria-hidden="true"></i></span>
        <h4>{{ $head['label'] }}</h4>
    </header>
    <article class="bns-audience-step">
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
            'contactFormConfig' => $contactFormConfig ?? config('contact.form', []),
            'formId' => 'bnsProgramIntroSessionForm',
        ])
    </article>
</section>

@php($admission = $journey['steps']['admission'] ?? [])
@php($head = $sectionHead('admission', 'Admission Form'))
<section class="bns-program-section" id="program-admission">
    <header class="bns-program-section__head">
        <span class="bns-program-section__icon"><i class="{{ $head['icon'] }}" aria-hidden="true"></i></span>
        <h4>{{ $head['label'] }}</h4>
    </header>
    <article class="bns-audience-step">
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
</section>
