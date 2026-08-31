@php
    $ww = old('form_type') === 'working-women-school';
    $checkboxGroups = [
        'highest_qualification' => ['Std 12', 'Diploma', 'Graduate', 'Post Graduate', 'MBA', 'M.Com', 'M.Sc', 'MCA', 'M.Tech', 'Ph.D.', 'CA', 'CS', 'Other'],
        'marital_status' => ['Unmarried', 'Married', 'Widow', 'Divorced', 'Other'],
        'employment_status' => ['Private Job', 'Government Job', 'Teacher', 'Professor', 'Banker', 'Healthcare Professional', 'Corporate Employee', 'Manager', 'Executive', 'Consultant', 'Freelancer', 'Other'],
        'total_experience' => ['Fresher', 'Less Than 1 Year', '1–3 Years', '3–5 Years', '5–10 Years', 'Above 10 Years'],
        'interests' => ['Leadership Development', 'Communication Skills', 'Public Speaking', 'Team Management', 'Human Resource Management', 'Artificial Intelligence (AI)', 'Digital Skills', 'Finance Management', 'Investment Knowledge', 'Personal Branding', 'Corporate Growth', 'Entrepreneurship', 'Business Management', 'Marketing', 'Sales', 'Networking', 'Productivity Improvement', 'Career Growth', 'Women Leadership', 'Startup Development'],
        'career_goals' => ['Promotion', 'Leadership Position', 'Department Head Position', 'Start Side Business', 'Become Entrepreneur', 'Career Change', 'Personal Branding', 'Financial Freedom', 'Multiple Income Sources', 'Become Trainer / Coach', 'Become Consultant', 'Other'],
        'digital_access' => ['Smartphone', 'Laptop', 'Tablet', 'Internet Connection', 'Personal Computer'],
    ];
@endphp

