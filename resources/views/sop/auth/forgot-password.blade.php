@extends('sop.layouts.guest')

@section('title', 'Forgot Password')
@section('heading', 'Forgot password?')
@section('subheading', 'We will email you a reset link')

@section('content')
<form method="POST" action="{{ route('controlpanel.password.email') }}">
    @csrf

    <div class="bns-input-group">
        <label for="email">Email address</label>
        <div class="bns-input-wrap">
            <i class="bi bi-envelope"></i>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" placeholder="Account email" required autofocus>
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn bns-btn-primary w-100">
        <i class="bi bi-send me-1"></i> Send Reset Link
    </button>

    <div class="bns-auth-links justify-content-center">
        <a href="{{ route('controlpanel.login') }}"><i class="bi bi-arrow-left me-1"></i> Back to login</a>
    </div>
</form>
@endsection
