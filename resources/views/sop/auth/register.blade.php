@extends('sop.layouts.guest')

@section('title', 'Register')
@section('heading', 'Create account')
@section('subheading', 'Register for BNS admin panel access')

@section('content')
<form method="POST" action="{{ route('controlpanel.register') }}">
    @csrf

    <div class="bns-input-group">
        <label for="name">Full name</label>
        <div class="bns-input-wrap">
            <i class="bi bi-person"></i>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name') }}" placeholder="Your full name" required autofocus>
        </div>
        @error('name')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="bns-input-group">
        <label for="email">Email address</label>
        <div class="bns-input-wrap">
            <i class="bi bi-envelope"></i>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
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
                   id="password" name="password" placeholder="Min. 8 characters" required>
        </div>
        @error('password')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="bns-input-group">
        <label for="password_confirmation">Confirm password</label>
        <div class="bns-input-wrap">
            <i class="bi bi-shield-check"></i>
            <input type="password" class="form-control"
                   id="password_confirmation" name="password_confirmation" placeholder="Repeat password" required>
        </div>
    </div>

    <button type="submit" class="btn bns-btn-primary w-100">
        <i class="bi bi-person-plus me-1"></i> Create Account
    </button>

    <div class="bns-auth-links justify-content-center">
        <a href="{{ route('controlpanel.login') }}">Already have an account? Sign in</a>
    </div>
</form>
@endsection
