@php
    $register = $register ?? [];
    $programLabel = $register['program_label'] ?? 'Business Navachar School';
    $contactProgram = $register['contact_program'] ?? 'School Students Program';
    $contactCategory = $register['contact_category'] ?? 'Other';
    $registerProgramId = $register['register_program_id'] ?? '';
    $formId = $formId ?? 'bnsQuickRegisterForm';
    $formAction = $formAction ?? route('contact.store', [], false);
    $formSource = $formSource ?? 'register-quick-modal';
    $submitLabel = $submitLabel ?? config('site.apply_cta_label', 'Book Your Spot Now');
    $programs = config('register.quick_modal_programs', []);
    $selectedProgramId = old('register_program_id', $registerProgramId);
@endphp

<form
    method="POST"
    action="{{ $formAction }}"
    class="bns-intro-session-form bns-audience-intro-form bns-quick-register-form"
    id="{{ $formId }}"
>
    @csrf
    <input type="hidden" name="form_source" value="{{ $formSource }}">
    <input type="hidden" name="register_program_id" class="bns-quick-register-form__program-id" value="{{ $selectedProgramId }}">
    <input type="hidden" name="interested_program" class="bns-quick-register-form__interested-program" value="{{ old('interested_program', $contactProgram) }}">
    <input type="hidden" name="category" class="bns-quick-register-form__category" value="{{ old('category', $contactCategory) }}">
    <input type="hidden" name="message" class="bns-quick-register-form__message" value="{{ old('message') }}">
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
        <div class="col-12">
            <label class="form-label" for="{{ $formId }}_state">State <span class="text-danger">*</span></label>
            <input type="text" name="state" id="{{ $formId }}_state" class="form-control" value="{{ old('state', 'Maharashtra') }}" required>
        </div>
    </div>

    @if(!empty($programs))
        <div class="bns-quick-register-form__who">
            <span class="bns-quick-register-form__who-label">Who are you?</span>
            <div class="bns-quick-register-form__programs" role="radiogroup" aria-label="Choose your BNS program">
                @foreach($programs as $program)
                    <label class="bns-quick-register-form__program">
                        <input
                            type="radio"
                            name="register_program_choice"
                            value="{{ $program['id'] }}"
                            data-contact-program="{{ $program['contact_program'] }}"
                            data-contact-category="{{ $program['category'] }}"
                            data-program-title="{{ $program['title'] }}"
                            @checked($selectedProgramId === $program['id'])
                        >
                        <span>{{ $program['title'] }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    @endif

    <button type="submit" class="thm-btn bns-intro-session-form__btn">
        {{ $submitLabel }} <span class="fas fa-arrow-right"></span>
    </button>
</form>
