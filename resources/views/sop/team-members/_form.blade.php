@php
    $editing = $editing ?? false;
    $isLeadership = old('category', $member->category) === 'leadership';

    $rawExpertise = old('expertise');
    if ($rawExpertise === null) {
        $expertiseItems = array_values(array_filter((array) ($member->expertise ?? [])));
        $expertiseHtml = $expertiseItems !== []
            ? '<ul><li>'.implode('</li><li>', array_map('e', $expertiseItems)).'</li></ul>'
            : '';
    } elseif (str_contains((string) $rawExpertise, '<')) {
        $expertiseHtml = (string) $rawExpertise;
    } else {
        $lines = array_values(array_filter(array_map('trim', preg_split('/\R+/', (string) $rawExpertise))));
        $expertiseHtml = $lines !== []
            ? '<ul><li>'.implode('</li><li>', array_map('e', $lines)).'</li></ul>'
            : '';
    }

    $profileHtml = old('profile', $member->profile ?? '');
    if ($profileHtml === '' && $isLeadership) {
        if (str_contains((string) $member->full_name, 'Mehul Rupani')) {
            $profileHtml = \App\Support\TeamMemberProfiles::render(\App\Support\TeamMemberProfiles::drMehulRupani());
        } elseif ((int) $member->sort_order === 2 || str_contains((string) $member->designation, 'Chief Executive Officer')) {
            $profileHtml = \App\Support\TeamMemberProfiles::render(\App\Support\TeamMemberProfiles::chiefExecutiveOfficer());
        } elseif ((int) $member->sort_order === 3 || str_contains((string) $member->designation, 'Director – Business Navachar School') || str_contains((string) $member->designation, 'Director - Business Navachar School')) {
            $profileHtml = \App\Support\TeamMemberProfiles::render(\App\Support\TeamMemberProfiles::directorBns());
        } elseif ((int) $member->sort_order === 4 || str_contains((string) $member->designation, 'Digital & Technology')) {
            $profileHtml = \App\Support\TeamMemberProfiles::render(\App\Support\TeamMemberProfiles::directorDigitalBns());
        } elseif ((int) $member->sort_order === 5 || str_contains((string) $member->designation, 'Social Media Marketing')) {
            $profileHtml = \App\Support\TeamMemberProfiles::render(\App\Support\TeamMemberProfiles::headSocialMediaBns());
        } elseif ((int) $member->sort_order === 6 || str_contains((string) $member->designation, 'Head of Marketing')) {
            $profileHtml = \App\Support\TeamMemberProfiles::render(\App\Support\TeamMemberProfiles::headMarketingBns());
        } elseif ((int) $member->sort_order === 7 || str_contains((string) $member->designation, 'Marketing Manager')) {
            $profileHtml = \App\Support\TeamMemberProfiles::render(\App\Support\TeamMemberProfiles::marketingManagerBns());
        }
    }
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
        <select name="category" id="team-member-category" class="form-select @error('category') is-invalid @enderror" required>
            <option value="leadership" {{ old('category', $member->category) === 'leadership' ? 'selected' : '' }}>Leadership Team</option>
            <option value="academic" {{ old('category', $member->category) === 'academic' ? 'selected' : '' }}>Academic Team</option>
        </select>
        @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Sort order</label>
        <input type="number" name="sort_order" class="form-control" min="0" max="9999"
               value="{{ old('sort_order', $member->sort_order) }}">
    </div>
</div>

