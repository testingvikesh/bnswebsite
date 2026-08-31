@php
    $intro = $intro ?? [];
    $programLabel = $intro['program_label'] ?? 'Business Navachar School';
    $contactProgram = $intro['contact_program'] ?? 'School Students Program';
    $contactCategory = $intro['contact_category'] ?? 'Other';
    $formConfig = $contactFormConfig ?? config('contact.form', []);
    $formId = $formId ?? 'bnsIntroSessionForm';
    $formSource = $formSource ?? 'intro-session-modal';
    $submitLabel = $submitLabel ?? 'Book Introduction Session';
@endphp

<form
    method="POST"
    action="{{ route('contact.store', [], false) }}"
    class="bns-intro-session-form bns-audience-intro-form"
    id="{{ $formId }}"
    data-check-mobile-url="{{ route('contact.check-mobile', [], false) }}"
    data-check-email-url="{{ route('contact.check-email', [], false) }}"
    data-csrf-url="{{ route('csrf-token', [], false) }}"
    data-hide-business-choices='@json(config('intro_session_form.hide_business_for_program_choices', []))'
    novalidate
>
    @csrf
    <input type="hidden" name="form_source" value="{{ $formSource }}">
    <input type="hidden" name="interested_program" class="js-audience-interested-program" value="{{ $contactProgram }}">
    <input type="hidden" name="category" class="js-audience-category" value="{{ $contactCategory }}">
    <input type="hidden" name="message" value="Introduction session admission request for {{ $programLabel }}.">
    <input type="hidden" name="agreed_info_correct" value="1">
    <input type="hidden" name="agreed_to_contact" value="1">
    <input type="hidden" name="agreed_privacy" value="1">

    @include('partials.intro-session-event-details', [
        'event' => bns_first_introduction_session(),
        'selectable' => true,
        'formId' => $formId,
    ])

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_full_name">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="full_name" id="{{ $formId }}_full_name" class="form-control" value="{{ old('full_name') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_mobile">Mobile <span class="text-danger">*</span></label>
            <input
                type="tel"
                name="mobile"
                id="{{ $formId }}_mobile"
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
            <label class="form-label" for="{{ $formId }}_email">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="{{ $formId }}_email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_city">City <span class="text-danger">*</span></label>
            <input type="text" name="city" id="{{ $formId }}_city" class="form-control" value="{{ old('city') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_state">State <span class="text-danger">*</span></label>
            <input type="text" name="state" id="{{ $formId }}_state" class="form-control" value="{{ old('state', 'Maharashtra') }}" required>
        </div>
    </div>

    @include('partials.audience-who-are-you')

    @include('partials.intro-session-business-fields', [
        'formId' => $formId,
        'required' => true,
    ])

    @include('partials.intro-session-hear-about', [
        'formId' => $formId,
    ])

    <button
        type="submit"
        class="thm-btn bns-intro-session-form__btn"
        data-default-label="{{ $submitLabel }}"
        data-loading-text="Submitting..."
    >
        <span class="bns-intro-session-form__btn-label" data-intro-btn-label>
            {{ $submitLabel }} <span class="fas fa-arrow-right"></span>
        </span>
        <span class="bns-intro-session-form__btn-loader" data-intro-btn-loader hidden>
            <span class="bns-intro-session-form__spinner" aria-hidden="true"></span>
            <span>Submitting...</span>
        </span>
    </button>
</form>

<style>
/* Intro form submit lock — kept inline so cache/theme CSS cannot hide the loader */
.bns-intro-session-form.is-submitting {
    pointer-events: none !important;
    opacity: 0.96;
}
.bns-intro-session-form__btn {
    position: relative;
    min-height: 54px;
}
.bns-intro-session-form__btn [data-intro-btn-loader] {
    display: none !important;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
}
.bns-intro-session-form.is-submitting .bns-intro-session-form__btn [data-intro-btn-label],
.bns-intro-session-form__btn.is-loading [data-intro-btn-label] {
    display: none !important;
}
.bns-intro-session-form.is-submitting .bns-intro-session-form__btn [data-intro-btn-loader],
.bns-intro-session-form__btn.is-loading [data-intro-btn-loader] {
    display: inline-flex !important;
}
.bns-intro-session-form__btn.is-loading,
.bns-intro-session-form.is-submitting .bns-intro-session-form__btn {
    cursor: wait !important;
    opacity: 0.9 !important;
}
.bns-intro-session-form__spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255, 255, 255, 0.35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: bns-intro-btn-spin 0.7s linear infinite;
    flex-shrink: 0;
}
@keyframes bns-intro-btn-spin {
    to { transform: rotate(360deg); }
}
</style>
