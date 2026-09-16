@extends('layouts.front')

@section('title', 'BNS CRM Login')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => $page['title'] ?? 'BNS CRM',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            <div class="bns-mail-login">
                <div class="bns-mail-login__card">
                    <span class="bns-mail-login__badge">CRM Access</span>
                    <h2>Login to BNS CRM</h2>
                    <p>Admin can assign members. Employees login here to call assigned members and manage 3 follow-ups.</p>

                    @if(session('status'))
                        <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bns-mail-login__alert">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('crm.login.store') }}" class="bns-mail-login__form">
                        @csrf
                        <label for="crmUsername">Username</label>
                        <input
                            type="text"
                            id="crmUsername"
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

                        <label for="crmPassword">Password</label>
                        <input
                            type="password"
                            id="crmPassword"
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

                    <a href="{{ route('crm.register') }}" class="bns-mail-login__register">
                        <i class="fas fa-user-plus" aria-hidden="true"></i>
                        Register
                    </a>
                    <p class="bns-mail-login__switch">New employee? Register, then you can login to your desk.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
