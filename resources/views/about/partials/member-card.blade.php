@props(['member', 'type' => 'leadership'])

@php
    $isModel = $member instanceof \App\Models\TeamMember;
    $name = $isModel ? $member->full_name : ($member['name'] ?? '');
    $designation = $isModel ? $member->designation : ($member['designation'] ?? '');
    $featured = $isModel ? $member->is_featured : ($member['featured'] ?? false);
    $linkedin = $isModel ? $member->linkedin_url : ($member['linkedin'] ?? null);
    $photoUrl = $isModel
        ? $member->photo_url
        : (! empty($member['photo']) ? bns_vasset($member['photo']) : null);
@endphp

<div class="col-xl-4 col-lg-4 col-md-6">
    <article class="bns-team-card bns-team-card--leadership {{ $featured ? 'bns-team-card--featured' : '' }}">
        <div class="bns-team-card__photo-wrap">
            @if($photoUrl)
                <img src="{{ $photoUrl }}" alt="{{ $name }}" class="bns-team-card__photo" loading="lazy" decoding="async">
            @else
                <div class="bns-team-card__photo bns-team-card__photo--placeholder" aria-hidden="true">
                    <i class="fas fa-user-tie"></i>
                </div>
            @endif
            <div class="bns-team-card__photo-shade" aria-hidden="true"></div>
            @if(!empty($linkedin))
                <a href="{{ $linkedin }}" class="bns-team-card__linkedin" target="_blank" rel="noopener noreferrer" title="LinkedIn profile">
                    <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                </a>
            @endif
        </div>

        <div class="bns-team-card__body bns-team-card__body--leadership">
            <h3 class="bns-team-card__name">{{ $name }}</h3>
            @if($designation !== '')
                <p class="bns-team-card__designation">{{ $designation }}</p>
                <span class="bns-team-card__accent" aria-hidden="true"></span>
            @endif
        </div>
    </article>
</div>
