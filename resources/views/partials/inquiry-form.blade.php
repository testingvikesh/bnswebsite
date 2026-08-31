@php
    $inquiry = $inquiry ?? [];
    $programLabel = $inquiry['program_label'] ?? 'Business Navachar School';
    $contactProgram = $inquiry['contact_program'] ?? 'School Students Program';
    $contactCategory = $inquiry['contact_category'] ?? 'Other';
    $formId = $formId ?? 'bnsInquiryForm';
@endphp

<form
    method="POST"
    action="{{ route('contact.store', [], false) }}"
    class="bns-intro-session-form bns-audience-intro-form bns-inquiry-form"
    id="{{ $formId }}"
>
    @csrf
    <input type="hidden" name="form_source" value="inquiry-modal">
    <input type="hidden" name="interested_program" class="js-audience-interested-program" value="{{ $contactProgram }}">
    <input type="hidden" name="category" class="js-audience-category" value="{{ $contactCategory }}">
    <input type="hidden" name="agreed_info_correct" value="1">
    <input type="hidden" name="agreed_to_contact" value="1">
    <input type="hidden" name="agreed_privacy" value="1">

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_full_name">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="full_name" id="{{ $formId }}_full_name" class="form-control" value="{{ old('full_name') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_mobile">Mobile <span class="text-danger">*</span></label>
            <input type="text" name="mobile" id="{{ $formId }}_mobile" class="form-control" value="{{ old('mobile') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_email">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" id="{{ $formId }}_email" class="form-control" value="{{ old('email') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_city">City <span class="text-danger">*</span></label>
            <input type="text" name="city" id="{{ $formId }}_city" class="form-control" value="{{ old('city') }}" required>
        </div>
        <div class="col-12">
            <label class="form-label" for="{{ $formId }}_state">State <span class="text-danger">*</span></label>
            <input type="text" name="state" id="{{ $formId }}_state" class="form-control" value="{{ old('state', 'Maharashtra') }}" required>
        </div>
        <div class="col-12">
            <label class="form-label" for="{{ $formId }}_message">Your Inquiry <span class="text-danger">*</span></label>
            <textarea
                name="message"
                id="{{ $formId }}_message"
                class="form-control"
                rows="3"
                placeholder="Tell us what you would like to know about {{ $programLabel }}..."
                required
            >{{ old('message', old('form_source') === 'inquiry-modal' ? '' : '') }}</textarea>
        </div>
    </div>

    @include('partials.audience-who-are-you')

    <button type="submit" class="thm-btn bns-intro-session-form__btn">
        Submit Inquiry <span class="fas fa-paper-plane"></span>
    </button>
</form>
