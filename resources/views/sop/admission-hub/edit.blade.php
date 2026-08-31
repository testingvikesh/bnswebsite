@extends('sop.layouts.app')

@section('title', 'Admissions Hub')
@section('page-title', 'Admissions Hub Page')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-0">
            Edit the main <a href="{{ route('admissions.index') }}" target="_blank">Admissions</a> hub page.
            Section content: <a href="{{ route('controlpanel.admission-pages.index') }}">Admission Sections</a>.
            Applications: <a href="{{ route('controlpanel.admission-forms.index') }}">Admission Forms</a>.
        </p>
    </div>
    <a href="{{ route('admissions.index') }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i> Preview</a>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('controlpanel.admission-hub.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Page header</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Title</label><input type="text" name="page_title" class="form-control" value="{{ old('page_title', $hub->page_title) }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Subtitle</label><input type="text" name="page_subtitle" class="form-control" value="{{ old('page_subtitle', $hub->page_subtitle) }}"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Introduction</label><textarea name="page_intro" rows="3" class="form-control" required>{{ old('page_intro', $hub->page_intro) }}</textarea></div>
                    <div class="col-12"><label class="form-label fw-semibold">Introduction (paragraph 2)</label><textarea name="page_intro_2" rows="2" class="form-control">{{ old('page_intro_2', $hub->page_intro_2) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Hub menu cards</h5>
                <p class="text-muted small">These cards appear on the Admissions hub page, grouped by section. Use slug <code>apply-now</code> for the featured Apply Now card.</p>
                @php
                    $menu = $hub->menu_items ?? [];
                    if (old('menu_labels')) {
                        $menu = [];
                        foreach (old('menu_labels', []) as $i => $label) {
                            $menu[] = [
                                'label' => $label,
                                'slug' => old('menu_slugs')[$i] ?? '',
                                'icon' => old('menu_icons')[$i] ?? 'fas fa-link',
                                'group' => old('menu_groups')[$i] ?? 'Admissions',
                                'description' => old('menu_descriptions')[$i] ?? '',
                            ];
                        }
                    }
                @endphp
                @for($i = 0; $i < max(20, count($menu)); $i++)
                <div class="border rounded p-3 mb-3 bg-light">
                    <div class="row g-2">
                        <div class="col-md-4"><input type="text" name="menu_labels[]" class="form-control form-control-sm" placeholder="Label" value="{{ $menu[$i]['label'] ?? '' }}"></div>
                        <div class="col-md-3"><input type="text" name="menu_slugs[]" class="form-control form-control-sm" placeholder="Slug" value="{{ $menu[$i]['slug'] ?? '' }}"></div>
                        <div class="col-md-2"><input type="text" name="menu_groups[]" class="form-control form-control-sm" placeholder="Group" value="{{ $menu[$i]['group'] ?? '' }}"></div>
                        <div class="col-md-3"><input type="text" name="menu_icons[]" class="form-control form-control-sm" placeholder="Icon class" value="{{ $menu[$i]['icon'] ?? 'fas fa-link' }}"></div>
                        <div class="col-12"><input type="text" name="menu_descriptions[]" class="form-control form-control-sm" placeholder="Short description" value="{{ $menu[$i]['description'] ?? '' }}"></div>
                    </div>
                </div>
                @endfor
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Trust, after admission &amp; dashboard</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Trust section title</label><input type="text" name="trust_title" class="form-control" value="{{ old('trust_title', $hub->trust_title) }}"></div>
                    <div class="col-md-6"><label class="form-label">After admission title</label><input type="text" name="after_admission_title" class="form-control" value="{{ old('after_admission_title', $hub->after_admission_title) }}"></div>
                    <div class="col-md-6"><label class="form-label">Trust items (one per line)</label><textarea name="trust_items_text" rows="6" class="form-control">{{ old('trust_items_text', implode("\n", $hub->trust_items ?? [])) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label">After admission items (one per line)</label><textarea name="after_admission_items_text" rows="6" class="form-control">{{ old('after_admission_items_text', implode("\n", $hub->after_admission_items ?? [])) }}</textarea></div>
                    <div class="col-12"><label class="form-label">Student dashboard title</label><input type="text" name="dashboard_title" class="form-control" value="{{ old('dashboard_title', $hub->dashboard_title) }}"></div>
                    <div class="col-12"><label class="form-label">Dashboard items (one per line)</label><textarea name="dashboard_items_text" rows="4" class="form-control">{{ old('dashboard_items_text', implode("\n", $hub->dashboard_items ?? [])) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Admission office &amp; map</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Counselor label</label><input type="text" name="office_counselor" class="form-control" value="{{ old('office_counselor', $hub->office_counselor) }}"></div>
                    <div class="col-md-3"><label class="form-label">Phone</label><input type="text" name="office_phone" class="form-control" value="{{ old('office_phone', $hub->office_phone) }}"></div>
                    <div class="col-md-3"><label class="form-label">WhatsApp</label><input type="text" name="office_whatsapp" class="form-control" value="{{ old('office_whatsapp', $hub->office_whatsapp) }}"></div>
                    <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="office_email" class="form-control" value="{{ old('office_email', $hub->office_email) }}"></div>
                    <div class="col-md-6"><label class="form-label">Address</label><input type="text" name="office_address" class="form-control" value="{{ old('office_address', $hub->office_address) }}"></div>
                    <div class="col-12"><label class="form-label">Google Maps embed URL</label><textarea name="maps_embed_url" rows="2" class="form-control">{{ old('maps_embed_url', $hub->maps_embed_url) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Footer tagline</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Brand</label><input type="text" name="tagline_brand" class="form-control" value="{{ old('tagline_brand', $hub->tagline_brand) }}"></div>
                    <div class="col-md-6"><label class="form-label">Tagline text</label><input type="text" name="tagline_text" class="form-control" value="{{ old('tagline_text', $hub->tagline_text) }}"></div>
                    <div class="col-md-6"><label class="form-label">Subtext</label><input type="text" name="tagline_subtext" class="form-control" value="{{ old('tagline_subtext', $hub->tagline_subtext) }}"></div>
                    <div class="col-md-6"><label class="form-label">Hindi tagline</label><input type="text" name="tagline_hindi" class="form-control" value="{{ old('tagline_hindi', $hub->tagline_hindi) }}"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Hero image</h5>
                @if($hub->hero_image)<img src="{{ asset($hub->hero_image) }}" class="img-fluid rounded mb-3 border" alt="">@endif
                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="remove_hero_image" value="1" id="remove_hero_image"><label class="form-check-label text-danger small" for="remove_hero_image">Remove image</label></div>
                <input type="file" name="hero_image" class="form-control" accept="image/*">
            </div>
            <div class="bns-card p-4">
                <div class="form-check mb-3"><input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $hub->is_active) ? 'checked' : '' }}><label class="form-check-label" for="is_active">Page published</label></div>
                <button type="submit" class="btn btn-sop-primary w-100">Save Changes</button>
            </div>
        </div>
    </div>
</form>
@endsection
