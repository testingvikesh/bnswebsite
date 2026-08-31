<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token-url" content="{{ route('csrf-token') }}">
    <title>@yield('title', 'Home') || BNS S</title>
    <link rel="icon" type="image/png" href="{{ $siteFaviconUrl }}" />
    <meta name="description" content="BNS School - Education HTML5 Template" />
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat+Alternates:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=DM+Sans:ital,opsz,wght@0,9..40,100..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/animate.min.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/custom-animate.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/swiper.min.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/font-awesome-all.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/jquery.magnific-popup.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/odometer.min.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/owl.carousel.min.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/owl.theme.default.min.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/nice-select.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/jquery-ui.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/aos.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/responsive.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/bns-dark-headings.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/bns-rich-text.css') }}" />
    @if(!request()->routeIs('register') && !empty(config('home.sticky_cta.enabled')))
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/sticky-admission-bar.css') }}" />
    @endif
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/bns-modals.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/home-audience.css') }}" />
    <link rel="stylesheet" href="{{ bns_vasset('assets/css/page-header-bns.css') }}" />
    @stack('head')
    @stack('styles')
    <style>
        body[data-bns-open-intro-session="1"] .js-preloader,
        body[data-bns-open-quick-register="1"] .js-preloader {
            display: none !important;
            visibility: hidden !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        .site-footer {
            margin-top: 0;
        }
        .bns-footer-bottom--center {
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center;
            gap: 12px;
            width: 100%;
            padding: 24px 0 28px;
        }
        .bns-footer-bottom--center .site-footer__copyright,
        .bns-footer-bottom--center .site-footer__bottom-menu-box {
            width: 100%;
            max-width: 100%;
        }
        .bns-footer-bottom--center .site-footer__copyright-text {
            margin: 0;
            text-align: center;
            line-height: 1.65;
        }
        .bns-footer-bottom--center .site-footer__bottom-menu {
            justify-content: center;
            flex-direction: row !important;
            flex-wrap: wrap;
            gap: 8px 20px;
            margin: 0;
        }
        .bns-footer-bottom--center .site-footer__bottom-menu li + li {
            margin-left: 0;
            margin-top: 0;
        }
        .site-footer__top-inner {
            padding: 54px 0 50px;
        }
        .footer-widget__about-logo {
            position: relative;
            display: inline-block;
            background-color: white;
            border-radius: 5px;
        }
        .main-menu__logo { padding: 0; }
        .main-menu__logo img {
            height: 99px !important;
            width: auto;
        }
        .main-menu__wrapper {
            background: linear-gradient(90deg, #ffffff 20%, rgb(51, 61, 97) 38%, rgb(51, 61, 97) 100%);
        }
        .main-menu__wrapper-inner { background-color: transparent; }
        .main-menu__nav-sidebar-icon {
            display: none !important;
        }
        .bns-mobile-menu-toggle {
            display: none;
        }
        .main-menu .mobile-nav__toggler {
            font-size: 40px;
            line-height: 1;
        }
        /* —— Header: all device breakpoints —— */
        @media (max-width: 1199px) {
            .main-menu__wrapper-inner {
                display: flex;
                align-items: center;
                gap: 12px;
            }
            .main-menu__main-menu-box {
                flex: 1 1 auto;
            }
            .bns-mobile-menu-toggle {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-size: 40px;
                line-height: 1;
                margin-left: auto;
                flex-shrink: 0;
            }
            .bns-mobile-menu-toggle:hover {
                color: var(--eduvers-base, #ff5544);
            }
        }
        @media (max-width: 991px) {
            .main-menu__wrapper-inner {
                padding-left: 16px;
                padding-right: 16px;
                gap: 10px;
                height: 71px;
            }
        }
        @media (max-width: 767px) {
            .main-menu__right {
                display: none !important;
            }
            .main-menu__wrapper-inner {
                padding-left: 12px;
                padding-right: 12px;
            }
            .bns-mobile-menu-toggle {
                margin-left: auto;
            }
            .mobile-nav__content {
                padding: 24px 18px;
            }
            .mobile-nav__content .main-menu__list > li > a {
                font-size: 16px;
                padding: 12px 0;
                justify-content: space-between;
            }
        }
        @media (max-width: 600px) {
            .main-menu__logo img {
                height: 78px !important;
                width: auto;
            }
            .main-menu__wrapper {
                background: #fff;
                background-color: #fff;
            }
            .bns-mobile-menu-toggle {
                color: rgb(51, 61, 97);
            }
        }
        @media (max-width: 399px) {
            .main-menu__wrapper-inner {
                padding-left: 10px;
                padding-right: 10px;
            }
        }
        .mobile-nav__content {
            width: 100% !important;
            max-width: 100% !important;
            background-color: rgb(51, 61, 97) !important;
            padding: 30px 24px;
        }
        .mobile-nav__overlay {
            display: none;
        }
        .mobile-nav__close {
            color: #fff;
            font-size: 22px;
        }
        .mobile-nav__content .main-menu__list > li > a {
            justify-content: space-between;
            width: 100%;
            font-size: 18px;
            padding: 14px 0;
        }
        .mobile-nav__content .main-menu__list > li.dropdown > a::after {
            display: none !important;
        }
        .mobile-nav__content .main-menu__list > li > a > button {
            margin-left: auto;
            flex-shrink: 0;
        }
        .main-slider__content { padding-top: 226px; padding-bottom: 100px; }
        .main-slider__img img { height: 600px; }
        .main-slider__btn-box--bns .thm-btn { margin-right: 12px; margin-bottom: 12px; }
        .main-slider__support-line { margin-top: 1.25rem; font-style: italic; color: rgba(255,255,255,.92); font-size: 1.05rem; line-height: 1.5; }
        .feature-one__text--three-lines { line-height: 1.65; margin-bottom: 0; padding-bottom: 4px; }
        .about-one__points-box.about-one__points-box--full { flex-direction: column; align-items: stretch; gap: 0; }
        .about-one__points-box.about-one__points-box--full .about-one__points { width: 100% !important; max-width: 100% !important; flex: 0 0 100% !important; }
        .about-one__text-2.about-one__text-2--full-line { display: block; white-space: normal; }
        .about-one__btn-box { margin-top: 20px; }
        .about-one__tagline-programs { margin-bottom: 14px; }
        .about-one .about-one__programs-link { color: inherit; text-decoration: none; transition: color 0.2s ease; }
        .about-one .about-one__programs-link:hover { color: var(--eduvers-base); }
        .categories-one--programs .categories-one__content { text-align: center; }
        .categories-one--programs .categories-one__title { margin-top: 0; margin-bottom: 6px; font-size: 20px; line-height: 1.35; }
        .categories-one--programs .categories-one__audience { font-size: 15px; font-weight: 500; color: var(--eduvers-gray, #6d7070); margin: 0px; }
        .categories-one--programs .categories-one__desc { font-size: 15px; line-height: 1.65; color: var(--eduvers-gray, #727779); margin: 0 0 18px; }
        .categories-one--programs .categories-one__desc--lead { margin-top: 4px; }
        .learning-methods-bns { margin-top: 56px; padding-top: 48px; border-top: 1px solid rgba(var(--eduvers-black-rgb, 8, 7, 7), 0.08); text-align: center; }
        .learning-methods-bns__heading { font-size: 26px; font-weight: 700; color: var(--eduvers-black); margin: 0 0 28px; line-height: 1.3; }
        .learning-methods-bns__row { align-items: stretch; }
        .learning-methods-bns__btn { display: flex; align-items: center; justify-content: center; min-height: 64px; width: 100%; padding: 14px 18px; margin: 0; font-size: 16px; font-weight: 600; line-height: 1.35; text-align: center; color: var(--eduvers-white) !important; background-color: var(--eduvers-base); border-radius: var(--eduvers-bdr-radius, 8px); border: 2px solid var(--eduvers-base); text-decoration: none !important; box-shadow: 0 8px 24px rgba(var(--eduvers-base-rgb, 255, 85, 68), 0.35); transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease; }
        .learning-methods-bns__btn:hover { color: var(--eduvers-white) !important; background-color: var(--eduvers-black); border-color: var(--eduvers-black); transform: translateY(-4px); box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18); }
        @media (min-width: 992px) {
            .learning-methods-bns__btn { min-height: 72px; font-size: 17px; }
        }
        .bns-info-hub { position: relative; display: block; padding: 96px 0 100px; background: var(--bns-band-b, #eef2f7); z-index: 1; }
        .bns-info-hub__card { height: 100%; display: flex; flex-direction: column; background: var(--eduvers-white); border-radius: 16px; padding: 26px 22px 22px; box-shadow: 0 10px 36px rgba(15, 23, 42, 0.08); border: 1px solid rgba(15, 23, 42, 0.06); transition: box-shadow 0.3s ease, transform 0.25s ease; }
        .bns-info-hub__card:hover { box-shadow: 0 16px 44px rgba(15, 23, 42, 0.12); transform: translateY(-4px); }
        .bns-info-hub__label { font-size: 11px; font-weight: 700; letter-spacing: 0.14em; text-transform: uppercase; color: var(--eduvers-base); margin: 0 0 12px; line-height: 1.4; }
        .bns-info-hub__title { font-size: clamp(1.25rem, 2.3vw, 1.55rem); font-weight: 700; line-height: 1.3; color: var(--eduvers-black); margin: 0 0 20px; }
        .bns-info-hub__title span { color: var(--eduvers-base); }
        .bns-info-hub__list { margin: 0; padding: 0; flex: 1 1 auto; }
        .bns-info-hub__list li { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; font-size: 16px; line-height: 1.55; color: #363a40; }
        .bns-info-hub__list li:last-child { margin-bottom: 0; }
        .bns-info-hub__check { flex-shrink: 0; margin-top: 4px; font-size: 15px; color: var(--eduvers-base); }
        .bns-info-hub__lead { font-size: 15px; line-height: 1.68; color: #454950; margin: 0 0 14px; }
        .bns-info-hub__sub { font-size: 15px; font-weight: 700; color: var(--eduvers-black); margin: 0 0 10px; }
        .bns-info-hub__note { font-size: 15px; font-weight: 600; line-height: 1.55; color: #2a2e34; margin: 0 0 18px; padding: 14px 16px; background: rgba(var(--eduvers-base-rgb, 255, 85, 68), 0.1); border-radius: 10px; border: 1px solid rgba(var(--eduvers-base-rgb, 255, 85, 68), 0.22); }
        .bns-info-hub__card--vision .bns-info-hub__quote { position: relative; margin: 18px 0 0; padding: 18px 18px 36px; font-size: 16px; font-style: italic; font-weight: 500; line-height: 1.6; color: #2c3036; text-align: center; background: #f1f3f7; border: 1px solid rgba(15, 23, 42, 0.06); border-left: 4px solid var(--eduvers-base); border-radius: 0 12px 12px 0; }
        .bns-info-hub__card--vision .bns-info-hub__quote::after { content: ""; position: absolute; left: 50%; bottom: 14px; transform: translateX(-50%); width: 10px; height: 10px; border-radius: 50%; background: var(--eduvers-base); box-shadow: 0 0 0 4px rgba(var(--eduvers-base-rgb, 255, 85, 68), 0.2); }
        .bns-info-hub__list--fees li span:last-child { font-size: 17px; }
        .bns-info-hub__list--fees strong { font-weight: 700; color: var(--eduvers-black); }
        .bns-info-hub__actions { margin-top: auto; padding-top: 22px; }
        .bns-info-hub__card--vision .bns-info-hub__actions .thm-btn { background-color: var(--eduvers-base); color: var(--eduvers-white); }
        .bns-info-hub__card--vision .bns-info-hub__actions .thm-btn:hover { color: var(--eduvers-white); }
        .bns-info-hub__actions .thm-btn { width: 100%; justify-content: center; text-align: center; display: inline-flex; align-items: center; }
        .bns-info-hub__btn-secondary { background-color: var(--eduvers-black) !important; color: var(--eduvers-white) !important; }
        .bns-info-hub__btn-secondary:hover { color: var(--eduvers-black) !important; }
        .enterprise-plan--usp .enterprise-plan__points li + li { margin-top: 8px; }
        .live-class-bns__intro-list { margin: 8px 0 22px; }
        .live-class-bns__intro-list li { display: flex; align-items: flex-start; gap: 12px; }
        .live-class-bns__intro-list li + li { margin-top: 10px; }
        .live-class-bns__intro-list .icon { flex-shrink: 0; padding-top: 2px; }
        .live-class-bns__intro-list .icon span { font-size: 17px; color: var(--eduvers-base); }
        .live-class-bns__intro-list p { margin: 0; font-size: 17px; font-weight: 600; line-height: 1.45; color: var(--eduvers-black); }
        .live-class-bns__cta-row { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 28px; }
        .live-class-bns__cta-row .thm-btn { flex: 1; min-width: 160px; justify-content: center; text-align: center; }
        .live-class-bns__cta-row .thm-btn--partner { background-color: var(--eduvers-black) !important; color: var(--eduvers-white) !important; }
        .live-class-bns__cta-row .thm-btn--partner:hover { color: var(--eduvers-black) !important; }
        /* Home page: consistent vertical rhythm, less wasted space */
        .bns-home {
            --bns-band-a: #ffffff;
            --bns-band-b: #eef2f7;
            width: 100%;
            max-width: 100%;
            overflow-x: clip;
        }
        body.bns-page-home {
            overflow-x: hidden;
        }
        .bns-home .bns-audience-journey__steps-scroll {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            overscroll-behavior-x: contain;
        }
        .bns-home .bns-home-hero-banner,
        .bns-home .bns-home-hero-slider {
            width: 100%;
            max-width: 100%;
            margin-left: 0;
            margin-right: 0;
        }
        /* Alternate section backgrounds (hero unchanged) */
        .bns-home .about-one { background-color: var(--bns-band-a); }
        .bns-home .about-one__bg { opacity: 0.42; }
        .bns-home .categories-one--programs { background-color: var(--bns-band-b); }
        .bns-home .live-class { background-color: var(--bns-band-a); }
        .bns-home .enterprise-plan--usp { background-color: var(--bns-band-b); }
        .bns-home .process-one {
            background-color: var(--bns-band-a) !important;
        }
        .bns-home .process-one__bg-shape {
            opacity: 0.07;
        }
        .bns-home .cta-one { background-color: var(--bns-band-a); }
        .bns-home .main-slider__content { padding-top: 200px; padding-bottom: 80px; }
        /* Hero: left copy slides in from left, visual from right (each slide) */
        .bns-home .main-slider__content-row { --bns-hero-slide: 56px; margin-left: 0; margin-right: 0; }
        .bns-home .main-slider__hero-col--text,
        .bns-home .main-slider__hero-col--visual {
            will-change: opacity, transform;
        }
        .bns-home .main-slider .swiper-slide:not(.swiper-slide-active) .main-slider__hero-col--text,
        .bns-home .main-slider .swiper-slide:not(.swiper-slide-active) .main-slider__hero-col--visual {
            animation: none !important;
        }
        .bns-home .main-slider .swiper-slide:not(.swiper-slide-active) .main-slider__hero-col--text {
            opacity: 0;
            transform: translate3d(calc(-1 * var(--bns-hero-slide)), 0, 0);
        }
        .bns-home .main-slider .swiper-slide:not(.swiper-slide-active) .main-slider__hero-col--visual {
            opacity: 0;
            transform: translate3d(var(--bns-hero-slide), 0, 0);
        }
        @keyframes bns-hero-from-left {
            from { opacity: 0; transform: translate3d(calc(-1 * var(--bns-hero-slide)), 0, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        @keyframes bns-hero-from-right {
            from { opacity: 0; transform: translate3d(var(--bns-hero-slide), 0, 0); }
            to { opacity: 1; transform: translate3d(0, 0, 0); }
        }
        .bns-home .main-slider .swiper-slide-active .main-slider__hero-col--text {
            animation: bns-hero-from-left 0.95s cubic-bezier(0.22, 1, 0.36, 1) 0.15s both;
        }
        .bns-home .main-slider .swiper-slide-active .main-slider__hero-col--visual {
            animation: bns-hero-from-right 0.95s cubic-bezier(0.22, 1, 0.36, 1) 0.32s both;
        }
        .bns-home .main-slider__hero-col--visual {
            position: relative;
            display: flex;
            justify-content: flex-end;
            align-items: flex-end;
            min-height: 280px;
        }
        @media (min-width: 992px) {
            .bns-home .main-slider__hero-col--visual { min-height: 420px; }
        }
        .bns-home .main-slider__img-box {
            position: relative !important;
            right: auto !important;
            bottom: auto !important;
            margin-top: 1.5rem;
            max-width: 100%;
        }
        @media (min-width: 1200px) {
            .bns-home .main-slider__img-box { margin-top: 0; transform: translateX(12px); }
        }
        @media (max-width: 575px) {
            .bns-home .main-slider__content-row { --bns-hero-slide: 28px; }
        }
        .bns-home .feature-one,
        .bns-home .categories-one--programs,
        .bns-home .enterprise-plan,
        .bns-home .process-one,
        .bns-home .live-class {
            padding-top: 70px !important;
            padding-bottom: 70px !important;
        }
        .bns-home .about-one .about-one__right {
            padding: 88px 36px 88px !important;
        }
        @media (min-width: 1200px) {
            .bns-home .about-one .about-one__right { padding-left: 48px !important; padding-right: 48px !important; }
        }
        .bns-home .bns-info-hub {
            padding-top: 70px !important;
            padding-bottom: 70px !important;
            background-color: var(--bns-band-b) !important;
        }
        .bns-home .learning-methods-bns {
            margin-top: 36px !important;
            padding-top: 28px !important;
        }
        .bns-home .learning-methods-bns__heading { margin-bottom: 20px !important; }
        .bns-home .cta-one {
            padding-top: 56px !important;
            padding-bottom: 72px !important;
        }
        .bns-home .cta-one__inner {
            padding: 44px 32px !important;
        }
        @media (min-width: 992px) {
            .bns-home .cta-one__inner { padding: 48px 48px !important; }
        }
        .bns-home .section-title { margin-bottom: 36px !important; }
        .bns-home .section-title.text-center { margin-bottom: 44px !important; }
        .bns-home .feature-one__single { margin-bottom: 22px !important; }
        .bns-home .categories-one--programs .categories-one__single { margin-bottom: 22px !important; }
        .chat-icon--whatsapp {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        .chat-icon--whatsapp .chat-whatsapp-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            font-size: 28px;
            line-height: 1;
            color: #fff !important;
            border-radius: 8px;
            background: #25d366;
            z-index: 1;
            border: none;
            text-decoration: none !important;
        }
        .chat-icon--whatsapp .chat-whatsapp-btn--channel {
            flex-direction: column;
            height: auto;
            min-height: 52px;
            padding: 6px 4px 5px;
            gap: 2px;
            background: #128c7e;
        }
        .chat-icon--whatsapp .chat-whatsapp-btn--channel i {
            font-size: 22px;
        }
        .chat-icon--whatsapp .chat-whatsapp-btn--channel .chat-whatsapp-btn__label {
            font-family: var(--eduvers-font, "DM Sans", sans-serif);
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            line-height: 1;
        }
        .chat-icon--whatsapp .chat-whatsapp-btn::before {
            content: "";
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background-color: rgba(37, 211, 102, 0.25);
            border-radius: 8px;
            z-index: -1;
        }
        .chat-icon--whatsapp .chat-whatsapp-btn--channel::before {
            background-color: rgba(18, 140, 126, 0.25);
        }
        .chat-icon--whatsapp .chat-whatsapp-btn:hover,
        .chat-icon--whatsapp .chat-whatsapp-btn:focus {
            color: #fff !important;
            background: #25d366;
        }
        .chat-icon--whatsapp .chat-whatsapp-btn--channel:hover,
        .chat-icon--whatsapp .chat-whatsapp-btn--channel:focus {
            background: #128c7e;
        }
    </style>
</head>
@php
    $bnsOpenIntroSession = request()->query('open') === 'introduction-session'
        || request()->query('open') === 'book-your-spot'
        || (old('form_source') === 'intro-session-modal' && ($errors->any() || session()->has('error')));
    $bnsOpenQuickRegister = old('form_source') === 'register-quick-modal' && $errors->any();
    $bnsStickyCtaResolved = app(\App\Services\StickyCtaService::class)->resolve();
@endphp
<body class="custom-cursor{{ request()->routeIs('home') ? ' bns-page-home' : '' }}{{ !empty(app(\App\Services\StickyCtaService::class)->resolve()['enabled']) ? ' has-bns-sticky-cta' : '' }}{{ ($bnsOpenIntroSession || $bnsOpenQuickRegister) ? ' modal-open' : '' }}" data-bns-open-intro-session="{{ $bnsOpenIntroSession ? '1' : '0' }}" data-bns-open-quick-register="{{ $bnsOpenQuickRegister ? '1' : '0' }}" data-csrf-url="{{ route('csrf-token') }}">
    @if (session('error'))
        <div class="alert alert-danger text-center mb-0" role="alert" style="position:relative;z-index:100060;">{{ session('error') }}</div>
    @endif
    <div class="custom-cursor__cursor"></div>
    <div class="custom-cursor__cursor-two"></div>
    <div class="loader js-preloader"><div></div><div></div><div></div></div>
    @php
        $whatsappFloatUrl = bns_whatsapp_link();
        $whatsappChannel = config('whatsapp.channel', []);
        $whatsappChannelUrl = $whatsappChannel['url'] ?? '';
        $whatsappChannelEnabled = !empty($whatsappChannel['enabled']) && $whatsappChannelUrl !== '';
    @endphp
    <div class="chat-icon chat-icon--whatsapp">
        @if($whatsappChannelEnabled)
            <a
                href="{{ $whatsappChannelUrl }}"
                class="chat-whatsapp-btn chat-whatsapp-btn--channel"
                target="_blank"
                rel="noopener noreferrer"
                aria-label="Join WhatsApp Channel"
                title="Join WhatsApp Channel"
            >
                <i class="fab fa-whatsapp" aria-hidden="true"></i>
                <span class="chat-whatsapp-btn__label">{{ $whatsappChannel['float_label'] ?? 'Join Channel' }}</span>
            </a>
        @endif
        <a
            href="{{ $whatsappFloatUrl }}"
            class="chat-whatsapp-btn"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Chat on WhatsApp"
            title="Chat on WhatsApp"
        ><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
    </div>
    @include('partials.sidebar')
    <div class="page-wrapper">
        @include('partials.header')
        @yield('content')
        @include('partials.footer')
    </div>
    @include('partials.mobile-nav')
    <div class="search-popup">
        <div class="color-layer"></div>
        <button class="close-search"><span class="far fa-times fa-fw"></span></button>
        <form method="get" action="{{ url('/') }}"><div class="form-group"><input type="search" name="q" placeholder="Search Here" required><button type="submit"><i class="fas fa-search"></i></button></div></form>
    </div>
    <a href="#" data-target="html" class="scroll-to-target scroll-to-top"><span class="scroll-to-top__wrapper"><span class="scroll-to-top__inner"></span></span><span class="scroll-to-top__text">Go Back Top</span></a>
    @include('partials.introduction-session-modal', [
        'stickyIntroSession' => $bnsStickyCtaResolved['intro'] ?? config('home.sticky_cta.intro_session', []),
        'openOnLoad' => $bnsOpenIntroSession,
    ])
    @include('partials.quick-register-modal', [
        'stickyRegister' => $bnsStickyCtaResolved['register'] ?? config('home.sticky_cta.intro_session', []),
        'openOnLoad' => $bnsOpenQuickRegister,
    ])
    @include('partials.sticky-admission-bar')
    <script src="{{ bns_vasset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
    /* Hard close fallback — works even if bns-modals.js is cached/old on CDN/host */
    window.bnsCloseModal = window.bnsCloseModal || function (modalEl) {
        if (!modalEl) return false;
        modalEl.classList.remove('show');
        modalEl.classList.add('bns-modal-is-closed');
        modalEl.style.setProperty('display', 'none', 'important');
        modalEl.setAttribute('aria-hidden', 'true');
        document.querySelectorAll('.modal-backdrop,[data-bns-intro-session-backdrop],[data-bns-quick-register-backdrop]').forEach(function (n) { n.remove(); });
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.setAttribute('data-bns-open-intro-session', '0');
        document.body.setAttribute('data-bns-open-quick-register', '0');
        try {
            var u = new URL(window.location.href);
            u.searchParams.delete('open');
            window.history.replaceState(null, '', u.pathname + u.search + u.hash);
        } catch (e) {}
        return false;
    };
    window.bnsCloseIntroModal = window.bnsCloseIntroModal || function () {
        return window.bnsCloseModal(document.getElementById('bnsIntroSessionModal'));
    };
    </script>
    <script src="{{ bns_vasset('assets/js/bns-csrf.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/bns-modals.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/jquery-latest.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/jquery.appear.min.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/swiper.min.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/bns-intro-session-form.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/odometer.min.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/wow.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/jquery-sidebar-content.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/aos.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/gsap/gsap.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/gsap/ScrollTrigger.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/gsap/SplitText.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/script.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/bns-cta-modal.js') }}"></script>
    <script>
    (function () {
        function hidePreloader() {
            var el = document.querySelector('.js-preloader');
            if (el) { el.style.display = 'none'; }
        }
        window.addEventListener('load', function () {
            setTimeout(function () {
                var el = document.querySelector('.js-preloader');
                if (el && window.getComputedStyle(el).display !== 'none' && parseFloat(window.getComputedStyle(el).opacity) > 0.05) {
                    hidePreloader();
                }
            }, 2500);
        });
        setTimeout(hidePreloader, 12000);
    })();
    </script>
    @stack('modals')
    @stack('scripts')
    <script>
    (function () {
        var authSelect = document.getElementById('headerAuthSelect');
        if (!authSelect) return;
        authSelect.addEventListener('change', function () {
            var url = authSelect.value;
            if (url) window.location.href = url;
        });
    })();
    </script>
</body>
</html>
