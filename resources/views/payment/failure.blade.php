@extends('layouts.front')

@section('title', 'Payment Failed')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/register.css') }}" />
@endpush

@section('content')
@php
    $isIntroSession = ($payment->form_type ?? '') === 'intro-session';
    $backUrl = $isIntroSession ? route('pay-now') : route('register');
    $backLabel = $isIntroSession ? 'Back to Pay Now' : 'Back to Registration';
@endphp
<div class="bns-register-page">
    @include('partials.page-header', [
        'title' => 'Payment Failed',
        'bgImage' => asset('assets/images/backgrounds/page-header-bg.jpg'),
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $isIntroSession ? 'Pay Now' : 'Registration', 'url' => $backUrl],
            ['label' => 'Payment Failed'],
        ],
    ])

    <section class="bns-register-hub">
        <div class="container py-4">
            <div class="alert alert-danger">
                <h2 class="h5 mb-2"><i class="fas fa-times-circle me-2"></i>Payment not completed</h2>
                <p class="mb-0">{{ session('error') ?: ($payment->response_description ?: 'Your payment could not be processed.') }}</p>
            </div>

            <div class="bns-register-panel is-open">
                <div class="bns-register-panel__body">
                    <p><strong>Registration Number:</strong> {{ $payment->registration_number }}</p>
                    <p><strong>Amount:</strong> ₹ {{ number_format((float) $payment->amount, 2) }}</p>
                    @if(!empty($payment->response_code))
                        <p><strong>Gateway Code:</strong> {{ $payment->response_code }}</p>
                    @endif
                    <p class="mb-0"><strong>Reference:</strong> {{ $payment->merchant_txn_no }}</p>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <form method="POST" action="{{ route('payment.initiate', $payment->merchant_txn_no) }}">
                    @csrf
                    <button type="submit" class="thm-btn bns-admission-form__btn--apply">Retry Payment</button>
                </form>
                <a href="{{ $backUrl }}" class="thm-btn bns-admission-form__btn--brochure">{{ $backLabel }}</a>
            </div>
        </div>
    </section>
</div>
@endsection
