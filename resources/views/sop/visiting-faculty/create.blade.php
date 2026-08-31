@extends('sop.layouts.app')

@section('title', 'Add Faculty')
@section('page-title', 'Add Faculty')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="sop-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-semibold mb-1">Add Visiting Expert Faculty</h5>
                    <p class="text-muted small mb-0">Full faculty profile for the public page</p>
                </div>
                <a href="{{ route('controlpanel.visiting-faculty.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>
            <form method="POST" action="{{ route('controlpanel.visiting-faculty.store') }}" enctype="multipart/form-data">
                @csrf
                @include('sop.visiting-faculty._form', ['faculty' => $faculty, 'editing' => false, 'titlePrefixes' => $titlePrefixes])
                <button type="submit" class="btn btn-sop-primary"><i class="bi bi-check-lg me-1"></i> Add Faculty</button>
            </form>
        </div>
    </div>
</div>
@endsection
