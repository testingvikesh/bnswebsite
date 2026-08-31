@extends('sop.layouts.guest')

@section('title', 'Login')
@section('heading', 'Sign in')
@section('subheading', 'Access your BNS admin account')

@section('content')
<form method="POST" action="{{ route('controlpanel.login') }}">
    @csrf

    <div class="bns-input-group">
        <label for="email">Email address</label>
        <div class="bns-input-wrap">
            <i class="bi bi-envelope"></i>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required autofocus>
        </div>
        @error('email')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="bns-input-group">
        <label for="password">Password</label>
        <div class="bns-input-wrap">
            <i class="bi bi-lock"></i>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" placeholder="Enter your password" required>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
        <label class="form-check-label" for="remember">Remember me on this device</label>
    </div>

    <button type="submit" class="btn bns-btn-primary w-100">
        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
    </button>

    <div class="bns-auth-links">
        <a href="{{ route('controlpanel.password.request') }}">Forgot password?</a>
        <a href="{{ route('controlpanel.register') }}">Create account</a>
    </div>
</form>
@endsection
