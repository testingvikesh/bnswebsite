@php
    $applyUrl = $applyUrl ?? route('register');
    $introSessionUrl = $introSessionUrl ?? route('admissions.page', 'introduction-session');
    $embedded = $embedded ?? false;
    $gridClass = $embedded ? 'g-3 g-md-4' : 'g-4';
@endphp

@if($embedded)
<div class="bns-about-links bns-about-links--embedded">
    <div class="bns-about-links__heading">
        <h3 class="bns-about-links__title">Explore <span>BNS</span></h3>
    </div>
@endif

<div class="row {{ $gridClass }} bns-about-links__grid">
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('about.team') }}" class="bns-about-links__btn">
            <i class="fas fa-users" aria-hidden="true"></i> Meet Our Team
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('about.faculty') }}" class="bns-about-links__btn">
            <i class="fas fa-chalkboard-teacher" aria-hidden="true"></i> Visiting Expert Faculty
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('admissions.index') }}" class="bns-about-links__btn">
            <i class="fas fa-graduation-cap" aria-hidden="true"></i> Admissions
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <button type="button" class="bns-about-links__btn bns-about-links__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
            <i class="fas fa-rocket" aria-hidden="true"></i> {{ config('site.apply_cta_label', 'Book Your Spot Now') }}
        </button>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('about.vision-2047') }}" class="bns-about-links__btn">
            <i class="fas fa-binoculars" aria-hidden="true"></i> Vision 2047
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('about.prosperity-mission') }}" class="bns-about-links__btn">
            <i class="fas fa-flag" aria-hidden="true"></i> Prosperity Mission
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('about.why-business-education') }}" class="bns-about-links__btn">
            <i class="fas fa-lightbulb" aria-hidden="true"></i> Why Business Education?
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('about.why') }}" class="bns-about-links__btn">
            <i class="fas fa-star" aria-hidden="true"></i> Why BNS?
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('about.founder') }}" class="bns-about-links__btn">
            <i class="fas fa-quote-left" aria-hidden="true"></i> Founder's Message
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ route('contact') }}" class="bns-about-links__btn">
            <i class="fas fa-envelope" aria-hidden="true"></i> Contact Us
        </a>
    </div>
    <div class="col-md-6 col-lg-4">
        <a href="{{ $introSessionUrl }}" class="bns-about-links__btn">
            <i class="fas fa-calendar-check" aria-hidden="true"></i> Book Intro Session
        </a>
    </div>
</div>

@if($embedded)
</div>
@endif
