@extends('sop.layouts.app')

@section('title', 'Add Testimonial')
@section('page-title', 'Add Testimonial')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="sop-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-semibold mb-1">Add Testimonial</h5>
                    <p class="text-muted small mb-0">Website display profile format</p>
                </div>
                <a href="{{ route('controlpanel.testimonials.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
            <form method="POST" action="{{ route('controlpanel.testimonials.store') }}" enctype="multipart/form-data">
                @csrf
                @include('sop.testimonials._form', ['testimonial' => $testimonial, 'editing' => false])
                <button type="submit" class="btn btn-sop-primary"><i class="bi bi-check-lg me-1"></i> Add Testimonial</button>
            </form>
        </div>
    </div>
</div>
@endsection
