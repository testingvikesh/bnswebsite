@php
    $message = data_get($item, 'message');
    $fullName = data_get($item, 'full_name');
    $designation = data_get($item, 'designation');
    $organization = data_get($item, 'organization');
    $location = data_get($item, 'location');
    $icon = data_get($item, 'icon');
    $photoUrl = $item instanceof \App\Models\Testimonial
        ? $item->photo_url
        : data_get($item, 'photo_url');
    $websiteUrl = data_get($item, 'website_url');
    if (! $websiteUrl && data_get($item, 'website')) {
        $website = trim((string) data_get($item, 'website'));
        $websiteUrl = preg_match('#^https?://#i', $website) ? $website : 'https://'.$website;
    }
    $websiteLabel = data_get($item, 'website_label');
    if (! $websiteLabel && $websiteUrl) {
        $websiteLabel = preg_replace('#^https?://#i', '', rtrim((string) data_get($item, 'website', $websiteUrl), '/'));
    }
    $mobileTel = data_get($item, 'mobile_tel');
    if (! $mobileTel && data_get($item, 'mobile')) {
        $digits = preg_replace('/\D+/', '', (string) data_get($item, 'mobile'));
        $mobileTel = $digits !== '' ? '+'.$digits : null;
    }
    $hasContact = $location || data_get($item, 'mobile') || data_get($item, 'email') || $websiteUrl;

    $accentThemes = ['sunset', 'ocean', 'forest', 'violet', 'amber'];
    $accentTheme = $accentThemes[($index ?? 0) % count($accentThemes)];

    $messagePoints = [];
    if ($message) {
        $clean = trim(preg_replace('/^["\'\x{201C}\x{2018}]+|["\'\x{201D}\x{2019}]+$/u', '', trim($message)));
        $parts = preg_split('/(?<=[.!?])\s+(?=[A-Z0-9"\'\x{201C}])/u', $clean, -1, PREG_SPLIT_NO_EMPTY);
        $messagePoints = array_values(array_filter(array_map('trim', $parts ?: [])));
    }
@endphp

<div class="col-xl-4 col-lg-4 col-md-6" @if(!empty($filterKey)) data-filter-key="{{ $filterKey }}" @endif>
    <article class="bns-testimonial-card bns-testimonial-card--{{ $accentTheme }}">
        <div class="bns-testimonial-card__header">
            <i class="fas fa-quote-right bns-testimonial-card__watermark" aria-hidden="true"></i>
            <div class="bns-testimonial-card__avatar">
                @if($photoUrl)
                    <img src="{{ $photoUrl }}" alt="{{ $fullName }}" class="bns-testimonial-card__avatar-img" loading="lazy" decoding="async">
                @elseif($icon)
                    <span class="bns-testimonial-card__avatar-emoji" aria-hidden="true">{{ $icon }}</span>
                @else
                    <i class="fas fa-user bns-testimonial-card__avatar-icon" aria-hidden="true"></i>
                @endif
            </div>
        </div>

        <div class="bns-testimonial-card__body">
            @if($fullName)
                <h3 class="bns-testimonial-card__name">{{ $fullName }}</h3>
            @endif

            @if($designation)
                <span class="bns-testimonial-card__badge">{{ $designation }}</span>
            @endif

            @if($organization)
                <p class="bns-testimonial-card__organization">
                    <i class="fas fa-building" aria-hidden="true"></i>
                    <span>{{ $organization }}</span>
                </p>
            @endif

            @if($location)
                <p class="bns-testimonial-card__location">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <span>{{ $location }}</span>
                </p>
            @endif

            @if(!empty($messagePoints))
                <div class="bns-testimonial-card__quote">
                    <i class="fas fa-quote-left bns-testimonial-card__quote-icon" aria-hidden="true"></i>
                    <ul class="bns-testimonial-card__points">
                        @foreach($messagePoints as $point)
                            <li>
                                <i class="fas fa-check-circle" aria-hidden="true"></i>
                                <span>{{ $point }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($hasContact)
                <div class="bns-testimonial-card__contact">
                    @if(data_get($item, 'mobile'))
                        @if($mobileTel)
                            <a href="tel:{{ $mobileTel }}" class="bns-testimonial-card__contact-link" title="Call">
                                <i class="fas fa-phone" aria-hidden="true"></i>
                            </a>
                        @else
                            <span class="bns-testimonial-card__contact-link" title="{{ data_get($item, 'mobile') }}">
                                <i class="fas fa-phone" aria-hidden="true"></i>
                            </span>
                        @endif
                    @endif
                    @if(data_get($item, 'email'))
                        <a href="mailto:{{ data_get($item, 'email') }}" class="bns-testimonial-card__contact-link" title="Email">
                            <i class="fas fa-envelope" aria-hidden="true"></i>
                        </a>
                    @endif
                    @if($websiteUrl)
                        <a href="{{ $websiteUrl }}" class="bns-testimonial-card__contact-link" target="_blank" rel="noopener noreferrer" title="Website">
                            <i class="fas fa-globe" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </article>
</div>
