@php
    $registerPrograms = config('register.programs', []);
    $activeQuickProgram = old('register_program_id', session('register_quick_program'));
@endphp

<div class="modal fade bns-register-quick-modal bns-vision-modal" id="bnsRegisterQuickModal" tabindex="-1" aria-labelledby="bnsRegisterQuickModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bns-vision-modal__header">
                <div>
                    <span class="bns-vision-modal__eyebrow" id="bnsRegisterQuickEyebrow">Business Navachar School</span>
                    <h5 class="modal-title" id="bnsRegisterQuickModalLabel">Book Your Spot Now</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body">
                <p class="bns-register-quick-modal__intro" id="bnsRegisterQuickIntro">
                    Share your basic details — our Admission Team will contact you shortly.
                </p>

                @if($errors->any() && old('form_source') === 'register-quick-modal')
                    <div class="alert alert-danger" role="alert">
                        <strong>Please fix the following:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('contact.store') }}"
                    class="bns-register-quick-form"
                    id="bnsRegisterQuickForm"
                >
                    @csrf
                    <input type="hidden" name="form_source" value="register-quick-modal">
                    <input type="hidden" name="register_program_id" id="bnsRegisterQuickProgramId" value="{{ old('register_program_id', $activeQuickProgram) }}">
                    <input type="hidden" name="interested_program" id="bnsRegisterQuickInterestedProgram" value="{{ old('interested_program') }}">
                    <input type="hidden" name="category" id="bnsRegisterQuickCategory" value="{{ old('category') }}">
                    <input type="hidden" name="message" id="bnsRegisterQuickMessage" value="{{ old('message') }}">
                    <input type="hidden" name="agreed_info_correct" value="1">
                    <input type="hidden" name="agreed_to_contact" value="1">
                    <input type="hidden" name="agreed_privacy" value="1">

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" for="bnsRegisterQuickFullName">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="bnsRegisterQuickFullName" class="form-control" value="{{ old('full_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="bnsRegisterQuickMobile">Mobile <span class="text-danger">*</span></label>
                            <input
                                type="tel"
                                name="mobile"
                                id="bnsRegisterQuickMobile"
                                class="form-control js-intro-session-mobile"
                                value="{{ old('mobile') }}"
                                inputmode="numeric"
                                autocomplete="tel"
                                maxlength="10"
                                pattern="[6-9][0-9]{9}"
                                placeholder="10-digit mobile (without +91)"
                                required
                            >
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="bnsRegisterQuickEmail">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="bnsRegisterQuickEmail" class="form-control" value="{{ old('email') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="bnsRegisterQuickCity">City <span class="text-danger">*</span></label>
                            <input type="text" name="city" id="bnsRegisterQuickCity" class="form-control" value="{{ old('city') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="bnsRegisterQuickState">State <span class="text-danger">*</span></label>
                            <input type="text" name="state" id="bnsRegisterQuickState" class="form-control" value="{{ old('state', 'Maharashtra') }}" required>
                        </div>
                    </div>

                    <div class="bns-register-quick-form__actions">
                        <button type="submit" class="thm-btn">
                            {{ config('site.apply_cta_label', 'Book Your Spot Now') }} <span class="fas fa-arrow-right"></span>
                        </button>
                        <button type="button" class="bns-audience-btn bns-audience-btn--ghost" id="bnsRegisterQuickFullFormBtn">
                            Complete full admission form
                        </button>
                    </div>

                    <div class="bns-register-quick-form__combo" id="bnsRegisterQuickCombo" hidden>
                        <p class="bns-register-quick-form__combo-label">Or jump straight to the detailed application:</p>
                        <div class="bns-register-quick-form__combo-options" id="bnsRegisterQuickComboOptions"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="application/json" id="bnsRegisterProgramsData">@json($registerPrograms)</script>
