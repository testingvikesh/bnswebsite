@extends('sop.layouts.app')

@section('title', 'Edit Testimonial')
@section('page-title', 'Edit Testimonial')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="sop-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-semibold mb-1">Edit Testimonial</h5>
                    <p class="text-muted small mb-0">{{ $testimonial->full_name }}</p>
                </div>
                <a href="{{ route('controlpanel.testimonials.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
            <form method="POST" action="{{ route('controlpanel.testimonials.update', $testimonial) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('sop.testimonials._form', ['testimonial' => $testimonial, 'editing' => true])
                <button type="submit" class="btn btn-sop-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
            </form>
        </div>
    </div>
</div>
@endsection
