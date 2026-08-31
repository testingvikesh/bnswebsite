<?php

namespace App\Services;

use App\Mail\PaymentSuccessMail;
use App\Models\AdmissionPayment;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentSuccessMailer
{
    public function send(AdmissionPayment $payment): void
    {
        $email = trim((string) ($payment->customer_email ?? ''));

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return;
        }

        $formLabel = config("payment.form_type_map.{$payment->form_type}.label", 'Registration');
        $programLabel = $formLabel;

        if ($payment->form_type === 'intro-session') {
            $programs = config('pay_now.programs', []);
            $inquiryProgram = $payment->payable->interested_program ?? null;

            if ($inquiryProgram && isset($programs[$inquiryProgram])) {
                $programLabel = $programs[$inquiryProgram];
            } elseif ($inquiryProgram) {
                $programLabel = $inquiryProgram;
            }
        }

        $details = [
            'Program / Form' => $programLabel,
            'Registration Number' => $payment->registration_number ?: '—',
            'Name' => $payment->customer_name ?: '—',
            'Email' => $payment->customer_email ?: '—',
            'Mobile' => $payment->customer_mobile ?: '—',
            'Merchant Transaction No' => $payment->merchant_txn_no ?: '—',
            'Bank / Gateway Transaction ID' => $payment->txn_id ?: '—',
            'Payment ID' => $payment->payment_id ?: '—',
            'Payment Mode' => $payment->payment_mode ?: '—',
            'Payment Status' => $payment->statusLabel(),
            'Amount Paid' => '₹ '.number_format((float) $payment->amount, 2),
            'Payment Date & Time' => $payment->paid_at?->format('d M Y, h:i A')
                ?: ($payment->payment_datetime ?: now()->format('d M Y, h:i A')),
        ];

        try {
            Mail::to($email)->send(new PaymentSuccessMail(
                payment: $payment,
                details: $details,
                receiptUrl: route('payment.receipt', $payment->merchant_txn_no),
            ));
        } catch (\Throwable $exception) {
            Log::error('Payment success email failed', [
                'email' => $email,
                'merchant_txn_no' => $payment->merchant_txn_no,
                'registration_number' => $payment->registration_number,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
