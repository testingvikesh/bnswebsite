@extends('layouts.front')

@section('title', 'BNS Mail Login')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal">
    @include('partials.page-header', [
        'title' => 'BNS Mail',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'BNS Mail'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            <div class="bns-mail-login">
                <div class="bns-mail-login__card">
                    <span class="bns-mail-login__badge">Secure Access</span>
                    <h2>Login to BNS Mail</h2>
                    <p>Enter your mail portal credentials to open Student Mail or Business Coach Mail.</p>

                    @if(session('status'))
                        <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bns-mail-login__alert">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('mail.login.store') }}" class="bns-mail-login__form">
                        @csrf
                        <label for="mailUsername">Username</label>
                        <input
                            type="text"
                            id="mailUsername"
                            name="username"
                            value="{{ old('username') }}"
                            class="@error('username') is-invalid @enderror"
                            placeholder="Enter username"
                            required
                            autofocus
                            autocomplete="username"
                        >
                        @error('username')
                            <span class="bns-mail-login__error">{{ $message }}</span>
                        @enderror

                        <label for="mailPassword">Password</label>
                        <input
                            type="password"
                            id="mailPassword"
                            name="password"
                            class="@error('password') is-invalid @enderror"
                            placeholder="Enter password"
                            required
                            autocomplete="current-password"
                        >
                        @error('password')
                            <span class="bns-mail-login__error">{{ $message }}</span>
                        @enderror

                        <button type="submit" class="bns-mail-login__submit">
                            <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
