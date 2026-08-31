<div class="section-title text-left sec-title-animation animation-style2">
    @unless($hideTagline ?? false)
    <div class="section-title__tagline-box">
        <div class="section-title__tagline-shape"></div>
        <div class="section-title__tagline-shape-2"></div>
        <span class="section-title__tagline">{{ $about->tagline }}</span>
    </div>
    @endunless
    <h2 class="section-title__title title-animation">{{ $about->heading }}</h2>
</div>
<p class="about-one__text">{!! bns_rich_text($about->intro_text) !!}</p>
@if($about->focus_heading && !empty($about->focus_points))
<p class="about-one__text about-one__text--focus mb-2"><strong>{{ $about->focus_heading }}</strong></p>
<div class="about-one__points-box about-one__points-box--full">
    <ul class="about-one__points list-unstyled">
        @foreach($about->focus_points as $point)
        <li>
            <div class="icon"><span class="icon-check-mark"></span></div>
            <h3>{!! bns_rich_text($point) !!}</h3>
        </li>
        @endforeach
    </ul>
</div>
@endif
@if($about->quote_text)
<h3 class="about-one__text-2 about-one__text-2--full-line">
    <span class="icon-graduate"></span>{!! bns_rich_text($about->quote_text) !!}
</h3>
@endif
<div class="about-one__btn-and-video">
    @if($showMoreLink ?? true)
    <div class="about-one__btn-box">
        <a href="{{ url('/about') }}" class="thm-btn">More About Us<span class="fas fa-arrow-right"></span></a>
    </div>
    @endif
</div>
