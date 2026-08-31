@extends('sop.layouts.app')

@section('title', 'Enquiry Details')
@section('page-title', 'Contact Enquiry Details')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <a href="{{ route('controlpanel.contact-inquiries.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to List</a>
    <form action="{{ route('controlpanel.contact-inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Delete this enquiry?');">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Delete Enquiry</button>
    </form>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="sop-card p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <h4 class="mb-1">{{ $inquiry->full_name }}</h4>
                    <p class="text-muted mb-0 small">Submitted {{ $inquiry->created_at?->format('d M Y, h:i A') }}</p>
                </div>
                @if($inquiry->registration_number)
                    <span class="badge text-bg-primary fs-6">{{ $inquiry->registration_number }}</span>
                @endif
            </div>

            <h6 class="text-uppercase text-muted small fw-bold mt-4">Personal Information</h6>
            <dl class="row mb-0">
                <dt class="col-sm-4">Mobile</dt><dd class="col-sm-8">{{ $inquiry->mobile }}</dd>
                <dt class="col-sm-4">WhatsApp</dt><dd class="col-sm-8">{{ $inquiry->whatsapp ?: '—' }}</dd>
                <dt class="col-sm-4">Email</dt><dd class="col-sm-8"><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></dd>
                <dt class="col-sm-4">Date of Birth</dt><dd class="col-sm-8">{{ $inquiry->date_of_birth?->format('d M Y') ?? '—' }}</dd>
                <dt class="col-sm-4">Gender</dt><dd class="col-sm-8">{{ $inquiry->gender ?? '—' }}</dd>
            </dl>

            <h6 class="text-uppercase text-muted small fw-bold mt-4">Address</h6>
            <dl class="row mb-0">
                <dt class="col-sm-4">Address</dt><dd class="col-sm-8">{{ $inquiry->address ?: '—' }}</dd>
                <dt class="col-sm-4">City / State</dt><dd class="col-sm-8">{{ $inquiry->city }}, {{ $inquiry->state }}</dd>
                <dt class="col-sm-4">PIN / Country</dt><dd class="col-sm-8">{{ $inquiry->pin_code ?: '—' }} / {{ $inquiry->country ?? 'India' }}</dd>
            </dl>

            <h6 class="text-uppercase text-muted small fw-bold mt-4">Program &amp; Category</h6>
            <dl class="row mb-0">
                <dt class="col-sm-4">Interested Program</dt><dd class="col-sm-8">{{ $inquiry->interested_program ?? '—' }}</dd>
                <dt class="col-sm-4">Applicant Category</dt><dd class="col-sm-8">{{ $inquiry->category ?? '—' }}</dd>
                <dt class="col-sm-4">Qualification</dt><dd class="col-sm-8">{{ $inquiry->educational_qualification ?? '—' }}</dd>
                <dt class="col-sm-4">Occupation</dt><dd class="col-sm-8">{{ $inquiry->occupation ?? '—' }}</dd>
                <dt class="col-sm-4">Organization</dt><dd class="col-sm-8">{{ $inquiry->organization_name ?? '—' }}</dd>
            </dl>

            <h6 class="text-uppercase text-muted small fw-bold mt-4">Preferences</h6>
            <dl class="row mb-0">
                <dt class="col-sm-4">Learning Centre</dt><dd class="col-sm-8">{{ $inquiry->preferred_centre ?? '—' }}</dd>
                <dt class="col-sm-4">Batch</dt><dd class="col-sm-8">{{ $inquiry->preferred_batch ?? '—' }}</dd>
                <dt class="col-sm-4">Language</dt><dd class="col-sm-8">{{ $inquiry->preferred_language ?? '—' }}</dd>
                <dt class="col-sm-4">Heard About BNS</dt><dd class="col-sm-8">{{ $inquiry->hear_about ?? '—' }}</dd>
            </dl>

            @if(!empty($inquiry->purpose_of_joining))
            <h6 class="text-uppercase text-muted small fw-bold mt-4">Purpose of Joining</h6>
            <div class="d-flex flex-wrap gap-2">
                @foreach($inquiry->purpose_of_joining as $purpose)
                    <span class="badge text-bg-light border">{{ $purpose }}</span>
                @endforeach
            </div>
            @endif

            @if($inquiry->expectations)
            <h6 class="text-uppercase text-muted small fw-bold mt-4">Expectations</h6>
            <p class="mb-0">{{ $inquiry->expectations }}</p>
            @endif

            @if($inquiry->message)
            <h6 class="text-uppercase text-muted small fw-bold mt-4">Message</h6>
            <p class="mb-0">{{ $inquiry->message }}</p>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="sop-card p-4 mb-4">
            <h6 class="fw-bold mb-3">Declarations</h6>
            <ul class="list-unstyled small mb-0">
                <li class="mb-2"><i class="bi {{ $inquiry->agreed_info_correct ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-danger' }}"></i> Information is true and correct</li>
                <li class="mb-2"><i class="bi {{ $inquiry->agreed_to_contact ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-danger' }}"></i> Agreed to be contacted</li>
                <li><i class="bi {{ $inquiry->agreed_privacy ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-danger' }}"></i> Privacy Policy &amp; Terms accepted</li>
            </ul>
        </div>

        <div class="sop-card p-4">
            <h6 class="fw-bold mb-3">Uploaded Documents</h6>
            @php $labels = $inquiry->documentLabels(); @endphp
            @if($inquiry->documents)
                <ul class="list-unstyled mb-0">
                    @foreach($labels as $key => $label)
                        @if($url = $inquiry->documentUrl($key))
                            <li class="mb-2">
                                <a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary w-100 text-start">
                                    <i class="bi bi-download"></i> {{ $label }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
                @if(collect($inquiry->documents)->filter()->isEmpty())
                    <p class="text-muted small mb-0">No documents uploaded.</p>
                @endif
            @else
                <p class="text-muted small mb-0">No documents uploaded.</p>
            @endif
        </div>
    </div>
</div>
@endsection
