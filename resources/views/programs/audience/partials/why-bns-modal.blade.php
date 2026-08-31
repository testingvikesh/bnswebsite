@php
    $contentKey = $contentKey ?? 'why_bns';
    $content = $program[$contentKey] ?? [];
    $modalId = $modalId ?? 'bnsWhyBnsModal';
@endphp
<div class="modal fade bns-why-bns-modal bns-vision-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">{{ $content['eyebrow'] ?? ($program['title'] ?? 'Business Navachar School') }}</span>
                    <h5 class="modal-title" id="{{ $modalId }}Label">{{ $content['title'] ?? ($contentKey === 'why_business_education' ? 'Why Business Education?' : 'Why Choose BNS?') }}</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body">
                @if(!empty($content['subtitle']))
                    <p class="bns-why-bns-modal__subtitle">{{ $content['subtitle'] }}</p>
                @endif

                @foreach($content['intro'] ?? [] as $paragraph)
                    <p class="bns-why-bns-modal__intro">{!! bns_rich_text($paragraph) !!}</p>
                @endforeach

                @if(!empty($content['intro_closing']))
                    <p class="bns-why-bns-modal__intro-closing">{!! bns_rich_text($content['intro_closing']) !!}</p>
                @endif

                @foreach($content['sections'] ?? [] as $section)
                    <div class="bns-why-bns-modal__section">
                        @if(!empty($section['heading']))
                            <h6 class="bns-vision-modal__subheading">{{ $section['heading'] }}</h6>
                        @endif

                        @if(!empty($section['intro']))
                            <p class="bns-why-bns-modal__intro">{!! bns_rich_text($section['intro']) !!}</p>
                        @endif

                        @if(!empty($section['paragraph']))
                            <p class="bns-why-bns-modal__intro">{!! bns_rich_text($section['paragraph']) !!}</p>
                        @endif

                        @if(!empty($section['list_intro']))
                            <p class="bns-why-bns-modal__list-intro">{!! bns_rich_text($section['list_intro']) !!}</p>
                        @endif

                        @if(!empty($section['points']))
                            <ul class="bns-why-bns-modal__compact-list list-unstyled">
                                @foreach($section['points'] as $point)
                                    @if(is_array($point))
                                        <li>
                                            <i class="fas fa-chevron-right" aria-hidden="true"></i>
                                            <span>
                                                <strong>{{ $point['title'] ?? '' }}</strong>
                                                @if(!empty($point['text']))
                                                    <span class="bns-why-bns-modal__point-text">{!! bns_rich_text($point['text']) !!}</span>
                                                @endif
                                            </span>
                                        </li>
                                    @else
                                        <li><i class="fas fa-chevron-right" aria-hidden="true"></i> {!! bns_rich_text($point) !!}</li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif

                        @if(!empty($section['checklist']))
                            <ul class="bns-why-bns-modal__learn-list list-unstyled">
                                @foreach($section['checklist'] as $line)
                                    <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($line) !!}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if(!empty($section['contrast']))
                            <div class="bns-why-bns-modal__contrast">
                                @if(!empty($section['contrast']['instead']))
                                    <p class="bns-why-bns-modal__contrast-old">{!! bns_rich_text($section['contrast']['instead']) !!}</p>
                                @endif
                                @if(!empty($section['contrast']['they_learn']))
                                    <p class="bns-why-bns-modal__contrast-new">{!! bns_rich_text($section['contrast']['they_learn']) !!}</p>
                                @endif
                            </div>
                        @endif

                        @if(!empty($section['closing']))
                            <p class="bns-why-bns-modal__section-closing">{!! bns_rich_text($section['closing']) !!}</p>
                        @endif

                        @if(!empty($section['footer']))
                            <p class="bns-why-bns-modal__section-footer">{!! bns_rich_text($section['footer']) !!}</p>
                        @endif
                    </div>
                @endforeach

                @if(!empty($content['belief_heading']) || !empty($content['taglines']))
                    <div class="bns-why-bns-modal__belief">
                        @if(!empty($content['belief_heading']))
                            <h6 class="bns-why-bns-modal__belief-title">{{ $content['belief_heading'] }}</h6>
                        @endif
                        @foreach($content['taglines'] ?? [] as $tagline)
                            <p class="bns-why-bns-modal__belief-line"><i class="fas fa-star" aria-hidden="true"></i> {!! bns_rich_text($tagline) !!}</p>
                        @endforeach
                    </div>
                @endif

                @if(!empty($content['footer_taglines']))
                    <div class="bns-why-bns-modal__taglines">
                        @foreach($content['footer_taglines'] as $tagline)
                            <p class="bns-why-bns-modal__tagline">{!! bns_rich_text($tagline) !!}</p>
                        @endforeach
                    </div>
                @endif
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
