@extends('sop.layouts.app')

@section('title', 'Newsletter Subscribers')
@section('page-title', 'Newsletter Subscribers')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <p class="text-muted mb-0">Emails submitted from the website footer <strong>Subscribe Us</strong> form.</p>
    </div>
    <span class="badge text-bg-primary fs-6">{{ $total }} subscriber{{ $total === 1 ? '' : 's' }}</span>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="sop-card mb-3">
    <div class="p-3 border-bottom">
        <form method="GET" action="{{ route('controlpanel.newsletter-subscribers.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="q" class="form-control" placeholder="Search email, source, or IP..."
                       value="{{ $search }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search me-1"></i> Search</button>
            </div>
            @if ($search !== '')
                <div class="col-auto">
                    <a href="{{ route('controlpanel.newsletter-subscribers.index') }}" class="btn btn-link text-decoration-none">Clear</a>
                </div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 56px;">#</th>
                    <th>Email</th>
                    <th>Source</th>
                    <th>Terms</th>
                    <th>IP Address</th>
                    <th>Subscribed On</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscribers as $item)
                <tr>
                    <td class="text-muted small">{{ $subscribers->firstItem() + $loop->index }}</td>
                    <td class="fw-semibold">
                        <a href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                    </td>
                    <td><span class="badge text-bg-secondary">{{ $item->source ?: '—' }}</span></td>
                    <td>
                        @if($item->agreed_terms)
                            <span class="badge text-bg-success">Agreed</span>
                        @else
                            <span class="badge text-bg-warning">Not agreed</span>
                        @endif
                    </td>
                    <td class="small text-muted">{{ $item->ip_address ?: '—' }}</td>
                    <td class="text-muted small">{{ $item->created_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}</td>
                    <td class="text-end text-nowrap">
                        <form action="{{ route('controlpanel.newsletter-subscribers.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this subscriber?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        {{ $search !== '' ? 'No subscribers match your search.' : 'No subscribers yet.' }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($subscribers, 'hasPages') && $subscribers->hasPages())
        <div class="p-3 border-top">{{ $subscribers->links() }}</div>
    @endif
</div>
@endsection
