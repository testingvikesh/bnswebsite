@php
    $name = $partner['name'] ?? '';
    $designation = $partner['designation'] ?? '';
    $photoUrl = ! empty($partner['photo']) ? bns_vasset($partner['photo']) : null;
    $profileData = $partner['profile_data'] ?? null;
@endphp

<article class="bns-leader-row bns-venue-partner-card wow fadeInUp" data-wow-duration="0.85s">
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
        <div class="bns-leader-row__head">
            <h3 class="bns-leader-row__name">{{ $name }}</h3>
            @if($designation !== '')
                <p class="bns-leader-row__designation">{{ $designation }}</p>
            @endif
        </div>

        @if(!empty($profileData))
            <div class="bns-leader-row__block bns-leader-row__block--profile">
                @include('about.partials.leader-profile-structured', ['profile' => $profileData])
            </div>
        @endif
    </div>
</article>
