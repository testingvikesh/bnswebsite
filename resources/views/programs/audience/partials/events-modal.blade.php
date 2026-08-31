@php($content = $program['events_experiences'] ?? [])
<div class="modal fade bns-events-modal bns-vision-modal" id="bnsEventsExperiencesModal" tabindex="-1" aria-labelledby="bnsEventsExperiencesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">{{ $content['eyebrow'] ?? 'Business Navachar School (BNS)' }}</span>
                    <h5 class="modal-title" id="bnsEventsExperiencesModalLabel">{{ $content['title'] ?? 'Events & Experiences' }}</h5>
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
                    @php($type = $section['type'] ?? 'compact')
                    <div class="bns-events-modal__section{{ $type === 'featured' ? ' bns-events-modal__section--featured' : '' }}">
                        @if(!empty($section['heading']))
                            <h6 class="bns-vision-modal__subheading">{{ $section['heading'] }}</h6>
                        @endif

                        @if(!empty($section['intro']))
                            <p class="bns-why-bns-modal__intro">{!! bns_rich_text($section['intro']) !!}</p>
                        @endif

                        @if(!empty($section['list_heading']))
                            <p class="bns-certification-modal__list-heading">{{ $section['list_heading'] }}</p>
                        @endif

                        @if($type === 'compact' && !empty($section['items']))
                            <ul class="bns-why-bns-modal__compact-list list-unstyled">
                                @foreach($section['items'] as $item)
                                    <li><i class="fas fa-chevron-right" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if($type === 'emoji' && !empty($section['items']))
                            <ul class="bns-events-modal__emoji-list list-unstyled">
                                @foreach($section['items'] as $item)
                                    <li>
                                        <span class="bns-events-modal__emoji" aria-hidden="true">{{ $section['emoji'] ?? '⭐' }}</span>
                                        {!! bns_rich_text($item) !!}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if($type === 'featured' && !empty($section['items']))
                            <ul class="bns-events-modal__featured-list list-unstyled">
                                @foreach($section['items'] as $item)
                                    <li>
                                        <span class="bns-events-modal__emoji" aria-hidden="true">{{ $section['emoji'] ?? '🚀' }}</span>
                                        {!! bns_rich_text($item) !!}
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if($type === 'checklist' && !empty($section['items']))
                            <ul class="bns-why-bns-modal__learn-list list-unstyled">
                                @foreach($section['items'] as $item)
                                    <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach

                @if(!empty($content['taglines']))
                    <div class="bns-why-bns-modal__taglines">
                        @foreach($content['taglines'] as $tagline)
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
