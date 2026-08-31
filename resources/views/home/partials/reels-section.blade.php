@php($reelsConfig = $reelsSection ?? config('home_reels', []))
@if(!empty($reelsConfig['enabled']) && !empty($reelsConfig['reels']))
<section class="bns-home-reels" aria-labelledby="bns-home-reels-title">
    <div class="container">
        <div class="bns-home-reels__header wow fadeInUp" data-wow-duration="0.8s">
            @if(!empty($reelsConfig['tagline']))
                <span class="bns-home-reels__tagline">{{ $reelsConfig['tagline'] }}</span>
            @endif
            <h2 class="bns-home-reels__title" id="bns-home-reels-title">
                {{ $reelsConfig['title'] ?? 'BNS Reels' }}
            </h2>
            @if(!empty($reelsConfig['subtitle']))
                <p class="bns-home-reels__subtitle">{!! bns_rich_text($reelsConfig['subtitle']) !!}</p>
            @endif
        </div>

        @php($reelIndex = 0)
        <div class="bns-home-reels__rows">
            <div class="bns-home-reels__grid bns-home-reels__grid--cols-4" role="list">
                @foreach($reelsConfig['reels'] as $reel)
                        @php($embedUrl = bns_youtube_embed_url($reel['youtube_url'] ?? '', true))
                        @php($watchUrl = bns_youtube_watch_url($reel['youtube_url'] ?? ''))
                        @php($thumbUrl = !empty($reel['thumbnail']) ? bns_vasset($reel['thumbnail']) : bns_youtube_thumbnail_url($reel['youtube_url'] ?? ''))
                        <article
                            class="bns-home-reels__card wow fadeInUp"
                            role="listitem"
                            data-wow-duration="0.85s"
                            data-wow-delay="{{ 80 + ($reelIndex * 60) }}ms"
                        >
                            <div class="bns-home-reels__media">
                                @if($embedUrl && $thumbUrl)
                                    <button
                                        type="button"
                                        class="bns-home-reels__thumb js-bns-reel-play"
                                        data-embed-url="{{ $embedUrl }}"
                                        data-watch-url="{{ $watchUrl }}"
                                        data-title="{{ $reel['title'] ?? 'BNS Reel' }}"
                                        aria-label="Play {{ $reel['title'] ?? 'BNS Reel' }}"
                                    >
                                        <img
                                            class="bns-home-reels__thumb-img"
                                            src="{{ $thumbUrl }}"
                                            alt="{{ $reel['title'] ?? 'BNS Reel' }}"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                        <span class="bns-home-reels__play" aria-hidden="true">
                                            <i class="fas fa-play"></i>
                                        </span>
                                    </button>
                                @endif
                            </div>
                            <div class="bns-home-reels__meta">
                                <span class="bns-home-reels__badge">{{ str_pad((string) ($reel['id'] ?? ($reelIndex + 1)), 2, '0', STR_PAD_LEFT) }}</span>
                                @if(!empty($reel['title']))
                                    <h3 class="bns-home-reels__card-title">{{ $reel['title'] }}</h3>
                                @endif
                                @if(!empty($reel['caption']))
                                    <p class="bns-home-reels__caption">{!! bns_rich_text($reel['caption']) !!}</p>
                                @endif
                            </div>
                        </article>
                        @php($reelIndex++)
                @endforeach
            </div>
        </div>
    </div>
</section>

<div class="bns-reels-modal" id="bnsReelsModal" hidden aria-hidden="true">
    <div class="bns-reels-modal__backdrop js-bns-reel-close" aria-hidden="true"></div>
    <div class="bns-reels-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="bnsReelsModalTitle">
        <button type="button" class="bns-reels-modal__close js-bns-reel-close" aria-label="Close video">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>
        <h3 class="bns-reels-modal__title" id="bnsReelsModalTitle"></h3>
        <div class="bns-reels-modal__player">
            <iframe
                id="bnsReelsModalIframe"
                title="BNS Reel player"
                src=""
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin"
                allowfullscreen
            ></iframe>
        </div>
        <a href="#" class="bns-reels-modal__fallback" id="bnsReelsModalFallback" target="_blank" rel="noopener noreferrer">
            <i class="fab fa-youtube" aria-hidden="true"></i> Watch on YouTube
        </a>
    </div>
</div>
@endif

@push('scripts')
@if(!empty($reelsConfig['enabled']) && !empty($reelsConfig['reels']))
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modal = document.getElementById('bnsReelsModal');
    var iframe = document.getElementById('bnsReelsModalIframe');
    var titleEl = document.getElementById('bnsReelsModalTitle');
    var fallback = document.getElementById('bnsReelsModalFallback');
    if (!modal || !iframe) return;

    var lastFocus = null;

    function openReel(trigger) {
        var embedUrl = trigger.getAttribute('data-embed-url') || '';
        var watchUrl = trigger.getAttribute('data-watch-url') || '';
        var title = trigger.getAttribute('data-title') || 'BNS Reel';

        if (!embedUrl) {
            if (watchUrl) window.open(watchUrl, '_blank', 'noopener,noreferrer');
            return;
        }

        lastFocus = trigger;
        titleEl.textContent = title;
        iframe.src = embedUrl;
        fallback.href = watchUrl || embedUrl;
        modal.hidden = false;
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('bns-reels-modal-open');
        modal.querySelector('.bns-reels-modal__close').focus();
    }

    function closeReel() {
        iframe.src = '';
        modal.hidden = true;
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('bns-reels-modal-open');
        if (lastFocus) lastFocus.focus();
    }

    document.querySelectorAll('.js-bns-reel-play').forEach(function (btn) {
        btn.addEventListener('click', function () {
            openReel(btn);
        });
    });

    modal.querySelectorAll('.js-bns-reel-close').forEach(function (el) {
        el.addEventListener('click', closeReel);
    });

    document.addEventListener('keydown', function (event) {
        if (!modal.hidden && event.key === 'Escape') closeReel();
    });
});
</script>
@endif
@endpush
