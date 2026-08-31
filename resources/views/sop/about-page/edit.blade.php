@extends('sop.layouts.app')

@section('title', 'About Us Page')
@section('page-title', 'About Us Page')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <p class="text-muted mb-0">Edit the public <a href="{{ route('about') }}" target="_blank">About Us</a> page and homepage about section.</p>
    </div>
    <a href="{{ route('about') }}" target="_blank" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-box-arrow-up-right me-1"></i> Preview Page
    </a>
</div>

<form method="POST" action="{{ route('controlpanel.about-page.update') }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Main content</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tagline</label>
                        <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $page->tagline) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Heading</label>
                        <input type="text" name="heading" class="form-control" value="{{ old('heading', $page->heading) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Introduction</label>
                        <textarea name="intro_text" rows="4" class="form-control" required>{{ old('intro_text', $page->intro_text) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Focus heading</label>
                        <input type="text" name="focus_heading" class="form-control" value="{{ old('focus_heading', $page->focus_heading) }}">
                    </div>
                    @php $points = old('focus_points', $page->focus_points ?? []); @endphp
                    @for($i = 0; $i < max(3, count($points)); $i++)
                    <div class="col-12">
                        <label class="form-label fw-semibold">Focus point {{ $i + 1 }}</label>
                        <input type="text" name="focus_points[]" class="form-control" value="{{ $points[$i] ?? '' }}">
                    </div>
                    @endfor
                    <div class="col-12">
                        <label class="form-label fw-semibold">Quote line</label>
                        <input type="text" name="quote_text" class="form-control" value="{{ old('quote_text', $page->quote_text) }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Video URL (YouTube)</label>
                        <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $page->video_url) }}">
                    </div>
                </div>
            </div>

            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Mission &amp; Vision</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mission title</label>
                        <input type="text" name="mission_title" class="form-control" value="{{ old('mission_title', $page->mission_title) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Vision title</label>
                        <input type="text" name="vision_title" class="form-control" value="{{ old('vision_title', $page->vision_title) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mission text</label>
                        <textarea name="mission_text" rows="4" class="form-control">{{ old('mission_text', $page->mission_text) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Vision text</label>
                        <textarea name="vision_text" rows="4" class="form-control">{{ old('vision_text', $page->vision_text) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="bns-card p-4">
                <h5 class="fw-bold mb-3">Core values</h5>
                @php
                    $values = $page->values ?? [];
                    if (old('value_titles')) {
                        $values = [];
                        foreach (old('value_titles', []) as $i => $title) {
                            $values[] = ['title' => $title, 'text' => old('value_texts')[$i] ?? ''];
                        }
                    }
                @endphp
                @for($i = 0; $i < max(3, count($values)); $i++)
                <div class="row g-2 mb-3">
                    <div class="col-md-5">
                        <input type="text" name="value_titles[]" class="form-control" placeholder="Value title"
                               value="{{ $values[$i]['title'] ?? '' }}">
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="value_texts[]" class="form-control" placeholder="Short description"
                               value="{{ $values[$i]['text'] ?? '' }}">
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <div class="col-lg-4">
            <div class="bns-card p-4 mb-4">
                <h5 class="fw-bold mb-3">Hero image</h5>
                <p class="small text-muted">Saved to <code>public/uploads/about/</code>. Falls back to Home Page background if empty.</p>
                @if($page->hero_image)
                <img src="{{ asset($page->hero_image) }}" alt="Hero" class="img-fluid rounded mb-3 border">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="remove_hero_image" value="1" id="removeHero">
                    <label class="form-check-label" for="removeHero">Remove custom hero image</label>
                </div>
                @endif
                <input type="file" name="hero_image" class="form-control" accept="image/*">
            </div>

            <div class="bns-card p-4">
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                           @checked(old('is_active', $page->is_active))>
                    <label class="form-check-label" for="isActive">Page published</label>
                </div>
                <button type="submit" class="btn btn-sop-primary w-100">
                    <i class="bi bi-check-lg me-1"></i> Save About Page
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
