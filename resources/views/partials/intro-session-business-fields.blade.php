@php
    $formId = $formId ?? 'bnsIntroSessionForm';
    $required = $required ?? true;
    $professionCategories = config('intro_session_form.business_profession_categories', []);
    $industryCategories = config('intro_session_form.business_industry_categories', []);
    $requiredMark = $required ? ' <span class="text-danger">*</span>' : '';
    $requiredAttr = $required ? 'required' : '';
    $professionSelected = old('business_profession_category', '');
    $professionOther = old('business_profession_category_other', '');
    $businessSelected = old('business_category', '');
    $businessOther = old('business_category_other', '');
    $professionShowOther = $professionSelected === 'Other';
    $businessShowOther = $businessSelected === 'Other';
@endphp

<div class="bns-intro-session-form__business js-intro-session-business-section">
    <span class="bns-quick-register-form__who-label">About You &amp; Your Business</span>

    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_business_profession_category">
                Business / Profession Category{!! $requiredMark !!}
            </label>
            <select
                name="business_profession_category"
                id="{{ $formId }}_business_profession_category"
                class="form-select ignore js-intro-session-category-select @error('business_profession_category') is-invalid @enderror @error('business_profession_category_other') is-invalid @enderror"
                data-other-target="{{ $formId }}_business_profession_category_other_wrap"
                {{ $requiredAttr }}
            >
                <option value="" disabled @selected($professionSelected === '')>What do you do currently?</option>
                @foreach($professionCategories as $option)
                    <option value="{{ $option }}" @selected($professionSelected === $option)>{{ $option }}</option>
                @endforeach
            </select>
            @error('business_profession_category')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            @error('business_profession_category_other')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

            <div
                id="{{ $formId }}_business_profession_category_other_wrap"
                class="bns-intro-session-form__other-wrap js-intro-session-other-wrap{{ $professionShowOther ? ' is-visible' : '' }}"
            >
                <label class="form-label" for="{{ $formId }}_business_profession_category_other">
                    Please specify{!! $requiredMark !!}
                </label>
                <input
                    type="text"
                    name="business_profession_category_other"
                    id="{{ $formId }}_business_profession_category_other"
                    class="form-control @error('business_profession_category_other') is-invalid @enderror"
                    value="{{ $professionOther }}"
                    placeholder="Enter your business / profession category"
                    @if($professionShowOther && $required) required @endif
                >
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_organization_name">
                Business / Company Name{!! $requiredMark !!}
            </label>
            <input
                type="text"
                name="organization_name"
                id="{{ $formId }}_organization_name"
                class="form-control @error('organization_name') is-invalid @enderror"
                value="{{ old('organization_name') }}"
                placeholder="What is your Business / Company / Organization Name?"
                {{ $requiredAttr }}
            >
            @error('organization_name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label" for="{{ $formId }}_business_category">
                Business Category{!! $requiredMark !!}
            </label>
            <select
                name="business_category"
                id="{{ $formId }}_business_category"
                class="form-select ignore js-intro-session-category-select @error('business_category') is-invalid @enderror @error('business_category_other') is-invalid @enderror"
                data-other-target="{{ $formId }}_business_category_other_wrap"
                {{ $requiredAttr }}
            >
                <option value="" disabled @selected($businessSelected === '')>Which industry is your business in?</option>
                @foreach($industryCategories as $option)
                    <option value="{{ $option }}" @selected($businessSelected === $option)>{{ $option }}</option>
                @endforeach
            </select>
            @error('business_category')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            @error('business_category_other')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror

            <div
                id="{{ $formId }}_business_category_other_wrap"
                class="bns-intro-session-form__other-wrap js-intro-session-other-wrap{{ $businessShowOther ? ' is-visible' : '' }}"
            >
                <label class="form-label" for="{{ $formId }}_business_category_other">
                    Please specify industry{!! $requiredMark !!}
                </label>
                <input
                    type="text"
                    name="business_category_other"
                    id="{{ $formId }}_business_category_other"
                    class="form-control @error('business_category_other') is-invalid @enderror"
                    value="{{ $businessOther }}"
                    placeholder="Enter your business industry / category"
                    @if($businessShowOther && $required) required @endif
                >
            </div>
        </div>

        <div class="col-12">
            <label class="form-label" for="{{ $formId }}_products_services">
                Product / Service{!! $requiredMark !!}
            </label>
            <textarea
                name="products_services"
                id="{{ $formId }}_products_services"
                class="form-control bns-intro-session-form__textarea @error('products_services') is-invalid @enderror"
                rows="4"
                placeholder="What products or services do you offer? (Please describe in 2–3 sentences.)"
                {{ $requiredAttr }}
            >{{ old('products_services') }}</textarea>
            @error('products_services')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
    </div>
</div>
