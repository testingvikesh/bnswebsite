@extends('sop.layouts.app')

@section('title', 'Email Dashboard')
@section('page-title', 'Email Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/email-dashboard-admin.css') }}">
@endpush

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-primary-subtle text-primary"><i class="bi bi-envelope-paper"></i></div>
            <div><div class="text-muted small">Total Emails</div><strong>{{ number_format($stats['total']) }}</strong></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-success-subtle text-success"><i class="bi bi-check-circle"></i></div>
            <div><div class="text-muted small">Sent</div><strong>{{ number_format($stats['sent']) }}</strong></div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="sop-stat">
            <div class="sop-stat__icon bg-danger-subtle text-danger"><i class="bi bi-x-circle"></i></div>
            <div><div class="text-muted small">Failed</div><strong>{{ number_format($stats['failed']) }}</strong></div>
        </div>
    </div>
</div>

<div class="sop-card p-4 mb-4 bns-email-dash__filters">
    <form method="GET" action="{{ route('controlpanel.email-dashboard.index') }}" class="row g-2 align-items-end">
        <div class="col-lg-3 col-md-6">
            <label class="form-label">Search</label>
            <input type="text" name="q" class="form-control" value="{{ $filters['q'] }}" placeholder="To, subject, body...">
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="form-label">Mail</label>
            <select name="process" class="form-select">
                <option value="">All mail</option>
                @foreach($processLabels as $key => $label)
                    <option value="{{ $key }}" @selected($filters['process'] === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All status</option>
                <option value="sent" @selected($filters['status'] === 'sent')>Sent</option>
                <option value="failed" @selected($filters['status'] === 'failed')>Failed</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <label class="form-label">Sender</label>
            <select name="sender" class="form-select">
                <option value="">All senders</option>
                @foreach($senders as $sender)
                    <option value="{{ $sender }}" @selected($filters['sender'] === $sender)>{{ $sender }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-1 col-md-4">
            <label class="form-label">From</label>
            <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] }}">
        </div>
        <div class="col-lg-2 col-md-4">
            <label class="form-label">To</label>
            <div class="d-flex gap-2">
                <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] }}">
            </div>
        </div>
        <div class="col-12 d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-sop-primary px-4">Filter</button>
            <a href="{{ route('controlpanel.email-dashboard.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>
</div>

<div class="sop-card bns-email-dash">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle bns-email-dash__table">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Process</th>
                    <th>To</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Sent By</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $item)
                <tr>
                    <td class="text-muted small text-nowrap">{{ $item->sent_at?->timezone('Asia/Kolkata')->format('d M Y H:i') ?: $item->created_at?->format('d M Y H:i') }}</td>
                    <td><span class="bns-email-dash__process">{{ $item->processLabel() }}</span></td>
                    <td class="small">
                        <a href="mailto:{{ $item->to_email }}">{{ $item->to_email }}</a>
                    </td>
                    <td class="small" title="{{ $item->subject }}">{{ Str::limit($item->subject, 72) }}</td>
                    <td>
                        @if($item->status === 'failed')
                            <span class="bns-email-dash__status bns-email-dash__status--failed">Failed</span>
                        @else
                            <span class="bns-email-dash__status bns-email-dash__status--sent">Sent</span>
                        @endif
                    </td>
                    <td class="small">{{ $item->senderLabel() }}</td>
                    <td class="text-end">
                        <a href="{{ route('controlpanel.email-dashboard.show', $item) }}" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5">
                        No emails logged yet. Emails sent from the website will appear here.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(method_exists($logs, 'hasPages') && $logs->hasPages())
        <div class="p-3 border-top">{{ $logs->links() }}</div>
    @endif
</div>
@endsection
