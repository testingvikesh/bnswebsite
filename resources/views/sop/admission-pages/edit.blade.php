@extends('sop.layouts.app')

@section('title', 'Edit Admission Page')
@section('page-title', $page->page_title)

@section('content')
<div class="mb-4"><a href="{{ route('controlpanel.admission-pages.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back</a></div>
@if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

<form method="POST" action="{{ route('controlpanel.admission-pages.update', $page) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bns-card p-4 mb-4">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Title</label><input type="text" name="page_title" class="form-control" value="{{ old('page_title', $page->page_title) }}" required></div>
                    <div class="col-md-6"><label class="form-label">Subtitle</label><input type="text" name="page_subtitle" class="form-control" value="{{ old('page_subtitle', $page->page_subtitle) }}"></div>
                    <div class="col-12"><label class="form-label">Introduction</label><textarea name="page_intro" rows="4" class="form-control">{{ old('page_intro', $page->page_intro) }}</textarea></div>
                    <div class="col-12"><label class="form-label">Content items (one per line)</label><textarea name="content_items_text" rows="10" class="form-control">{{ old('content_items_text', implode("\n", $page->content_items ?? [])) }}</textarea></div>
                    <div class="col-12"><label class="form-label">Download URL (brochure/prospectus)</label><input type="text" name="download_url" class="form-control" value="{{ old('download_url', $page->download_url) }}"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="bns-card p-4 mb-4">
                <label class="form-label">Hero image</label>
                @if($page->hero_image)<img src="{{ asset($page->hero_image) }}" class="img-fluid rounded mb-2">@endif
                <input type="file" name="hero_image" class="form-control" accept="image/*">
            </div>
            <div class="bns-card p-4 mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Active</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save</button>
        </div>
    </div>
</form>
@endsection
