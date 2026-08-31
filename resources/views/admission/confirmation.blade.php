@extends('layouts.front')

@section('title', 'Admission Confirmation')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/admission-page.css') }}" />
@endpush

@section('content')
<div class="bns-admission-page">
    <section class="bns-admission-confirm">
        <div class="container">
            <div class="bns-admission-confirm__card">
                <div class="bns-admission-confirm__icon"><i class="fas fa-check-circle"></i></div>
                <h1>Application Submitted Successfully</h1>
                <p class="bns-admission-confirm__number">Application Number: <strong>{{ $application->application_number }}</strong></p>
                <p>Thank you, <strong>{{ $application->full_name }}</strong>. Our admission team will review your application and contact you shortly.</p>

                <div class="bns-admission-confirm__details">
                    <p><strong>Program:</strong> {{ $application->program }}</p>
                    <p><strong>Category:</strong> {{ $application->category }}</p>
                    <p><strong>Centre:</strong> {{ $application->centre }}</p>
                    @if($application->fee_breakdown)
                        <p><strong>Total Payable:</strong> ₹{{ number_format($application->fee_breakdown['total_payable'] ?? 0, 2) }}</p>
                    @endif
                </div>

                <h3>What Happens Next</h3>
                <ul class="bns-admission-list list-unstyled">
                    @foreach($config['after_admission']['items'] as $item)
                        <li><i class="fas fa-star"></i> {{ $item }}</li>
                    @endforeach
                </ul>

                @include('admission.partials.ctas')
            </div>
        </div>
    </section>
</div>
@endsection
