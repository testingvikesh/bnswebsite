@extends('layouts.front')

@section('title', $pageTitle)

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admission-page.css') }}" />
@endpush

@section('content')
<div class="bns-admission-page">
    @include('partials.page-header', [
        'title' => $pageTitle,
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Admissions', 'url' => route('admissions.index')],
            ['label' => $pageTitle],
        ],
    ])

    <section class="bns-admission-apply">
        <div class="container">
            @if($errors->any())
                <div class="alert alert-danger bns-admission-alert">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="bns-admission-steps" id="admissionSteps">
                @foreach(['Category','Program','Year / Level','Batch','City','Centre','Admission Form','Documents','Fee Payment','Confirmation'] as $i => $step)
                    <span class="bns-admission-step {{ $i === 0 ? 'is-active' : '' }}" data-step="{{ $i }}">{{ $i + 1 }}. {{ $step }}</span>
                @endforeach
            </div>

            <form method="POST" action="{{ route('admissions.apply.store') }}" enctype="multipart/form-data" class="bns-admission-form" id="admissionForm">
                @csrf

                <fieldset class="bns-admission-panel is-active" data-panel="0">
                    <legend>1. Select Category</legend>
                    <div class="bns-admission-options">
                        @foreach($config['categories'] as $cat)
                            <label class="bns-admission-option"><input type="radio" name="category" value="{{ $cat }}" {{ old('category') === $cat ? 'checked' : '' }} required><span>{{ $cat }}</span></label>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="bns-admission-panel" data-panel="1">
                    <legend>2. Select Program</legend>
                    <div class="bns-admission-options">
                        @foreach($config['programs'] as $prog)
                            <label class="bns-admission-option"><input type="radio" name="program" value="{{ $prog }}" {{ old('program') === $prog ? 'checked' : '' }} required><span>{{ $prog }}</span></label>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="bns-admission-panel" data-panel="2">
                    <legend>3. Select Year / Level</legend>
                    <div class="bns-admission-options">
                        @foreach($config['year_levels'] as $level)
                            <label class="bns-admission-option"><input type="radio" name="year_level" value="{{ $level }}" {{ old('year_level') === $level ? 'checked' : '' }} required><span>{{ $level }}</span></label>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="bns-admission-panel" data-panel="3">
                    <legend>4. Select Batch</legend>
                    <div class="bns-admission-options">
                        @foreach($config['batches'] as $batch)
                            <label class="bns-admission-option"><input type="radio" name="batch" value="{{ $batch }}" {{ old('batch') === $batch ? 'checked' : '' }} required><span>{{ $batch }}</span></label>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="bns-admission-panel" data-panel="4">
                    <legend>5. Select City</legend>
                    <div class="bns-admission-options">
                        @foreach($config['cities'] as $city)
                            <label class="bns-admission-option"><input type="radio" name="city" value="{{ $city }}" {{ old('city') === $city ? 'checked' : '' }} required><span>{{ $city }}</span></label>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="bns-admission-panel" data-panel="5">
                    <legend>6. Select Centre</legend>
                    <div class="bns-admission-options">
                        @foreach($config['centres'] as $centre)
                            <label class="bns-admission-option"><input type="radio" name="centre" value="{{ $centre }}" {{ old('centre') === $centre ? 'checked' : '' }} required><span>{{ $centre }}</span></label>
                        @endforeach
                    </div>
                </fieldset>

                <fieldset class="bns-admission-panel" data-panel="6">
                    <legend>7. Student Registration Form</legend>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Full Name *</label><input type="text" name="full_name" class="form-control" value="{{ old('full_name') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Photo</label><input type="file" name="photo" class="form-control" accept="image/*"></div>
                        <div class="col-md-6"><label class="form-label">Mobile Number *</label><input type="text" name="mobile" class="form-control" value="{{ old('mobile') }}" required></div>
                        <div class="col-md-6"><label class="form-label">WhatsApp Number</label><input type="text" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}"></div>
                        <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" value="{{ old('email') }}" required></div>
                        <div class="col-md-6"><label class="form-label">Date of Birth</label><input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}"></div>
                        <div class="col-md-6"><label class="form-label d-block">Gender</label>
                            @foreach(['Male','Female','Other'] as $g)
                                <label class="me-3"><input type="radio" name="gender" value="{{ $g }}" {{ old('gender') === $g ? 'checked' : '' }}> {{ $g }}</label>
                            @endforeach
                        </div>
                        <div class="col-12"><label class="form-label">Address</label><textarea name="address" rows="2" class="form-control">{{ old('address') }}</textarea></div>
                        <div class="col-md-4"><label class="form-label">State</label><input type="text" name="state" class="form-control" value="{{ old('state') }}"></div>
                        <div class="col-md-4"><label class="form-label">PIN Code</label><input type="text" name="pin_code" class="form-control" value="{{ old('pin_code') }}"></div>
                        <div class="col-md-6"><label class="form-label">Parent Name (if applicable)</label><input type="text" name="parent_name" class="form-control" value="{{ old('parent_name') }}"></div>
                        <div class="col-md-6"><label class="form-label">Parent Mobile</label><input type="text" name="parent_mobile" class="form-control" value="{{ old('parent_mobile') }}"></div>
                        <div class="col-md-6"><label class="form-label">Education Qualification</label><input type="text" name="education_qualification" class="form-control" value="{{ old('education_qualification') }}"></div>
                        <div class="col-md-6"><label class="form-label">College / School Name</label><input type="text" name="institution_name" class="form-control" value="{{ old('institution_name') }}"></div>
                        <div class="col-md-6"><label class="form-label">Occupation</label><input type="text" name="occupation" class="form-control" value="{{ old('occupation') }}"></div>
                        <div class="col-md-6"><label class="form-label">Experience</label><input type="text" name="experience" class="form-control" value="{{ old('experience') }}"></div>
                        <div class="col-md-6"><label class="form-label">LinkedIn (Optional)</label><input type="url" name="linkedin" class="form-control" value="{{ old('linkedin') }}"></div>
                    </div>
                </fieldset>

                <fieldset class="bns-admission-panel" data-panel="7">
                    <legend>8. Upload Documents</legend>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Aadhaar Card</label><input type="file" name="aadhaar" class="form-control" accept="image/*,.pdf"></div>
                        <div class="col-md-6"><label class="form-label">School / College ID</label><input type="file" name="school_id" class="form-control" accept="image/*,.pdf"></div>
                        <div class="col-md-6"><label class="form-label">Last Marksheet</label><input type="file" name="marksheet" class="form-control" accept="image/*,.pdf"></div>
                        <div class="col-md-6"><label class="form-label">Bonafide Certificate</label><input type="file" name="bonafide" class="form-control" accept="image/*,.pdf"></div>
                        <div class="col-md-6"><label class="form-label">Graduation Certificate (if applicable)</label><input type="file" name="graduation" class="form-control" accept="image/*,.pdf"></div>
                        <div class="col-md-6"><label class="form-label">Experience Letter (Professionals)</label><input type="file" name="experience_letter" class="form-control" accept="image/*,.pdf"></div>
                        <div class="col-md-6"><label class="form-label">Business Proof (Business Owners)</label><input type="file" name="business_proof" class="form-control" accept="image/*,.pdf"></div>
                    </div>
                </fieldset>

                <fieldset class="bns-admission-panel" data-panel="8">
                    <legend>9. Fee Section</legend>
                    <div class="row g-3 bns-admission-fees">
                        <div class="col-md-4"><label class="form-label">Registration Fee (₹)</label><input type="number" name="registration_fee" class="form-control fee-input" value="{{ old('registration_fee', 0) }}" min="0" step="0.01"></div>
                        <div class="col-md-4"><label class="form-label">Admission Fee (₹)</label><input type="number" name="admission_fee" class="form-control fee-input" value="{{ old('admission_fee', 0) }}" min="0" step="0.01"></div>
                        <div class="col-md-4"><label class="form-label">Course Fee (₹)</label><input type="number" name="course_fee" class="form-control fee-input" value="{{ old('course_fee', 0) }}" min="0" step="0.01"></div>
                        <div class="col-md-4"><label class="form-label">GST (₹)</label><input type="number" name="gst" class="form-control fee-input" value="{{ old('gst', 0) }}" min="0" step="0.01"></div>
                        <div class="col-md-4"><label class="form-label">Scholarship Adjustment (₹)</label><input type="number" name="scholarship" class="form-control fee-input" value="{{ old('scholarship', 0) }}" min="0" step="0.01"></div>
                        <div class="col-md-4"><label class="form-label">Discount (₹)</label><input type="number" name="discount" class="form-control fee-input" value="{{ old('discount', 0) }}" min="0" step="0.01"></div>
                        <div class="col-12"><div class="bns-admission-total">Total Payable: ₹<span id="feeTotal">0.00</span></div>
                        <p class="text-muted small mb-0">Online payment will be enabled after application review. Submit application to proceed.</p></div>
                    </div>
                </fieldset>

                <fieldset class="bns-admission-panel" data-panel="9">
                    <legend>10. Review &amp; Submit</legend>
                    <p>Please review your details and submit your admission application. Our team will contact you for payment and confirmation.</p>
                    <div class="bns-admission-review" id="admissionReview"></div>
                </fieldset>

                <div class="bns-admission-nav">
                    <button type="button" class="bns-admission-btn bns-admission-btn--outline" id="admissionPrev" disabled>Previous</button>
                    <button type="button" class="bns-admission-btn bns-admission-btn--primary" id="admissionNext">Next</button>
                    <button type="submit" class="bns-admission-btn bns-admission-btn--primary d-none" id="admissionSubmit">Submit Application</button>
                </div>
            </form>

            @include('admission.partials.trust', ['hub' => $hub ?? null])
            @include('admission.partials.ctas')
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const panels = document.querySelectorAll('.bns-admission-panel');
    const steps = document.querySelectorAll('.bns-admission-step');
    const prev = document.getElementById('admissionPrev');
    const next = document.getElementById('admissionNext');
    const submit = document.getElementById('admissionSubmit');
    const form = document.getElementById('admissionForm');
    let current = 0;

    function showStep(i) {
        current = i;
        panels.forEach((p, idx) => p.classList.toggle('is-active', idx === i));
        steps.forEach((s, idx) => s.classList.toggle('is-active', idx === i));
        prev.disabled = i === 0;
        next.classList.toggle('d-none', i === panels.length - 1);
        submit.classList.toggle('d-none', i !== panels.length - 1);
        if (i === panels.length - 1) updateReview();
    }

    function updateReview() {
        const fd = new FormData(form);
        const lines = [];
        ['category','program','year_level','batch','city','centre','full_name','mobile','email'].forEach(k => {
            if (fd.get(k)) lines.push('<strong>' + k.replace(/_/g,' ') + ':</strong> ' + fd.get(k));
        });
        document.getElementById('admissionReview').innerHTML = lines.join('<br>');
    }

    function calcFees() {
        const vals = [...document.querySelectorAll('.fee-input')].map(i => parseFloat(i.value) || 0);
        const total = Math.max(0, vals[0]+vals[1]+vals[2]+vals[3]-vals[4]-vals[5]);
        document.getElementById('feeTotal').textContent = total.toFixed(2);
    }

    prev.addEventListener('click', () => showStep(current - 1));
    next.addEventListener('click', () => {
        const panel = panels[current];
        const required = panel.querySelectorAll('[required]');
        for (const el of required) {
            if (!el.checkValidity()) { el.reportValidity(); return; }
        }
        showStep(current + 1);
    });
    document.querySelectorAll('.fee-input').forEach(i => i.addEventListener('input', calcFees));
    calcFees();
})();
</script>
@endpush
