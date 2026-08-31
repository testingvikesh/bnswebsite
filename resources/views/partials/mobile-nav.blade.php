<div class="mobile-nav__wrapper">
    <div class="mobile-nav__overlay mobile-nav__toggler"></div>
    <div class="mobile-nav__content">
        <span class="mobile-nav__close mobile-nav__toggler"><i class="fa fa-times"></i></span>
        <div class="mobile-nav__container"></div>
        <ul class="mobile-nav__contact list-unstyled">
            @if(!empty($siteHeader['email']))
            <li><i class="fa fa-envelope"></i> <a href="{{ $siteHeader['email_href'] }}">{{ $siteHeader['email'] }}</a></li>
            @endif
            @foreach($siteHeader['phones'] ?? [] as $phoneItem)
            <li><i class="fas fa-phone"></i> <a href="{{ $phoneItem['href'] }}">{{ $phoneItem['label'] }}</a></li>
            @endforeach
        </ul>
        <div class="mobile-nav__top">
            <div class="mobile-nav__social">
                @foreach($siteHeader['social_links'] ?? [] as $social)
                    <a href="{{ $social['url'] }}" class="{{ $social['icon'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}"></a>
                @endforeach
            </div>
        </div>
    </div>
</div>
