@php($content = $program['certification'] ?? [])
<div class="modal fade bns-certification-modal bns-vision-modal" id="bnsCertificationModal" tabindex="-1" aria-labelledby="bnsCertificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">{{ $content['eyebrow'] ?? 'Business Navachar School (BNS)' }}</span>
                    <h5 class="modal-title" id="bnsCertificationModalLabel">{{ $content['title'] ?? 'Certification' }}</h5>
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

                @php($sampleCertificate = $content['sample_certificate'] ?? config('audience_program_iim_certificate', []))
                @if(!empty($sampleCertificate['enabled']) && !empty($sampleCertificate['image']))
                    <div class="bns-certification-modal__sample">
                        @if(!empty($sampleCertificate['heading']))
                            <h6 class="bns-vision-modal__subheading">{{ $sampleCertificate['heading'] }}</h6>
                        @endif
                        @if(!empty($sampleCertificate['intro']))
                            <p class="bns-why-bns-modal__intro">{!! bns_rich_text($sampleCertificate['intro']) !!}</p>
                        @endif
                        <figure class="bns-certification-modal__sample-figure">
                            <img
                                src="{{ bns_vasset($sampleCertificate['image']) }}"
                                alt="{{ $sampleCertificate['image_alt'] ?? 'Faculties of IIM Participation Certificate' }}"
                                class="bns-certification-modal__sample-img"
                                loading="lazy"
                                decoding="async"
                            >
                            @if(!empty($sampleCertificate['caption']))
                                <figcaption class="bns-certification-modal__sample-caption">{{ $sampleCertificate['caption'] }}</figcaption>
                            @endif
                        </figure>
                    </div>
                @endif

                @foreach($content['sections'] ?? [] as $section)
                    <div class="bns-certification-modal__section">
                        @if(!empty($section['heading']))
                            <h6 class="bns-vision-modal__subheading">{{ $section['heading'] }}</h6>
                        @endif

                        @if(!empty($section['intro']))
                            <p class="bns-why-bns-modal__intro">{!! bns_rich_text($section['intro']) !!}</p>
                        @endif

                        @if(($section['type'] ?? '') === 'graduation' && !empty($section['highlight_title']))
                            <div class="bns-certification-modal__graduation">
                                <i class="fas fa-award" aria-hidden="true"></i>
                                <strong>{{ $section['highlight_title'] }}</strong>
                            </div>
                        @endif

                        @if(!empty($section['list_heading']))
                            <p class="bns-certification-modal__list-heading">{{ $section['list_heading'] }}</p>
                        @endif

                        @if(($section['type'] ?? '') === 'year_certificates' && !empty($section['certificates']))
                            <ul class="bns-certification-modal__year-certs list-unstyled">
                                @foreach($section['certificates'] as $cert)
                                    <li>
                                        <span class="bns-certification-modal__trophy" aria-hidden="true">🏆</span>
                                        <div class="bns-certification-modal__year-cert-body">
                                            <span><strong>Year {{ $cert['year'] ?? '' }}</strong> – {{ $cert['name'] ?? '' }}</span>
                                            @if(!empty($cert['description']))
                                                <p class="bns-certification-modal__year-cert-desc">{!! bns_rich_text($cert['description']) !!}</p>
                                            @endif
                                            @if(!empty($cert['areas']))
                                                <ul class="bns-certification-modal__year-cert-areas list-unstyled">
                                                    @foreach($cert['areas'] as $area)
                                                        <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($area) !!}</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        @if(!empty($section['checklist']))
                            <ul class="bns-why-bns-modal__learn-list list-unstyled">
                                @foreach($section['checklist'] as $item)
                                    <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if(($section['type'] ?? '') === 'skills' && !empty($section['items']))
                            <ul class="bns-why-bns-modal__compact-list list-unstyled">
                                @foreach($section['items'] as $item)
                                    <li><i class="fas fa-chevron-right" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if(($section['type'] ?? '') === 'projects' && !empty($section['items']))
                            <ul class="bns-certification-modal__projects list-unstyled">
                                @foreach($section['items'] as $item)
                                    <li><i class="fas fa-thumbtack" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                @endforeach
                            </ul>
                        @endif

                        @if(!empty($section['footer']))
                            <p class="bns-certification-modal__footer">{!! bns_rich_text($section['footer']) !!}</p>
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