<form method="POST" action="{{ route('register.working-women.store') }}" enctype="multipart/form-data" class="bns-admission-form" id="workingWomenAdmissionForm">
    @csrf
    <input type="hidden" name="form_type" value="working-women-school">
    <input type="hidden" name="gender" value="female">

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '01', 'title' => 'Personal Information'])
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="ww_full_name">Full Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="ww_full_name" name="full_name" value="{{ $ww ? old('full_name') : '' }}" placeholder="Enter your full name" required>
                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <span class="bns-admission-form__label">Gender</span>
                <div class="bns-admission-form__inline-options">
                    <label class="bns-chip">
                        <input type="radio" checked disabled>
                        <span class="bns-chip__text">Female</span>
                    </label>
                </div>
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_date_of_birth">Date of Birth <span class="req">*</span></label>
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="ww_date_of_birth" name="date_of_birth" value="{{ $ww ? old('date_of_birth') : '' }}" required>
                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_age">Age <span class="req">*</span></label>
                <input type="number" class="form-control @error('age') is-invalid @enderror" id="ww_age" name="age" value="{{ $ww ? old('age') : '' }}" min="1" max="120" placeholder="Years" required>
                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label">Photo Upload</label>
                <div class="bns-photo-upload">
                    <input type="file" class="bns-photo-upload__input @error('photo') is-invalid @enderror" id="ww_photo" name="photo" accept="image/*">
                    <div class="bns-photo-upload__box">
                        <span class="bns-photo-upload__icon"><i class="fas fa-camera"></i></span>
                        <span class="bns-photo-upload__text">
                            <strong>Upload your photo</strong>
                            JPG, PNG — max 2MB
                        </span>
                    </div>
                    <div class="bns-photo-upload__filename" id="wwPhotoFilename"></div>
                </div>
                @error('photo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_mobile">Mobile Number <span class="req">*</span></label>
                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="ww_mobile" name="mobile" value="{{ $ww ? old('mobile') : '' }}" placeholder="+91 XXXXX XXXXX" required>
                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_whatsapp">WhatsApp Number</label>
                <input type="text" class="form-control" id="ww_whatsapp" name="whatsapp" value="{{ $ww ? old('whatsapp') : '' }}" placeholder="+91 XXXXX XXXXX">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_email">Email ID <span class="req">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="ww_email" name="email" value="{{ $ww ? old('email') : '' }}" placeholder="you@email.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '02', 'title' => 'Educational Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Highest Qualification</span>
            @include('register.partials.chip-group', ['name' => 'highest_qualification', 'options' => $checkboxGroups['highest_qualification'], 'type' => 'checkbox', 'oldPrefix' => 'working-women-school'])
        </div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="ww_qualification_details">Qualification Details</label>
                <input type="text" class="form-control" id="ww_qualification_details" name="qualification_details" value="{{ $ww ? old('qualification_details') : '' }}" placeholder="Enter qualification details">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '03', 'title' => 'Family Information'])
        <div class="row g-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_father_name">Father's Name</label>
                <input type="text" class="form-control" id="ww_father_name" name="father_name" value="{{ $ww ? old('father_name') : '' }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_husband_name">Husband's Name (If Applicable)</label>
                <input type="text" class="form-control" id="ww_husband_name" name="husband_name" value="{{ $ww ? old('husband_name') : '' }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_family_mobile">Family Mobile Number</label>
                <input type="text" class="form-control" id="ww_family_mobile" name="family_mobile" value="{{ $ww ? old('family_mobile') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '04', 'title' => 'Marital Status'])
        @include('register.partials.chip-group', ['name' => 'marital_status', 'options' => $checkboxGroups['marital_status'], 'type' => 'radio', 'oldPrefix' => 'working-women-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '05', 'title' => 'Address Details'])
        <div class="row g-3">
            <div class="col-12">
                <label class="bns-admission-form__label" for="ww_current_address">Current Address</label>
                <textarea class="form-control" id="ww_current_address" name="current_address" rows="3" placeholder="House no., street, area">{{ $ww ? old('current_address') : '' }}</textarea>
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="ww_city">City</label>
                <input type="text" class="form-control" id="ww_city" name="city" value="{{ $ww ? old('city') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="ww_district">District</label>
                <input type="text" class="form-control" id="ww_district" name="district" value="{{ $ww ? old('district') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="ww_state">State</label>
                <input type="text" class="form-control" id="ww_state" name="state" value="{{ $ww ? old('state') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="ww_pin_code">PIN Code</label>
                <input type="text" class="form-control" id="ww_pin_code" name="pin_code" value="{{ $ww ? old('pin_code') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '06', 'title' => 'Employment Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Current Employment Status</span>
            @include('register.partials.chip-group', ['name' => 'employment_status', 'options' => $checkboxGroups['employment_status'], 'type' => 'checkbox', 'oldPrefix' => 'working-women-school'])
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="organization_name">Organization Name</label>
                <input type="text" class="form-control" id="organization_name" name="organization_name" value="{{ $ww ? old('organization_name') : '' }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="designation">Designation</label>
                <input type="text" class="form-control" id="designation" name="designation" value="{{ $ww ? old('designation') : '' }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="department">Department</label>
                <input type="text" class="form-control" id="department" name="department" value="{{ $ww ? old('department') : '' }}">
            </div>
        </div>
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Total Experience</span>
            @include('register.partials.chip-group', ['name' => 'total_experience', 'options' => $checkboxGroups['total_experience'], 'type' => 'radio', 'oldPrefix' => 'working-women-school'])
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '07', 'title' => 'Professional Interest Profile'])
        <span class="bns-admission-form__label">Which Areas Interest You Most?</span>
        @include('register.partials.chip-group', ['name' => 'interests', 'options' => $checkboxGroups['interests'], 'type' => 'checkbox', 'oldPrefix' => 'working-women-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '08', 'title' => 'Career Goals'])
        <span class="bns-admission-form__label">My Primary Goal Is:</span>
        @include('register.partials.chip-group', ['name' => 'career_goals', 'options' => $checkboxGroups['career_goals'], 'type' => 'checkbox', 'oldPrefix' => 'working-women-school'])
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="ww_goal_other_specify">Specify (if Other)</label>
                <input type="text" class="form-control" id="ww_goal_other_specify" name="goal_other_specify" value="{{ $ww ? old('goal_other_specify') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '09', 'title' => 'Digital Access'])
        <span class="bns-admission-form__label">Do You Have Access To?</span>
        @include('register.partials.chip-group', ['name' => 'digital_access', 'options' => $checkboxGroups['digital_access'], 'type' => 'checkbox', 'oldPrefix' => 'working-women-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '10', 'title' => 'Health Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Any Medical Condition? <span class="req">*</span></span>
            <div class="bns-admission-form__inline-options">
                @foreach(['no' => 'No', 'yes' => 'Yes'] as $value => $label)
                    <label class="bns-chip">
                        <input type="radio" name="medical_condition" value="{{ $value }}" {{ ($ww ? old('medical_condition', 'no') : 'no') === $value ? 'checked' : '' }} required>
                        <span class="bns-chip__text">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="ww_medical_details">If Yes, Please Mention</label>
                <input type="text" class="form-control" id="ww_medical_details" name="medical_details" value="{{ $ww ? old('medical_details') : '' }}" placeholder="Describe medical condition">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '11', 'title' => 'Consent & Declaration'])
        <p class="bns-admission-form__declaration">I hereby declare that all information provided by me is true and correct. I agree to follow all rules, attendance requirements, code of conduct and learning guidelines of Business Navachar School (BNS).</p>
        <label class="bns-chip bns-admission-form__check--consent">
            <input type="checkbox" name="consent_agreed" value="1" {{ $ww && old('consent_agreed') ? 'checked' : '' }} required>
            <span class="bns-chip__text">I Agree <span class="req">*</span></span>
        </label>
        @error('consent_agreed')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_applicant_name">Applicant Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('applicant_name') is-invalid @enderror" id="ww_applicant_name" name="applicant_name" value="{{ $ww ? old('applicant_name') : '' }}" required>
                @error('applicant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_signature">Signature <span class="req">*</span></label>
                <input type="text" class="form-control @error('signature') is-invalid @enderror" id="ww_signature" name="signature" value="{{ $ww ? old('signature') : '' }}" placeholder="Type your full name as signature" required>
                @error('signature')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="ww_application_date">Date <span class="req">*</span></label>
                <input type="date" class="form-control @error('application_date') is-invalid @enderror" id="ww_application_date" name="application_date" value="{{ $ww ? old('application_date', now()->toDateString()) : now()->toDateString() }}" required>
                @error('application_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    @include('register.partials.form-footer', [
        'submitId' => 'workingWomenApplySubmit',
        'motto' => 'Empowering Working Women to Become Leaders, Professionals & Entrepreneurs',
    ])
</form>
