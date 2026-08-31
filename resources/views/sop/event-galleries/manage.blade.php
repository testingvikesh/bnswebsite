@extends('sop.layouts.app')

@section('title', 'Manage Gallery — '.$gallery->title)
@section('page-title', 'Manage: '.$gallery->title)

@section('content')
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <a href="{{ route('controlpanel.event-galleries.index') }}" class="btn btn-sm btn-outline-secondary">&larr; All Event Galleries</a>
    <a href="{{ route('gallery.index') }}#gallery-{{ $gallery->slug }}" class="btn btn-sm btn-outline-primary" target="_blank">View Public Page</a>
</div>

<div class="sop-card p-4 mb-4">
    <h5 class="fw-bold mb-3">Event Details</h5>
    <form method="POST" action="{{ route('controlpanel.event-galleries.update', $gallery) }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-4">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $gallery->title) }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label">Subtitle</label>
            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $gallery->subtitle) }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Event Date</label>
            <input type="date" name="event_date" class="form-control" value="{{ old('event_date', optional($gallery->event_date)->format('Y-m-d')) }}">
        </div>
        <div class="col-md-1">
            <label class="form-label">Order</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $gallery->sort_order) }}" min="0" max="999">
        </div>
        <div class="col-md-2">
            <label class="form-label">Cover Image</label>
            <input type="file" name="cover" class="form-control" accept="image/*">
        </div>
        @if($gallery->coverUrl())
            <div class="col-12">
                <img src="{{ $gallery->coverUrl() }}" alt="" class="rounded mb-2" style="max-height:100px;">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="remove_cover" name="remove_cover" value="1">
                    <label class="form-check-label" for="remove_cover">Remove cover</label>
                </div>
            </div>
        @endif
        <div class="col-md-7">
            <label class="form-label">Picasa / Google Photos Album Link</label>
            <input type="url" name="picasa_url" class="form-control" value="{{ old('picasa_url', $gallery->picasa_url) }}" placeholder="https://photos.app.goo.gl/... or Picasa album URL">
            <div class="form-text">Paste the shared album link. Visitors can open the full album from the Gallery page.</div>
        </div>
        <div class="col-md-5">
            <label class="form-label">Album Button Label (optional)</label>
            <input type="text" name="picasa_label" class="form-control" value="{{ old('picasa_label', $gallery->picasa_label) }}" placeholder="e.g. View Full Album on Google Photos">
        </div>
        <div class="col-12">
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active</label>
            </div>
            <button type="submit" class="btn btn-danger">Save Event Details</button>
        </div>
    </form>
</div>

