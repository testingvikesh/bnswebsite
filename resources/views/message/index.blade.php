@extends('layouts.front')

@section('title', $page['title'] ?? 'BNS Message')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
@endpush

@php
    $sectionMeta = [
        'stage_1' => ['icon' => 'fas fa-bullhorn', 'tone' => 's1'],
        'stage_2' => ['icon' => 'fas fa-clipboard-list', 'tone' => 's2'],
        'stage_3' => ['icon' => 'fas fa-comments', 'tone' => 's3'],
        'stage_4' => ['icon' => 'fas fa-calendar-week', 'tone' => 's4'],
        'stage_5' => ['icon' => 'fas fa-hourglass-half', 'tone' => 's5'],
        'stage_6' => ['icon' => 'fas fa-calendar-day', 'tone' => 's6'],
        'stage_7' => ['icon' => 'fas fa-chalkboard-teacher', 'tone' => 's7'],
        'stage_8' => ['icon' => 'fas fa-handshake', 'tone' => 's8'],
        'stage_9' => ['icon' => 'fas fa-user-check', 'tone' => 's9'],
        'stage_10' => ['icon' => 'fas fa-award', 'tone' => 's10'],
    ];

    $messageCatalog = [];
    foreach ($sections as $sectionKey => $section) {
        $items = array_values($section['items'] ?? []);
        foreach ($items as $itemIndex => $item) {
            $plain = ! empty($item['whatsapp'])
                ? trim((string) $item['whatsapp'])
                : collect($item['body'] ?? [])
                    ->map(fn ($line) => trim(strip_tags((string) $line)))
                    ->filter()
                    ->implode("\n\n");

            $messageCatalog[] = [
                'section' => $sectionKey,
                'section_title' => $section['title'] ?? 'Message',
                'index' => $itemIndex,
                'total' => count($items),
                'id' => $item['id'] ?? ($sectionKey.'-'.$itemIndex),
                'title' => $item['title'] ?? 'Message',
                'layout' => $item['layout'] ?? 'default',
                'image' => ! empty($item['image']) ? bns_vasset($item['image']) : null,
                'body' => array_values(array_map(
                    fn ($line) => bns_rich_text((string) $line),
                    $item['body'] ?? []
                )),
                'plain' => $plain,
                'promo' => $item['promo'] ?? null,
                'about' => $item['about'] ?? null,
                'vision' => $item['vision'] ?? null,
                'pitch' => $item['pitch'] ?? null,
                'reels' => $item['reels'] ?? null,
                'journey' => $item['journey'] ?? null,
                'benefits' => $item['benefits'] ?? null,
                'highlights' => $item['highlights'] ?? null,
                'bring' => $item['bring'] ?? null,
                'countdown' => $item['countdown'] ?? null,
                'confirm' => $item['confirm'] ?? null,
                'thanks' => $item['thanks'] ?? null,
                'savedate' => $item['savedate'] ?? null,
                'calreminder' => $item['calreminder'] ?? null,
                'wachannel' => $item['wachannel'] ?? null,
                'venue' => $item['venue'] ?? null,
                'dress' => $item['dress'] ?? null,
                'bizcard' => $item['bizcard'] ?? null,
                'reporting' => $item['reporting'] ?? null,
                'surprise' => $item['surprise'] ?? null,
                'tomorrow' => $item['tomorrow'] ?? null,
                'checklist' => $item['checklist'] ?? null,
                'founder' => $item['founder'] ?? null,
                'today' => $item['today'] ?? null,
                'reminder' => $item['reminder'] ?? null,
                'welcome' => $item['welcome'] ?? null,
                'venuegps' => $item['venuegps'] ?? null,
                'welcomereg' => $item['welcomereg'] ?? null,
                'attendance' => $item['attendance'] ?? null,
                'instructions' => $item['instructions'] ?? null,
                'admitcounter' => $item['admitcounter'] ?? null,
                'scholarship' => $item['scholarship'] ?? null,
                'usefullinks' => $item['usefullinks'] ?? null,
                'semthanks' => $item['semthanks'] ?? null,
                'photogallery' => $item['photogallery'] ?? null,
                'introsession' => $item['introsession'] ?? null,
                'syllabus' => $item['syllabus'] ?? null,
                'admitreminder' => $item['admitreminder'] ?? null,
                'paynow' => $item['paynow'] ?? null,
                'firstbatch' => $item['firstbatch'] ?? null,
                'faq' => $item['faq'] ?? null,
                'bnsfamily' => $item['bnsfamily'] ?? null,
                'founderwelcome' => $item['founderwelcome'] ?? null,
                'links' => array_values(array_map(function ($link) {
                    return [
                        'label' => $link['label'] ?? 'Open link',
                        'url' => $link['url'] ?? '#',
                        'external' => ! empty($link['external']),
                    ];
                }, $item['links'] ?? [])),
                'cta' => ! empty($item['cta']['url']) ? [
                    'label' => $item['cta']['label'] ?? 'Continue',
                    'url' => $item['cta']['url'],
                ] : null,
            ];
        }
    }
