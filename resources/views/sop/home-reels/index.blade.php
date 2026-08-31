@extends('sop.layouts.app')

@section('title', 'Home Page Reels')
@section('page-title', 'Home Page Reels')

@push('styles')
<style>
    .bns-reel-admin-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .bns-reel-admin-card__preview {
        position: relative;
        background: #0a1d37;
        aspect-ratio: 9 / 16;
        max-height: 280px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--bns-border);
        overflow: hidden;
    }
    .bns-reel-admin-card__preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .bns-reel-admin-card__body {
        padding: 1rem;
        flex: 1;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <p class="text-muted mb-0">
        Manage home page reels — set <strong>name</strong>, <strong>details</strong>, <strong>YouTube link</strong>, and <strong>thumbnail image</strong> for each reel.
        Changes appear on the <a href="{{ route('home') }}" target="_blank">home page</a> instantly.
    </p>
</div>

<div class="sop-card p-4 mb-4">
    <h5 class="fw-bold mb-3">Section Heading</h5>
    <form method="POST" action="{{ route('controlpanel.home-reels.settings') }}" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-3">
            <label class="form-label">Tagline</label>
            <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $section['tagline'] ?? '') }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $section['title'] ?? '') }}" required>
        </div>
        <div class="col-md-5">
            <label class="form-label">Subtitle</label>
            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $section['subtitle'] ?? '') }}">
        </div>
        <div class="col-12">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="section_enabled" name="enabled" value="1" {{ old('enabled', $section['enabled'] ?? true) ? 'checked' : '' }}>
                <label class="form-check-label" for="section_enabled">Show reels section on home page</label>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-danger">Save Section Heading</button>
        </div>
    </form>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h5 class="fw-bold mb-0">Reels ({{ $reels->count() }})</h5>
</div>

<div class="row g-3 mb-4">
    @foreach($reels as $reel)
        <div class="col-md-6 col-xl-3">
            <div class="sop-card bns-reel-admin-card">
                <div class="bns-reel-admin-card__preview">
                    <img src="{{ $reel->thumbnailUrl() }}" alt="{{ $reel->title }}">
                    @if($reel->hasCustomThumbnail())
                        <span class="position-absolute top-0 end-0 m-2 badge text-bg-success">Custom</span>
                    @else
                        <span class="position-absolute top-0 end-0 m-2 badge text-bg-secondary">Default / YouTube</span>
                    @endif
                </div>
                <div class="bns-reel-admin-card__body">
                    <form method="POST" action="{{ route('controlpanel.home-reels.update', $reel) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Name / Title</label>
                            <input type="text" name="title" class="form-control form-control-sm" value="{{ old('title', $reel->title) }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Details / Caption</label>
                            <textarea name="caption" class="form-control form-control-sm" rows="2">{{ old('caption', $reel->caption) }}</textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">YouTube Link</label>
                            <input type="url" name="youtube_url" class="form-control form-control-sm" value="{{ old('youtube_url', $reel->youtube_url) }}" required placeholder="https://www.youtube.com/shorts/...">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Sort Order</label>
                            <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ old('sort_order', $reel->sort_order) }}" min="0">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Thumbnail Image</label>
                            <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*">
                        </div>
                        @if($reel->hasCustomThumbnail())
                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="remove_thumb_{{ $reel->id }}" name="remove_thumbnail" value="1">
                                <label class="form-check-label small" for="remove_thumb_{{ $reel->id }}">Remove custom thumbnail</label>
                            </div>
                        @endif
                        <div class="form-check mb-3">
                            <input type="checkbox" class="form-check-input" id="active_{{ $reel->id }}" name="is_active" value="1" {{ $reel->is_active ? 'checked' : '' }}>
                            <label class="form-check-label small" for="active_{{ $reel->id }}">Active (show on home page)</label>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-danger flex-grow-1">Save Reel</button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('controlpanel.home-reels.destroy', $reel) }}" class="mt-2" onsubmit="return confirm('Delete this reel?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="sop-card p-4">
    <h5 class="fw-bold mb-3">Add New Reel</h5>
    <form method="POST" action="{{ route('controlpanel.home-reels.store') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        <div class="col-md-3">
            <label class="form-label">Name / Title</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">YouTube Link</label>
            <input type="url" name="youtube_url" class="form-control" required placeholder="https://www.youtube.com/shorts/...">
        </div>
        <div class="col-md-3">
            <label class="form-label">Thumbnail Image</label>
            <input type="file" name="thumbnail" class="form-control" accept="image/*">
        </div>
        <div class="col-md-1">
            <label class="form-label">Order</label>
            <input type="number" name="sort_order" class="form-control" value="{{ ($reels->max('sort_order') ?? 0) + 1 }}" min="0">
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-danger w-100">Add Reel</button>
        </div>
        <div class="col-12">
            <label class="form-label">Details / Caption</label>
            <textarea name="caption" class="form-control" rows="2"></textarea>
        </div>
    </form>
</div>
@endsection
