@php
    $content = $program[$type] ?? [];
    $modalId = $modalId ?? ('bns' . \Illuminate\Support\Str::studly($type) . 'Modal');
    $eyebrow = $content['eyebrow'] ?? ($program['title'] ?? ($eyebrow ?? 'Business Navachar School'));
    $modalDialogClass = $modalDialogClass ?? 'modal-lg';
@endphp
<div class="modal fade bns-vision-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $modalDialogClass }} modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">{{ $eyebrow }}</span>
                    <h5 class="modal-title" id="{{ $modalId }}Label">{{ $content['title'] ?? ucfirst(str_replace('_', ' ', $type)) }}</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body">
                @if(!empty($content['tracks']))
                    @foreach($content['tracks'] as $trackIndex => $track)
                        <div class="bns-vision-modal__track{{ $trackIndex > 0 ? ' bns-vision-modal__track--divider' : '' }}">
                            @if(!empty($track['school_name']))
                                <h6 class="bns-vision-modal__track-title">{{ $track['school_name'] }}</h6>
                            @endif
                            @if(!empty($track['subtitle']))
                                <p class="bns-why-bns-modal__subtitle">{{ $track['subtitle'] }}</p>
                            @endif
                            @if(!empty($track['tagline']))
                                <p class="bns-program-structure-modal__tagline">{{ $track['tagline'] }}</p>
                            @endif
                            @foreach((array) ($track['intro'] ?? []) as $paragraph)
                                <p class="bns-vision-modal__intro">{!! bns_rich_text($paragraph) !!}</p>
                            @endforeach
                            @if(!empty($track['points_heading']))
                                <h6 class="bns-vision-modal__subheading">{{ $track['points_heading'] }}</h6>
                            @endif
                            <ul class="bns-vision-modal__list list-unstyled">
                                @foreach($track['points'] ?? [] as $point)
                                    @php
                                        $icon = is_array($point) ? ($point['icon'] ?? 'fas fa-check-circle') : 'fas fa-check-circle';
                                        $text = is_array($point) ? ($point['text'] ?? '') : $point;
                                        $pointTitle = is_array($point) ? ($point['title'] ?? null) : null;
                                    @endphp
                                    <li class="bns-vision-modal__item{{ $pointTitle ? ' bns-vision-modal__item--titled' : '' }}">
                                        <span class="bns-vision-modal__icon" aria-hidden="true">
                                            <i class="{{ $icon }}"></i>
                                        </span>
                                        <div class="bns-vision-modal__text">
                                            @if($pointTitle)
                                                <strong class="bns-vision-modal__value-title">{{ $pointTitle }}</strong>
                                            @endif
                                            <span>{!! bns_rich_text($text) !!}</span>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            @if(!empty($track['statement']))
                                <div class="bns-vision-modal__statement">
                                    @if(!empty($track['statement_heading']))
                                        <h6 class="bns-vision-modal__statement-title">{{ $track['statement_heading'] }}</h6>
                                    @endif
                                    @foreach((array) $track['statement'] as $paragraph)
                                        <p>{!! bns_rich_text($paragraph) !!}</p>
                                    @endforeach
                                </div>
                            @endif
                            @if(!empty($track['taglines']))
                                <div class="bns-vision-modal__taglines">
                                    @foreach($track['taglines'] as $tagline)
                                        <p class="bns-vision-modal__tagline">{!! bns_rich_text($tagline) !!}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @else
                @if(!empty($content['subtitle']))
                    <p class="bns-why-bns-modal__subtitle">{{ $content['subtitle'] }}</p>
                @endif
                @foreach((array) ($content['intro'] ?? []) as $paragraph)
                    <p class="bns-vision-modal__intro">{!! bns_rich_text($paragraph) !!}</p>
                @endforeach
                @if(!empty($content['points_heading']))
                    <h6 class="bns-vision-modal__subheading">{{ $content['points_heading'] }}</h6>
                @endif
                <ul class="bns-vision-modal__list list-unstyled">
                    @foreach($content['points'] ?? [] as $point)
                        @php
                            $icon = is_array($point) ? ($point['icon'] ?? 'fas fa-check-circle') : 'fas fa-check-circle';
                            $text = is_array($point) ? ($point['text'] ?? '') : $point;
                            $pointTitle = is_array($point) ? ($point['title'] ?? null) : null;
                        @endphp
                        <li class="bns-vision-modal__item{{ $pointTitle ? ' bns-vision-modal__item--titled' : '' }}">
                            <span class="bns-vision-modal__icon" aria-hidden="true">
                                <i class="{{ $icon }}"></i>
                            </span>
                            <div class="bns-vision-modal__text">
                                @if($pointTitle)
                                    <strong class="bns-vision-modal__value-title">{{ $pointTitle }}</strong>
                                @endif
                                <span>{!! bns_rich_text($text) !!}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @if(!empty($content['dream_heading']) || !empty($content['dream_lines']) || !empty($content['journey_years']))
                    <div class="bns-vision-modal__dream">
                        @if(!empty($content['dream_heading']))
                            <h6 class="bns-vision-modal__subheading">{{ $content['dream_heading'] }}</h6>
                        @endif
                        @if(!empty($content['journey_years']))
                            @foreach($content['journey_years'] as $year)
                                <div class="bns-vision-modal__journey-year">
                                    <p class="bns-vision-modal__dream-line"><strong>{{ $year['title'] ?? '' }}</strong></p>
                                    @if(!empty($year['subtitle']))
                                        <p class="bns-vision-modal__journey-subtitle">{{ $year['subtitle'] }}</p>
                                    @endif
                                    @if(!empty($year['intro']))
                                        <p class="bns-vision-modal__dream-line">{!! bns_rich_text($year['intro']) !!}</p>
                                    @endif
                                    @if(!empty($year['focus_areas']))
                                        <h6 class="bns-vision-modal__focus-title">{{ $year['focus_heading'] ?? 'Focus Areas' }}</h6>
                                        <ul class="bns-vision-modal__focus-list list-unstyled">
                                            @foreach($year['focus_areas'] as $area)
                                                <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($area) !!}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            @foreach($content['dream_lines'] ?? [] as $line)
                                <p class="bns-vision-modal__dream-line">{!! bns_rich_text($line) !!}</p>
                            @endforeach
                        @endif
                    </div>
                @endif
                @if(!empty($content['structure_block']))
                    <div class="bns-vision-modal__structure">
                        <h6 class="bns-vision-modal__subheading">{{ $content['structure_block']['heading'] ?? 'Program Structure' }}</h6>
                        <ul class="bns-vision-modal__structure-list list-unstyled">
                            @foreach($content['structure_block']['items'] ?? [] as $item)
                                <li><i class="fas fa-chevron-right" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(!empty($content['promise_block']))
                    <div class="bns-vision-modal__structure bns-vision-modal__promise">
                        <h6 class="bns-vision-modal__subheading">{{ $content['promise_block']['heading'] ?? 'Our Promise' }}</h6>
                        @if(!empty($content['promise_block']['intro']))
                            <p class="bns-vision-modal__promise-intro">{!! bns_rich_text($content['promise_block']['intro']) !!}</p>
                        @endif
                        <ul class="bns-vision-modal__focus-list list-unstyled">
                            @foreach($content['promise_block']['items'] ?? [] as $item)
                                <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(!empty($content['statement']))
                    <div class="bns-vision-modal__statement">
                        @if(!empty($content['statement_heading']))
                            <h6 class="bns-vision-modal__statement-title">{{ $content['statement_heading'] }}</h6>
                        @endif
                        @foreach((array) $content['statement'] as $paragraph)
                            <p>{!! bns_rich_text($paragraph) !!}</p>
                        @endforeach
                    </div>
                @endif
                @if(!empty($content['commitment_block']))
                    <div class="bns-vision-modal__structure bns-vision-modal__commitment">
                        <h6 class="bns-vision-modal__subheading">{{ $content['commitment_block']['heading'] ?? 'Our Commitment' }}</h6>
                        @foreach($content['commitment_block']['items'] ?? [] as $line)
                            <p class="bns-vision-modal__dream-line">{!! bns_rich_text($line) !!}</p>
                        @endforeach
                    </div>
                @endif
                @if(!empty($content['motto']))
                    <div class="bns-vision-modal__motto">
                        @if(!empty($content['motto_heading']))
                            <h6 class="bns-vision-modal__motto-title">{{ $content['motto_heading'] }}</h6>
                        @endif
                        <p class="bns-vision-modal__motto-text">{!! bns_rich_text($content['motto']) !!}</p>
                    </div>
                @endif
                @if(!empty($content['taglines']))
                    <div class="bns-vision-modal__taglines">
                        @foreach($content['taglines'] as $tagline)
                            <p class="bns-vision-modal__tagline">{!! bns_rich_text($tagline) !!}</p>
                        @endforeach
                    </div>
                @endif
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
