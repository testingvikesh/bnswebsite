<div class="xs-sidebar-group info-group info-sidebar">
    <div class="xs-overlay xs-bg-black"></div>
    <div class="xs-sidebar-widget">
        <div class="sidebar-widget-container">
            <div class="widget-heading"><a href="#" class="close-side-widget">X</a></div>
            <div class="sidebar-textwidget">
                <div class="sidebar-info-contents">
                    <div class="content-inner">
                        <div class="logo"><a href="{{ url('/') }}"><img src="{{ $siteLogoUrl }}" alt="{{ $siteLogoAlt }}" style="height: 80px;" /></a></div>
                        <div class="content-box"><h4>About Us</h4><div class="inner-text"><p>Business Navachar School is a business learning ecosystem where individuals learn how to think, build, and grow in business.</p></div></div>
                        <div class="form-inner"><h4>Contact us</h4>
                            <form action="#" method="POST" class="contact-form-validated">@csrf
                                <div class="form-group"><input type="text" name="name" placeholder="Name" required></div>
                                <div class="form-group"><input type="email" name="email" placeholder="Email" required></div>
                                <div class="form-group"><textarea name="message" placeholder="Message..." required></textarea></div>
                                <div class="form-group message-btn"><button class="thm-btn" type="submit">Submit Now <span class="fas fa-arrow-right"></span></button></div>
                                <div class="result"></div>
                            </form>
                        </div>
                        <div class="sidebar-contact-info"><h4>Contact Info</h4><ul class="list-unstyled">
                            @if(!empty($siteHeader['address']))
                            <li><span class="far fa-map-marker-alt"></span> <a href="{{ $siteHeader['maps_url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ $siteHeader['address'] }}</a></li>
                            @endif
                            @foreach($siteHeader['phones'] ?? [] as $phoneItem)
                            <li><span class="far fa-phone"></span> <a href="{{ $phoneItem['href'] }}">{{ $phoneItem['label'] }}</a></li>
                            @endforeach
                            @if(!empty($siteHeader['email']))
                            <li><span class="fal fa-envelope"></span> <a href="{{ $siteHeader['email_href'] }}">{{ $siteHeader['email'] }}</a></li>
                            @endif
                        </ul></div>
                        @if(!empty($siteHeader['social_links']))
                        <div class="thm-social-link1"><ul class="social-box list-unstyled">
                            @foreach($siteHeader['social_links'] as $social)
                            <li><a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}"><i class="{{ $social['icon'] }}"></i></a></li>
                            @endforeach
                        </ul></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
