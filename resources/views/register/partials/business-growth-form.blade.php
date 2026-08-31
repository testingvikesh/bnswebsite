@php
    $bg = old('form_type') === 'business-growth-school';
    $checkboxGroups = [
        'highest_qualification' => ['Std 10', 'Std 12', 'Diploma', 'ITI', 'Graduate', 'Post Graduate', 'MBA', 'M.Com', 'M.Sc', 'MCA', 'M.Tech', 'Ph.D.', 'CA', 'CS', 'LLB', 'Other'],
        'business_type' => ['Proprietorship', 'Partnership', 'LLP', 'Private Limited Company', 'Public Limited Company', 'Section 8 Company', 'Trust', 'Cooperative Society', 'Other'],
        'business_category' => ['Manufacturing', 'Trading', 'Retail', 'Wholesale', 'Service Industry', 'Education', 'Healthcare', 'Agriculture', 'Construction', 'Real Estate', 'Hospitality', 'Tourism', 'IT & Technology', 'Marketing Agency', 'Consultancy', 'Startup', 'Other'],
        'current_status' => ['Startup Stage', 'Growing Business', 'Established Business', 'Family Business', 'Professional Practice', 'Multiple Businesses'],
        'employee_count' => ['1–5', '6–10', '11–25', '26–50', '51–100', 'Above 100'],
        'interests' => ['Business Growth', 'Business Expansion', 'Sales Growth', 'Marketing', 'Branding', 'Team Building', 'Leadership', 'Human Resource Management', 'Finance Management', 'Cash Flow Management', 'Business Systems', 'Artificial Intelligence (AI)', 'Technology Adoption', 'Automation', 'Franchise Development', 'Digital Marketing', 'Social Media Growth', 'Export Business', 'Investment', 'Wealth Creation', 'Networking', 'Public Speaking', 'Personal Branding'],
        'business_challenges' => ['Sales', 'Marketing', 'Team Management', 'Hiring', 'Cash Flow', 'Finance', 'Systems & Processes', 'Technology', 'Competition', 'Expansion', 'Leadership', 'Customer Acquisition', 'Business Growth', 'Other'],
        'business_goals' => ['Double Business Growth', 'Double Income', 'Increase Profitability', 'Expand Team', 'Expand Branches', 'Build Strong Systems', 'Become Industry Leader', 'Franchise Development', 'National Expansion', 'International Expansion', 'Learn AI for Business', 'Build Personal Brand', 'Wealth Creation', 'Business Succession Planning', 'Other'],
        'digital_access' => ['Smartphone', 'Laptop', 'Tablet', 'Internet Connection', 'Personal Computer', 'ERP Software', 'CRM Software'],
    ];
@endphp

