@php($slides = $heroSlides ?? config('home.hero_slides', []))
@if(!empty($slides))
<section class="bns-home-hero-slider" aria-label="Business Navachar School">
    <div class="swiper bns-home-hero-slider__carousel">
        <div class="swiper-wrapper">
            @foreach($slides as $index => $slide)
                <div class="swiper-slide">
                    <figure class="bns-home-hero-slider__slide">
                        <img
                            src="{{ $img($slide['image'] ?? 'hero_slide_1') }}"
                            alt="{{ $slide['alt'] ?? 'Business Navachar School' }}"
                            class="bns-home-hero-slider__img"
                            @if($index === 0) fetchpriority="high" decoding="async" @else loading="lazy" decoding="async" @endif
                            sizes="100vw"
                        >
                    </figure>
                </div>
            @endforeach
        </div>
        @if(count($slides) > 1)
            <div class="bns-home-hero-slider__pagination swiper-pagination"></div>
            <button type="button" class="bns-home-hero-slider__nav bns-home-hero-slider__nav--prev" aria-label="Previous banner">
                <i class="fas fa-chevron-left" aria-hidden="true"></i>
            </button>
            <button type="button" class="bns-home-hero-slider__nav bns-home-hero-slider__nav--next" aria-label="Next banner">
                <i class="fas fa-chevron-right" aria-hidden="true"></i>
            </button>
        @endif
    </div>
</section>
@endif