<div class="mb-3 mt-3">
    <label class="form-label fw-semibold">Photo</label>
    @if($editing && $member->photo_path)
        <div class="mb-3 d-flex align-items-start gap-3">
            @if($member->photo_url)
                <img src="{{ $member->photo_url }}" alt="{{ $member->full_name }}"
                     class="rounded border" style="width: 96px; height: 96px; object-fit: cover;">
            @else
                <div class="rounded border bg-light d-flex align-items-center justify-content-center text-muted small text-center px-2"
                     style="width: 96px; height: 96px;">
                    Photo not found.<br>Upload a new file.
                </div>
            @endif
            <div>
                <p class="small text-muted mb-2">{{ $member->photo_path }}</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remove_photo" value="1" id="remove_photo">
                    <label class="form-check-label text-danger small" for="remove_photo">Remove photo</label>
                </div>
            </div>
        </div>
    @endif
    <input type="file" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/webp">
    <div class="form-text">JPG, PNG or WebP. Max 2 MB. Upload a new file to replace the current photo.</div>
    @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
    <input type="text" name="full_name" class="form-control @error('full_name') is-invalid @enderror"
           value="{{ old('full_name', $member->full_name) }}" required>
    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Designation <span class="text-danger">*</span></label>
    <input type="text" name="designation" class="form-control @error('designation') is-invalid @enderror"
           value="{{ old('designation', $member->designation) }}" required>
    @error('designation')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3" id="team-member-profile-wrap" @if(!$isLeadership) style="display:none;" @endif>
    <label class="form-label fw-semibold">Profile</label>
    <textarea name="profile" id="profile-editor" class="form-control" rows="10">{!! $profileHtml !!}</textarea>
    <div class="form-text">Leadership profile text shown on the team page. Use lists, headings, and paragraphs for an attractive profile layout.</div>
</div>

<div class="mb-3" id="team-member-expertise-wrap" @if($isLeadership) style="display:none;" @endif>
    <label class="form-label fw-semibold">Area of Expertise</label>
    <textarea name="expertise" id="expertise-editor" class="form-control" rows="8">{!! $expertiseHtml !!}</textarea>
    <div class="form-text">Use the editor to add bullet points. Each bullet appears as a separate expertise block on the Academic Team page.</div>
</div>

@once
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
        <style>
            .note-editor.note-frame {
                border-color: var(--bns-border, #e2e8f0);
                border-radius: 10px;
                overflow: hidden;
            }

            .note-toolbar {
                background: #f8fafc;
                border-bottom-color: var(--bns-border, #e2e8f0);
            }

            .note-editable {
                font-size: 15px;
                line-height: 1.6;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const categorySelect = document.getElementById('team-member-category');
                const profileWrap = document.getElementById('team-member-profile-wrap');
                const expertiseWrap = document.getElementById('team-member-expertise-wrap');
                const editors = {};

                function initEditor(id, placeholder, height) {
                    const $editor = window.jQuery('#' + id);
                    if (!$editor.length || editors[id]) {
                        return;
                    }

                    $editor.summernote({
                        height: height,
                        placeholder: placeholder,
                        toolbar: [
                            ['style', ['style', 'ul', 'ol', 'paragraph']],
                            ['font', ['bold', 'italic', 'underline', 'clear']],
                            ['view', ['fullscreen', 'codeview']]
                        ]
                    });

                    editors[id] = $editor;
                }

                function syncEditors() {
                    Object.values(editors).forEach(function ($editor) {
                        $editor.val($editor.summernote('code'));
                    });
                }

                function toggleMemberFields() {
                    const isLeadership = categorySelect.value === 'leadership';
                    profileWrap.style.display = isLeadership ? '' : 'none';
                    expertiseWrap.style.display = isLeadership ? 'none' : '';

                    if (isLeadership) {
                        initEditor(
                            'profile-editor',
                            'Add leadership profile details, roles, strengths, vision, and mission.',
                            320
                        );
                    } else {
                        initEditor(
                            'expertise-editor',
                            'Add expertise points as a bullet list. Each bullet appears as a separate block on the team page.',
                            260
                        );
                    }
                }

                categorySelect?.addEventListener('change', toggleMemberFields);
                toggleMemberFields();

                window.jQuery('form').on('submit', function () {
                    syncEditors();
                });
            });
        </script>
    @endpush
@endonce

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">LinkedIn URL</label>
        <input type="url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $member->linkedin_url) }}">
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $member->email) }}">
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6">
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="is_featured"
                   {{ old('is_featured', $member->is_featured) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">Featured card (orange header)</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                   {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Active (show on website)</label>
        </div>
    </div>
</div>
