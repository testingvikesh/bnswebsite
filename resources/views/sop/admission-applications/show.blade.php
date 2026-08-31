@extends('sop.layouts.app')

@section('title', 'Application Details')
@section('page-title', $application->application_number)

@section('content')
<a href="{{ route('controlpanel.admission-applications.index') }}" class="btn btn-outline-secondary btn-sm mb-4">&larr; Back</a>
<div class="sop-card p-4">
    <h4>{{ $application->full_name }}</h4>
    <dl class="row">
        <dt class="col-sm-3">Category</dt><dd class="col-sm-9">{{ $application->category }}</dd>
        <dt class="col-sm-3">Program</dt><dd class="col-sm-9">{{ $application->program }}</dd>
        <dt class="col-sm-3">Batch / Centre</dt><dd class="col-sm-9">{{ $application->batch }} / {{ $application->centre }}</dd>
        <dt class="col-sm-3">Mobile</dt><dd class="col-sm-9">{{ $application->mobile }}</dd>
        <dt class="col-sm-3">Email</dt><dd class="col-sm-9">{{ $application->email }}</dd>
        <dt class="col-sm-3">Education</dt><dd class="col-sm-9">{{ $application->education_qualification }} — {{ $application->institution_name }}</dd>
        @if($application->fee_breakdown)
        <dt class="col-sm-3">Total Payable</dt><dd class="col-sm-9">₹{{ number_format($application->fee_breakdown['total_payable'] ?? 0, 2) }}</dd>
        @endif
    </dl>
</div>
@endsection
