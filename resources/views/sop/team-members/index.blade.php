@extends('sop.layouts.app')

@section('title', 'Team Members')
@section('page-title', 'Team Members')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Leadership &amp; Academic Team</h4>
        <p class="text-muted mb-0">Manage members on <a href="{{ route('about.team') }}" target="_blank">Meet Our Team</a></p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('controlpanel.team-page.edit') }}" class="btn btn-outline-secondary btn-sm">Page Settings</a>
        <a href="{{ route('controlpanel.team-members.create', ['category' => 'leadership']) }}" class="btn btn-sop-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i> Add Member
        </a>
    </div>
</div>

<div class="sop-card mb-3">
    <div class="p-3 border-bottom">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search name or designation..." value="{{ $search }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All categories</option>
                    <option value="leadership" {{ $category === 'leadership' ? 'selected' : '' }}>Leadership</option>
                    <option value="academic" {{ $category === 'academic' ? 'selected' : '' }}>Academic</option>
                </select>
            </div>
            <div class="col-md-auto">
                <button type="submit" class="btn btn-outline-primary">Filter</button>
            </div>
        </form>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Designation</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td class="fw-semibold">{{ $member->full_name }}</td>
                    <td><span class="badge text-bg-secondary text-capitalize">{{ $member->category }}</span></td>
                    <td>{{ $member->designation }}</td>
                    <td>{{ $member->sort_order }}</td>
                    <td>
                        @if($member->is_active)
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-secondary">Hidden</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <a href="{{ route('controlpanel.team-members.edit', $member) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('controlpanel.team-members.destroy', $member) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Delete this member?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No team members yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($members->hasPages())
        <div class="p-3 border-top">{{ $members->links() }}</div>
    @endif
</div>
@endsection
