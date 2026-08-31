@php
    $checkboxGroups = [
        'current_standard' => ['Std 6', 'Std 7', 'Std 8', 'Std 9', 'Std 10', 'Std 11'],
        'board' => ['GSEB', 'CBSE', 'ICSE', 'Other'],
        'medium' => ['Gujarati', 'English', 'Hindi', 'Other'],
        'interests' => ['Entrepreneurship', 'Business', 'Artificial Intelligence (AI)', 'Robotics', 'Technology', 'Public Speaking', 'Leadership', 'Finance', 'Marketing', 'Digital Skills', 'Innovation', 'Startup', 'Creativity', 'Social Service', 'Communication Skills', 'Personality Development'],
        'future_dream' => ['Entrepreneur', 'Business Owner', 'IAS / IPS Officer', 'Doctor', 'Engineer', 'CA', 'Lawyer', 'Scientist', 'Teacher', 'Politician', 'Social Leader', 'Content Creator', 'Other'],
        'digital_access' => ['Smartphone', 'Laptop', 'Tablet', 'Internet Connection', 'None'],
    ];
@endphp

<form method="POST" action="{{ route('register.student.store') }}" enctype="multipart/form-data" class="bns-admission-form" id="studentAdmissionForm">
    @csrf

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '01', 'title' => 'Student Information'])
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="student_full_name">Student Full Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="student_full_name" name="full_name" value="{{ old('form_type') === 'student-school' ? old('full_name') : '' }}" placeholder="Enter student full name" required>
                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <span class="bns-admission-form__label">Gender <span class="req">*</span></span>
                <div class="bns-admission-form__inline-options">
                    @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $value => $label)
                        <label class="bns-chip">
                            <input type="radio" name="gender" value="{{ $value }}" {{ old('form_type') === 'student-school' && old('gender') === $value ? 'checked' : '' }} required>
                            <span class="bns-chip__text">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
                @error('gender')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="student_date_of_birth">Date of Birth <span class="req">*</span></label>
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="student_date_of_birth" name="date_of_birth" value="{{ old('form_type') === 'student-school' ? old('date_of_birth') : '' }}" required>
                @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="student_age">Age <span class="req">*</span></label>
                <input type="number" class="form-control @error('age') is-invalid @enderror" id="student_age" name="age" value="{{ old('form_type') === 'student-school' ? old('age') : '' }}" min="1" max="120" placeholder="Years" required>
                @error('age')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label">Student Photo Upload</label>
                <div class="bns-photo-upload">
                    <input type="file" class="bns-photo-upload__input @error('photo') is-invalid @enderror" id="student_photo" name="photo" accept="image/*">
                    <div class="bns-photo-upload__box">
                        <span class="bns-photo-upload__icon"><i class="fas fa-camera"></i></span>
                        <span class="bns-photo-upload__text">
                            <strong>Upload student photo</strong>
                            JPG, PNG — max 2MB
                        </span>
                    </div>
                    <div class="bns-photo-upload__filename" id="studentPhotoFilename"></div>
                </div>
                @error('photo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="student_mobile">Student Mobile Number <span class="req">*</span></label>
                <input type="text" class="form-control @error('mobile') is-invalid @enderror" id="student_mobile" name="mobile" value="{{ old('form_type') === 'student-school' ? old('mobile') : '' }}" placeholder="+91 XXXXX XXXXX" required>
                @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="student_whatsapp">Student WhatsApp Number</label>
                <input type="text" class="form-control" id="student_whatsapp" name="whatsapp" value="{{ old('form_type') === 'student-school' ? old('whatsapp') : '' }}" placeholder="+91 XXXXX XXXXX">
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="student_email">Email ID <span class="req">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="student_email" name="email" value="{{ old('form_type') === 'student-school' ? old('email') : '' }}" placeholder="you@email.com" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '02', 'title' => 'Academic Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Current Standard</span>
            @include('register.partials.chip-group', [
                'name' => 'current_standard',
                'options' => $checkboxGroups['current_standard'],
                'type' => 'checkbox',
                'oldPrefix' => 'student-school',
            ])
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="school_name">School Name</label>
                <input type="text" class="form-control" id="school_name" name="school_name" value="{{ old('form_type') === 'student-school' ? old('school_name') : '' }}" placeholder="Enter school name">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="last_academic_result">Last Academic Result (%)</label>
                <input type="text" class="form-control" id="last_academic_result" name="last_academic_result" value="{{ old('form_type') === 'student-school' ? old('last_academic_result') : '' }}" placeholder="e.g. 85">
            </div>
        </div>
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Board</span>
            @include('register.partials.chip-group', [
                'name' => 'board',
                'options' => $checkboxGroups['board'],
                'type' => 'checkbox',
                'oldPrefix' => 'student-school',
            ])
        </div>
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Medium</span>
            @include('register.partials.chip-group', [
                'name' => 'medium',
                'options' => $checkboxGroups['medium'],
                'type' => 'checkbox',
                'oldPrefix' => 'student-school',
            ])
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '03', 'title' => 'Parent Information'])
        <div class="row g-3">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="father_name">Father's Name</label>
                <input type="text" class="form-control" id="father_name" name="father_name" value="{{ old('form_type') === 'student-school' ? old('father_name') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="father_mobile">Father's Mobile Number</label>
                <input type="text" class="form-control" id="father_mobile" name="father_mobile" value="{{ old('form_type') === 'student-school' ? old('father_mobile') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="father_occupation">Father's Occupation</label>
                <input type="text" class="form-control" id="father_occupation" name="father_occupation" value="{{ old('form_type') === 'student-school' ? old('father_occupation') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="mother_name">Mother's Name</label>
                <input type="text" class="form-control" id="mother_name" name="mother_name" value="{{ old('form_type') === 'student-school' ? old('mother_name') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="mother_mobile">Mother's Mobile Number</label>
                <input type="text" class="form-control" id="mother_mobile" name="mother_mobile" value="{{ old('form_type') === 'student-school' ? old('mother_mobile') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="mother_occupation">Mother's Occupation</label>
                <input type="text" class="form-control" id="mother_occupation" name="mother_occupation" value="{{ old('form_type') === 'student-school' ? old('mother_occupation') : '' }}">
            </div>
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="parent_whatsapp">Parent WhatsApp Number</label>
                <input type="text" class="form-control" id="parent_whatsapp" name="parent_whatsapp" value="{{ old('form_type') === 'student-school' ? old('parent_whatsapp') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '04', 'title' => 'Address Details'])
        <div class="row g-3">
            <div class="col-12">
                <label class="bns-admission-form__label" for="student_current_address">Current Address</label>
                <textarea class="form-control" id="student_current_address" name="current_address" rows="3" placeholder="House no., street, area">{{ old('form_type') === 'student-school' ? old('current_address') : '' }}</textarea>
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="student_city">City</label>
                <input type="text" class="form-control" id="student_city" name="city" value="{{ old('form_type') === 'student-school' ? old('city') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="student_district">District</label>
                <input type="text" class="form-control" id="student_district" name="district" value="{{ old('form_type') === 'student-school' ? old('district') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="student_state">State</label>
                <input type="text" class="form-control" id="student_state" name="state" value="{{ old('form_type') === 'student-school' ? old('state') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="bns-admission-form__label" for="student_pin_code">PIN Code</label>
                <input type="text" class="form-control" id="student_pin_code" name="pin_code" value="{{ old('form_type') === 'student-school' ? old('pin_code') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '05', 'title' => 'Student Interest Profile'])
        <span class="bns-admission-form__label d-block mb-2">Which areas interest you most?</span>
        @include('register.partials.chip-group', [
            'name' => 'interests',
            'options' => $checkboxGroups['interests'],
            'type' => 'checkbox',
            'oldPrefix' => 'student-school',
            'wide' => true,
        ])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '06', 'title' => 'Future Dream Profile'])
        <span class="bns-admission-form__label d-block mb-2">What would you like to become?</span>
        @include('register.partials.chip-group', [
            'name' => 'future_dream',
            'options' => $checkboxGroups['future_dream'],
            'type' => 'checkbox',
            'oldPrefix' => 'student-school',
            'wide' => true,
        ])
        <div class="row g-3 mt-2">
            <div class="col-md-6">
                <label class="bns-admission-form__label" for="dream_other_specify">Specify (if Other)</label>
                <input type="text" class="form-control" id="dream_other_specify" name="dream_other_specify" value="{{ old('form_type') === 'student-school' ? old('dream_other_specify') : '' }}">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '07', 'title' => 'Health Information'])
        <div class="bns-admission-form__field-group">
            <span class="bns-admission-form__label">Any Medical Condition? <span class="req">*</span></span>
            <div class="bns-admission-form__inline-options">
                @foreach(['no' => 'No', 'yes' => 'Yes'] as $value => $label)
                    <label class="bns-chip">
                        <input type="radio" name="medical_condition" value="{{ $value }}" {{ (old('form_type') !== 'youth-school' ? old('medical_condition', 'no') : 'no') === $value ? 'checked' : '' }} required>
                        <span class="bns-chip__text">{{ $label }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="bns-admission-form__label" for="student_medical_details">If Yes, Please Mention</label>
                <input type="text" class="form-control" id="student_medical_details" name="medical_details" value="{{ old('form_type') === 'student-school' ? old('medical_details') : '' }}" placeholder="Describe medical condition">
            </div>
        </div>
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '08', 'title' => 'Social Media & Digital Access'])
        <span class="bns-admission-form__label">Do You Have Access To?</span>
        @include('register.partials.chip-group', [
            'name' => 'digital_access',
            'options' => $checkboxGroups['digital_access'],
            'type' => 'checkbox',
            'oldPrefix' => 'student-school',
        ])
    </div>

    <div class="bns-admission-form__section-card">
        @include('register.partials.section-head', ['num' => '09', 'title' => 'Consent & Declaration'])
        <p class="bns-admission-form__declaration">I hereby declare that all information provided by me is true and correct. I agree to follow all rules, discipline policies, attendance requirements, code of conduct and learning guidelines of Business Navachar School (BNS).</p>
        <p class="bns-admission-form__label mb-2">Parent Consent:</p>
        <label class="bns-chip bns-admission-form__check--consent">
            <input type="checkbox" name="consent_agreed" value="1" {{ old('form_type') === 'student-school' && old('consent_agreed') ? 'checked' : '' }} required>
            <span class="bns-chip__text">I Agree <span class="req">*</span></span>
        </label>
        @error('consent_agreed')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
        <div class="row g-3 mt-3">
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="parent_name">Parent Name <span class="req">*</span></label>
                <input type="text" class="form-control @error('parent_name') is-invalid @enderror" id="parent_name" name="parent_name" value="{{ old('form_type') === 'student-school' ? old('parent_name') : '' }}" required>
                @error('parent_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="parent_signature">Parent Signature <span class="req">*</span></label>
                <input type="text" class="form-control @error('parent_signature') is-invalid @enderror" id="parent_signature" name="parent_signature" value="{{ old('form_type') === 'student-school' ? old('parent_signature') : '' }}" placeholder="Type parent full name as signature" required>
                @error('parent_signature')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="bns-admission-form__label" for="student_application_date">Date <span class="req">*</span></label>
                <input type="date" class="form-control @error('application_date') is-invalid @enderror" id="student_application_date" name="application_date" value="{{ old('form_type') === 'student-school' ? old('application_date', now()->toDateString()) : now()->toDateString() }}" required>
                @error('application_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <input type="hidden" name="form_type" value="student-school">

    @include('register.partials.form-footer', [
        'submitId' => 'studentApplySubmit',
        'motto' => 'Learning Business, Leadership & Life Skills for Future Success',
    ])
</form>
