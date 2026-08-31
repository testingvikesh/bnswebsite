@if(!empty($venuePartner['member']))
<div class="bns-team-section bns-team-section--venue-partner">
    <div class="bns-team-section__head">
        <span class="bns-team-section__label">Collaboration</span>
        <h2 class="bns-team-section__title">{{ $venuePartner['title'] ?? 'Venue Partner' }}</h2>
        @if(!empty($venuePartner['subtitle']))
            <p class="bns-team-section__desc">{!! bns_rich_text($venuePartner['subtitle']) !!}</p>
        @endif
    </div>

    @if(!empty($venuePartner['venue']['photo']))
        <figure class="bns-venue-partner-photo">
            <img
                src="{{ bns_vasset($venuePartner['venue']['photo']) }}"
                alt="{{ $venuePartner['venue']['photo_alt'] ?? ($venuePartner['venue']['location'] ?? 'Venue Partner') }}"
                class="bns-venue-partner-photo__img"
                loading="lazy"
                decoding="async"
            >
        </figure>
    @endif

    @if(!empty($venuePartner['venue']['address_lines']) || !empty($venuePartner['venue']['location']))
        <div class="bns-venue-partner-info bns-venue-partner-info--full">
            <article class="bns-venue-partner-info__card bns-venue-partner-info__card--venue">
                <span class="bns-venue-partner-info__label">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    {{ $venuePartner['venue']['title'] ?? 'Venue Partner (Address)' }}
                </span>
                @if(!empty($venuePartner['venue']['address_lines']))
                    <address class="bns-venue-partner-info__address">
                        @foreach($venuePartner['venue']['address_lines'] as $line)
                            <span>{{ $line }}</span>
                        @endforeach
                    </address>
                @elseif(!empty($venuePartner['venue']['location']))
                    <p class="bns-venue-partner-info__location">{{ $venuePartner['venue']['location'] }}</p>
                @endif
            </article>
        </div>
    @endif

    @if(!empty($venuePartner['supported_by']['items']))
        <div class="bns-team-section__head bns-team-section__head--supported">
            <span class="bns-team-section__label">{{ $venuePartner['supported_by']['section_label'] ?? 'Support' }}</span>
            <h2 class="bns-team-section__title">{{ $venuePartner['supported_by']['title'] ?? 'Supported By' }}</h2>
            @if(!empty($venuePartner['supported_by']['subtitle']))
                <p class="bns-team-section__desc">{!! bns_rich_text($venuePartner['supported_by']['subtitle']) !!}</p>
            @endif
        </div>

        <div class="bns-venue-partner-info bns-venue-partner-info--full">
            <article class="bns-venue-partner-info__card bns-venue-partner-info__card--supported">
                <ul class="bns-venue-partner-info__list list-unstyled">
                    @foreach($venuePartner['supported_by']['items'] as $item)
                        <li>{!! bns_rich_text($item) !!}</li>
                    @endforeach
                </ul>
            </article>
        </div>
    @endif

    @include('about.partials.venue-partner-card', ['partner' => $venuePartner['member']])
</div>
@endif
