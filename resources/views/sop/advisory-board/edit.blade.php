@extends('sop.layouts.app')

@section('title', 'Edit Advisor')
@section('page-title', 'Edit Advisor')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="sop-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-semibold mb-1">Edit Advisory Board Member</h5>
                    <p class="text-muted small mb-0">{{ $member->full_name }}</p>
                </div>
                <a href="{{ route('controlpanel.advisory-board.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

            <form method="POST" action="{{ route('controlpanel.advisory-board.update', $member) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('sop.advisory-board._form', ['member' => $member, 'editing' => true])
                <button type="submit" class="btn btn-sop-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Changes
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
