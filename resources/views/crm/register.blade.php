@extends('layouts.front')

@section('title', 'BNS CRM Register')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => 'CRM Register',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => route('crm.login')],
            ['label' => 'Register'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            <div class="bns-mail-login">
                <div class="bns-mail-login__card">
                    <span class="bns-mail-login__badge">CRM Register</span>
                    <h2>Employee Register</h2>
                    <p>Create your CRM employee account. After register you are logged in to your desk.</p>

                    @if(session('status'))
                        <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bns-mail-login__alert">{{ session('error') }}</div>
                    @endif

                    <form method="POST" action="{{ route('crm.register.store') }}" class="bns-mail-login__form">
                        @csrf
                        <label for="crmRegName">Name</label>
                        <input type="text" id="crmRegName" name="name" value="{{ old('name') }}" class="@error('name') is-invalid @enderror" placeholder="Full name" required autofocus>
                        @error('name')<span class="bns-mail-login__error">{{ $message }}</span>@enderror

                        <label for="crmRegUsername">Username</label>
                        <input type="text" id="crmRegUsername" name="username" value="{{ old('username') }}" class="@error('username') is-invalid @enderror" placeholder="Login username" required autocomplete="username">
                        @error('username')<span class="bns-mail-login__error">{{ $message }}</span>@enderror

                        <label for="crmRegPassword">Password</label>
                        <input type="password" id="crmRegPassword" name="password" class="@error('password') is-invalid @enderror" placeholder="Minimum 6 characters" required autocomplete="new-password">
                        @error('password')<span class="bns-mail-login__error">{{ $message }}</span>@enderror

                        <label for="crmRegEmail">Email ID</label>
                        <input type="email" id="crmRegEmail" name="email" value="{{ old('email') }}" class="@error('email') is-invalid @enderror" placeholder="name@example.com">
                        @error('email')<span class="bns-mail-login__error">{{ $message }}</span>@enderror

                        <label for="crmRegMobile">Mobile</label>
                        <input type="text" id="crmRegMobile" name="mobile" value="{{ old('mobile') }}" placeholder="Mobile number">

                        <label for="crmRegFacility">Facility</label>
                        <input type="text" id="crmRegFacility" name="facility" value="{{ old('facility') }}" list="crmRegFacilities" placeholder="Centre / facility name">
                        <datalist id="crmRegFacilities">
                            @foreach(($facilities ?? []) as $facility)
                                <option value="{{ $facility }}"></option>
                            @endforeach
                        </datalist>
                        @error('facility')<span class="bns-mail-login__error">{{ $message }}</span>@enderror

                        <button type="submit" class="bns-mail-login__submit">
                            <i class="fas fa-user-plus" aria-hidden="true"></i>
                            Register
                        </button>
                    </form>

                    <a href="{{ route('crm.login') }}" class="bns-mail-login__register">
                        <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                        Login
                    </a>
                    <p class="bns-mail-login__switch">Already registered? Login with your username and password.</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
