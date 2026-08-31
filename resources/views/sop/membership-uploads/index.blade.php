@extends('sop.layouts.app')

@section('title', 'Membership Uploads')
@section('page-title', 'Membership Uploads')

@section('content')
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-primary-subtle text-primary"><i class="bi bi-person-vcard"></i></div>
            <div><div class="text-muted small">Total</div><strong>{{ $stats['total'] }}</strong></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-warning-subtle text-warning"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="text-muted small">Pending</div><strong>{{ $stats['pending'] }}</strong></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-success-subtle text-success"><i class="bi bi-check-circle"></i></div>
            <div><div class="text-muted small">Verified</div><strong>{{ $stats['verified'] }}</strong></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-danger-subtle text-danger"><i class="bi bi-x-circle"></i></div>
            <div><div class="text-muted small">Rejected</div><strong>{{ $stats['rejected'] }}</strong></div>
        </div>
    </div>
</div>

<div class="sop-card p-4 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-control" value="{{ $search }}" placeholder="Name, membership no, email, mobile, reference no">
        </div>
        <div class="col-md-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All statuses</option>
                @foreach($statusOptions as $option)
                    <option value="{{ $option }}" @selected($statusFilter === $option)>{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-danger w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('controlpanel.membership-uploads.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
    </form>
</div>

<div class="sop-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Membership Name</th>
                    <th>Membership No</th>
                    <th>Contact</th>
                    <th>Reference No</th>
                    <th>Status</th>
                    <th>Proof</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($uploads as $upload)
                    <tr>
                        <td class="small">{{ $upload->created_at?->format('d M Y, h:i A') }}</td>
                        <td class="fw-semibold">{{ $upload->membership_name }}</td>
                        <td>{{ $upload->membership_no }}</td>
                        <td class="small">
                            <div>{{ $upload->email ?: '—' }}</div>
                            <div class="text-muted">{{ $upload->mobile ?: '—' }}</div>
                        </td>
                        <td class="small">{{ $upload->registration_number ?: '—' }}</td>
                        <td>
                            @php
                                $badge = match($upload->status) {
                                    'verified' => 'success',
                                    'trustee_verified' => 'primary',
                                    'rejected' => 'danger',
                                    'refunded' => 'info',
                                    default => 'warning',
                                };
                            @endphp
                            <span class="badge text-bg-{{ $badge }}">{{ str_replace('_', ' ', ucfirst($upload->status)) }}</span>
                        </td>
                        <td>
                            @if($url = $upload->photoUrl())
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary"><i class="bi bi-image"></i> View</a>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('controlpanel.membership-uploads.edit', $upload) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No membership uploads found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($uploads->hasPages())
        <div class="p-3">{{ $uploads->links() }}</div>
    @endif
</div>
@endsection
