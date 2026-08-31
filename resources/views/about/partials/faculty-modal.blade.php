@php
    $experiencePoints = $member->experience_points ?? [];
    if (! is_array($experiencePoints)) {
        $experiencePoints = [];
    }
    $achievementPoints = $member->achievement_points ?? [];
    if (! is_array($achievementPoints)) {
        $achievementPoints = [];
    }
    $coachPoints = $member->coach_points ?? [];
    if (! is_array($coachPoints)) {
        $coachPoints = [];
    }
    $taglines = $member->taglines ?? [];
    if (! is_array($taglines)) {
        $taglines = [];
    }
    $profileFacts = $member->profile_facts ?? [];
    if (! is_array($profileFacts)) {
        $profileFacts = [];
    }
    $expertiseSummary = $member->expertise_summary ?: null;
    $expertiseSectionTitle = $member->expertise_section_title ?: 'Core Expertise';
    $experienceSectionTitle = $member->experience_section_title ?: 'Professional Experience';
    $achievementSectionTitle = $member->achievement_section_title ?: 'Major Achievement';
@endphp

<div class="modal fade bns-faculty-modal bns-vision-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">Business Navachar School (BNS)</span>
                    <h5 class="modal-title" id="{{ $modalId }}Label">Faculty Profile</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body bns-faculty-modal__body">
                <div class="bns-faculty-modal__hero">
                    <div class="bns-faculty-modal__photo-wrap">
                        @if($member->photo_url)
                            <img src="{{ $member->photo_url }}" alt="{{ $member->display_name }}" class="bns-faculty-modal__photo">
                        @else
                            <div class="bns-faculty-modal__photo bns-faculty-modal__photo--placeholder" aria-hidden="true">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                        @endif
                    </div>
                    <div class="bns-faculty-modal__identity">
                        <p class="bns-faculty-modal__kicker">Visiting Expert Faculty</p>
                        <h3 class="bns-faculty-modal__name">{{ $member->display_name }}</h3>
                        <p class="bns-faculty-modal__role">{{ $member->designation }}</p>
                        @if($member->secondary_role)
                            <p class="bns-faculty-modal__role bns-faculty-modal__role--secondary">{{ $member->secondary_role }}</p>
                        @endif
                        @if($member->recognition)
                            <p class="bns-faculty-modal__badge">
                                <i class="fas fa-bullseye" aria-hidden="true"></i>
                                <span>{!! bns_rich_text($member->recognition) !!}</span>
                            </p>
                        @endif
                        <div class="bns-faculty-modal__stats">
                            @if($member->professional_experience)
                                <div class="bns-faculty-modal__stat">
                                    <strong>{{ $member->professional_experience }}</strong>
                                    <span>Experience</span>
                                </div>
                            @endif
                            @if($member->digital_experience)
                                <div class="bns-faculty-modal__stat">
                                    <strong>{{ $member->digital_experience }}</strong>
                                    <span>{{ $member->digital_experience_label ?: 'Digital Marketing' }}</span>
                                </div>
                            @elseif($member->industry_exposure)
                                <div class="bns-faculty-modal__stat">
                                    <strong>{{ $member->industry_exposure }}</strong>
                                    <span>Industry Exposure</span>
                                </div>
                            @elseif($member->current_role)
                                <div class="bns-faculty-modal__stat">
                                    <strong>{{ $member->current_role }}</strong>
                                    <span>Current Role</span>
                                </div>
                            @elseif($expertiseSummary || ($member->expertiseList()[0] ?? null))
                                <div class="bns-faculty-modal__stat">
                                    <strong>{{ $expertiseSummary ?: $member->expertiseList()[0] }}</strong>
                                    <span>Expertise</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @if($profileFacts !== [] || $member->professional_experience || $member->current_role || $member->expertise || $member->industry_exposure || $member->experience_type || $member->specialization)
                    <section class="bns-faculty-modal__section">
                        <h4>Professional Profile</h4>
                        <ul class="bns-faculty-modal__facts">
                            @if($profileFacts !== [])
                                @foreach($profileFacts as $fact)
                                    @if(! empty($fact['label']) && ! empty($fact['value']))
                                        <li><span>{{ $fact['label'] }}</span> {{ $fact['value'] }}</li>
                                    @endif
                                @endforeach
                            @else
                                @if($member->professional_experience)
                                    <li><span>Experience</span> {{ $member->professional_experience }}</li>
                                @endif
                                @if($member->industry_exposure)
                                    <li><span>Industry Exposure</span> {{ $member->industry_exposure }}</li>
                                @endif
                                @if($member->experience_type)
                                    <li><span>Experience Type</span> {{ $member->experience_type }}</li>
                                @endif
                                @if($expertiseSummary || ($member->expertiseList()[0] ?? null))
                                    <li><span>Expertise</span> {{ $expertiseSummary ?: $member->expertiseList()[0] }}</li>
                                @endif
                                @if($member->specialization)
                                    <li><span>Specialisation</span> {{ $member->specialization }}</li>
                                @endif
                                @if($member->current_role || $member->designation)
                                    <li><span>Current Role</span> {{ $member->current_role ?: $member->designation }}</li>
                                @endif
                            @endif
                        </ul>
                    </section>
                @endif

                @if($member->expertiseList())
                    <section class="bns-faculty-modal__section">
                        <h4>{{ $expertiseSectionTitle }}</h4>
                        <div class="bns-faculty-modal__chips">
                            @foreach($member->expertiseList() as $skill)
                                <span>{{ $skill }}</span>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($experiencePoints !== [])
                    <section class="bns-faculty-modal__section">
                        <h4>{{ $experienceSectionTitle }}</h4>
                        <ul class="bns-faculty-modal__list">
                            @foreach($experiencePoints as $point)
                                <li><i class="fas fa-check-circle" aria-hidden="true"></i> {!! bns_rich_text($point) !!}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if($achievementPoints !== [])
                    <section class="bns-faculty-modal__section bns-faculty-modal__section--achievement">
                        <h4>{{ $achievementSectionTitle }}</h4>
                        <ul class="bns-faculty-modal__list">
                            @foreach($achievementPoints as $point)
                                <li><i class="fas fa-trophy" aria-hidden="true"></i> {!! bns_rich_text($point) !!}</li>
                            @endforeach
                        </ul>
                    </section>
                @endif

                @if($member->current_focus)
                    <section class="bns-faculty-modal__section bns-faculty-modal__section--focus">
                        <h4>Current Focus</h4>
                        <p>{!! bns_rich_text($member->current_focus) !!}</p>
                    </section>
                @endif

                @if($member->coach_intro || $member->about || $coachPoints !== [])
                    <section class="bns-faculty-modal__section bns-faculty-modal__section--coach">
                        <h4>As a BNS Business Coach</h4>
                        @if($member->about)
                            <p>{!! bns_rich_text($member->about) !!}</p>
                        @endif
                        @if($member->coach_intro)
                            <p>{!! bns_rich_text($member->coach_intro) !!}</p>
                        @endif
                        @if($coachPoints !== [])
                            <ul class="bns-faculty-modal__list">
                                @foreach($coachPoints as $point)
                                    <li><i class="fas fa-rocket" aria-hidden="true"></i> {!! bns_rich_text($point) !!}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if($member->coach_outcome)
                            <p class="bns-faculty-modal__outcome">{{ $member->coach_outcome }}</p>
                        @endif
                    </section>
                @endif

                @if($taglines !== [])
                    <section class="bns-faculty-modal__section bns-faculty-modal__section--bns">
                        <h4>Business Navachar School (BNS)</h4>
                        <div class="bns-faculty-modal__taglines">
                            @foreach($taglines as $line)
                                <span>{!! bns_rich_text($line) !!}</span>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="bns-faculty-modal__close" data-bs-dismiss="modal">Close</button>
                <button
                    type="button"
                    class="thm-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#bnsIntroSessionModal"
                >
                    Book Your Spot Now <span class="fas fa-arrow-right"></span>
                </button>
            </div>
        </div>
    </div>
</div>
