@php($founder = $program['founder_message'] ?? [])
<div class="modal fade bns-founder-modal" id="bnsFounderMessageModal" tabindex="-1" aria-labelledby="bnsFounderMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bns-founder-modal__header">
                <div>
                    <span class="bns-founder-modal__eyebrow">{{ $founder['eyebrow'] ?? 'Business Navachar School (BNS)' }}</span>
                    <h5 class="modal-title" id="bnsFounderMessageModalLabel">{{ $founder['title'] ?? "Founder's Message" }}</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body">
                <div class="bns-founder-modal__layout">
                    <aside class="bns-founder-modal__photo-wrap">
                        @if(!empty($founder['photo']))
                            <img
                                src="{{ bns_vasset($founder['photo']) }}"
                                alt="{{ $founder['photo_alt'] ?? 'Dr. Mehul Rupani' }}"
                                class="bns-founder-modal__photo"
                                loading="lazy"
                                decoding="async"
                            >
                        @endif
                        <div class="bns-founder-modal__photo-card">
                            <p class="bns-founder-modal__photo-name">{{ $founder['signature']['name'] ?? 'Dr. Mehul Rupani' }}</p>
                            <p class="bns-founder-modal__photo-role">{{ $founder['signature']['title'] ?? 'Founder & Visionary' }}</p>
                            <p class="bns-founder-modal__photo-org">{{ $founder['signature']['organization'] ?? 'Business Navachar School (BNS)' }}</p>
                        </div>
                    </aside>

                    <div class="bns-founder-modal__letter">
                        @if(!empty($founder['program_subtitle']))
                            <p class="bns-founder-modal__program-subtitle">{{ $founder['program_subtitle'] }}</p>
                        @endif
                        @if(!empty($founder['salutation']))
                            <p class="bns-founder-modal__salutation">{{ $founder['salutation'] }}</p>
                        @endif

                        @foreach($founder['opening'] ?? [] as $paragraph)
                            <p class="bns-founder-modal__para">{!! bns_rich_text($paragraph) !!}</p>
                        @endforeach

                        @foreach($founder['sections'] ?? [] as $section)
                            <div class="bns-founder-modal__section">
                                @if(!empty($section['heading']))
                                    <h6 class="bns-founder-modal__section-title">{{ $section['heading'] }}</h6>
                                @endif
                                @if(!empty($section['intro']))
                                    <p class="bns-founder-modal__para">{!! bns_rich_text($section['intro']) !!}</p>
                                @endif
                                @if(!empty($section['paragraphs']))
                                    @foreach($section['paragraphs'] as $paragraph)
                                        <p class="bns-founder-modal__para">{!! bns_rich_text($paragraph) !!}</p>
                                    @endforeach
                                @endif
                                @if(!empty($section['list']))
                                    <ul class="bns-founder-modal__list list-unstyled">
                                        @foreach($section['list'] as $item)
                                            <li><i class="fas fa-check-circle" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if(!empty($section['taglines']))
                                    @php($taglineIcon = ($section['tagline_icon'] ?? '') === 'star' ? 'fas fa-star' : 'fas fa-chevron-right')
                                    @foreach($section['taglines'] as $line)
                                        <p class="bns-founder-modal__tagline{{ ($section['tagline_icon'] ?? '') === 'star' ? ' bns-founder-modal__tagline--star' : '' }}">
                                            <i class="{{ $taglineIcon }}" aria-hidden="true"></i> {!! bns_rich_text($line) !!}
                                        </p>
                                    @endforeach
                                @endif
                                @foreach($section['blocks'] ?? [] as $block)
                                    <div class="bns-founder-modal__tagline-block">
                                        <p class="bns-founder-modal__tagline"><i class="fas fa-chevron-right" aria-hidden="true"></i> {!! bns_rich_text($block['tagline'] ?? '') !!}</p>
                                        @if(!empty($block['text']))
                                            <p class="bns-founder-modal__tagline-text">{!! bns_rich_text($block['text']) !!}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        @if(!empty($founder['remember']))
                            <div class="bns-founder-modal__section bns-founder-modal__section--remember">
                                <h6 class="bns-founder-modal__section-title">{{ $founder['remember']['heading'] ?? 'Remember...' }}</h6>
                                @foreach($founder['remember']['taglines'] ?? [] as $line)
                                    <p class="bns-founder-modal__tagline bns-founder-modal__tagline--remember">
                                        <i class="fas fa-chevron-right" aria-hidden="true"></i> {!! bns_rich_text($line) !!}
                                    </p>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($founder['closing_lines']))
                            <div class="bns-founder-modal__section bns-founder-modal__section--closing">
                                @foreach($founder['closing_lines'] as $line)
                                    <p class="bns-founder-modal__para bns-founder-modal__para--closing">{!! bns_rich_text($line) !!}</p>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($founder['signature']))
                            <div class="bns-founder-modal__signature">
                                @if(!empty($founder['signature']['closing']))
                                    <p class="bns-founder-modal__signature-closing">{!! bns_rich_text($founder['signature']['closing']) !!}</p>
                                @endif
                                <p class="bns-founder-modal__signature-name">{{ $founder['signature']['name'] ?? '' }}</p>
                                <p class="bns-founder-modal__signature-title">{{ $founder['signature']['title'] ?? '' }}</p>
                                <p class="bns-founder-modal__signature-org">{{ $founder['signature']['organization'] ?? '' }}</p>
                            </div>
                        @endif

                        @if(!empty($founder['footer_taglines']))
                            <div class="bns-founder-modal__footer-tagline">
                                @foreach($founder['footer_taglines'] as $tagline)
                                    <p>{!! bns_rich_text($tagline) !!}</p>
                                @endforeach
                            </div>
                        @elseif(!empty($founder['footer_tagline']))
                            <div class="bns-founder-modal__footer-tagline">
                                <p>{!! bns_rich_text($founder['footer_tagline']) !!}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="bns-audience-btn bns-audience-btn--ghost" data-bs-dismiss="modal">Close</button>
                <button
                    type="button"
                    class="thm-btn"
                    data-bs-toggle="modal"
                    data-bs-target="#bnsIntroSessionModal"
                    data-register-program-id="{{ $journey['register_program_id'] ?? '' }}"
                    data-contact-program="{{ $journey['contact_program'] ?? '' }}"
                    data-contact-category="{{ $journey['contact_category'] ?? '' }}"
                    data-program-title="{{ $journey['label'] ?? '' }}"
                >
                    {{ config('site.apply_cta_label', 'Book Your Spot Now') }} <span class="fas fa-arrow-right"></span>
                </button>
            </div>
        </div>
    </div>
</div>
