@extends('sop.layouts.app')

@section('title', 'Contact Inquiries')
@section('page-title', 'Contact Form Submissions')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="text-muted mb-0">Enquiries submitted from the <a href="{{ route('contact') }}" target="_blank">Contact Us</a> page.</p>
    </div>
    <a href="{{ route('controlpanel.contact-page.edit') }}" class="btn btn-outline-secondary btn-sm">Contact Page Settings</a>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="sop-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Reg. No.</th>
                    <th>Name</th>
                    <th>Program</th>
                    <th>Category</th>
                    <th>Contact</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inquiries as $item)
                <tr>
                    <td class="text-muted small">{{ $item->created_at?->format('d M Y H:i') }}</td>
                    <td class="small fw-semibold text-primary">{{ $item->registration_number ?? '—' }}</td>
                    <td class="fw-semibold">{{ $item->full_name }}</td>
                    <td class="small">{{ Str::limit($item->interested_program ?? $item->subject, 28) }}</td>
                    <td><span class="badge text-bg-secondary">{{ $item->category }}</span></td>
                    <td class="small">
                        <div>{{ $item->mobile }}</div>
                        <div>{{ $item->email }}</div>
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('controlpanel.contact-inquiries.show', $item) }}" class="btn btn-sm btn-primary">View Details</a>
                        <form action="{{ route('controlpanel.contact-inquiries.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this enquiry?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">No enquiries yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($inquiries->hasPages())<div class="p-3 border-top">{{ $inquiries->links() }}</div>@endif
</div>
@endsection
