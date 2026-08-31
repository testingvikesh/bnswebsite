@extends('sop.layouts.app')

@section('title', 'Form Details')
@section('page-title', $reference)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <a href="{{ route('controlpanel.admission-forms.index', ['type' => $type]) }}" class="btn btn-outline-secondary btn-sm">&larr; Back to {{ $typeLabel }}</a>
    <form action="{{ route('controlpanel.admission-forms.destroy', ['type' => $type, 'id' => $record->id]) }}" method="POST" onsubmit="return confirm('Delete this submission permanently?');">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
    </form>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="sop-card p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                <div>
                    <span class="badge text-bg-secondary mb-2">{{ $typeLabel }}</span>
                    <h4 class="mb-1">{{ $record->full_name }}</h4>
                    <p class="text-muted mb-0 small">Submitted {{ $record->created_at?->format('d M Y, h:i A') }}</p>
                </div>
                <span class="badge text-bg-primary fs-6">{{ $reference }}</span>
            </div>

            @if($type === 'online')
                @include('sop.admission-forms.partials.online-details', ['record' => $record])
            @else
                @include('sop.admission-forms.partials.registration-details', ['record' => $record, 'formFields' => $formFields])
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        @if($photoUrl)
        <div class="sop-card p-4 mb-4 text-center">
            <h6 class="fw-bold mb-3">Photo</h6>
            <img src="{{ $photoUrl }}" alt="{{ $record->full_name }}" class="img-fluid rounded" style="max-height:220px;">
        </div>
        @endif

        @if($type === 'online')
        <div class="sop-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Uploaded Documents</h6>
            @php $labels = \App\Models\AdmissionApplication::documentLabels(); @endphp
            <ul class="list-unstyled mb-0">
                @if($record->photo_url)
                    <li class="mb-2"><a href="{{ $record->photo_url }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 text-start"><i class="bi bi-download"></i> Applicant Photo</a></li>
                @endif
                @foreach($labels as $key => $label)
                    @if($url = $record->documentUrl($key))
                        <li class="mb-2"><a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-primary w-100 text-start"><i class="bi bi-download"></i> {{ $label }}</a></li>
                    @endif
                @endforeach
            </ul>
            @if(!$record->photo_url && empty(array_filter($record->documents ?? [])))
                <p class="text-muted small mb-0">No documents uploaded.</p>
            @endif
        </div>
        @endif

        @if($type !== 'online')
        <div class="sop-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Payment Details</h6>
            @if($payments->isEmpty())
                <p class="text-muted small mb-0">No payment records for this registration yet.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Txn ID</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td class="small">{{ $payment->created_at?->format('d M Y') }}</td>
                                    <td>₹ {{ number_format((float) $payment->amount, 2) }}</td>
                                    <td><span class="badge text-bg-{{ $payment->statusBadgeClass() }}">{{ $payment->statusLabel() }}</span></td>
                                    <td class="small">{{ $payment->txn_id ?: $payment->merchant_txn_no }}</td>
                                    <td><a href="{{ route('controlpanel.payments.show', $payment) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
        @endif

        <div class="sop-card p-4">
            <h6 class="fw-bold mb-3">Manage Status</h6>
            <form method="POST" action="{{ route('controlpanel.admission-forms.status', ['type' => $type, 'id' => $record->id]) }}">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Application Status</label>
                    <select name="status" class="form-select">
                        @foreach($statusOptions as $option)
                            <option value="{{ $option }}" @selected($record->status === $option)>{{ ucfirst($option) }}</option>
                        @endforeach
                    </select>
                </div>
                @if($type === 'online')
                <div class="mb-3">
                    <label class="form-label">Payment Status</label>
                    <select name="payment_status" class="form-select">
                        @foreach(['pending', 'partial', 'paid', 'failed'] as $payment)
                            <option value="{{ $payment }}" @selected(($record->payment_status ?? 'pending') === $payment)>{{ ucfirst($payment) }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <button type="submit" class="btn btn-primary w-100">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection
