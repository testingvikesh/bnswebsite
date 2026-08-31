<?php

namespace App\Mail;

use App\Models\AdmissionPayment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $details
     */
    public function __construct(
        public AdmissionPayment $payment,
        public array $details,
        public string $receiptUrl,
    ) {}

    public function envelope(): Envelope
    {
        $registration = (string) ($this->payment->registration_number ?: $this->payment->merchant_txn_no);

        return new Envelope(
            from: new Address(
                (string) config('mail.from.address', 'businessnavacharschool1@gmail.com'),
                'Business Navachar School',
            ),
            subject: 'Payment Successful — Receipt '.$registration.' | Business Navachar School',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-success',
            with: [
                'payment' => $this->payment,
                'details' => $this->details,
                'receiptUrl' => $this->receiptUrl,
                'scholarshipAmount' => (int) config('pay_now.scholarship_amount', 3160),
            ],
        );
    }
}
