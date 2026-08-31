@extends('layouts.front')

@section('title', 'Payment Receipt — '.$payment->merchant_txn_no)

@php
    $isIntroSession = ($payment->form_type ?? '') === 'intro-session';
    $programLabel = $programLabel
        ?? $formLabel
        ?? config("payment.form_type_map.{$payment->form_type}.label", 'Registration');
    $details = [
        'Program / Form' => $programLabel,
        'Registration Number' => $payment->registration_number ?: '—',
        'Student / Customer Name' => $payment->customer_name ?: '—',
        'Email' => $payment->customer_email ?: '—',
        'Mobile' => $payment->customer_mobile ?: '—',
        'Merchant Transaction No' => $payment->merchant_txn_no ?: '—',
        'Bank / Gateway Transaction ID' => $payment->txn_id ?: '—',
        'Payment ID' => $payment->payment_id ?: '—',
        'Payment Mode' => $payment->payment_mode ?: '—',
        'Payment Status' => $payment->statusLabel(),
        'Amount Paid' => '₹ '.number_format((float) $payment->amount, 2),
        'Payment Date & Time' => $payment->paid_at?->format('d M Y, h:i A') ?: ($payment->payment_datetime ?: '—'),
        'Response Code' => $payment->response_code ?: '—',
        'Response Description' => $payment->response_description ?: 'Payment successful',
    ];
@endphp

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/payment-success.css') }}" />
@endpush

@section('content')
<div class="bns-payment-receipt-page">
    <div class="bns-payment-receipt-page__toolbar no-print">
        <a href="{{ route('payment.success', $payment->merchant_txn_no) }}" class="bns-payment-success__btn">
            <i class="fas fa-arrow-left"></i> Back to Success Page
        </a>
    </div>

    <article class="bns-payment-receipt" id="bnsPaymentReceipt">
        <div class="bns-payment-receipt__top">
            <p class="bns-payment-receipt__top-brand">Business Navachar School (BNS)</p>
            <h1>Payment Receipt</h1>
            <p>Official confirmation of your successful payment</p>
            <div class="bns-payment-receipt__paid">
                <i class="fas fa-check-circle"></i> PAID
            </div>
        </div>

        <div class="bns-payment-receipt__body">
            <div class="bns-payment-receipt__amount">
                <span>Amount Received</span>
                <strong>₹ {{ number_format((float) $payment->amount, 2) }}</strong>
            </div>

            <table class="bns-payment-receipt__table">
                <tbody>
                    @foreach($details as $label => $value)
                        <tr>
                            <th>{{ $label }}</th>
                            <td>{{ $value }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="bns-payment-receipt__footer">
            <p>This is a computer-generated receipt from Business Navachar School (BNS).</p>
            <p>For support: +91 72086 28671 | WhatsApp: +91 70218 39703</p>
            @if($isIntroSession)
                <p>Santacruz Jain Upashray Permanent Members may upload membership proof after payment to claim the ₹{{ number_format((int) config('pay_now.scholarship_amount', 3160)) }} scholarship refund.</p>
            @endif
        </div>
    </article>
</div>
@endsection
