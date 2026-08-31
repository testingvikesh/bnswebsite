@if(session('contact_success'))
    <div class="alert alert-success bns-contact-alert">{{ session('contact_success') }}</div>
@endif

@php
    $fc = $formConfig ?? config('contact.form');
    $categories = config('contact.form_categories');
    $defaultProgram = $fc['interested_programs'][0] ?? 'General Enquiry';
    $defaultCategory = $categories[0] ?? 'Other';
@endphp

<div class="bns-contact-card bns-contact-card--form bns-contact-form-wrap bns-contact-form-wrap--compact" id="contact-form">
    <div class="bns-contact-form__header">
        <span class="bns-contact-card__label">Contact Form</span>
        <h3 class="bns-contact-form__title">{{ $fc['title'] ?? 'Contact Form' }}</h3>
        @if(!empty($fc['subtitle']))<h4 class="bns-contact-form__subtitle">{{ $fc['subtitle'] }}</h4>@endif
        @if(!empty($fc['intro']))<p class="bns-contact-form__intro">{!! bns_rich_text($fc['intro']) !!}</p>@endif
    </div>

    <form method="POST" action="{{ route('contact.store') }}" class="bns-contact-form bns-contact-form--compact">
        @csrf
        <input type="hidden" name="form_source" value="contact-page">
        <input type="hidden" name="interested_program" class="js-audience-interested-program" value="{{ old('interested_program', $defaultProgram) }}">
        <input type="hidden" name="category" class="js-audience-category" value="{{ old('category', $defaultCategory) }}">
        <input type="hidden" name="agreed_info_correct" value="1">
        <input type="hidden" name="agreed_to_contact" value="1">
        <input type="hidden" name="agreed_privacy" value="1">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name') }}" required>
                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" required>
                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Email Address <span class="text-danger">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">City <span class="text-danger">*</span></label>
                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>
                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">State <span class="text-danger">*</span></label>
                <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}" required>
                @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        @include('partials.audience-who-are-you')

        <div class="bns-contact-form__message">
            <label class="form-label">Your Message</label>
            <textarea name="message" rows="3" class="form-control" placeholder="Tell us how we can help...">{{ old('message') }}</textarea>
        </div>

        <div class="bns-contact-form__actions">
            <button type="submit" class="bns-contact-btn bns-contact-btn--primary">Submit Enquiry</button>
        </div>
    </form>
</div>
