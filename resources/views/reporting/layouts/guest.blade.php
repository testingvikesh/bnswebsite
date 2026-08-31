<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token-url" content="{{ route('csrf-token') }}">
    <title>@yield('title', 'Reporting') — BNS School</title>
    <link rel="icon" type="image/png" href="{{ $siteFaviconUrl }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-panel.css') }}" rel="stylesheet">
    @stack('styles')
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
                <h1 class="bns-auth-brand__title">Santacruz School Reporting Status</h1>
                <p class="bns-auth-brand__text">
                    View and filter all website contact form submissions — introduction sessions, inquiries, confirm admissions, and contact page enquiries.
                </p>
                <ul class="bns-auth-brand__features">
                    <li><i class="bi bi-funnel"></i> Filter by form type, program, category &amp; date</li>
                    <li><i class="bi bi-table"></i> Full submission details in one dashboard</li>
                    <li><i class="bi bi-shield-lock"></i> Secure admin-only access</li>
                </ul>
            </div>
            <p class="mb-0 small opacity-75">&copy; {{ date('Y') }} BNS School. All rights reserved.</p>
        </aside>

        <div class="bns-auth-panel">
            <div class="bns-auth-card">
                <div class="bns-auth-card__mobile-logo">
                    <a href="{{ url('/') }}"><img src="{{ $siteLogoUrl }}" alt="{{ $siteLogoAlt }}"></a>
                </div>
                <h2>@yield('heading', 'Sign in')</h2>
                <p class="bns-auth-sub">@yield('subheading', 'Enter username and password to open the reporting dashboard')</p>

                @if (session('status'))
                    <div class="alert alert-success py-2 small border-0">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger py-2 small border-0">{{ session('error') }}</div>
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
