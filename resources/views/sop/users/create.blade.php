@extends('sop.layouts.app')

@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="sop-card p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h5 class="fw-semibold mb-1">Create new user</h5>
                    <p class="text-muted small mb-0">Add a user who can access the Control Panel</p>
                </div>
                <a href="{{ route('controlpanel.users.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
            </div>

            <form method="POST" action="{{ route('controlpanel.users.store') }}">
                @csrf
                @include('sop.users._form', ['user' => $user, 'editing' => false])
                <button type="submit" class="btn btn-sop-primary">Create User</button>
            </form>
        </div>
    </div>
</div>
@endsection
