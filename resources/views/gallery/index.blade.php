@extends('layouts.front')

@section('title', $page['title'] ?? 'Gallery')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/gallery-page.css') }}" />
@endpush

@section('content')
<div class="bns-gallery-page">
    @include('partials.page-header', [
        'title' => $page['title'] ?? 'Gallery',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page['title'] ?? 'Gallery'],
        ],
    ])

    <section class="bns-gallery-content">
        <div class="container">
            <div class="bns-gallery-intro">
                <span class="bns-gallery-intro__label">{{ $page['label'] ?? 'Event Gallery' }}</span>
                @if(!empty($page['subtitle']))
                    <h2>{{ $page['subtitle'] }}</h2>
                @endif
                @if(!empty($page['intro']))
                    <p>{!! bns_rich_text($page['intro']) !!}</p>
                @endif
            </div>

            @if($events->isEmpty())
                <div class="bns-gallery-empty">
                    <i class="fas fa-images" aria-hidden="true"></i>
                    <p>Gallery photos and reels will appear here once events are published.</p>
                </div>
            @else
                {{-- Album covers --}}
                <div class="bns-gallery-albums" id="bnsGalleryAlbums" role="list" aria-label="Photo albums">
                    @foreach($events as $event)
                        @php
                            $photoCount = $event->activePhotos->count();
                            $reelCount = $event->activeReels->count();
                            $cover = $event->coverUrl();
                        @endphp
                        <button
                            type="button"
                            class="bns-gallery-album"
                            data-open-album="{{ $event->slug }}"
                            role="listitem"
                        >
                            <span class="bns-gallery-album__cover">
                                @if($cover)
                                    <img src="{{ $cover }}" alt="" loading="lazy">
                                @else
                                    <span class="bns-gallery-album__placeholder" aria-hidden="true">
                                        <i class="fas fa-images"></i>
                                    </span>
                                @endif
                                <span class="bns-gallery-album__badge">
                                    <i class="fas fa-camera" aria-hidden="true"></i>
                                    {{ $photoCount }} {{ $photoCount === 1 ? 'Photo' : 'Photos' }}
                                    @if($reelCount > 0)
                                        · {{ $reelCount }} {{ $reelCount === 1 ? 'Reel' : 'Reels' }}
                                    @endif
                                    @if($event->hasPicasaLink())
                                        · Album Link
                                    @endif
                                </span>
                            </span>
                            <span class="bns-gallery-album__meta">
                                <strong>{{ $event->title }}</strong>
                                @if($event->dateLabel())
                                    <em>{{ $event->dateLabel() }}</em>
                                @elseif($event->subtitle)
                                    <em>{{ $event->subtitle }}</em>
                                @endif
                            </span>
                        </button>
                    @endforeach
                </div>

                {{-- Opened album --}}
                @foreach($events as $event)
                    <section
                        class="bns-gallery-event"
                        id="gallery-{{ $event->slug }}"
                        data-gallery-panel="{{ $event->slug }}"
                        hidden
                    >
                        <div class="bns-gallery-event__toolbar">
                            <button type="button" class="bns-gallery-back" data-close-album>
                                <i class="fas fa-arrow-left" aria-hidden="true"></i>
                                All Albums
                            </button>
                        </div>

                        <div class="bns-gallery-album-sheet">
                            <div class="bns-gallery-event__head">
                                <h3>{{ $event->title }}</h3>
                                @if($event->subtitle)
                                    <p>{{ $event->subtitle }}</p>
                                @elseif($event->dateLabel())
                                    <p>{{ $event->dateLabel() }}</p>
                                @endif
                                @if($event->hasPicasaLink())
                                    <a
                                        class="bns-gallery-picasa"
                                        href="{{ $event->picasa_url }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                    >
                                        <i class="fas fa-external-link-alt" aria-hidden="true"></i>
                                        {{ $event->picasaButtonLabel() }}
                                    </a>
                                @endif
                            </div>

                            @if($event->activePhotos->isNotEmpty())
                                <div class="bns-gallery-block">
                                    <h4 class="bns-gallery-block__title">
                                        <i class="fas fa-camera" aria-hidden="true"></i> Photos
                                    </h4>
                                    <div class="bns-gallery-photos">
                                        @foreach($event->activePhotos as $photo)
                                            <a
                                                href="{{ $photo->url() }}"
                                                class="bns-gallery-photo img-popup"
                                                data-group="{{ $event->id }}"
                                                title="{{ $photo->title ?: $event->title }}"
                                            >
                                                <img src="{{ $photo->url() }}" alt="{{ $photo->title ?: $event->title }}" loading="lazy">
                                                @if($photo->title || $photo->caption)
                                                    <span class="bns-gallery-photo__caption">{{ $photo->title ?: $photo->caption }}</span>
                                                @endif
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if($event->activeReels->isNotEmpty())
                                <div class="bns-gallery-block">
                                    <h4 class="bns-gallery-block__title">
                                        <i class="fab fa-youtube" aria-hidden="true"></i> YouTube Reels
                                    </h4>
                                    <div class="bns-gallery-reels">
                                        @foreach($event->activeReels as $reel)
                                            @php($embedUrl = bns_youtube_embed_url($reel->youtube_url))
                                            @php($watchUrl = bns_youtube_watch_url($reel->youtube_url))
                                            @if($embedUrl)
                                                <a
                                                    href="{{ $embedUrl }}"
                                                    class="bns-gallery-reel video-popup"
                                                    data-mfp-src="{{ $embedUrl }}"
                                                    title="{{ $reel->title }}"
                                                >
                                                    <span class="bns-gallery-reel__thumb">
                                                        <img src="{{ $reel->thumbnailUrl() }}" alt="{{ $reel->title }}" loading="lazy">
                                                        <span class="bns-gallery-reel__play"><i class="fas fa-play" aria-hidden="true"></i></span>
                                                    </span>
                                                    <span class="bns-gallery-reel__meta">
                                                        <strong>{{ $reel->title }}</strong>
                                                        @if($reel->caption)
                                                            <span>{{ $reel->caption }}</span>
                                                        @endif
                                                    </span>
                                                </a>
                                            @elseif($watchUrl)
                                                <a
                                                    href="{{ $watchUrl }}"
                                                    class="bns-gallery-reel"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    title="{{ $reel->title }}"
                                                >
                                                    <span class="bns-gallery-reel__thumb">
                                                        <img src="{{ $reel->thumbnailUrl() }}" alt="{{ $reel->title }}" loading="lazy">
                                                        <span class="bns-gallery-reel__play"><i class="fas fa-play" aria-hidden="true"></i></span>
                                                    </span>
                                                    <span class="bns-gallery-reel__meta">
                                                        <strong>{{ $reel->title }}</strong>
                                                        @if($reel->caption)
                                                            <span>{{ $reel->caption }}</span>
                                                        @endif
                                                    </span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </section>
                @endforeach
            @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var albumsWrap = document.getElementById('bnsGalleryAlbums');
    var openBtns = document.querySelectorAll('[data-open-album]');
    var panels = document.querySelectorAll('[data-gallery-panel]');
    var closeBtns = document.querySelectorAll('[data-close-album]');
    if (!openBtns.length || !panels.length) return;

    function headerOffset() {
        var header = document.querySelector('.main-header') || document.querySelector('header');
        var h = header ? header.getBoundingClientRect().height : 0;
        return Math.max(80, Math.round(h) + 16);
    }

    function scrollToEl(el) {
        if (!el) return;
        var top = el.getBoundingClientRect().top + window.pageYOffset - headerOffset();
        window.scrollTo({ top: Math.max(0, top), behavior: 'smooth' });
    }

    function openAlbum(slug, pushHash) {
        var activePanel = null;
        if (albumsWrap) albumsWrap.hidden = true;
        document.body.classList.add('bns-gallery-album-open');
        panels.forEach(function (panel) {
            var on = panel.getAttribute('data-gallery-panel') === slug;
            panel.classList.toggle('is-active', on);
            if (on) {
                panel.removeAttribute('hidden');
                activePanel = panel;
            } else {
                panel.setAttribute('hidden', 'hidden');
            }
        });
        if (pushHash !== false) {
            history.replaceState(null, '', '#gallery-' + slug);
        }
        // Double rAF + short delay so sticky header & layout settle
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                scrollToEl(activePanel);
                setTimeout(function () {
                    scrollToEl(activePanel);
                }, 50);
            });
        });
    }

    function closeAlbum() {
        panels.forEach(function (panel) {
            panel.classList.remove('is-active');
            panel.setAttribute('hidden', 'hidden');
        });
        document.body.classList.remove('bns-gallery-album-open');
        if (albumsWrap) albumsWrap.hidden = false;
        history.replaceState(null, '', window.location.pathname + window.location.search);
        requestAnimationFrame(function () {
            scrollToEl(albumsWrap);
        });
    }

    openBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            openAlbum(btn.getAttribute('data-open-album'));
        });
    });

    closeBtns.forEach(function (btn) {
        btn.addEventListener('click', closeAlbum);
    });

    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }

    if (window.location.hash) {
        var hashSlug = window.location.hash.replace('#gallery-', '');
        if (hashSlug) openAlbum(hashSlug, false);
    }
})();
</script>
@endpush
