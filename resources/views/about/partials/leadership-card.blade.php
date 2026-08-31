@php
    $isModel = $member instanceof \App\Models\TeamMember;
    $name = $isModel ? $member->full_name : ($member['name'] ?? '');
    $designation = $isModel ? $member->designation : ($member['designation'] ?? '');
    $linkedin = $isModel ? $member->linkedin_url : ($member['linkedin'] ?? null);
    $email = $isModel ? $member->email : ($member['email'] ?? null);
    $profileHtml = $isModel ? trim((string) ($member->profile ?? '')) : '';
    $profileData = $isModel ? null : ($member['profile_data'] ?? null);
    $expertise = array_values(array_filter($isModel ? (array) ($member->expertise ?? []) : (array) ($member['expertise'] ?? [])));
    $photoUrl = $isModel
        ? $member->photo_url
        : (! empty($member['photo']) ? bns_vasset($member['photo']) : null);
    $orderNumber = str_pad((string) (($index ?? 0) + 1), 2, '0', STR_PAD_LEFT);
    $sortOrder = $isModel ? (int) $member->sort_order : (int) ($member['sort_order'] ?? (($index ?? 0) + 1));

    if ($profileHtml === '' && empty($profileData)) {
        if (str_contains($name, 'Mehul Rupani')) {
            $profileData = \App\Support\TeamMemberProfiles::drMehulRupani();
        } elseif ($sortOrder === 2 || str_contains($designation, 'Chief Executive Officer') || str_contains($designation, '(CEO)')) {
            $profileData = \App\Support\TeamMemberProfiles::chiefExecutiveOfficer();
        } elseif ($sortOrder === 3 || str_contains($designation, 'Director – Business Navachar School') || str_contains($designation, 'Director - Business Navachar School')) {
            $profileData = \App\Support\TeamMemberProfiles::directorBns();
        } elseif ($sortOrder === 4 || str_contains($designation, 'Digital & Technology')) {
            $profileData = \App\Support\TeamMemberProfiles::directorDigitalBns();
        } elseif ($sortOrder === 5 || str_contains($designation, 'Social Media Marketing')) {
            $profileData = \App\Support\TeamMemberProfiles::headSocialMediaBns();
        } elseif ($sortOrder === 6 || str_contains($designation, 'Head of Marketing')) {
            $profileData = \App\Support\TeamMemberProfiles::headMarketingBns();
        } elseif ($sortOrder === 7 || str_contains($designation, 'Marketing Manager')) {
            $profileData = \App\Support\TeamMemberProfiles::marketingManagerBns();
        }
    }

    $hasProfile = $profileHtml !== '' || ! empty($profileData);
@endphp

<article class="bns-leader-row wow fadeInUp" data-wow-duration="0.85s">
    <div class="bns-leader-row__photo-wrap">
        @if($photoUrl)
            <img src="{{ $photoUrl }}" alt="{{ $name }}" class="bns-leader-row__photo" loading="lazy" decoding="async">
        @else
            <div class="bns-leader-row__photo bns-leader-row__photo--placeholder" aria-hidden="true">
                <i class="fas fa-user-tie"></i>
            </div>
        @endif
    </div>

    <div class="bns-leader-row__content">
        <span class="bns-leader-row__order">{{ $orderNumber }}</span>

        <div class="bns-leader-row__head">
            <h3 class="bns-leader-row__name">{{ $name }}</h3>
            @if($designation !== '')
                <p class="bns-leader-row__designation">{{ $designation }}</p>
            @endif
        </div>

        @if($hasProfile)
            <div class="bns-leader-row__block bns-leader-row__block--profile">
                @if($profileHtml !== '')
                    <div class="bns-leader-row__profile">{!! $profileHtml !!}</div>
                @else
                    @include('about.partials.leader-profile-structured', ['profile' => $profileData])
                @endif
            </div>
        @elseif(!empty($expertise))
            <div class="bns-leader-row__block">
                <span class="bns-leader-row__label"><i class="fas fa-star" aria-hidden="true"></i> Area of Expertise</span>
                <ul class="bns-leader-row__points list-unstyled">
                    @foreach($expertise as $skill)
                        <li class="bns-leader-row__point">{!! bns_rich_text($skill) !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if((!empty($linkedin) || !empty($email)) && $sortOrder !== 2)
            <div class="bns-leader-row__links">
                @if(!empty($linkedin))
                    <a href="{{ $linkedin }}" class="bns-leader-row__link" target="_blank" rel="noopener noreferrer">
                        <i class="fab fa-linkedin-in" aria-hidden="true"></i> LinkedIn
                    </a>
                @endif
                @if(!empty($email))
                    <a href="mailto:{{ $email }}" class="bns-leader-row__link">
                        <i class="fas fa-envelope" aria-hidden="true"></i> {{ $email }}
                    </a>
                @endif
            </div>
        @endif
    </div>
</article>
