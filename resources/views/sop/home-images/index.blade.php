@extends('sop.layouts.app')

@section('title', 'Home Page Images')
@section('page-title', 'Home Page Images')

@push('styles')
<style>
    .bns-home-img-card {
        height: 100%;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .bns-home-img-card__preview {
        position: relative;
        background: #f1f5f9;
        min-height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid var(--bns-border);
    }
    .bns-home-img-card__preview img {
        max-width: 100%;
        max-height: 180px;
        object-fit: contain;
    }
    .bns-home-img-card__preview--bg img {
        width: 100%;
        max-height: 140px;
        object-fit: cover;
    }
    .bns-home-img-card__body { padding: 1rem; flex: 1; display: flex; flex-direction: column; }
    .bns-home-img-card__key {
        font-size: 0.72rem;
        color: var(--bns-muted);
        font-family: ui-monospace, monospace;
    }
    .bns-section-title {
        font-size: 1rem;
        font-weight: 700;
        margin: 1.75rem 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--bns-primary-soft);
        color: var(--bns-text);
    }
    .bns-section-title:first-child { margin-top: 0; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <p class="text-muted mb-0">
        Upload images for the homepage. Files are saved in <code>public/uploads/home/</code>.
        Leave unchanged to keep the default theme image.
    </p>
</div>

@foreach ($sections as $sectionName => $images)
    <h3 class="bns-section-title"><i class="bi bi-folder2-open me-2 text-danger"></i>{{ $sectionName }}</h3>
    <div class="row g-3 mb-2">
        @foreach ($images as $image)
            <div class="col-md-6 col-xl-4">
                <div class="bns-card bns-home-img-card">
                    <div class="bns-home-img-card__preview {{ str_contains($image->key, '_bg') || str_contains($image->key, 'bg_') ? 'bns-home-img-card__preview--bg' : '' }}">
                        <img src="{{ $image->url() }}" alt="{{ $image->label }}">
                        @if ($image->isCustom())
                            <span class="position-absolute top-0 end-0 m-2 badge text-bg-success">Custom</span>
                        @else
                            <span class="position-absolute top-0 end-0 m-2 badge text-bg-secondary">Default</span>
                        @endif
                    </div>
                    <div class="bns-home-img-card__body">
                        <h6 class="fw-semibold mb-1">{{ $image->label }}</h6>
                        <div class="bns-home-img-card__key mb-2">{{ $image->key }}</div>

                        <form method="POST" action="{{ route('controlpanel.home-images.update', $image) }}"
                              enctype="multipart/form-data" class="mt-auto">
                            @csrf
                            <div class="mb-2">
                                <input type="file" name="image" class="form-control form-control-sm" accept="image/*" required>
                            </div>
                            <button type="submit" class="btn btn-sop-primary btn-sm w-100">
                                <i class="bi bi-upload me-1"></i> Upload Image
                            </button>
                        </form>

                        @if ($image->isCustom())
                            <form method="POST" action="{{ route('controlpanel.home-images.destroy', $image) }}"
                                  class="mt-2" onsubmit="return confirm('Reset to default image?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary btn-sm w-100">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset to Default
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endforeach
@endsection
