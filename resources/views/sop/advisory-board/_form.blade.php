<div class="mb-3">
    <label class="form-label fw-semibold">Professional Photo</label>
    @if($editing && $member->photo_url)
        <div class="mb-3 d-flex align-items-start gap-3">
            <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}"
                 class="rounded border" style="width: 96px; height: 96px; object-fit: cover;">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="remove_photo"
                       {{ old('remove_photo') ? 'checked' : '' }}>
                <label class="form-check-label text-danger small" for="remove_photo">Remove current photo</label>
            </div>
        </div>
    @endif
    <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
    <div class="form-text">JPG, PNG or WebP. Max 2 MB. Recommended: square portrait, min 400×400px.</div>
    @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="full_name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
    <input type="text" name="full_name" id="full_name"
           class="form-control @error('full_name') is-invalid @enderror"
           value="{{ old('full_name', $member->full_name) }}" required>
    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="designation" class="form-label fw-semibold">Designation <span class="text-danger">*</span></label>
        <input type="text" name="designation" id="designation"
               class="form-control @error('designation') is-invalid @enderror"
               value="{{ old('designation', $member->designation) }}" required
               placeholder="e.g. Industry Leader">
        @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="organization" class="form-label fw-semibold">Organization</label>
        <input type="text" name="organization" id="organization"
               class="form-control @error('organization') is-invalid @enderror"
               value="{{ old('organization', $member->organization) }}"
               placeholder="e.g. Company / Institution name">
        @error('organization')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label for="expertise" class="form-label fw-semibold">Area of Expertise <span class="text-danger">*</span></label>
    <input type="text" name="expertise" id="expertise"
           class="form-control @error('expertise') is-invalid @enderror"
           value="{{ old('expertise', $member->expertise) }}" required
           placeholder="e.g. Strategy, Finance, Leadership (comma-separated)">
    <div class="form-text">Separate multiple areas with commas.</div>
    @error('expertise')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="profile" class="form-label fw-semibold">Profile (2–3 lines) <span class="text-danger">*</span></label>
    <textarea name="profile" id="profile" rows="4"
              class="form-control @error('profile') is-invalid @enderror" required
              placeholder="Brief professional background and contribution to BNS...">{{ old('profile', $member->profile) }}</textarea>
    @error('profile')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="linkedin_url" class="form-label fw-semibold">LinkedIn <span class="text-muted fw-normal">(Optional)</span></label>
    <input type="url" name="linkedin_url" id="linkedin_url"
           class="form-control @error('linkedin_url') is-invalid @enderror"
           value="{{ old('linkedin_url', $member->linkedin_url) }}"
           placeholder="https://www.linkedin.com/in/username">
    @error('linkedin_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="sort_order" class="form-label fw-semibold">Display Order</label>
        <input type="number" name="sort_order" id="sort_order" min="0" max="9999"
               class="form-control @error('sort_order') is-invalid @enderror"
               value="{{ old('sort_order', $member->sort_order ?? 0) }}">
        <div class="form-text">Lower numbers appear first on the website.</div>
        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                   {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="is_active">Show on website</label>
        </div>
    </div>
</div>