@endphp

@section('content')
<div class="bns-message-page">
    @include('partials.page-header', [
        'title' => $page['title'] ?? 'BNS Message',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $page['title'] ?? 'BNS Message'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            <div class="bns-message-intro">
                <span class="bns-message-intro__label">{{ $page['label'] ?? 'Messages' }}</span>
                @if(!empty($page['subtitle']))
                    <h2>{{ $page['subtitle'] }}</h2>
                @endif
                @if(!empty($page['intro']))
                    <p>{!! bns_rich_text($page['intro']) !!}</p>
                @endif
            </div>

            <div class="bns-message-overview" aria-label="Communication stages">
                @foreach($sections as $sectionKey => $section)
                    @php
                        $meta = $sectionMeta[$sectionKey] ?? ['icon' => 'fas fa-comment-dots', 'tone' => 's1'];
                        $count = count($section['items'] ?? []);
                        $stageNo = (int) preg_replace('/^stage_/', '', (string) $sectionKey);
                        if ($stageNo < 1) {
                            $stageNo = (int) $loop->iteration;
                        }
                        $cardTitle = $section['short_title'] ?? preg_replace('/^Stage\s*\d+\s*[–-]\s*/u', '', (string) ($section['title'] ?? 'Section'));
                    @endphp
                    <a href="#{{ $section['id'] ?? $sectionKey }}" class="bns-message-overview__card bns-message-overview__card--{{ $meta['tone'] }}">
                        <span class="bns-message-overview__top">
                            <span class="bns-message-overview__stage">{{ str_pad((string) $stageNo, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="bns-message-overview__icon" aria-hidden="true"><i class="{{ $meta['icon'] }}"></i></span>
                        </span>
                        <span class="bns-message-overview__copy">
                            <span class="bns-message-overview__eyebrow">Stage {{ $stageNo }}</span>
                            <strong>{{ $cardTitle }}</strong>
                            <span class="bns-message-overview__count">
                                <i class="fas fa-comment-dots" aria-hidden="true"></i>
                                {{ $count }} {{ Str::plural('message', $count) }}
                            </span>
                        </span>
                        <span class="bns-message-overview__footer">
                            <span>Open stage</span>
                            <span class="bns-message-overview__arrow" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>
                        </span>
                    </a>
                @endforeach
            </div>

            @foreach($sections as $sectionKey => $section)
                @php
                    $sectionId = $section['id'] ?? $sectionKey;
                    $meta = $sectionMeta[$sectionKey] ?? ['icon' => 'fas fa-comment-dots', 'tone' => 's1'];
                    $items = array_values($section['items'] ?? []);
                @endphp
                <section class="bns-message-section bns-message-section--{{ $meta['tone'] }}" id="{{ $sectionId }}">
                    <header class="bns-message-section__head">
                        <div class="bns-message-section__badge" aria-hidden="true">
                            <i class="{{ $meta['icon'] }}"></i>
                        </div>
                        <div>
                            @if(!empty($section['eyebrow']))
                                <span class="bns-message-section__eyebrow">{{ $section['eyebrow'] }}</span>
                            @endif
                            <h3>{{ $section['title'] ?? 'Section' }}</h3>
                            @if(!empty($section['intro']))
                                <p>{{ $section['intro'] }}</p>
                            @endif
                        </div>
                        <div class="bns-message-section__meta">
                            <span>{{ count($items) }}</span>
                            Messages
                        </div>
                    </header>

                    <div class="bns-message-grid" role="list">
                        @forelse($items as $itemIndex => $item)
                            @php($richLayout = in_array(($item['layout'] ?? ''), ['promo', 'about', 'vision', 'pitch', 'reels', 'journey', 'benefits', 'highlights', 'bring', 'countdown', 'confirm', 'thanks', 'savedate', 'calreminder', 'wachannel', 'venue', 'dress', 'bizcard', 'reporting', 'surprise', 'tomorrow', 'checklist', 'founder', 'today', 'reminder', 'welcome', 'venuegps', 'welcomereg', 'attendance', 'instructions', 'admitcounter', 'scholarship', 'usefullinks', 'semthanks', 'photogallery', 'introsession', 'syllabus', 'admitreminder', 'paynow', 'firstbatch', 'faq', 'bnsfamily', 'founderwelcome'], true))
                            <button
                                type="button"
                                class="bns-message-card{{ $richLayout ? ' bns-message-card--promo' : '' }}"
                                role="listitem"
                                data-bs-toggle="modal"
                                data-bs-target="#bnsMessageViewerModal"
                                data-message-open
                                data-section="{{ $sectionKey }}"
                                data-index="{{ $itemIndex }}"
                            >
                                <span class="bns-message-card__num">{{ str_pad((string) ($itemIndex + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="bns-message-card__body">
                                    <span class="bns-message-card__title">{{ $item['title'] ?? 'Message' }}</span>
                                    <span class="bns-message-card__hint">
                                        {{ $richLayout ? 'Tap for rich layout' : 'Tap to open message' }}
                                    </span>
                                </span>
                                <span class="bns-message-card__meta">
                                    <span class="bns-message-card__action" aria-hidden="true">
                                        <i class="fas fa-expand"></i>
                                    </span>
                                </span>
                            </button>
                        @empty
                            <p class="bns-message-grid__empty">Messages for this section will be added soon.</p>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>
    </section>
</div>
@endsection

{{-- Outside .page-wrapper (overflow:hidden) so Bootstrap modal can display --}}
@push('modals')
<div
    class="modal fade bns-message-modal bns-vision-modal"
    id="bnsMessageViewerModal"
    tabindex="-1"
    aria-labelledby="bnsMessageViewerModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow" data-message-section>Message</span>
                    <h5 class="modal-title" id="bnsMessageViewerModalLabel" data-message-title>Message</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body bns-message-modal__panel">
                <div class="bns-message-modal__nav-row">
                    <button type="button" class="bns-message-modal__nav" data-message-nav="prev" aria-label="Previous message">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <span class="bns-message-modal__counter" data-message-counter>1 / 1</span>
                    <button type="button" class="bns-message-modal__nav" data-message-nav="next" aria-label="Next message">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="bns-message-modal__image d-none" data-message-image-wrap>
                    <img src="" alt="" data-message-image loading="lazy" decoding="async">
                </div>

                <div class="bns-message-modal__sheet">
                    <div class="bns-message-modal__body" data-message-body></div>
                    <div class="bns-message-modal__links d-none" data-message-links></div>
                </div>

                <div class="bns-message-modal__actions">
                    <button type="button" class="btn bns-message-modal__copy" data-message-copy>
                        <i class="fas fa-copy" aria-hidden="true"></i> Copy Message
                    </button>
                    <a href="#" class="btn bns-message-modal__send" target="_blank" rel="noopener noreferrer" data-message-send>
                        <i class="fab fa-whatsapp" aria-hidden="true"></i> Send Message
                    </a>
                    <button type="button" class="btn bns-message-modal__mail" data-message-mail>
                        <i class="fas fa-envelope" aria-hidden="true"></i> Send Mail
                    </button>
                    <a href="#" class="btn bns-message-modal__cta d-none" data-message-cta>
                        <span data-message-cta-label>Continue</span>
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div
    class="modal fade bns-message-mail-modal"
    id="bnsMessageMailModal"
    tabindex="-1"
    aria-labelledby="bnsMessageMailModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bns-message-mail-modal__header">
                <div>
                    <span class="bns-message-mail-modal__eyebrow">Email Message</span>
                    <h5 class="modal-title" id="bnsMessageMailModalLabel" data-mail-title>Send Mail</h5>
                </div>
                @include('partials.modal-close-button', ['onLight' => true])
            </div>
            <form id="bnsMessageMailForm" class="bns-message-mail-modal__form" novalidate>
                <div class="modal-body">
                    <p class="bns-message-mail-modal__hint">Enter the recipient email address to send this message.</p>
                    <label class="bns-message-mail-modal__label" for="bnsMessageMailEmail">Email address</label>
                    <input
                        type="email"
                        id="bnsMessageMailEmail"
                        name="email"
                        class="form-control bns-message-mail-modal__input"
                        placeholder="name@example.com"
                        required
                        autocomplete="email"
                        data-mail-email
                    >
                    <p class="bns-message-mail-modal__error d-none" data-mail-error role="alert"></p>
                    <p class="bns-message-mail-modal__success d-none" data-mail-success role="status"></p>
                </div>
                <div class="modal-footer bns-message-mail-modal__footer">
                    <button type="button" class="btn bns-message-mail-modal__cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn bns-message-mail-modal__submit" data-mail-submit>
                        <i class="fas fa-paper-plane" aria-hidden="true"></i>
                        <span data-mail-submit-label>Send Mail</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="application/json" id="bnsMessageCatalog">@json($messageCatalog)</script>
@endpush

@push('scripts')
<script>
    window.bnsMessageMailSendUrl = @json(route('message.send-mail'));
</script>
<script src="{{ bns_vasset('assets/js/bns-message-page.js') }}"></script>
@endpush
