@extends('sop.layouts.app')

@section('title', 'Change Password')
@section('page-title', 'Change Password')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-6 col-xl-5">
        <div class="bns-card p-4">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bns-stat__icon bg-danger bg-opacity-10 text-danger">
                    <i class="bi bi-key"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-0">Update your password</h5>
                    <p class="text-muted small mb-0">Use a strong password you do not use elsewhere.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('controlpanel.password.change.update') }}">
                @csrf

                <div class="mb-3">
                    <label for="current_password" class="form-label fw-semibold">Current password</label>
                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                           id="current_password" name="current_password" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">New password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           id="password" name="password" required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label fw-semibold">Confirm new password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn bns-btn-primary px-4">
                        <i class="bi bi-check-lg me-1"></i> Save Password
                    </button>
                    <a href="{{ route('controlpanel.dashboard') }}" class="btn btn-light border">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
