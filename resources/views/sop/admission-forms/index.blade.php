@extends('sop.layouts.app')

@section('title', 'Admission Forms')
@section('page-title', 'Admission Form Submissions')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="text-muted mb-0">Manage all admission &amp; registration form submissions from the website.</p>
    </div>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('register') }}" target="_blank" class="btn btn-outline-secondary btn-sm">Register Page</a>
        <a href="{{ route('admissions.online-apply') }}" target="_blank" class="btn btn-outline-secondary btn-sm">Online Application Form</a>
        <a href="{{ route('register') }}" target="_blank" class="btn btn-outline-secondary btn-sm">Register Forms</a>
    </div>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="row g-3 mb-4">
    @foreach($types as $key => $config)
    <div class="col-6 col-md-4 col-xl">
        <a href="{{ route('controlpanel.admission-forms.index', ['type' => $key]) }}" class="text-decoration-none">
            <div class="sop-card p-3 h-100 {{ $currentType === $key ? 'border-primary border-2' : '' }}">
                <div class="small text-muted">{{ $config['label'] }}</div>
                <div class="fs-4 fw-bold text-primary">{{ $counts[$key] ?? 0 }}</div>
            </div>
        </a>
    </div>
    @endforeach
</div>

<div class="sop-card mb-4">
    <div class="p-3 border-bottom">
        <form method="GET" class="row g-2 align-items-end">
            <input type="hidden" name="type" value="{{ $currentType }}">
            <div class="col-md-5">
                <label class="form-label small mb-1">Search</label>
                <input type="search" name="q" class="form-control form-control-sm" value="{{ $search }}" placeholder="Name, email, mobile, reference no.">
            </div>
            <div class="col-md-3">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All statuses</option>
                    @foreach($statusOptions as $status)
                        <option value="{{ $status }}" @selected($statusFilter === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('controlpanel.admission-forms.index', ['type' => $currentType]) }}" class="btn btn-outline-secondary btn-sm">Reset</a>
            </div>
        </form>
    </div>

    <div class="p-3 border-bottom bg-light">
        <h5 class="mb-0">{{ $currentTypeLabel }}</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Name</th>
                    <th>Program / Type</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                <tr>
                    <td class="text-muted small">{{ $record->created_at?->format('d M Y H:i') }}</td>
                    <td class="small fw-semibold text-primary">
                        {{ $currentType === 'online' ? $record->application_number : $record->registration_number }}
                    </td>
                    <td class="fw-semibold">{{ $record->full_name }}</td>
                    <td class="small">
                        @if($currentType === 'online')
                            {{ Str::limit($record->program, 24) }}<br>
                            <span class="text-muted">{{ $record->category }}</span>
                        @else
                            {{ $currentTypeLabel }}
                        @endif
                    </td>
                    <td class="small">
                        <div>{{ $record->mobile }}</div>
                        <div>{{ $record->email }}</div>
                    </td>
                    <td>
                        <span class="badge text-bg-{{ $record->status === 'approved' || $record->status === 'enrolled' ? 'success' : ($record->status === 'rejected' ? 'danger' : 'warning') }}">
                            {{ ucfirst($record->status ?? 'pending') }}
                        </span>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('controlpanel.admission-forms.show', ['type' => $currentType, 'id' => $record->id]) }}" class="btn btn-sm btn-primary">View</a>
                        <form action="{{ route('controlpanel.admission-forms.destroy', ['type' => $currentType, 'id' => $record->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this submission?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No submissions for this form type yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($records->hasPages())<div class="p-3 border-top">{{ $records->links() }}</div>@endif
</div>
@endsection
