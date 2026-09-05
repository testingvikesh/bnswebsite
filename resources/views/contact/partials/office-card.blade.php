@php
    $mapsUrl = $page->mapsUrl();
    $tel = fn (?string $phone) => 'tel:+'.ltrim((string) preg_replace('/\D+/', '', (string) $phone), '0');
    $wa = fn (?string $phone) => 'https://wa.me/'.preg_replace('/\D+/', '', (string) $phone);
@endphp
<div class="bns-contact-card bns-contact-card--office">
    <span class="bns-contact-card__label">{{ $page->office_title }}</span>
    <h3 class="bns-contact-card__title">{{ $page->office_brand }}</h3>
    @if($page->office_tagline)
        <p class="bns-contact-card__tagline">{{ $page->office_tagline }}</p>
    @endif

    <div class="bns-contact-info-block">
        <h4><i class="fas fa-map-marker-alt"></i> {{ $page->office_head_label }}</h4>
        <a class="bns-contact-address-link" href="{{ $mapsUrl }}" target="_blank" rel="noopener noreferrer">
            @if($page->address_line)<p>{{ $page->address_line }}</p>@endif
            <ul class="list-unstyled bns-contact-address">
                @if($page->city)<li><strong>City:</strong> {{ $page->city }}</li>@endif
                @if($page->state)<li><strong>State:</strong> {{ $page->state }}</li>@endif
                @if($page->pin_code)<li><strong>PIN Code:</strong> {{ $page->pin_code }}</li>@endif
            </ul>
            <span class="bns-contact-map-open"><i class="fas fa-map-marked-alt"></i> Open in Google Maps</span>
        </a>
    </div>

    <div class="bns-contact-info-block">
        <h4><i class="fas fa-phone-alt"></i> Contact Information</h4>
        <ul class="list-unstyled bns-contact-lines">
            @if($page->phone_helpline)
                <li><span>Admission Helpline</span><a href="{{ $tel($page->phone_helpline) }}">{{ $page->phone_helpline }}</a></li>
            @endif
            @if($page->phone_whatsapp)
                <li><span>WhatsApp</span><a href="{{ $wa($page->phone_whatsapp) }}" target="_blank" rel="noopener">{{ $page->phone_whatsapp }}</a></li>
            @endif
            @if($page->phone_office)
                <li><span>Office Number</span><a href="{{ $tel($page->phone_office) }}">{{ $page->phone_office }}</a></li>
            @endif
            @if($page->email_admissions)
                <li><span>Email</span><a href="mailto:{{ $page->email_admissions }}">{{ $page->email_admissions }}</a></li>
            @endif
            @if($page->email_general)
                <li><span>General Enquiry</span><a href="mailto:{{ $page->email_general }}">{{ $page->email_general }}</a></li>
            @endif
            @if($page->website)
                <li><span>Website</span><a href="{{ $page->websiteUrl() }}" target="_blank" rel="noopener">{{ $page->website }}</a></li>
            @endif
        </ul>
    </div>

    @if(!empty($page->office_hours))
    <div class="bns-contact-info-block">
        <h4><i class="far fa-clock"></i> Office Hours</h4>
        <ul class="list-unstyled bns-contact-hours">
            @foreach($page->office_hours as $hour)
                <li>{{ $hour }}</li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
