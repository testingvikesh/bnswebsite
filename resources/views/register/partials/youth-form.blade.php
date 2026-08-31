@php
    $checkboxGroups = [
        'highest_qualification' => ['Std 12', 'Diploma', 'Graduate', 'Post Graduate (Master Degree)', 'M.Com', 'M.Sc', 'MBA', 'MCA', 'M.Tech', 'Ph.D.', 'CA', 'CS', 'Other'],
        'current_course' => ['B.Com', 'BBA', 'BCA', 'BA', 'B.Sc', 'BE/B.Tech', 'LLB', 'CA', 'CS', 'MBA', 'MCA', 'M.Com', 'M.Sc', 'M.Tech', 'Ph.D.', 'Other'],
        'current_year_status' => ['1st Year', '2nd Year', '3rd Year', '4th Year', 'Final Year', 'Completed', 'Working Professional', 'Research Scholar', 'Ph.D. Scholar'],
        'interests' => ['Entrepreneurship', 'Startup Development', 'Artificial Intelligence (AI)', 'Business Management', 'Leadership', 'Communication Skills', 'Public Speaking', 'Personal Branding', 'Digital Marketing', 'Sales', 'Finance', 'Investment', 'Stock Market', 'Wealth Creation', 'Content Creation', 'Social Media Growth', 'E-Commerce', 'Freelancing', 'Technology', 'Innovation', 'Networking', 'Career Growth'],
        'primary_goal' => ['Start My Own Business', 'Join Family Business', 'Grow Family Business', 'Get High Paying Job', 'Become Freelancer', 'Become Startup Founder', 'Become Business Consultant', 'Become Corporate Leader', 'Become Investor', 'Become Digital Creator', 'Build Personal Brand', 'Financial Freedom', 'Multiple Income Sources', 'International Career', 'Other'],
        'current_status' => ['School Student (Std 12)', 'College Student', 'Graduate', 'Post Graduate', 'Research Scholar', 'Working Professional', 'Freelancer', 'Startup Founder', 'Business Owner', 'Looking For Career Opportunities', 'Preparing For Competitive Exams', 'Other'],
        'work_experience_options' => ['Fresher', 'Less Than 1 Year', '1–3 Years', '3–5 Years', '5–10 Years', 'More Than 10 Years'],
        'digital_access' => ['Smartphone', 'Laptop', 'Tablet', 'Internet Connection', 'Personal Computer', 'None'],
    ];
@endphp

@php $y = ! in_array(old('form_type'), ['student-school', 'women-school', 'working-women-school', 'job-professional-school', 'business-growth-school'], true); @endphp

