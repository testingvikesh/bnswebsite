@extends('layouts.front')

@section('title', 'Thank You — Payment Successful')

@php
    $isIntroSession = ($payment->form_type ?? '') === 'intro-session';
    $scholarshipAmount = (int) config('pay_now.scholarship_amount', 3160);
    $programLabel = $programLabel
        ?? $formLabel
        ?? config("payment.form_type_map.{$payment->form_type}.label", 'Registration');
    $detailItems = [
        ['label' => 'Program / Form', 'value' => $programLabel],
        ['label' => 'Registration Number', 'value' => $payment->registration_number ?: '—'],
        ['label' => 'Name', 'value' => $payment->customer_name ?: '—'],
        ['label' => 'Email', 'value' => $payment->customer_email ?: '—'],
        ['label' => 'Mobile', 'value' => $payment->customer_mobile ?: '—'],
        ['label' => 'Merchant Txn No', 'value' => $payment->merchant_txn_no ?: '—'],
        ['label' => 'Transaction ID', 'value' => $payment->txn_id ?: '—'],
        ['label' => 'Payment ID', 'value' => $payment->payment_id ?: '—'],
        ['label' => 'Payment Mode', 'value' => $payment->payment_mode ?: '—'],
        ['label' => 'Status', 'value' => $payment->statusLabel()],
        ['label' => 'Response Code', 'value' => $payment->response_code ?: '—'],
        ['label' => 'Date & Time', 'value' => $payment->paid_at?->format('d M Y, h:i A') ?: ($payment->payment_datetime ?: '—')],
    ];
@endphp

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/pay-now.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/payment-success.css') }}" />
@endpush

