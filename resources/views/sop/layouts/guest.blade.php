<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token-url" content="{{ route('csrf-token') }}">
    <title>@yield('title', 'Admin Panel') — BNS School</title>
    <link rel="icon" type="image/png" href="{{ $siteFaviconUrl }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-panel.css') }}" rel="stylesheet">
</head>
<body class="bns-admin-auth">
    <div class="bns-auth-shell">
        <aside class="bns-auth-brand">
            <div class="bns-auth-brand__logo">
                <a href="{{ url('/') }}">
                    <img src="{{ $siteLogoUrl }}" alt="{{ $siteLogoAlt }}">
                </a>
            </div>
            <div>
                <h1 class="bns-auth-brand__title">Business Navachar School</h1>
                <p class="bns-auth-brand__text">
                    Manage website content, team profiles, testimonials, and user accounts from one secure admin panel.
                </p>
                <ul class="bns-auth-brand__features">
                    <li><i class="bi bi-shield-lock"></i> Secure login &amp; password recovery</li>
                    <li><i class="bi bi-speedometer2"></i> Clean dashboard overview</li>
                    <li><i class="bi bi-globe2"></i> Update public website content</li>
                </ul>
            </div>
            <p class="mb-0 small opacity-75">&copy; {{ date('Y') }} BNS School. All rights reserved.</p>
        </aside>

        <div class="bns-auth-panel">
            <div class="bns-auth-card">
                <div class="bns-auth-card__mobile-logo">
                    <a href="{{ url('/') }}"><img src="{{ $siteLogoUrl }}" alt="{{ $siteLogoAlt }}"></a>
                </div>
                <h2>@yield('heading', 'Welcome back')</h2>
                <p class="bns-auth-sub">@yield('subheading', 'Sign in to your admin account')</p>

                @if (session('status'))
                    <div class="alert alert-success py-2 small border-0">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger py-2 small border-0">{{ session('error') }}</div>
                @endif

                @if ($errors->any() && ! $errors->has('email') && ! $errors->has('password') && ! $errors->has('name') && ! $errors->has('current_password'))
                    <div class="alert alert-danger py-2 small border-0">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ bns_vasset('assets/js/bns-csrf.js') }}"></script>
    @stack('scripts')
</body>
</html>