<form method="POST" action="{{ route('register.youth.store') }}" enctype="multipart/form-data" class="bns-admission-form" id="youthAdmissionForm">
    @csrf

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '01', 'title' => 'Personal Information'])
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="full_name">Full Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ $y ? old('full_name') : '' }}" placeholder="Enter your full name" required>
                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <span class="bns-admission-form__label">Gender <span class="req">*</span></span>
                <div class="bns-admission-form__inline-options">
                    @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                        <label class="bns-chip">
                            <input type="radio" name="gender" value="{{ $value }}" {{ $y && old('gender') === $value ? 'checked' : '' }} required>
                            <span class="bns-chip__text">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('gender')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="date_of_birth">Date of Birth <span class="req">*</span></label>
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ $y ? old('date_of_birth') : '' }}" required>
                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="age">Age <span class="req">*</span></label>
                <input type="number" class="form-control @error('age') is-invalid @enderror" id="age" name="age" value="{{ $y ? old('age') : '' }}" min="1" max="120" placeholder="Years" required>
                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label">Photo Upload</label>
                <div class="bns-photo-upload">
                    <input type="file" class="bns-photo-upload__input @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*">
                    <div class="bns-photo-upload__box">
                        <span class="bns-photo-upload__icon"><i class="fas fa-camera"></i></span>
                        <span class="bns-photo-upload__text">
                            <strong>Upload your photo</strong>
                            JPG, PNG — max 2MB
                        </span>
                    </div>
                    <div class="bns-photo-upload__filename" id="photoFilename"></div>
                </div>
                @error('photo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="mobile">Mobile Number <span class="req">*</span></label>
                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="mobile" name="mobile" value="{{ $y ? old('mobile') : '' }}" placeholder="+91 XXXXX XXXXX" required>
                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="whatsapp">WhatsApp Number</label>
                <input type="text" class="form-control @error('whatsapp') is-invalid @enderror" id="whatsapp" name="whatsapp" value="{{ $y ? old('whatsapp') : '' }}" placeholder="+91 XXXXX XXXXX">
                @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="email">Email ID <span class="req">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $y ? old('email') : '' }}" placeholder="you@email.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '02', 'title' => 'Educational Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Highest Qualification</span>
            @include('register.partials.chip-group', ['name' => 'highest_qualification', 'options' => $checkboxGroups['highest_qualification'], 'type' => 'checkbox', 'oldPrefix' => 'youth-school'])
        </div>
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Current Course / Qualification</span>
            @include('register.partials.chip-group', ['name' => 'current_course', 'options' => $checkboxGroups['current_course'], 'type' => 'checkbox', 'oldPrefix' => 'youth-school'])
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="college_name">College / University / Institute Name</label>
                <input type="text" class="form-control" id="college_name" name="college_name" value="{{ $y ? old('college_name') : '' }}" placeholder="Enter institute name">
            </div>
        </div>
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Current Year / Status</span>
            @include('register.partials.chip-group', ['name' => 'current_year_status', 'options' => $checkboxGroups['current_year_status'], 'type' => 'checkbox', 'oldPrefix' => 'youth-school'])
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '03', 'title' => 'Family Information'])
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="father_name">Father's Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name" value="{{ $y ? old('father_name') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="father_occupation">Father's Occupation</label>
                <input type="text" class="form-control" id="father_occupation" name="father_occupation" value="{{ $y ? old('father_occupation') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="mother_name">Mother's Name</label>
                <input type="text" class="form-control" id="mother_name" name="mother_name" value="{{ $y ? old('mother_name') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="mother_occupation">Mother's Occupation</label>
                <input type="text" class="form-control" id="mother_occupation" name="mother_occupation" value="{{ $y ? old('mother_occupation') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="parent_mobile">Parent Mobile Number</label>
                <input type="text" class="form-control" id="parent_mobile" name="parent_mobile" value="{{ $y ? old('parent_mobile') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '04', 'title' => 'Address Details'])
        <div class="row g-3">
            <div class="col-12">
                <label class="bns-admission-form__label" for="current_address">Current Address</label>
                <textarea class="form-control" id="current_address" name="current_address" rows="3" placeholder="House no., street, area">{{ $y ? old('current_address') : '' }}</textarea>
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="city">City</label>
                <input type="text" class="form-control" id="city" name="city" value="{{ $y ? old('city') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="district">District</label>
                <input type="text" class="form-control" id="district" name="district" value="{{ $y ? old('district') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="state">State</label>
                <input type="text" class="form-control" id="state" name="state" value="{{ $y ? old('state') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="pin_code">PIN Code</label>
                <input type="text" class="form-control" id="pin_code" name="pin_code" value="{{ $y ? old('pin_code') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '05', 'title' => 'Career & Interest Profile'])
        <span class="bns-admission-form__label">Which Areas Interest You Most?</span>
        @include('register.partials.chip-group', ['name' => 'interests', 'options' => $checkboxGroups['interests'], 'type' => 'checkbox', 'oldPrefix' => 'youth-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '06', 'title' => 'Future Goal Profile'])
        <span class="bns-admission-form__label">My Primary Goal Is:</span>
        @include('register.partials.chip-group', ['name' => 'primary_goal', 'options' => $checkboxGroups['primary_goal'], 'type' => 'checkbox', 'oldPrefix' => 'youth-school'])
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="goal_other_specify">Specify (if Other)</label>
                <input type="text" class="form-control" id="goal_other_specify" name="goal_other_specify" value="{{ $y ? old('goal_other_specify') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '07', 'title' => 'Current Status'])
        <span class="bns-admission-form__label">Presently I Am:</span>
        @include('register.partials.chip-group', ['name' => 'current_status', 'options' => $checkboxGroups['current_status'], 'type' => 'checkbox', 'oldPrefix' => 'youth-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '08', 'title' => 'Experience Details'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Work Experience</span>
            @include('register.partials.chip-group', ['name' => 'work_experience', 'options' => $checkboxGroups['work_experience_options'], 'type' => 'radio', 'oldPrefix' => 'youth-school'])
        </div>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="organization_name">Current Organization / Business Name</label>
                <input type="text" class="form-control" id="organization_name" name="organization_name" value="{{ $y ? old('organization_name') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="designation">Designation</label>
                <input type="text" class="form-control" id="designation" name="designation" value="{{ $y ? old('designation') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '09', 'title' => 'Health Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Any Medical Condition? <span class="req">*</span></span>
            <div class="bns-admission-form__inline-options">
                @foreach(['no' => 'No', 'yes' => 'Yes'] as $value => $label)
                    <label class="bns-chip">
                        <input type="radio" name="medical_condition" value="{{ $value }}" {{ ($y ? old('medical_condition', 'no') : 'no') === $value ? 'checked' : '' }} required>
                        <span class="bns-chip__text">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="medical_details">If Yes, Please Mention</label>
                <input type="text" class="form-control" id="medical_details" name="medical_details" value="{{ $y ? old('medical_details') : '' }}" placeholder="Describe medical condition">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '10', 'title' => 'Digital Access'])
        <span class="bns-admission-form__label">Do You Have Access To?</span>
        @include('register.partials.chip-group', ['name' => 'digital_access', 'options' => $checkboxGroups['digital_access'], 'type' => 'checkbox', 'oldPrefix' => 'youth-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '11', 'title' => 'Consent & Declaration'])
        <p class="bns-admission-form__declaration">I hereby declare that all information provided by me is true and correct. I agree to follow all rules, attendance requirements, code of conduct and learning guidelines of Business Navachar School (BNS).</p>
        <label class="bns-chip bns-admission-form__check--consent">
            <input type="checkbox" name="consent_agreed" value="1" {{ $y && old('consent_agreed') ? 'checked' : '' }} required>
            <span class="bns-chip__text">I Agree <span class="req">*</span></span>
        </label>
        @error('consent_agreed')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="applicant_name">Applicant Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('applicant_name') is-invalid @enderror" id="applicant_name" name="applicant_name" value="{{ $y ? old('applicant_name') : '' }}" required>
                @error('applicant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="signature">Signature <span class="req">*</span></label>
                <input type="text" class="form-control @error('signature') is-invalid @enderror" id="signature" name="signature" value="{{ $y ? old('signature') : '' }}" placeholder="Type your full name as signature" required>
                @error('signature')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="application_date">Date <span class="req">*</span></label>
                <input type="date" class="form-control @error('application_date') is-invalid @enderror" id="application_date" name="application_date" value="{{ $y ? old('application_date', now()->toDateString()) : now()->toDateString() }}" required>
                @error('application_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <input type="hidden" name="form_type" value="youth-school">

    @include('register.partials.form-footer', [
        'submitId' => 'applySubmit',
        'motto' => 'Transforming Youth Into Entrepreneurs, Leaders, Wealth Creators & Nation Builders',
    ])
</form>
