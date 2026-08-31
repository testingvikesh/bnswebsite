@extends('layouts.front')

@section('title', 'Venue Inspection')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/register.css') }}" />
@endpush

@section('content')
<div class="bns-register-page bns-venue-inspection-page">
    <section class="bns-register-hero bns-venue-inspection-hero">
        <div class="container">
            <p class="bns-register-hero__eyebrow"><i class="fas fa-building"></i> BNS Operations</p>
            <h1 class="bns-register-hero__title">Venue Inspection Form</h1>
            <p class="bns-register-hero__text">Complete this inspection report for venue evaluation, capacity assessment, and approval decisions.</p>
        </div>
    </section>

    <section class="bns-register-hub">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success bns-register-alert" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger bns-register-alert" role="alert">
                    <strong>Please fix the following errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bns-register-panel is-open bns-venue-inspection-panel">
                <div class="bns-register-panel__header bns-register-panel__header--venue-inspection">
                    <h2 class="bns-register-panel__title">BNS Venue Inspection Form</h2>
                    <p class="bns-register-panel__subtitle">Facility assessment for BNS programs, events &amp; training sessions</p>
                </div>
                <div class="bns-register-panel__body">
                    @include('venue-inspection.partials.form')
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
