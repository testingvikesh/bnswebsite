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
    <link href="{{ bns_vasset('assets/css/bns-modals.css') }}" rel="stylesheet">
    <style>
        .sop-card { background: var(--bns-card); border: 1px solid var(--bns-border); border-radius: var(--bns-radius); box-shadow: 0 2px 12px rgba(15, 23, 42, 0.04); }
        .sop-stat { padding: 1.25rem 1.35rem; height: 100%; border-radius: var(--bns-radius); background: #fff; border: 1px solid var(--bns-border); display: flex; align-items: center; gap: 1rem; }
        .sop-stat__icon { width: 52px; height: 52px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; }
    </style>
    @stack('styles')
</head>
<body class="bns-admin">
    @include('sop.partials.sidebar')

    <div class="bns-main">
        @include('sop.partials.topbar')

        <main class="bns-content">
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

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @include('sop.partials.logout-modal')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ bns_vasset('assets/js/bns-csrf.js') }}"></script>
    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function () {
            document.getElementById('bnsSidebar')?.classList.toggle('is-open');
        });
    </script>
    @stack('scripts')
</body>
</html>
