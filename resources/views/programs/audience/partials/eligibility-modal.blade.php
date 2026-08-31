@php($content = $program['eligibility_content'] ?? [])
<div class="modal fade bns-eligibility-modal bns-vision-modal" id="bnsEligibilityModal" tabindex="-1" aria-labelledby="bnsEligibilityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow">{{ $content['subtitle'] ?? 'Business Navachar School (BNS)' }}</span>
                    <h5 class="modal-title" id="bnsEligibilityModalLabel">{{ $content['title'] ?? 'Eligibility Criteria' }}</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body">
                @if(!empty($content['programs']) || !empty($content['tracks']))
                    @include('admission.partials.eligibility-content', ['eligibility' => $content, 'hide_eligibility_cta' => true])
                @elseif(!empty($content['age_standards']) || !empty($content['criteria']))
                    @include('programs.audience.partials.eligibility-school-body', ['eligibility' => $content])
                @else
                    @include('admission.partials.eligibility-content', ['eligibility' => $content, 'hide_eligibility_cta' => true])
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
