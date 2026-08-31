@extends('sop.layouts.app')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="sop-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-semibold mb-1">Account details</h5>
                    <p class="text-muted small mb-0">{{ $user->email }}</p>
                </div>
                <a href="{{ route('controlpanel.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

            <form method="POST" action="{{ route('controlpanel.users.update', $user) }}">
                @csrf
                @method('PUT')
                @include('sop.users._form', ['user' => $user, 'editing' => true])
                <button type="submit" class="btn btn-sop-primary w-100">
                    <i class="bi bi-check-lg me-1"></i> Update User
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
