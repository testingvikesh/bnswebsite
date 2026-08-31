<div class="mb-3">
    <label class="form-label fw-semibold">Photo</label>
    @if($editing && $testimonial->photo_path)
        <div class="mb-3 d-flex align-items-start gap-3">
            @if($testimonial->photo_url)
                <img src="{{ $testimonial->photo_url }}" alt="{{ $testimonial->full_name }}"
                     class="rounded border" style="width: 96px; height: 96px; object-fit: cover;">
            @else
                <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted small text-center px-2"
                     style="width: 96px; height: 96px;">
                    Photo not found.<br>Upload a new file.
                </div>
            @endif
            <div>
                <p class="small text-muted mb-2">{{ $testimonial->photo_path }}</p>
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

<div class="mb-3">
    <label for="full_name" class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
    <input type="text" name="full_name" id="full_name"
           class="form-control @error('full_name') is-invalid @enderror"
           value="{{ old('full_name', $testimonial->full_name) }}" required
           placeholder="e.g. Manojkumar Mer">
    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="designation" class="form-label fw-semibold">Designation</label>
        <input type="text" name="designation" id="designation"
               class="form-control @error('designation') is-invalid @enderror"
               value="{{ old('designation', $testimonial->designation) }}"
               placeholder="e.g. Owner At">
        @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="organization" class="form-label fw-semibold">Organization</label>
        <input type="text" name="organization" id="organization"
               class="form-control @error('organization') is-invalid @enderror"
               value="{{ old('organization', $testimonial->organization) }}"
               placeholder="e.g. FineArt Cad Design">
        @error('organization')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label for="location" class="form-label fw-semibold">Location</label>
    <input type="text" name="location" id="location"
           class="form-control @error('location') is-invalid @enderror"
           value="{{ old('location', $testimonial->location) }}"
           placeholder="e.g. Rajkot (Gujarat)">
    @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="mobile" class="form-label fw-semibold">Mobile No.</label>
        <input type="text" name="mobile" id="mobile"
               class="form-control @error('mobile') is-invalid @enderror"
               value="{{ old('mobile', $testimonial->mobile) }}"
               placeholder="e.g. 91 9429564030">
        @error('mobile')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" name="email" id="email"
               class="form-control @error('email') is-invalid @enderror"
               value="{{ old('email', $testimonial->email) }}"
               placeholder="e.g. fineartcadcam@gmail.com">
        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>

<div class="mb-3">
    <label for="website" class="form-label fw-semibold">Website</label>
    <input type="text" name="website" id="website"
           class="form-control @error('website') is-invalid @enderror"
           value="{{ old('website', $testimonial->website) }}"
           placeholder="e.g. www.manojmer.com">
    @error('website')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="message" class="form-label fw-semibold">Testimonial Message <span class="text-muted fw-normal">(Optional)</span></label>
    <textarea name="message" id="message" rows="3"
              class="form-control @error('message') is-invalid @enderror"
              placeholder="Optional quote or feedback text...">{{ old('message', $testimonial->message) }}</textarea>
    @error('message')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="sort_order" class="form-label fw-semibold">Display Order</label>
        <input type="number" name="sort_order" id="sort_order" min="0" max="9999"
               class="form-control @error('sort_order') is-invalid @enderror"
               value="{{ old('sort_order', $testimonial->sort_order ?? 0) }}">
        @error('sort_order')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3 d-flex align-items-end">
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                   {{ old('is_active', $testimonial->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold" for="is_active">Show on website</label>
        </div>
    </div>
</div>
