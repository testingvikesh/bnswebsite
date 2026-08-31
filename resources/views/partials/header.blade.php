<header class="main-header">
    <div class="main-menu__top">
        <div class="main-menu__top-inner">
            <ul class="list-unstyled main-menu__contact-list">
                @if(!empty($siteHeader['email']))
                <li>
                    <div class="icon"><i class="fal fa-envelope"></i></div>
                    <div class="text"><p><a href="{{ $siteHeader['email_href'] }}">{{ $siteHeader['email'] }}</a></p></div>
                </li>
                @endif
                @foreach($siteHeader['phones'] ?? [] as $phoneItem)
                <li>
                    <div class="icon"><i class="far fa-phone"></i></div>
                    <div class="text"><p><a href="{{ $phoneItem['href'] }}">{{ $phoneItem['label'] }}</a></p></div>
                </li>
                @endforeach
            </ul>
            @if(!empty($siteHeader['welcome_text']))
                <p class="main-menu__top-welcome-text">{{ $siteHeader['welcome_text'] }}</p>
            @endif
            @if(!empty($siteHeader['social_links']))
            <div class="main-menu__top-right">
                @if(!empty($siteHeader['social_title']))
                    <p class="main-menu__social-title">{{ $siteHeader['social_title'] }}</p>
                @endif
                <div class="main-menu__social">
                    @foreach($siteHeader['social_links'] as $social)
                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}">
                            <i class="{{ $social['icon'] }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    <nav class="main-menu">
        <div class="main-menu__wrapper">
            <div class="main-menu__wrapper-inner">
                <div class="main-menu__left">
                    <div class="main-menu__logo">
                        <a href="{{ url('/') }}"><img src="{{ $siteLogoUrl }}" alt="{{ $siteLogoAlt }}" fetchpriority="high" decoding="async"></a>
                    </div>
                </div>
                <div class="main-menu__main-menu-box">
                    <ul class="main-menu__list">
                        <li class="{{ request()->is('/') ? 'current' : '' }}"><a href="{{ url('/') }}">Home</a></li>
                        <li class="dropdown {{ request()->routeIs('pitch*') ? 'current' : '' }}">
                            <a href="{{ route('pitch') }}">Pitch</a>
                            <ul class="shadow-box">
                                @foreach(config('pitch_menu.items', []) as $item)
                                    <li class="{{ request()->routeIs($item['route']) ? 'current' : '' }}">
                                        <a href="{{ route($item['route']) }}">{{ $item['label'] }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="{{ request()->routeIs('about', 'about.*', 'statistics.*', 'testimonials') ? 'current' : '' }}"><a href="{{ url('/about') }}">About Us</a></li>
                        <li class="{{ request()->routeIs('about.team') ? 'current' : '' }}"><a href="{{ route('about.team') }}">Meet Our Team</a></li>
                        <li class="{{ request()->routeIs('about.sponsors') ? 'current' : '' }}"><a href="{{ route('about.sponsors') }}">Meet Our Sponsors</a></li>
                        <li class="dropdown {{ request()->routeIs('syllabus') ? 'current' : '' }}">
                            <a href="{{ route('syllabus') }}">Syllabus</a>
                            <ul class="shadow-box">
                                @foreach(config('programs.featured_page.programs', []) as $programItem)
                                    @php($programSlug = $programItem['slug'] ?? '')
                                    @if($programSlug !== '' && !empty(config('audience_programs.'.$programSlug.'.program_structure')))
                                        <li>
                                            <a href="{{ route('syllabus') }}#syllabus-{{ $programSlug }}">{{ $programItem['title'] ?? $programSlug }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </li>
                        <li class="{{ request()->routeIs('events.*') ? 'current' : '' }}"><a href="{{ route('events.index') }}">Events</a></li>
                        <li class="{{ request()->routeIs('gallery.*') ? 'current' : '' }}"><a href="{{ route('gallery.index') }}">Gallery</a></li>
                        <li class="{{ request()->routeIs('contact', 'whatsapp.support', 'social.follow') ? 'current' : '' }}"><a href="{{ route('contact') }}">Contact Us</a></li>
                    </ul>
                </div>
                <div class="main-menu__right">
                    <div class="main-menu__search-cart-box">
                        <div class="main-menu__search-box"><a href="#" class="main-menu__search searcher-toggler-box icon-search-interface-symbol"></a></div>
                    </div>
                </div>
                <a href="#" class="mobile-nav__toggler bns-mobile-menu-toggle" aria-label="Open menu"><i class="fa fa-bars"></i></a>
            </div>
        </div>
    </nav>
</header>
<div class="stricky-header stricked-menu main-menu"><div class="sticky-header__content"></div></div>
