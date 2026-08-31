@extends('sop.layouts.app')

@section('title', 'Edit Faculty')
@section('page-title', 'Edit Faculty')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="sop-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-semibold mb-1">Edit Faculty Profile</h5>
                    <p class="text-muted small mb-0">{{ $faculty->display_name }}</p>
                </div>
                <a href="{{ route('controlpanel.visiting-faculty.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
            <form method="POST" action="{{ route('controlpanel.visiting-faculty.update', $faculty) }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                @include('sop.visiting-faculty._form', ['faculty' => $faculty, 'editing' => true, 'titlePrefixes' => $titlePrefixes])
                <button type="submit" class="btn btn-sop-primary"><i class="bi bi-check-lg me-1"></i> Save Changes</button>
            </form>
        </div>
    </div>
</div>
@endsection
