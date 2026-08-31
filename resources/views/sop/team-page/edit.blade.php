@extends('sop.layouts.app')

@section('title', 'Team Page')
@section('page-title', 'Meet Our Team Page')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-0">Edit <a href="{{ route('about.team') }}" target="_blank">Meet Our Team</a> page content. Add leadership &amp; academic members under <a href="{{ route('controlpanel.team-members.index') }}">Team Members</a>.</p>
    </div>
    <a href="{{ route('about.team') }}" target="_blank" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-box-arrow-up-right me-1"></i> Preview
    </a>
</div>

<form method="POST" action="{{ route('controlpanel.team-page.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Page header</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="page_title" class="form-control" value="{{ old('page_title', $page->page_title) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Subtitle</label>
                        <input type="text" name="page_subtitle" class="form-control" value="{{ old('page_subtitle', $page->page_subtitle) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Introduction</label>
                        <textarea name="page_intro" rows="3" class="form-control" required>{{ old('page_intro', $page->page_intro) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Section titles</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Leadership</label>
                        <input type="text" name="leadership_title" class="form-control" value="{{ old('leadership_title', $page->leadership_title) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Academic</label>
                        <input type="text" name="academic_title" class="form-control" value="{{ old('academic_title', $page->academic_title) }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Advisory Board</label>
                        <input type="text" name="advisory_title" class="form-control" value="{{ old('advisory_title', $page->advisory_title) }}" required>
                    </div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Academic collaboration banner</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Badge</label>
                        <input type="text" name="collab_badge" class="form-control" value="{{ old('collab_badge', $page->collab_badge) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="collab_title" class="form-control" value="{{ old('collab_title', $page->collab_title) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="collab_description" rows="3" class="form-control">{{ old('collab_description', $page->collab_description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Operations teams</h5>
                <label class="form-label fw-semibold">Section title</label>
                <input type="text" name="operations_title" class="form-control mb-3" value="{{ old('operations_title', $page->operations_title) }}" required>
                @php
                    $ops = old('ops_names') ? [] : ($page->operations_teams ?? []);
                    if (old('ops_names')) {
                        foreach (old('ops_names', []) as $i => $name) {
                            $ops[] = ['name' => $name, 'description' => old('ops_descriptions')[$i] ?? '', 'icon' => old('ops_icons')[$i] ?? ''];
                        }
                    }
                @endphp
                @for($i = 0; $i < max(5, count($ops)); $i++)
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Team name</label>
                            <input type="text" name="ops_names[]" class="form-control form-control-sm" value="{{ $ops[$i]['name'] ?? '' }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Icon class</label>
                            <input type="text" name="ops_icons[]" class="form-control form-control-sm" value="{{ $ops[$i]['icon'] ?? 'fas fa-users' }}" placeholder="fas fa-users">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-semibold">Description</label>
                            <input type="text" name="ops_descriptions[]" class="form-control form-control-sm" value="{{ $ops[$i]['description'] ?? '' }}">
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Team values</h5>
                <label class="form-label fw-semibold">Section title</label>
                <input type="text" name="values_title" class="form-control mb-3" value="{{ old('values_title', $page->values_title) }}" required>
                @php $values = old('values_items', $page->values_items ?? []); @endphp
                @for($i = 0; $i < max(7, count($values)); $i++)
                <div class="mb-2">
                    <input type="text" name="values_items[]" class="form-control" value="{{ $values[$i] ?? '' }}" placeholder="Value {{ $i + 1 }}">
                </div>
                @endfor
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Join our team</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="join_title" class="form-control" value="{{ old('join_title', $page->join_title) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Introduction</label>
                        <textarea name="join_intro" rows="2" class="form-control">{{ old('join_intro', $page->join_intro) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Looking for label</label>
                        <input type="text" name="join_looking_label" class="form-control" value="{{ old('join_looking_label', $page->join_looking_label) }}">
                    </div>
                    @php $roles = old('join_roles', $page->join_roles ?? []); @endphp
                    @for($i = 0; $i < max(7, count($roles)); $i++)
                    <div class="col-md-6">
                        <input type="text" name="join_roles[]" class="form-control" value="{{ $roles[$i] ?? '' }}" placeholder="Role {{ $i + 1 }}">
                    </div>
                    @endfor
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">CTA title</label>
                        <input type="text" name="join_cta_title" class="form-control" value="{{ old('join_cta_title', $page->join_cta_title) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Contact email</label>
                        <input type="email" name="join_contact_email" class="form-control" value="{{ old('join_contact_email', $page->join_contact_email) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">CTA text</label>
                        <textarea name="join_cta_text" rows="2" class="form-control">{{ old('join_cta_text', $page->join_cta_text) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Hero banner image</h5>
                @if($page->hero_image)
                    <img src="{{ asset($page->hero_image) }}" alt="" class="img-fluid rounded mb-3 border">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remove_hero_image" value="1" id="remove_hero_image">
                        <label class="form-check-label text-danger small" for="remove_hero_image">Remove image</label>
                    </div>
                @endif
                <input type="file" name="hero_image" class="form-control" accept="image/*">
            </div>
            <div class="bns-card p-4">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                           {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Page published</label>
                </div>
                <button type="submit" class="btn btn-sop-primary w-100">Save Changes</button>
            </div>
        </div>
    </div>
</form>
@endsection
