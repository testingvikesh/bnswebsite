@extends('reporting.layouts.app')

@section('title', 'Submission Details')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <a href="{{ route('reporting.index', request()->only(['q', 'form_source', 'category', 'program', 'date_from', 'date_to'])) }}" class="btn btn-outline-secondary bns-reporting-back-btn">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
    <span class="bns-reporting-badge bns-reporting-badge--contact">{{ $formSourceLabel }}</span>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="bns-reporting-detail-card mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                <div>
                    <h4 class="mb-1 fw-bold">{{ $inquiry->full_name }}</h4>
                    <p class="text-muted mb-0 small"><i class="bi bi-clock me-1"></i>Submitted {{ $inquiry->created_at?->format('d M Y, h:i A') }} IST</p>
                </div>
                @if($inquiry->registration_number)
                    <span class="bns-reporting-reg fs-6">{{ $inquiry->registration_number }}</span>
                @endif
            </div>

            <h6>Contact</h6>
            <dl class="row mb-0">
                <dt class="col-sm-4">Mobile</dt><dd class="col-sm-8">{{ $inquiry->mobile }}</dd>
                <dt class="col-sm-4">WhatsApp</dt><dd class="col-sm-8">{{ $inquiry->whatsapp ?: '—' }}</dd>
                <dt class="col-sm-4">Email</dt><dd class="col-sm-8"><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></dd>
            </dl>

            <h6>Location</h6>
            <dl class="row mb-0">
                <dt class="col-sm-4">City / State</dt><dd class="col-sm-8">{{ $inquiry->city }}, {{ $inquiry->state }}</dd>
                <dt class="col-sm-4">PIN / Country</dt><dd class="col-sm-8">{{ $inquiry->pin_code ?: '—' }} / {{ $inquiry->country ?? 'India' }}</dd>
            </dl>

            <h6>Program &amp; Category</h6>
            <dl class="row mb-0">
                <dt class="col-sm-4">Form Source</dt><dd class="col-sm-8">{{ $formSourceLabel }}</dd>
                <dt class="col-sm-4">Interested Program</dt><dd class="col-sm-8">{{ $inquiry->interested_program ?? '—' }}</dd>
                <dt class="col-sm-4">Category</dt><dd class="col-sm-8">{{ $inquiry->category ?? '—' }}</dd>
                <dt class="col-sm-4">Status</dt><dd class="col-sm-8">{{ ucfirst($inquiry->status ?? 'pending') }}</dd>
            </dl>

            @if($inquiry->business_profession_category || $inquiry->organization_name || $inquiry->business_category || $inquiry->products_services)
                <h6>Business Details</h6>
                <dl class="row mb-0">
                    <dt class="col-sm-4">Profession Category</dt><dd class="col-sm-8">{{ $inquiry->business_profession_category ?: '—' }}</dd>
                    <dt class="col-sm-4">Business / Company Name</dt><dd class="col-sm-8">{{ $inquiry->organization_name ?: '—' }}</dd>
                    <dt class="col-sm-4">Business Category</dt><dd class="col-sm-8">{{ $inquiry->business_category ?: '—' }}</dd>
                    <dt class="col-sm-4">Products / Services</dt><dd class="col-sm-8">{{ $inquiry->products_services ?: '—' }}</dd>
                </dl>
            @endif

            @if($inquiry->message)
                <h6>Message</h6>
                <p class="mb-0 p-3 rounded-3 bg-light border">{{ $inquiry->message }}</p>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="bns-reporting-detail-card mb-4">
            <h6 class="mt-0 fw-bold mb-3">Declarations</h6>
            <ul class="list-unstyled small mb-0">
                <li class="mb-2"><i class="bi {{ $inquiry->agreed_info_correct ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-danger' }}"></i> Information is true and correct</li>
                <li class="mb-2"><i class="bi {{ $inquiry->agreed_to_contact ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-danger' }}"></i> Agreed to be contacted</li>
                <li><i class="bi {{ $inquiry->agreed_privacy ? 'bi-check-circle-fill text-success' : 'bi-x-circle text-danger' }}"></i> Privacy Policy &amp; Terms accepted</li>
            </ul>
        </div>

        @if($inquiry->documents)
            <div class="bns-reporting-detail-card">
                <h6 class="mt-0 fw-bold mb-3">Uploaded Documents</h6>
                @php $labels = $inquiry->documentLabels(); @endphp
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
            </div>
        @endif
    </div>
</div>
@endsection
