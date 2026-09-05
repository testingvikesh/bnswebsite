<footer class="site-footer">
    <div class="site-footer__map" style="background-image: url({{ bns_vasset('assets/images/shapes/footer-one-map.png') }});"></div>
    <div class="site-footer__top">
        <div class="container">
            <div class="site-footer__top-inner">
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="100ms">
                        <div class="footer-widget__about">
                            <div class="footer-widget__about-logo">
                                <a href="{{ url('/') }}"><img src="{{ $siteLogoUrl }}" alt="{{ $siteLogoAlt }}" style="height: 80px;" loading="lazy" decoding="async"></a>
                            </div>
                            <p class="footer-widget__about-text">{{ config('site.footer.about_text', 'Business Navachar School (BNS) is run by BNS E-TECH PRIVATE LIMITED — committed to quality, practical business education for every learner.') }}</p>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="200ms">
                        <div class="footer-widget__links">
                            <h4 class="footer-widget__title">Useful Links</h4>
                            <ul class="footer-widget__links-list list-unstyled">
                                <li><a href="{{ url('/about') }}">About Us</a></li>
                                <li><a href="{{ route('about.team') }}">Meet Our Team</a></li>
                                <li><a href="{{ route('about.sponsors') }}">Meet Our Sponsors</a></li>
                                <li><a href="{{ route('about.faculty') }}">Visiting Expert Faculty</a></li>
                                <li><a href="{{ route('testimonials') }}">Testimonials</a></li>
                                <li><a href="{{ route('syllabus') }}">Syllabus</a></li>
                                <li><a href="{{ route('events.index') }}">Events</a></li>
                                <li><a href="{{ route('gallery.index') }}">Gallery</a></li>
                                <li><a href="{{ route('message.index') }}">BNS Message</a></li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="400ms">
                        <div class="footer-widget__contact-info">
                            <h4 class="footer-widget__title">Contact Us</h4>
                            <div class="footer-widget__contact-info-box">
                                <ul class="footer-widget__contact-info-list list-unstyled">
                                    @if(!empty($siteHeader['address']))
                                    <li><div class="footer-widget__contact-info-list-shape-1"></div><div class="icon"><span class="fas fa-map-marker-alt"></span></div><p><a href="{{ $siteHeader['maps_url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">{{ $siteHeader['address'] }}</a></p></li>
                                    @endif
                                    @if(!empty($siteHeader['email']))
                                    <li><div class="footer-widget__contact-info-list-shape-1"></div><div class="icon"><span class="fas fa-envelope"></span></div><p><a href="{{ $siteHeader['email_href'] }}">{{ $siteHeader['email'] }}</a></p></li>
                                    @endif
                                    @foreach($siteHeader['phones'] ?? [] as $phoneItem)
                                    <li><div class="footer-widget__contact-info-list-shape-1"></div><div class="icon"><span class="fas fa-phone"></span></div><p><a href="{{ $phoneItem['href'] }}">{{ $phoneItem['label'] }}</a></p></li>
                                    @endforeach
                                </ul>
                                @if(!empty($siteHeader['social_links']))
                                <div class="footer-widget__social-box">
                                    @foreach($siteHeader['social_links'] as $social)
                                        <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}"><span class="{{ $social['icon'] }}"></span></a>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="300ms">
                        <div class="site-footer__newsletter" id="newsletter-subscribe">
                            <div class="footer-widget__title-box"><h3 class="footer-widget__title">Subscribe Us</h3></div>
                            <p class="site-footer__text">Subscribe for updates and events!</p>

                            @if(session('newsletter_success'))
                                <div class="bns-footer-newsletter-alert bns-footer-newsletter-alert--success" role="status">
                                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                                    <span>{{ session('newsletter_success') }}</span>
                                </div>
                            @endif

                            @if($errors->has('email') || $errors->has('agree'))
                                <div class="bns-footer-newsletter-alert bns-footer-newsletter-alert--error" role="alert">
                                    <i class="fas fa-exclamation-circle" aria-hidden="true"></i>
                                    <span>{{ $errors->first('email') ?: $errors->first('agree') }}</span>
                                </div>
                            @endif

                            <form class="site-footer__newsletter-form" action="{{ route('newsletter.subscribe') }}" method="post">
                                @csrf
                                <div class="site-footer__newsletter-input">
                                    <input
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        placeholder="Email address..."
                                        required
                                        autocomplete="email"
                                        @error('email') aria-invalid="true" @enderror
                                    >
                                    <div class="site-footer__newsletter-input-icon"><span class="fas fa-envelope"></span></div>
                                </div>
                                <div class="checked-box">
                                    <input type="checkbox" name="agree" id="footer-newsletter-agree" value="1" @checked(old('email') !== null ? (bool) old('agree') : true)>
                                    <label for="footer-newsletter-agree"><span></span>I agree to <a href="{{ route('legal.show', 'terms-and-conditions') }}">terms &amp; conditions</a>.</label>
                                </div>
                                <div class="site-footer__newsletter-btn-box">
                                    <button type="submit" class="site-footer__newsletter-btn" aria-label="Subscribe">
                                        <span class="fas fa-paper-plane"></span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="site-footer__bottom">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="site-footer__bottom-inner bns-footer-bottom--center">
                        <div class="site-footer__copyright">
                            <p class="site-footer__copyright-text">
                                Copyright &copy; {{ date('Y') }}
                                <a href="{{ url('/') }}">{{ config('site.footer.copyright_entity', 'BNS E-TECH PRIVATE LIMITED') }}</a>.
                                Running {{ config('site.footer.copyright_brand', 'Business Navachar School (BNS)') }}.
                                All Rights Reserved.
                            </p>
                        </div>
                        <div class="site-footer__bottom-menu-box">
                            <ul class="list-unstyled site-footer__bottom-menu">
                                <li><a href="{{ route('legal.show', 'privacy-policy') }}">Privacy Policy</a></li>
                                <li><a href="{{ route('legal.show', 'terms-and-conditions') }}">Terms &amp; Conditions</a></li>
                                <li><a href="{{ route('legal.show', 'refund-policy') }}">Refund Policy</a></li>
                                <li><a href="{{ route('legal.index') }}">Legal &amp; Compliance</a></li>
                                <li><a href="{{ route('contact') }}">Support</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
