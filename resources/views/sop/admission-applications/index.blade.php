@extends('sop.layouts.app')

@section('title', 'Admission Applications')
@section('page-title', 'Online Admission Applications')

@section('content')
<div class="sop-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light"><tr><th>Date</th><th>App No.</th><th>Name</th><th>Program</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td class="small text-muted">{{ $app->created_at?->format('d M Y') }}</td>
                    <td class="fw-semibold text-primary">{{ $app->application_number }}</td>
                    <td>{{ $app->full_name }}</td>
                    <td class="small">{{ Str::limit($app->program, 30) }}</td>
                    <td><span class="badge text-bg-warning">{{ $app->status }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('controlpanel.admission-applications.show', $app) }}" class="btn btn-sm btn-primary">View</a>
                        <form action="{{ route('controlpanel.admission-applications.destroy', $app) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?');">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No applications yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($applications->hasPages())<div class="p-3">{{ $applications->links() }}</div>@endif
</div>
@endsection
