<article class="bns-faculty-profile">
    <div class="bns-faculty-profile__inner">
        <div class="row g-0">
            <div class="col-lg-4">
                <div class="bns-faculty-profile__aside">
                    <div class="bns-faculty-profile__photo-wrap">
                        @if($member->photo_url)
                            <img src="{{ $member->photo_url }}" alt="{{ $member->display_name }}" class="bns-faculty-profile__photo" loading="lazy" decoding="async">
                        @else
                            <div class="bns-faculty-profile__photo bns-faculty-profile__photo--placeholder" aria-hidden="true">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                        @endif
                    </div>
                    <div class="bns-faculty-profile__identity">
                        <p class="bns-faculty-profile__label">Faculty Profile</p>
                        <h3 class="bns-faculty-profile__name">{{ $member->display_name }}</h3>
                        <p class="bns-faculty-profile__designation">{{ $member->designation }}</p>
                        @if($member->recognition)
                            <p class="bns-faculty-profile__recognition">
                                <i class="fas fa-award" aria-hidden="true"></i>
                                <span>{!! bns_rich_text($member->recognition) !!}</span>
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="bns-faculty-profile__main">
                    @if($member->professional_experience || $member->sessions_conducted || $member->learners_mentored || $member->faculty_since)
                        <div class="bns-faculty-profile__stats">
                            @if($member->professional_experience)
                                <div class="bns-faculty-profile__stat">
                                    <span class="bns-faculty-profile__stat-value">{{ $member->professional_experience }}</span>
                                    <span class="bns-faculty-profile__stat-label">Professional Experience</span>
                                </div>
                            @endif
                            @if($member->sessions_conducted)
                                <div class="bns-faculty-profile__stat">
                                    <span class="bns-faculty-profile__stat-value">{{ $member->sessions_conducted }}</span>
                                    <span class="bns-faculty-profile__stat-label">Sessions Conducted</span>
                                </div>
                            @endif
                            @if($member->learners_mentored)
                                <div class="bns-faculty-profile__stat">
                                    <span class="bns-faculty-profile__stat-value">{{ $member->learners_mentored }}</span>
                                    <span class="bns-faculty-profile__stat-label">Learners Mentored</span>
                                </div>
                            @endif
                            @if($member->faculty_since)
                                <div class="bns-faculty-profile__stat">
                                    <span class="bns-faculty-profile__stat-value">{{ $member->faculty_since }}</span>
                                    <span class="bns-faculty-profile__stat-label">Faculty Since</span>
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            @if($member->expertiseList())
                                <div class="bns-faculty-profile__block">
                                    <h4 class="bns-faculty-profile__block-title"><i class="fas fa-star" aria-hidden="true"></i> Expertise</h4>
                                    <ul class="bns-faculty-profile__points list-unstyled">
                                        @foreach($member->expertiseList() as $point)
                                            <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{!! bns_rich_text($point) !!}</span></li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($member->industry)
                                <div class="bns-faculty-profile__block">
                                    <h4 class="bns-faculty-profile__block-title"><i class="fas fa-industry" aria-hidden="true"></i> Industry</h4>
                                    <p class="bns-faculty-profile__text">{{ $member->industry }}</p>
                                </div>
                            @endif

                            @if($member->qualification)
                                <div class="bns-faculty-profile__block">
                                    <h4 class="bns-faculty-profile__block-title"><i class="fas fa-graduation-cap" aria-hidden="true"></i> Qualification</h4>
                                    <p class="bns-faculty-profile__text">{{ $member->qualification }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($member->specializationList())
                                <div class="bns-faculty-profile__block">
                                    <h4 class="bns-faculty-profile__block-title"><i class="fas fa-briefcase" aria-hidden="true"></i> Specialization</h4>
                                    <div class="bns-faculty-profile__tags">
                                        @foreach($member->specializationList() as $tag)
                                            <span class="bns-faculty-profile__tag">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($member->languagesList())
                                <div class="bns-faculty-profile__block">
                                    <h4 class="bns-faculty-profile__block-title"><i class="fas fa-language" aria-hidden="true"></i> Languages</h4>
                                    <div class="bns-faculty-profile__tags bns-faculty-profile__tags--lang">
                                        @foreach($member->languagesList() as $lang)
                                            <span class="bns-faculty-profile__tag">{{ $lang }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($member->about)
                                <div class="bns-faculty-profile__block">
                                    <h4 class="bns-faculty-profile__block-title"><i class="fas fa-align-left" aria-hidden="true"></i> About</h4>
                                    <p class="bns-faculty-profile__about">{!! bns_rich_text($member->about) !!}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
