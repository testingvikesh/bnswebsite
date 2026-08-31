@extends('reporting.layouts.guest')

@section('title', 'Santacruz School Reporting Status')
@section('heading', 'Santacruz School Reporting Status')
@section('subheading', 'Enter your username and password to view reporting status')

@section('content')
<form method="POST" action="{{ route('reporting.login') }}">
    @csrf

    <div class="bns-input-group">
        <label for="username">Username / Email</label>
        <div class="bns-input-wrap">
            <i class="bi bi-person"></i>
            <input type="text" class="form-control @error('username') is-invalid @enderror"
                   id="username" name="username" value="{{ old('username') }}"
                   placeholder="Enter username or email" required autofocus>
        </div>
        @error('username')
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
        <i class="bi bi-box-arrow-in-right me-1"></i> Login to Dashboard
    </button>
</form>
@endsection