<div class="row g-4">
    <div class="col-lg-6">
        <div class="sop-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Upload Photos</h5>
            <form method="POST" action="{{ route('controlpanel.event-galleries.photos.store', $gallery) }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label">Select Photos (multiple) <span class="text-danger">*</span></label>
                    <input type="file" name="photos[]" class="form-control" accept="image/*" multiple required>
                </div>
                <div class="col-12">
                    <label class="form-label">Optional Title for batch</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Stage photos">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-danger">Upload Photos</button>
                </div>
            </form>
        </div>

        <h6 class="fw-bold mb-3">Photos ({{ $gallery->photos->count() }})</h6>
        @forelse($gallery->photos as $photo)
            <div class="sop-card p-3 mb-3">
                <div class="row g-3 align-items-start">
                    <div class="col-4">
                        <img src="{{ $photo->url() }}" alt="" class="img-fluid rounded" style="width:100%;height:110px;object-fit:cover;">
                    </div>
                    <div class="col-8">
                        <form method="POST" action="{{ route('controlpanel.event-galleries.photos.update', [$gallery, $photo]) }}" enctype="multipart/form-data" class="row g-2">
                            @csrf
                            @method('PUT')
                            <div class="col-8">
                                <input type="text" name="title" class="form-control form-control-sm" value="{{ $photo->title }}" placeholder="Title">
                            </div>
                            <div class="col-4">
                                <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $photo->sort_order }}" min="0">
                            </div>
                            <div class="col-12">
                                <input type="text" name="caption" class="form-control form-control-sm" value="{{ $photo->caption }}" placeholder="Caption">
                            </div>
                            <div class="col-12">
                                <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                            </div>
                            <div class="col-12 d-flex flex-wrap gap-2 align-items-center">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="photo_active_{{ $photo->id }}" name="is_active" value="1" {{ $photo->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="photo_active_{{ $photo->id }}">Active</label>
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('controlpanel.event-galleries.photos.destroy', [$gallery, $photo]) }}" class="mt-2" onsubmit="return confirm('Delete this photo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted small mb-4">No photos uploaded yet.</div>
        @endforelse
    </div>

    <div class="col-lg-6">
        <div class="sop-card p-4 mb-4">
            <h5 class="fw-bold mb-3">Add YouTube Video Reel</h5>
            <form method="POST" action="{{ route('controlpanel.event-galleries.reels.store', $gallery) }}" enctype="multipart/form-data" class="row g-3">
                @csrf
                <div class="col-12">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-12">
                    <label class="form-label">YouTube URL <span class="text-danger">*</span></label>
                    <input type="url" name="youtube_url" class="form-control" required placeholder="https://www.youtube.com/watch?v=... or /shorts/...">
                </div>
                <div class="col-12">
                    <label class="form-label">Caption</label>
                    <textarea name="caption" class="form-control" rows="2"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Custom Thumbnail (optional)</label>
                    <input type="file" name="thumbnail" class="form-control" accept="image/*">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Order</label>
                    <input type="number" name="sort_order" class="form-control" min="0" max="999">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" id="reel_active_new" name="is_active" value="1" checked>
                        <label class="form-check-label" for="reel_active_new">Active</label>
                    </div>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-danger">Add Reel</button>
                </div>
            </form>
        </div>

        <h6 class="fw-bold mb-3">Reels ({{ $gallery->reels->count() }})</h6>
        @forelse($gallery->reels as $reel)
            <div class="sop-card p-3 mb-3">
                <div class="row g-3">
                    <div class="col-4">
                        <img src="{{ $reel->thumbnailUrl() }}" alt="" class="img-fluid rounded" style="width:100%;aspect-ratio:9/16;max-height:160px;object-fit:cover;">
                    </div>
                    <div class="col-8">
                        <form method="POST" action="{{ route('controlpanel.event-galleries.reels.update', [$gallery, $reel]) }}" enctype="multipart/form-data" class="row g-2">
                            @csrf
                            @method('PUT')
                            <div class="col-12">
                                <input type="text" name="title" class="form-control form-control-sm" value="{{ $reel->title }}" required>
                            </div>
                            <div class="col-12">
                                <input type="url" name="youtube_url" class="form-control form-control-sm" value="{{ $reel->youtube_url }}" required>
                            </div>
                            <div class="col-12">
                                <textarea name="caption" class="form-control form-control-sm" rows="2">{{ $reel->caption }}</textarea>
                            </div>
                            <div class="col-8">
                                <input type="file" name="thumbnail" class="form-control form-control-sm" accept="image/*">
                            </div>
                            <div class="col-4">
                                <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ $reel->sort_order }}" min="0">
                            </div>
                            @if($reel->thumbnail_path)
                                <div class="col-12">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="remove_thumb_{{ $reel->id }}" name="remove_thumbnail" value="1">
                                        <label class="form-check-label" for="remove_thumb_{{ $reel->id }}">Remove custom thumbnail</label>
                                    </div>
                                </div>
                            @endif
                            <div class="col-12 d-flex flex-wrap gap-2 align-items-center">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="reel_active_{{ $reel->id }}" name="is_active" value="1" {{ $reel->is_active ? 'checked' : '' }}>
                                    <label class="form-check-label" for="reel_active_{{ $reel->id }}">Active</label>
                                </div>
                                <button type="submit" class="btn btn-sm btn-outline-primary">Save</button>
                            </div>
                        </form>
                        <form method="POST" action="{{ route('controlpanel.event-galleries.reels.destroy', [$gallery, $reel]) }}" class="mt-2" onsubmit="return confirm('Delete this reel?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-muted small">No YouTube reels added yet.</div>
        @endforelse
    </div>
</div>
@endsection
