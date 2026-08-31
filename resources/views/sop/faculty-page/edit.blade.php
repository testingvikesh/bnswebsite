@extends('sop.layouts.app')

@section('title', 'Faculty Page')
@section('page-title', 'Visiting Expert Faculty Page')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-0">Edit <a href="{{ route('about.faculty') }}" target="_blank">Visiting Expert Faculty</a> page. Manage profiles under <a href="{{ route('controlpanel.visiting-faculty.index') }}">Visiting Faculty</a>.</p>
    </div>
    <a href="{{ route('about.faculty') }}" target="_blank" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-box-arrow-up-right me-1"></i> Preview
    </a>
</div>

<form method="POST" action="{{ route('controlpanel.faculty-page.update') }}" enctype="multipart/form-data">
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
                <h5 class="fw-bold mb-3">Faculty excellence block</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Label</label>
                        <input type="text" name="excellence_label" class="form-control" value="{{ old('excellence_label', $page->excellence_label) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Title</label>
                        <input type="text" name="excellence_title" class="form-control" value="{{ old('excellence_title', $page->excellence_title) }}" required>
                    </div>
                    @php $paragraphs = old('excellence_paragraphs', $page->excellence_paragraphs ?? []); @endphp
                    @for($i = 0; $i < max(2, count($paragraphs)); $i++)
                    <div class="col-12">
                        <label class="form-label fw-semibold">Paragraph {{ $i + 1 }}</label>
                        <textarea name="excellence_paragraphs[]" rows="3" class="form-control">{{ $paragraphs[$i] ?? '' }}</textarea>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Bottom tagline banner</h5>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Brand line</label>
                        <input type="text" name="tagline_brand" class="form-control" value="{{ old('tagline_brand', $page->tagline_brand) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Tagline text</label>
                        <input type="text" name="tagline_text" class="form-control" value="{{ old('tagline_text', $page->tagline_text) }}">
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
