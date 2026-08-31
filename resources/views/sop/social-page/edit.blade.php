@extends('sop.layouts.app')

@section('title', 'Follow Us Page')
@section('page-title', 'Follow Us / Social Media Page')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-0">Edit <a href="{{ route('social.follow') }}" target="_blank">Follow Us</a> page and social platform links.</p>
    </div>
    <a href="{{ route('social.follow') }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-box-arrow-up-right me-1"></i> Preview</a>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@php
    $platformLines = collect($page->platforms ?? [])->map(function ($p) {
        return ($p['icon'] ?? '').'|'.($p['name'] ?? '').'|'.($p['description'] ?? '').'|'.($p['button_label'] ?? '').'|'.($p['url'] ?? '#').'|'.($p['accent'] ?? 'default');
    })->implode("\n");
@endphp

<form method="POST" action="{{ route('controlpanel.social-page.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Page header</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label fw-semibold">Title</label><input type="text" name="page_title" class="form-control" value="{{ old('page_title', $page->page_title) }}" required></div>
                    <div class="col-md-6"><label class="form-label fw-semibold">Subtitle</label><input type="text" name="page_subtitle" class="form-control" value="{{ old('page_subtitle', $page->page_subtitle) }}"></div>
                    <div class="col-12"><label class="form-label fw-semibold">Introduction</label><textarea name="page_intro" rows="3" class="form-control" required>{{ old('page_intro', $page->page_intro) }}</textarea></div>
                    <div class="col-12"><label class="form-label fw-semibold">Introduction (paragraph 2)</label><textarea name="page_intro_2" rows="2" class="form-control">{{ old('page_intro_2', $page->page_intro_2) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Social platforms</h5>
                <div class="mb-3">
                    <label class="form-label">Section title</label>
                    <input type="text" name="platforms_title" class="form-control" value="{{ old('platforms_title', $page->platforms_title) }}">
                </div>
                <p class="text-muted small">One platform per line:<br><code>Icon|Name|Description|Button label|URL|accent</code><br>Accent: facebook, instagram, youtube, linkedin, twitter, whatsapp, telegram, threads</p>
                <textarea name="platforms_text" rows="14" class="form-control font-monospace">{{ old('platforms_text', $platformLines) }}</textarea>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">What you'll get</h5>
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Section title</label><input type="text" name="benefits_title" class="form-control" value="{{ old('benefits_title', $page->benefits_title) }}"></div>
                    <div class="col-12"><label class="form-label">Benefits (one per line)</label><textarea name="benefits_items_text" rows="8" class="form-control">{{ old('benefits_items_text', implode("\n", $page->benefits_items ?? [])) }}</textarea></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Prosperity movement &amp; quick connect</h5>
                <div class="row g-3">
                    <div class="col-12"><label class="form-label">Movement title</label><input type="text" name="movement_title" class="form-control" value="{{ old('movement_title', $page->movement_title) }}"></div>
                    <div class="col-12"><label class="form-label">Movement text</label><textarea name="movement_text" rows="2" class="form-control">{{ old('movement_text', $page->movement_text) }}</textarea></div>
                    <div class="col-12"><label class="form-label">Movement text (paragraph 2)</label><textarea name="movement_text_2" rows="2" class="form-control">{{ old('movement_text_2', $page->movement_text_2) }}</textarea></div>
                    <div class="col-12"><label class="form-label">Quick connect title</label><input type="text" name="quick_connect_title" class="form-control" value="{{ old('quick_connect_title', $page->quick_connect_title) }}"></div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Footer tagline</h5>
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label">Brand</label><input type="text" name="tagline_brand" class="form-control" value="{{ old('tagline_brand', $page->tagline_brand) }}"></div>
                    <div class="col-md-6"><label class="form-label">Tagline</label><input type="text" name="tagline_text" class="form-control" value="{{ old('tagline_text', $page->tagline_text) }}"></div>
                    <div class="col-md-6"><label class="form-label">Subtext</label><input type="text" name="tagline_subtext" class="form-control" value="{{ old('tagline_subtext', $page->tagline_subtext) }}"></div>
                    <div class="col-md-6"><label class="form-label">Hindi tagline</label><input type="text" name="tagline_hindi" class="form-control" value="{{ old('tagline_hindi', $page->tagline_hindi) }}"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Hero image</h5>
                @if($page->hero_image)
                    <img src="{{ asset($page->hero_image) }}" alt="" class="img-fluid rounded mb-3">
                @endif
                <input type="file" name="hero_image" class="form-control mb-2" accept="image/*">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remove_hero_image" value="1" id="remove_hero_image">
                    <label class="form-check-label" for="remove_hero_image">Remove current image</label>
                </div>
            </div>
            <div class="bns-card p-4 mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Page active</label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save Changes</button>
        </div>
    </div>
</form>
@endsection
