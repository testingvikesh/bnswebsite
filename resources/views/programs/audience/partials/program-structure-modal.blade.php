@php
    $content = $program['program_structure'] ?? [];
    $modalId = $modalId ?? 'bnsProgramStructureModal';
    $modalLabelId = $modalId.'Label';
    $yearsAccordionId = $modalId.'YearsAccordion';
    $modulesAccordionId = $modalId.'ModulesAccordion';
@endphp
<div class="modal fade bns-program-structure-modal bns-vision-modal" id="{{ $modalId }}" tabindex="-1" aria-labelledby="{{ $modalLabelId }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">{{ $content['eyebrow'] ?? 'Business Navachar School (BNS)' }}</span>
                    <h5 class="modal-title" id="{{ $modalLabelId }}">{{ $content['title'] ?? 'Program Structure' }}</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body">
                @if(!empty($content['program_name']))
                    <p class="bns-program-structure-modal__tagline">{{ $content['program_name'] }}</p>
                @endif

                @if(!empty($content['subtitle']))
                    <p class="bns-five-year-modal__subtitle">{{ $content['subtitle'] }}</p>
                @endif

                @if(!empty($content['tagline']))
                    <p class="bns-program-structure-modal__tagline">{{ $content['tagline'] }}</p>
                @endif

                @if(!empty($content['stats']))
                    <div class="bns-program-structure-modal__stats bns-program-structure-modal__stats--header">
                        @foreach($content['stats'] as $stat)
                            <span class="bns-program-structure-modal__stat bns-program-structure-modal__stat--accent">
                                <i class="{{ $stat['icon'] ?? 'fas fa-check' }}" aria-hidden="true"></i>
                                {!! bns_rich_text($stat['text'] ?? '') !!}
                            </span>
                        @endforeach
                    </div>
                @endif

                @foreach($content['intro'] ?? [] as $paragraph)
                    <p class="bns-why-bns-modal__intro">{!! bns_rich_text($paragraph) !!}</p>
                @endforeach

                @if(!empty($content['plans_heading']) && !empty($content['plans']))
                    <h6 class="bns-vision-modal__subheading">{{ $content['plans_heading'] }}</h6>
                @endif

                @foreach($content['plans'] ?? [] as $plan)
                    <div class="bns-program-structure-modal__plan">
                        <h6 class="bns-vision-modal__subheading">{{ $plan['heading'] ?? $plan['title'] ?? '' }}</h6>
                        @if(!empty($plan['lead']))
                            <p class="bns-program-structure-modal__lead">{!! bns_rich_text($plan['lead']) !!}</p>
                        @endif
                        @if(!empty($plan['stats']))
                            <div class="bns-program-structure-modal__stats">
                                @foreach($plan['stats'] as $stat)
                                    <span class="bns-program-structure-modal__stat">
                                        <i class="{{ $stat['icon'] ?? 'fas fa-check' }}" aria-hidden="true"></i>
                                        {!! bns_rich_text($stat['text'] ?? '') !!}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        @if(!empty($plan['note']))
                            <p class="bns-program-structure-modal__note">{!! bns_rich_text($plan['note']) !!}</p>
                        @endif
                        @if(!empty($plan['includes_heading']))
                            <p class="bns-program-structure-modal__includes-heading">{{ $plan['includes_heading'] }}</p>
                        @endif
                        @if(!empty($plan['includes']))
                            <ul class="bns-program-structure-modal__includes list-unstyled">
                                @foreach($plan['includes'] as $item)
                                    <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach

                @if(!empty($content['five_year_plan']))
                    @php($fiveYear = $content['five_year_plan'])
                    <div class="bns-program-structure-modal__plan bns-program-structure-modal__plan--highlight">
                        <h6 class="bns-vision-modal__subheading">{{ $fiveYear['heading'] ?? '5-Year Learning Plan' }}</h6>
                        @if(!empty($fiveYear['lead']))
                            <p class="bns-program-structure-modal__lead">{!! bns_rich_text($fiveYear['lead']) !!}</p>
                        @endif
                        @if(!empty($fiveYear['stats']))
                            <div class="bns-program-structure-modal__stats">
                                @foreach($fiveYear['stats'] as $stat)
                                    @if(is_array($stat))
                                        <span class="bns-program-structure-modal__stat bns-program-structure-modal__stat--accent">
                                            <i class="{{ $stat['icon'] ?? 'fas fa-check' }}" aria-hidden="true"></i>
                                            {!! bns_rich_text($stat['text'] ?? '') !!}
                                        </span>
                                    @else
                                        <span class="bns-program-structure-modal__stat bns-program-structure-modal__stat--accent">
                                            <i class="fas fa-check" aria-hidden="true"></i>
                                            {!! bns_rich_text($stat) !!}
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                        @if(!empty($fiveYear['includes_heading']))
                            <p class="bns-program-structure-modal__includes-heading">{{ $fiveYear['includes_heading'] }}</p>
                        @endif
                        @if(!empty($fiveYear['includes']))
                            <ul class="bns-program-structure-modal__includes list-unstyled">
                                @foreach($fiveYear['includes'] as $item)
                                    <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                @endforeach
                            </ul>
                        @endif
                        @if(!empty($fiveYear['progress_heading']))
                            <p class="bns-program-structure-modal__includes-heading">{{ $fiveYear['progress_heading'] }}</p>
                        @endif
                        @if(!empty($fiveYear['years_summary']))
                            <div class="bns-program-structure-modal__years-summary">
                                @foreach($fiveYear['years_summary'] as $year)
                                    <div class="bns-program-structure-modal__year-summary">
                                        <span class="bns-five-year-modal__year-badge">Year {{ $year['year'] ?? '' }}</span>
                                        <div>
                                            <strong>{{ $year['name'] ?? '' }}</strong>
                                            <p>{!! bns_rich_text($year['summary'] ?? '') !!}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if(!empty($fiveYear['footer']))
                            <p class="bns-program-structure-modal__note">{!! bns_rich_text($fiveYear['footer']) !!}</p>
                        @endif
                    </div>
                @endif

                @if(!empty($content['hours_table']))
                    @php($table = $content['hours_table'])
                    <div class="bns-program-structure-modal__table-wrap">
                        <h6 class="bns-vision-modal__subheading">{{ $table['heading'] ?? 'Learning Hours Overview' }}</h6>
                        <div class="table-responsive">
                            <table class="table bns-program-structure-modal__table">
                                <thead>
                                    <tr>
                                        @foreach($table['columns'] ?? [] as $column)
                                            <th scope="col">{{ $column }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($table['rows'] ?? [] as $row)
                                        <tr>
                                            @foreach($row as $cell)
                                                <td>{!! bns_rich_text($cell) !!}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if(!empty($content['modules']))
                    <h6 class="bns-vision-modal__subheading bns-five-year-modal__years-heading">{{ $content['modules_heading'] ?? 'Syllabus Modules' }}</h6>
                    <div class="accordion bns-five-year-modal__accordion" id="{{ $modulesAccordionId }}">
                        @foreach($content['modules'] as $index => $module)
                            @php($collapseId = $modalId.'Module'.($index + 1))
                            <div class="accordion-item bns-five-year-modal__year">
                                <h2 class="accordion-header" id="heading{{ $collapseId }}">
                                    <button
                                        class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#{{ $collapseId }}"
                                        aria-expanded="false"
                                        aria-controls="{{ $collapseId }}"
                                    >
                                        <span class="bns-five-year-modal__year-badge">M{{ $index + 1 }}</span>
                                        <span class="bns-five-year-modal__year-name">{{ $module['heading'] ?? '' }}</span>
                                    </button>
                                </h2>
                                <div
                                    id="{{ $collapseId }}"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $collapseId }}"
                                    data-bs-parent="#{{ $modulesAccordionId }}"
                                >
                                    <div class="accordion-body">
                                        <ul class="bns-five-year-modal__skills list-unstyled">
                                            @foreach($module['topics'] ?? [] as $topic)
                                                <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($topic) !!}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(!empty($content['key_areas']))
                    <div class="bns-program-structure-modal__plan bns-program-structure-modal__plan--highlight">
                        <h6 class="bns-vision-modal__subheading">{{ $content['key_areas_heading'] ?? 'Key Learning Areas' }}</h6>
                        @if(!empty($content['key_areas_intro']))
                            <p class="bns-program-structure-modal__lead">{!! bns_rich_text($content['key_areas_intro']) !!}</p>
                        @endif
                        <ul class="bns-why-bns-modal__compact-list list-unstyled">
                            @foreach($content['key_areas'] as $area)
                                <li><i class="fas fa-chevron-right" aria-hidden="true"></i> {!! bns_rich_text($area) !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(!empty($content['years']))
                    <h6 class="bns-vision-modal__subheading bns-five-year-modal__years-heading">{{ $content['years_heading'] ?? 'Year-wise Curriculum Details' }}</h6>
                    <div class="accordion bns-five-year-modal__accordion" id="{{ $yearsAccordionId }}">
                        @foreach($content['years'] as $index => $year)
                            @php($collapseId = $modalId.'Year'.($year['year'] ?? ($index + 1)))
                            <div class="accordion-item bns-five-year-modal__year">
                                <h2 class="accordion-header" id="heading{{ $collapseId }}">
                                    <button
                                        class="accordion-button collapsed"
                                        type="button"
                                        data-bs-toggle="collapse"
                                        data-bs-target="#{{ $collapseId }}"
                                        aria-expanded="false"
                                        aria-controls="{{ $collapseId }}"
                                    >
                                        <span class="bns-five-year-modal__year-badge">Year {{ $year['year'] ?? ($index + 1) }}</span>
                                        <span class="bns-five-year-modal__year-name">{{ $year['name'] ?? '' }}</span>
                                    </button>
                                </h2>
                                <div
                                    id="{{ $collapseId }}"
                                    class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $collapseId }}"
                                    data-bs-parent="#{{ $yearsAccordionId }}"
                                >
                                    <div class="accordion-body">
                                        @if(!empty($year['theme']))
                                            <p class="bns-five-year-modal__theme"><strong>Theme:</strong> {!! bns_rich_text($year['theme']) !!}</p>
                                        @endif
                                        @if(!empty($year['objective']))
                                            <p class="bns-five-year-modal__objective"><strong>Objective:</strong> {!! bns_rich_text($year['objective']) !!}</p>
                                        @endif
                                        @if(!empty($year['modules']))
                                            @foreach($year['modules'] as $module)
                                                <div class="bns-program-structure-modal__year-module">
                                                    <h6 class="bns-five-year-modal__skills-title">{{ $module['heading'] ?? '' }}</h6>
                                                    <ul class="bns-five-year-modal__skills list-unstyled">
                                                        @foreach($module['topics'] ?? [] as $topic)
                                                            <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($topic) !!}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endforeach
                                        @elseif(!empty($year['skills']))
                                            <h6 class="bns-five-year-modal__skills-title">{{ $year['skills_title'] ?? 'Students Will Learn' }}</h6>
                                            <ul class="bns-five-year-modal__skills list-unstyled">
                                                @foreach($year['skills'] as $skill)
                                                    <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($skill) !!}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                        @if(!empty($year['practical_learning']))
                                            @php($practicalLearning = $year['practical_learning'])
                                            <div class="bns-program-structure-modal__year-module bns-program-structure-modal__year-module--highlight">
                                                <h6 class="bns-five-year-modal__skills-title">{{ $practicalLearning['heading'] ?? 'Practical Learning' }}</h6>
                                                <ul class="bns-five-year-modal__skills list-unstyled">
                                                    @foreach($practicalLearning['items'] ?? [] as $item)
                                                        <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <div class="bns-five-year-modal__meta">
                                            @if(!empty($year['hours']))
                                                <span><i class="fas fa-clock" aria-hidden="true"></i> Learning Hours: <strong>{{ $year['hours'] }}</strong></span>
                                            @endif
                                            @if(!empty($year['sessions']))
                                                <span><i class="fas fa-chalkboard-teacher" aria-hidden="true"></i> Sessions: <strong>{{ $year['sessions'] }}</strong></span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(!empty($content['practical_model']))
                    @php($model = $content['practical_model'])
                    <div class="bns-program-structure-modal__plan">
                        <h6 class="bns-vision-modal__subheading">{{ $model['heading'] ?? 'Practical Learning Model' }}</h6>
                        @if(!empty($model['intro']))
                            <p class="bns-why-bns-modal__intro">{!! bns_rich_text($model['intro']) !!}</p>
                        @endif
                        @if(!empty($model['includes_heading']))
                            <p class="bns-program-structure-modal__includes-heading">{{ $model['includes_heading'] }}</p>
                        @endif
                        @if(!empty($model['items']))
                            <ul class="bns-why-bns-modal__compact-list list-unstyled">
                                @foreach($model['items'] as $item)
                                    <li><i class="fas fa-chevron-right" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif

                @foreach(['certification', 'outcome'] as $structureSectionKey)
                    @if(!empty($content[$structureSectionKey]))
                        @php($structureSection = $content[$structureSectionKey])
                        <div class="bns-program-structure-modal__plan">
                            <h6 class="bns-vision-modal__subheading">{{ $structureSection['heading'] ?? '' }}</h6>
                            @if(!empty($structureSection['intro']))
                                <p class="bns-why-bns-modal__intro">{!! bns_rich_text($structureSection['intro']) !!}</p>
                            @endif
                            @if(!empty($structureSection['includes_heading']))
                                <p class="bns-program-structure-modal__includes-heading">{{ $structureSection['includes_heading'] }}</p>
                            @endif
                            @if(!empty($structureSection['items']))
                                <ul class="bns-program-structure-modal__includes list-unstyled">
                                    @foreach($structureSection['items'] as $item)
                                        <li><i class="fas fa-check" aria-hidden="true"></i> {!! bns_rich_text($item) !!}</li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endif
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
