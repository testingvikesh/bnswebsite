<div class="col-xl-4 col-lg-6 col-md-6">
    <article class="bns-advisory-card">
        <div class="bns-advisory-card__photo-wrap">
            @if($member->photo_url)
                <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}" class="bns-advisory-card__photo" loading="lazy" decoding="async">
            @else
                <div class="bns-advisory-card__photo bns-advisory-card__photo--placeholder" aria-hidden="true">
                    <i class="fas fa-user-tie"></i>
                </div>
            @endif
            @if($member->linkedin_url)
                <a href="{{ $member->linkedin_url }}" class="bns-advisory-card__linkedin" target="_blank" rel="noopener noreferrer" title="LinkedIn profile">
                    <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                </a>
            @endif
        </div>
        <div class="bns-advisory-card__body">
            <h3 class="bns-advisory-card__name">{{ $member->full_name }}</h3>
            <p class="bns-advisory-card__designation">{{ $member->designation }}</p>
            @if($member->organization)
                <p class="bns-advisory-card__org">
                    <i class="fas fa-building" aria-hidden="true"></i>
                    <span>{{ $member->organization }}</span>
                </p>
            @endif

            <div class="bns-advisory-card__block">
                <p class="bns-advisory-card__block-title">
                    <i class="fas fa-star" aria-hidden="true"></i> Area of Expertise
                </p>
                <ul class="bns-advisory-card__points list-unstyled">
                    @foreach($member->expertiseList() as $point)
                        <li><i class="fas fa-check-circle" aria-hidden="true"></i><span>{!! bns_rich_text($point) !!}</span></li>
                    @endforeach
                </ul>
            </div>

            <div class="bns-advisory-card__block">
                <p class="bns-advisory-card__block-title">
                    <i class="fas fa-align-left" aria-hidden="true"></i> Profile
                </p>
                <p class="bns-advisory-card__profile">{!! bns_rich_text($member->profile) !!}</p>
            </div>
        </div>
    </article>
</div>
