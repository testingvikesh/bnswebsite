@php
    $credentialsHeading = trim((string) ($profile['credentials_heading'] ?? ''));
    $credentials = array_values(array_filter((array) ($profile['credentials'] ?? [])));
    $credentialsPosition = $profile['credentials_position'] ?? 'before';
    $tagline = trim((string) ($profile['tagline'] ?? ''));
    $positions = array_values(array_filter((array) ($profile['positions'] ?? [])));
    $strengthsHeading = trim((string) ($profile['strengths_heading'] ?? ''));
    $strengths = array_values(array_filter((array) ($profile['strengths'] ?? [])));
    $vision = trim((string) ($profile['vision'] ?? ''));
    $mission = trim((string) ($profile['mission'] ?? ''));
@endphp

<div class="bns-leader-profile">
    @if($credentials !== [] && $credentialsPosition !== 'after')
        @include('about.partials.leader-profile-credentials', [
            'credentialsHeading' => $credentialsHeading,
            'credentials' => $credentials,
        ])
    @endif

    @if($tagline !== '')
        <p class="bns-leader-profile__tagline">{!! bns_rich_text($tagline) !!}</p>
    @endif

    @if($positions !== [])
        <div class="bns-leader-profile__positions">
            @foreach($positions as $position)
                <article class="bns-leader-profile__position">
                    @if(!empty($position['title']))
                        <h4 class="bns-leader-profile__position-title">{{ $position['title'] }}</h4>
                    @endif
                    @if(!empty($position['organization']))
                        <p class="bns-leader-profile__position-org">{!! nl2br(e($position['organization'])) !!}</p>
                    @endif
                    @if(!empty($position['note']))
                        <p class="bns-leader-profile__position-note">{!! bns_rich_text($position['note']) !!}</p>
                    @endif
                </article>
            @endforeach
        </div>
    @endif

    @if($strengths !== [])
        <div class="bns-leader-profile__section">
            @if($strengthsHeading !== '')
                <span class="bns-leader-profile__section-label">{{ $strengthsHeading }}</span>
            @endif
            <ul class="bns-leader-profile__strengths list-unstyled">
                @foreach($strengths as $strength)
                    <li class="bns-leader-profile__strength">
                        <i class="fas fa-check-circle" aria-hidden="true"></i>
                        <span>{!! bns_rich_text($strength) !!}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($credentials !== [] && $credentialsPosition === 'after')
        @include('about.partials.leader-profile-credentials', [
            'credentialsHeading' => $credentialsHeading,
            'credentials' => $credentials,
        ])
    @endif

    @if($vision !== '')
        <div class="bns-leader-profile__vision">
            <span class="bns-leader-profile__vision-label"><i class="fas fa-lightbulb" aria-hidden="true"></i> Vision</span>
            <p class="bns-leader-profile__vision-text">{!! bns_rich_text($vision) !!}</p>
        </div>
    @endif

    @if($mission !== '')
        <div class="bns-leader-profile__mission">
            <span class="bns-leader-profile__mission-label"><i class="fas fa-bullseye" aria-hidden="true"></i> Mission</span>
            <p class="bns-leader-profile__mission-text">{!! bns_rich_text($mission) !!}</p>
        </div>
    @endif
</div>
