@extends('sop.layouts.app')

@section('title', 'Visiting Expert Faculty')
@section('page-title', 'Visiting Expert Faculty')

@push('styles')
<style>
    .sop-faculty-thumb { width: 52px; height: 52px; border-radius: 10px; object-fit: cover; background: #e2e8f0; }
    .sop-faculty-placeholder {
        width: 52px; height: 52px; border-radius: 10px;
        background: linear-gradient(135deg, #0a2240, #123a5e);
        color: #fff; display: flex; align-items: center; justify-content: center;
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Visiting Expert Faculty</h4>
        <p class="text-muted mb-0">Manage faculty profiles shown on the public page</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('controlpanel.faculty-page.edit') }}" class="btn btn-outline-secondary btn-sm">Page Settings</a>
        <a href="{{ route('about.faculty') }}" target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-box-arrow-up-right me-1"></i> View Page
        </a>
        <a href="{{ route('controlpanel.visiting-faculty.create') }}" class="btn btn-sop-primary">
            <i class="bi bi-person-plus me-1"></i> Add Faculty
        </a>
    </div>
</div>

<div class="sop-card mb-3">
    <div class="p-3 border-bottom">
        <form method="GET" action="{{ route('controlpanel.visiting-faculty.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search name, industry, qualification..."
                       value="{{ $search }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search me-1"></i> Search</button>
            </div>
            @if ($search->isNotEmpty())
                <div class="col-auto"><a href="{{ route('controlpanel.visiting-faculty.index') }}" class="btn btn-link text-decoration-none">Clear</a></div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 56px;">#</th>
                    <th style="width: 64px;">Photo</th>
                    <th>Name</th>
                    <th>Experience</th>
                    <th>Industry</th>
                    <th style="width: 72px;">Order</th>
                    <th style="width: 88px;">Status</th>
                    <th class="text-end" style="width: 96px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($faculty as $member)
                    <tr>
                        <td class="text-muted">{{ $faculty->firstItem() + $loop->index }}</td>
                        <td>
                            @if($member->photo_url)
                                <img src="{{ $member->photo_url }}" alt="{{ $member->display_name }}"
                                     class="sop-faculty-thumb"
                                     onerror="this.classList.add('d-none'); this.nextElementSibling?.classList.remove('d-none');">
                                <div class="sop-faculty-placeholder d-none"><i class="bi bi-person"></i></div>
                            @else
                                <div class="sop-faculty-placeholder" title="{{ $member->photo_path ? 'Photo file not found' : 'No photo uploaded' }}">
                                    <i class="bi bi-{{ $member->photo_path ? 'image' : 'person' }}"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong class="d-block">{{ $member->display_name }}</strong>
                            <span class="text-muted small">{{ $member->designation }}</span>
                        </td>
                        <td class="small">{{ $member->professional_experience ?: '—' }}</td>
                        <td class="small text-truncate" style="max-width: 160px;" title="{{ $member->industry }}">{{ $member->industry ?: '—' }}</td>
                        <td><span class="badge text-bg-light text-dark border">{{ $member->sort_order }}</span></td>
                        <td>
                            @if($member->is_active)
                                <span class="badge text-bg-success">Active</span>
                            @else
                                <span class="badge text-bg-secondary">Hidden</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('controlpanel.visiting-faculty.edit', $member) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal" data-bs-target="#deleteFacultyModal"
                                    data-faculty-name="{{ $member->display_name }}"
                                    data-delete-url="{{ route('controlpanel.visiting-faculty.destroy', $member) }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            <i class="bi bi-mortarboard display-6 d-block mb-2 opacity-50"></i>
                            No faculty profiles yet.
                            <a href="{{ route('controlpanel.visiting-faculty.create') }}">Add the first profile</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($faculty->hasPages())
        <div class="p-3 border-top">{{ $faculty->links() }}</div>
    @endif
</div>

<div class="modal fade" id="deleteFacultyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title"><i class="bi bi-exclamation-circle text-danger me-2"></i>Delete Faculty</h5>
                @include('partials.modal-close-button', ['onLight' => true])
            </div>
            <div class="modal-body">Remove <strong id="deleteFacultyName"></strong>? This cannot be undone.</div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteFacultyForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('deleteFacultyModal')?.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('deleteFacultyName').textContent = button.getAttribute('data-faculty-name');
        document.getElementById('deleteFacultyForm').action = button.getAttribute('data-delete-url');
    });
</script>
@endpush
