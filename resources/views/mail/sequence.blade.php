@extends('layouts.front')

@section('title', $page['title'] ?? 'BNS Message')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
@endpush

@php
    $sectionMeta = [
        'coach_messages' => ['icon' => 'fas fa-chalkboard-teacher', 'tone' => 's1'],
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
    $messageCatalog = bns_build_message_catalog($sections);
    $isCoach = ($audienceKey ?? '') === 'business_coach';
    $flatBoxes = ! empty($flatBoxes);
    $showStageOverview = ! $flatBoxes || $isCoach;
@endphp

@section('content')
<div class="bns-message-page bns-mail-portal{{ $isCoach ? ' bns-mail-portal--coach' : ' bns-mail-portal--student' }}{{ $flatBoxes ? ' bns-mail-portal--flat' : '' }}">
    @include('partials.page-header', [
        'title' => $page['title'] ?? 'BNS Message',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'BNS Mail', 'url' => route('mail.hub')],
            ['label' => $page['title'] ?? 'BNS Message'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            <div class="bns-mail-toolbar bns-mail-toolbar--compact">
                <div class="bns-mail-toolbar__links">
                    <a href="{{ route('mail.hub') }}" class="bns-mail-toolbar__link"><i class="fas fa-th-large" aria-hidden="true"></i> All Mail</a>
                    <a href="{{ route('mail.student') }}" class="bns-mail-toolbar__link{{ ! $isCoach ? ' is-active' : '' }}">Student</a>
                    <a href="{{ route('mail.business-coach') }}" class="bns-mail-toolbar__link{{ $isCoach ? ' is-active' : '' }}">Business Coach</a>
                </div>
                <form method="POST" action="{{ route('mail.logout') }}">
                    @csrf
                    <button type="submit" class="bns-mail-toolbar__logout">
                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
                    </button>
                </form>
            </div>

            <div class="bns-message-intro">
                <span class="bns-message-intro__label">{{ $page['label'] ?? 'Messages' }}</span>
                @if(!empty($page['subtitle']))
                    <h2>{{ $page['subtitle'] }}</h2>
                @endif
                @if(!empty($page['intro']))
                    <p>{!! bns_rich_text($page['intro']) !!}</p>
                @endif
            </div>

            @if($showStageOverview)
            <div class="bns-message-overview{{ $isCoach ? ' bns-message-overview--single' : '' }}" aria-label="Communication stages">
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
                                {{ $count }} {{ \Illuminate\Support\Str::plural('message', $count) }}
                            </span>
                        </span>
                        <span class="bns-message-overview__footer">
                            <span>Open stage</span>
                            <span class="bns-message-overview__arrow" aria-hidden="true"><i class="fas fa-arrow-right"></i></span>
                        </span>
                    </a>
                @endforeach
            </div>
            @endif

            @foreach($sections as $sectionKey => $section)
                @php
                    $sectionId = $section['id'] ?? $sectionKey;
                    $meta = $sectionMeta[$sectionKey] ?? ['icon' => 'fas fa-chalkboard-teacher', 'tone' => 's1'];
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

                    <div class="bns-message-grid{{ $flatBoxes ? ' bns-mail-box-grid' : '' }}" role="list">
                        @forelse($items as $itemIndex => $item)
                            @php($richLayout = in_array(($item['layout'] ?? ''), ['promo', 'about', 'vision', 'pitch', 'reels', 'journey', 'benefits', 'highlights', 'bring', 'countdown', 'confirm', 'thanks', 'savedate', 'calreminder', 'wachannel', 'venue', 'dress', 'bizcard', 'reporting', 'surprise', 'tomorrow', 'checklist', 'founder', 'today', 'reminder', 'welcome', 'venuegps', 'welcomereg', 'attendance', 'instructions', 'admitcounter', 'scholarship', 'usefullinks', 'semthanks', 'photogallery', 'introsession', 'syllabus', 'admitreminder', 'paynow', 'firstbatch', 'faq', 'bnsfamily', 'founderwelcome', 'coach'], true))
                            <button
                                type="button"
                                class="bns-message-card{{ $richLayout ? ' bns-message-card--promo' : '' }}{{ $flatBoxes ? ' bns-mail-box' : '' }}"
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
    window.bnsMessageMailSendUrl = @json($sendMailUrl);
</script>
<script src="{{ bns_vasset('assets/js/bns-message-page.js') }}"></script>
@endpush
