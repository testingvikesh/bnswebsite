@php
    $jp = old('form_type') === 'job-professional-school';
    $checkboxGroups = [
        'highest_qualification' => ['Std 12', 'Diploma', 'ITI', 'Graduate', 'Post Graduate', 'MBA', 'M.Com', 'M.Sc', 'MCA', 'M.Tech', 'Ph.D.', 'CA', 'CS', 'LLB', 'Other'],
        'marital_status' => ['Unmarried', 'Married', 'Divorced', 'Widow / Widower', 'Other'],
        'employment_status' => ['Private Job', 'Government Job', 'PSU Employee', 'Teacher', 'Professor', 'Banker', 'Engineer', 'Doctor', 'Healthcare Professional', 'Corporate Employee', 'Manager', 'Executive', 'Consultant', 'Sales Professional', 'HR Professional', 'Accountant', 'Freelancer', 'Other'],
        'total_experience' => ['Fresher', 'Less Than 1 Year', '1–3 Years', '3–5 Years', '5–10 Years', '10–20 Years', 'Above 20 Years'],
        'interests' => ['Leadership Development', 'Communication Skills', 'Public Speaking', 'Team Management', 'Artificial Intelligence (AI)', 'Business Management', 'Entrepreneurship', 'Sales Growth', 'Marketing Skills', 'Finance Management', 'Investment Knowledge', 'Personal Branding', 'Productivity Improvement', 'Career Growth', 'Time Management', 'Networking', 'Digital Skills', 'Startup Development', 'Multiple Income Sources', 'Wealth Creation'],
        'professional_goals' => ['Career Growth', 'Promotion', 'Leadership Position', 'Department Head Position', 'Become Senior Manager', 'Become Business Leader', 'Start Side Business', 'Become Entrepreneur', 'Build Personal Brand', 'Learn AI & Future Skills', 'Financial Freedom', 'Multiple Income Sources', 'Become Trainer / Coach', 'Become Consultant', 'Other'],
        'digital_access' => ['Smartphone', 'Laptop', 'Tablet', 'Internet Connection', 'Personal Computer'],
    ];
@endphp

