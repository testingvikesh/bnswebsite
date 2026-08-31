@extends('layouts.front')

@section('title', 'Complete Payment')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/register.css') }}" />
<style>
.bns-payment-card {
    max-width: 640px;
    margin: 0 auto;
    background: #fff;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 12px 40px rgba(15, 23, 42, 0.08);
}
.bns-payment-card__amount {
    font-size: 2rem;
    font-weight: 800;
    color: #0a2240;
}
</style>
@endpush

@section('content')
<div class="bns-register-page">
    @include('partials.page-header', [
        'title' => 'Complete Payment',
        'bgImage' => asset('assets/images/backgrounds/page-header-bg.jpg'),
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Registration', 'url' => route('register')],
            ['label' => 'Payment'],
        ],
    ])

    <section class="bns-register-hub">
        <div class="container py-4">
            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif

            <div class="bns-payment-card">
                <p class="text-uppercase small fw-bold text-muted mb-2">{{ $formLabel }}</p>
                <h2 class="h4 mb-3">Registration Number: {{ $payment->registration_number }}</h2>
                <p class="mb-1"><strong>Name:</strong> {{ $payment->customer_name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $payment->customer_email }}</p>
                <p class="mb-4"><strong>Mobile:</strong> {{ $payment->customer_mobile }}</p>

                <div class="bns-payment-card__amount mb-2">₹ {{ number_format((float) $payment->amount, 2) }}</div>
                <p class="text-muted mb-4">Secure payment via ICICI Bank payment gateway.</p>

                <form method="POST" action="{{ route('payment.initiate', $payment->merchant_txn_no) }}">
                    @csrf
                    <button type="submit" class="thm-btn bns-admission-form__btn--apply w-100">
                        Pay Now <i class="fas fa-lock"></i>
                    </button>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
