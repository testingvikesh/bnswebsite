@php
    $w = old('form_type') === 'women-school';
    $checkboxGroups = [
        'highest_qualification' => ['Std 10', 'Std 12', 'Diploma', 'Graduate', 'Post Graduate (Master Degree)', 'B.Com', 'BBA', 'BA', 'B.Sc', 'MBA', 'M.Com', 'M.Sc', 'MCA', 'Ph.D.', 'Other'],
        'marital_status' => ['Unmarried', 'Married', 'Widow', 'Divorced', 'Other'],
        'current_status' => ['Housewife', 'Working Woman', 'Business Woman', 'Freelancer', 'Teacher', 'Professional', 'Student', 'Other'],
        'interests' => ['Home Business', 'Online Business', 'Boutique Business', 'Food & Catering Business', 'Bakery Business', 'Beauty & Salon Business', 'Tuition Classes', 'Coaching Centre', 'Digital Marketing', 'Social Media Business', 'Content Creation', 'Freelancing', 'Handicraft Business', 'Retail Business', 'Artificial Intelligence (AI)', 'Personal Branding', 'Leadership', 'Communication Skills', 'Finance Management', 'Investment Knowledge'],
        'monthly_income' => ['No Income', 'Below ₹10,000', '₹10,000 – ₹25,000', '₹25,000 – ₹50,000', '₹50,000 – ₹1,00,000', 'Above ₹1,00,000'],
        'primary_goal' => ['Start My Own Business', 'Increase Family Income', 'Become Entrepreneur', 'Become Financially Independent', 'Learn Digital Skills', 'Build Personal Brand', 'Start Online Business', 'Grow Existing Business', 'Earn From Home', 'Leadership Development', 'Self Development', 'Other'],
        'digital_access' => ['Smartphone', 'Laptop', 'Tablet', 'Internet Connection', 'Personal Computer', 'None'],
    ];
@endphp