<form method="POST" action="{{ route('register.job-professional.store') }}" enctype="multipart/form-data" class="bns-admission-form" id="jobProfessionalAdmissionForm">
    @csrf
    <input type="hidden" name="form_type" value="job-professional-school">

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '01', 'title' => 'Personal Information'])
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="jp_full_name">Full Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="jp_full_name" name="full_name" value="{{ $jp ? old('full_name') : '' }}" placeholder="Enter your full name" required>
                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <span class="bns-admission-form__label">Gender <span class="req">*</span></span>
                <div class="bns-admission-form__inline-options">
                    @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                        <label class="bns-chip">
                            <input type="radio" name="gender" value="{{ $value }}" {{ $jp && old('gender') === $value ? 'checked' : '' }} required>
                            <span class="bns-chip__text">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('gender')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="jp_date_of_birth">Date of Birth <span class="req">*</span></label>
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="jp_date_of_birth" name="date_of_birth" value="{{ $jp ? old('date_of_birth') : '' }}" required>
                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="jp_age">Age <span class="req">*</span></label>
                <input type="number" class="form-control @error('age') is-invalid @enderror" id="jp_age" name="age" value="{{ $jp ? old('age') : '' }}" min="1" max="120" placeholder="Years" required>
                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label">Photo Upload</label>
                <div class="bns-photo-upload">
                    <input type="file" class="bns-photo-upload__input @error('photo') is-invalid @enderror" id="jp_photo" name="photo" accept="image/*">
                    <div class="bns-photo-upload__box">
                        <span class="bns-photo-upload__icon"><i class="fas fa-camera"></i></span>
                        <span class="bns-photo-upload__text">
                            <strong>Upload your photo</strong>
                            JPG, PNG — max 2MB
                        </span>
                    </div>
                    <div class="bns-photo-upload__filename" id="jpPhotoFilename"></div>
                </div>
                @error('photo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="jp_mobile">Mobile Number <span class="req">*</span></label>
                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="jp_mobile" name="mobile" value="{{ $jp ? old('mobile') : '' }}" placeholder="+91 XXXXX XXXXX" required>
                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="jp_whatsapp">WhatsApp Number</label>
                <input type="text" class="form-control" id="jp_whatsapp" name="whatsapp" value="{{ $jp ? old('whatsapp') : '' }}" placeholder="+91 XXXXX XXXXX">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="jp_email">Email ID <span class="req">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="jp_email" name="email" value="{{ $jp ? old('email') : '' }}" placeholder="you@email.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '02', 'title' => 'Educational Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Highest Qualification</span>
            @include('register.partials.chip-group', ['name' => 'highest_qualification', 'options' => $checkboxGroups['highest_qualification'], 'type' => 'checkbox', 'oldPrefix' => 'job-professional-school'])
        </div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="jp_qualification_details">Qualification Details</label>
                <input type="text" class="form-control" id="jp_qualification_details" name="qualification_details" value="{{ $jp ? old('qualification_details') : '' }}" placeholder="Enter qualification details">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '03', 'title' => 'Family Information'])
        <div class="row g-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="jp_father_name">Father's Name</label>
                <input type="text" class="form-control" id="jp_father_name" name="father_name" value="{{ $jp ? old('father_name') : '' }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="spouse_name">Spouse Name (If Applicable)</label>
                <input type="text" class="form-control" id="spouse_name" name="spouse_name" value="{{ $jp ? old('spouse_name') : '' }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="jp_family_mobile">Family Mobile Number</label>
                <input type="text" class="form-control" id="jp_family_mobile" name="family_mobile" value="{{ $jp ? old('family_mobile') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '04', 'title' => 'Marital Status'])
        @include('register.partials.chip-group', ['name' => 'marital_status', 'options' => $checkboxGroups['marital_status'], 'type' => 'radio', 'oldPrefix' => 'job-professional-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '05', 'title' => 'Address Details'])
        <div class="row g-3">
            <div class="col-12">
                <label class="bns-admission-form__label" for="jp_current_address">Current Address</label>
                <textarea class="form-control" id="jp_current_address" name="current_address" rows="3" placeholder="House no., street, area">{{ $jp ? old('current_address') : '' }}</textarea>
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="jp_city">City</label>
                <input type="text" class="form-control" id="jp_city" name="city" value="{{ $jp ? old('city') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="jp_district">District</label>
                <input type="text" class="form-control" id="jp_district" name="district" value="{{ $jp ? old('district') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="jp_state">State</label>
                <input type="text" class="form-control" id="jp_state" name="state" value="{{ $jp ? old('state') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="jp_pin_code">PIN Code</label>
                <input type="text" class="form-control" id="jp_pin_code" name="pin_code" value="{{ $jp ? old('pin_code') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '06', 'title' => 'Employment Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Current Employment Status</span>
            @include('register.partials.chip-group', ['name' => 'employment_status', 'options' => $checkboxGroups['employment_status'], 'type' => 'checkbox', 'oldPrefix' => 'job-professional-school'])
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="jp_organization_name">Organization Name</label>
                <input type="text" class="form-control" id="jp_organization_name" name="organization_name" value="{{ $jp ? old('organization_name') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="jp_designation">Designation</label>
                <input type="text" class="form-control" id="jp_designation" name="designation" value="{{ $jp ? old('designation') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="jp_department">Department</label>
                <input type="text" class="form-control" id="jp_department" name="department" value="{{ $jp ? old('department') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="industry">Industry</label>
                <input type="text" class="form-control" id="industry" name="industry" value="{{ $jp ? old('industry') : '' }}">
            </div>
        </div>
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Total Experience</span>
            @include('register.partials.chip-group', ['name' => 'total_experience', 'options' => $checkboxGroups['total_experience'], 'type' => 'radio', 'oldPrefix' => 'job-professional-school'])
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '07', 'title' => 'Professional Interest Profile'])
        <span class="bns-admission-form__label">Which Areas Interest You Most?</span>
        @include('register.partials.chip-group', ['name' => 'interests', 'options' => $checkboxGroups['interests'], 'type' => 'checkbox', 'oldPrefix' => 'job-professional-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '08', 'title' => 'Professional Goals'])
        <span class="bns-admission-form__label">My Primary Goal Is:</span>
        @include('register.partials.chip-group', ['name' => 'professional_goals', 'options' => $checkboxGroups['professional_goals'], 'type' => 'checkbox', 'oldPrefix' => 'job-professional-school'])
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="jp_goal_other_specify">Specify (if Other)</label>
                <input type="text" class="form-control" id="jp_goal_other_specify" name="goal_other_specify" value="{{ $jp ? old('goal_other_specify') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '09', 'title' => 'Digital Access'])
        <span class="bns-admission-form__label">Do You Have Access To?</span>
        @include('register.partials.chip-group', ['name' => 'digital_access', 'options' => $checkboxGroups['digital_access'], 'type' => 'checkbox', 'oldPrefix' => 'job-professional-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '10', 'title' => 'Health Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Any Medical Condition? <span class="req">*</span></span>
            <div class="bns-admission-form__inline-options">
                @foreach(['no' => 'No', 'yes' => 'Yes'] as $value => $label)
                    <label class="bns-chip">
                        <input type="radio" name="medical_condition" value="{{ $value }}" {{ ($jp ? old('medical_condition', 'no') : 'no') === $value ? 'checked' : '' }} required>
                        <span class="bns-chip__text">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="jp_medical_details">If Yes, Please Mention</label>
                <input type="text" class="form-control" id="jp_medical_details" name="medical_details" value="{{ $jp ? old('medical_details') : '' }}" placeholder="Describe medical condition">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '11', 'title' => 'Consent & Declaration'])
        <p class="bns-admission-form__declaration">I hereby declare that all information provided by me is true and correct. I agree to follow all rules, attendance requirements, code of conduct and learning guidelines of Business Navachar School (BNS).</p>
        <label class="bns-chip bns-admission-form__check--consent">
            <input type="checkbox" name="consent_agreed" value="1" {{ $jp && old('consent_agreed') ? 'checked' : '' }} required>
            <span class="bns-chip__text">I Agree <span class="req">*</span></span>
        </label>
        @error('consent_agreed')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="jp_applicant_name">Applicant Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('applicant_name') is-invalid @enderror" id="jp_applicant_name" name="applicant_name" value="{{ $jp ? old('applicant_name') : '' }}" required>
                @error('applicant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="jp_signature">Signature <span class="req">*</span></label>
                <input type="text" class="form-control @error('signature') is-invalid @enderror" id="jp_signature" name="signature" value="{{ $jp ? old('signature') : '' }}" placeholder="Type your full name as signature" required>
                @error('signature')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="jp_application_date">Date <span class="req">*</span></label>
                <input type="date" class="form-control @error('application_date') is-invalid @enderror" id="jp_application_date" name="application_date" value="{{ $jp ? old('application_date', now()->toDateString()) : now()->toDateString() }}" required>
                @error('application_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    @include('register.partials.form-footer', [
        'submitId' => 'jobProfessionalApplySubmit',
        'motto' => 'Transforming Employees into Leaders, Professionals into Wealth Creators, and Careers into Opportunities',
    ])
</form>
