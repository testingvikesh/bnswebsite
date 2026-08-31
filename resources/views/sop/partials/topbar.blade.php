@php
    $initials = collect(explode(' ', auth()->user()->name))->take(2)->map(fn ($w) => strtoupper(substr($w, 0, 1)))->join('');
@endphp
<header class="bns-topbar">
    <div class="d-flex align-items-center gap-2">
        <button type="button" class="btn btn-light d-lg-none border" id="sidebarToggle" aria-label="Toggle menu">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="bns-topbar__title mb-0">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="bns-topbar__user">
        <div class="text-end d-none d-sm-block">
            <div class="fw-semibold small">{{ auth()->user()->name }}</div>
            <div class="text-muted" style="font-size: 0.75rem;">
                @if (auth()->user()->isSopAdmin())
                    <span class="badge text-bg-danger">Administrator</span>
                @else
                    <span class="badge text-bg-secondary">User</span>
                @endif
            </div>
        </div>
        <span class="bns-avatar">{{ $initials }}</span>
        <button type="button" class="btn btn-outline-danger btn-sm bns-btn-outline-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right"></i>
            <span class="d-none d-md-inline ms-1">Logout</span>
        </button>
    </div>
</header>