<form method="POST" action="{{ route('register.business-growth.store') }}" enctype="multipart/form-data" class="bns-admission-form" id="businessGrowthAdmissionForm">
    @csrf
    <input type="hidden" name="form_type" value="business-growth-school">

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '01', 'title' => 'Personal Information'])
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="bg_full_name">Full Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="bg_full_name" name="full_name" value="{{ $bg ? old('full_name') : '' }}" placeholder="Enter your full name" required>
                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <span class="bns-admission-form__label">Gender <span class="req">*</span></span>
                <div class="bns-admission-form__inline-options">
                    @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                        <label class="bns-chip">
                            <input type="radio" name="gender" value="{{ $value }}" {{ $bg && old('gender') === $value ? 'checked' : '' }} required>
                            <span class="bns-chip__text">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('gender')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_date_of_birth">Date of Birth <span class="req">*</span></label>
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="bg_date_of_birth" name="date_of_birth" value="{{ $bg ? old('date_of_birth') : '' }}" required>
                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_age">Age <span class="req">*</span></label>
                <input type="number" class="form-control @error('age') is-invalid @enderror" id="bg_age" name="age" value="{{ $bg ? old('age') : '' }}" min="1" max="120" placeholder="Years" required>
                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label">Photo Upload</label>
                <div class="bns-photo-upload">
                    <input type="file" class="bns-photo-upload__input @error('photo') is-invalid @enderror" id="bg_photo" name="photo" accept="image/*">
                    <div class="bns-photo-upload__box">
                        <span class="bns-photo-upload__icon"><i class="fas fa-camera"></i></span>
                        <span class="bns-photo-upload__text">
                            <strong>Upload your photo</strong>
                            JPG, PNG — max 2MB
                        </span>
                    </div>
                    <div class="bns-photo-upload__filename" id="bgPhotoFilename"></div>
                </div>
                @error('photo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_mobile">Mobile Number <span class="req">*</span></label>
                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="bg_mobile" name="mobile" value="{{ $bg ? old('mobile') : '' }}" placeholder="+91 XXXXX XXXXX" required>
                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_whatsapp">WhatsApp Number</label>
                <input type="text" class="form-control" id="bg_whatsapp" name="whatsapp" value="{{ $bg ? old('whatsapp') : '' }}" placeholder="+91 XXXXX XXXXX">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_email">Email ID <span class="req">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="bg_email" name="email" value="{{ $bg ? old('email') : '' }}" placeholder="you@email.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '02', 'title' => 'Educational Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Highest Qualification</span>
            @include('register.partials.chip-group', ['name' => 'highest_qualification', 'options' => $checkboxGroups['highest_qualification'], 'type' => 'checkbox', 'oldPrefix' => 'business-growth-school'])
        </div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="bg_qualification_details">Qualification Details</label>
                <input type="text" class="form-control" id="bg_qualification_details" name="qualification_details" value="{{ $bg ? old('qualification_details') : '' }}" placeholder="Enter qualification details">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '03', 'title' => 'Family Information'])
        <div class="row g-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_father_name">Father's Name</label>
                <input type="text" class="form-control" id="bg_father_name" name="father_name" value="{{ $bg ? old('father_name') : '' }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_spouse_name">Spouse Name (If Applicable)</label>
                <input type="text" class="form-control" id="bg_spouse_name" name="spouse_name" value="{{ $bg ? old('spouse_name') : '' }}">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_family_mobile">Family Mobile Number</label>
                <input type="text" class="form-control" id="bg_family_mobile" name="family_mobile" value="{{ $bg ? old('family_mobile') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '04', 'title' => 'Address Details'])
        <div class="row g-3">
            <div class="col-12">
                <label class="bns-admission-form__label" for="bg_current_address">Current Address</label>
                <textarea class="form-control" id="bg_current_address" name="current_address" rows="3" placeholder="House no., street, area">{{ $bg ? old('current_address') : '' }}</textarea>
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="bg_city">City</label>
                <input type="text" class="form-control" id="bg_city" name="city" value="{{ $bg ? old('city') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="bg_district">District</label>
                <input type="text" class="form-control" id="bg_district" name="district" value="{{ $bg ? old('district') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="bg_state">State</label>
                <input type="text" class="form-control" id="bg_state" name="state" value="{{ $bg ? old('state') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="bg_pin_code">PIN Code</label>
                <input type="text" class="form-control" id="bg_pin_code" name="pin_code" value="{{ $bg ? old('pin_code') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '05', 'title' => 'Business Information'])
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="business_name">Business / Firm Name</label>
                <input type="text" class="form-control" id="business_name" name="business_name" value="{{ $bg ? old('business_name') : '' }}" placeholder="Enter business or firm name">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="business_since_year">Business Since (Year)</label>
                <input type="text" class="form-control" id="business_since_year" name="business_since_year" value="{{ $bg ? old('business_since_year') : '' }}" placeholder="e.g. 2015">
            </div>
        </div>
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Business Type</span>
            @include('register.partials.chip-group', ['name' => 'business_type', 'options' => $checkboxGroups['business_type'], 'type' => 'checkbox', 'oldPrefix' => 'business-growth-school'])
        </div>
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Business Category</span>
            @include('register.partials.chip-group', ['name' => 'business_category', 'options' => $checkboxGroups['business_category'], 'type' => 'checkbox', 'oldPrefix' => 'business-growth-school'])
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '06', 'title' => 'Business Profile'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Current Status</span>
            @include('register.partials.chip-group', ['name' => 'current_status', 'options' => $checkboxGroups['current_status'], 'type' => 'checkbox', 'oldPrefix' => 'business-growth-school'])
        </div>
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Number of Employees</span>
            @include('register.partials.chip-group', ['name' => 'employee_count', 'options' => $checkboxGroups['employee_count'], 'type' => 'radio', 'oldPrefix' => 'business-growth-school'])
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="business_location">Business Location</label>
                <input type="text" class="form-control" id="business_location" name="business_location" value="{{ $bg ? old('business_location') : '' }}" placeholder="City / area of business">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '07', 'title' => 'Business Interest Profile'])
        <span class="bns-admission-form__label">Which Areas Interest You Most?</span>
        @include('register.partials.chip-group', ['name' => 'interests', 'options' => $checkboxGroups['interests'], 'type' => 'checkbox', 'oldPrefix' => 'business-growth-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '08', 'title' => 'Business Challenges'])
        <span class="bns-admission-form__label">Current Challenges</span>
        @include('register.partials.chip-group', ['name' => 'business_challenges', 'options' => $checkboxGroups['business_challenges'], 'type' => 'checkbox', 'oldPrefix' => 'business-growth-school'])
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="challenge_other_specify">Specify (if Other)</label>
                <input type="text" class="form-control" id="challenge_other_specify" name="challenge_other_specify" value="{{ $bg ? old('challenge_other_specify') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '09', 'title' => 'Business Goals'])
        <span class="bns-admission-form__label">My Primary Goal Is:</span>
        @include('register.partials.chip-group', ['name' => 'business_goals', 'options' => $checkboxGroups['business_goals'], 'type' => 'checkbox', 'oldPrefix' => 'business-growth-school'])
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="bg_goal_other_specify">Specify (if Other)</label>
                <input type="text" class="form-control" id="bg_goal_other_specify" name="goal_other_specify" value="{{ $bg ? old('goal_other_specify') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '10', 'title' => 'Digital Access'])
        <span class="bns-admission-form__label">Do You Have Access To?</span>
        @include('register.partials.chip-group', ['name' => 'digital_access', 'options' => $checkboxGroups['digital_access'], 'type' => 'checkbox', 'oldPrefix' => 'business-growth-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '11', 'title' => 'Health Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Any Medical Condition? <span class="req">*</span></span>
            <div class="bns-admission-form__inline-options">
                @foreach(['no' => 'No', 'yes' => 'Yes'] as $value => $label)
                    <label class="bns-chip">
                        <input type="radio" name="medical_condition" value="{{ $value }}" {{ ($bg ? old('medical_condition', 'no') : 'no') === $value ? 'checked' : '' }} required>
                        <span class="bns-chip__text">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="bg_medical_details">If Yes, Please Mention</label>
                <input type="text" class="form-control" id="bg_medical_details" name="medical_details" value="{{ $bg ? old('medical_details') : '' }}" placeholder="Describe medical condition">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '12', 'title' => 'Consent & Declaration'])
        <p class="bns-admission-form__declaration">I hereby declare that all information provided by me is true and correct. I agree to follow all rules, attendance requirements, code of conduct and learning guidelines of Business Navachar School (BNS).</p>
        <label class="bns-chip bns-admission-form__check--consent">
            <input type="checkbox" name="consent_agreed" value="1" {{ $bg && old('consent_agreed') ? 'checked' : '' }} required>
            <span class="bns-chip__text">I Agree <span class="req">*</span></span>
        </label>
        @error('consent_agreed')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_applicant_name">Applicant Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('applicant_name') is-invalid @enderror" id="bg_applicant_name" name="applicant_name" value="{{ $bg ? old('applicant_name') : '' }}" required>
                @error('applicant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_signature">Signature <span class="req">*</span></label>
                <input type="text" class="form-control @error('signature') is-invalid @enderror" id="bg_signature" name="signature" value="{{ $bg ? old('signature') : '' }}" placeholder="Type your full name as signature" required>
                @error('signature')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="bg_application_date">Date <span class="req">*</span></label>
                <input type="date" class="form-control @error('application_date') is-invalid @enderror" id="bg_application_date" name="application_date" value="{{ $bg ? old('application_date', now()->toDateString()) : now()->toDateString() }}" required>
                @error('application_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    @include('register.partials.form-footer', [
        'submitId' => 'businessGrowthApplySubmit',
        'motto' => 'Building Entrepreneurs, Strengthening Businesses, Creating Wealth & Developing Industry Leaders',
    ])
</form>
