{{-- Home page program preview card --}}
@props(['program'])

<div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-duration="0.85s" data-wow-delay="{{ $delay ?? '100ms' }}">
    <div class="categories-one__single bns-program-preview">
        <div class="categories-one__icon bns-program-preview__icon">
            <span aria-hidden="true">{{ $program['icon'] ?? '📚' }}</span>
        </div>
        <div class="categories-one__content-inner">
            <div class="categories-one__content-shape-1"></div>
            <div class="categories-one__content-shape-2"></div>
            <div class="categories-one__content">
                <h3 class="categories-one__title">
                    <a href="{{ route('programs.featured') }}#{{ $program['slug'] ?? '' }}">{{ $program['title'] ?? '' }}</a>
                </h3>
                @if(!empty($program['audience']))
                    <p class="categories-one__audience">{!! bns_rich_text($program['audience']) !!}</p>
                @endif
                @if(!empty($program['summary']))
                    <p class="categories-one__desc">{!! bns_rich_text($program['summary']) !!}</p>
                @endif
                @if(!empty($program['duration']))
                    <p class="bns-program-preview__duration">{!! bns_rich_text($program['duration']) !!}</p>
                @endif
                <div class="categories-one__read-more">
                    <a href="{{ route('programs.featured') }}#{{ $program['slug'] ?? '' }}">Read More<span class="fas fa-arrow-right"></span></a>
                </div>
            </div>
        </div>
    </div>
</div>
