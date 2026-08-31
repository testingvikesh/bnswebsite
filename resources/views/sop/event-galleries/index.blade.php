@extends('sop.layouts.app')

@section('title', 'Event Galleries')
@section('page-title', 'Event Galleries')

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

<div class="mb-4">
    <p class="text-muted mb-0">
        Create an event album, then upload <strong>photos</strong>, add a <strong>Picasa / Google Photos</strong> album link, and add <strong>YouTube video reels</strong>.
        Public page: <a href="{{ route('gallery.index') }}" target="_blank">/gallery</a>
    </p>
</div>

<div class="sop-card p-4 mb-4">
    <h5 class="fw-bold mb-3">Add Event Gallery</h5>
    <form method="POST" action="{{ route('controlpanel.event-galleries.store') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        <div class="col-md-4">
            <label class="form-label">Event Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. Introduction Session 1">
        </div>
        <div class="col-md-3">
            <label class="form-label">Subtitle</label>
            <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle') }}" placeholder="Optional">
        </div>
        <div class="col-md-2">
            <label class="form-label">Event Date</label>
            <input type="date" name="event_date" class="form-control" value="{{ old('event_date') }}">
        </div>
        <div class="col-md-1">
            <label class="form-label">Order</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order') }}" min="0" max="999">
        </div>
        <div class="col-md-2">
            <label class="form-label">Cover Image</label>
            <input type="file" name="cover" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6">
            <label class="form-label">Picasa / Google Photos Album Link</label>
            <input type="url" name="picasa_url" class="form-control" value="{{ old('picasa_url') }}" placeholder="https://photos.app.goo.gl/... or Picasa album URL">
        </div>
        <div class="col-md-6">
            <label class="form-label">Album Button Label (optional)</label>
            <input type="text" name="picasa_label" class="form-control" value="{{ old('picasa_label') }}" placeholder="e.g. View Full Album on Google Photos">
        </div>
        <div class="col-12">
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Active (show on public Gallery page)</label>
            </div>
            <button type="submit" class="btn btn-danger">Create Event Gallery</button>
        </div>
    </form>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h5 class="fw-bold mb-0">Events ({{ $galleries->count() }})</h5>
</div>

@if($galleries->isEmpty())
    <div class="sop-card p-4 text-muted">No event galleries yet. Create one above.</div>
@else
    <div class="row g-3">
        @foreach($galleries as $gallery)
            <div class="col-md-6 col-xl-4">
                <div class="sop-card p-3 h-100">
                    @if($gallery->coverUrl())
                        <img src="{{ $gallery->coverUrl() }}" alt="{{ $gallery->title }}" class="img-fluid rounded mb-3" style="width:100%;height:160px;object-fit:cover;">
                    @endif
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                        <div>
                            <h6 class="fw-bold mb-1">{{ $gallery->title }}</h6>
                            @if($gallery->dateLabel())
                                <div class="small text-muted">{{ $gallery->dateLabel() }}</div>
                            @endif
                        </div>
                        <span class="badge {{ $gallery->is_active ? 'bg-success' : 'bg-secondary' }}">
                            {{ $gallery->is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </div>
                    <div class="small text-muted mb-3">
                        {{ $gallery->photos_count }} photos · {{ $gallery->reels_count }} reels
                        @if($gallery->hasPicasaLink())
                            · Picasa link
                        @endif
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('controlpanel.event-galleries.manage', $gallery) }}" class="btn btn-sm btn-danger">
                            Manage Photos & Reels
                        </a>
                        <form method="POST" action="{{ route('controlpanel.event-galleries.destroy', $gallery) }}" onsubmit="return confirm('Delete this event gallery and all media?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
@endsection
