<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token-url" content="{{ route('csrf-token') }}">
    <title>@yield('title', 'Reporting Dashboard') — BNS School</title>
    <link rel="icon" type="image/png" href="{{ $siteFaviconUrl }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;0,9..40,800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/admin-panel.css') }}" rel="stylesheet">
    <link href="{{ bns_vasset('assets/css/bns-modals.css') }}" rel="stylesheet">
    <link href="{{ bns_vasset('assets/css/reporting-dashboard.css') }}" rel="stylesheet">
    <style>
        .bns-reporting-btn-view {
            background: linear-gradient(135deg, #ff5544, #e04a3a) !important;
            border: none !important;
            color: #fff !important;
            font-weight: 700;
        }
    </style>
    @stack('styles')
</head>
<body class="bns-reporting-shell">
    <header class="bns-reporting-topbar">
        <a href="{{ route('reporting.index') }}" class="bns-reporting-topbar__brand text-decoration-none">
            <img src="{{ $siteLogoUrl }}" alt="{{ $siteLogoAlt }}">
            <div>
                <div class="bns-reporting-topbar__title">Santacruz School Reporting Status</div>
            </div>
        </a>
        <div class="d-flex align-items-center gap-2">
            <div class="bns-reporting-topbar__user d-none d-sm-flex">
                <i class="bi bi-person-circle"></i>
                <span>{{ auth()->user()->name ?? auth()->user()->email }}</span>
            </div>
            <button type="button" class="btn btn-sm bns-reporting-btn-logout" data-bs-toggle="modal" data-bs-target="#reportingLogoutModal">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </div>
    </header>

    <main class="bns-reporting-content">
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <button type="button" class="bns-reporting-back-top" aria-label="Back to top">
        <i class="bi bi-arrow-up"></i>
        <span>Top</span>
    </button>

    @include('reporting.partials.logout-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ bns_vasset('assets/js/bns-csrf.js') }}"></script>
    <script src="{{ bns_vasset('assets/js/reporting-dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>