<form method="POST" action="{{ route('register.women.store') }}" enctype="multipart/form-data" class="bns-admission-form" id="womenAdmissionForm">
    @csrf
    <input type="hidden" name="form_type" value="women-school">
    <input type="hidden" name="gender" value="female">

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '01', 'title' => 'Personal Information'])
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="women_full_name">Full Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="women_full_name" name="full_name" value="{{ $w ? old('full_name') : '' }}" placeholder="Enter your full name" required>
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
                <label class="bns-admission-form__label" for="women_date_of_birth">Date of Birth <span class="req">*</span></label>
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="women_date_of_birth" name="date_of_birth" value="{{ $w ? old('date_of_birth') : '' }}" required>
                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="women_age">Age <span class="req">*</span></label>
                <input type="number" class="form-control @error('age') is-invalid @enderror" id="women_age" name="age" value="{{ $w ? old('age') : '' }}" min="1" max="120" placeholder="Years" required>
                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label">Photo Upload</label>
                <div class="bns-photo-upload">
                    <input type="file" class="bns-photo-upload__input @error('photo') is-invalid @enderror" id="women_photo" name="photo" accept="image/*">
                    <div class="bns-photo-upload__box">
                        <span class="bns-photo-upload__icon"><i class="fas fa-camera"></i></span>
                        <span class="bns-photo-upload__text">
                            <strong>Upload your photo</strong>
                            JPG, PNG — max 2MB
                        </span>
                    </div>
                    <div class="bns-photo-upload__filename" id="womenPhotoFilename"></div>
                </div>
                @error('photo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="women_mobile">Mobile Number <span class="req">*</span></label>
                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="women_mobile" name="mobile" value="{{ $w ? old('mobile') : '' }}" placeholder="+91 XXXXX XXXXX" required>
                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="women_whatsapp">WhatsApp Number</label>
                <input type="text" class="form-control" id="women_whatsapp" name="whatsapp" value="{{ $w ? old('whatsapp') : '' }}" placeholder="+91 XXXXX XXXXX">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="women_email">Email ID <span class="req">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="women_email" name="email" value="{{ $w ? old('email') : '' }}" placeholder="you@email.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '02', 'title' => 'Educational Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Highest Qualification</span>
            @include('register.partials.chip-group', ['name' => 'highest_qualification', 'options' => $checkboxGroups['highest_qualification'], 'type' => 'checkbox', 'oldPrefix' => 'women-school'])
        </div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="qualification_details">Qualification Details</label>
                <input type="text" class="form-control" id="qualification_details" name="qualification_details" value="{{ $w ? old('qualification_details') : '' }}" placeholder="Enter qualification details">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '03', 'title' => 'Family Information'])
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="women_father_name">Father's Name</label>
                <input type="text" class="form-control" id="women_father_name" name="father_name" value="{{ $w ? old('father_name') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="husband_name">Husband's Name (If Applicable)</label>
                <input type="text" class="form-control" id="husband_name" name="husband_name" value="{{ $w ? old('husband_name') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="women_occupation">Occupation</label>
                <input type="text" class="form-control" id="women_occupation" name="occupation" value="{{ $w ? old('occupation') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="family_mobile">Family Mobile Number</label>
                <input type="text" class="form-control" id="family_mobile" name="family_mobile" value="{{ $w ? old('family_mobile') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '04', 'title' => 'Marital Status'])
        @include('register.partials.chip-group', ['name' => 'marital_status', 'options' => $checkboxGroups['marital_status'], 'type' => 'radio', 'oldPrefix' => 'women-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '05', 'title' => 'Address Details'])
        <div class="row g-3">
            <div class="col-12">
                <label class="bns-admission-form__label" for="women_current_address">Current Address</label>
                <textarea class="form-control" id="women_current_address" name="current_address" rows="3" placeholder="House no., street, area">{{ $w ? old('current_address') : '' }}</textarea>
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="women_city">City</label>
                <input type="text" class="form-control" id="women_city" name="city" value="{{ $w ? old('city') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="women_district">District</label>
                <input type="text" class="form-control" id="women_district" name="district" value="{{ $w ? old('district') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="women_state">State</label>
                <input type="text" class="form-control" id="women_state" name="state" value="{{ $w ? old('state') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="women_pin_code">PIN Code</label>
                <input type="text" class="form-control" id="women_pin_code" name="pin_code" value="{{ $w ? old('pin_code') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '06', 'title' => 'Current Status'])
        <span class="bns-admission-form__label">Presently I Am:</span>
        @include('register.partials.chip-group', ['name' => 'current_status', 'options' => $checkboxGroups['current_status'], 'type' => 'checkbox', 'oldPrefix' => 'women-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '07', 'title' => 'Business & Interest Profile'])
        <span class="bns-admission-form__label">Which Areas Interest You Most?</span>
        @include('register.partials.chip-group', ['name' => 'interests', 'options' => $checkboxGroups['interests'], 'type' => 'checkbox', 'oldPrefix' => 'women-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '08', 'title' => 'Income Status'])
        <span class="bns-admission-form__label">Current Monthly Income</span>
        @include('register.partials.chip-group', ['name' => 'monthly_income', 'options' => $checkboxGroups['monthly_income'], 'type' => 'radio', 'oldPrefix' => 'women-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '09', 'title' => 'Future Goals'])
        <span class="bns-admission-form__label">My Primary Goal Is:</span>
        @include('register.partials.chip-group', ['name' => 'primary_goal', 'options' => $checkboxGroups['primary_goal'], 'type' => 'checkbox', 'oldPrefix' => 'women-school'])
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="women_goal_other_specify">Specify (if Other)</label>
                <input type="text" class="form-control" id="women_goal_other_specify" name="goal_other_specify" value="{{ $w ? old('goal_other_specify') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '10', 'title' => 'Digital Access'])
        <span class="bns-admission-form__label">Do You Have Access To?</span>
        @include('register.partials.chip-group', ['name' => 'digital_access', 'options' => $checkboxGroups['digital_access'], 'type' => 'checkbox', 'oldPrefix' => 'women-school'])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '11', 'title' => 'Health Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Any Medical Condition? <span class="req">*</span></span>
            <div class="bns-admission-form__inline-options">
                @foreach(['no' => 'No', 'yes' => 'Yes'] as $value => $label)
                    <label class="bns-chip">
                        <input type="radio" name="medical_condition" value="{{ $value }}" {{ ($w ? old('medical_condition', 'no') : 'no') === $value ? 'checked' : '' }} required>
                        <span class="bns-chip__text">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="women_medical_details">If Yes, Please Mention</label>
                <input type="text" class="form-control" id="women_medical_details" name="medical_details" value="{{ $w ? old('medical_details') : '' }}" placeholder="Describe medical condition">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '12', 'title' => 'Consent & Declaration'])
        <p class="bns-admission-form__declaration">I hereby declare that all information provided by me is true and correct. I agree to follow all rules, attendance requirements, code of conduct and learning guidelines of Business Navachar School (BNS).</p>
        <label class="bns-chip bns-admission-form__check--consent">
            <input type="checkbox" name="consent_agreed" value="1" {{ $w && old('consent_agreed') ? 'checked' : '' }} required>
            <span class="bns-chip__text">I Agree <span class="req">*</span></span>
        </label>
        @error('consent_agreed')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="women_applicant_name">Applicant Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('applicant_name') is-invalid @enderror" id="women_applicant_name" name="applicant_name" value="{{ $w ? old('applicant_name') : '' }}" required>
                @error('applicant_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="women_signature">Signature <span class="req">*</span></label>
                <input type="text" class="form-control @error('signature') is-invalid @enderror" id="women_signature" name="signature" value="{{ $w ? old('signature') : '' }}" placeholder="Type your full name as signature" required>
                @error('signature')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="women_application_date">Date <span class="req">*</span></label>
                <input type="date" class="form-control @error('application_date') is-invalid @enderror" id="women_application_date" name="application_date" value="{{ $w ? old('application_date', now()->toDateString()) : now()->toDateString() }}" required>
                @error('application_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    @include('register.partials.form-footer', [
        'submitId' => 'womenApplySubmit',
        'motto' => 'Empowering Women, Creating Entrepreneurs, Building Financial Independence',
    ])
</form>
