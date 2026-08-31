@extends('sop.layouts.guest')

@section('title', 'Reset Password')
@section('heading', 'Set new password')
@section('subheading', 'Choose a strong password for your account')

@section('content')
<form method="POST" action="{{ route('controlpanel.password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="bns-input-group">
        <label for="email">Email address</label>
        <div class="bns-input-wrap">
            <i class="bi bi-envelope"></i>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $email) }}" required>
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="bns-input-group">
        <label for="password">New password</label>
        <div class="bns-input-wrap">
            <i class="bi bi-lock"></i>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" required>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="bns-input-group">
        <label for="password_confirmation">Confirm password</label>
        <div class="bns-input-wrap">
            <i class="bi bi-shield-check"></i>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
        </div>
    </div>

    <button type="submit" class="btn bns-btn-primary w-100">
        <i class="bi bi-check-lg me-1"></i> Reset Password
    </button>

    <div class="bns-auth-links justify-content-center">
        <a href="{{ route('controlpanel.login') }}">Back to login</a>
    </div>
</form>
@endsection
