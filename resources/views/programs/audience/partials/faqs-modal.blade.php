@php($content = $program['faqs'] ?? [])
@php($snapshot = $content['program_snapshot'] ?? [])
@php($snapshotAfterPart = $snapshot['after_part_index'] ?? null)
<div class="modal fade bns-faqs-modal bns-vision-modal" id="bnsFaqsModal" tabindex="-1" aria-labelledby="bnsFaqsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">{{ $content['eyebrow'] ?? ($program['title'] ?? 'Business Navachar School') }}</span>
                    <h5 class="modal-title" id="bnsFaqsModalLabel">{{ $content['title'] ?? 'Frequently Asked Questions (FAQs)' }}</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body">
                @foreach($content['parts'] ?? [] as $partIndex => $part)
                    @if(!empty($part['heading']))
                        <h6 class="bns-vision-modal__subheading{{ $partIndex > 0 ? ' bns-faqs-modal__part-heading' : '' }}">{{ $part['heading'] }}</h6>
                    @endif

                    <div class="accordion bns-faqs-modal__accordion" id="bnsFaqsAccordion{{ $partIndex }}">
                        @foreach($part['questions'] ?? [] as $qIndex => $faq)
                            @php($collapseId = 'bnsFaq' . $partIndex . 'Q' . ($faq['number'] ?? ($qIndex + 1)))
                            <div class="accordion-item bns-faqs-modal__item">
                                <h2 class="accordion-header" id="heading{{ $collapseId }}">
                                    <button
                                        class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#{{ $collapseId }}"
                                        aria-expanded="false"
                                        aria-controls="{{ $collapseId }}"
                                    >
                                        <span class="bns-faqs-modal__q-num">Q{{ $faq['number'] ?? ($qIndex + 1) }}</span>
                                        <span class="bns-faqs-modal__q-text">{{ $faq['question'] ?? '' }}</span>
                                    </button>
                                </h2>
                                <div
                                    id="{{ $collapseId }}"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $collapseId }}"
                                    data-bs-parent="#bnsFaqsAccordion{{ $partIndex }}"
                                >
                                    <div class="accordion-body">
                                        <ul class="bns-faqs-modal__answers list-unstyled">
                                            @foreach($faq['answers'] ?? [] as $answer)
                                                <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($answer) !!}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($snapshotAfterPart !== null && (int) $snapshotAfterPart === (int) $partIndex && !empty($snapshot['rows']))
                        <div class="bns-faqs-modal__snapshot">
                            <h6 class="bns-vision-modal__subheading bns-faqs-modal__part-heading">{{ $snapshot['heading'] ?? 'Program Snapshot' }}</h6>
                            <ul class="bns-faqs-modal__snapshot-list list-unstyled">
                                @foreach($snapshot['rows'] as $row)
                                    <li>
                                        <span class="bns-faqs-modal__snapshot-label">{{ $row['label'] ?? '' }}</span>
                                        <span class="bns-faqs-modal__snapshot-value">{{ $row['value'] ?? '' }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(!empty($part['footer']['philosophy']))
                        @php($philosophy = $part['footer']['philosophy'])
                        <div class="bns-faqs-modal__philosophy">
                            <h6 class="bns-vision-modal__subheading bns-faqs-modal__part-heading">{{ $philosophy['heading'] ?? 'Our Learning Philosophy' }}</h6>
                            @if(!empty($philosophy['intro']))
                                <p class="bns-faqs-modal__philosophy-intro">{!! bns_rich_text($philosophy['intro']) !!}</p>
                            @endif
                            <ul class="bns-faqs-modal__philosophy-lines list-unstyled">
                                @foreach($philosophy['lines'] ?? [] as $line)
                                    <li>{!! bns_rich_text($line) !!}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(!empty($part['footer']['admission_heading']) || !empty($part['footer']['steps']))
                        <div class="bns-faqs-modal__admission">
                            @if(!empty($part['footer']['admission_heading']))
                                <h6 class="bns-vision-modal__subheading bns-faqs-modal__part-heading">{{ $part['footer']['admission_heading'] }}</h6>
                            @endif
                            <ul class="bns-faqs-modal__admission-steps list-unstyled">
                                @foreach($part['footer']['steps'] ?? [] as $step)
                                    <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($step) !!}</li>
                                @endforeach
                            </ul>
                            @if(!empty($part['footer']['highlight']))
                                <p class="bns-faqs-modal__admission-highlight">{!! bns_rich_text($part['footer']['highlight']) !!}</p>
                            @endif
                        </div>
                    @endif

                    @if(!empty($part['footer']['certification_notice']))
                        @php($notice = $part['footer']['certification_notice'])
                        <div class="bns-faqs-modal__cert-notice">
                            @if(!empty($notice['heading']))
                                <h6 class="bns-vision-modal__subheading bns-faqs-modal__part-heading">{{ $notice['heading'] }}</h6>
                            @endif
                            @if(!empty($notice['text']))
                                <p class="bns-faqs-modal__cert-notice-text">{!! bns_rich_text($notice['text']) !!}</p>
                            @endif
                        </div>
                    @endif

                    @if(!empty($part['footer']['taglines']))
                        <div class="bns-why-bns-modal__taglines">
                            @foreach($part['footer']['taglines'] as $tagline)
                                <p class="bns-why-bns-modal__tagline">{!! bns_rich_text($tagline) !!}</p>
                            @endforeach
                        </div>
                    @endif
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
