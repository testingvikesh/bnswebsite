@extends('sop.layouts.app')

@section('title', 'Sponsors Page')
@section('page-title', 'Sponsors Page')

@push('styles')
<style>
    .bns-sponsor-admin-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .bns-sponsor-admin-card__preview {
        position: relative;
        background: #eef2f7;
        min-height: 220px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--bns-border);
        overflow: hidden;
    }
    .bns-sponsor-admin-card__preview img {
        width: 100%;
        height: 220px;
        object-fit: cover;
        object-position: center 22%;
    }
    .bns-sponsor-admin-card__body {
        padding: 1rem;
        flex: 1;
    }
</style>
@endpush

@section('content')
<div class="mb-4">
    <p class="text-muted mb-0">
        Manage <a href="{{ route('about.sponsors') }}" target="_blank">Meet Our Sponsors</a> page —
        set <strong>name</strong>, <strong>designation</strong> (e.g. President), <strong>details</strong>, and <strong>photo</strong> for each sponsor.
    </p>
</div>

<div class="sop-card p-4 mb-4">
    <h5 class="fw-bold mb-3">Section Heading</h5>
    <form method="POST" action="{{ route('controlpanel.sponsor-members.settings') }}" class="row g-3">
        @csrf
        @method('PUT')
        <div class="col-md-3">
            <label class="form-label">Section Label</label>
            <input type="text" name="section_label" class="form-control" value="{{ old('section_label', $section['section_label'] ?? '') }}" placeholder="Partners">
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
            <button type="submit" class="btn btn-danger">Save Section Heading</button>
        </div>
    </form>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h5 class="fw-bold mb-0">Sponsors ({{ $members->count() }})</h5>
</div>

<div class="row g-3 mb-4">
    @foreach($members as $member)
        <div class="col-md-6">
            <div class="sop-card bns-sponsor-admin-card">
                <div class="bns-sponsor-admin-card__preview">
                    @if($member->photoUrl())
                        <img src="{{ $member->photoUrl() }}" alt="{{ $member->name }}">
                    @else
                        <i class="fas fa-user-tie fa-3x text-muted"></i>
                    @endif
                    @if($member->hasCustomPhoto())
                        <span class="position-absolute top-0 end-0 m-2 badge text-bg-success">Custom</span>
                    @endif
                </div>
                <div class="bns-sponsor-admin-card__body">
                    <form method="POST" action="{{ route('controlpanel.sponsor-members.update', $member) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Name</label>
                            <input type="text" name="name" class="form-control form-control-sm" value="{{ old('name', $member->name) }}" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Designation</label>
                            <input type="text" name="designation" class="form-control form-control-sm" value="{{ old('designation', $member->designation) }}" placeholder="President">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Details</label>
                            <textarea name="profile" class="form-control form-control-sm" rows="3">{{ old('profile', $member->profile) }}</textarea>
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Photo</label>
                            <input type="file" name="photo" class="form-control form-control-sm" accept="image/*">
                            @if($member->hasCustomPhoto())
                                <div class="form-check mt-1">
                                    <input type="checkbox" class="form-check-input" id="remove_photo_{{ $member->id }}" name="remove_photo" value="1">
                                    <label class="form-check-label small" for="remove_photo_{{ $member->id }}">Remove uploaded photo</label>
                                </div>
                            @endif
                        </div>
                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small fw-semibold">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control form-control-sm" value="{{ old('sort_order', $member->sort_order) }}" min="0" max="999">
                            </div>
                            <div class="col-6 d-flex align-items-end">
                                <div class="form-check mb-2">
                                    <input type="checkbox" class="form-check-input" id="active_{{ $member->id }}" name="is_active" value="1" {{ old('is_active', $member->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label small" for="active_{{ $member->id }}">Active</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-sm btn-danger w-100">Save</button>
                    </form>
                    <form method="POST" action="{{ route('controlpanel.sponsor-members.destroy', $member) }}" class="mt-2" onsubmit="return confirm('Delete this sponsor?');">
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
    <h5 class="fw-bold mb-3">Add Sponsor</h5>
    <form method="POST" action="{{ route('controlpanel.sponsor-members.store') }}" enctype="multipart/form-data" class="row g-3">
        @csrf
        <div class="col-md-4">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Designation</label>
            <input type="text" name="designation" class="form-control" placeholder="Vice President">
        </div>
        <div class="col-md-4">
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" class="form-control" min="0" max="999">
        </div>
        <div class="col-12">
            <label class="form-label">Details</label>
            <textarea name="profile" class="form-control" rows="2"></textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">Photo</label>
            <input type="file" name="photo" class="form-control" accept="image/*">
        </div>
        <div class="col-md-6 d-flex align-items-end">
            <div class="form-check mb-2">
                <input type="checkbox" class="form-check-input" id="new_active" name="is_active" value="1" checked>
                <label class="form-check-label" for="new_active">Active</label>
            </div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-danger">Add Sponsor</button>
        </div>
    </form>
</div>
@endsection
