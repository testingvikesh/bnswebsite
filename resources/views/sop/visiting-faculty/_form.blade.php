@php
    $prefixes = $titlePrefixes ?? config('faculty.title_prefixes');
@endphp

<div class="mb-4 pb-3 border-bottom">
    <h6 class="fw-bold text-uppercase small text-muted mb-3">Faculty Profile</h6>

    <div class="mb-3">
        <label class="form-label fw-semibold">Professional Photo</label>
        @if($editing && $faculty->photo_path)
            <div class="mb-3 d-flex align-items-start gap-3">
                @if($faculty->photo_url)
                    <img src="{{ $faculty->photo_url }}" alt="{{ $faculty->display_name }}"
                         class="rounded border" style="width: 96px; height: 96px; object-fit: cover;">
                @else
                    <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted small text-center px-2"
                         style="width: 96px; height: 96px;">
                        Photo not found.<br>Upload a new file.
                    </div>
                @endif
                <div>
                    <p class="small text-muted mb-2">{{ $faculty->photo_path }}</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="remove_photo"
                               {{ old('remove_photo') ? 'checked' : '' }}>
                        <label class="form-check-label text-danger small" for="remove_photo">Remove current photo</label>
                    </div>
                </div>
            </div>
        @endif
        <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
        <div class="form-text">JPG, PNG or WebP. Max 2 MB. Upload a new file to replace the current photo.</div>
        @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <label for="title_prefix" class="form-label fw-semibold">Title</label>
            <select name="title_prefix" id="title_prefix" class="form-select @error('title_prefix') is-invalid @enderror">
                <option value="">— None —</option>
                @foreach($prefixes as $prefix)
                    <option value="{{ $prefix }}" @selected(old('title_prefix', $faculty->title_prefix) === $prefix)>{{ $prefix }}</option>
                @endforeach
            </select>
            @error('title_prefix')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-9 mb-3">
            <label for="full_name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="full_name" id="full_name"
                   class="form-control @error('full_name') is-invalid @enderror"
                   value="{{ old('full_name', $faculty->full_name) }}" required>
            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="designation" class="form-label fw-semibold">Designation <span class="text-danger">*</span></label>
            <input type="text" name="designation" id="designation"
                   class="form-control @error('designation') is-invalid @enderror"
                   value="{{ old('designation', $faculty->designation) }}" required>
            @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="recognition" class="form-label fw-semibold">Recognition</label>
            <input type="text" name="recognition" id="recognition"
                   class="form-control @error('recognition') is-invalid @enderror"
                   value="{{ old('recognition', $faculty->recognition) }}"
                   placeholder="Faculties of IIM Trained Business Education Mentor">
            @error('recognition')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="mb-4 pb-3 border-bottom">
    <h6 class="fw-bold text-uppercase small text-muted mb-3">Expertise &amp; Background</h6>

    <div class="mb-3">
        <label for="expertise" class="form-label fw-semibold">Expertise <span class="text-danger">*</span></label>
        <input type="text" name="expertise" id="expertise"
               class="form-control @error('expertise') is-invalid @enderror"
               value="{{ old('expertise', $faculty->expertise) }}" required
               placeholder="Entrepreneurship, Marketing, Finance, Leadership (comma-separated)">
        @error('expertise')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="professional_experience" class="form-label fw-semibold">Professional Experience</label>
            <input type="text" name="professional_experience" id="professional_experience"
                   class="form-control @error('professional_experience') is-invalid @enderror"
                   value="{{ old('professional_experience', $faculty->professional_experience) }}"
                   placeholder="e.g. 15+ Years">
            @error('professional_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="industry" class="form-label fw-semibold">Industry</label>
            <input type="text" name="industry" id="industry"
                   class="form-control @error('industry') is-invalid @enderror"
                   value="{{ old('industry', $faculty->industry) }}"
                   placeholder="Manufacturing / Healthcare / IT / Education">
            @error('industry')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="qualification" class="form-label fw-semibold">Qualification</label>
            <input type="text" name="qualification" id="qualification"
                   class="form-control @error('qualification') is-invalid @enderror"
                   value="{{ old('qualification', $faculty->qualification) }}"
                   placeholder="MBA / Ph.D. / CA / Industry Professional">
            @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="specialization" class="form-label fw-semibold">Specialization</label>
            <input type="text" name="specialization" id="specialization"
                   class="form-control @error('specialization') is-invalid @enderror"
                   value="{{ old('specialization', $faculty->specialization) }}"
                   placeholder="Business Growth • Sales • Branding • Startup Development">
            @error('specialization')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
</div>

<div class="mb-4 pb-3 border-bottom">
    <h6 class="fw-bold text-uppercase small text-muted mb-3">Impact &amp; Details</h6>

    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="faculty_since" class="form-label fw-semibold">Faculty Since</label>
            <input type="number" name="faculty_since" id="faculty_since" min="1990" max="2100"
                   class="form-control @error('faculty_since') is-invalid @enderror"
                   value="{{ old('faculty_since', $faculty->faculty_since) }}" placeholder="2026">
            @error('faculty_since')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="sessions_conducted" class="form-label fw-semibold">Sessions Conducted</label>
            <input type="text" name="sessions_conducted" id="sessions_conducted"
                   class="form-control @error('sessions_conducted') is-invalid @enderror"
                   value="{{ old('sessions_conducted', $faculty->sessions_conducted) }}" placeholder="125+">
            @error('sessions_conducted')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="learners_mentored" class="form-label fw-semibold">Learners Mentored</label>
            <input type="text" name="learners_mentored" id="learners_mentored"
                   class="form-control @error('learners_mentored') is-invalid @enderror"
                   value="{{ old('learners_mentored', $faculty->learners_mentored) }}" placeholder="2,500+">
            @error('learners_mentored')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="languages" class="form-label fw-semibold">Languages</label>
        <input type="text" name="languages" id="languages"
               class="form-control @error('languages') is-invalid @enderror"
               value="{{ old('languages', $faculty->languages) }}"
               placeholder="English | Hindi | Gujarati">
        @error('languages')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="mb-3">
        <label for="about" class="form-label fw-semibold">About (2–3 lines)</label>
        <textarea name="about" id="about" rows="4"
                  class="form-control @error('about') is-invalid @enderror"
                  placeholder="Brief professional summary...">{{ old('about', $faculty->about) }}</textarea>
        @error('about')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="sort_order" class="form-label fw-semibold">Display Order</label>
        <input type="number" name="sort_order" id="sort_order" min="0" max="9999"
               class="form-control @error('sort_order') is-invalid @enderror"
               value="{{ old('sort_order', $faculty->sort_order ?? 0) }}">
        <div class="form-text">Lower numbers appear first.</div>
        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                   {{ old('is_active', $faculty->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="is_active">Show on website</label>
        </div>
    </div>
</div>
