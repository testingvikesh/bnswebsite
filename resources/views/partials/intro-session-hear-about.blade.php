@php
    $formId = $formId ?? 'bnsIntroSessionForm';
    $hearAboutOptions = config('intro_session_form.hear_about_options', config('contact.form.hear_about', []));
    $hearAboutSelected = old('hear_about', '');
    $hearAboutOther = old('hear_about_other', '');
    $hearAboutShowOther = $hearAboutSelected === 'Other';
@endphp

<div class="row g-3 mt-1">
    <div class="col-12">
        <label class="form-label" for="{{ $formId }}_hear_about">
            How did you hear about BNS? <span class="text-danger">*</span>
        </label>
            <select
                name="hear_about"
                id="{{ $formId }}_hear_about"
                class="form-select ignore js-intro-session-category-select @error('hear_about') is-invalid @enderror @error('hear_about_other') is-invalid @enderror"
                data-other-target="{{ $formId }}_hear_about_other_wrap"
                required
            >
            <option value="" disabled @selected($hearAboutSelected === '')>Select an option</option>
            @foreach($hearAboutOptions as $option)
                <option value="{{ $option }}" @selected($hearAboutSelected === $option)>
                    {{ $option === 'Other' ? 'Other (Please Specify)' : $option }}
                </option>
            @endforeach
        </select>
        @error('hear_about')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        @error('hear_about_other')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

        <div
            id="{{ $formId }}_hear_about_other_wrap"
            class="bns-intro-session-form__other-wrap js-intro-session-other-wrap{{ $hearAboutShowOther ? ' is-visible' : '' }}"
        >
            <label class="form-label" for="{{ $formId }}_hear_about_other">
                Please specify <span class="text-danger">*</span>
            </label>
            <input
                type="text"
                name="hear_about_other"
                id="{{ $formId }}_hear_about_other"
                class="form-control @error('hear_about_other') is-invalid @enderror"
                value="{{ $hearAboutOther }}"
                placeholder="Please specify how you heard about BNS"
                @if($hearAboutShowOther) required @endif
            >
        </div>
    </div>
</div>
