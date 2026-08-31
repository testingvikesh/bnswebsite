@extends('sop.layouts.app')

@section('title', 'User Management')
@section('page-title', 'User Management')

@push('styles')
<style>
    .sop-users-table { table-layout: fixed; width: 100%; }
    .sop-users-table th,
    .sop-users-table td {
        vertical-align: middle;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .sop-users-table .col-num { width: 48px; }
    .sop-users-table .col-name { width: 22%; }
    .sop-users-table .col-email { width: 26%; }
    .sop-users-table .col-role { width: 140px; }
    .sop-users-table .col-date { width: 110px; white-space: nowrap; }
    .sop-users-table .col-actions { width: 96px; }
    .sop-users-table .col-actions .btn { padding: .2rem .45rem; }
    @media (max-width: 768px) {
        .sop-users-table .col-date { display: none; }
        .sop-users-table th.col-date,
        .sop-users-table td.col-date { display: none; }
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Users</h4>
        <p class="text-muted mb-0">Manage accounts and roles</p>
    </div>
    <a href="{{ route('controlpanel.users.create') }}" class="btn btn-sop-primary">
        <i class="bi bi-person-plus me-1"></i> Add User
    </a>
</div>

@if ($errors->has('delete'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $errors->first('delete') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="sop-card mb-3">
    <div class="p-3 border-bottom">
        <form method="GET" action="{{ route('controlpanel.users.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email..."
                       value="{{ $search }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-secondary">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </div>
            @if ($search->isNotEmpty())
                <div class="col-auto">
                    <a href="{{ route('controlpanel.users.index') }}" class="btn btn-link text-decoration-none">Clear</a>
                </div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 sop-users-table">
            <thead class="table-light">
                <tr>
                    <th class="col-num">#</th>
                    <th class="col-name">Name</th>
                    <th class="col-email">Email</th>
                    <th class="col-role">Role</th>
                    <th class="col-date">Registered</th>
                    <th class="col-actions text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td class="col-num text-muted">{{ $users->firstItem() + $loop->index }}</td>
                        <td class="col-name">
                            <div class="d-flex align-items-center gap-1 flex-wrap">
                                <strong class="text-truncate">{{ $user->name }}</strong>
                                @if ($user->id === auth()->id())
                                    <span class="badge bg-primary">You</span>
                                @endif
                            </div>
                        </td>
                        <td class="col-email">
                            <span class="text-truncate d-block" title="{{ $user->email }}">{{ $user->email }}</span>
                        </td>
                        <td class="col-role">
                            @if ($user->isSopAdmin())
                                <span class="badge text-bg-danger">Administrator</span>
                            @else
                                <span class="badge text-bg-info">User</span>
                            @endif
                        </td>
                        <td class="col-date text-muted small">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="col-actions text-end text-nowrap">
                            <a href="{{ route('controlpanel.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary"
                               title="Edit user">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if ($user->id !== auth()->id())
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        title="Delete user"
                                        data-bs-toggle="modal" data-bs-target="#deleteUserModal"
                                        data-user-name="{{ $user->name }}"
                                        data-delete-url="{{ route('controlpanel.users.destroy', $user) }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="p-3 border-top">
            {{ $users->links() }}
        </div>
    @endif
</div>

<div class="alert alert-light border small mb-0">
    <i class="bi bi-info-circle me-1"></i>
    <strong>Administrator</strong> — full panel access including website content sections.
    <strong>User</strong> — limited panel access.
</div>

<div class="modal fade" id="deleteUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-circle text-danger me-2"></i>Delete User
                </h5>
                @include('partials.modal-close-button', ['onLight' => true])
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong id="deleteUserName"></strong>? This action cannot be undone.
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteUserForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('deleteUserModal')?.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('deleteUserName').textContent = button.getAttribute('data-user-name');
        document.getElementById('deleteUserForm').action = button.getAttribute('data-delete-url');
    });
</script>
@endpush