@section('content')
<div class="bns-payment-success">
    @include('partials.page-header', [
        'title' => 'Payment Successful',
        'bgImage' => asset('assets/images/backgrounds/page-header-bg.jpg'),
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => $isIntroSession ? 'Pay Now' : 'Registration', 'url' => $isIntroSession ? route('pay-now') : route('register')],
            ['label' => 'Thank You'],
        ],
    ])

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bns-payment-success__hero">
            <div class="bns-payment-success__badge" aria-hidden="true">
                <i class="fas fa-check"></i>
            </div>
            <span class="bns-payment-success__eyebrow">Payment Confirmed</span>
            <h1>Thank you! Your payment was successful</h1>
            <p class="bns-payment-success__lead">
                Your transaction has been completed securely. Please save your receipt for future reference.
            </p>
        </div>

        <div class="bns-payment-success__print-area">
            <article class="bns-payment-success__receipt" id="bnsPaymentSuccessReceipt">
                <div class="bns-payment-success__receipt-head">
                    <div>
                        <p class="bns-payment-success__brand">Business Navachar School (BNS)</p>
                        <h2 class="bns-payment-success__receipt-title">Payment Receipt</h2>
                    </div>
                    <span class="bns-payment-success__status">
                        <i class="fas fa-check-circle"></i> {{ strtoupper($payment->statusLabel()) }}
                    </span>
                </div>

                <div class="bns-payment-success__amount-bar">
                    <p class="bns-payment-success__amount-label">Amount Paid</p>
                    <p class="bns-payment-success__amount-value">₹ {{ number_format((float) $payment->amount, 2) }}</p>
                </div>

                <div class="bns-payment-success__grid">
                    @foreach($detailItems as $item)
                        <div class="bns-payment-success__item">
                            <span class="bns-payment-success__item-label">{{ $item['label'] }}</span>
                            <p class="bns-payment-success__item-value">{{ $item['value'] }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="bns-payment-success__actions no-print">
                    <a href="{{ route('payment.receipt', $payment->merchant_txn_no) }}" class="bns-payment-success__btn bns-payment-success__btn--primary" target="_blank" rel="noopener">
                        <i class="fas fa-file-invoice"></i> View Full Receipt
                    </a>
                    @if($isIntroSession)
                        @if(!empty($membershipAlreadyUploaded))
                            <span class="bns-payment-success__btn bns-payment-success__btn--primary" style="opacity:0.85;cursor:default;">
                                <i class="fas fa-check-circle"></i> Membership Proof Already Uploaded
                            </span>
                        @else
                            <button type="button" class="bns-payment-success__btn bns-payment-success__btn--primary" id="bnsOpenMembershipUploadBtn" data-bs-toggle="modal" data-bs-target="#bnsMembershipUploadModal">
                                <i class="fas fa-id-card"></i> Upload Membership Proof
                            </button>
                        @endif
                        <a href="{{ route('pay-now') }}" class="bns-payment-success__btn">
                            <i class="fas fa-arrow-left"></i> Back to Pay Now
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bns-payment-success__btn">
                            <i class="fas fa-arrow-left"></i> Back to Registration
                        </a>
                    @endif
                </div>
            </article>
        </div>

        @if($isIntroSession)
            <div class="bns-pay-now__banner mt-4">
                <p class="bns-pay-now__eyebrow">🌟 Scholarship Benefit 🌟</p>
                <h2>Special Scholarship for Permanent Members of Santacruz Jain Upashray</h2>
                <ul class="bns-pay-now__points">
                    <li>₹{{ number_format($scholarshipAmount) }} Scholarship / Discount is available exclusively for Permanent Members.</li>
                    <li>After completing the Admission & Fee Payment, kindly upload your Permanent Membership Proof.</li>
                    <li>Once your Membership is verified, ₹{{ number_format($scholarshipAmount) }} will be refunded.</li>
                    <li>Please upload your Membership Proof as soon as possible after payment.</li>
                </ul>
                <p class="bns-pay-now__footer">Business Navachar School (BNS) 🇮🇳</p>
                <div class="bns-pay-now__actions">
                    @if(!empty($membershipAlreadyUploaded))
                        <span class="thm-btn bns-pay-now__btn bns-pay-now__btn--primary" style="opacity:0.85;cursor:default;">
                            <i class="fas fa-check-circle"></i> Membership Proof Already Uploaded
                        </span>
                    @else
                        <button type="button" class="thm-btn bns-pay-now__btn bns-pay-now__btn--primary" data-bs-toggle="modal" data-bs-target="#bnsMembershipUploadModal">
                            <i class="fas fa-id-card"></i> Membership Upload Link
                        </button>
                    @endif
                </div>
            </div>
        @endif

        <p class="bns-payment-success__note">
            Keep this receipt for your records. For any payment support, contact BNS Helpline:
            <strong>+91 72086 28671</strong> or WhatsApp <strong>+91 70218 39703</strong>.
        </p>
    </div>
</div>
@endsection

@if($isIntroSession && empty($membershipAlreadyUploaded))
@push('modals')
@include('partials.membership-upload-modal', [
    'merchantTxnNo' => $payment->merchant_txn_no,
    'registrationNumber' => $payment->registration_number,
    'email' => $payment->customer_email,
    'mobile' => $payment->customer_mobile,
])
@endpush
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('bnsMembershipUploadModal');
    if (!modalEl || !window.bootstrap) {
        return;
    }

    if (modalEl.parentElement !== document.body) {
        document.body.appendChild(modalEl);
    }

    modalEl.addEventListener('show.bs.modal', function () {
        document.body.classList.add('bns-membership-modal-open');
    });

    modalEl.addEventListener('shown.bs.modal', function () {
        var backdrops = document.querySelectorAll('.modal-backdrop');
        if (backdrops.length) {
            backdrops[backdrops.length - 1].classList.add('bns-membership-upload-backdrop');
        }
        var firstInput = modalEl.querySelector('#membership_name');
        if (firstInput) {
            firstInput.focus();
        }
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        document.body.classList.remove('bns-membership-modal-open');
    });

    @if($errors->any() && old('membership_name') !== null)
    bootstrap.Modal.getOrCreateInstance(modalEl).show();
    @endif
});
</script>
@endpush
