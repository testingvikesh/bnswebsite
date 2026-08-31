@php($content = $program['prosperity_mission'] ?? [])
<div class="modal fade bns-prosperity-modal bns-vision-modal" id="bnsProsperityMissionModal" tabindex="-1" aria-labelledby="bnsProsperityMissionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">{{ $content['eyebrow'] ?? ($program['title'] ?? 'Business Navachar School') }}</span>
                    <h5 class="modal-title" id="bnsProsperityMissionModalLabel">{{ $content['title'] ?? "India's Prosperity Mission" }}</h5>
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

                @if(!empty($content['points_heading']))
                    <h6 class="bns-vision-modal__subheading">{{ $content['points_heading'] }}</h6>
                @endif

                <ul class="bns-vision-modal__list list-unstyled">
                    @foreach($content['points'] ?? [] as $point)
                        <li class="bns-vision-modal__item{{ !empty($point['text']) ? ' bns-vision-modal__item--titled' : '' }}">
                            <span class="bns-vision-modal__icon" aria-hidden="true">
                                <i class="{{ $point['icon'] ?? 'fas fa-gem' }}"></i>
                            </span>
                            <div class="bns-vision-modal__text">
                                @if(!empty($point['title']))
                                    <strong class="bns-vision-modal__value-title">{{ $point['title'] }}</strong>
                                @endif
                                @if(!empty($point['text']))
                                    <span>{!! bns_rich_text($point['text']) !!}</span>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>

                @if(!empty($content['vision_heading']) || !empty($content['vision']) || !empty($content['vision_lines']))
                    <div class="bns-vision-modal__statement">
                        @if(!empty($content['vision_heading']))
                            <h6 class="bns-vision-modal__statement-title">{{ $content['vision_heading'] }}</h6>
                        @endif
                        @if(!empty($content['vision_lines']))
                            @foreach($content['vision_lines'] as $line)
                                <p class="bns-prosperity-modal__vision-line">{!! bns_rich_text($line) !!}</p>
                            @endforeach
                        @elseif(!empty($content['vision']))
                            <p>{!! bns_rich_text($content['vision']) !!}</p>
                        @endif
                    </div>
                @endif

                @if(!empty($content['commitment_heading']) || !empty($content['commitment_points']))
                    <div class="bns-why-bns-modal__learn">
                        @if(!empty($content['commitment_heading']))
                            <h6 class="bns-why-bns-modal__learn-title">{{ $content['commitment_heading'] }}</h6>
                        @endif
                        <ul class="bns-why-bns-modal__learn-list list-unstyled">
                            @foreach($content['commitment_points'] ?? [] as $line)
                                <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($line) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(!empty($content['become_heading']) || !empty($content['become_points']))
                    <div class="bns-why-bns-modal__learn">
                        @if(!empty($content['become_heading']))
                            <h6 class="bns-why-bns-modal__learn-title">{{ $content['become_heading'] }}</h6>
                        @endif
                        <ul class="bns-why-bns-modal__learn-list list-unstyled">
                            @foreach($content['become_points'] ?? [] as $line)
                                <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($line) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(!empty($content['belief_heading']) || !empty($content['belief_lines']) || !empty($content['belief_star_lines']))
                    <div class="bns-prosperity-modal__belief">
                        @if(!empty($content['belief_heading']))
                            <h6 class="bns-prosperity-modal__belief-title">{{ $content['belief_heading'] }}</h6>
                        @endif
                        @foreach($content['belief_star_lines'] ?? [] as $line)
                            <p class="bns-why-bns-modal__belief-line"><i class="fas fa-star" aria-hidden="true"></i> {!! bns_rich_text($line) !!}</p>
                        @endforeach
                        @foreach($content['belief_lines'] ?? [] as $line)
                            <p class="bns-prosperity-modal__belief-line">{!! bns_rich_text($line) !!}</p>
                        @endforeach
                    </div>
                @endif

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
